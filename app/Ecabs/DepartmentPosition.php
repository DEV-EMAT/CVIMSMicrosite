<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;

class DepartmentPosition extends Model
{
    protected $hidden = ["created_at", "updated_at"];
    
    public function positions()
    {
        return $this->hasOne('App\Ecabs\PositionAccess', 'id', 'position_access_id');
    }

    public function departments()
    {
        return $this->hasOne('App\Ecabs\Department', 'id', 'department_id');
    }

    public function person_department_positions()
    {
        return $this->hasMany('App\Ecabs\PersonDepartmentPosition');
    }
}
