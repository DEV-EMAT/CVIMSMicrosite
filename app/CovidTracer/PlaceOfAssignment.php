<?php

namespace App\CovidTracer;

use Illuminate\Database\Eloquent\Model;

class PlaceOfAssignment extends Model
{
    protected $connection = "covid_tracer";

    protected $hidden = ["created_at", "updated_at"];

    public function investigator()
    {
        return $this->belongsTo('App\CovidTracer\Investigator');
    }
}
