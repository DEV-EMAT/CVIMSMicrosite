<?php

namespace App\Http\Controllers\API\IskoCab;

use App\Comprehensive\Examination;
use App\Comprehensive\ExaminationHasDepartment;
use App\Comprehensive\ProgramServicesHasDepartment;
use App\Comprehensive\ExamTitle;
use App\Http\Controllers\Controller;
use App\IskoCab\Scholar;
use App\IskoCab\ScholarHasApplication;
use App\IskoCab\ScholarHasApplicationSummary;
use App\IskoCab\ScholarHasExaminationSummary;
use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;

class ExamController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getIskocabMyExam(Request $request)
    {
        if(Auth::user()->account_status == 1){
            // $validator = Validator::make($request->all(), [
            //     'program_services_id' => 'required',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            // }

            try {
                DB::beginTransaction();
                $programModules = ProgramServicesHasDepartment::join('program_services as program_services', 'program_services_has_departments.program_services_id', 'program_services.id')
                                    ->join(connectionName('iskocab') . '.scholarship_program_modules as scholarship_program_modules', 'program_services.id', 'scholarship_program_modules.program_id')
                                    ->select(
                                        'scholarship_program_modules.id AS scholarship_program_modules_id',
                                    )->where('program_services.status', '=', 1)
                                    ->where('scholarship_program_modules.status', '=', 1)
                                    ->where('program_services_has_departments.department_id', '=', 3)->first();

                $scholar_id = Scholar::select('id')->where('scholars.user_id', '=', Auth::user()->id)->first();


                $qualified_for_exam = ScholarHasApplication::join('scholar_has_application_summaries', 'scholar_has_applications.id', 'scholar_has_application_summaries.application_id')
                                        ->select(
                                            'scholar_has_applications.scholar_id',
                                            'scholar_has_applications.progmodule_id',
                                            'scholar_has_applications.application_code',
                                            'scholar_has_application_summaries.id AS application_summary_id',
                                            'scholar_has_application_summaries.exam_qualification',
                                            'scholar_has_application_summaries.exam_already_taken_status'
                                        )->where('scholar_has_applications.scholar_id', '=', $scholar_id->id)
                                        ->where('scholar_has_applications.progmodule_id', '=', $programModules->scholarship_program_modules_id)->first();

                // $check_for_examination = ScholarHasApplication::join('scholar_has_application_summaries', 'scholar_has_applications.id', 'scholar_has_application_summaries.application_id')
                //                         ->join('assistance_types', 'scholar_has_application_summaries.assistance_id', 'assistance_types.id')
                //                         ->select(
                //                             'assistance_types.required_exam',
                //                             'scholar_has_applications.scholar_id',
                //                             'scholar_has_applications.progmodule_id'
                //                         )->where('scholar_has_applications.scholar_id', '=', $scholar_id->id)
                //                         ->where('scholar_has_applications.progmodule_id', '=', $request['program_services_id'])->first();

                if(!is_null($qualified_for_exam)){
                    if($qualified_for_exam->exam_already_taken_status != 1){
                        if($qualified_for_exam->exam_qualification == 'QUALIFIED'){
                    // if(!is_null($check_for_examination)){
                    //     if($check_for_examination->required_exam == 1){
                            $exam = ExaminationHasDepartment::join('exam_titles', 'examination_has_departments.exam_title_id', 'exam_titles.id')
                                    ->select(
                                        'examination_has_departments.exam_title_id',
                                        'exam_titles.title',
                                        'exam_titles.description',
                                        'exam_titles.time',
                                        'exam_titles.item_number',
                                        'exam_titles.passing',
                                    )->where('examination_has_departments.department_id', '=', 3)
                                    ->where('exam_titles.status', '=', 1)
                                    ->where('exam_titles.exam_title_status', '=', 1)->first();

                            if(!is_null($exam)){
                                $exam_questionaires = Examination::join('questions as questions', 'examinations.question_id', 'questions.id')
                                                        ->join('exam_subjects as exam_subjects', 'questions.exam_subject_id', 'exam_subjects.id')
                                                        ->join('exam_types as exam_types', 'questions.exam_type_id', 'exam_types.id')
                                                        ->select(
                                                            'questions.id AS question_id',
                                                            'questions.question',
                                                            'questions.answer',
                                                            'questions.choices',
                                                            'exam_subjects.subject',
                                                            'exam_types.type'
                                                        )->where('examinations.exam_title_id', '=', $exam->exam_title_id)->get();

                                $exam_questionaires->map(function($data){
                                    $data->choices = unserialize($data->choices);
                                });

                                $scholar_app_summary = ScholarHasApplicationSummary::findOrFail($qualified_for_exam->application_summary_id);
                                $scholar_app_summary->exam_already_taken_status = 1;
                                $scholar_app_summary->save();

                                $exam_questionaires_randomized = $exam_questionaires->shuffle();

                                $exam->my_exam = $exam_questionaires_randomized;
                                $exam->my_application_code = $qualified_for_exam->application_code;
                                DB::commit();

                                return response()->json(['success' => $this->successStatus, 'message' => 'Exam data retrieved successfully.', 'data' => $exam], $this->successStatus);
                            } else {
                                DB::commit();

                                return response()->json(['success' => $this->errorStatus, 'message' => 'No Exam data found.', 'data' => null], $this->errorStatus);
                            }
                        } else {
                            DB::commit();
                            return response()->json(['error' => $this->errorStatus, 'data'=> null, 'message' => 'User is not required for this scholarship examination.'], $this->errorStatus);
                        }
                    } else {
                        DB::commit();
                        return response()->json(['success' => $this->successStatus, 'data'=> $qualified_for_exam->application_code, 'message' => 'It looks like you already requested to take the exam before. Please proceed to CYDA office to request another examination.'], $this->successStatus);
                    }
                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus, 'data'=> null, 'message' => 'User has no data for scholar application.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

    public function storeScholarExam(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'exam_title_id' => 'required',
                'score' => 'required',
                'answer_sheet' => 'required',
                'score_equivalent' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $programModules = ProgramServicesHasDepartment::join('program_services as program_services', 'program_services_has_departments.program_services_id', 'program_services.id')
                                    ->join(connectionName('iskocab') . '.scholarship_program_modules as scholarship_program_modules', 'program_services.id', 'scholarship_program_modules.program_id')
                                    ->select(
                                        'scholarship_program_modules.id AS scholarship_program_modules_id',
                                    )->where('program_services.status', '=', 1)
                                    ->where('scholarship_program_modules.status', '=', 1)
                                    ->where('scholarship_program_modules.application_status', '=', 1)
                                    ->where('program_services_has_departments.department_id', '=', 3)->first();

                if(!is_null($programModules)){

                    $scholar = Scholar::where('user_id', '=', Auth::user()->id)->select('id')->first();

                    if(!is_null($scholar)){
                        $get_active_application = ScholarHasApplication::join('scholar_has_application_summaries', 'scholar_has_applications.id', 'scholar_has_application_summaries.application_id')
                                                    ->where('scholar_has_applications.progmodule_id', '=', $programModules->scholarship_program_modules_id)
                                                    ->where('scholar_has_application_summaries.status', '=', 1)
                                                    ->where('scholar_has_applications.scholar_id', '=', $scholar->id)->select('scholar_has_applications.id')->first();

                        if(!is_null($get_active_application)){
                            $sholar_exam_data = new ScholarHasExaminationSummary();
                            $sholar_exam_data->application_id = $get_active_application->id;
                            $sholar_exam_data->exam_title_id = $request->exam_title_id;
                            $sholar_exam_data->score = $request->score;

                            $data = json_decode($request->answer_sheet,true);
                            $exam_info = ExamTitle::where('id', '=', $request->exam_title_id)->first();
                            if($request->score_equivalent >= $exam_info->passing){
                                $sholar_exam_data->examination_result = 'PASSED';
                            } else {
                                $sholar_exam_data->examination_result = 'FAILED';
                            }
                            $sholar_exam_data->answer_sheet = serialize($data);
                            $sholar_exam_data->examination_status = 1;
                            $sholar_exam_data->save();
                            
                            $update_exam_already_taken = ScholarHasApplicationSummary::findOrFail($get_active_application->scholar_has_application_summaries_id);
                            $update_exam_already_taken->exam_already_taken_status = 1;
                            $update_exam_already_taken->save();
                            
                            DB::commit();

                            return response()->json(['success' => $this->successCreateStatus, 'message' => 'Exam data saved successfully.', 'data' => null], $this->successCreateStatus);
                        } else {
                            DB::commit();

                            return response()->json(['error' => $this->errorStatus, 'message' => 'No application found.', 'data' => null], $this->errorStatus);
                        }
                    } else {
                        DB::commit();

                        return response()->json(['error' => $this->errorStatus, 'message' => 'Scholar not found.', 'data' => null], $this->errorStatus);
                    }
                } else {
                    DB::commit();

                    return response()->json(['error' => $this->errorStatus, 'message' => 'No Program module found.', 'data' => null], $this->errorStatus);
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
