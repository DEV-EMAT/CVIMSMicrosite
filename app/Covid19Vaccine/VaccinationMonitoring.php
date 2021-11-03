<?php

namespace App\Covid19Vaccine;

use Illuminate\Database\Eloquent\Model;

class VaccinationMonitoring extends Model
{
    protected $connection = "covid19vaccine";

    protected $hidden = ["updated_at"];

    public function qualified_patient()
    {
        return $this->belongsTo('App\Covid19Vaccine\QualifiedPatient');
    }
}
