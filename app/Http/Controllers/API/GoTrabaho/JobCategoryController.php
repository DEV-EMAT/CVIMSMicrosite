<?php

namespace App\Http\Controllers\API\GoTrabaho;

use App\GoTrabaho\JobCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class JobCategoryController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;
    
    public function getJobCategory()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                
                $get_job_categories = JobCategory::all();
                
                DB::commit();
            
                return response()->json(['success' => $this->successStatus, 'data' => $get_job_categories, 'message' => 'Job Categories retrieved successfully.'], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }
}
