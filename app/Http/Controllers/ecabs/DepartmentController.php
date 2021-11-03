<?php

namespace App\Http\Controllers\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ecabs\Department;
use App\Ecabs\DepartmentPosition;
use App\Ecabs\PositionAccess;
use Validator;
use Auth;
use App\Events\DataTableEvent;
use DB;
use Gate;
use Image;
use Storage;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ecabs.department.index', ['title' => 'Department Management']);
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

    public function findall(Request $request)
    {
        $columns = array('id', 'department', 'description');

        $totalData = Department::where('id', '!=', '1')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $record_list = Department::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = Department::where('department', 'like', "%{$search}%");

            $record_list =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($record_list))
        {
            foreach ($record_list as $record)
            {
                $buttons ="";

                if(Gate::allows('permission', 'updateDepartment')){
                    $buttons = '<a onclick="edit('. $record->id .')" class="btn btn-xs btn-success btn-fill btn-rotate edit" data-toggle="tooltip" title="Click here to edit Department"><i class="ti-pencil-alt"></i> EDIT</a> '; 
                }

                if($record['status'] == '1'){
                    
                    if(Gate::allows('permission', 'deleteDepartment')){
                        $buttons .= '<a onclick="deactivate('. $record->id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove" data-toggle="tooltip" title="Click here to delete Department"><i class="ti-trash"></i> DELETE</a>';  
                    }  
                    $status = "<label class='label label-primary'>Active</label>";
                } else {
                    if(Gate::allows('permission', 'restoreDepartment')){
                        $buttons .= '<a onclick="deactivate('. $record->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove" data-toggle="tooltip" title="Click here to restore Department"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }

                $roles = array_map(function($value )use ($record) { 
                    $button = '<a onclick="updateAccess('.$value['positions']['id'].', \''. strtolower($record->department).'\')" class="btn btn-xs btn-success btn-fill btn-rotate edit" data-toggle="tooltip" title="Click here to edit Department"><i class="ti-pencil-alt"></i> EDIT</a></button> ';
                    
                    if($value['positions']['status'] == '1'){
                       $value['positions']['status'] = "<label class='label label-primary'>Active</label>";
                    }else{
                       $value['positions']['status'] = "<label class='label label-danger'>Deleted</label>";
                    }

                    return array_merge($value['positions'], ['action' => $button ] ); 
                
                }, DepartmentPosition::where('department_id', $record->id)->with(['positions' => function($query){ 
                    $query->select('id', 'position', 'access', 'status');
                }])->get()->toArray());
                

                $nestedData['department'] = $record->department;
                $nestedData['address'] = $record->address;
                $nestedData['logo'] = !empty($record->logo)?'<div style="text-align:center"><img width="40%" src="'. Storage::url('public/'. $record->logo).'" /></div>':'<div style="text-align:center"><img width="40%" src="'. Storage::url('public/ecabs/images/logo/default-logo.png').'" /></div>';
                $nestedData['roles'] = $roles;
                $nestedData['status'] = $status;
                $nestedData['actions'] = $buttons;
                $nestedData['logo'] = !empty($record->logo)?'<div style="text-align:center"><img width="40%" src="'. Storage::url('public/'. $record->logo) .'" /></div>':'<div style="text-align:center"><img width="40%" src="'. Storage::url('public/ecabs/images/logo/default-logo.png'). '" /></div>';
                ($record->id != 1)? $data[] = $nestedData: array();
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

    // get all department for combo box
    public function findall2()
    { 
        return response()->json(Department::where('status', '=', '1')->where('id', '!=', '2')->get());
    }

    // get all department for updates
    public function findall3()
    { 
        $department = Department::where('id', $this->getDepartment(Auth::user())->department_position['department_id'])->get();
        if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
            $department = Department::all();
        }
        return response()->json($department);
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
            'department' => 'required',
            'barangay' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(array('success'=>false, 'messages'=>'Please input valid data!'));
        }else{

            try {
                DB::beginTransaction();
                
                /* save department */
                $department = new Department;
                $department->department = convertData($request['department']);
                $department->acronym = convertData($request['acronym']);
                $department->office_hours = $request['from'] .'-'. $request['to'] ;
                $department->about = convertData($request['about']);
                $department->mission = convertData($request['mission']);
                $department->vision = convertData($request['vision']);
                $department->mobile = convertData($request['mobile']);
                $department->telephone = convertData($request['telephone']);
                $department->email_address = $request['email'];
                $department->website = $request['website'];
                $department->barangay_id = $request['barangay'];
                $department->address = convertData($request['address']);
                $department->employees = $request['employees'];
                $department->status = '1';
                $changes = $department->getDirty(); 
                $department->save();

                if($request->hasFile('logo')) {
                    $filename= 'ecabs/images/logo/' . $department->department .'.'. $request['logo']->getClientOriginalExtension();
                    
                    $size = getimagesize($request['logo']->getRealPath());

                    $img = Image::make($request['logo']->getRealPath())->resize(200, 200);
                    $img->stream();
                    
                    Storage::disk('local')->put('public/'.$filename, $img, 'public');
                }
                $department->logo = !empty($filename)?$filename : 'ecabs/images/logo/default-logo.png';
                $department->save();

                /* create access */
                $access = new PositionAccess;
                $access->position = 'ADMIN';
                $access->access = 'a:5:{i:0;s:13:"createUpdates";i:1;s:13:"updateUpdates";i:2;s:11:"viewUpdates";i:3;s:13:"deleteUpdates";i:4;s:14:"restoreUpdates";}';
                $access->status = '1';
                $changes = array_merge($changes, $access->getDirty()); 
                $access->save();

                /* create position */
                $department_position = new DepartmentPosition;
                $department_position->department_id = $department->id;
                $department_position->position_access_id = $access->id;
                $department_position->status = '1';
                $changes = array_merge($changes, $department_position->getDirty()); 
                $department_position->save();

                DB::commit();

                /* logs */
                action_log('Department mngt', 'CREATE', array_merge(['id' => $department->id], $changes));

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
    public function show(Department $department)
    {
        return response()->json($department);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(),[
            'edit_department'=>'required',
            'barangay' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(array('success'=>false, 'messages'=>'Please input valid data!'));
        }else{
            try {
                DB::beginTransaction();

                /* update department */
                $department->department = convertData($request['edit_department']);
                $department->acronym = convertData($request['edit_acronym']);
                $department->office_hours = $request['edit_from'] .'-'. $request['edit_to'] ;
                $department->about = convertData($request['edit_about']);
                $department->mission = convertData($request['edit_mission']);
                $department->vision = convertData($request['edit_vision']);
                $department->mobile = convertData($request['edit_mobile']);
                $department->telephone = convertData($request['edit_telephone']);
                $department->email_address = $request['edit_email'];
                $department->website = $request['edit_website'];
                $department->barangay_id = $request['barangay'];
                $department->address = convertData($request['edit_address']);
                $department->employees = convertData($request['edit_employees']);
                $changes = $department->getDirty();
                $department->save();

                if($request->hasFile('logo')) {
                    $filename= 'ecabs/images/logo/'. $department->department .'.'. $request['logo']->getClientOriginalExtension();
                    $img = Image::make($request['logo']->getRealPath())->resize(200, 200);
                    $img->stream();
                    
                    Storage::disk('local')->put('public/'.$filename, $img, 'public');

                    $department->logo = !empty($filename)?$filename : 'ecabs/images/logo/default-logo.png';
                    $department->save();
                }

                DB::commit();

                /* logs */
                action_log('Department mngt', 'UPDATE', array_merge(['id' => $department->id], $changes));

                return response()->json(array('success'=>true, 'messages'=>'Record successfully saved!'));
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
            }
        }
    }

    public function togglestatus($id) {

        $department = Department::findOrFail($id);
        $message = '';
        $action = '';

        try {
            DB::beginTransaction();

            if($department->status == '1') {
                $department->status = '0';
                $message = 'Record successfully Deactivated!';
                $action = 'DELETED';
            } else {
                $department->status = '1';
                $message = 'Record successfully Retreived!';
                $action = 'RESTORE';
            }
            $changes = $department->getDirty();
            $department->save();

            DB::commit();

            action_log('Department mngt', $action, array_merge(['id' => $department->id], $changes));

            return response()->json(array('success'=> true, 'messages'=>$message));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
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
