<?php

namespace App\Http\Controllers\API\Covid19Vaccine;


use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\QualifiedPatient;
use App\Covid19Vaccine\VaccineCategory;
use App\Covid19Vaccine\Vaccinator;
use App\Covid19Vaccine\VaccinationMonitoring;
use App\Covid19Vaccine\VaccinationMonitoringSurvey;
use App\Http\Resources\QualifiedpatientResource;
use App\Http\Resources\PreRegResource;
use App\Covid19Vaccine\Survey;
use App\Covid19Vaccine\Barangay;
use App\Covid19Vaccine\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



use Auth;
use DB;
use Validator;
use Carbon\Carbon;
use Gate;

class PatientEncodingController extends Controller
{
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function storePreRegistered(Request $request)
    {
        // $answer = [];
        // if($request['question2'] == "YES"){
        //     $answer['question3'] = 'required';
        // }if($request['question4'] == "YES"){
        //     $answer['question5'] = 'required';
        // }

        $validate = Validator::make($request->all(), [
            'last_name' => 'required',
            'first_name' => 'required',
            'date_of_birth' => 'required',
            'sex' => 'required',
            'civil_status' => 'required',
            'contact_number' => 'required',
            'barangay_obj' => 'required',
            'specific_profession' => 'required',
            'categories' => 'required',
            'id_categories' => 'required',
            'home_address' => 'required',

        ]);

        if($validate->fails()){
            return response()->json(array('success' => false, 'messages' => 'May be missing required fields, Please check your input.', 'title'=> 'Oops! something went wrong.'));
        }else{
            $data = [
                'lastname' => convertData($request->last_name),
                'firstname' => convertData($request->first_name),
                'middlename' => convertData($request->middle_name),
                'dob' => convertData($request->date_of_birth),
                'suffix' => convertData($request->suffix),
            ];

            if(empty($this->validateUser($data))){

                DB::connection('covid19vaccine')->beginTransaction();
                try{

                    /* barangay */
                    $barangay = Barangay::findOrFail($request->barangay_obj['id']);

                    /* employer */
                    $employer = new Employer;
                    $employer->employment_status_id = convertData($request->employee_status['id']);
                    $employer->profession_id = convertData($request->profession['id']);
                    $employer->specific_profession = convertData($request->specific_profession);
                    $employer->employer_name = convertData($request->employer_name);
                    $employer->employer_contact = convertData($request->employer_contact);
                    $employer->employer_barangay_name = convertData($request->employer_address);
                    $employer->status = '1';
                    $employer->save();

                    $register = new PreRegistration();
                    $register->last_name = convertData($request->last_name);
                    $register->first_name = convertData($request->first_name);
                    $register->middle_name = (convertData($request->middle_name) == 'N/A')? 'NA' : convertData($request->middle_name);
                    $register->suffix = convertData($request->suffix);
                    $register->date_of_birth = convertData($request->date_of_birth);
                    $register->sex = convertData($request->sex);
                    $register->contact_number = "09" . convertData($request->contact_number);
                    $register->civil_status = convertData($request->civil_status);
                    $register->province = 'LAGUNA';
                    $register->city = 'CABUYAO';
                    $register->barangay = $barangay->barangay;
                    $register->barangay_id = convertData($request->barangay_obj['id']);
                    $register->home_address = convertData($request->home_address);
                    $register->employment_id = $employer->id;
                    $register->category_id = convertData($request->categories['id']);
                    $register->category_id_number = convertData($request->category_id_number);
                    $register->category_for_id = convertData($request->id_categories['id']);
                    $register->philhealth_number = convertData($request->philhealth_number);
                    $register->status = '1';
                    $register->save();

                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;
                    $register->registration_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $register->id, 12, '0', STR_PAD_LEFT);
                    $register->save();

                    $register->image = 'covid19_vaccine_preregistration/default-avatar.png';
                    $register->save();

                    /* survey */
                    $survey = new Survey;
                    $survey->registration_id = $register->id;
                    $survey->question_1 = 'NO';
                    $survey->question_2 = ($request['withAlergy']) ? 'YES' : 'NO';
                    $survey->question_3 = ($request['withAlergy']) ? $request['withAlergyAnswer'] : null;
                    $survey->question_4 = ($request['withComorbidities']) ? 'YES' : 'NO';
                    $survey->question_5 = ($request['withComorbidities']) ? $request['withComorbiditiesAnswer'] : null;
                    $survey->question_6 = 'NO';
                    $survey->question_7 = null;
                    $survey->question_8 = null;
                    $survey->question_9 = 'YES';
                    $survey->question_10 = 'NO';
                    $survey->status = '1';
                    $survey->save();

                    $registeredPerson = PreRegistration::where('id', '=', $register->id)->where('status', '=', '1')->first();

                    $fullname = $registeredPerson->last_name;

                    if($registeredPerson->affiliation){
                        $fullname .= " " . $registeredPerson->affiliation;
                    }
                    $fullname .= ", " . $registeredPerson->first_name . " ";

                    if($registeredPerson->middle_name){
                        $fullname .= $registeredPerson->middle_name[0] . ".";
                    }

                    DB::connection('covid19vaccine')->commit();

                    return response()->json(array('success' => true, 'messages' => 'Thank you!', 'fullname' => $fullname, 'date_registered' => $registeredPerson->created_at->format('m-d-Y H:i:s'), 'registration_code' => $registeredPerson->registration_code));

                }catch(\PDOException $e){
                    DB::connection('covid19vaccine')->rollBack();
                    return response()->json(array('success' => false, 'messages' => 'Transaction Failed!','title' => 'Oops! something went wrong.'));
                }
            }else{
                return response()->json(array('success' => false, 'messages' => 'Please check your lastname, firstname, middlename and birthday!.','title' => 'Your name is already exist to our record!'));
            }

        }


    }

    public function updatePreRegistered(Request $request)
    {
         $validate = Validator::make($request->all(), [
            'last_name' => 'required',
            'first_name' => 'required',
            'date_of_birth' => 'required',
            'sex' => 'required',
            'civil_status' => 'required',
            'contact_number' => 'required',
            'barangay' => 'required',
            'profession' => 'required',
            'categories' => 'required',
            'id_categories' => 'required',
            'home_address' => 'required',
        ]);

        if($validate->fails()){
            return response()->json(array('success' => false, 'messages' => 'May be missing required fields, Please check your input.', 'title'=> 'Oops! something went wrong.'));
        }else{

            DB::connection('covid19vaccine')->beginTransaction();
            try{

                /* barangay */
                $barangay = Barangay::findOrFail($request->barangay_obj["id"]);
                $register = PreRegistration::findOrFail($request->id);

                $register->last_name = convertData($request->last_name);
                $register->first_name = convertData($request->first_name);
                $register->middle_name = convertData($request->middle_name);
                $register->suffix = convertData($request->suffix);
                $register->date_of_birth = convertData($request->date_of_birth);
                $register->sex = convertData($request->sex);
                $register->contact_number = convertData($request->contact_number);
                $register->civil_status = convertData($request->civil_status);
                $register->province = 'LAGUNA';
                $register->city = 'CABUYAO';
                $register->barangay = $barangay->barangay;
                $register->barangay_id = convertData($request->barangay_obj['id']);
                $register->home_address = convertData($request->home_address);
                $register->category_id = convertData($request->categories['id']);
                $register->category_id_number = convertData($request->category_id_number);
                $register->philhealth_number = convertData($request->philhealth_number);
                $register->category_for_id = convertData($request->id_categories['id']);
                $changes = $register->getDirty();
                $register->save();

                /* employer */

                $employer = Employer::findOrFail($register->employment_id);
                $employer->employment_status_id = convertData($request->employee_status['id']);
                $employer->profession_id = convertData($request->profession['id']);
                $employer->specific_profession = convertData($request->specific_profession);
                $employer->employer_name = convertData($request->employer_name);
                $employer->employer_contact = convertData($request->employer_contact);
                $employer->employer_barangay_name = convertData($request->employer_address);
                $changes = array_merge($changes, $employer->getDirty());
                $employer->save();

                // $register->save();

                $survey = Survey::findOrFail($request->surveys['id']);
                $survey->question_2 = ($request['withAlergy']) ? 'YES' : 'NO';
                $survey->question_3 = ($request['withAlergy']) ? $request['withAlergyAnswer'] : null;
                $survey->question_4 = ($request['withComorbidities']) ? 'YES' : 'NO';
                $survey->question_5 = ($request['withComorbidities']) ? $request['withComorbiditiesAnswer'] : null;
                $survey->save();

                DB::connection('covid19vaccine')->commit();

                /* logs */
                action_log('Pre-registration mngt', 'UPDATE', array_merge(['id' => $register->id], $changes));

                return response()->json(array('success' => true, 'messages' => 'Record successfully updated'));

            }catch(\PDOException $e){
                DB::connection('covid19vaccine')->rollBack();
                return response()->json(array('success' => false, 'messages' => 'Transaction Failed!','title' => 'Oops! something went wrong.'));
            }
        }
    }

    public function validateUser($data){
        // ->where('middle_name', '=', $data['middlename'])
        // ->where('suffix', '=', $data['suffix'])

        return PreRegistration::where('last_name', '=', $data['lastname'])
            ->where('first_name', '=', $data['firstname'])
            ->where('date_of_birth', '=', $data['dob'])
            ->first();
    }


    public function getUnverifiedPatients(Request $request) {
        if(Auth::user()->account_status == 1){

            try {

                $keyword = $request->search_key;
                $unverified_patients = PreRegistration::with(['categories'])
                ->with(['id_categories'])
                ->with(['surveys'])
                ->with(['employers'])
                // ->select(
                //     'barangay',
                //     'barangay_id',
                //     'category_for_id',
                //     'category_id',
                //     'category_id_number',
                //     'city',
                //     'civil_status',
                //     'contact_number',
                //     'created_at',
                //     'date_of_birth',
                //     'employment_id',
                //     'first_name',
                //     'home_address',
                //     'id',
                //     'image',
                //     'last_name',
                //     'middle_name',
                //     'philhealth_number',
                //     'province',
                //     'registration_code',
                //     'sex',
                //     'status',
                //     'suffix',
                // )
                ->whereRaw("concat(pre_registrations.first_name, ' ', pre_registrations.last_name) like '%{$keyword}%' ")
                ->where('pre_registrations.status', '<>', 2)
                ->paginate($request->items_per_page);

                return PreRegResource::Collection($unverified_patients);

                //return response()->json(['status' => $this->successStatus, 'data' => $unverified_patients, 'message' => 'Patient list retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {

                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }
    }

    public function validatePatient(Request $request) {


        if(Auth::user()->account_status == 1){


            if(Gate::allows('permission', 'viewRegistrationAndValidation')) {

                $validator = Validator::make($request->all(), [
                    'pre_registration_id' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors(), 'message' => 'error'], $this->errorStatus);
                }

                // dd($request->pre_registration_id);



                DB::beginTransaction();


                try {

                    $registration_data = VaccinationMonitoring::find($request->pre_registration_id);

                    $qualifiedPatient = new QualifiedPatient;
                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;

                    $qualifiedPatient->registration_id = $request->pre_registration_id;
                    $qualifiedPatient->qrcode = 'V' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $request->pre_registration_id, 16, '0', STR_PAD_LEFT);
                    $qualifiedPatient->qualification_status = "APPROVED";
                    $qualifiedPatient->verified_by = Auth::user()->person->last_name . ", ". Auth::user()->person->first_name . " " . Auth::user()->person->middle_name;
                    $qualifiedPatient->assessment_status = 1;
                    $qualifiedPatient->status = 1;
                    $changes = $qualifiedPatient->getDirty();
                    $qualifiedPatient->save();

                    $preRegistration = PreRegistration::findOrFail($request->pre_registration_id);
                    $preRegistration->status = '0';
                    $preRegistration->save();

                    DB::commit();

                    action_log('Registration Approval', 'CREATE', array_merge(['id' => $qualifiedPatient->id], $changes));

                    return response()->json(['status' => $this->successStatus, 'message' => 'Patient validated successfully.'], $this->successStatus);

                } catch (\PDOException $e) {

                    DB::rollBack();
                    return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

                }

            } else {
                return response()->json(['status' => $this->errorStatus, 'message' => 'You dont have the permission to access this functionality, coordinate with ECabs Administrator regarding with you issue. Please try to re-login your account.'], $this->errorStatus);
            }


        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }



    }

    public function getQualifiedPatients(Request $request) {
        if(Auth::user()->account_status == 1){

            try {

                $keyword = $request->search_key;

                if(empty($keyword)){
                    $qualified_patients = QualifiedPatient::with(['vaccination_monitoring'])
                        ->with(['pre_registration' => function($query) use ($request){
                        $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', "%$request->search_key%");
                        // $query->where("concat(first_name, ' ', last_name) like '%{$request->search_key}%' ");
                    }])
                    // ->select('*', 'qualified_patients.id AS qualified_patient_id')
                    ->leftJoin('surveys as surveys', 'qualified_patients.registration_id', '=', 'surveys.registration_id')
                    ->select(
                        'assessment_status',
                        'deleted_at',
                        'qualified_patients.id',
                        'qrcode',
                        'qualification_status',
                        'question_1',
                        'question_2',
                        'question_3',
                        'question_4',
                        'question_5',
                        'question_6',
                        'question_7',
                        'question_8',
                        'question_9',
                        'question_10',
                        'qualified_patients.registration_id',
                        'qualified_patients.status'
                    )
                    // ->whereHas('vaccination_monitoring', function($query){
                    //     $query->where('status', '=', '1');
                    // })
                    ->where('qualified_patients.qualification_status', '=', 'APPROVED')
                    ->where('qualified_patients.assessment_status', '=', '1')
                    ->where('qualified_patients.status', '=', '1')
                    ->paginate($request->items_per_page);
                }else{
                    $qualified_patients = QualifiedPatient::with(['vaccination_monitoring'])
                    ->with(['pre_registration'])
                    ->leftJoin('surveys as surveys', 'qualified_patients.registration_id', '=', 'surveys.registration_id')
                    ->select(
                        'assessment_status',
                        'deleted_at',
                        'qualified_patients.id',
                        'qrcode',
                        'qualification_status',
                        'question_1',
                        'question_2',
                        'question_3',
                        'question_4',
                        'question_5',
                        'question_6',
                        'question_7',
                        'question_8',
                        'question_9',
                        'question_10',
                        'qualified_patients.registration_id',
                        'qualified_patients.status',
                    )
                    //->searchData($request->search_key)
                    ->whereHas('pre_registration', function($query) use ($request){
                        // $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', "%$request->search_key%");
                        $query->whereRaw("concat(first_name, ' ',last_name) like '%{$request->search_key}%' ");
                    })
                    // ->whereIn('qualified_patients.registration_id', function($query) use ($request){
                    //     $query->from('pre_registrations')
                    //     ->select('*')
                    //     ->whereRaw("concat(pre_registrations.first_name, ' ',pre_registrations.last_name) like '%{$request->search_key}%' ");
                    // })
                    // ->whereHas('vaccination_monitoring', function($query){
                    //     $query->where('status', '=', '1');
                    // })
                    ->where('qualified_patients.qualification_status', '=', 'APPROVED')
                    ->where('qualified_patients.assessment_status', '=', '1')
                    ->where('qualified_patients.status', '=', '1')
                    ->paginate($request->items_per_page);
                }

                     return QualifiedPatientResource::Collection($qualified_patients);

                //return response()->json(['status' => $this->successStatus, 'data' => $qualified_patients, 'message' => 'Qualified Patient list retrieved successfully.'], $this->successStatus);


            } catch (\PDOException $e) {
                return response()->json(['status' => $this->errorStatus, 'message' => $e], $this->errorStatus);
            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }
    }



    // eto onio
    // |
    // |
    // v
    public function monitorQualifiedPatient(Request $request) {

        if(Auth::user()->account_status == 1){

            if(Gate::allows('permission', 'viewVaccinationMonitoring')) {
                $validator = Validator::make($request->all(), [
                    'dose'=> 'required',
                    'vaccination_date'=> 'required',
                    'vaccine_categories'=> 'required',
                    'batch_number' => 'required',
                    'lot_number'=> 'required',
                    'vaccinators'=> 'required',
                    'consent'=> 'required',
                ]);


                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], $this->errorStatus);
                }


                DB::connection('covid19vaccine')->beginTransaction();

                $isMonitorCompleted = VaccinationMonitoring::where('qualified_patient_id', '=', $request["qualified_patient_id"])
                                    ->where('dosage', '=', $request["dose"])
                                    ->where('status', '=', '1')
                                    ->first();

                if($isMonitorCompleted){
                    $dosage = "1st";
                    if($request["dose"] == "2"){
                        $dosage = "2nd";
                    }
                    return response()->json(['status' => $this->errorStatus, 'message'=> $dosage . ' dose already completed!'], $this->successStatus);

                }

                try {


                    $vaccinationMonitoring = new VaccinationMonitoring;
                    $vaccinationMonitoring->qualified_patient_id = $request["qualified_patient_id"];
                    $vaccinationMonitoring->dosage = $request["dose"];
                    $vaccinationMonitoring->vaccination_date = $request["vaccination_date"];
                    $vaccinationMonitoring->vaccine_category_id = $request['vaccine_categories']['id'];
                    $vaccinationMonitoring->batch_number = convertData($request['batch_number']);
                    $vaccinationMonitoring->lot_number = convertData($request['lot_number']);
                    $vaccinationMonitoring->vaccinator_id = $request['vaccinators']['id'];
                    $vaccinationMonitoring->consent = convertData($request['consent']);
                    $vaccinationMonitoring->reason_for_refusal = convertData($request['reason_for_refusal']);
                    $vaccinationMonitoring->deferral = convertData($request['deferral']);
                    $vaccinationMonitoring->encoded_by = Auth::user()->person->last_name . ", ". Auth::user()->person->first_name . " " . Auth::user()->person->middle_name;
                    $vaccinationMonitoring->status = 1;
                    $changes = $vaccinationMonitoring->getDirty();

                    $vaccinationMonitoring->save();
                    $monitoringSurvey = new VaccinationMonitoringSurvey;
                    $monitoringSurvey->vaccination_monitoring_id = $vaccinationMonitoring->id;
                    $monitoringSurvey->question_1 = $request['question1'];
                    $monitoringSurvey->question_2 = $request['question2'];
                    $monitoringSurvey->question_3 = $request['question3'];
                    $monitoringSurvey->question_4 = $request['question4'];
                    $monitoringSurvey->question_5 = $request['question5'];
                    $monitoringSurvey->question_6 = $request['question6'];
                    $monitoringSurvey->question_7 = $request['question7'];
                    $monitoringSurvey->question_8 = $request['question8'];
                    $monitoringSurvey->question_9 = $request['question8Arr'];
                    $monitoringSurvey->question_10 = $request['question10'];
                    $monitoringSurvey->question_11 = $request['question11'];
                    $monitoringSurvey->question_12 = $request['question12'];
                    $monitoringSurvey->question_13 = $request['question13'];
                    $monitoringSurvey->question_14 = $request['question14'];
                    $monitoringSurvey->question_15 = $request['question15'];
                    $monitoringSurvey->question_16 = $request['question16'];
                    $monitoringSurvey->question_17 = $request['question17Arr'];
                    $monitoringSurvey->question_18 = $request['question18'];
                    $monitoringSurvey->question_19 = $request['question19'];
                    $monitoringSurvey->status = 1;
                    $monitoringSurvey->save();

                    DB::connection('covid19vaccine')->commit();

                    /* logs */
                    action_log('Vaccination Monitoring', 'CREATE', array_merge(['id' => $vaccinationMonitoring->id], $changes));

                    return response()->json(['status' => $this->successStatus, 'message' => 'Qualified Patient monitored successfully.'], $this->successStatus);

                    // return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
                } catch (\PDOException $e) {

                    DB::connection('covid19vaccine')->rollBack();
                    return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->successStatus);
                    // return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
                }
            } else {
                return response()->json(['status' => $this->errorStatus, 'message' => 'You dont have the permission to access this functionality, coordinate with ECabs Administrator regarding with you issue. Please try to re-login your account.'], $this->errorStatus);
            }


        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }


    }

    public function getVaccineCategories() {
        if(Auth::user()->account_status == 1){

            $vaccine_categories = VaccineCategory::where('status', '=', 1)->get();

            return response()->json(['status' => $this->successStatus, 'data' => $vaccine_categories, 'message' => 'Qualified Patient list retrieved successfully.'], $this->successStatus);


            try {
            } catch (\PDOException $e) {
                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);
            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }

    }

    public function getVaccinators() {
        if(Auth::user()->account_status == 1){

            $vaccinators = Vaccinator::where('status', 1)->orderBy('last_name')->get();

            return response()->json(['status' => $this->successStatus, 'data' => $vaccinators, 'message' => 'Vaccinators list retrieved successfully.'], $this->successStatus);


            try {
            } catch (\PDOException $e) {
                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);
            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }

    }


}
