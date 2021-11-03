<?php

namespace App\Http\Controllers\API\Ecabs;

use App\Ecabs\Barangay;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use DB;
use Auth;

class AddressController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getPH(){
        $json = Storage::disk('local')->get('ph_address.json');
        $json = json_decode($json, true);

        return $json;
    }

    public function getBarangay()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                
                $bgry = Barangay::select('barangay')->get();
    
                DB::commit();
                
                return response()->json(['success' => $bgry], $this->successStatus);
    
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
