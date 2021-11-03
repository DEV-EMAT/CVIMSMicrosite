<?php

namespace App\Http\Controllers\covidtracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\CovidTracer;
use App\CovidTracer\EstablishmentInformation;
use App\CovidTracer\PersonCovidStatus;
use App\CovidTracer\PersonCovidStatusBreakdown;
use App\CovidTracer\PersonCovidSummary;
use App\Ecabs\Address;
use DB;
use Carbon\Carbon;
use App\Events\DataTableEvent;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Writer as Writer;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Session;



/* ecabs model */
use App\ecabs\Person;
use App\User;

class CovidTracerController extends Controller
{
    public function index()
    {
        return view('covidtracer.tracer.index', ['title' => "Covid Tracer"]);
    }

    public function view(){
        return view('covidtracer.tracer.view', ['title' => "Covid Tracer"]);
    }

    public function findall(request $request)
    {

        $columns = array(
            0 =>'id',
            // 1 =>'position'
        );

        $totalData = CovidTracer::where('status', '1')->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = CovidTracer::query();
        $flag = true;

        //name
        if(!empty(request('person_code'))){
            $person_code = request('person_code');
            if(!empty($person_code)){
                $query->where(function ($q) use ($person_code) {
                    $q->where('transaction_one', '=', $person_code)->orWhere('transaction_two', '=', $person_code);
                });
            }
            else{
                $flag = false;
            }
        }

        if(!empty(request('establishment'))){
            $query->where(function ($q) {
                $search = request('establishment');

                $q->where('transaction_one', '=', $search)->orWhere('transaction_two', '=', $search);
            });
        }

        //date
        if(!empty(request('date_from')) && !empty(request('date_to'))){
            $query->where(function ($q) {
                $time_from = !empty(request('time_from'))?request('time_from'):"00:00:00";
                $time_to = !empty(request('time_to'))?request('time_to'):"23:59:59";

                $search_from = request('date_from') . " " . $time_from;
                $search_to = request('date_to') . " " . $time_to;

                // dd($search_to);
                $q->whereBetween('created_at', [$search_from, $search_to]);
            });
        }

        if(!empty(request('search_id'))){
            $search = request('search_id');
            $query->where('id', '!=', $search);
        }

        //if name exists
        if($flag == true){
            $totalFiltered = $query->count();

            $involves = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        }else{
            $involves = "";
        }
        ////////////////

        $data = array();
        if(!empty($involves))
        {
            foreach ($involves as $involve)
            {

                // if(!($involve->transaction_one[0] == 'P' && $involve->transaction_two[0] == 'P')){

                    if($involve->transaction_one[0] == 'P'){
                        $person = Person::where('person_code', '=', $involve->transaction_one)->first();

                        $trans1 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
                        $trans1_code = $involve->transaction_one;
                    }else{
                        $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                            ->select('establishment_information.business_name', 'establishment_categories.description')
                            ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                            ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_one)
                            ->first();
                        $trans1 = $est->business_name;
                        $trans1_code = $involve->transaction_one;
                    }

                    if($involve->transaction_two[0] == 'P'){
                        $person = Person::where('person_code', '=', $involve->transaction_two)->first();

                        $trans2 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
                        $trans2_code = $involve->transaction_two;
                    }else{
                        $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                            ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                            ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_two)
                            ->first();

                        $trans2 = $est->business_name;
                        $trans2_code = $involve->transaction_two;
                    }

                    $nestedData['trans1'] =  $trans1;
                    $nestedData['trans1_code'] =  $trans1_code;
                    $nestedData['trans2'] =  $trans2;
                    $nestedData['trans2_code'] =  $trans2_code;
                    $nestedData['date'] =  explode(' ', $involve->created_at)[0];
                    $nestedData['time'] =  date( 'g:i A', strtotime(explode(' ', $involve->created_at)[1]));
                    $nestedData['id'] = $involve->id;
                    $data[] = $nestedData;
                // }
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

    public function getNameByCode($person_code){
        $person = Person::where('person_code', '=', $person_code)->first();

        return $person->last_name.', '.$person->first_name.' '.$person->middle_name;
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

    public function print_excel($data){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sort = array();
        /* arrange data by name */
        for ($index=0; $index < count($data) ; $index++) {
            $sort[$data[$index]['name']][] = $data[$index];
        }
        $row = 1;
        for ($index=0; $index < count($sort) ; $index++) {
            $name =  array_keys($sort)[$index];
            $initial_ctr = $row;
            $temp = [];
            $sheet->setCellValue('A'. $initial_ctr , $name);
            foreach ($sort[array_keys($sort)[$index]] as $key => $value) {
                if(!empty($value['establishment'])){
                    $temp = $value;
                    $sheet->setCellValue('B'. $row , $value['establishment']);
                    $sheet->setCellValue('C'. $row , $value['contact']);
                    $sheet->setCellValue('D'. $row , $value['date']);
                    $sheet->setCellValue('E'. $row , $value['time']);
                    $row++;
                }
            }
            $sheet->setCellValue('A'. ($initial_ctr + 1) , $temp['address']);
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();
        return "data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
        // $writer = new Xlsx($spreadsheet);
        // $writer->save('helloworld.xlsx');
    }

    // public function format_data($id){
    //     $involve = CovidTracer::findOrFail($id);
    //     if(!empty($involve))
    //     {
    //         $fullname1 = '';
    //         $fullname2 = '';
    //         $establishment = '';
    //         $contact = '';
    //         if($involve->transaction_one[0] == 'P'){
    //             $person = Person::where('person_code', '=', $involve->transaction_one)->with('user')->first();
    //             $user = User::where('person_id', '=', $person->id)->first();
    //             $contact = $user->contact_number;
    //             $fullname1 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
    //         }else{
    //             $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
    //                 ->select('establishment_information.business_name', 'establishment_categories.description')
    //                 ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
    //                 ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_one)
    //                 ->first();
    //             $establishment = $est->business_name . "(" . $est->description . ")";
    //         }
    //         if($involve->transaction_two[0] == 'P'){
    //             $person2 = Person::where('person_code', '=', $involve->transaction_two)->with('user')->first();
    //             $user = User::where('person_id', '=', $person2->id)->first();
    //             $contact = $user->contact_number;
    //             if(!empty($fullname1)){
    //                 $fullname2 = $person2->last_name.', '.$person2->first_name.' '.$person2->middle_name;
    //                 $fullname2 = $fullname2;
    //             }else{
    //                 $fullname1 = $person2->last_name.', '.$person2->first_name.' '.$person2->middle_name;
    //                 $fullname1 = $fullname1;
    //             }
    //         }else{
    //             $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
    //                 ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
    //                 ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_two)
    //                 ->first();
    //             $establishment = $est->business_name . "(" . $est->description . ")";
    //         }

    //         $nestedData['name'] = $fullname1;
    //         $nestedData['establishment'] = (!empty($fullname2))? $fullname2 : $establishment;
    //         $nestedData['contact'] = $contact;
    //         $nestedData['date'] = explode(' ', $involve->created_at)[0];
    //         $nestedData['time'] = date( 'g:i A', strtotime( explode(' ', $involve->created_at)[1]));
    //         $nestedData['id'] = $involve->id;

    //         return $nestedData;
    //     }
    // }

    public function format_data($person_code, $search_from, $search_to){
        $query = CovidTracer::query();
        $query->where(function ($q) use ($person_code) {
            $q->where('transaction_one', '=', $person_code)->orWhere('transaction_two', '=', $person_code);
        });
        $query->where(function ($q) use($search_from, $search_to) {
            $q->whereBetween('created_at', [$search_from, $search_to]);
        });
        $involves = $query->get();
        $data = array();
        foreach ($involves as $involve) {
            if(!empty($involve)){
                $fullname1 = $fullname2 = $establishment = $contact = '';
                if($involve->transaction_one[0] == 'P'){
                    $address = '';
                    $person = Person::where('person_code', '=', $involve->transaction_one)->with('user')->first();
                    $user = User::where('person_id', '=', $person->id)->first();
                    $contact = $user->contact_number;
                    $fullname1 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;

                    $personAddress = Address::where('id', '=', $person->address_id)->first();
                    if($person->address != null) $address .= $person->address . ", ";
                    if($personAddress->barangay != null) $address .= $personAddress->barangay . ", ";
                    if($personAddress->city != null) $address .= $personAddress->city . ", ";
                    if($personAddress->province != null) $address .= $personAddress->province . ", ";
                }else{
                    $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                        ->select('establishment_information.business_name', 'establishment_categories.description')
                        ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                        ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_one)
                        ->first();
                    $establishment = $est->business_name . "(" . $est->description . ")";
                }
                if($involve->transaction_two[0] == 'P'){
                    $address2 = '';
                    $person2 = Person::where('person_code', '=', $involve->transaction_two)->with('user')->first();
                    $user = User::where('person_id', '=', $person2->id)->first();
                    $contact = $user->contact_number;

                    $personAddress2 = Address::where('id', '=', $person2->address_id)->first();
                    if($person2->address != null) $address2 .= $person2->address . ", ";
                    if($personAddress2->barangay != null) $address2 .= $personAddress2->barangay . ", ";
                    if($personAddress2->city != null) $address2 .= $personAddress2->city . ", ";
                    if($personAddress2->province != null) $address2 .= $personAddress2->province;

                    if(!empty($fullname1)){
                        $fullname2 = $person2->last_name.', '.$person2->first_name.' '.$person2->middle_name;
                        $fullname2 = $fullname2;
                    }else{
                        $fullname1 = $person2->last_name.', '.$person2->first_name.' '.$person2->middle_name;
                        $fullname1 = $fullname1;
                    }

                    if($person2->person_code == $person_code){
                        $temp = $fullname2;
                        $fullname2 = $fullname1;
                        $fullname1 = $temp;
                        $address = $address2;
                    }
                }else{
                    $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                        ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                        ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_two)
                        ->first();
                    $establishment = $est->business_name . "(" . $est->description . ")";
                }

                $nestedData['name'] = $fullname1;
                $nestedData['address'] = $address;
                $nestedData['establishment'] = (!empty($fullname2))? $fullname2 : $establishment;
                $nestedData['contact'] = $contact;
                $nestedData['date'] = explode(' ', $involve->created_at)[0];
                $nestedData['time'] = date( 'g:i A', strtotime( explode(' ', $involve->created_at)[1]));
                $nestedData['id'] = $involve->id;
                $data[] = $nestedData;
            }
        }
        return $data;
    }

    //Save Positive Report
    public function store(Request $request)
    {
        // dd(Session::get('generatedId'));
        $searchId = array();
        if(!(empty(Session::get('generatedId')))){
            foreach (array_unique(Session::get('generatedId'), SORT_REGULAR) as $value) {
                $searchId[] = $value;
            }
        }

        try {
            DB::beginTransaction();

            $positivePerson = Person::where('person_code', '=', $request['positive_person_code'])->first();
            $positiveUser = User::where('person_id', '=', $positivePerson->id)->first();

            $personCovidStatus = new PersonCovidStatus;
            $personCovidStatus->covid_tracer_id = serialize($request['search_id']);
            if($request['date_positive'] != ""){
                $personCovidStatus->date_positive = $request['date_positive'];
            }
            $personCovidStatus->date_from = $request['date_from'];
            $personCovidStatus->date_to = $request['date_to'];
            $personCovidStatus->time_from = $request['time_from'];
            $personCovidStatus->time_to = $request['time_to'];
            $personCovidStatus->status = '1';
            $changes = $personCovidStatus->getDirty();
            $personCovidStatus->save();

            $data = array();

            $time_from = !empty(request('time_from'))?request('time_from'):"00:00:00";
            $time_to = !empty(request('time_to'))?request('time_to'):"23:59:59";

            $search_from = request('date_from') . " " . $time_from;
            $search_to = request('date_to') . " " . $time_to;

            foreach($searchId as $id){

                $tracer = CovidTracer::findOrFail($id);
                $query = CovidTracer::query();

                //date
                if(!empty(request('date_from')) && !empty(request('date_to'))){
                    if($tracer->transaction_one[0] == 'E'){
                        $query->where(function ($q) use ($tracer) {
                            $q->where('transaction_one', '=', $tracer->transaction_one)->orWhere('transaction_two', '=', $tracer->transaction_one);
                        });
                    }

                    if($tracer->transaction_two[0] == 'E'){
                        $query->where(function ($q) use ($tracer) {
                            $q->where('transaction_one', '=', $tracer->transaction_two)->orWhere('transaction_two', '=', $tracer->transaction_two);

                        });
                    }

                    $query->whereBetween('created_at', [$search_from, $search_to]);
                }

                $involves = $query->get();
                if(!empty($involves))
                {
                    foreach ($involves as $involve)
                    {
                        $nestedData['id'] = $involve->id;
                        $data[] = $involve->id;
                    }
                }
            }

            $final = [];
            $breakdown = [];
            $summary = [];
            foreach (array_unique($data, SORT_REGULAR) as $value) {
                $final[] = $value;
                $exists = false;
                foreach($request['search_id'] as $id){
                    if($value == $id){
                        $exists = true;
                    }
                }
                if($exists == false){
                    $breakdown[] = $value;
                }
            }
            $identifier = 1;

            $lastUpdate = PersonCovidSummary::whereRaw('identifier = (select max(`identifier`) from person_covid_summaries)')->first();
            if($lastUpdate){
                $identifier = $lastUpdate->identifier + 1;
            }

            // if(empty($final)){
                $personCovidSummary = new PersonCovidSummary;
                $personCovidSummary->user_id = $positivePerson->person_code;
                $personCovidSummary->user_category = 1;
                $personCovidSummary->covid_status = "Positive";
                $personCovidSummary->identifier = $identifier;
                $personCovidSummary->info_date_at = Carbon::now();
                $changes = array_merge($changes, $personCovidSummary->getDirty());
                $personCovidSummary->save();

                $summary[] = $positivePerson->person_code;
            // }
            // else{

            if(!empty($final)){
                foreach($final as $tracerId){
                    $tracer = CovidTracer::findOrFail($tracerId);

                    if($tracer->transaction_one[0] == 'P'){
                        $person = Person::where('person_code', '=', $tracer->transaction_one)->with('user')->first();
                    }

                    if($tracer->transaction_two[0] == 'P'){
                        $person = Person::where('person_code', '=', $tracer->transaction_two)->with('user')->first();
                    }

                    $covidStatus = "Suspected";
                    foreach($request['search_id'] as $searchId){
                        if($person->person_code == $request['positive_person_code'] && $request['date_positive'] != ""){
                            $covidStatus = "Positive";
                        }
                    }

                    if(!(in_array($person->person_code, $summary)) && $covidStatus == "Suspected"){
                        $summary[] = $person->person_code;

                        $personCovidSummary = new PersonCovidSummary;
                        $personCovidSummary->user_id = $person->person_code;
                        $personCovidSummary->user_category = 1;
                        $personCovidSummary->covid_status = $covidStatus;
                        $personCovidSummary->identifier = $identifier;
                        $personCovidSummary->info_date_at = Carbon::now();
                        $changes = array_merge($changes, $personCovidSummary->getDirty());
                        $personCovidSummary->save();

                        //get PersonCovidSummary id of positive
                        if($covidStatus = "Positive"){
                            $personSummaryId = $personCovidSummary->id;
                        }
                    }
                }
            }

            $personCovidBreakdown = new PersonCovidStatusBreakdown;
            $personCovidBreakdown->person_covid_status_id = $personCovidStatus->id;
            $personCovidBreakdown->user_1_id = $positiveUser->id;
            $personCovidBreakdown->user_1_status_breakdown = serialize($breakdown);
            $personCovidBreakdown->identifier = $identifier;
            $changes = array_merge($changes, $personCovidSummary->getDirty());
            $personCovidBreakdown->save();

            $transactions = array();

            //find positive person
            $positivePersonSummary = PersonCovidSummary::whereRaw('identifier = (select max(`identifier`) from person_covid_summaries)')->where('covid_status', '=', 'Positive')->first();

            if($positivePersonSummary){
                $positivePerson = Person::where('person_code', '=', $positivePersonSummary->user_id)->first();
                $transactions[0] = [
                    'name' => $positivePerson->last_name.', '.$positivePerson->first_name.' '.$positivePerson->middle_name,
                    'address' => '',
                    'establishment' => '',
                    'date' => '',
                    'time' => '',
                    'contact' => '',
                ];

                // foreach($request['search_id'] as $id){
                //     $breakdown[] = $id;
                foreach ($summary as $key => $person_code) {
                    $transactions = array_merge($transactions, $this->format_data($person_code, $search_from, $search_to));
                }

                if(!(empty(Session::get('generatedId')))){
                    foreach (array_unique(Session::get('generatedId'), SORT_REGULAR) as $value) {
                        $test[] = $value;
                    }
                }

                $file = $this->print_excel($transactions);
            }

            DB::commit();

            /* logs */
            action_log('Covid Tracer', 'Create', array_merge(['id' => $personCovidStatus->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Save!', 'file' => $file));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }


    public function show($id)
    {

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

    // public function getByCode($code, $search_from, $search_to, $search_id){

    //     $query = CovidTracer::query();

    //     $query->where(function ($q) use ($code) {
    //         $q->where('transaction_one', '=', $code)->orWhere('transaction_two', '=', $code);
    //     });

    //     $query->where(function ($q) use ($search_from, $search_to) {
    //         $q->whereBetween('created_at', [$search_from, $search_to]);
    //     });

    //     // $query->where('id', '!=', $search_id);

    //     $involves = $query->get();

    //     $data = array();

    //     foreach ($involves as $key => $involve) {
    //         // $contact = '';

    //         if($involve->transaction_one[0] == 'P'){
    //                 $person = Person::where('person_code', '=', $involve->transaction_one)->with('user')->first();

    //                 $trans1 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
    //                 $trans1Code = $person->person_code;

    //                 if($trans1Code != $code){
    //                     $contact = $person->user['contact_number'];
    //                 }
    //         }else{
    //             $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
    //                 ->select('establishment_information.business_name', 'establishment_categories.description', 'establishment_information.establishment_identification_code')
    //                 ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
    //                 ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_one)
    //                 ->first();
    //                 $trans1 = $est->business_name . "(" . $est->description . ")";
    //                 $trans1Code = $est->establishment_identification_code;
    //         }


    //         if($involve->transaction_two[0] == 'P'){
    //             $person = Person::where('person_code', '=', $involve->transaction_two)->with('user')->first();

    //             $trans2 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
    //             $trans2Code = $person->person_code;

    //             // if($trans2Code != $code){
    //             //     $contact = $person->user['contact_number'];
    //             // }

    //         }else{
    //             $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
    //                 ->select('establishment_information.business_name', 'establishment_categories.description', 'establishment_information.establishment_identification_code')
    //                 ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
    //                 ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_two)
    //                 ->first();

    //             $trans2 = $est->business_name . "(" . $est->description . ")";
    //             $trans2Code = $est->establishment_identification_code;
    //         }

    //         $nestedData['id'] = $involve->id;
    //         $nestedData['trans_1'] = $trans1;
    //         $nestedData['trans_1_code'] = $trans1Code;
    //         $nestedData['trans_2'] = $trans2;
    //         $nestedData['trans_2_code'] = $trans2Code;
    //         // $nestedData['contact'] = $contact;
    //         $nestedData['date'] =  explode(' ', $involve->created_at)[0];
    //         $nestedData['time'] =  date( 'g:i A', strtotime(explode(' ', $involve->created_at)[1]));
    //         $data[] = $nestedData;

    //         session()->forget('generatedId');
    //         session()->push('generatedId', $nestedData['id']);
    //     }

    //     return $data;
    // }

    public function getByCode($code, $search_from, $search_to, $search_id = ''){
        $query = CovidTracer::query();
        $query->where(function ($q) use ($code) {
            $q->where('transaction_one', '=', $code)->orWhere('transaction_two', '=', $code);
        });
        $query->where(function ($q) use ($search_from, $search_to) {
            $q->whereBetween('created_at', [$search_from, $search_to]);
        });
        if(!empty($search_id)){
            $query->where('id', '!=', $search_id);
        }
        $involves = $query->get();
        $data = array();
        foreach ($involves as $key => $involve) {
            $contact = '';
            if($involve->transaction_one[0] == 'P'){
                    $person = Person::where('person_code', '=', $involve->transaction_one)->with('user')->first();
                    $trans1 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
                    $trans1Code = $person->person_code;
                    if($trans1Code != $code){
                        $contact = $person->user['contact_number'];
                    }
            }else{
                $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                    ->select('establishment_information.business_name', 'establishment_categories.description', 'establishment_information.establishment_identification_code')
                    ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                    ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_one)
                    ->first();
                    $trans1 = $est->business_name . "(" . $est->description . ")";
                    $trans1Code = $est->establishment_identification_code;
            }

            if($involve->transaction_two[0] == 'P'){
                $person = Person::where('person_code', '=', $involve->transaction_two)->with('user')->first();
                $trans2 = $person->last_name.', '.$person->first_name.' '.$person->middle_name;
                $trans2Code = $person->person_code;
                if($trans2Code != $code){
                    $contact = $person->user['contact_number'];
                }
            }else{
                $est = DB::table(connectionName('covid_tracer') . '.establishment_information')
                    ->select('establishment_information.business_name', 'establishment_categories.description', 'establishment_information.establishment_identification_code')
                    ->join(connectionName('covid_tracer') . '.establishment_categories', 'establishment_categories.id', '=', 'establishment_information.establishment_category_id')
                    ->where('establishment_information.establishment_identification_code', '=', $involve->transaction_two)
                    ->first();
                $trans2 = $est->business_name . "(" . $est->description . ")";
                $trans2Code = $est->establishment_identification_code;
            }
            $nestedData['id'] = $involve->id;
            $nestedData['trans_1'] = $trans1;
            $nestedData['trans_1_code'] = $trans1Code;
            $nestedData['trans_2'] = $trans2;
            $nestedData['trans_2_code'] = $trans2Code;
            $nestedData['contact'] = $contact;
            $nestedData['date'] =  explode(' ', $involve->created_at)[0];
            $nestedData['time'] =  date( 'g:i A', strtotime(explode(' ', $involve->created_at)[1]));
            $data[] = $nestedData;

            // session()->forget('generatedId');
            session()->push('generatedId', $nestedData['id']);
        }
        return $data;
    }

    public function searchPositive(Request $request)
    {
        $fullname = "";
        foreach($request['search_id'] as $id)
        {
            $tracer = CovidTracer::findOrFail($id);
            $person = DB::table(connectionName('mysql') . '.people')
                ->select('people.*')
                ->where('person_code', '=', $request['search_person_code'])->first();

            $fullname = $person->last_name . ' ' . $person->affiliation . ', ' . $person->first_name . ' ' . $person->middle_name;
        }

        return response()->json(array('success' => true, 'fullname' => $fullname, 'positive_person_code' => $person->person_code));
    }

    public function generateresults(Request $request)
    {
        // session()->push('generatedId', $nestedData['id']);
        session()->put('generatedId');
        // dd(Session::get('generatedId'));
        $data = array();
        $level1 = array();
        $level2 = array();
        $level3 = array();

        $root = $request['search'];
        $time_from = !empty(request('time_from'))?request('time_from'):"00:00:00";
        $time_to = !empty(request('time_to'))?request('time_to'):"23:59:59";
        $search_from = request('date_from') . " " . $time_from;
        $search_to = request('date_to') . " " . $time_to;

        /* get level 1 */
        foreach($request['search_id'] as $id){
            $tracer = CovidTracer::findOrFail($id);
            /* get who will search */
            $filter = ($tracer->transaction_one != $root)? $tracer->transaction_one:$tracer->transaction_two;
            if($filter[0] == 'P'){
                $gte_transaction_1 = $this->getByCode($filter, $search_from, $search_to, $id);
                foreach ($gte_transaction_1 as $key => $one) {
                    /* identify who will search */
                    $filter1 = ($one['trans_1_code'] != $filter)? $one['trans_1_code']:$one['trans_2_code'];
                    // dd($filter1);
                    if($filter1[0] == 'P' ){
                        /* level 1 */
                        $data[] = $one;
                        $level1[] = $one;
                        $get_transaction = $this->getByCode($filter1, $search_from, $search_to);
                        foreach ($get_transaction as $key => $trans2) {
                            /* get who will search */
                            $filter3 = ($trans2['trans_1_code'] != $filter1)? $trans2['trans_1_code']:$trans2['trans_2_code'];
                            if($filter3[0] == 'P'){
                                /* level 2 */
                                $data[] = $trans2;
                                $level2[] = $trans2;
                                $get_transaction = $this->getByCode($filter3, $search_from, $search_to);
                                foreach ($get_transaction as $key => $person_one) {
                                    $filter4 = ($person_one['trans_1_code'] != $filter3)? $person_one['trans_1_code']:$person_one['trans_2_code'];
                                    if($filter4[0] == 'P'){
                                        /* level 3 */
                                        $data[] = $person_one;
                                        $level3[] = $person_one;
                                    }else{
                                        $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to);
                                        foreach ($get_involve_by_est2 as $key => $est3) {
                                            /* level 3 */
                                            $data[] = $est3;
                                            $level3[] = $person_one;
                                        }
                                    }
                                }
                            }else{
                                $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to);
                                foreach ($get_involve_by_est2 as $key => $est3) {
                                    /* level 2 */
                                    $data[] = $est3;
                                    $level2[] = $est3;
                                    /* get who will search */
                                    $filter4 = ($est3['trans_1_code'] != $filter1)? $est3['trans_1_code']:$est3['trans_2_code'];
                                    $get_transaction_3 = $this->getByCode($filter4, $search_from, $search_to);
                                    foreach ($get_transaction_3 as $key => $trans3) {
                                        /* get who will search */
                                        $filter5 = ($trans3['trans_1_code'] != $filter4)? $trans3['trans_1_code']:$trans3['trans_2_code'];
                                        if($filter5[0] == 'P'){
                                            /* level 3 */
                                            $data[] = $trans3;
                                            $level3[] = $trans3;
                                        }else{
                                            $get_involve_by_est3 = $this->getByCode($filter5, $search_from, $search_to);
                                            foreach ($get_involve_by_est3 as $key => $est4) {
                                                /* level 3 */
                                                $data[] = $est4;
                                                $level3[] = $est4;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        $get_involve_by_est = $this->getByCode($filter1, $search_from, $search_to, $one['id']);
                        foreach ($get_involve_by_est as $key => $est_one) {
                            /* level 1 */
                            $data[] = $est_one;
                            $level1[][] = $est_one;
                            /* get who will search */
                            $filter2 = ($est_one['trans_1_code'] != $filter1)? $est_one['trans_1_code']:$est_one['trans_2_code'];
                            $get_transaction_2 = $this->getByCode($filter2, $search_from, $search_to, $est_one['id']);
                            foreach ($get_transaction_2 as $key => $trans2) {
                                /* get who will search */
                                $filter3 = ($trans2['trans_1_code'] != $filter2)? $trans2['trans_1_code']:$trans2['trans_2_code'];
                                // echo $filter3;
                                // dd($get_transaction_2);
                                if($filter3[0] == 'P'){
                                    /* level 2 */
                                    $data[] = $trans2;
                                    $level2[] = $trans2;
                                    $get_transaction = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);
                                    foreach ($get_transaction as $key => $person_one) {
                                        $filter4 = ($person_one['trans_1_code'] != $filter3)? $person_one['trans_1_code']:$person_one['trans_2_code'];
                                        if($filter4[0] == 'P'){
                                            /* level 3 */
                                            $data[] = $person_one;
                                            $level3[] = $person_one;
                                        }else{
                                            $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);
                                            foreach ($get_involve_by_est2 as $key => $est3) {
                                                /* level 3 */
                                                $data[] = $est3;
                                                $level3[] = $est3;
                                            }
                                        }
                                    }
                                }else{
                                    $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);
                                    foreach ($get_involve_by_est2 as $key => $est3) {
                                        /* level 2 */
                                        $data[] = $est3;
                                        $level2[] = $est3;
                                        /* get who will search */
                                        $filter4 = ($est3['trans_1_code'] != $filter1)? $est3['trans_1_code']:$est3['trans_2_code'];
                                        $get_transaction_3 = $this->getByCode($filter4, $search_from, $search_to, $est3['id']);
                                        foreach ($get_transaction_3 as $key => $trans3) {
                                            /* get who will search */
                                            $filter5 = ($trans3['trans_1_code'] != $filter4)? $trans3['trans_1_code']:$trans3['trans_2_code'];
                                            if($filter5[0] == 'P'){
                                                /* level 3 */
                                                $data[] = $trans3;
                                                $level3[] = $trans3;
                                            }else{
                                                $get_involve_by_est3 = $this->getByCode($filter5, $search_from, $search_to, $trans3['id']);
                                                foreach ($get_involve_by_est3 as $key => $est4) {
                                                    /* level 3 */
                                                    $data[] = $est4;
                                                    $level3[] = $est4;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                $get_involve_by_est = $this->getByCode($filter, $search_from, $search_to, $id);
                foreach ($get_involve_by_est as $key => $est_one) {
                    /* level 1 */
                    $data[] = $est_one;
                    /* get who will search */
                    $filter2 = ($est_one['trans_1_code'] != $filter)? $est_one['trans_1_code']:$est_one['trans_2_code'];
                    $get_transaction_2 = $this->getByCode($filter2, $search_from, $search_to);
                    foreach ($get_transaction_2 as $key => $trans2) {
                        /* get who will search */
                        $filter3 = ($trans2['trans_1_code'] != $filter2)? $trans2['trans_1_code']:$trans2['trans_2_code'];
                        // echo $filter3;
                        // dd($get_transaction_2);
                        if($filter3[0] == 'P'){
                            /* level 2 */
                            $data[] = $trans2;
                            $get_transaction = $this->getByCode($filter3, $search_from, $search_to);
                            foreach ($get_transaction as $key => $person_one) {
                                $filter4 = ($person_one['trans_1_code'] != $filter3)? $person_one['trans_1_code']:$person_one['trans_2_code'];
                                if($filter4[0] == 'P'){
                                    /* level 3 */
                                    $data[] = $person_one;
                                }else{
                                    $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to);
                                    foreach ($get_involve_by_est2 as $key => $est3) {
                                        /* level 3 */
                                        $data[] = $est3;
                                    }
                                }
                            }
                        }else{
                            $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to);
                            foreach ($get_involve_by_est2 as $key => $est3) {
                                /* level 2 */
                                $data[] = $est3;
                                /* get who will search */
                                $filter4 = ($est3['trans_1_code'] != $filter)? $est3['trans_1_code']:$est3['trans_2_code'];
                                $get_transaction_3 = $this->getByCode($filter4, $search_from, $search_to);
                                foreach ($get_transaction_3 as $key => $trans3) {
                                    /* get who will search */
                                    $filter5 = ($trans3['trans_1_code'] != $filter4)? $trans3['trans_1_code']:$trans3['trans_2_code'];
                                    if($filter5[0] == 'P'){
                                        /* level 3 */
                                        $data[] = $trans3;
                                    }else{
                                        $get_involve_by_est3 = $this->getByCode($filter5, $search_from, $search_to);
                                        foreach ($get_involve_by_est3 as $key => $est4) {
                                            /* level 3 */
                                            $data[] = $est4;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $columns = array(
            0 =>'id',
        );
        $limit = $request->input('length');
        $start = $request->input('start');

        $final = [];
        foreach (array_unique($data, SORT_REGULAR) as $value) {
            $final[] = $value;
        }
        $pagedArray = array_slice($final, $start, $limit);
        $totalData = count($final);
        $totalFiltered = count($pagedArray);
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            // "recordsFiltered" => count($final),
            "data" => $pagedArray
        );
        echo json_encode($json_data);
    }

    // public function generateresults(Request $request)
    // {
    //     $data = array();
    //     $level1 = array();
    //     $level2 = array();
    //     $level3 = array();

    //     $root = $request['search'];
    //     $time_from = !empty(request('time_from'))?request('time_from'):"00:00:00";
    //     $time_to = !empty(request('time_to'))?request('time_to'):"23:59:59";
    //     $search_from = request('date_from') . " " . $time_from;
    //     $search_to = request('date_to') . " " . $time_to;


    //     /* get level 1 */
    //     foreach($request['search_id'] as $id){

    //         $tracer = CovidTracer::findOrFail($id);

    //         /* get who will search */
    //         $filter = ($tracer->transaction_one != $root)? $tracer->transaction_one:$tracer->transaction_two;
    //         if($filter[0] == 'P'){
    //             $gte_transaction_1 = $this->getByCode($filter, $search_from, $search_to, $id);

    //             foreach ($gte_transaction_1 as $key => $one) {

    //                 /* identify who will search */
    //                 $filter1 = ($one['trans_1_code'] != $filter)? $one['trans_1_code']:$one['trans_2_code'];

    //                 if($filter1[0] == 'P' ){
    //                     /* level 1 */
    //                     $data[] = $one;

    //                     $get_transaction = $this->getByCode($filter1, $search_from, $search_to, $one['id']);

    //                     foreach ($get_transaction as $key => $trans2) {
    //                         /* get who will search */
    //                         $filter3 = ($trans2['trans_1_code'] != $filter1)? $trans2['trans_1_code']:$trans2['trans_2_code'];

    //                         if($filter3[0] == 'P'){
    //                             /* level 2 */
    //                             $data[] = $trans2;

    //                             $get_transaction = $this->getByCode($filter3, $search_from, $search_to, $one['id']);

    //                             foreach ($get_transaction as $key => $person_one) {

    //                                 $filter4 = ($person_one['trans_1_code'] != $filter3)? $person_one['trans_1_code']:$person_one['trans_2_code'];

    //                                 if($filter4[0] == 'P'){
    //                                     /* level 3 */
    //                                     $data[] = $person_one;
    //                                 }else{
    //                                     $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);
    //                                     foreach ($get_involve_by_est2 as $key => $est3) {
    //                                         /* level 3 */
    //                                         $data[] = $est3;
    //                                     }
    //                                 }
    //                             }
    //                         }else{
    //                             $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);

    //                             foreach ($get_involve_by_est2 as $key => $est3) {
    //                                 /* level 2 */
    //                                 $data[] = $est3;

    //                                 /* get who will search */
    //                                 $filter4 = ($est3['trans_1_code'] != $filter1)? $est3['trans_1_code']:$est3['trans_2_code'];

    //                                 $get_transaction_3 = $this->getByCode($filter4, $search_from, $search_to, $est3['id']);

    //                                 foreach ($get_transaction_3 as $key => $trans3) {
    //                                     /* get who will search */
    //                                     $filter5 = ($trans3['trans_1_code'] != $filter4)? $trans3['trans_1_code']:$trans3['trans_2_code'];

    //                                     if($filter5[0] == 'P'){
    //                                         /* level 3 */
    //                                         $data[] = $trans3;
    //                                     }else{
    //                                         $get_involve_by_est3 = $this->getByCode($filter5, $search_from, $search_to, $trans3['id']);

    //                                         foreach ($get_involve_by_est3 as $key => $est4) {
    //                                             /* level 3 */
    //                                             $data[] = $est4;
    //                                         }
    //                                     }
    //                                 }

    //                             }
    //                         }
    //                     }
    //                 }else{

    //                     $get_involve_by_est = $this->getByCode($filter1, $search_from, $search_to, $one['id']);

    //                     foreach ($get_involve_by_est as $key => $est_one) {
    //                         /* level 1 */
    //                         $data[] = $est_one;

    //                         /* get who will search */
    //                         $filter2 = ($est_one['trans_1_code'] != $filter1)? $est_one['trans_1_code']:$est_one['trans_2_code'];

    //                         $get_transaction_2 = $this->getByCode($filter2, $search_from, $search_to, $est_one['id']);

    //                         foreach ($get_transaction_2 as $key => $trans2) {
    //                             /* get who will search */
    //                             $filter3 = ($trans2['trans_1_code'] != $filter2)? $trans2['trans_1_code']:$trans2['trans_2_code'];

    //                             if($filter3[0] == 'P'){
    //                                 /* level 2 */
    //                                 $data[] = $trans2;

    //                                 $get_transaction = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);

    //                                 foreach ($get_transaction as $key => $person_one) {

    //                                     $filter4 = ($person_one['trans_1_code'] != $filter3)? $person_one['trans_1_code']:$person_one['trans_2_code'];

    //                                     if($filter4[0] == 'P'){
    //                                         /* level 3 */
    //                                         $data[] = $person_one;

    //                                     }else{
    //                                         $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);
    //                                         foreach ($get_involve_by_est2 as $key => $est3) {
    //                                             /* level 3 */
    //                                             $data[] = $est3;

    //                                         }
    //                                     }
    //                                 }
    //                             }else{
    //                                 $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);

    //                                 foreach ($get_involve_by_est2 as $key => $est3) {
    //                                     /* level 2 */
    //                                     $data[] = $est3;

    //                                     /* get who will search */
    //                                     $filter4 = ($est3['trans_1_code'] != $filter1)? $est3['trans_1_code']:$est3['trans_2_code'];

    //                                     $get_transaction_3 = $this->getByCode($filter4, $search_from, $search_to, $est3['id']);

    //                                     foreach ($get_transaction_3 as $key => $trans3) {
    //                                         /* get who will search */
    //                                         $filter5 = ($trans3['trans_1_code'] != $filter4)? $trans3['trans_1_code']:$trans3['trans_2_code'];

    //                                         if($filter5[0] == 'P'){
    //                                             /* level 3 */
    //                                             $data[] = $trans3;
    //                                         }else{
    //                                             $get_involve_by_est3 = $this->getByCode($filter5, $search_from, $search_to, $trans3['id']);

    //                                             foreach ($get_involve_by_est3 as $key => $est4) {
    //                                                 /* level 3 */
    //                                                 $data[] = $est4;
    //                                             }
    //                                         }
    //                                     }

    //                                 }
    //                             }
    //                         }

    //                     }
    //                 }
    //             }
    //         }else{
    //             $get_involve_by_est = $this->getByCode($filter, $search_from, $search_to, $id);

    //             foreach ($get_involve_by_est as $key => $est_one) {
    //                 /* level 1 */
    //                 $data[] = $est_one;

    //                 /* get who will search */
    //                 $filter2 = ($est_one['trans_1_code'] != $filter)? $est_one['trans_1_code']:$est_one['trans_2_code'];
    //                 $get_transaction_2 = $this->getByCode($filter2, $search_from, $search_to, $est_one['id']);

    //                 foreach ($get_transaction_2 as $key => $trans2) {
    //                     /* get who will search */
    //                     $filter3 = ($trans2['trans_1_code'] != $filter2)? $trans2['trans_1_code']:$trans2['trans_2_code'];

    //                     if($filter3[0] == 'P'){
    //                         /* level 2 */
    //                         $data[] = $trans2;

    //                         $get_transaction = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);

    //                         foreach ($get_transaction as $key => $person_one) {

    //                             $filter4 = ($person_one['trans_1_code'] != $filter3)? $person_one['trans_1_code']:$person_one['trans_2_code'];

    //                             if($filter4[0] == 'P'){
    //                                 /* level 3 */
    //                                 $data[] = $person_one;

    //                             }else{
    //                                 $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);
    //                                 foreach ($get_involve_by_est2 as $key => $est3) {
    //                                     /* level 3 */
    //                                     $data[] = $est3;

    //                                 }
    //                             }
    //                         }
    //                     }else{
    //                         $get_involve_by_est2 = $this->getByCode($filter3, $search_from, $search_to, $trans2['id']);

    //                         foreach ($get_involve_by_est2 as $key => $est3) {
    //                             /* level 2 */
    //                             $data[] = $est3;

    //                             /* get who will search */
    //                             $filter4 = ($est3['trans_1_code'] != $filter)? $est3['trans_1_code']:$est3['trans_2_code'];

    //                             $get_transaction_3 = $this->getByCode($filter4, $search_from, $search_to, $est3['id']);

    //                             foreach ($get_transaction_3 as $key => $trans3) {
    //                                 /* get who will search */
    //                                 $filter5 = ($trans3['trans_1_code'] != $filter4)? $trans3['trans_1_code']:$trans3['trans_2_code'];

    //                                 if($filter5[0] == 'P'){
    //                                     /* level 3 */
    //                                     $data[] = $trans3;
    //                                 }else{
    //                                     $get_involve_by_est3 = $this->getByCode($filter5, $search_from, $search_to, $trans3['id']);

    //                                     foreach ($get_involve_by_est3 as $key => $est4) {
    //                                         /* level 3 */
    //                                         $data[] = $est4;
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }

    //             }
    //         }
    //     }
    //     $columns = array(
    //         0 =>'id',
    //     );

    //     $limit = $request->input('length');
    //     $start = $request->input('start');
    //     // $order = $columns[$request->input('order.0.column')];
    //     // $dir = $request->input('order.0.dir');

    //     $final = [];
    //     foreach (array_unique($data, SORT_REGULAR) as $value) {
    //         $final[] = $value;
    //     }

    //     $pagedArray = array_slice($final, $start, $limit);

    //     $totalData = count($final);
    //     $totalFiltered = count($pagedArray);
    //     $json_data = array(
    //         "draw" => intval($request->input('draw')),
    //         "recordsTotal" => intval($totalData),
    //         "recordsFiltered" => intval($totalFiltered),
    //         "recordsFiltered" => count($final),
    //         "data" => $pagedArray
    //     );

    //     echo json_encode($json_data);
    // }


}
