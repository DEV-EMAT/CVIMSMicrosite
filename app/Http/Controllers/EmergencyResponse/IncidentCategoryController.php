<?php

namespace App\Http\Controllers\EmergencyResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmergencyResponse\IncidentCategory;
use App\Http\Controllers\Auth\RegisterController;
use DB;
use Response;
use Gate;
class IncidentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('emergencyresponse.incident_category.index', ['title' => 'Incident Category']);
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
        $this -> validate ($request, [
            'IncidentCategoryDescription'=>'required',
        ]);

        try {
            DB::beginTransaction();
            
            $incidentCategory = new IncidentCategory;
            $incidentCategory->description = convertData($request["IncidentCategoryDescription"]);
            $incidentCategory->status = 1;
            $changes = $incidentCategory->getDirty();
            $incidentCategory->save();
            DB::commit();
            /* logs */
            action_log('Incident Mngt', 'CREATE', array_merge(['id' => $incidentCategory->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Saved!'));
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
        $incidentCategory = IncidentCategory::find($id);
        return response::json($incidentCategory);
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
        try {
            DB::beginTransaction();
            $incidentCagtegory = IncidentCategory::findOrFail($id);
            $incidentCagtegory->description =  convertData($request["edit_incident_category_desc"]);
            $changes = $incidentCagtegory->getDirty();
            $incidentCagtegory->save();


            DB::commit();

            /* logs */
            action_log('Incident Category', 'Update', array_merge(['id' => $incidentCagtegory->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
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

    public function findAll(Request $request)
    {
        $columns = array( 
            0 =>'description',
            1=> 'status',
            2=> 'actions',
        );

        $totalData = IncidentCategory::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $incidentCategory = IncidentCategory::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $incidentCategory =  IncidentCategory::where('description','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = IncidentCategory::where('description','LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($incidentCategory))
        {
            foreach ($incidentCategory as $incidentcategories)
            {   
                // $buttons = "";
               if(Gate::allows('permission', 'updateIncidentCategory')){
                    $buttons = '<a href="#" title="Edit" onclick="edit('. $incidentcategories['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ';
                }else{
                    $buttons = 'Not Applicable!';
                }
                   
                if($incidentcategories['status'] == '1'){
                    if(Gate::allows('permission', 'deleteIncidentCategory')){
                        $buttons .= '<a href="#" onclick="deactivate('. $incidentcategories['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';
                    }
                    $status = "<label class='label label-primary'>Active</label>";
                }
                else{
                if(Gate::allows('permission', 'restoreIncidentCategory')){
                        $buttons = '<a href="#"  onclick="activate('. $incidentcategories['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }
                $nestedData['description'] = $incidentcategories->description;
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

     //toggle status
     public function updateStatus($id){
        try {
            DB::beginTransaction();

            $incidentCategory = IncidentCategory::findOrFail($id);
            $status = $incidentCategory->status;
            if($status == 1){
                $incidentCategory->status = 0;
                $action = 'DELETED';
            }
            else{
                $incidentCategory->status = 1;
                $action = 'RESTORE';
            }
            $changes = $incidentCategory->getDirty(); 
            $incidentCategory->save();

            DB::commit();

            /* logs */
            action_log('Incident Category', $action, array_merge(['id' => $incidentCategory->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }



}
