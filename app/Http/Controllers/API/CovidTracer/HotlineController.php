<?php

namespace App\Http\Controllers\API\CovidTracer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CovidTracer\EmergencyHotline;
use Validator;
use Auth;
use DB;

class HotlineController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function index()
    {
        if(Auth::user()->account_status == 1){
            $hotlines = EmergencyHotline::join(connectionName().'.addresses AS addresses', 'emergency_hotlines.address_id', 'addresses.id')
                        ->select(
                            'emergency_hotlines.name',
                            'emergency_hotlines.address',
                            'emergency_hotlines.contact',
                            'addresses.barangay'
                        )->get();

            $hotlines->map(function ($hotline) {
                return $hotline->contact =  unserialize($hotline->contact);
            });

            return response()->json(['success' => $hotlines], $this->successStatus);
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'address' => 'required',
    //         'contact_number' => 'required',
    //         'telephone_number' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error'=>$validator->errors()], $this->errorStatus);
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $hotline = new EmergencyHotline();
    //         $hotline->name = $request['name'];
    //         $hotline->address = $request['address'];
    //         $hotline->contact_number = $request['contact_number'];
    //         $hotline->telephone_number = $request['telephone_number'];
    //         $hotline->status = 'active';
    //         $hotline->save();

    //         DB::commit();

    //         return response()->json(['success' => $hotline], $this->successCreateStatus);

    //     } catch (\PDOException $e) {
    //         DB::rollBack();
    //         return response()->json($e, $this->queryErrorStatus);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $hotline = EmergencyHotline::findOrFail($id);

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'address' => 'required',
    //         'contact_number' => 'required',
    //         'telephone_number' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error'=>$validator->errors()], $this->errorStatus);
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $hotline->name = convertData($request['name']);
    //         $hotline->address = convertData($request['address']);
    //         $hotline->contact_number = $request['contact_number'];
    //         $hotline->telephone_number = $request['telephone_number'];
    //         $hotline->status = '1';

    //         $hotline->save();

    //         DB::commit();

    //         return response()->json(['success' => $hotline], $this->successCreateStatus);

    //     } catch (\PDOException $e) {
    //         DB::rollBack();
    //         return response()->json($e, $this->queryErrorStatus);
    //     }
    // }
}
