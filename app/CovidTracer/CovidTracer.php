<?php

namespace App\CovidTracer;

use Illuminate\Database\Eloquent\Model;

class CovidTracer extends Model
{
    //
    protected $connection = "covid_tracer";

    protected $fillable = [
        'temperature',
        'transaction_one',
        'transaction_two',
        'type',
        'location',
        'establishment_category_id',
        'status'
    ];

    protected $hidden = [
        'id',
        // 'created_at',
        'updated_at',
    ];
}
