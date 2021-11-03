<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $hidden = ["created_at", "updated_at"];

    public function department_position()
    {
        return $this->belongsTo('App\Ecabs\DepartmentPosition', 'id', 'department_id');
    }

    public function module()
    {
        return $this->hasOne('App\Ecabs\Module', 'department_id', 'id');
    }
    
    public function update_account_department()
    {
        return $this->hasOne('App\Ecabs\UpdateAccountDepartment');
    }

    // public function events()
    // {
    //     return $this->hasMany('App\Comprehensive\Event');
    // }
    
    // public function requirements()
    // {
    //     return $this->hasMany('App\Comprehensive\Requirement');
    // }
}
