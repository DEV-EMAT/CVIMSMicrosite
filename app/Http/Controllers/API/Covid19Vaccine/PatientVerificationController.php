<?php

namespace App\Http\Controllers\API\Covid19Vaccine;

use App\Covid19Vaccine\VaccinationMonitoring;
use App\Covid19Vaccine\QualifiedPatient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



use Auth;
use DB;
use Validator;
use Gate;


class PatientVerificationController extends Controller
{
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;


    public function getPatientsList(Request $request){

        if(Auth::user()->account_status == 1){

            try {

                $keyword = $request->search_key;
                $patients_list = VaccinationMonitoring::join('qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                    ->join('pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                    ->join('vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
                    ->join('vaccination_monitoring_surveys as vax_survey', 'vax_survey.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
                    ->select(
                        DB::raw("CONCAT(pre_registrations.first_name,' ',pre_registrations.middle_name,' ',pre_registrations.last_name) AS patient_name"),
                        'pre_registrations.suffix',
                        'pre_registrations.contact_number',
                        'pre_registrations.philhealth_number',
                        'pre_registrations.sex',
                        'pre_registrations.date_of_birth',
                        'pre_registrations.civil_status',
                        'pre_registrations.home_address',
                        'pre_registrations.image',
                        'qualified_patients.qrcode as patient_code',
                        'vaccination_monitorings.dosage as vaccine_dosage',
                        'vaccination_monitorings.vaccination_date as vaccination_date',
                        'vaccine_categories.vaccine_name',
                        'vaccination_monitorings.consent',
                        'vaccination_monitorings.verified_by',
                        'vaccination_monitorings.assessment_status',
                        'vaccination_monitorings.id as verification_id',
                        'vax_survey.question_1 as age_validation',
                        'vax_survey.question_2 as allergic_for_peg',
                        'vax_survey.question_3 as allergic_after_dose',
                        'vax_survey.question_4 as allergic_to_food',
                        'vax_survey.question_5 as asthma_validation',
                        'vax_survey.question_6 as bleeding_disorders',
                        'vax_survey.question_7 as syringe_validation',
                        'vax_survey.question_8 as symptoms_manifest',
                        'vax_survey.question_9 as symptoms_specific',
                        'vax_survey.question_10 as infection_history',
                        'vax_survey.question_11 as previously_treated',
                        'vax_survey.question_12 as received_vaccine',
                        'vax_survey.question_13 as received_convalescent',
                        'vax_survey.question_14 as pregnant',
                        'vax_survey.question_15 as pregnancy_trimester',
                        'vax_survey.question_16 as diagnosed_six_months',
                        'vax_survey.question_17 as specific_diagnosis',
                        'vax_survey.question_18 as medically_cleared'
                    )
                    ->where(DB::raw("CONCAT(pre_registrations.first_name,' ',pre_registrations.middle_name,' ',pre_registrations.last_name)"), 'LIKE', "%$keyword%")
                    ->where('vaccination_monitorings.status', '=', 1)
                    ->paginate(6);

                return response()->json(['status' => $this->successStatus, 'data' => $patients_list, 'message' => 'Patient list retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {

                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }




    }

      public function checkPatientExist(Request $request) {

        if(Auth::user()->account_status == 1){

            if(Gate::allows('permission', 'viewVaccinationMonitoring')) {
                $validator = Validator::make($request->all(), [
                    'patient_code' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], $this->errorStatus);
                }

                try {

                    $patient_code = $request->patient_code;

                    $patient_data = VaccinationMonitoring::join('qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                        ->join('pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                        ->join('vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
                        ->select(
                            DB::raw("CONCAT(pre_registrations.first_name,' ',pre_registrations.middle_name,' ',pre_registrations.last_name) AS patient_name"),
                            DB::raw("GROUP_CONCAT(vaccination_monitorings.id SEPARATOR ', ') as vaccination_monitoring_ids"),
                            'qualified_patients.id as qualified_patient_id'
                        )->where('qualified_patients.qrcode', '=', $patient_code)
                        ->where('vaccination_monitorings.status', '=', 1)
                        // ->where('vaccination_monitorings.dosage', '=', "2")
                        ->whereNull('vaccination_monitorings.verified_by')
                        ->whereNull('vaccination_monitorings.assessment_status')
                        ->first();

                    if(!empty($patient_data)) {
                        return response()->json(['status' => $this->successStatus, 'data' => $patient_data, 'message' => 'Patient record found!'], $this->successStatus);
                    } else {
                        return response()->json(['status' => $this->errorStatus, 'message' => 'Patient record cannot be found!'], $this->errorStatus);
                    }


                } catch (\PDOException $e) {
                    return response()->json(['status' => $this->queryErrorStatus, 'message' => 'Something went wrong! Please try again'], $this->queryErrorStatus);

                }
            } else {
                return response()->json(['status' => $this->errorStatus, 'message' => 'You dont have the permission to access this functionality. Please try to re-login your account.'], $this->errorStatus);
            }


        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Something went wrong! Please try again'], $this->errorStatus);
        }
    }

    public function verifyPatient(Request $request) {
        if(Auth::user()->account_status == 1){

            if(Gate::allows('permission', 'viewSecondDoseVerification')) {

                $validator = Validator::make($request->all(), [
                    'verification_id' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], $this->errorStatus);
                }


                DB::connection('covid19vaccine')->beginTransaction();
                try {

                    $verification_data = VaccinationMonitoring::findOrFail($request->verification_id);

                    $verification_data->verified_by = Auth::user()->person->last_name . ", ". Auth::user()->person->first_name . " " . Auth::user()->person->middle_name;
                    $verification_data->assessment_status = "1";
                    $verification_data->save();

                    DB::connection('covid19vaccine')->commit();

                    return response()->json(['status' => $this->successStatus, 'message' => 'Patient verified successfully'], $this->successStatus);

                } catch (\PDOException $e) {
                    DB::connection('covid19vaccine')->rollBack();
                    return response()->json(['status' => $this->queryErrorStatus, 'message' => 'Something went wrong! Please try again'], $this->queryErrorStatus);
                }
            } else {
                return response()->json(['status' => $this->errorStatus, 'message' => 'You dont have the permission to access this functionality. Please try to re-login your account.'], $this->errorStatus);
            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Something went wrong! Please try again'], $this->errorStatus);
        }
    }

    public function findSummary(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'qualified_patient_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                $id = $request->qualified_patient_id;
                $columns = array(
                    0=> 'dosage',
                    1=> 'vaccination_date',
                );

                $query = VaccinationMonitoring::join('qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                ->join('vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
                ->join('vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
                ->join('vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
                ->select(
                    'vaccination_monitorings.id AS monitoring_id',
                    'vaccination_monitorings.dosage',
                    'vaccination_monitorings.vaccination_date',
                    'vaccination_monitorings.batch_number',
                    'vaccination_monitorings.lot_number',
                    'vaccination_monitorings.encoded_by',
                    'vaccination_monitorings.consent',
                    'vaccination_monitorings.reason_for_refusal',
                    'vaccination_monitorings.deferral',
                    'vaccinators.first_name as vaccinator_first_name',
                    'vaccinators.last_name as vaccinator_last_name',
                    'vaccinators.middle_name as vaccinator_middle_name',
                    'vaccinators.suffix as vaccinator_suffix',
                    'qualified_patients.id',
                    'qualified_patients.registration_id',
                    'qualified_patients.qualification_status',
                    'qualified_patients.status',
                    'pre_registrations.last_name',
                    'pre_registrations.first_name',
                    'pre_registrations.middle_name',
                    'vaccination_monitoring_surveys.question_1',
                    'vaccination_monitoring_surveys.question_2',
                    'vaccination_monitoring_surveys.question_3',
                    'vaccination_monitoring_surveys.question_4',
                    'vaccination_monitoring_surveys.question_5',
                    'vaccination_monitoring_surveys.question_6',
                    'vaccination_monitoring_surveys.question_7',
                    'vaccination_monitoring_surveys.question_8',
                    'vaccination_monitoring_surveys.question_9',
                    'vaccination_monitoring_surveys.question_10',
                    'vaccination_monitoring_surveys.question_11',
                    'vaccination_monitoring_surveys.question_12',
                    'vaccination_monitoring_surveys.question_13',
                    'vaccination_monitoring_surveys.question_14',
                    'vaccination_monitoring_surveys.question_15',
                    'vaccination_monitoring_surveys.question_16',
                    'vaccination_monitoring_surveys.question_17',
                    'vaccination_monitoring_surveys.question_18',
                    'vaccine_categories.vaccine_name'
                )->where('vaccination_monitorings.status', '=', '1')->where('qualified_patients.id', '=', $id);

                // $totalData = with(clone $query)->count();

                // $totalFiltered = $totalData;

                // $limit = $request->input('length');
                // $start = $request->input('start');
                // $order = $columns[$request->input('order.0.column')];
                // $dir = $request->input('order.0.dir');


                // if(empty($request->input('search.value')))
                // {
                    $qualifiedPatient = $query->get();
                // }

                // $buttons = "";
                // $data = array();
                // if(!empty($qualifiedPatient))
                // {
                //     foreach ($qualifiedPatient as $qualifiedPatients)
                //     {

                //         $vaccinator = '';
                //         $vaccinator = $qualifiedPatients->vaccinator_last_name;
                //         if($qualifiedPatients->vaccinator_suffix && $qualifiedPatients->vaccinator_suffix != "NA"){ $vaccinator .= " " . $qualifiedPatients->vaccinator_suffix;}
                //         $vaccinator .= ", " . $qualifiedPatients->vaccinator_first_name . " ";
                //         if($qualifiedPatients->middle_name && $qualifiedPatients->middle_name != "NA"){ $vaccinator .= $qualifiedPatients->vaccinator_middle_name[0] . "."; }

                //         $middleName = "";
                //         if($qualifiedPatients->middle_name != "NA"){$middleName = $qualifiedPatients->middle_name;}
                //         $fullname = $qualifiedPatients->last_name . " ". $qualifiedPatients->affiliation . ", ". $qualifiedPatients->first_name . " ". $middleName;

                //         $nestedData['dosage'] = $qualifiedPatients['dosage'];
                //         $nestedData['vaccination_date'] = $qualifiedPatients['vaccination_date'];
                //         $nestedData['vaccine_name'] = $qualifiedPatients['vaccine_name'];
                //         $nestedData['batch_number'] = $qualifiedPatients['batch_number'];
                //         $nestedData['lot_number'] = $qualifiedPatients['lot_number'];
                //         $nestedData['encoded_by'] = $qualifiedPatients['encoded_by'];
                //         $nestedData['consent'] = $qualifiedPatients['consent'];
                //         $nestedData['reason_for_refusal'] = $qualifiedPatients['reason_for_refusal'];
                //         $nestedData['deferral'] = $qualifiedPatients['deferral'];
                //         $nestedData['vaccinator'] = $vaccinator;
                //         $nestedData['fullname'] = $fullname;
                //         $nestedData['data'] = $qualifiedPatients;
                //         // $nestedData['otherInformation'] = '<a href="#" data-toggle="tooltip" title="Click to view other information." onclick="viewOtherInformation('. $qualifiedPatients['monitoring_id'] .')"  class="btn btn-xs btn-info btn-fill btn-rotate remove"><i class="fa fa-list-alt" aria-hidden="true"></i> Other Informations</a>';
                //         // $nestedData['actions'] = $buttons;

                //         $data[] = $nestedData;
                //     }
                // }
                // $json_data = array(
                //     "draw"            => intval($request->input('draw')),
                //     "recordsTotal"    => intval($totalData),
                //     "recordsFiltered" => intval($totalFiltered),
                //     "data"            => $data
                //     );
                    return response()->json(['status' => $this->successStatus, 'data' => $qualifiedPatient, 'message' => 'success'], $this->successStatus);
                // echo json_encode($json_data);
            } catch (\PDOException $e) {

                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }
    }

}
