<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\EstablishmentCategory;
use App\Events\DataTableEvent;
use Response;
use DB;
use Gate;

class EstablishmentCategoryController extends Controller
{
    public function index()
    {
        return view('covidtracer/establishment_category.index', ['title' => "Establishment Category Management"]);
    }

    public function create()
    {
        //

    }

    public function store(Request $request)
    {
        $this -> validate ($request, [
            'description'=>'required',
        ]);
        
        try {
            DB::beginTransaction();

            $est_category = new EstablishmentCategory;
            $est_category->description = convertData($request["description"]);
            $est_category->status = 1;
            $changes = $est_category->getDirty();
            $est_category->save();

            DB::commit();

            /* logs */
            action_log('Establishment Category Mngt', 'CREATE', array_merge(['id' => $est_category->id], $changes));
            
            return response()->json(array('success' => true, 'messages' => 'Successfully Created!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function show($id)
    {
        $est_category = EstablishmentCategory::find($id);
        
        return response::json($est_category);
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $this -> validate ($request, [
            'description'=>'required',
        ]);
        try {
            DB::beginTransaction();
            
            $est_category = EstablishmentCategory::findOrFail($id);
            $est_category->description =  convertData($request["description"]);
            $changes = $est_category->getDirty();
            $est_category->save();

            DB::commit();

            /* logs */
            action_log('Establishment Category Mngt', 'UPDATE', array_merge(['id' => $est_category->id], $changes));
            
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

    public function findall(Request $request)
    {
        $columns = array( 
            0 =>'description', 
            1 =>'status',
        );

        $totalData = EstablishmentCategory::count();

        $totalFiltered = $totalData; 


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $est_categories = EstablishmentCategory::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query = EstablishmentCategory::where('description','LIKE',"%{$search}%");

            $est_categories =  $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = $query->count();
        }

        $data = array();
        if(!empty($est_categories))
        {
            foreach ($est_categories as $est_category)
            {  
                $buttons = '';  
                
                if($est_category['status'] == '1'){
                    if(Gate::allows('permission', 'updateEstcat')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to edit Establishment Category" onclick="edit('. $est_category['id'] .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ' ;
                    }

                    if(Gate::allows('permission', 'deleteEstcat')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to remove Establishment Category" onclick="deactivate('. $est_category['id'] .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';    
                    }
                    $status = "<label class='label label-primary'>Active</label>";
                }
                else{
                    if(Gate::allows('permission', 'restoreEstcat')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to restore Establishment Category" onclick="activate('. $est_category['id'] .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }
                
                $nestedData['description'] = $est_category->description;
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
    
    //get all establishment category for combo box
    public function findallforcombobox()
    {
        $establishment_category = EstablishmentCategory::where('status', '1')->get();

        return response()->json($establishment_category);
    }

    //toggle status
    public function togglestatus($id){
        try {
            DB::beginTransaction();

            $est_category = EstablishmentCategory::findOrFail($id);
            $status = $est_category->status;
            $action = '';

            if($status == 1){
                $est_category->status = 0;
                $action = 'DELETED';
            }
            else{                
                $est_category->status = 1;
                $action = 'RESTORE';
            }
            $changes = $est_category->getDirty();
            $est_category->save();
            
            DB::commit();

            /* logs */
            action_log('Establishment Category Mngt', $action, array_merge(['id' => $est_category->id], $changes));

            return response()->json(array('success' => true, 'messages' => 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
