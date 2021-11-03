<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use App\Events\DataTableEvent;
use Illuminate\Http\Request;
use App\CovidTracer\Investigator;
use App\User;
use DB;

class InvestigatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covidtracer/investigator.index', ['title' => "Investigator Management"]);
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

    /* find all for combobox */
    public function findAllInvestigatorForCombobox(){
        $connectionName = connectionName('mysql');
        
        $investigator = Investigator::select('investigators.id', 'people.first_name as first_name', 'people.last_name as last_name', 'people.middle_name as middle_name', 'people.affiliation as suffix')
                ->join($connectionName.'.users as users', 'users.id', '=', 'investigators.user_id')
                ->join($connectionName.'.people as people', 'users.person_id', '=', 'people.id')->get();

        return response()->json($investigator);
    }

    /* use on investigator monitoring */
    public function findAllInvestigator(request $request)
    {
        $columns = array(
            0 =>'investigators.id',
            1 =>'address',
        );

        $totalData = Investigator::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $connectionName = connectionName('mysql');
        
        $query = Investigator::select('investigators.id', 'people.first_name', 'people.last_name', 'people.middle_name', 'people.affiliation', 'people.address', 'users.contact_number')->join($connectionName.'.users as users', 'users.id', '=', 'investigators.user_id')
                ->join($connectionName.'.people as people', 'users.person_id', '=', 'people.id');
        
        if(empty($request->input('search.value')))
        {
            $results =  $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

                // dd($results[0]->user->email);
                  
        }
        else {
            $search = $request->input('search.value');

            $results = $query->where('people.last_name', 'LIKE',"%{$search}%")->orWhere('people.first_name', 'LIKE',"%{$search}%")->orWhere('people.middle_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered =  $query->where('people.last_name', 'LIKE',"%{$search}%")->orWhere('people.first_name', 'LIKE',"%{$search}%")->orWhere('people.middle_name', 'LIKE',"%{$search}%")->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {

                $suffix =  (!empty($result->affiliation))? " ".$result->affiliation: '';
                $fullname = ucfirst($result->last_name) .$suffix .', '. ucfirst($result->first_name) .' '. ucfirst($result->middle_name);
                $buttons = '<a data-toggle="tooltip" title="Click here to view History" onclick="history('. $result->id .', \''. $fullname .'\')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="fa fa-line-chart"></i> HISTORY</a> <a data-toggle="tooltip" title="Click here to add new Daily Activity Monitoring" onclick="monitor('. $result->id .', \''. $fullname .'\')"  class="btn btn-xs btn-warning btn-fill btn-rotate remove"><i class="fa fa-edit"></i> MONITOR</a>';
               
                $nestedData['investigator_id'] = $result->id;
                $nestedData['fullname'] =  $fullname;
                $nestedData['contact'] =  $result->contact_number;
                $nestedData['address'] =  $result->address;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /* use on investigator */
    public function findall(Request $request)
    {
        $columns = array( 
            0 =>'first_name',
            1 =>'last_name', 
            2 =>'address',
            3 =>'status',
        );

        $totalData = Investigator::where('investigator_status', '=', 1)->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {    
            $investigators = Investigator::join(connectionName() . '.users as users', 'investigators.user_id', connectionName() . '.users.id')
                            ->join(connectionName() . '.people as people', connectionName() . '.users.person_id', connectionName() . '.people.id')
                            ->join(connectionName() . '.addresses as addresses', connectionName() . '.people.address_id', connectionName() . '.addresses.id')
                            ->select(
                                'investigators.id AS id',
                                'people.first_name',
                                'people.first_name',
                                'people.last_name',
                                'people.address',
                                'addresses.barangay',
                                'addresses.city',
                                'addresses.province',
                                'investigators.investigator_status'
                            )->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->where('users.account_status', '=', 1)
                            ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = Investigator::join(connectionName() . '.users as users', 'investigators.user_id', 'users.id')
                        ->join(connectionName() . '.people as people', 'users.person_id', 'people.id')
                        ->join(connectionName() . '.addresses as addresses', 'people.address_id', 'addresses.id')
                        ->select(
                            'investigators.id AS id',   
                            'people.first_name',
                            'people.last_name',
                            'people.address',
                            'addresses.barangay',
                            'addresses.city', 
                            'addresses.province',
                            'investigators.investigator_status'
                            )->where('people.first_name','LIKE',"%{$search}%")
                            ->orWhere('people.last_name', 'LIKE', "%{$search}%")
                            ->orWhere('people.address', 'LIKE', "%{$search}%")
                            ->orWhere('addresses.barangay', 'LIKE', "%{$search}%")
                            ->orWhere('addresses.city', 'LIKE', "%{$search}%")
                            ->orWhere('addresses.province', 'LIKE', "%{$search}%")    
                            ->where('users.account_status', '=', 1);

            $investigators =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($investigators))
        {
            foreach ($investigators as $investigator)
            {  
                $buttons = '';

                if($investigator['investigator_status'] == '1'){

                        $buttons .= '<a data-toggle="tooltip" title="Click here to remove Investigator" onclick="deactivate('. $investigator['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> Remove Investigator</a>';    
                        
                        $status = "<label class='label label-primary'>Active</label>";
                }
                else{
                    
                        $buttons .= '<a data-toggle="tooltip" title="Click here to add Investigator" onclick="activate('. $investigator['id'] .')"  class="btn btn-xs btn-success btn-fill remove"><i class="ti-plus"></i> Add Investigator</a>';
                        
                        $status = "<label class='label label-danger'>Deleted</label>";
                }
                
                $nestedData['full_name'] = $investigator->first_name . " " . $investigator->last_name;
                $nestedData['address'] = $investigator->address . ", " . $investigator->barangay . ", " . $investigator->city . ", " . $investigator->province;
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

    /* use on selecting a new investigator */
    public function findAllUsers(Request $request)
    {
        $columns = array( 
            0 =>'first_name',
            1 =>'last_name',
            2 =>'status',
        );

        $totalData = User::where('id', '!=', 1)->where('account_status', '=', 1)->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $users = User::join('people', 'users.person_id', 'people.id')
                        ->leftjoin(connectionName('covid_tracer') . '.investigators as investigators', 'users.id', 'investigators.user_id')
                        ->select(
                            'users.id',
                            'people.first_name',
                            'people.last_name',
                            'investigators.id AS investigator_id',
                            'investigators.investigator_status'
                        )->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->where('users.account_status', '=', 1)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = User::join('people', 'users.person_id', 'people.id')
                            ->leftjoin(connectionName('covid_tracer') . '.investigators as investigators', 'users.id', 'investigators.user_id')
                            ->select(
                                'users.id',
                                'people.first_name',
                                'people.last_name',
                                'investigators.id AS investigator_id',
                                'investigators.investigator_status'
                            )->where('first_name','LIKE',"%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->where('users.account_status', '=', 1);

            $users =  $query->offset($start)
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
                $buttons = '';

                if($user['investigator_status'] == '1'){

                        $buttons .= '<a disabled class="btn btn-xs btn-primary btn-fill plus"><i class="ti-plus"></i> Add Investigator</a>';

                        $status = "<label class='label label-primary'>Active Investigator</label>";
                }
                else{
                    
                        $buttons .= '<a data-toggle="tooltip" title="Click here to add Investigator" onclick="addInvestigator('. $user['id'] .')"  class="btn btn-xs btn-success btn-fill plus"><i class="ti-plus"></i> Add Investigator</a>';
                        
                        $status = "<label class='label label-danger'>Not Selected</label>";
                }
                
                $nestedData['full_name'] = $user->first_name . " " . $user->last_name;
                $nestedData['address'] = $user->address;
                $nestedData['status'] = $status;
                $nestedData['actions'] = $buttons;
                ($user->id != 1)? $data[] = $nestedData : array();
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
    
    //toggle status
    public function togglestatus($id){
        try {
            DB::beginTransaction();
            $action = '';
            $investigator = Investigator::findOrFail($id);
            
            $status = $investigator->investigator_status;
            
            if($status == 1){
                $investigator->investigator_status = 0;
                $action = 'DELETED';
            }
            else{
                $investigator->investigator_status = 1;
                $action = 'RESTORE';
            }
            $changes = $investigator->getDirty();
            $investigator->save();
            
            /* logs */
            action_log('Investigator Mngt', $action, array_merge(['id' => $investigator->id], $changes));

            DB::commit();
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'. $e));
        }
    }

    //add investigator
    public function addInvestigator($id){
        try {
            DB::beginTransaction();
            
            $investigator = Investigator::where('user_id', '=', $id)->first();
            if(is_null($investigator))
            {
                $new_investigator = new Investigator();
                $new_investigator->user_id = $id;
                $new_investigator->investigator_status = 1;
                $changes = $new_investigator->getDirty();
                $new_investigator->save();
                
                /* logs */
                action_log('Investigator Mngt', 'CREATE', array_merge(['id' => $new_investigator->id], $changes));
            } else {
                $status = $investigator->investigator_status;
                if($status == 1){
                    $investigator->investigator_status = 0;
                    $action = 'DELETED';
                }
                else{
                    $investigator->investigator_status = 1;
                    $action = 'RESTORE';
                }
                $changes = $investigator->getDirty();
                $investigator->save();
            }

            DB::commit();
            
            /* logs */
            action_log('Investigator Mngt', 'DELETED', array_merge(['id' => $investigator->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'. $e));
        }
    }
}
