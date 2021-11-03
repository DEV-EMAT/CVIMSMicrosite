<?php

namespace App\Comprehensive;

use Illuminate\Database\Eloquent\Model;

class EventSummary extends Model
{
    protected $connection = "comprehensive";

    public function event()
    {
        return $this->belongsTo('App\Comprehensive\Event');
    }
}
