<?php

namespace App\Http\Controllers\API\Covid19Vaccine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\QualifiedPatient;
use App\Covid19Vaccine\VaccinationMonitoring;
use App\Covid19Vaccine\HealthFacility;

use DB;
use Auth;
use Validator;


class StatisticsController extends Controller
{
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getStatistics() {

        if(Auth::user()->account_status == 1){

            try {

                $data = new \stdClass;




                $data->registrationCount = PreRegistration::count();
                $data->evaluatedCounter = QualifiedPatient::count();
                $data->vaccinatedCounter = VaccinationMonitoring::distinct('qualified_patient_id')->count();
                $data->healthFacility = HealthFacility::count();


                $data->evaluatedPercent = ceil(($data->evaluatedCounter / $data->registrationCount) * 100);
                $data->vaccinatedPercent = ceil(($data->vaccinatedCounter / $data->evaluatedCounter) * 100);



                return response()->json(['status' => $this->successStatus, 'data' => $data, 'message' => 'Statistics retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {

                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }

    }

    public function getBarangayStatistics() {

        if(Auth::user()->account_status == 1){

            try {

                $data = new \stdClass;

                $query = VaccinationMonitoring::join('qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                    ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                    ->join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
                    ->select(
                        'pre_registrations.id',
                        'pre_registrations.last_name',
                        'pre_registrations.first_name',
                        'pre_registrations.middle_name',
                        'pre_registrations.date_of_birth',
                        'pre_registrations.image',
                        'pre_registrations.contact_number',
                        'pre_registrations.philhealth_number',
                        'pre_registrations.civil_status',
                        'pre_registrations.sex',
                        'pre_registrations.home_address',
                        'categories.category_name',
                        'pre_registrations.category_id_number',
                        'barangays.barangay'
                    );

                $BACLARAN = with(clone $query)->where('barangays.id', '=', 1)->orWhere('barangays.id', '=', 2)->distinct('qualified_patient_id')->count();
                $BANAYBANAY =  with(clone $query)->where('barangays.id', '=', 3)->orWhere('barangays.id', '=', 4)->distinct('qualified_patient_id')->count();
                $BANLIC =  with(clone $query)->where('barangays.id', '=', 5)->distinct('qualified_patient_id')->count();
                $BUTONG =  with(clone $query)->where('barangays.id', '=', 6)->distinct('qualified_patient_id')->count();
                $BIGAA =  with(clone $query)->where('barangays.id', '=', 7)->distinct('qualified_patient_id')->count();
                $CASILE =   with(clone $query)->where('barangays.id', '=', 8)->distinct('qualified_patient_id')->count();
                $GULOD =  with(clone $query)->where('barangays.id', '=', 9)->distinct('qualified_patient_id')->count();
                $MAMATID =  with(clone $query)->where('barangays.id', '=', 10)->orWhere('barangays.id', '=', 11)->distinct('qualified_patient_id')->count();
                $MARINIG =  with(clone $query)->where('barangays.id', '=', 12)->orWhere('barangays.id', '=', 13)->orWhere('barangays.id', '=', 14)->orWhere('barangays.id', '=', 15)->distinct('qualified_patient_id')->count();
                $NIUGAN =  with(clone $query)->where('barangays.id', '=', 16)->distinct('qualified_patient_id')->count();
                $PITTLAND =  with(clone $query)->where('barangays.id', '=', 17)->distinct('qualified_patient_id')->count();
                $PULO =  with(clone $query)->where('barangays.id', '=', 18)->distinct('qualified_patient_id')->count();
                $SALA =  with(clone $query)->where('barangays.id', '=', 19)->distinct('qualified_patient_id')->count();
                $SAN_ISIDRO =  with(clone $query)->where('barangays.id', '=', 20)->distinct('qualified_patient_id')->count();
                $DIEZMO =  with(clone $query)->where('barangays.id', '=', 21)->distinct('qualified_patient_id')->count();
                $BARANGAY_UNO =  with(clone $query)->where('barangays.id', '=', 22)->distinct('qualified_patient_id')->count();
                $BARANGAY_DOS =  with(clone $query)->where('barangays.id', '=', 23)->distinct('qualified_patient_id')->count();
                $BARANGAY_TRES =  with(clone $query)->where('barangays.id', '=', 24)->distinct('qualified_patient_id')->count();

                $data->barangays =
                    array(array("barangay" => "BACLARAN", "vaccinated" =>  $BACLARAN),
                    array("barangay" => "BANAYBANAY", "vaccinated" =>  $BANAYBANAY),
                    array("barangay" => "BANLIC", "vaccinated" =>  $BANLIC),
                    array("barangay" => "BUTONG", "vaccinated" =>  $BUTONG),
                    array("barangay" => "BIGAA", "vaccinated" =>  $BIGAA),
                    array("barangay" => "CASILE", "vaccinated" =>  $CASILE),
                    array("barangay" => "GULOD", "vaccinated" =>  $GULOD),
                    array("barangay" => "MAMATID", "vaccinated" =>  $MAMATID),
                    array("barangay" => "MARINIG", "vaccinated" =>  $MARINIG),
                    array("barangay" => "NIUGAN", "vaccinated" =>  $NIUGAN),
                    array("barangay" => "PITTLAND", "vaccinated" =>  $PITTLAND),
                    array("barangay" => "PULO", "vaccinated" =>  $PULO),
                    array("barangay" => "SALA", "vaccinated" =>  $SALA),
                    array("barangay" => "SAN_ISIDRO", "vaccinated" =>  $SAN_ISIDRO),
                    array("barangay" => "DIEZMO", "vaccinated" =>  $DIEZMO),
                    array("barangay" => "BARANGAY_UNO", "vaccinated" =>  $BARANGAY_UNO),
                    array("barangay" => "BARANGAY_DOS", "vaccinated" =>  $BARANGAY_DOS),
                    array("barangay" => "BARANGAY_TRES", "vaccinated" =>  $BARANGAY_TRES),
                );








                return response()->json(['status' => $this->successStatus, 'data' => $data, 'message' => 'Barangay Statistics retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {

                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }

    }

    public function getPreregisteredStatistics() {

        if(Auth::user()->account_status == 1){

            try {

                $data = new \stdClass;

                $query = PreRegistration::where('status','=', '1');

                $prereg = new \stdClass;
                $prereg->health_care_workers = with(clone $query)->where('category_id', '=', '1')->count();
                $prereg->senior_citizen = with(clone $query)->where('category_id', '=', '2')->count();
                $prereg->indigent = with(clone $query)->where('category_id', '=', '3')->count();
                $prereg->uniformed_personnel = with(clone $query)->where('category_id', '=', '4')->count();
                $prereg->essential_worker = with(clone $query)->where('category_id', '=', '5')->count();
                $prereg->others = with(clone $query)->where('category_id', '=', '6')->count();
                $prereg->comorbidities = with(clone $query)->where('category_id', '=', '7')->count();
                $prereg->teachers_social_workers = with(clone $query)->where('category_id', '=', '8')->count();
                $prereg->other_govt_workers = with(clone $query)->where('category_id', '=', '9')->count();
                $prereg->other_high_risk = with(clone $query)->where('category_id', '=', '10')->count();
                $prereg->ofw = with(clone $query)->where('category_id', '=', '11')->count();
                $prereg->remaining_work_force = with(clone $query)->where('category_id', '=', '12')->count();

                $data->preregistration = array(array("category" => "Health care workers", "count" =>  $prereg->health_care_workers),
                    array("category" => "Senior citizen", "count" =>  $prereg->senior_citizen),
                    array("category" => "Indigent", "count" =>  $prereg->indigent),
                    array("category" => "Uniformed personnel", "count" =>  $prereg->uniformed_personnel),
                    array("category" => "Essential worker", "count" =>  $prereg->essential_worker),
                    array("category" => "Others", "count" =>  $prereg->others),
                    array("category" => "Comorbidities", "count" =>  $prereg->comorbidities),
                    array("category" => "Teachers social workers", "count" =>  $prereg->teachers_social_workers),
                    array("category" => "Other govt workers", "count" =>  $prereg->other_govt_workers),
                    array("category" => "Other high risk", "count" =>  $prereg->other_high_risk),
                    array("category" => "Ofw", "count" =>  $prereg->ofw),
                    array("category" => "Remaining work force", "count" =>  $prereg->remaining_work_force),
                );

                return response()->json(['status' => $this->successStatus, 'data' => $data, 'message' => 'Pre-registration Statistics retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {

                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }



    }

    public function getDoseStatistics() {

        if(Auth::user()->account_status == 1){

            try {

                $data = new \stdClass;

                $query = PreRegistration::join(connectionName('covid19vaccine'). '.qualified_patients', 'qualified_patients.registration_id', '=', 'pre_registrations.id')
                    ->join(connectionName('covid19vaccine'). '.vaccination_monitorings', 'vaccination_monitorings.qualified_patient_id', '=', 'qualified_patients.id')
                    ->where('pre_registrations.status', '=', '0')->where('vaccination_monitorings.status', '=', '1');

                $first_dose_data = new \stdClass;
                $first_dose_data->health_care_workers_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '1')->distinct('qualified_patient_id')->count();
                $first_dose_data->senior_citizen_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '2')->distinct('qualified_patient_id')->count();
                $first_dose_data->indigent_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '3')->distinct('qualified_patient_id')->count();
                $first_dose_data->uniformed_personnel_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '4')->distinct('qualified_patient_id')->count();
                $first_dose_data->essential_worker_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '5')->distinct('qualified_patient_id')->count();
                $first_dose_data->others_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '6')->distinct('qualified_patient_id')->count();
                $first_dose_data->comorbidities_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '7')->distinct('qualified_patient_id')->count();
                $first_dose_data->teachers_social_workers_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '8')->distinct('qualified_patient_id')->count();
                $first_dose_data->other_govt_workers_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '9')->distinct('qualified_patient_id')->count();
                $first_dose_data->other_high_risk_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '10')->distinct('qualified_patient_id')->count();
                $first_dose_data->ofw_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '11')->distinct('qualified_patient_id')->count();
                $first_dose_data->remaining_work_force_first_dose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', '12')->distinct('qualified_patient_id')->count();

                $second_dose_data = new \stdClass;
                $second_dose_data->health_care_workers_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '1')->distinct('qualified_patient_id')->count();
                $second_dose_data->senior_citizen_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '2')->distinct('qualified_patient_id')->count();
                $second_dose_data->indigent_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '3')->distinct('qualified_patient_id')->count();
                $second_dose_data->uniformed_personnel_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '4')->distinct('qualified_patient_id')->count();
                $second_dose_data->essential_worker_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '5')->distinct('qualified_patient_id')->count();
                $second_dose_data->others_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '6')->distinct('qualified_patient_id')->count();
                $second_dose_data->comorbidities_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '7')->distinct('qualified_patient_id')->count();
                $second_dose_data->teachers_social_workers_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '8')->distinct('qualified_patient_id')->count();
                $second_dose_data->other_govt_workers_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '9')->distinct('qualified_patient_id')->count();
                $second_dose_data->other_high_risk_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '10')->distinct('qualified_patient_id')->count();
                $second_dose_data->ofw_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '11')->distinct('qualified_patient_id')->count();
                $second_dose_data->remaining_work_force_second_dose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', '12')->distinct('qualified_patient_id')->count();




                $data->first_dose_data = array(array("category" => "Health care workers", "count" =>  $first_dose_data->health_care_workers_first_dose),
                    array("category" => "Senior citizen", "count" =>  $first_dose_data->senior_citizen_first_dose),
                    array("category" => "Indigent", "count" =>  $first_dose_data->indigent_first_dose),
                    array("category" => "Uniformed personnel", "count" =>  $first_dose_data->uniformed_personnel_first_dose),
                    array("category" => "Essential worker", "count" =>  $first_dose_data->essential_worker_first_dose),
                    array("category" => "Others", "count" =>  $first_dose_data->others_first_dose),
                    array("category" => "Comorbidities", "count" =>  $first_dose_data->comorbidities_first_dose),
                    array("category" => "Teachers social workers", "count" =>  $first_dose_data->teachers_social_workers_first_dose),
                    array("category" => "Other govt workers", "count" =>  $first_dose_data->other_govt_workers_first_dose),
                    array("category" => "Other high risk", "count" =>  $first_dose_data->other_high_risk_first_dose),
                    array("category" => "Ofw", "count" =>  $first_dose_data->ofw_first_dose),
                    array("category" => "Remaining work force", "count" =>  $first_dose_data->remaining_work_force_first_dose),
                );
                $data->second_dose_data =
                    array(array("category" => "Health care workers", "count" =>  $second_dose_data->health_care_workers_second_dose),
                    array("category" => "Senior citizen", "count" =>  $second_dose_data->senior_citizen_second_dose),
                    array("category" => "Indigent", "count" =>  $second_dose_data->indigent_second_dose),
                    array("category" => "Uniformed personnel", "count" =>  $second_dose_data->uniformed_personnel_second_dose),
                    array("category" => "Essential worker", "count" =>  $second_dose_data->essential_worker_second_dose),
                    array("category" => "Others", "count" =>  $second_dose_data->others_second_dose),
                    array("category" => "Comorbidities", "count" =>  $second_dose_data->comorbidities_second_dose),
                    array("category" => "Teachers social workers", "count" =>  $second_dose_data->teachers_social_workers_second_dose),
                    array("category" => "Other govt workers", "count" =>  $second_dose_data->other_govt_workers_second_dose),
                    array("category" => "Other high risk", "count" =>  $second_dose_data->other_high_risk_second_dose),
                    array("category" => "Ofw", "count" =>  $second_dose_data->ofw_second_dose),
                    array("category" => "Remaining work force", "count" =>  $second_dose_data->remaining_work_force_second_dose),
                );





                return response()->json(['status' => $this->successStatus, 'data' => $data, 'message' => 'Pre-registration Statistics retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {

                return response()->json(['status' => $this->errorStatus, 'message' => 'There is an error encountered. Please try again.'], $this->errorStatus);

            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Server error.'], $this->errorStatus);
        }



    }

    // public function getStatistics(){
    //     $preregisteredCounter = PreRegistration::count();
    //     $evaluatedCounter = QualifiedPatient::count();
    //     $vaccinatedCounter = VaccinationMonitoring::distinct('qualified_patient_id')->count();
    //     $healthFacility = HealthFacility::count();
    //     $data = ['evaluatedCounter'=> $evaluatedCounter, 'preregisteredCounter'=> $preregisteredCounter, 'vaccinatedCounter'=> $vaccinatedCounter, 'healthFacility'=> $healthFacility,];
    //     return response::json($data);
    // }


}
