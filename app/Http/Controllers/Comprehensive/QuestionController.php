<?php

namespace App\Http\Controllers\Comprehensive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\Question;
use DB;
use Validator;
use Reponse;
use Gate;

class QuestionController extends Controller
{
    public function index()
    {
        return view('comprehensive.examination.question.index' , ['title' => "Question Management"]);
    }

    public function findAll(request $request)
    {
        $columns = array(
            0 =>'questions.id',
            1 =>'question',
        );

        $totalData = Question::count();
        if($request['examination'] == "createExam"){
            $totalData = Question::where('status', '=', 1)->count();
        }

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('comprehensive').'.questions')
        ->select('questions.id', 'questions.question', 'exam_subjects.subject','exam_types.type','questions.answer','questions.choices','questions.status')
        ->join(connectionName('comprehensive').'.exam_types', 'questions.exam_type_id', '=', 'exam_types.id')
        ->join(connectionName('comprehensive').'.exam_subjects', 'questions.exam_subject_id', '=', 'exam_subjects.id');

        if($request['examination'] == "createExam"){
            $query->where('questions.status', '=', 1);
        }

        if(empty($request->input('search.value')))
        {       
            $questions = $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                    
        }
        else {
            $search = $request->input('search.value');

            $questions = $query
                ->orWhere('questions.question','LIKE',"%{$search}%")
                ->orWhere('exam_subjects.subject', 'LIKE',"%{$search}%")
                ->orWhere('exam_types.type', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            // $totalFiltered = $query
            //     ->orWhere('questions.question','LIKE',"%{$search}%")->where('questions.status', '1')
            //     ->orWhere('exam_subjects.subject', 'LIKE',"%{$search}%")->where('questions.status', '1')
            //     ->orWhere('exam_types.type', 'LIKE',"%{$search}%")->where('questions.status', '1')
            //     ->count();
                
            $totalFiltered = $questions->count();
        }

        $data = array();
        if(!empty($questions))
        {   
            $ctr =1;
            foreach ($questions as $question)
            {

                $status = ($question->status == 1)?'<label class="label label-primary">ACTIVE</label>':'<label class="label label-danger">DELETED</label>';
                    
                $nestedData['ctr'] = $ctr++;
                $nestedData['id'] = $question->id;
                $nestedData['question'] = $question->question;
                $nestedData['subject'] = $question->subject;
                $nestedData['type'] = $question->type;
                $nestedData['answer'] = $question->answer;
                $nestedData['choices'] =  unserialize($question->choices);
                $nestedData['status'] = $status;

                $buttons = '';
                
                if($question->status == 1){
                    if(Gate::allows('permission', 'updateQuestion')){
                        $buttons = ' <a onclick="edit('.$question->id.')" class="btn btn-xs btn-success btn-fill btn-rotate"><i class="ti-pencil-alt"></i> EDIT</a>';
                    }if(Gate::allows('permission', 'deleteQuestion')){
                        $buttons .= ' <a onclick="toggleStatus('.$question->id.')" class="btn btn-xs btn-danger btn-fill btn-rotate"><i class="ti-trash"></i> DELETE</a>';
                    }
                }
                else{
                    if(Gate::allows('permission', 'deleteQuestion')){
                        $buttons = ' <a onclick="toggleStatus('.$question->id.')" class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                }

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

    public function create()
    {
        return view('comprehensive.examination.question.create' , ['title' => "Question Management"]);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'subject'   => 'required',
            'question'  =>'required',
            'answer'  =>'required',
            'choices'  =>'required'
        ]);
        if($validator->errors()->first('choices') || $validator->errors()->first('answer')){
            return response()->json(array('success'=>false, 'error' => 'Invalid Input', 'messages'=>'Please enter valid answer and choices'));
        }else{
            if($validator->fails()){ return response()->json(array('success'=>false, 'messages' => 'An Error Occured!')); }
            else{

                try {
                    DB::beginTransaction();
        
                    $choices = '';
                    $answer = '';
                    if($request['examtype']=='1'){ 
                        $answer = $request['choices'][$request['answer']];
                        $choices = serialize(array_map('strtoupper', $request['choices']));
                    }else{
                        $answer = $request['answer'];
                        $choices = serialize(array('TRUE','FALSE'));
                    }

                    $question                    = new Question;
                    $question->question          = convertData($request['question']);
                    $question->answer            = convertData($answer);
                    $question->choices           = $choices;
                    $question->exam_subject_id   = $request['subject'];
                    $question->exam_type_id      = $request['examtype'];
                    $question->status            = '1';
                    $changes = $question->getDirty();
                    $question->save();

                    DB::commit();
                    
                    /* logs */
                    action_log('Question Mngt', 'CREATE', array_merge(['id' => $question->id], $changes));

                    return response()->json(array('success' =>true, 'messages' => 'Record successfully saved!'));
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
                }
            }
        }

    }

    public function show($id)
    {
        $question = Question::findOrFail($id);
        // return response()->json(Question::findOrFail($id));
        return response()->json(
            array('id'=>$question['id'],
                'question'=>$question['question'], 
                'exam_subject_id'=>$question['exam_subject_id'], 
                'choices'=>unserialize($question['choices']), 
                'answer'=>$question['answer'],
                'exam_type_id'=>$question['exam_type_id'])
        );
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'subject'   => 'required',
            'question'  =>'required',
            'answer'  =>'required',
            'choices'  =>'required'
        ]);

        if($validator->errors()->first('choices') || $validator->errors()->first('answer')){
            return response()->json(array('success'=>false, 'error' => 'Invalid Input', 'messages'=>'Please enter valid answer and choices'));
        }else{
            if($validator->fails()){ return response()->json(array('success'=>false, 'messages' => 'An Error Occured!')); }
            else{
                try {
                    DB::beginTransaction();

                    $choices = '';
                    $answer = '';
                    if($request['examtype']=='1'){ 
                        $answer = $request['choices'][$request['answer']];
                        $choices = serialize(array_map('strtoupper', $request['choices']));
                    }else{
                        $answer = $request['answer'];
                        $choices = serialize(array('TRUE','FALSE'));
                    }

                    $question = Question::findOrFail($id);
                    $question->question          = convertData($request['question']);
                    $question->answer            = convertData($answer);
                    $question->choices           = $choices;
                    $question->exam_subject_id   = $request['subject'];
                    $question->exam_type_id      = $request['examtype'];
                    $question->status            = '1';
                    $changes = $question->getDirty();
                    $question->save();

                    DB::commit();
                    
                    /* logs */
                    action_log('Question Mngt', 'Update', array_merge(['id' => $question->id], $changes));

                    return response()->json(array('success' =>true, 'messages' => 'Record successfully saved!'));
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
                }
            }
        }
    }
    
    public function showWithoutAnswer($id)
    {   
        $question = Question::findOrFail($id);
        return response()->json(
            array('id'=>$question['id'],
                'question'=>$question['question'], 
                'exam_subject_id'=>$question['exam_subject_id'], 
                'choices'=>unserialize($question['choices']))
        );
    }

    public function toggleStatus($id) {

        $message='';
        $question=Question::findOrFail($id);
        try {
            DB::beginTransaction();

            if($question->status=='1') {
                $question->status='0';
                $message='Record successfully Deleted!';
                $action = 'DELETED';
            }
            else {
                $question->status='1';
                $message='Record successfully Retreive!';
                $action = 'RESTORE';
            }
            $changes = $question->getDirty();
            $question->save();
            
            DB::commit();

            /* logs */
            action_log('Question Mngt', $action, array_merge(['id' => $question->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => $message));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function destroy($id)
    {
        //
    }
}
