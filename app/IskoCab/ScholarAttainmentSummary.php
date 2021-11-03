<?php

namespace App\iskocab;

use Illuminate\Database\Eloquent\Model;

class ScholarAttainmentSummary extends Model
{
    protected $connection = "iskocab";

    protected $hidden = ["created_at", "updated_at"];
}
