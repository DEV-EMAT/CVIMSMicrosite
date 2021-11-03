<?php

namespace App\Http\Controllers\ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ecabs\PersonDepartmentPosition;
use App\PreReg\PreRegistration;
use App\Ecabs\Address;
use App\Ecabs\Person;
use Carbon\Carbon;
use Validator;
use App\User;
use DB;

class PreRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ecabs.user.verify_acc', ['title' => "Verify Pre-registration Account"]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $data = PreRegistration::where('id', '=', $id)->first();
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [ 
            'edit_first_name'=> ' required',
            'edit_last_name'=> ' required',
            'edit_dob'=> ' required',
            'edit_sex'=> ' required',
            'edit_address'=> ' required',
        ]);

        $validate_email = User::where('email', $request['edit_email'])->first();
            
        $number = convertData($request['edit_contact']);
        
        if(!is_null($number)){
            if(is_numeric($number)){
                if(strlen($number) == 11){
                    $number = substr_replace($number, '+63', 0, 1);
                } else {
                    $number = substr_replace($number, '+63', 0, 3);
                }
            }
        }
        
        $contact_exist = User::where('contact_number', $number)->first();

        if(!is_null($validate_email)) {
            return response()->json(array('success'=> false, 'error'=>'Email already exist!', 'messages'=>'Please provide another email!'));
        }
        else if(!is_null($contact_exist)) {
            return response()->json(array('success'=> false, 'error'=>'Contact already exist!', 'messages'=>'Please provide another contact!'));
        }
        else {
            if($validator->fails()) {
                return response()->json(array('success'=> false, 'error'=>'Validation error!', 'messages'=>'Please provide valid inputs!'));
            } else {
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
                    
                    $person = new Person;
                    $person->first_name=convertData($request['edit_first_name']);
                    $person->last_name=convertData($request['edit_last_name']);
                    $person->middle_name=convertData($request['edit_middle_name']);
                    $person->affiliation=convertData($request['edit_affiliation']);
                    $person->gender=convertData($request['edit_sex']);
                    $person->date_of_birth=convertData($request['edit_dob']);
                    $person->address=convertData($request['edit_address']);
                    $person->address_id = $address->id;
                    $person->civil_status=convertData($request['edit_civil_status']);
                    $person->telephone_number=convertData($request['edit_telephone']);
                    $person->religion=convertData($request['edit_religion']);
                    $person->image = 'ecabs/profiles/default-avatar.png';
                    
                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;
                    $person->person_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $person->id, 16, '0', STR_PAD_LEFT);
                
                    $person->save();
                    
                    //update credentials in pre-registration
                    $pre_reg_update = PreRegistration::where('id', '=', $id)->first();
                    $pre_reg_update->status = 0;
                    $pre_reg_update->update();
                    
                    //add user
                    $user = new User;
                    $user->email = 'sample.'.$person->id.'@gmail.com';
                    $user->password = bcrypt('secret123');
                    $user->contact_number = $number;
                    $user->account_status = '1';
                    $user->person_id = $person->id;
                    $user->device_identifier = identifierCredentials('', 'no_device', 'create_identifier');
                    $user->user_type = $pre_reg_update->pre_reg_type;
                    $user->save();
                    
                    //add person in GUEST department position
                    $person_department_position = new PersonDepartmentPosition;
                    $person_department_position->person_id = $person->id;
                    $person_department_position->department_position_id = 2;
                    $person_department_position->status = '1';
                    $person_department_position->save();
                    
                    
                    DB::commit();
                
                    return response()->json(array('success'=> true, 'messages'=> 'Success', 'messages'=>'Acount verified.'));
        
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json($e, $this->queryErrorStatus);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    //find all pre-registration
    public function findall(Request $request)
    {
    	$columns = array( 
            0 =>'first_name',
            1 =>'last_name',
        );

        $query = PreRegistration::query();

        //datatables total datas
        $totalData = $query->count();   
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $results = $query
                    ->offset($start)
                 	->limit($limit)
                 	->orderBy($order,$dir)
                    ->get();
        }
        else {
           $search = $request->input('search.value'); 

           $results= $query->where('last_name', 'LIKE', "%{$search}%")
                ->orWhere('first_name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query
                ->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $buttons = "";
                if($result->status == 1){ 
                    $status = "<label class='label label-primary'>Unverified</label>";
                }elseif($result->status == 0){
                    $status = "<label class='label label-danger'>Verified</label>";
                } 

                $buttons = '<button disabled class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="fa fa-magnifiying"></i> Verify Guest</button> ';
                if($result->status == 1){
                    $buttons = '<button data-toggle="tooltip" title="Click here to Verify Guest Account" onclick="edit('.$result->id.')" class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="fa fa-magnifiying"></i> Verify Guest</button> ';
                }


                $nestedData['last_name'] = $result->last_name;
                $nestedData['first_name'] =  $result->first_name;
                $nestedData['status'] = $status;
                $nestedData['actions'] = $buttons;
                $data[] = $nestedData;
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
}
