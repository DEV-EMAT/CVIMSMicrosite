<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Covid19Vaccine\QualifiedPatient;
use App\Covid19Vaccine\Vaccinator;
use App\Covid19Vaccine\VaccinationMonitoring;
use App\Covid19Vaccine\VaccinationMonitoringSurvey;
use App\Covid19Vaccine\UserHasFacility;
use App\Covid19Vaccine\ExportHasPatient;
use App\User;
use DB;
use Response;
use Gate;
use Validator;
use Auth;

use Carbon\Carbon;


use App\Exports\VIMSVASExport;
use Maatwebsite\Excel\Facades\Excel;

class VaccinationMonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.monitoring.index',['title' => "Vaccination Monitoring"]);
    }
    
    public function viewAllMonitoring()
    {
        return view('covid19_vaccine.monitoring.view_monitoring',['title' => "Vaccination Monitoring"]);
    }
    
    public function monitorVaccinatedFirstDose(){
        return view('covid19_vaccine.monitoring.second_dose_checking',['title' => "Vaccination Monitoring"]);
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
        $validator = Validator::make($request->all(), [
                'dosage'=> 'required',
                'vaccination_date'=> 'required',
                'vaccine_manufacturer'=> 'required',
                'batch_number' => 'required',
                'lot_number'=> 'required',
                'vaccinator'=> 'required',
                'consent'=> 'required',
        ]);
        $isMonitorCompleted = VaccinationMonitoring::where('qualified_patient_id', '=', $request["qualified_patient_id"])
                            ->where('dosage', '=', $request["dosage"])
                            ->where('status', '=', '1')
                            ->first();

        if($isMonitorCompleted){
            $dosage = "first";
            if($request["dosage"] == "2"){
                $dosage = "second";
            }
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=> 'This patient has already taken the ' . $dosage . ' dosage of the vaccine. Please check the vaccination summary for the details.'));
        }else{
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

                $encodedBy = $currentUser->last_name;

                if($currentUser->affiliation){
                    $encodedBy .= " " . $currentUser->affiliation;
                }

                if($currentUser->first_name){
                    $encodedBy .= ", " . $currentUser->first_name . " ";
                }

                if($currentUser->middle_name){
                    $encodedBy .= $currentUser->middle_name[0] . ".";
                }

                $vaccinationMonitoring = new VaccinationMonitoring;
                $vaccinationMonitoring->qualified_patient_id = $request["qualified_patient_id"];
                $vaccinationMonitoring->dosage = $request["dosage"];
                $vaccinationMonitoring->vaccination_date = $request["vaccination_date"];
                $vaccinationMonitoring->vaccine_category_id = $request['vaccine_manufacturer'];
                $vaccinationMonitoring->batch_number = $request['batch_number'];
                $vaccinationMonitoring->lot_number = $request['lot_number'];
                $vaccinationMonitoring->vaccinator_id = $request['vaccinator'];
                $vaccinationMonitoring->consent = convertData($request['consent']);
                $vaccinationMonitoring->reason_for_refusal = convertData($request['reason_for_refusal']);
                $vaccinationMonitoring->deferral = convertData($request['deferral']);
                $vaccinationMonitoring->encoded_by = $encodedBy;
                $vaccinationMonitoring->status = 1;
                $changes = $vaccinationMonitoring->getDirty();

                $vaccinationMonitoring->save();
                $monitoringSurvey = new VaccinationMonitoringSurvey;
                $monitoringSurvey->vaccination_monitoring_id = $vaccinationMonitoring->id;
                $monitoringSurvey->question_1 = $request['question1'];
                $monitoringSurvey->question_2 = $request['question2'];
                $monitoringSurvey->question_3 = $request['question3'];
                $monitoringSurvey->question_4 = $request['question4'];
                $monitoringSurvey->question_5 = $request['question5'];
                $monitoringSurvey->question_6 = $request['question6'];
                $monitoringSurvey->question_7 = $request['question7'];
                $monitoringSurvey->question_8 = $request['question8'];
                $monitoringSurvey->question_9 = $request['question9'];
                $monitoringSurvey->question_10 = $request['question10'];
                $monitoringSurvey->question_11 = $request['question11'];
                $monitoringSurvey->question_12 = $request['question12'];
                $monitoringSurvey->question_13 = $request['question13'];
                $monitoringSurvey->question_14 = $request['question14'];
                $monitoringSurvey->question_15 = $request['question15'];
                $monitoringSurvey->question_16 = $request['question16'];
                $monitoringSurvey->question_17 = $request['question17'];
                $monitoringSurvey->question_18 = $request['question18'];
                $monitoringSurvey->question_19 = $request['question19'];
                $monitoringSurvey->status = 1;
                $monitoringSurvey->save();

                DB::connection('covid19vaccine')->commit();

                /* logs */
                action_log('Vaccination Monitoring', 'CREATE', array_merge(['id' => $vaccinationMonitoring->id], $changes));

                return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
            } catch (\PDOException $e) {

                DB::connection('covid19vaccine')->rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $checkSecondDose = false;
        $patient = QualifiedPatient::join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                    ->select(
                        'qualified_patients.id',
                        'pre_registrations.last_name',
                        'pre_registrations.middle_name',
                        'pre_registrations.first_name',
                        'pre_registrations.suffix'
                    )
                    ->where('qualified_patients.id', '=', $id)->first();
        $vaccinationMonitoring = VaccinationMonitoring::where('qualified_patient_id', '=', $patient['id'])->where('dosage', '=', '1')->where('status', '=', '1')->first();
        if($vaccinationMonitoring) $checkSecondDose = true;

        return response::json(["patient" => $patient, "checkSecondDose" => $checkSecondDose]);
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
        $validator = Validator::make($request->all(), [
            'edit_dosage'=> 'required',
            'edit_vaccination_date'=> 'required',
            'edit_vaccine_manufacturer'=> 'required',
            'edit_batch_number' => 'required',
            'edit_lot_number'=> 'required',
            'edit_vaccinator'=> 'required',
            'edit_consent'=> 'required',
        ]);

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

            $encodedBy = $currentUser->last_name;

            if($currentUser->affiliation){
                $encodedBy .= " " . $currentUser->affiliation;
            }

            if($currentUser->first_name){
                $encodedBy .= ", " . $currentUser->first_name . " ";
            }

            if($currentUser->middle_name){
                $encodedBy .= $currentUser->middle_name[0] . ".";
            }

            $vaccinationMonitoring = VaccinationMonitoring::findOrFail($request['monitoring_id']);;
            $vaccinationMonitoring->qualified_patient_id = $request["edit_qualified_patient_id"];
            $vaccinationMonitoring->vaccination_date = $request["edit_vaccination_date"];
            $vaccinationMonitoring->vaccine_category_id = $request['vaccine_manufacturer'];
            $vaccinationMonitoring->batch_number = $request['edit_batch_number'];
            $vaccinationMonitoring->lot_number = $request['edit_lot_number'];
            $vaccinationMonitoring->vaccinator_id = $request['vaccinator'];
            $vaccinationMonitoring->consent = convertData($request['edit_consent']);
            $vaccinationMonitoring->reason_for_refusal = convertData($request['edit_reason_for_refusal']);
            $vaccinationMonitoring->deferral = convertData($request['edit_deferral']);
            $vaccinationMonitoring->encoded_by = $encodedBy;
            $vaccinationMonitoring->reason_for_update = convertData($request['reason_for_update']);
            $vaccinationMonitoring->status = 1;
            $changes = $vaccinationMonitoring->getDirty();
            $vaccinationMonitoring->save();

            $monitoringSurvey = VaccinationMonitoringSurvey::findOrFail($request['survey_id']);
            $monitoringSurvey->vaccination_monitoring_id = $vaccinationMonitoring->id;
            $monitoringSurvey->question_1 = $request['edit_question1'];
            $monitoringSurvey->question_2 = $request['edit_question2'];
            $monitoringSurvey->question_3 = $request['edit_question3'];
            $monitoringSurvey->question_4 = $request['edit_question4'];
            $monitoringSurvey->question_5 = $request['edit_question5'];
            $monitoringSurvey->question_6 = $request['edit_question6'];
            $monitoringSurvey->question_7 = $request['edit_question7'];
            $monitoringSurvey->question_8 = $request['edit_question8'];
            $monitoringSurvey->question_9 = $request['edit_question9'];
            $monitoringSurvey->question_10 = $request['edit_question10'];
            $monitoringSurvey->question_11 = $request['edit_question11'];
            $monitoringSurvey->question_12 = $request['edit_question12'];
            $monitoringSurvey->question_13 = $request['edit_question13'];
            $monitoringSurvey->question_14 = $request['edit_question14'];
            $monitoringSurvey->question_15 = $request['edit_question15'];
            $monitoringSurvey->question_16 = $request['edit_question16'];
            $monitoringSurvey->question_17 = $request['edit_question17'];
            $monitoringSurvey->question_18 = $request['edit_question18'];
            $monitoringSurvey->question_19 = $request['edit_question19'];
            $monitoringSurvey->status = 1;
            $monitoringSurvey->save();

            DB::connection('covid19vaccine')->commit();

            /* logs */
            action_log('Vaccination Monitoring', 'UPDATE', array_merge(['id' => $vaccinationMonitoring->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {

            DB::connection('covid19vaccine')->rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
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
        DB::connection('covid19vaccine')->beginTransaction();
        try {

            $monitoringSummary = VaccinationMonitoring::where('id', '=', $id)->where('status', '=', '1')->first();
            $monitoringSummary->status = 0;
            $changes = $monitoringSummary->getDirty(); 
            $monitoringSummary->save();

            DB::connection('covid19vaccine')->commit();

            /* logs */
            action_log('Vaccination Monitoring', 'DELETED', array_merge(['id' => $monitoringSummary->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::connection('covid19vaccine')->rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    //datatable of vaccination monitoring
    public function monitoringFindAll(Request $request)
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
        )->where('qualified_patients.status', '=', '1')->where('assessment_status', '=', '1')->count();

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
            'qualified_patients.assessment_status',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name'
        );

        if($request["action"] == "second_dose_verification"){
            $query = QualifiedPatient::join(connectionName('covid19vaccine'). '.pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
            ->join(connectionName('covid19vaccine') . '.vaccination_monitorings', 'vaccination_monitorings.qualified_patient_id', '=', 'qualified_patients.id')
            ->select(
                'qualified_patients.id',
                'qualified_patients.qrcode',
                'qualified_patients.registration_id',
                'qualified_patients.qualification_status',
                'qualified_patients.status',
                'qualified_patients.assessment_status',
                'vaccination_monitorings.assessment_status AS monitoring_assessment_status',
                'vaccination_monitorings.vaccination_date',
                'pre_registrations.last_name',
                'pre_registrations.first_name',
                'pre_registrations.middle_name'
            );
        }

         if(empty($request->input('search.value')))
        {
            $qualifiedPatient =  with(clone $query)->where('qualified_patients.status', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
          $search = $request->input('search.value');
            $qualifiedPatient = with(clone $query)
            ->whereRaw("concat(pre_registrations.first_name, ' ', pre_registrations.last_name) like '%{$search}%' ")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            if($request["action"] == "second_dose_verification"){
                $qualifiedPatient = $query
                ->whereRaw("concat(pre_registrations.first_name, ' ', pre_registrations.last_name) like '%{$search}%' ")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            }

            $totalFiltered = with(clone $query)
            ->whereRaw("concat(pre_registrations.first_name, ' ', pre_registrations.last_name) like '%{$search}%' ")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->count();
        }

        $buttons = "";
        $data = array();
        if(!empty($qualifiedPatient))
        {
            foreach ($qualifiedPatient as $qualifiedPatients)
            {
                $vaccinationDate = $qrCode = '';
                if($qualifiedPatients['status'] == '1'){
                    if(Gate::allows('permission', 'viewVaccinationMonitoring')){
                        $btnMonitor = '<a href="#" data-toggle="tooltip" title="Click to monitor patient." onclick="monitor('. $qualifiedPatients['id'] . ')" class="btn btn-xs btn-warning btn-fill btn-rotate edit"><i class="fa fa-stethoscope" aria-hidden="true"></i> Monitor Patient</a></button> | ';
                        $btnView = '<a href="#" data-toggle="tooltip" title="Click to view patient details."  onclick="viewPatient('. $qualifiedPatients['registration_id'] .')"  class="btn btn-xs btn-success btn-fill btn-rotate remove"><i class="fa fa-eye" aria-hidden="true"></i> View Details</a> | ';
                        $btnSummary = '<a href="#" data-toggle="tooltip" title="Click to view vaccination summary."  onclick="viewSummary('. $qualifiedPatients['id'] .')"  class="btn btn-xs btn-info btn-fill btn-rotate remove"><i class="fa fa-list-alt" aria-hidden="true"></i> Vaccination Summary</a> ';
                    }else{
                        $btnMonitor = $btnView = $btnSummary = "";
                    }
                    
                    if(Gate::allows('permission', 'viewVASLineInfo')){
                        $btnVas = "";
                        if($btnSummary != ""){
                            $btnVas = "| ";
                        }
                        $btnVas .= '<a href="#" data-toggle="tooltip" title="Click to view information." onclick="viewVasInfo('. $qualifiedPatients['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="fa fa-list-alt" aria-hidden="true"></i> Vas Line Info</a>';
                    }else{
                        $btnVas = "";
                    }
                    
                    $buttons = $btnMonitor. " " . $btnView . " " . $btnSummary . " " . $btnVas;
                    $status = "<label class='label label-success'><i class='fa fa-check-circle' aria-hidden='true'></i> Verified</label>";
                }
                if($request["action"] == "second_dose_verification"){
                    $buttons = '<a href="#" data-toggle="tooltip" title="Click to verify patient." onclick="verifyPatient('. $qualifiedPatients['registration_id'] . ')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="fa fa-search" aria-hidden="true"></i> Verify Patient</a></button>';
                    if($qualifiedPatients['monitoring_assessment_status'] == "1")
                        $buttons = '<a href="#" data-toggle="tooltip" title="Click to verify patient." class="btn btn-xs btn-info btn-fill btn-rotate edit" disabled><i class="fa fa-search" aria-hidden="true"></i> Verify Patient</a></button>';
                    $vaccinationDate = date("m-d-Y", strtotime($qualifiedPatients['vaccination_date']));
                    $qrCode = $qualifiedPatients['qrcode'];
                    if($qualifiedPatients['monitoring_assessment_status'] == "" || $qualifiedPatients['monitoring_assessment_status'] == null){
                        $status = "<label class='label label-danger'><i class='fa fa-check-circle' aria-hidden='true'></i> Not Yet Verified</label>";
                    }else{
                        $status = "<label class='label label-success'><i class='fa fa-check-circle' aria-hidden='true'></i> Verified</label>";
                    }
                }

                $middleName = "";
                if($qualifiedPatients->middle_name != "NA"){$middleName = $qualifiedPatients->middle_name;}
                $fullname = $qualifiedPatients->last_name . " ". $qualifiedPatients->affiliation . ", ". $qualifiedPatients->first_name . " ". $middleName;
                $nestedData['fullname'] = $fullname;
                $nestedData['status'] = $status;
                $nestedData['vaccination_date'] = $vaccinationDate;
                $nestedData['qr_code'] = $qrCode;
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
    
    
    //datatable of vaccination monitoring summary
    public function monitoringFindAllSummary(Request $request)
    {
        $columns = array(
            0 => 'pre_registrations.last_name',
            1 => 'vaccination_date',
            2 => 'dosage',
        );
        
        $query = VaccinationMonitoring::join(connectionName('covid19vaccine'). '.qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
        ->join(connectionName('covid19vaccine'). '.pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
        ->join(connectionName('covid19vaccine'). '.vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
        ->join(connectionName('covid19vaccine'). '.health_facilities as health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
        ->join(connectionName('covid19vaccine'). '.vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
        ->join(connectionName('covid19vaccine'). '.vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
        ->select(
            'vaccination_monitorings.id AS monitoring_id',
            'vaccination_monitorings.dosage',
            'vaccination_monitorings.vaccination_date',
            'vaccination_monitorings.batch_number',
            'vaccination_monitorings.lot_number',
            'vaccination_monitorings.encoded_by',
            'vaccination_monitorings.consent',
            'vaccination_monitorings.created_at AS date_encoded',
            'vaccination_monitorings.reason_for_refusal',
            'vaccination_monitorings.deferral',
            'vaccinators.first_name as vaccinator_first_name',
            'vaccinators.last_name as vaccinator_last_name',
            'vaccinators.middle_name as vaccinator_middle_name',
            'vaccinators.suffix as vaccinator_suffix',
            'health_facilities.facility_name as facility_name',
            'qualified_patients.id',
            'qualified_patients.registration_id',
            'qualified_patients.qualification_status',
            'qualified_patients.status',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name',
            'vaccination_monitoring_surveys.question_1',
            'vaccination_monitoring_surveys.question_2',
            'vaccination_monitoring_surveys.question_3',
            'vaccination_monitoring_surveys.question_4',
            'vaccination_monitoring_surveys.question_5',
            'vaccination_monitoring_surveys.question_6',
            'vaccination_monitoring_surveys.question_7',
            'vaccination_monitoring_surveys.question_8',
            'vaccination_monitoring_surveys.question_9',
            'vaccination_monitoring_surveys.question_10',
            'vaccination_monitoring_surveys.question_11',
            'vaccination_monitoring_surveys.question_12',
            'vaccination_monitoring_surveys.question_13',
            'vaccination_monitoring_surveys.question_14',
            'vaccination_monitoring_surveys.question_15',
            'vaccination_monitoring_surveys.question_16',
            'vaccination_monitoring_surveys.question_17',
            'vaccination_monitoring_surveys.question_18',
            'vaccination_monitoring_surveys.question_19',
            'vaccine_categories.vaccine_name')
            ->where('vaccination_monitorings.status', '=', '1')
            ->where('qualified_patients.qualification_status', '=', 'APPROVED')
            ->where('qualified_patients.assessment_status', '=', '1')
            ->groupBy('qualified_patients.id', 'vaccination_monitorings.dosage')
            ->orderBy('vaccination_monitorings.created_at', 'DESC');

        $totalData = $query->get()->count();
        $totalFiltered = $totalData;
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $qualifiedPatient =  with(clone $query)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value');
            $qualifiedPatient = with(clone $query)
            ->where('pre_registrations.last_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('pre_registrations.first_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('pre_registrations.middle_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('vaccination_monitorings.vaccination_date', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('vaccine_categories.vaccine_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
            
            $totalFiltered = $qualifiedPatient->count();
        }

        $buttons = "";
        $data = array();
        if(!empty($qualifiedPatient))
        {
            foreach ($qualifiedPatient as $qualifiedPatients)
            {
                $vaccinator = '';
                $vaccinator = $qualifiedPatients->vaccinator_last_name;
                if($qualifiedPatients->vaccinator_suffix && $qualifiedPatients->vaccinator_suffix != "NA"){ $vaccinator .= " " . $qualifiedPatients->vaccinator_suffix;}
                $vaccinator .= ", " . $qualifiedPatients->vaccinator_first_name . " ";
                
                if($qualifiedPatients->vaccinator_middle_name && $qualifiedPatients->vaccinator_middle_name != "NA"){ $vaccinator .= $qualifiedPatients->vaccinator_middle_name[0] . "."; }

                $middleName = "";
                if($qualifiedPatients->middle_name != "NA"){$middleName = $qualifiedPatients->middle_name;}
                $fullname = $qualifiedPatients->last_name . " ". $qualifiedPatients->affiliation . ", ". $qualifiedPatients->first_name . " ". $middleName;
                
                $nestedData['dosage'] = $qualifiedPatients['dosage'];
                $nestedData['vaccination_date'] =  $qualifiedPatients['vaccination_date'];
                $nestedData['date_encoded'] = date("m/d/Y", strtotime($qualifiedPatients['date_encoded']));
                $nestedData['time_encoded'] = date("G:i:s", strtotime($qualifiedPatients['date_encoded']));
                $nestedData['vaccine_name'] = $qualifiedPatients['vaccine_name'];
                $nestedData['batch_number'] = $qualifiedPatients['batch_number'];
                $nestedData['lot_number'] = $qualifiedPatients['lot_number'];
                $nestedData['encoded_by'] = $qualifiedPatients['encoded_by'];
                $nestedData['consent'] = $qualifiedPatients['consent'];
                $nestedData['reason_for_refusal'] = $qualifiedPatients['reason_for_refusal'];
                $nestedData['deferral'] = $qualifiedPatients['deferral'];
                $nestedData['vaccinator'] = $vaccinator;
                $nestedData['facility'] = $qualifiedPatients['facility_name'];
                $nestedData['fullname'] = $fullname;
                $nestedData['data'] = $qualifiedPatients;
                // $nestedData['otherInformation'] = '<a href="#" data-toggle="tooltip" title="Click to view other information." onclick="viewOtherInformation('. $qualifiedPatients['monitoring_id'] .')"  class="btn btn-xs btn-info btn-fill btn-rotate remove"><i class="fa fa-list-alt" aria-hidden="true"></i> Other Informations</a>';
                $nestedData['action'] = $buttons;

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
    
    //find all patients vaccinated for first dose
    public function findAllVaccinatedFirstDose(Request $request)
    {
        $columns = array(
            0 => 'pre_registrations.last_name',
            1 => 'vaccination_date',
            2 => 'dosage',
        );
        
        $vaccinatedId = array();
        $results = VaccinationMonitoring::
        join(connectionName('covid19vaccine'). '.qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
        // ->join(connectionName('covid19vaccine'). '.pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
        // ->join(connectionName('covid19vaccine'). '.vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
        // ->join(connectionName('covid19vaccine'). '.health_facilities as health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
        // ->join(connectionName('covid19vaccine'). '.vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
        // ->join(connectionName('covid19vaccine'). '.vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
        ->select('vaccination_monitorings.id', 'vaccination_monitorings.qualified_patient_id AS qualified_patient_id')    
        ->where('vaccination_monitorings.status', '=', '1')
        ->where('vaccination_monitorings.dosage', '=', '2')
        ->where('qualified_patients.qualification_status', '=', 'APPROVED')
        ->where('qualified_patients.assessment_status', '=', '1')
        ->groupBy('qualified_patients.id', 'vaccination_monitorings.dosage')
        ->orderBy('vaccination_monitorings.created_at', 'DESC')
        ->get();
        
        foreach($results as $result){
            // if(!($checkVaccinatedSecondDose)){
                $vaccinatedId[] = $result->qualified_patient_id; 
            // }
            // $checkVaccinatedSecondDose = VaccinationMonitoring::
            //     join(connectionName('covid19vaccine'). '.qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                //  ->where('vaccination_monitorings.qualified_patient_id', '=', $result->id)->where('vaccination_monitorings.status', '=', '1')->where('vaccination_monitorings.dosage', '=', '2')->first();
            // dd($checkVaccinatedSecondDose);
            // if(!($checkVaccinatedSecondDose)){
            //     $vaccinatedId[] = $result->id; 
            // }
        }
        
        $query = VaccinationMonitoring::join(connectionName('covid19vaccine'). '.qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
            ->join(connectionName('covid19vaccine'). '.pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
            ->join(connectionName('covid19vaccine'). '.vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
            ->join(connectionName('covid19vaccine'). '.health_facilities as health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
            ->join(connectionName('covid19vaccine'). '.vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
            ->join(connectionName('covid19vaccine'). '.vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
            ->select(
                'vaccination_monitorings.id AS monitoring_id',
                'vaccination_monitorings.dosage',
                'vaccination_monitorings.vaccination_date',
                'vaccination_monitorings.batch_number',
                'vaccination_monitorings.lot_number',
                'vaccination_monitorings.encoded_by',
                'vaccination_monitorings.consent',
                'vaccination_monitorings.created_at AS date_encoded',
                'vaccination_monitorings.reason_for_refusal',
                'vaccination_monitorings.deferral',
                'vaccinators.first_name as vaccinator_first_name',
                'vaccinators.last_name as vaccinator_last_name',
                'vaccinators.middle_name as vaccinator_middle_name',
                'vaccinators.suffix as vaccinator_suffix',
                'health_facilities.facility_name as facility_name',
                'qualified_patients.id',
                'qualified_patients.registration_id',
                'qualified_patients.qualification_status',
                'qualified_patients.status',
                'pre_registrations.last_name',
                'pre_registrations.first_name',
                'pre_registrations.middle_name',
                'vaccination_monitoring_surveys.question_1',
                'vaccination_monitoring_surveys.question_2',
                'vaccination_monitoring_surveys.question_3',
                'vaccination_monitoring_surveys.question_4',
                'vaccination_monitoring_surveys.question_5',
                'vaccination_monitoring_surveys.question_6',
                'vaccination_monitoring_surveys.question_7',
                'vaccination_monitoring_surveys.question_8',
                'vaccination_monitoring_surveys.question_9',
                'vaccination_monitoring_surveys.question_10',
                'vaccination_monitoring_surveys.question_11',
                'vaccination_monitoring_surveys.question_12',
                'vaccination_monitoring_surveys.question_13',
                'vaccination_monitoring_surveys.question_14',
                'vaccination_monitoring_surveys.question_15',
                'vaccination_monitoring_surveys.question_16',
                'vaccination_monitoring_surveys.question_17',
                'vaccination_monitoring_surveys.question_18',
                'vaccination_monitoring_surveys.question_19',
                'vaccine_categories.vaccine_name'
                )
            ->where('vaccination_monitorings.status', '=', '1')
            ->where('qualified_patients.qualification_status', '=', 'APPROVED')
            ->where('qualified_patients.assessment_status', '=', '1')
            ->whereNotIn('vaccination_monitorings.qualified_patient_id', $vaccinatedId)
            ->groupBy('qualified_patients.id', 'vaccination_monitorings.dosage')
            ->orderBy('vaccination_monitorings.vaccination_date', 'ASC');
            
        $totalData = $query->get()->count();
        $totalFiltered = $totalData;
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $qualifiedPatient =  with(clone $query)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value');
            $qualifiedPatient = with(clone $query)
            ->where('pre_registrations.last_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('pre_registrations.first_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('pre_registrations.middle_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('vaccination_monitorings.vaccination_date', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->orWhere('vaccine_categories.vaccine_name', 'LIKE',"%{$search}%")->where('qualified_patients.status', '=', '1')->where('qualified_patients.qualification_status', '=', 'APPROVED')->where('qualified_patients.assessment_status', '=', '1')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
            
            $totalFiltered = $qualifiedPatient->count();
        }

        $buttons = "";
        $data = array();
        if(!empty($qualifiedPatient))
        {
            foreach ($qualifiedPatient as $qualifiedPatients)
            {
                $vaccinator = '';
                $vaccinator = $qualifiedPatients->vaccinator_last_name;
                if($qualifiedPatients->vaccinator_suffix && $qualifiedPatients->vaccinator_suffix != "NA"){ $vaccinator .= " " . $qualifiedPatients->vaccinator_suffix;}
                $vaccinator .= ", " . $qualifiedPatients->vaccinator_first_name . " ";
                
                if($qualifiedPatients->vaccinator_middle_name && $qualifiedPatients->vaccinator_middle_name != "NA"){ $vaccinator .= $qualifiedPatients->vaccinator_middle_name[0] . "."; }

                $middleName = "";
                if($qualifiedPatients->middle_name != "NA"){$middleName = $qualifiedPatients->middle_name;}
                $fullname = $qualifiedPatients->last_name . " ". $qualifiedPatients->affiliation . ", ". $qualifiedPatients->first_name . " ". $middleName;
                
                // $approximate_date_second_dose 
                $first_dose = new Carbon(date("m/d/Y", strtotime($qualifiedPatients['vaccination_date'])));
                if($qualifiedPatients['vaccine_name'] == "ASTRAZENECA"){
                    $first_dose = $first_dose->addMonths(3);
                }if($qualifiedPatients['vaccine_name'] == "SINOVAC"){
                    $first_dose = $first_dose->addMonths(1);
                }
                
                $today = Carbon::now()->format('m-d-Y');
                $first_dose = $first_dose->format('m-d-Y');
                if($today > $first_dose){
                    $first_dose = "<span style='color:red'>". $first_dose . "</span>";
                }
                
                $nestedData['dosage'] = $qualifiedPatients['dosage'];
                $nestedData['vaccination_date'] =  date("m-d-Y", strtotime($qualifiedPatients['vaccination_date']));
                $nestedData['vaccine_name'] = $qualifiedPatients['vaccine_name'];
                $nestedData['approximate_date_second_dose'] = $first_dose;
                $nestedData['fullname'] = $fullname;
                $nestedData['data'] = $qualifiedPatients;
                // $nestedData['otherInformation'] = '<a href="#" data-toggle="tooltip" title="Click to view other information." onclick="viewOtherInformation('. $qualifiedPatients['monitoring_id'] .')"  class="btn btn-xs btn-info btn-fill btn-rotate remove"><i class="fa fa-list-alt" aria-hidden="true"></i> Other Informations</a>';
                $nestedData['action'] = $buttons;

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

    //vaccination summary
    public function findSummary(Request $request, $id)
    {
        $columns = array(
            0=> 'dosage',
            1=> 'vaccination_date',
        );

        $query = VaccinationMonitoring::join('qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
        ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
        ->join('vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
        ->join('vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
        ->join('vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
        ->select(
            'vaccination_monitorings.id AS monitoring_id',
            'vaccination_monitorings.dosage',
            'vaccination_monitorings.vaccination_date',
            'vaccination_monitorings.batch_number',
            'vaccination_monitorings.lot_number',
            'vaccination_monitorings.encoded_by',
            'vaccination_monitorings.consent',
            'vaccination_monitorings.reason_for_refusal',
            'vaccination_monitorings.deferral',
            'vaccinators.first_name as vaccinator_first_name',
            'vaccinators.last_name as vaccinator_last_name',
            'vaccinators.middle_name as vaccinator_middle_name',
            'vaccinators.suffix as vaccinator_suffix',
            'qualified_patients.id',
            'qualified_patients.registration_id',
            'qualified_patients.qualification_status',
            'qualified_patients.status',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name',
            'vaccination_monitoring_surveys.question_1',
            'vaccination_monitoring_surveys.question_2',
            'vaccination_monitoring_surveys.question_3',
            'vaccination_monitoring_surveys.question_4',
            'vaccination_monitoring_surveys.question_5',
            'vaccination_monitoring_surveys.question_6',
            'vaccination_monitoring_surveys.question_7',
            'vaccination_monitoring_surveys.question_8',
            'vaccination_monitoring_surveys.question_9',
            'vaccination_monitoring_surveys.question_10',
            'vaccination_monitoring_surveys.question_11',
            'vaccination_monitoring_surveys.question_12',
            'vaccination_monitoring_surveys.question_13',
            'vaccination_monitoring_surveys.question_14',
            'vaccination_monitoring_surveys.question_15',
            'vaccination_monitoring_surveys.question_16',
            'vaccination_monitoring_surveys.question_17',
            'vaccination_monitoring_surveys.question_18',
            'vaccination_monitoring_surveys.question_19',
            'vaccine_categories.vaccine_name'
        )->where('vaccination_monitorings.status', '=', '1')->where('qualified_patients.id', '=', $id);

        $totalData = $query->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        if(empty($request->input('search.value')))
        {
            $qualifiedPatient = $query
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }

        $buttons = "";
        $data = array();
        if(!empty($qualifiedPatient))
        {
            foreach ($qualifiedPatient as $qualifiedPatients)
            {
                $buttons = "";
                if(Gate::allows('permission', 'updateVaccinationMonitoring')){
                    $buttons = '<a data-toggle="tooltip" title="Click to update information." onclick="updateVacinnationSummary('. $qualifiedPatients['monitoring_id'] .')"  class="btn btn-xs btn-info btn-fill btn-rotate remove"><i class="fa fa-edit" aria-hidden="true"></i> UPDATE</a>';
                }else{
                    $buttons = '<a data-toggle="tooltip" title="Click to update information." disabled class="btn btn-xs btn-info btn-fill btn-rotate remove"><i class="fa fa-edit" aria-hidden="true"></i> UPDATE</a>';
                }
                if(Gate::allows('permission', 'deleteVaccinationMonitoring')){
                    $buttons .= '<a data-toggle="tooltip" title="Click to void information." onclick="voidSummary('. $qualifiedPatients['monitoring_id'] .')"  class="btn btn-xs btn-waning btn-fill btn-rotate remove"><i class="fa fa-trash" aria-hidden="true"></i> VOID</a>';
                }

                $vaccinator = '';
                $vaccinator = $qualifiedPatients->vaccinator_last_name;
                if($qualifiedPatients->vaccinator_suffix && $qualifiedPatients->vaccinator_suffix != "NA"){ $vaccinator .= " " . $qualifiedPatients->vaccinator_suffix;}
                $vaccinator .= ", " . $qualifiedPatients->vaccinator_first_name . " ";
                if($qualifiedPatients->middle_name && $qualifiedPatients->middle_name != "NA"){ $vaccinator .= $qualifiedPatients->vaccinator_middle_name[0] . "."; }

                $middleName = "";
                if($qualifiedPatients->middle_name != "NA"){$middleName = $qualifiedPatients->middle_name;}
                $fullname = $qualifiedPatients->last_name . " ". $qualifiedPatients->affiliation . ", ". $qualifiedPatients->first_name . " ". $middleName;

                $nestedData['dosage'] = $qualifiedPatients['dosage'];
                $nestedData['vaccination_date'] = $qualifiedPatients['vaccination_date'];
                $nestedData['vaccine_name'] = $qualifiedPatients['vaccine_name'];
                $nestedData['batch_number'] = $qualifiedPatients['batch_number'];
                $nestedData['lot_number'] = $qualifiedPatients['lot_number'];
                $nestedData['encoded_by'] = $qualifiedPatients['encoded_by'];
                $nestedData['consent'] = $qualifiedPatients['consent'];
                $nestedData['reason_for_refusal'] = $qualifiedPatients['reason_for_refusal'];
                $nestedData['deferral'] = $qualifiedPatients['deferral'];
                $nestedData['vaccinator'] = $vaccinator;
                $nestedData['fullname'] = $fullname;
                $nestedData['buttons'] = $buttons;
                $nestedData['data'] = $qualifiedPatients;
                // $nestedData['otherInformation'] = '<a href="#" data-toggle="tooltip" title="Click to view other information." onclick="viewOtherInformation('. $qualifiedPatients['monitoring_id'] .')"  class="btn btn-xs btn-info btn-fill btn-rotate remove"><i class="fa fa-list-alt" aria-hidden="true"></i> Other Informations</a>';
                $nestedData['action'] = $buttons;

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

    public function exportVaccineMonitoringList() {


        $facility = DB::table('person_department_positions')
            // ->select('position_accesses.access', 'position_accesses.status')
            ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
            ->join('departments', 'departments.id', '=', 'department_positions.department_id')

            ->where('person_department_positions.person_id', '=', Auth::user()->person_id)->first();

        // dd($person_dept_pos->department);

        $exporter = Auth::user()->person->last_name . ", " . Auth::user()->person->first_name . " " . Auth::user()->person->middle_name;


        // dd($facility->department);



        $now = Carbon::now();

        $filename = "VIMS_VAS_$now.xlsx";
        return Excel::download(new VIMSVASExport("", $facility->department, $exporter), $filename);
    }

    public function summaryOtherInformation($id)
    {
        $columns = array(
            0 => 'id',
        );

        $otherInformation = VaccinationMonitoringSurvey::where('vaccination_monitoring_id', '=', $id)->first();

        return response::json($otherInformation);
    }

    public function findVaccinationSummary($id){

        $query = VaccinationMonitoring::join('qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
        ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
        ->join('vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
        ->join('vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
        ->join('vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
        ->select(
            'vaccination_monitorings.id AS monitoring_id',
            'vaccination_monitorings.dosage',
            'vaccination_monitorings.vaccination_date',
            'vaccination_monitorings.batch_number',
            'vaccination_monitorings.lot_number',
            'vaccination_monitorings.encoded_by',
            'vaccination_monitorings.consent',
            'vaccination_monitorings.reason_for_refusal',
            'vaccination_monitorings.deferral',
            'vaccinators.id as vaccinator_id',
            'vaccinators.first_name as vaccinator_first_name',
            'vaccinators.last_name as vaccinator_last_name',
            'vaccinators.middle_name as vaccinator_middle_name',
            'vaccinators.suffix as vaccinator_suffix',
            'qualified_patients.id',
            'qualified_patients.registration_id',
            'qualified_patients.qualification_status',
            'qualified_patients.status',
            'pre_registrations.last_name',
            'pre_registrations.first_name',
            'pre_registrations.middle_name',
            'vaccination_monitoring_surveys.id as survey_id',
            'vaccination_monitoring_surveys.question_1',
            'vaccination_monitoring_surveys.question_2',
            'vaccination_monitoring_surveys.question_3',
            'vaccination_monitoring_surveys.question_4',
            'vaccination_monitoring_surveys.question_5',
            'vaccination_monitoring_surveys.question_6',
            'vaccination_monitoring_surveys.question_7',
            'vaccination_monitoring_surveys.question_8',
            'vaccination_monitoring_surveys.question_9',
            'vaccination_monitoring_surveys.question_10',
            'vaccination_monitoring_surveys.question_11',
            'vaccination_monitoring_surveys.question_12',
            'vaccination_monitoring_surveys.question_13',
            'vaccination_monitoring_surveys.question_14',
            'vaccination_monitoring_surveys.question_15',
            'vaccination_monitoring_surveys.question_16',
            'vaccination_monitoring_surveys.question_17',
            'vaccination_monitoring_surveys.question_18',
            'vaccination_monitoring_surveys.question_19',
            'vaccine_categories.vaccine_name',
            'vaccine_categories.id as vaccine_id'
        )->where('vaccination_monitorings.status', '=', '1')->where('vaccination_monitorings.id', '=', $id);

        $result = $query->first();
        $result['question_9'] = ($result['question_9'])? explode(',', $result['question_9']) : $result['question_9'];
        $result['question_17'] = ($result['question_17'])? explode(',', $result['question_17']) : $result['question_17'];

        return $result;
    }
    
    public function vasLineInfo($id)
    {
        $yes_no = array("01_Yes", "02_No");

        $active_region = "REGION IV-A (CALABARZON)";
        $active_province = "043400000Laguna";
        $active_city = "043404000City of Cabuyao";

        $first_dose = "02_No";
        $second_dose = "02_No";
        
        $category = [
            '01_Health_Care_Worker' => 'A1',
            '02_Senior_Citizen' => 'A2',
            '07_Comorbidities' => 'A3',
            '03_Indigent ' => 'A5',
            '12_Remaining_Workforce' => 'A4',
            '11_OFW' => 'A4',
            '10_Other_High_Risk' => 'A4',
            '09_Other_Govt_Wokers' => 'A4',
            '08_Teachers_Social_Workers' => 'A4',
            '06_Other' => 'A4',
            '05_Essential_Worker' => 'A4',
            '04_Uniformed_Personnel' => 'A4',
        ];

        $vaccine = [
            "SINOVAC" => "Sinovac",
            "ASTRAZENECA" => "AZ",
            "PFIZER" => "Pfizer",
            "MODERNA" => "Moderna",
            "SPUTNIK V/GAMALEYA" => "Gamaleya",
            "NOVAVAX" => "Novavax",
            "JOHNSON AND JOHNSON" => "J&J",
        ];

        
        $center = [
            "CABUYAO CHO I – BAKUNA CENTER" => "CBC07609",
            "CABUYAO CHO II – BAKUNA CENTER" => "CBC07625",
            "CABUYAO CITY HOSPITAL" => "CBC06192",
            "HOLY ROSARY OF CABUYAO HOSPITAL INC." => "CBC06191",
            "FIRST CABUYAO HOSPITAL AND MEDICAL CENTER, INC." => "CBC06190",
            "GLOBAL MEDICAL CENTER OF LAGUNA" => "CBC06260",
        ];
        
        // $vaccination_monitoring = ExportHasPatient::join('vaccination_monitorings as vaccination_monitorings', 'vaccination_monitorings.id', '=',  'export_has_patients.patient_id')
        $vaccination_monitorings = VaccinationMonitoring::
            join('qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
            ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
            ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
            ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
            ->join('vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
            ->join('vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
            ->join('health_facilities as health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
            ->join('vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
            ->join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
            ->select(
                'categories.category_format',
                'id_categories.id_category_code',
                'id_categories.id as id_category',

                'pre_registrations.category_id_number',
                'pre_registrations.philhealth_number',
                'pre_registrations.barangay_id',
                'pre_registrations.last_name',
                'pre_registrations.first_name',
                'pre_registrations.middle_name',
                'pre_registrations.suffix',
                'pre_registrations.contact_number',
                'pre_registrations.home_address',
                'pre_registrations.status',
                'pre_registrations.province',
                'pre_registrations.city',
                // 'pre_registrations.barangay',
                'barangays.real_name as barangay',
                'pre_registrations.sex',
                'pre_registrations.date_of_birth',
                'qualified_patients.qrcode',
                'qualified_patients.id as qualified_patient__id',


                'vaccination_monitorings.id as vaccination_monitorings_id',
                'vaccination_monitorings.consent',
                'vaccination_monitorings.reason_for_refusal',
                'vaccination_monitorings.vaccination_date',
                // 'vaccination_monitorings.vaccine_manufacturer',
                'vaccine_categories.vaccine_name as vaccine_manufacturer',
                'vaccination_monitorings.batch_number',
                'vaccination_monitorings.lot_number',
                'vaccination_monitorings.deferral',
                'vaccination_monitorings.dosage',
                // 'vaccination_monitorings.vaccine_manufacturer',
                // 'pre_registrations.civil_status',
                'vaccinators.last_name as vaccinator_lastname',
                'vaccinators.first_name as vaccinator_firstname',
                // 'vaccinators.middle_name',
                'vaccinators.suffix',
                'vaccinators.profession',
                'health_facilities.facility_name',

                'vaccination_monitoring_surveys.question_1 as age_validation',
                'vaccination_monitoring_surveys.question_2 as allergic_for_peg',
                'vaccination_monitoring_surveys.question_3 as allergic_after_dose',
                'vaccination_monitoring_surveys.question_4 as allergic_to_food',
                'vaccination_monitoring_surveys.question_5 as asthma_validation',
                'vaccination_monitoring_surveys.question_6 as bleeding_disorders',
                'vaccination_monitoring_surveys.question_7 as syringe_validation',
                'vaccination_monitoring_surveys.question_8 as symptoms_manifest',
                'vaccination_monitoring_surveys.question_9 as symptoms_specific',
                'vaccination_monitoring_surveys.question_10 as infection_history',
                'vaccination_monitoring_surveys.question_11 as previously_treated',
                'vaccination_monitoring_surveys.question_12 as received_vaccine',
                'vaccination_monitoring_surveys.question_13 as received_convalescent',
                'vaccination_monitoring_surveys.question_14 as pregnant',
                'vaccination_monitoring_surveys.question_15 as pregnancy_trimester',
                'vaccination_monitoring_surveys.question_16 as diagnosed_six_months',
                'vaccination_monitoring_surveys.question_17 as specific_diagnosis',
                'vaccination_monitoring_surveys.question_18 as medically_cleared'

            )
            // ->where('vaccination_monitorings.status', '=', 1)
            ->where('qualified_patients.id', '=', $id)->get();
        // return $vaccination_monitoring;
        $data = array();
        foreach($vaccination_monitorings as $vaccination_monitoring){
        
            if($vaccination_monitoring->dosage == 1) {
                $first_dose = "01_Yes";
                $second_dose = "02_No";
            } else {
                $first_dose = "01_Yes";
                $second_dose = "01_Yes";
            }
            
            $nestedData["0"] = $category[$vaccination_monitoring->category_format];
            $nestedData["1"] = $vaccination_monitoring->qrcode; //government ID
            $nestedData["2"] = ($vaccination_monitoring->id_category_code) == "04 - PWD ID"? "Y" : "N";
            $nestedData["3"] = "NO";
            $nestedData["4"] = $vaccination_monitoring->last_name;
            $nestedData["5"] = $vaccination_monitoring->first_name;
            $nestedData["6"] = $vaccination_monitoring->middle_name;
            $nestedData["7"] = ($vaccination_monitoring->suffix == null)? "NA" : $vaccination_monitoring->suffix;
            $nestedData["8"] = $vaccination_monitoring->contact_number;
            $nestedData["9"] = $active_region;
            $nestedData["10"] = $active_province;
            $nestedData["11"] = $active_city;
            $nestedData["12"] = $vaccination_monitoring->barangay;
            $nestedData["13"] = ($vaccination_monitoring->sex) == '1_FEMALE'? "F" : "M";
            $nestedData["14"] = $vaccination_monitoring->date_of_birth;
            $nestedData["15"] = "N"; //deferral
            $nestedData["16"] = "NONE"; // reason for deferral
            $nestedData["17"] = $vaccination_monitoring->vaccination_date;
            $nestedData["18"] = $vaccine[$vaccination_monitoring->vaccine_manufacturer];
            $nestedData["19"] = $vaccination_monitoring->batch_number;
            $nestedData["20"] = $vaccination_monitoring->lot_number;
            $nestedData["21"] = $center[$vaccination_monitoring->facility_name];
            $nestedData["22"] = $vaccination_monitoring->vaccinator_lastname . ", " . $vaccination_monitoring->vaccinator_firstname;
            $nestedData["23"] = ($first_dose == "01_Yes") ? 'Y' : 'N';
            $nestedData["24"] = ($second_dose == "01_Yes") ? 'Y' : 'N';
            $nestedData["25"] = "N";
            $nestedData["26"] = "NONE";
            
            $data[] = $nestedData;
        }
        return $data;
    }
}
