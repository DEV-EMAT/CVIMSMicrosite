<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $hidden = ["updated_at"];
    
    public function person_department_position()
    {
        return $this->belongsTo('App\Ecabs\PersonDepartmentPosition');
    }

    public function update_account_department()
    {
        return $this->hasOne('App\Ecabs\UpdateAccountDepartment');
    }
}
