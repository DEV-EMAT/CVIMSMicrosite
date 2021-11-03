<?php

namespace App\Http\Controllers\API\Spes;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use Auth;
use DB;

class SpesController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getSpesBeneficiaries()
    {
        if(Auth::user()->account_status == 1){
            try {
                    DB::beginTransaction();
                    $spes = User::join('people', 'users.person_id', 'people.id')
                            ->select(
                                'users.id AS user_id',
                                DB::raw('CONCAT(people.last_name, ", ", people.first_name) AS full_name'),
                                'people.person_code'
                            )->get();

                    DB::commit();

                    return response()->json(['success' => $this->successStatus, 'message' => "Spes Beneficiaries retrieved successfully.", 'data' => $spes], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus, 'message' => 'User is not Authorized.'], $this->errorStatus);
        }
    }

}
