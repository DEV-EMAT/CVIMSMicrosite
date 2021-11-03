<?php

namespace App\GoTrabaho;

use Illuminate\Database\Eloquent\Model;

class Eligibility extends Model
{
    //
    protected $connection = "gotrabaho";

    protected $hidden = ["created_at", "updated_at"];
}
