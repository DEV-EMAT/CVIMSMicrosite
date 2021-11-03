<?php

namespace App\Http\Controllers\API\IskoCab;

use App\Http\Controllers\Controller;
use App\IskoCab\Course;
use Illuminate\Http\Request;
use Auth;
use DB;

class CourseController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getCourse()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                // $courses = Course::select('id','course_code','course_description')->where('status', '=', 1)->paginate(6)->get();
                $courses = Course::select('id','course_code','course_description')->where('status', '=', 1)->orderBy('course_description')->get();

                DB::commit();

                return response()->json(['success' => $this->successStatus, 'data' => $courses, 'message' => 'Courses retrieved successfully.'], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }
}
