<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\DataTableEvent;
use App\CovidTracer\SignsSymptoms;
use App\CovidTracer\PatientProfile;
use DB;
use Validator;
use Response;
use Carbon\Carbon;
use App\CovidTracer\PlaceOfAssignment;
use App\User;

class PatientMonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covidtracer.patient_monitoring.index', ['title' => 'Patient Monitoring Management']);
    }

    public function reports()
    {
        return view('covidtracer.patient_monitoring.reports', ['title' => 'Patient Monitoring Management']);
    }

    public function findAllReports(Request $request)
    {
        $columns = array(
            0 =>'last_name',
            1 =>'address',
            2 =>'date_of_onset_of_illness',
            3 =>'date_of_admission_consultation'
        );

        $totalData = PatientProfile::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        // if($request['patientStatus']){
        //     $status = $request['patientStatus'];
        //     if($status == 1 || $status == 2 || $status == 3){
        //         $query = DB::table(connectionName('covid_tracer') . '.patient_profiles')
        //             ->leftJoin(connectionName('covid_tracer') . '.signs_symptoms', 'signs_symptoms.patient_profile_id', '=', 'patient_profiles.id')
        //             // ->leftJoin(connectionName('covid_tracer') . '.signs_symptoms', function($query){
        //             //     $query->on('patient_profiles.id','=','signs_symptoms.patient_profile_id')
        //             //     ->whereRaw(connectionName('covid_tracer').'.signs_symptoms.patient_profile_id = (select MAX('.connectionName('covid_tracer').'.signs_symptoms.patient_profile_id) FROM '.connectionName('covid_tracer').'.signs_symptoms as '.connectionName('covid_tracer').'.signs_symptoms ON '.connectionName('covid_tracer').'.patient_profiles.id = '.connectionName('covid_tracer').'.signs_symptoms.patient_profile_id )');
        //             // })
        //             ->select('patient_profiles.*', 'signs_symptoms.signs_symptoms_status')
        //             ->where('signs_symptoms.signs_symptoms_status', '=', $status)->distinct();
        //             // ->whereRaw(connectionName('covid_tracer').'.signs_symptoms.patient_profile_id = (select MAX('.connectionName('covid_tracer').'.signs_symptoms.patient_profile_id) FROM '.connectionName('covid_tracer').'.signs_symptoms)')
        //     }
        // }

        $allPatients = PatientProfile::all();
        $monitorPatient = array();
        $patients = array();
        foreach($allPatients as $patient){
            $signs = SignsSymptoms::where('patient_profile_id', '=', $patient->id)->orderBy('id', 'desc')->first();
            $data["id"] = $signs->id;
            $data["status"] = $signs->signs_symptoms_status;
            $monitorPatient[] = $data;
        }

        foreach($monitorPatient as $patient){
            if($request['patientStatus']){
                $status = $request['patientStatus'];
                if($patient['status'] == $status){
                    $patients[] = $patient['id'];
                }
            }
            else{
                $patients[] = $patient['id'];
            }
        }        

        $query = DB::table(connectionName('covid_tracer') . '.patient_profiles')
                ->select('patient_profiles.*')
                ->join(connectionName('covid_tracer') . '.signs_symptoms', 'signs_symptoms.patient_profile_id', '=', 'patient_profiles.id')
                ->whereIn('signs_symptoms.id', $patients);
                
        $totalFiltered = $query->count();
        
        if(empty($request->input('search.value')))
        {
            $results = $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir) 
                ->get();
        }
        // else {
        //     $search = $request->input('search.value');

        //     $results = $query
        //         ->where('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
        //         ->offset($start)
        //         ->limit($limit)
        //         ->orderBy($order, $dir)
        //         ->get();

        // }
        
        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $suffix =  (!empty($result->affiliation))? " ".$result->affiliation: '';
                $fullname = ucfirst($result->last_name) .$suffix .', '. ucfirst($result->first_name) .' '. ucfirst($result->middle_name);
                $informant = SignsSymptoms::where('patient_profile_id', '=', $result->id)->orderBy('id', 'desc')->first();
                
                $patientStatus = $informant->signs_symptoms_status;
                $status = '';

                if($patientStatus == 1){
                    $status = "<label class='label label-danger'>ON GOING</label>";
                }else if($patientStatus == 2){
                    $status = "<label class='label label-success'>RECOVERED</label>";
                }else{
                    $status = "<label class='label label-default'>DECEASED</label>";
                }
                
                $nestedData['id'] = $result->id;
                $nestedData['fullname'] =  $fullname ;
                $nestedData['dateOnsetOfIllness'] =  $result->date_of_onset_of_illness;
                $nestedData['dateOfAdmissionConsultation'] =  $result->date_of_admission_consultation;
                $nestedData['status'] = $status;
                $data[] = $nestedData;
            }
        } 
        $totalData = count($data);

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

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
            'category' => 'required',
            'barangay' => 'required',
            'investigator' => 'required',
            'informant' => 'required',
            'contact' => 'required',
            'relationship' => 'required',
            'placeDescription' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(array('success'=> false, 'messages' =>'Please provide valid inputs'));
        }else{
            try {
                DB::beginTransaction();

                $identifier = SignsSymptoms::where('patient_profile_id', $request['patient_id'])->max('identifier');

                $placeAssign = new PlaceOfAssignment;
                $placeAssign->barangay_id = $request['barangay'];
                $placeAssign->investigator_id = $request['investigator'];
                $placeAssign->investigator_category = $request['category'];
                $placeAssign->description = convertData($request['placeDescription']);
                $placeAssign->assignment_status = 1;
                $changes = $placeAssign->getDirty();
                $placeAssign->save();

                $signs = new SignsSymptoms;
                $signs->place_of_assignments_id = $placeAssign->id;
                $signs->patient_profile_id = $request['patient_id'];
                $signs->fever_degree = empty($request['fever'])? '0':'1';
                $signs->cough = empty($request['cough'])? '0':'1';
                $signs->sore_throat = empty($request['soreThroat'])? '0':'1';
                $signs->colds = empty($request['nasalCongestion'])? '0':'1';
                $signs->shortness_difficulty_of_breathing = empty($request['shortnessOfBreath'])? '0':'1';
                $signs->vomiting = empty($request['vomiting'])? '0':'1';
                $signs->diarrhea = empty($request['diarrhea'])? '0':'1';
                $signs->fatigue_chills = empty($request['fatigue'])? '0':'1';
                $signs->headache = empty($request['headache'])? '0':'1';
                $signs->joint_pains = empty($request['jointPains'])? '0':'1';
                $signs->other_symptoms = convertData($request['otherSysmtoms']);
                $signs->daily_conditions = convertData($request['dailyCondition']);
                $signs->date_of_consultation = Carbon::today();
                $signs->name_of_informant = convertData($request['informant']);
                $signs->relationship = convertData($request['relationship']);
                $signs->relationship_phone_no = $request['contact'];
                $signs->signs_symptoms_status = $request['status'];
                $signs->identifier = ($identifier + 1);
                $changes = array_merge($changes, $signs->getDirty());
                $signs->save();

                DB::commit();

                /* logs */
                action_log('Patient Monitoring Mngt', 'CREATE', array_merge(['id' => $signs->id], $changes));

                return response()->json(array('success'=>true, 'messages'=>'Record successfully saved!'));  
            } catch (\PDOException $e) {
                DB::rollBack();
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
        //
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

    /* for history on patient monitoring */
    public function getMonitoringHistory(Request $request){
        $columns = array(
            0 =>'created_at',
            1 =>'address',
            2 =>'date_of_onset_of_illness',
            3 =>'date_of_admission_consultation'
        );

        $totalData = SignsSymptoms::where('patient_profile_id', '=', $request['patient_id'])->count();

        $totalFiltered = $totalData;

        $limit = ($request->input('length') == -1)? $totalData:$request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = SignsSymptoms::where('patient_profile_id', '=', $request['patient_id']);
        
        if(empty($request->input('search.value')))
        {
            $results = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $placeAssign = PlaceOfAssignment::where('id', '=', $result->place_of_assignments_id)->with('investigator')->first();
                $user = User::where('id', $placeAssign->investigator->user_id)->with('person')->first();

                $nestedData['investigator'] = $user->person->last_name.', '.$user->person->first_name.' '.$user->person->middle_name;
                $nestedData['fever_degree'] = ($result->fever_degree != 0)? "<i class='fa fa-check'></i>":"--";
                $nestedData['cough'] = ($result->cough == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['sore_throat'] = ($result->sore_throat == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['colds'] = ($result->colds == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['shortness_difficulty_of_breathing'] = ($result->shortness_difficulty_of_breathing == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['vomiting'] = ($result->vomiting == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['diarrhea'] = ($result->diarrhea == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['fatigue_chills'] = ($result->fatigue_chills == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['headache'] = ($result->headache == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['joint_pains'] = ($result->joint_pains == 1)? "<i class='fa fa-check'></i>":"--";
                $nestedData['other_symptoms'] = $result->other_symptoms;
                $nestedData['daily_conditions'] = ($result->signs_symptoms_status == '1')? 'ON GOING':(($result->signs_symptoms_status == '2')? 'MONITORING COMPLETED' : 'DECEASED');
                $nestedData['other_symptoms'] = $result->other_symptoms;
                $nestedData['name_of_informant'] = $result->name_of_informant;
                $nestedData['relationship'] = $result->relationship;
                $nestedData['relationship_phone_no '] = $result->relationship_phone_no;
                $nestedData['created_at'] = explode(' ', $result->date_of_consultation)[0];
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

}
