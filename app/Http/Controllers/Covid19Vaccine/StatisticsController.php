<?php

namespace App\Http\Controllers\covid19Vaccine;
use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\QualifiedPatient;
use App\Covid19Vaccine\VaccinationMonitoring;
use App\Covid19Vaccine\VaccineCategory;
use App\Covid19Vaccine\Category;
use App\Covid19Vaccine\HealthFacility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.statistics.dashboard',['title' => "Vaccination Statistics"]);
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

    public function getStatistics(){
        $timezone = date_default_timezone_get();
        $date = Carbon::today()->timezone($timezone);
        $dateToday = date("M d, Y", strtotime($date));
        
        $preRegisteredToday = PreRegistration::whereDate('created_at', $date = Carbon::today()->timezone($timezone))->count();
        
        $preregisteredCounter = PreRegistration::count();
        $evaluatedCounter = QualifiedPatient::count();
        $vaccinatedCounterFirstDose = VaccinationMonitoring::where('dosage', '=', '1')->where('status', '=', '1')->distinct('qualified_patient_id')->count();
        $vaccinatedCounterSecondDose = VaccinationMonitoring::where('dosage', '=', '2')->where('status', '=', '1')->distinct('qualified_patient_id')->count();
        $healthFacility = HealthFacility::count();
        $data = ['evaluatedCounter'=> $evaluatedCounter, 'preregisteredCounter'=> $preregisteredCounter, 'vaccinatedCounterFirstDose'=> $vaccinatedCounterFirstDose, 'vaccinatedCounterSecondDose' => $vaccinatedCounterSecondDose, 'healthFacility'=> $healthFacility, 'preRegisteredToday' => $preRegisteredToday, 'dateToday' => $dateToday];
        return response::json($data);
    }

    public function getStatisticsPerBarangay(){
        $query = VaccinationMonitoring::join('qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
        ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
        ->join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
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
            'barangays.barangay'
        );
        
        $BACLARAN = with(clone $query)->where('barangays.id', '=', 1)->orWhere('barangays.id', '=', 2)->distinct('qualified_patient_id')->count();
        $BANAYBANAY =  with(clone $query)->where('barangays.id', '=', 3)->orWhere('barangays.id', '=', 4)->distinct('qualified_patient_id')->count();
        $BANLIC =  with(clone $query)->where('barangays.id', '=', 5)->distinct('qualified_patient_id')->count();
        $BUTONG =  with(clone $query)->where('barangays.id', '=', 6)->distinct('qualified_patient_id')->count();
        $BIGAA =  with(clone $query)->where('barangays.id', '=', 7)->distinct('qualified_patient_id')->count();
        $CASILE =   with(clone $query)->where('barangays.id', '=', 8)->distinct('qualified_patient_id')->count();
        $GULOD =  with(clone $query)->where('barangays.id', '=', 9)->distinct('qualified_patient_id')->count();
        $MAMATID =  with(clone $query)->where('barangays.id', '=', 10)->orWhere('barangays.id', '=', 11)->distinct('qualified_patient_id')->count();
        $MARINIG =  with(clone $query)->where('barangays.id', '=', 12)->orWhere('barangays.id', '=', 13)->orWhere('barangays.id', '=', 14)->orWhere('barangays.id', '=', 15)->distinct('qualified_patient_id')->count();
        $NIUGAN =  with(clone $query)->where('barangays.id', '=', 16)->distinct('qualified_patient_id')->count();
        $PITTLAND =  with(clone $query)->where('barangays.id', '=', 17)->distinct('qualified_patient_id')->count();
        $PULO =  with(clone $query)->where('barangays.id', '=', 18)->distinct('qualified_patient_id')->count();
        $SALA =  with(clone $query)->where('barangays.id', '=', 19)->distinct('qualified_patient_id')->count();
        $SAN_ISIDRO =  with(clone $query)->where('barangays.id', '=', 20)->distinct('qualified_patient_id')->count();
        $DIEZMO =  with(clone $query)->where('barangays.id', '=', 21)->distinct('qualified_patient_id')->count();
        $BARANGAY_UNO =  with(clone $query)->where('barangays.id', '=', 22)->distinct('qualified_patient_id')->count();
        $BARANGAY_DOS =  with(clone $query)->where('barangays.id', '=', 23)->distinct('qualified_patient_id')->count();
        $BARANGAY_TRES =  with(clone $query)->where('barangays.id', '=', 24)->distinct('qualified_patient_id')->count();
        
        $data = array("data" =>
            array(array("barangay" => "BACLARAN", "vaccinated" =>  $BACLARAN),
            array("barangay" => "BANAYBANAY", "vaccinated" =>  $BANAYBANAY),
            array("barangay" => "BANLIC", "vaccinated" =>  $BANLIC),
            array("barangay" => "BUTONG", "vaccinated" =>  $BUTONG),
            array("barangay" => "BIGAA", "vaccinated" =>  $BIGAA),
            array("barangay" => "CASILE", "vaccinated" =>  $CASILE),
            array("barangay" => "GULOD", "vaccinated" =>  $GULOD),
            array("barangay" => "MAMATID", "vaccinated" =>  $MAMATID),
            array("barangay" => "MARINIG", "vaccinated" =>  $MARINIG),
            array("barangay" => "NIUGAN", "vaccinated" =>  $NIUGAN),
            array("barangay" => "PITTLAND", "vaccinated" =>  $PITTLAND),
            array("barangay" => "PULO", "vaccinated" =>  $PULO),
            array("barangay" => "SALA", "vaccinated" =>  $SALA),
            array("barangay" => "SAN_ISIDRO", "vaccinated" =>  $SAN_ISIDRO),
            array("barangay" => "DIEZMO", "vaccinated" =>  $DIEZMO),
            array("barangay" => "BARANGAY_UNO", "vaccinated" =>  $BARANGAY_UNO),
            array("barangay" => "BARANGAY_DOS", "vaccinated" =>  $BARANGAY_DOS),
            array("barangay" => "BARANGAY_TRES", "vaccinated" =>  $BARANGAY_TRES),
        ));
        return response::json($data);
    }
    
    public function getReports(Request $request){
        $reports = array();
        $vaccinatedPerVaccine = array();
        $vaccinatedPerCategory = array();
        $dataPreRegistered = array();
        $nestedDataPerVaccine = array();
        
        $categories = Category::all();
        
        $query = PreRegistration::where('status','=', '1');
        
        if(!empty($request['dateFrom']) && !empty($request['dateTo'])){
            
            $search_from = date("Y-m-d", strtotime($request['dateFrom'])) . " 00:00:00";
            $search_to = date("Y-m-d", strtotime($request['dateTo'])) . " 00:00:00";

            $query->whereBetween('pre_registrations.created_at', [$search_from, $search_to]);
        }
        
        $query = PreRegistration::join(connectionName('covid19vaccine'). '.qualified_patients', 'qualified_patients.registration_id', '=', 'pre_registrations.id')
        ->join(connectionName('covid19vaccine'). '.vaccination_monitorings', 'vaccination_monitorings.qualified_patient_id', '=', 'qualified_patients.id')
        ->join(connectionName('covid19vaccine'). '.vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
        ->join(connectionName('covid19vaccine'). '.health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
        ->where('vaccination_monitorings.status', '=', '1')->where('qualified_patients.status', '=', '1');
 
        if(!empty($request['dateFrom']) && !empty($request['dateTo'])){
            
            // $search_from = date("Y-m-d", strtotime($request['dateFrom'])) . " 00:00:00";
            // $search_to = date("Y-m-d", strtotime($request['dateTo'])) . " 23:59:59";
            
            $search_from = date("m/d/Y", strtotime($request['dateFrom']));
            $search_to = date("m/d/Y", strtotime($request['dateTo']));
            $query->whereBetween('vaccination_monitorings.vaccination_date', [$search_from, $search_to]);
        }
        
        $vaccineCategories = VaccineCategory::all();
        foreach($vaccineCategories as $vaccine){
            $vaccinatedFirstDose = with(clone $query)->where('vaccine_category_id', '=', $vaccine->id)->where('dosage', '=', '1');
            $vaccinatedSecondDose = with(clone $query)->where('vaccine_category_id', '=', $vaccine->id)->where('dosage', '=', '2');
            
            //search specific vaccine
            foreach($categories as $category){
                $firstDoseQuery = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', $category->id)->distinct('qualified_patient_id');
                $secondDoseQuery = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', $category->id)->distinct('qualified_patient_id');
                
                if($request['vaccine'] && $request['vaccine'] != 0){
                    $firstDoseQuery->where('vaccine_category_id', '=', $request['vaccine']);
                    $secondDoseQuery->where('vaccine_category_id', '=', $request['vaccine']);
                }
                
                if($request['healthFacility'] && $request['healthFacility'] != 0){
                    $firstDoseQuery->where('health_facilities_id', '=', $request['healthFacility']);
                    $secondDoseQuery->where('health_facilities_id', '=', $request['healthFacility']);
                }
                
                $nestedData = [];
                $nestedData['category'] = $category->category_name;
                $nestedData['firstDoseCount'] = $firstDoseQuery->count(); 
                $nestedData['secondDoseCount'] = $secondDoseQuery->count(); 
                $nestedDataPerVaccine[] = $nestedData;
            }
            
            $nestedData = [];
            $nestedData['vaccinatedCategoryAndVaccine'] = $nestedDataPerVaccine;
            $nestedData['vaccine'] = $vaccine['vaccine_name'];
            $nestedData['totalCountFirstDose'] = $vaccinatedFirstDose->count();
            $nestedData['totalCountSecondDose'] = $vaccinatedSecondDose->count();
            $vaccinatedPerVaccine[] = $nestedData;
        }
        
        
        foreach($categories as $category){
            //pre registered chart
            $preRegistered = PreRegistration::where('category_id', '=', $category->id);
            $nestedData = [];
            $nestedData['category'] = $category->category_name;
            $nestedData['count'] = $preRegistered->count();
            $dataPreRegistered[] = $nestedData;
        
            //first dose chart
            $vaccinatedFirstDose = with(clone $query)->where('dosage', '=', '1')->where('category_id', '=', $category->id)->distinct('qualified_patient_id');
            $nestedData = [];
            $nestedData['category'] = $category->category_name;
            $nestedData['count'] = $vaccinatedFirstDose->count();
            $dataFirstDose[] = $nestedData;
            
            //second dose chart
            $vaccinatedFirstDose = with(clone $query)->where('dosage', '=', '2')->where('category_id', '=', $category->id)->distinct('qualified_patient_id');
            $nestedData = [];
            $nestedData['category'] = $category->category_name;
            $nestedData['count'] = $vaccinatedFirstDose->count();
            $dataSecondDose[] = $nestedData;
        }
        
        return response::json(array('preRegistered' => $dataPreRegistered, 'firstDose' => $dataFirstDose, 'secondDose' => $dataSecondDose, 'vaccinatedPerVaccine' => $vaccinatedPerVaccine));
    }
}
