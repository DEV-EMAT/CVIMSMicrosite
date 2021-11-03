<?php

namespace App\Http\Controllers\API\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\CovidTracer;
use App\CovidTracer\EstablishmentInformation;
use App\Ecabs\Person;
use App\User;
use Validator;
use Carbon\Carbon;
use Auth;
use DB;

class ScannerController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function establishmentToPersonScanner(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [ 
                'temperature' => 'required', 
                'transaction_two' => 'required',
                'location' => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }

            $person = Person::where('person_code', '=', $request['transaction_two'])->first();

            if(! is_null($person)){
                try {
                    DB::beginTransaction();
                    $covid_data = new CovidTracer();
                    $covid_data->temperature = $request['temperature'];
                    $covid_data->transaction_one = $request['transaction_one'];
                    $covid_data->transaction_two = $person->person_code;
                    $date = Carbon::now();
                    $covid_data->date_created = $date->year . "-" . $date->month . '-' . $date->day;
                    $covid_data->type = 1;
                    $covid_data->location = $request['location'];
                    $covid_data->status = 1;
        
                    $covid_data->save();
        
                    DB::commit();
        
                    return response()->json(['success' => $covid_data, 'message' => 'Data saved successfully!!'], $this->successCreateStatus);
        
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json($e, $this->queryErrorStatus);
                } 
            } else {
                return response()->json(['message' => 'invalid QR Code!!'], $this->errorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        } 
    }

    public function personToEstablishmentScanner(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [ 
                'temperature' => 'required', 
                'transaction_two' => 'required',
                'location' => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }

            $establishment = EstablishmentInformation::where('establishment_identification_code', '=', $request['transaction_two'])->first();

            if(! is_null($establishment)){
                try {
                    DB::beginTransaction();
                    
                    $covid_data = new CovidTracer();
                    $covid_data->temperature = $request['temperature'];
                    $covid_data->transaction_one = $request['transaction_one'];
                    $covid_data->transaction_two = $establishment->establishment_identification_code;
                    $date = Carbon::now();
                    $covid_data->date_created = $date->year . "-" . $date->month . '-' . $date->day;
                    $covid_data->type = 2;
                    $covid_data->location = $request['location'];
                    $covid_data->status = 1;
        
                    $covid_data->save();
        
                    DB::commit();
        
                    return response()->json(['success' => $covid_data, 'message' => 'Data saved successfully!!'], $this->successCreateStatus);
        
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json($e, $this->queryErrorStatus);
                } 
            } else {
                return response()->json(['message' => 'invalid QR Code!!'], $this->errorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        } 
    }

    public function personToPersonScanner(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [ 
                'temperature' => 'required', 
                'transaction_two' => 'required',
                'location' => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }

            $stranger = User::join('people', 'users.person_id', 'people.id')
                        ->select('people.person_code AS person_code')
                        ->where('people.person_code', '=', $request['transaction_two'])->first();

            if(! is_null($stranger)){
                try {
                    DB::beginTransaction();

                    $covid_data = new CovidTracer();
                    $covid_data->temperature = $request['temperature'];
                    $covid_data->transaction_one = $request['transaction_one'];
                    $covid_data->transaction_two = $stranger->person_code;
                    $date = Carbon::now();
                    $covid_data->date_created = $date->year . "-" . $date->month . '-' . $date->day;
                    $covid_data->type = 3;
                    $covid_data->location = $request['location'];
                    $covid_data->status = 1;

                    $covid_data->save();

                    DB::commit();

                    return response()->json(['success' => $covid_data, 'message' => 'Data saved successfully!!'], $this->successCreateStatus);

                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json($e, $this->queryErrorStatus);
                } 
            } else {
                return response()->json(['message' => 'invalid QR Code!!'], $this->errorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        } 
    }
    
    public function offlineData(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [ 
                'data' => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }
            
            $data = json_decode($request->data, true); 
            $ctr = 0;
            $data_length = count($data);
            foreach($data as $value){
            
                $ctr++;
                
                $establishment_1 = EstablishmentInformation::where('establishment_identification_code', '=', $value['transaction_one'])->first();
                $user_2 = User::join('people', 'users.person_id', 'people.id')
                                ->select('people.person_code AS person_code')
                                ->where('people.person_code', '=', $value['transaction_two'])->first();
                                
                if((!is_null($establishment_1)) || (!is_null($user_2))) {
                    try {
                        DB::beginTransaction();
                        
                        $timestamp = $date_created = $time_db = '';
                        
                        $covid_data = new CovidTracer();
                        $covid_data->temperature = $value['temperature'];
                        $covid_data->transaction_one = $value['transaction_one'];
                        $covid_data->transaction_two = $value['transaction_two'];
                        
                        $timestamp=$value['date_created'];
                        $date_created = date('Y-m-d', $timestamp/1000);
                        $time_db = date('Y-m-d H:i:s', $timestamp/1000);
                        $covid_data->date_created = $date_created;
                        
                        $covid_data->type = 1;
                        $covid_data->location = $value['location'];
                        $covid_data->status = 1;
                        $covid_data->created_at = $time_db;
                        $covid_data->updated_at = $time_db;
                        
                        $covid_data->save();
            
                        DB::commit();
                        
                        if($data_length == $ctr){
                            return response()->json(['success' => $covid_data, 'message' => 'All data from offline data is saved successfully!!'], $this->successCreateStatus);
                        }
            
                    } catch (\PDOException $e) {
                        DB::rollBack();
                        return response()->json($e, $this->queryErrorStatus);
                    } 
                }
            }
            
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        } 
    }
}
