<?php

namespace App\Http\Controllers\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DB;
use Image;
use Storage;
use Gate;

use App\Ecabs\Update;
use App\Ecabs\Department;
use App\Events\DataTableEvent;
use App\Ecabs\UpdateAccountDepartment;
use App\Ecabs\PersonDepartmentPosition;

class UpdateController extends Controller
{
    public function index()
    {
        $department_status = 0;
        if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
            $department_status = 1;
        }

        return view('ecabs.updates.index', ['title' => "Announcement Management", 'department_status' => $department_status]);
    }

    public function create()
    {   
        $department_status = 0;
        if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
            $department_status = 1;
        }
        return view('ecabs.updates.create', ['title' => "Create Announcement", 'department_status' => $department_status]);
    }

    public function findall(Request $request)
    {
        $columns = array(
            0 => 'title',
            1 => 'id',
            2 => 'title',
        );
        // $columns = array('id', 'title');

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
            $query = DB::table('updates')->select('updates.*');
        }else{
            $query = DB::table('updates')->select('updates.*')
            ->join('update_account_departments', 'update_account_departments.update_id', '=', 'updates.id')
            // ->join('users', 'users.person_id', '=', 'update_account_departments.user_id')
            // ->join('person_department_positions', 'person_department_positions.person_id', '=', 'users.person_id')
            // ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
            // ->join('departments', 'departments.id', '=', 'department_positions.department_id')
            // ->where('departments.id', '=', $this->getDepartment(Auth::user())->department_position['department_id'])
            // ->orWhere('departments.id', '!=', $this->getDepartment(Auth::user())->department_position['department_id'])
            // ->where('update_account_departments.status', '=', '1')
            // ->where('updates.status', '=', '1')
            ->where('update_account_departments.merging_dept_id', '=', $this->getDepartment(Auth::user())->department_position['department_id']);
        }

        // dd($this->getDepartment(Auth::user())->department_position['department_id']);

        //datatables total data
        $totalData = $query->count();
        $totalFiltered = $totalData; 

        if(empty($request->input('search.value')))
        {            

            $record_list = $query->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $query =  $query->where('updates.title', 'like', "%{$search}%");

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
                $buttons = "";
                if($record->status == '1'){
                    
                    if(Gate::allows('permission', 'updateUpdates')){
                        $buttons = '<a data-toggle="tooltip" title="Click here to edit Announcement" title="Edit" onclick="edit('. $record->id .')" class="btn btn-xs btn-success btn-fill btn-rotate edit"><i class="ti-pencil-alt"></i> EDIT</a></button> ' ;
                    }

                    if(Gate::allows('permission', 'deleteUpdates')){
                        $buttons .= '<a data-toggle="tooltip" title="Click here to delete Announcement" onclick="deactivate('. $record->id .')"  class="btn btn-xs btn-danger btn-fill btn-rotate remove"><i class="ti-trash"></i> DELETE</a>';  
                    }  

                    $status = "<label class='label label-primary'>Active</label>";
                    
                } else {
                    
                    if(Gate::allows('permission', 'restoreUpdates')){
                        $buttons = '<a data-toggle="tooltip" title="Click here to restore Announcement" onclick="activate('. $record->id .')"  class="btn btn-xs btn-primary btn-fill btn-rotate remove"><i class="ti-reload"></i> RESTORE</a>';
                    }
                    $status = "<label class='label label-danger'>Deleted</label>";
                }
                
                $xmlString = Storage::get('public/ecabs/updates/'. date('Y') . '' . $record->id.'.xml');

                $post_user = UpdateAccountDepartment::join('users', 'users.id', '=', 'update_account_departments.user_id')
                            ->join('people', 'people.id', '=', 'users.person_id')
                            ->where('update_id', '=', $record->id)
                            ->first();
                
                $department = '';
                if(!empty($post_user->merging_dept_id)){
                    $department = Department::findOrFail($post_user->merging_dept_id);       
                }else{
                    $department = Department::findOrFail($this->getDepartment(Auth::user())->department_position['department_id']); 
                }      

                $middleInitial =(!empty($post_user->middle_name))? $post_user->middle_name[0].'.':'';

                $nestedData['id'] = $record->id;
                $nestedData['title'] = $record->title;
                $nestedData['content'] = $xmlString;
                $nestedData['images'] = array_map(function($value){ return Storage::url('public/ecabs/images/updates/'.$value); }, unserialize($record->images_path));
                $nestedData['post_info'] = array('department' => $department->department, 'date_created' => $record->created_at, 'user' => $post_user->first_name .' '. $middleInitial .'. '. $post_user->last_name, 'category' => !empty($record->category) ? $record->category : 'NO CATEGORY');
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

    public function store(Request $request)
    {
        
        $validator=Validator::make($request->all(), [ 
            'title'=> ' required',
            'updates_image'=> ' image|mimes:jpeg,png,jpg,gif',
        ]);

        try {
            DB::beginTransaction();

            $update = new Update;
            $update->category = $request['category'];
            $update->title = convertData($request['updates_title']);
            $update->status = '1';
            $changes = $update->getDirty();
            $update->save(); 

            $image_list = array();
            $count = 1;
            if($request['updates_image'])
            {
                foreach($request['updates_image'] as $image)
                {
                    $filename=date('Y') . '' . $update->id . '' . $count . '' . rand(pow(10, 4-1), pow(10, 4)-1) . '.' . $image->getClientOriginalExtension();
                    //add files to array
                    array_push($image_list, $filename);                     

                    $img = Image::make($image->getRealPath());
                    $img->stream();

                    // $img->save('storage/ecabs/images/updates/' . $filename, 20);
                    
                    Storage::disk('local')->put('public/ecabs/images/updates/'.$filename, $img, 'public');
                    $data[] = $filename;  
                    $count++;                    
                }
            }
            
            $update->images_path = serialize($image_list);
            
            Storage::append('public/ecabs/updates/' . date('Y') . '' . $update->id . '.xml', $request['content']);
            $update->content_path = date('Y') . '' . $update->id . '.xml';
            $update->save(); 

            $update_account_department = new UpdateAccountDepartment;
            $update_account_department->update_id = $update->id;
            $update_account_department->user_id = Auth::id();

            //query from PersonDepartmentPosition to Department
            $person_department_position = PersonDepartmentPosition::where('person_id', Auth::user())->with(['department_position'=> function($query){
                $query -> join('departments', 'department_positions.department_id', 'departments.id');
            }])->first();
            

            if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
                if($request['department']){
                    if($this->getDepartment(Auth::user())->department_position['department_id'] == $request['department']){
                        $deptId = $this->getDepartment(Auth::user())->department_position['department_id'];
                        $update_account_department->status = '0';
                        $update_account_department->merging_dept_id = $deptId;
                    }
                    else{
                        $update_account_department->merging_dept_id = $request['department'];
                        $update_account_department->status = '1';
                    }
                }
            }
            else{
                $deptId = $this->getDepartment(Auth::user())->department_position['department_id'];
                $update_account_department->status = '0';
                $update_account_department->merging_dept_id = $deptId;
            }
            $changes = array_merge($changes, $update_account_department->getDirty());
            $update_account_department->save();

            DB::commit();
            
            /* logs */
            action_log('Updates Mngt', 'Create', array_merge(['id' => $update->id], $changes));

            // event(new DataTableEvent(true));

            return response()->json(array('success'=> true, 'messages'=>'Record successfully Saved!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }   
    
    // Compress image
    function compressImage($source, $quality) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

        // imagejpeg($image, $destination, $quality);
        return $image;
    
    }

    public function show($id)
    {
        $updates = Update::findOrFail($id);
        $content = Storage::get('public/ecabs/updates/'.$updates->content_path);
        $images = array_map(function($value){ return "<img src='storage/ecabs/images/updates/" . $value . "'>"; }, unserialize($updates->images_path));
        $update_department = UpdateAccountDepartment::where('update_id', $updates->id)->first();
        $department = $update_department->merging_dept_id;

        if($update_department->status == 0){
            $department = $this->getDepartment(Auth::user())->department_position['department_id'];
        }
        return response()->json(array($updates, $content, $department, $images));   
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(), [ 
            'title'=> ' required',
            'updates_image'=> ' image|mimes:jpeg,png,jpg,gif',
        ]);

        try {
            DB::beginTransaction();

            $update = Update::findOrFail($id);
            $update->category = $request['category'];
            $update->title = mb_strtoupper($request['updates_title']);
            $changes = $update->getDirty();
            $update->status = '1';

            $image_list = array();
            $count = 1;

            if($request['updates_image'] && $request['images'] == null)
            {
                foreach($request['updates_image'] as $image)
                {
                    $filename=date('Y') . '' . $id . '' . $count . '' . rand(pow(10, 4-1), pow(10, 4)-1) . '.' . $image->getClientOriginalExtension();
                    
                    //add files to array
                    array_push($image_list, $filename);
                    
                    // $path=public_path('images/updates'); 
                    // if(!file_exists($path)){
                    //     mkdir($path, 666, true);
                    // }
                    
                    $img = Image::make($image->getRealPath());
                    $img->stream();

                    Storage::disk('local')->put('public/ecabs/images/updates/'.$filename, $img, 'public');

                    // Image::make($image->getRealPath())->resize(200, 200)->save($path . '/' . $filename);
                    $data[] = $filename;  
                    $count++;
                }

                //remove old images
                $oldImages = unserialize($update->images_path);
                foreach($oldImages as $path){
                    unlink('storage/ecabs/images/updates/' . $path);
                }
                
                $update->images_path = serialize($image_list);
            }
            
            $update->content_path = date('Y') . '' . $id . '.xml';
            $update->save(); 

            $update_account_department = UpdateAccountDepartment::where('update_id', $update->id)->first();

            //query from PersonDepartmentPosition to Department
            $person_department_position = PersonDepartmentPosition::where('person_id', Auth::user()->person_id)->with(['department_position'=> function($query){
                $query -> join('departments', 'department_positions.department_id', 'departments.id');
            }])->first();
            
            if($this->getDepartment(Auth::user())->department_position['department_id'] == 1){
                if($request['department']){
                    if($this->getDepartment(Auth::user())->department_position['department_id'] == $request['department']){
                        $update_account_department->status = '0';
                    }
                    else{
                        $update_account_department->merging_dept_id = $request['department'];
                        $update_account_department->status = '1';
                    }
                }
            }
            else{
                $update_account_department->status = '0';
            }
            $changes = array_merge($changes, $update_account_department->getDirty());
            $update_account_department->save();
            
            Storage::delete('public/ecabs/updates/' . date('Y') . '' . $id . '.xml', $request['content']);
            Storage::append('public/ecabs/updates/' . date('Y') . '' . $id . '.xml', $request['content']);
            DB::commit();
            
            /* logs */
            action_log('Updates Mngt', 'update', array_merge(['id' => $update->id], $changes));

            // event(new DataTableEvent(true));

            return response()->json(array('success'=> true, 'messages'=>'Record successfully Saved!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }

    public function destroy($id)
    {
        //
    }

    //deactivate or activate data
    public function togglestatus($id) {  

        $updates = Update::findOrFail($id);
        $status=$updates->status;

        try {
            DB::beginTransaction();
            if($status==1){
                $updates->status = 0;
                $action = 'DELETED';
            }else {
                $updates->status = 1;
                $action = 'RESTORE';
            }
            $changes = $updates->getDirty();
            $updates->save();

            DB::commit();

            /* logs */
            action_log('Updates Mngt', $action, array_merge(['id' => $updates->id], $changes));

            return response()->json(array('success'=> true, 'messages'=> 'Successfully Updated!'));
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('success'=> false, 'error'=>'SQL error!', 'messages'=>'Transaction failed!'));
        }
    }
}
