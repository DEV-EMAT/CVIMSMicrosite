<?php

namespace App\Http\Controllers\Covid19VaccineOnline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Covid19Vaccine\Employer;
use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\Survey;

use DB;
use Gate;
use Response;
use Auth;


class TransferPreRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.online_pre_registration.index',['title' => "Transfer Online Pre Registration"]);
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
        $query = DB::table(connectionName('covid19vaccineonline') .'.pre_registrations')->join(connectionName('covid19vaccineonline') .'.barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
        ->join(connectionName('covid19vaccineonline') .'.categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
        ->join(connectionName('covid19vaccineonline') .'.employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
        ->join(connectionName('covid19vaccineonline') .'.professions as professions', 'professions.id', '=', 'employers.profession_id')
        ->join(connectionName('covid19vaccineonline') .'.id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
        ->join(connectionName('covid19vaccineonline') .'.employment_statuses as employement_statuses', 'employement_statuses.id', '=', 'employers.employment_status_id')
        ->leftJoin(connectionName('covid19vaccineonline') .'.surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id')
        ->select(
            'pre_registrations.id',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name',
            'pre_registrations.date_of_birth',
            'pre_registrations.image',
            'pre_registrations.contact_number',
            'pre_registrations.philhealth_number',
            'pre_registrations.civil_status',
            'pre_registrations.sex',
            'pre_registrations.home_address',
            'categories.category_name',
            'pre_registrations.category_id_number',
            'barangays.barangay',
            'surveys.question_1',
            'surveys.question_2',
            'surveys.question_3',
            'surveys.question_4',
            'surveys.question_5',
            'surveys.question_6',
            'surveys.question_7',
            'surveys.question_8',
            'surveys.question_9',
            'surveys.question_10'
        )->where('pre_registrations.id', '=', $id)->get();
        
        return response::json($query);
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
        
        $user = Auth::user()->person;
        
        $existPreRegistration = false;
        $onlinePreRegistration = DB::table(connectionName('covid19vaccineonline') .'.pre_registrations')
                                ->join(connectionName('covid19vaccineonline').'.employers', 'employers.id', '=', 'pre_registrations.employment_id')
                                ->join(connectionName('covid19vaccineonline').'.surveys', 'surveys.registration_id', '=', 'pre_registrations.id')
                                ->select(
                                    'pre_registrations.*',
                                    'employers.employment_status_id AS employers_employment_status_id',
                                    'employers.profession_id AS employers_profession_id',
                                    'employers.specific_profession AS employers_specific_profession',
                                    'employers.employer_name AS employers_employer_name',
                                    'employers.employer_provice AS employers_employer_provice',
                                    'employers.employer_city AS employers_employer_city',
                                    'employers.employer_barangay_id AS employers_employer_barangay_id',
                                    'employers.employer_barangay_name AS employers_employer_barangay_name',
                                    'employers.employer_contact AS employers_employer_contact',
                                    'employers.status AS employers_status',
                                    'surveys.question_1 AS surveys_question_1',
                                    'surveys.question_2 AS surveys_question_2',
                                    'surveys.question_3 AS surveys_question_3',
                                    'surveys.question_4 AS surveys_question_4',
                                    'surveys.question_5 AS surveys_question_5',
                                    'surveys.question_6 AS surveys_question_6',
                                    'surveys.question_7 AS surveys_question_7',
                                    'surveys.question_8 AS surveys_question_8',
                                    'surveys.question_9 AS surveys_question_9',
                                    'surveys.question_10 AS surveys_question_10',
                                    'surveys.status AS surveys_status'
                                )
                                ->where('pre_registrations.id', '=', $id)->first();
        
        $checkPreRegistration = PreRegistration::where('last_name', '=', $onlinePreRegistration->last_name)
            ->where('first_name', '=', $onlinePreRegistration->first_name)
            ->where('date_of_birth', '=', $onlinePreRegistration->date_of_birth)
            ->first();
        if($checkPreRegistration){ $existPreRegistration = true; }
        
        if($existPreRegistration == false){
            
            $employer = new Employer;
            $employer->employment_status_id = $onlinePreRegistration->employers_employment_status_id;
            $employer->profession_id = $onlinePreRegistration->employers_profession_id;
            $employer->specific_profession = $onlinePreRegistration->employers_specific_profession;
            $employer->employer_name = $onlinePreRegistration->employers_employer_name;
            $employer->employer_provice = $onlinePreRegistration->employers_employer_provice;
            $employer->employer_city = $onlinePreRegistration->employers_employer_city;
            $employer->employer_barangay_id = $onlinePreRegistration->employers_employer_barangay_id;
            $employer->employer_barangay_name = $onlinePreRegistration->employers_employer_barangay_name;
            $employer->employer_contact = $onlinePreRegistration->employers_employer_contact;
            $employer->status = $onlinePreRegistration->employers_status;
            $employer->save();
            
            $preRegistration = new PreRegistration;
            $preRegistration->last_name = $onlinePreRegistration->last_name;
            $preRegistration->first_name = $onlinePreRegistration->first_name;
            $preRegistration->middle_name = $onlinePreRegistration->middle_name;
            $preRegistration->suffix = $onlinePreRegistration->suffix;
            $preRegistration->date_of_birth = $onlinePreRegistration->date_of_birth;
            $preRegistration->sex = $onlinePreRegistration->sex;
            $preRegistration->contact_number = $onlinePreRegistration->contact_number;
            $preRegistration->civil_status = $onlinePreRegistration->civil_status;
            $preRegistration->employment_id = $employer->id;
            $preRegistration->province = $onlinePreRegistration->province;
            $preRegistration->city = $onlinePreRegistration->city;
            $preRegistration->barangay = $onlinePreRegistration->barangay;
            $preRegistration->barangay_id = $onlinePreRegistration->barangay_id;
            $preRegistration->category_id = $onlinePreRegistration->category_id;
            $preRegistration->category_id_number = $onlinePreRegistration->category_id_number;
            $preRegistration->philhealth_number = $onlinePreRegistration->philhealth_number;
            $preRegistration->home_address = $onlinePreRegistration->home_address;
            $preRegistration->image = "covid19_vaccine_preregistration/default-avatar.png";
            $preRegistration->transfered_by = 'ONLINE';
            $preRegistration->uploaded_by = convertData($user->last_name.', '. $user->first_name.' '. $user->middle_name);
            $preRegistration->category_for_id = $onlinePreRegistration->category_for_id;
            $preRegistration->status = 1;
            $preRegistration->save();
            
            $survey = new Survey;
            $survey->registration_id = $preRegistration->id;
            $survey->question_1 = $onlinePreRegistration->surveys_question_1;
            $survey->question_2 = $onlinePreRegistration->surveys_question_2;
            $survey->question_3 = $onlinePreRegistration->surveys_question_3;
            $survey->question_4 = $onlinePreRegistration->surveys_question_4;
            $survey->question_5 = $onlinePreRegistration->surveys_question_5;
            $survey->question_6 = $onlinePreRegistration->surveys_question_6;
            $survey->question_7 = $onlinePreRegistration->surveys_question_7;
            $survey->question_8 = $onlinePreRegistration->surveys_question_8;
            $survey->question_9 = $onlinePreRegistration->surveys_question_9;
            $survey->question_10 = $onlinePreRegistration->surveys_question_10;
            $survey->status = $onlinePreRegistration->surveys_status;
            $survey->save();
            
            $survey = new Survey;
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        }else{
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Duplicate Data!'));
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
    
    public function findAll(Request $request)
    {
        $columns = array( 
            0=> 'last_name',
            1=> 'status',
            2=> 'created_at',
        );
    
        $query = DB::table(connectionName('covid19vaccineonline') .'.pre_registrations')->select("*")->where('status', '=', '1');
                
        $totalData = with(clone $query)->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $preRegistration = with(clone $query)->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();;
        }
        else {
            $search = $request->input('search.value'); 
            $preRegistration = with(clone $query)->where('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = with(clone $query)->where('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
                             ->count();
        }
        $buttons = "";
        $data = array();
        if(!empty($preRegistration))
        {
            foreach ($preRegistration as $preRegistrations)
            {  
                $status = '';
                $checkPreRegistration = PreRegistration::where('last_name', '=', $preRegistrations->last_name)
                    ->where('first_name', '=', $preRegistrations->first_name)
                    ->where('date_of_birth', '=', $preRegistrations->date_of_birth)
                    ->first();
                
                if($checkPreRegistration){ $preRegistrations->status = '0';}
                
                if($preRegistrations->status == '1' && $request['status'] != 1){
                    if(Gate::allows('permission', 'viewTransferOnlinePreRegistration')){
                        $buttons = '<a href="#" data-toggle="tooltip" title="Click to transfer patient." onclick="transferPatient('. $preRegistrations->id .')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="ti ti-reload" aria-hidden="true"></i> TRANSFER PATIENT</a></button> ';
                    }
                    $status = "<label class='label label-danger'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> NOT YET TRANSFERRED</label>";
                    
                    $middleName = "";
                    if($preRegistrations->middle_name != "NA"){$middleName = $preRegistrations->middle_name;}
                    $fullname = $preRegistrations->last_name . " ". $preRegistrations->suffix . ", ". $preRegistrations->first_name . " ". $middleName;
                    $nestedData['fullname'] = $fullname;
                    $nestedData['status'] = $status;
                    $nestedData['created_at'] = date("m-d-Y", strtotime($preRegistrations->created_at));
                    $nestedData['actions'] = $buttons;
                    $data[] = $nestedData;
                }
                
                if($preRegistrations->status == '0' && $request['status'] != 0){
                    $buttons = '<a href="#" disabled class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="ti ti-reload" aria-hidden="true"></i> TRANSFER PATIENT</a></button> ';
                    $status = "<label class='label label-success'> <i class='fa fa-check-circle' aria-hidden='true'></i> TRANSFERRED</label>";
                    
                    $middleName = "";
                    if($preRegistrations->middle_name != "NA"){$middleName = $preRegistrations->middle_name;}
                    $fullname = $preRegistrations->last_name . " ". $preRegistrations->suffix . ", ". $preRegistrations->first_name . " ". $middleName;
                    $nestedData['fullname'] = $fullname;
                    $nestedData['status'] = $status;
                    $nestedData['created_at'] = date("m-d-Y", strtotime($preRegistrations->created_at));
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
}
