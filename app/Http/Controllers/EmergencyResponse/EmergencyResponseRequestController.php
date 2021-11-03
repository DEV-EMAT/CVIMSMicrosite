<?php

namespace App\Http\Controllers\EmergencyResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmergencyResponse\EmergencyResponseRequest;
use App\User;
use App\Ecabs\Person;
use DB;
use Response;
use Gate;

class EmergencyResponseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('emergencyresponse.emergency_response_request.index', ['title' => 'Emergency Response Request']);
    }

    public function map()
    {
        return view('emergencyresponse.emergency_response_request.map', ['title' => 'Emergency Response Request']);
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

    public function findAllIncidentRequest(){
        $query = DB::table(connectionName('emergencyresponse') . '.emergency_response_requests')
        ->join(connectionName('emergencyresponse') . '.incident_categories', 'incident_categories.id', '=', 'emergency_response_requests.incidentcat_id')
        ->join(connectionName('mysql') . '.users', 'users.id', '=', 'emergency_response_requests.user_id') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('emergency_response_requests.*', 'people.last_name','people.first_name','incident_categories.description') 
        ->where('users.account_status', 1)
        ->where('emergency_response_requests.incident_status',"ALARMING")->get();
        echo json_encode($query); 
    }

    public function findAll(Request $request)
    {
        $columns = array(
            0 =>'contact_number',
            1=> 'incident_location',
            2=> 'status',
            3=> 'actions',
        );
        $totalData = EmergencyResponseRequest::count();
        $status = 1;       
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('emergencyresponse') . '.emergency_response_requests') 
        ->join(connectionName('mysql') . '.users', 'users.id', '=', 'emergency_response_requests.user_id') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('emergency_response_requests.*', 'people.last_name','people.first_name') 
        ->where('users.account_status', 1);

        if(empty($request->input('search.value')))
        {            
            $emergencyResponseRequest = $query
                         ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $emergencyResponseRequest = $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
            ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
            ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
            $totalFiltered= $query->count();
        }
        $data = array();
        if(!empty($emergencyResponseRequest))
        {
            foreach ($emergencyResponseRequest as $emergencyResponseRequestValue)
            {   
                if($emergencyResponseRequestValue->incident_status == 'ALARMING')
                {
                    $alertIcon = '<div class="adjust"><div class="loader"></div></div>';
                    $buttons = '<a href="#" title="Edit" onclick="flyToArea('. $emergencyResponseRequestValue->id.')" class="btn btn-xs btn-danger btn-fill btn-rotate edit"> RESPONSE NOW</a></button>';
                }
                if($emergencyResponseRequestValue->incident_status == 'RESPONSED')
                {
                    $alertIcon = "<label class='label label-primary'><b>RESPONSED</b></label>";
                    $buttons = "--";
                }
                if($emergencyResponseRequestValue->incident_status == 'RESOLVED')
                {
                    $alertIcon = "<label class='label label-success'><b>RESOLVED</b></label>";
                    $buttons = '<a href="#" title="Edit" onclick="edit('. $emergencyResponseRequestValue->id .')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"> SAVE NOW</a></button>';
                }
                $queryData = User::join('people', 'people.id', 'users.person_id')->where('users.id','=', $emergencyResponseRequestValue->user_id)->first();
                $fullName = $queryData->last_name. ", " .$queryData->first_name." ".$queryData->middle_name;
                //$long = json_decode($emergencyResponseRequestValue->incident_location);
               
                $latLongJson = json_decode($emergencyResponseRequestValue->incident_location,true);
               
                $latLong = array($latLongJson['latitude'],$latLongJson['longitude']);
                $nestedData['id'] = $emergencyResponseRequestValue->id;
                $nestedData['requestant'] = $fullName;
                $nestedData['contact_number'] = $emergencyResponseRequestValue->contact_number;
                $nestedData['incident_location'] = $latLong;
                $nestedData['incident_status'] = '<span><b>'.$emergencyResponseRequestValue->incident_status.'</b></span>';
                $nestedData['status'] = $alertIcon;
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


    public function locatorData(Request $request)
    {
        $columns = array(
            0 =>'contact_number',
            1=> 'incident_location',
            2=> 'status',
            3=> 'actions',
        );
        $totalData = EmergencyResponseRequest::count();
        $status = 1;       
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('emergencyresponse') . '.emergency_response_requests') 
        ->join(connectionName('mysql') . '.users', 'users.id', '=', 'emergency_response_requests.user_id') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('emergency_response_requests.*', 'people.last_name','people.first_name') 
        ->where('users.account_status', 1);

        if(empty($request->input('search.value')))
        {            
            $emergencyResponseRequest = $query
                         ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $emergencyResponseRequest = $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
            ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
            ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
            $totalFiltered= $query->count();
        }
        $data = array();
        if(!empty($emergencyResponseRequest))
        {
            foreach ($emergencyResponseRequest as $emergencyResponseRequestValue)
            {   
                if($emergencyResponseRequestValue->incident_status == 'ALARMING')
                {
                    $latLong = $emergencyResponseRequestValue->incident_location;
                    $contact = $emergencyResponseRequestValue->contact_number;
                    $alertIcon = '<div class="adjust"><div class="loader"></div></div>';
                    $buttons = "<a href='#' title='Edit' onclick='myGeocoding.flyToArea(".$latLong.")' class='btn btn-xs btn-info btn-fill btn-rotate edit'><i class='fa fa-map'></i></a>";
                    $contactButton = "<a href='#' title='Edit' onclick='contactPerson(".$contact."') class='btn btn-xs btn-danger btn-fill btn-rotate edit'><i class='fa fa-phone'></i></a>";
                    $locatorButton = $buttons. "|" .$contactButton;
                }
                if($emergencyResponseRequestValue->incident_status == 'RESPONSED')
                {
                    $alertIcon = "<label class='label label-primary'><b>RESPONSED</b></label>";
                    $buttons = "--";
                }
                if($emergencyResponseRequestValue->incident_status == 'RESOLVED')
                {
                    $alertIcon = "<label class='label label-success'><b>RESOLVED</b></label>";
                    $buttons = '<a href="#" title="Edit" onclick="edit('. $emergencyResponseRequestValue->id .')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"> SAVE NOW</a></button>';
                }
                $queryData = User::join('people', 'people.id', 'users.person_id')->where('users.id','=', $emergencyResponseRequestValue->user_id)->first();
                $fullName = $queryData->last_name. ", " .$queryData->first_name." ".$queryData->middle_name;
                $nestedData['requestant'] = $fullName;
                $nestedData['actions'] = $locatorButton;
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
}
