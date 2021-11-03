<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use App\CovidTracer\SmsNotification;
use App\CovidTracer\SmsHistory;
use App\Ecabs\Person;
use App\User;
use Illuminate\Http\Request;
use Response;
use DB;
use Gate;
use Auth;

class SmsNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covidtracer/sms_notification.index', ['title' => 'Sms Notification Management']);
    }

    public function history()
    {
        return view('covidtracer/sms_notification.history', ['title' => 'Sms History']);
    }

    public function findAll(Request $request)
    {
        $columns = array( 
            0 =>'description', 
            1 =>'status',
        );

        $totalData = SmsNotification::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $smsNotifications = SmsNotification::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = SmsNotification::where('description','LIKE',"%{$search}%");

            $smsNotifications =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($smsNotifications))
        {
            foreach ($smsNotifications as $smsNotification)
            {  
                $buttons = '';  
                
                if($smsNotification['status'] == '1'){
                    if(Gate::allows('permission', 'updateSmsNotification')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to edit SMS Notification" onclick="edit('. $smsNotification['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ' ;
                    }

                    if(Gate::allows('permission', 'deleteSmsNotification')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to remove SMS Notification" onclick="deactivate('. $smsNotification['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';    
                    }
                    $status = "<label class='label label-primary'>Active</label>";
                }
                else{
                    if(Gate::allows('permission', 'restoreSmsNotification')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to restore SMS Notification" onclick="activate('. $smsNotification['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }
                
                $nestedData['description'] = $smsNotification->description;
                $nestedData['message'] = $smsNotification->message;
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

    public function findHistory(Request $request)
    {
        $columns = array( 
            // 0 =>'last_name', 
            0 =>'status',
        );
        
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // $query = SmsHistory::where('receiver', '=', $request['receiverId']);
        
        $query = DB::table(connectionName('covid_tracer') . '.sms_histories') 
        ->join(connectionName('mysql') . '.users', 'users.id', '=', 'sms_histories.receiver')
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('sms_histories.*')
        ->where('receiver', '=', $request['receiverId']);

        if(empty($request->input('search.value')))
        {            
            $smsHistories = $query->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $smsHistories = $query->where('people.last_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                            ->orWhere('people.first_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                            ->orWhere('people.middle_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }

        $totalData = $smsHistories->count();

        $totalFiltered = $totalData; 

        $data = array();
        if(!empty($smsHistories))
        {
            foreach ($smsHistories as $smsHistory)
            {  
                $buttons = ''; 
                
                $personReceiver = Person::findOrFail($smsHistory->receiver);
                $personSender = Person::findOrFail($smsHistory->sender);
                if($smsHistory->status == '1'){
                    $status = "<label class='label label-primary'>Success</label>";
                }
                else{
                    $status = "<label class='label label-danger'>Failed</label>";
                }

                $message = '<div id="acordeon">' .
                    '<div class="panel-group" id="accordion">' .
                        '<div class="panel panel-border panel-default">' .
                            '<a data-toggle="collapse" href="#collapseOne' . $smsHistory->id .  '" class="collapsed" aria-expanded="false">' .    
                                '<div class="panel-heading">' .
                                    '<h4 class="panel-title">' .
                                        'MESSAGE' .
                                        '<i class="ti-angle-down"></i>' .
                                    '</h4>' .
                                '</div>' .
                            '</a>' .
                            '<div id="collapseOne' . $smsHistory->id .  '" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">' .
                                '<div class="panel-body">' .
                                '<p>' . $smsHistory->message . '</p>' .
                                '</div>' .
                            '</div>' .
                        '</div>' .
                    '</div>' .
                '</div>';

                $receiver = $personReceiver->last_name . " ". $personReceiver->affiliation . ", ". $personReceiver->first_name . " ". $personReceiver->middle_name;
                $sender = $personSender->last_name . " ". $personSender->affiliation . ", ". $personSender->first_name . " ". $personSender->middle_name;

                $nestedData['sender'] = $sender;
                $nestedData['receiver'] = $receiver;
                $nestedData['message'] = $message;
                $nestedData['status'] = $status;
                $nestedData['date'] = explode(' ', $smsHistory->created_at)[0];
                $nestedData['time'] = explode(' ', $smsHistory->created_at)[1];
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

    public function findAllForComboBox()
    {
        $sms = SmsNotification::where('status', '1')->get();

        return response()->json($sms);
    }

    public function getMessage($id)
    {
        $sms = SmsNotification::where('id', $id)->first(); 

        return response()->json($sms);
    }

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
        $this -> validate ($request, [
            'description'=>'required',
            'message' => 'required',
        ]);
        
        try {
            DB::beginTransaction();

            $smsNotification = new SmsNotification();
            $smsNotification->description = convertData($request["description"]);
            $smsNotification->message = $request["message"];
            $smsNotification->status = 1;
            $changes = $smsNotification->getDirty();
            $smsNotification->save();

            DB::commit();

            /* logs */
            action_log('SMS Notification Mngt', 'CREATE', array_merge(['id' => $smsNotification->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Created!'));
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
        $smsNotification = SmsNotification::find($id);
        
        return response::json($smsNotification);
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
        $this -> validate ($request, [
            'description'=>'required',
            'message'=>'required',
        ]);
        try {
            DB::beginTransaction();
            
            $smsNotification = SmsNotification::findOrFail($id);
            
            $smsNotification->description = convertData($request["description"]);
            $smsNotification->message = $request["message"];
            $smsNotification->status = 1;
            $changes = $smsNotification->getDirty();
            $smsNotification->save();

            DB::commit();
            
            /* logs */
            action_log('SMS Notification Mngt', 'UPDATE', array_merge(['id' => $smsNotification->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
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

    //toggle status
    public function togglestatus($id){
        try {
            DB::beginTransaction();
            $smsNotification = SmsNotification::findOrFail($id);
            $status = $smsNotification->status;
            if($status == 1){
                $smsNotification->status = 0;
                $action = 'DELETED';
            }
            else{                
                $smsNotification->status = 1;
                $action = 'RESTORE';
            }
            $changes = $smsNotification->getDirty();
            $smsNotification->save();
            
            DB::commit();
            
            /* logs */
            action_log('SMS Notification Mngt', $action, array_merge(['id' => $smsNotification->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function sendSms(Request $request){
        $sms = SmsNotification::findOrFail($request['messageId']);
        $user = User::where('person_id', '=', $request['personId'])->first();

        $number = $user->contact_number;
        $message = $sms->message;

        $username = env('SMS_USERNAME', null);
        $password = env('SMS_PASSWORD', null);
        $data = "{  \"messages\" : [    {      \"source\" : \"php\",     \"body\" : \"$message\",      \"to\" : \"$number\" }]}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://rest.clicksend.com/v3/sms/send");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . base64_encode("$username:$password"), 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        // curl_exec($ch);

        try {
            DB::beginTransaction();
            if($response == true){
                $smsHistory = new SmsHistory();
                $smsHistory->sender = Auth::user()->id;
                $smsHistory->receiver = $user->id;
                $smsHistory->message = $message;
                $smsHistory->status = 1;
                $changes = $smsHistory->getDirty();
                $smsHistory->save();

                DB::commit();
                /* logs */
                action_log('SMS Notification Mngt', 'sms sent', array_merge(['id' => $smsHistory->id], $changes));
            }
            else{
                $smsHistory = new SmsHistory();
                $smsHistory->sender = Auth::user()->id;
                $smsHistory->receiver = $user->id;
                $smsHistory->message = $message;
                $smsHistory->status = 0;
                $changes = $smsHistory->getDirty();
                $smsHistory->save();
                DB::commit();
                /* logs */
                action_log('SMS Notification Mngt', 'SMS failed', array_merge(['id' => $smsHistory->id], $changes));
            }

            curl_close($ch);    
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }

        
    }
}
