<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Covid19Vaccine\Employer;
use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\Survey;
use App\Covid19Vaccine\Barangay;
use App\Covid19Vaccine\QualifiedPatient;
use App\User;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use PDOException;
use DB;
use Image;
use App\Rules\ReCaptchaRule;
use Carbon\Carbon;
use Response;
use Auth;
use Gate;

class Covid19VaccineRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.registration.index');
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
    
    public function formatDatePage()
    {
        return view('covid19_vaccine.registration.format_date', ['title' => 'Format Date']);
    }

    public function validateUser($data){
        // ->where('middle_name', '=', $data['middlename'])
        // ->where('suffix', '=', $data['suffix'])

        return PreRegistration::where('last_name', '=', $data['lastname'])
            ->where('first_name', '=', $data['firstname'])
            ->where('date_of_birth', '=', $data['dob'])
            ->first();
    }

    // public function validateAnswers($data){
    //     $found_unique = false;
    //     $data_length = count($data);

    //     foreach ($data as $key => $value) {
    //         if(($value  != 'YES' || $value  != 'NO')){
    //             if($data_length > 1){
    //                 if($key != ($data_length - 1)){
    //                     $found_unique = true;
    //                     break;
    //                 }
    //             }
    //         }
    //     }

    //     return $found_unique;
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $answer = [];
        if($request['question2'] == "YES"){
            $answer['question3'] = 'required';
        }if($request['question4'] == "YES"){
            $answer['question5'] = 'required';
        }

        $validate = Validator::make($request->all(), array_merge([
            'last_name' => 'required',
            'first_name' => 'required',
            'dob' => 'required',
            'sex' => 'required',
            'contact' => 'required',
            'barangay' => 'required',
            'profession' => 'required',
            'category_for_id' => 'required',
            'address' => 'required',
            'question9' => 'required',
            
        ], $answer));

        if($validate->fails()){
            return response()->json(array('success' => false, 'messages' => 'May be missing required fields or Invalid reCAPTCHA and data entry! Please check your input.', 'title'=> 'Oops! something went wrong.'));
        }else{
            $data = [
                'lastname' => convertData($request->last_name),
                'firstname' => convertData($request->first_name),
                'middlename' => convertData($request->middle_name),
                'dob' => convertData($request->dob),
                'suffix' => convertData($request->affiliation),
            ];

            if(empty($this->validateUser($data))){
                
                DB::connection('covid19vaccine')->beginTransaction();
                try{

                    /* barangay */
                    $barangay = Barangay::findOrFail($request->barangay);

                    /* employer */
                    $employer = new Employer;
                    $employer->employment_status_id = convertData($request->employment);
                    $employer->profession_id = convertData($request->profession);
                    $employer->specific_profession = convertData($request->specific_profession);
                    $employer->employer_name = convertData($request->employer_name);
                    $employer->employer_contact = convertData($request->employer_contact);
                    $employer->employer_barangay_name = convertData($request->employer_address);
                    $employer->status = '1';
                    $employer->save();

                    $register = new PreRegistration();
                    $register->last_name = convertData($request->last_name);
                    $register->first_name = convertData($request->first_name);
                    $register->middle_name = (convertData($request->middle_name) == 'N/A')? 'NA' : convertData($request->middle_name);
                    $register->suffix = convertData($request->affiliation);
                    $register->date_of_birth = convertData($request->dob);
                    $register->sex = convertData($request->sex);
                    $register->contact_number = convertData($request->contact);
                    $register->civil_status = convertData($request->civil_status);
                    $register->province = 'LAGUNA';
                    $register->city = 'CABUYAO';
                    $register->barangay = $barangay->barangay;
                    $register->barangay_id = convertData($request->barangay);
                    $register->home_address = convertData($request->address);
                    $register->employment_id = $employer->id;
                    $register->barangay_id = convertData($request->barangay);
                    $register->category_id = convertData($request->category);
                    $register->category_id_number = convertData($request->category_id_number);
                    $register->category_for_id = convertData($request->category_for_id);
                    $register->philhealth_number = convertData($request->philhealth);
                    $register->status = '1';
                    $register->save();
                    
                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;
                    $register->registration_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $register->id, 12, '0', STR_PAD_LEFT);
                    $register->save();
                  
                    $register->image = 'covid19_vaccine_preregistration/default-avatar.png';
                    $register->save();
                    
                    /* survey */
                    $survey = new Survey;
                    $survey->registration_id = $register->id;
                    $survey->question_1 = 'NO';
                    $survey->question_2 = ($request['question2'] == 'YES')? 'YES' : 'NO';
                    $survey->question_3 = ($request['question2'] == 'YES')? implode(', ', $request['question3']) : null;
                    $survey->question_4 = ($request['question4'] == 'YES')? 'YES' : 'NO';
                    $survey->question_5 = ($request['question4'] == 'YES')? implode(', ', $request['question5']) : null;
                    $survey->question_6 = 'NO';
                    $survey->question_7 = null;
                    $survey->question_8 = null;
                    $survey->question_9 = ($request['question9'] == 'YES')? 'YES' : 'NO';
                    $survey->question_10 = 'NO';
                    $survey->status = '1';
                    $survey->save();
    
                    $registeredPerson = PreRegistration::where('id', '=', $register->id)->where('status', '=', '1')->first();
                    
                    $fullname = $registeredPerson->last_name;

                    if($registeredPerson->affiliation){
                        $fullname .= " " . $registeredPerson->affiliation;
                    }
                    $fullname .= ", " . $registeredPerson->first_name . " ";

                    if($registeredPerson->middle_name){
                        $fullname .= $registeredPerson->middle_name[0] . ".";
                    }
                    
                    DB::connection('covid19vaccine')->commit();
    
                    return response()->json(array('success' => true, 'messages' => 'Thank you!', 'fullname' => $fullname, 'date_registered' => $registeredPerson->created_at->format('m-d-Y H:i:s'), 'registration_code' => $registeredPerson->registration_code));
    
                }catch(\PDOException $e){
                    DB::connection('covid19vaccine')->rollBack();
                    return response()->json(array('success' => false, 'messages' => 'Transaction Failed!','title' => 'Oops! something went wrong.'));
                }
            }else{
                return response()->json(array('success' => false, 'messages' => 'Please check your lastname, firstname, middlename and birthday!.','title' => 'Your name is already exist to our record!'));
            }

        }


    }
    
    
    public function preRegistered(){
        $total = PreRegistration::where('status', '=','1') ->count();
        return response()->json(['total' => $total],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = PreRegistration::join(connectionName('covid19vaccine').'.employers as employers', 'employers.id', 'pre_registrations.employment_id')
        ->join(connectionName('covid19vaccine').'.employment_statuses as employment_status', 'employment_status.id', 'employers.employment_status_id')
        ->join(connectionName('covid19vaccine').'.professions as profession', 'profession.id', 'employers.profession_id')
        ->join(connectionName('covid19vaccine').'.barangays as barangays', 'barangays.id', 'pre_registrations.barangay_id')
        ->join(connectionName('covid19vaccine').'.categories as category', 'category.id', 'pre_registrations.category_id')
        ->join(connectionName('covid19vaccine').'.id_categories as id_for_category', 'id_for_category.id', 'pre_registrations.category_for_id')
        ->select('id_for_category.*', 'id_for_category.id as id_for_category_id', 
            'category.*', 'category.id as category_id', 
            'barangays.*', 'barangays.id as barangays_id', 
            'profession.*', 'profession.id as profession_id', 
            'employment_status.*', 'employment_status.id as employment_status_id', 
            'pre_registrations.*', 'pre_registrations.id as registration_id', 
            'employers.*', 'employers.id as employer_id'
        )->where('pre_registrations.id', '=', $id)->get();
        
        $isVaccinated = PreRegistration::join(connectionName('covid19vaccine'). '.qualified_patients as qualified_patients', 'qualified_patients.registration_id', 'pre_registrations.id')
            ->join(connectionName('covid19vaccine'). '.vaccination_monitorings as vaccination_monitorings', 'vaccination_monitorings.qualified_patient_id', 'qualified_patients.id')
            ->where('vaccination_monitorings.dosage', '=', '1')
            ->where('pre_registrations.id', '=', $id)->get();
            
        return response::json(array('preRegistration' => $query, 'isVaccinated' => $isVaccinated));
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
    
    
    public function createPatientProfile(Request $request){
        $answer = [];
        if($request['question2'] == "YES"){
            $answer['question3'] = 'required';
        }if($request['question4'] == "YES"){
            $answer['question5'] = 'required';
        }

        $validate = Validator::make($request->all(), array_merge([
            'last_name' => 'required',
            'first_name' => 'required',
            'dob' => 'required',
            'sex' => 'required',
            'contact' => 'required',
            'barangay' => 'required',
            'profession' => 'required',
            'category_for_id' => 'required',
            'address' => 'required',
            
        ], $answer));

        if($validate->fails()){
            return response()->json(array('success' => false, 'messages' => 'May be missing required fields or Invalid reCAPTCHA and data entry! Please check your input.', 'title'=> 'Oops! something went wrong.'));
        }else{
            $data = [
                'lastname' => convertData($request->last_name),
                'firstname' => convertData($request->first_name),
                'middlename' => convertData($request->middle_name),
                'dob' => convertData($request->dob),
                'suffix' => convertData($request->affiliation),
            ];

            if(empty($this->validateUser($data))){
                
                DB::connection('covid19vaccine')->beginTransaction();
                try{

                    /* barangay */
                    $barangay = Barangay::findOrFail($request->barangay);

                    /* employer */
                    $employer = new Employer;
                    $employer->employment_status_id = convertData($request->employment);
                    $employer->profession_id = convertData($request->profession);
                    $employer->specific_profession = convertData($request->specific_profession);
                    $employer->employer_name = convertData($request->employer_name);
                    $employer->employer_contact = convertData($request->employer_contact);
                    $employer->employer_barangay_name = convertData($request->employer_address);
                    $employer->status = '1';
                    $changes = $employer->getDirty(); 
                    $employer->save();

                    $register = new PreRegistration();
                    $register->last_name = convertData($request->last_name);
                    $register->first_name = convertData($request->first_name);
                    $register->middle_name = (convertData($request->middle_name) == 'N/A')? 'NA' : convertData($request->middle_name);
                    $register->suffix = convertData($request->affiliation);
                    $register->date_of_birth = convertData($request->dob);
                    $register->sex = convertData($request->sex);
                    $register->contact_number = convertData($request->contact);
                    $register->civil_status = convertData($request->civil_status);
                    $register->province = 'LAGUNA';
                    $register->city = 'CABUYAO';
                    $register->barangay = $barangay->barangay;
                    $register->barangay_id = convertData($request->barangay);
                    $register->home_address = convertData($request->address);
                    $register->employment_id = $employer->id;
                    $register->barangay_id = convertData($request->barangay);
                    $register->category_id = convertData($request->category);
                    $register->category_id_number = convertData($request->category_id_number);
                    $register->category_for_id = convertData($request->category_for_id);
                    $register->philhealth_number = convertData($request->philhealth);
                    $register->status = '0';
                    $changes = array_merge($changes, $register->getDirty()); 
                    $register->save();
                    
                    $currentUser = User::join('people', 'people.id', '=', 'users.person_id')
                                ->select(
                                'people.last_name',
                                'people.first_name',
                                'people.middle_name',
                                'people.affiliation'
                                )
                    ->where('users.id', '=', Auth::user()->id)->first();
                    
                    $verifiedBy = $currentUser->last_name;
                
                    if($currentUser->affiliation){
                        $verifiedBy .= " " . $currentUser->affiliation;
                    }
                    
                    if($currentUser->first_name){
                        $verifiedBy .= ", " . $currentUser->first_name . " ";
                    }
                    
                    if($currentUser->middle_name){
                        $verifiedBy .= $currentUser->middle_name[0] . "."; 
                    }
                    
                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;
                    
                    $qualifiedPatient = new QualifiedPatient;
                    $qualifiedPatient->registration_id = $register->id;
                    $qualifiedPatient->qrcode = 'V' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $register->id, 16, '0', STR_PAD_LEFT);
                    $qualifiedPatient->qualification_status = "APPROVED";
                    $qualifiedPatient->verified_by = $verifiedBy;
                    $qualifiedPatient->status = 1;
                    $changes = array_merge($changes, $qualifiedPatient->getDirty());
                    $qualifiedPatient->save();
                    
                    $current_date = Carbon::today();
                    $year = $current_date->year;
                    $day = $current_date->day;
                    $month = $current_date->month;
                    $register->registration_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $register->id, 12, '0', STR_PAD_LEFT);
                    $changes = array_merge($changes, $register->getDirty()); 
                    $register->save();
                  
                    $register->image = 'covid19_vaccine_preregistration/default-avatar.png';
                    $register->save();
                    
                    /* survey */
                    $survey = new Survey;
                    $survey->registration_id = $register->id;
                    $survey->question_1 = 'NO';
                    $survey->question_2 = ($request['question2'] == 'YES')? 'YES' : 'NO';
                    $survey->question_3 = ($request['question2'] == 'YES')? implode(', ', $request['question3']) : null;
                    $survey->question_4 = ($request['question4'] == 'YES')? 'YES' : 'NO';
                    $survey->question_5 = ($request['question4'] == 'YES')? implode(', ', $request['question5']) : null;
                    $survey->question_6 = 'NO';
                    $survey->question_7 = null;
                    $survey->question_8 = null;
                    $survey->question_9 = ($request['question9'] == 'YES')? 'YES' : 'NO';
                    $survey->question_10 = 'NO';
                    $survey->status = '1';
                    $changes = array_merge($changes, $survey->getDirty()); 
                    $survey->save();
    
                    $registeredPerson = PreRegistration::where('id', '=', $register->id)->first();
                    
                    $fullname = $registeredPerson->last_name;

                    if($registeredPerson->affiliation){
                        $fullname .= " " . $registeredPerson->affiliation;
                    }
                    $fullname .= ", " . $registeredPerson->first_name . " ";

                    if($registeredPerson->middle_name){
                        $fullname .= $registeredPerson->middle_name[0] . ".";
                    }
                    
                    DB::connection('covid19vaccine')->commit();
                    
                    
                    /* logs */
                    action_log('Pre-registration mngt', 'CREATE', array_merge(['id' => $register->id], $changes));
    
                    return response()->json(array('success' => true, 'messages' => 'Thank you!', 'fullname' => $fullname, 'date_registered' => $registeredPerson->created_at->format('m-d-Y H:i:s'), 'registration_code' => $registeredPerson->registration_code));
    
                }catch(\PDOException $e){
                    DB::connection('covid19vaccine')->rollBack();
                    return response()->json(array('success' => false, 'messages' => 'Transaction Failed!','title' => 'Oops! something went wrong.'));
                }
            }else{
                return response()->json(array('success' => false, 'messages' => 'Please check your lastname, firstname, middlename and birthday!.','title' => 'Your name is already exist to our record!'));
            }
        }
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
         $validate = Validator::make($request->all(), [
            'last_name' => 'required',
            'first_name' => 'required',
            'dob' => 'required',
            'sex' => 'required',
            'contact' => 'required',
            'barangay' => 'required',
            'profession' => 'required',
            'category_for_id' => 'required',
            'address' => 'required',
        ]);

        if($validate->fails()){
            return response()->json(array('success' => false, 'messages' => 'May be missing required fields or Invalid reCAPTCHA and data entry! Please check your input.', 'title'=> 'Oops! something went wrong.'));
        }else{      

            DB::connection('covid19vaccine')->beginTransaction();
            try{

                /* barangay */
                $barangay = Barangay::findOrFail($request->barangay);
                $register = PreRegistration::findOrFail($id);

                $register->last_name = convertData($request->last_name);
                $register->first_name = convertData($request->first_name);
                $register->middle_name = convertData($request->middle_name);
                $register->suffix = convertData($request->affiliation);
                $register->date_of_birth = convertData($request->dob);
                $register->sex = convertData($request->sex);
                $register->contact_number = convertData($request->contact);
                $register->civil_status = convertData($request->civil_status);
                $register->province = 'LAGUNA';
                $register->city = 'CABUYAO';
                $register->barangay = $barangay->barangay;
                $register->barangay_id = convertData($request->barangay);
                $register->home_address = convertData($request->address);
                $register->barangay_id = convertData($request->barangay);
                $register->category_id = convertData($request->category);
                
                if($request->categoryIfVaccinated != ""){
                    $register->category_id = convertData($request->categoryIfVaccinated);
                }
                $register->category_id_number = convertData($request->category_id_number);
                $register->philhealth_number = convertData($request->philhealth);
                $register->category_for_id = convertData($request->category_for_id);
                $changes = $register->getDirty(); 
                $register->save();

                /* employer */
                $employer = Employer::findOrFail($register->employment_id);
                $employer->employment_status_id = convertData($request->employment);
                $employer->profession_id = convertData($request->profession);
                $employer->specific_profession = convertData($request->specific_profession);
                $employer->employer_name = convertData($request->employer_name);
                $employer->employer_contact = convertData($request->employer_contact);
                $employer->employer_barangay_name = convertData($request->employer_address);
                $changes = array_merge($changes, $employer->getDirty()); 
                $employer->save();
                
                $register->save();

                DB::connection('covid19vaccine')->commit();
                
                /* logs */
                action_log('Pre-registration mngt', 'UPDATE', array_merge(['id' => $register->id], $changes));

                return response()->json(array('success' => true, 'messages' => 'Record successfully updated'));

            }catch(\PDOException $e){
                DB::connection('covid19vaccine')->rollBack();
                return response()->json(array('success' => false, 'messages' => 'Transaction Failed!','title' => 'Oops! something went wrong.'));
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
    
    
    public function findRegisterUser(Request $request){

        $sex = '';
        if(convertData($request['sex']) == "MALE"){
            $sex = '01_MALE';
        }else{
            $sex = '02_FEMALE';
        }

        $result = PreRegistration::where('last_name', '=', convertData($request['last_name']))
                ->where('first_name', '=', convertData($request['first_name']))
                ->where('middle_name', '=', convertData($request['middle_name']))
                ->where('suffix', '=', convertData($request['suffix']))
                ->where('date_of_birth', '=', date("m/d/Y", strtotime(convertData($request['date_of_birth']))))
                ->where('sex', '=', $sex)
                ->select('id','last_name', 'first_name', 'middle_name', 'suffix', 'registration_code', 'created_at')
                ->first();

        if(!empty($result)){
            $fullname = $result->last_name;
            $fullname = (!empty($result->suffix) && $result->suffix != 'NA' && $result->suffix != 'N/A')? $fullname .' '. $result->suffix . ', ' : $fullname .', ';
            $fullname = $fullname . $result->first_name .' '. $result->middle_name;

            if(empty($result->registration_code)){
                $current_date = Carbon::today();
                $year = $current_date->year;
                $day = $current_date->day;
                $month = $current_date->month;

                $result->registration_code = 'P' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $result->id, 12, '0', STR_PAD_LEFT);
                $result->save();
            }
            
            return response::json(array('success' => true, 'message' => '', 'data' => array('fullname' => $fullname, 'date_registered' => $result->created_at->format('F d, Y  H:i A'), 'registration_code' => $result->registration_code)));
        }else{
            return response::json(array('success' => false, 'messages' => 'User not found on the system'));
        }
    }
    
    public function findAll(Request $request)
    {
        $columns = array( 
            0=> 'last_name',
            1=> 'status',
        );

        // $totalData = PreRegistration::where('status', '!=', '2')->count();
        DB::enableQueryLog();
        // $query = PreRegistration::query();
        $query = PreRegistration::where(DB::raw("(STR_TO_DATE(date_of_birth,'%m/%d/%y'))"));
        // PreRegistration::query()->where(DB::raw("(STR_TO_DATE(date_of_birth,'%m/%d/%y'))"))->get();
        
        // PreRegistration::query()->get();
        
        $totalData = with(clone $query)->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // $query = PreRegistration::where(DB::raw("(STR_TO_DATE(date_of_birth,'%m/%d/%y'))"));
        
        if(empty($request->input('search.value')))
        {            
            $preRegistration = with(clone $query)->where('status', '!=', '2')->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $preRegistration = with(clone $query)->where('last_name', 'LIKE',"%{$search}%")->where('status', '!=', '2')
                        ->orWhere('first_name', 'LIKE',"%{$search}%")->where('status', '!=', '2')
                        ->orWhere('middle_name', 'LIKE',"%{$search}%")->where('status', '!=', '2')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = with(clone $query)->where('last_name', 'LIKE',"%{$search}%")->where('status', '!=', '2')
                            ->orWhere('first_name', 'LIKE',"%{$search}%")->where('status', '!=', '2')
                            ->orWhere('middle_name', 'LIKE',"%{$search}%")->where('status', '!=', '2')
                             ->count();
        }
        $buttons = "";
        $data = array();
        if(!empty($preRegistration))
        {
            foreach ($preRegistration as $preRegistrations)
            {  
                $fullname = '';
                $middleName = "";
                if($preRegistrations->middle_name != "NA"){$middleName = $preRegistrations->middle_name;}
                $fullname = $preRegistrations->last_name . " ". $preRegistrations->affiliation . ", ". $preRegistrations->first_name . " ". $middleName;
                
                if(Gate::allows('permission', 'changeDateFormat')){
                    $buttons = '<a href="#" data-toggle="tooltip" title="Click to update patient." onclick="changeDate('. $preRegistrations['id'] .')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="ti ti-pencil-alt" aria-hidden="true"></i> CHANGE DATE OF BIRTH</a></button> ';   
                }
                if($preRegistrations['status'] == '1'){
                    $status = "<label class='label label-danger'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> UNVERIFY</label>";
                }
                else{
                    $status = "<label class='label label-success'> <i class='fa fa-check-circle' aria-hidden='true'></i> EVALUATED</label>";
                }
                
                $nestedData['fullname'] = $fullname;
                $nestedData['date_of_birth'] = $preRegistrations->date_of_birth;
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
    
    public function getInformation($id)
    {
        $preRegistration = PreRegistration::where('id','=', $id)->first();
        
        $fullname = '';
        $middleName = "";
        if($preRegistration->middle_name != "NA"){$middleName = $preRegistration->middle_name;}
        $fullname = $preRegistration->last_name . " ". $preRegistration->affiliation . ", ". $preRegistration->first_name . " ". $middleName;
        $date_of_birth = $preRegistration->date_of_birth;
        
        return response()->json(array('fullname' => $fullname, 'date_of_birth' => $date_of_birth));
    }
    
    public function updateDateOfBirth(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'date_of_birth'=> 'required'
        ]);
        
        DB::connection('covid19vaccine')->beginTransaction();
        try {
            $preRegistration = PreRegistration::where('id','=', $id)->first();
            $preRegistration->date_of_birth = $request['date_of_birth'];
            $changes = $preRegistration->getDirty();
            $preRegistration->save();
    
            DB::connection('covid19vaccine')->commit();
    
            /* logs */
            action_log('Pre Registration', 'UPDATE', array_merge(['id' => $preRegistration->id], $changes));
    
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
    
            DB::connection('covid19vaccine')->rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
