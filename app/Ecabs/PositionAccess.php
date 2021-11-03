<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;

class PositionAccess extends Model
{
    protected $hidden = ["created_at", "updated_at"];
    
    // public function department_position()
    // {
    //     return $this->belongsTo('App\ecabs\DepartmentPosition');
    // }

    public function department_position()
    {
        return $this->hasOne('App\Ecabs\DepartmentPosition');
    }
}
