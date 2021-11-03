<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use DB;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        
        Gate::define('permission', function($user, $permission){
             $person_dept_pos = DB::table('person_department_positions')
                ->select('position_accesses.access', 'position_accesses.status')
                ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
                ->join('position_accesses', 'position_accesses.id', '=', 'department_positions.position_access_id')
                ->where('person_department_positions.person_id', '=', $user->person_id)->first();
                
            $check = ($person_dept_pos)? unserialize($person_dept_pos->access) : null;

            if($check != null){
                return in_array($permission,  unserialize($person_dept_pos->access)) && $person_dept_pos->status == '1';
            }else{
                return false;
            }
        });
    }
}
