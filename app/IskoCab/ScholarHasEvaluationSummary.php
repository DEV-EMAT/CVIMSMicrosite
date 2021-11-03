<?php

namespace App\IskoCab;

use Illuminate\Database\Eloquent\Model;

class ScholarHasEvaluationSummary extends Model
{
    protected $connection = 'iskocab';

    protected $hidden = ["created_at", "updated_at"];
}
