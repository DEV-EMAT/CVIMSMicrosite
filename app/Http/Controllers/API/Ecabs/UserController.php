<?php

namespace App\Http\Controllers\API\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\VerifyEmail;
use App\Ecabs\Address;
use App\Ecabs\Person;
use App\User; 
use Validator;
use Auth;
use DB;
use Mail;

class UserController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function profile()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                $checkAddressHasId = User::join('people', 'users.person_id', 'people.id')
                                    ->where('users.id', '=', Auth::user()->id)->first();
                
                if(is_null($checkAddressHasId->address_id)){
                    $user = User::join('people', 'users.person_id', 'people.id')
                        ->select(
                            'users.contact_number',
                            'users.email',
                            'people.first_name',
                            'people.middle_name',
                            'people.last_name',
                            'people.affiliation',
                            'people.gender',
                            'people.address',
                            'people.date_of_birth',
                            'people.civil_status',
                            'people.telephone_number',
                            'people.person_code',
                        )->where('users.id', '=', Auth::user()->id)->first();

                    DB::commit();
                    return response()->json(['success' => $user, 'message' => 'Please update your Profile'], $this->successStatus);
                } else {
                    $user = User::join('people', 'users.person_id', 'people.id')
                        ->join('addresses', 'people.address_id', 'addresses.id')
                        ->select(
                            'users.id AS user_id',
                            'users.contact_number',
                            'users.email',
                            'people.first_name',
                            'people.middle_name',
                            'people.last_name',
                            'people.affiliation',
                            'people.gender',
                            'people.address',
                            'people.date_of_birth',
                            'people.civil_status',
                            'people.telephone_number',
                            'people.person_code',
                            'addresses.id AS address_id',
                            'addresses.region',
                            'addresses.barangay',
                            'addresses.city',
                            'addresses.province',
                        )->where('users.id', '=', Auth::user()->id)->first();
                    
                    DB::commit();
                    return response()->json(['success' => $user], $this->successStatus);
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function updateProfile(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $user = User::findOrFail(Auth::user()->id);

            $validator = Validator::make($request->all(), [ 
                'first_name' => 'required',
                'last_name' => 'required',
                'gender' => 'required',
                'address' => 'required',
                'date_of_birth' => 'required',
                'civil_status' => 'required',
                'barangay' => 'required',
                'city' => 'required',
                'province' => 'required',
                'region' => 'required',
                'region_id' => 'required',
                'barangay_id' => 'required'

            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }
            try {
                DB::beginTransaction();

                $person = Person::findOrFail($user->person_id);

                if(is_null($person->address_id)){

                    $address = new Address();
                    $address->region = convertData($request['region']);
                    $address->region_id = convertData($request['region_id']);
                    $address->province = convertData($request['province']);
                    $address->city = convertData($request['city']);
                    $address->barangay = convertData($request['barangay']);
                    $address->barangay_id = convertData($request['barangay_id']);
                    $address->status = '1';
                    $address->save();
        
                    $person->first_name = convertData($request['first_name']);
                    $person->middle_name = convertData($request['middle_name']);
                    $person->last_name = convertData($request['last_name']);
                    $person->affiliation = convertData($request['affiliation']);
                    $person->gender = convertData($request['gender']);
                    $person->address = convertData($request['address']);
                    $person->date_of_birth = convertData($request['date_of_birth']);
                    $person->civil_status = convertData($request['civil_status']);
                    $person->address_id = $address->id;
                    $person->telephone_number = $request['telephone_number'];
                    $person->save();

                } else {

                    $address = Address::findOrFail($person->address_id);
                    $address->region = convertData($request['region']);
                    $address->region_id = convertData($request['region_id']);
                    $address->province = convertData($request['province']);
                    $address->city = convertData($request['city']);
                    $address->barangay = convertData($request['barangay']);
                    $address->barangay_id = convertData($request['barangay_id']);
                    $address->status = '1';
                    $address->save();
        
                    $person->first_name = convertData($request['first_name']);
                    $person->middle_name = convertData($request['middle_name']);
                    $person->last_name = convertData($request['last_name']);
                    $person->affiliation = convertData($request['affiliation']);
                    $person->gender = convertData($request['gender']);
                    $person->address = convertData($request['address']);
                    $person->date_of_birth = convertData($request['date_of_birth']);
                    $person->civil_status = convertData($request['civil_status']);
                    $person->telephone_number = $request['telephone_number'];
                    $person->save();

                }

                $update_profile = User::join('people', 'users.person_id', 'people.id')
                    ->join('addresses', 'people.address_id', 'addresses.id')
                    ->select(
                        'users.id AS user_id',
                        'users.contact_number',
                        'users.email',
                        'people.first_name',
                        'people.middle_name',
                        'people.last_name',
                        'people.affiliation',
                        'people.gender',
                        'people.address',
                        'people.date_of_birth',
                        'people.civil_status',
                        'people.telephone_number',
                        'people.person_code',
                        'addresses.id AS address_id',
                        'addresses.region',
                        'addresses.province',
                        'addresses.city',
                        'addresses.barangay'
                    )->where('users.id', '=', Auth::user()->id)->first();
                
                DB::commit();

                return response()->json(['success' => $update_profile], $this->successCreateStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
    
    public function verifyEmail()
    {
        if(Auth::user()->account_status == 1){
        
            if(Auth::user()->email_verified_at == null){
                try {
                    DB::beginTransaction();
                    
                    $user = Person::where('id', '=', Auth::user()->person_id)->first();
                    $code = md5('verify_email');
                    $user_code = md5('verify_email'.$user->person_code);
                    $details = [
                        'name' => $user->first_name . ' ' . $user->last_name ,
                        'link' => env('EMAIL_VERIFY_ENDPOINT').'/verify/'.$user->person_code.'/'.$code.'/'.$user_code,
                        
                    ];
                   
                    Mail::to(Auth::user()->email)->send(new VerifyEmail($details));
                    
                    DB::commit();
                    
                    return response()->json(['success' => 'Email Sent'], $this->successStatus);
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json($e, $this->queryErrorStatus);
                }
            } else {
                return response()->json(['success' => $this->successStatus, 'message' => 'Email already verified.'], $this->successStatus); 
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
