<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Covid19Vaccine\Vaccine;
use App\Covid19Vaccine\VaccineCategory;
use Response;
use Validator;
use DB;
use Gate;

class VaccineCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('covid19_vaccine.vaccine_category.index',['title' => "Vaccine Category Management"]);
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
        $validator = Validator::make($request->all(), [
            'vaccine_manufacturer'=> 'required',
            'vaccine_name'=> 'required',
        ]);

        DB::connection('covid19vaccine')->beginTransaction();
        try {
            $vaccineCategory = new VaccineCategory;
            $vaccineCategory->vaccine_name = convertData($request["vaccine_name"]);
            $vaccineCategory->vaccine_manufacturer = convertData($request["vaccine_manufacturer"]);
            $vaccineCategory->status = 1;
            $changes = $vaccineCategory->getDirty();
            $vaccineCategory->save();
    
            DB::connection('covid19vaccine')->commit();
    
            /* logs */
            action_log('Vaccine Category Mngt', 'CREATE', array_merge(['id' => $vaccineCategory->id], $changes));
    
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
    
            DB::connection('covid19vaccine')->rollBack();
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
        $vaccineCategory = VaccineCategory::find($id);
        
        return response::json($vaccineCategory);
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
        $validator = Validator::make($request->all(), [
            'edit_vaccine_manufacturer'=> 'required',
            'edit_vaccine_name'=> 'required',
        ]);

        DB::connection('covid19vaccine')->beginTransaction();
        try {
            $vaccineCategory = VaccineCategory::where('id', '=', $id)->first();
            $vaccineCategory->vaccine_name = convertData($request["edit_vaccine_name"]);
            $vaccineCategory->vaccine_manufacturer = convertData($request["edit_vaccine_manufacturer"]);
            $vaccineCategory->status = 1;
            $changes = $vaccineCategory->getDirty();
            $vaccineCategory->save();
    
            DB::connection('covid19vaccine')->commit();
    
            /* logs */
            action_log('Vaccine Category Mngt', 'UPDATE', array_merge(['id' => $vaccineCategory->id], $changes));
    
            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
    
            DB::connection('covid19vaccine')->rollBack();
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
    
    //for combo box
    public function findAllVaccine()
    {
        $vaccineCategory = VaccineCategory::where('status', 1)->orderBy('vaccine_name')->get();
        
        return response()->json($vaccineCategory);
    }
    
    //vaccinator datatable
    public function findAll(Request $request)
    {
        $columns = array( 
            0=> 'vaccine_name',
            1=> 'vaccine_manufacturer',
            2=> 'status',
        );

        $totalData = VaccineCategory::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $vaccineCategories = VaccineCategory::
                        offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 
            
            $vaccineCategories = VaccineCategory::where('vaccine_categories.vaccine_name', 'LIKE',"%{$search}%")
            ->orWhere('vaccine_categories.vaccine_manufacturer', 'LIKE',"%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = VaccineCategory::where('vaccine_categories.vaccine_name', 'LIKE',"%{$search}%")
            ->orWhere('vaccine_categories.vaccine_manufacturer', 'LIKE',"%{$search}%")
            ->count();
        }
        $buttons = "";
        $data = array();
        if(!empty($vaccineCategories))
        {
            foreach ($vaccineCategories as $vaccineCategory)
            {   
                $btnEdit = $btnToggle = $role = '';
                if($vaccineCategory['status'] == '1'){
                    if(Gate::allows('permission', 'updateVaccineCategory')){
                        $btnEdit = '<a href="#" data-toggle="tooltip" title="Click to edit vaccine category." onclick="edit('. $vaccineCategory['id'] . ')" class="btn btn-xs btn-info btn-fill btn-rotate edit"><i class="ti ti-pencil-alt" aria-hidden="true"></i> Edit</a></button> ';
                    }
                    
                    if(Gate::allows('permission', 'deleteVaccineCategory')){
                        $btnToggle = '<a href="#" data-toggle="tooltip" title="Click to delete vaccine category." onclick="deactivate('. $vaccineCategory['id'] . ')" class="btn btn-xs btn-danger btn-fill btn-rotate edit"><i class="ti ti-trash" aria-hidden="true"></i> Delete</a></button> ';
                    }
                }else{
                    if(Gate::allows('permission', 'restoreVaccineCategory')){
                        $btnToggle = '<a href="#" data-toggle="tooltip" title="Click to restore vaccine category." onclick="restore('. $vaccineCategory['id'] . ')" class="btn btn-xs btn-primary btn-fill btn-rotate edit"><i class="ti ti-reload" aria-hidden="true"></i> Restore</a></button> ';
                    }
                }
                $buttons = $btnEdit . " " . $btnToggle;
            
                $status = ($vaccineCategory['status'] == "1") ?  "<label class='label label-success'><i class='fa fa-check-circle' aria-hidden='true'></i> Active</label>" : "<label class='label label-danger'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> Deleted</label>";
                
                $nestedData['vaccine_name'] = $vaccineCategory['vaccine_name'];
                $nestedData['vaccine_manufacturer'] = $vaccineCategory['vaccine_manufacturer'];
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
    
    public function togglestatus($id){
        try {
            DB::beginTransaction();

            $vaccineCategory = VaccineCategory::findOrFail($id);
            $status = $vaccineCategory->status;
            if($status == 1){
                $vaccineCategory->status = 0;
                $action = 'DELETED';
            }
            else{
                $vaccineCategory->status = 1;
                $action = 'RESTORE';
            }
            $changes = $vaccineCategory->getDirty(); 
            $vaccineCategory->save();

            DB::commit();

            /* logs */
            action_log('Vaccinator Mngt', $action, array_merge(['id' => $vaccineCategory->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
