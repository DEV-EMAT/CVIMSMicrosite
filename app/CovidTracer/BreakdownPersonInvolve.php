<?php

namespace App\CovidTracer;

use Illuminate\Database\Eloquent\Model;

class BreakdownPersonInvolve extends Model
{
    protected $connection = "covid_tracer";

    protected $hidden = ["created_at", "updated_at"];
}
