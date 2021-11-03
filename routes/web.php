<?php
use Spatie\Geocoder\Facades\Geocoder;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
|--------------------------------------------------------------------------
| Global Routes
|-------------------`-------------------------------------------------------
*/

/* set ecabs as welcome page */
Route::get('/', function () { return view('covid19_vaccine.home.index'); })->name('website_home');
// Route::get('/', function () { return redirect()->to('/login'); })->name('website_home');

// Route::get('/', 'HomeController@vaccine_dashboard')->name('website_home');
/* disable register */
Auth::routes(['register' => false]);

/* logout routes */
Route::post('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout')->middleware('auth');

Route::get('privacy-and-terms', function () {
    return view('layouts.widgets.privacy_and_terms');
})->name('privacy_and_terms');


Route::get('clear_cache', function () {

    \Artisan::call('cache:clear');

    dd("Cache is cleared");

});

// Route::get('clear_cache', function () {

//     \Artisan::call('cache:clear');

//     dd("Cache is cleared");

// });

// Route::get('make_migration', function () {

//     \Artisan::call('make:model Covid19Vaccine/IdCategory -m');

//     dd("migration created");

// });


// Route::get('make_migration', function () {

//     \Artisan::call('make:model Covid19VaccineOnline/PreRegistration -m');
//     \Artisan::call('make:model Covid19VaccineOnline/Employer -m');
//     \Artisan::call('make:model Covid19VaccineOnline/Survey -m');

//     dd("migration created");
// });


Route::get('make_controller', function () {
    \Artisan::call('make:export VASLineExport');

    dd("controller created");
});

// Route::get('make_seeder', function () {

//     \Artisan::call('make:seeder VaxBarangaySeeder');

//     dd("seeder created");

// });


// Route::get('make_controller', function () {

//     \Artisan::call('make:controller Covid19Vaccine/IdCategoryController -r');

//     dd("seeder created");

// });


// Route::get('reset_database/covid19vaccine', function () {
//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/Covid19Vaccine',
//             '--database' => 'covid19vaccine',
//             '--force' => true
//         ));
//     return "DATABASE RESET SUCCESS";
// });

// Route::get('seeder', function () {

//     \Artisan::call('db:seed');

//     return "DATABASE RESET SUCCESS";
// });




// Route::get('reset_database/sidebar', function () {
//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/Sidebar',
//             '--database' => 'sidebar',
//             '--force' => true
//         ));
//     return "DATABASE RESET SUCCESS";
// });


// Route::get('reset_database/gotrabaho', function () {
//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/GoTrabaho',
//             '--database' => 'gotrabaho',
//             '--force' => true
//         ));
//     return "DATABASE RESET SUCCESS";
// });

// Route::get('reset_database/comprehensive', function () {
//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/comprehensive',
//             '--database' => 'comprehensive',
//             '--force' => true
//         ));
//     return "DATABASE RESET SUCCESS";
// });

// Route::get('reset_database/prereg', function () {
//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/PreReg',
//             '--database' => 'preregistration',
//             '--force' => true
//         ));
//     return "DATABASE RESET SUCCESS";
// });

// Route::get('reset_database/iskocab', function () {
//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/IskoCab',
//             '--database' => 'iskocab',
//             '--force' => true
//         ));
//     return "DATABASE RESET SUCCESS";
// });

// Route::get('reset_database/covid', function () {

//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/CovidTracer',
//             '--database' => 'covid_tracer',
//             '--force' => true
//         ));

//     return "DATABASE RESET SUCCESS";
// });

// Route::get('reset_database/covid19vaccine', function () {

//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/Covid19Vaccine',
//             '--database' => 'covid19vaccine',
//             '--force' => true
//         ));

//     return "DATABASE RESET SUCCESS";
// });


// Route::get('reset_database/emergency', function () {
//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/EmergencyResponse',
//             '--database' => 'emergencyresponse',
//             '--force' => true
//         ));
//     return "DATABASE RESET SUCCESS";
// });



// Route::get('reset_database/ecabs', function () {
//     $folder = 'ecabs';
//     $database = 'covid_tracer';

//     \Artisan::call('migrate:fresh',
//         array(
//             '--path' => 'database/migrations/ecabs',
//             '--database' => 'mysql',
//             '--force' => true
//         ));

//     return "DATABASE RESET SUCCESS";
// });

// Route::get('seeder', function () {

//     \Artisan::call('db:seed');

//     return "DATABASE RESET SUCCESS";
// });


Route::get('verify/{person_code}/{code}/{user_code}', 'ecabs\UserController@updatingEmailVerified');



/*
|--------------------------------------------------------------------------
| End Global Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Enterprise Cabuyao Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function() {

    /* for home dashboard */
    // Route::get('/dashboard', 'HomeController@ecabs')->name('ecabs.dashboard'); //->middleware(['permission:viewCovidDashboard'])

    /* barangay routes */
    Route::get('/barangay/findall2', 'ecabs\BarangayController@findall2')->name('barangay.findall2');
    Route::post('/barangay/findall', 'ecabs\BarangayController@findall')->name('barangay.findall');
    Route::post('/barangay/status/{id}', 'ecabs\BarangayController@togglestatus')->name('barangay.updatestatus');
    Route::resource('/barangay', 'ecabs\BarangayController');
    Route::resource('/barangay', 'ecabs\BarangayController',['only'=>['create','store']]);
    Route::resource('/barangay', 'ecabs\BarangayController',['only'=>['edit','update']]);
    Route::resource('/barangay', 'ecabs\BarangayController',['only'=>['index']]);

    /* department routes */
    Route::get('/department/findall3', 'ecabs\DepartmentController@findall3')->name('department.findall3');
    Route::get('/department/findall2', 'ecabs\DepartmentController@findall2')->name('department.findall2');
    Route::post('/department/toggle/{id}', 'ecabs\DepartmentController@togglestatus')->name('department.toggle');
    Route::post('/department/findall', 'ecabs\DepartmentController@findall')->name('department.findall');
    Route::resource('/department', 'ecabs\DepartmentController');
    Route::resource('/department', 'ecabs\DepartmentController',['only'=>['create','store']])->middleware(['permission:createDepartment']);
    Route::resource('/department', 'ecabs\DepartmentController',['only'=>['edit','update']])->middleware(['permission:updateDepartment']);
    Route::resource('/department', 'ecabs\DepartmentController',['only'=>['index']])->middleware(['permission:viewDepartment,updateDepartment,deleteDepartment']);

    /* updates routes */
    Route::post('/updates/findall', 'ecabs\UpdateController@findall')->name('updates.findall');
    Route::get('/updates/archive', 'ecabs\UpdateController@archive')->name('updates.archive');
    Route::post('/updates/status/{id}', 'ecabs\UpdateController@togglestatus')->name('updates.updatestatus');
    Route::resource('/updates', 'ecabs\UpdateController');
    Route::resource('/updates', 'ecabs\UpdateController',['only'=>['create','store']])->middleware(['permission:createUpdates']);
    Route::resource('/updates', 'ecabs\UpdateController',['only'=>['edit','update']])->middleware(['permission:updateUpdates']);
    Route::resource('/updates', 'ecabs\UpdateController',['only'=>['index']])->middleware(['permission:viewUpdates,updateUpdates,deleteUpdates']);

    /* access routes */
    Route::post('/access/findall3', 'ecabs\PositionAccessController@findall3')->name('access.findall3');
    Route::get('/access/findall2', 'ecabs\PositionAccessController@findall2')->name('access.findall2');
    Route::post('/access/findall', 'ecabs\PositionAccessController@findall')->name('access.findall');
    Route::post('/access/toggle/{id}', 'ecabs\PositionAccessController@togglestatus')->name('access.toggle');
    Route::resource('/access', 'ecabs\PositionAccessController');
    Route::resource('/access', 'ecabs\PositionAccessController',['only'=>['create','store']])->middleware(['permission:createAccess']);
    Route::resource('/access', 'ecabs\PositionAccessController',['only'=>['edit','update']])->middleware(['permission:updateAccess']);
    Route::resource('/access', 'ecabs\PositionAccessController',['only'=>['index']])->middleware(['permission:viewAccess,updateAccess,deleteAccess']);

    //account routes
    Route::get('/account/count', 'ecabs\UserController@userCount')->name('account.userCounter');
    Route::get('/account/profile', 'ecabs\UserController@profile')->name('account.profile');
    Route::get('/account/archive', 'ecabs\UserController@archive')->name('account.archive')->middleware(['permission:restoreAccount']);
    Route::get('/account/resetpassword', 'ecabs\UserController@gotoresetpassword')->name('account.gotoresetpassword')->middleware(['permission:resetAccount']);
    Route::post('/account/findall', 'ecabs\UserController@findall')->name('account.findall');
    Route::get('/account/findallforcombobox', 'ecabs\UserController@findallforcombobox')->name('account.findallforcombobox');
    Route::post('/account/update/status/{id}', 'ecabs\UserController@updatestatus')->name('account.updateStatus');
    Route::post('/account/resetpassword/{id}', 'ecabs\UserController@resetpassword')->name('account.resetpassword');
    Route::post('/account/changepassword/{id}', 'ecabs\UserController@changepassword')->name('account.changepassword');
    Route::get('/account/print-qr-code', 'ecabs\UserController@qrCodePrinting')->name('account.qr-code-printing')->middleware(['permission:viewPrintUserCode']);
    Route::get('/account/print-qr-code/{id}', 'ecabs\UserController@printQrCode')->name('account.print-qr-code')->middleware(['permission:viewPrintUserCode']);
    Route::post('/account/verify-password/', 'ecabs\UserController@verifyPassword')->name('account.verify-password');
    
    //check default password
    Route::get('/account/check-password/', 'ecabs\UserController@checkPassword')->name('account.check-password');
    Route::post('/account/changepassword/{id}', 'ecabs\UserController@changepassword')->name('account.changepassword');
    
    Route::resource('/account', 'ecabs\UserController');
    Route::resource('/account', 'ecabs\UserController',['only'=>['create','store']])->middleware(['permission:createAccount']);
    Route::resource('/account', 'ecabs\UserController',['only'=>['edit','update']])->middleware(['permission:updateAccount']);
    Route::resource('/account', 'ecabs\UserController',['only'=>['index']])->middleware(['permission:viewAccount,updateAccount,deleteAccount']);

    // Route::get('/account-deletion-history', 'ecabs\UserController@deletionHistory')->name('account.deletion-history');
    Route::post('/account-deletion-history/findall', 'ecabs\UserDeletionHistoryController@findall')->name('account-deletion-history.findall');
    Route::post('/account-deletion-history/find-history','ecabs\UserDeletionHistoryController@findHistory')->name('account-deletion-history.find-history');
    Route::resource('/account-deletion-history', 'ecabs\UserDeletionHistoryController');
    Route::resource('/account-deletion-history', 'ecabs\UserDeletionHistoryController',['only'=>['index']])->middleware(['permission:deleteHistory']);

    //guest accounts
    Route::post('/guest-account/findall', 'ecabs\GuestAccountsController@findall')->name('guest-account.findall');
    Route::get('/guest-account/print-qr-code', 'ecabs\GuestAccountsController@qrCodePrinting')->name('guest-account.qr-code-printing')->middleware(['permission:viewPrintGuestCode']);
    Route::get('/guest-account/print-qr-code/{id}', 'ecabs\GuestAccountsController@printQrCode')->name('guest-account.print-qr-code')->middleware(['permission:viewPrintGuestCode']);
    Route::resource('/guest-account', 'ecabs\GuestAccountsController');
    Route::resource('/guest-account', 'ecabs\GuestAccountsController', ['only'=>['create','store']])->middleware(['permission:createGuestAccount']);
    Route::resource('/guest-account', 'ecabs\GuestAccountsController', ['only'=>['index']])->middleware(['permission:viewGuestAccount']);

    //pre-registration
    Route::resource('/pre-register', 'ecabs\PreRegistrationController');
    Route::resource('/pre-register', 'ecabs\PreRegistrationController',['only'=>['index']])->middleware(['permission:verifyAccount']);
    Route::post('/pre-register/findall', 'ecabs\PreRegistrationController@findall')->name('pre-register.findall');

    //logs
    Route::post('/logs/find-all', 'ecabs\LogsController@findAllLogs')->name('logs.find-all');
    Route::resource('/logs', 'ecabs\LogsController')->middleware(['permission:viewLogs']);


    //event management
    // Route::post('/event/toggle/{id}', 'ecabs\EventController@togglestatus')->name('event.toggle')->middleware(['permission:deleteEvent']);
    // Route::post('/event/closeevent/{id}', 'ecabs\EventController@closeevent')->name('event.closeevent')->middleware(['permission:viewSelectEvent']);
    // Route::put('/event/toggleinout/{id}', 'ecabs\EventController@toggleinout')->name('event.toggleinout')->middleware(['permission:viewSelectEvent']);
    // Route::post('/event/findall', 'ecabs\EventController@findall')->name('event.findall');
    // Route::get('/event/checkeventstatus', 'ecabs\EventController@checkeventstatus')->name('event.checkeventstatus');
    // Route::resource('/event', 'ecabs\EventController');
    // Route::resource('/event', 'ecabs\EventController', ['only' => ['create', 'store']])->middleware(['permission:createEvent']);
    // Route::resource('/event', 'ecabs\EventController', ['only' => ['show','edit', 'update']])->middleware(['permission:updateEvent,viewSelectEvent']);
    // Route::resource('/event', 'ecabs\EventController', ['only' => ['index']])->middleware(['permission:viewEvent,deleteEvent,updateEvent,createEvent']);

    /* maintenance */
    Route::post('/maintenance/update-status', 'ecabs\MaintenanceController@update_status')->name('maintenance.update_status')->middleware(['permission:updatePreRegistration']);
    Route::post('/maintenance/findall', 'ecabs\MaintenanceController@findall')->name('maintenance.findall');
    Route::resource('/maintenance', 'ecabs\MaintenanceController');
    Route::resource('/maintenance', 'ecabs\MaintenanceController', ['only' => ['create', 'store']])->middleware(['permission:createPreRegistration']);
    Route::resource('/maintenance', 'ecabs\MaintenanceController', ['only' => ['show','edit', 'update']])->middleware(['permission:updatePreRegistration']);
    Route::resource('/maintenance', 'ecabs\MaintenanceController', ['only' => ['index']])->middleware(['permission:viewPreRegistration,updatePreRegistration,createPreRegistration']);

     /* file manager */
     Route::resource('/file-manager', 'ecabs\FileManagerController');
     Route::resource('/file-manager', 'ecabs\FileManagerController', ['only' => ['create', 'store']]);
     Route::resource('/file-manager', 'ecabs\FileManagerController', ['only' => ['show','edit', 'update']]);
     Route::resource('/file-manager', 'ecabs\FileManagerController', ['only' => ['index']])->middleware(['permission:viewFileManagerManagement']);


});

/*
|--------------------------------------------------------------------------
| End Enterprise Cabuyao Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Start of Covid Tracer Route
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'covidtracer', 'middleware' => 'auth'], function () {

    /* for home dashboard */
    // Route::get('/dashboard', 'HomeController@covid_tracer')->name('covid.dashboard');
    Route::get('/dashboard', 'HomeController@vaccine_dashboard')->name('covid.dashboard');

    //establishment category
    Route::post('/estcat/findall','CovidTracer\EstablishmentCategoryController@findall')->name('covidtracer.est_cat.findall');
    Route::get('/estcat/findallforcombobox','CovidTracer\EstablishmentCategoryController@findallforcombobox')->name('covidtracer.est_cat.findallforcombobox');
    Route::post('/estcat/status/{id}', 'CovidTracer\EstablishmentCategoryController@togglestatus')->middleware(['permission:deleteEstcat,restoreEstcat']);
    Route::resource('/estcat', 'CovidTracer\EstablishmentCategoryController');
    Route::resource('/estcat', 'CovidTracer\EstablishmentCategoryController',['only'=>['create','store']])->middleware(['permission:createEstcat']);
    Route::resource('/estcat', 'CovidTracer\EstablishmentCategoryController',['only'=>['show','update']])->middleware(['permission:updateEstcat']);
    Route::resource('/estcat', 'CovidTracer\EstablishmentCategoryController',['only'=>['index']])->middleware(['permission:viewEstcat,updateEstcat,deleteEstcat,restoreEstcat']);

    //establishment information
    Route::get('/estinfo/findallforcombobox','CovidTracer\EstablishmentInformationController@findallforcombobox')->name('covidtracer.est_info.findallforcombobox');
    Route::post('/estinfo/findall','CovidTracer\EstablishmentInformationController@findall')->name('covidtracer.est_info.findall');
    Route::post('/estinfo/status/{id}', 'CovidTracer\EstablishmentInformationController@togglestatus')->middleware(['permission:deleteEstinfo,restoreEstinfo']);
    Route::get('/estinfo/qrcode/{id}', 'CovidTracer\EstablishmentInformationController@establishmentQrCode')->name('covidtracer.est-info.get-qr-code');
    Route::get('/estinfo/print-qrcode/{id}', 'CovidTracer\EstablishmentInformationController@printQrCode')->name('covidtracer.est-info.print-qr-code');
    Route::post('/estinfo/find-owner','CovidTracer\EstablishmentInformationController@findOwner')->name('covidtracer.est-info.find-owner');
    Route::get('/estinfo/get-owner/{id}','CovidTracer\EstablishmentInformationController@getOwner')->name('covidtracer.est-info.get-owner');
    Route::resource('/estinfo', 'CovidTracer\EstablishmentInformationController');
    Route::resource('/estinfo', 'CovidTracer\EstablishmentInformationController',['only'=>['create','store']])->middleware(['permission:createEstinfo']);
    Route::resource('/estinfo', 'CovidTracer\EstablishmentInformationController',['only'=>['show','update']])->middleware(['permission:updateEstinfo']);
    Route::resource('/estinfo', 'CovidTracer\EstablishmentInformationController',['only'=>['index']])->middleware(['permission:viewEstinfo,updateEstinfo,deleteEstinfo,restoreEstinfo']);

    //establishment staff
    Route::post('/est-staff/find-all','CovidTracer\EstablishmentStaffController@findAll')->name('est-staff.find-all');
    Route::post('/est-staff/find-all-staff','CovidTracer\EstablishmentStaffController@findAllStaff')->name('est-staff.find-all-staff');
    Route::post('/est-staff/remove-staff/{id}', 'CovidTracer\EstablishmentStaffController@removeStaff')->name('est-staff.remove-staff');
    Route::resource('/est-staff', 'CovidTracer\EstablishmentStaffController');

    /* emergency hotline */
    Route::post('/emergency-hotline/toggle/{id}', 'CovidTracer\EmergencyHotlineController@togglestatus')->name('covidtracer.hotline.toggle')->middleware(['permission:deleteHotline,updateHotline']);
    Route::post('/emergency-hotline/findall','CovidTracer\EmergencyHotlineController@findall')->name('covidtracer.hotline.findall');
    Route::resource('/emergency-hotline', 'CovidTracer\EmergencyHotlineController');
    Route::resource('/emergency-hotline', 'CovidTracer\EmergencyHotlineController',['only'=>['create','store']])->middleware(['permission:createHotline']);
    Route::resource('/emergency-hotline', 'CovidTracer\EmergencyHotlineController',['only'=>['show','update']])->middleware(['permission:updateHotline']);
    Route::resource('/emergency-hotline', 'CovidTracer\EmergencyHotlineController',['only'=>['index']])->middleware(['permission:viewHotline,updateHotline,deleteHotline,restoreHotline']);


    //covid tracer
    // Route::get('/tracer/detailed','CovidTracer\CovidTracerController@view')->name('covidtracer.tracer_detailed');
    // Route::post('/tracer/findall2','CovidTracer\CovidTracerController@tracer_detailed')->name('covidtracer.tracer_detailed.findall');
    Route::post('/tracer/findall','CovidTracer\CovidTracerController@findall')->name('covidtracer.tracer.findall');
    Route::post('/tracer/generateresults','CovidTracer\CovidTracerController@generateresults')->name('covidtracer.tracer.generate_breakdown');
    Route::post('/tracer/search-positive','CovidTracer\CovidTracerController@searchPositive')->name('covidtracer.tracer.search-positive');
    Route::post('/tracer/involvedresult/{id}','CovidTracer\CovidTracerController@involvedresult')->name('covidtracer.involvedresult');
    Route::get('/tracer/involvedresult','CovidTracer\CovidTracerController@getinvolvedresult')->name('covidtracer.getinvolvedresult');
    Route::resource('/tracer', 'CovidTracer\CovidTracerController');
    Route::resource('/tracer', 'CovidTracer\CovidTracerController',['only'=>['index']])->middleware(['permission:createCovidTracer']);

    //investigator
    Route::get('/investigator/find-all-investigator-combobox', 'CovidTracer\InvestigatorController@findAllInvestigatorForCombobox')->name('covidtracer.investigator.all-investigator'); /* findAllInvestigatorForCombobox */
    Route::post('/investigator/find-all-investigator','CovidTracer\InvestigatorController@findAllInvestigator')->name('covidtracer.investigator.find-all-investigator');
    Route::post('/investigator/findall','CovidTracer\InvestigatorController@findall')->name('covidtracer.investigator.findall');
    Route::post('/investigator/find-all-users','CovidTracer\InvestigatorController@findAllUsers')->name('covidtracer.investigator.findalluser');
    Route::post('/investigator/status/{id}', 'CovidTracer\InvestigatorController@togglestatus');
    Route::post('/investigator/add-investigator/{id}', 'CovidTracer\InvestigatorController@addInvestigator');
    Route::resource('/investigator', 'CovidTracer\InvestigatorController',['only'=>['create','store']])->middleware(['permission:createInvestigator']);
    Route::resource('/investigator', 'CovidTracer\InvestigatorController',['only'=>['index']])->middleware(['permission:createInvestigator']);

    /* investigator monitoring */
    Route::post('/investigator-monitoring/get-history','CovidTracer\InvestigatorMonitoringController@getMonitoringHistory')->name('covidtracer.investigator-monitoring.history');
    Route::resource('/investigator-monitoring', 'CovidTracer\InvestigatorMonitoringController');
    Route::resource('/investigator-monitoring', 'CovidTracer\InvestigatorMonitoringController',['only'=>['index']])->middleware(['permission:createInvestigatorMonitoring']);

     //patient encoding
    Route::get('/encoding/find-all-investigator', 'CovidTracer\EncodingController@findInvestigator')->name('encoding.find-all-investigator');
    Route::get('/encoding/find-all-barangay', 'CovidTracer\EncodingController@findBarangay')->name('encoding.find-all-barangay');
    Route::post('/encoding/find-all-patient', 'CovidTracer\EncodingController@findAllPatient')->name('encoding.find-all-patient');
    Route::get('/encoding/find-user-by-id/{id}','CovidTracer\EncodingController@findUserById')->name('encoding.find-user-by-id');
    Route::resource('/encoding', 'CovidTracer\EncodingController',['only'=>['create','store']]);
    Route::resource('/encoding', 'CovidTracer\EncodingController',['only'=>['show','update']]);
    Route::resource('/encoding', 'CovidTracer\EncodingController',['only'=>['index']])->middleware(['permission:createEncoding']);


    /* patient monitoring */
    Route::post('/patient-monitoring/get-history','CovidTracer\PatientMonitoringController@getMonitoringHistory')->name('covidtracer.patient-monitoring.history');
    Route::get('/patient-monitoring/reports','CovidTracer\PatientMonitoringController@reports')->name('covidtracer.patient-monitoring.reports')->middleware(['permission:viewPatientReports']);
    Route::post('/patient-monitoring/find-all-reports','CovidTracer\PatientMonitoringController@findAllReports')->name('covidtracer.patient-monitoring.find-all-reports');
    Route::resource('/patient-monitoring', 'CovidTracer\PatientMonitoringController',['only'=>['create','store']])->middleware(['permission:createPatientMonitoring']);
    Route::resource('/patient-monitoring', 'CovidTracer\PatientMonitoringController',['only'=>['index']])->middleware(['permission:createPatientMonitoring']);

    //covid summary
    Route::post('/summary/find-all-summaries','CovidTracer\SummaryController@findAllSummaries')->name('covidtracer.summary.find-all-summaries');
    Route::post('/summary/find-all-involved','CovidTracer\SummaryController@findAllInvolved')->name('covidtracer.summary.find-all-involved');
    Route::post('/summary/find-tracer-history','CovidTracer\SummaryController@findTracerHistory')->name('covidtracer.summary.find-tracer-history');
    Route::resource('/summary','CovidTracer\SummaryController')->middleware(['permission:viewCovidSummary']);

    /* patient profile */
    Route::get('/patient-profile/count-all','CovidTracer\PatientProfileController@countAll')->name('covidtracer.patient-profile.counter');
    Route::post('/patient-profile/findall','CovidTracer\PatientProfileController@findall')->name('covidtracer.patient-profile.findall');
    Route::get('/patient-profile/{id}','CovidTracer\PatientProfileController@show')->name('covidtracer.patient-profile.show');

    /* cases updates summary */
    Route::get('/cases-updates/all-cases','CovidTracer\CasesUpdatesSummaryController@getAllCovidCases')->name('covidtracer.allCases');
    Route::post('/cases-updates/find-all','CovidTracer\CasesUpdatesSummaryController@findAll')->name('covidtracer.cases-updates.find-all');
    Route::resource('/cases-updates', 'CovidTracer\CasesUpdatesSummaryController');
    Route::resource('/cases-updates', 'CovidTracer\CasesUpdatesSummaryController',['only'=>['create','store']])->middleware(['permission:createCovidCasesUpdates']);
    Route::resource('/cases-updates', 'CovidTracer\CasesUpdatesSummaryController',['only'=>['index']])->middleware(['permission:viewCovidCasesUpdates,createCovidCasesUpdates']);

    /* patient history exposure */
    Route::post('/patient-monitoring/get-exposure-history','CovidTracer\ExposureHistoryController@getMonitoringExposureHistory')->name('covidtracer.patient-monitoring.exposure-history');
    Route::get('/patient-monitoring/toggle-tracked-status/{id}', 'CovidTracer\ExposureHistoryController@toggleTrackedStatus');
    Route::post('exposure-history', 'CovidTracer\ExposureHistoryController@store')->name('covidtracer.exposure-history.store');

    /* save then generate code */
    Route::post('/print-logs', 'CovidTracer\PrintDocumentController@store')->name('covidtracer.print-docs.store');

     /* tv dashboard */
    Route::get('/tv-dashboard', 'HomeController@tv_dashboard')->name('covidtracer.tv-dashboard'); //->middleware(['permission:viewCovidDashboard'])
    Route::get('/tv-dashboard-stats', 'HomeController@tv_dashboard_stats')->name('covidtracer.tv-dashboard-stats'); //->middleware(['permission:viewCovidDashboard'])
    Route::get('/tv-dashboard-philippines', 'HomeController@tv_dashboard_phil')->name('covidtracer.tv-dashboard-philippines'); //->middleware(['permission:viewCovidDashboard'])

    /* sms notification */
    Route::post('/sms-notification/find-all','CovidTracer\SmsNotificationController@findAll')->name('covidtracer.sms-notification.find-all');
    Route::get('/sms-notification/find-all-for-combobox', 'CovidTracer\SmsNotificationController@findallforcombobox')->name('covidtracer.sms-notification.find-all-for-combobox');
    Route::post('/sms-notification/find-history','CovidTracer\SmsNotificationController@findHistory')->name('covidtracer.sms-notification.find-history');
    Route::get('/sms-notification/get-message/{id}', 'CovidTracer\SmsNotificationController@getMessage')->name('covidtracer.sms-notification.get-message');
    Route::post('/sms-notification/toggle-status/{id}', 'CovidTracer\SmsNotificationController@toggleStatus');
    Route::get('/sms-notification/send-sms', 'CovidTracer\SmsNotificationController@sendSms')->name('covidtracer.sms-notification.send-sms');
    Route::get('/sms-notification/history', 'CovidTracer\SmsNotificationController@history')->name('covidtracer.sms-notification.history');
    Route::resource('/sms-notification','CovidTracer\SmsNotificationController', ['only'=>['create','store']])->middleware(['permission:createSmsNotification']);
    Route::resource('/sms-notification','CovidTracer\SmsNotificationController', ['only'=>['show','update']])->middleware(['permission:updateSmsNotification']);
    Route::resource('/sms-notification','CovidTracer\SmsNotificationController', ['only'=>['index']])->middleware(['permission:viewSmsNotification, createSmsNotification, updateSmsNotification']);

});

/*
|--------------------------------------------------------------------------
| End of Covid Tracer Route
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Start of ISKOCAB Route
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'iskocab', 'middleware' => 'auth'], function () {

    /* for home dashboard */
    Route::get('/dashboard', 'HomeController@iskocab')->name('iskocab.dashboard');

    /* Course Routes */
    Route::get('/course/find-all-course', 'IskoCab\CourseController@findAllCourse')->name('course.find-all-course');
    Route::post('/course/find/all', 'IskoCab\CourseController@findAll')->name('course.findall');
    Route::post('/course/status/{id}', 'IskoCab\CourseController@updateStatus')->name('course.updateStatus')->middleware(['auth','permission:deleteCourse']);
    Route::resource('/course', 'IskoCab\CourseController')->middleware('auth');
    Route::resource('/course', 'IskoCab\CourseController',['only'=>['create', 'store']])->middleware(['auth','permission:createCourse']);
    Route::resource('/course', 'IskoCab\CourseController',['only'=>['edit', 'update']])->middleware(['auth','permission:updateCourse']);
    Route::resource('/course', 'IskoCab\CourseController',['only'=>['index']])->middleware(['auth','permission:updateCourse,deleteCourse,createCourse,viewCourse']);

    /* Scholar */
    Route::post('/scholar/verify-store', 'IskoCab\ScholarController@verifyStore')->name('scholar.verify-store');
    Route::get('/scholar/unverified/{id}', 'IskoCab\ScholarController@showPreScholar')->name('scholar.show-unverified');
    Route::get('/scholar/verification', 'IskoCab\ScholarController@verify_scholar')->name('scholar.verification');

    Route::post('/scholar/findall-unverified', 'IskoCab\ScholarController@getAllUnverifiedScholar')->name('scholar.findunverified');
    Route::post('/scholar/findall-achievements', 'IskoCab\ScholarController@getAllAchievements')->name('scholar.findall-achievements');
    Route::post('/scholar/find-all', 'IskoCab\ScholarController@findAll')->name('scholar.find-all');
    Route::get('/scholar/archive', 'IskoCab\ScholarController@archive')->name('scholar.archive')->middleware(['auth','permission:restoreScholar']);
    Route::post('/scholar/status/{id}', 'IskoCab\ScholarController@toggleStatus')->name('scholar.toggle-status');
    Route::resource('/scholar', 'IskoCab\ScholarController')->middleware('auth');
    Route::resource('/scholar', 'IskoCab\ScholarController', ['only' => ['create', 'store']])->middleware(['auth','permission:createScholar']);
    Route::resource('/scholar', 'IskoCab\ScholarController', ['only' => ['edit', 'update']])->middleware(['auth','permission:updateScholar']);
    Route::resource('/scholar', 'IskoCab\ScholarController', ['only' => ['index']])->middleware(['auth','permission:updateScholar,viewScholar,deleteScholar']);

    /* School*/
    Route::get('/school/getSchoolByScholarID/{id}', 'IskoCab\SchoolController@getSchoolByScholarID')->name('school.getSchoolByScholarID');
    Route::post('/school/find-all', 'IskoCab\SchoolController@findAll')->name('school.find-all');
    Route::get('/school/find-all-school', 'IskoCab\SchoolController@findAllForComboBox')->name('school.find-all-school');
    Route::post('/school/view-history', 'IskoCab\SchoolController@viewHistory')->name('school.view-history');
    Route::post('/school/status/{id}', 'IskoCab\SchoolController@toggleStatus')->name('school.toggle-status')->middleware(['auth','permission:deleteSchool, restoreSchool']);
    Route::resource('/school', 'IskoCab\SchoolController')->middleware('auth');
    Route::resource('/school', 'IskoCab\SchoolController',['only'=>['create', 'store']])->middleware(['auth','permission:createSchool']);
    Route::resource('/school', 'IskoCab\SchoolController',['only'=>['edit', 'update']])->middleware(['auth','permission:updateSchool']);
    Route::resource('/school', 'IskoCab\SchoolController',['only'=>['index']])->middleware(['auth','permission:createSchool, viewSchool, updateSchool, deleteSchool, restoreSchool']);

    /* Scholar type */
    Route::get('/educational-attainment/find-all-type', 'IskoCab\EducationalAttainmentController@findAllEducationalAttainment')->name('educational-attainment.find-type');
    Route::post('/educational-attainment/toggle/{id}', 'IskoCab\EducationalAttainmentController@togglestatus')->name('educational-attainment.toggle')->middleware(['auth','permission:deleteEducationalAttainment']);
    Route::post('/educational-attainment/findall', 'IskoCab\EducationalAttainmentController@findall')->name('educational-attainment.findall');
    Route::resource('/educational-attainment', 'IskoCab\EducationalAttainmentController')->middleware('auth');
    Route::resource('/educational-attainment', 'IskoCab\EducationalAttainmentController',['only'=>['store', 'create']])->middleware(['auth','permission:createEducationalAttainment']);
    Route::resource('/educational-attainment', 'IskoCab\EducationalAttainmentController',['only'=>['edit', 'update']])->middleware(['auth','permission:updateEducationalAttainment']);
    Route::resource('/educational-attainment', 'IskoCab\EducationalAttainmentController',['only'=>['index']])->middleware(['auth','permission:viewEducationalAttainment,updateEducationalAttainment,createEducationalAttainment,deleteEducationalAttainment']);

    /* Scholarship Assessment */
    // Route::get('/sch-assessment/find-all', 'IskoCab\ScholarshipAssessmentController@findAll')->name('sch-assessment.find-all');
    Route::resource('/sch-assessment', 'IskoCab\ScholarshipAssessmentController')->middleware('auth');
    // Route::resource('/sch-assessment', 'IskoCab\ScholarshipAssessmentController', ['only' => ['create', 'store']])->middleware('auth');
    // Route::resource('/sch-assessment', 'IskoCab\ScholarshipAssessmentController', ['only' => ['edit', 'update']])->middleware('auth');
    // Route::resource('/sch-assessment', 'IskoCab\ScholarshipAssessmentController', ['only' => ['index']])->middleware('auth');

    /* application */
    Route::get('/application/compute-grades', 'IskoCab\ApplicationController@computeGrades')->name('application.compute-grades');
    Route::post('/application/find-all', 'IskoCab\ApplicationController@findall')->name('application.findall');
    Route::resource('/application', 'IskoCab\ApplicationController');

    /* evaluation */
    Route::get('/evaluation/grades-history/{id}', 'IskoCab\EvaluationController@getGradesHistory')->name('evaluation.grades-history');
    Route::post('/evaluation/find-all', 'IskoCab\EvaluationController@findall')->name('evaluation.findall');
    Route::resource('/evaluation', 'IskoCab\EvaluationController');

    /* assessment */
    Route::post('/assessment/find-all', 'IskoCab\AssessmentController@findAll')->name('assessment.find-all');
    Route::get('/assessment/print-assessment/{id}', 'IskoCab\AssessmentController@printAssessment')->name('assessment.print-assessment')->middleware(['auth','permission:viewSchAssessment']);
    Route::resource('/assessment', 'IskoCab\AssessmentController')->middleware('auth');
    Route::resource('/assessment', 'IskoCab\AssessmentController',['only'=>['index']])->middleware(['auth','permission:viewSchAssessment']);

    /* get active program */
    Route::get('/scholar-program/find-active', 'IskoCab\ScholarshipProgramController@get_active_program')->name('sch-program.find-active');

    /* evaluation summary */
    Route::get('/scholarship-summaries', 'IskoCab\ScholarshipEvaluationSummaryController@index')->name('scholarship-summaries.index');
    Route::post('/scholarship-summaries/find-all', 'IskoCab\ScholarshipEvaluationSummaryController@findAll')->name('scholarship-summaries.find-all');

    /* Scholar Program */
    Route::post('/scholarship-program/find-all', 'IskoCab\ScholarshipProgramModuleController@findAll')->name('scholarship-program.find-all');
    Route::post('/scholarship-program/toggle-application-status/{id}', 'IskoCab\ScholarshipProgramModuleController@toggleApplicationStatus');
    Route::post('/scholarship-program/toggle-program-status/{id}', 'IskoCab\ScholarshipProgramModuleController@toggleProgramStatus');
    Route::post('/scholarship-program/toggle-assistance-status/{id}', 'IskoCab\ScholarshipProgramModuleController@toggleAssistanceStatus')->middleware(['auth','permission:deleteScholarProgram, restoreScholarProgram']);
    Route::get('/scholarship-program/print/{id}', 'IskoCab\ScholarshipProgramModuleController@printProgram')->name('scholarship-program.print')->middleware(['auth','permission:printScholarProgram']);
    Route::resource('/scholarship-program', 'IskoCab\ScholarshipProgramModuleController')->middleware('auth');
    Route::resource('/scholarship-program', 'IskoCab\ScholarshipProgramModuleController',['only'=>['index']])->middleware(['auth','permission:viewScholarProgram,updateScholarProgram,createScholarProgram,deleteScholarProgram, restoreScholarProgram']);

});

/*
|--------------------------------------------------------------------------
| End of ISKOCABS
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Start of Comprehensive Route
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'comprehensive', 'middleware' => 'auth'], function () {
    
    //event management
    Route::post('/event/toggle/{id}', 'Comprehensive\EventController@togglestatus')->name('event.toggle')->middleware(['permission:deleteEvent']);
    Route::post('/event/closeevent/{id}', 'Comprehensive\EventController@closeevent')->name('event.closeevent')->middleware(['permission:viewSelectEvent']);
    Route::put('/event/toggleinout/{id}', 'Comprehensive\EventController@toggleinout')->name('event.toggleinout')->middleware(['permission:viewSelectEvent']);
    Route::post('/event/findall', 'Comprehensive\EventController@findall')->name('event.findall');
    Route::get('/event/checkeventstatus', 'Comprehensive\EventController@checkeventstatus')->name('event.checkeventstatus');
    Route::resource('/event', 'Comprehensive\EventController');
    Route::resource('/event', 'Comprehensive\EventController', ['only' => ['create', 'store']])->middleware(['permission:createEvent']);
    Route::resource('/event', 'Comprehensive\EventController', ['only' => ['show','edit', 'update']])->middleware(['permission:updateEvent,viewSelectEvent']);
    Route::resource('/event', 'Comprehensive\EventController', ['only' => ['index']])->middleware(['permission:viewEvent,deleteEvent,updateEvent,createEvent, restoreEvent']);

    //requirements
    Route::post('/requirement/find-all', 'Comprehensive\RequirementController@findAll')->name('requirement.find-all');
    Route::post('/requirement/toggle/{id}', 'Comprehensive\RequirementController@toggleStatus')->name('requirement.toggle');
    Route::resource('/requirement', 'Comprehensive\RequirementController');
    Route::resource('/requirement', 'Comprehensive\RequirementController', ['only' => ['create', 'store']])->middleware(['permission:createRequirement']);
    Route::resource('/requirement', 'Comprehensive\RequirementController', ['only' => ['show','edit', 'update']])->middleware(['permission:updateRequirement,viewSelectRequirement']);
    Route::resource('/requirement', 'Comprehensive\RequirementController', ['only' => ['index']])->middleware(['permission:viewRequirement,deleteRequirement,updateRequirement,createRequirement, restoreRequirement']);

    //program
    Route::post('/program/find-all', 'Comprehensive\ProgramServiceController@findAll')->name('program.find-all');
    Route::post('/program/toggle/{id}', 'Comprehensive\ProgramServiceController@toggleStatus')->name('program.toggle');
    Route::resource('/program', 'Comprehensive\ProgramServiceController');
    Route::resource('/program', 'Comprehensive\ProgramServiceController', ['only' => ['create', 'store']])->middleware(['permission:createProgram']);
    Route::resource('/program', 'Comprehensive\ProgramServiceController', ['only' => ['show','edit', 'update']])->middleware(['permission:updateProgram,viewSelectProgram']);
    Route::resource('/program', 'Comprehensive\ProgramServiceController', ['only' => ['index']])->middleware(['permission:viewProgram,deleteProgram,updateProgram,createProgram, restoreProgram']);


    /* Subjects */
    Route::get('/exam-subject/find-all-subject', 'Comprehensive\ExamSubjectController@findAllForComboBox')->name('exam-subject.find-subject');
    Route::post('/exam-subject/find-all', 'Comprehensive\ExamSubjectController@findAll')->name('exam-subject.find-all');
    Route::post('/exam-subject/toggle/{id}', 'Comprehensive\ExamSubjectController@toggleStatus')->name('exam-subject.toggle')->middleware(['auth','permission:deleteSubject, restoreSubject']);
    Route::resource('/exam-subject', 'Comprehensive\ExamSubjectController')->middleware('auth');
    Route::resource('/exam-subject', 'Comprehensive\ExamSubjectController', ['only' => ['create', 'store']])->middleware(['auth','permission:createSubject']);
    Route::resource('/exam-subject', 'Comprehensive\ExamSubjectController', ['only' => ['edit', 'update']])->middleware(['auth','permission:updateSubject']);
    Route::resource('/exam-subject', 'Comprehensive\ExamSubjectController', ['only' => ['index']])->middleware(['auth','permission:updateSubject,createSubject,viewSubject,deleteSubject, restoreSubject']);

    /* Questions */
    Route::post('/exam-question/find-all', 'Comprehensive\QuestionController@findAll')->name('exam-question.find-all');
    Route::post('/exam-question/toggle/{id}', 'Comprehensive\QuestionController@toggleStatus')->name('exam-question.toggle');
    Route::get('/question/showquestion/{id}', 'Comprehensive\QuestionController@showWithoutAnswer')->name('examination.showwithoutanswer')->middleware('auth');
    Route::resource('/exam-question', 'Comprehensive\QuestionController')->middleware('auth');
    Route::resource('/exam-question', 'Comprehensive\QuestionController', ['only' => ['create', 'store']])->middleware(['auth', 'permission:createQuestion']);
    Route::resource('/exam-question', 'Comprehensive\QuestionController', ['only' => ['edit', 'update']])->middleware(['auth', 'permission:updateQuestion']);
    Route::resource('/exam-question', 'Comprehensive\QuestionController', ['only' => ['index']])->middleware(['auth','permission:viewGrants,updateQuestion,createQuestion,deleteQuestion, restoreQuestion']);

    /* Examination */
    // Route::get('/examination/findall2', 'Comprehensive\ExamTitleController@findall2')->name('examination.findall2')->middleware('auth','permission:viewProgram');
    Route::post('/examination/find-all', 'Comprehensive\ExamTitleController@findall')->name('examination.findall');
    Route::post('/examination/toggle/{id}', 'Comprehensive\ExamTitleController@togglestatus')->name('examination.toggle')->middleware(['auth','permission:deleteExamination']);
    Route::post('/examination/findquestion', 'Comprehensive\ExaminationController@findquestion')->name('examination.findquestion')->middleware('auth');
    Route::get('/examination/findquestion/{id}', 'Comprehensive\ExaminationController@findquestion2')->name('examination.findquestion2')->middleware('auth');
    Route::post('/examination/togglequestion/{id}', 'Comprehensive\ExaminationController@togglestatus')->name('examination.togglequestion')->middleware('auth');
    Route::resource('/examination', 'Comprehensive\ExamTitleController')->middleware('auth');
    Route::resource('/examination', 'Comprehensive\ExamTitleController',['only'=>['create','store']])->middleware(['auth','permission:createExamination']);
    Route::resource('/examination', 'Comprehensive\ExamTitleController',['only'=>['edit','update']])->middleware(['auth','permission:updateExamination']);
    Route::resource('/examination', 'Comprehensive\ExamTitleController',['only'=>['index']])->middleware(['auth','permission:updateExamination,deleteExamination,viewExamination,createExamination']);

    /* Program management */
    Route::resource('/create-program', 'Comprehensive\ProgramServiceController')->middleware('auth');
    Route::resource('/create-program', 'Comprehensive\ProgramServiceController', ['only' => ['create', 'store']])->middleware('auth');
    Route::resource('/create-program', 'Comprehensive\ProgramServiceController', ['only' => ['edit', 'update']])->middleware('auth');
    Route::resource('/create-program', 'Comprehensive\ProgramServiceController', ['only' => ['index']])->middleware('auth');

    /*Qr Code*/
    Route::post('/qr-code/find-all', 'Comprehensive\PrintQrCodeController@findAll')->name('qr-code.find-all');
    Route::get('/qr-code/print', 'Comprehensive\PrintQrCodeController@printQrCode')->name('qr-code.print');
    Route::resource('/qr-code', 'Comprehensive\PrintQrCodeController')->middleware('auth');
    Route::resource('/qr-code', 'Comprehensive\PrintQrCodeController', ['only' => ['index']])->middleware(['auth','permission:viewQrCodePrinting']);
});

/*
|--------------------------------------------------------------------------
| End of Comprehensive
|--------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------
| Start of Emergency Route
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'emergency', 'middleware' => 'auth'], function () {
    //Incident Categry Route
    Route::post('/incident-category/status/{id}', 'EmergencyResponse\IncidentCategoryController@updateStatus')->name('incident-category.updateStatus')->middleware(['auth']);
    Route::post('/incident-category/find-all', 'EmergencyResponse\IncidentCategoryController@findAll')->name('incident-category.findall');
    Route::resource('/incident-category', 'EmergencyResponse\IncidentCategoryController');
    Route::resource('/incident-category', 'EmergencyResponse\IncidentCategoryController', ['only' => ['create', 'store']])->middleware(['auth']);
    Route::resource('/incident-category', 'EmergencyResponse\IncidentCategoryController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
    Route::resource('/incident-category', 'EmergencyResponse\IncidentCategoryController', ['only' => ['index']])->middleware(['auth']);

    //Emergency Response Request
    // Route::post('/incident-category/status/{id}', 'EmergencyResponse\IncidentCategoryController@updateStatus')->name('incident-category.updateStatus')->middleware(['auth']);
    Route::get('/response-request/find-all-incident-request', 'EmergencyResponse\EmergencyResponseRequestController@findAllIncidentRequest')->name('find-all-incident-request');
    Route::get('/response-request/map', 'EmergencyResponse\EmergencyResponseRequestController@map')->name('response-request.map');
    Route::post('/response-request/find-all', 'EmergencyResponse\EmergencyResponseRequestController@findAll')->name('response-request.findall');
    //locator on map datatable
    Route::post('/response-request/locator-data', 'EmergencyResponse\EmergencyResponseRequestController@locatorData')->name('response-request.locator-data');

    Route::resource('/response-request', 'EmergencyResponse\EmergencyResponseRequestController');
    Route::resource('/response-request', 'EmergencyResponse\EmergencyResponseRequestController', ['only' => ['create', 'store']])->middleware(['auth']);
    Route::resource('/response-request', 'EmergencyResponse\EmergencyResponseRequestController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
    Route::resource('/response-request', 'EmergencyResponse\EmergencyResponseRequestController', ['only' => ['index']])->middleware(['auth']);
    //Incident Monitoring
    //Route::post('/response-request/find-all', 'EmergencyResponse\EmergencyResponseRequestController@findAll')->name('response-request.findall');
    Route::resource('/incident-report', 'EmergencyResponse\IncidentReportController');
    Route::resource('/incident-report', 'EmergencyResponse\IncidentReportController', ['only' => ['create', 'store']])->middleware(['auth']);
    Route::resource('/incident-report', 'EmergencyResponse\IncidentReportController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
    Route::resource('/incident-report', 'EmergencyResponse\IncidentReportController', ['only' => ['index']])->middleware(['auth']);

});


Route::group(['prefix' => 'covid19vaccine'], function () {

    /* barangay */
    Route::get('/barangay/all-for-combobox', 'Covid19Vaccine\CoVaxBarangayController@findAllBarangay')->name('covid19vaccine.all-barangay-for-combobox');

     /* id category */
    Route::get('/id-categoty-combobox', 'Covid19Vaccine\IdCategoryController@findAllIdCategory')->name('covid19vaccine.all-idcategory-for-combobox');

    /* category */
    Route::get('/category/all-for-combobox', 'Covid19Vaccine\CategoryController@findAllForCombobox')->name('covid19vaccine.all-category-for-combobox');

    /* profession */
    Route::get('/profession/all-for-combobox', 'Covid19Vaccine\CategoryController@findAllProfessionForCombobox')->name('covid19vaccine.all-profession-for-combobox');

    /* employer type */
    Route::get('/employer-type/all-for-combobox', 'Covid19Vaccine\EmploymentStatusController@findAllForCombobox')->name('covid19vaccine.all-employertype-for-combobox');


    /* file upload */
    Route::get('/file-upload', 'Covid19Vaccine\FileUploadController@index')->name('file-upload.index')->middleware(['auth','permission:viewCovid19FileUpload']);
    Route::post('/file-upload', 'Covid19Vaccine\FileUploadController@store')->name('file-upload.store')->middleware(['auth','permission:viewCovid19FileUpload']);

    /* home */
    Route::get('/', function () { return view('covid19_vaccine.home.index'); });

    /* registration */
    Route::post('/find-register-user', 'Covid19Vaccine\Covid19VaccineRegistrationController@findRegisterUser')->name('covid19vaccine.findRegisterUser');
    Route::post('/pre-registered/create-patient', 'Covid19Vaccine\Covid19VaccineRegistrationController@createPatientProfile')->name('covid19vaccine.createPatientProfile')->middleware(['auth']);
    Route::get('/registration/format-date-page', 'Covid19Vaccine\Covid19VaccineRegistrationController@formatDatePage')->middleware(['auth','permission:changeDateFormat']);
    Route::get('/registration/get-info/{id}', 'Covid19Vaccine\Covid19VaccineRegistrationController@getInformation')->middleware(['auth','permission:changeDateFormat']);
    Route::post('/registration/update-date-of-birth/{id}', 'Covid19Vaccine\Covid19VaccineRegistrationController@updateDateOfBirth')->middleware(['auth','permission:changeDateFormat']);
    Route::post('/registration/find-all-birthday', 'Covid19Vaccine\Covid19VaccineRegistrationController@findAll')->name('registration.find-all');
    Route::resource('/registration', 'Covid19Vaccine\Covid19VaccineRegistrationController');
    Route::resource('/registration', 'Covid19Vaccine\Covid19VaccineRegistrationController', ['only' => ['create', 'store']]);
    Route::resource('/registration', 'Covid19Vaccine\Covid19VaccineRegistrationController', ['only' => ['show','edit', 'update']])->middleware(['auth','permission:updateRegistrationAndValidation']);
 /* registered counter */
    Route::get('/pre-registered', 'Covid19Vaccine\Covid19VaccineRegistrationController@preRegistered')->name('covid19vaccine.pre-registered');


   //Incident Categry Route
    //Route::post('/incident-category/status/{id}', 'EmergencyResponse\IncidentCategoryController@updateStatus')->name('incident-category.updateStatus')->middleware(['auth']);
    //Route::post('/incident-category/find-all', 'EmergencyResponse\IncidentCategoryController@findAll')->name('incident-category.findall');

    Route::get('/vaccination/second-dose-verification', 'Covid19Vaccine\VaccinationController@secondDoseVerification');
    Route::get('/vaccination/print/{id}', 'Covid19Vaccine\VaccinationController@printAssessment')->name('assessment.print')->middleware(['auth','permission:viewAssessment']);
    Route::get('/vaccination/assessment-details/{id}', 'Covid19Vaccine\VaccinationController@showAssessmentDetails')->name('assessment.assessment-details')->middleware(['auth','permission:viewAssessment']);
    Route::post('/vaccination/assessment-status/{id}', 'Covid19Vaccine\VaccinationController@changeAssessmentStatus')->name('assessment.assessment-status')->middleware(['auth','permission:viewAssessment']);
    Route::resource('/vaccination', 'Covid19Vaccine\VaccinationController');
    Route::resource('/vaccination', 'Covid19Vaccine\VaccinationController', ['only' => ['create', 'store']])->middleware(['auth']);
    Route::resource('/vaccination', 'Covid19Vaccine\VaccinationController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
    Route::resource('/vaccination', 'Covid19Vaccine\VaccinationController', ['only' => ['index']])->middleware(['auth']);

    /* counseling */
    Route::get('/counseling-and-final-consent', 'Covid19Vaccine\VaccinationController@counseling');
    Route::post('/counseling-and-final-consent/find-all', 'Covid19Vaccine\VaccinationController@counselingFindAll')->name('counseling-and-final-consent.counselingFindAll');

     /* registration */
    //  Route::get('/get-registered/{id}', 'Covid19Vaccine\VaccinationController@findPatientById');
     Route::get('/registration-and-validation', 'Covid19Vaccine\VaccinationController@registrationValidation')->middleware(['auth','permission:viewRegistrationAndValidation']);
     Route::post('/registration-and-validation/find-all', 'Covid19Vaccine\VaccinationController@registrationAndValidationFindAll')->name('registration-and-validation.findAll')->middleware(['auth','permission:viewRegistrationAndValidation']);

    Route::post('/registration-approval/{id}', 'Covid19Vaccine\VaccinationController@registrationApproval')->name('registration-approval.approved')->middleware(['auth','permission:viewRegistrationAndValidation']);
     Route::post('/registration-restore/{id}', 'Covid19Vaccine\VaccinationController@registrationRestore')->name('registration-restore.restored')->middleware(['auth','permission:restoreRegistrationAndValidation']);
     Route::post('/second-registration-approval/{id}', 'Covid19Vaccine\VaccinationController@secondRegistrationApproval')->name('second-registration-approval.approved');

     //  Route::get('/get-registered/{id}', 'Covid19Vaccine\VaccinationController@findPatientById');

     Route::get('/assessment', 'Covid19Vaccine\VaccinationController@assessment')->middleware(['auth','permission:viewAssessment']);
     Route::post('/assessment/find-all', 'Covid19Vaccine\VaccinationController@assessmentFindAll')->name('assessment.findAll')->middleware(['auth','permission:viewAssessment']);

     Route::get('/survey-list/export', 'Covid19Vaccine\VaccinationController@exportSurveyList')->middleware(['auth']);
     Route::get('/vaccination-monitoring/export', 'Covid19Vaccine\VaccinationMonitoringController@exportVaccineMonitoringList')->middleware(['auth','permission:viewVaccinationExport']);

     Route::get('/vaccinator/find-all-vacinator', 'Covid19Vaccine\VaccinatorController@findAllVaccinator')->name('vaccinator.find-all-vaccinator')->middleware(['auth']);
     Route::post('/vaccinator/find-all', 'Covid19Vaccine\VaccinatorController@findAll')->name('vaccinator.find-all');
     Route::post('/vaccinator/status/{id}', 'Covid19Vaccine\VaccinatorController@togglestatus')->name('vaccinator.togglestatus');
     Route::resource('/vaccinator', 'Covid19Vaccine\VaccinatorController');
     Route::resource('/vaccinator', 'Covid19Vaccine\VaccinatorController', ['only' => ['create', 'store']])->middleware(['auth']);
     Route::resource('/vaccinator', 'Covid19Vaccine\VaccinatorController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
     Route::resource('/vaccinator', 'Covid19Vaccine\VaccinatorController', ['only' => ['index']])->middleware(['auth','permission:viewVaccinator']);
     
     
     Route::post('/health-facility/assign-user', 'Covid19Vaccine\HealthFacilityController@assignUser')->name('health-facility.assignedUser')->middleware(['auth', 'permission:viewAssignStaff']);
     Route::get('/health-facility/find-all-facility', 'Covid19Vaccine\HealthFacilityController@findAllFacility')->name('health-facility.find-all-facility')->middleware(['auth']);
     Route::post('/health-facility/find-all', 'Covid19Vaccine\HealthFacilityController@findAll')->name('health-facility.find-all');
     Route::post('/health-facility/status/{id}', 'Covid19Vaccine\HealthFacilityController@togglestatus')->name('health-facility.togglestatus');
     Route::resource('/health-facility', 'Covid19Vaccine\HealthFacilityController');
     Route::resource('/health-facility', 'Covid19Vaccine\HealthFacilityController', ['only' => ['create', 'store']])->middleware(['auth']);
     Route::resource('/health-facility', 'Covid19Vaccine\HealthFacilityController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
     Route::resource('/health-facility', 'Covid19Vaccine\HealthFacilityController', ['only' => ['index']])->middleware(['auth','permission:viewHealthFacility']);
     Route::post('/find-all-users', 'Covid19Vaccine\HealthFacilityController@findAllUsers')->name('find-all-users')->middleware(['auth']);

     Route::get('/vaccine-category/find-all-vaccine', 'Covid19Vaccine\VaccineCategoryController@findAllVaccine')->name('vaccine-category.find-all-vaccine')->middleware(['auth']);
     Route::post('/vaccine-category/find-all', 'Covid19Vaccine\VaccineCategoryController@findAll')->name('vaccine-category.find-all');
     Route::post('/vaccine-category/status/{id}', 'Covid19Vaccine\VaccineCategoryController@togglestatus')->name('vaccine-category.togglestatus');
     Route::resource('/vaccine-category', 'Covid19Vaccine\VaccineCategoryController');
     Route::resource('/vaccine-category', 'Covid19Vaccine\VaccineCategoryController', ['only' => ['create', 'store']])->middleware(['auth']);
     Route::resource('/vaccine-category', 'Covid19Vaccine\VaccineCategoryController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
     Route::resource('/vaccine-category', 'Covid19Vaccine\VaccineCategoryController', ['only' => ['index']]);

    Route::get('/vaccination-monitoring/vas-info/{id}', 'Covid19Vaccine\VaccinationMonitoringController@vasLineInfo')->name('vaccination-monitoring.view-all-monitoring')->middleware(['auth','permission:viewVASLineInfo']);
     
     Route::get('/vaccination-monitoring/view-monitoring', 'Covid19Vaccine\VaccinationMonitoringController@viewAllMonitoring')->name('vaccination-monitoring.view-all-monitoring')->middleware(['auth','permission:viewVaccinationPatientMonitoring']);
     Route::get('/vaccination-monitoring/find-specific-summary/{id}', 'Covid19Vaccine\VaccinationMonitoringController@findVaccinationSummary')->name('vaccination-monitoring.find-specific-summary');
     Route::post('/vaccination-monitoring/find-all', 'Covid19Vaccine\VaccinationMonitoringController@monitoringFindAll')->name('vaccination-monitoring.find-all');
     Route::post('/vaccination-monitoring/find-all-summary', 'Covid19Vaccine\VaccinationMonitoringController@monitoringFindAllSummary')->name('vaccination-monitoring.find-all-summary');
     Route::get('/vaccination-monitoring/vaccinated-first-dose', 'Covid19Vaccine\VaccinationMonitoringController@monitorVaccinatedFirstDose')->name('vaccination-monitoring.vaccinated-first-dose')->middleware(['auth','permission:viewVaccinationPatientMonitoring']);
     Route::post('/vaccination-monitoring/find-all-vaccinated-first-dose', 'Covid19Vaccine\VaccinationMonitoringController@findAllVaccinatedFirstDose')->name('vaccination-monitoring.find-all-vaccinated-first-dose');
      Route::post('/vaccination-monitoring/find-summary/{id}', 'Covid19Vaccine\VaccinationMonitoringController@findSummary')->name('vaccination-monitoring.find-summary');
     Route::get('/vaccination-monitoring/summary-other-information/{id}', 'Covid19Vaccine\VaccinationMonitoringController@summaryOtherInformation')->name('vaccination-monitoring.summary-other-information');
     Route::resource('/vaccination-monitoring', 'Covid19Vaccine\VaccinationMonitoringController');
     Route::resource('/vaccination-monitoring', 'Covid19Vaccine\VaccinationMonitoringController', ['only' => ['create', 'store']])->middleware(['auth']);
     Route::resource('/vaccination-monitoring', 'Covid19Vaccine\VaccinationMonitoringController', ['only' => ['show','edit', 'update']])->middleware(['auth']);
     Route::resource('/vaccination-monitoring', 'Covid19Vaccine\VaccinationMonitoringController', ['only' => ['index']])->middleware(['auth','permission:viewVaccinationMonitoring']);

     Route::get('/statistics-dashboard', 'Covid19Vaccine\StatisticsController@index')->middleware(['auth']);
     Route::get('/statistics-list', 'Covid19Vaccine\StatisticsController@getStatistics')->name('statistics.get')->middleware(['auth']);

     Route::get('/public-statistics', 'Covid19Vaccine\StatisticsController@getStatistics')->name('public.statistics'); //public statistics
     Route::get('/statistics-list-perbarangay', 'Covid19Vaccine\StatisticsController@getStatisticsPerBarangay')->name('statistics.getPerBarangay')->middleware(['auth']);
    Route::get('/statistics-reports', 'Covid19Vaccine\StatisticsController@getReports')->name('statistics.getReports')->middleware(['auth']);

    Route::post('/file-export/perspective-facility/', 'Covid19Vaccine\ExportSummaryController@exportByFacility')->name('file-export.exportByFacility')->middleware(['auth','permission:exportVASReport']); 
    Route::get('/file-export/perspective-facility', 'Covid19Vaccine\ExportSummaryController@findPerspectiveFacilities')->name('file-export.perspective-facility')->middleware(['auth','permission:exportVASReport']); 
    Route::get('/file-export/download-file/{id}/{type}/{filename}', 'Covid19Vaccine\ExportSummaryController@downloadFile')->name('file-export.download-file')->middleware(['auth','permission:exportVASReport']); 
    Route::post('/file-export/find-all', 'Covid19Vaccine\ExportSummaryController@findAll')->name('file-export.find-all')->middleware(['auth','permission:exportVASReport']);
    Route::resource('/file-export', 'Covid19Vaccine\ExportSummaryController')->middleware(['auth','permission:exportVASReport']);
    Route::get('/vas-report', 'Covid19Vaccine\ExportSummaryController@vasReportView')->middleware(['auth','permission:exportVASReport']);
    //emat
    Route::post('/vims-ir/find-all', 'Covid19Vaccine\ExportSummaryController@findallVIMSReport')->name('vims-ir.find-all')->middleware(['auth','permission:exportVIMSIRReport']);
    Route::post('/file-export/vims-ir-report/', 'Covid19Vaccine\ExportSummaryController@exportVIMSIRReport')->name('export.vimsirreport')->middleware(['auth','permission:exportVIMSIRReport']); 
    Route::get('/vims-report', 'Covid19Vaccine\ExportSummaryController@vimsReportView')->middleware(['auth','permission:exportVIMSIRReport']);
    
    Route::get('/vas-report-dates/{id}', 'Covid19Vaccine\ExportSummaryController@getVasDate')->name('file-export.get-vas-report-dates')->middleware(['auth','permission:VASReportPerDate']);
    Route::get('/vas-report-per-date', 'Covid19Vaccine\ExportSummaryController@vasReportPerDate')->middleware(['auth','permission:VASReportPerDate']);
    Route::post('/vas-report-per-date', 'Covid19Vaccine\ExportSummaryController@findAllVasReportPerDate')->name('file-export.find-all-vas-per-date')->middleware(['auth','permission:VASReportPerDate']);

});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});



/*
|--------------------------------------------------------------------------
| End of Emergency
|--------------------------------------------------------------------------
*/
