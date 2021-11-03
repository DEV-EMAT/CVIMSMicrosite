<?php

namespace App\Http\Controllers\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Ecabs\Barangay;
use App\Ecabs\Person;
use App\Ecabs\DepartmentPosition;
use App\Ecabs\PersonDepartmentPosition;
use App\Ecabs\Department;
use App\Ecabs\PositionAccess;
use App\Ecabs\Address;
use App\Ecabs\UserDeletionHistory;
use DB;
use Gate;
use Validator;
use File;
use Image;
use Hash;
use Auth;
use App\Events\DataTableEvent;
use Carbon\Carbon;
use App\CovidTracer\EstablishmentStaff;
use PDF;
use SimpleSoftwareIO\QrCode\Generator;

class UserController extends Controller
{
    //go to profile
    public function profile(){
        return view('ecabs.user.profile', ['title' => "Profile"]);
    }

    //go to account management
    public function index(){
        $department = Department::where('id', $this->getDepartment(Auth::user())->department_position['department_id'])->first();
        $department_ecabs = 0;
        if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
            $department_ecabs = 1;
        }

        return view('ecabs.user.manageaccounts', ['title' => "Accounts Management", 'department_status' => $department_ecabs, 'department' => $department]);
    }

    //go to account creation
    public function create(){
        $department = Department::where('id', $this->getDepartment(Auth::user())->department_position['department_id'])->first();
        $department_ecabs = 0;
        if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
            $department_ecabs = 1;
        }

        return view('ecabs.user.create', ['title' => "Create Account", 'department_status' => $department_ecabs, 'department' => $department]);
    }

    //go to archive
    public function archive(){
        return view('ecabs.user.archive', ['title' => "Accounts Management"]);
    }

    public function gotoresetpassword(){
        return view('ecabs.user.resetpassword', ['title' => "Reset Password"]);
    }

    //get all users for combo box
    public function findallforcombobox()
    {
        $users = User::where('id', '!=', '1')->where('account_status', '1')->get();

        $data = array();
        foreach($users as $user){
            $person = Person::where('id', $user->person_id)->first();
            $fullname = $person->last_name;

            if($person->affiliation){
                $fullname .= " " . $person->affiliation;
            }
            $fullname .= ", " . $person->first_name . " ";

            if($person->middle_name){
                $fullname .= $person->middle_name[0] . ".";
            }

            $nestedData['fullname'] = $fullname;
            $nestedData['user_id'] = $user->id;
            $data[] = $nestedData;
        }
        return response()->json($data);
    }

    //find all data
    public function findall(Request $request)
    {
    	$columns = array(
            0 =>'last_name',
            1 =>'department',
            2 =>'position',
        );

        $status = 1;
        //for archive
        if($request['action'] == 'archive'){
            $status= 0;
        }

        //datatables total data
        $totalData = User::where('account_status', $status)->where('id', '!=', '1')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        //check user department
        $department = $this->getDepartment(Auth::user())->department_position['department_id'];

        if($department == 1){
            $query = DB::table('users')
            ->join('people', 'people.id', '=', 'users.person_id')
            ->select('people.*')
            ->where('users.account_status', $status)->where('users.id', '!=', '1');
        }else{
            $query = DB::table('users')
                ->join('people', 'people.id', '=', 'users.person_id')
                ->join('person_department_positions', 'person_department_positions.person_id', '=', 'people.id')
                ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
                ->join('departments', 'departments.id', '=', 'department_positions.department_id')
                ->select('people.*')
                ->where('departments.id', '=', $department)
                ->where('users.account_status', $status)
                ->where('users.id', '!=', '1');
        }
        if($request['action'] == "selectUser"){
            $query = DB::table('users')
                ->join('people', 'people.id', '=', 'users.person_id')
                ->join('person_department_positions', 'person_department_positions.person_id', '=', 'people.id')
                ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
                ->join('departments', 'departments.id', '=', 'department_positions.department_id')
                ->select('people.*')
                ->where('users.account_status', $status)
                ->where('users.id', '!=', '1');
        }


        if(empty($request->input('search.value')))
        {
            $users = $query
                    ->offset($start)
                 	->limit($limit)
                 	->orderBy($order,$dir)
                    ->get();
        }
        else {
           $search = $request->input('search.value');

           $users= $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', $status)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query
                ->count();
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $account = User::where('person_id', $user->id)->first();
                $person_dept_pos = PersonDepartmentPosition::where('person_id', $account['person_id'])->with('department_position')->first();

                if($person_dept_pos){
                    $department = Department::where('id', $person_dept_pos->department_position->department_id)->first();
                    $position =  PositionAccess::where('id', $person_dept_pos->department_position->position_access_id)->first();

                    $status = "<label class='label label-danger'>Deleted</label>";

                    $buttons = ' <a data-toggle="tooltip" title="Click here to view Account Information" onclick="view('. $account->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW</a>';
                    if($account->account_status == 1){

                        if(Gate::allows('permission', 'updateAccount')){
                            $buttons .=' <a data-toggle="tooltip" title="Click here to edit Account Information" onclick="edit('. $account->id .')" class="btn btn-xs btn-success btn-fill btn-rotate  edit"><i class="ti-pencil-alt"></i> EDIT</a>';
                        }

                        if(Gate::allows('permission', 'deleteAccount')){
                            $buttons .= ' <a data-toggle="tooltip" title="Click here to deactivate Account" onclick="deactivate('. $account->id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';
                        }

                        $status = "<label class='label label-primary'>Active</label>";
                    }
                    else{
                        $buttons .= ' <a data-toggle="tooltip" title="Click here to restore Account" onclick="activate('. $account->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate restore"><i class="ti-reload"></i> RESTORE</a>';
                    }

                    //for reset password
                    if($request['action'] == 'reset_password'){
                        $buttons = '<button data-toggle="tooltip" title="Click here to reset Account Password" onclick="resetpassword('.$account->id.')" class="btn btn-xs btn-primary btn-fill btn-rotate" ';
                        $buttons.= (Hash::check('admin123', $account['password']))? "disabled": '';
                        $buttons.='><i class="ti-lock"></i> RESET</button>';

                        $status=(Hash::check('admin123', $account['password']))?'<label class="label label-primary">Default password</label>': '<label class="label label-danger">Password Changed</label>';
                    }

                    //user priting of qr code
                    if($request['action'] == 'qrcodeprinting'){
                        $buttons = '<button data-toggle="tooltip" title="Click here to print Account QRCode" onclick="print_form('.$user->id.')" class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="ti-printer"></i> PRINT QR CODE</button> ';
                        // $buttons = '<a href="/account/print-qr-code/'. $user->id .'" target="_blank"  class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="ti-printer"></i> PRINT QR CODE</a> ';
                    }

                    //patient encoding
                    if($request['action'] == 'selectUser'){
                        $buttons = ' <a onclick="addProfile('. $account->id .')" class="btn btn-xs btn-warning btn-fill btn-rotate view"><i class="fa fa-plus"></i> Add this profile</a>';
                    }

                    //for SMS History
                    if($request['action'] == 'smsHistory'){
                        $buttons = ' <a data-toggle="tooltip" title="Click here to view Sms History" onclick="viewSmsHistory('. $account->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW SMS HISTORY</a>';
                    }

                    $fullname = $user->last_name;

                    if($user->affiliation){
                        $fullname .= " " . $user->affiliation;
                    }
                    $fullname .= ", " . $user->first_name . " ";

                    if($user->middle_name){
                        $fullname .= $user->middle_name[0] . ".";
                    }

                    $nestedData['fullname'] = $fullname;
                    $nestedData['department'] = $department->department;
                    $nestedData['position'] = $position->position;
                    $nestedData['status'] = $status;
                    $nestedData['actions'] = $buttons;
                    $nestedData['id'] = $account->id;
                    $data[] = $nestedData;
                }
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

    //save new data
    public function store(Request $request){
        $validator=Validator::make($request->all(), [
            'first_name'=> ' required',
            'last_name'=> ' required',
            'dob'=> ' required',
            'email' => 'required',
            'sex'=> ' required',
            'contact'=> ' required',
            'address'=> ' required',
        ]);


        $contact_exist = false;

        $validate_email=User::where('email', '=', $request['email'])->first();

        $contact_exist=User::where('contact_number', '=', $request['contact'])->first();

        if($validate_email) {
            return response()->json(array('success'=> false, 'error'=>'Email already exist!', 'messages'=>'Please provide another email!'));
        }
        else if($contact_exist == true) {
            return response()->json(array('success'=> false, 'error'=>'Contact already exist!', 'messages'=>'Please provide another contact!'));
        }
        else {
            if($validator->fails()) {
                return response()->json(array('success'=> false, 'error'=>'Validation error!', 'messages'=>'Please provide valid inputs!'));
            }
            else {
                try {
                    DB::beginTransaction();

                    $address = new Address;
                    $address->region = $request['txtRegion'];
                    $address->region_id = $request['region'];
                    $address->barangay = $request['txtBarangay'];
                    $address->barangay_id = $request['barangay'];
                    $address->city = $request['city'];
                    $address->province = $request['province'];
                    $address->status = '1';
                    $address->save();

                    //add person
                    $person = new Person;
                    $person->first_name=convertData($request['first_name']);
                    $person->last_name=convertData($request['last_name']);
                    $person->middle_name=convertData($request['middle_name']);
                    $person->affiliation=convertData($request['affiliation']);
                    $person->gender=convertData($request['sex']);
                    $person->date_of_birth=convertData($request['dob']);
                    $person->address=convertData($request['address']);
                    $person->address_id = $address->id;
                    $person->civil_status=convertData($request['civil_status']);
                    $person->telephone_number=convertData($request['telephone']);
                    $person->religion=convertData($request['religion']);

                    $person->save();

                    if($request->hasFile('avatar')) {
                        $filename= 'ecabs/profiles/' . date('Y') . '' . $person->id .'.'. $request['avatar']->getClientOriginalExtension();
                        $path=public_path('images/'. $filename);
                        Image::make($request['avatar']->getRealPath())->resize(200, 200)->save($path);
                    }

                    $person->image = !empty($filename)?$filename : 'ecabs/profiles/default-avatar.png';
                    $person->save();

                    //add user
                    $user = new User;
                    $user->email = $request['email'];
                    $user->password = bcrypt('admin123');

                    //format contact
                    $contact = $request['contact'];
                    if(strlen($contact) == 11){
                        $contact = substr_replace($contact, '+63', 0, 1);
                    }
                    $user->contact_number = $contact;

                    $user->account_status = '1';
                    $user->person_id = $person->id;
                    $user->save();

                    //department id of ecabs account
                    $get_department_id = DepartmentPosition::where('department_id', $this->getDepartment(Auth::user())->department_position['department_id'])
                                        ->where('position_access_id', $request['access'])
                                        ->first();

                    //department id of non-ecabs account
                    if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
                        $get_department_id = DepartmentPosition::where('department_id', $request['department'])
                        ->where('position_access_id', $request['access'])
                        ->first();
                    }

                    //add person department position
                    $person_department_position = new PersonDepartmentPosition;
                    $person_department_position->person_id = $person->id;
                    $department_position_id = $get_department_id->id;
                    $person_department_position->department_position_id = $department_position_id;
                    $person_department_position->status = '1';
                    $person_department_position->save();

                    $identifier = identifierCredentials('', 'web_account', 'create_identifier');
                    if($get_department_id->id == 2){
                        $identifier = identifierCredentials('', 'mobile_account', 'create_identifier');
                    }
                    $user->device_identifier = $identifier;
                    $user->save();

                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;
                    $person->person_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $person->id, 16, '0', STR_PAD_LEFT);
                    $person->save();

                    $fullname = $person->last_name;

                    if($person->affiliation){
                        $fullname .= " " . $person->affiliation;
                    }
                    $fullname .= ", " . $person->first_name . " ";

                    if($person->middle_name){
                        $fullname .= $person->middle_name[0] . ".";
                    }

                    DB::commit();
                    // activity logs
                    action_log('ACCOUNT MNGT', 'CREATE ACCOUNT :'. $user->id . '(' . $fullname .')');

                    return response()->json(array('success'=> true, 'messages'=>'Record successfully Saved!'));
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
                }
            }
        }
    }

    //view data
	public function show($id) {
        $user = User::findOrFail($id);
        $person = Person::findOrFail($user['person_id']);
        $barangay = Barangay::where('id', '=', $person['barangay_id'])->first();
        $address = "";

        $person_department_position = PersonDepartmentPosition::where('person_id', '=', $person['id'])
                    ->join('department_positions', 'person_department_positions.department_position_id', 'department_positions.id')
                    ->join('departments', 'department_positions.department_id', 'departments.id')
                    ->join('position_accesses', 'department_positions.position_access_id', 'position_accesses.id')->first();

        if($person->address_id){
            $address = Address::findOrFail($person->address_id);
        }
        $department = $person_department_position->department_position->department_id;
        $position = $person_department_position->department_position->position_access_id;
        $user_department = $this->getDepartment(Auth::user())->department_position['department_id'];

        return response()->json(array($person, $user,  $barangay, $department, $position, $user_department, $address));
    }
    //update data
    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(), [
                'edit_first_name'=> ' required',
                'edit_last_name'=> ' required',
                'edit_dob'=> ' required',
                'edit_sex'=> ' required',
                'edit_contact'=> ' required',
                'edit_address'=> ' required'
                ]);


        $user = User::findOrFail($id);
        $person = Person::findOrFail($user['person_id']);

        $address = Address::findOrFail($person['address_id']);

        $email_exist = false;
        $contact_exist = false;
        if($user['email'] != $request['edit_email']){
            $email_exist=User::where('email', '=', $request['edit_email'])->first();
        }

        if($user['contact_number'] != $request['edit_contact']){
            $contact_exist=User::where('contact_number', '=', $request['edit_contact'])->first();
        }

        if($email_exist) {
            return response()->json(array('success'=> false, 'messages'=> 'Email already exist!', 'messages'=>'Please provide another email!'));
        }
        else if($contact_exist) {
            return response()->json(array('success'=> false, 'messages'=> 'Contact already exist!', 'messages'=>'Please provide another contact!'));
        }
        else {
            if($validator->fails()) {
                return response()->json(array('success'=> false, 'error'=>'Validation error!', 'messages'=>'Please provide valid inputs!'));
            }
            else {
                try {
                    DB::beginTransaction();

                    $changes = array();

                    $address->region = $request['txtRegion'];
                    $address->region_id = $request['region'];
                    $address->barangay = $request['txtBarangay'];
                    $address->barangay_id = $request['barangay'];
                    $address->city = $request['city'];
                    $address->province = $request['province'];
                    $changes = $address->getDirty();
                    $address->save();

                    $person->first_name=convertData($request['edit_first_name']);
                    $person->last_name=convertData($request['edit_last_name']);
                    $person->middle_name=convertData($request['edit_middle_name']);
                    $person->affiliation=convertData($request['edit_affiliation']);
                    $person->gender=convertData($request['edit_sex']);
                    $person->date_of_birth=convertData($request['edit_dob']);
                    $person->address=convertData($request['edit_address']);
                    $person->civil_status=convertData($request['edit_civil_status']);
                    $person->telephone_number=convertData($request['edit_telephone']);
                    $person->religion=convertData($request['edit_religion']);

                    if($request->hasFile('avatar')) {
                        $filename= 'ecabs/profiles/' . date('Y') . '' . $person->id .'.'. $request['avatar']->getClientOriginalExtension();
                        $path='/home/cabuyaovaccine/public_html/images/'. $filename;
                        //$path='/home/cabuyaovaccine/cabuyaovaccine/public/images/'. $filename);
                        Image::make($request['avatar']->getRealPath())->resize(200, 200)->save($path);

                        $person->image = $filename;
                    }
                    
                    $changes = array_merge($changes, $person->getDirty());
                    $person->save();
                    if($user['email'] != $request['edit_email'])
                        $user->email = $request['edit_email'];
                    if($user['contact_number'] != $request['edit_contact']){
                        $contact = $request['edit_contact'];
                        if(strlen($contact) == 11){
                            $contact = substr_replace($contact, '+63', 0, 1);
                        }
                        $user->contact_number = $contact;
                    }

                    if((isset($request['department']) || isset($request['department_id'])) && isset($request['access'])){
                        //update person department position
                        $person_department_position = PersonDepartmentPosition::where('person_id', '=', $person->id)->first();

                        $department_position_id = DepartmentPosition::where('department_id', '=', $request['department'])
                                                                    ->where('position_access_id', '=', $request['access'])
                                                                    ->first();


                        if($this->getDepartment(Auth::user())->department_position['department_id'] > 1){
                            $department_position_id = DepartmentPosition::where('department_id', $request['department_id'])
                                                    ->where('position_access_id', '=', $request['access'])
                                                    ->first();
                        }
                        //2 = guest
                        $identifier = identifierCredentials('', 'web_account', 'create_identifier');
                        if($request['department'] == 2){
                            $identifier = identifierCredentials('', 'mobile_account', 'create_identifier');
                        }
                        $user->device_identifier = $identifier;

                        $person_department_position->department_position_id = $department_position_id->id;
                        
                        $changes = array_merge($changes, $person_department_position->getDirty());
                        $person_department_position->save();
                    }

                    // $userChanges = array();

                    // $userChanges[] = $user['contact_number']->getDirty();
                    // $userChanges[] = $user->isDirty('contact_number');
                    // $userChanges[] = $user['email']->getDirty();
                    // $userChanges[] = $user['password']->getDirty();
                    // $user->save();
                    DB::commit();
                                        
                    $fullname = $person->last_name;

                    if($person->affiliation){
                        $fullname .= " " . $person->affiliation;
                    }
                    $fullname .= ", " . $person->first_name . " ";

                    if($person->middle_name){
                        $fullname .= $person->middle_name[0] . ".";
                    }
                    
                    /* logs */
                    action_log('User Mngt', 'Update', array_merge(['id' => $person->id], $changes));

                    return response()->json(array('success'=> true, 'messages'=>'Record successfully Updated!'));
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
                }
            }
        }
    }

    //deactivate or activate data
    public function updatestatus(Request $request, $id) {
        $user = User::findOrFail($id);
        $status=$user->account_status;
        try {
            DB::beginTransaction();

            if($status==1){
                $user->account_status=0;

                $deleteHistory = new UserDeletionHistory;
                $deleteHistory->updated_by = Auth::user()->id;
                $deleteHistory->updated_status_user_id = $id;
                $deleteHistory->reason = $request['reason'];
                $deleteHistory->status = '0';
                $deleteHistory->save();

                // activity logs
                action_log('ACCOUNT MNGT', 'DELETE ACCOUNT :'. $user->id);
            }else {
                $user->account_status=1;

                $restoreHistory = new UserDeletionHistory;
                $restoreHistory->updated_by = Auth::user()->id;
                $restoreHistory->updated_status_user_id = $id;
                $restoreHistory->reason = $request['reason'];
                $restoreHistory->status = '1';
                $restoreHistory->save();

                // activity logs
                action_log('ACCOUNT MNGT', 'RESTORE ACCOUNT :'. $user->id);
            }
            $user->save();
            DB::commit();

            return response()->json(array('success'=> true, 'messages'=> 'Successfully Updated!'));

        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    //reset password
    public function resetpassword($id){
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->password=bcrypt('admin123');
            $user->save();

            DB::commit();
            // activity logs
            action_log('ACCOUNT MNGT', 'RESET PASSWORD :'. $user->id);

            return response()->json(array('success'=> true, 'messages'=>"Account of ".$user['email'].' already reset!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    //change password
    public function changepassword(Request $request, $id){
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            $user->password = bcrypt($request['new_password']);
            $user->save();

            DB::commit();

            // activity logs
            action_log('ACCOUNT MNGT', 'CHANGE PASSWORD');

            return response()->json(array('success'=> true, 'messages'=>"Password successfully change!"));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function userCount(){
        return response()->json(User::where('account_status', '=', 1)->count());
    }

    public function qrCodePrinting(){
        return view('ecabs.user.qrcodeprinting', ['title' => "QR Code Printing"]);
    }

    public function printQrCode($id){
        $person = Person::findOrFail($id);
        $address = Address::findOrFail($person->address_id);

        $qrCode = new Generator;
        $data = $qrCode->size(200)->generate($person->person_code);

        // $barangay = Barangay::findOrFail($person->barangay_id);
        $fullname = $person->last_name;

        if($person->affiliation){
            $fullname .= " " . $person->affiliation;
        }
        $fullname .= ", " . $person->first_name . " ";

        if($person->middle_name){
            $fullname .= $person->middle_name[0] . ".";
        }

        return response()->json(['qrcode' => $data, 'name' => $fullname, 'address' => $address->barangay . ', ' . $address->city . ', ' . $address->province]);
    }

    public function verifyPassword(Request $request){
        if(Hash::check($request['password'], Auth::user()->password)) {
            return response()->json(array('success'=>true));
        } else {
            return response()->json(array('success'=>false));
        }
    }

    public function updatingEmailVerified($person_code = null, $code = null, $user_code = null)
    {
        $check_if_exist = Person::where('person_code', '=', $person_code)->first();

        if((!is_null($check_if_exist)) && (md5('verify_email') == $code) && (md5('verify_email'.$person_code) == $user_code)){

            $user = User::where('person_id', '=', $check_if_exist->id)->first();

            if(is_null($user->email_verified_at)){
                try {
                    DB::beginTransaction();

                    $user->email_verified_at = \Carbon\Carbon::now();
                    $user->update();

                    DB::commit();

                    return redirect('/');

                } catch (\PDOException $e) {
                    DB::rollBack();
                    return redirect('/');
                }

            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    public function getAllPendingAccount()
    {
        return view('ecabs.user.verify_acc', ['title' => "Verify Account"]);
    }

    public function checkPassword(){
        if(Hash::check('admin123', Auth::user()->password)) {
            return response()->json(array('success'=>true));
        } else {
            return response()->json(array('success'=>false));
        }
    }
}
