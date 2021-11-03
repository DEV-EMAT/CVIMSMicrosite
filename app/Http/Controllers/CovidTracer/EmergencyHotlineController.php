<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\EmergencyHotline;
use App\Ecabs\Address;
use DB;
use Validator;
use Gate;


class EmergencyHotlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covidtracer.hotline.index', ['title' => 'Emergency Hotline']);
    }

    public function findall(request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'name',
            1 =>'contact'
        );

        $totalData = EmergencyHotline::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = EmergencyHotline::query();
        
        if(empty($request->input('search.value')))
        {
            $results = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        else {
            $search = $request->input('search.value');

            $results = $query->orWhere('name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->orWhere('name', 'LIKE',"%{$search}%")->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $address = Address::findOrFail($result->address_id);
                
                $buttons = '';
                if($result['status'] == '1'){
                    if(Gate::allows('permission', 'updateHotline')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to edit Hotline" onclick="edit('. $result->id .')"  class="btn btn-xs btn-success btn-fill btn-rotate remove"><i class="ti-trash"></i> EDIT</a> '; 
                    }

                    if(Gate::allows('permission', 'deleteHotline')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to remove Hotline" onclick="deactivate('. $result->id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';  
                    }  
                    $status = "<label class='label label-primary'>Active</label>";
                } else {
                    if(Gate::allows('permission', 'restoreHotline')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to restore Hotline" onclick="deactivate('. $result->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }
                $status = ($result->status==1)?'<label class="label label-primary">ACTIVE</label>':'<label class="label label-danger">IN-ACTIVE</label>';
               
                $nestedData['id'] = $result->id;
                $nestedData['name'] = $result->name;
                $nestedData['contact'] = unserialize($result->contact);
                $nestedData['address'] = $result->address;
                $nestedData['barangay'] = $address->barangay;
                $nestedData['province'] = $address->province;
                $nestedData['city'] = $address->city;
                $nestedData['region'] = $address->region;
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
        $validator = Validator::make($request->all(),[
            'fullname' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'txtRegion' => 'required', 
            'txtBarangay' => 'required', 
            'province' => 'required', 
            'city' => 'required', 
        ]);

        if($validator->fails()){
            return response()->json(array('success'=>false, 'messages'=>'Please input valid data!'));
        }else{
            try{
                /* Note: problem on db Transaction due of different connection/ multi tenancy */

                // DB::beginTransaction();
                
                $hotlineAddress = new Address;
                $hotlineAddress->region = $request['txtRegion'];
                $hotlineAddress->region_id = $request['region'];
                $hotlineAddress->barangay = $request['txtBarangay'];
                $hotlineAddress->barangay_id = $request['barangay'];
                $hotlineAddress->city = $request['city'];
                $hotlineAddress->province = $request['province'];
                $hotlineAddress->status = '1';
                $changes = $hotlineAddress->getDirty();
                $hotlineAddress->save(); 
                           
                $hotline = new EmergencyHotline;
                $hotline->name = convertData($request['fullname']);
                $hotline->contact = serialize($request['contact']);
                $hotline->address = $request['address'];
                $hotline->address_id = convertData($hotlineAddress->id);
                $hotline->status = "1";  
                $changes = array_merge($changes, $hotline->getDirty());
                $hotline->save();
            
                /* logs */
                action_log('Emergency hotline Mngt', 'Create', array_merge(['id' => $hotline->id], $changes));


                // event(new DataTableEvent(true));
                DB::commit();
                
                return response()->json(array('success'=>true, 'messages'=>'Record Successfully Saved'));
            }catch (\PDOException $e) {
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
        $hotline = EmergencyHotline::findOrFail($id);
        $contact = unserialize($hotline->contact);
        $address = Address::findOrFail($hotline->address_id);
        return response()->json(array($hotline, $address, $contact));
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
            'editFullname' => 'required',
            'editContact' => 'required',
            'editAddress' => 'required', 
            'editRegion' => 'required', 
            'editProvince' => 'required', 
            'editCity' => 'required', 
            'editBarangay' => 'required', 
        ]);

        if($validator->fails()){
            return response()->json(array('success'=>false, 'messages'=>'Please input valid data!'));
        }else{
            try{
                DB::beginTransaction();
                
                $hotline = EmergencyHotline::findOrFail($id);

                $address = Address::findOrFail($hotline->address_id);
                $address->region = $request['txtRegion'];
                $address->region_id = $request['editRegion'];
                $address->barangay = $request['txtBarangay'];
                $address->barangay_id = $request['editBarangay'];
                $address->city = $request['editCity'];
                $address->province = $request['editProvince'];
                $changes = $address->getDirty();
                $address->save();

                $hotline->name = convertData($request['editFullname']);
                $hotline->contact = serialize($request['editContact']);
                $hotline->address = convertData($request['editAddress']);
                $changes = array_merge($hotline->getDirty() ,$address->getDirty());
                $hotline->save();

                DB::commit();
                
                /* logs */
                action_log('Emergency hotline Mngt', 'Update', array_merge(['id' => $hotline->id], $changes));
                
                return response()->json(array('success'=>true, 'messages'=>'Record Successfully Updated'));
            }catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    public function togglestatus($id) {

        $hotline = EmergencyHotline::findOrFail($id);
        $message = '';
        $action = '';

        try {
            DB::beginTransaction();

            if($hotline->status == '1') {
                $hotline->status = '0';
                $message = 'Record successfully Deleted!';
                $action = 'DELETED';
            } else {
                $hotline->status = '1';
                $message = 'Record successfully Retreived!';
                $action = 'RESTORE';
            }
            $changes = $hotline->getDirty();
            $hotline->save();

            DB::commit();

            /* logs */
            action_log('Emergency hotline Mngt', $action, array_merge(['id' => $hotline->id], $changes));

            return response()->json(array('success'=> true, 'messages'=>$message));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
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
