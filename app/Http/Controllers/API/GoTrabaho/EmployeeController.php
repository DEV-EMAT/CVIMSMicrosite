<?php

namespace App\Http\Controllers\API\GoTrabaho;

use App\GoTrabaho\CertificateOfCompetence;
use App\GoTrabaho\Eligibility;
use App\GoTrabaho\FormalEducation;
use App\GoTrabaho\JobApplication;
use App\GoTrabaho\JobBookmark;
use App\GoTrabaho\OtherSkills;
use App\GoTrabaho\PersonalInformation;
use App\GoTrabaho\ProfessionalLicense;
use App\GoTrabaho\SeaBasedWorkers;
use App\GoTrabaho\VocationalTraining;
use App\GoTrabaho\WorkExperience;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;

class EmployeeController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getMyProfile()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $personal_info = PersonalInformation::where('user_id', '=', Auth::user()->id)->first();

                $my_certs = CertificateOfCompetence::where('personal_info_id', '=', $personal_info->id)
                            ->select(
                                'certificates',
                                'certificates',
                                'issued_by',
                                'date_issued',
                            )->where('status', '=', 1)->get();

                $my_eligibility = Eligibility::where('personal_info_id', '=', $personal_info->id)
                                    ->select(
                                        'eligibility_title',
                                        'year_taken',
                                    )->where('status', '=', 1)->get();

                $my_formal_educ = FormalEducation::where('personal_info_id', '=', $personal_info->id)
                                    ->select(
                                        'degree',
                                        'level',
                                        'name_of_school',
                                        'yr_graduated',
                                    )->where('status', '=', 1)->get();

                $my_other_skills = OtherSkills::where('personal_info_id', '=', $personal_info->id)
                                    ->select(
                                        'skill_description',
                                    )->where('status', '=', 1)->get();

                $my_professional_licenses = ProfessionalLicense::where('personal_info_id', '=', $personal_info->id)
                                                ->select(
                                                    'license_title',
                                                    'expiry_date',
                                                )->where('status', '=', 1)->get();

                $my_sea_based_works = SeaBasedWorkers::where('personal_info_id', '=', $personal_info->id)
                                        ->select(
                                            'position',
                                            'agency_company',
                                            'type_tonnage',
                                            'date_of_service_from',
                                            'date_of_service_to',
                                        )->where('status', '=', 1)->get();

                $my_vocational_trainings = VocationalTraining::where('personal_info_id', '=', $personal_info->id)
                                            ->select(
                                                'name_of_training',
                                                'skill_acquired',
                                                'yr_of_exp',
                                                'cert_received',
                                                'issuing_school_agency',
                                            )->where('status', '=', 1)->get();

                $my_work_experiences = WorkExperience::where('personal_info_id', '=', $personal_info->id)
                                        ->select(
                                            'company_name',
                                            'address',
                                            'inclusive_date_from',
                                            'inclusive_date_to',
                                            'position_held',
                                            'formal_education',
                                        )->where('status', '=', 1)->get();

                $personal_info->certs = $my_certs;
                $personal_info->eligibility = $my_eligibility;
                $personal_info->formal_educ = $my_formal_educ;
                $personal_info->other_skills = $my_other_skills;
                $personal_info->professional_licenses = $my_professional_licenses;
                $personal_info->sea_based_works = $my_sea_based_works;
                $personal_info->vocational_trainings = $my_vocational_trainings;
                $personal_info->work_experiences = $my_work_experiences;

                DB::commit();

                return response()->json(['success' => $this->successStatus, 'data' => $personal_info, 'message' => 'Employee profile retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function getMyBookmarks()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $my_job_bookmarks = JobBookmark::join('personal_information', 'job_bookmarks.personal_info_id', 'personal_information.id')
                                    ->join('job_vacancies', 'job_bookmarks.job_vacancy_id', 'job_vacancies.id')
                                    ->join('company_contacts', 'job_vacancies.company_contacts_id', 'company_contacts.id')
                                    ->join('companies', 'company_contacts.company_id', 'companies.id')
                                    ->join('job_categories', 'job_vacancies.job_categories_id', 'job_categories.id')
                                    ->where('personal_information.user_id', '=', Auth::user()->id)->get();

                DB::commit();

                return response()->json(['success' => $this->successStatus, 'data' => $my_job_bookmarks, 'message' => 'Job bookmarks retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function getMyJobApplications()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $get_my_job_application = JobApplication::join('personal_information', 'job_applications.personal_info_id', 'personal_information.id')
                                        ->join('job_vacancies', 'job_applications.job_vacancies_id', 'job_vacancies.id')
                                        ->join('job_categories', 'job_vacancies.job_categories_id', 'job_categories.id')
                                        ->join('company_contacts', 'job_vacancies.company_contacts_id', 'company_contacts.id')
                                        ->join('companies', 'company_contacts.company_id', 'companies.id')
                                        ->where('personal_information.user_id', '=', Auth::user()->id)->get();

                DB::commit();

                return response()->json(['success' => $this->successStatus, 'data' => $get_my_job_application, 'message' => 'Job Categories retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Information
    public function storeEmployeeInfo(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'height' => 'required',
                'weight' => 'required',
                'employment_status' => 'required',
                'preferred_occupation' => 'required',
                'disable' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $check_if_exists = PersonalInformation::where('user_id', '=', Auth::user()->id)->first();

                if(is_null($check_if_exists)){
                    $new_employee = new PersonalInformation();
                    $new_employee->user_id = Auth::user()->id;
                    $new_employee->height = $request->height;
                    $new_employee->weight = $request->weight;
                    $new_employee->employment_status = $request->employment_status;
                    $new_employee->employment_description = $request->employment_description;
                    $new_employee->preferred_occupation = $request->preferred_occupation;
                    $new_employee->preferred_occupation_details = $request->preferred_occupation_details;
                    $new_employee->passport_number = $request->passport_number;
                    $new_employee->expiry_date = $request->expiry_date;
                    $new_employee->disable = $request->disable;
                    $new_employee->disable_category = $request->disable_category;
                    $new_employee->language_dialect = $request->language_dialect;
                    $new_employee->other_language = $request->other_language;
                    $new_employee->status = 1;
                    $new_employee->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Already Existed', 'message' => 'Employee already existed.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeInfo(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'height' => 'required',
                'weight' => 'required',
                'employment_status' => 'required',
                'preferred_occupation' => 'required',
                'disable' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $edit_employee = PersonalInformation::findOrFail($request->id);
                $edit_employee->height = $request->height;
                $edit_employee->weight = $request->weight;
                $edit_employee->employment_status = $request->employment_status;
                $edit_employee->employment_description = $request->employment_description;
                $edit_employee->preferred_occupation = $request->preferred_occupation;
                $edit_employee->preferred_occupation_details = $request->preferred_occupation_details;
                $edit_employee->passport_number = $request->passport_number;
                $edit_employee->expiry_date = $request->expiry_date;
                $edit_employee->disable = $request->disable;
                $edit_employee->disable_category = $request->disable_category;
                $edit_employee->language_dialect = $request->language_dialect;
                $edit_employee->other_language = $request->other_language;
                $edit_employee->save();

                DB::commit();

                return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data updated successfully.'], $this->successCreateStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Professional License
    public function storeEmployeeProfessionalLicense(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_professional_license = new ProfessionalLicense();
                        $new_employee_professional_license->personal_info_id = $get_employee_data->id;
                        $new_employee_professional_license->license_title = $value->license_title;
                        $new_employee_professional_license->expiry_date = $value->expiry_date;
                        $new_employee_professional_license->status = 1;
                        $new_employee_professional_license->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeProfessionalLicense(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_professional_license = ProfessionalLicense::findOrFail($request->id);
                    $edit_employee_professional_license->personal_info_id = $get_employee_data->id;
                    $edit_employee_professional_license->license_title = $request->license_title;
                    $edit_employee_professional_license->expiry_date = $request->expiry_date;
                    $edit_employee_professional_license->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeProfessionalLicense(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_professional_license = ProfessionalLicense::findOrFail($request->id);
                    $edit_employee_professional_license->status = 0;
                    $edit_employee_professional_license->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Sea based Workers
    public function storeEmployeeSeaBasedWorkers(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_sea_based_worker = new SeaBasedWorkers();
                        $new_employee_sea_based_worker->personal_info_id = $get_employee_data->id;
                        $new_employee_sea_based_worker->position = $value->position;
                        $new_employee_sea_based_worker->agency_company = $value->agency_company;
                        $new_employee_sea_based_worker->type_tonnage = $value->type_tonnage;
                        $new_employee_sea_based_worker->date_of_service_from = $value->date_of_service_from;
                        $new_employee_sea_based_worker->date_of_service_to = $value->date_of_service_to;
                        $new_employee_sea_based_worker->status = 1;
                        $new_employee_sea_based_worker->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeSeaBasedWorkers(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_sea_based_worker = SeaBasedWorkers::findOrFail($request->id);
                    $edit_employee_sea_based_worker->personal_info_id = $get_employee_data->id;
                    $edit_employee_sea_based_worker->position = $request->position;
                    $edit_employee_sea_based_worker->agency_company = $request->agency_company;
                    $edit_employee_sea_based_worker->type_tonnage = $request->type_tonnage;
                    $edit_employee_sea_based_worker->date_of_service_from = $request->date_of_service_from;
                    $edit_employee_sea_based_worker->date_of_service_to = $request->date_of_service_to;
                    $edit_employee_sea_based_worker->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeSeaBasedWorkers(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_sea_based_worker = SeaBasedWorkers::findOrFail($request->id);
                    $edit_employee_sea_based_worker->status = 0;
                    $edit_employee_sea_based_worker->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Vocational Training
    public function storeEmployeeVocationalTraining(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_vocational_training = new VocationalTraining();
                        $new_employee_vocational_training->personal_info_id = $get_employee_data->id;
                        $new_employee_vocational_training->name_of_training = $value->name_of_training;
                        $new_employee_vocational_training->skill_acquired = $value->skill_acquired;
                        $new_employee_vocational_training->yr_of_exp = $value->yr_of_exp;
                        $new_employee_vocational_training->cert_received = $value->cert_received;
                        $new_employee_vocational_training->issuing_school_agency = $value->issuing_school_agency;
                        $new_employee_vocational_training->status = 1;
                        $new_employee_vocational_training->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeVocationalTraining(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_vocational_training = VocationalTraining::findOrFail($request->id);
                    $edit_employee_vocational_training->personal_info_id = $get_employee_data->id;
                    $edit_employee_vocational_training->name_of_training = $request->name_of_training;
                    $edit_employee_vocational_training->skill_acquired = $request->skill_acquired;
                    $edit_employee_vocational_training->yr_of_exp = $request->yr_of_exp;
                    $edit_employee_vocational_training->cert_received = $request->cert_received;
                    $edit_employee_vocational_training->issuing_school_agency = $request->issuing_school_agency;
                    $edit_employee_vocational_training->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeVocationalTraining(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_vocational_training = VocationalTraining::findOrFail($request->id);
                    $edit_employee_vocational_training->status = 0;
                    $edit_employee_vocational_training->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Work Experience
    public function storeEmployeeWorkExperience(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_work_experience = new WorkExperience();
                        $new_employee_work_experience->personal_info_id = $get_employee_data->id;
                        $new_employee_work_experience->company_name = $value->company_name;
                        $new_employee_work_experience->address = $value->address;
                        $new_employee_work_experience->inclusive_date_from = $value->inclusive_date_from;
                        $new_employee_work_experience->inclusive_date_to = $value->inclusive_date_to;
                        $new_employee_work_experience->position_held = $value->position_held;
                        $new_employee_work_experience->formal_education = $value->formal_education;
                        $new_employee_work_experience->status = 1;
                        $new_employee_work_experience->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeWorkExperience(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_work_experience = WorkExperience::findOrFail($request->id);
                    $edit_employee_work_experience->personal_info_id = $get_employee_data->id;
                    $edit_employee_work_experience->company_name = $request->company_name;
                    $edit_employee_work_experience->address = $request->address;
                    $edit_employee_work_experience->inclusive_date_from = $request->inclusive_date_from;
                    $edit_employee_work_experience->inclusive_date_to = $request->inclusive_date_to;
                    $edit_employee_work_experience->position_held = $request->position_held;
                    $edit_employee_work_experience->formal_education = $request->formal_education;
                    $edit_employee_work_experience->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data updated successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeWorkExperience(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_work_experience = WorkExperience::findOrFail($request->id);
                    $edit_employee_work_experience->status = 0;
                    $edit_employee_work_experience->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Cert of Competence
    public function storeEmployeeCertificateOfCompetence(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_certificate_of_competence = new CertificateOfCompetence();
                        $new_employee_certificate_of_competence->personal_info_id = $get_employee_data->id;
                        $new_employee_certificate_of_competence->certificates = $value->certificates;
                        $new_employee_certificate_of_competence->issued_by = $value->issued_by;
                        $new_employee_certificate_of_competence->date_issued = $value->date_issued;
                        $new_employee_certificate_of_competence->status = 1;
                        $new_employee_certificate_of_competence->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeCertificateOfCompetence(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_certificate_of_competence = CertificateOfCompetence::findOrFail($request->id);
                    $edit_employee_certificate_of_competence->personal_info_id = $get_employee_data->id;
                    $edit_employee_certificate_of_competence->certificates = $request->certificates;
                    $edit_employee_certificate_of_competence->issued_by = $request->issued_by;
                    $edit_employee_certificate_of_competence->date_issued = $request->date_issued;
                    $edit_employee_certificate_of_competence->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data updated successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeCertificateOfCompetence(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_certificate_of_competence = CertificateOfCompetence::findOrFail($request->id);
                    $edit_employee_certificate_of_competence->status = 0;
                    $edit_employee_certificate_of_competence->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Eligibility
    public function storeEmployeeEligibility(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_eligibilities = new Eligibility();
                        $new_employee_eligibilities->personal_info_id = $get_employee_data->id;
                        $new_employee_eligibilities->eligibility_title = $value->eligibility_title;
                        $new_employee_eligibilities->year_taken = $value->year_taken;
                        $new_employee_eligibilities->status = 1;
                        $new_employee_eligibilities->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeEligibility(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_eligibilities = Eligibility::findOrFail($request->id);
                    $edit_employee_eligibilities->personal_info_id = $get_employee_data->id;
                    $edit_employee_eligibilities->eligibility_title = $request->eligibility_title;
                    $edit_employee_eligibilities->year_taken = $request->year_taken;
                    $edit_employee_eligibilities->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data updated successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeEligibility(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_eligibilities = Eligibility::findOrFail($request->id);
                    $edit_employee_eligibilities->status = 0;
                    $edit_employee_eligibilities->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Formal Education
    public function storeEmployeeFormalEducation(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_formal_education = new FormalEducation();
                        $new_employee_formal_education->personal_info_id = $get_employee_data->id;
                        $new_employee_formal_education->degree = $value->degree;
                        $new_employee_formal_education->level = $value->level;
                        $new_employee_formal_education->name_of_school = $value->name_of_school;
                        $new_employee_formal_education->yr_graduated = $value->yr_graduated;
                        $new_employee_formal_education->status = 1;
                        $new_employee_formal_education->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeFormalEducation(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_formal_education = FormalEducation::findOrFail($request->id);
                    $edit_employee_formal_education->personal_info_id = $get_employee_data->id;
                    $edit_employee_formal_education->degree = $request->degree;
                    $edit_employee_formal_education->level = $request->level;
                    $edit_employee_formal_education->name_of_school = $request->name_of_school;
                    $edit_employee_formal_education->yr_graduated = $request->yr_graduated;
                    $edit_employee_formal_education->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data updated successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeFormalEducation(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_formal_education = FormalEducation::findOrFail($request->id);
                    $edit_employee_formal_education->status = 0;
                    $edit_employee_formal_education->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    //Employee Other skills
    public function storeEmployeeOtherSkills(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('user_id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){
                    $data = json_decode($request->data);
                    foreach ($data as $key => $value) {
                        $new_employee_other_skills = new OtherSkills();
                        $new_employee_other_skills->personal_info_id = $get_employee_data->id;
                        $new_employee_other_skills->skill_description = $value->skill_description;
                        $new_employee_other_skills->status = 1;
                        $new_employee_other_skills->save();
                    }
                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data saved successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function updateEmployeeOtherSkills(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'data' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_other_skills = OtherSkills::findOrFail($request->id);
                    $edit_employee_other_skills->personal_info_id = $get_employee_data->id;
                    $edit_employee_other_skills->skill_description = $request->skill_description;
                    $edit_employee_other_skills->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data updated successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function deleteEmployeeOtherSkills(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $get_employee_data = PersonalInformation::select('id')->where('id', '=', Auth::user()->id)->first();

                if(!is_null($get_employee_data)){

                    $edit_employee_other_skills = OtherSkills::findOrFail($request->id);
                    $edit_employee_other_skills->status = 0;
                    $edit_employee_other_skills->save();

                    DB::commit();

                    return response()->json(['success' => $this->successCreateStatus, 'data' => 'Saved', 'message' => 'Employee data deleted successfully.'], $this->successCreateStatus);
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'data' => 'Not found', 'message' => 'Employee data not found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }
}
