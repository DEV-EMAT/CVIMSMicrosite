<?php

namespace App\Http\Controllers\API\Ecabs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comprehensive\EventHasPreRegistration;
use App\Ecabs\PersonDepartmentPosition;
use App\Comprehensive\Event;
use App\Comprehensive\EventHasAttendance;
use App\Comprehensive\Attendance;
use App\Ecabs\Department;
use Carbon\Carbon;
use Validator;
use Auth;
use DB;

class EventsController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getAllEventByDept()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                $user_dept = PersonDepartmentPosition::where('person_id', '=', Auth::user()->person_id)->with('department_position', 'department_position.departments')->first();

                $events = Event::join('event_summaries', 'events.id', 'event_summaries.event_id')
                            ->select(
                                'events.id AS event_id',
                                'events.event_code',
                                'events.title',
                                'events.description',
                                'events.in_out_status',
                                'events.event_status',
                                'events.created_at',
                                'events.updated_at',
                                'event_summaries.date_of_event',
                                'event_summaries.venue',
                                'event_summaries.time_of_event_from',
                                'event_summaries.time_of_event_to',
                                'event_summaries.time_in_allowance',
                                'event_summaries.time_out_allowance',
                                'event_summaries.required_attendance',
                                'event_summaries.status'
                            )->where('events.department_id', '=', $user_dept->department_position->departments->id)
                            ->where('event_summaries.status', '=', 1)
                            ->where('events.event_status', '=', 'open')
                            ->where('events.status', '=', 1)->get();

                $events->map(function ($event) {
                    return $event->date_of_event =  Carbon::parse($event->date_of_event)->format('Y-m-d');
                });

                $new_events = [];
                foreach ($events as $key => $value) {
                    $attendance = Attendance::where('event_id', '=', $value->event_id)->first();
                    if(!is_null($attendance)){
                        $has_attendance = EventHasAttendance::where('attendances_id', '=', $attendance->id)->first();
                        if(is_null($has_attendance)){
                            $new_events[] = $value;
                        }
                    }
                }

                DB::commit();
                return response()->json(['success' => $new_events, 'system_time' => Carbon::now()], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function getAllEvents()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $events = Event::leftJoin('event_summaries', 'events.id', 'event_summaries.event_id')
                            ->select(
                                'events.id as id',
                                'events.title',
                                'events.description',
                                'events.department_id',
                                'event_summaries.time_of_event_from',
                                'event_summaries.time_of_event_to',
                                'event_summaries.date_of_event',
                                'event_summaries.exclusive',
                                'event_summaries.venue'
                            )->where('events.status', '=', 1)
                            ->where('event_summaries.status', '=', 1)
                            ->where('event_summaries.exclusive', '=', 0)->paginate(6);

                $events->map(function($event) {
                    $has_attendance = EventHasPreRegistration::where('event_id', '=', $event->id)->where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->first();
                    if(!is_null($has_attendance)){
                        $event->registration_status = "registered";
                    } else {
                        $event->registration_status = "not registered";
                    }
                    $event->dept_info = Department::where('id', '=', $event->department_id)->select('department', 'acronym', 'logo')->first();
                });

                DB::commit();
                return response()->json(['success' => $events], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function preRegistrationOnEvent(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $check_if_active_event = Event::where('id', '=', $request['event_id'])->first();

                if(!is_null($check_if_active_event)){
                    if($check_if_active_event->status == '1'){

                        $pre_registration_data = EventHasPreRegistration::where('event_id', '=', $request['event_id'])
                                                ->where('user_id', '=', Auth::user()->id)
                                                ->where('status', '=', 0)
                                                ->select('id')->first();

                        if(!is_null($pre_registration_data)){
                            $my_pre_registration_data = EventHasPreRegistration::findOrFail($pre_registration_data->id);
                            $my_pre_registration_data->status = 1;
                            $my_pre_registration_data->save();
                        } else {
                            $pre_registration = new EventHasPreRegistration();
                            $pre_registration->event_id = $request['event_id'];
                            $pre_registration->user_id = Auth::user()->id;
                            $pre_registration->status = 1;
                            $pre_registration->save();
                        }


                        DB::commit();

                        return response()->json(['success' => $this->successCreateStatus, 'message' => 'Registered successfully.'], $this->successCreateStatus);
                    } else {
                        DB::commit();
                        return response()->json(['error' => $this->errorStatus, 'message' => 'Event registration is currently unavailable. Please try again later.'], $this->errorStatus);
                    }
                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus, 'message' => 'No existing event.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }

        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function cancelPreRegistrationOnEvent(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $check_if_active_event = Event::where('id', '=', $request['event_id'])->first();

                if(!is_null($check_if_active_event)){
                    if($check_if_active_event->status == '1'){
                        $pre_registration_data = EventHasPreRegistration::where('event_id', '=', $request['event_id'])->where('user_id', '=', Auth::user()->id)->select('id')->first();

                        $my_pre_registration_data = EventHasPreRegistration::findOrFail($pre_registration_data->id);
                        $my_pre_registration_data->status = 0;
                        $my_pre_registration_data->save();

                        DB::commit();

                        return response()->json(['success' => $this->successStatus, 'message' => 'Pre-registration canceled successfully.'], $this->successStatus);
                    } else {
                        DB::commit();
                        return response()->json(['error' => $this->errorStatus, 'message' => 'Event registration is currently unavailable. Please try again later.'], $this->errorStatus);
                    }
                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus, 'message' => 'No existing event.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }

        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function getListOfRegisteredOnEvent(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'event_code' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {
                DB::beginTransaction();

                $check_if_active_event = Event::where('event_code', '=', $request['event_code'])->first();

                if(!is_null($check_if_active_event)){
                    if($check_if_active_event->status == '1'){
                        $list_of_attendies = EventHasPreRegistration::join(connectionName(). '.users as users', 'event_has_pre_registrations.user_id', 'users.id')
                                            ->join(connectionName(). '.people as people', 'users.person_id', 'people.id')
                                            ->join('events', 'event_has_pre_registrations.event_id', 'events.id')
                                            ->select(
                                                'users.id as user_id',
                                                'people.person_code',
                                                DB::raw('CONCAT(people.first_name, " ", people.last_name) AS full_name'),
                                                'events.event_code',
                                                'event_has_pre_registrations.status',
                                                'event_has_pre_registrations.created_at'
                                            )->where('event_code', '=', $request['event_code'])->get();

                        DB::commit();

                        return response()->json(['success' => $this->successCreateStatus, 'data' => $list_of_attendies], $this->successCreateStatus);
                    } else {
                        DB::commit();
                        return response()->json(['error' => $this->errorStatus, 'message' => 'Event is currently unavailable. Please try again later.'], $this->errorStatus);
                    }
                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus, 'message' => 'No existing event.'], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }

        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
