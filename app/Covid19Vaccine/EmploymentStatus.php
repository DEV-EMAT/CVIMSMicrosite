<?php

namespace App\Covid19Vaccine;

use Illuminate\Database\Eloquent\Model;

class EmploymentStatus extends Model
{
    protected $connection = "covid19vaccine";
    protected $hidden = ["created_at", "updated_at"];

    public function pre_registration()
    {
        return $this->belongsTo('App\Covid19Vaccine\PreRegistration');
    }
}
