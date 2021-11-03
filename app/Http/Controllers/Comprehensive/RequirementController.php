<?php

namespace App\Http\Controllers\Comprehensive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comprehensive\Requirement;
use App\Comprehensive\RequirementHasDepartment;
use App\Ecabs\Department;
use Validator;
use DB;
use Response;
use Auth;
use Gate;

class RequirementController extends Controller
{
    public function index()
    {
        $department = $this->getDepartment(\Auth::user())->department_position['department_id'];
        $status = ($department == '1')? true : false;

        return view('comprehensive.requirements.index', ['title' => "Requirements Management", 'department_status' => $status]);
    }

    public function findall(Request $request)
    {
        $columns = array(
            0 =>'name',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
            $query = DB::table(connectionName('comprehensive').'.requirements')->select('requirements.*', 'requirement_has_departments.department_id')
            ->join(connectionName('comprehensive').'.requirement_has_departments', 'requirement_has_departments.requirement_id', '=', 'requirements.id');
        }else{
            $query = DB::table(connectionName('comprehensive').'.requirements')->select('requirements.*', 'requirement_has_departments.department_id')
            ->join(connectionName('comprehensive').'.requirement_has_departments', 'requirement_has_departments.requirement_id', '=', 'requirements.id')
            ->where('requirement_has_departments.department_id', '=', $this->getDepartment(Auth::user())->department_position['department_id']);
        }

        if($request["action"] == "programManagement"){
            $query->where('requirements.status', 1);
        }

        //datatables total data
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $requirements = $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
        }
        else {
            $search = $request->input('search.value');

            $query =  $query->where('requirements.name', 'like', "%{$search}%");

            $requirements =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($requirements))
        {
            foreach ($requirements as $requirement)
            {
                $buttons = "";
                if($requirement->status == '1'){

                    if(Gate::allows('permission', 'updateRequirement')){
                        $buttons = '<a data-toggle="tooltip" title="Click here to edit Announcement" title="Edit" onclick="edit('. $requirement->id .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ' ;
                    }

                    if(Gate::allows('permission', 'deleteRequirement')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to delete Announcement" onclick="del('. $requirement->id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';
                    }

                    $status = "<label class='label label-primary'>Active</label>";

                } else {

                    if(Gate::allows('permission', 'restoreRequirement')){
                        $buttons = '<a data-toggle="tooltip" title="Click here to restore Announcement" onclick="restore('. $requirement->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }

                $department = Department::where('id', '=', $requirement->department_id)->first();

                // $department = '';
                // if(!empty($post_user->merging_dept_id)){
                //     $department = Department::findOrFail($post_user->merging_dept_id);
                // }else{
                //     $department = Department::findOrFail($this->getDepartment(Auth::user())->department_position['department_id']);
                // }

                // $middleInitial =(!empty($post_user->middle_name))? $post_user->middle_name[0].'.':'';

                $nestedData['id'] = $requirement->id;
                $nestedData['name'] = $requirement->name;
                $nestedData['department'] = $department->department;
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $department = $this->getDepartment(\Auth::user())->department_position['department_id'];
        $status = ($department == '1')? true : false;

        $rules = [
            'name'=>'required',
            'description'=>'required',
        ];
        if($status){
            $rules = array_merge($rules, [ 'department'=>'required']);
        }

        $this->validate ($request, $rules);


        try {
            DB::beginTransaction();
            $requirement = new Requirement;
            $requirement->name = convertData($request["name"]);
            $requirement->description = convertData($request["description"]);
            $requirement->status = '1';
            $changes = $requirement->getDirty();
            $requirement->save();

            $requirementDepartment = new RequirementHasDepartment;
            $requirementDepartment->requirement_id = $requirement->id;
            $requirementDepartment->department_id =($status)?$request['department']:$department;
            $requirementDepartment->status = '1';
            $changes = array_merge($changes, $requirementDepartment->getDirty());
            $requirementDepartment->save();

            DB::commit();

            /* logs */
            action_log('Requirement Mngt', 'CREATE', array_merge(['id' => $requirement->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function show($id)
    {
        $requirement = Requirement::find($id);
        $requirementHasDepartment = RequirementHasDepartment::where('requirement_id', '=', $requirement->id)->first();
        $department = Department::findOrFail($requirementHasDepartment->department_id);

        return response()->json(array("requirement" => $requirement, "department" => $department ));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {

        $department = $this->getDepartment(\Auth::user())->department_position['department_id'];
        $status = ($department == '1')? true : false;

        try {
            DB::beginTransaction();

            $requirement = Requirement::findOrFail($id);
            $requirement->name =  $request["edit_name"];
            $requirement->description = convertData($request["edit_description"]);
            $changes = $requirement->getDirty();
            $requirement->save();

            $requirementDepartment = RequirementHasDepartment::where('requirement_id', '=', $requirement->id)->first();
            $requirementDepartment->department_id = ($status)?$request['edit_department']:$department;
            $changes = array_merge($changes, $requirementDepartment->getDirty());
            $requirementDepartment->save();

            DB::commit();

            /* logs */
            action_log('Requirement Mngt', 'UPDATE', array_merge(['id' => $requirement->id], $changes));

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
    public function toggleStatus($id){
        try {
            DB::beginTransaction();

            $requirement = Requirement::findOrFail($id);
            $requirementDepartment = RequirementHasDepartment::where('requirement_id', '=', $requirement->id)->first();
            $status = $requirement->status;
            if($status == 1){
                $requirement->status = 0;
                $requirementDepartment->status = 0;
                $action = 'DELETED';
            }
            else{
                $requirement->status = 1;
                $requirementDepartment->status = 0;
                $action = 'RESTORE';
            }
            $changes = $requirement->getDirty();
            $changes = array_merge($changes, $requirementDepartment->getDirty());
            $requirement->save();
            $requirementDepartment->save();

            DB::commit();

            /* logs */
            action_log('Requirement Mngt', $action, array_merge(['id' => $requirement->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
