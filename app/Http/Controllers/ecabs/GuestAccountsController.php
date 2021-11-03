<?php

namespace App\Http\Controllers\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Ecabs\Person;
use App\Ecabs\PersonDepartmentPosition;
use App\Ecabs\Department;
use App\Ecabs\PositionAccess;
use App\Ecabs\Address;
use DB;
use Validator;
use Image;
use Auth;
use App\Events\DataTableEvent;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Generator;

class GuestAccountsController extends Controller
{
    //go to profile
    public function profile(){
        return view('ecabs.user.profile', ['title' => "Profile"]);
    }

    //go to account management
    public function index(){
        return view('ecabs.guest_accounts.manageaccounts', ['title' => "Accounts Management"]);
    }

    //go to account creation
    public function create(){
        return view('ecabs.guest_accounts.create', ['title' => "Create Account"]);
    }
    
    public function qrCodePrinting(){
        return view('ecabs.guest_accounts.qrcodeprinting', ['title' => "QR Code Printing"]);
    }

    //find all data
    public function findall(Request $request)
    {
    	$columns = array( 
            0 =>'last_name', 
            1 =>'department',
            2 =>'position',
            3 =>'status',
        );

        $query = DB::table('users') 
            ->join('people', 'people.id', '=', 'users.person_id') 
            ->join('person_department_positions', 'person_department_positions.person_id', '=', 'people.id')
            ->select('people.*')
            ->where('users.id', '!=', '1')
            ->where('person_department_positions.department_position_id', '=', '2');

        //datatables total data
        $totalData = $query->count();   
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        //check user department
        $department = $this->getDepartment(Auth::user())->department_position['department_id'];
        
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

           $users= $query->where('last_name', 'LIKE', "%{$search}%")
                ->orWhere('person_department_positions.department_position_id', '=', '2')->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('person_department_positions.department_position_id', '=', '2')->where('middle_name', 'LIKE', "%{$search}%")
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
                $person_address = Address::findOrFail($user->address_id);

                if($person_dept_pos){
                    $department = Department::where('id', $person_dept_pos->department_position->department_id)->first();
                    $position =  PositionAccess::where('id', $person_dept_pos->department_position->position_access_id)->first();

                    $status = "<label class='label label-danger'>Deleted</label>";

                    $buttons = "";
                    if($account->account_status == 1){ 
                        $status = "<label class='label label-primary'>Active</label>";
                    }elseif($account->account_status == 0){
                        $status = "<label class='label label-danger'>Deleted</label>";
                    } 

                    if($request['action'] == 'qrcodeprinting' && $account->account_status == 1){
                        $buttons = '<button data-toggle="tooltip" title="Click here to print Account QRCode" onclick="print_form('.$user->id.')" class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="ti-printer"></i> PRINT QR CODE</button> ';
                        
                    }

                    $fullname = $user->last_name . " ". $user->affiliation . ", ". $user->first_name . " ". $user->middle_name;
                    $address = '';
                    if($person_address->barangay) $address .= $person_address->barangay . ", ";
                    if($person_address->city) $address .= $person_address->city . ', ';
                    if($person_address->province) $address .= $person_address->province;

                    $nestedData['fullname'] = $fullname;
                    $nestedData['address'] =  $address;
                    $nestedData['position'] = $position->position;
                    $nestedData['status'] = $status;
                    $nestedData['actions'] = $buttons;
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

        $validate_email=User::where('email', $request['email'])->first();

        $contact_exist=User::where('contact_number', $request['contact'])->first();

        if($validate_email) {
            return response()->json(array('success'=> false, 'error'=>'Email already exist!', 'messages'=>'Please provide another email!'));
        }
        else if($contact_exist) {
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
                    $changes = $address->getDirty();
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
                        $filename= 'ecabs/' . date('Y') . '' . $person->id .'.'. $request['avatar']->getClientOriginalExtension();
                        $path=public_path('images/'. $filename);
                        Image::make($request['avatar']->getRealPath())->resize(200, 200)->save($path);
                    }
    
                    $person->image = !empty($filename)?$filename : 'ecabs/default-avatar.png';
                    $changes = array_merge($changes, $person->getDirty());
                    $person->save();

                    //add user
                    $user = new User;
                    $user->person_id = $person->id;
                    $user->email = $request['email'];
                    $user->password = bcrypt('admin123');

                    //format contact
                    $contact = $request['contact'];
                    if(strlen($contact) == 11){
                        $contact = substr_replace($contact, '+63', 0, 1);
                    }
                    $user->contact_number = $contact;
                    
                    $user->account_status = '1';
                    $user->device_identifier = identifierCredentials('', 'web_account', 'create_identifier');
                    $changes = array_merge($changes, $user->getDirty());
                    $user->save();

                    //add person department position
                    $person_department_position = new PersonDepartmentPosition;
                    $person_department_position->person_id = $person->id;
                    $person_department_position->department_position_id = 2;
                    $person_department_position->status = '1';
                    $changes = array_merge($changes, $person_department_position->getDirty());
                    $person_department_position->save();
                    
                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;
                    $person->person_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $person->id, 16, '0', STR_PAD_LEFT);
                    $person->save();
                    
                    DB::commit();
                    
                    /* logs */
                    action_log('Guest account Mngt', 'CREATE', array_merge(['id' => $user->id], $changes));

                    // event(new DataTableEvent(true));
                    return response()->json(array('success'=> true, 'messages'=>'Record successfully Saved!'));
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
                }
            }
        }
    }

    public function printQrCode($id){
        $person = Person::findOrFail($id);
        $address = Address::findOrFail($person->address_id);

        $qrCode = new Generator;
        $data = $qrCode->size(200)->generate($person->person_code);

        $fullname = $person->last_name . " " . $person->affiliation . ", " . $person->first_name . " ";
        
        if($person->middle_name){
            $fullname .= $person->middle_name[0] . "."; 
        }
        
        /* logs */
        action_log('Guest account Mngt', 'PRINT', array_merge(['id' => $person->id]));

        return response()->json(['qrcode' => $data, 'name' => $fullname, 'address' => $address->barangay . ', ' . $address->city . ', ' . $address->province]);
    }
}
