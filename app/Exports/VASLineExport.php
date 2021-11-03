<?php

namespace App\Exports;
use App\Covid19Vaccine\VaccinationMonitoring;
use App\Covid19Vaccine\ExportSummary;
use App\Covid19Vaccine\ExportHasPatient;
use Auth;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class VASLineExport extends StringValueBinder implements WithCustomValueBinder, WithMapping, WithStyles, FromQuery, WithHeadings, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id, $facility, $exporter, $user_has_facility, $facility_name;

    function __construct($id="", $facility="", $exporter="", $user_has_facility="", $facility_name="") {
        $this->id = $id;
        $this->facility = $facility;
        $this->exporter = $exporter;
        $this->user_has_facility = $user_has_facility;
        $this->facility_name = $facility_name;

    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 35,
            'C' => 35,
            'D' => 35,
            'E' => 35,
            'F' => 35,
            'G' => 35,
            'H' => 35,
            'I' => 35,
            'J' => 45,
            'K' => 45,
            'L' => 45,
            'M' => 45,
            'N' => 45,
            'O' => 45,
            'P' => 45,
            'Q' => 35,
            'R' => 45,
            'S' => 50,
            'T' => 50,
            'U' => 50,
            'V' => 50,
            'W' => 50,
            'X' => 50,
            'Y' => 50,
            'Z' => 50,
            'AA' => 50,
            'AB' => 50,
            'AC' => 50,
            'AD' => 50,
            'AE' => 50,
            'AF' => 50,
            'AG' => 50,
            'AH' => 50,
            'AI' => 50,
            'AJ' => 35,
            'AK' => 35,
            'AL' => 35,
            'AM' => 35,
            'AN' => 35,
            'AO' => 35,
            'AP' => 35,
            'AQ' => 35,
            'AR' => 35,
            'AS' => 35,
            'AT' => 35,
            // 'AU' => 35,
            // 'A:I' => 35,
            // 'J:P' => 45,
            // 'Q' => 35,
            // 'R' => 45,
            // 'S:AJ' => 50,
            // 'AK:AS' => 35,

        ];
        return [];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getRowDimension(1)->setRowHeight(80);

        // $sheet->getStyle();


        return [
            'A1:P1'    => [
                'font' => ['bold' => true, 'name' => 'Arial'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'fill' => ['fillType' => 'solid', 'rotation' => 0, 'startColor' => ['rgb' => 'D9EAD3'], 'endColor' => ['argb' => 'FFFFFFFF']],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]
            ],

            'Q1:R1'    => [
                'font' => ['bold' => true, 'name' => 'Arial'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'fill' => ['fillType' => 'solid', 'rotation' => 0, 'startColor' => ['rgb' => 'FFFF00'], 'endColor' => ['argb' => 'FFFFFFFF']],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]
            ],

            'S1:AI1'    => [
                'font' => ['bold' => true, 'name' => 'Arial'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'fill' => ['fillType' => 'solid', 'rotation' => 0, 'startColor' => ['rgb' => 'BDD6EE'], 'endColor' => ['argb' => 'FFFFFFFF']],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]
            ],

            'AJ1'    => [
                'font' => ['bold' => true, 'name' => 'Arial'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'fill' => ['fillType' => 'solid', 'rotation' => 0, 'startColor' => ['rgb' => 'FFFF00'], 'endColor' => ['argb' => 'FFFFFFFF']],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]
            ],

            'AK1:AT1'    => [
                'font' => ['bold' => true, 'name' => 'Arial'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'fill' => ['fillType' => 'solid', 'rotation' => 0, 'startColor' => ['rgb' => 'E2EFD9'], 'endColor' => ['argb' => 'FFFFFFFF']],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]
            ],


        ];
    }

    function checkNullData($data) {
        return is_null($data) || $data == "" ? "N/A" : $data;
    }
    
    function checkNullData2($data) {
        $cleanData = is_null($data) || $data == "" ? "NA" : $data;
        return preg_replace('/[^A-Za-z0-9\-]/', '', $cleanData);
    }
    
    public function headings(): array
    {
        return [
            'CATEGORY',
            'UNIQUE_PERSON_ID',
            'PWD',
            'Indigenous Member',
            'LAST_NAME',
            'FIRST_NAME',
            'MIDDLE_NAME',
            'SUFFIX',
            'CONTACT_NO',
            'REGION',
            'PROVINCE',
            'MUNI_CITY',
            'BARANGAY',
            'SEX',
            'BIRTHDATE',
            'DEFERRAL',
            'REASON_FOR_DEFERRAL',
            'VACCINATION_DATE',
            'VACCINE_MANUFACTURER_NAME',
            'BATCH_NUMBER',
            'LOT_NO',
            'BAKUNA_CENTER_CBCR_ID',
            'VACCINATOR_NAME',
            '1ST_DOSE',
            '2ND_DOSE',
            'Adverse Event',
            'Adverse Event Condition',
        ];
    }

    public function map($entry): array
    {


        $yes_no = array("01_Yes", "02_No");

        $active_region = "REGION IV-A (CALABARZON)";
        $active_province = "043400000Laguna";
        $active_city = "043404000City of Cabuyao";

        $first_dose = "02_No";
        $second_dose = "02_No";

        if($entry->dosage == 1) {
            $first_dose = "01_Yes";
            $second_dose = "02_No";
        } else {
            $first_dose = "01_Yes";
            $second_dose = "01_Yes";
        }


        $category = [
            '01_Health_Care_Worker' => 'A1',
            '02_Senior_Citizen' => 'A2',
            '07_Comorbidities' => 'A3',
            '03_Indigent ' => 'A5',
            '12_Remaining_Workforce' => 'A4',
            '11_OFW' => 'A4',
            '10_Other_High_Risk' => 'A4',
            '09_Other_Govt_Wokers' => 'A4',
            '08_Teachers_Social_Workers' => 'A4',
            '06_Other' => 'A4',
            '05_Essential_Worker' => 'A4',
            '04_Uniformed_Personnel' => 'A4',
        ];

        $vaccine = [
            "SINOVAC" => "Sinovac",
            "ASTRAZENECA" => "AZ",
            "PFIZER" => "Pfizer",
            "MODERNA" => "Moderna",
            "SPUTNIK V/GAMALEYA" => "Gamaleya",
            "NOVAVAX" => "Novavax",
            "JOHNSON AND JOHNSON" => "J&J",
            "SINOPHARM" => "Sinopharm",
        ];

        
        $center = [
            "CABUYAO CHO I – BAKUNA CENTER" => "CBC07609",
            "CABUYAO CHO II – BAKUNA CENTER" => "CBC07625",
            "CABUYAO CITY HOSPITAL" => "CBC06192",
            "HOLY ROSARY OF CABUYAO HOSPITAL INC." => "CBC06191",
            "FIRST CABUYAO HOSPITAL AND MEDICAL CENTER, INC." => "CBC06190",
            "GLOBAL MEDICAL CENTER OF LAGUNA" => "CBC06260",
        ];
        
        return [
            $category[$this->checkNullData($entry->category_format)],
            $this->checkNullData($entry->qrcode), //government ID
            ($this->checkNullData($entry->id_category_code) == "04 - PWD ID")? "Y" : "N",
            "No",
            $this->checkNullData($entry->last_name),
            $this->checkNullData($entry->first_name),
            $this->checkNullData2($entry->middle_name),
            ($this->checkNullData($entry->suffix) == "N/A")? "NA" : $this->checkNullData($entry->suffix),
            $this->convertContact($entry->contact_number),
            $active_region,
            $active_province,
            $active_city,
            $this->checkNullData($entry->barangay),
            ($this->checkNullData($entry->sex) == '02_FEMALE')? "F" : "M",
            $this->checkNullData($this->convertDate($entry->date_of_birth)),
            "N", //deferral
            "NONE", // reason for deferral
            $this->checkNullData($entry->vaccination_date),
            $vaccine[$this->checkNullData($entry->vaccine_manufacturer)],
            $this->convertVaccineNumber($entry->batch_number),
            $this->convertVaccineNumber($entry->lot_number),
            $center[$entry->facility_name],
            $this->checkNullData($entry->vaccinator_lastname . ", " . $entry->vaccinator_firstname),
            ($first_dose == "01_Yes") ? 'Y' : 'N',
            ($second_dose == "01_Yes") ? 'Y' : 'N',
            "N",
            "NONE",
        ];
    }
    
    public function convertContact($contact){
        $convertedContact = "";
        if($contact == null || $contact == "" || $contact == "NA" || $contact == "N/A"){
            $convertedContact = "09000000000";
        }else{
            $convertedContact = $contact;
        }
        return preg_replace('/[^A-Za-z0-9\-]/', '', $convertedContact);
    }
    
    public function convertDate($date){
        $data = [];
        if (strpos($date, '-')){
            $data = explode("-", $date);
        }else{
            $data = explode("/", $date);
        }
        
        $converted = "";
        $newDate = "";
        
        if($date == "N/A" || $date == "NA" || $date == null){
            $newDate = "1/1/2000";
        }else{
            if(count($data) < 3){
                 $newDate = "1/1/2000";
            }else{
                if(is_numeric($data[0]) && is_numeric($data[1]) && is_numeric($data[2])){
                    if(strlen($data[2]) == 2 ){
                        $yearNow = date("y");
                        if ($data[2] > $yearNow) {
                            $converted = '19' . $data[2];
                        } else {
                            $converted = '20' . $data[2];
                        }
                        $newDate2 = $data[0] . "/" . $data[1] . "/" . $converted;  
                        $newDate = date("m/d/Y", strtotime($newDate2));
                    }else{
                        $newDate2 = $data[0] . "/" . $data[1]  . "/" . $data[2];
                        $newDate = date("m/d/Y", strtotime($newDate2));
                    }
                }else{
                    $newDate = date("m/d/Y", strtotime($date));
                }
            
            }
        }
        
        return $newDate;
    }
    
    public function convertVaccineNumber($number){
        if($number == "N/A" || is_null($number) || $number == ""){
            $number = "NA";
        }
        
        return $number;
    }

    public function query() {
        if(empty($this->id)){
            DB::connection('covid19vaccine')->beginTransaction();
            try {
                /* KUKUNIN UNG EXPORT SUMMARY NA MONITORING */
                // $exp_summaries = ExportSummary::where('export_type', 'MONITORING')->get();
                // $exp_ids = array();

                // /* TEMP ARRAY NG MGA ID */
                // foreach($exp_summaries as $exp_sum) {
                //     $exp_ids[] = $exp_sum->id;
                // }

                // // dd($exp_ids);
                // /* CHECK KUNG UNG ID NASA EXPORT SUMMARY NA TABLE */
                // $exported_list = ExportHasPatient::whereIn('export_has_patients.export_summary_id', $exp_ids)->get();
                // // dd($exported_list);
                // $uploaded = array();
                // /* TEMP PARA ARRAY PARA SA PATIENT ID */
                // foreach($exported_list as $exp) {
                //     $uploaded[] = $exp->patient_id;
                // }

                $latestSummary =  ExportSummary::select('export_summaries.id', 'export_summaries.created_at')
                    ->join('user_has_facilities', 'export_summaries.user_has_facilities_id', '=', 'user_has_facilities.id')
                    ->where('export_summaries.export_type', 'MONITORING')
                    ->where('user_has_facilities.facility_id', '=', $this->facility)
                    ->latest('export_summaries.created_at')
                    ->first();
                
                $vaccination_monitoring = VaccinationMonitoring::join('qualified_patients as qualified_patients', 'qualified_patients.id', '=',  'vaccination_monitorings.qualified_patient_id')
                ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
                ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
                ->join('vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
                ->join('vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
                ->join('health_facilities as health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
                ->join('vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
                ->join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
                ->select(
                    'categories.category_format',
                    'id_categories.id_category_code',
                    'id_categories.id as id_category',

                    'pre_registrations.category_id_number',
                    'pre_registrations.philhealth_number',
                    'pre_registrations.barangay_id',
                    'pre_registrations.last_name',
                    'pre_registrations.first_name',
                    'pre_registrations.middle_name',
                    'pre_registrations.suffix',
                    'pre_registrations.contact_number',
                    'pre_registrations.home_address',
                    'pre_registrations.status',
                    'pre_registrations.province',
                    'pre_registrations.city',
                    // 'pre_registrations.barangay',
                    'barangays.real_name as barangay',
                    'pre_registrations.sex',
                    'pre_registrations.date_of_birth',
                    'qualified_patients.qrcode',


                    'vaccination_monitorings.id as vaccination_monitorings_id',
                    'vaccination_monitorings.consent',
                    'vaccination_monitorings.reason_for_refusal',
                    'vaccination_monitorings.vaccination_date',
                    // 'vaccination_monitorings.vaccine_manufacturer',
                    'vaccine_categories.vaccine_name as vaccine_manufacturer',
                    'vaccination_monitorings.batch_number',
                    'vaccination_monitorings.lot_number',
                    'vaccination_monitorings.deferral',
                    'vaccination_monitorings.dosage',
                    // 'vaccination_monitorings.vaccine_manufacturer',
                    // 'pre_registrations.civil_status',
                    'vaccinators.last_name as vaccinator_lastname',
                    'vaccinators.first_name as vaccinator_firstname',
                    // 'vaccinators.middle_name',
                    'vaccinators.suffix',
                    'vaccinators.profession',
                    'health_facilities.facility_name',



                    'vaccination_monitoring_surveys.question_1 as age_validation',
                    'vaccination_monitoring_surveys.question_2 as allergic_for_peg',
                    'vaccination_monitoring_surveys.question_3 as allergic_after_dose',
                    'vaccination_monitoring_surveys.question_4 as allergic_to_food',
                    'vaccination_monitoring_surveys.question_5 as asthma_validation',
                    'vaccination_monitoring_surveys.question_6 as bleeding_disorders',
                    'vaccination_monitoring_surveys.question_7 as syringe_validation',
                    'vaccination_monitoring_surveys.question_8 as symptoms_manifest',
                    'vaccination_monitoring_surveys.question_9 as symptoms_specific',
                    'vaccination_monitoring_surveys.question_10 as infection_history',
                    'vaccination_monitoring_surveys.question_11 as previously_treated',
                    'vaccination_monitoring_surveys.question_12 as received_vaccine',
                    'vaccination_monitoring_surveys.question_13 as received_convalescent',
                    'vaccination_monitoring_surveys.question_14 as pregnant',
                    'vaccination_monitoring_surveys.question_15 as pregnancy_trimester',
                    'vaccination_monitoring_surveys.question_16 as diagnosed_six_months',
                    'vaccination_monitoring_surveys.question_17 as specific_diagnosis',
                    'vaccination_monitoring_surveys.question_18 as medically_cleared'

                )->where('vaccination_monitorings.status', '=', 1)
                ->where('health_facilities.id', '=', $this->facility)
                ->where('vaccination_monitorings.created_at', '>', $latestSummary->created_at);
                
                $to_be_export = $vaccination_monitoring->get();

                $now = Carbon::now();

                if(count($to_be_export) > 0) {

                    $export_summary = new ExportSummary;
                    $export_summary->datetime_requested = $now->toDateTimeString();
                    $export_summary->export_type = convertData('MONITORING');
                    $export_summary->remarks = convertData("VIMS_VAS_" . $now->toDateTimeString()."_". $this->facility_name);
                    $export_summary->generated_by = convertData($this->exporter);
                    $export_summary->user_has_facilities_id = $this->user_has_facility;
                    $export_summary->status = 1;
                    $export_summary->save();

                    $result = true;

                    foreach($to_be_export as $new_export) {

                        $new_entry = new ExportHasPatient;
                        $new_entry->export_summary_id = $export_summary->id;
                        $new_entry->patient_id = $new_export->vaccination_monitorings_id;
                        $new_entry->status = 1;
                        $new_entry->save();

                        if(!$new_entry) {
                            $result = false;
                        }
                    }

                    if($result) {
                        DB::connection('covid19vaccine')->commit();
                        return $vaccination_monitoring;
                    } else {
                        DB::connection('covid19vaccine')->rollback();
                        return $vaccination_monitoring;
                    }

                } else {
                    return $vaccination_monitoring;
                }
            } catch(Exception $ex){
                DB::connection('covid19vaccine')->rollback();
            }

            return $vaccination_monitoring;
        }else{
            // dd(ExportHasPatient::where('export_has_patients.export_summary_id', '=', $this->id)->count());
                $vaccination_monitoring = ExportHasPatient::join('vaccination_monitorings as vaccination_monitorings', 'vaccination_monitorings.id', '=',  'export_has_patients.patient_id')
                    ->join('qualified_patients as qualified_patients', 'qualified_patients.id', '=', 'vaccination_monitorings.qualified_patient_id')
                    ->join('pre_registrations as pre_registrations', 'pre_registrations.id', '=', 'qualified_patients.registration_id')
                    ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
                    ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
                    ->join('vaccination_monitoring_surveys as vaccination_monitoring_surveys', 'vaccination_monitoring_surveys.vaccination_monitoring_id', '=', 'vaccination_monitorings.id')
                    ->join('vaccinators as vaccinators', 'vaccinators.id', '=', 'vaccination_monitorings.vaccinator_id')
                    ->join('health_facilities as health_facilities', 'health_facilities.id', '=', 'vaccinators.health_facilities_id')
                    ->join('vaccine_categories as vaccine_categories', 'vaccine_categories.id', '=', 'vaccination_monitorings.vaccine_category_id')
                    ->join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
                    ->select(
                        'categories.category_format',
                        'id_categories.id_category_code',
                        'id_categories.id as id_category',

                        'pre_registrations.category_id_number',
                        'pre_registrations.philhealth_number',
                        'pre_registrations.barangay_id',
                        'pre_registrations.last_name',
                        'pre_registrations.first_name',
                        'pre_registrations.middle_name',
                        'pre_registrations.suffix',
                        'pre_registrations.contact_number',
                        'pre_registrations.home_address',
                        'pre_registrations.status',
                        'pre_registrations.province',
                        'pre_registrations.city',
                        // 'pre_registrations.barangay',
                        'barangays.real_name as barangay',
                        'pre_registrations.sex',
                        'pre_registrations.date_of_birth',
                        'qualified_patients.qrcode',


                        'vaccination_monitorings.id as vaccination_monitorings_id',
                        'vaccination_monitorings.consent',
                        'vaccination_monitorings.reason_for_refusal',
                        'vaccination_monitorings.vaccination_date',
                        // 'vaccination_monitorings.vaccine_manufacturer',
                        'vaccine_categories.vaccine_name as vaccine_manufacturer',
                        'vaccination_monitorings.batch_number',
                        'vaccination_monitorings.lot_number',
                        'vaccination_monitorings.deferral',
                        'vaccination_monitorings.dosage',
                        // 'vaccination_monitorings.vaccine_manufacturer',
                        // 'pre_registrations.civil_status',
                        'vaccinators.last_name as vaccinator_lastname',
                        'vaccinators.first_name as vaccinator_firstname',
                        // 'vaccinators.middle_name',
                        'vaccinators.suffix',
                        'vaccinators.profession',
                        'health_facilities.facility_name',

                        'vaccination_monitoring_surveys.question_1 as age_validation',
                        'vaccination_monitoring_surveys.question_2 as allergic_for_peg',
                        'vaccination_monitoring_surveys.question_3 as allergic_after_dose',
                        'vaccination_monitoring_surveys.question_4 as allergic_to_food',
                        'vaccination_monitoring_surveys.question_5 as asthma_validation',
                        'vaccination_monitoring_surveys.question_6 as bleeding_disorders',
                        'vaccination_monitoring_surveys.question_7 as syringe_validation',
                        'vaccination_monitoring_surveys.question_8 as symptoms_manifest',
                        'vaccination_monitoring_surveys.question_9 as symptoms_specific',
                        'vaccination_monitoring_surveys.question_10 as infection_history',
                        'vaccination_monitoring_surveys.question_11 as previously_treated',
                        'vaccination_monitoring_surveys.question_12 as received_vaccine',
                        'vaccination_monitoring_surveys.question_13 as received_convalescent',
                        'vaccination_monitoring_surveys.question_14 as pregnant',
                        'vaccination_monitoring_surveys.question_15 as pregnancy_trimester',
                        'vaccination_monitoring_surveys.question_16 as diagnosed_six_months',
                        'vaccination_monitoring_surveys.question_17 as specific_diagnosis',
                        'vaccination_monitoring_surveys.question_18 as medically_cleared'

                    )
                    // ->where('vaccination_monitorings.status', '=', 1)
                    ->where('export_has_patients.export_summary_id', '=', $this->id);

                return $vaccination_monitoring;

        }




    }
}
