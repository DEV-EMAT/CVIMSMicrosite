<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\QualifiedPatient;
use App\Covid19Vaccine\VaccinationMonitoring;
use App\User;
use Gate;
use Response;
use DB;
use Auth;
use Carbon\Carbon;

use App\Exports\PreRegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;

class VaccinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.vaccination.vaccination',['title' => "Vaccination Management"]);
    }

    public function counseling()
    {
        return view('covid19_vaccine.vaccination.vaccine_counseling',['title' => "Counseling and Final Consent"]);
    }

    public function registrationValidation()
    {
        return view('covid19_vaccine.vaccination.registration_validation',['title' => "Registration / Validation"]);
    }

    public function assessment()
    {
        return view('covid19_vaccine.vaccination.assessment',['title' => "Vaccination Assessment"]);
    }
    
    public function secondDoseVerification(){
        return view('covid19_vaccine.vaccination.second_dose_verification',['title' => "Second Dose Verification"]);
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
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = PreRegistration::join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
        ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
        ->join('employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
        ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
        ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
        ->join('employment_statuses as employement_statuses', 'employement_statuses.id', '=', 'employers.employment_status_id')
        ->leftJoin('surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id')
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
        
        // $preRegister = PreRegistration::find($id);
        return response::json($query);
    }
    
    
    public function showAssessmentDetails($id)
    {
        $vaccinatedFirstDose = $vaccinatedSecondDose = "";
        
        $query = PreRegistration::join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
        ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
        ->join('employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
        ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
        ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
        ->join('employment_statuses as employement_statuses', 'employement_statuses.id', '=', 'employers.employment_status_id')
        ->join('qualified_patients as qualified_patients', 'qualified_patients.registration_id', '=', 'pre_registrations.id')
        ->leftJoin('surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id')
        ->select(
            'pre_registrations.id',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name',
            'pre_registrations.suffix',
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
            'surveys.question_10',
            'qualified_patients.qrcode',
            'qualified_patients.id AS qualified_patient_id'
        )->where('pre_registrations.id', '=', $id)->where('qualified_patients.qualification_status', '=', 'APPROVED')->get();
        
        $queryVaccinated = VaccinationMonitoring::join(connectionName('covid19vaccine') . '.vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
            ->join(connectionName('covid19vaccine') . '.vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
            ->select(
                'vaccine_categories.vaccine_name as vaccine_name',
                'vaccination_monitorings.vaccination_date',
                'vaccination_monitorings.batch_number',
                'vaccination_monitorings.lot_number',
                'vaccinators.last_name',
                'vaccinators.first_name',
                'vaccinators.middle_name',
                'vaccinators.suffix'
        )->where('vaccination_monitorings.qualified_patient_id', '=', $query[0]->qualified_patient_id)->where('vaccination_monitorings.status', '=', '1');
        if($queryVaccinated){
            $vaccinatedFirstDose = with(clone $queryVaccinated)->where('dosage', '=', '1')->first();
            if($vaccinatedFirstDose){
                $vaccinatedSecondDose = with(clone $queryVaccinated)->where('dosage', '=', '2')->first();
            }
        }

        return response()->json(array('assessment' => $query, 'vaccinatedFirstDose' => $vaccinatedFirstDose, 'vaccinatedSecondDose' => $vaccinatedSecondDose));
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

    public function findPatientById($id){
        $query = PreRegistration::join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
        ->select(
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name',
            'pre_registrations.person_code',
            'pre_registrations.date_of_birth',
            'pre_registrations.address'
        )->where('pre_registrations.id', '=', $id);

        // $preRegister = PreRegistration::find($id);
        return response::json($query);
    }
    
    //counseling find all
    public function counselingFindAll(Request $request)
    {
        $columns = array( 
            0=> 'status',
            1=> 'actions',
        );

        $totalData = PreRegistration::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = PreRegistration::query();
        if(empty($request->input('search.value')))
        {            
            $preRegistration = PreRegistration::offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $preRegistration = $query->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = $query->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($preRegistration))
        {
            foreach ($preRegistration as $preRegistrations)
            {   
                if($preRegistrations['status'] == '1'){
                    if(Gate::allows('permission', 'viewCounselingAndFinalConsentEvaluation')){
                        $buttons = '<a href="#" title="Edit" onclick="edit('. $preRegistrations['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EVALUATE PATIENT</a></button> ';
                    }
                    $status = "<label class='label label-primary'>Unevaluated</label>";
                }
                else{
                if(Gate::allows('permission', 'viewCounselingAndFinalConsentEvaluation')){
                        $buttons = '<a href="#"  onclick="activate('. $preRegistrations['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Evaluated</label>";
                }
                $fullname = $preRegistrations->last_name . " ". $preRegistrations->affiliation . ", ". $preRegistrations->first_name . " ". $preRegistrations->middle_name;
                $nestedData['fullname'] = $fullname;
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

    
    public function registrationAndValidationFindAll(Request $request)
    {
        $columns = array( 
            0=> 'last_name',
            1=> 'status',
        );

        $totalData = PreRegistration::where('status', '!=', '2')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = PreRegistration::query();
        if(empty($request->input('search.value')))
        {            
            $preRegistration = PreRegistration::where('status', '!=', '2')->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $preRegistration = with(clone $query)->whereRaw("concat(first_name, ' ', last_name) like '%{$search}%' ")->where('status', '!=', '2')
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = with(clone $query)->whereRaw("concat(first_name, ' ', last_name) like '%{$search}%' ")->where('status', '!=', '2')
                             ->count();
        }
        $buttons = "";
        $data = array();
        if(!empty($preRegistration))
        {
            foreach ($preRegistration as $preRegistrations)
            {  
                if($preRegistrations['status'] == '1'){
                    if(Gate::allows('permission', 'viewRegistrationAndValidation')){
                        $buttons = '<a href="#" data-toggle="tooltip" title="Click to verify patient." onclick="verifyPatient('. $preRegistrations['id'] .')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="fa fa-search" aria-hidden="true"></i> VERIFY PATIENT</a></button> ';
                    }
                    $status = "<label class='label label-danger'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> UNVERIFY</label>";
                }
                else{
                    if(Gate::allows('permission', 'viewRegistrationAndValidation')){
                        if(Gate::allows('permission', 'restoreRegistrationAndValidation')){
                            $btnRestore = '<a href="#" data-toggle="tooltip" title="Click to restore patient data."  onclick="registrationRestore('. $preRegistrations['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="fa fa-refresh" aria-hidden="true"></i> RESTORE</a> | ';
                        }else{
                            $btnRestore="";
                        }
                        $btnView = '<a href="#" data-toggle="tooltip" title="Click to view patient details."  onclick="viewPatient('. $preRegistrations['id'] .')"  class="btn btn-xs btn-success btn-fill btn-rotate remove"><i class="fa fa-eye" aria-hidden="true"></i> VIEW DETAILS</a>';
                        $buttons = $btnRestore. " " .$btnView;
                    }
                    $status = "<label class='label label-success'> <i class='fa fa-check-circle' aria-hidden='true'></i> EVALUATED</label>";
                }

                if(Gate::allows('permission', 'updateRegistrationAndValidation')){
                    $buttons .= ' <a data-toggle="tooltip" title="Click to updated patient profile." onclick="updatePatientPatient('. $preRegistrations['id'] .')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="fa fa-edit" aria-hidden="true"></i> EDIT</a></button> ';
                }
                
                $middleName = "";
                if($preRegistrations->middle_name != "NA"){$middleName = $preRegistrations->middle_name;}
                $fullname = $preRegistrations->last_name . " ". $preRegistrations->affiliation . ", ". $preRegistrations->first_name . " ". $middleName;
                $nestedData['fullname'] = $fullname;
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
    
    public function assessmentFindAll(Request $request)
    {
        $columns = array( 
            0=> 'last_name',
            1=> 'status',
        );

        $totalData = QualifiedPatient::join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
        ->select(
            'qualified_patients.id',
            'qualified_patients.registration_id',
            'qualified_patients.qualification_status',
            'qualified_patients.status',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name'
        )->where('qualified_patients.status', '=', '1')->where('qualified_patients.status', '!=', '2')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = QualifiedPatient::join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
        ->select(
            'qualified_patients.id',
            'qualified_patients.registration_id',
            'qualified_patients.qualification_status',
            'qualified_patients.status',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name'
        );
     
        if(empty($request->input('search.value')))
        {            
             $qualifiedPatient = with(clone $query)->where('qualified_patients.status', '1')
                        ->orWhere('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.status', '!=', '2')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
                $search = $request->input('search.value');
               
                $qualifiedPatient = with(clone $query)
                                    ->whereRaw("concat(pre_registrations.first_name, ' ', pre_registrations.last_name) like '%{$search}%' ")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.status', '!=', '2')
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
        
                $totalFiltered = with(clone $query)
                                ->whereRaw("concat(pre_registrations.first_name, ' ', pre_registrations.last_name) like '%{$search}%' ")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.status', '!=', '2')
                                ->count();
        }
        
        $buttons = "";
        $data = array();
        if(!empty($qualifiedPatient))
        {
            foreach ($qualifiedPatient as $qualifiedPatients)
            {   
                $btnPrint = '';
                if($qualifiedPatients['status'] == '1'){
                    if(Gate::allows('permission', 'viewAssessment')){
                        if(Gate::allows('permission', 'printAssessment')){
                            
                            $btnPrint .= '<a href="#" data-toggle="tooltip" title="Click to print assessment." onclick="print('. $qualifiedPatients['registration_id'] .','. $qualifiedPatients['registration_id'] .',' . '\'assessment\'' . ')" class="btn btn-xs btn-warning btn-fill btn-rotate view"><i class="fa fa-print" aria-hidden="true"></i> Print Assessment</a></button> | ';
                        }
                        if(Gate::allows('permission', 'printCertificate')){
                            $btnPrint .= '<a href="#" data-toggle="tooltip" title="Click to print vaccine certificate." onclick="print('. $qualifiedPatients['registration_id'] .','. $qualifiedPatients['registration_id'] .',' . '\'certificate\'' . ')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="fa fa-print" aria-hidden="true"></i> Print Certificate</a></button> | ';
                        }
                        if(Gate::allows('permission', 'printConsent')){
                            $btnPrint .= '<a href="#" data-toggle="tooltip" title="Click to print consent form." onclick="printConsent('. $qualifiedPatients['registration_id'] .')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="fa fa-print" aria-hidden="true"></i> Print Consent Form</a></button> | ';
                        }
                        $btnView = '<a href="#" data-toggle="tooltip" title="Click to view patient details."  onclick="viewPatient('. $qualifiedPatients['registration_id'] .')"  class="btn btn-xs btn-success btn-fill btn-rotate remove"><i class="fa fa-eye" aria-hidden="true"></i> View Details</a>';
                    }
                    $buttons = $btnPrint. " " .$btnView;
                    $status = "<label class='label label-success'><i class='fa fa-check-circle' aria-hidden='true'></i> Verified</label>";
                }
                $middleName = "";
                if($qualifiedPatients->middle_name != "NA"){$middleName = $qualifiedPatients->middle_name;}
                $fullname = $qualifiedPatients->last_name . " ". $qualifiedPatients->affiliation . ", ". $qualifiedPatients->first_name . " ". $middleName;
                $nestedData['fullname'] = $fullname;
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

    public function registrationApproval($id){
        DB::connection('covid19vaccine')->beginTransaction();
        try {
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
            
            $qualifiedPatient = new QualifiedPatient;
            $current_date = Carbon::today();
            $year = $current_date->year;
            $day = $current_date->day;
            $month = $current_date->month;
            $qualifiedPatient->registration_id = $id;
            $qualifiedPatient->qrcode = 'V' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $id, 16, '0', STR_PAD_LEFT);
            $qualifiedPatient->qualification_status = "APPROVED";
            $qualifiedPatient->verified_by = $verifiedBy;
            $qualifiedPatient->status = 1;
            $changes = $qualifiedPatient->getDirty();
            $qualifiedPatient->save();

            /* preRegistration */
            $preRegistration = PreRegistration::findOrFail($id);
            $preRegistration->status = '0';
            $preRegistration->save();

            DB::connection('covid19vaccine')->commit();
            /* logs */
            action_log('Registration Approval', 'CREATE', array_merge(['id' => $qualifiedPatient->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Evaluated!'));
        } catch (\PDOException $e) {
            DB::connection('covid19vaccine')->rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
    
    public function secondRegistrationApproval($id){
    
        DB::connection('covid19vaccine')->beginTransaction();
        try {
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
            
            $qualifiedPatient = QualifiedPatient::where('registration_id', '=', $id)->first();
            $vaccinationMonitoringPatient = VaccinationMonitoring::where('qualified_patient_id', '=', $qualifiedPatient->id)->first();
            $vaccinationMonitoringPatient->verified_by = $verifiedBy;
            $vaccinationMonitoringPatient->assessment_status = "1";
            $vaccinationMonitoringPatient->save();
            
            $changes = $qualifiedPatient->getDirty();

            DB::connection('covid19vaccine')->commit();
            /* logs */
            action_log('Second Dose Verification', 'CREATE', array_merge(['id' => $qualifiedPatient->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Evaluated!'));
        } catch (\PDOException $e) {
            DB::connection('covid19vaccine')->rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function registrationRestore($id){
        try {
            DB::connection('covid19vaccine')->beginTransaction();
            /* preRegistration */
            $preRegistration = PreRegistration::findOrFail($id);
            $preRegistration->status = '1';
            $changes = $preRegistration->getDirty();
            $preRegistration->save();

            /* preRegistration */
            $qualifiedPatient = QualifiedPatient::where('registration_id', '=', $id)->where('status', '=' , '1')->first();
            $qualifiedPatient->qualification_status = 'DECLINED';
            $qualifiedPatient->status = '0';
            $qualifiedPatient->save();
            DB::connection('covid19vaccine')->commit();
            /* logs */
            action_log('Registration Restore', 'CREATE', array_merge(['id' => $qualifiedPatient->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Evaluated!'));
        } catch (\PDOException $e) {
            DB::connection('covid19vaccine')->rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function printAssessment($id){
        
        $query = PreRegistration::join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
        ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
        ->join('employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
        ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
        ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
        ->join('employment_statuses as employement_statuses', 'employement_statuses.id', '=', 'employers.employment_status_id')
        ->leftJoin('surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id')
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
        
        return view('covid19_vaccine.vaccination.print_details',['data' => $query]);
    }
    
    public function changeAssessmentStatus($id){
        $qualifiedPatient = QualifiedPatient::where('registration_id', '=', $id)->where('status', '=', '1')->first();
        $qualifiedPatient->assessment_status = "1";
        $qualifiedPatient->save();
        // return response()->json(array('success' => true, 'messages' => 'Successfully Save!'));
    }

    public function exportSurveyList() {
        $now = Carbon::now();

        $filename = "CABVax_GENERATED_$now.xlsx";

        return Excel::download(new PreRegistrationsExport, $filename);
    }
}
