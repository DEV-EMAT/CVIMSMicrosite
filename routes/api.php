<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//API
Route::group([
    'namespace' => 'API'
    ], function (){

    //Ecabs
    Route::group([
        'namespace' => 'Ecabs'
    ], function (){

        Route::prefix('system')->group(function () {
            Route::post('checkundermaintenance', 'MaintenanceController@checkUnderMaintenance');
        });

        Route::prefix('auth')->group(function () {
            Route::post('login', 'AuthController@login')->name('login');
            Route::post('register', 'AuthController@register');
            Route::post('checkuser', 'AuthController@checkUserExists');
            Route::post('forgotpassword/{id}', 'AuthController@forgotPassword');
            Route::post('checkforgot/{id}', 'AuthController@checkChangeAlreadyPass');
            Route::post('checkifexists', 'AuthController@checkIfExists');
            Route::post('refreshtoken', 'AuthController@refreshToken');

        });

        Route::prefix('location')->group(function () {
            Route::get('getph', 'AddressController@getPH');
        });
        Route::prefix('test')->group(function() {
            Route::get('sample', function(){
                // $request['grade_list'] = '[{"id":2,"subject_code":"ENGLISH1","no_of_units":"3","grade":"1.25","subject_grade_equivalent":"93.5","remarks":"Passed","created_at":1604543245945,"updated_at":1604543255710},{"id":3,"subject_code":"FILIPINO1","no_of_units":"3","grade":"1.75","subject_grade_equivalent":"85.5","remarks":"Passed","created_at":1604543285363,"updated_at":1604543285363},{"id":4,"subject_code":"MATH","no_of_units":"3","grade":"2.0","subject_grade_equivalent":"81.5","remarks":"Passed","created_at":1604543310333,"updated_at":1604543310333},{"id":8,"subject_code":"SAMPLE","no_of_units":"3","grade":"1.25","subject_grade_equivalent":"93.5","remarks":"Passed","created_at":1604546086390,"updated_at":1604546086390}]';
                // $request['grade_list'] = json_decode($request['grade_list'],true);
                // return serialize($request['grade_list']);

                // $request['grade_list'] = '[{"id":2,"subject_code":"ENGLISH1","no_of_units":"3","grade":"1.25","subject_grade_equivalent":"93.5","remarks":"Passed","created_at":1604543245945,"updated_at":1604543255710},{"id":3,"subject_code":"FILIPINO1","no_of_units":"3","grade":"1.75","subject_grade_equivalent":"85.5","remarks":"Passed","created_at":1604543285363,"updated_at":1604543285363},{"id":4,"subject_code":"MATH","no_of_units":"3","grade":"2.0","subject_grade_equivalent":"81.5","remarks":"Passed","created_at":1604543310333,"updated_at":1604543310333},{"id":8,"subject_code":"SAMPLE","no_of_units":"3","grade":"1.25","subject_grade_equivalent":"93.5","remarks":"Passed","created_at":1604546086390,"updated_at":1604546086390}]';
                // $request['grade_list'] = json_decode($request['grade_list'],true);
                // return serialize($request['grade_list']);

                $request['data'] = '[{"id":1,"question_id":"2","question":"EDI WOW","answer":"TRUE","choices":"[\"TRUE\",\"FALSE\"]","subject":"FILIPINO","type":"TRUE OR FALSE","my_answer":"TRUE","remarks":"CORRECT","created_at":1606269948655,"updated_at":1606270856714},{"id":2,"question_id":"1","question":"SINO SI RIZAL?","answer":"A","choices":"[\"A\",\"B\",\"C\",\"D\"]","subject":"FILIPINO","type":"MULTIPLE CHOICES","my_answer":null,"remarks":null,"created_at":1606269948655,"updated_at":1606269948655}]';

                $data =  json_decode($request['data'],true);
                return serialize($data);

                // $exam_items = 3;
                // $exam_passing = 80;
                // return ($exam_items) * ($exam_passing / 100);
                // foreach ($request['data'] as $key => $value) {
                // }
            });
        });

        Route::group([
            'middleware' => 'auth:api'
        ], function(){

            //Logout
            Route::prefix('auth')->group(function () {
                Route::post('logout', 'AuthController@logout');
                Route::get('createdat', 'AuthController@createdAtToken');
            });

            //Users
            Route::prefix('user')->group(function () {
                Route::get('profile', 'UserController@profile');
                Route::post('updateprofile', 'UserController@updateProfile');
                Route::post('changepassword', 'AuthController@changePassword');
                Route::post('checkpassword', 'AuthController@checkOldPass');
                Route::post('checkcontact', 'AuthController@checkContact');
                Route::post('updatecontact', 'AuthController@updateContact');
                Route::post('updateemail', 'AuthController@updateEmail');
                Route::get('verify', 'UserController@verifyEmail');
            });

            //Updates
            Route::prefix('updates')->group(function () {
                Route::get('getupdates', 'UpdatesController@getAllUpdates');
                Route::get('getupdate/{id}', 'UpdatesController@getUpdate');
                Route::get('getallrecentupdatesperdepartment', 'UpdatesController@getAllRecentFromDepartment');
            });

            //Barangay
            Route::prefix('barangay')->group(function () {
                Route::get('getbrgy', 'AddressController@getBarangay');
            });

            //Events
            Route::prefix('events')->group(function () {
                Route::get('getmyevents', 'EventsController@getAllEventByDept');
                Route::get('getallevents', 'EventsController@getAllEvents');
                Route::post('pre_register', 'EventsController@preRegistrationOnEvent');
                Route::post('pre-registered-attendees', 'EventsController@getListOfRegisteredOnEvent');
                Route::post('cancel-pre-reg', 'EventsController@cancelPreRegistrationOnEvent');

            });

            //Attendance
            Route::prefix('attendance')->group(function () {
                Route::post('attendees', 'AttendanceController@getAllAttendeesByEventId');
                Route::post('store-attendance', 'AttendanceController@storeAttendance');
            });

        });

    });

    //Covid Tracer
    Route::group([
        'namespace' => 'CovidTracer'
    ], function(){

        Route::group([
            'middleware' => 'auth:api'
        ], function(){

            Route::prefix('emergency')->group(function () {
                //Hotlines
                Route::get('hotlines', 'HotlineController@index');
                // Route::post('hotlines', 'HotlineController@store');
                // Route::post('hotlines/{id}', 'HotlineController@update');
            });

            Route::prefix('covid')->group(function () {
                //Covid tracer
                Route::post('establishmentscanner', 'ScannerController@establishmentToPersonScanner');
                Route::post('persontopersonscanner', 'ScannerController@personToPersonScanner');
                Route::post('persontoestablishmentscanner', 'ScannerController@personToEstablishmentScanner');
                Route::post('offlinescan', 'ScannerController@offlineData');

                //Personal Tracking History
                Route::post('personaltrackinghistory', 'TrackingHistoryController@trackHistoryPerson');

                //Establishments Tracking History
                Route::post('establishmenttrackinghistory', 'TrackingHistoryController@trackHistoryEstablishment');

                //Covid Statistics
                Route::get('covidstats', 'CovidUpdatesController@covidStatistics');
                Route::post('covidstatsbarangay', 'CovidUpdatesController@covidStatisticsPerBarangay');
                Route::get('covidstatstally', 'CovidUpdatesController@covidStatsTally');
                Route::get('getnewandtotalcases', 'CovidUpdatesController@getNewActiveAndTotalActiveCases');

            });

            Route::prefix('establishment')->group(function () {
                Route::get('myestablishments', 'EstablishmentController@getMyEstablishments');
                Route::post('myestablishmentstaff', 'EstablishmentController@getEstablishmentStaff');
                Route::post('myestablishmentstaffstatus', 'EstablishmentController@changeEstablishmentStaffStatus');
                Route::get('getestqr', 'EstablishmentController@getEstablishmentQrCode');

                //Establishment add Staff
                Route::post('addstaff', 'EstablishmentController@addEstablishmentStaff');
                Route::post('checkstatus', 'EstablishmentController@getStaffStatus');
                Route::post('checkestablishmentstatus', 'EstablishmentController@checkEstablishmentStatus');

                Route::get('getestqr', 'EstablishmentController@getEstablishmentQrCode');
            });
        });
    });

    //IskoCab
    Route::group([
        'namespace' => 'IskoCab'
    ], function(){

        Route::group([
            'middleware' => 'auth:api'
        ], function(){

            Route::prefix('iskocab')->group(function () {
                //Pre Registration
                Route::post('application', 'PreRegistrationController@preRegistration');
                Route::get('checkstatus', 'PreRegistrationController@checkScholarStatus');
                Route::get('getscholartypecategory', 'ScholarTypeCategoryController@getScholarTypeCategory');
                //Scholart Type Category SHS, College, Masteral
                Route::get('getcourses', 'CourseController@getCourse');

                Route::get('getschool', 'SchoolController@getSchool');

                //Scholar
                Route::get('scholar-data', 'ScholarController@getScholarData');

                //Program Module
                Route::get('scholarship-modules', 'ScholarController@getScholarshipProgramModules');

                //Scholar Application
                Route::post('scholar-application', 'ScholarController@storeScholarApplicationData');

                //Check for Examination
                Route::get('scholar-check-exam', 'ScholarController@checkforExamination');

                //Get my Exam
                Route::get('get-my-exam', 'ExamController@getIskocabMyExam');

                //Get Scholar Grade History
                Route::get('get-my-grade-history', 'ScholarController@getScholarPerformance');

                //Store Exam
                Route::post('store-exam-data', 'ExamController@storeScholarExam');

            });
        });
    });

    //Spes
    Route::group([
        'namespace' => 'Spes'
    ], function(){

        Route::group([
            'middleware' => 'auth:api'
        ], function(){

            Route::prefix('spes')->group(function () {
                //Get all Beneficiaries
                Route::get('get-spes-beneficiaries', 'SpesController@getSpesBeneficiaries');
            });
        });
    });




    //CovidVaccine
    Route::group([
        'namespace' => 'Covid19Vaccine'
    ], function(){
        // Route::prefix('dashboard')->group(function () {
        //     Route::get('get-stat', 'StatisticsController@getStatistics')->name('api.get.stat');
        // });

        Route::group([
            'middleware' => 'auth:api'
        ], function(){


            Route::prefix('cabvax')->group(function () {
                //Get all my company
                Route::get('lol', function () {
                    return [1, 2, 3];
                });
                Route::post('add-prereg', 'PatientEncodingController@storePreRegistered');
                Route::put('update-prereg', 'PatientEncodingController@updatePreRegistered');
                // Patient Verification Routes
                Route::post('get-patients-list', 'PatientVerificationController@getPatientsList');
                Route::post('check-patient', 'PatientVerificationController@checkPatientExist');
                Route::post('verify-patient', 'PatientVerificationController@verifyPatient');
                //get summary
                Route::post('find-summary', 'PatientVerificationController@findSummary');



                // Patient Encoding Routes
                Route::post('check-pre-reg-exist', 'PreRegistrationController@checkPreRegExist');
                Route::post('get-unverified-patients', 'PatientEncodingController@getUnverifiedPatients');
                Route::post('validate-patient', 'PatientEncodingController@validatePatient');
                Route::post('get-qualified-patients', 'PatientEncodingController@getQualifiedPatients');
                Route::post('monitor-qualified-patient', 'PatientEncodingController@monitorQualifiedPatient'); // eto yung api sa saving
                Route::get('get-vaccine-categories', 'PatientEncodingController@getVaccineCategories');
                Route::get('get-vaccinators', 'PatientEncodingController@getVaccinators');

                // Pre Registration
                Route::get('get-barangay', 'PreRegistrationController@getBarangay');
                Route::get('get-categories', 'PreRegistrationController@getCategories');
                Route::get('get-id-categories', 'PreRegistrationController@getIDCategories');
                Route::get('get-employee-status', 'PreRegistrationController@getEmploymentStatus');
                Route::get('get-professions', 'PreRegistrationController@getProfessions');

                // Route::post('save-registration', 'PreRegistrationController@saveRegistrationData');


                // Dashboard
                Route::get('get-statistics', 'StatisticsController@getStatistics');
                Route::get('get-brgy-statistics', 'StatisticsController@getBarangayStatistics');
                Route::get('get-preregistration-statistics', 'StatisticsController@getPreregisteredStatistics');
                Route::get('get-dose-statistics', 'StatisticsController@getDoseStatistics');






            });

        });
    });

    //GoTrabaho
    Route::group([
        'namespace' => 'GoTrabaho'
    ], function(){

        Route::group([
            'middleware' => 'auth:api'
        ], function(){

            Route::prefix('owner')->group(function () {
                //Get all my company
                Route::get('my-company', 'CompanyController@getMyCompany');
            });

            Route::prefix('employee')->group(function () {
                //Get my employee profile
                Route::get('my-profile', 'EmployeeController@getMyProfile');

                //Get my job bookmarks
                Route::get('my-job-bookmarks', 'EmployeeController@getMyBookmarks');

                //get my job applications
                Route::get('my-job-applications', 'EmployeeController@getMyJobApplications');

                //store employee profile
                Route::post('store-employee-info', 'EmployeeController@storeEmployeeInfo');

                //update employee profile
                Route::post('update-employee-info', 'EmployeeController@updateEmployeeInfo');

                //store employee professional license
                Route::post('store-employee-pro-license', 'EmployeeController@storeEmployeeProfessionalLicense');

                //update employee professional license
                Route::post('update-employee-pro-license', 'EmployeeController@updateEmployeeProfessionalLicense');

                //delete employee professional license
                Route::post('delete-employee-pro-license', 'EmployeeController@deleteEmployeeProfessionalLicense');

                //store employee sea based
                Route::post('store-employee-sea-based', 'EmployeeController@storeEmployeeSeaBasedWorkers');

                //update employee sea based
                Route::post('update-employee-sea-based', 'EmployeeController@updateEmployeeSeaBasedWorkers');

                //delete employee sea based
                Route::post('delete-employee-sea-based', 'EmployeeController@deleteEmployeeSeaBasedWorkers');

                //store employee vocational
                Route::post('store-employee-vocational', 'EmployeeController@storeEmployeeVocationalTraining');

                //update employee vocational
                Route::post('update-employee-vocational', 'EmployeeController@updateEmployeeVocationalTraining');

                //delete employee vocational
                Route::post('delete-employee-vocational', 'EmployeeController@deleteEmployeeVocationalTraining');

                //store employee work exp
                Route::post('store-employee-work-exp', 'EmployeeController@storeEmployeeWorkExperience');

                //update employee work exp
                Route::post('update-employee-work-exp', 'EmployeeController@updateEmployeeWorkExperience');

                //delete employee work exp
                Route::post('delete-employee-work-exp', 'EmployeeController@deleteEmployeeWorkExperience');

                //store employee cert
                Route::post('store-employee-cert-compt', 'EmployeeController@storeEmployeeCertificateOfCompetence');

                //update employee cert
                Route::post('update-employee-cert-compt', 'EmployeeController@updateEmployeeCertificateOfCompetence');

                //delete employee cert
                Route::post('delete-employee-cert-compt', 'EmployeeController@deleteEmployeeCertificateOfCompetence');

                //store employee eligibility
                Route::post('store-employee-eligi', 'EmployeeController@storeEmployeeEligibility');

                //update employee eligibility
                Route::post('update-employee-eligi', 'EmployeeController@updateEmployeeEligibility');

                //delete employee eligibility
                Route::post('delete-employee-eligi', 'EmployeeController@deleteEmployeeEligibility');

                //store employee formal education
                Route::post('store-employee-formal-educ', 'EmployeeController@storeEmployeeFormalEducation');

                //update employee formal education
                Route::post('update-employee-formal-educ', 'EmployeeController@updateEmployeeFormalEducation');

                //delete employee formal education
                Route::post('delete-employee-formal-educ', 'EmployeeController@deleteEmployeeFormalEducation');

                //store employee other skills
                Route::post('store-employee-other-skills', 'EmployeeController@storeEmployeeOtherSkills');

                //update employee other skills
                Route::post('update-employee-other-skills', 'EmployeeController@updateEmployeeOtherSkills');

                //delete employee other skills
                Route::post('delete-employee-other-skills', 'EmployeeController@deleteEmployeeOtherSkills');
            });

            Route::prefix('jobs')->group(function () {
                //Get search job
                Route::post('search-job', 'JobController@SearchJob');
            });

            Route::prefix('job-category')->group(function () {
                //Get job category
                Route::get('get-job-categories', 'JobCategoryController@getJobCategory');
            });


        });
    });
});





