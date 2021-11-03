<?php

namespace App\Http\Controllers\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IskoCab\Course;
use Response;
use DB;
use Gate;

class CourseController extends Controller
{
    public function index()
    {
        return view('iskocab.course.index', ['title' => "Course Management"]);
    }

    public function findall(Request $request)
    {
        $columns = array( 
            0 =>'course_code', 
            1 =>'course_description',
            2=> 'status',
            3=> 'actions',
        );

        $totalData = Course::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $courses = Course::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $courses =  Course::where('course_code','LIKE',"%{$search}%")
                            ->orWhere('course_description', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Course::where('course_code','LIKE',"%{$search}%")
                             ->orWhere('course_description', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($courses))
        {
            foreach ($courses as $course)
            {   
                
            if(Gate::allows('permission', 'updateCourse')){
                $buttons = '
                <a href="#" title="Edit" onclick="edit('. $course['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ';
            }
                
            if($course['status'] == '1'){
                if(Gate::allows('permission', 'deleteCourse')){
                    $buttons .= '<a href="#" onclick="deactivate('. $course['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';
                }
                $status = "<label class='label label-primary'>Active</label>";
            }
            else{
                if(Gate::allows('permission', 'restoreCourse')){
                    $buttons = '<a href="#"  onclick="activate('. $course['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                }
                $status = "<label class='label label-danger'>Deleted</label>";
            }
                $nestedData['course_code'] = $course->course_code;
                $nestedData['course_description'] = $course->course_description;
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

    public function create()
    {
        
    }

    //save course
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'course_description'=>'required',
            'course_code'=>'required',
        ]);

        try {
            DB::beginTransaction();
            
            $course = new Course;
            $course->course_code = convertData($request["course_code"]);
            $course->course_description = convertData($request["course_description"]);
            $course->status = 1;
            $changes = $course->getDirty();
            $course->save();
            
            DB::commit();
            
            /* logs */
            action_log('Course Mngt', 'CREATE', array_merge(['id' => $course->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function show($id)
    {
        $course = Course::find($id);
        
        return response::json($course);
    }

    public function edit(Course $course)
    {
        
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $course = Course::findOrFail($id);
            $course->course_code =  convertData($request["edit_course_code"]);
            $course->course_description = convertData($request["edit_course_description"]);
            $changes = $course->getDirty();
            $course->save();

            DB::commit();

            /* logs */
            action_log('Course Mngt', 'Update', array_merge(['id' => $course->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function destroy(Course $course)
    {
        //
    }

    public function findAllCourse()
    {
        $courses = DB::table(connectionName('iskocab') . '.courses')->where('status', 1)->orderBy('course_code')->get();
        
        return response()->json($courses);
    }
    
    //toggle status
    public function updateStatus($id){
        try {
            DB::beginTransaction();

            $course = Course::findOrFail($id);
            $status = $course->status;
            if($status == 1){
                $course->status = 0;
                $action = 'DELETED';
            }
            else{
                $course->status = 1;
                $action = 'RESTORE';
            }
            $changes = $course->getDirty(); 
            $course->save();

            DB::commit();

            /* logs */
            action_log('Course Mngt', $action, array_merge(['id' => $course->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
