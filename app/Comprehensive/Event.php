<?php

namespace App\Comprehensive;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $connection = "comprehensive";

    // protected $hidden = ["created_at", "updated_at"];

    // public function department()
    // {
    //     return $this->belongsTo('App\Ecabs\Department', 'ecabs');
    // }

    public function event_summary()
    {
        return $this->hasMany('App\Comprehensive\EventSummary');
    }
}
