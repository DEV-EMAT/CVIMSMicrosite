<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Covid19Vaccine\Vaccinator;
use Response;
use Validator;
use DB;
use Gate;

class VaccinatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.vaccinator.index',['title' => "Vaccinator Management"]);
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
            'last_name'=> 'required',
            'first_name'=> 'required',
            'health_facility'=> 'required',
            'prc_license_number' => 'required',
            'profession'=> 'required',
            'role'=> 'required',
        ]);

        DB::connection('covid19vaccine')->beginTransaction();
        try {
            $vaccinator = new Vaccinator;
            $vaccinator->last_name = convertData($request["last_name"]);
            $vaccinator->first_name = convertData($request["first_name"]);
            $vaccinator->middle_name = convertData($request["middle_name"]);
            $vaccinator->suffix = convertData($request['suffix']);
            $vaccinator->prc_license_number = $request['prc_license_number'];
            $vaccinator->health_facilities_id = $request['health_facility'];
            $vaccinator->profession = convertData($request['profession']);
            $vaccinator->role = $request['role'];
            $vaccinator->status = 1;
            $changes = $vaccinator->getDirty();
            $vaccinator->save();
    
            DB::connection('covid19vaccine')->commit();
    
            /* logs */
            action_log('Vaccinator Mngt', 'CREATE', array_merge(['id' => $vaccinator->id], $changes));
    
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
    
            DB::connection('covid19vaccine')->rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
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
        $vaccinator = Vaccinator::find($id);
        
        return response::json($vaccinator);
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
            'edit_last_name'=> 'required',
            'edit_first_name'=> 'required',
            'edit_health_facility'=> 'required',
            'edit_prc_license_number' => 'required',
            'edit_profession'=> 'required',
            'edit_role'=> 'required',
        ]);
        DB::connection('covid19vaccine')->beginTransaction();
        try {
            $vaccinator = Vaccinator::where('id','=', $id)->first();
            $vaccinator->last_name = convertData($request["edit_last_name"]);
            $vaccinator->first_name = convertData($request["edit_first_name"]);
            $vaccinator->middle_name = convertData($request["edit_middle_name"]);
            $vaccinator->suffix = convertData($request['edit_suffix']);
            $vaccinator->prc_license_number = $request['edit_prc_license_number'];
            $vaccinator->health_facilities_id = $request['edit_health_facility'];
            $vaccinator->profession = convertData($request['edit_profession']);
            $vaccinator->role = $request['edit_role'];
            $vaccinator->status = 1;
            $changes = $vaccinator->getDirty();
            $vaccinator->save();
    
            DB::connection('covid19vaccine')->commit();
    
            /* logs */
            action_log('Vaccinator Mngt', 'UPDATE', array_merge(['id' => $vaccinator->id], $changes));
    
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
    
            DB::connection('covid19vaccine')->rollBack();
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
    
    //for combo box
    public function findAllVaccinator()
    {
        $vaccinator = Vaccinator::where('status', 1)->orderBy('last_name')->get();
        
        return response()->json($vaccinator);
    }
    
    //vaccinator datatable
    public function findAll(Request $request)
    {
        $columns = array( 
            0=> 'last_name',
            1=> 'last_name',
            2=> 'status',
        );

        $totalData = Vaccinator::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $vaccinators = Vaccinator::join('health_facilities', 'health_facilities.id', '=' , 'vaccinators.health_facilities_id')
                        ->select('vaccinators.*', 'health_facilities.facility_name')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 
            
            $vaccinators = Vaccinator::join('health_facilities', 'health_facilities.id', '=' , 'vaccinators.health_facilities_id')
            ->select('vaccinators.*', 'health_facilities.facility_name')
            ->where('vaccinators.last_name', 'LIKE',"%{$search}%")
            ->orWhere('vaccinators.first_name', 'LIKE',"%{$search}%")
            ->orWhere('vaccinators.middle_name', 'LIKE',"%{$search}%")
            ->orWhere('health_facilities.facility_name', 'LIKE',"%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Vaccinator::join('health_facilities', 'health_facilities.id', '=' , 'vaccinators.health_facilities_id')
            ->select('vaccinators.*', 'health_facilities.facility_name')
            ->where('vaccinators.last_name', 'LIKE',"%{$search}%")
            ->orWhere('vaccinators.first_name', 'LIKE',"%{$search}%")
            ->orWhere('vaccinators.middle_name', 'LIKE',"%{$search}%")
            ->orWhere('health_facilities.facility_name', 'LIKE',"%{$search}%")
            ->count();
        }
        $buttons = "";
        $data = array();
        if(!empty($vaccinators))
        {
            foreach ($vaccinators as $vaccinator)
            {   
                $btnEdit = $btnToggle = $role = '';
                if($vaccinator['status'] == '1'){
                    if(Gate::allows('permission', 'updateVaccinator')){
                        $btnEdit = '<a href="#" data-toggle="tooltip" title="Click to edit vaccinator." onclick="edit('. $vaccinator['id'] . ')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="ti ti-pencil-alt" aria-hidden="true"></i> Edit</a></button> ';
                    }
                    
                    if(Gate::allows('permission', 'deleteVaccinator')){
                        $btnToggle = '<a href="#" data-toggle="tooltip" title="Click to delete vaccinator." onclick="deactivate('. $vaccinator['id'] . ')" class="btn btn-xs btn-danger btn-fill btn-rotate edit"><i class="ti ti-trash" aria-hidden="true"></i> Delete</a></button> ';
                    }
                }else{
                    if(Gate::allows('permission', 'restoreVaccinator')){
                        $btnToggle = '<a href="#" data-toggle="tooltip" title="Click to restore vaccinator." onclick="restore('. $vaccinator['id'] . ')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="ti ti-reload" aria-hidden="true"></i> Restore</a></button> ';
                    }
                }
                $buttons = $btnEdit . " " . $btnToggle;
            
                $status = ($vaccinator['status'] == "1") ?  "<label class='label label-success'><i class='fa fa-check-circle' aria-hidden='true'></i> Active</label>" : "<label class='label label-danger'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> Deleted</label>";
                
                
                $middleName = "";
                if($vaccinator->middle_name != "NA"){$middleName = $vaccinator->middle_name;}
                $fullname = $vaccinator->last_name . " ". $vaccinator->affiliation . ", ". $vaccinator->first_name . " ". $middleName;
                    
                if($vaccinator['role'] == "Team_Lead"){
                    $role = "Team Lead";
                }else if($vaccinator['role'] == "Counseling_Nurse"){
                    $role = "Counseling Nurse";
                }else{
                    $role = "Encoder";
                }
                
                
                $nestedData['fullname'] = $fullname;
                $nestedData['status'] = $status;
                $nestedData['prc_license_number'] = $vaccinator['prc_license_number'];
                $nestedData['profession'] = $vaccinator['profession'];
                $nestedData['role'] = $role;
                $nestedData['facility_name'] = $vaccinator['facility_name'];
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
    
    public function togglestatus($id){
        try {
            DB::beginTransaction();

            $vaccinator = Vaccinator::findOrFail($id);
            $status = $vaccinator->status;
            if($status == 1){
                $vaccinator->status = 0;
                $action = 'DELETED';
            }
            else{
                $vaccinator->status = 1;
                $action = 'RESTORE';
            }
            $changes = $vaccinator->getDirty(); 
            $vaccinator->save();

            DB::commit();

            /* logs */
            action_log('Vaccinator Mngt', $action, array_merge(['id' => $vaccinator->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
