<?php

namespace App\Http\Controllers\Comprehensive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Ecabs\Person;
use App\Ecabs\Address;
use Auth;
use DB;
use SimpleSoftwareIO\QrCode\Generator;

class PrintQrCodeController extends Controller
{
    public function index()
    {
        return view('comprehensive.qr_code_printing.index' , ['title' => "QR Code Printing"]);
    }

    //find all data
    public function findAll(Request $request)
    {
    	$columns = array( 
            0 =>'last_name', 
            1 =>'department',
            2 =>'position',
        );

        //datatables total data
        $totalData = User::where('account_status', '1')->where('id', '!=', '1')->count();  
        $totalFiltered = $totalData; 
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        //check user department
        $department = $this->getDepartment(Auth::user())->department_position['department_id'];

        if($department == 1){
            $query = DB::table('users') 
            ->join('people', 'people.id', '=', 'users.person_id') 
            ->select('people.*')
            ->where('users.account_status', '1')->where('users.id', '!=', '1');
        }else{
            $query = DB::table('users') 
                ->join('people', 'people.id', '=', 'users.person_id')
                ->join('person_department_positions', 'person_department_positions.person_id', '=', 'people.id')
                ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
                ->join('departments', 'departments.id', '=', 'department_positions.department_id')
                ->select('people.*')
                ->where('departments.id', '=', $department)
                ->where('users.account_status', '1')
                ->where('users.id', '!=', '1');
        }
        
        if(empty($request->input('search.value')))
        {            
            $users = $query
                    ->offset($start)
                 	->limit($limit)
                 	->orderBy($order,$dir)
                    ->get();
        }
        else {
           $search = $request->input('search.value'); 

           $users= $query->where('last_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.account_status', '1')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query
                ->count();
        }
        
        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $account = User::where('person_id', $user->id)->first();
                // $person_dept_pos = PersonDepartmentPosition::where('person_id', $account['person_id'])->with('department_position')->first();
                
                // if($person_dept_pos){
                //     $department = Department::where('id', $person_dept_pos->department_position->department_id)->first();
                //     $position =  PositionAccess::where('id', $person_dept_pos->department_position->position_access_id)->first();

                    $status = "<label class='label label-primary'>Active</label>";

                    $buttons = ' <a data-toggle="tooltip" title="Click here to view Account Information" onclick="view('. $account->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW</a>';
                    
                    //user priting of qr code
                    if($request['action'] == 'qrcodeprinting'){
                        $buttons = '<button data-toggle="tooltip" title="Click here to print Account QRCode" onclick="print_form('.$user->id.')" class="btn btn-xs btn-warning btn-fill btn-rotate add"><i class="ti-printer"></i> PRINT QR CODE</button> ';
                    }
                    $fullname = $user->last_name;
        
                    if($user->affiliation){
                        $fullname .= " " . $user->affiliation;
                    }
                    $fullname .= ", " . $user->first_name . " ";
                    
                    if($user->middle_name){
                        $fullname .= $user->middle_name[0] . "."; 
                    }
                    
                    $nestedData['fullname'] = $fullname;
                    // $nestedData['department'] = $department->department;
                    // $nestedData['position'] = $position->position;
                    $nestedData['status'] = $status;
                    $nestedData['actions'] = $buttons;
                    $nestedData['id'] = $account->id;
                    $data[] = $nestedData;
                // }
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

    public function printQrCode(Request $request){
        $qrCodeList = array();
        $names = array();

        // dd($request['userId']);
        // dd("asd");
        foreach($request['userId'] as $userId){
            $user = User::findOrFail($userId);
            $person = Person::findOrFail($user->person_id);

            $qrCode = new Generator;
            $data = $qrCode->size(200)->generate($person->person_code);
    
            // $barangay = Barangay::findOrFail($person->barangay_id);
            $fullname = $person->last_name;
            
            if($person->affiliation){
                $fullname .= " " . $person->affiliation;
            }
            $fullname .= ", " . $person->first_name . " ";
            
            if($person->middle_name){
                $fullname .= $person->middle_name[0] . "."; 
            }

            $nestedData["name"] = $fullname;
            $nestedData["qrCode"] = $data;
            $qrCodeList[] = $nestedData;
        }
        
        // dd($qrCodeList);
        // dd($qrCode);
        // return response()->json(['qrcodeList' => $qrCodeList]);
        return response()->json($qrCodeList);
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
