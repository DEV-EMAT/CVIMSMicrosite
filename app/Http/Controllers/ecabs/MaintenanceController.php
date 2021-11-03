<?php

namespace App\Http\Controllers\ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ecabs\Maintenance;
use Validator;
use Gate;
use Response;
use DB;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ecabs.maintenance.index',['title' => 'Pre-Registration Maintenance']);
    }

    public function findall(request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'description'
        );

        $totalData = Maintenance::count();

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = Maintenance::query();

        if(empty($request->input('search.value')))
        {       
            $results = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        else {
            $search = $request->input('search.value');

            $results = $query->orWhere('description', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->orWhere('description', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $buttons="";
                $status="";

                if(Gate::allows('permission', 'updatePreRegistration')){
                    $buttons = ' <a onclick="setup('.$result->id.')" class="btn btn-xs btn-primary btn-fill btn-rotate"><i class="fa fa-cogs"></i> SETUP</a>';
                    $buttons .= ' <a onclick="edit('.$result->id.')" class="btn btn-xs btn-success btn-fill btn-rotate"><i class="ti-pencil-alt"></i> EDIT</a>';
                }
                if($result->status==1){
                    $status = '<label class="label label-primary">OPEN REGISTRATION</label>';
                    $status .= (($result->platform_id=="1")?' - <label class="label label-info"><i class="fa fa-mobile"></i> - MOBILE PLATFORM</label>': (($result->platform_id == "2")? ' - <label class="label label-success"><i class="fa fa-desktop"></i> - WEB PLATFORM</label>':' - <label class="label label-warning"><i class="fa fa-desktop"></i>&nbsp;&nbsp;<i class="fa fa-mobile"></i> - BOTH PLATFORM</label>'));
                }else{
                    $status = '<label class="label label-danger">CLOSE REGISTRATION</label>';
                }

                $nestedData['id'] = $result->id;
                $nestedData['description'] =  $result->description;
                $nestedData['status'] = $status;
                $nestedData['actions'] = $buttons;
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
        $validator = Validator::make($request->all(), [
            'description' => 'required' 
        ]);


        if($validator->fails()){
            return response()->json(array('success'=>false, 'messages' => 'Please provide valid inputs!'));
        }else{
            try{
                DB::beginTransaction();
                $maintenance = new Maintenance;
                $maintenance->description = convertData($request['description']);
                $maintenance->status = '0';
                $maintenance->platform_id = 3;
                $changes = $maintenance->getDirty();
                $maintenance->save();

                DB::commit();
                
                /* logs */
                action_log('Maintenance Mngt', 'CREATE', array_merge(['id' => $maintenance->id], $changes));

                return response()->json(array('success' => true, 'messages' => 'Record Successfully Saved!'));
            }catch(\PDOException $e){
                DB::rollback();
                return response()->json(array('success' => false, 'messages' => 'Transaction Failed!'));
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
        return response()->json(Maintenance::findOrFail($id));
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
        $validator = Validator::make($request->all(), [
            'edit_description' => 'required' 
        ]);

        if($validator->fails()){
            return response()->json(array('success'=>false, 'messages' => 'Please provide valid inputs!'));
        }else{
            try{
                DB::beginTransaction();
                $maintenance = Maintenance::findOrFail($id);
                $maintenance->description = convertData($request['edit_description']);
                $changes = $maintenance->getDirty();
                $maintenance->save();

                DB::commit();
                
                /* logs */
                action_log('Maintenance Mngt', 'Update', array_merge(['id' => $maintenance->id], $changes));

                return response()->json(array('success' => true, 'messages' => 'Record Successfully Updated!'));
            }catch(\PDOException $e){
                DB::rollback();
                return response()->json(array('success' => false, 'messages' => 'Transaction Failed!'));
            }
        }
    }

    public function update_status(Request $request){
        try{
            DB::beginTransaction();
            $maintenance = Maintenance::findOrFail($request['maintenance_id']);
            $maintenance->status = $request['active_status'];
            $maintenance->platform_id = $request['platform'];
            $changes = $maintenance->getDirty();
            $maintenance->save();

            DB::commit();

            /* logs */
            action_log('Maintenance Mngt', 'UPDATE', array_merge(['id' => $maintenance->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Record Successfully Updated!'));
        }catch(\PDOException $e){
            DB::rollback();
            return response()->json(array('success' => false, 'messages' => 'Transaction Failed!'));
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
}
