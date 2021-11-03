<?php

namespace App\Http\Controllers\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IskoCab\School;
use App\IskoCab\GradingSystem;
use App\IskoCab\SchoolHasGradingSystemSummary;
use Gate;
use Validator;
use DB;
use Response;

class SchoolController extends Controller
{
    public function index()
    {
        return view('iskocab.school.index', ['title'=>"School Management"]);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'school_name'=>'required',
            'school_address'=>'required',
            'official_grade'=>'required',
            'grading_type' =>'required',
            'grade_from'=>'required',
            'grade_to'=>'required',
            'remarks'=>'required',
        ]);
        
        // $schcatcode ='CY-'.date('y').'-'.str_pad(GradingSystem::count() + 1, 5, "0", STR_PAD_LEFT);
        $grade_fromArray = $request['grade_from'];
        $grade_toArray = $request['grade_to'];
        
        $grade_from = $grade_to = array();

        //convert to integer
        foreach($grade_fromArray as $data){
            $grade_from[] = (int)$data;
        }
        
        //convert to integer
        foreach($grade_toArray as $data){
            $grade_to[] = (int)$data;
        }

        $min = $grade_from[0];
        $max = $grade_to[0];
        $errorGrade = true;
        for($index = 0; $index < count($grade_from); $index++){
            if($grade_from[$index] > $grade_to[$index]){
                $errorGrade = true;
                break;
            }
            if($index > 0){
                if(($grade_from[$index] >= $min && $grade_from[$index] <= $max) || ($grade_to[$index] >= $min && $grade_to[$index] <= $max)){
                    $errorGrade = true;
                    break;
                }
                else{
                    $errorGrade = false;
                }
            }
            $min = $grade_from[$index];
        }
        if($errorGrade == true){
            return response()->json(array('success' => false, 'messages'=>'Please input correct grades!'));
        }
        if($validate->fails()){
            return response()->json(array('success' => false, 'messages'=>'Validator Error!'));
        }else{
            try {
                DB::beginTransaction();
                $data = array(
                    'official_grade'=>$request['official_grade'],
                    'grade_from'=>$request['grade_from'],
                    'grade_to'=>$request['grade_to'],
                    'remarks'=>$request['remarks'],
                );
                
                $school = new School;
                $school->school_name = convertData($request["school_name"]);
                $school->address = convertData($request["school_address"]);
                $school->status = 1;
                $changes = $school->getDirty();
                $school->save();

                $grading_system = new GradingSystem;
                $grading_system->school_id = $school->id;
                $grading_system->grade_list = serialize($data);
                $grading_system->grading_type = convertData($request['grading_type']); 
                $grading_system->status = 1;
                $changes = array_merge($changes, $grading_system->getDirty());
                $grading_system->save();

                DB::commit();

                /* logs */
                action_log('School Mngt', 'CREATE', array_merge(['id' => $school->id], $changes));

                return response()->json(array('success'=> true, 'messages'=> 'Successfully Created!'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return response()->json(array('success'=> false, 'messages'=> 'SQL Error!' . $e));
            }
        }    
    }

    // public function getSchoolByScholarID($id){
    //     $scholar = Scholar::findOrFail($id);
        
    //     $school=School::findOrFail($scholar['school_id']);
    //     $grading_systems = GradingSystem::where('school_id', $scholar['school_id'])->get();
    //     foreach($grading_systems as $grading_system)
    //         $grading_system = unserialize($grading_system->grade_list);

    //     return response::json(array($school, $grading_system));
    // }

    public function show($id)
    {
        $school = School::find($id);
        
        $grading_systems = GradingSystem::where('school_id', $school->id)->where('status', '=', '1')->first();
        $grading_type = $grading_systems->grading_type;
        foreach($grading_systems as $grading_system){
            $grading_system = unserialize($grading_systems->grade_list);
        }

        return response::json(array("school"=>$school, "grading_system"=>$grading_system, "grading_type"=>$grading_type));
    }
    
    public function edit($id)
    {  
        
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'edit_school_name'=>'required',
            'edit_school_address'=>'required',
            'edit_grading_type' =>'required',
            'edit_official_grade'=>'required',
            'edit_grade_from'=>'required',
            'edit_grade_to'=>'required',
            'edit_remarks'=>'required',
        ]);
        $grade_fromArray = $request['edit_grade_from'];
        $grade_toArray = $request['edit_grade_to'];
        
        $grade_from = $grade_to = array();

        //convert to integer
        foreach($grade_fromArray as $data){
            $grade_from[] = (int)$data;
        }
        
        //convert to integer
        foreach($grade_toArray as $data){
            $grade_to[] = (int)$data;
        }

        $min = $grade_from[0];
        $max = $grade_to[0];
        $errorGrade = true;
        for($index = 0; $index < count($grade_from); $index++){
            if($grade_from[$index] > $grade_to[$index]){
                $errorGrade = true;
                break;
            }
            if($index > 0){
                if(($grade_from[$index] >= $min && $grade_from[$index] <= $max) || ($grade_to[$index] >= $min && $grade_to[$index] <= $max)){
                    $errorGrade = true;
                    break;
                }
                else{
                    $errorGrade = false;
                }
            }
            $min = $grade_from[$index];
        }
        if($errorGrade == true){
            return response()->json(array('success' => false, 'messages'=>'Please input correct grades!'));
        }
        else{
            try {
                DB::beginTransaction();

                $school = School::findOrFail($id);
                $school->school_name =  convertData($request["edit_school_name"]);
                $school->address = convertData($request["edit_school_address"]);
                $changes = $school->getDirty();
                $school->save();
                $data = array(
                    'official_grade'=>$request['edit_official_grade'],
                    'grade_from'=>$request['edit_grade_from'],
                    'grade_to'=>$request['edit_grade_to'],
                    'remarks'=>$request['edit_remarks'],
                );

                $currentGradingSystem = GradingSystem::where('school_id', $school->id)->where('status', '=', '1')->firstOrFail();
                $currentGradeList = $currentGradingSystem->grade_list;
                $currentGradeType = $currentGradingSystem->grading_type;
                $currentGradingSystem->grade_list = serialize($data);
                $currentGradingSystem->grading_type = convertData($request['edit_grading_type']); 
                $changes = array_merge($changes, $currentGradingSystem->getDirty());

                if(count($currentGradingSystem->getDirty()) > 0){
                    $grading_system = new GradingSystem;
                    $grading_system->school_id = $school->id;
                    $grading_system->grade_list = serialize($data);
                    $grading_system->grading_type = convertData($request['edit_grading_type']); 
                    $grading_system->status = 1;
                    $changes = array_merge($changes, $grading_system->getDirty());
                    $grading_system->save();

                    $currentGradingSystem->status = 0; 
                    $currentGradingSystem->grade_list = $currentGradeList;
                    $currentGradingSystem->grading_type = $currentGradeType;
                    $currentGradingSystem->save();
                }

                DB::commit();
                
                /* logs */
                action_log('School Mngt', 'UPDATE', array_merge(['id' => $school->id], $changes));

                return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    public function destroy($id)
    {
        //
    }
    
    public function findAllSchool(){
        return response()->json(School::where('status', '1')->get());
    }

    public function findAll(Request $request)
    {
        $columns = array( 
            0 => 'id',
            1 => 'school_name',
            2 => 'address',
        );

        $totalData = School::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $schools = School::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $schools =  School::where('school_name','LIKE',"%{$search}%")
                            ->orWhere('address', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = School::where('school_name','LIKE',"%{$search}%")
                             ->orWhere('address', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($schools))
        {
            foreach ($schools as $school)
            {
                $buttons = '';
                $grading_system = '';
                
                //if school is active
                if($school['status'] == '1'){
                    if(Gate::allows('permission', 'updateSchool')){
                        $buttons .= '<a title="Edit" onclick="edit('. $school['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a>';
                    }if(Gate::allows('permission', 'deleteSchool')){
                        $buttons .= ' <a onclick="deactivate('. $school['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';
                    }
                    $status = "<label class='label label-primary'>Active</label>";

                    $grading_systems = GradingSystem::where('school_id', $school->id)->get();
                    foreach($grading_systems as $grading_system)
                        $grading_system = unserialize($grading_system->grade_list);
                } else{
                    //if school is deleted
                    if(Gate::allows('permission', 'restoreSchool')){
                        $buttons .= '<a onclick="activate('. $school['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }

                    $status = "<label class='label label-danger'>Inactive</label>";
                    
                    $grading_systems = GradingSystem::where('school_id', $school->id)
                                        ->get();
                    foreach($grading_systems as $grading_system)
                        $grading_system = unserialize($grading_system->grade_list);
                }

                $buttons .= ' <a onclick="viewHistory('. $school['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate view"><i class="fa fa-list-alt"></i> VIEW HISTORY</a>';

                $nestedData['school_name'] = $school->school_name;
                $nestedData['address'] = $school->address;
                $nestedData['grading_system'] =  $grading_system;
                $nestedData['status'] = $status;
                $nestedData['actions'] = $buttons;
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

    public function viewHistory(Request $request)
    {
        $columns = array( 
            0 => 'id',
            1 => 'school_name',
            2 => 'address',
        );

        $schoolId = $request['schoolId'];

        $totalData = GradingSystem::where('school_id', '=', $schoolId)->where('status', '=', '0')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('iskocab') . '.grading_systems') 
                ->join(connectionName('iskocab') . '.schools', 'schools.id', '=', 'grading_systems.school_id')
                ->select('grading_systems.*', 'schools.school_name')
                ->where('grading_systems.school_id', '=', $schoolId)
                ->where('grading_systems.status', '=', '0');

        if(empty($request->input('search.value')))
        {            
            $schools = $query->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $schools =  $query->where('school_name','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = $schools->count();
        }

        $data = array();
        if(!empty($schools))
        {
            foreach ($schools as $school)
            {
                $buttons = '';
                $grading_system = '';
                
                $buttons .= ' <a onclick="viewHistory(  '. $school->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate view"><i class="fa fa-list-alt"></i> VIEW HISTORY</a>';
                
                $nestedData['school_name'] = $school->school_name;
                $nestedData['grading_system'] =  unserialize($school->grade_list);
                $nestedData['date_updated'] =  explode(' ', $school->updated_at)[0];
                $nestedData['time_updated'] =  explode(' ', $school->updated_at)[1];
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

    public function findAllForComboBox(){
        return response()->json(School::where('status', '=', '1')->get());
    }

    //Update Status
    public function toggleStatus($id){
        $school = School::findOrFail($id);
        $status = $school->status;

        try {
            DB::beginTransaction();
            if($status == 1){
                $school->status = 0;
                $action = 'DELETED';
            }
            else{
                $school->status = 1;
                $action = 'RESTORE';
            }
            $changes = $school->getDirty();
            $school->save();
            
            DB::commit();

            /* logs */
            action_log('School Mngt', $action, array_merge(['id' => $school->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }    
}
