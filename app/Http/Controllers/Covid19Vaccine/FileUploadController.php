<?php

namespace App\Http\Controllers\Covid19Vaccine;

use App\Covid19Vaccine\Barangay;
use App\Covid19Vaccine\Category;
use App\Covid19Vaccine\Employer;
use App\Covid19Vaccine\EmploymentStatus;
use App\Covid19Vaccine\IdCategory;
use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\Profession;
use App\Covid19Vaccine\Survey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class FileUploadController extends Controller
{

    public function index(){
        return view('covid19_vaccine.file_upload.index', ['title' => 'File Upload']);
    }

    public function categoryValue($category){

        $equivalent = [
            "_02_A2_Senior_Citizens" => '02_Senior_Citizen',
            "_01_A1_Health_Care_Workers" => '01_Health_Care_Worker',
            "05_A5: Poor Population" => '03_Indigent',
            "_04_A4_Frontline_Personnel_in_Essential_Sector" => '04_Uniformed_Personnel',
            "08_B3: Other Essential Workers" => '05_Essential_Worker',
            "12_C: Rest of the Population" => '06_Other',
            "_03_A3_Adult_with_Comorbidity" => '07_Comorbidities',
            "06_B1: Teachers and Social Workers" => '08_Teachers_Social_Workers',
            "07_B2: Other Government Workers" => '09_Other_Govt_Wokers',
            "09_B4: Socio-demographic Groups" => '10_Other_High_Risk',
            "10_B5: Overseas Filipino Workers" => '11_OFW',
            "11_B6: Other Remaining Workforce" => '12_Remaining_Workforce'
        ];

        $flag = false;
        $result = "";

        foreach ($equivalent as $key => $value) {
            if($category == $key){
                $result = $value;
                break;
            }
        }

        return $result;

    }

    public function store(Request $request) {

        $data = json_decode($request['array_of_data'], true);
        $type = $request['file_type'];
        $user = Auth::user()->person;
        $user_exist = [];
        $has_exist_user = false;

        if($type == 'OLD_FORMAT'){
            foreach ($data as $value) {
                $is_user_exist = PreRegistration::where('last_name', '=', $value['Lastname'])->where('first_name', '=', $value['Firstname'])->where('date_of_birth', '=', $value['Birthdate_'])->get();
                // $is_user_exist = PreRegistration::where('last_name', '=', convertData($value['Lastname']))->where('first_name', '=', convertData($value['Firstname']))->where('date_of_birth', '=', $value['Birthdate_'])->get();

                if($is_user_exist->isEmpty()){
                    $barangay = Barangay::where('DOH_brgy_id', '=', $value['Barangay'])->first();
                    $employment_status = EmploymentStatus::where('employment_type_format', '=', $value['Employed'])->first();
                    $category = Category::where('category_format', '=', $value['Category'])->first();
                    $categoryID = IdCategory::where('id_category_code', '=', $value['CategoryID'])->first();
                    $profession = Profession::where('profession_format', '=', $value['Profession'])->first();

                    DB::connection('covid19vaccine')->beginTransaction();

                    try{
                        /* employer */
                        $employer = new Employer;
                        $employer->employment_status_id = !empty($employment_status->id) ? $employment_status->id : 5;
                        $employer->profession_id = !empty($profession->id)? $profession->id : 19;
                        $employer->specific_profession = "";
                        $employer->employer_name = convertData($value['Employer_name']);
                        $employer->employer_contact = convertData($value['Employer_contact_no.']);
                        $employer->employer_barangay_name = convertData($value['Employer_address']);
                        $employer->status = '1';
                        $employer->save();

                        /* pre registrations */
                        $register = new PreRegistration;
                        $register->last_name = convertData($value['Lastname']);
                        $register->first_name = convertData($value['Firstname']);
                        $register->middle_name = convertData($value['Middlename']);
                        $register->suffix = convertData($value['Suffix']);
                        $register->date_of_birth = $value['Birthdate_'];
                        $register->sex = (convertData($value['Sex']) == '01_FEMALE')? '02_FEMALE' : '01_MALE';
                        $register->contact_number = $value['Contact_no'];
                        $register->civil_status = convertData($value['Civilstatus']);
                        $register->employment_id = $employer->id;
                        $register->province = "LAGUNA";
                        $register->city = "CABUYAO";
                        $register->barangay = !empty($barangay->real_name)? $barangay->real_name: 'PULO';
                        $register->barangay_id = !empty($barangay->id)? $barangay->id : 18;
                        $register->category_id = !empty($category->id)? $category->id : 6;
                        $register->category_id_number = convertData($value['CategoryIDnumber']);
                        $register->philhealth_number = convertData($value['PhilHealthID']);
                        $register->home_address = convertData($value['Full_address']);
                        $register->image = 'covid19_vaccine_preregistration/default-avatar.png';
                        $register->transfered_by = 'EXCEL';
                        $register->uploaded_by = convertData($user->last_name.', '. $user->first_name.' '. $user->middle_name);
                        $register->category_for_id = !empty($categoryID->id)? $categoryID->id : 5;
                        $register->status = '1';
                        $register->save();

                        $allergy = [];
                        $comorbidity = [];

                        $value['Allergy_01'] == '01_Yes' ? $allergy[] = 'DRUGS' : null;
                        $value['Allergy_02'] == '01_Yes' ? $allergy[] = 'FOOD' : null;
                        $value['Allergy_03'] == '01_Yes' ? $allergy[] = 'INSECTS' : null;
                        $value['Allergy_04'] == '01_Yes' ? $allergy[] = 'LATEX' : null;
                        $value['Allergy_05'] == '01_Yes' ? $allergy[] = 'MOLD' : null;
                        $value['Allergy_06'] == '01_Yes' ? $allergy[] = 'PET' : null;
                        $value['Allergy_07'] == '01_Yes' ? $allergy[] = 'POLLEN' : null;

                        $value['Comorbidity_01'] == '01_Yes' ? $comorbidity[] = 'HYPERTENSION' : null;
                        $value['Comorbidity_02'] == '01_Yes' ? $comorbidity[] = 'HEART DISEASE' : null;
                        $value['Comorbidity_03'] == '01_Yes' ? $comorbidity[] = 'KIDNEY DISEASE' : null;
                        $value['Comorbidity_04'] == '01_Yes' ? $comorbidity[] = 'DIABETES MELLITUS' : null;
                        $value['Comorbidity_05'] == '01_Yes' ? $comorbidity[] = 'BRONCHIAL ASTHMA' : null;
                        $value['Comorbidity_06'] == '01_Yes' ? $comorbidity[] = 'IMMUNODEFICIENCY STATE' : null;
                        $value['Comorbidity_07'] == '01_Yes' ? $comorbidity[] = 'CANCER' : null;

                        /* survey */
                        $survey = new Survey;
                        $survey->registration_id = $register->id;
                        $survey->question_1 = ($value['Preg_status'] == '01_Yes')? 'YES' : 'NO'; /* pregnant */
                        $survey->question_2 = (!empty($allergy))? 'YES' : 'NO'; /* with allergy */
                        $survey->question_3 = (!empty($allergy))? implode(', ', $allergy) : null; /* types of allergy */
                        $survey->question_4 = ($value['W_comorbidities'] == '01_Yes')? 'YES' : 'NO'; /* with commirbidities */
                        $survey->question_5 = ($value['W_comorbidities'] == '01_Yes')? implode(', ', $comorbidity) : null; /* types of commorbidities */
                        $survey->question_6 = ($value['covid_history'] == '01_Yes')? 'YES' : 'NO'; /* covid infection */
                        $survey->question_7 = ($value['covid_history'] == '01_Yes')? convertData($request['covid_date']): null; /* covid date */
                        $survey->question_8 = ($value['covid_history'] == '01_Yes')? convertData($request['covid_classification']): null; /* covid classification */
                        $survey->question_9 = 'YES'; /* consent */
                        $survey->question_10 = ($value['Direct_covid'] == '01_Yes')? 'YES' : 'NO'; /* interaction covid patient */
                        $survey->status = '1';
                        $survey->save();

                        DB::connection('covid19vaccine')->commit();

                    }catch(\PDOException $e){
                        DB::connection('covid19vaccine')->rollBack();
                    }
                }else{
                    $has_exist_user = true;
                    $user_exist[] = $value;
                }
            }
        }else{
            foreach ($data as $value) {
                $is_user_exist = PreRegistration::where('last_name', '=', $value['Last_Name*'])->where('first_name', '=', $value['First_Name*'])->where('date_of_birth', '=', $value['Birthdate_mm/dd/yyyy_*'])->get();
                
                if($is_user_exist->isEmpty()){
                    $barangay = Barangay::where('DOH_brgy_id', '=', $value["Current_Residence:\r\nBarangay*"])->first();
                    $category = Category::where('category_format', '=',$this->categoryValue($value['Priority Group*']))->first();

                    DB::connection('covid19vaccine')->beginTransaction();

                    try{
                        /* employer */
                        $employer = new Employer;
                        $employer->employment_status_id = 5;
                        $employer->profession_id = 19;
                        $employer->specific_profession = ($value['Occupation*'])? convertData($value['Occupation*']) : "N/A";
                        $employer->employer_name = "N/A";
                        $employer->employer_contact = "N/A";
                        $employer->employer_barangay_name = "N/A";
                        $employer->status = '1';
                        $employer->save();

                        /* pre registrations */
                        $register = new PreRegistration;
                        $register->last_name = convertData($value['Last_Name*']);
                        $register->first_name = convertData($value['First_Name*']);
                        $register->middle_name = convertData($value['Middle_Name*']);
                        $register->suffix = convertData($value['Suffix']);
                        $register->date_of_birth = $value['Birthdate_mm/dd/yyyy_*'];
                        $register->sex = (convertData($value['Sex*']) == '01_FEMALE')? '02_FEMALE' : '01_MALE';
                        $register->contact_number = $value['Contact_No.*'];
                        $register->civil_status = '01_SINGLE';
                        $register->employment_id = $employer->id;
                        $register->province = "LAGUNA";
                        $register->city = "CABUYAO";
                        $register->barangay = !empty($barangay->real_name)? $barangay->real_name: 'PULO';
                        $register->barangay_id = !empty($barangay->id)? $barangay->id : 18;
                        $register->category_id = !empty($category->id)? $category->id : 6;
                        $register->category_id_number = "N/A";
                        $register->philhealth_number = "N/A";
                        $register->home_address = "N/A";
                        $register->image = 'covid19_vaccine_preregistration/default-avatar.png';
                        $register->transfered_by = 'EXCEL';
                        $register->uploaded_by = convertData($user->last_name.', '. $user->first_name.' '. $user->middle_name);
                        $register->category_for_id = 5;
                        $register->status = '1';
                        $register->save();

                        /* survey */
                        $survey = new Survey;
                        $survey->registration_id = $register->id;
                        $survey->question_1 = 'NO'; /* pregnant */
                        $survey->question_2 = ($value["Allergy to vaccines or components of vaccines"] == '01_Yes')? 'YES' : 'NO'; /* with allergy */
                        $survey->question_3 = null; /* types of allergy */
                        $survey->question_4 = ($value['With_Comorbidity?'] == '01_Yes')? 'YES' : 'NO'; /* with commirbidities */
                        $survey->question_5 = null; /* types of commorbidities */
                        $survey->question_6 = 'NO'; /* covid infection */
                        $survey->question_7 = null; /* covid date */
                        $survey->question_8 = null; /* covid classification */
                        $survey->question_9 = 'YES'; /* consent */
                        $survey->question_10 = 'NO'; /* interaction covid patient */
                        $survey->status = '1';
                        $survey->save();

                        DB::connection('covid19vaccine')->commit();

                    }catch(\PDOException $e){
                        DB::connection('covid19vaccine')->rollBack();
                    }
                }else{
                    $has_exist_user = true;
                    $user_exist[] = $value;
                }
            }
        }

        if(!$has_exist_user){
            return response()->json(array('success' => true, 'type'=> $type, 'title' => 'Success!','messages' => 'Excel Record Successfully Saved!'));
        }else{
            return response()->json(array('success' => false, 'type'=> $type, 'title' => 'Oops! something went wrong.','messages' => 'There are some errors on uploading Excel!', 'conflict_data' => $user_exist));
        }
    }
}


