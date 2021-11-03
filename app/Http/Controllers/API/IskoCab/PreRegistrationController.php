<?php

namespace App\Http\Controllers\API\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IskoCab\PreRegScholar;
use App\Ecabs\Person;
use Auth;
use DB;
use Validator;

class PreRegistrationController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;
    
    public function preRegistration(Request $request)
    {
        if(checkMaintenaince('ISKOCAB-PRE-REGISTRATION')){
            if(Auth::user()->account_status == 1){
            
                $validator = Validator::make($request->all(), [ 
                    'school' => 'required',
                    'course' => 'required',
                    'image' => 'required',
                ]);
        
                if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
                }
    
                try {
                    DB::beginTransaction();
                    
                    $check_if_exists = PreRegScholar::where('user_id', '=', Auth::user()->id)->first();
                    
                    if(is_null($check_if_exists)){
                        $pre_registration_scholar = new PreRegScholar();
                        $pre_registration_scholar->user_id = Auth::user()->id;
                        $person_code = Person::where('id', '=', Auth::user()->person_id)
                                        ->select(
                                            'person_code'
                                        )->first();
                        
                        $filename = $person_code->person_code .'.png';
                        
                        $base64_image = $request['image'];
        
                        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                            $data = substr($base64_image, strpos($base64_image, ',') + 1);
                        
                            $data = base64_decode($data);
                            file_put_contents(public_path("\images\iskocab\pre_registration\\").$filename, $data);
                        }
                        
                        $school_data = json_decode($request['school'], true);
                        $course_data = json_decode($request['course'], true);
    
                        $pre_registration_scholar->image = $filename;
                        $pre_registration_scholar->pre_registration_status = "UNVERIFIED";
                        $pre_registration_scholar->school_id = convertData($school_data['id']);
                        $pre_registration_scholar->school_name = convertData($school_data['school_name']);
                        $pre_registration_scholar->course_id = convertData($course_data['id']);
                        $pre_registration_scholar->course = convertData($course_data['course_description']);
                        $pre_registration_scholar->status = 1;
                        $pre_registration_scholar->save();
                        
                        DB::commit();
                    
                        return response()->json(['success' => $this->successCreateStatus, 'message' => 'Registered successfully.', 'data' => null], $this->successCreateStatus);
                    } else {
                        DB::commit();
                    
                        return response()->json(['error' => $this->errorStatus, 'message' => 'You already have a pre-registration.', 'data' => null], $this->errorStatus);
                    }
                    
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json($e, $this->queryErrorStatus);
                }
                
            } else {
                return response()->json(['error' => $this->errorStatus], $this->errorStatus);
            }
        } else {
            return response()->json(['message' => 'Registration is currently unavailble right now. Please try again later.'], $this->successStatus);
        }
    }
    
    public function checkScholarStatus()
    {
        if(Auth::user()->account_status == 1)
        {
            if(!is_null(Auth::user()->email_verified_at)){
                $status = PreRegScholar::where('user_id','=',Auth::user()->id)->first();
                
                if(is_null($status)){    
                    return response()->json(['success' => 'UNSET'], $this->successStatus);
                } else {
                    if($status->pre_registration_status == 'VERIFIED'){
                        return response()->json(['success' => 'VERIFIED'], $this->successStatus);
                    } else {
                        return response()->json(['success' => 'UNVERIFIED'], $this->successStatus);
                    }
                }
            } else {
                return response()->json(['error' => 'Please verify your ecabs account first to access this feature.'], $this->errorStatus); 
            }
            
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
