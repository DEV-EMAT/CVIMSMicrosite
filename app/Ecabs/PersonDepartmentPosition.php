<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;

class PersonDepartmentPosition extends Model
{
    protected $hidden = ["created_at", "updated_at"];
    
    public function updates()
    {
        return $this->hasMany('App\Ecabs\Update');
    }

    public function department_position()
    {
        return $this->belongsTo('App\Ecabs\DepartmentPosition');
    }
}
