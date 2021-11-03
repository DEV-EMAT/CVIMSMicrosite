<?php

namespace App\Exports;

use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\ExportSummary;
use App\Covid19Vaccine\ExportHasPatient;

use Carbon\Carbon;
use DB;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;


// class PreRegistrationsExport implements WithMapping, WithStyles, FromQuery, WithHeadings, ShouldAutoSize
class PreRegistrationsExport extends StringValueBinder implements WithCustomValueBinder, WithMapping, WithStyles, FromQuery, WithHeadings, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    protected $id;

    function __construct($id="") {
        $this->id = $id;
        ini_set('memory_limit', '8192M');
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
            'J' => 35,
            'K' => 35,
            'L' => 35,
            'M' => 35,
            'N' => 35,
            'O' => 35,
            'P' => 35,
            'Q' => 35,
            'R' => 35,
            'S' => 35,
            'T' => 35,
            'U' => 35,
            'V' => 35,
            'W' => 35,
            'X' => 35,
            'Y' => 35,
            'Z' => 35,
            'AA' => 35,
            'AB' => 35,
            'AC' => 35,
            'AD' => 35,
            'AE' => 35,
            'AF' => 35,
            'AG' => 35,
            'AH' => 35,
            'AI' => 35,
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

        ];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getRowDimension(1)->setRowHeight(80);

        // $sheet->getStyle();

        return [
            'A1:AT1'    => [
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
        ];
    }

    public function headings(): array
    {
        return [
            // 'ID',
            'Category*',
            'Category_ID*',
            'Category_ID_Number*',
            'PhilHealth_ID*',
            'PWD ID',
            'Last_Name*',
            'First_Name*',
            'Middle_Name*',
            'Suffix*',
            'Contact_Number*',
            'Current_Residence: _Unit/Building/House_Number, _Street_Name*',
            'Current_Residence: _Region*',
            'Current_Residence: _Province*',
            'Current_Residence: _Municipality/City*',
            'Current_Residence: _Barangay*',
            'Sex*',
            'Birthdate: mm/dd/yyyy*',
            'Civil_Status*',
            'Employment_Status*',
            'Directly_in_interaction_with_COVID_patient*',
            'Profession*', // dito nagtapos
            'Name_of_Employer*',
            'Province/HUC/ICC_of_Employer*',
            'Address_of_Employer*',
            'Contact_number_of_employer*',
            'Pregnancy_status',
            'Drug_Allergy?',
            'Food_Allergy?',
            'Insect_Allergy?',
            'Latex_Allergy?',
            'Mold_Allergy?',
            'Pet_Allergy?',
            'Pollen_Allergy?',
            'With_Comorbidity?',
            'Hypertension',
            'Heart_Disease',
            'Kidney_Disease',
            'Diabetes_Mellitus',
            'Bronchial_Asthma',
            'Immunodeficiency_Status*',
            'Cancer',
            'Others',
            'Patient_was_diagnosed_with_COVID_19',
            'Date_of_first_positive_result_/_specimen_collection_mm/dd/yyyy_',
            'Classification_of_COVID_19',
            'Willing to_be_Vaccinated?',

            // 'Date',
        ];
    }

    function checkNullData($data) {
        return is_null($data) || $data == "" ? "N/A" : $data;
    }

    public function map($entry): array
    {

        $yes_no = array("01_Yes", "02_No");
        $classification = array("01_Asymptomatic", "02_Mild", "03_Moderate", "04_Severe", "05_Critical");
        $active_class = "";
        $active_region = "CALABARZON";
        $active_province = "_434_LAGUNA";
        $active_city = "_43404_CABUYAO_CITY";


        $employer_province = "434 - LAGUNA";

        $allergies = array(
            "DRUG" => "02_No",
            "FOOD" => "02_No",
            "INSECTS" => "02_No",
            "LATEX" => "02_No",
            "MOLD" => "02_No",
            "PET" => "02_No",
            "POLLEN" => "02_No",
            "OTHERS" => "02_No",
        );

        $comorbidities = array(
            "HYPERTENSION" => "02_No",
            "HEART DISEASE" => "02_No",
            "KIDNEY DISEASE" => "02_No",
            "DIABETES MELLITUS" => "02_No",
            "BROCHIAL ASTHMA" => "02_No",
            "IMMUNODEFICIENCY STATE" => "02_No",
            "CANCER" => "02_No",
            "OTHERS" => "02_No",
        );

        if($entry->has_allergy == "YES") {
            $types = explode(", ", $entry->allergy_types);

            foreach($types as $type) {
                if(array_key_exists(strtoupper($type), $allergies)) {
                    $allergies[$type] = "01_Yes";
                }
            }

        }

        if($entry->has_history == "YES") {

            foreach($classification as $class) {
                // dd(strtoupper($entry->infection_class));
                if(!is_null($entry->infection_class) && $entry->infection_class != "") {

                    if(strpos(strtoupper($class), $entry->infection_class) !== false ) {
                        $active_class = $class;
                        break;
                    }
                }
            }
        } else {
            $active_class = "N/A";
        }

        if($entry->has_comorbidities == "YES") {
            $types = explode(", ", $entry->comorbidities_type);

            foreach($types as $type) {
                if(array_key_exists(strtoupper($type), $comorbidities)) {
                    $comorbidities[$type] = "01_Yes";
                }
            }

        }


        $id_category_code = $this->checkNullData($entry->id_category_code);

        if($id_category_code != "N/A") {
            if($id_category_code == "04 – PWD ID") {
                $id_category_code = "04_Other_ID";
            }
        }

        return [
            $this->checkNullData($entry->category_format),
            $id_category_code,
            $this->checkNullData($entry->category_id_number),
            $this->checkNullData($entry->philhealth_number),
            // "N/A",
            $this->checkNullData($entry->id_category == 4 ? $entry->category_id_number : "N/A"),
            $this->checkNullData($entry->last_name),
            $this->checkNullData($entry->first_name),
            $this->checkNullData($entry->middle_name),
            $this->checkNullData($entry->suffix),
            $this->checkNullData($entry->contact_number),
            $this->checkNullData($entry->home_address),
            $active_region,
            $active_province,
            $active_city,
            $this->checkNullData($entry->barangay),
            $this->checkNullData($entry->sex),
            $this->checkNullData($entry->date_of_birth),
            $this->checkNullData($entry->civil_status),
            $this->checkNullData($entry->employment_type_format),
            $entry->covid_contact == "YES" ? $yes_no[0] : $yes_no[1], // covid_contact
            // $yes_no[1],
            $this->checkNullData($entry->profession_format), // dito nagtapos
            // $employer_province,
            $this->checkNullData($entry->employer_name),
            $this->checkNullData($entry->employer_provice),
            $this->checkNullData($entry->employer_barangay_name),
            $this->checkNullData($entry->employer_contact),
            // strpos(strtoupper($yes_no[0]), $entry->pregnant) !== false ? $yes_no[0] : $yes_no[1], // pregnant
            $entry->pregnant == "YES" ? "01_PREGNANT": "02_NOT_PREGNANT",
            // "02_NOT_PREGNANT",
            $allergies['DRUG'],
            $allergies['FOOD'],
            $allergies['INSECTS'],
            $allergies['LATEX'],
            $allergies['MOLD'],
            $allergies['PET'],
            $allergies['POLLEN'],
            // $allergies['OTHERS']
            $entry->has_comorbidities == "YES" ? $yes_no[0] : "02_NONE", // has_comorbidities
            // "02_NONE",
            $comorbidities['HYPERTENSION'],
            $comorbidities['HEART DISEASE'],
            $comorbidities['KIDNEY DISEASE'],
            $comorbidities['DIABETES MELLITUS'],
            $comorbidities['BROCHIAL ASTHMA'],
            $comorbidities['IMMUNODEFICIENCY STATE'],
            $comorbidities['CANCER'],
            $comorbidities['OTHERS'],
            $entry->has_history == "YES" ? $yes_no[0] : $yes_no[1], // has_history
            // $yes_no[1],
            $this->checkNullData($entry->date_of_infection),
            $active_class,
            $yes_no[0]
            // strpos(strtoupper($yes_no[0]), $entry->electronic_informed) !== false ? $yes_no[0] : $yes_no[1], // electronic informed

        ];
    }

    public function query() {


        if(empty($this->id)){
            DB::connection('covid19vaccine')->beginTransaction();
            try {

                $exp_summaries = ExportSummary::where('export_type', 'MASTERLIST')->get();
                $exp_ids = array();

                foreach($exp_summaries as $exp_sum) {
                    $exp_ids[] = $exp_sum->id;
                }

                $exported_list = ExportHasPatient::whereIn('export_has_patients.export_summary_id', $exp_ids)->get();

                $uploaded = array();
                foreach($exported_list as $exp) {
                    $uploaded[] = $exp->patient_id;
                }

                // dd($uploaded);

                $pre_registrations = PreRegistration::join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
                    ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
                    ->join('employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
                    ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
                    ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
                    ->join('employment_statuses as employment_statuses', 'employment_statuses.id', '=', 'employers.employment_status_id')
                    ->leftJoin('surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id')
                    ->select(
                        'categories.category_format',
                        'id_categories.id_category_code',
                        'id_categories.id as id_category',

                        'pre_registrations.id as pre_registrations_id',
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
                        'barangays.DOH_brgy_id as barangay',
                        'pre_registrations.sex',
                        'pre_registrations.date_of_birth',
                        'pre_registrations.civil_status',

                        'employers.employer_name',
                        'employers.employer_provice',
                        'employers.employer_barangay_name',
                        'employers.employer_contact',
                        'employment_statuses.employment_type_format',
                        'professions.profession_format',

                        'surveys.question_1 as pregnant',
                        'surveys.question_2 as has_allergy',
                        'surveys.question_3 as allergy_types',
                        'surveys.question_4 as has_comorbidities',
                        'surveys.question_5 as comorbidities_type',
                        'surveys.question_6 as has_history',
                        'surveys.question_7 as date_of_infection',
                        'surveys.question_8 as infection_class',
                        'surveys.question_9 as electronic_informed',
                        'surveys.question_10 as covid_contact'
                    )->whereNotIn('pre_registrations.id', $uploaded);


                $to_be_export = $pre_registrations->get();


                // dd($to_be_export[0]);





                $now = Carbon::now();

                if(count($to_be_export) > 0) {




                    $export_summary = new ExportSummary;
                    $export_summary->datetime_requested = $now->toDateTimeString();
                    $export_summary->export_type = convertData('MASTERLIST');
                    $export_summary->remarks = convertData("CABVAX_GENERATED_" . $now->toDateTimeString());
                    $export_summary->status = 1;
                    $export_summary->save();

                    $result = true;

                    // for($index = 0; $index < 1000; $index++) {
                    //     $new_entry = new ExportHasPatient;
                    //     $new_entry->export_summary_id = $export_summary->id;
                    //     $new_entry->patient_id = $to_be_export[$index]->pre_registrations_id;
                    //     $new_entry->status = 1;
                    //     $new_entry->save();

                    //     if(!$new_entry) {
                    //         $result = false;
                    //     }
                    // }
                    foreach($to_be_export as $new_export) {

                        $new_entry = new ExportHasPatient;
                        $new_entry->export_summary_id = $export_summary->id;
                        $new_entry->patient_id = $new_export->pre_registrations_id;
                        $new_entry->status = 1;
                        $new_entry->save();

                        if(!$new_entry) {
                            $result = false;
                        }
                    }

                    if($result) {
                        DB::connection('covid19vaccine')->commit();
                        return $pre_registrations->limit(1000);
                    } else {
                        DB::connection('covid19vaccine')->rollback();
                        return $pre_registrations->limit(1000);
                    }

                } else {
                    return $pre_registrations;
                }
            } catch(Exception $ex){
                DB::connection('covid19vaccine')->rollback();
            }

            return $pre_registrations;
        }else{

            $pre_registrations = ExportHasPatient::join('pre_registrations as pre_registrations', 'pre_registrations.id', '=',  'export_has_patients.patient_id')
                ->join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
                ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
                ->join('employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
                ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
                ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
                ->join('employment_statuses as employment_statuses', 'employment_statuses.id', '=', 'employers.employment_status_id')
                ->leftJoin('surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id')
                ->select(
                    'categories.category_format',
                    'id_categories.id_category_code',
                    'id_categories.id as id_category',

                    'pre_registrations.id as pre_registrations_id',
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
                    'barangays.DOH_brgy_id as barangay',
                    'pre_registrations.sex',
                    'pre_registrations.date_of_birth',
                    'pre_registrations.civil_status',

                    'employers.employer_name',
                    'employers.employer_provice',
                    'employers.employer_barangay_name',
                    'employers.employer_contact',
                    'employment_statuses.employment_type_format',
                    'professions.profession_format',

                    'surveys.question_1 as pregnant',
                    'surveys.question_2 as has_allergy',
                    'surveys.question_3 as allergy_types',
                    'surveys.question_4 as has_comorbidities',
                    'surveys.question_5 as comorbidities_type',
                    'surveys.question_6 as has_history',
                    'surveys.question_7 as date_of_infection',
                    'surveys.question_8 as infection_class',
                    'surveys.question_9 as electronic_informed',
                    'surveys.question_10 as covid_contact'
                )->where('export_has_patients.export_summary_id', '=', $this->id);

                return $pre_registrations;

        }



    }

}
