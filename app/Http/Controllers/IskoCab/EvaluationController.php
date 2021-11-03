<?php

namespace App\Http\Controllers\iskocab;

use App\Comprehensive\Attendance;
use App\Http\Controllers\Controller;
use App\IskoCab\EducationalAttainmentHasRequiredEvents;
use App\IskoCab\EducationalAttainmentHasRequirements;
use App\IskoCab\ScholarHasApplication;
use App\IskoCab\ScholarHasRequirementSummary;
use App\IskoCab\ScholarHasEvaluationSummary;
use App\IskoCab\AssistanceType;
use App\IskoCab\EducationalAttainmentHasExaminationModule;
use App\IskoCab\ScholarHasApplicationSummary;
use App\IskoCab\ScholarHasEventAttendance;
use Illuminate\Http\Request;
use DB;
use Auth;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('iskocab.scholarship_program.evaluation');
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

    public function findall(Request $request){
        $columns=array(0=> 'scholars.id',
            1=> 'person_code',
            2=> 'last_name',
        );

        // $totalData = ScholarHasApplication::where('application_status', '=', 'SUCCESS')
        // ->where('evaluation_status', '=', 'FALSE')->count();

        // $totalFiltered=$totalData;

        $limit=$request->input('length');
        $start=$request->input('start');
        $order=$columns[$request->input('order.0.column')];
        $dir=$request->input('order.0.dir');

        $query = ScholarHasApplication::join(connectionName('iskocab') .'.scholars as scholars', 'scholars.id', 'scholar_has_applications.scholar_id')
            ->join(connectionName('iskocab') .'.scholar_has_application_summaries as summarries', 'summarries.application_id', 'scholar_has_applications.id')
            ->join(connectionName('iskocab') .'.scholar_has_subject_grades as grades', 'grades.id', 'summarries.grades_id')
            ->join(connectionName('iskocab') .'.scholar_has_school_summaries as school_summaries', 'school_summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.schools as schools', 'schools.id', 'school_summaries.school_id')
            ->join(connectionName('iskocab') .'.grading_systems as grading_system', 'grading_system.school_id', 'schools.id')
            ->join(connectionName('iskocab') .'.scholar_has_course_summaries as course_summaries', 'course_summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.courses as courses', 'courses.id', 'course_summaries.course_id')
            ->join(connectionName() .'.users as users', 'users.id', 'scholars.user_id')
            ->join(connectionName() .'.people as people', 'people.id', 'users.person_id')
            ->join(connectionName() .'.addresses as addresses', 'addresses.id', 'people.address_id')
            ->join(connectionName('iskocab'). '.scholarship_program_modules as sch_modules', 'sch_modules.id', 'scholar_has_applications.progmodule_id')
            ->join(connectionName('comprehensive'). '.program_services_has_departments as prog_dept', 'prog_dept.program_services_id', 'sch_modules.program_id')
            ->select(
                'summarries.id as summarries_id',
                'sch_modules.program_id as program_id',
                'scholar_has_applications.id',
                'scholar_has_applications.application_status',
                'grades.gwa',
                'grades.grade_list',
                'scholars.id as scholar_id',
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
                'grading_system.grade_list as grading_system',
                'scholar_has_applications.evaluation_status'
            )->where('scholars.status', '=', '1')
            ->where('summarries.status', '=', '1')
            ->where('prog_dept.status', '=', '1')
            ->where('grading_system.status', '=', '1')
            ->where('course_summaries.status', '=', '1')
            ->where('school_summaries.status', '=', '1');

        if(empty($request->input('search.value'))) {

            $application=$query->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

        } else {
            $search=$request->input('search.value');

            $application = $query
                ->where('people.last_name', 'LIKE', "%{$search}%")->where('summarries.status', '=', '1')
                ->orWhere('people.first_name', 'LIKE', "%{$search}%")->where('summarries.status', '=', '1')
                ->orWhere('people.middle_name', 'LIKE', "%{$search}%")->where('summarries.status', '=', '1')
                ->orWhere('people.person_code', 'LIKE', "%{$search}%")->where('summarries.status', '=', '1')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        
        $totalData = $application->count();
        $totalFiltered = $totalData;

        $data=array();

        if(!empty($application)) {
            foreach ($application as $entry) {
                $fullname = strtoupper($entry->last_name . ", ". $entry->first_name. " ". $entry->middle_name);

                if(convertData($entry->evaluation_status) == 'FALSE' && convertData($entry->application_status) == 'SUCCESS'){
                    $status = '<label class="label label-danger">UNEVALUATED</label>';
                    $buttons ='<a onclick="eval('. $entry->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="fa fa-edit"></i>&nbsp;Evaluate</a>';
                }else if(convertData($entry->evaluation_status) == 'PENDING' && convertData($entry->application_status) == 'SUCCESS'){
                    $status = '<label class="label label-warning">PENDING</label>';
                    $buttons ='<a onclick="eval('. $entry->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="fa fa-edit"></i>&nbsp;Evaluate</a>';
                }else{
                    if(convertData($entry->application_status) == 'FAILED'){
                        $status = '<label class="label label-danger">Data Submitted is Invalid.</label>';
                        $buttons = '';
                    }else{
                        /* successs and evaluation status is TRUE */
                        $status = '<label class="label label-primary">EVALUATED</label>';
                        $buttons = '<a onclick="printAssessment('. $entry->id .')" class="btn btn-xs btn-success btn-fill btn-rotate"><i class="fa fa-print"></i> Print</a>';
                    }
                }

                $achivements = ScholarHasApplication::join(connectionName('iskocab'). '.scholarship_program_modules as modules', 'modules.id', 'scholar_has_applications.progmodule_id')
                            ->join(connectionName('comprehensive'). '.program_services as program', 'program.id', 'modules.program_id')
                            ->join(connectionName('iskocab'). '.scholar_has_application_summaries as summaries', 'summaries.application_id', 'scholar_has_applications.id')
                            ->join(connectionName('iskocab'). '.assistance_types as assistance', 'assistance.id', 'summaries.assistance_id')
                            ->join(connectionName('iskocab'). '.scholar_has_subject_grades as subject', 'subject.id', 'summaries.grades_id')
                            ->select('program.title',
                                    'program.description',
                                    'subject.grade_list',
                                    'subject.gwa',
                                    'assistance.title as assistance_type'
                            )
                            ->where('summaries.status', '=', '1')
                            ->where('scholar_has_applications.scholar_id', '=', $entry->scholar_id)
                            // ->where('scholar_has_applications.application_status', '=', 'SUCCESS')
                            // ->where('scholar_has_applications.evaluation_status', '=', 'TRUE')
                            // ->where('scholar_has_applications.assessment_status', '=', 'TRUE')
                            ->where('modules.program_id', '!=', $entry->program_id )->get();



                //array data
                $nestedData['summarries_id'] = $entry->summarries_id;
                $nestedData['barcode'] = $entry->person_code;
                $nestedData['sch_info'] = $entry;
                $nestedData['fullname'] = $fullname;
                $nestedData['status'] = $status;
                $nestedData['grades'] = $entry->grade_list;
                $nestedData['grading_system'] = unserialize($entry->grading_system);
                $nestedData['gwa'] = $entry->gwa;
                $nestedData['actions'] = $buttons;
                $data[]=$nestedData;
            }

        }
        $json_data=array("draw"=> intval($request->input('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=> $data);

        echo json_encode($json_data);
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

            $requirement = json_decode($request['required_requirement'], true);
            $requirement = !empty($requirement)? $requirement:[];

            $ctr = 0;
            $event_ctr = 0;
            foreach ($requirement as $key => $value) {
                /* count error */
                if(!in_array($value['id'], (!empty($request['document'])?$request['document']:[]))){
                    $ctr++;
                }
            }

            $event = json_decode($request['required_event'], true);
            $event = !empty($event)? $event:[];

            foreach ($event as $key => $value) {
                /* count error */
                if(!in_array($value['id'], (!empty($request['event'])?$request['event']:[]))){
                    $ctr++;
                    $event_ctr++;
                }
            }

            $application = ScholarHasApplication::findOrFail($request['application_id']);
            if($ctr == 0){
                /* update application status */
                $application->evaluation_status = "TRUE";
            }else if($ctr > 0){
                /* update application status */
                $application->evaluation_status = "PENDING";
            }else{
                /* update application status */
                $application->evaluation_status = "FALSE";
            }
            $application->save();

             /* update requirements */
            $sch_has_evaluation_summaries = ScholarHasEvaluationSummary::where('application_id', '=', $request['application_id'])->where('status', '=', '1')->first();
            if(!empty($sch_has_evaluation_summaries)){
                $sch_has_evaluation_summaries->status = '0';
                $sch_has_evaluation_summaries->save();

                /* create new requirements */
                $sch_has_evaluation_summaries = new ScholarHasEvaluationSummary;
                $sch_has_evaluation_summaries->application_id = $request['application_id'];
                $sch_has_evaluation_summaries->evaluated_by = Auth::user()->id;
                $sch_has_evaluation_summaries->status = '1';
                $sch_has_evaluation_summaries->save();

            }else{
                /* create new requirements */
                $sch_has_evaluation_summaries = new ScholarHasEvaluationSummary;
                $sch_has_evaluation_summaries->application_id = $request['application_id'];
                $sch_has_evaluation_summaries->evaluated_by = Auth::user()->id;
                $sch_has_evaluation_summaries->status = '1';
                $sch_has_evaluation_summaries->save();
            }

            /* update requirements */
            $sch_has_requirement = ScholarHasRequirementSummary::where('application_id', '=', $request['application_id'])->where('status', '=', '1')->first();
            if(!empty($sch_has_requirement)){
                if($sch_has_requirement->requirement_list != serialize($request['document'])){
                    $sch_has_requirement->status = '0';
                    $sch_has_requirement->save();

                    /* create new requirements */
                    $sch_has_requirement = new ScholarHasRequirementSummary;
                    $sch_has_requirement->application_id = $request['application_id'];
                    $sch_has_requirement->requirement_list = serialize($request['document']);
                    $sch_has_requirement->status = 1;
                    $sch_has_requirement->save();
                }
            }else{
                /* create new requirements */
                $sch_has_requirement = new ScholarHasRequirementSummary;
                $sch_has_requirement->application_id = $request['application_id'];
                $sch_has_requirement->requirement_list = serialize($request['document']);
                $sch_has_requirement->status = 1;
                $sch_has_requirement->save();
            }

            /* update events */
            $sch_has_event = ScholarHasEventAttendance::where('application_id', '=', $request['application_id'])->where('status', '=', '1')->first();
            if(!empty($sch_has_event)){
                if(!empty(json_decode($request['exempt_event'], true))){

                    /* create new event */
                    if($sch_has_event['attendace_status'] == 'INCOMPLETE'){

                        $sch_has_event->status = '0';
                        $sch_has_event->save();

                        $sch_has_event_new = new ScholarHasEventAttendance;
                        $sch_has_event_new->application_id = $request['application_id'];
                        $sch_has_event_new->attendance_history =  serialize(array_merge(json_decode($request['exempt_event'], true), unserialize($sch_has_event['attendance_history'])));
                        $sch_has_event_new->attendace_status = ($event_ctr == 0)?'COMPLETE':'INCOMPLETE';
                        $sch_has_event_new->remarks = '';
                        $sch_has_event_new->status = 1;
                        $sch_has_event_new->save();
                    }
                    // else{
                        // $sch_has_event_new->attendance_history = $sch_has_event['attendance_history'];
                        // $sch_has_event_new->attendace_status = $sch_has_event['attendace_status'];
                    // }

                }
            }else{

                /* create new event */
                $sch_has_event = new ScholarHasEventAttendance;
                $sch_has_event->application_id = $request['application_id'];
                $sch_has_event->attendance_history = serialize(json_decode($request['exempt_event'], true));
                $sch_has_event->attendace_status = ($event_ctr == 0)?'COMPLETE':'INCOMPLETE';
                $sch_has_event->remarks = '';
                $sch_has_event->status = 1;

                //save if data not null
                if(!empty(json_decode($request['exempt_event'], true))){
                    $sch_has_event->save();
                }
            }

            /* exam exemption */
            if(!empty(json_decode($request['exempt_exam_reason'], true))){
                $application_summary = ScholarHasApplicationSummary::where('application_id', '=', $request['application_id'])->where('status', '=', '1')->first();
                $application_summary->exam_qualification = 'EXEMPTION';
                $application_summary->exam_qualification_remarks = convertData(json_decode($request['exempt_exam_reason'], true));
                $application_summary->save();
            }

            DB::commit();
            return response()->json(array('success'=> true, 'messages'=>'Record successfully saved!'));
        }catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = ScholarHasApplication::join(connectionName('iskocab') .'.scholars as scholars', 'scholars.id', '=', 'scholar_has_applications.scholar_id')
            ->join(connectionName('iskocab') .'.scholar_has_application_summaries as summarries', 'summarries.application_id', '=', 'scholar_has_applications.id')
            ->join(connectionName('iskocab') .'.scholar_attainment_summaries as att_summarries', 'att_summarries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.scholar_has_subject_grades as grades', 'grades.id', '=', 'summarries.grades_id')
            ->join(connectionName('iskocab') .'.scholarship_program_modules as modules', 'modules.id', '=', 'scholar_has_applications.progmodule_id')
            ->join(connectionName('iskocab') .'.scholar_has_school_summaries as school_summaries', 'school_summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.schools as schools', 'schools.id', '=', 'school_summaries.school_id')
            ->join(connectionName('iskocab') .'.grading_systems as grading_system', 'grading_system.school_id', 'schools.id')
            ->join(connectionName('iskocab') .'.scholar_has_course_summaries as course_summaries', 'course_summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.courses as courses', 'courses.id', '=', 'course_summaries.course_id')
            ->join(connectionName() .'.users as users', 'users.id', '=', 'scholars.user_id')
            ->join(connectionName() .'.people as people', 'people.id', '=', 'users.person_id')
            ->join(connectionName() .'.addresses as addresses', 'addresses.id', '=', 'people.address_id')
            ->select(
                'summarries.exam_qualification',
                'scholar_has_applications.id',
                'grades.gwa',
                'grades.grade_list',
                'scholars.id as scholar_id',
                'people.last_name',
                'people.first_name',
                'people.middle_name',
                'people.person_code',
                'courses.course_description',
                'schools.school_name',
                'users.contact_number',
                'users.id as user_id',
                'people.date_of_birth',
                'addresses.barangay',
                'people.address',
                'people.image',
                'att_summarries.attainment_id',
                'modules.required_grade',
                'modules.id as module_id',
                'modules.required_exam',
                'modules.required_requirements',
                'modules.required_event',
                'modules.program_id',
                'modules.number_of_units',
                'grading_system.grade_list as grading_system',
            )->where('scholars.status', '=', 1)
            ->where('summarries.status', '=', 1)
            ->where('att_summarries.status', '=', 1)
            // ->where('scholar_has_applications.application_status', '=', 'SUCCESS')
            ->where('scholar_has_applications.id', '=', $id)
            ->where('school_summaries.status', '=', '1')
            ->where('grading_system.status', '=', 1)
            ->where('course_summaries.status', '=', 1)
            // ->where('scholar_has_applications.evaluation_status', '=', 'FALSE')
            ->first();

        /* check required exam */
        if($application['required_exam'] == '1'){
            $exam = EducationalAttainmentHasExaminationModule::join(connectionName('comprehensive'). '.exam_titles', 'exam_titles.id', 'educational_attainment_has_examination_modules.exam_id')
                    ->select('exam_titles.title')
                    ->where('educational_attainment_has_examination_modules.prog_id', '=', $application->program_id)
                    ->where('educational_attainment_has_examination_modules.ea_id', '=', $application->attainment_id)
                    ->where('educational_attainment_has_examination_modules.status', '=', '1')
                    ->first();

            $application->examination = $exam;
        }

        /* check required requirements */
        if($application['required_requirements'] == '1'){
            $requirements = EducationalAttainmentHasRequirements::join(connectionName('comprehensive') .'.requirements as requirements', 'requirements.id', '=', 'educational_attainment_has_requirements.requirement_id')
                    ->select('requirements.name','requirements.description', 'requirements.id')
                    ->where('program_id', '=', $application->program_id)
                    ->where('ea_id', '=', $application->attainment_id)
                    ->where('educational_attainment_has_requirements.status', '=', 1)->get();


            $documents_summitted = ScholarHasRequirementSummary::where('application_id', '=', $application->id)->where('status', '=', '1')->first();
            if(!empty($documents_summitted)){
                $documents_summitted = (!empty(unserialize($documents_summitted['requirement_list'])))? unserialize($documents_summitted['requirement_list']) : [];
            }else{
                $documents_summitted = [];
            }

            $req_ctr_false = 0;
            foreach ($requirements as $key => $value) {
                if(in_array($value['id'], $documents_summitted)){
                    $requirements[$key]->submitted = true;
                }else{
                    $requirements[$key]->submitted = false;
                    $req_ctr_false++;
                }
            }

            $application->requirement = $requirements;
            $application->requirement_status = ($req_ctr_false == 0)? 'COMPLETE':'INCOMPLETE';
        }

        /* check required event */
        if($application['required_event'] == '1'){
            $events = EducationalAttainmentHasRequiredEvents::join(connectionName('comprehensive') .'.events as events', 'events.id', '=', 'educational_attainment_has_required_events.event_id')
                    ->join(connectionName('comprehensive') .'.event_summaries as summaries', 'summaries.event_id', '=', 'events.id')
                    ->select('events.title','events.description', 'events.id', 'summaries.date_of_event')
                    ->where('program_id', '=', $application->program_id)
                    ->where('ea_id', '=', $application->attainment_id)
                    ->where('educational_attainment_has_required_events.status', '=', 1)
                    ->where('summaries.status', '=', 1)->get();


            $event_ctr_false = 0;
            foreach ($events as $key => $value) {
                $event_attended = Attendance::join(connectionName('comprehensive').'.event_has_attendances as event_attendance', 'event_attendance.attendances_id', 'attendances.id')
                        ->select('attendances.title', 'event_attendance.created_at', 'event_attendance.updated_at', 'event_attendance.attendee_remarks')
                        ->where('event_attendance.user_id', '=', $application->user_id)
                        ->where('attendances.event_id', '=', $value['id'])
                        ->first();

                /* check in attendance in attended */
                if($event_attended){
                    $events[$key]->attended = true;
                    $events[$key]['in'] = date('h:i:s a', strtotime($event_attended['created_at']));
                    $events[$key]['out'] = date('h:i:s a', strtotime($event_attended['created_at']));
                    $events[$key]['attendee_remarks'] = $event_attended['attendee_remarks'];
                }else{
                    /* check if event is exempted */
                    $exempt_events = ScholarHasEventAttendance::where('application_id', '=', $application->id)->where('status', '=', '1')->first();
                    if(!empty($exempt_events)){
                        foreach (unserialize($exempt_events['attendance_history']) as $exemption) {
                            if(!empty($exemption)){
                                if($value['id'] == $exemption['event_id']){
                                    $events[$key]->attended = true;
                                    $events[$key]['in'] = '';
                                    $events[$key]['out'] = '';
                                    $events[$key]['attendee_remarks'] = "EXEMPTED";
                                }
                            }
                        }
                    }
                }
            }

            $application->events = $events;
            $application->event_status = ($event_ctr_false == 0)? 'COMPLETE':'INCOMPLETE';
        }

        $assistance_type = AssistanceType::where('program_services_id', '=', $application['program_id'])->where('educational_attainment_id', '=', $application['attainment_id'])->where('status', '=', '1')->get();

        $application->grade_list = unserialize($application->grade_list);
        $application->grading_system = unserialize($application->grading_system);
        $application->assistance_type = $assistance_type;
        return $application;
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
        //
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
