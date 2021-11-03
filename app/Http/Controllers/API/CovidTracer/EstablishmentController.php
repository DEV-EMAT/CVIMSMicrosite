<?php

namespace App\Http\Controllers\API\CovidTracer;

use App\CovidTracer\EstablishmentInformation;
use App\CovidTracer\EstablishmentStaff;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class EstablishmentController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getMyEstablishments()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                $owner_establishments = EstablishmentInformation::join('establishment_categories', 'establishment_information.establishment_category_id', 'establishment_categories.id')
                                        ->select(
                                            'establishment_information.owner_id',
                                            'establishment_information.establishment_identification_code',
                                            'establishment_information.business_name',
                                            'establishment_information.business_permit_number',
                                            'establishment_information.status',
                                            'establishment_categories.description',
                                            'establishment_categories.status'
                                        )->where('owner_id', '=', Auth::user()->id)
                                        ->where('establishment_information.status', '=', 1)->get();
                DB::commit();
    
                return response()->json(['my_establishments' => $owner_establishments], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
    public function getEstablishmentInfo(Request $request)
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
                $establishment_info = EstablishmentInformation::join('establishment_categories', 'establishment_information.establishment_category_id', 'establishment_categories.id')
                                        ->select(
                                            'establishment_information.owner_id',
                                            'establishment_information.establishment_identification_code',
                                            'establishment_information.business_name',
                                            'establishment_information.business_permit_number',
                                            'establishment_information.status',
                                            'establishment_categories.description',
                                            'establishment_categories.status'
                                        )->where('establishment_information.establishment_identification_code', '=', $request['est_code'])
                                        ->where('establishment_information.status', '=', 1)->first();
                DB::commit();
            
                return response()->json(['establishment_info' => $establishment_info], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function getEstablishmentStaff(Request $request)
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
                $est_id = EstablishmentInformation::where('establishment_identification_code', '=', $request['est_code'])->select('id')->first();
    
                $staffs = EstablishmentStaff::where('establishment_information_id', '=', $est_id['id'])
                            ->join(connectionName('mysql') . '.users', 'establishment_staff.user_id', connectionName('mysql') . '.users.id')
                            ->join(connectionName('mysql') . '.people', connectionName('mysql') . '.users.person_id', connectionName('mysql') . '.people.id')
                            ->select(
                                'establishment_staff.start',
                                'establishment_staff.end',
                                'establishment_staff.staff_status',
                                connectionName('mysql') . '.people.first_name',
                                connectionName('mysql') . '.people.middle_name',
                                connectionName('mysql') . '.people.last_name',
                                connectionName('mysql') . '.people.person_code',
                            )->where('establishment_staff.staff_status', '=', 1)->get();
                DB::commit();
                return response()->json(['establishment_staff' => $staffs], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function addEstablishmentStaff(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [ 
                'est_code' => 'required',
                'person_code' => 'required'
            ]);
    
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }
            
            try {
                DB::beginTransaction();
    
                $user = User::join('people', 'users.person_id', 'people.id')
                        ->select(
                            'users.id AS user_id',
                            'users.account_status',
                            'people.person_code as person_code'
                        )->where('people.person_code', '=', $request['person_code'])->where('users.account_status', '=', 1)->first();
                
                if(!is_null($user)){
                    $est = EstablishmentInformation::select('id', 'owner_id')->where('establishment_identification_code', '=', $request->est_code)->first();
                    
                    if($user->user_id != $est->owner_id){
                        if(EstablishmentStaff::where('user_id', '=', $user->user_id)->where('establishment_information_id', '=', $est->id)->first()){
                            return response()->json(['success' => 'Staff already existed'], $this->errorStatus);
                        } else {
                            $est = EstablishmentInformation::where('establishment_identification_code', '=', $request['est_code'])
                                    ->select('id')->first();
                
                            $staff = new EstablishmentStaff();
                            $staff->establishment_information_id = $est->id;
                            $staff->user_id = $user->user_id;
                            $staff->staff_status = 1;
                
                            $staff->save();
                
                            DB::commit();
                
                            return response()->json(['success' => $staff , 'response_code' => $this->successCreateStatus], $this->successCreateStatus);
                        }
        
                    } else {
                        DB::commit();
                        return response()->json(['error' => 'Owner cannot be added as staff.'], $this->errorStatus);
                    }
                } else {
                    DB::commit();
                    return response()->json(['error' => 'User cannot be added as staff.'], $this->errorStatus);
                }
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function changeEstablishmentStaffStatus(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [ 
                'est_code' => 'required',
                'person_code' => 'required'
            ]);
    
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }
            
            try {
                DB::beginTransaction();
    
                $user = User::join('people', 'users.person_id', 'people.id')
                        ->select('users.id AS user_id')
                        ->where('people.person_code', '=', $request['person_code'])->first();
    
                $est_id = EstablishmentInformation::where('establishment_identification_code', '=', $request['est_code'])->select('id')->first();
                
                $staff = EstablishmentStaff::where('establishment_information_id', '=', $est_id->id)
                        ->select(
                            'establishment_staff.id',
                        )->where('establishment_staff.user_id', '=', $user->user_id)->first();
    
                if(!is_null($staff)){
                    
                    $staff_updated = EstablishmentStaff::findOrFail($staff->id);
                    if($staff_updated->staff_status == 1){
                        $staff_updated->staff_status = 0;
                    } else {
                        $staff_updated->staff_status = 1;
                    }
                    
                    $staff_updated->save();
    
                    DB::commit();
                    return response()->json(['success' => "Status Updated" , 'response_code' => $this->successStatus], $this->successStatus);
                } 
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function getEstablishmentQrCode()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                    $check_as_owner = EstablishmentInformation::where('owner_id', '=', Auth::user()->id)->select('establishment_identification_code','status', 'business_name')->where('status', '=', '1')->get();
    
                    $est_qr = EstablishmentStaff::join('establishment_information', 'establishment_staff.establishment_information_id','establishment_information.id')
                            ->select(
                                'establishment_information.establishment_identification_code',
                                'establishment_information.business_name',
                                'establishment_staff.staff_status'
                                )
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('staff_status', '=', '1')->get();
    
                    DB::commit();   
                    
                    return response()->json(['success' => ['as_owner' => $check_as_owner, 'as_staff' => $est_qr], 'response_code' => $this->successStatus], $this->successStatus);   
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function getStaffStatus(Request $request)
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
    
                $est_id = EstablishmentInformation::where('establishment_identification_code', '=', $request['est_code'])->select('id')->first();
    
                $staff_status = EstablishmentStaff::where('establishment_information_id', '=', $est_id->id)
                        ->select(
                            'establishment_staff.staff_status',
                        )->where('establishment_staff.user_id', '=', Auth::user()->id)->first();
    
                DB::commit();   
                return response()->json(['success' => $staff_status->staff_status, 'response_code' => $this->successStatus], $this->successStatus);
                
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function checkEstablishmentStatus(Request $request)
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
    
                $est_status = EstablishmentInformation::where('establishment_identification_code', '=', $request->est_code)->select('status')->first();
    
                DB::commit();   
                return response()->json(['success' => $est_status, 'response_code' => $this->successStatus], $this->successStatus);
                
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
