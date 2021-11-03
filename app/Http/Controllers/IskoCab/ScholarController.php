<?php

namespace App\Http\Controllers\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
use Gate;
use Auth;
use Validator;
use Image;
use Carbon\Carbon;
use App\User;
use App\Ecabs\Person;
use App\Ecabs\Barangay;
use App\Ecabs\PersonDepartmentPosition;
use App\Ecabs\Address;
use App\IskoCab\Scholar;
use App\IskoCab\School;
use App\IskoCab\Course;
use App\IskoCab\PreRegScholar;
use App\IskoCab\ScholarAttainmentSummary;
use App\IskoCab\ScholarHasApplication;
use App\IskoCab\ScholarHasSchoolSummary;
use App\IskoCab\ScholarHasCourseSummary;

class ScholarController extends Controller
{
    //go to account management
    public function index()
    {
        return view('iskocab.scholar.manageaccounts', ['title'=>"Scholar Management"]);
    }
    
    //Go To Archive Page
    public function archive() {
        return view('iskocab.scholar.archive', ['title'=>'Scholar Management']);
    }

    public function create()
    {
        return view('iskocab.scholar.create', ['title'=>'Scholar Management']);
    }

    public function findAll(Request $request){
        $columns=array(0=> 'last_name');

        $status = 1;
        if($request['action'] == "archive"){
            $status = 0;
        }

        $totalData = DB::table(connectionName('iskocab') . '.scholars') 
        ->join(connectionName('mysql') . '.users', 'users.id', '=', 'scholars.user_id') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('scholars.*', 'people.last_name') 
        ->where('users.account_status', 1)
        ->where('scholars.status', $status)
        ->count();
        
        $totalFiltered=$totalData;

        $limit=$request->input('length');
        $start=$request->input('start');
        $order=$columns[$request->input('order.0.column')];
        $dir=$request->input('order.0.dir');

        $query = DB::table(connectionName('iskocab') . '.scholars') 
        ->join(connectionName('mysql') . '.users', 'users.id', '=', 'scholars.user_id') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('scholars.*', 'people.last_name') 
        ->where('users.account_status', 1)
        ->where('scholars.status', $status);

        if(empty($request->input('search.value'))) {

            $scholars= $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search=$request->input('search.value');

            $scholars= $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered= $query->count();
        }

        $data=array();

        if(!empty($scholars)) {
            foreach ($scholars as $scholar) {
                $user = User::findOrFail($scholar->user_id);
                $person = Person::findOrFail($user->person_id);

                
                if(Gate::allows('permission', 'viewScholar')){
                    $buttons =' <a onclick="view('. $scholar->user_id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW</a>';
                }
                if(Gate::allows('permission', 'updateScholar')){
                    $buttons .=' <a onclick="edit('. $scholar->user_id .')" class="btn btn-xs btn-success btn-fill btn-rotate  edit"><i class="ti-pencil-alt"></i> EDIT</a>';
                }
                if(Gate::allows('permission', 'deleteScholar')){
                    $buttons .=' <a  onclick="deactivate('. $scholar->user_id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate  remove"><i class="ti-trash"></i> DELETE</a>';
                }
                $status="<label class='label label-primary'>Active</label>";

                if($request['action'] == "archive"){
                    if(Gate::allows('permission', 'deleteScholar')){
                        $buttons = '<a onclick="view('. $scholar->user_id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW</a></button> 
                        <a  onclick="restore('. $scholar->user_id .')" class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                        $status = "<label class='label label-danger'>Deleted</label>";
                }
                $fullname = $person->last_name;
        
                if($person->affiliation){
                    $fullname .= " " . $person->affiliation;
                }
                $fullname .= ", " . $person->first_name . " ";
                
                if($person->middle_name){
                    $fullname .= $person->middle_name[0] . "."; 
                }

                $nestedData['fullname'] = $fullname;
                $nestedData['status'] = $status;
                $nestedData['actions'] = $buttons;
                $data[]=$nestedData;
            } 
        }


        $json_data=array("draw"=> intval($request->input('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=> $data);

        echo json_encode($json_data);
    }

    public function store(Request $request)
    {
        // $validator=Validator::make($request->all(), [ 
        //     'first_name'=> 'required',
        //     'last_name'=> 'required',
        //     'dob'=> 'required',
        //     'email' => 'required',
        //     'sex'=> 'required',
        //     'contact'=> 'required',
        //     'address'=> 'required',
        //     'school' => 'required',
        //     'course' => 'required',
        // ]);

        // $contact_exist = false;

        // $validate_email=User::where('email', $request['email'])->first();

        // $contact_exist=User::where('contact_number', $request['contact'])->first();

        // if($validate_email) {
        //     return response()->json(array('success'=> false, 'error'=>'Email already exist!', 'messages'=>'Please provide another email!'));
        // }
        // else if($contact_exist) {
        //     return response()->json(array('success'=> false, 'error'=>'Contact already exist!', 'messages'=>'Please provide another contact!'));
        // }
        // else {
        //     if($validator->fails()) {
        //         return response()->json(array('success'=> false, 'error'=>'Validation error!', 'messages'=>'Please provide valid inputs!'));
        //     }
        //     else {
        //         // try {
        //         //     DB::beginTransaction();
                    
        //             $address = new Address;
        //             $address->region = $request['txtRegion'];
        //             $address->region_id = $request['region'];
        //             $address->barangay = $request['txtBarangay'];
        //             $address->barangay_id = $request['barangay'];
        //             $address->city = $request['city'];
        //             $address->province = $request['province'];
        //             $address->status = '1';
        //             $address->save(); 

        //             //add person
        //             $person = new Person;
        //             $person->first_name=convertData($request['first_name']);
        //             $person->last_name=convertData($request['last_name']);
        //             $person->middle_name=convertData($request['middle_name']);
        //             $person->affiliation=convertData($request['affiliation']);
        //             $person->gender=convertData($request['sex']);
        //             $person->date_of_birth=convertData($request['dob']);
        //             $person->address=convertData($request['address']);
        //             $person->address_id = $address->id;
        //             $person->civil_status=convertData($request['civil_status']);
        //             $person->telephone_number=convertData($request['telephone']);
        //             $person->religion=convertData($request['religion']);

        //             $person->save();

        //             if($request->hasFile('avatar')) {
        //                 $filename= 'ecabs/profiles/' . date('Y') . '' . $person->id .'.'. $request['avatar']->getClientOriginalExtension();
        //                 $path=public_path('images/'. $filename);
        //                 Image::make($request['avatar']->getRealPath())->resize(200, 200)->save($path);
        //             }

        //             $person->image = !empty($filename)?$filename : 'ecabs/profiles/default-avatar.png';
        //             $person->save();

        //             //add user
        //             $user = new User;
        //             $user->email = $request['email'];
        //             $user->password = bcrypt('admin123');

        //             //format contact
        //             $contact = $request['contact'];
        //             if(strlen($contact) == 11){
        //                 $contact = substr_replace($contact, '+63', 0, 1);
        //             }
        //             $user->contact_number = $contact;
                    
        //             $user->account_status = '1';
        //             $user->person_id = $person->id;
        //             $user->save();

        //             //add person department position
        //             $person_department_position = new PersonDepartmentPosition;
        //             $person_department_position->person_id = $person->id;
        //             $person_department_position->department_position_id = 2;
        //             $person_department_position->status = '1';
        //             $person_department_position->save();

        //             // $identifier = identifierCredentials('', 'web_account', 'create_identifier');
        //             // if($get_department_id->id == 2){
        //                 $identifier = identifierCredentials('', 'mobile_account', 'create_identifier');
        //             // }
                    
        //             $user->device_identifier = $identifier;
        //             $user->save();
                    
        //             $current_date = Carbon::today();
        //             $year = $current_date->year;
        //             $day = $current_date->day;
        //             $month = $current_date->month;
        //             $person->person_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $person->id, 16, '0', STR_PAD_LEFT);
        //             $person->save();

        //             $scholar = new Scholar;
        //             $scholar->user_id = $user->id;
        //             $scholar->school_id = $request['school'];
        //             $scholar->course_id = $request['course'];
        //             $scholar->application_status = 0;
        //             $scholar->status = 1;
        //             $scholar->save();
                    
        //             // DB::commit();
        //             // activity logs
        //             action_log('SCHOLAR MNGT', 'CREATE ACCOUNT :'. $user->id);

        //             // event(new DataTableEvent(true));
        //             return response()->json(array('success'=> true, 'messages'=>'Record successfully Saved!'));
        //         // } catch (\PDOException $e) {
        //         //     DB::rollBack();
        //         //     return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        //         // }
        //     }
        // }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $person = Person::findOrFail($user->person_id);
        $barangay = Barangay::where('id', $person['barangay_id'])->first();
        $address = "";
        $scholar = Scholar::where('user_id', '=', $id)->first();
        $school = School::findOrFail($scholar->school_id);
        $course = Course::findOrFail($scholar->course_id);

        $person_department_position = PersonDepartmentPosition::where('person_id', $person['id'])
                    ->join('department_positions', 'person_department_positions.department_position_id', 'department_positions.id')
                    ->join('departments', 'department_positions.department_id', 'departments.id')
                    ->join('position_accesses', 'department_positions.position_access_id', 'position_accesses.id')->first();
        
        if($person->address_id){
            $address = Address::findOrFail($person->address_id);
        }

        $department = "";
        $position = "";
        $user_department = "";
        
        return response()->json(array($person, $user,  $barangay, $address, $school, $course));     
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {     
        // $validator = Validator::make($request->all(), [ 
        //     'edit_first_name'=> ' required',
        //     'edit_last_name'=> ' required',
        //     'edit_dob'=> ' required',
        //     'edit_sex'=> ' required',
        //     'edit_contact'=> ' required',
        //     'edit_address'=> ' required'
        //     ]);

        // $user = User::findOrFail($id);
        // $person = Person::findOrFail($user['person_id']);
        // $address = Address::findOrFail($person['address_id']);

        // $email_exist = false;
        // $contact_exist = false;
        // if($user['email'] != $request['edit_email']){
        //     $email_exist=User::where('email', $request['edit_email'])->first();
        // }
        
        // if($user['contact_number'] != $request['edit_contact']){
        //     $contact_exist=User::where('contact_number', $request['edit_contact'])->first();
        // }

        // if($email_exist) {
        //     return response()->json(array('success'=> false, 'messages'=> 'Email already exist!', 'messages'=>'Please provide another email!'));
        // }
        // else if($contact_exist) {
        //     return response()->json(array('success'=> false, 'messages'=> 'Contact already exist!', 'messages'=>'Please provide another contact!'));
        // }
        // else {
        //     if($validator->fails()) {
        //         return response()->json(array('success'=> false, 'error'=>'Validation error!', 'messages'=>'Please provide valid inputs!'));
        //     }
        //     else {
        //         try {
        //             DB::beginTransaction();

        //             $address->region = $request['txtRegion'];
        //             $address->region_id = $request['region'];
        //             $address->barangay = $request['txtBarangay'];
        //             $address->barangay_id = $request['barangay'];
        //             $address->city = $request['city'];
        //             $address->province = $request['province'];
        //             $address->save();

        //             $person->first_name=convertData($request['edit_first_name']);
        //             $person->last_name=convertData($request['edit_last_name']);
        //             $person->middle_name=convertData($request['edit_middle_name']);
        //             $person->affiliation=convertData($request['edit_affiliation']);
        //             $person->gender=convertData($request['edit_sex']);
        //             $person->date_of_birth=convertData($request['edit_dob']);
        //             $person->address=convertData($request['edit_address']);
        //             $person->civil_status=convertData($request['edit_civil_status']);
        //             $person->telephone_number=convertData($request['edit_telephone']);
        //             $person->religion=convertData($request['edit_religion']);

        //             $scholar = Scholar::where('user_id', '=', $id)->first();
        //             $scholar->school_id = $request['edit_school'];
        //             $scholar->course_id = $request['edit_course'];
        //             $scholar->save();

        //             if($request->hasFile('avatar')) {
        //                 $filename= 'ecabs/profiles/' . date('Y') . '' . $person->id .'.'. $request['avatar']->getClientOriginalExtension();
        //                 $path=public_path('images/'. $filename);
        //                 Image::make($request['avatar']->getRealPath())->resize(200, 200)->save($path);

        //                 $person->image = $filename;
        //             }

        //             $person->save();
        //             if($user['email'] != $request['edit_email'])
        //                 $user->email = $request['edit_email'];
        //             if($user['contact_number'] != $request['edit_contact']){
        //                 $contact = $request['edit_contact'];
        //                 if(strlen($contact) == 11){
        //                     $contact = substr_replace($contact, '+63', 0, 1);
        //                 }
        //                 $user->contact_number = $contact;
        //             }
                    
        //             $user->save();
        //             DB::commit();
                    
        //             // activity logs
        //             action_log('SCHOLAR MNGT', 'UPDATE ACCOUNT :'. $user->id);

        //             return response()->json(array('success'=> true, 'messages'=>'Record successfully Updated!'));
        //         } catch (\PDOException $e) {
        //             DB::rollBack();
        //             return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        //         }
        //     }
        // }
    }

    public function destroy($id)
    {
        //
    }

    //Toggle Status
    public function toggleStatus($id) {
        $scholar = Scholar::where('user_id', '=', $id)->first();
        $status = $scholar->status;

        try {
            DB::beginTransaction();

            if($status==1){
                $scholar->status=0;
                $action = 'DELETED';
            }else {
                $scholar->status=1;
                $action = 'RESTORE';
            }
            $changes = $scholar->getDirty();
            $scholar->save();
           
            DB::commit();

            /* logs */
            action_log('Scholar mngt', $action, array_merge(['id' => $scholar->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
       

        return response()->json(array('success'=> true, 'messages'=> 'Successfully Updated!'));
    }
    public function verify_scholar(){
        return view('iskocab.scholar.verification', ['title' => 'Scholar Verifications']);
    }

    public function getAllUnverifiedScholar(Request $request){
        $columns = array(
            0 =>'pre_scholar.id'
        );

        $totalData = PreRegScholar::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('iskocab'). '.pre_reg_scholars as pre_scholar')
                ->join(connectionName(). '.users as users', 'users.id', '=', 'pre_scholar.user_id')
                ->join(connectionName(). '.people as people', 'people.id', '=', 'users.person_id')
                ->select('pre_scholar.*', 'people.first_name', 'people.last_name', 'people.middle_name', 'people.affiliation', 'people.image');
                // ->where('pre_registration_status', '=', 'UNVERIFIED');

        if(empty($request->input('search.value')))
        {       
            $results = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        else {
            $search = $request->input('search.value');

            $results = $query->where('first_name', 'LIKE',"%{$search}%")->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->where('first_name', 'LIKE',"%{$search}%")->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $buttons = ($result->pre_registration_status == "UNVERIFIED")?'<a class="btn btn-sm btn-fill btn-primary" onclick="verify('.$result->id.')"><i class="fa fa-check"></i> VERIFY </a>':'<a class="btn btn-sm btn-fill btn-primary" disabled><i class="fa fa-check"></i> VERIFY </a>';

                $status = ($result->pre_registration_status != "UNVERIFIED")?'<label class="label label-primary">VERIFIED SCHOLAR</label>':'<label class="label label-danger">UNVERIFIED</label>';

                $nestedData['id'] = $result->id;
                $nestedData['fullname'] =  $result->last_name .' '. (!empty($result->affiliation)? $result->affiliation .', ': ', ') . ' '. $result->first_name .' '. $result->middle_name;
                $nestedData['image'] = asset('images/iskocab/pre_registration/'. $result->image);
                $nestedData['date_register'] = explode(' ', $result->created_at)[0];
                $nestedData['status'] = $status;
                $nestedData['action'] = $buttons;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }


    public function showPreScholar($id){

        $query = DB::table(connectionName('iskocab'). '.pre_reg_scholars as pre_scholar')
        ->join(connectionName(). '.users as users', 'users.id', '=', 'pre_scholar.user_id')
        ->join(connectionName(). '.people as people', 'people.id', '=', 'users.person_id')
        ->select('pre_scholar.*', 'people.first_name', 'people.last_name', 'people.middle_name', 'people.affiliation')
        ->where('pre_scholar.id', '=', $id)
        ->first();

        return response()->json($query);
    }

    /* use on verification */
    public function verifyStore(Request $request){
        $validator = Validator::make($request->all(), [
            'school_list' => 'required',
            'user_id' => 'required',
            'pre_reg_id' => 'required',
            'course_list' => 'required',
            'type_list' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(array('success'=> false, 'messages' => 'Please provide valid inputs!'));
        }else{
            try {
                DB::beginTransaction();

                $pre_reg = PreRegScholar::findOrFail($request['pre_reg_id']);
                $pre_reg->pre_registration_status = "VERIFIED";
                $changes = $pre_reg->getDirty();
                $pre_reg->save();

                $scholar = new Scholar;
                $scholar->user_id = $request['user_id']; 
                $scholar->image = $request['image_filename'];
                $scholar->status = 1;
                $changes = array_merge($changes, $scholar->getDirty());
                $scholar->save();
                
                /* course summary */
                $course = new ScholarHasCourseSummary;
                $course->scholar_id = $scholar->id;
                $course->course_id = $request['course_list'];
                $course->status = 1;
                $course->save();
                
                $sch_degree_taken = new ScholarAttainmentSummary;
                $sch_degree_taken->scholar_id = $scholar->id;
                $sch_degree_taken->attainment_id = $request['type_list'];
                $sch_degree_taken->status = 1;
                $changes = array_merge($changes, $sch_degree_taken->getDirty());
                $sch_degree_taken->save();

                $school_summary = new ScholarHasSchoolSummary;
                $school_summary->scholar_id = $scholar->id;
                $school_summary->school_id = $request['school_list'];
                $school_summary->status = '1';
                $changes = array_merge($changes, $school_summary->getDirty());
                $school_summary->save();

                DB::commit();
                
                rename('../public_html/images/iskocab/pre_registration/'. $request['image_filename'], '../public_html/images/iskocab/scholar_profile/'. $request['image_filename']);
                clearstatcache();
                
                // move_uploaded_file ( $request['image_filename'] , public_path('images/iskocab/profiles/') ); 

                /* logs */
                action_log('Scholar mngt', 'VERIFY', array_merge(['id' => $pre_reg->id], $changes));

                return response()->json(array('success'=> true, 'messages' => 'Record Successfully saved'));
            } catch (\PDOException $e) {
                DB::rollBack();
                // rename('../public_html/images/iskocab/scholar_profile/'. $request['image_filename'], '../public_html/images/iskocab/pre_registration/'. $request['image_filename']);
                // clearstatcache();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    
    public function getAllAchievements(Request $request){
        $columns=array(0=> 'subject.gwa',
            1=> 'program.title',
            2=> 'subject.gwa',
        );


        $limit=$request->input('length');
        $start=$request->input('start');
        $order=$columns[$request->input('order.0.column')];
        $dir=$request->input('order.0.dir');

        $query = ScholarHasApplication::join(connectionName('iskocab'). '.scholarship_program_modules as modules', 'modules.id', 'scholar_has_applications.progmodule_id')
            ->join(connectionName('comprehensive'). '.program_services as program', 'program.id', 'modules.program_id')
            ->join(connectionName('iskocab'). '.scholar_has_application_summaries as summaries', 'summaries.application_id', 'scholar_has_applications.id')
            ->join(connectionName('iskocab'). '.assistance_types as assistance', 'assistance.id', 'summaries.assistance_id')
            ->join(connectionName('iskocab'). '.scholar_has_subject_grades as subject', 'subject.id', 'summaries.grades_id')
            ->join(connectionName('comprehensive'). '.program_services_has_departments as prog_dept', 'prog_dept.program_services_id', 'modules.program_id')
            ->select('program.title',
                    'program.description',
                    'subject.grade_list',
                    'subject.gwa',
                    'assistance.title as assistance_type',
                    'program.created_at'
            )
            ->where('summaries.status', '=', '1')
            ->where('scholar_has_applications.scholar_id', '=', $request['scholar_id'])
            ->where('scholar_has_applications.application_status', '=', 'SUCCESS')
            ->where('scholar_has_applications.evaluation_status', '=', 'TRUE')
            ->where('scholar_has_applications.assessment_status', '=', 'TRUE')
            ->where('prog_dept.status', '=', '0');

            
        $totalData = $query->count();
        
        $totalFiltered=$totalData;

        if(empty($request->input('search.value'))) {

            $results=$query->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

        } else {
            $search=$request->input('search.value');

            $results = $query->where('program.title', 'LIKE', "%{$search}%")
                ->orWhere('assistance.title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query->where('program.title', 'LIKE', "%{$search}%")
                ->orWhere('assistance.title', 'LIKE', "%{$search}%")
                ->count();
        }

        $json_data=array(
            "draw"=> intval($request->input('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=> $results
        );

        echo json_encode($json_data);
    }

}
