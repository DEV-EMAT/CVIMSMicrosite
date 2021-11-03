<?php

namespace App\Http\Controllers\iskocab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IskoCab\Scholar;
use App\IskoCab\School;
use App\IskoCab\GradingSystem;
use App\Comprehensive\ProgramService;
use App\IskoCab\ScholarHasApplication;
use App\IskoCab\ScholarHasApplicationSummary;
use App\IskoCab\ScholarHasEvaluationSummary;
use App\IskoCab\ScholarHasSubjectGrades;
use App\IskoCab\ScholarshipProgramModule;
use App\iskocab\ScholarAttainmentSummary;
use Carbon\Carbon;
use DB;
use Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $program = ProgramService::join(connectionName('comprehensive'). '.program_services_has_departments as prog_dept', 'prog_dept.program_services_id', 'program_services.id')
            ->where('program_services.status', '=', '1')->where('prog_dept.status', '=', '1')->where('department_id', '=', '3')->first();

        if(!empty($program)){
            return view('iskocab.scholarship_program.application');
        }else{
            return view('layouts.unavailable',['message' => 'No Program Available!', 'description' => 'Wait for further announcement for next scholarship application. Please visit <a href="facebook.com">CYDA Facebook Page</a> for more information.', 'title' => "Scholarship Application", 'success' => false]);
        }
    }

    public function findall(Request $request){
        $columns=array(0=> 'scholars.id',
            1=> 'person_code',
            2=> 'last_name',
        );

        $totalData = Scholar::where('status', '=', '1')->count();

        $totalFiltered=$totalData;

        $limit=$request->input('length');
        $start=$request->input('start');
        $order=$columns[$request->input('order.0.column')];
        $dir=$request->input('order.0.dir');

        $query = Scholar::join(connectionName() .'.users as users', 'users.id', '=', 'scholars.user_id')
            ->join(connectionName('iskocab'). '.scholar_attainment_summaries as summaries', 'summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName() .'.people as people', 'people.id', '=', 'users.person_id')
            ->join(connectionName('iskocab') .'.scholar_has_school_summaries as school_summaries', 'school_summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.schools as schools', 'schools.id', '=', 'school_summaries.school_id')
            ->join(connectionName('iskocab') .'.scholar_has_course_summaries as course_summaries', 'course_summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.courses as courses', 'courses.id', '=', 'course_summaries.course_id')
            ->join(connectionName() .'.addresses as addresses', 'addresses.id', '=', 'people.address_id')
            ->select(
                'scholars.id',
                'people.last_name',
                'people.first_name',
                'people.middle_name',
                'people.person_code',
                'courses.course_description',
                'schools.school_name',
                'users.contact_number',
                'people.date_of_birth',
                'addresses.barangay',
                'people.address',
                'scholars.image',
            )->where('scholars.status', '=', 1)
            ->where('summaries.status', '=', 1)
            ->where('school_summaries.status', '=', 1)
            ->where('course_summaries.status', '=', 1);

        if(empty($request->input('search.value'))) {

            $scholars=$query->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

        } else {
            $search=$request->input('search.value');

            $scholars = $query->where('people.last_name', 'LIKE', "%{$search}%")
                ->orWhere('people.first_name', 'LIKE', "%{$search}%")
                ->orWhere('people.middle_name', 'LIKE', "%{$search}%")
                ->orWhere('people.person_code', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered=$query->where('people.last_name', 'LIKE', "%{$search}%")
                ->orWhere('people.first_name', 'LIKE', "%{$search}%")
                ->orWhere('people.middle_name', 'LIKE', "%{$search}%")
                ->orWhere('people.person_code', 'LIKE', "%{$search}%")
                ->count();
        }

        $data=array();

        if(!empty($scholars)) {
            foreach ($scholars as $scholar) {
                $fullname = strtoupper($scholar->last_name . ", ". $scholar->first_name. " ". $scholar->middle_name);

                $application = ScholarHasApplication::join(connectionName('iskocab'). '.scholarship_program_modules as sch_modules', 'sch_modules.id', 'scholar_has_applications.progmodule_id')
                        ->join(connectionName('comprehensive'). '.program_services as program', 'program.id', 'sch_modules.program_id')
                        ->join(connectionName('comprehensive'). '.program_services_has_departments as prog_dept', 'prog_dept.program_services_id', 'program.id')
                        ->select('scholar_has_applications.*')
                        ->where('prog_dept.status', '=', '1')
                        ->where('prog_dept.department_id', '=', '3')
                        ->where('scholar_id', '=', $scholar->id)->first();


                if($application){
                    $status="<label class='label label-danger'>Already Applied</label>";
                    $buttons ='<a onclick="grades_submitted('. $application->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="fa fa-edit"></i>&nbsp;View Grades Submitted</a>';
                }else{
                    $status="<label class='label label-primary'>Active</label>";
                    $buttons ='<a onclick="apply('. $scholar->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="fa fa-edit"></i>&nbsp;Apply...</a>';
                }

                //array data
                $nestedData['barcode']=$scholar->person_code;
                $nestedData['sch_info']=$scholar;
                $nestedData['fullname']=$fullname;
                $nestedData['status'] = $status;
                $nestedData['actions']=$buttons;
                $data[]=$nestedData;
            }

        }
        $json_data=array("draw"=> intval($request->input('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=> $data);

        echo json_encode($json_data);
    }


    public function computeGrades(Request $request){
        $isPassed = [];

        /* get grading system using scholar id */
        $grading_system = $this->getGradingSystemByScholarID($request->scholar_id);

        /* percentage/ midpoint */
        $percentage = $this->formatData($request['subject_code'], $request['units'][0], $request['grades'], $grading_system);

        /* compute GWA */
        $finalGWA = $this->computeGWA($percentage);

        // convert final gwa
        $gwaConverted = $this->comparePercentage(ceil($finalGWA), $request->scholar_id);
        
        /* convert final gwa to midpoint again */
        $finalGWA = ($gwaConverted['from'] + $gwaConverted['to'])/2;

        /* get passing grades */
        $passingGroup = $this->comparePercentage($request['min_percentage'], $request->scholar_id);

        /* check if passed base on $condition  */
        if( $finalGWA >= $passingGroup['from']){
            $isPassed = ['isPassed' => true, 'grades' => $percentage,'grading_system' => $grading_system, 'GWA' => number_format($finalGWA, 3), 'messages'=> 'Your grades is qualified!, Apply now', 'passing' => $passingGroup];
        }else{
            $isPassed = ['isPassed' => false, 'grades' => $percentage,'grading_system' => $grading_system, 'GWA' =>  number_format($finalGWA, 3), 'messages'=> 'Your Percenatge DOESNT meet the required grade for this application!', 'passing' => $passingGroup];
        }

        return response()->json($isPassed);
    }

    function formatData($subject, $units, $grades, $grading_system){
        $equivalent = [];

        foreach ($grades as $key => $grade) {
            $index = array_search($grade, $grading_system['official_grade']);
            $equivalent[] = [
                'subject_code' => $subject[$key],
                'no_of_units' => $units[$key],
                'grade' => $grade,
                'subject_grade_equivalent' =>(float) (($grading_system['grade_from'][$index] + $grading_system['grade_to'][$index]) / 2),
                'remarks' => $grading_system['remarks'][$index]
            ];
        }

        return $equivalent;
    }

    function getGradingSystemByScholarID($scholar_id) {

        $grading_system = '';

        // $scholar = Scholar::findOrFail($scholar_id);

        $scholar = Scholar::join(connectionName() .'.users as users', 'users.id', '=', 'scholars.user_id')
            ->join(connectionName('iskocab') .'.scholar_has_school_summaries as school_summaries', 'school_summaries.scholar_id', '=', 'scholars.id')
            ->where('school_summaries.status', '=', '1')->where('scholars.id', '=', $scholar_id)->first();

        $school=School::findOrFail($scholar['school_id']);
        $grading_systems = GradingSystem::where('school_id', $scholar['school_id'])->where('status', '=', 1)->get();
        foreach($grading_systems as $grading_system)
            $grading_system = unserialize($grading_system->grade_list);

        return $grading_system;
    }

    function computeGWA($grades){
        $arrayBase = count($grades);

        $temp = [];
        $total = 0;
        $total_units = 0;
        $final = 0;

        for ($index = 0; $index < $arrayBase; $index++) {
            $sum = (float) $grades[$index]['subject_grade_equivalent'] * (float) $grades[$index]['no_of_units'];
            $temp[] = $sum;
            $total += $sum;
            $total_units += (int) $grades[$index]['no_of_units'];
        }

        $final = $total/$total_units;

        return $final;
    }

    function comparePercentage($grade, $scholar_id) {
        /* grade is percentage */
        $grading_system = $this->getGradingSystemByScholarID($scholar_id);
        $finalRemarks = [];
        foreach ($grading_system['official_grade'] as $key => $value) {
            if($grade >= $grading_system['grade_from'][$key] && $grade <= $grading_system['grade_to'][$key]){
                $finalRemarks = ["official_grade" => $grading_system['official_grade'][$key] , "from" => $grading_system['grade_from'][$key], "to" => $grading_system['grade_to'][$key], "remarks" => $grading_system['remarks'][$key] ];
                break;
            }
        }

        return $finalRemarks;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    function getAssistanceType($gwa, $program_module_id, $scholar_id){
        $final_asst = '';

        $educational_attainment = ScholarAttainmentSummary::where('scholar_id', '=', $scholar_id)->where('status', '=', '1')->first();

        $module = ScholarshipProgramModule::join(connectionName('comprehensive'). '.program_services as program', 'program.id', 'scholarship_program_modules.program_id')
            ->join(connectionName('iskocab'). '.assistance_types as assistance', 'assistance.program_services_id', 'scholarship_program_modules.program_id')
            ->select('assistance.*')
            ->where('scholarship_program_modules.id', '=', $program_module_id)
            ->where('assistance.educational_attainment_id', '=', $educational_attainment['attainment_id'])->get();

        foreach ($module as $key => $value) {
            if($gwa >= $value['grade_from'] && $gwa <= $value['grade_to']){
                $final_asst = $value;
            }
        }

        return $final_asst;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            $current_date = Carbon::today();
            $year = $current_date->year;
            $day = $current_date->day;
            $month = $current_date->month;

            /* get program module */
            $program_module = ScholarshipProgramModule::findOrFail($request['program_module_id']);
            /* get assistance type */
            $assistance = $this->getAssistanceType(ceil($request['gwa']), $request['program_module_id'], $request['scholar_id']);
            /* scholar has application */
            $sch_has_application = new ScholarHasApplication;
            $sch_has_application->scholar_id = $request['scholar_id'];
            $sch_has_application->progmodule_id = $request['program_module_id'];
            $sch_has_application->application_code = '';
            $sch_has_application->assessment_status = 'FALSE';
            $sch_has_application->evaluation_status = 'FALSE';
            $sch_has_application->application_status = 'SUCCESS';
            $sch_has_application->save();

            $sch_has_application->application_code = 'APP' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $sch_has_application->id, 16, '0', STR_PAD_LEFT);
            $sch_has_application->save();

            /* save scholar inputted grades */
            $sch_grades_converted = $this->comparePercentage(ceil($request['gwa']), $request['scholar_id']);
            $sch_grades = new ScholarHasSubjectGrades;
            $sch_grades->grade_list = serialize($request['grades']);
            $sch_grades->gwa = $sch_grades_converted['official_grade'];
            $sch_grades->overall_remarks = convertData($sch_grades_converted['remarks']);
            $sch_grades->total_grade_equivalent = ($sch_grades_converted['from'] + $sch_grades_converted['to']) / 2;
            $sch_grades->status = '1';
            $sch_grades->save();

            /* save summaries */
            $sch_has_application_summaries = new ScholarHasApplicationSummary;
            $sch_has_application_summaries->application_id = $sch_has_application->id;
            $sch_has_application_summaries->assistance_id = $assistance['id'];
            $sch_has_application_summaries->grades_id = $sch_grades->id;
            $sch_has_application_summaries->year_level = !empty($request['year_level'])? $request['year_level']: '';
            $sch_has_application_summaries->exam_qualification = (($program_module['required_exam'] == '1')? (($assistance['required_exam']=='1')? 'QUALIFIED':'UNQUALIFIED'):'');
            $sch_has_application_summaries->exam_qualification_remarks = (($program_module['required_exam'] == '1')? (($assistance['required_exam']=='1')? '':'NOT REQUIRED TO TAKE EXAM'):'NO EXAM ON THIS SCHOLAR MODULE');
            $sch_has_application_summaries->status = '1';
            $sch_has_application_summaries->applied_by = Auth::user()->id;
            $sch_has_application_summaries->save();

            /*applied by summaries */
            $sch_has_evaluation_summaries = new ScholarHasEvaluationSummary;
            $sch_has_evaluation_summaries->application_id = $sch_has_application->id;
            $sch_has_evaluation_summaries->applied_by = Auth::user()->id;
            $sch_has_evaluation_summaries->status = "1";
            $sch_has_evaluation_summaries->save();

            DB::commit();
            return response()->json(array('success'=> true, 'messages'=>'Record successfully saved!'));
        }catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }

    }

    // public function getActiveProgram(){
    //     return ProgramService::join(connectionName('comprehensive'). '.program_services_has_departments as prog_dept', 'prog_dept.program_services_id', 'program_services.id')
    //         ->join(connectionName('iskocab'). '.scholarship_program_modules as sch_modules', 'sch_modules.program_id', 'program_services.id')
    //         ->join(connectionName('iskocab'). '.educational_attainments as educ_att', 'sch_modules.ea_id', 'educ_att.id')
    //         ->select('program_services.*', 'sch_modules.*', 'program_services.id as program_id','sch_modules.id as module_id')
    //         ->where('program_services.status', '=', '1')
    //         ->where('prog_dept.status', '=', '1')
    //         ->where('program_services.status', '=', '1')
    //         ->where('prog_dept.department_id', '=', '3')->get();
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sch_application = ScholarHasApplicationSummary::join(connectionName('iskocab').'.scholar_has_applications as application', 'application.id', 'scholar_has_application_summaries.application_id')
            ->join(connectionName('iskocab').'.scholar_has_subject_grades as grades', 'scholar_has_application_summaries.grades_id', 'grades.id')
            ->select('scholar_has_application_summaries.year_level',
                    'grades.gwa',
                    'grades.grade_list',
                    'scholar_has_application_summaries.id as summary_id',
                    'grades.id as grade_id',
                    'application.id as sch_id')
            ->where('application.id', '=', $id)->where('scholar_has_application_summaries.status', '=', '1')->first();

        $sch_application['grade_list'] = unserialize($sch_application['grade_list']);

        return $sch_application;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();

            /* get program module */
            $program_module = ScholarshipProgramModule::findOrFail($request['program_module_id']);

            /* get assistance type */
            $assistance = $this->getAssistanceType(ceil($request['gwa']), $request['program_module_id'], $request['scholar_id']);

            /* scholar has application */
            $sch_has_application = ScholarHasApplication::findOrFail($id);
            if( $request['passed'] === 'true'){ $sch_has_application->application_status = 'SUCCESS'; }
            else{ $sch_has_application->application_status = 'FAILED'; }
            $sch_has_application->save();

            /* set old data to deactivated */
            $application_summary = ScholarHasApplicationSummary::where('application_id', '=', $sch_has_application->id)->where('status', '=', '1')->first();
            $application_summary->status = '0';
            $application_summary->save();

            /* set old data to deactivated */
            $sch_grades = ScholarHasSubjectGrades::findOrFail($application_summary['grades_id']);
            $sch_grades->status = '0';
            $sch_grades->save();

            /* save scholar inputted grades */
            $sch_grades_converted = $this->comparePercentage(ceil($request['gwa']), $request['scholar_id']);
            $sch_grades = new ScholarHasSubjectGrades;
            $sch_grades->grade_list = serialize($request['grades']);
            $sch_grades->gwa = $sch_grades_converted['official_grade'];
            $sch_grades->overall_remarks = (($request['units_error'] === 'true')? 'REQUIRED UNITS NOT REACHED': (($request['passed'] === 'true')? 'PASSED':'FAILED'));
            $sch_grades->total_grade_equivalent = ($sch_grades_converted['from'] + $sch_grades_converted['to']) / 2;
            $sch_grades->status = '1';
            $sch_grades->save();


            /* save summarries */
            $sch_has_application_summaries = new ScholarHasApplicationSummary;
            $sch_has_application_summaries->application_id = $sch_has_application->id;
            $sch_has_application_summaries->assistance_id = !empty($assistance)? $assistance['id']: $application_summary['assistance_id'];
            $sch_has_application_summaries->year_level = $application_summary['year_level'];
            $sch_has_application_summaries->exam_qualification = (($program_module['required_exam'] == '1')?(($assistance['required_exam'] == '1')? 'QUALIFIED':'UNQUALIFIED'):'');
            $sch_has_application_summaries->exam_qualification_remarks = (($program_module['required_exam'] == '1')? (($assistance['required_exam']=='1')? '':'NOT REQUIRED TO TAKE EXAM'):'NO EXAM ON THIS SCHOLAR MODULE');;
            $sch_has_application_summaries->grades_id = $sch_grades->id;
            $sch_has_application_summaries->applied_by = $application_summary->applied_by;
            $sch_has_application_summaries->status = '1';
            $sch_has_application_summaries->save();

            DB::commit();

            return response()->json(array('success'=> true, 'messages'=>'Record successfully saved!'));
        }catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
