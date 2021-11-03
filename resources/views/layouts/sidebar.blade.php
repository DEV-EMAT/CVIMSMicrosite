<?php

    /* get department_used */
    $department_used = \App\Ecabs\PersonDepartmentPosition::where('person_id', '=', Auth::user()->person_id)->with('department_position', 'department_position.departments')->first()->department_position;

    $user = \App\Ecabs\Person::findOrfail(\Auth::user()->person_id);

    $tracerSection = $ecabsSection = $iskocabSection = $comprehensiveSection = 0;
?>

<div class="sidebar" data-background-color="brown" data-active-color="danger" id="sidebar">
    <!--
        Tip 1: you can change the color of the sidebar's background using: data-background-color="white | brown"
        Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
    -->
    <div class="logo">
        <a href="/" class="simple-text logo-mini">
            <img style="width: 40px;" src="{{ asset('images/ecabs/profiles/ecabslogo-rad.png') }}" />
        </a>
        <a href="/" class="simple-text logo-normal">

            @if(isset($department_used['departments']['acronym']))
                {{ (!empty($department_used))? strtoupper($department_used['departments']['acronym']):'LOGO 101' }}
            @else
                {{ (!empty($department_used))? strtoupper($department_used['departments']['department']):'LOGO 101' }}
            @endif
        </a>
    </div>

    <div class="sidebar-wrapper">
        <div class="user">
            <div class="info">
                <div class="photo">
                    <img src="{{ asset('images/'.$user['image']) }}" />
                </div>

                <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                    <span style="text-transform:initial;">
                        <p style="display: block;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">{{ ($user)? ucfirst($user['last_name']).', '.ucfirst($user['first_name']) : 'USER 101'  }}</p>
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="clearfix"></div>

                <div class="collapse" id="collapseExample">
                    <ul class="nav">
                        <li>
                        <a href="{{ route('account.profile') }}">
                                <span class="sidebar-mini"><i class="fa fa-user"></i></span>
                                <span class="sidebar-normal">Profile</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if(!empty($department_used))

            <ul class="nav">
                
                @can('permission','viewCovidHeader')
                <li>
                    <a href="#"><p>COVID TRACER</p></a>
                </li>
                <div class="divider"></div> 
                @endcan
                
                @can('permission','viewCovidDashboard')
                <li>
                    <a href="/covidtracer/dashboard">
                        <i class="fa fa-line-chart"></i>
                        <p>COVID Dashboard</p>
                    </a>
                </li>
                @endcan

                @can('permission','createCovidTracer')
                <li>
                    <a href="/covidtracer/tracer">
                        <i class="fa fa-search"></i>
                        <p> Covid Tracer</p>
                    </a>
                </li>
                @endcan

                @can('permission','viewCovidSummary')
                <li>
                    <a href="/covidtracer/summary">
                        <i class="fa fa-list-alt"></i>
                        <p> Covid Tracer Summary</p>
                    </a>
                </li>
                @endcan

                @if(Gate::check('permission', 'viewCovidCasesUpdates') || Gate::check('permission', 'createCovidCasesUpdates'))
                <li>
                    <a href="/covidtracer/cases-updates">
                        <i class="fa fa-line-chart"></i>
                        <p> Covid Cases Updates</p>
                    </a>
                </li>
                @endif

                @if(Gate::check('permission','createHotline') || Gate::check('permission','updateHotline')
                    || Gate::check('permission','viewHotline') || Gate::check('permission','deleteHotline')
                    || Gate::check('permission','restoreHotline'))
                <li>
                    <a href="/covidtracer/emergency-hotline">
                        <i class="fa fa-ambulance"></i>
                        <p> Emergency Hotline MNGT</p>
                    </a>
                </li>
                @endif

                @if(Gate::check('permission','createEstcat') || Gate::check('permission','updateEstcat')
                || Gate::check('permission','viewEstcat') || Gate::check('permission','deleteEstcat')
                || Gate::check('permission','restoreEstcat') || Gate::check('permission','createEstinfo') || Gate::check('permission','updateEstinfo')
                || Gate::check('permission','viewEstinfo') || Gate::check('permission','deleteEstinfo')
                || Gate::check('permission','restoreEstinfo'))
                <li>
                    <a data-toggle="collapse" href="#establishment_mngt">
                        <i class="fa fa-building"></i>
                        <p>ESTABLISHMENT MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="establishment_mngt">
                        <ul class="nav">
                            @if(Gate::check('permission','createEstcat') || Gate::check('permission','updateEstcat')
                                || Gate::check('permission','viewEstcat') || Gate::check('permission','deleteEstcat')
                                || Gate::check('permission','restoreEstcat'))
                            <li>
                                <a href="/covidtracer/estcat">
                                    <i class="fa fa-sliders"></i>
                                    <p> Est Category MNGT</p>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission','createEstinfo') || Gate::check('permission','updateEstinfo')
                                || Gate::check('permission','viewEstinfo') || Gate::check('permission','deleteEstinfo')
                                || Gate::check('permission','restoreEstinfo'))
                            <li>
                                <a href="/covidtracer/estinfo">
                                    <i class="fa fa-info"></i>
                                    <p> Est Information MNGT</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission','createInvestigator') || Gate::check('permission','createInvestigatorMonitoring'))
                <li>
                    <a data-toggle="collapse" href="#investigator">
                        <i class="fa fa-user-secret"></i>
                        <p>Investigator MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="investigator">
                        <ul class="nav">
                            @can('permission', 'createInvestigator')
                            <li>
                                <a href="/covidtracer/investigator">
                                    <span class="sidebar-mini"><i class="fa fa-user-plus"></i></span>
                                    <span class="sidebar-normal">Assign New Investigator</span>
                                </a>
                            </li>
                            @endcan
                            @can('permission', 'createInvestigatorMonitoring')
                            <li>
                                <a href="/covidtracer/investigator-monitoring">
                                    <span class="sidebar-mini"><i class="fa fa-file-text-o"></i></span>
                                    <span class="sidebar-normal">Investigator Monitoring</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission','createEncoding') || Gate::check('permission','createPatientMonitoring'))
                <li>
                    <a data-toggle="collapse" href="#encoding">
                        <i class="fa fa-stethoscope"></i>
                        <p>Patient MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="encoding">
                        <ul class="nav">
                            @can('permission', 'createEncoding')
                            <li>
                                <a href="/covidtracer/encoding">
                                    <span class="sidebar-mini"><i class="fa fa-user-plus"></i></span>
                                    <span class="sidebar-normal">Patient Encoding</span>
                                </a>
                            </li>
                            @endcan
                            @can('permission', 'createPatientMonitoring')
                            <li>
                                <a href="/covidtracer/patient-monitoring">
                                    <span class="sidebar-mini"><i class="fa fa-file-text-o"></i></span>
                                    <span class="sidebar-normal">Patient Monitoring</span>
                                </a>
                            </li>
                            @endcan

                            @can('permission', 'viewPatientReports')
                            <li>
                                <a href="/covidtracer/patient-monitoring/reports">
                                    <span class="sidebar-mini"><i class="fa fa-list-alt"></i></span>
                                    <span class="sidebar-normal">Patient Reports</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission','createSmsNotification') || Gate::check('permission','updateSmsNotification')
                || Gate::check('permission','viewSmsNotification') || Gate::check('permission','deleteSmsNotification')
                || Gate::check('permission','restoreSmsNotification'))
                <li>
                    <a data-toggle="collapse" href="#sms">
                        <i class="fa fa-envelope"></i>
                        <p> SMS NOTIFICATION MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="sms">
                        <ul class="nav">
                            @if(Gate::check('permission','createSmsNotification') || Gate::check('permission','updateSmsNotification')
                            || Gate::check('permission','viewSmsNotification') || Gate::check('permission','deleteSmsNotification')
                            || Gate::check('permission','restoreSmsNotification'))
                            <li>
                                <a href="/covidtracer/sms-notification">
                                    <i class="fa fa-pencil-square"></i>
                                    <p> MANAGE SMS</p>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('covidtracer.sms-notification.history') }}">
                                    <i class="fa fa-list-alt"></i>
                                    <p> SMS HISTORY</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission', 'viewECABSHeader'))
                <li>
                    <a href="#" id="ecabsSection"><p>ECABS</p></a>
                </li>
                <div class="divider"></div>
                @endif

                @if(Gate::check('permission','createPreRegistration') || Gate::check('permission','updatePreRegistration')
                    || Gate::check('permission','viewPreRegistration'))
                <li>
                    <a href="/maintenance">
                        <i class="fa fa-warning"></i>
                        <p> PRE-REGISTRATION MAINT</p>
                    </a>
                </li>
                @endif

                @if(Gate::check('permission','createDepartment') || Gate::check('permission','updateDepartment')
                || Gate::check('permission','viewDepartment') || Gate::check('permission','deleteDepartment')
                || Gate::check('permission','restoreDepartment'))
                <li>
                    <a href="/department">
                        <i class="fa fa-building-o"></i>
                        <p> DEPARTMENT MNGT</p>
                    </a>
                </li>
                @endif

                @if(Gate::check('permission','createBarangay') || Gate::check('permission','updateBarangay')
                    || Gate::check('permission','viewBarangay') || Gate::check('permission','deleteBarangay')
                    || Gate::check('permission','restoreBarangay'))
                <li>
                    <a href="/barangay">
                        <i class="fa fa-map-marker"></i>
                        <p> BARANGAY MNGT</p>
                    </a>
                </li>
                @endif


                @if(Gate::check('permission','createUpdates') || Gate::check('permission','updateUpdates')
                || Gate::check('permission','viewUpdates') || Gate::check('permission','deleteUpdates')
                || Gate::check('permission','restoreUpdates'))

                <li>
                    <a data-toggle="collapse" href="#updates">
                        <i class="fa fa-bullhorn"></i>
                        <p>UPDATES MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="updates">
                        <ul class="nav">
                            @can('permission','createUpdates')
                            <li>
                                <a href="/updates/create">
                                    <i class="fa fa-user-plus"></i>
                                    <p> CREATE ANNOUNCEMENT</p>
                                </a>
                            </li>
                            @endcan
                            @if(Gate::check('permission','deleteUpdates') || Gate::check('permission','updateUpdates') || Gate::check('permission','viewUpdates') || Gate::check('permission','restoreUpdates'))
                            <li>
                                <a href="/updates">
                                    <i class="fa fa-pencil-square"></i>
                                    <p> MANAGE UPDATES</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission','createAccount') || Gate::check('permission','updateAccount')
                    || Gate::check('permission','viewAccount') || Gate::check('permission','deleteAccount')
                    || Gate::check('permission','restoreAccount') || Gate::check('permission','resetAccount')
                    || Gate::check('permission','viewPrintUserCode') || Gate::check('permission','deleteHistory')
                    || Gate::check('permission','verifyAccount'))
                <li>
                    <a data-toggle="collapse" href="#account">
                        <i class="fa fa-lock"></i>
                        <p>ACCOUNT MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="account">
                        <ul class="nav">
                            {{-- Verify Account from Pre-registration --}}
                            @can('permission','verifyAccount')
                            <li>
                                <a href="/pre-register">
                                    <i class="fa fa-check-square"></i>
                                    <p> VERIFY ACCOUNT</p>
                                </a>
                            </li>
                            @endcan
                            @can('permission','createAccount')
                            <li>
                                <a href="/account/create">
                                    <i class="fa fa-user-plus"></i>
                                    <p> CREATE ACCOUNT</p>
                                </a>
                            </li>
                            @endcan
                            @if(Gate::check('permission','deleteAccount') || Gate::check('permission','updateAccount') || Gate::check('permission','viewAccount'))
                            <li>
                                <a href="/account">
                                    <i class="fa fa-pencil-square"></i>
                                    <p> MANAGE ACCOUNTS</p>
                                </a>
                            </li>
                            @endif
                            @can('permission','restoreAccount')
                            <li>
                                <a href="/account/archive">
                                    <i class="fa fa-archive"></i>
                                    <p> ARCHIVE</p>
                                </a>
                            </li>
                            @endcan
                            @can('permission','resetAccount')
                            <li>
                                <a href="/account/resetpassword">
                                    <i class="fa fa-refresh"></i>
                                    <p> RESET PASSWORD</p>
                                </a>
                            </li>
                            @endcan

                            @can('permission','deleteHistory')
                            <li>
                                <a href="/account-deletion-history">
                                    <i class="fa fa-list-alt"></i>
                                    <p> DELETION HISTORY</p>
                                </a>
                            </li>
                            @endcan

                            @can('permission', 'viewPrintUserCode')
                            <li>
                                <a href="/account/print-qr-code">
                                    <i class="fa fa-print"></i>
                                    <p> PRINT USER QR CODE</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission','createGuestAccount') || Gate::check('permission','updateGuestAccount')
                    || Gate::check('permission','viewPrintGuestCode'))
                <li>
                    <a data-toggle="collapse" href="#guestAccount">
                        <i class="fa fa-lock"></i>
                        <p>GUEST ACCOUNT MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="guestAccount">
                        <ul class="nav">
                            @can('permission','createGuestAccount')
                            <li>
                                <a href="/guest-account/create">
                                    <i class="fa fa-user-plus"></i>
                                    <p> CREATE ACCOUNT</p>
                                </a>
                            </li>
                            @endcan
                            @can('permission','viewGuestAccount')
                            <li>
                                <a href="/guest-account">
                                    <i class="fa fa-pencil-square"></i>
                                    <p> MANAGE ACCOUNTS</p>
                                </a>
                            </li>
                            @endcan
                            @can('permission','viewPrintGuestCode')
                            <li>
                                <a href="/guest-account/print-qr-code">
                                    <i class="fa fa-print"></i>
                                    <p> PRINT USER QR CODE</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission','createAccess') || Gate::check('permission','updateAccess')
                || Gate::check('permission','viewAccess') || Gate::check('permission','deleteAccess')
                || Gate::check('permission','restoreAccess'))
                <li>
                    <a href="/access">
                        <i class="fa fa-users"></i>
                        <p> SYSTEM ACCESS MNGT</p>
                    </a>
                </li>
                @endif

                @can('permission', 'viewLogs')
                <li>
                    <a href="/logs">
                        <i class="fa fa-users"></i>
                        <p> LOGS</p>
                    </a>
                </li>
                @endcan

                @can('permission', 'viewFileManagerManagement')
                <li>
                    <a data-toggle="collapse" href="#file_manager">
                        <i class="fa fa-folder"></i>
                        <p>FILE MANAGER MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="file_manager">
                        <ul class="nav">
                            {{-- --}}   <li>
                                <a href="/file-manager">
                                    <i class="fa fa-folder-open"></i>
                                    <p> FILE MANAGER</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan
         
                @if(Gate::check('permission', 'viewISKOCABHeader'))
                <li>
                    <a id="iskocabSection"><p>ISKOCAB</p></a>
                </li>
                <div class="divider"></div>
                @endif

                @if(Gate::check('permission','viewCYDADashboard'))
                <li>
                    <a href="/iskocab/dashboard">
                        <i class="fa fa-line-chart"></i>
                        <p>CYDAO Dashboard</p>
                    </a>
                </li>
                @endif

                <!-- Scholars -->
                @if(Gate::check('permission','createScholar') || Gate::check('permission','updateScholar')
                || Gate::check('permission','viewScholar') || Gate::check('permission','deleteScholar')
                || Gate::check('permission','restoreScholar') || Gate::check('permission','resetScholar')
                || Gate::check('permission','verifyScholar'))
                <li>
                    <a data-toggle="collapse" href="#scholar">
                        <i class="fa fa-graduation-cap"></i>
                        <p> SCHOLARS MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="scholar">
                        <ul class="nav">
                            @if(Gate::check('permission', 'verifyScholar'))
                            <li>
                                <a href="{{ route('scholar.verification') }}">
                                    <i class="fa fa-check-circle"></i>
                                    <p> SCHOLAR VERIFICATION</p>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission', 'createScholar'))
                            <li>
                                <a href="{{route('scholar.create')}}">
                                    <i class="fa fa-user-plus"></i>
                                    <p> CREATE SCHOLAR</p>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission', 'deleteScholar') || Gate::check('permission', 'updateScholar')
                            || Gate::check('permission', 'viewScholar'))
                            <li>
                                <a href="{{route('scholar.index')}}">
                                    <i class="fa fa-pencil-square"></i>
                                    <p> MANAGE SCHOLAR</p>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission', 'restoreScholar'))
                            <li>
                                <a href="{{route('scholar.archive')}}">
                                    <i class="fa fa-archive"></i>
                                    <p> ARCHIVE</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Program -->
                @if(Gate::check('permission','createProgram') || Gate::check('permission','updateProgram')
                || Gate::check('permission','viewProgram') || Gate::check('permission','deleteProgram')
                || Gate::check('permission','restoreProgram'))
                <li>
                    {{-- <a href="/comprehensive/program"> --}}
                    <a href="{{route('scholarship-program.index')}}">
                        <i class="fa fa-sliders "></i>
                        <p>PROGRAM MNGT</p>
                    </a>
                </li>
                @endif

                @if(Gate::check('permission','viewSchApplication') || Gate::check('permission','viewSchEvaluation')
                || Gate::check('permission','viewSchAssesment'))
                <li>
                    <a data-toggle="collapse" href="#scholarship">
                        <i class="fa fa-list-alt"></i>
                        <p> SCHOLARSHIP MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="scholarship">
                        <ul class="nav">
                            @if(Gate::check('permission', 'viewSchApplication'))
                            <li>
                                <a href="{{ route('application.index') }}">
                                    <i class="fa fa-edit"></i>
                                    <p> Application</p>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission', 'viewSchEvaluation'))
                            <li>
                                <a href="{{route('evaluation.index')}}">
                                    <i class="fa fa-check-circle"></i>
                                    <p> Evaluation / Assessment</p>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission', 'viewScholarshipSummary'))
                            <li>
                                <a href="{{ route('scholarship-summaries.index') }}">
                                    <i class="fa fa-folder"></i>
                                    <p>Scholarship Summary</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(Gate::check('permission','createSchool') || Gate::check('permission','updateSchool')
                || Gate::check('permission','viewSchool') || Gate::check('permission','deleteSchool')
                || Gate::check('permission','restoreSchool'))
                <li>
                    <a href="/iskocab/school">
                        <i class="fa fa-university"></i>
                        <p> SCHOOL MNGT</p>
                    </a>
                </li>
                @endif

                @if(Gate::check('permission', 'createEducationalAttainment') || Gate::check('permission', 'deleteEducationalAttainment')
                || Gate::check('permission', 'updateEducationalAttainment') || Gate::check('permission', 'viewEducationalAttainment') || Gate::check('permission', 'restoreEducationalAttainment') )
                <!-- type -->
                <li>
                    <a href="{{ route('educational-attainment.index') }}">
                        <i class="fa fa-clipboard"></i>
                        <p>EDUCATIONAL ATTAINMENT</p>
                    </a>
                </li>
                @endif

                @if(Gate::check('permission', 'createCourse') || Gate::check('permission', 'deleteCourse')
                || Gate::check('permission', 'updateCourse') || Gate::check('permission', 'viewCourse') || Gate::check('permission', 'restoreCourse'))
                <!-- course -->
                <li>
                    <a href="{{ route('course.index') }}">
                        <i class="fa fa-paperclip"></i>
                        <p>COURSE MNGT</p>
                    </a>
                </li>
                @endif




                @if(Gate::check('permission', 'viewComprehensiveHeader'))
                <li>
                    <a id="comprehensiveSection"><p>COMPREHENSIVE</p></a>
                </li>
                <div class="divider"></div>
                @endif

                <!-- Examination -->
                @if(Gate::check('permission', 'createSubject') || Gate::check('permission', 'deleteSubject') || Gate::check('permission', 'updateSubject')
                || Gate::check('permission', 'viewSubject')|| Gate::check('permission', 'restoreSubject')
                || Gate::check('permission', 'createQuestion') || Gate::check('permission', 'deleteQuestion') || Gate::check('permission', 'updateQuestion')
                || Gate::check('permission', 'viewQuestion') || Gate::check('permission', 'restoreQuestion')
                || Gate::check('permission', 'createExamination') || Gate::check('permission', 'deleteExamination')
                || Gate::check('permission', 'updateExamination') || Gate::check('permission', 'viewExamination')
                || Gate::check('permission', 'restoreExamination'))

                <li>
                    <a data-toggle="collapse" href="#tablesExamples">
                        <i class="fa fa-list"></i>
                        <p>
                            Examination MNGT
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="tablesExamples">
                        <ul class="nav">
                            @if(Gate::check('permission', 'createSubject') || Gate::check('permission', 'deleteSubject')
                                || Gate::check('permission', 'updateSubject') || Gate::check('permission', 'viewSubject') || Gate::check('permission', 'restoreSubject'))
                            <li>
                                <a href="{{ route('exam-subject.index') }}">
                                    <span class="sidebar-mini"><i class="fa fa-book"></i></span>
                                    <span class="sidebar-normal">Subjects</span>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission', 'createQuestion') || Gate::check('permission', 'deleteQuestion') || Gate::check('permission', 'updateQuestion')
                                || Gate::check('permission', 'viewQuestion') || Gate::check('permission', 'restoreQuestion'))

                            <li>
                                <a href="{{ route('exam-question.index') }}">
                                    <span class="sidebar-mini"><i class="fa fa-question"></i></span>
                                    <span class="sidebar-normal">Questions</span>
                                </a>
                            </li>
                            @endif

                            @if(Gate::check('permission', 'createExamination') || Gate::check('permission', 'deleteExamination')
                                || Gate::check('permission', 'updateExamination') || Gate::check('permission', 'viewExamination')  || Gate::check('permission', 'restoreExamination'))
                            <li>
                                <a href="{{ route('examination.index') }}">
                                    <span class="sidebar-mini"><i class="fa fa-list"></i></span>
                                    <span class="sidebar-normal">Examinations</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Requirement -->
                @if(Gate::check('permission','createRequirement') || Gate::check('permission','updateRequirement')
                || Gate::check('permission','viewRequirement') || Gate::check('permission','deleteRequirement')
                || Gate::check('permission','restoreRequirement'))
                <li>
                    <a href="/comprehensive/requirement">
                        <i class="fa fa-file-text-o "></i>
                        <p> REQUIREMENT MNGT</p>
                    </a>
                </li>
                @endif

                <!-- Event -->
                @if(Gate::check('permission','createEvent') || Gate::check('permission','updateEvent')
                || Gate::check('permission','viewEvent') || Gate::check('permission','deleteEvent')
                || Gate::check('permission','restoreEvent'))
                <li>
                    <a href="/comprehensive/event">
                        <i class="fa fa-edit "></i>
                        <p> EVENT MNGT</p>
                    </a>
                </li>
                @endif

                <!-- Qr Code -->
                @if(Gate::check('permission','viewQrCodePrinting'))
                <li>
                    <a href="/comprehensive/qr-code">
                        <i class="fa fa-sliders"></i>
                        <p> QR CODE PRINTING</p>
                    </a>
                </li>
                @endif


                @if(Gate::check('permission','viewGoTrabahoHeader'))
                <li>
                    <a id="comprehensiveSection"><p>GO TRABAHO</p></a>
                </li>
                <div class="divider"></div>
                @endif


                <!-- JobCategory -->
                @if(Gate::check('permission','createJobCategory') || Gate::check('permission','updateJobCategory')
                || Gate::check('permission','viewJobCategory') || Gate::check('permission','deleteJobCategory')
                || Gate::check('permission','restoreJobCategory'))


                <li>
                    <a href="/gotrabaho/job-category">
                        <i class="fa fa-file-text-o "></i>
                        <p> JOB CATEGORY MNGT</p>
                    </a>
                </li>
                @endif

                <!-- EmployerType -->
                @if(Gate::check('permission','createEmployerType') || Gate::check('permission','updateEmployerType')
                || Gate::check('permission','viewEmployerType') || Gate::check('permission','deleteEmployerType')
                || Gate::check('permission','restoreEmployerType'))
                <li>
                    <a href="/gotrabaho/employer-type">
                        <i class="fa fa-user"></i>
                        <p> EMPLOYER TYPE MNGT</p>
                    </a>
                </li>

                <li>
                    <a href="/gotrabaho/employer-type">
                        <i class="fa fa-briefcase"></i>
                        <p> JOB VACANCY MNGT</p>
                    </a>
                </li>

                @endif

                @if(Gate::check('permission','viewEmergencyResponseHeader'))
                <li>
                    <a id="comprehensiveSection"><p>EMERGENCY RESPONSE</p></a>
                </li>
                <div class="divider"></div>
                @endif

                @if(Gate::check('permission','createIncidentCategory'))
                {{-- ============================================ --}}
                <li>
                    <a data-toggle="collapse" href="#emergencyresponse">
                        <i class="fa fa-warning"></i>
                        <p>EMERGENCY RESPONSE
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="emergencyresponse">
                        <ul class="nav">
                            @can('permission', 'createIncidentCategory')
                            <li>
                                <a href="/emergency/incident-category">
                                    <span class="sidebar-mini"><i class="fa fa-user-plus"></i></span>
                                    <span class="sidebar-normal">Incident Category</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                        <ul class="nav">
                            <li>
                                <a href="/emergency/response-request">
                                    <span class="sidebar-mini"><i class="fa fa-user-plus"></i></span>
                                    <span class="sidebar-normal">Emergency Request</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </li>
                @endif
                @can('permission', 'viewVaccinationHeader')
                <li>
                    <a href="#" id="tracerSection"><p>VACCINATION</p></a>
                    <div class="divider"></div>
                </li>
                @endcan
                
                
                @can('permission', 'viewVaccinationDashboard')
                <li>
                    <a href="/covid19vaccine/statistics-dashboard">
                        <i class="fa fa-line-chart"></i>
                        <p>Vaccination Dashboard</p>
                    </a>
                </li>
                @endcan
                
                @if(Gate::check('permission','viewRegistrationAndValidation'))
                {{-- ============================================ --}}
                <li>
                    <a data-toggle="collapse" href="#registrationValidation">
                        <i class="fa fa-medkit" aria-hidden="true"></i>
                        <p>VACCINATION Mngt
                            <b class="caret"></b>
                        </p>
                    </a>
                    
                    <div class="collapse" id="registrationValidation">
                        <ul class="nav">
                            @can('permission', 'viewRegistrationAndValidation')
                            <li>
                                <a href="/covid19vaccine/registration-and-validation">
                                    <span class="sidebar-mini"><i class="fa fa-user-md" aria-hidden="true"></i></span>
                                    <span class="sidebar-normal">Patient Reg-Verification</span>
                                </a>
                            </li>
                            @endcan
                
                            @if(Gate::check('permission', 'viewSecondDoseVerification'))
                            <li>
                                <a href="/covid19vaccine/vaccination/second-dose-verification">
                                    <span class="sidebar-mini"><i class="fa fa-users"></i></span>
                                    <span> Second Dose Verification</span>
                                </a>
                            </li>
                            @endif
                            
                            <li>
                                <a href="/covid19vaccine/assessment">
                                    <span class="sidebar-mini"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                    <span class="sidebar-normal">Patient Vaccination Card</span>
                                </a>
                            </li>
                            
                            
                            @if(Gate::check('permission', 'viewVaccinationMonitoring'))
                            <li>
                                <a href="/covid19vaccine/vaccination-monitoring">
                                    <span class="sidebar-mini"><i class="fa fa-stethoscope" aria-hidden="true"></i></span>
                                    <span class="sidebar-normal">Patient Monitoring</span>
                                </a>
                            </li>
                            @endif
                            
                            @can('permission', 'viewVaccinationPatientMonitoring')
                            <li>
                                <a href="/covid19vaccine/vaccination-monitoring/view-monitoring">
                                    <span class="sidebar-mini"><i class="fa fa-file-text-o"></i></span>
                                    <span class="sidebar-normal">View Monitoring</span>
                                </a>
                            </li>
                            @endcan
                            
                            @can('permission', 'viewVaccinationPatientMonitoring')
                            <li>
                                <a href="/covid19vaccine/vaccination-monitoring/vaccinated-first-dose">
                                    <span class="sidebar-mini"><i class="fa fa-file-text-o"></i></span>
                                    <span class="sidebar-normal">Patients for Second Dose</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif
                
                @if(Gate::check('permission','createVaccinator') || Gate::check('permission','updateVaccinator')
                || Gate::check('permission','viewVaccinator') || Gate::check('permission','deleteVaccinator')
                || Gate::check('permission','restoreVaccinator'))
                <li>
                    <a href="/covid19vaccine/vaccinator">
                        <i class="fa fa-user-md"></i>
                        <p> VACCINATOR MNGT</p>
                    </a>
                </li>
                @endif
                
                
                @if(Gate::check('permission','createVaccineCategory') || Gate::check('permission','updateVaccineCategory')
                || Gate::check('permission','viewVaccineCategory') || Gate::check('permission','deleteVaccineCategory')
                || Gate::check('permission','restoreVaccineCategory'))
                <li>
                    <a href="/covid19vaccine/vaccine-category">
                        <i class="fa fa-user-md"></i>
                        <p> VACCINE CATEGORY MNGT</p>
                    </a>
                </li>
                @endif
                
                @if(Gate::check('permission','createHealthFacility') || Gate::check('permission','updateHealthFacility')
                || Gate::check('permission','viewHealthFacility') || Gate::check('permission','deleteHealthFacility')
                || Gate::check('permission','restoreHealthFacility'))
                <li>
                    <a href="/covid19vaccine/health-facility">
                        <i class="fa fa-user-md"></i>
                        <p> HEALTH FACILITY MNGT</p>
                    </a>
                </li>
                @endif
                
                @if(Gate::check('permission','changeDateFormat'))
                <li>
                    <a href="/covid19vaccine/registration/format-date-page">
                        <i class="fa fa-list"></i>
                        <p> DATE FORMAT</p>
                    </a>
                </li>
                @endif
                
                 <!-- EmployerType -->
                 @if(Gate::check('permission','viewCovid19FileUpload'))
                 <li>
                     <a data-toggle="collapse" href="#masterlist-file-upload">
                        <i class="fa fa-list" aria-hidden="true"></i>
                         <p>COVax Masterlist Mngt
                             <b class="caret"></b>
                         </p>
                     </a>
                     <div class="collapse" id="masterlist-file-upload">
                         <ul class="nav">
                             @can('permission', 'viewCovid19FileUpload')
                             <li>
                                 <a href="/covid19vaccine/file-upload">
                                     <span class="sidebar-mini"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                     <span class="sidebar-normal">Upload Masterlist</span>
                                 </a>
                             </li>
                             @endcan
                             
                             @can('permission', 'generateMasterlistVimsIR')
                             <li>
                                <a href="/covid19vaccine/survey-list/export">
                                    <span class="sidebar-mini"><i class="fa fa-cog" aria-hidden="true"></i></span>
                                    <span class="sidebar-normal">Generate Masterlist</span>
                                </a>
                            </li>
                            @endcan

                            
                            @can('permission', 'exportVASReport')
                            <li>
                                <a href="/covid19vaccine/vas-report">
                                    <span class="sidebar-mini"><i class="fa fa-cog" aria-hidden="true"></i></span>
                                    <span class="sidebar-normal">Generate VAS Reports</span>
                                </a>
                            </li>
                            @endcan

                            @can('permission', 'VASReportPerDate')
                            <li>
                                <a href="/covid19vaccine/vas-report-per-date">
                                    <span class="sidebar-mini"><i class="fa fa-cog" aria-hidden="true"></i></span>
                                    <span class="sidebar-normal">VAS Reports Per Date</span>
                                </a>
                            </li>
                            @endcan
                         </ul>
                     </div>
                 </li>
                 @endif
            </ul>
        @endif
    </div>
</div>
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        let ecabsSection = "<?php echo $ecabsSection?>";
        let tracerSection = "<?php echo $tracerSection?>";
        let iskocabSection = "<?php echo $iskocabSection?>";
        let comprehensiveSection = "<?php echo $comprehensiveSection?>";
    });
</script>
