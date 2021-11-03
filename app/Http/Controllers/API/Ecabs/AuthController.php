<?php

namespace App\Http\Controllers\API\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Ecabs\Address;
use Illuminate\Support\Str;
use App\Ecabs\ForgotPassword;
use App\Ecabs\Person;
use App\Mail\VerifyEmail;
use App\Ecabs\PersonDepartmentPosition;
use App\User;
use Validator;
use DB;
use Auth;
use Mail;

use \stdClass;

use Gate;

class AuthController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;
    public $serverErrorStatus = 500;

    public function getPermissions($person_id, $action) {
        $person_dept_pos = DB::table('person_department_positions')
                ->select('position_accesses.access', 'position_accesses.status')
                ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
                ->join('position_accesses', 'position_accesses.id', '=', 'department_positions.position_access_id')
                ->where('person_department_positions.person_id', '=', $person_id)->first();

        $check = ($person_dept_pos)? unserialize($person_dept_pos->access) : null;

        // $permission = new stdClass;
        // $permission->permission = $action;
        // $permission->has_access = false;

        if($check != null){
            if(in_array($action, unserialize($person_dept_pos->access)) && $person_dept_pos->status == '1'){
                return true;
            }
        }

        return false;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        $username = format_number($request->username);

        if(is_numeric($username)){
            if(strlen($username) == 11){
                $username = substr_replace($username, '+63', 0, 1);
            } else {
                $username = substr_replace($username, '+63', 0, 3);
            }
        }

        $check_user = User::where('contact_number', '=', $username)
                        ->orWhere('email', '=', $username)
                        ->select(
                            'account_status',
                            'device_identifier',
                            'email_verified_at'
                        )->first();

        if(!is_null($check_user)){

            if(!is_numeric($username) && is_null($check_user->email_verified_at)){
                return response()->json(['error'=> 'Your Email is not yet verified. Please verify your email first or use your registered mobile number to login.'], $this->errorStatus);
            }



            if(identifierCredentials($check_user->device_identifier, 'mobile_account', 'hash_check') || identifierCredentials($check_user->device_identifier, 'web_account', 'hash_check')){


                if($check_user->account_status == 1){

                    try {
                        DB::connection('mysql')->beginTransaction();

                        $http = new \GuzzleHttp\Client;

                        try {

                            // dd(config('services.passport.login_endpoint'));
                            $response = $http->post(config('services.passport.login_endpoint') . '/oauth/token', [
                                'form_params' => [
                                    'grant_type' => 'password',
                                    'client_id' => config('services.passport.client_id'),
                                    'client_secret' => config('services.passport.client_secret'),
                                    'username' => $username,
                                    'password' => $request->password,
                                ]
                            ]);


                            $token = json_decode((string) $response->getBody(), true);

                            // dd($response->getResourceOwner());
                            $user = User::where('contact_number', '=', $username)
                                    ->orWhere('email', '=', $username)
                                    ->first();

                            if(Hash::check($request->password, $user->password)) {
                                $user_details = Person::where('id', '=', $user->person_id)->select('person_code', 'first_name', 'last_name', 'address_id', 'affiliation')->first();
                                $user_address = Address::where('id', '=', $user_details->address_id)->select('barangay', 'city')->first();
                                $department = PersonDepartmentPosition::where('person_id', '=', $user->person_id)->with('department_position', 'department_position.departments')
                                            ->first();
                                $department = $department->department_position->departments;
                                if($department->status != 1){
                                    $department = null;
                                }
                            }

                            DB::connection('mysql')->commit();

                            // $user_permissions = array();

                            $permission = new stdClass;
                            $permission->viewRegistrationAndValidation = $this->getPermissions($user->person_id, 'viewRegistrationAndValidation');
                            $permission->viewVaccinationMonitoring = $this->getPermissions($user->person_id, 'viewVaccinationMonitoring');
                            $permission->viewSecondDoseVerification = false;
                            $permission->printAssessment = $this->getPermissions($user->person_id, 'printAssessment');


                            return response()->json([
                                'status' => $this->successStatus,
                                'success' => $token,
                                'user_details' => $user_details,
                                'user_permission' => $permission,
                                'user_address' => $user_address,
                                'department' => $department,
                            ], $this->successStatus);

                        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                            if ($e->getCode() === 400) {
                                return response()->json(['status' => $this->queryErrorStatus, 'message' => 'Your credentials are incorrect. Please try again'], $this->errorStatus);
                            } else if ($e->getCode() === 401) {
                                return response()->json(['status' => $this->errorStatus, 'message' => 'Invalid Request. Please enter a username or a password.'], $this->errorStatus);

                            }
                            return response()->json(['status' => $this->serverErrorStatus, 'message' => 'Something went wrong on the server.'], $this->serverErrorStatus);
                        }
                    } catch (\PDOException $e) {
                        DB::connection('mysql')->rollBack();
                        // return response()->json($e, $this->queryErrorStatus);
                        return response()->json(['status' => $this->serverErrorStatus, 'message' => 'Something went wrong on the server.'], $this->serverErrorStatus);
                    }
                } else {
                    return response()->json(['status' => $this->errorStatus, 'message'=> 'Your account is deactivated. Please contact our support.'], $this->errorStatus);
                }
            } else {
                return response()->json(['status' => $this->errorStatus, 'message'=> 'Your account is not supported to access this feature.'], $this->errorStatus);
            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message'=> 'No existing account.'], $this->errorStatus);
        }
        // } else {
        //     return redirect('/');
        // }
    }

    public function checkIfExists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $request['email'],
            'contact_number' => 'required|unique:users,contact_number,' . $request['contact_number'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        return response()->json(['success' => 'Credentials is available'], $this->successStatus);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'contact_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'user_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        try {
            DB::beginTransaction();

            $person = new Person();
            $person->first_name = convertData($request['first_name']);
            $person->last_name = convertData($request['last_name']);
            $person->image = 'ecabs/default-avatar.png';
            $person->save();

            $current_date = Carbon::today();
            $year = $current_date->year;
            $day = $current_date->day;
            $month = $current_date->month;
            $person->person_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . cstmCombination() .  str_pad($day . substr($year, -2) . $month . $person->id, 16, '0', STR_PAD_LEFT);
            $person->save();

            $user = new User();
            $user->person_id = $person->id;
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);

            $user_contact = format_number($request['contact_number']);

            if(is_numeric($user_contact)){
                if(strlen($user_contact) == 11){
                    $user_contact = substr_replace($user_contact, '+63', 0, 1);
                } else {
                    $user_contact = substr_replace($user_contact, '+63', 0, 3);
                }
            }

            $user->contact_number = $user_contact;
            $user->remember_token = Str::random(10);
            $user->device_identifier = identifierCredentials('', 'mobile_account', 'create_identifier');
            $user->account_status = 1;
            $user->user_type = convertData($request['user_type']);
            $user->mac_address = $request['mac_address'];
            $user->save();

            $new_dept = new PersonDepartmentPosition();
            $new_dept->person_id = $person->id;
            $new_dept->department_position_id = 2;
            $new_dept->status = 1;
            $new_dept->save();

            $address = new Address;
            $address->save();

            $person->address_id = $address->id;
            $person->save();

            // $code = md5('verify_email');
            // $user_code = md5('verify_email' . $person->person_code);
            // $details = [
            //     'name' => $person->first_name . ' ' . $person->last_name ,
            //     'link' => env('EMAIL_VERIFY_ENDPOINT').'/verify/'.$person->person_code.'/'.$code.'/'.$user_code,
            // ];

            // Mail::to($user->email)->send(new VerifyEmail($details));

            DB::commit();

            $http = new \GuzzleHttp\Client;
            try {
                $response = $http->post(config('services.passport.login_endpoint') . '/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => config('services.passport.client_id'),
                        'client_secret' => config('services.passport.client_secret'),
                        'username' => $user->email,
                        'password' => $request['password'],
                    ]
                ]);

                $token = json_decode((string) $response->getBody(), true);

            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                if ($e->getCode() === 400) {
                    return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
                } else if ($e->getCode() === 401) {
                    return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
                }
                return response()->json('Something went wrong on the server.', $e->getCode());
            }
            $user_details['person_code'] = $person->person_code;
            $user_details['first_name'] = $person->first_name;
            $user_details['last_name'] = $person->last_name;

            return response()->json(['success' => $token, 'user_details' => $user_details], $this->successCreateStatus);

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json($e, $this->queryErrorStatus);
        }
    }

    public function logout()
    {


        // dd("Sample");
        try {
            DB::beginTransaction();
            DB::table('oauth_access_tokens')->where('id', '=', Auth::user()->token()->id)->delete();

            DB::commit();

            return response()->json(['status' => $this->successStatus, 'message' => 'Logged out successfully'], $this->successStatus);

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['status' => $this->errorStatus, 'message'=> 'Error Request'], $this->errorStatus);
            // return response()->json($e, $this->queryErrorStatus);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        $user = User::findOrFail(Auth::user()->id);
        if(Hash::check($request['old_password'], $user->password))
        {
            try {
                DB::beginTransaction();

                if(!is_null($request['password'])){
                    $user->password = Hash::make($request['password']);
                }

                $user->save();

                DB::commit();

                return response()->json(['status' => $this->successStatus, 'message' => 'Password changed successfully'],$this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(['status' => $this->queryErrorStatus, 'message' => 'Something went wrong! Please try again'], $this->queryErrorStatus);
            }
        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Old password does not match'],$this->errorStatus);
        }
    }

    public function checkUserExists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        $findUser = User::where('contact_number', '=', $request['username'])
                    ->orWhere('email', '=', $request['username'])
                    ->select(
                        'id',
                        'contact_number'
                    )->first();

        if(is_null($findUser)){
            return response()->json(['error' => $findUser, 'message' => 'User not found'], $this->errorStatus);
        } else {
            return response()->json(['success' => $findUser, 'message' => "User's contact"], $this->successCreateStatus);
        }
    }

    public function forgotPassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $changePass = new ForgotPassword();
            $changePass->user_id = $id;
            $changePass->old_password = $user->password;
            $user->password = Hash::make($request['password']);
            $changePass->new_password = Hash::make($request['password']);
            $user->save();
            $changePass->save();

            DB::commit();

            return response()->json(['message' => 'Password changed successfully'],$this->successCreateStatus);

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json($e, $this->queryErrorStatus);
        }
    }

    public function checkOldPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        $user = User::findOrFail(Auth::user()->id);
        if(Hash::check($request['password'], $user->password)){
            return response()->json(['success'=> 'Password match'], $this->successStatus);
        } else {
            return response()->json(['error'=> 'Password does not match!'], $this->errorStatus);
        }
    }

    public function checkContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_number' => 'required|unique:users,contact_number,' . $request['contact_number'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        return response()->json(['success' => 'Contact is available'], $this->successStatus);
    }

    public function updateContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        try {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::user()->id);
            $user->contact_number = $request['contact_number'];
            $user->save();

            DB::commit();

            return response()->json(['message' => 'Contact number changed successfully'],$this->successCreateStatus);

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json($e, $this->queryErrorStatus);
        }
    }

    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $request['email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        try {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::user()->id);
            $user->email = $request['email'];
            $user->email_verified_at = null;
            $user->save();

            DB::commit();

            return response()->json(['message' => 'Email number changed successfully'],$this->successCreateStatus);

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json($e, $this->queryErrorStatus);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            DB::beginTransaction();

            $http = new \GuzzleHttp\Client;

            $response = $http->post(config('services.passport.login_endpoint') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request['refresh_token'],
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'scope' => '',
                ],
            ]);

            $token = json_decode((string) $response->getBody(), true);

            DB::commit();

            return response()->json(['success' => $token], $this->successStatus);

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json($e, $this->queryErrorStatus);
        }
    }

    public function createdAtToken()
    {
        try {
            DB::beginTransaction();
            $created_at = DB::table('oauth_access_tokens')
                            ->where('id', '=', Auth::user()->token()->id)
                            ->select('created_at')
                            ->first();

            DB::commit();

            return response()->json($created_at, $this->successStatus);

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json($e, $this->queryErrorStatus);
        }
    }

    public function checkChangeAlreadyPass($id)
    {
        $changepassCreds_counter = ForgotPassword::where('user_id', '=', $id)->whereMonth('created_at', Carbon::now()->month)->count();
        if($changepassCreds_counter > 0){
            return response()->json(['error' => 'Reach maximum counts of change password request. You can only change your password once a month. If you need help please contact our support.'], $this->errorStatus);
        } else {
            return response()->json(['success' => 'Able to change password'], $this->successStatus);
        }
    }
}
