<?php

namespace App\Covid19Vaccine;

use Illuminate\Database\Eloquent\Model;

class PreRegistration extends Model
{
    protected $connection = "covid19vaccine";

    protected $hidden = ["updated_at"];

    public function qualified_patient()
    {
        return $this->belongsTo('App\Covid19Vaccine\QualifiedPatient', 'id', 'qualified_patient_id');
    }

    public function categories()
    {
       return $this->hasOne('App\Covid19Vaccine\Category', 'id', 'category_id');
    }

    public function employers()
    {
       return $this->hasOne('App\Covid19Vaccine\Employer', 'id', 'employment_id');
    //    ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
    //    ->join('employment_statuses as employment_statuses', 'employment_statuses.id', '=', 'employers.employment_status_id');
    }

    public function id_categories()
    {
       return $this->hasOne('App\Covid19Vaccine\IdCategory', 'id', 'category_for_id');
    }

    public function surveys()
    {
       return $this->hasOne('App\Covid19Vaccine\Survey', 'registration_id', 'id');
    }
}
