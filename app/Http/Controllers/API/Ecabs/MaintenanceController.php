<?php

namespace App\Http\Controllers\API\Ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;


class MaintenanceController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function checkUnderMaintenance(Request $request){
        try {
            return response()->json(['Undermaintenance' => checkMaintenaince($request->module)], $this->successStatus);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json($e, $this->queryErrorStatus);
        } 
    }
}
