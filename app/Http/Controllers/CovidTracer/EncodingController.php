<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\CovidTracer\Investigator;
use App\CovidTracer\HomeAddress;
use App\CovidTracer\SignsSymptoms;
use App\CovidTracer\PatientProfile;
use App\CovidTracer\PlaceOfAssignment;
use App\CovidTracer\PatientRoot;
use App\CovidTracer\InvestigatorHasPatient;
use App\CovidTracer\SpecimentInformation;
use App\Ecabs\Barangay;
use App\Ecabs\Person;
use App\User;
use DB;
use Validator;
use App\Events\DataTableEvent;
class EncodingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covidtracer/patient_encoding.index', ['title' => "Covid Patient Encoding Management"]);
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
        $rawData = [
            'first_name'=> ' required',
            'last_name'=> ' required',
            'email' => 'required',
            'sex'=> ' required',
            'place_of_interview'=> ' required',
            'home_address'=> ' required',
            'exposure_risk'=> 'required',
            'facility'=> ' required',
        ];

        if($request['travel_his_checkbox'] == '1'){
            $rawData = array_merge($rawData, [ 
                'port_of_exit'=> ' required',
                'airline_sea_vessel'=> ' required',
                'flight_vessel'=> ' required',
                'date_of_departure'=> ' required',
                'date_of_arrival_in_phil'=> ' required',    
            ]);
        }

        $validator=Validator::make($request->all(), $rawData);


        $validatePatientFirstname = PatientProfile::where('first_name', '=', $request['first_name'])->first();
        $validatePatientLastname = PatientProfile::where('last_name', '=', $request['last_name'])->first();
        $validatePatientMiddlename = PatientProfile::where('middle_name', '=', $request['middle_name'])->first();
        $validatePatientDateOfBirth = PatientProfile::where('date_of_birth', '=', $request['date_of_birth'])->first();  
        
        $validatorDuplicate = false;

        if(($validatePatientFirstname && $validatePatientLastname) && ($validatePatientMiddlename && $validatePatientDateOfBirth) )
        {
            $validatorDuplicate = true;
        }
        
        
        // dd($validator->errors());
        if($validatorDuplicate) {
            return response()->json(array('success'=> false, 'error'=>'This person already exist', 'messages'=>'This person already exist!'));
        } else {
            if($validator->fails()) {
                return response()->json(array('success'=> false, 'error'=>'Validation error!', 'messages'=>'Please provide valid inputs!'));
            }
            else {
                try {
                    DB::beginTransaction();
                    // $homeAddress = new HomeAddress;
                    // $placeOfAssignment = new PlaceOfAssignment;//place_of_assignments
                    // $patientRoot = new PatientRoot;//patient_roots
                    // $investigatorHasPatient = new InvestigatorHasPatient;//investigator_has_patients
                    // $patientRoot = new InvestigatorHasPatient;//investigator_has_patients
                    // $signsOfSymtoms = new SignsSymptoms;//signs_symptoms
                    // $specimentInformation = new SpecimentInformation;//speciment_information

                    
                    $patient = new PatientProfile;//patient_profiles
                    //---step 1 profile ($patient)
                    $patient->place_of_interview = convertData($request['place_of_interview']);
                    $patient->first_name=convertData($request['first_name']);//first_name
                    $patient->last_name=convertData($request['last_name']);//last_name
                    $patient->middle_name=convertData($request['middle_name']);//middle_name
                    $patient->affiliation=convertData($request['suffix']);//affiliation
                    $patient->date_of_birth=convertData($request['date_of_birth']);//date_of_birth
                    $patient->gender=convertData($request['sex']);//gender
                    $patient->social_sector=convertData($request['social_sector']);//	social_sector
                    $patient->workplace=convertData($request['work_place']);//workplace
                    $patient->civil_status=convertData($request['civil_status']);//email
                    $patient->nationality=convertData($request['nationality']);//nationality
                    $patient->passport_number=convertData($request['passport_number']);//nationality 
                    $patient->email= $request['email'];//email
                    $patient->employer_name=convertData($request['eoa_employers_name']);//employer_name
                    $patient->occupation=convertData($request['eoa_occupation']);//occupation
                    $patient->place_of_work_overseas=convertData($request['eoa_place_of_work']);
                    
                    //---step 4 TRAVEL HISTORY  ($patient)
                    $patient->port_of_exit=convertData($request['port_of_exit']);//	port_of_exit
                    $patient->airline_sea_vessel=convertData($request['airline_sea_vessel']);//	airline_sea_vessel
                    $patient->flight_vessel_number=convertData($request['flight_vessel']);//flight_vessel_number
                    $patient->date_of_departure=convertData($request['date_of_departure']);//date_of_departure
                    $patient->date_of_arrival_in_philippines=convertData($request['date_of_arrival_in_phil']);//date_of_arrival_in_philippines

                    //---step 6 CLINICAL INFORMATION
                    $patient->clinical_status=convertData($request['clinical_status']);//clinical_status
                    $patient->date_of_onset_of_illness=convertData($request['date_of_onset_of_illness']);//	date_of_onset_of_illness
                    $patient->date_of_admission_consultation=convertData($request['date_of_addmission']);//date_of_admission_consultation
                    $patient->history_of_other_illness=convertData($request['his_of_other_illness']);//history_of_other_illness
                    $patient->chest_xray=$request['chest_xray_done'];//	chest_xray
                    $patient->pregnant=convertData($request['pregnant']);//	chest_xray
                    $patient->cxr_result=convertData($request['cxr_result']);//	chest_xray
                    $patient->other_radiologic_findings=convertData($request['other_radiologic_findings']);//	chest_xray


                    //---step 5 EXPOSURE HISTORY
                    $patient->date_of_contact_if_yes=convertData($request['date_of_contact_with_covid']);//clinical_status
                    $patient->his_of_exposure=convertData($request['history_of_exposure']);//clinical_status
                    $patient->risk_exposure=convertData($request['exposure_risk']);//risk exposure
                    $patient->isolation_facility=convertData($request['facility']);//facility


                    
                    //---step 8 FINAL CLASSIFICATION
                    $patient->classification=convertData($request['classification']);
                    $patient->date_interview=$request['date_of_interview'];
                    $patient->final_classification=convertData($request['final_classification']);
                    $patient->status='1';
                    
                    //---step 9 OUTCOME
                    $patient->outcome=convertData($request['condition_on_discharge']);//	outcome
                    $changes = $patient->getDirty();
                    $patient->save();

                    $placeOfAssignment = new PlaceOfAssignment;//place_of_assignments
                    $placeOfAssignment->barangay_id=$request['place_of_assignment'];//barangay_id
                    $placeOfAssignment->investigator_id= $request['investigator_option'];//investigator_id
                    $placeOfAssignment->investigator_category="BHERT";//investigator_category
                    $placeOfAssignment->description=convertData($request['POA_description']);//investigator_category
                    $placeOfAssignment->assignment_status= '1';//assignment_status
                    $changes = array_merge($changes, $placeOfAssignment->getDirty());
                    $placeOfAssignment->save();


                    //---step 2 addess PHILIPPINE RESIDENCE
                    $homeAddress = new HomeAddress;
                    $homeAddress->patient_profile_id= $patient->id;
                    $homeAddress->house_no=convertData($request['home_address']);//	social_sector
                    $homeAddress->street=convertData($request['street']);//	social_sector
                    $homeAddress->region_country=convertData($request['region_ph']);//	social_sector
                    $homeAddress->province_state=convertData($request['province_ph']);//	social_sector
                    $homeAddress->city_municipality=convertData($request['city_ph']);//	social_sector
                    $homeAddress->barangay=convertData($request['brgy_ph']);//	social_sector
                    $homeAddress->home_office_no=null;//	social_sector
                    $homeAddress->cellphone_no=$request['contact_number'];//	social_sector
                    $homeAddress->category='1';//	category phil residence
                    $changes = array_merge($changes, $homeAddress->getDirty());
                    $homeAddress->save();

                    // //---step 3 addess OVERSEAS EMPLOYMENT ADDRESS (for Overseas Filipino Workers)
                    $homeAddress2 = new HomeAddress;
                    $homeAddress2->patient_profile_id=$patient->id;
                    $homeAddress2->house_no=convertData($request['eoa_home_address']);//	social_sector
                    $homeAddress2->street=convertData($request['eoa_street']);//	social_sector
                    $homeAddress2->region_country=convertData($request['eoa_region']);//	social_sector
                    $homeAddress2->province_state=convertData($request['eoa_province']);//	social_sector
                    $homeAddress2->city_municipality=convertData($request['eoa_city']);//	social_sector
                    $homeAddress2->barangay='';//	social_sector
                    $homeAddress2->home_office_no=convertData($request['eoa_office_phone_number']);//	social_sector
                    $homeAddress2->cellphone_no=convertData($request['eoa_cellphone_number']);//	social_sector
                    $homeAddress2->category = '2';//	category oversease residence
                    $changes = array_merge($changes, $homeAddress2->getDirty());
                    $homeAddress2->save();

                   
                    $signsOfSymtoms = new SignsSymptoms;//signs_symptoms
                    $signsOfSymtoms->place_of_assignments_id=$placeOfAssignment->id;
                    $signsOfSymtoms->patient_profile_id=$patient->id;
                    $signsOfSymtoms->cough=!empty($request['checkbox_cough'])? '1':'0';//	cough
                    $signsOfSymtoms->sore_throat=!empty($request['checkbox_sore_throat'])? '1':'0';//		sore_throat
                    $signsOfSymtoms->colds=!empty($request['checkbox_colds'])? '1':'0';//	colds
                    $signsOfSymtoms->shortness_difficulty_of_breathing = !empty($request['checkbox_shortness_of_breathing'])? '1':'0';//		shortness_difficulty_of_breathing
                    $signsOfSymtoms->vomiting=0;//	vomiting
                    $signsOfSymtoms->diarrhea=0;//		diarrhea
                    $signsOfSymtoms->fatigue_chills=0;//		fatigue_chills
                    $signsOfSymtoms->headache=0;//		headache
                    $signsOfSymtoms->joint_pains=0;//		join_pains
                    $signsOfSymtoms->date_of_consultation = $request['date_of_addmission'];//date of admission
                    $signsOfSymtoms->date_of_discharge = $request['date_of_discharge'];//date of admission
                    $signsOfSymtoms->identifier='1';
                    
                    $signsOfSymtoms->name_of_informant=convertData($request['name_of_imformant']);//	name_of_informant
                    $signsOfSymtoms->relationship=convertData($request['relationship']);//relationship
                    $signsOfSymtoms->relationship_phone_no=convertData($request['outcome_phone_number']);//relationship_phone_no
                    $signsOfSymtoms->other_symptoms=convertData($request['other_symptoms_specify']);//		other_symptoms
                    $signsOfSymtoms->fever_degree=convertData($request['fever_temperature']);//	fever_degree

                    if($request['condition_on_discharge'] == 'Died'){
                        $signsOfSymtoms->signs_symptoms_status= 3;//	died
                    }else{
                        $signsOfSymtoms->signs_symptoms_status= 1;//	ongoing
                    }

                    $changes = array_merge($changes, $signsOfSymtoms->getDirty());
                    $signsOfSymtoms->save();

                    //---step 7 SPECIMEN INFORMATION
                    $arrSpecimenCollected = $request['specimen_collected'];
                    $arrDateCollected = $request['date_collected'];
                    $arrDateSentToRitm = $request['date_sent_to_ritm'];
                    $arrDateReceiveToRitm = $request['date_receive_in_ritm'];
                    $arrVirusIsolationResult = $request['virus_isolation_result'];
                    $arrPCRResult = $request['pcr_result'];

                    $countFields = count($arrSpecimenCollected);

                    for($index = 0; $index < $countFields; $index ++){

                        if($arrSpecimenCollected[$index] != ''){
                            $specimentInformation = new SpecimentInformation;
                            $specimentInformation->patient_profile_id = $patient->id;
                            $specimentInformation->place_of_assignments_id = $placeOfAssignment->id;

                            $specimentInformation->speciment_category =   $arrSpecimenCollected[$index];
                            $specimentInformation->date_collected =  $arrDateCollected[$index];
                            $specimentInformation->date_sent_to_RITM = $arrDateSentToRitm[$index];
                            $specimentInformation->date_received_in_RITM =  $arrDateReceiveToRitm[$index];
                            $specimentInformation->virus_isolation_result = $arrVirusIsolationResult[$index];
                            $specimentInformation->pcr_result =  $arrPCRResult[$index];   
                            $specimentInformation->specimen_information_status =  '1';   
                            $changes = array_merge($changes, $specimentInformation->getDirty());
                            $specimentInformation->save();
                        }
                    }
              
                    DB::commit();
                    
                    /* logs */
                    action_log('Patient Encoding Mngt', 'CREATE', array_merge(['id' => $patient->id], $changes));

                    return response()->json(array('success'=> true, 'messages'=>'Record successfully Saved!'));
                } catch (\PDOException $e) {
                    DB::rollBack();
                    return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
                }
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
        // dd($request['edit_region_country']);
        $rawData = [
            'edit_first_name'=> ' required',
            'edit_last_name'=> ' required',
            'edit_email' => 'required',
            'edit_gender'=> ' required',
            'edit_place_of_assginment_desc'=> ' required',
            'edit_house_no'=> ' required',
            'edit_exposure_risk'=> 'required',
        ];

        $validator=Validator::make($request->all(), $rawData);

        if($validator->fails()) {
            return response()->json(array('success'=> false, 'error'=>'Validation error!', 'messages'=>'Please provide valid inputs!'));
        }
        else {
            try {
                DB::beginTransaction();

                $patient = PatientProfile::findOrFail($request['patient_id']);//patient_profiles
                //---step 1 profile ($patient)
                $patient->place_of_interview = convertData($request['edit_place_of_interview']);
                $patient->first_name=convertData($request['edit_first_name']);//first_name
                $patient->last_name=convertData($request['edit_last_name']);//last_name
                $patient->middle_name=convertData($request['edit_middle_name']);//middle_name
                $patient->affiliation=convertData(($request['edit_affiliation'] == 'N/A')? '':$request['edit_affiliation']);//affiliation
                $patient->date_of_birth=convertData($request['edit_date_of_birth']);//date_of_birth
                $patient->gender=convertData($request['edit_gender']);//gender
                // $patient->social_sector=convertData($request['social_sector']);//	social_sector=============================
                // $patient->workplace=convertData($request['work_place']);//workplace===============================
                $patient->civil_status=convertData($request['edit_civil_status']);//email
                $patient->nationality=convertData($request['edit_nationality']);//nationality
                $patient->passport_number=convertData($request['edit_passport_number']);//nationality 
                $patient->email= $request['edit_email'];//email
                $patient->employer_name=convertData($request['edit_employer_name']);//employer_name
                $patient->occupation=convertData($request['edit_occupation']);//occupation
                $patient->place_of_work_overseas=convertData($request['edit_place_of_work_overseas']);
                
                //---step 4 TRAVEL HISTORY  ($patient)
                $patient->port_of_exit=convertData($request['edit_port_of_exit']);//	port_of_exit
                $patient->airline_sea_vessel=convertData($request['edit_airline_sea_vessel']);//	airline_sea_vessel
                $patient->flight_vessel_number=convertData($request['edit_flight_vessel_number']);//flight_vessel_number
                $patient->date_of_departure=convertData($request['edit_date_of_departure']);//date_of_departure
                $patient->date_of_arrival_in_philippines=convertData($request['edit_date_of_arrival_in_philippines']);//date_of_arrival_in_philippines

                //---step 6 CLINICAL INFORMATION
                $patient->clinical_status=convertData($request['edit_clinical_status']);//clinical_status
                $patient->date_of_onset_of_illness=convertData($request['edit_date_of_onset_of_illness']);//	date_of_onset_of_illness
                $patient->date_of_admission_consultation=convertData($request['edit_date_of_admission_consultation']);//date_of_admission_consultation
                $patient->history_of_other_illness=convertData($request['edit_history_of_other_illness']);//history_of_other_illness
                $patient->chest_xray=$request['edit_chest_xray'];//	chest_xray
                $patient->pregnant=convertData($request['edit_pregnant']);//	chest_xray
                $patient->cxr_result=convertData($request['edit_cxr_result']);//	chest_xray
                $patient->other_radiologic_findings=convertData($request['edit_other_radiologic_findings']);//	chest_xray


                //---step 5 EXPOSURE HISTORY
                $patient->date_of_contact_if_yes=convertData($request['edit_date_of_contact_if_yes']);//clinical_status
                $patient->his_of_exposure=convertData($request['edit_history_of_exposure']);//clinical_status
                $patient->risk_exposure=convertData($request['edit_exposure_risk']);//risk exposure
                $patient->isolation_facility=convertData($request['edit_facility']);//facility
                $patient->date_of_admission_consultation = $request['edit_date_of_admission_consultation'];//date of admission


                
                //---step 8 FINAL CLASSIFICATION
                $patient->classification=convertData($request['edit_classification']);
                $patient->date_interview=$request['edit_date_of_interview'];
                $patient->final_classification=convertData($request['final_classification']);
                
                //---step 9 OUTCOME
                $patient->outcome=convertData($request['outcome']);//	outcome
                $changes = $patient->getDirty();
                $patient->save();

                $placeOfAssignment = PlaceOfAssignment::findOrFail($request['assign_id']);//place_of_assignments
                $placeOfAssignment->barangay_id=$request['edit_place_of_assignment'];//barangay_id
                $placeOfAssignment->investigator_id= $request['edit_investigator_option'];//investigator_id
                $placeOfAssignment->description=convertData($request['edit_place_of_assginment_desc']);//investigator_category
                $placeOfAssignment->save();


                //---step 2 addess PHILIPPINE RESIDENCE
                $homeAddress = HomeAddress::findOrFail($request['address_1_id']);
                $homeAddress->house_no=convertData($request['edit_house_no']);//	social_sector
                $homeAddress->street=convertData($request['edit_street']);//	social_sector
                $homeAddress->region_country=convertData($request['edit_region_country']);//	social_sector
                $homeAddress->province_state=convertData($request['edit_province_state']);//	social_sector
                $homeAddress->city_municipality=convertData($request['edit_city_municipality']);//	social_sector
                $homeAddress->barangay=convertData($request['edit_barangay']);//	social_sector
                $homeAddress->cellphone_no=$request['edit_cellphone_no'];//	social_sector
                $changes = array_merge($changes, $homeAddress->getDirty());
                $homeAddress->save();

                // //---step 3 addess OVERSEAS EMPLOYMENT ADDRESS (for Overseas Filipino Workers)
                $homeAddress2 =  HomeAddress::findOrFail($request['address_2_id']);
                $homeAddress2->house_no=convertData($request['edit_eoa_house_no']);//	social_sector
                $homeAddress2->street=convertData($request['edit_eoa_street']);//	social_sector
                $homeAddress2->region_country=convertData($request['edit_eoa_region_country']);//	social_sector
                $homeAddress2->province_state=convertData($request['edit_eoa_province_state']);//	social_sector
                $homeAddress2->city_municipality=convertData($request['edit_eoa_city_municipality']);//	social_sector
                $homeAddress2->home_office_no=convertData($request['edit_eoa_home_office_no']);//	social_sector
                $homeAddress2->cellphone_no=convertData($request['edit_eoa_cellphone_no']);//	social_sector
                $changes = array_merge($changes, $homeAddress2->getDirty());
                $homeAddress2->save();

                
                $signsOfSymtoms = SignsSymptoms::findOrFail($request['symptoms_id']);//signs_symptoms
                $signsOfSymtoms->cough=!empty($request['edit_checkbox_cough'])? '1':'0';//	cough
                $signsOfSymtoms->sore_throat=!empty($request['edit_checkbox_sore_throat'])? '1':'0';//		sore_throat
                $signsOfSymtoms->colds=!empty($request['edit_checkbox_colds'])? '1':'0';//	colds
                $signsOfSymtoms->shortness_difficulty_of_breathing = !empty($request['edit_checkbox_shortness_of_breathing'])? '1':'0';//		shortness_difficulty_of_breathing
                $signsOfSymtoms->date_of_discharge = $request['edit_date_of_discharge'];//date of admission
                
                $signsOfSymtoms->name_of_informant=convertData($request['edit_name_of_informant']);//	name_of_informant
                $signsOfSymtoms->relationship=convertData($request['edit_relationship']);//relationship
                $signsOfSymtoms->relationship_phone_no=convertData($request['edit_relationship_phone_no']);//relationship_phone_no
                $signsOfSymtoms->other_symptoms=convertData($request['edit_other_symptoms']);//		other_symptoms
                $signsOfSymtoms->fever_degree=convertData($request['edit_fever_degree']);//	fever_degree

                if($request['outcome'] == 'Died'){
                    $signsOfSymtoms->signs_symptoms_status= 3;//	died
                }else{
                    $signsOfSymtoms->signs_symptoms_status= 1;//	ongoing
                }

                $changes = array_merge($changes, $signsOfSymtoms->getDirty());
                $signsOfSymtoms->save();

                //---step 7 SPECIMEN INFORMATION
                $arrSpecimenId = $request['specimen_id'];
                $arrSpecimenCollected = $request['edit_specimen_collected'];
                $arrDateCollected = $request['edit_date_collected'];
                $arrDateSentToRitm = $request['edit_date_sent_to_ritm'];
                $arrDateReceiveToRitm = $request['edit_date_receive_in_ritm'];
                $arrVirusIsolationResult = $request['edit_virus_isolation_result'];
                $arrPCRResult = $request['edit_pcr_result'];

                $countFields = count($arrSpecimenCollected);

                for($index = 0; $index < $countFields; $index ++){

                    if($arrSpecimenCollected[$index] != ''){
                        $specimentInformation = SpecimentInformation::findOrFail($arrSpecimenId[$index]);
                        $specimentInformation->speciment_category =   $arrSpecimenCollected[$index];
                        $specimentInformation->date_collected =  $arrDateCollected[$index];
                        $specimentInformation->date_sent_to_RITM = $arrDateSentToRitm[$index];
                        $specimentInformation->date_received_in_RITM =  $arrDateReceiveToRitm[$index];
                        $specimentInformation->virus_isolation_result = $arrVirusIsolationResult[$index];
                        $specimentInformation->pcr_result =  $arrPCRResult[$index];   
                        $changes = array_merge($changes, $specimentInformation->getDirty());
                        $specimentInformation->save();
                    }
                }
            
                DB::commit();
                
                /* logs */
                action_log('Patient Encoding Mngt', 'CREATE', array_merge(['id' => $patient->id], $changes));

                return response()->json(array('success'=> true, 'messages'=>'Record successfully Saved!'));
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
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

//start of get all investigator
    public function findInvestigator(Request $request)
    {

        $users = Investigator::leftjoin(connectionName().'.users', 'investigators.user_id', 'users.id')
                    ->leftjoin(connectionName().'.people', 'users.person_id', 'people.id')
                    ->select(
                    'investigators.id',
                    'people.first_name',
                    'people.last_name',
                    'people.address'
                    )->where('investigator_status', '=', 1)->where('users.account_status', '=', 1)->get();
         return json_encode($users);
    }
//end get all investigator


//start of get all investigator
public function findBarangay(Request $request)
{
    $barangay = Barangay::all();
     return json_encode($barangay);
}
//end get all investigator

//start of get all profile
    public function findAllProfile(Request $request){
    	$columns = array( 
            0 =>'last_name', 
            1 =>'department',
            2 =>'position',
            3 =>'status',
        );
        $status = 1;
        //datatables total data
        $totalData = User::where('account_status', $status)->where('id', '!=', '1')->count();   
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        
        $query = DB::table('users') 
            ->join('people', 'people.id', '=', 'users.person_id')
            ->where('users.account_status', $status)
            ->where('users.id', '!=', '1');

        
        if(empty($request->input('search.value')))
        {            
            $users = $query
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

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $account = User::where('person_id', $user->id)->first();
    
            	$buttons = ' <a onclick="addProfile('. $account->id .')" class="btn btn-xs btn-warning btn-fill btn-rotate view"><i class="fa fa-plus"></i> Add this profile</a>';
                $fullname = $user->last_name . " ". $user->affiliation . ", ". $user->first_name . " ". $user->middle_name;

                $nestedData['fullname'] = $fullname;
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
//end of get all profile

//start of find people by id
    public function findUserById($id)
    {
        $user = User::join('people', 'people.id', '=', 'users.person_id')
        ->where('users.id',$id)
        ->select(
            'users.id',
            'users.contact_number',
            'users.email',
            'people.first_name',
            'people.last_name',
            'people.middle_name',
            'people.affiliation',
            'people.date_of_birth',
            'people.person_code',
            'people.address',
            'people.civil_status',
            'people.telephone_number',
            'people.religion',
            'people.image',
            'people.gender'
            )->get();
        return json_encode($user);
    }
//start of find people by id



//start of get all patient
public function findAllPatient(Request $request){
    $columns = array( 
        0 =>'last_name', 
        1 =>'department',
        2 =>'position',
        3 =>'status',
    );
    $status = 1;
    //datatables total data
    $totalData = PatientProfile::count();   
    $totalFiltered = $totalData; 

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    
    $query = PatientProfile::where('status', $status);

    
    if(empty($request->input('search.value')))
    {            
        $users = $query
                ->limit($limit)
                 ->orderBy($order,$dir)
                ->get();
    }
    else {
       $search = $request->input('search.value'); 

       $patients = $query->where('last_name', 'LIKE', "%{$search}%")->where('status', $status)
            ->orWhere('first_name', 'LIKE', "%{$search}%")->where('status', $status)
            ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('status', $status)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

        $totalFiltered = $query->count();
    }

    $data = array();
    if(!empty($patients ))
    {
        foreach ($patients  as $patient)
        {
            //$patient = PatientProfile::where('person_id', $patient->id)->first();

            $buttons = ' <a onclick="addProfile('. $patient->id .')" class="btn btn-xs btn-warning btn-fill btn-rotate view"><i class="fa fa-plus"></i> Add this profile</a>';
            $fullname = $patient->last_name . " ". $patient->affiliation . ", ". $patient->first_name . " ". $patient->middle_name;
            $finalClasification = $patient->final_classification;

            $nestedData['fullname'] = $fullname;
            $nestedData['final_classification'] = $finalClasification;
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
//end of get all patient

}
