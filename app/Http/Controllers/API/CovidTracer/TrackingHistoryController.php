<?php

namespace App\Http\Controllers\API\CovidTracer;

use App\Http\Controllers\Controller;
use App\CovidTracer\CovidTracer;
use App\User;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use DateTime;

class TrackingHistoryController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function trackHistoryPerson(Request $request)
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                
                $user = User::join('people', 'users.person_id', 'people.id')
                        ->select('person_code')
                        ->where('users.id', '=', Auth::user()->id)->first();
                $code = $user->person_code;
                if($request['start_date'] == $request['end_date']){
                    $myTrackingHistory = CovidTracer::select(
                        'temperature',
                        'location',
                        'transaction_two',
                        'type',
                        'created_at',
                    )->where(function ($query) use ($code){
                        $query->where('transaction_one', '=', $code)
                            ->orWhere('transaction_two', '=', $code);
                    })->latest()->paginate(6);
                } else {
                    $myTrackingHistory = CovidTracer::select(
                                            'temperature',
                                            'location',
                                            'transaction_two',
                                            'type',
                                            'created_at',
                                        )->where(function ($query) use ($code){
                                            $query->where('transaction_one', '=', $code)
                                                ->orWhere('transaction_two', '=', $code);
                                        })->orderBy('created_at', 'desc')->paginate(6);
                }
                
                DB::commit();

                return response()->json(['success' => $myTrackingHistory], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }  
    }

    public function trackHistoryEstablishment(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [
                'est_code' => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }

            try {
                DB::beginTransaction();

                $code = $request['est_code'];
                
                if($request['start_date'] == $request['end_date']){
                    $myTrackingHistory = CovidTracer::select(
                                            'temperature',
                                            'location',
                                            'transaction_two',
                                            'type',
                                            'date_created',
                                            'created_at'
                                        )->where(function ($query) use ($code){
                                            $query->where('transaction_one', '=', $code)
                                                ->orWhere('transaction_two', '=', $code);
                                        })->latest()->paginate(6);
                } else {
                    $myTrackingHistory = CovidTracer::select(
                                            'temperature',
                                            'location',
                                            'transaction_two',
                                            'type',
                                            'created_at',
                                        )->where(function ($query) use ($code){
                                            $query->where('transaction_one', '=', $code)
                                                ->orWhere('transaction_two', '=', $code);
                                        })->orderBy('created_at', 'desc')->paginate(6);
                }

                DB::commit();

                return response()->json(['success' => $myTrackingHistory], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }  
    }
}