<?php

namespace App\Http\Controllers\API\Ecabs;

use App\Comprehensive\EventHasAttendance;
use App\Comprehensive\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\Attendance;
use App\Ecabs\Person;
use Validator;
use Auth;
use DB;

class AttendanceController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getAllAttendeesByEventId(Request $request)
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $attendance = Attendance::where('event_id', '=', $request['id'])->where('status', '=', 1)->get();

                DB::commit();
                return response()->json(['success' => $attendance], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function storeAttendance(Request $request)
    {
        if(Auth::user()->account_status == 1){

            $validator = Validator::make($request->all(), [
                'attendance' => 'required',
                'event_code' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {

                DB::beginTransaction();

                $get_event_id = Event::where('event_code', '=', $request['event_code'])->select('id')->first();

                if(is_null($get_event_id)){
                    DB::commit();
                    return response()->json(['success' => $this->errorStatus, 'data' => null, 'message' => 'No Event found.'], $this->errorStatus);
                }

                $attendance_id = Attendance::where('event_id', '=', $get_event_id->id)->first();

                if(is_null($attendance_id)){
                    DB::commit();
                    return response()->json(['success' => $this->errorStatus, 'data' => null, 'message' => 'No attendance for this event found.'], $this->errorStatus);
                }

                $data = json_decode($request['attendance'],true);

                foreach ($data as $value) {

                    $new_attendance = new EventHasAttendance();
                    $new_attendance->attendances_id = $attendance_id->id;
                    $new_attendance->attendees= convertData($value['full_name']);
                    $new_attendance->person_code= $value['person_code'];

                    $check_person = Person::where('person_code', '=',$value['person_code'] )->first();

                    if(!is_null($check_person)){
                        $user = Person::join('users', 'people.id', 'users.person_id')
                            ->select(
                                'users.id'
                            )->where('people.person_code', '=', $value['person_code'])->first();
                        $new_attendance->user_id= $user->id;
                    }

                    $new_attendance->attendee_status= convertData($value['status']);
                    $new_attendance->attendee_remarks= convertData($value['remarks']);
                    $new_attendance->time_in = $value['created_at'];
                    $new_attendance->time_out = $value['updated_at'];
                    $new_attendance->status= 1;
                    $new_attendance->save();
                }
                
                $logs = json_decode($request['logs'],true);
                
                foreach ($logs as $value) {
                    mobile_log($value['event_code'],$value['remarks'],$value['time_out'],$value['extended_by'],$value['created_at'],$value['updated_at']);
                }
                
                DB::commit();

                return response()->json(['success' => 'Attendance uploaded successfully.'], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
