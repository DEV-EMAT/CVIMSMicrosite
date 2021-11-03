<?php

namespace App\Covid19Vaccine;

use Illuminate\Database\Eloquent\Model;
use DB;

class QualifiedPatient extends Model
{
    protected $connection = "covid19vaccine";
    protected $hidden = ["created_at", "updated_at"];

    public function pre_registration()
    {
       return $this->hasOne('App\Covid19Vaccine\PreRegistration', 'id', 'registration_id');
    //    ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
    //    ->join('employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
    //    ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
    //    ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
    //    ->join('employment_statuses as employment_statuses', 'employment_statuses.id', '=', 'employers.employment_status_id')
    //    ->leftJoin('surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id');
    }

    public function pre_reg()
    {
       return $this->hasOne('App\Covid19Vaccine\PreRegistration', 'id', 'registration_id');
    }

    public function vaccination_monitoring()
    {
       return $this->hasMany('App\Covid19Vaccine\VaccinationMonitoring', 'qualified_patient_id', 'id')
       ->join('vaccine_categories', 'vaccine_categories.id', '=', 'vaccine_category_id')
       ->join('vaccinators', 'vaccinators.id', '=', 'vaccinator_id')
       ->join('health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
       ->join('vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id');
    }

    public function scopeSearchData($query, $search_key){
        //$query->leftJoin('pre_registrations', 'qualified_patients.registration_id', 'pre_registrations.id')->whereRaw("concat(pre_registrations.first_name, ' ',pre_registrations.last_name) like '%{$search_key}%'");
        // $query->with(['pre_registration' => function($q) use ($search_key){
        //     $q->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', "%$search_key%");
        //     // $query->where("concat(first_name, ' ', last_name) like '%{$request->search_key}%' ");
        // }]);
        $query->whereHas('pre_registration', function($query) use ($search_key){
            // $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', "%$request->search_key%");
            $query->whereRaw("concat(first_name, ' ',last_name) like '%{$search_key}%' ");
        });
    }
}
