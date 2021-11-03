<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;

class UpdateAccountDepartment extends Model
{
    protected $hidden = ["created_at", "updated_at"];
    
    public function updates()
    {
        return $this->hasOne('App\Ecabs\Update');
    }

    public function departments()
    {
        return $this->hasOne('App\Ecabs\Department');
    }
}
