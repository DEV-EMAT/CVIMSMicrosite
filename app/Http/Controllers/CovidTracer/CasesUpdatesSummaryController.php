<?php

namespace App\Http\Controllers\CovidTracer;

use App\Ecabs\Barangay;
use App\CovidTracer\CasesUpdateSummary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Gate;
use DB;
use App\Events\DataTableEvent;

class CasesUpdatesSummaryController extends Controller
{
    public function index()
    {
        return view('covidtracer.covid_cases_updates.index', ['title' => 'Covid-19 Cases Management']);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this -> validate ($request, [
            // 'barangay'=>'required',
            // 'newCases'=>'required',
            // 'recovered'=>'required',
            // 'deceased'=>'required',
            // 'suspected'=>'required',
            // 'bjmp'=>'required',
            // 'probable'=>'required',
        ]);

        try {
            DB::beginTransaction();

            $activeCases = $confirmedCases = $recovered = $deceased = 0;
            $identifier = 1;
            
            $lastUpdate = CasesUpdateSummary::whereRaw('identifier = (select max(`identifier`) from cases_update_summaries)')->first();
            if($lastUpdate){
                $identifier = $lastUpdate->identifier + 1;
            }

            $barangays = Barangay::all();
            foreach($barangays as $barangay){
                if($request['newCases'.$barangay->id] > 0 || $request['recovered'.$barangay->id] > 0 || $request['deceased'.$barangay->id] > 0 || $request['suspect'.$barangay->id] > 0 || $request['probable'.$barangay->id] > 0 || $request['bjmp'.$barangay->id] > 0){
                    

                    $newCases = $recovered = $deceased = $suspect = $probable = $bjmp  = $active = $confirmed = 0;

                    if($request['newCases'.$barangay->id]){
                        $newCases = $request['newCases'.$barangay->id];
                    }
                    if($request['recovered'.$barangay->id]){
                        $recovered = $request['recovered'.$barangay->id];
                    }
                    if($request['deceased'.$barangay->id]){
                        $deceased = $request['deceased'.$barangay->id];
                    }
                    if($request['suspect'.$barangay->id]){
                        $suspect = $request['suspect'.$barangay->id];
                    }
                    if($request['probable'.$barangay->id]){
                        $probable = $request['probable'.$barangay->id];
                    }
                    if($request['bjmp'.$barangay->id]){
                        $bjmp = $request['bjmp'.$barangay->id];
                    }

                    if($lastUpdate){
                        $update = CasesUpdateSummary::where('barangay_id', '=', $barangay->id)->orderBy('identifier', 'desc')->first();
                        if(!empty($update)){
                            $active = ($update->active_cases + $newCases + $bjmp) - ($recovered + $deceased);
                            $confirmed = $update->confirmed_cases + $newCases + $bjmp;
                        }
                        else{
                            $active = $newCases;
                            $confirmed = $newCases;
                        }
                    }
                    else{
                        $active = $newCases;
                        $confirmed = $newCases;
                    }
                
                    $casesUpdate = new CasesUpdateSummary;
                    $casesUpdate->barangay_id = $barangay->id;
                    $casesUpdate->new_cases = $newCases;
                    $casesUpdate->recovered = $recovered;
                    $casesUpdate->active_cases = $active;
                    $casesUpdate->confirmed_cases = $confirmed;
                    $casesUpdate->deceased = $deceased;
                    $casesUpdate->suspected_cases = $suspect;
                    $casesUpdate->bjmp_confirmed_cases = $bjmp;
                    $casesUpdate->probable_cases = $probable;
                    $casesUpdate->identifier = $identifier;
                    $casesUpdate->status = '1';
                    $changes = $casesUpdate->getDirty();
                    $casesUpdate->save();
                }                
            }

            DB::commit();

            /* logs */
            action_log('Case update summary', 'CREATE', array_merge(['id' => $casesUpdate->id], $changes));

            // event(new DataTableEvent(true));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function show(Request $request, $date_from)
    {
        $columns = array( 
            0 =>'barangay', 
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $updates = CasesUpdateSummary::whereDate('created_at', '=', $date_from)->get();
        
        if($request['date_to']){
            $updates = CasesUpdateSummary::whereBetween('created_at', [$date_from . ' 00:00:00', $request["date_to"] . ' 23:59:59'])->get();
        }
        
        $updatesBarangays = array();    
        foreach($updates as $update){
            $updatesBarangays[] = $update->barangay_id;
        }

        $barangays = [];
        if(count($updatesBarangays) > 0){
            foreach (array_unique($updatesBarangays, SORT_REGULAR) as $value) {
                $barangays[] = $value;
            }
        }

        // $newCases = $activeCases = $confirmedCases = $recovered = $deceased = $suspected = $probable = $bjmp = 0;       
        $data = array();
        if($updates){
            //get all barangay
            foreach($updates as $update){
                $updatesBarangays[] = $update->barangay_id;
            }
    
            $barangays = [];
            foreach (array_unique($updatesBarangays, SORT_REGULAR) as $value) {
                $barangays[] = $value;
            }

            
            foreach($barangays as $barangay){
                $newCases = $activeCases = $confirmedCases = $recovered = $deceased = $suspected = $probable = $bjmp = 0; 
                foreach($updates as $update){
                    if($update->barangay_id == $barangay){
                        $newCases += $update->new_cases;
                        $confirmedCases = $update->confirmed_cases;
                        $recovered += $update->recovered;
                        $deceased += $update->deaceased;
                        $suspected += $update->suspected_cases;
                        $probable += $update->probable_cases;
                        $bjmp += $update->bjmp_confirmed_cases;
                        $activeCases = $newCases - ($recovered + $deceased);
                    }
                }

                $barangayName = Barangay::findOrFail($barangay); 
                $nestedData['barangay'] = $barangayName->barangay;
                $nestedData['newCases'] = "<label class='label label-info' style='font-size:16px'>" . $newCases . "</label>";
                $nestedData['confirmedCases'] = "<label class='label label-info' style='font-size:16px'>" . $confirmedCases . "</label>";
                $nestedData['recovered'] = "<label class='label label-success' style='font-size:16px'>" . $recovered . "</label>";
                $nestedData['deceased'] = "<label class='label label-default' style='font-size:16px'>" . $deceased . "</label>";
                $nestedData['suspected'] ="<label class='label label-primary' style='font-size:16px'>" . $suspected . "</label>";
                $nestedData['probable'] = "<label class='label label-primary' style='font-size:16px'>" . $probable . "</label>";
                $nestedData['bjmp'] = "<label class='label label-warning' style='font-size:16px'>" . $bjmp . "</label>";
                $nestedData['activeCases'] = "<label class='label label-danger' style='font-size:16px'>" . $activeCases . "</label>";
                $data[] = $nestedData;
            }
        }
        
        $totalData =   $data;
        $totalFiltered = $totalData; 
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
            
        echo json_encode($json_data); 
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

    public function findAll(Request $request)
    {
        $columns = array( 
            0 =>'barangay', 
        );

        $totalData = Barangay::where('status', '=', '1')->where('id', '!=', '1')->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $barangays = Barangay::where('id', '!=', '1')
                        ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = Barangay::where('barangay','LIKE',"%{$search}%")->where('status', '=', '1');

            $barangays =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }
        $data = array();
        $overallSuspect = $overallProbable = $overallConfirmed = $overallActive = $overallRecovered = $overallDeceased = $overallBjmp = $overallNewCases = 0;
        
        if(!empty($barangays))
        {
            foreach ($barangays as $barangay)
            {  
                $suspect = '';
                $probable = '';
                $active = '';
                
                $activeCases = $confirmedCases = $totalSuspect = $totalProbable = $totalRecovered = $totalDeceased = $totalBjmp = $newCases = $bjmpCases = 0;
                
                $query = CasesUpdateSummary::where('barangay_id', '=', $barangay->id);
                
                if($request['date_to']){
                    $query->whereBetween('created_at', [$request["date_from"] . ' 00:00:00', $request["date_to"] . ' 23:59:59']);
                }else if($request['date_from']){
                    $query->whereDate('created_at', '=', $request["date_from"]);
                }
                $barangayCases = $query->get();

                if($barangayCases){
                    foreach($barangayCases as $barangayCase){
                        $suspect = $barangayCase->suspected_cases;
                        $probable = $barangayCase->probable_cases;

                        $newCases += $barangayCase->new_cases;
                        $confirmedCases += $barangayCase->new_cases + $barangayCase->bjmp_confirmed_cases;
                        $totalSuspect += $barangayCase->suspected_cases;
                        $totalProbable += $barangayCase->probable_cases;
                        $totalRecovered += $barangayCase->recovered;
                        $totalDeceased += $barangayCase->deceased;
                        $totalBjmp += $barangayCase->bjmp_confirmed_cases;
                    }
                    $activeCases = $confirmedCases - ($totalRecovered + $totalDeceased);
                }

                if($request['action'] == "addUpdate"){
                    $totalSuspect = '<input type="number" value="0" min="0" id="suspect' . $barangay->id . '">';
                    $totalProbable = '<input type="number" value="0" min="0" id="probable' . $barangay->id . '">';
                    $totalRecovered = '<input type="number" value="0" min="0" id="recovered' . $barangay->id . '">';
                    $totalDeceased = '<input type="number" value="0" min="0" id="deceased' . $barangay->id . '">';
                    $newCases = '<input type="number" value="0" min="0" id="cases' . $barangay->id . '">';
                    $totalBjmp = '<input type="number" value="0" min="0" id="bjmp' . $barangay->id . '">';
                    // $confirmedCases = '<input type="text" value="0" min="0">';
                }
                
                $nestedData['id'] = $barangay->id;
                $nestedData['barangay'] = $barangay->barangay;

                $nestedData['suspect'] = "<label class='label label-primary' style='font-size:16px'>" . $totalSuspect . "</label>";
                $nestedData['probable'] = "<label class='label label-primary' style='font-size:16px'>" . $totalProbable . "</label>";
                $nestedData['recovered'] = "<label class='label label-success' style='font-size:16px'>" . $totalRecovered . "</label>";
                $nestedData['deceased'] = "<label class='label label-default' style='font-size:16px'>" . $totalDeceased . "</label>";
                $nestedData['bjmp'] = "<label class='label label-warning' style='font-size:16px'>" . $totalBjmp . "</label>";
                $nestedData['active'] = "<label class='label label-danger' style='font-size:16px'>" . $activeCases . "</label>";
                $nestedData['confirmed'] = "<label class='label label-info' style='font-size:16px'>" . $confirmedCases . "</label>";
                $nestedData['newCases'] = "<label class='label label-primary' style='font-size:16px'>" . $newCases . "</label>";

                $data[] = $nestedData;
                
                $overallNewCases += $newCases;
                $overallConfirmed += $confirmedCases;
                $overallSuspect += $totalSuspect;
                $overallProbable += $totalProbable;
                $overallRecovered += $totalRecovered;
                $overallDeceased += $totalDeceased;
                $overallBjmp += $totalBjmp;
                $overallActive = $overallConfirmed - ($overallRecovered + $overallDeceased);
            };
        }
        $nestedData['barangay'] = "<h5><b>TOTAL</b></h5>";
        $nestedData['suspect'] = "<b>" . $overallSuspect . "</b>";
        $nestedData['probable'] = "<b>" . $overallProbable . "</b>";
        $nestedData['recovered'] = "<b>" . $overallRecovered . "</b>";
        $nestedData['deceased'] = "<b>" . $overallDeceased . "</b>";
        $nestedData['bjmp'] = "<b>" . $overallBjmp . "</b>";
        $nestedData['newCases'] = "<b>" . $overallNewCases . "</b>";
        $nestedData['confirmed'] = "<b>" . $overallConfirmed . "</b>";
        $nestedData['active'] = "<b>" . $overallActive . "</b>";
        $data[] = $nestedData;
          
        
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
            
        echo json_encode($json_data); 
    }

    public function  getAllCovidCases(){
        $barangays = Barangay::where('id', '!=', '1')->get();
        
        $cases = CasesUpdateSummary::all();
        $data = array();
        $overallSuspect = $overallProbable = $overallConfirmed = $overallActive = $overallRecovered = $overallDeceased = $overallBjmp = 0;
        
        if(!empty($barangays))
        {
            foreach ($barangays as $barangay)
            {  
                $suspect = '';
                $probable = '';
                $active = '';
                
                $activeCases = $confirmedCases = $totalSuspect = $totalProbable = $totalRecovered = $totalDeceased = $totalBjmp = $newCases = $bjmpCases = $dates = 0;
                
                $newCases = $newRecovered = $newDeceased = $newSuspect = $newProbable = $newBjmp = 0;

                $barangayCases = CasesUpdateSummary::where('barangay_id', '=', $barangay->id)->get();
                if($barangayCases){
                    foreach($barangayCases as $barangayCase){
                        $suspect = $barangayCase->suspected_cases;
                        $probable = $barangayCase->probable_cases;

                        $confirmedCases += $barangayCase->new_cases;
                        $totalSuspect += $barangayCase->suspected_cases;
                        $totalProbable += $barangayCase->probable_cases;
                        $totalRecovered += $barangayCase->recovered;
                        $totalDeceased += $barangayCase->deceased;
                        $totalBjmp += $barangayCase->bjmp_confirmed_cases;
                    }
                    $activeCases = $confirmedCases - ($totalRecovered + $totalDeceased);
                }

                //get latest
                $latestDate = $latestTime = today();
                $latestUpdate = CasesUpdateSummary::whereRaw('identifier = (select max(identifier) from cases_update_summaries)')->get();
                if($latestUpdate){
                    foreach($latestUpdate as $latest){  
                        if($latest->barangay_id == $barangay->id){
                            $newCases += $latest->new_cases;
                            $newRecovered += $latest->recovered;
                            $newDeceased += $latest->deceased;

                            $newSuspect += $latest->suspected_cases;
                            $newProbable += $latest->probable_cases;
                            $newBjmp += $latest->bjmp_confirmed_cases;
                        }
                        $latestDate = explode(' ', $latest->created_at)[0];
                        $latestTime = date( 'g:i A', strtotime(explode(' ', $latest->created_at)[1]));;
                    }
                }

                $overallConfirmed += $confirmedCases;
                $overallSuspect += $totalSuspect;
                $overallProbable += $totalProbable;
                $overallRecovered += $totalRecovered;
                $overallDeceased += $totalDeceased;
                $overallBjmp += $totalBjmp;
                $overallActive = $overallConfirmed - ($overallRecovered + $overallDeceased);
                $data[] = array(
                    'barangay' => $barangay->barangay,
                    'active' => $activeCases,
                    'recovered' => $totalRecovered,
                    'deceased' => $totalDeceased,
                    'suspected' => $totalSuspect,
                    'probable' => $totalProbable,
                    'newCases' => $newCases,
                    'totalCases' => $confirmedCases,
                    'bjmp' => $totalBjmp,
                    'dates' => $dates,
                    'newCases' =>$newCases,
                    'newRecovered' => $newRecovered,
                    'newDeceased' => $newDeceased,
                    'newConfirmed' => $newCases + $newRecovered + $newDeceased,
                    'newSuspect' => $newSuspect,
                    'newProbable' => $newProbable,
                    'newBjmp' => $newBjmp,
                    'latestDate' => $latestDate,
                    'latestTime' => $latestTime
                );
            };
        }
        
        $totalCase = $activeCase = 0;
        $dataLineGraph = [];
        if(!empty($cases)){
            foreach($cases as $case){
                $date = explode(' ', $case->created_at)[0];

                // $dataLineGraph[$date] = $activeCase;
                if(array_key_exists($date, $dataLineGraph)){
                    $temp = $dataLineGraph[$date];
                    $dataLineGraph[$date] = (int)$temp + (int)$case->active_cases;
                }
                else{
                    $dataLineGraph[$date] = (int)$case->active_cases;
                }
            }
        }

        return response()->json([$data, $dataLineGraph]);
    }
}
