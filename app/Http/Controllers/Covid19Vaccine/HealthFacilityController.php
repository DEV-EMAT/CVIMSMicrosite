<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Covid19Vaccine\HealthFacility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Covid19\HealthFacilitator;
use App\Covid19Vaccine\UserHasFacility;
use Response;
use Validator;
use DB;
use Gate;
use App\User;

class HealthFacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.health_facility.index',['title' => "Health Facility Management"]);
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
            'facility_name'=> 'required',
        ]);

        DB::connection('covid19vaccine')->beginTransaction();
        try {
            $healthFacility = new HealthFacility;
            $healthFacility->facility_name = convertData($request["facility_name"]);
            $healthFacility->address = convertData($request["address"]);
            $healthFacility->status = 1;
            $changes = $healthFacility->getDirty();
            $healthFacility->save();
    
            DB::connection('covid19vaccine')->commit();
    
            /* logs */
            action_log('Health Facility Mngt', 'CREATE', array_merge(['id' => $healthFacility->id], $changes));
    
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
        $healthFacility = HealthFacility::find($id);
        
        return response::json($healthFacility);
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
            'edit_facility_name'=> 'required',
        ]);

        DB::connection('covid19vaccine')->beginTransaction();
        try {
            $healthFacility = HealthFacility::where('id', '=', $id)->first();
            $healthFacility->facility_name = convertData($request["edit_facility_name"]);
            $healthFacility->address = convertData($request["edit_address"]);
            $changes = $healthFacility->getDirty();
            $healthFacility->save();
    
            DB::connection('covid19vaccine')->commit();
    
            /* logs */
            action_log('Health Facility Mngt', 'UPDATE', array_merge(['id' => $healthFacility->id], $changes));
    
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
    public function findAllFacility()
    {
        $healthFacility = HealthFacility::where('status', 1)->orderBy('facility_name')->get();
        
        return response()->json($healthFacility);
    }
    
    //health facility datatable
    public function findAll(Request $request)
    {
        $columns = array( 
            0=> 'facility_name',
            1=> 'status',
        );

        $totalData = HealthFacility::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $healthFacilities = HealthFacility::offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 
            
            $healthFacilities = HealthFacility::where('health_facilities.facility_name', 'LIKE',"%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = HealthFacility::where('health_facilities.facility_name', 'LIKE',"%{$search}%")
            ->count();
        }
        $buttons = "";
        $data = array();
        if(!empty($healthFacilities))
        {
            foreach ($healthFacilities as $healthFacility)
            {   
                $btnEdit = $btnToggle = $btnAddUser = '';
                if($healthFacility['status'] == '1'){
                    if(Gate::allows('permission', 'updateHealthFacility')){
                        $btnEdit = '<a href="#" data-toggle="tooltip" title="Click to edit health facility." onclick="edit('. $healthFacility['id'] . ')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="ti ti-pencil-alt" aria-hidden="true"></i> Edit</a></button> ';
                    }
                    
                    if(Gate::allows('permission', 'deleteHealthFacility')){
                        $btnToggle = '<a href="#" data-toggle="tooltip" title="Click to delete health facility." onclick="deactivate('. $healthFacility['id'] . ')" class="btn btn-xs btn-danger btn-fill btn-rotate edit"><i class="ti ti-trash" aria-hidden="true"></i> Delete</a></button> ';
                    }
                }else{
                    if(Gate::allows('permission', 'restoreHealthFacility')){
                        $btnToggle = '<a href="#" data-toggle="tooltip" title="Click to restore health facility." onclick="restore('. $healthFacility['id'] . ')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="ti ti-reload" aria-hidden="true"></i> Restore</a></button> ';
                    }
                }

                if(Gate::allows('permission', 'viewAssignStaff')){
                    $user_facility = UserHasFacility::select('user_id')->where('facility_id', '=', $healthFacility['id'])->where('status', '=', '1')->get()->toArray();
                    $user_facility = array_map(function($data){
                        return $data['user_id'];
                    }, $user_facility);

                    $btnAddUser = '<a href="#" data-toggle="tooltip" title="Click to user to health facility." onclick="assignUser(['. (($user_facility)? "'".implode("','", $user_facility)."'" : "") .'],'. $healthFacility['id'] . ')" class="btn btn-xs btn-warning btn-fill btn-rotate edit"><i class="fa fa-plus" aria-hidden="true"></i> Assign User</a></button> ';
                }

                $buttons = $btnEdit . " " . $btnToggle. " " .$btnAddUser;
            
                $status = ($healthFacility['status'] == "1") ?  "<label class='label label-success'><i class='fa fa-check-circle' aria-hidden='true'></i> Active</label>" : "<label class='label label-danger'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> Deleted</label>";
                
                $nestedData['facility_name'] = $healthFacility['facility_name'];
                $nestedData['description'] = $healthFacility['description'];
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
    
    public function togglestatus($id){
        try {
            DB::beginTransaction();

            $healthFacility = HealthFacility::findOrFail($id);
            $status = $healthFacility->status;
            if($status == 1){
                $healthFacility->status = 0;
                $action = 'DELETED';
            }
            else{
                $healthFacility->status = 1;
                $action = 'RESTORE';
            }
            $changes = $healthFacility->getDirty(); 
            $healthFacility->save();

            DB::commit();

            /* logs */
            action_log('Health Facility Mngt', $action, array_merge(['id' => $healthFacility->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    //start of find people by id
    public function findUserById($id)
    {
        $user = User::join('people', 'people.id', '=', 'users.person_id')
        ->where('users.id',$id)
        ->select(
            'users.id',
            'users.contact_number',
            'users.email',
            'people.first_name',
            'people.last_name',
            'people.middle_name',
            'people.affiliation',
            'people.date_of_birth',
            'people.person_code',
            'people.address',
            'people.civil_status',
            'people.telephone_number',
            'people.religion',
            'people.image',
            'people.gender'
            )->get();
        return json_encode($user);
    }
    //start of find people by id


    //start of get all profile
    public function findAllUsers(Request $request){
        $columns = array( 
            0 =>'last_name', 
            1 =>'fistname',
            2 =>'position',
            3 =>'status',
        );
        $status = 1;
        //datatables total data
        $totalData = User::where('account_status', $status)->where('id', '!=', '1')->count();   
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        
        $query = DB::table('users') 
            ->join('people', 'people.id', '=', 'users.person_id')
            ->select('users.*', 'people.*', 'users.id as user_id', 'people.id as people_id')
            ->where('users.account_status', $status)
            ->where('users.id', '!=', '1');
            

        
        if(empty($request->input('search.value')))
        {            
            $users = $query
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else {
        $search = $request->input('search.value'); 

        $users= $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $account = User::where('person_id', $user->id)->first();

                $buttons = ' <a onclick="addProfile('. $account->user_id .')" class="btn btn-xs btn-warning btn-fill btn-rotate view"><i class="fa fa-plus"></i> Add this profile</a>';
                $fullname = $user->last_name . " ". $user->affiliation . ", ". $user->first_name . " ". $user->middle_name;
                $status = ($account->account_status == "1") ?  "<label class='label label-success'><i class='fa fa-check-circle' aria-hidden='true'></i> Active</label>" : "<label class='label label-danger'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> Deleted</label>";
                $nestedData['fullname'] = $fullname;
                $nestedData['id'] = $user->user_id;
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

    public function assignUser(Request $request){
        $user_id = json_decode($request['assinged_user'], true);
        UserHasFacility::where('facility_id', '=', $request['facility_id'])->update(array('status' => '0'));
        
        try {
            DB::beginTransaction();

            foreach ($user_id as $value) {
                $is_exist = UserHasFacility::where('facility_id', '=', $request['facility_id'])->where('user_id', '=', $value)->first();
                
                if(empty($is_exist)){
                    $assign = new UserHasFacility;
                    $assign->user_id = $value;
                    $assign->facility_id = $request['facility_id'];
                    $assign->status = "1";
                    $assign->save();
                    DB::commit();
                }else{

                    $is_exist->status = 1;
                    $is_exist->save();
                    DB::commit();
                }
            }

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }



}
