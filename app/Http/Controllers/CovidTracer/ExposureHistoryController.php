<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\ExposureHistory;
use App\CovidTracer\PlaceOfAssignment;
use App\User;
use Validator;
use Response;
use DB;

class ExposureHistoryController extends Controller
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
    public function getMonitoringExposureHistory(Request $request){
        $columns = array(
            0 =>'created_at',
            1 =>'address',
            2 =>'date_of_onset_of_illness',
            3 =>'date_of_admission_consultation'
        );

        $totalData = ExposureHistory::where('patient_profile_id', '=', $request['patient_id'])->count();

        $totalFiltered = $totalData;

        $limit = ($request->input('length') == -1)? $totalData:$request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = ExposureHistory::where('patient_profile_id', '=', $request['patient_id']);
        
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
                $placeAssign = PlaceOfAssignment::where('id', '=', $result->place_of_assignment_id)->with('investigator')->first();
                // dd($result->place_of_assignment_id);
                $user = User::where('id', $placeAssign->investigator->user_id)->with('person')->first();

                $nestedData['investigator'] = $user->person->last_name.', '.$user->person->first_name.' '.$user->person->middle_name;
                $nestedData['date_of_exposure'] = $result->date_of_exposure;
                $nestedData['time_of_exposure'] = $result->time_of_exposure;
                $nestedData['mode_of_transportation'] = $result->mode_of_transportation;
                $nestedData['places_of_engagement'] = $result->places_of_engagement;
                $nestedData['person_enteracted_with'] = $result->person_enteracted_with;
                $nestedData['remarks'] = $result->remarks;
                $nestedData['tracked_status'] = ($result->tracked_status == 'TRACKED')? 'TRACKED':'UNTRACKED';
                $nestedData['tracked_action'] = ($result->tracked_status == 'TRACKED')? '<div style="text-align:center"><a class="btn btn-primary btn-sm btn-fill" onclick="update_tracked('. $result->id .')" ><i class="fa fa-check"></i></a></div>':'<div style="text-align:center"><a class="btn btn-danger btn-sm btn-fill" onclick="update_tracked('. $result->id .')" ><i class="fa fa-times"></i></a></div>';
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

        $validator = Validator::make($request->all(),[
            'patient_exposure_id' => 'required',
            'investigator' => 'required',
            'category' => 'required',
            'barangay' => 'required',
            'placeDescription' => 'required',
            'date' => 'required',
            'time' => 'required',
            'modeOfTranspo' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(array('success'=> false, 'messages' => 'Please provide valid inputs!'));
        }else{
            try {
                DB::beginTransaction();

                $placeAssign = new PlaceOfAssignment;
                $placeAssign->barangay_id = $request['barangay'];
                $placeAssign->investigator_id = $request['investigator'];
                $placeAssign->investigator_category = $request['category'];
                $placeAssign->description = convertData($request['placeDescription']);
                $placeAssign->assignment_status = 1;
                $changes = $placeAssign->getDirty();
                $placeAssign->save();
                

                $tracked = $request['tracked'];
                foreach ($request['fullname'] as $key => $value) {
                    $exposure = new ExposureHistory;
                    $exposure->patient_profile_id = $request['patient_exposure_id'];
                    $exposure->place_of_assignment_id = $placeAssign->id;
                    $exposure->date_of_exposure = $request['date'];
                    $exposure->time_of_exposure = $request['time'];
                    $exposure->mode_of_transportation = convertData($request['modeOfTranspo']);
                    $exposure->places_of_engagement = convertData($request['placeOfEngagement']);
                    $exposure->person_enteracted_with = convertData($value);
                    $exposure->tracked_status = isset($tracked[$key])? 'TRACKED':'UNTRACKED';
                    $exposure->remarks = convertData($request['remarks']);
                    $exposure->exposure_status = '1';
                    $changes = array_merge($changes, $exposure->getDirty());
                    $exposure->save();
                }

               
                DB::commit();

                /* logs */
                action_log('Exposure History', 'Create', array_merge(['id' => $request['patient_exposure_id']], $changes));

                return response()->json(array('success'=>true, 'messages'=>'Record successfully saved!'));  
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    public function toggleTrackedStatus($id){

        try {
            DB::beginTransaction();

            $exposure = ExposureHistory::findOrFail($id);

            if($exposure->tracked_status === 'TRACKED'){
                $exposure->tracked_status = 'UNTRACKED';
            }else{
                $exposure->tracked_status = 'TRACKED';
            }
            $changes = $exposure->getDirty();
            $exposure->save();
            
            DB::commit();

            /* logs */
            action_log('Exposure History', 'Update', array_merge(['id' => $exposure->id], $changes));

            return response()->json(array('success'=>true, 'messages'=>'Record successfully updated!'));  
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
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
}
