<?php

namespace App\Http\Middleware;
use App\ecabs\PersonDepartmentPosition;
use Closure;
use DB;
use Auth;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 

        $actions = array_slice(func_get_args(), 2);
        foreach($actions as $action){

            $person_dept_pos = DB::table('person_department_positions')
                    ->select('position_accesses.access', 'position_accesses.status')
                    ->join('department_positions', 'department_positions.id', '=', 'person_department_positions.department_position_id')
                    ->join('position_accesses', 'position_accesses.id', '=', 'department_positions.position_access_id')
                    ->where('person_department_positions.person_id', '=', Auth::user()->person_id)->first();

            $check = ($person_dept_pos)? unserialize($person_dept_pos->access) : null;

            if($check != null){
                if(in_array($action, unserialize($person_dept_pos->access)) && $person_dept_pos->status == '1'){
                    return $next($request);
                }
            }
        }
        abort(403);
    }
}
