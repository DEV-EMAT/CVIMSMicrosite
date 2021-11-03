<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\PatientProfile;
use App\CovidTracer\SignsSymptoms;
use App\CovidTracer\HomeAddress;
use App\CovidTracer\PlaceOfAssignment;
use App\CovidTracer\SpecimentInformation;

class PatientProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /* used on patient monitoring */
    public function findall(request $request)
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

        $query = PatientProfile::query();
        
        if(empty($request->input('search.value')))
        {
            $results = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        else {
            $search = $request->input('search.value');

            $results = $query->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")->count();
        }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {

                $suffix =  (!empty($result->affiliation))? " ".$result->affiliation: '';
                $fullname = ucfirst($result->last_name) .$suffix .', '. ucfirst($result->first_name) .' '. ucfirst($result->middle_name);
                $informant = SignsSymptoms::where('patient_profile_id', '=', $result->id)->first();
                $buttons = '<a data-toggle="tooltip" title="Click here to add new Daily Health Status" onclick="monitor(  '. $result->id .', \''. $fullname .'\',\''. $informant->name_of_informant .'_'.$informant->relationship .'_'.$informant->relationship_phone_no .'\')"  class="btn btn-xs btn-warning btn-fill btn-rotate remove"><i class="fa fa-edit"></i> MONITOR</a> <a data-toggle="tooltip" title="Click here to view Patient Monitoring History"  onclick="history('. $result->id .', \''. $fullname .'\')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="fa fa-line-chart"></i> HISTORY</a> <a data-toggle="tooltip" title="Click here to add new History Exposure" onclick="exposure('. $result->id .', \''. $fullname .'\')"  class="btn btn-xs btn-warning btn-fill btn-rotate remove"><i class="fa fa-edit"></i> ADD HISTORY EXPOSURE</a> <a data-toggle="tooltip" title="Click here to view History of Exposure" onclick="history_exposure('. $result->id .', \''. $fullname .'\')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="fa fa-line-chart"></i> EXPOSURE HISTORY</a>';
                $sign_n_syptoms = SignsSymptoms::where('patient_profile_id', '=', $result->id)->where('identifier', '=', SignsSymptoms::where('patient_profile_id', '=', $result->id)->min('identifier'))->first();

                $nestedData['id'] = $result->id;
                $nestedData['fullname'] =  $fullname ;
                $nestedData['dateOnsetOfIllness'] =  $result->date_of_onset_of_illness;
                $nestedData['dateOfAdmissionConsultation'] =  $result->date_of_admission_consultation;
                $nestedData['detailed'] =  $result;
                $nestedData['home_address'] =  HomeAddress::where('patient_profile_id', '=', $result->id)->get();
                $nestedData['sign_n_sypmtoms'] =  $sign_n_syptoms;
                $nestedData['place_of_assignment'] =  PlaceOfAssignment::findOrFail($sign_n_syptoms['place_of_assignments_id']);
                $nestedData['specimen'] =  SpecimentInformation::where('patient_profile_id', '=', $result->id)->get();
                $nestedData['actions'] = $buttons;
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
        $patientProfiles = PatientProfile::findOrFail($id);

        return response()->json($patientProfiles);
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

    
    public function countAll(){
        $patient = PatientProfile::count();

        return response()->json($patient);
    }
}
