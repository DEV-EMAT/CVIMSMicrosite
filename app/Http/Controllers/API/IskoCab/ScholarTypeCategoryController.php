<?php

namespace App\Http\Controllers\API\IskoCab;

use App\Http\Controllers\Controller;
use App\IskoCab\ScholarTypeCategory;
use Auth;
use DB;

class ScholarTypeCategoryController extends Controller
{
    //
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;
    
    public function getScholarTypeCategory()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                
                $scholar_type_category = ScholarTypeCategory::where('status', '=', 1)
                                        ->select(
                                            'id',
                                            'scholar_type'
                                        )->get();
                
                DB::commit();
            
                return response()->json(['success' => $this->successStatus, 'data' => $scholar_type_category], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
