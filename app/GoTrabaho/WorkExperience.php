<?php

namespace App\GoTrabaho;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    //
    protected $connection = "gotrabaho";

    protected $hidden = ["created_at", "updated_at"];
}
