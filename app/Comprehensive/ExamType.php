<?php

namespace App\Comprehensive;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    protected $connection = "comprehensive";

    protected $hidden = ["created_at", "updated_at"];
}
