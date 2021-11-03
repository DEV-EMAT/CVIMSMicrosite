<?php

namespace App\Http\Controllers\API\GoTrabaho;

use App\GoTrabaho\JobCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class JobController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;
    
    public function SearchJob(Request $request)
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                
                $job_search = JobCategory::join('job_vacancies', 'job_categories.id', 'job_vacancies.job_categories_id')
                                ->join('company_contacts', 'job_vacancies.company_contacts_id', 'company_contacts.id')
                                ->where('job_categories.category_description', 'LIKE', "%$request->search_key%")->paginate(6)->get();
                
                DB::commit();
            
                return response()->json(['success' => $this->successStatus, 'data' => $job_search, 'message' => 'Available job vacancies retrieved successfully.'], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }
}
