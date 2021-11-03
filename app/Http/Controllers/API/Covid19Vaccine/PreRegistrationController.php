<?php

namespace App\Http\Controllers\API\Covid19Vaccine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Covid19Vaccine\Employer;
use App\Covid19Vaccine\PreRegistration;
use App\Covid19Vaccine\Survey;
use App\Covid19Vaccine\Barangay;
use App\Covid19Vaccine\Category;
use App\Covid19Vaccine\EmploymentStatus;
use App\Covid19Vaccine\IdCategory;
use App\Covid19Vaccine\Profession;



use Storage;
use DB;
use Auth;
use Validator;


class PreRegistrationController extends Controller
{
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function validateUser($data){


        return PreRegistration::where('last_name', '=', $data['lastname'])
            ->where('first_name', '=', $data['firstname'])
            ->where('date_of_birth', '=', $data['dob'])
            ->first();
    }

    public function saveRegistrationData(Request $request) {
        if(Auth::user()->account_status == 1) {

            $answer = [];
            if($request['question2'] == "YES"){
                $answer['question3'] = 'required';
            }if($request['question4'] == "YES"){
                $answer['question5'] = 'required';
            }if($request['question6'] == "YES"){
                $answer['question7'] = 'required';
                $answer['question8'] = 'required';
            }

            $validate = Validator::make($request->all(), array_merge([
                'last_name' => 'required',
                'first_name' => 'required',
                'dob' => 'required',
                'sex' => 'required',
                'contact' => 'required',
                'barangay' => 'required',
                'profession' => 'required',
                'category_for_id' => 'required',
                // 'category_id_number' => 'required',
                'address' => 'required',
                'question1' => 'required',
                'question10' => 'required',
                'question9' => 'required',
                // 'g-recaptcha-response' => new ReCaptchaRule()
            ], $answer));

            if($validate->fails()){
                // return response()->json(array('success' => false, 'messages' => 'May be missing required fields or Invalid reCAPTCHA and data entry! Please check your input.', 'title'=> 'Oops! something went wrong.'));


                // dd($request);
                return response()->json(['error'=>$validate->errors()], $this->errorStatus);
                // return response()->json(['status' => $this->errorStatus, 'message' => 'May be missing required fields!'], $this->successStatus);
            } else{
                $data = [
                    'lastname' => convertData($request->last_name),
                    'firstname' => convertData($request->first_name),
                    'middlename' => convertData($request->middle_name),
                    'dob' => convertData($request->dob),
                    'suffix' => convertData($request->affiliation),
                ];



                if(empty($this->validateUser($data))){

                    DB::connection('covid19vaccine')->beginTransaction();
                    try{

                        /* barangay */
                        $barangay = Barangay::findOrFail($request->barangay);
                        // dd($request->barangay);

                        /* employer */

                        $employer = new Employer;
                        $employer->employment_status_id = convertData($request->employment);
                        $employer->profession_id = convertData($request->profession);
                        $employer->specific_profession = convertData($request->specific_profession);
                        $employer->employer_name = convertData($request->employer_name);
                        $employer->employer_contact = convertData($request->employer_contact);
                        $employer->employer_barangay_name = convertData($request->employer_address);
                        $employer->status = '1';
                        $employer->save();



                        $register = new PreRegistration;
                        $register->last_name = convertData($request->last_name);
                        $register->first_name = convertData($request->first_name);
                        $register->middle_name = convertData($request->middle_name);
                        $register->suffix = convertData($request->affiliation);
                        $register->date_of_birth = convertData($request->dob);
                        $register->sex = convertData($request->sex);
                        $register->contact_number = convertData($request->contact);
                        $register->civil_status = convertData($request->civil_status);
                        $register->province = 'LAGUNA';
                        $register->city = 'CABUYAO';
                        $register->barangay = $barangay->barangay;
                        $register->barangay_id = convertData($request->barangay);
                        $register->home_address = convertData($request->address);
                        $register->employment_id = $employer->id;
                        $register->barangay_id = convertData($request->barangay);
                        $register->category_id = convertData($request->category);
                        $register->category_id_number = convertData($request->category_id_number);
                        $register->category_for_id = convertData($request->category_for_id);
                        $register->philhealth_number = convertData($request->philhealth);
                        $register->status = '1';
                        $register->save();




                        // if($request->hasFile('imagefile')) {


                        $filename= date('Y') . '' . $register->id .'.jpg';

                        //     $path=public_path('images/'. $filename);

                        //     //$path='/home/cabuyaovaccine/public_html/images/'. $filename;

                        //     Image::make($request['imagefile']->getRealPath())->resize(200, 200)->save($path);


                        // }
                        // $filename = $person_code->person_code .'.png';

                        // if($request['image']) {
                        //     $base64_image = $request['image'];

                        //     if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                        //         $data = substr($base64_image, strpos($base64_image, ',') + 1);

                        //         $data = base64_decode($data);
                        //         file_put_contents(public_path("\images\covid19_vaccine_preregistration\\").$filename, $data);
                        //     }

                        //     $register->image = 'covid19_vaccine_preregistration/' . $filename;
                        //     $register->save();
                        // } else {
                        //     $register->image = 'covid19_vaccine_preregistration/default-avatar.png';
                        //     $register->save();
                        // }

                        $register->image = 'covid19_vaccine_preregistration/default-avatar.png';
                        $register->save();

                        // $register->image = $request->hasFile('imagefile')? $filename : 'covid19_vaccine_preregistration/default-avatar.png';


                        /* survey */
                        $survey = new Survey;
                        $survey->registration_id = $register->id;
                        $survey->question_1 = ($request['question1'] == "true")? 'YES' : 'NO';
                        $survey->question_2 = ($request['question2'] == "true")? 'YES' : 'NO';
                        $survey->question_3 = ($request['question2'] == "true")? $request['question3'] : null;
                        $survey->question_4 = ($request['question4'] == "true")? 'YES' : 'NO';
                        $survey->question_5 = ($request['question4'] == "true")? $request['question5'] : null;
                        $survey->question_6 = ($request['question6'] == "true")? 'YES' : 'NO';
                        $survey->question_7 = ($request['question6'] == "true")? convertData($request['question7']): null;
                        $survey->question_8 = ($request['question6'] == "true")? convertData($request['question8']): null;
                        $survey->question_9 = ($request['question9'] == "true")? 'YES' : 'NO';
                        $survey->question_10 = ($request['question10'] == "true")? 'YES' : 'NO';
                        $survey->status = '1';
                        $survey->save();

                        DB::connection('covid19vaccine')->commit();

                        // return response()->json(array('success' => true, 'messages' => 'Thank you!'));
                        return response()->json(['status' => $this->successStatus, 'message' => 'Pre-Registration Submitted Success! Please coordinate with your BARANGAY HEALTH CENTER for your vaccination schedule.'], $this->successStatus);


                    }catch(\PDOException $e){
                        DB::connection('covid19vaccine')->rollBack();
                        return response()->json(['status' => $this->queryErrorStatus, 'message' => 'Transaction Failed!'], $this->successStatus);
                        // return response()->json(array('success' => false, 'messages' => 'Transaction Failed!','title' => 'Oops! something went wrong.'));
                    }
                } else {
                    return response()->json(['status' => $this->errorStatus, 'message' => 'Please check your lastname, firstname, middilename and birthday!.'], $this->successStatus);
                    // return response()->json(array('success' => false, 'messages' => 'Please check your lastname, firstname, middilename and birthday!.','title' => 'Your name is already exist to our record!'));
                }

            }
        }
    }
    
