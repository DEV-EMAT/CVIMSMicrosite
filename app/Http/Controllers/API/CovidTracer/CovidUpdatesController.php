<?php

namespace App\Http\Controllers\API\CovidTracer;

use App\CovidTracer\CasesUpdateSummary;
use App\Ecabs\Barangay;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;

class CovidUpdatesController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function covidStatistics()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $stats = CasesUpdateSummary::leftJoin(connectionName() . '.barangays', 'cases_update_summaries.barangay_id', connectionName() . '.barangays.id')
                        ->select(
                            'barangays.barangay',  
                            \DB::raw('SUM(cases_update_summaries.new_cases) as new_cases'),
                            \DB::raw('SUM(cases_update_summaries.active_cases) as active_cases'),
                            \DB::raw('SUM(cases_update_summaries.confirmed_cases) as confirmed_cases'),
                            \DB::raw('SUM(cases_update_summaries.recovered) as recovered'),
                            \DB::raw('SUM(cases_update_summaries.deceased) as deceased'),
                            \DB::raw('SUM(cases_update_summaries.suspected_cases) as suspected_cases'),
                            \DB::raw('SUM(cases_update_summaries.bjmp_confirmed_cases) as bjmp_confirmed_cases'),
                            \DB::raw('SUM(cases_update_summaries.probable_cases) as probable_cases')
                        )
                        ->orderBy('identifier', 'asc')->groupBy('barangay')->get();

                DB::commit();

                return response()->json(['success' => $stats], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        } 
    }

    public function covidStatisticsPerBarangay(Request $request)
    {
        if(Auth::user()->account_status == 1){
            $validator = Validator::make($request->all(), [ 
                'barangay' => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
            }

            try {
                DB::beginTransaction();

                $barangay = Barangay::where('barangay', '=', $request['barangay'])->select('id')->first();
                // $recent = CasesUpdateSummary::where('barangay_id', '=', $barangay->id)->orderBy('identifier', 'desc')->select('identifier')->first();
                $stats = CasesUpdateSummary::where('barangay_id', '=', $barangay->id)->orderBy('identifier', 'desc')->get();

                DB::commit();
                
                return response()->json(['success' => $stats], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }  
    }

    public function covidStatsTally()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $confirmed_cases = CasesUpdateSummary::sum('new_cases');
                $recovered = CasesUpdateSummary::sum('recovered');
                $deceased = CasesUpdateSummary::sum('deceased');
                $suspected_cases = CasesUpdateSummary::sum('suspected_cases');
                $bjmp_confirmed_cases = CasesUpdateSummary::sum('bjmp_confirmed_cases');
                $probable_cases = CasesUpdateSummary::sum('probable_cases');

                $total_fresh_new_cases = 0;
                $get_new = CasesUpdateSummary::orderBy('identifier', 'desc')->select('identifier')->first();
                $fresh_new_cases = CasesUpdateSummary::where('identifier', '=', $get_new->identifier)->select('new_cases')->get();

                foreach($fresh_new_cases as $fresh_new_case){
                    $total_fresh_new_cases += $fresh_new_case->new_cases;
                }

                DB::commit();

                return response()->json(['success' => [
                            'confirmed_cases' => $confirmed_cases,
                            'recovered' => $recovered,
                            'deceased' => $deceased,
                            'suspected_cases' => $suspected_cases,
                            'bjmp_confirmed_cases' => $bjmp_confirmed_cases,
                            'probable_cases' => $probable_cases,
                            'fresh_new_cases' => $total_fresh_new_cases,
                            ]], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        } 
    }

    public function getNewActiveAndTotalActiveCases()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();

                $total_new_cases = 0;
                $total_active_cases = 0;
                $get_new = CasesUpdateSummary::orderBy('identifier', 'desc')->select('identifier')->first();
                $new_cases = CasesUpdateSummary::where('identifier', '=', $get_new->identifier)->select('new_cases')->get();
                $confirmed_cases = CasesUpdateSummary::sum('new_cases');
                $recovered = CasesUpdateSummary::sum('recovered');
                $deceased = CasesUpdateSummary::sum('deceased');
                $total_active_cases = ($confirmed_cases - $recovered) - $deceased;

                foreach($new_cases as $new_case){
                    $total_new_cases += $new_case->new_cases;
                }
                DB::commit();

                return response()->json(['success' => [
                            'total_new_cases' => $total_new_cases,
                            'total_active_cases' => $total_active_cases
                            ]], $this->successStatus);

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            } 
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        } 
    }
}
