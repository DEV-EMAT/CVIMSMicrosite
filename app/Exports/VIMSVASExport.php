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


class VIMSVASExport extends StringValueBinder implements WithCustomValueBinder, WithMapping, WithStyles, FromQuery, WithHeadings, WithColumnWidths
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

    public function headings(): array
    {
        return [
            // 'ID',
            'Category',
            'Category_ID',
            'Category_ID_Number',
            'PhilHealth_ID',
            'PWD ID',
            'Last_Name',
            'First_Name',
            'Middle_Name',
            'Suffix',
            'Contact_No.',

            'Current_Residence: _Region',
            'Current_Residence: _Province',
            'Current_Residence: _Municipality/City',
            'Current_Residence: _Barangay',
            // 'Current_Residence: _Home_Address',
            'Sex',
            'Birthdate: mm/dd/yyyy',

            'CONSENT',
            'Reason for refusal',
            'Age more than 16 years old?',
            'Has no allergies to PEG or polysorbate?',
            'Has no severe allergic reaction after the 1st/2nd dose of the vaccine?',
            'Has no allergy to food, egg, medicines, and has asthma?',
            '* If with allergy or asthma, will the vaccinator able to monitor the patient for 30 minutes?',
            'Has no history of bleeding disorders or currently taking anti-coagulants?',
            '* if with bleeding history, is a gauge 23 - 25 syringe available for injection?',
            'Does not manifest any of the following symptoms: Fever/chills, Headache, Cough, Colds, Sore throat,  Myalgia, Fatigue, Weakness, Loss of smell/taste, Diarrhea, Shortness of breath/ difficulty in breathing',
            '* If manifesting any of the mentioned symptom/s, specify all that apply',
            'Has no history of exposure to a confirmed or suspected COVID-19 case in the past 2 weeks?',
            'Has not been previously treated for COVID-19 in the past 90 days?',
            'Has not received any vaccine in the past 2 weeks?',
            'Has not received convalescent plasma or monoclonal antibodies for COVID-19 in the past 90 days?',
            'Not Pregnant?',
            '* if pregnant, 2nd or 3rd Trimester?',
            'Does not have any of the following: HIV, Cancer/ Malignancy, Underwent Transplant, Under Steroid Medication/ Treatment, Bed Ridden, terminal illness, less than 6 months prognosis',
            '* If with mentioned condition/s, specify.',
            '* If with mentioned condition, has presented medical clearance prior to vaccination day?',
            'Deferral',
            'Date of Vaccination',
            'Vaccine Manufacturer Name',
            'Batch Number',
            'Lot Number',
            'Vaccinator Name',
            'Profession of Vaccinator',
            '1st Dose',
            '2nd Dose',
            'Facility Name',

            // 'Date',
        ];
    }

    public function map($entry): array
    {


        $yes_no = array("01_Yes", "02_No");

        $active_region = "CALABARZON";
        $active_province = "_434_LAGUNA";
        $active_city = "_43404_CABUYAO_CITY";

        $first_dose = "02_No";
        $second_dose = "02_No";

        if($entry->dosage == 1) {
            $first_dose = "01_Yes";
            $second_dose = "02_No";
        } else {
            $first_dose = "01_Yes";
            $second_dose = "01_Yes";
        }



        return [

            $this->checkNullData($entry->category_format),
            $this->checkNullData($entry->id_category_code),
            $this->checkNullData($entry->category_id_number),
            $this->checkNullData($entry->philhealth_number),
            // "N/A",
            $this->checkNullData($entry->id_category == 4 ? $entry->category_id_number : "N/A"),
            $this->checkNullData($entry->last_name),
            $this->checkNullData($entry->first_name),
            $this->checkNullData($entry->middle_name),
            $this->checkNullData($entry->suffix),
            $this->checkNullData($entry->contact_number),
            $active_region,
            $active_province,
            $active_city,
            $this->checkNullData($entry->barangay),
            // $this->checkNullData($entry->home_address),
            $this->checkNullData($entry->sex),
            $this->checkNullData($entry->date_of_birth),


            strpos(strtoupper($yes_no[1]), $entry->consent) !== false ? $yes_no[0] : $yes_no[1],
            $this->checkNullData($entry->reason_for_refusal),

            // surveys
            $entry->age_validation,
            $entry->allergic_for_peg == $yes_no[1] ? $yes_no[0] : $yes_no[1],  // strpos(strtoupper($yes_no[0]), $entry->age_validation) !== false ? $yes_no[0] : $yes_no[1],
            $entry->allergic_after_dose == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->allergic_to_food == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->asthma_validation,
            $entry->bleeding_disorders == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->syringe_validation,
            $entry->symptoms_manifest == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $this->checkNullData($entry->symptoms_specific),
            $entry->infection_history == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->previously_treated == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->received_vaccine == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->received_convalescent == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->pregnant == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->pregnancy_trimester == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $entry->diagnosed_six_months == $yes_no[1] ? $yes_no[0] : $yes_no[1],
            $this->checkNullData($entry->specific_diagnosis),
            $entry->medically_cleared == $yes_no[1] ? $yes_no[0] : $yes_no[1],

            // monitoring
            $this->checkNullData($entry->deferral),
            $this->checkNullData($entry->vaccination_date),
            $this->checkNullData($entry->vaccine_manufacturer),
            $this->checkNullData($entry->batch_number),
            $this->checkNullData($entry->lot_number),

            // vaccinator
            $this->checkNullData($entry->vaccinator_firstname . " " . $entry->vaccinator_lastname),
            $this->checkNullData($entry->profession),

            $first_dose,
            $second_dose,
            $entry->facility_name,

        ];
    }

    public function query() {


        if(empty($this->id)){
            DB::connection('covid19vaccine')->beginTransaction();
            try {

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
                        'barangays.DOH_brgy_id as barangay',
                        'pre_registrations.sex',
                        'pre_registrations.date_of_birth',


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
