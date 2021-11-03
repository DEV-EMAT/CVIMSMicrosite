<?php

namespace App\IskoCab;

use Illuminate\Database\Eloquent\Model;

class ScholarHasApplicationSummary extends Model
{
    protected $connection = "iskocab";

    protected $hidden = ["created_at", "updated_at"];
}
