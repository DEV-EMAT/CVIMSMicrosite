<?php

namespace App\Http\Controllers\iskocab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\ProgramService;
use App\IskoCab\ScholarshipProgramModule;
use App\IskoCab\ScholarAttainmentSummary;
use App\IskoCab\AssistanceType;
use App\IskoCab\School;

class ScholarshipProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function get_active_program(Request $request){

        $educational_attainment = ScholarAttainmentSummary::where('scholar_id', '=', $request['scholar_id'])->where('status', '=', '1')->first();
        $program = ProgramService::join(connectionName('comprehensive'). '.program_services_has_departments as prog_dept', 'prog_dept.program_services_id', 'program_services.id')
                        ->join(connectionName('iskocab'). '.scholarship_program_modules as sch_modules', 'sch_modules.program_id', 'program_services.id')
                        ->leftJoin(connectionName('iskocab'). '.educational_attainments as educ_att', 'sch_modules.ea_id', 'educ_att.id')
                        ->select(
                            'program_services.id',
                            'program_services.title as program_title',
                            'program_services.description as program_description',
                            'sch_modules.id as module_id',
                            'sch_modules.accept_passing_grade',
                            'sch_modules.required_year',
                            'sch_modules.status as application_status',
                            'sch_modules.required_grade',
                            'sch_modules.number_of_units',
                            'educ_att.title as educ_attainment')
                        ->where('program_services.status', '=', '1')
                        ->where('prog_dept.status', '=', '1')
                        ->where('department_id', '=', '3')
                        ->where('sch_modules.ea_id', '=', $educational_attainment['attainment_id'])
                        // ->where('sch_modules.application_status', '=', '1')
                        ->where('sch_modules.status', '=', '1')
                        ->first();
        if($program){
            $grade_list = School::join(connectionName('iskocab') .'.scholar_has_school_summaries as school_summaries', 'school_summaries.school_id', '=', 'schools.id')
                                ->join(connectionName('iskocab').'.grading_systems as grading_systems', 'grading_systems.school_id', 'schools.id')
                                ->select('schools.id','schools.school_name','schools.address', 'grading_systems.grade_list')
                                ->where('school_summaries.scholar_id', '=', $request['scholar_id'])->where('school_summaries.status', '=', '1')->first();
            
            /* remove last 2 data */
            if($program['accept_passing_grade'] == '0'){
                $grading_system = unserialize($grade_list['grade_list']);
                
                array_splice($grading_system['official_grade'], count($grading_system['official_grade']) - 2, 2);
                array_splice($grading_system['grade_from'], count($grading_system['grade_from']) - 2, 2);
                array_splice($grading_system['grade_to'], count($grading_system['grade_to']) - 2, 2);
                array_splice($grading_system['remarks'], count($grading_system['remarks']) - 2, 2);
                
                $grade_list['grade_list'] = serialize($grading_system);
            }
        
            $assistance_type = AssistanceType::where('program_services_id', '=', $program['id'])->where('educational_attainment_id', '=', $educational_attainment['attainment_id'])->where('status', '=', '1')->get();
                                
            return json_encode(array('available_program_on_attainment' => true, 'program' => $program, 'assistance_type' => $assistance_type, 'grading_system' => unserialize($grade_list['grade_list'])));
        }else{
            return json_encode(array('available_program_on_attainment' => false, 'messages' => 'No available assistance for this type of scholar. (scholar attainment)'));
        }
    }
}
