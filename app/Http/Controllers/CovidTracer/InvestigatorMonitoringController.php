<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use App\CovidTracer\MonitoringOfInvestigator;
use App\Events\DataTableEvent;


class InvestigatorMonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covidtracer.investigator_monitoring.index',['title' => 'Investigator Monitoring Management']);
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
        $validator = Validator::make($request->all(),[
            'investigator_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'modeOfTranspo' => 'required',
            'placeOfEngagement' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(array('success'=> false, 'messages' => 'Please provide valid inputs!'));
        }else{
            try {
                DB::beginTransaction();

                $monitor = new MonitoringOfInvestigator;
                $monitor->investigator_id = $request['investigator_id'];
                $monitor->date = $request['date'];
                $monitor->time = $request['time'];
                $monitor->mode_of_transportation = convertData($request['modeOfTranspo']);
                $monitor->places_of_engagement = convertData($request['placeOfEngagement']);
                $monitor->remarks = convertData($request['remarks']);
                $changes = $monitor->getDirty();
                $monitor->save();

                DB::commit();

                /* logs */
                action_log('Investigator Monitoring Mngt', 'CREATE', array_merge(['id' => $monitor->id], $changes));

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

    /* for history on investigator monitoring */
    public function getMonitoringHistory(Request $request){
        $columns = array(
            0 =>'created_at',
            // 1 =>'address',
            // 2 =>'date_of_onset_of_illness',
            // 3 =>'date_of_admission_consultation'
        );

        $totalData = MonitoringOfInvestigator::where('investigator_id', '=', $request['investigator_id'])->count();

        $totalFiltered = $totalData;

        $limit = ($request->input('length') == -1)? $totalData:$request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = MonitoringOfInvestigator::where('investigator_id', '=', $request['investigator_id']);
        
        if(empty($request->input('search.value')))
        {
            $results = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                  
        }
        // else {
        //     $search = $request->input('search.value');

        //     $results = $query->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")
        //         ->offset($start)
        //         ->limit($limit)
        //         ->orderBy($order, $dir)
        //         ->get();

        //     $totalFiltered = $query->orWhere('last_name', 'LIKE',"%{$search}%")->orWhere('first_name', 'LIKE',"%{$search}%")->orWhere('middle_name', 'LIKE',"%{$search}%")->count();
        // }

        $data = array();
        if(!empty($results))
        {
            foreach ($results as $result)
            {
                $nestedData['date'] = $result->date;
                $nestedData['time'] = date( 'g:i A', strtotime($result->time));
                $nestedData['place_of_engagement'] = $result->places_of_engagement;
                $nestedData['mode_of_transpo'] = $result->mode_of_transportation;
                $nestedData['remarks'] = $result->remarks;
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
