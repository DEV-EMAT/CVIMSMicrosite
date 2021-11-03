<?php

namespace App\IskoCab;

use Illuminate\Database\Eloquent\Model;

class GradingSystem extends Model
{
     protected $connection = "iskocab";

     protected $hidden = ["created_at", "updated_at"];
 
     public function school()
     {
         return $this->belongsTo('App\IskoCab\School');
     }
}