     public function checkPreRegExist(Request $request) {

        if(Auth::user()->account_status == 1){


            $validator = Validator::make($request->all(), [
                'registration_code' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], $this->errorStatus);
            }

            try {

                $reg_code = $request->registration_code;

                // dd($reg_code);

                $patient_data = PreRegistration::join('barangays as barangays', 'barangays.id', '=', 'pre_registrations.barangay_id')
                    ->join('categories as categories', 'categories.id', '=', 'pre_registrations.category_id')
                    ->join('employers as employers', 'employers.id', '=', 'pre_registrations.employment_id')
                    ->join('professions as professions', 'professions.id', '=', 'employers.profession_id')
                    ->join('id_categories as id_categories', 'id_categories.id', '=', 'pre_registrations.category_for_id')
                    ->join('employment_statuses as employment_statuses', 'employment_statuses.id', '=', 'employers.employment_status_id')
                    ->leftJoin('surveys as surveys', 'pre_registrations.id', '=', 'surveys.registration_id')
                    ->select(
                        'categories.id as category_id',
                        'categories.category_format',
                        'categories.category_name',

                        'id_categories.id_category_name',
                        'id_categories.id_category_code',
                        'id_categories.id as id_category',

                        'pre_registrations.id as pre_registrations_id',
                        'pre_registrations.category_id_number',
                        'pre_registrations.philhealth_number',
                        'pre_registrations.barangay_id',
                        DB::raw("CONCAT(pre_registrations.first_name,' ',pre_registrations.middle_name,' ',pre_registrations.last_name) AS patient_name"),
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
                        'pre_registrations.image',
                        'pre_registrations.registration_code',

                        'employers.employer_name',
                        'employers.employer_provice',
                        'employers.employer_barangay_name',
                        'employers.employer_contact',
                        'employers.specific_profession',
                        'employment_statuses.employment_type',
                        'professions.profession_name',

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

                    )
                    ->where('pre_registrations.registration_code', '=', $reg_code)
                    ->first();

                if(!empty($patient_data)) {
                    return response()->json(['status' => $this->successStatus, 'data' => $patient_data, 'message' => 'Patient record found!'], $this->successStatus);
                } else {
                    return response()->json(['status' => $this->errorStatus, 'message' => 'Patient record cannot be found!'], $this->errorStatus);
                }


            } catch (\PDOException $e) {
                return response()->json(['status' => $this->queryErrorStatus, 'message' => 'Something went wrong! Please try again'], $this->queryErrorStatus);

            }



        } else {
            return response()->json(['status' => $this->errorStatus, 'message' => 'Something went wrong! Please try again'], $this->errorStatus);
        }
    }

    public function getBarangay(){
        return Barangay::where('status', 1)->get();
    }

    public function getCategories() {
        return Category::where('status', 1)->get();
    }

    public function getIDCategories() {
        return IdCategory::where('status', 1)->get();
    }

    public function getEmploymentStatus() {
        return EmploymentStatus::where('status', 1)->get();
    }

    public function getProfessions() {
        return Profession::where('status', 1)->get();
    }

}
