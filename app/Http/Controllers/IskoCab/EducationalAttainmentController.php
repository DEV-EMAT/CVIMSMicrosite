<?php

namespace App\Http\Controllers\iskocab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IskoCab\EducationalAttainment;
use Gate;
use DB;
use Validator;
use Response;

class EducationalAttainmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('iskocab.educational_attainment.index');
    }

    public function create()
    {
        //
    }

    public function findall(request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'title'
        );

        $totalData = EducationalAttainment::count();

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = EducationalAttainment::query();

        if(empty($request->input('search.value')))
        {       
            $categories = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        else {
            $search = $request->input('search.value');

            $categories = $query->orWhere('title', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->orWhere('title', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($categories))
        {
            foreach ($categories as $category)
            {
                $buttons = '';
                if(Gate::allows('permission', 'updateEducationalAttainment')){
                    $buttons .= ' <a onclick="edit('.$category->id.')" class="btn btn-xs btn-success btn-fill btn-rotate"><i class="ti-pencil-alt"></i> EDIT</a>';
                }if(Gate::allows('permission', 'deleteEducationalAttainment')){

                    if($category->status == 1){
                        $buttons .= ' <a onclick="del('.$category->id.')" class="btn btn-xs btn-danger btn-fill btn-rotate"><i class="ti-trash"></i> DELETE</a>';
                    }else{
                        if(Gate::allows('permission', 'restoreEducationalAttainment')){
                            $buttons .= ' <a onclick="del('.$category->id.')" class="btn btn-xs btn-warning btn-fill btn-rotate"><i class="fa fa-refresh"></i> RESTORE</a>';
                        }
                    }

                }

                $status = ($category->status==1)?'<label class="label label-primary">ACTIVE</label>':'<label class="label label-danger">IN-ACTIVE</label>';

                $nestedData['id'] = $category->id;
                $nestedData['title'] =  $category->title;
                $nestedData['description'] = $category->description;
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

    public function findAllEducationalAttainment()
    {
        $courses = DB::table(connectionName('iskocab') . '.educational_attainments')->where('status', 1)->orderBy('title')->get();
        
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'title'=>'required'
        ]);

        if($validate->fails()){
            return response()->json(array('success'=>false, 'messages'=> 'An Error Occured!'));
        }else{

            try {
                DB::beginTransaction();

                $category = new EducationalAttainment;
                $category->title = convertData($request['title']);
                $category->description = convertData($request['description']);
                $category->status = '1';
                $changes = $category->getDirty();
                $category->save();
     
                DB::commit();

                /* logs */
                action_log('Educational Attainment', 'Update', array_merge(['id' => $category->id], $changes));

                return response()->json(array('success'=>true, 'messages'=> 'Record Successfully Saved!'));
            } catch (\PDOException $e) {
                DB::rollback();
                return response()->json(array('success'=>false, 'messages'=> 'SQL Transaction Failed!'));
            }
        }
    }

    public function show($id)
    {
        return response()->json(EducationalAttainment::findOrFail($id));
    }

    public function edit($id)
    {
        //
    }

    function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'edit_title'=>'required'
        ]);

        if($validate->fails()){
            return response::json(array('success'=>false , 'messages'=>'Validation Error!'));
        }else{
            try {

                DB::beginTransaction();

                $category = EducationalAttainment::findOrFail($id);
                $category->title = convertData($request['edit_title']);
                $category->description = convertData($request['edit_description']);
                $changes = $category->getDirty();
                $category->save();

                DB::commit();

                /* logs */
                action_log('Educational Attainment', 'Update', array_merge(['id' => $category->id], $changes));

                return response()->json(array('success'=>true, 'messages'=> 'Record Successfully Updated!'));
            } catch (\PDOException $e) {
                DB::rollback();
                return response()->json(array('success'=>false, 'messages'=> 'SQL Transaction Failed!'));
            }
        }
    }

    public function findAllForComboBox(){
        return response()->json(EducationalAttainment::where('status', '=', '1')->get());
    }

    public function togglestatus($id){

        $attainment = EducationalAttainment::findOrFail($id);
        $message = '';
        $action = '';

        try {
            DB::beginTransaction();
            if($attainment->status == '1'){
                $message = 'Record deleted successfully!';
                $attainment->status = '0';
                
                /* logs */
                $changes = $attainment->getDirty();
                $action = 'DELETED';
            }else{
                $message = 'Record retrieve successfully!';
                $attainment->status ='1';
                
                /* logs */
                $changes = $attainment->getDirty();
                $action = 'RESTORE';
            }
            $attainment->save();

            DB::commit();
            
            /* logs */
            action_log('Educational Attainment', $action, array_merge(['id' => $attainment->id], $changes));

            return response()->json(array('success'=> true, 'messages'=>$message));
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
