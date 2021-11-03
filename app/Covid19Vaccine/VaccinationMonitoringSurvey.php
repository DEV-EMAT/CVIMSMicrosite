<?php

namespace App\Covid19Vaccine;

use Illuminate\Database\Eloquent\Model;

class VaccinationMonitoringSurvey extends Model
{
    protected $connection = "covid19vaccine";

    protected $hidden = ["created_at", "updated_at"];
}
