<?php

namespace App\IskoCab;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $connection = "iskocab";

    protected $hidden = ["created_at", "updated_at"];

    public function grading_system()
    {
        return $this->hasOne('App\IskoCab\School', 'school_id', 'id');
    }
}
