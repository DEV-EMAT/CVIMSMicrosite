<?php

namespace App\Http\Controllers\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\ProgramService;
use App\Comprehensive\ProgramServicesHasDepartment;
use App\IskoCab\ScholarshipProgramModule;
use App\IskoCab\AssistanceType;
use App\IskoCab\EducationalAttainment;
use App\IskoCab\ScholarHasApplicationSummary;
use App\IskoCab\EducationalAttainmentHasExaminationModule;
use App\IskoCab\EducationalAttainmentHasAssistanceType;
use App\IskoCab\EducationalAttainmentHasRequiredEvents;
use App\IskoCab\EducationalAttainmentHasRequirements;
use App\Comprehensive\Examination;
use App\Comprehensive\ExamTitle;
use App\Comprehensive\Requirement;
use App\Comprehensive\Event;
use DB;
use Gate;
use Response;
use PDF;

class ScholarshipProgramModuleController extends Controller
{
    public function index()
    {
        return view('iskocab.program.create_program' , ['title' => "Program Management"]);
    }

    public function findAll(Request $request)
    {
        $columns = array(
            0 => 'program_services.title',
            1 => 'program_services.title',
            // 1 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('comprehensive').'.program_services')
                ->join(connectionName('iskocab').'.scholarship_program_modules', 'scholarship_program_modules.program_id', '=', 'program_services.id')
                ->join(connectionName('comprehensive').'.program_services_has_departments', 'program_services_has_departments.program_services_id', '=', 'program_services.id')
                ->join(connectionName('mysql').'.departments', 'departments.id', '=', 'program_services_has_departments.department_id')
                ->select('program_services.*', 'departments.id AS deptId', 'departments.department', 'departments.acronym', 'program_services_has_departments.status AS progDeptStatus')->distinct('program_services.id');

        //datatables total data
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $programs = $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
        }
        else {
            $search = $request->input('search.value');

            $query =  $query->where('program_services.title', 'like', "%{$search}%");

            $programs =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();

        if(!empty($programs))
        {

            $isProgramActive = false;

            foreach ($programs as $program)
            {
                $assistanceTypes = array();

                $applicationStatus = "<label class='label label-danger'>Not Available</label>";
                $scholarshipProgramModule = DB::table(connectionName('iskocab').'.scholarship_program_modules')
                                            ->join(connectionName('iskocab').'.educational_attainments', 'educational_attainments.id', '=', 'scholarship_program_modules.ea_id')
                                            ->select('scholarship_program_modules.*', 'educational_attainments.title')
                                            ->where('scholarship_program_modules.program_id', '=', $program->id)
                                            ->distinct('educational_attainments.title')
                                            ->get();

                if($scholarshipProgramModule[0]->application_status == "1"){
                    $applicationStatus = "<label class='label label-primary'>Available</label>";
                }


                //check program has department if active
                if($program->progDeptStatus == '1'){
                    $btnApp = 'enable';
                    $status = "<label class='label label-primary'>OPEN</label>";
                }else{
                    $btnApp = 'disabled';
                    $status = "<label class='label label-danger'>CLOSED</label>";
                }


                //check if there is active program
                $activeProgramId = "";
                $activePrograms = ProgramServicesHasDepartment::where('department_id', '=', $program->deptId)->get();

                if($activePrograms){
                    foreach($activePrograms as $activeProgram){
                        if($activeProgram->status == "1"){
                            $activeProgramId = $activeProgram->program_services_id;
                            $isProgramActive = true;
                        }
                    }
                }

                //check if there is active program
                $btnProg = 'disabled';
                if($isProgramActive == false){
                    $btnProg = 'enable';
                }

                if($activeProgramId != ""){
                    if($activeProgramId == $program->id){
                        $btnProg = 'enable';
                    }
                }
                $buttons = "";
                if(Gate::allows('permission', 'deleteScholarProgram') || Gate::allows('permission', 'restoreScholarProgram') || Gate::allows('permission', 'printScholarProgram')){
                    $buttons = '<div class="dropdown">
                                    <button href="#" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Actions
                                        <b class="caret"></b>
                                    </button>
                                    <ul class="dropdown-menu">';
                    if(Gate::allows('permission', 'deleteScholarProgram') || Gate::allows('permission', 'restoreScholarProgram')){
                        $buttons .= '<li class="' . $btnProg . '"><a onclick="changeProgramStatus('. $program->id .')">Change Program Status</a></li>';
                    }
                    if(Gate::allows('permission', 'printScholarProgram')){
                        $buttons.='<li><a onclick="printProgram('. $program->id .')" class="btn-rotate"><i class="fa fa-print"></i> Print</a></li>';
                    }
                    $buttons .= '</ul></div>';
                }
                $modules = '';

                for($index = 0; $index < count($scholarshipProgramModule); $index++){
                    // dd($scholarshipProgramModule[$counter]->title);
                    $modules .= $scholarshipProgramModule[$counter]->title;

                    if($index+1 < count($scholarshipProgramModule))
                        $modules .= "/ ";
                }
                $department = $program->acronym;
                if($program->acronym == ""){
                    $department = $program->department;
                }
                $nestedData['id'] = $program->id;
                $nestedData['title'] = $program->title;
                $nestedData['department'] = $department;
                $nestedData['programStatus'] = $program->progDeptStatus;
                $nestedData['status'] = $status;
                $nestedData['modules'] = $modules;
                $nestedData['applicationStatus'] = $applicationStatus;
                $nestedData['actions'] = $buttons;
                $nestedData['scholarshipProgramModule'] = $scholarshipProgramModule;
                // $nestedData['assistanceTypes'] = $assistanceTypes;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $programModule = $request['programModule'];
        try {
            DB::beginTransaction();

            $programService = new ProgramService;
            $programService->title = convertData($request['programTitle']);
            $programService->description = $request['programDescription'];
            $programService->status = "1";
            $programService->save();

            //fixed department of cyda (department id = 3)
            $programServiceDepartment = new ProgramServicesHasDepartment;
            $programServiceDepartment->program_services_id = $programService->id;
            $programServiceDepartment->department_id = 3;
            $programServiceDepartment->status = "0";
            $programServiceDepartment->save();

            foreach ($programModule as $program) {
                $requiredExam = $requiredRequirement = $requiredEvent = $requiredGrade =  0;
                $eaId = $program["module"]["ea_id"];

                if(!empty($program["exam"])){
                    $requiredExam = 1;
                }
                if(!empty($program["requirement"])){
                    $requiredRequirement = 1;
                }
                if(!empty($program["event"])){
                    $requiredEvent = 1;
                }
                if(!empty($program["grade"])){
                    $requiredGrade = 1;
                }

                $programModule = new ScholarshipProgramModule;
                $programModule->program_id = $programService->id;
                $programModule->ea_id = $eaId;
                $programModule->number_of_units	= $program["requiredUnits"];
                $programModule->required_grade = $requiredGrade;
                $programModule->required_exam = $requiredExam;
                $programModule->required_requirements = $requiredRequirement;
                $programModule->required_event = $requiredEvent;
                $programModule->required_year = $program["yearLevel"];
                $programModule->accept_passing_grade = $program["passingGrade"];
                $programModule->application_status = "0";
                $programModule->status = "1";
                $programModule->save();

                //exam is required
                if($requiredExam == 1){
                    foreach($program["exam"] as $exam){
                        $educationalAttainmenthasExam = new EducationalAttainmentHasExaminationModule;
                        $educationalAttainmenthasExam->exam_id = $exam["id"];
                        $educationalAttainmenthasExam->prog_id = $programService->id;
                        $educationalAttainmenthasExam->ea_id = $eaId;
                        $educationalAttainmenthasExam->status = "1";
                        $educationalAttainmenthasExam->save();
                    }
                }

                //event is required
                if($requiredEvent == 1){
                    foreach($program["event"] as $event){
                        $educationalAttainmenthasEvents = new EducationalAttainmentHasRequiredEvents;
                        $educationalAttainmenthasEvents->ea_id = $eaId;
                        $educationalAttainmenthasEvents->program_id = $programService->id;
                        $educationalAttainmenthasEvents->event_id = $event["eventId"];
                        $educationalAttainmenthasEvents->status = "1";
                        $educationalAttainmenthasEvents->save();
                    }
                }

                //requirement is required
                if($requiredRequirement == 1){
                    foreach($program["requirement"] as $requirement){
                        $educationalAttainmenthasRequirements = new EducationalAttainmentHasRequirements;
                        $educationalAttainmenthasRequirements->program_id = $programService->id;
                        $educationalAttainmenthasRequirements->ea_id = $eaId;
                        $educationalAttainmenthasRequirements->requirement_id = $requirement["requirementId"];
                        $educationalAttainmenthasRequirements->status = "1";
                        $educationalAttainmenthasRequirements->save();
                    }
                }

                if($requiredGrade == 1){
                    $isExamExist = false;
                    //find last index of grade with required exam
                    if($requiredExam == 1){
                        for($indexExam = count($program["grade"])-1; $indexExam >= 0; $indexExam--){
                            if($program["grade"][$indexExam]["required_exam"] == "1"){
                                $isExamExist = true;
                                $indexExam += 1;
                                break;
                            }
                        }
                    }

                    for($index = 0; $index< count($program["grade"]); $index++){
                        $assistanceType = new AssistanceType;
                        $assistanceType->title = $program["grade"][$counter]["cat_name"];
                        $assistanceType->program_services_id = $programService->id;
                        $assistanceType->educational_attainment_id = $eaId;
                        $assistanceType->grade_from = $program["grade"][$counter]["grade_from"];
                        $assistanceType->grade_to = $program["grade"][$counter]["grade_to"];
                        if($requiredExam == 1){
                            if($isExamExist == true){
                                if($index < $indexExam){
                                    $assistanceType->required_exam = "1";
                                }else{
                                    $assistanceType->required_exam = "0";
                                }
                            }
                        }else{
                            $assistanceType->required_exam = "0";
                        }
                        $assistanceType->status = '1';
                        $assistanceType->save();
                    }

                    // foreach($program["grade"] as $grade){
                    //     $assistanceType = new AssistanceType;
                    //     $assistanceType->title = $grade["cat_name"];
                    //     $assistanceType->program_services_id = $programService->id;
                    //     $assistanceType->educational_attainment_id = $eaId;
                    //     $assistanceType->grade_from = $grade["grade_from"];
                    //     $assistanceType->grade_to = $grade["grade_to"];
                    //     if($requiredExam == 1){
                    //         $assistanceType->required_exam = $grade["required_grade"];
                    //     }else{
                    //         $assistanceType->required_exam = "0";
                    //     }
                    //     $assistanceType->status = '1';
                    //     $assistanceType->save();
                    // }
                }else{
                    //if grade is not required, add default value of grade
                    $assistanceType = new AssistanceType;
                    $assistanceType->title = "EA1";
                    $assistanceType->program_services_id = $programService->id;
                    $assistanceType->educational_attainment_id = $eaId;
                    $assistanceType->grade_from = 0;
                    $assistanceType->grade_to = 100;
                    if($requiredExam == 1){
                        $assistanceType->required_exam = "1";
                    }else{
                        $assistanceType->required_exam = "0";
                    }
                    $assistanceType->status = '1';
                    $assistanceType->save();
                }
            }

            DB::commit();

            /* logs */
            // action_log('Requirement Mngt', 'CREATE', array_merge(['id' => $requirement->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function show($id)
    {
        $scholarshipProgramModule = ScholarshipProgramModule::findOrFail($id);
        $eaId = $scholarshipProgramModule->ea_id;
        $educationalAttainment = EducationalAttainment::findOrFail($scholarshipProgramModule->ea_id);
        $assistanceType = $examTitle = "";
        $requirements = array();
        $events = array();

        if($scholarshipProgramModule->required_grade == 1){
            $assistanceType = AssistanceType::where('program_services_id', '=', $scholarshipProgramModule->program_id)->where('educational_attainment_id', '=', $eaId)->get();
        }

        if($scholarshipProgramModule->required_exam == 1){
            $educationalAttainmenthasExam = EducationalAttainmentHasExaminationModule::where('prog_id', '=', $scholarshipProgramModule->program_id)->where('ea_id', '=', $eaId)->first();
            $exam = ExamTitle::findOrFail($educationalAttainmenthasExam->exam_id);
            $examTitle = $exam->title;
        }


        if($scholarshipProgramModule->required_requirements == 1){
            $educationalAttainmenthasRequirements = EducationalAttainmentHasRequirements::where('program_id', '=', $scholarshipProgramModule->program_id)->where('ea_id', '=', $eaId)->get();
            foreach($educationalAttainmenthasRequirements as $data){
                $requirement = Requirement::where('id', '=', $data->requirement_id)->first();
                $requirements[] = $requirement->name;
            }
        }

        if($scholarshipProgramModule->required_event == 1){
            $educationalAttainmenthasEvents = EducationalAttainmentHasRequiredEvents::where('program_id', '=', $scholarshipProgramModule->program_id)->where('ea_id', '=', $eaId)->get();
            foreach($educationalAttainmenthasEvents as $data){
                $event = Event::where('id', '=', $data->event_id)->first();
                $events[] = $event->title;
            }
        }

        return response()->json(array("scholarshipProgramModule" => $scholarshipProgramModule, "educationalAttainment" => $educationalAttainment, "assistanceType" => $assistanceType, "examTitle" => $examTitle, "requirements" => $requirements, "events" => $events));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    //Update Application Status
    public function toggleApplicationStatus($id){
        $scholarshipProgramModule = ScholarshipProgramModule::where('program_id', '=', $id)->get();

        try {
            DB::beginTransaction();

            foreach($scholarshipProgramModule as $programModule){
                if($programModule->application_status == 1){
                    $programModule->application_status = 0;
                    $action = 'DELETED';
                }
                else{
                    $programModule->application_status = 1;
                    $action = 'RESTORE';
                }
                $changes = $programModule->getDirty();
                $programModule->save();
            }

            DB::commit();

            /* logs */
            // action_log('School Mngt', $action, array_merge(['id' => $school->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    //Update Program Module Status
    public function toggleProgramStatus($id){
        $programService = ProgramServicesHasDepartment::where('program_services_id', '=', $id)->get();

        try {
            DB::beginTransaction();

            foreach($programService as $service){
                if($service->status == 1){
                    $service->status = 0;

                    //deactivate if application is available
                    $scholarshipProgramModule = ScholarshipProgramModule::where('program_id', '=', $service->program_services_id)->get();
                    foreach($scholarshipProgramModule as $programModule){
                        $programModule->application_status = 0;
                        $programModule->save();
                    }
                    $action = 'DELETED';
                }
                else{
                    $service->status = 1;
                    $action = 'RESTORE';
                }
                $changes = $service->getDirty();
                $service->save();
            }

            DB::commit();

            /* logs */
            // action_log('School Mngt', $action, array_merge(['id' => $school->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }

    }

    //Update Assistance Module Status
    public function toggleAssistanceStatus($id){
        $scholarshipProgramModule = ScholarshipProgramModule::where('id', '=', $id)->first();
        // dd($scholarshipProgramModule);

        try {
            DB::beginTransaction();

            // foreach($scholarshipProgramModule as $programModule){
                if($scholarshipProgramModule->application_status == 1){
                    $scholarshipProgramModule->application_status = 0;
                    $action = 'DELETED';
                }
                else{
                    $scholarshipProgramModule->application_status = 1;
                    $action = 'RESTORE';
                }
                $changes = $scholarshipProgramModule->getDirty();
                $scholarshipProgramModule->save();
            // }

            DB::commit();

            /* logs */
            // action_log('School Mngt', $action, array_merge(['id' => $school->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function printProgram($id){
        $programService = ProgramService::where('id', '=', $id)->first();
        $scholarshipProgramModule = ScholarshipProgramModule::where('program_id', '=', $id)->get();

        // foreach($scholarshipProgramModule as $programModule){
        //     dd($programModule);
        // }

        $counter = 1;

        $pdf=new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);
        // set document information
        PDF::SetTitle($counter);
        $counter++;
        // PDF::SetAuthor('Cabuyao Youth Development Affairs');

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        PDF::SetFont('times', '', 12, '', true);
        define ("pdf_page_format", "letter");
        // Add a page
        // This method has several options, check the source code documentation for more information.
        PDF::AddPage();

            $style=array('position'=> '',
                'align'=> '',
                'stretch'=> false,
                'fitwidth'=> true,
                'cellfitalign'=> '',
                'border'=> true,
                'hpadding'=> 'auto',
                'vpadding'=> 'auto',
                'fgcolor'=> array(0, 0, 0),
                'bgcolor'=> false, //array(255,255,255),
                'text'=> true,
                'font'=> 'times',
                'fontsize'=> 8,
                'stretchtext'=> 4);
            $style['position']='R';
            //$pdf->Ln();

            //$header = HTML::image('assets/image/ass1.png');



            $table='<div align="left" style="margin: 0px; padding: 0px;"><img alt="test alt attribute"   style="margin: 0px; padding: 0px; width: 500px;"/></div>';

            // output the HTML content
            PDF::writeHTML($table, true, false, true, false, '');


            // PDF::Cell(110, 5, '');
            // PDF::Cell(50, 5, PDF::write1DBarcode($trans, 'C128B', '', 12, 105, 18, 0.4, $style, 'M'));
            // PDF::Ln(9);


            // output the HTML content
            //PDF::writeHTML($html, true, false, true, false, '');
            PDF::Cell(0, 5, 'PROGRAM : ' . $programService->title);
            PDF::Ln(1);
            PDF::Cell(50, 5, '_________________________________________________________________________________________');
            PDF::Ln(6);

        // foreach($scholarshipProgramModule as $programModule){
        for($counter = 0; $counter < count($scholarshipProgramModule); $counter++){
            if($counter > 0 && $counter%2 == 0){
                PDF::AddPage();
                PDF::Cell(50, 5, '_________________________________________________________________________________________');
                PDF::Ln(6);
            }
            $totalApplied = $totalEvaluated = $totalAssessed = 0;
            $eaId = $scholarshipProgramModule[$counter]->ea_id;
            $educationalAttainment = EducationalAttainment::findOrFail($scholarshipProgramModule[$counter]->ea_id);
            $assistanceType = $examTitle = "";
            $requirements = array();
            $events = array();
            $isExamRequired = "NO";
            $txtRequirements = 'NONE';
            $txtEvents = 'NONE';
            $passingGrade = "NOT REQUIRED";
            $yearLevel = "NOT REQUIRED";

            if($scholarshipProgramModule[$counter]->required_exam == 1){
                $isExamRequired = "YES";
            }

            if($scholarshipProgramModule[$counter]->accept_passing_grade == 1){
                $passingGrade = "REQUIRED";
            }

            if($scholarshipProgramModule[$counter]->required_year == 1){
                $yearLevel = "REQUIRED";
            }

            // if($scholarshipProgramModule[$counter]->required_grade == 1){
                $assistanceType = AssistanceType::where('program_services_id', '=', $scholarshipProgramModule[$counter]->program_id)->where('educational_attainment_id', '=', $eaId)->get();
            // }

            if($scholarshipProgramModule[$counter]->required_exam == 1){
                $educationalAttainmenthasExam = EducationalAttainmentHasExaminationModule::where('prog_id', '=', $scholarshipProgramModule[$counter]->program_id)->where('ea_id', '=', $eaId)->first();
                $exam = ExamTitle::findOrFail($educationalAttainmenthasExam->exam_id);
                $examTitle = $exam->title;
            }

            if($scholarshipProgramModule[$counter]->required_requirements == 1){
                $educationalAttainmenthasRequirements = EducationalAttainmentHasRequirements::where('program_id', '=', $scholarshipProgramModule[$counter]->program_id)->where('ea_id', '=', $eaId)->get();
                foreach($educationalAttainmenthasRequirements as $data){
                    $requirement = Requirement::where('id', '=', $data->requirement_id)->first();
                    $requirements[] = $requirement->name;
                }
                $txtRequirements = '';
                for($index = 0; $index < count($requirements); $index++){
                    if($index == 0){
                        $txtRequirements .= $requirements[$index];
                    }else{
                        $txtRequirements .= ',  ' . $requirements[$index];
                    }
                }
            }

            if($scholarshipProgramModule[$counter]->required_event == 1){
                $educationalAttainmenthasEvents = EducationalAttainmentHasRequiredEvents::where('program_id', '=', $scholarshipProgramModule[$counter]->program_id)->where('ea_id', '=', $eaId)->get();
                foreach($educationalAttainmenthasEvents as $data){
                    $event = Event::where('id', '=', $data->event_id)->first();
                    $events[] = $event->title;
                }

                $txtEvents = '';
                for($index = 0; $index < count($events); $index++){
                    if($index == 0){
                        $txtEvents .= $events[$index];
                    }else{
                        $txtEvents .= ',  ' . $events[$index];
                    }
                }
            }

            // Module
            PDF::Cell(16, 5, 'Module:');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $educationalAttainment->title );
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Event
            PDF::Cell(30, 5, 'Event Required: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $txtEvents);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Requirements
            PDF::Cell(30, 5, 'Requirements:');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $txtRequirements);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Exam
            PDF::Cell(30, 5, 'Exam Required: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $isExamRequired);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Required Units
            PDF::Cell(30, 5, 'Required Units: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $scholarshipProgramModule[$counter]->number_of_units);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Year level
            PDF::Cell(30, 5, 'Year Level: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $yearLevel);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Accept Passing Grade
            PDF::Cell(45, 5, 'Accept Passing Grade: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $passingGrade);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // GRADES
            PDF::SetFont('Times', '', 12);
            PDF::Cell(30, 5, 'Grades:');

            if($assistanceType){
                PDF::Ln(6);
                PDF::SetFont('Times', '', 12);
                PDF::Cell(15, 5, '');
                PDF::SetFont('Times', '0', 12);
                PDF::Cell(40, 5, 'Category Name');
                PDF::Cell(40, 5, 'Grade(From)');
                PDF::Cell(40, 5, 'Grade(To)');
                PDF::Cell(40, 5, 'Required Exam');
                PDF::Ln(6);
                foreach($assistanceType as $assistance){
                    //$person->image = !empty($filename)?$filename : 'ecabs/profiles/default-avatar.png';
                    $exam = $assistance->required_exam=="1"?"YES" :" NO";
                    PDF::SetFont('Times', '', 12);
                    PDF::Cell(20, 5, '');
                    PDF::SetFont('Times', '0', 12);
                    PDF::Cell(42, 5, $assistance->title);
                    PDF::Cell(40, 5, $assistance->grade_from);
                    PDF::Cell(40, 5, $assistance->grade_to);
                    PDF::Cell(40, 5, $exam);
                    PDF::Ln(6);
                }

                // SCHOLARS
                PDF::SetFont('Times', '', 12);
                PDF::Cell(30, 5, 'Scholars:');
                PDF::Ln(6);
                PDF::SetFont('Times', '', 12);
                PDF::Cell(15, 5, '');
                PDF::SetFont('Times', '0', 12);
                PDF::Cell(40, 5, 'Category Name');
                PDF::Cell(40, 5, 'Applied');
                PDF::Cell(40, 5, 'Evaluated');
                PDF::Cell(40, 5, 'Assessed');
                PDF::Ln(6);

                foreach($assistanceType as $assistance){
                    $applied = $evaluated = $assessed = 0;
                    // $scholarHasApplicationSummaries = ScholarHasApplicationSummary::where('id', '=', $assistance->id)->first();
                    $scholarHasApplicationSummaries = DB::table(connectionName('iskocab').'.scholar_has_application_summaries')
                        ->join(connectionName('iskocab').'.scholar_has_applications', 'scholar_has_applications.id', '=', 'scholar_has_application_summaries.application_id')
                        ->select('scholar_has_applications.*')
                        ->where('scholar_has_application_summaries.assistance_id', '=', $assistance->id);

                    $applied = $scholarHasApplicationSummaries->where('scholar_has_applications.application_status', '=', 'SUCCESS')->count();
                    $evaluated = $scholarHasApplicationSummaries->where('scholar_has_applications.evaluation_status', '=', 'TRUE')->count();
                    $assessed = $scholarHasApplicationSummaries->where('scholar_has_applications.assessment_status', '=', 'TRUE')->count();
                    $totalApplied += $applied;
                    $totalEvaluated += $evaluated;
                    $totalAssessed += $assessed;

                    PDF::SetFont('Times', '', 12);
                    PDF::Cell(20, 5, '');
                    PDF::SetFont('Times', '0', 12);
                    PDF::Cell(43, 5, $assistance->title);
                    PDF::Cell(43, 5, $applied);
                    PDF::Cell(43, 5, $evaluated);
                    PDF::Cell(43, 5, $assessed);
                    PDF::Ln(6);

                }
                PDF::Cell(20, 5, '');
                PDF::SetFont('Times', 'B', 12);
                PDF::Cell(43, 5, 'Total');
                PDF::Cell(43, 5, $totalApplied);
                PDF::Cell(43, 5, $totalEvaluated);
                PDF::Cell(43, 5, $totalAssessed);
                PDF::Ln(6);
            }
            else{
                PDF::SetFont('Times', 'B', 12);
                PDF::Cell(30, 5, 'NOT REQUIRED');
                PDF::Ln(6);
            }

            PDF::Cell(55, 5, '_________________________________________________________________________________________');
            PDF::Ln(6);
        }

            PDF::SetFont('Times', 'B', 8);
            PDF::Cell(140, 5, '');
            PDF::Cell(50, 5, 'This copy is system generated document.');
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            //=============second copy



            // Close and output PDF document
            // This method has several options, check the source code documentation for more information.
            PDF::Output($eaId.'.pdf', 'I');
            exit;
            //============================================================+
            // END OF FILE
            //============================================================+
        // return response()->json(array("scholarshipProgramModule" => $scholarshipProgramModule, "educationalAttainment" => $educationalAttainment, "assistanceType" => $assistanceType, "examTitle" => $examTitle, "requirements" => $requirements, "events" => $events));
    }
}
