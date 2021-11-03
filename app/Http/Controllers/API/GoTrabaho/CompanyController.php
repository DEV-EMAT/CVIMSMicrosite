<?php

namespace App\Http\Controllers\API\GoTrabaho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GoTrabaho\Company;
use Auth;
use DB;

class CompanyController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;
    
    public function getMyCompany()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                
                $companies = Company::join('company_contacts', 'companies.id', 'company_contacts.company_id')
                            ->where('companies.status', '=', 1)
                            ->where('company_contacts.status', '=', 1)
                            ->where('companies.user_id', '=', Auth::user()->id)->get();
                
                DB::commit();
            
                return response()->json(['success' => $this->successStatus, 'data' => $companies, 'message' => 'Company profiles retrieved successfully.'], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }
}
