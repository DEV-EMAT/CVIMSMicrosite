<?php

namespace App\IskoCab;

use Illuminate\Database\Eloquent\Model;

class ScholarHasApplication extends Model
{
    protected $connection = "iskocab";

    protected $hidden = ["created_at", "updated_at"];

    public function educational_attainment()
    {
        return $this->hasMany('App\IskoCab\EducationalAttainment', 'id', 'ea_id');
    }
}
