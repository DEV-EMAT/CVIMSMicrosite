<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Covid19Vaccine\EmploymentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmploymentStatusController extends Controller
{
    public function findAllForCombobox(){
        return EmploymentStatus::where('status', '=', '1')->orderBy('id', 'ASC')->get();
    }
}
