<?php

namespace App\Http\Controllers\IskoCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IskoCab\ScholarHasEvaluationSummary;
use App\IskoCab\ScholarHasApplication;
use DB;
use PDF;

class AssessmentController extends Controller
{
    public function index()
    {
        return view('iskocab.scholarship_assessment.index', ['title' => "Scholars Assessment"]);
    }

    public function findall(Request $request){
        $columns=array(0=> 'scholars.id',
            1=> 'person_code',
            2=> 'last_name',
        );

        $limit=$request->input('length');
        $start=$request->input('start');
        $order=$columns[$request->input('order.0.column')];
        $dir=$request->input('order.0.dir');

        $query = ScholarHasApplication::join(connectionName('iskocab') .'.scholars as scholars', 'scholars.id', 'scholar_has_applications.scholar_id') 
            ->join(connectionName('iskocab') .'.scholar_has_school_summaries AS school_summaries', 'school_summaries.scholar_id', '=', 'scholars.id')
            ->join(connectionName('iskocab') .'.schools', 'schools.id', '=', 'school_summaries.school_id')
            ->join(connectionName('iskocab') .'.grading_systems as grading_system', 'grading_system.school_id', 'schools.id') 
            ->join(connectionName('iskocab') .'.courses as courses', 'courses.id', 'scholars.course_id') 
            ->join(connectionName() .'.users as users', 'users.id', 'scholars.user_id') 
            ->join(connectionName() .'.people as people', 'people.id', 'users.person_id') 
            ->join(connectionName() .'.addresses as addresses', 'addresses.id', 'people.address_id') 
            ->select(
                'scholar_has_applications.id',
                'scholars.id as scholar_id',
                'people.last_name',
                'people.first_name',
                'people.middle_name',
                'people.person_code',
                'courses.course_description',
                // 'schools.school_name',
                'users.contact_number',
                'people.date_of_birth',
                'addresses.barangay',
                'people.address',
                'people.image',
                // 'grading_system.grade_list as grading_system',
                'scholar_has_applications.evaluation_status')->where('scholars.status', '=', 1)
            ->where('school_summaries.status', '=', '1')
            ->where('application_status', '=', 'SUCCESS');
            
        $totalData = $query->count();
        $totalFiltered=$totalData;

        if(empty($request->input('search.value'))) {

            $application=$query->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

        } else {
            $search=$request->input('search.value');

            $application = $query->where('people.last_name', 'LIKE', "%{$search}%")
                ->orWhere('people.first_name', 'LIKE', "%{$search}%")
                ->orWhere('people.middle_name', 'LIKE', "%{$search}%")
                ->orWhere('people.person_code', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered=$query->where('people.last_name', 'LIKE', "%{$search}%")
                ->orWhere('people.first_name', 'LIKE', "%{$search}%")
                ->orWhere('people.middle_name', 'LIKE', "%{$search}%")
                ->orWhere('people.person_code', 'LIKE', "%{$search}%")
                ->count();
        }

        $data=array();

        if(!empty($application)) {
            foreach ($application as $entry) {

                $fullname = $entry->last_name;
                
                if($entry->affiliation){
                    $fullname .= " " . $entry->affiliation;
                }
                $fullname .= ", " . $entry->first_name . " ";
                
                if($entry->middle_name){
                    $fullname .= $entry->middle_name; 
                }

                if($entry->evaluation_status == 'PENDING'){
                    $status = '<label class="label label-danger">PENDING</label>';
                    $buttons = '<a class="btn btn-xs btn-info btn-fill btn-rotate" disabled><i class="fa fa-print"></i> Print</a>';
                }else{
                    $status = '<label class="label label-success">EVALUATED</label>';
                    $buttons = '<a onclick="printAssessment('. $entry->id .')" class="btn btn-xs btn-info btn-fill btn-rotate"><i class="fa fa-print"></i> Print</a>';
                }                
                
                //array data
                $nestedData['barcode'] = $entry->person_code;
                $nestedData['sch_info'] = $entry;
                $nestedData['fullname'] = $fullname;
                $nestedData['status'] = $status;
                $nestedData['actions'] = $buttons;
                $data[]=$nestedData;
            }
 
        }   
        $json_data=array("draw"=> intval($request->input('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=> $data);

        echo json_encode($json_data);
    }

    public function printAssessment($id){
        $scholarHasApplication = ScholarHasApplication::findOrFail($id);
        $scholar = DB::table(connectionName('iskocab') .'.scholar_has_applications')
                    ->join(connectionName('iskocab') .'.scholars', 'scholars.id', '=', 'scholar_has_applications.scholar_id')
                    ->join(connectionName('iskocab') .'.scholar_has_application_summaries', 'scholar_has_application_summaries.application_id', '=', 'scholar_has_applications.id')
                    ->join(connectionName('iskocab') .'.scholar_has_subject_grades', 'scholar_has_subject_grades.id', '=', 'scholar_has_application_summaries.grades_id')
                    ->join(connectionName('mysql') .'.users', 'users.id', '=', 'scholars.user_id')
                    ->join(connectionName('mysql') .'.people', 'people.id', '=', 'users.person_id')
                    ->join(connectionName('iskocab') .'.scholar_has_school_summaries AS school_summaries', 'school_summaries.scholar_id', '=', 'scholars.id')
                    ->join(connectionName('iskocab') .'.schools', 'schools.id', '=', 'school_summaries.school_id')
                    ->join(connectionName('iskocab') .'.scholar_has_course_summaries as course_summaries', 'course_summaries.scholar_id', '=', 'scholars.id')
                    ->join(connectionName('iskocab') .'.courses as courses', 'courses.id', '=', 'course_summaries.course_id')                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
                    ->select(
                        'people.*', 
                        'schools.school_name',
                        'courses.course_description',
                        'scholar_has_application_summaries.*',
                        'scholar_has_subject_grades.gwa',
                        'scholar_has_subject_grades.grade_list')
                    ->where('school_summaries.status', '=', '1')
                    ->where('scholar_has_applications.id', '=', $id)->first();

        if($scholar){
            $fullName = $scholar->last_name;
            
            if($scholar->affiliation){
                $fullName .= " " . $scholar->affiliation;
            }
            $fullName .= ", " . $scholar->first_name;
            
            if($scholar->middle_name){
                $fullName .= " " . $scholar->middle_name[0] . ".";
            }
            

            $trans = "1";
            $dataIssue=date("Y/m/d");
            $yearLevel =  "3";
            $barcode =  "4";
            $noOfUnits = 0;
            $grades = unserialize($scholar->grade_list);
            
            if($grades)
                foreach($grades as $grade){
                    $noOfUnits += $grade['no_of_units'];
                }
            
            // // create new PDF document
            //PDF:: new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf=new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);
            // set document information
            PDF::SetTitle($barcode);
            PDF::SetAuthor('Cabuyao Youth Development Affairs');

            // Set font
            // dejavusans is a UTF-8 Unicode font, if you only need to
            // print standard ASCII chars, you can use core fonts like
            // helvetica or times to reduce file size.
            PDF::SetFont('times', '', 12, '', true);
            define ("pdf_page_format", "letter");
            
            // Add a page
            // This method has several options, check the source code documentation for more information.
            PDF::AddPage();

            $style=array('position'=> '',
                'align'=> '',
                'stretch'=> false,
                'fitwidth'=> true,
                'cellfitalign'=> '',
                'border'=> true,
                'hpadding'=> 'auto',
                'vpadding'=> 'auto',
                'fgcolor'=> array(0, 0, 0),
                'bgcolor'=> false, //array(255,255,255),
                'text'=> true,
                'font'=> 'times',
                'fontsize'=> 8,
                'stretchtext'=> 4);
            $style['position']='R';
            $table='<div align="left" style="margin: 0px; padding: 0px;"><img src="assets/image/cyda_assessment.png" alt="test alt attribute" style="margin: 0px; padding: 0px; width: 500px;"/></div>';
            
            // $pdf->write2DBarcode($scholar->person_code, 'RAW', 80, 30, 30, 20, $style, 'N');
            // output the HTML content
            PDF::writeHTML($table, true, false, true, false, '');

            // output the HTML contentyear
            //PDF::writeHTML($html, true, false, true, false, '');
            PDF::Cell(31, 5, 'Assessment Date: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $dataIssue);   
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            PDF::Cell(55, 5, '_________________________________________________________________________________________');
            PDF::Cell(50, 5, 'SCHOLARSHIP ASSESSMENT FORM');
            PDF::Ln(6);

            // Full name
            PDF::Cell(16, 5, 'Name:');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $fullName);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);


            // Couse
            PDF::Cell(16, 5, 'Course: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $scholar->course_description);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // School
            PDF::Cell(16, 5, 'School: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $scholar->school_name);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Number of Units
            PDF::Cell(35, 5, 'Number of Units:');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $noOfUnits);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // GWA
            PDF::Cell(60, 5, 'General Weighted Average (gwa): ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $scholar->gwa);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);

            // Year level
            PDF::Cell(23, 5, 'Year Level: ');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, $scholar->year_level);
            PDF::SetFont('Times', '', 12);
            PDF::Ln(6);


            PDF::Cell(55, 5, '_________________________________________________________________________________________');
            PDF::Ln(6);


            // $user = Employee::where('user_id', Auth::id())->first();
            PDF::SetFont('Times', 'B', 8);
            PDF::Cell(110, 5, ' Evaluated By: , | Assessed By:');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, '');
            PDF::Ln(6);
            PDF::Cell(110, 5, '');
            PDF::Cell(50, 5, '');
            PDF::Ln(6);

            PDF::SetFont('Times', 'B', 8);
            PDF::Cell(110, 5, '  approve by: _________________________________');
            PDF::SetFont('Times', 'B', 12);
            PDF::Cell(50, 5, '  _________________________________');
            PDF::Ln(6);
            PDF::SetFont('Times', 'B', 8);
            PDF::Cell(120, 5, '   remarks:    _________________________________');
            PDF::SetFont('Times', 'B', 8);
            PDF::Cell(50, 5, '                       (YDA - System Admin)');
            PDF::Ln(6);



            PDF::Cell(55, 5, '');
            PDF::Ln(6);

            PDF::Cell(55, 5, '');
            PDF::Cell(50, 5, '            _________________________________');
            PDF::Ln(6);

            PDF::Cell(65, 5, '');
            PDF::Cell(50, 5, '         YDA Department Head');
            PDF::Ln(6);

            PDF::SetFont('Times', 'B', 8);
            PDF::Cell(140, 5, 'Note: INCOMPLETE signature is considered as not valid.');
            PDF::Cell(50, 5, 'This copy is system generated document.');
            PDF::SetFont('Times', 'B', 12);
            PDF::Ln(6);


            PDF::Cell(65, 5, '-------------------------------------------------------------------------------------------------------------------------------------');
            PDF::Ln(6);
            //=============second copy


            // Close and output PDF document
            // This method has several options, check the source code documentation for more information.
            PDF::Output($barcode.'.pdf', 'I');
            exit;
            //============================================================+
            // END OF FILE
            //============================================================+   
        }

        else {
            echo "No Record Found!";
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
