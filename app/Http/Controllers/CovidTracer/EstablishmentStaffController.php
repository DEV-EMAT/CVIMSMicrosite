<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\EstablishmentStaff;
use App\CovidTracer\EstablishmentInformation;
use App\Ecabs\Person;
use App\User;
use DB;
use App\Events\DataTableEvent;
use Hash;
use Gate;

class EstablishmentStaffController extends Controller
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
        try {
            $action = '';
            DB::beginTransaction();
            $oldStaff = EstablishmentStaff::where('establishment_information_id', '=', $request["staffEstInfoId"])->where('staff_status', '=', '1')->get();     
            foreach($oldStaff as $old){
                $staff = EstablishmentStaff::findOrFail($old->id);
                $staff->staff_status = 0;
                $changes = $staff->getDirty();
                $staff->save();
            }

            foreach($request['staffId'] as $id){
                $staffExists = EstablishmentStaff::where('user_id', '=', $id)->where('establishment_information_id', '=', $request["staffEstInfoId"])->first();
                if(!$staffExists){
                    $staff = new EstablishmentStaff;
                    $staff->establishment_information_id = $request["staffEstInfoId"];
                    $staff->user_id = $id;
                    $staff->staff_status = 1;
                    $changes = array_merge($staff->getDirty());
                    $action = 'Update';
                    $staff->save();
                }else{
                    // $staff = EstablishmentStaff::where('user_id', '=', $id)->where('establishment_information_id', '=', $request["staffEstInfoId"])->first();
                    $staffExists->establishment_information_id = $request["staffEstInfoId"];
                    $staffExists->user_id = $id;
                    $staffExists->staff_status = 1;
                    $changes = array_merge($staff->getDirty());
                    $staffExists->save();
                    $action = 'Update';
                }
            }
            DB::commit();

            /* logs */
            action_log('Establishment Staff Mngt', $action, array_merge(['id' => (($action == 'Update')? $staffExists->id: $staff->id)], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
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
        return response()->json(EstablishmentStaff::select('user_id')->where('establishment_information_id', '=', $id)->where('staff_status','=', '1')->get());
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

    public function findAll(Request $request)
    {
        $columns = array( 
            0 =>'last_name', 
        );

        //datatables total data
        $totalData = User::where('account_status', '1')->count();   

        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $establishment = EstablishmentInformation::findOrFail($request['establishmentId']);
        $query = DB::table(connectionName('mysql') . '.users') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('people.*', 'users.id as user_id')
        ->where('users.account_status', '1')->where('users.id', '!=', '1')->where('users.id', '!=', $establishment->owner_id);
        
        if(empty($request->input('search.value')))
        {            
            $people = $query
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $people= $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')->where('users.id', '!=', '1')->where('users.id', '!=', $establishment->owner_id)
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')->where('users.id', '!=', '1')->where('users.id', '!=', $establishment->owner_id)
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')->where('users.id', '!=', '1')->where('users.id', '!=', $establishment->owner_id)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query
                ->count();
        }

        $data = array();
        $counter = 0;
        if(!empty($people))
        {
            foreach ($people as $person)
            {
                $flag = false;
                $staffs = EstablishmentStaff::select('user_id')->where('establishment_information_id', '=', $request['establishmentId'])->where('staff_status','=', '1')->get();
                foreach($staffs as $val => $staff){
                    if($person->user_id == $staff['user_id']){
                        $flag = true;
                        $counter++;
                        break;
                    }
                }

                $user = User::where('person_id', $person->id)->first();

                $buttons = ' <a onclick="view('. $user->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW</a>';
                if($user->account_status == 1){
                    
                    if(Gate::allows('permission', 'updateAccount')){
                        $buttons .=' <a onclick="edit('. $user->id .')" class="btn btn-xs btn-success btn-fill btn-rotate  edit"><i class="ti-pencil-alt"></i> EDIT</a>';
                    }

                    if(Gate::allows('permission', 'deleteAccount')){
                        $buttons .= ' <a onclick="deactivate('. $user->id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>'; 
                    }  
                    
                    $status = "<label class='label label-primary'>Active</label>";
                }
                else{
                    $buttons .= ' <a onclick="activate('. $user->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate restore"><i class="ti-reload"></i> RESTORE</a>';  
                }

                $fullname = $person->last_name . " ". $person->affiliation . ", ". $person->first_name . " ". $person->middle_name;

                if($flag == false){
                    $nestedData['fullname'] = $fullname;
                    $nestedData['id'] = $user->id;
                    $data[] = $nestedData;
                }
            }
        }

        $pagedArray = array_slice($data, $start, $limit);
        $totalData = count($data);
        $totalFiltered = count($data);

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $pagedArray   
            );
            
        echo json_encode($json_data); 
    }

    //view current staff of establishment
    public function findAllStaff(Request $request)
    {
        $columns = array( 
            0 =>'last_name', 
            1 =>'status',
        );

        //datatables total data
        $totalData = EstablishmentStaff::where('establishment_information_id', '=', $request['establishmentId'])->count();  

        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('mysql') . '.users') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->join(connectionName('covid_tracer') . '.establishment_staff', 'establishment_staff.user_id', '=', 'users.id')
        ->select('people.*', 'establishment_staff.user_id as staff_id' )
        ->where('users.account_status', '1')->where('users.id', '!=', '1')
        ->where('establishment_staff.establishment_information_id', '=', $request['establishmentId'])
        ->where('establishment_staff.staff_status', '=', '1');
        
        if(empty($request->input('search.value')))
        {            
            $people = $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $people= $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query
                ->count();
        }

        $data = array();
        $counter = 0;
        if(!empty($people))
        {
            foreach ($people as $person)
            {
                $user = User::where('person_id', $person->id)->first();
                $buttons = '';
                $status = "<label class='label label-danger'>Deleted</label>";
                
                if(Gate::allows('permission', 'deleteEstStaff')){
                    $buttons = ' <a onclick="removeStaff('. $person->staff_id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> REMOVE EMPLOYEE</a>'; 
                }

                $fullname = $person->last_name . " ". $person->affiliation . ", ". $person->first_name . " ". $person->middle_name;

                $nestedData['fullname'] = $fullname;
                $nestedData['buttons'] = $buttons;
                $nestedData['id'] = $user->id;
                $data[] = $nestedData;
            }
        }

        $pagedArray = array_slice($data, $start, ($limit == "-1")? count($data):$limit);

        $totalData = count($data);
        $totalFiltered = count($data);

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $pagedArray   
            );
            
        echo json_encode($json_data); 
    }

    public function removeStaff(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $staff = EstablishmentStaff::where('user_id', '=', $id)->where('establishment_information_id', '=', $request['establishmentId'])->where('staff_status', '=', '1')->first();
            $staff->staff_status = '0';
            $changes = $staff->getDirty();
            $staff->save();
            
            DB::commit();

            /* logs */
            action_log('Establishment staff Mngt', 'Update', array_merge(['id' => $staff->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
