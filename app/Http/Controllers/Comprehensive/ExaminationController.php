<?php

namespace App\Http\Controllers\Comprehensive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\Examination;
use DB;
use Gate;

class ExaminationController extends Controller
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
    public function create()
    {
        //
    }

    public function findquestion(request $request)
    {
        $columns = array(
            0 =>'question',
        );
        $id = $request['exam_id'];
        $totalData = Examination::where('exam_title_id', $id)->where('status', '1')->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $query = DB::table(connectionName('comprehensive').'.examinations') 
        ->join(connectionName('comprehensive').'.questions', 'examinations.question_id', '=', 'questions.id')
        ->join(connectionName('comprehensive').'.exam_types', 'questions.exam_type_id', '=', 'exam_types.id')
        ->join(connectionName('comprehensive').'.exam_subjects', 'questions.exam_subject_id', '=', 'exam_subjects.id')
        ->select('examinations.id','examinations.status','questions.question','exam_types.type','exam_subjects.subject','questions.choices','questions.answer');
        if(empty($request->input('search.value')))
        {       
            $exam_questions = $query->where('examinations.exam_title_id', $id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');
            $exam_questions = $query->where('questions.question', 'LIKE', "%{$search}%")->where('examinations.exam_title_id', $id)  
                ->offset($start) ->limit($limit) ->orderBy($order, $dir)->get();
            $totalFiltered = $exam_questions->count();
        }
        $data = array();
        if(!empty($exam_questions))
        {   
            foreach ($exam_questions as $exam)
            {
                $status = '';
                $buttons = '';
                if($exam->status==1){
                    $status = '<label class="label label-primary">ACTIVE</label>';
                    if(Gate::allows('permission', 'updateExamination')){
                        $buttons = '<a onclick="togglequestion('.$exam->id.')" class="btn btn-xs btn-danger btn-fill btn-rotate"><i class="ti-trash"></i> DELETE</a>';
                    }
                }else{
                    $status = '<label class="label label-danger">IN-ACTIVE</label>';
                    if(Gate::allows('permission', 'updateExamination')){
                        $buttons = '<a onclick="togglequestion('.$exam->id.')" class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                }
                $nestedData['id'] =  $exam->id;
                $nestedData['question'] =  $exam->question; 
                $nestedData['subject'] = $exam->subject;
                $nestedData['type'] = $exam->type;
                $nestedData['answer'] = $exam->answer;
                $nestedData['choices'] =  unserialize($exam->choices);
                $nestedData['status'] =  $status;
                $nestedData['action'] =  $buttons;
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

}
