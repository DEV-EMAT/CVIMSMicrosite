<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Covid19Vaccine\ExportHasPatient;
use App\Covid19Vaccine\ExportSummary;
use App\Covid19Vaccine\HealthFacility;
use App\Covid19Vaccine\UserHasFacility;
use App\Covid19Vaccine\VaccinationMonitoring;
use App\Covid19Vaccine\VaccineCategory;
use App\Covid19Vaccine\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Carbon\Carbon;
use App\Exports\VIMSVASExport;
use App\Exports\VASLineExport;
use App\Exports\PreRegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Gate;
use Response;

class ExportSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.file_export.index', ['title' => 'File Export']);

    }

    public function vasReportView()
    {
        return view('covid19_vaccine.file_export.vasreport', ['title' => 'File Export']);

    }
    
    public function vimsReportView()
    {
        return view('covid19_vaccine.file_export.vimsreport', ['title' => 'File Export']);

    }
    
    public function vasReportPerDate(){
        return view('covid19_vaccine.file_export.vasreport_per_date', ['title' => 'VAS Report']);
    }

    public function findall(Request $request)
    {
        $columns = array(
            0 =>'datetime_requested',
            1 =>'export_type',
            2=> 'remarks',
        );
        
        $list_of_facility = UserHasFacility::select("facility_id")->where('user_has_facilities.user_id', '=', Auth::user()->id)->get()->toArray();

        $list = array_map(function($data){
            return $data["facility_id"];
        },$list_of_facility);
        
        $query = ExportSummary::join(connectionName('covid19vaccine').'.user_has_facilities', 'user_has_facilities.id', '=', 'export_summaries.user_has_facilities_id')
                ->join(connectionName('covid19vaccine').'.health_facilities', 'health_facilities.id', '=', 'user_has_facilities.facility_id')
                ->select(
                    'export_summaries.*',
                    'health_facilities.facility_name'
                )->where('export_type', '=', 'MONITORING')
                ->whereDate('export_summaries.created_at', '2021-10-26 00:00:00')
                ->whereIn('user_has_facilities.facility_id', $list)
                ->orderBy('export_summaries.datetime_requested', 'desc');
                
        $totalData = with(clone $query)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $results = with(clone $query)->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else { 
            $search = $request->input('search.value');

            $results =  with(clone $query)->where('datetime_requested','LIKE',"%{$search}%")
                            ->orWhere('export_type', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = with(clone $query)->where('datetime_requested','LIKE',"%{$search}%")
                             ->orWhere('export_type', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $buttons = "";
                $vaccinatedFirstDose = $vaccinatedSecondDose = 0;
                $vaccinatedPerVaccine = array();
                $dates = array();
                if(Gate::allows('permission', 'exportVASReport')){
                    $buttons = '<a title="Edit" onclick="fileExport(\''.$result->id.'\',\''.$result->export_type.'\',\''.$result->remarks.'\',\''.$result->created_at.'\')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="fa fa-download" aria-hidden="true"></i> File Export</a></button> ';
                }else{
                    $buttons = '<a title="Edit" disabled class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="fa fa-download" aria-hidden="true"></i> File Export</a></button> ';
                }

                if(Gate::allows('permission', 'exportVASReport')){
                    $buttons .= '<a title="Edit" onclick="fileExport(\''.$result->id.'\',\''.$result->export_type.'\',\''.$result->remarks.'\',\''.$result->created_at.'\', \'VASLine\')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="fa fa-download" aria-hidden="true"></i> VASLine Export</a></button> ';
                }else{
                    $buttons .= '<a title="Edit" disabled class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="fa fa-download" aria-hidden="true"></i> VASLine Export</a></button> ';
                }

                $label = '<label class="label label-warning"><b>'.ExportHasPatient::where('export_summary_id', '=', $result->id)->count().'</b> Total Patient/s</label>';
               
                
                $patients = ExportHasPatient::where('export_summary_id', '=', $result->id)->where('status', '=', '1')->count();
                $categories = Category::where('status', '=', '1')->get();
                
                if($patients > 0){
                
                    $query = VaccinationMonitoring::join(connectionName('covid19vaccine'). '.export_has_patients', 'export_has_patients.patient_id', '=', 'vaccination_monitorings.id')
                                ->join(connectionName('covid19vaccine'). '.qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                                ->join(connectionName('covid19vaccine'). '.pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                                ->where('vaccination_monitorings.status', '=', '1')->where('export_has_patients.export_summary_id', '=', $result->id)->where('export_has_patients.status', '=', '1');
                                
                    $vaccineCategories = VaccineCategory::all();
                    $queryDates = with(clone $query)->select('vaccination_monitorings.vaccination_date')->orderBy('vaccination_date', 'ASC')->distinct('vaccination_date')->get();
                    // foreach($queryDates as $date){
                    //     $dates[] = $date->vaccination_date;
                    // }
                    foreach($vaccineCategories as $vaccine){
                        $vaccinatedFirstDose = with(clone $query)->where('vaccine_category_id', '=', $vaccine->id)->where('dosage', '=', '1');
                        $vaccinatedSecondDose = with(clone $query)->where('vaccine_category_id', '=', $vaccine->id)->where('dosage', '=', '2');
                    
                        $vaccinatedPerDate = array();
                        foreach($queryDates as $date){
                            $nestedDates = [];
                            $nestedDates['secondDoseCount'] = with(clone $vaccinatedSecondDose)->where('vaccination_date', '=', $date->vaccination_date)->count();
                            $nestedDates['firstDoseCount'] = with(clone $vaccinatedFirstDose)->where('vaccination_date', '=', $date->vaccination_date)->count();
                            $vaccinatedPerDate[] = $nestedDates;
                        }
                        
                        $nestedData = [];
                        $nestedData['vaccine'] = $vaccine['vaccine_name'];
                        $nestedData['totalCountFirstDose'] = $vaccinatedFirstDose->count();
                        $nestedData['totalCountSecondDose'] = $vaccinatedSecondDose->count();
                        $nestedData['vaccinatedPerDate'] = $vaccinatedPerDate;
                        $vaccinatedPerVaccine[] = $nestedData;
                    }
                    
                    //category per date
                    foreach($queryDates as $date){
                        $categoryCountPerDate = array();
                        foreach($vaccineCategories as $vaccine){
                            foreach($categories as $category){
                                //first dose chart
                                $categoryPerDateFirstDose = with(clone $query)->where('dosage', '=', '1')->where('vaccination_date', '=', $date->vaccination_date)->where('category_id', '=', $category->id);
                                $categoryPerDateSecondDose = with(clone $query)->where('dosage', '=', '2')->where('vaccination_date', '=', $date->vaccination_date)->where('category_id', '=', $category->id);
                                
                                    $categoryPerVaccineFirstDose = with(clone $categoryPerDateFirstDose)->where('vaccine_category_id', '=', $vaccine->id);
                                    $categoryPerVaccineSecondDose = with(clone $categoryPerDateSecondDose)->where('vaccine_category_id', '=', $vaccine->id);
                                    
                                $nestedData = [];
                                $nestedData['vaccine'] = $vaccine->vaccine_name;
                                $nestedData['countPerVaccineFirstDose'] = $categoryPerVaccineFirstDose->count();
                                $nestedData['countPerVaccineSecondDose'] = $categoryPerVaccineSecondDose->count();
                                
                                $nestedData['categoryId'] = $category->id;
                                $nestedData['category'] = $category->category_name;
                                $nestedData['countFirstDose'] = $categoryPerDateFirstDose->count();
                                $nestedData['countSecondDose'] = $categoryPerDateSecondDose->count();
                                $categoryCountPerDate[] = $nestedData;
                            }
                        }
                        
                        $nestedData = [];
                        $nestedData['categoryCountPerDate'] = $categoryCountPerDate;
                        $nestedData['date'] = $date->vaccination_date;
                        $dates[] = $nestedData;
                    }
                }
                
                $dataFirstDose = $dataSecondDose = [];
                foreach($categories as $category){
                
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
                
                $nestedData = [];
                $nestedData['vaccinatedPerVaccine'] = $vaccinatedPerVaccine;
                $nestedData['dates'] = $dates;
                $nestedData['dataFirstDose'] = $dataFirstDose;
                $nestedData['dataSecondDose'] = $dataSecondDose;
                $nestedData['datetime_requested'] ="<b>" . $result->datetime_requested . "</b>";
                $nestedData['total_of_data'] = $label;
                $nestedData['export_type'] = $result->export_type;
                $nestedData['facility_name'] = $result->facility_name;
                $nestedData['generated_by'] = $result->generated_by;
                $nestedData['remarks'] = $result->remarks;
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
    
    
    public function findallVIMSReport(Request $request)
    {
        $columns = array(
            0 =>'datetime_requested',
            1 =>'export_type',
            2=> 'remarks',
        );
        
        // $list_of_facility = UserHasFacility::select("facility_id")->where('user_has_facilities.user_id', '=', Auth::user()->id)->get()->toArray();

        // $list = array_map(function($data){
        //     return $data["facility_id"];
        // },$list_of_facility);
        
        $query = ExportSummary::join(connectionName('covid19vaccine').'.user_has_facilities', 'user_has_facilities.id', '=', 'export_summaries.user_has_facilities_id')
                ->join(connectionName('covid19vaccine').'.health_facilities', 'health_facilities.id', '=', 'user_has_facilities.facility_id')
                ->select(
                    'export_summaries.*',
                    'health_facilities.facility_name'
                )->where('export_type', '=', 'MASTERLIST')
                 ->where('user_has_facilities.vims_ir_credentials', '=', '1')
                // ->whereIn('user_has_facilities.facility_id', $list)
                // ->where('user_has_facilities.user_id', "=" , "21")
                ->orderBy('export_summaries.datetime_requested', 'desc');
                
        $totalData = with(clone $query)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $results = with(clone $query)->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else { 
            $search = $request->input('search.value');

            $results =  with(clone $query)->where('datetime_requested','LIKE',"%{$search}%")
                            ->orWhere('export_type', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = with(clone $query)->where('datetime_requested','LIKE',"%{$search}%")
                             ->orWhere('export_type', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $buttons = "";
                if(Gate::allows('permission', 'exportVIMSIRReport')){
                    $buttons = '<a title="Edit" onclick="fileExport(\''.$result->id.'\',\''.$result->export_type.'\',\''.$result->remarks.'\',\''.$result->created_at.'\')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="fa fa-download" aria-hidden="true"></i> File Export</a></button> ';
                }else{
                    $buttons = '<a title="Edit" disabled class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="fa fa-download" aria-hidden="true"></i> File Export</a></button> ';
                }
                $label = '<label class="label label-warning"><b>'.ExportHasPatient::where('export_summary_id', '=', $result->id)->count().'</b> Total Patient/s</label>';
              
                $nestedData = [];
                $nestedData['datetime_requested'] ="<b>" . $result->datetime_requested . "</b>";
                $nestedData['total_of_data'] = $label;
                $nestedData['export_type'] = $result->export_type;
                $nestedData['facility_name'] = $result->facility_name;
                $nestedData['generated_by'] = $result->generated_by;
                $nestedData['remarks'] = $result->remarks;
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
    
    public function userHealthFacility()
    {
        if(Auth::user()->id != 1){
            $healthFacility = UserHasFacility::join(connectionName('covid19vaccine').'.health_facilities', 'health_facilities.id', '=', 'user_has_facilities.facility_id')
            ->select('user_has_facilities.facility_id', 'health_facilities.facility_name')
            ->where('user_has_facilities.user_id', '=', Auth::user()->id)
            ->get();
        }else{
            $healthFacility = HealthFacility::select('id AS facility_id', 'facility_name')->get();
        }          
        return response::json($healthFacility);
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

    public function downloadFile(Request $request, $id, $type, $filename){

        action_log('CVIMS - File Export', 'DOWNLOAD', ['id' => $id, 'filename' => $filename ]);
        if($request['report'] == 'VASLine'){
            return Excel::download(new VASLineExport($id), $filename);
        }else{
            if($type == 'MONITORING'){
                return Excel::download(new VIMSVASExport($id), $filename);
            }else{
                return Excel::download(new PreRegistrationsExport($id), $filename);
            }
        }
    }


    public function findPerspectiveFacilities() {
        $getFacilities = UserHasFacility::join(connectionName('mysql').'.users', 'user_has_facilities.user_id', '=', 'users.id')
        ->join('health_facilities as health_facilities', 'user_has_facilities.facility_id', '=', 'health_facilities.id')
        ->select(
            'health_facilities.id AS hf_id',
            'health_facilities.facility_name AS hf_name',
            'users.id AS user_id'
        )->where('user_has_facilities.user_id', '=', \Auth::user()->id)->get();

        echo json_encode($getFacilities);
    }

    public function exportByFacility(Request $request){
        $exporter = Auth::user()->person->last_name . ", " . Auth::user()->person->first_name . " " . Auth::user()->person->middle_name;

        // $user_has_facility = UserHasFacility::where('user_id', '=', Auth::user()->id)->where('facility_id', '=', $request['facility_id'])->select('id')->first();
        $user_has_facility = UserHasFacility::join('health_facilities as health_facilities', 'user_has_facilities.facility_id', '=', 'health_facilities.id')->where('user_id', '=', Auth::user()->id)->where('facility_id', '=', $request['facility_id'])->select('user_has_facilities.id', 'health_facilities.facility_name')->first();


        $now = Carbon::now();
        $facility = str_replace(" ", "_", $user_has_facility->facility_name);

        $filename = "VIMS_VAS_".$now."_".$facility.".xlsx";
        return Excel::download(new VIMSVASExport("", $request['facility_id'], $exporter, $user_has_facility->id, $facility), $filename);


        // echo json_encode($request['facility_id']);
        // echo json_encode($exporter);

    }
    
    public function exportVIMSIRReport(Request $request){
        $exporter = Auth::user()->person->last_name . ", " . Auth::user()->person->first_name . " " . Auth::user()->person->middle_name;

        // $user_has_facility = UserHasFacility::where('user_id', '=', Auth::user()->id)->where('facility_id', '=', $request['facility_id'])->select('id')->first();
        $user_has_facility = UserHasFacility::join('health_facilities as health_facilities', 'user_has_facilities.facility_id', '=', 'health_facilities.id')->where('user_id', '=', Auth::user()->id)->where('facility_id', '=', $request['facility_id'])->select('user_has_facilities.id', 'health_facilities.facility_name')->first();


        $now = Carbon::now();
        $facility = str_replace(" ", "_", $user_has_facility->facility_name);

        $filename = "VIMS_IR_".$now."_".$facility.".xlsx";
        //dd($request['facility_id']. "-" . $exporter. "-" .$user_has_facility->id . "-" . $facility . "-" . $filename);
        return Excel::download(new PreRegistrationsExport("", $request['facility_id'], $exporter, $user_has_facility->id, $facility), $filename);

    }
    
    
    public function getVasDate($facility_id)
    {
        $export_summary = array();
        $dates = array();
        $query = VaccinationMonitoring::join(connectionName('covid19vaccine'). '.export_has_patients', 'export_has_patients.patient_id', '=', 'vaccination_monitorings.id')
                    ->join(connectionName('covid19vaccine'). '.export_summaries', 'export_summaries.id', '=', 'export_has_patients.export_summary_id')
                    ->join(connectionName('covid19vaccine'). '.qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                    ->join(connectionName('covid19vaccine'). '.pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                    ->join(connectionName('covid19vaccine'). '.vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
                    ->join(connectionName('covid19vaccine'). '.health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
                    ->select('vaccination_monitorings.vaccination_date')
                    ->where('vaccination_monitorings.status', '=', '1')
                    ->where('export_summaries.export_type', '=', 'MONITORING')
                    ->where('export_has_patients.status', '=', '1');
                    
        if($facility_id){
            $query->where('vaccinators.health_facilities_id', $facility_id);
        }
        
        $patients = $query->get();
        foreach($patients as $patient){
            $date = strtotime($patient->vaccination_date);
            $vaccination_date = date('Y-m-d',$date);
            $dates[] = $vaccination_date;
        } 
        //remove duplicate dates
        $dates = array_unique($dates);
        //sort dates
        usort($dates, function($date1, $date2) {
            return strtotime($date2) - strtotime($date1);
        });
        
        for($index = 0; $index < count($dates); $index++){
            $vaccination_date = strtotime($dates[$index]);
            $dates[$index] = date('M d,Y', $vaccination_date);
        }
        
        return response::json($dates);
    }  
    
    public function findAllVasReportPerDate(Request $request)
    {
        $columns = array(
            0 =>'datetime_requested',
            1 =>'export_type',
            2=> 'remarks',
        );
        
        $query = ExportSummary::join(connectionName('covid19vaccine').'.user_has_facilities', 'user_has_facilities.id', '=', 'export_summaries.user_has_facilities_id')
                ->join(connectionName('covid19vaccine').'.health_facilities', 'health_facilities.id', '=', 'user_has_facilities.facility_id')
                ->select(
                    'export_summaries.*',
                    'health_facilities.facility_name'
                )->where('export_type', '=', 'MONITORING')
                ->orderBy('export_summaries.datetime_requested', 'desc');
        $totalData = with(clone $query)->count();

        $totalFiltered = $totalData;
        
        $results = with(clone $query)
                    ->get();
        
        $data = array();
        $date2 = strtotime($request['date']);
        $date = date("m/d/Y", $date2);
        
        $export_summary = array();
        
        foreach($results as $result){
            array_push($export_summary, $result->id);
        }
        
        if(!empty($results))
        {   
            $dates = array();
            $query = VaccinationMonitoring::join(connectionName('covid19vaccine'). '.export_has_patients', 'export_has_patients.patient_id', '=', 'vaccination_monitorings.id')
                        ->join(connectionName('covid19vaccine'). '.qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                        ->join(connectionName('covid19vaccine'). '.pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                        ->join(connectionName('covid19vaccine'). '.vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
                        ->join(connectionName('covid19vaccine'). '.health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
                        ->where('vaccination_monitorings.status', '=', '1')
                        ->whereIn('export_has_patients.export_summary_id', $export_summary)
                        ->where('vaccination_monitorings.vaccination_date', '=', $date)
                        ->where('vaccinators.health_facilities_id', '=', $request['facility'])
                        ->where('export_has_patients.status', '=', '1');
                        
            $categories = Category::where('status', '=', '1')->get();
            $vaccineCategories = VaccineCategory::where('status', '=', '1')->get();
            $vaccinatedPerCategory = array();
            foreach($categories as $category){
            
                $vaccinatedPerVaccine = array();
                foreach($vaccineCategories as $vaccine){
                    $vaccinatedFirstDose = with(clone $query)->where('vaccine_category_id', '=', $vaccine->id)
                                        ->where('dosage', '=', '1')
                                        ->where('pre_registrations.category_id', $category->id)
                                        ->where('vaccination_date', '=', $date);
                    $vaccinatedSecondDose = with(clone $query)->where('vaccine_category_id', '=', $vaccine->id)
                                        ->where('dosage', '=', '2')
                                        ->where('pre_registrations.category_id', $category->id)
                                        ->where('vaccination_date', '=', $date);
                    
                    $nestedData = [];
                    $nestedData['vaccine'] = $vaccine->vaccine_name;
                    $nestedData['firstDoseCount'] = $vaccinatedFirstDose->count();
                    $nestedData['secondDoseCount'] = $vaccinatedSecondDose->count();
                    $vaccinatedPerVaccine[] = $nestedData;
                }
                
                $nestedData = [];
                $nestedData['category'] = $category->category_name;
                $nestedData['vaccinatedPerVaccine'] = $vaccinatedPerVaccine;
                $vaccinatedPerCategory[] = $nestedData;
            }
            
            $nestedData = [];
            $nestedData['vaccination_date'] = $date;
            $nestedData['vaccinatedPerCategory'] = $vaccinatedPerCategory;
            // $nestedData['status'] = $status;
            $data[] = $nestedData;
            // }
                
        }
        $json_data = array(
            "data"            => $data
        );

        echo json_encode($json_data);
    }


}
