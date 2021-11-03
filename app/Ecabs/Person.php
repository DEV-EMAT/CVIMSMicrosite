<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $guarded = [];
    
    public function person_department_position()
    {
        return $this->hasOne('App\Ecabs\PersonDepartmentPosition', 'person_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'person_id', 'id');
    }
}
