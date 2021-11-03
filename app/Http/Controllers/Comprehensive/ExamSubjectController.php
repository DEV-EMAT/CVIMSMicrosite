<?php

namespace App\Http\Controllers\Comprehensive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\ExamSubject;
use DB;
use Gate;
use Validator;

class ExamSubjectController extends Controller
{
    public function index()
    {        
        return view('comprehensive.examination.subject.index', ['title' => "Subject Management"]);
    }

    public function create()
    {
        //
    }

    public function findAll(request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'subject'
        );

        $totalData = ExamSubject::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {       
            $subjects = ExamSubject::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        else {
            $search = $request->input('search.value');

            $subjects = ExamSubject::where('subject', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = ExamSubject::where('subject', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($subjects))
        {
            foreach ($subjects as $subject)
            {
                $buttons = '';
                
                if(Gate::allows('permission', 'updateSubject')){
                    $buttons .= ' <a onclick="edit('.$subject->id.')" class="btn btn-xs btn-success btn-fill btn-rotate"><i class="ti-pencil-alt"></i> EDIT</a>';
                }
                if($subject->status == 1){
                    if(Gate::allows('permission', 'deleteSubject')){
                        $buttons .= ' <a onclick="toggleStatus('.$subject->id.')" class="btn btn-xs btn-danger btn-fill btn-rotate"><i class="ti-trash"></i> DELETE</a>';
                    }
                }else{
                    if(Gate::allows('permission', 'deleteSubject')){
                        $buttons = ' <a onclick="toggleStatus('.$subject->id.')" class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                }                

                $status = ($subject->status==1)?'<label class="label label-primary">ACTIVE</label>':'<label class="label label-danger">DELETED</label>';
               
                $nestedData['id'] = $subject->id;
                $nestedData['subject'] =  $subject->subject;
                $nestedData['status'] = $status;
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

    public function findAllForComboBox()
    {
        return response()->json(ExamSubject::where('status', '1')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'subject'   => 'required'
        ]);

        if($validator->fails()){ return response()->json(array('success'=>false, 'messages' => 'An Error Occured!')); }
        else{
            try {
                DB::beginTransaction();

                $subject                = new ExamSubject;
                $subject->subject       = convertData($request['subject']);
                $subject->description   = convertData($request['description']);
                $subject->status        = '1';
                $changes = $subject->getDirty();
                $subject->save();

                DB::commit();
                
                /* logs */
                action_log('Subject Mngt', 'CREATE', array_merge(['id' => $subject->id], $changes));

                return response()->json(array('success'=> true, 'messages'=> 'Successfully Created!'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return response()->json(array('success'=> false, 'messages'=> 'SQL Error!' . $e));
            }
            return response()->json(array('success' => true, 'messages' => 'Successfully Created!'));
        }

    }

    public function show($id)
    {
        return response()->json(ExamSubject::findOrFail($id));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'editsubject'   => 'required'
        ]);

        if($validator->fails()){ return response()->json(array('success'=>false, 'messages' => 'An Error Occured!')); }
        else{
            try {
                DB::beginTransaction();

                $examSubject                = ExamSubject::findOrFail($id);
                $examSubject->subject       = convertData($request['editsubject']);
                $examSubject->description   = convertData($request['editdescription']);
                $changes = $examSubject->getDirty();
                $examSubject->save();

                 DB::commit();
                 
                /* logs */
                action_log('Subject Mngt', 'CREATE', array_merge(['id' => $examSubject->id], $changes));

                return response()->json(array('success'=> true, 'messages'=> 'Successfully Created!'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return response()->json(array('success'=> false, 'messages'=> 'SQL Error!' . $e));
            }
            return response()->json(array('success' => true, 'messages' => 'Successfully Created!'));
        }
    }

    public function destroy($id)
    {
        //
    }

    public function toggleStatus($id) 
    {
        $message='';
        $subject=ExamSubject::findOrFail($id);

        try {
            DB::beginTransaction();
            if($subject->status=='1') {
                $subject->status='0';
                $message='Record successfully Deleted!';
                $action = 'DELETED';
            }
            else {
                $subject->status='1';
                $message='Record successfully Retreive!';
                $action = 'RESTORE';
            }
            $changes = $subject->getDirty();
            $subject->save();
            
            DB::commit();

            /* logs */
            action_log('Subject Mngt', $action, array_merge(['id' => $subject->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
