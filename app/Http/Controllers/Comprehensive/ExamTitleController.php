<?php

namespace App\Http\Controllers\Comprehensive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\ExamTitle;
use App\Comprehensive\Examination;
use App\Comprehensive\ExaminationHasDepartment;
use App\Ecabs\Department;
use Validator;
use DB;
use Gate;
use Response;

class ExamTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('comprehensive.examination.index' , ['title' => "Examination Management"]);
    }

    public function create()
    {
        return view('comprehensive.examination.create' , ['title' => "Examination Management"]);
    }

    public function findall2(){
        return response()->json(ExamTitle::all());
    }

    public function findall(request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'title',
            2 =>'time',
            3 =>'item_number',
            4 =>'passing'
        );

        $totalData = ExamTitle::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            if($request['action'] == 'programManagement'){
                $exam_titles = ExamTitle::where('status', '=', 1)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();
            }
            else{
                $exam_titles = ExamTitle::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            }
        }
        else {
            $search = $request->input('search.value');

            $query = ExamTitle::where('title', 'LIKE',"%{$search}%");

            if($request['action'] == 'programManagement'){
                $query->where('status', '=', 1);
            }

            $exam_titles = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->count();
        }
        $data = array();
        if(!empty($exam_titles))
        {
            foreach ($exam_titles as $exam)
            {
                $buttons = '<a onclick="show('.$exam->id.')" class="btn btn-xs btn-primary btn-fill btn-rotate"><i class="ti-eye"></i> VIEW</a>';

                if($exam->status == '1'){
                    if(Gate::allows('permission', 'updateExamination')){
                        $buttons .= ' <a onclick="edit('.$exam->id.')" class="btn btn-xs btn-success btn-fill btn-rotate"><i class="ti-pencil-alt"></i> EDIT</a>';
                    }if(Gate::allows('permission', 'deleteExamination')){
                        $buttons .= ' <a
                        onclick="toggleStatus('.$exam->id.')" class="btn btn-xs btn-danger btn-fill btn-rotate"><i class="ti-trash"></i> DELETE</a>';
                    }
                }else{
                    if(Gate::allows('permission', 'restoreExamination')){
                        $buttons = ' <a
                        onclick="toggleStatus('.$exam->id.')" class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                }
                $examHasDepartment = ExaminationHasDepartment::where('exam_title_id', '=', $exam->id)->first();
                $department = Department::where('id', '=', $examHasDepartment->department_id)->first();

                //for program module
                if($request['action'] == 'programManagement'){
                    $buttons = ' <a onclick="addExam('.$exam->id.', this.name)" class="btn btn-xs btn-warning btn-fill btn-rotate" id="exam' . $exam->id .'" name="' . $exam->title.'"><i class="fa fa-plus"></i> SELECT EXAM</a>';
                }

                $status = ($exam->status==1)?'<label class="label label-primary">ACTIVE</label>':'<label class="label label-danger">DELETED</label>';

                $nestedData['id'] = $exam->id;
                $nestedData['title'] =  $exam->title;
                $nestedData['department'] = $department;
                $nestedData['time'] =  $exam->time;
                $nestedData['number'] =  $exam->item_number;
                $nestedData['passing'] =  $exam->passing.' %';
                $nestedData['status'] =  $status;
                $nestedData['action'] = $buttons;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'txtExamTitle' => 'required',
            'txtTime' => 'required',
            'txtPassing' => 'required',
            'txtItems' => 'required',
            'department' => 'required'
        ]);

        if($validator->errors()->first('question')){
            return response()->json(array('success' =>false, 'error'=>'Invalid Input', 'messages'=>'Please add questions!'));
        }else{
            if($validator->fails()){
                return response()->json(array('success' =>false,'error'=>'Validation Error!', 'messages'=>'Please provide valid inputs!'));
            }else{
                try {
                    DB::beginTransaction();

                    $examination = new ExamTitle;
                    $examination->title = convertData($request['txtExamTitle']);
                    $examination->description = convertData($request['description']);
                    $examination->time = $request['txtTime'];
                    $examination->item_number = $request['txtItems'];
                    $examination->passing = $request['txtPassing'];
                    $examination->status = '1';
                    $examination->exam_title_status = '1';
                    $changes = $examination->getDirty();
                    $examination->save();

                    $question = explode(',',$request['question']);
                    for ($index=0; $index < count($question) ; $index++) {
                        $examination_question = new Examination;
                        $examination_question->question_id = $question[$index];
                        $examination_question->exam_title_id = $examination->id;
                        $examination_question->status = '1';
                        $changes = array_merge($changes, $examination_question->getDirty());
                        $examination_question->save();
                    }

                    $examDepartment = new ExaminationHasDepartment;
                    $examDepartment->exam_title_id = $examination->id;
                    $examDepartment->department_id = $request['department'];
                    $examDepartment->status = '1';
                    $examDepartment->save();

                    DB::commit();

                    /* logs */
                    action_log('Exam Title Mngt', 'CREATE', array_merge(['id' => $examination->id], $changes));

                    return response()->json(array('success' =>true, 'messages'=>'Record Successfully Saved!'));
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json(array('success' =>false,'error'=>'SQL Error!', 'messages'=>'Transaction failed'));
                }
            }
        }
    }

    public function show($id)
    {
        $examTitle = ExamTitle::findOrFail($id);
        $examHasDepartment = ExaminationHasDepartment::where('exam_title_id', '=', $examTitle->id)->first();
        $department = Department::where('id', '=', $examHasDepartment->department_id)->first();

        return response::json(array("examTitle" => $examTitle, "department" => $department->id));

        // return response::json(array("school"=>$school, "grading_system"=>$grading_system, "grading_type"=>$grading_type));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'txtExamTitle' => 'required',
            'txtTime' => 'required',
            'txtPassing' => 'required',
            'department' => 'required'
        ]);


        if($validator->fails()){
            return response()->json(array('success' =>false,'error'=>'Validation Error!', 'messages'=>'Please provide valid inputs!'));
        }else{

            try {
                DB::beginTransaction();

                $examTitle = ExamTitle::findOrFail($id);
                $examTitle->title = convertData($request['txtExamTitle']);
                $examTitle->description = convertData($request['description']);
                $examTitle->time = $request['txtTime'];
                $examTitle->passing = $request['txtPassing'];
                $changes = $examTitle->getDirty();
                $examTitle->save();

                $examHasDepartment = ExaminationHasDepartment::where('exam_title_id', '=', $examTitle->id)->first();
                $examHasDepartment->department_id = $request['department'];
                $examHasDepartment->save();

                DB::commit();

                /* logs */
                action_log('Exam Title Mngt', 'UPDATE', array_merge(['id' => $examTitle->id], $changes));

                return response()->json(array('success' =>true, 'messages'=>'Record Successfully Update!'));
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    public function destroy($id)
    {

    }

    public function togglestatus($id){
        $message='';
        $exam_title = ExamTitle::findOrFail($id);

        try {
            DB::beginTransaction();

            if($exam_title->status=='1'){
                $message = 'Record successfully deleted!';
                $exam_title->status = '0';
                $action = 'DELETED';
            }else{
                $message = 'Record successfully retrived!';
                $exam_title->status = '1';
                $action = 'RESTORE';
            }
            $changes = $exam_title->getDirty();
            $exam_title->save();

            DB::commit();

            /* logs */
            action_log('Barangay Mngt', $action, array_merge(['id' => $exam_title->id], $changes));

            return response()->json(array('success' => true, 'messages' => $message));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
