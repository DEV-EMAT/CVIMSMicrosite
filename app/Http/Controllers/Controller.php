<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUserInfo($auth){
        return \App\User::findOrFail($auth->id)->with('person')->first();
    }

    public function getDepartment($auth){
        return \App\Ecabs\PersonDepartmentPosition::where('person_id', '=', $auth->person_id)->with('department_position', 'department_position.departments')->first();
    }

    public function getDepartmentByUser_Id($id){
        return \App\Ecabs\PersonDepartmentPosition::where('person_id', '=', $id)->with('department_position', 'department_position.departments')->first();
    }
}
