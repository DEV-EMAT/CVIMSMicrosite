<?php

namespace App\CovidTracer;

use Illuminate\Database\Eloquent\Model;

class HomeAddress extends Model
{
    //
    protected $connection = "covid_tracer";

    protected $hidden = ["created_at", "updated_at"];

    public function patient_profile()
    {
        return $this->belongsTo('App\CovidTracer\PatientProfile');
    }
}
