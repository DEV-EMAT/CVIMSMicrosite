<?php

namespace App\Http\Controllers\API\IskoCab;

use App\Http\Controllers\Controller;
use App\IskoCab\School;
use Illuminate\Http\Request;
use Auth;
use DB;

class SchoolController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getSchool()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                // $school = School::select('id','school_name')->where('status', '=', 1)->paginate(6)->get();
                $school = School::select('id','school_name')->where('status', '=', 1)->orderBy('school_name')->get();

                DB::commit();

                return response()->json(['success' => $this->successStatus, 'data' => $school, 'message' => 'Schools retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }
}
