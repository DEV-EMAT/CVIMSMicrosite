<?php

namespace App\Http\Controllers\Comprehensive;

use App\Comprehensive\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\Event;
use App\Comprehensive\EventSummary;
use App\Ecabs\Department;
use Carbon\Carbon;
use DB;
use Gate;
use Validator;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $department = $this->getDepartment(\Auth::user())->department_position['department_id'];
        $status = ($department == '1')? true : false;

        return view('comprehensive.event.index' , ['title' => "Event Management", 'department_status' => $status ]);
    }


    public function findall(request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'title',
            2 =>'description',
        );

        $totalData = Event::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $department = $this->getDepartment(\Auth::user())->department_position['department_id'];
        $status = ($department == '1')? true : false;

        if($status){
            /* ecabs */
            $query = Event::query();
        }else{
            $query = Event::where('department_id', '=', $department);
        }

        if($request["action"] == "programManagement"){
            $query->where('status', 1);
        }

        if(empty($request->input('search.value')))
        {
            $events = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

        }
        else {
            $search = $request->input('search.value');

            $events = $query->where('title', 'LIKE',"%{$search}%")
                ->orWhere('description', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->where('title',"%{$search}%")
                ->orWhere('description', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($events))
        {
            foreach ($events as $event)
            {

                $department = Department::findOrFail($event->department_id);

                $buttons = '';
                $status2 = ($event->event_status=='OPEN')?'<label class="label label-primary">Open</label> ': '<label class="label label-danger">Closed</label>';
                $status = ($event->status==1)?'<label class="label label-primary">ACTIVE</label>':'<label class="label label-danger">IN-ACTIVE</label>';


                if($event->status==1){

                    if(Gate::allows('permission', 'updateEvent')){
                        $buttons .= '<li><a onclick="edit('.$event->id.')" class="btn-fill btn-rotate"><i class="fa fa-edit"></i> EDIT</a></li> ';
                    }

                    if(Gate::allows('permission', 'deleteEvent')){
                        $buttons .= '<li><a onclick="del('.$event->id.')" class="btn-fill btn-rotate"><i class="fa fa-trash"></i> DELETE</a></li>';
                    }


                    if(Gate::allows('permission', 'viewSelectEvent')){
                        $buttons .= '<li class="divider"></li>';

                        if($event->event_status=='OPEN'){
                            $buttons .= '<li><a onclick="changeinout('.$event->id.')" class="btn-fill btn-rotate"><i class="fa fa-refresh"></i> CHANGE STATUS</a></li>';
                            $buttons .= '<li><a onclick="closeevent('.$event->id.')" class="btn-fill btn-rotate"><i class="fa fa-close"></i> CLOSE EVENT</a></li>';
                        }else{
                            $buttons .= '<li><a onclick="openevent('.$event->id.')" class="btn-fill btn-rotate"><i class="fa fa-inbox"></i> OPEN EVENT</a></li>';
                        }
                    }
                }else{
                    if(Gate::allows('permission', 'restoreEvent')){
                        $buttons .= '<li><a onclick="del('.$event->id.')" class="btn-fill btn-rotate"><i class="fa fa-recycle"></i> RESTORE</a></li>';
                    }
                }

                if(!empty($buttons)){

                    $button_group = '
                        <div class="dropup" style="width:30%">
                                <button href="#" class="btn btn-xs btn-block btn-fill btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <b class="caret"></b>
                                </button>
                                <ul class="dropdown-menu">
                                    '. $buttons .'
                                </ul>
                        </div>';
                }else{
                    $button_group = '
                        <div class="dropup" style="width:30%">
                                <button disabled href="#" class="btn btn-xs btn-block btn-fill btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <b class="caret"></b>
                                </button>
                        </div>';
                }

                $event_schedules = EventSummary::where('event_id', '=', $event->id)->where('status', '=', '1')->first();

                $nestedData['id'] = $event->id;
                $nestedData['event'] = $event->title;
                $nestedData['date_event'] = $event_schedules->date_of_event.' ('. date('h:i:s a', strtotime($event_schedules->time_of_event_from)) .' - '. date('h:i:s a', strtotime($event_schedules->time_of_event_to)).')';
                $nestedData['capacity'] = $event_schedules->attendees_capacity;
                $nestedData['description'] =  $event->description;
                $nestedData['department'] =  $department['acronym'];
                $nestedData['status'] = $status;
                $nestedData['action'] = $button_group;
                $nestedData['status2'] = $status2;
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
        $validate = Validator::make($request->all(),[
            'event'=>'required',
            'venue'=>'required',
            'date_of_event'=>'required',
            'department'=>'required',
            'start_of_event'=>'required',
            'end_of_event'=>'required',
            'attendees_capacity'=>'required',
            'time_in_allowance'=>'required',
            'time_out_allowance'=>'required',
        ]);

        if($validate->fails()){
            return response()->json(array('success'=>false, 'messages'=>'Please input valid data!'));
        }else{
            try {
                DB::beginTransaction();

                $department = $this->getDepartment(\Auth::user())->department_position['department_id'];
                $status = ($department == '1')? true : false;

                $event = new Event;
                $event->event_code = md5('E'. convertData($request['event']) . Carbon::now());
                $event->title = convertData($request['event']);
                $event->description = convertData($request['description']);
                $event->department_id = ($status)? $request['department']: $department;
                $event->status = '1';
                $event->event_status = 'CLOSE';
                $changes = $event->getDirty();
                $event->save();

                $summary = new EventSummary;
                $summary->event_id = $event->id;
                $summary->exclusive = !empty($request['event_type'])? $request['event_type']: '0';
                $summary->required_attendance = !empty($request['required_attendance'])? $request['required_attendance']: '0';
                $summary->venue = convertData($request['venue']);
                $summary->date_of_event = $request['date_of_event'];
                $summary->time_of_event_from = $request['start_of_event'];
                $summary->time_of_event_to = $request['end_of_event'];
                $summary->attendees_capacity = $request['attendees_capacity'];
                $summary->time_in_allowance = $request['time_in_allowance'];
                $summary->time_out_allowance = $request['time_out_allowance'];
                $summary->reasons = '';
                $summary->status = '1';
                $changes = array_merge($changes, $summary->getDirty());
                $summary->save();

                if(!empty($request['required_attendance']) && $request['required_attendance'] ==='1'){
                    $attendance = new Attendance;
                    $attendance->title = $event->title;
                    $attendance->attendance_code = $event->event_code;
                    $attendance->event_id = $event->id;
                    $attendance->status = '1';
                    $attendance->save();
                }

                DB::commit();

                /* logs */
                action_log('Event Mngt', 'CREATE', array_merge(['id' => $event->id], $changes));

                return response()->json(array('success'=>true, 'messages'=>'Record successfully saved!'));
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    public function togglestatus($id) {

        $message='';
        $event=Event::findOrFail($id);

        if($event->status=='1') {
            $event->status='0';
            $event->event_status='CLOSE';
            $message='Record successfully Deleted!';
        }
        else {
            $event->status='1';
            $message='Record successfully Retreive!';
        }

        $event->save();

        return response()->json(array('success'=> true, 'messages'=>$message));

        try {
            DB::beginTransaction();

            if($event->status=='1') {
                $event->status='0';
                $event->event_status='CLOSE';
                $message='Record successfully Deleted!';
                $action = 'DELETED';
            }
            else {
                $event->status='1';
                $message='Record successfully Retreive!';
                $action = 'RESTORE';
            }
            $changes = $event->getDirty();
            $event->save();

            DB::commit();

            /* logs */
            action_log('Event Mngt', $action, array_merge(['id' => $event->id], $changes));

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
        $event = Event::with(['event_summary' => function($q){
            $q->where('status', '=', '1');
        }])->where('id', '=', $id)->first();

        return response()->json($event);
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
        $validate = Validator::make($request->all(),[
            'edit_event'=>'required',
            'edit_venue'=>'required',
            'edit_date_of_event'=>'required',
            'edit_start_of_event'=>'required',
            'edit_end_of_event'=>'required',
            'edit_time_in_allowance'=>'required',
            'edit_time_out_allowance'=>'required',
        ]);

        if($validate->fails()){
            return response()->json(array('success'=>false, 'messages'=>'Please input valid data!'));
        }else{
            // try {
            //     DB::beginTransaction();

                $department = $this->getDepartment(\Auth::user())->department_position['department_id'];
                $status = ($department == '1')? true : false;

                $event = Event::findOrFail($id);

                $old_summary = EventSummary::where('event_id', '=', $event->id)->where('status', '=', '1')->first();

                /* update title on attendance if update title on event */
                if($event->title != convertData($request['edit_event']) && $old_summary->required_attendance == '1'){
                    $attendance = Attendance::where('event_id', '=', $id)->first();
                    $attendance->title = convertData($request['edit_event']);
                    $attendance->save();
                }

                /* event */
                $event->title = convertData($request['edit_event']);
                $event->description = convertData($request['edit_description']);
                $event->department_id = ($status)? $request['department']: $department;
                $changes = $event->getDirty();
                $event->save();
                // dd($request['edit_event_type']);

                /* event summaries */
                $old_summary->exclusive = !empty($request['edit_event_type'])? $request['edit_event_type']: '0';
                $old_summary->required_attendance = !empty($request['edit_required_attendance'])? $request['edit_required_attendance']: '0';
                $old_summary->save();


                /* create attendance if update required_attendance to true */
                if($old_summary->required_attendance === '1'){

                    $attendance = Attendance::where('event_id', '=', $id)->where('status', '=', '0')->first();
                    if(!empty($attendance)){
                        $attendance->status = '1';
                        $attendance->save();
                    }else{
                        $attendance = new Attendance;
                        $attendance->title = $event->title;
                        $attendance->attendance_code = $event->event_code;
                        $attendance->event_id = $event->id;
                        $attendance->status = '1';
                        $attendance->save();
                    }
                }else{
                    $attendance = Attendance::where('event_id', '=', $id)->where('status', '=', '1')->first();
                    if(!empty($attendance)){
                        $attendance->status = '0';
                        $attendance->save();
                    }
                }


                /* event summaries */
                if($old_summary->date_of_event != $request['edit_date_of_event'] ||
                    $old_summary->time_of_event_from != $request['edit_start_of_event'] ||
                    $old_summary->time_of_event_to != $request['edit_end_of_event'] ||
                    $old_summary->attendees_capacity != $request['edit_attendees_capacity'] ||
                    $old_summary->time_in_allowance != $request['edit_time_in_allowance'] ||
                    $old_summary->time_out_allowance != $request['edit_time_out_allowance'] ||
                    $old_summary->venue != $request['edit_venue']){

                        $old_summary->status = '0';
                        $old_summary->reasons = $request['edit_reason'];
                        $old_summary->save();

                        $summary = new EventSummary;
                        $summary->event_id = $event->id;
                        $summary->venue = convertData($request['edit_venue']);
                        $summary->date_of_event = $request['edit_date_of_event'];
                        $summary->time_of_event_from = $request['edit_start_of_event'];
                        $summary->time_of_event_to = $request['edit_end_of_event'];
                        $summary->attendees_capacity = $request['edit_attendees_capacity'];
                        $summary->time_in_allowance = $request['edit_time_in_allowance'];
                        $summary->time_out_allowance = $request['edit_time_out_allowance'];
                        $summary->required_attendance = $request['edit_required_attendance'];
                        $summary->exclusive = $old_summary['exclusive'];
                        $summary->required_attendance = $old_summary['required_attendance'];
                        $summary->reasons = '';
                        $summary->exclusive = $old_summary->exclusive;
                        $summary->required_attendance = $old_summary->required_attendance;
                        $summary->status = '1';
                        $changes = array_merge($changes, $summary->getDirty());
                        $summary->save();
                }

                // DB::commit();

                /* logs */
                action_log('Event Mngt', 'Update', array_merge(['id' => $event->id], $changes));

                return response()->json(array('success'=>true, 'messages'=>'Record successfully updated!'));
            // } catch (\PDOException $e) {
            //     DB::rollBack();
            //     return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            // }
        }
    }

    public function closeevent($id) {

        $event=Event::findOrFail($id);

        $event->event_status='CLOSE';
        $changes = $event->getDirty();
        $event->save();

        /* logs */
        action_log('Event Mngt', 'Update', array_merge(['id' => $event->id], $changes));

        return response()->json(array('success'=> true, 'messages'=>'Event is now closed!'));
    }

    public function toggleinout(Request $request, $id) {

        $event=Event::findOrFail($id);

        $event->event_status = 'OPEN';
        $changes = $event->getDirty();
        $event->save();

        /* logs */
        action_log('Event Mngt', 'Update', array_merge(['id' => $event->id], $changes));

        return response()->json(array('success'=> true, 'messages'=>'Attendance is updated!'));
    }

    public function checkeventstatus(Request $request) {
        $event = Event::findOrFail($request['event_id']);
        $enableevent = Event::where('event_status','=','OPEN')->where('department_id','=', $event['department_id'] )->first();
        if($enableevent){
            return response()->json(array('success'=> true, 'data'=> $enableevent));
        }else{
            return response()->json(array('success'=> false));
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
