<?php

namespace App\Http\Controllers\API\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\ProgramServicesHasDepartment;
use App\IskoCab\AssistanceType;
use App\iskocab\ScholarAttainmentSummary;
use App\IskoCab\Scholar;
use App\IskoCab\ScholarHasApplication;
use App\IskoCab\ScholarHasApplicationSummary;
use App\IskoCab\ScholarHasSubjectGrades;
use App\IskoCab\ScholarshipProgramModule;
use Carbon\Carbon;
use Validator;
use Auth;
use DB;

class ScholarController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getScholarData()
    {
        if(Auth::user()->account_status == 1){
            try {
                // $active_program = ProgramServicesHasDepartment::where('department_id', '=', 3)
                //                     ->where('status', '=', 1)
                //                     ->first();

                // if(!is_null($active_program)){
                DB::beginTransaction();
                $scholar = Scholar::where('user_id','=', Auth::user()->id)
                            ->join('scholar_has_course_summaries', 'scholars.id', 'scholar_has_course_summaries.scholar_id')
                            ->join('courses', 'scholar_has_course_summaries.course_id', 'courses.id')
                            ->join('scholar_has_school_summaries', 'scholars.id', 'scholar_has_school_summaries.scholar_id')
                            ->join('schools', 'scholar_has_school_summaries.school_id', 'schools.id')
                            ->join('grading_systems', 'scholar_has_school_summaries.school_id', 'grading_systems.school_id')
                            ->join(connectionName(). '.users as users', 'scholars.user_id', 'users.id')
                            ->join(connectionName(). '.people as people', 'users.person_id', 'people.id')
                            ->select(
                                'scholars.id AS scholar_id',
                                'people.first_name',
                                'people.middle_name',
                                'people.last_name',
                                'courses.course_code',
                                'courses.course_description',
                                'schools.school_name',
                                'schools.address',
                                'grading_systems.grade_list',
                                'grading_systems.grading_type'
                            )->where('scholar_has_school_summaries.status', '=', 1)
                            ->where('scholar_has_school_summaries.status', '=', 1)
                            ->where('scholar_has_course_summaries.status', '=', 1)
                            ->where('grading_systems.status', '=', 1)->first();

                $updated_attainment = ScholarAttainmentSummary::join('scholars', 'scholar_attainment_summaries.scholar_id', 'scholars.id')
                                ->join('educational_attainments', 'scholar_attainment_summaries.attainment_id', 'educational_attainments.id')
                                ->select('educational_attainments.id', 'educational_attainments.title')->where('scholar_attainment_summaries.status', '=', 1)->first();
                $scholar->educational_attainment = $updated_attainment->title;

                $scholar->grade_list = unserialize($scholar->grade_list);

                DB::commit();

                return response()->json(['success' => $this->successStatus, 'data' => $scholar], $this->successStatus);
                // } else {
                //     return response()->json(['error' => $this->errorStatus, 'message' => 'No Scholarship available'], $this->errorStatus);
                // }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function getScholarshipProgramModules()
    {
        if(Auth::user()->account_status == 1){
            try {
                $active_program = ProgramServicesHasDepartment::where('department_id', '=', 3)
                                    ->where('status', '=', 1)
                                    ->first();

                if(!is_null($active_program)){
                    DB::beginTransaction();

                    $scholar_attainment = Scholar::join('scholar_attainment_summaries', 'scholars.id', 'scholar_attainment_summaries.scholar_id')
                                    ->where('user_id', '=', Auth::user()->id)->first();
                                    
                    $programModules = ProgramServicesHasDepartment::join('program_services as program_services', 'program_services_has_departments.program_services_id', 'program_services.id')
                                        ->join(connectionName('iskocab') . '.scholarship_program_modules as scholarship_program_modules', 'program_services.id', 'scholarship_program_modules.program_id')
                                        ->join(connectionName('iskocab') . '.educational_attainments as educational_attainments', 'scholarship_program_modules.ea_id', 'educational_attainments.id')
                                        ->select(
                                            'program_services_has_departments.id AS program_services_id',
                                            'program_services.title',
                                            'program_services.status',
                                            'educational_attainments.title',
                                            'educational_attainments.status',
                                            'scholarship_program_modules.id AS scholarship_program_modules_id',
                                            'scholarship_program_modules.number_of_units',
                                            'scholarship_program_modules.required_grade',
                                            'scholarship_program_modules.required_exam',
                                            'scholarship_program_modules.required_requirements',
                                            'scholarship_program_modules.required_year',
                                            'scholarship_program_modules.application_status',
                                            'scholarship_program_modules.accept_passing_grade',
                                            'scholarship_program_modules.status',
                                        )->where('program_services.status', '=', 1)
                                        ->where('educational_attainments.status', '=', 1)
                                        ->where('scholarship_program_modules.status', '=', 1)
                                        ->where('scholarship_program_modules.application_status', '=', 1)
                                        ->where('program_services_has_departments.department_id', '=', 3)
                                        ->where('scholarship_program_modules.ea_id', '=', $scholar_attainment->attainment_id)->first();

                    if(!is_null($programModules)){
                        $lowest_assistance_type = AssistanceType::where('program_services_id', '=', $programModules->program_services_id)->orderBy('grade_from', 'ASC')->first();

                        $programModules->lowest_assistance_grade = $lowest_assistance_type->grade_from;

                        $check_scholar_application_exists = ScholarHasApplication::where('scholar_has_applications.scholar_id', '=', $scholar_attainment->scholar_id)
                                                            ->where('scholar_has_applications.progmodule_id', '=', $programModules->scholarship_program_modules_id)->first();

                        if(is_null($check_scholar_application_exists)){
                            $programModules->already_applied = 0;
                        } else {
                            $programModules->already_applied = 1;
                            $programModules->assessment_status = $check_scholar_application_exists->assessment_status;
                            $programModules->evaluation_status = $check_scholar_application_exists->evaluation_status;
                            $programModules->application_status = $check_scholar_application_exists->application_status;
                        }

                        DB::commit();

                        return response()->json(['success' => $this->successStatus, 'data' => $programModules, 'message' => 'Retrieved successfully.'], $this->successStatus);
                    } else {
                        DB::commit();

                        return response()->json(['success' => $this->successStatus, 'data' => $programModules, 'message' => "Sorry, It seems like your current standing is not qualified for this kind of scholarship."], $this->successStatus);
                    }
                } else {
                    return response()->json(['error' => $this->errorStatus, 'message' => 'Wait for further announcement for next scholarship application. Please visit CYDA Facebook Page for more information.', 'data' => null], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function storeScholarApplicationData(Request $request)
    {
        if(Auth::user()->account_status == 1){

            try {
                DB::beginTransaction();

                $get_program_module = ScholarshipProgramModule::where('id', '=', $request['program_module_id'])->where('application_status', '=', 1)->first();
                $scholar_information = Scholar::join('scholar_attainment_summaries as scholar_attainment_summaries', 'scholars.id', 'scholar_attainment_summaries.scholar_id')
                                        ->join('scholar_has_course_summaries', 'scholars.id', 'scholar_has_course_summaries.scholar_id')
                                        ->join('scholar_has_school_summaries', 'scholars.id', 'scholar_has_school_summaries.scholar_id')
                                        ->select(
                                            'scholars.id AS scholar_id',
                                            'scholar_attainment_summaries.attainment_id as attainment_id',
                                            'scholar_has_course_summaries.id as scholar_has_course_summaries_id',
                                            'scholar_has_school_summaries.id as scholar_has_school_summaries_id',
                                        )->where('scholars.user_id', '=', Auth::user()->id)->first();

                $check_scholar_application_exists = ScholarHasApplication::where('scholar_has_applications.scholar_id', '=', $scholar_information->scholar_id)
                                                    ->where('scholar_has_applications.progmodule_id', '=', $get_program_module->id)->first();

                if(!is_null($get_program_module)){
                    if(is_null($check_scholar_application_exists)){

                        //check if pasok ung unit sa specified program module
                        if(!$this->checkRequiredUnits($get_program_module->number_of_units, $request['total_no_of_units'])){
                            return response()->json(['error' => $this->errorStatus, 'message' => 'Your current no. of units does not meet the required unit for this Scholarsip Application.'], $this->errorStatus);
                        }

                        //get scholar type base sa educational attainment
                        $assistance_type = ProgramServicesHasDepartment::join('program_services as program_services', 'program_services_has_departments.program_services_id', 'program_services.id')
                                        ->leftJoin(connectionName('iskocab'). '.assistance_types as assistance_types', 'program_services.id', 'assistance_types.program_services_id')
                                        ->join(connectionName('iskocab'). '.educational_attainments', 'assistance_types.educational_attainment_id', 'educational_attainments.id')
                                        ->select(
                                            'assistance_types.id AS assistance_types_id',
                                            'assistance_types.grade_from',
                                            'assistance_types.grade_to',
                                            'assistance_types.required_exam',
                                            'assistance_types.status',
                                            'educational_attainments.id AS educational_attainments_id',
                                        )->where('program_services.status', '=', 1)
                                        ->where('assistance_types.status', '=', 1)
                                        ->where('assistance_types.grade_from', '<=', intval(ceil($request['total_grade_equivalent'])))
                                        ->where('assistance_types.grade_to', '>=', intval(ceil($request['total_grade_equivalent'])))
                                        ->where('educational_attainments.id', '=', $scholar_information->attainment_id)
                                        ->first();

                        if(is_null($assistance_type)){
                            return response()->json(['error' => $this->errorStatus, 'message' => 'Your current grade equivalent is not applicable for this Scholarsip Application.'], $this->errorStatus);
                        }

                        $current_date = Carbon::today();
                        $year = $current_date->year;
                        $day = $current_date->day;
                        $month = $current_date->month;

                        $scholar_application = new ScholarHasApplication();
                        $scholar_application->progmodule_id = $request['program_module_id'];
                        $scholar_application->scholar_id = $scholar_information->scholar_id;
                        $scholar_application->assessment_status = "FALSE";
                        $scholar_application->evaluation_status = "FALSE";
                        $scholar_application->application_status = "SUCCESS";
                        $scholar_application->application_code = 'mm';
                        $scholar_application->save();

                        $scholar_application->application_code = 'APP' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $scholar_application->id, 16, '0', STR_PAD_LEFT);
                        $scholar_application->save();

                        $scholar_grades = new ScholarHasSubjectGrades();
                        $data = json_decode($request['grade_list'],true);
                        $grade = serialize($data);
                        $scholar_grades->grade_list = $grade;
                        $scholar_grades->gwa = $request['GWA'];
                        $scholar_grades->total_grade_equivalent = convertData($request['total_grade_equivalent']);
                        $scholar_grades->overall_remarks = convertData($request['overall_remarks']);
                        $scholar_grades->status = 1;
                        $scholar_grades->save();

                        $scholar_application_summary = new ScholarHasApplicationSummary();
                        $scholar_application_summary->application_id = $scholar_application->id;
                        $scholar_application_summary->assistance_id = $assistance_type->assistance_types_id;

                        $scholar_application_summary->scholar_course_id = $scholar_information->scholar_has_course_summaries_id;
                        $scholar_application_summary->scholar_school_id = $scholar_information->scholar_has_school_summaries_id;
                        $scholar_application_summary->applied_by = Auth::user()->id;
                        if($assistance_type->required_exam == 0){
                            $scholar_application_summary->exam_qualification = 'UNQUALIFIED';
                        } else {
                            $scholar_application_summary->exam_qualification = 'QUALIFIED';
                        }
                        $scholar_application_summary->grades_id = $scholar_grades->id;
                        $scholar_application_summary->exam_already_taken_status = 0;
                        $scholar_application_summary->year_level = convertData($request['year_level']);
                        $scholar_application_summary->status = 1;
                        $scholar_application_summary->save();

                        DB::commit();
                        return response()->json(['success' => $this->successCreateStatus, 'message' => 'Scholarship Application submittted successfully.'], $this->successCreateStatus);

                    } else {
                        DB::rollBack();
                        return response()->json(['error' => $this->errorStatus, 'message' => 'You already applied for this scholarship application.'], $this->errorStatus);
                    }

                } else {
                    DB::rollBack();
                    return response()->json(['error' => $this->errorStatus, 'message' => 'No Scholarship Program found.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    private function checkRequiredUnits($scholarship_program_module_data, $scholar_data)
    {
        if($scholarship_program_module_data <= $scholar_data){
            return true;
        }
        return false;
    }

    public function checkforExamination(Request $request)
    {
        if(Auth::user()->account_status == 1){

            // $validator = Validator::make($request->all(), [
            //     'program_module_id' => 'required',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            // }

            try {

                DB::beginTransaction();

                $scholar_data = Scholar::join('scholar_attainment_summaries', 'scholars.id', 'scholar_attainment_summaries.scholar_id')
                                ->select(
                                    'scholars.id AS scholar_id',
                                    'scholars.status AS scholar_status',
                                    'scholar_attainment_summaries.attainment_id',
                                    'scholar_attainment_summaries.status AS scholar_attainment_summaries_status'
                                )->where('scholars.user_id', '=', Auth::user()->id)
                                ->where('scholars.status', '=', 1)
                                ->where('scholar_attainment_summaries.status', '=', 1)->first();

                $get_program_module = ProgramServicesHasDepartment::join('program_services AS program_services', 'program_services_has_departments.program_services_id', 'program_services.id')
                                    ->join(connectionName('iskocab'). '.scholarship_program_modules as scholarship_program_modules', 'program_services.id', 'scholarship_program_modules.program_id')
                                    ->select(
                                        'scholarship_program_modules.id AS program_module_id',
                                        'scholarship_program_modules.ea_id',
                                        'program_services.status AS program_services_status',
                                        'program_services_has_departments.status AS program_services_has_departments_status',
                                        'program_services_has_departments.department_id'
                                    )->where('program_services_has_departments.department_id', '=', 3)
                                    ->where('program_services_has_departments.status', '=', 1)
                                    ->where('scholarship_program_modules.ea_id', '=', $scholar_data->attainment_id)
                                    ->where('program_services.status', '=', 1)->first();

                // $check_for_examination = ScholarHasApplication::join('scholar_has_application_summaries', 'scholar_has_applications.id', 'scholar_has_application_summaries.application_id')
                //                         ->join('assistance_types', 'scholar_has_application_summaries.assistance_id', 'assistance_types.id')
                //                         ->select(
                //                             'assistance_types.required_exam',
                //                             'scholar_has_applications.scholar_id',
                //                             'scholar_has_applications.progmodule_id'
                //                         )->where('scholar_has_applications.scholar_id', '=', $scholar_id->id)
                //                         ->where('scholar_has_applications.progmodule_id', '=', $request['program_services_id'])->first();
                if(!is_null($get_program_module)){
                    $qualified_for_exam = ScholarHasApplication::join('scholar_has_application_summaries', 'scholar_has_applications.id', 'scholar_has_application_summaries.application_id')
                                            ->select(
                                                'scholar_has_applications.scholar_id',
                                                'scholar_has_applications.progmodule_id',
                                                'scholar_has_applications.evaluation_status',
                                                'scholar_has_application_summaries.exam_qualification',
                                                'scholar_has_application_summaries.exam_qualification_remarks',
                                                'scholar_has_application_summaries.exam_already_taken_status',
                                            )->where('scholar_has_applications.scholar_id', '=', $scholar_data->scholar_id)
                                            ->where('scholar_has_application_summaries.status', '=', 1)
                                            ->where('scholar_has_applications.progmodule_id', '=', $get_program_module->program_module_id)->first();
                    
                     
                    if(!is_null($qualified_for_exam)){
                        if($qualified_for_exam->exam_already_taken_status == 1){
                            DB::commit();
                            return response()->json(['success' => $this->successStatus, 'data'=> null, 'message' => 'You are already taken the exam.'], $this->successStatus);
                        } else {
                            if($qualified_for_exam->evaluation_status == 'TRUE'){
                                if($qualified_for_exam->exam_qualification == 'QUALIFIED'){
                            // if(!is_null($check_for_examination)){
                            //     if($check_for_examination->required_exam == 1){
                                    DB::commit();
                                    return response()->json(['success' => $this->successStatus, 'data'=> $qualified_for_exam->exam_qualification, 'message' => 'You are qualified to take the examination.'], $this->successStatus);
                                } elseif ($qualified_for_exam->exam_qualification == 'EXEMPTION') {
                                    DB::commit();
                                    return response()->json(['success' => $this->successStatus, 'data'=> $qualified_for_exam->exam_qualification, 'message' => 'You are exempted to take the examination. REASON: ' . $qualified_for_exam->exam_qualification_remarks], $this->successStatus);
                                } else {
                                    DB::commit();
                                    return response()->json(['success' => $this->successStatus, 'data'=> $qualified_for_exam->exam_qualification, 'message' => 'You are not qualified to take the examination.'], $this->successStatus);
                                }
                            } else {
                                DB::commit();
                                return response()->json(['error' => $this->errorStatus, 'data'=> null, 'message' => 'Please wait for your scholarship application to be evaluated.'], $this->errorStatus);
                            }
                        }
                    } else {
                        DB::commit();
                        return response()->json(['error' => $this->errorStatus, 'data'=> null, 'message' => 'We can\'t find any record. Make sure you have applied for ISKOCAB program first.'], $this->errorStatus);
                    }
                    
                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus, 'data'=> null, 'message' => 'No Program available.'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function getScholarPerformance(Request $request)
    {
    //     $validator = Validator::make($request->all(), [
    //         'scholar_id' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error'=>$validator->errors()], $this->errorStatus);
    //     }

        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $scholar = Scholar::where('user_id', '=', Auth::user()->id)->select('id')->first();

                if(!is_null($scholar)){

                    $scholar_grades_history = ScholarHasApplication::join('scholar_has_application_summaries', 'scholar_has_applications.id', 'scholar_has_application_summaries.application_id')
                                            ->join('scholar_has_subject_grades', 'scholar_has_application_summaries.grades_id', 'scholar_has_subject_grades.id')
                                            ->join('assistance_types', 'scholar_has_application_summaries.assistance_id', 'assistance_types.id')
                                            ->join('scholar_has_course_summaries', 'scholar_has_applications.scholar_id', 'scholar_has_course_summaries.scholar_id')
                                            ->join('courses', 'scholar_has_course_summaries.course_id', 'courses.id')
                                            ->join('scholar_has_school_summaries', 'scholar_has_applications.scholar_id', 'scholar_has_school_summaries.scholar_id')
                                            ->join('schools', 'scholar_has_school_summaries.school_id', 'schools.id')
                                            ->join('grading_systems', 'scholar_has_school_summaries.school_id', 'grading_systems.school_id')
                                            ->join('scholarship_program_modules', 'scholar_has_applications.progmodule_id', 'scholarship_program_modules.id')
                                            ->join(connectionName('comprehensive').'.program_services as program_services', 'scholarship_program_modules.program_id', 'program_services.id')
                                            ->select(
                                                'program_services.title as as program_service_title',
                                                'assistance_types.title as assistance_type',
                                                'courses.course_code',
                                                'courses.course_description',
                                                'schools.school_name',
                                                'schools.address',
                                                'scholar_has_application_summaries.year_level',
                                                'scholar_has_subject_grades.id',
                                                'scholar_has_subject_grades.gwa',
                                                'scholar_has_subject_grades.total_grade_equivalent',
                                                'scholar_has_subject_grades.overall_remarks',
                                                'scholar_has_applications.created_at'
                                            )->where('scholar_has_applications.scholar_id', '=', $scholar->id)
                                            ->where('scholarship_program_modules.application_status', '=', 0)->latest()->get();
                    
                    if(is_null($scholar_grades_history)){
                        DB::commit();

                        return response()->json(['success' => $this->successStatus, 'data' => $scholar_grades_history, 'message' => 'No Scholar grades found.'], $this->successStatus);
                    } else {
                        unset($scholar_grades_history[0]);
                        DB::commit();
                        
                        return response()->json(['success' => $this->successStatus, 'data' => $scholar_grades_history, 'message' => 'Scholar grades retrieved successfully.'], $this->successStatus);
                    }
                } else {
                    DB::commit();

                    return response()->json(['success' => $this->errorStatus, 'data' => null, 'message' => 'No Scholar found.'], $this->errorStatus);
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
