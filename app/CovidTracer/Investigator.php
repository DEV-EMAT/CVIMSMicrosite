<?php

namespace App\CovidTracer;

use Illuminate\Database\Eloquent\Model;

class Investigator extends Model
{
    //
    protected $connection = "covid_tracer";

    protected $hidden = ["created_at", "updated_at"];

    public function place_of_assigmnent()
    {
        return $this->hasMany('App\CovidTracer\PlaceOfAssignment', 'investigator_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
