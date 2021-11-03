<?php

namespace App\CovidTracer;

use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    protected $connection = "covid_tracer";

    protected $hidden = ["created_at", "updated_at"];

    public function home_address()
    {
        return $this->hasOne('App\CovidTracer\HomeAddress', 'patient_profile_id', 'id');
    }
}
