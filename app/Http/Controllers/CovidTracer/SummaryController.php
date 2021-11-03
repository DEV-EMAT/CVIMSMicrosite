<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\PersonCovidSummary;
use App\CovidTracer\PersonCovidStatusBreakdown;
use App\CovidTracer\CovidTracer;
use App\CovidTracer\PersonCovidStatus;
use App\Ecabs\Address;
use App\User;
use DB;
use DateTime;

//ecabs
use App\Ecabs\Person;

class SummaryController extends Controller
{
    public function index()
    {
        return view('covidtracer.summary.index', ['title' => 'Covid Tracer Summary']);
    }

    public function findAllSummaries(Request $request)
    {
        $columns = array( 
            0 =>'id',
        );
        
        $totalData = PersonCovidSummary::where('covid_status', '=', 'Positive')->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $summaries = PersonCovidSummary::where('covid_status', '=', 'Positive')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('info_date_at', 'ASC')
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $summaries = DB::table(connectionName('mysql') . '.people')
                    ->select('people.*', 'person_covid_summaries.*')
                    ->join(connectionName('covid_tracer') . '.person_covid_summaries', 'person_covid_summaries.user_id', '=', 'people.person_code')
                    ->where('person_covid_summaries.covid_status', '=', 'Positive')->where('people.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('person_covid_summaries.covid_status', '=', 'Positive')->where('people.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('person_covid_summaries.covid_status', '=', 'Positive')->where('people.middle_name', 'LIKE', "%{$search}%")
                    ->get();

            $totalFiltered = $summaries->count();
        }

        $data = array();
        if(!empty($summaries))
        {
            foreach ($summaries as $summary)
            {   
                $person = Person::where('person_code', '=', $summary->user_id)->with('user')->first();
                $fullname = $person->last_name . ' ' . $person->affiliation . ', ' . $person->first_name . ' ' . $person->middle_name;

                $category = "from Application";

                if($summary->user_category == 2){
                    $category = "Interview/Encoded";
                }

                $status = $summary->covid_status;
                if($summary->covid_status == "Positive"){
                    $status = "<label style=color:red>".  $summary->covid_status ."</label>"; 
                }
                $buttons = ' <a data-toggle="tooltip" title="Click here to view involved people" onclick="viewInvolved('. $summary->identifier .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> View Involved</a>';
                $buttons .= ' <a data-toggle="tooltip" title="Click here to view tracer search history" onclick="viewHistory('. $summary->identifier .')" class="btn btn-xs btn-primary btn-fill btn-rotate view"><i class="fa fa-line-chart"></i> Tracer History</a>';
                $datetime = explode(" ", $summary->info_date_at);

                $nestedData['fullname'] = $fullname;
                $nestedData['category'] = $category;
                $nestedData['status'] = $status;
                $nestedData['date'] = $datetime[0];
                $nestedData['time'] = date("g:i a", strtotime($datetime[1]));
                $nestedData['buttons'] = $buttons;
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

    public function findAllInvolved(Request $request)
    {
        $columns = array( 
            0 =>'last_name', 
        );
        
        $totalData = PersonCovidSummary::where('identifier', '=', $request['identifier'])->count();
        $totalFiltered = $totalData; 

        $limit = ($request->input('length') == -1)? $totalData:$request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $summaries = PersonCovidSummary::where('identifier', '=', $request['identifier'])
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('info_date_at', 'ASC')
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $summaries = DB::table(connectionName('mysql') . '.people')
                    ->select('people.*', 'person_covid_summaries.*')
                    ->join(connectionName('covid_tracer') . '.person_covid_summaries', 'person_covid_summaries.user_id', '=', 'people.person_code')
                    ->where('people.last_name', 'LIKE', "%{$search}%")->where('identifier', '=', $request['identifier'])
                    ->orWhere('people.first_name', 'LIKE', "%{$search}%")->where('identifier', '=', $request['identifier'])
                    ->orWhere('people.middle_name', 'LIKE', "%{$search}%")->where('identifier', '=', $request['identifier'])
                    ->get();

            $totalFiltered = $summaries->count();
        }
        
        $totalData = count($summaries);
            

        $data = array();
        if(!empty($summaries))
        {
            foreach ($summaries as $summary)
            {   
                $person = Person::where('person_code', '=', $summary->user_id)->with('user')->first();
                $fullname = $person->last_name . ' ' . $person->affiliation . ', ' . $person->first_name . ' ' . $person->middle_name;

                $category = "from Application";

                if($summary->user_category == 2){
                    $category = "Interview/Encoded";
                }

                $status = $summary->covid_status;
                if($summary->covid_status == "Positive"){
                    $status = "<label style=color:red>".  $summary->covid_status ."</label>"; 
                }
                $buttons = ' <a data-toggle="tooltip" title="Click here to send message" onclick="sendSms('. $person->id .')" class="btn btn-xs btn-warning btn-fill btn-rotate view"><i class="ti-envelope"></i> Send Sms</a>';
                $datetime = explode(" ", $summary->info_date_at);

                $user = User::where('person_id', '=', $person->id)->first();

                $searchAddress = Address::findOrFail($person->address_id);

                $address = "";

                if($person->address != null) $address .= $person->address . " ";
                if($searchAddress->barangay != null) $address .= $searchAddress->barangay . ", ";
                if($searchAddress->city != null) $address .= $searchAddress->city . ", ";
                if($searchAddress->province != null) $address .= $searchAddress->province;
                

                $nestedData['fullname'] = $fullname;
                $nestedData['address'] = $address;
                $nestedData['contact'] = $user->contact_number;
                $nestedData['status'] = $status;
                $nestedData['buttons'] = $buttons;
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

    public function findTracerHistory(Request $request)
    {
        $columns = array(
            0 =>'id',
        );

        $covidBreakdown = PersonCovidStatusBreakdown::where('identifier', '=', $request['identifier'])->first();
        $covidStatus = PersonCovidStatus::findOrFail($covidBreakdown->person_covid_status_id);

        $statusTransaction = unserialize($covidStatus->covid_tracer_id);
        $breakdownTransaction = unserialize($covidBreakdown->user_1_status_breakdown);

        $breakdown = array();

        foreach($statusTransaction as $data){
            $breakdown[] = intval($data)        ;
        }

        foreach($breakdownTransaction as $data){
            $breakdown[] = $data;
        }

        //sort to ascending
        sort($breakdown);

        //find positive person
        $summary = PersonCovidSummary::where('identifier', '=', $request['identifier'])->where('covid_status', '=', 'Positive')->first();
        $positivePerson = Person::where('person_code', '=', $summary->user_id)->first();
        $positivePersonCode = $positivePerson->person_code;

        $totalData = CovidTracer::where('status', '1')->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $data = array();

        foreach($breakdown as $id){
            $involve = CovidTracer::findOrFail($id);

            $totalFiltered = $involve->count();

            if(!empty($involve))
            {
                $fullname1 = '';
                $fullname2 = '';
                $establishment = '';
                $contact = '';

                if($involve->transaction_one[0] == 'P'){
                    $person = Person::where('person_code', '=', $involve->transaction_one)->with('user')->first();
                    $user = User::where('person_id', '=', $person->id)->first();
                    $contact = $user->contact_number;
                    if($person->person_code == $positivePersonCode)
                        $fullname1 = "<span style='color:red'>" . $person->last_name.', '.$person->first_name.' '.$person->middle_name . "</span>";
                    else
                        $fullname1 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
                }else{
                    $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                        ->select('establishment_information.business_name', 'establishment_categories.description')
                        ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                        ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_one)
                        ->first();
                    $establishment = $est->business_name . "(" . $est->description . ")";
                }
                
                if($involve->transaction_two[0] == 'P'){
                    $person2 = Person::where('person_code', '=', $involve->transaction_two)->with('user')->first();
                    $user = User::where('person_id', '=', $person2->id)->first();
                    $contact = $user->contact_number;

                    if(!empty($fullname1)){
                        $fullname2 = $person2->last_name.', '.$person2->first_name.' '.$person2->middle_name;
                        if($person2->person_code == $positivePersonCode){
                            $fullname2 = "<span style='color:red'>" . $fullname2 . "</span>";
                        }
                    }else{
                        $fullname1 = $person2->last_name.', '.$person2->first_name.' '.$person2->middle_name;
                        if($person2->person_code == $positivePersonCode){
                            $fullname1 = "<span style='color:red'>" . $fullname1 . "</span>";
                        }
                    }
                }else{
                    $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                        ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                        ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_two)
                        ->first();

                    $establishment = $est->business_name . "(" . $est->description . ")";
                }

                $nestedData['name'] = $fullname1;
                $nestedData['establishment'] = (!empty($fullname2))? $fullname2 : $establishment;
                $nestedData['contact'] = $contact;
                $nestedData['date'] = explode(' ', $involve->created_at)[0];
                $nestedData['time'] = date( 'g:i A', strtotime( explode(' ', $involve->created_at)[1]));
                $nestedData['id'] = $involve->id;
                $data[] = $nestedData;
            }
        }

        $final = [];
        foreach (array_unique($data, SORT_REGULAR) as $value) {
            $final[] = $value;
        }
        
        $pagedArray = array_slice($final, $start, ($limit == "-1")? count($final):$limit);

        $totalData = count($final);
        $totalFiltered = count($final);

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $pagedArray
        );
        echo json_encode($json_data);
    }

    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
