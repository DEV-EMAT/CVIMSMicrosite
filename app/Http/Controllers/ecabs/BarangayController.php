<?php

namespace App\Http\Controllers\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ecabs\Barangay;
use Response;
use Gate;
use App\Events\DataTableEvent;
use DB;

class BarangayController extends Controller
{
    public function index()
    {
        return view('ecabs.barangay.index', ['title' => "Barangay Management"]);
    }

    public function findall(Request $request)
    {
        $columns = array( 
            0 =>'barangay', 
            1 =>'city',
            2=> 'province',
            3=> 'zipcode',
            4=> 'actions',
        );

        $totalData = Barangay::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $barangays = Barangay::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = Barangay::where('barangay','LIKE',"%{$search}%");

            $barangays =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($barangays))
        {
            foreach ($barangays as $barangay)
            {  
                $buttons = '';
                
                if(Gate::allows('permission', 'updateBarangay')){
                    $buttons = '<a data-toggle="tooltip" title="Click here to edit Barangay" onclick="edit('. $barangay['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ' ;
                }

                if($barangay['status'] == '1'){
                    if(Gate::allows('permission', 'deleteBarangay')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to delete Barangay" onclick="deactivate('. $barangay['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';    
                    }
                    $status = "<label class='label label-primary'>Active</label>";
                }
                else{
                    if(Gate::allows('permission', 'restoreBarangay')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to restore Barangay" onclick="activate('. $barangay['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }

                $nestedData['barangay'] = $barangay->barangay;
                $nestedData['city'] = $barangay->city;
                $nestedData['province'] = $barangay->province;
                $nestedData['zipcode'] = $barangay->zipcode;
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

    //get all barangay for combo box
    public function findall2(Request $request)
    {
        $barangays = Barangay::where('status', '1')->get();

        return response()->json($barangays);
    }
  
    public function create()
    {   
        //
    }

    public function store(Request $request)
    {
        $this -> validate ($request, [
            'barangay'=>'required',
            'city'=>'required',
            'province'=>'required',
            'zipcode'=>'required',
        ]);

        try {
            DB::beginTransaction();

            $barangay = new Barangay;
            $barangay->barangay = convertData($request["barangay"]);
            $barangay->city = convertData($request["city"]);
            $barangay->province = convertData($request["province"]);
            $barangay->zipcode = convertData($request["zipcode"]);
            $barangay->status = 1;
            $changes = $barangay->getDirty();
            $barangay->save();
              
            DB::commit();

            /* logs */
            action_log('Barangay Mngt', 'CREATE', array_merge(['id' => $barangay->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function show($id)
    {
        $barangay = Barangay::find($id);
        
        return response::json($barangay);
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $this -> validate ($request, [
            'barangay'=>'required',
            'city'=>'required',
            'province'=>'required',
            'zipcode'=>'required',
        ]);
        
        try {
            DB::beginTransaction();

            $barangay = Barangay::findOrFail($id);
            $barangay->barangay =  convertData($request["barangay"]);
            $barangay->city =  convertData($request["city"]);
            $barangay->province =  convertData($request["province"]);
            $barangay->zipcode =  convertData($request["zipcode"]);
            $changes = $barangay->getDirty();
            $barangay->save();

            
            DB::commit();
            
            /* logs */
            action_log('Barangay Mngt', 'CREATE', array_merge(['id' => $barangay->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

   
    public function destroy($id)
    {
        
    }

    //toggle status
    public function togglestatus($id){
        $barangay = Barangay::findOrFail($id);
        $status = $barangay->status;
        $action = '';

        try {
            DB::beginTransaction();

            if($status == 1){                
                $barangay->status = 0;
                $action = 'DELETED';
            }
            else{
                $barangay->status = 1;
                $action = 'RESTORE';
            }
            $changes = $barangay->getDirty();
            $barangay->save();
            
            DB::commit();

            /* logs */
            action_log('Barangay Mngt', $action, array_merge(['id' => $barangay->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
