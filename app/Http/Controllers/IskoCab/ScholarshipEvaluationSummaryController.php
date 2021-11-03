<?php

namespace App\Http\Controllers\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IskoCab\Scholar;
use App\IskoCab\ScholarHasApplication;
use App\IskoCab\ScholarHasEvaluationSummary;
use App\Ecabs\Person;
use App\User;

class ScholarshipEvaluationSummaryController extends Controller
{
    public function index()
    {
        return view('iskocab.scholarship_summaries.index', ['title'=>"Scholarship Summaries"]);
    }

    public function findall(Request $request)
    {   
        $columns = array( 
            0 =>'id', 
            // 1 =>'course_description',
            // 2=> 'status',
            // 3=> 'actions',
        );

        $totalData = ScholarHasEvaluationSummary::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $summaries = ScholarHasEvaluationSummary::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $summaries =  ScholarHasEvaluationSummary::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = ScholarHasEvaluationSummary::count();
        }

        $data = array();
        if(!empty($summaries))
        {
            foreach ($summaries as $summary)
            {   
                $application = ScholarHasApplication::where('id', '=', $summary->application_id)->first();
                $scholar = Scholar::where('id', '=', $application->scholar_id)->first();
                $user = User::where('id', '=', $scholar->user_id)->first();
                $person = Person::where('id', '=', $user->person_id)->first();
                
                $fullname = $person->last_name;
                
                if($person->affiliation){
                    $fullname .= " " . $person->affiliation;
                }
                $fullname .= ", " . $person->first_name . " ";
                
                if($person->middle_name){
                    $fullname .= $person->middle_name; 
                }

                $appliedBy = "<label class='label label-danger'>Not yet Applied</label>";
                $evaluatedBy = "<label class='label label-danger'>Not yet Evaluated</label>";
                $assessedBy = "<label class='label label-danger'>Not yet Assessed</label>";

                if($summary->applied_by){
                    $appliedByUser = User::where('id', '=', $summary->applied_by)->first();
                    $appliedByPerson = Person::where('id', '=', $appliedByUser->person_id)->first();
                    
                    $appliedBy = $appliedByPerson->last_name;
                
                    if($appliedByPerson->affiliation){
                        $appliedBy .= " " . $appliedByPerson->affiliation;
                    }
                    $appliedBy .= ", " . $appliedByPerson->first_name . " ";
                    
                    if($appliedByPerson->middle_name){
                        $appliedBy .= $appliedByPerson->middle_name[0] . "."; 
                    }
                    
                }

                if($summary->evaluated_by){
                    $evaluatedByUser = User::where('id', '=', $summary->evaluated_by)->first();
                    $evaluatedByPerson = Person::where('id', '=', $evaluatedByUser->person_id)->first();
    
                    $evaluatedBy = $evaluatedByPerson->last_name;
                
                    if($evaluatedByPerson->affiliation){
                        $evaluatedBy .= " " . $evaluatedByPerson->affiliation;
                    }
                    $evaluatedBy .= ", " . $evaluatedByPerson->first_name . " ";
                    
                    if($evaluatedByPerson->middle_name){
                        $evaluatedBy .= $evaluatedByPerson->middle_name[0] . "."; 
                    }
                }

                if($summary->assessed_by){
                    $assessedByUser = User::where('id', '=', $summary->assessed_by)->first();
                    $assessedByPerson = Person::where('id', '=', $assessedByUser->person_id)->first();
                    
                    $assessedBy = $assessedByPerson->last_name;
                    
                    if($assessedByPerson->affiliation){
                        $assessedBy .= " " . $assessedByPerson->affiliation;
                    }
                    $assessedBy .= ", " . $assessedByPerson->first_name . " ";
                    
                    if($assessedByPerson->middle_name){
                        $assessedBy .= $assessedByPerson->middle_name[0] . "."; 
                    }
                }

                $nestedData['fullname'] = $fullname;
                $nestedData['applicationCode'] = $application->application_code ;
                $nestedData['appliedBy'] = $appliedBy;
                $nestedData['evaluatedBy'] = $evaluatedBy;
                $nestedData['assessedBy'] = $assessedBy;
                $data[] = $nestedData;
            }
        }
          
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
            
        echo json_encode($json_data); 
    }
}
