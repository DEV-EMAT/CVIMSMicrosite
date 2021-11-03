<?php

namespace App\Comprehensive;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $connection = "comprehensive";

    protected $hidden = ["created_at", "updated_at"];

    // public function department()
    // {
    //     return $this->belongsTo('App\Ecabs\Department');
    // }
}
