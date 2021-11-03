<?php

namespace App\Comprehensive;

use Illuminate\Database\Eloquent\Model;

class RequirementHasDepartment extends Model
{
    protected $connection = "comprehensive";

    protected $hidden = ["created_at", "updated_at"];
}
