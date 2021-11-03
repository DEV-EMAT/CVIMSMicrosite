<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\EstablishmentInformation;
use App\CovidTracer\EstablishmentStaff;
use App\Events\DataTableEvent;
use Response;
use DB;
use App\CovidTracer\EstablishmentCategory;
use App\Ecabs\Barangay;
use App\User;
use App\Ecabs\Person;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Generator;
use PDF;
use Gate;


class EstablishmentInformationController extends Controller
{
    public function index()
    {
        return view('covidtracer/establishment_information.index', ['title' => "Establishment Information Management"]);
    }

    public function findall(Request $request)
    {
        $columns = array( 
            0 =>'business_name', 
            1 =>'status',
        );

        $totalData = EstablishmentInformation::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $est_information = EstablishmentInformation::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = EstablishmentInformation::where('business_name','LIKE',"%{$search}%");

            $est_information =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($est_information))
        {
            foreach ($est_information as $est_info)
            {  
                $buttons = '';
               
                if($est_info['status'] == '1'){
                    // $buttons .= '<a data-toggle="tooltip" title="Click here to print QR Code" href="/covidtracer/estinfo/print-qrcode/'. $est_info['id'] .'" target="_blank"  class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="ti-printer"></i> PRINT QR CODE</a> ';
                    if(Gate::allows('permission', 'viewPrintEstQrCode')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to print QR Code" onclick="printQrCode('. $est_info['id'] .')" target="_blank"  class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="ti-printer"></i> PRINT QR CODE</a> ';
                    }

                    if(Gate::allows('permission', 'createEstStaff')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to add Staffs" onclick="addStaff('. $est_info['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate add"><i class="ti-plus"></i> ADD STAFF</a> ';    
                    }

                    if(Gate::allows('permission', 'viewEstStaff') || Gate::allows('permission', 'deleteEstStaff')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to view Staffs" onclick="viewStaff('. $est_info['id'] .')"  class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW STAFF</a> ';    
                    }
                    
                    if(Gate::allows('permission', 'updateEstinfo')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to edit Establishment information" onclick="edit('. $est_info['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ' ;
                    }

                    if(Gate::allows('permission', 'deleteEstinfo')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to delete Establishment information" onclick="deactivate('. $est_info['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';
                    }

                    $status = "<label class='label label-primary'>Active</label>";
                }
                else{
                    if(Gate::allows('permission', 'restoreEstinfo')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to restore Establishment information" onclick="activate('. $est_info['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }
                
                $nestedData['business_name'] = $est_info->business_name;
                $nestedData['status'] = $status;
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

    public function findallforcombobox()
    {
        $establishment_info = EstablishmentInformation::where('status', '1')->get();

        return response()->json($establishment_info);
    }

    //search users for owner
    public function findOwner(Request $request)
    {
        $columns = array( 
            0 =>'last_name', 
            1 =>'status',
        );

        $establishmentStaffs = array();
        $establishmentId = $request['establishmentId'];
        //get active staffs
        $staffs = EstablishmentStaff::where('establishment_information_id','=', $establishmentId)->where('staff_status','=', '1')->get();
        if($staffs){
            foreach($staffs as $staff){
                // array_push($establishmentStaffs , "$staff->user_id");
                $establishmentStaffs[] = $staff->user_id;
            }
        }

        //datatables total data
        $totalData = User::where('account_status', '1')->where('id', '!=', '1')->whereNotIn('users.id', $establishmentStaffs)->count();   
        $totalFiltered = $totalData; 
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table(connectionName('mysql') . '.users') 
        ->join(connectionName('mysql') . '.people', 'people.id', '=', 'users.person_id')
        ->select('people.*')
        ->where('users.account_status', '1')->where('users.id', '!=', '1')->whereNotIn('users.id', $establishmentStaffs);

        if(empty($request->input('search.value')))
        {            
            $people = $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $people= $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')->where('users.id', '!=', '1')->whereNotIn('users.id', $establishmentStaffs)
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')->where('users.id', '!=', '1')->whereNotIn('users.id', $establishmentStaffs)
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')->where('users.id', '!=', '1')->whereNotIn('users.id', $establishmentStaffs)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query
                ->count();
        }

        $data = array();
        $counter = 0;
        if(!empty($people))
        {
            foreach ($people as $person)
            {
                $account = User::where('person_id', $person->id)->first();
                // $flag = false;
                // foreach($staffs as $staff){
                //     if($account->id == $staff['user_id']){
                //         $flag = true;
                //     }
                // }
                // if($flag == false){
                    // $final[] = $value;
                    $fullname = '';
                    $fullname = $person->last_name;
                    if($person->affiliation){ $fullname .= " " . $person->affiliation;}
                    $fullname .= ", " . $person->first_name . " ";
                    if($person->middle_name){ $fullname .= $person->middle_name[0] . "."; }

                    $status = "<label class='label label-danger'>Inactive</label>";
                    $buttons = '<a onclick="addOwner('. $account->id .')"  class="btn btn-xs btn-warning btn-fill btn-rotate view"><i class="fa fa-plus"></i> Add Owner</a>';                      

                    $nestedData['id'] = $account->id;
                    $nestedData['fullname'] = $fullname;
                    $nestedData['buttons'] = $buttons;
                    $data[] = $nestedData;
                // }
            }
        }

        // $final = [];
        // foreach ($data as $value) {
        //     $flag = false;
        //     foreach($staffs as $staff){
        //         if($value['id'] == $staff['user_id']){
        //             $flag = true;
        //         }
        //     }
        //     if($flag == false){
        //         $final[] = $value;
        //     }
        // }

        // $final = $data;
        
        $totalData = count($data);
        // $totalFiltered = count($data);
        
        // $json_data = array(
        //     "draw"            => intval($request->input('draw')),  
        //     "recordsTotal"    => intval($totalData),  
        //     "recordsFiltered" => intval($totalFiltered), 
        //     "data"            => $data   
        //     );
            
        // echo json_encode($json_data); 
        $limit = $request->input('length');
        $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        // $dir = $request->input('order.0.dir');
        
        $pagedArray = array_slice($data, $start, ($limit == "-1")? count($data):$limit);
        // $totalData = count($data);
        // $totalFiltered = count($data);
            
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            // "recordsFiltered" => count($final),
            "data" => $data
        );
        echo json_encode($json_data); 
    }

    public function getOwner($id){
        $user = User::findOrFail($id);
        $person = Person::findOrFail($user->person_id);
        
        $fullname = '';
        $fullname = $person->last_name;
        if($person->affiliation){ $fullname .= " " . $person->affiliation;}
        $fullname .= ", " . $person->first_name . " ";
        if($person->middle_name){ $fullname .= $person->middle_name[0] . "."; }

        return response()->json(array('name' => $fullname));   
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $this -> validate ($request, [
            'category'=>'required',
            'ownerId'=>'required',
            'business_name'=>'required',
            'business_permit'=>'required',
            'address'=>'required',
            'barangay'=>'required',
        ]);
        
        try {
            DB::beginTransaction();

            $est_info = new EstablishmentInformation;
            $est_info->establishment_category_id = $request['category'];
            $est_info->owner_id = $request['ownerId'];

            $user = User::findOrFail($request['ownerId']);

            $current_date = Carbon::today();
            $year = $current_date->year;
            $day = $current_date->day;
            $month = $current_date->month;
            $est_info->establishment_identification_code = 'E' . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . str_pad($day . substr($year, -2) . $month . $user->person_id, 16, '0', STR_PAD_LEFT);
            
            $est_info->business_name = convertData($request['business_name']);
            $est_info->business_permit_number = $request['business_permit'];
            $est_info->address = convertData($request['address']);
            $est_info->barangay_id = $request['barangay'];
            $est_info->status = 1;
            $changes = $est_info->getDirty();
            $est_info->save();

            DB::commit();
            
            /* logs */
            action_log('Establishment Information Mngt', 'Create', array_merge(['id' => $est_info->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Created!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function show($id)
    {
        $est_info = EstablishmentInformation::findOrFail($id);
        $user = User::findOrFail($est_info->owner_id);
        $person = Person::findOrFail($user->person_id);


        $fullname = '';
        $fullname = $person->last_name;
        if($person->affiliation){ $fullname .= " " . $person->affiliation;}
        $fullname .= ", " . $person->first_name . " ";
        if($person->middle_name){ $fullname .= $person->middle_name[0] . "."; }
        
        return response()->json(array('owner' => $fullname, 'estInfo' => $est_info));  
    }
    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $this -> validate ($request, [
            'category'=>'required',
            'ownerId'=>'required',
            'business_name'=>'required',
            'business_permit'=>'required',
            'address'=>'required',
            'barangay'=>'required',
        ]);

         try {
            DB::beginTransaction();

            $est_info = EstablishmentInformation::findOrFail($id);

            $est_info->establishment_category_id = $request['category'];
            $est_info->owner_id = $request['ownerId'];

            $user = User::findOrFail($request['ownerId']);

            $est_info->business_name = convertData($request['business_name']);
            $est_info->business_permit_number = $request['business_permit'];
            $est_info->address = convertData($request['address']);
            $est_info->barangay_id = $request['barangay'];
            $changes = $est_info->getDirty();
            $est_info->save();

            DB::commit();
            
            /* logs */
            action_log('Establishment Information Mngt', 'Create', array_merge(['id' => $est_info->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function destroy($id)
    {
        //
    }

    //toggle status
    public function togglestatus($id){
        try {
            DB::beginTransaction();
            $est_info = EstablishmentInformation::findOrFail($id);
            $status = $est_info->status;
            $action = '';
            
            if($status == 1){
                $est_info->status = 0;
                $action = 'DELETED';
            }
            else{
                $est_info->status = 1;
                $action = 'RESTORE';
            }
            $changes = $est_info->getDirty();
            $est_info->save();
            
            DB::commit();

            /* logs */
            action_log('Establishment Information Mngt', $action, array_merge(['id' => $est_info->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function establishmentQrCode($id){
        $establishment = EstablishmentInformation::findOrFail($id);
        $qrCode = new Generator;
        $data = $qrCode->size(200)->generate($establishment->establishment_identification_code);
        
        return response::json($data);
    }

    public function printQrCode($id){
        $establishment = EstablishmentInformation::findOrFail($id);
        $barangay = Barangay::findOrFail($establishment->barangay_id);

        $qrCode = new Generator;
        $data = $qrCode->size(200)->generate($establishment->establishment_identification_code);
        
        return response()->json(['qrcode' => $data, 'establishment' => $establishment->business_name, 'address' => $barangay->barangay . ', ' . $barangay->city . ', ' . $barangay->province]);
    }
}
