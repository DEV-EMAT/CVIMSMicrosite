<?php

use Illuminate\Database\Seeder;

class PositionAccessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('position_accesses')->insert([
            [
                'position' => 'SUPER-ADMIN-GENERAL',
                'access' => 'a:76:{i:0;s:18:"viewCovidDashboard";i:1;s:13:"createAccount";i:2;s:13:"updateAccount";i:3;s:11:"viewAccount";i:4;s:13:"deleteAccount";i:5;s:14:"restoreAccount";i:6;s:12:"resetAccount";i:7;s:16:"createDepartment";i:8;s:16:"updateDepartment";i:9;s:14:"viewDepartment";i:10;s:16:"deleteDepartment";i:11;s:17:"restoreDepartment";i:12;s:13:"createUpdates";i:13;s:13:"updateUpdates";i:14;s:11:"viewUpdates";i:15;s:13:"deleteUpdates";i:16;s:14:"restoreUpdates";i:17;s:14:"createBarangay";i:18;s:14:"updateBarangay";i:19;s:12:"viewBarangay";i:20;s:14:"deleteBarangay";i:21;s:15:"restoreBarangay";i:22;s:12:"createAccess";i:23;s:12:"updateAccess";i:24;s:10:"viewAccess";i:25;s:12:"deleteAccess";i:26;s:13:"restoreAccess";i:27;s:8:"viewLogs";i:28;s:18:"viewCovidDashboard";i:29;s:13:"createEstinfo";i:30;s:13:"updateEstinfo";i:31;s:11:"viewEstinfo";i:32;s:13:"deleteEstinfo";i:33;s:14:"restoreEstinfo";i:34;s:12:"createEstcat";i:35;s:12:"updateEstcat";i:36;s:10:"viewEstcat";i:37;s:12:"deleteEstcat";i:38;s:13:"restoreEstcat";i:39;s:13:"createHotline";i:40;s:13:"updateHotline";i:41;s:11:"viewHotline";i:42;s:13:"deleteHotline";i:43;s:14:"restoreHotline";i:44;s:23:"createPatientMonitoring";i:45;s:18:"createInvestigator";i:46;s:28:"createInvestigatorMonitoring";i:47;s:17:"createCovidTracer";i:48;s:16:"viewCovidSummary";i:49;s:17:"viewPrintUserCode";i:50;s:23:"createCovidCasesUpdates";i:51;s:21:"viewCovidCasesUpdates";i:52;s:14:"createEncoding";i:53;s:21:"createSmsNotification";i:54;s:21:"updateSmsNotification";i:55;s:19:"viewSmsNotification";i:56;s:21:"deleteSmsNotification";i:57;s:22:"restoreSmsNotification";i:58;s:18:"createGuestAccount";i:59;s:16:"viewGuestAccount";i:60;s:18:"viewPrintGuestCode";i:61;s:12:"createCourse";i:62;s:12:"updateCourse";i:63;s:10:"viewCourse";i:64;s:12:"deleteCourse";i:65;s:13:"restoreCourse";i:66;s:14:"createCategory";i:67;s:14:"updateCategory";i:68;s:12:"viewCategory";i:69;s:14:"deleteCategory";i:70;s:15:"restoreCategory";i:71;s:12:"createSchool";i:72;s:12:"updateSchool";i:73;s:10:"viewSchool";i:74;s:12:"deleteSchool";i:75;s:13:"restoreSchool";}',
                'status' => 1,
            ],
            [
                'position' => 'GUEST',
                'access' => 'N;',
                'status' => 1,
            ],
            [
                'position' => 'CYDA-SUPER-ADMIN',
                'access' => 'a:46:{i:0;s:12:"createCourse";i:1;s:12:"updateCourse";i:2;s:10:"viewCourse";i:3;s:12:"deleteCourse";i:4;s:13:"restoreCourse";i:5;s:12:"createSchool";i:6;s:12:"updateSchool";i:7;s:10:"viewSchool";i:8;s:12:"deleteSchool";i:9;s:13:"restoreSchool";i:10;s:27:"createEducationalAttainment";i:11;s:27:"updateEducationalAttainment";i:12;s:25:"viewEducationalAttainment";i:13;s:27:"deleteEducationalAttainment";i:14;s:28:"restoreEducationalAttainment";i:15;s:13:"createScholar";i:16;s:13:"updateScholar";i:17;s:11:"viewScholar";i:18;s:13:"deleteScholar";i:19;s:14:"restoreScholar";i:20;s:13:"verifyScholar";i:21;s:13:"createSubject";i:22;s:13:"updateSubject";i:23;s:11:"viewSubject";i:24;s:13:"deleteSubject";i:25;s:14:"restoreSubject";i:26;s:14:"createQuestion";i:27;s:14:"updateQuestion";i:28;s:12:"viewQuestion";i:29;s:14:"deleteQuestion";i:30;s:15:"restoreQuestion";i:31;s:17:"createExamination";i:32;s:17:"updateExamination";i:33;s:15:"viewExamination";i:34;s:17:"deleteExamination";i:35;s:18:"restoreExamination";i:36;s:20:"createScholarProgram";i:37;s:20:"updateScholarProgram";i:38;s:18:"viewScholarProgram";i:39;s:20:"deleteScholarProgram";i:40;s:21:"restoreScholarProgram";i:41;s:19:"printScholarProgram";i:42;s:18:"viewSchApplication";i:43;s:17:"viewSchEvaluation";i:44;s:17:"viewSchAssessment";i:45;s:22:"viewScholarshipSummary";}',
                'status' => 1,
            ],
            [
                'position' => 'COVIDTRACER-SUPER-ADMIN',
                'access' => 'a:40:{i:0;s:18:"viewCovidDashboard";i:1;s:13:"createEstinfo";i:2;s:13:"updateEstinfo";i:3;s:11:"viewEstinfo";i:4;s:13:"deleteEstinfo";i:5;s:14:"restoreEstinfo";i:6;s:14:"createEstStaff";i:7;s:12:"viewEstStaff";i:8;s:14:"deleteEstStaff";i:9;s:18:"viewPrintEstQrCode";i:10;s:12:"createEstcat";i:11;s:12:"updateEstcat";i:12;s:10:"viewEstcat";i:13;s:12:"deleteEstcat";i:14;s:13:"restoreEstcat";i:15;s:13:"createHotline";i:16;s:13:"updateHotline";i:17;s:11:"viewHotline";i:18;s:13:"deleteHotline";i:19;s:14:"restoreHotline";i:20;s:23:"createPatientMonitoring";i:21;s:18:"viewPatientReports";i:22;s:18:"createInvestigator";i:23;s:28:"createInvestigatorMonitoring";i:24;s:17:"createCovidTracer";i:25;s:16:"viewCovidSummary";i:26;s:17:"viewPrintUserCode";i:27;s:23:"createCovidCasesUpdates";i:28;s:21:"viewCovidCasesUpdates";i:29;s:14:"createEncoding";i:30;s:14:"updateEncoding";i:31;s:17:"viewEncodingPrint";i:32;s:21:"createSmsNotification";i:33;s:21:"updateSmsNotification";i:34;s:19:"viewSmsNotification";i:35;s:21:"deleteSmsNotification";i:36;s:22:"restoreSmsNotification";i:37;s:18:"createGuestAccount";i:38;s:16:"viewGuestAccount";i:39;s:18:"viewPrintGuestCode";}',
                'status' => 1,
            ],
            [
                'position' => 'ECABS-SUPER-ADMIN',
                'access' => 'a:32:{i:0;s:13:"createAccount";i:1;s:13:"updateAccount";i:2;s:11:"viewAccount";i:3;s:13:"deleteAccount";i:4;s:14:"restoreAccount";i:5;s:12:"resetAccount";i:6;s:13:"deleteHistory";i:7;s:13:"verifyAccount";i:8;s:16:"createDepartment";i:9;s:16:"updateDepartment";i:10;s:14:"viewDepartment";i:11;s:16:"deleteDepartment";i:12;s:17:"restoreDepartment";i:13;s:13:"createUpdates";i:14;s:13:"updateUpdates";i:15;s:11:"viewUpdates";i:16;s:13:"deleteUpdates";i:17;s:14:"restoreUpdates";i:18;s:14:"createBarangay";i:19;s:14:"updateBarangay";i:20;s:12:"viewBarangay";i:21;s:14:"deleteBarangay";i:22;s:15:"restoreBarangay";i:23;s:12:"createAccess";i:24;s:12:"updateAccess";i:25;s:10:"viewAccess";i:26;s:12:"deleteAccess";i:27;s:13:"restoreAccess";i:28;s:8:"viewLogs";i:29;s:21:"createPreRegistration";i:30;s:21:"updatePreRegistration";i:31;s:19:"viewPreRegistration";}',
                'status' => 1,
            ]
        ]);
    }
}
