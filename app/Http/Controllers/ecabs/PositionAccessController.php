<?php

namespace App\Http\Controllers\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

/* Models */
use App\Ecabs\PositionAccess;
use App\Ecabs\DepartmentPosition;
use DB;
use App\Events\DataTableEvent;
use Gate;

class PositionAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ecabs.access.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ecabs.access.create');
    }

    // get all positions for combo box
    public function findall2(Request $request)
    {
        $access = PositionAccess::where('status', '1')->get();

        return response()->json($access);
    }

    //display positions of selected department in combo box
    public function findall3(Request $request)
    { 
        $department_id = $request['department_id'];
        $department_positions = DepartmentPosition::where('department_id', $department_id)->get();
        
        if(!empty($department_positions)){
            $positions = array();
            foreach($department_positions as $department_position){ 
                $position = PositionAccess::findorFail($department_position['position_access_id']);
                array_push($positions, $position);
            }
        }
        
        return response()->json($positions);
    }

    public function findall(request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'position'
        );

        $totalData = PositionAccess::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table('department_positions')
                ->select('position_accesses.id', 'position_accesses.position', 'departments.department', 'position_accesses.status')
                ->join('departments', 'departments.id', '=', 'department_positions.department_id')
                ->join('position_accesses', 'position_accesses.id', '=', 'department_positions.position_access_id');
        
        if(empty($request->input('search.value')))
        {
            $system_access = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        else {
            $search = $request->input('search.value');

            $system_access = $query->orWhere('position_accesses.position', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->orWhere('position_accesses.position', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($system_access))
        {
            foreach ($system_access as $access)
            {

                $buttons = '';
                
                if(Gate::allows('permission', 'updateAccess')){
                    $buttons .= ' <a data-toggle="tooltip" title="Click here to edit System Access." onclick="edit('.$access->id.')" class="btn btn-xs btn-success btn-fill btn-rotate"><i class="ti-pencil-alt"></i> EDIT</a> ';
                }

                if($access->status == '1'){
                    
                    if(Gate::allows('permission', 'deleteAccess')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to remove System Access." onclick="del('. $access->id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';  
                    }  
                    $status = "<label class='label label-primary'>Active</label>";
                } else {
                    if(Gate::allows('permission', 'restoreAccess')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to restore System Access." onclick="del('. $access->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }
                
                // $status = ($access->status==1)?'<label class="label label-primary">ACTIVE</label>':'<label class="label label-danger">IN-ACTIVE</label>';
               
                $nestedData['position'] =  $access->position;
                $nestedData['department'] =  $access->department;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'position'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(array('success'=>false, 'error'=>'Validation Error','messages'=>'Please input valid data!'));
        }else{

            try {
                DB::beginTransaction();

                $access = new PositionAccess;
                $access->position = convertData($request['position']);
                $access->access = serialize($request['permission']);
                $access->status = '1';
                $changes = $access->getDirty();
                $access->save();

                $department_pos = new DepartmentPosition;
                $department_pos->department_id = $request['department'];
                $department_pos->position_access_id = $access->id;
                $changes = array_merge($changes, $department_pos->getDirty());
                $department_pos->save();

                DB::commit();

                /* logs */
                action_log('Position Access Mngt', 'CREATE', array_merge(['id' => $access->id], $changes));
    
                return response()->json(array('success'=>true, 'messages'=>'Record successfully saved!'));
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
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
        $position = PositionAccess::findOrfail($id);

        return response()->json(array('id'=>$position['id'], 'position'=>$position['position'],'access'=>unserialize($position['access'])));
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
       $validator = Validator::make($request->all(),[
            'editaccess'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(array('success'=>false, 'error'=>'Validation Error','messages'=>'Please input valid data!'));
        }else{
            
            try {
                DB::beginTransaction();

                $access = PositionAccess::findOrFail($id);
                $access->position = convertData($request['editaccess']);
                $access->access = serialize($request['permission']);
                $changes = $access->getDirty();
                $access->save();
                
                DB::commit();

                /* logs */
                action_log('Maintenance Mngt', 'UPDATE', array_merge(['id' => $access->id], $changes));
    
                return response()->json(array('success'=>true, 'messages'=>'Record succesfully updated!'));
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
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

    public function togglestatus($id) {

        $message='';
        $action = "";
        $access=PositionAccess::findOrFail($id);

        try {
            DB::beginTransaction();
            if($access->status=='1') {
                $access->status='0';
                $message='Record successfully Deleted!';
                $action = 'DELETED';
            }
            else {
                $access->status='1';
                $message='Record successfully Retreive!';
                $action = 'RESTORE';
            }
            $changes = $access->getDirty();
            $access->save();

            DB::commit();

            /* logs */
            action_log('POSITION access Mngt', $action, array_merge(['id' => $access->id], $changes));

            return response()->json(array('success'=> true, 'messages'=>$message));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

}
