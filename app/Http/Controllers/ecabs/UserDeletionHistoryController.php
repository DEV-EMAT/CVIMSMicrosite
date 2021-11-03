<?php

namespace App\Http\Controllers\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ecabs\UserDeletionHistory;
use App\Ecabs\PersonDepartmentPosition;
use App\Ecabs\Department;
use App\Ecabs\PositionAccess;
use App\User;
use Auth;
use DB;
use Response;

class UserDeletionHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ecabs.user.deletionhistory', ['title' => "Deletion History"]);
    }

    public function findall(Request $request)
    {
    	$columns = array( 
            0 =>'last_name', 
        );

        //datatables total data
        $totalData = UserDeletionHistory::where('status', '0')->count();  
        $totalFiltered = $totalData; 
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        //check user department
        $department = $this->getDepartment(Auth::user())->department_position['department_id'];

        if($department == 1){
            $query = DB::table('user_deletion_histories') 
            ->join('users', 'users.id', '=', 'user_deletion_histories.updated_status_user_id') 
            ->join('people', 'people.id', '=', 'users.person_id') 
            ->select('people.*')
            ->where('user_deletion_histories.status', '0');
        }else{
            $query = DB::table('user_deletion_histories') 
                ->join('users', 'users.id', '=', 'user_deletion_histories.updated_status_user_id')
                ->join('people', 'people.id', '=', 'users.person_id') 
                ->join('person_department_positions', 'person_department_positions.person_id', '=', 'people.id')
                ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
                ->join('departments', 'departments.id', '=', 'department_positions.department_id')
                ->select('people.*')
                ->where('departments.id', '=', $department)
                ->where('user_deletion_histories.status', '0');
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

           $users= $query->where('last_name', 'LIKE', "%{$search}%")->where('users.status', '0')
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('users.status', '0')
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('users.status', '0')
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
                $person_dept_pos = PersonDepartmentPosition::where('person_id', $account['person_id'])->with('department_position')->first();
                
                if($person_dept_pos){
                    $department = Department::where('id', $person_dept_pos->department_position->department_id)->first();
                    $position =  PositionAccess::where('id', $person_dept_pos->department_position->position_access_id)->first();

                    $status = "<label class='label label-danger'>Deleted</label>";

                    $buttons = ' <a data-toggle="tooltip" title="Click here to view Deletion History" onclick="view('. $account->id .')" class="btn btn-xs btn-info btn-fill btn-rotate view"><i class="ti-eye"></i> VIEW HISTORY</a>';
                    

                    $fullname = $user->last_name;
        
                    if($user->affiliation){
                        $fullname .= " " . $user->affiliation;
                    }
                    $fullname .= ", " . $user->first_name . " ";
                    
                    if($user->middle_name){
                        $fullname .= $user->middle_name[0] . "."; 
                    }

                    $nestedData['fullname'] = $fullname;
                    $nestedData['status'] = $status;
                    $nestedData['actions'] = $buttons;
                    $nestedData['id'] = $account->id;
                    $data[] = $nestedData;
                }
            }
        }
        
        // $limit = $request->input('length');
        // $start = $request->input('start');

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
            "recordsFiltered" => count($final),
            "data" => $pagedArray
        );

        // $json_data = array(
        //     "draw"            => intval($request->input('draw')),  
        //     "recordsTotal"    => intval($totalData),  
        //     "recordsFiltered" => intval($totalFiltered), 
        //     "data"            => $data   
        //     );
            
        echo json_encode($json_data); 
    }

    public function findHistory(Request $request)
    {
    	$columns = array( 
            0 =>'last_name', 
        );

        //datatables total data
        $totalData = UserDeletionHistory::where('status', '0')->count();  
        $totalFiltered = $totalData; 
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = DB::table('user_deletion_histories') 
            ->join('users', 'users.id', '=', 'user_deletion_histories.updated_by') 
            ->join('people', 'people.id', '=', 'users.person_id') 
            ->select('people.first_name', 'people.last_name', 'people.middle_name', 'people.affiliation' , 'user_deletion_histories.*')
            ->where('user_deletion_histories.updated_status_user_id', '=', $request['userId']);
             
        
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

           $users= $query->where('last_name', 'LIKE', "%{$search}%")->where('user_deletion_histories.updated_status_user_id', '=', $request['userId'])
                ->orWhere('first_name', 'LIKE', "%{$search}%")->where('user_deletion_histories.updated_status_user_id', '=', $request['userId'])
                ->orWhere('middle_name', 'LIKE', "%{$search}%")->where('user_deletion_histories.updated_status_user_id', '=', $request['userId'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query
                ->count();
        }

        // dd($users);

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $account = User::where('person_id', $user->id)->first();
                
                if($user->status == '0'){
                    $status = "<label class='label label-danger'>Deleted</label>";;
                }
                else{
                    $status = "<label class='label label-primary'>Restored</label>";
                }

                $fullname = $user->last_name;
    
                if($user->affiliation){
                    $fullname .= " " . $user->affiliation;
                }
                $fullname .= ", " . $user->first_name . " ";
                
                if($user->middle_name){
                    $fullname .= $user->middle_name[0] . "."; 
                }
                
                // if($fullname != ""){
                    $nestedData['updatedBy'] = $fullname;
                    $nestedData['reason'] = $user->reason;

                    $nestedData['status'] = $status;
                    $nestedData['date'] = explode(' ', $user->created_at)[0];
                    $nestedData['time'] = explode(' ', $user->created_at)[1];
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
