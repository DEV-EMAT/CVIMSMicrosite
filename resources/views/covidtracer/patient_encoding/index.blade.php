@extends('layouts.app2')
@section('location')
{{$title}}
@endsection

@section('style')
    <style>
        td.details-control {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('../assets/image/minus.png') no-repeat center center;
        }


        #edit_table input{
            border: none;
            border-bottom: 1px solid;
            background-color: white
        } 
        
        #edit_table select{
            border: none;
            border-bottom: 1px solid;
            background-color: white
        } 
        
        .formTitle {
            /* timog man o hilaga */
            background-color: #e3e5e6;

        } */

        @media print {
            body {transform: scale(43);}
        }
        </style>
@endsection
@section('content')
<!-- Encoding Button -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="icon-big icon-danger text-center">
                                <i class="fa fa-thermometer-quarter" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-xs-9">
                            <div class="numbers">
                                <p>Total Covid Patients</p>
                                <b id="patientCounter">00</b>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-content">
                    <a class="btn btn-block btn-primary" onclick="encode_new_patient()" data-toggle="tooltip" title="Click here to encode new patient.">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span>
                        Encode New Patient
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">

        </div>
    </div>
</div>
<!-- Display All Data -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                </h4><br>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Covid Patient List</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="covid_patient_list" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        @if(Gate::check('permission','viewEncodingPrint') || Gate::check('permission','updateEncoding'))
                                            <th style="width: 20px"></th>
                                        @endif
                                        <th>Fullname</th>
                                        <th>Date onset of Illness</th>
                                        <th>Date of Admission</th>
                                    </tr>
                                </thead>
                                <!--Table head-->
                                <!--Table body-->
                                <tbody>
                                </tbody>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col-md-12 -->
        </div>
    </div>
</div>
<!-- End Display All Data -->


<!-- start modal -->
<div class="modal fade" id="encode_new_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title w-100 text-center">Modal title</h4>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto; background-color:#f7f7f7;">
                <div class="container-fluid">
                    <!--START OF INVESTIGATOR -->
                    <form id="investigationWizardForm" name="investigationWizardForm" novalidate="novalidate">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-content text-center">
                                        <div class="form-group">
                                            <label>Name of Investigator</label>
                                            <select class="selectpicker form-control"
                                                data-style="btn-info btn-fill btn-block" data-live-search="true"
                                                name="investigator_option" id="investigator_option">
                                                <option value="" disabled selected>Select.....</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-content text-center">
                                        <div class="form-group">
                                            <label>Place of Assignment (Barangay)</label>
                                            <select class="selectpicker form-control"
                                                data-style="btn-info btn-fill btn-block" data-live-search="true"
                                                name="place_of_assignment" id="place_of_assignment">
                                                <option value="" disabled selected>Select.....</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-content text-center">
                                        <div class="form-group">
                                            <label>Place of Assignment (Description)</label>
                                            <input type="text" class="form-control border-input"
                                                placeholder="Description" name="POA_description" id="POA_description">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-content text-center">
                                        <div class="form-group">
                                            <label>Place of Interview</label>
                                            <input type="text" class="form-control valid"
                                                name="place_of_interview" id="place_of_interview"
                                                placeholder="Place of Interview" aria-invalid="false">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-content text-center">
                                        <div class="form-group">
                                            <label>Date of Interview</label>
                                            <input type="text" class="form-control datetimepicker valid" name="date_of_interview" id="date_of_interview" placeholder="Date of Interview" aria-invalid="false">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-content text-center">
                                        <div class="form-group">
                                            <label>Classification</label>
                                            <select class="selectpicker form-control" data-live-search="true"
                                                name="classification" id="classification"
                                                data-style="btn-info btn-fill btn-block">
                                                <option value="" disabled selected>Select.....</option>
                                                <option value="Confirmed">Confirmed</option>
                                                <option value="Probable">Probable</option>
                                                <option value="Suspect">Suspect</option>
                                                <option value="Possible case">Possible case</option>
                                                <option value="Non-Covid">Non-Covid</option>
                                                <option value="Unclassified">Unclassified</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-content text-center">
                                        <div class="form-group">
                                            <label>Isolation Facility</label>
                                            <input type="text" class="form-control valid" name="facility" id="facility" placeholder="Isolation Facility">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--END OF INVESTIGATOR -->
                        <div class="card card-wizard" id="wizardCard">

                            <div class="card-header text-center">
                                <h4 class="card-title">Please Complete the following steps.</h4>
                                <p class="category">Patient Investigation Form</p>
                            </div>
                            <div class="card-content">
                                <ul class="nav nav-pills">
                                    <li class="active" style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab1" data-toggle="tab" aria-expanded="true">Step 1</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab2" data-toggle="tab">Step 2</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab3" data-toggle="tab">Step 3</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab4" data-toggle="tab">Step 4</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab5" data-toggle="tab">Step 5</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab6" data-toggle="tab">Step 6</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab7" data-toggle="tab">Step 7</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab8" data-toggle="tab">Step 8</a></li>
                                    <li style="width: 11.111111111111111%;pointer-events:none;"><a href="#tab9" data-toggle="tab">Step 9</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab1">
                                        <!--START OF PATIENT PROFILE -->
                                        <div class="row">
                                            <div class="patient_profile">
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4 class="card-title text-center"><b>PATIENT PROFILE</b>
                                                            </h4>
                                                        </div>
                                                        <div class="card-content">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="card">
                                                                        <div class="card-content text-center">
                                                                            <a class="btn btn-block btn-primary"
                                                                                onclick="add_new_patient()">
                                                                                <span class="btn-label">
                                                                                    <i class="fa fa-search"></i>
                                                                                </span>
                                                                                Search for Patient
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Last Name *</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Last Name" name="last_name"
                                                                            id="last_name">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>First Name *</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="First Name" name="first_name"
                                                                            id="first_name">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Middle Name</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Middle Name" name="middle_name"
                                                                            id="middle_name">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Date of Birth</label>
                                                                        <input type="text" class="form-control datetimepicker valid"
                                                                            id="date_of_birth" name="date_of_birth"
                                                                            placeholder="Date of Birth"
                                                                            aria-invalid="false">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Suffix</label><small>(Jr., Sr.,
                                                                            etc.)</small>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Suffix" name="suffix"
                                                                            id="suffix">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <div class="form-group">
                                                                        <label>Sex *</label>
                                                                        <select class="selectpicker form-control"
                                                                            name="sex" id="sex"
                                                                            data-style="btn-info btn-fill btn-block">
                                                                            <option value="" disabled selected>
                                                                                Select.....</option>
                                                                            <option value="1">Male</option>
                                                                            <option value="2">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <div class="form-group">
                                                                        <label>Civil Status *</label>
                                                                        <select class="selectpicker form-control"
                                                                            name="civil_status" id="civil_status"
                                                                            data-style="btn-info btn-fill btn-block">
                                                                            <option value="" disabled selected>
                                                                                Select.....</option>
                                                                            <option value="Single">Single</option>
                                                                            <option value="Married">Married</option>
                                                                            <option value="Divorced">Divorced</option>
                                                                            <option value="Seperated">Seperated</option>
                                                                            <option value="Widowed">Widowed</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <div class="form-group">
                                                                        <label>Contact No. *</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Contact No"
                                                                            name="contact_number" id="contact_number">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Email Address *</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Email Address" name="email" id="email">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Nationality *</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Nationality" name="nationality"
                                                                            id="nationality">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Passport Number</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Passport Number"
                                                                            name="passport_number" id="passport_number">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Work place</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Work place" name="work_place"
                                                                            id="work_place">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Social Sector *</label>
                                                                        <!-- multiple select -->
                                                                        <select multiple title="Select....."
                                                                            class="selectpicker" name="social_sector"
                                                                            id="social_sector"
                                                                            data-style="btn-info btn-fill btn-block"
                                                                            data-size="7" data-live-search="true">
                                                                            <option value="None">None</option>
                                                                            <option value="4PS">4PS</option>
                                                                            <option value="Business Owner">Business Owner (Small, Medium, Large)</option>
                                                                            <option value="Enterprise Owner">Enterprise
                                                                                Owner (Small, Medium, Large)</option>
                                                                            <option value="Farmers">Farmers</option>
                                                                            <option value="HomeWorkers/House Helpers">HomeWorkers/House Helpers</option>
                                                                            <option value="Minimum Wage Earner">Minimum Wage Earner</option>    
                                                                            <option value="OFW">OFW</option>
                                                                            <option value="Pedicab Driver">Pedicab Driver</option>
                                                                            <option value="PWD">PWD</option>
                                                                            <option value="PUV/PUJ/Taxi/Van/TNC Driver">PUV/PUJ/Taxi/Van/TNC Driver</option>
                                                                            <option value="Scholar ng Cabuyao">Scholar
                                                                                ng Cabuyao</option>
                                                                                <option value="Senior Citizen">Senior Citizen</option>
                                                                                <option value="Solo Parent">Solo Parent</option>
                                                                                <option value="Tricycle Driver">Tricycle Driver</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF PATIENT PROFILE-->
                                    </div>

                                    <div class="tab-pane" id="tab2">
                                        <!--START OF PHILIPPINE RESIDENCE -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>PHILIPPINE RESIDENCE</b>
                                                        </h4>
                                                    </div>
                                                    <div class="card-content">
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>House No./Lot/Bldg *</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="House No./Lot/Bldg"
                                                                        name="home_address" id="home_address">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Street *</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Street" name="street" id="street">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Region *</label>
                                                                    <select class="form-control" name="region_ph"
                                                                        id="region_ph"
                                                                        data-style="btn-info btn-fill btn-block">
                                                                        <option value="" disabled selected>Select.....
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Provice *</label>
                                                                    <select class="form-control" data-live-search="true"
                                                                        name="province_ph" id="province_ph"
                                                                        data-style="btn-info btn-fill btn-block">
                                                                        <option value="" disabled selected>Select.....
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>City/Municipality *</label>
                                                                    <select class="form-control" data-live-search="true"
                                                                        name="city_ph" id="city_ph"
                                                                        data-style="btn-info btn-fill btn-block">
                                                                        <option value="" disabled selected>Select.....
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Barangay *</label>
                                                                    <select class="form-control" data-live-search="true"
                                                                        name="brgy_ph" id="brgy_ph"
                                                                        data-style="btn-info btn-fill btn-block">
                                                                        <option value="" disabled selected>Select.....
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF PHILIPPINE RESIDENCE-->
                                    </div>

                                    <div class="tab-pane" id="tab3">
                                        <!--START OF OVERSEAS EMPLOYMENT ADDRESS -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>OVERSEAS EMPLOYMENT
                                                                ADDRESS (for Overseas Filipino Workers)</b></h4>
                                                    </div>
                                                    <div class="card-content">
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Employer's name</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Employer name" name="eoa_employers_name"
                                                                        id="eoa_employers_name">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Occupation</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Occupation" name="eoa_occupation"
                                                                        id="eoa_occupation">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <label>Place of Work</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Place of Work" name="eoa_place_of_work"
                                                                            id="eoa_place_of_work">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <label>House No./Bldg. Name</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="House No./Bldg. Name" name="eoa_home_address"
                                                                            id="eoa_home_address">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Street</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Street" name="eoa_street"
                                                                        id="eoa_street">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Region</label>
                                                                    <input type="text" class="form-control" name="eoa_region" id="eoa_region" placeholder="Region">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>City/Municipality</label>
                                                                    <input type="text" class="form-control" name="eoa_city" id="eoa_city" placeholder="City/Municipality">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Provice or State</label>
                                                                    <input type="text" class="form-control" name="eoa_province" id="eoa_province" placeholder="Provice or State">
                                                                </div>
                                                            </div>



                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Country</label>
                                                                    <input type="text" class="form-control" name="eoa_country" id="eoa_country" placeholder="Country">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <label>Office Phone No.</label>
                                                                        <input type="text"
                                                                            class="form-control border-input"
                                                                            placeholder="Office Phone No"
                                                                            name="eoa_office_phone_number"
                                                                            id="eoa_office_phone_number">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Cellphone No.</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Cellphone No."
                                                                        name="eoa_cellphone_number"
                                                                        id="eoa_cellphone_number">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF OVERSEAS EMPLOYMENT ADDRESS-->
                                    </div>

                                    <div class="tab-pane" id="tab4">
                                        <!--START OF TRAVEL HISTORY -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>TRAVEL HISTORY</b></h4>
                                                    </div>
                                                    <div class="card-content">
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <div class="form-group">
                                                                    <p>History of travel/visit/work in other countries
                                                                        within last 14 days:</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-2">
                                                                <div class="checkbox">
                                                                    <input type="checkbox" name="travel_his_checkbox"
                                                                        id="travel_his_checkbox" value="1">
                                                                    <label><small style="color: red;"><em>(Ckeck If
                                                                                YES)</em></small></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Port of Exit *</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Title" name="port_of_exit"
                                                                        id="port_of_exit" disabled>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Airline/Sea vessel *</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Title" name="airline_sea_vessel"
                                                                        id="airline_sea_vessel" disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">

                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Flight/Vessel Number *</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Title" name="flight_vessel"
                                                                        id="flight_vessel" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Date of Departure *</label>
                                                                    <input type="text" class="form-control datetimepicker valid"
                                                                        id="date_of_departure" name="date_of_departure"
                                                                        placeholder="Date of Departure"
                                                                        aria-invalid="false" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Date of Arrival in Philippines *</label>
                                                                    <input type="text" class="form-control datetimepicker valid"
                                                                        id="date_of_arrival_in_phil"
                                                                        name="date_of_arrival_in_phil"
                                                                        placeholder="Date of Arrival in Philippines"
                                                                        aria-invalid="false" disabled>
                                                                </div>
                                                            </div>


                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF TRAVEL HISTORY-->
                                    </div>

                                    <div class="tab-pane" id="tab5">
                                        <!--START OF EXPOSURE HISTORY -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>EXPOSURE HISTORY</b></h4>
                                                    </div>
                                                    <div class="card-content">
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <div class="form-group">
                                                                    <p>History of Exposure to Known CoViD-19 Case:</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-2">
                                                                <div class="form-group">
                                                                    <div class="radio">
                                                                        <input type="radio" name="history_of_exposure"
                                                                            id="history_of_exposure1" value="1">
                                                                        <label for="radio1">
                                                                            YES
                                                                        </label>
                                                                    </div>
                                                                    <div class="radio">
                                                                        <input type="radio" name="history_of_exposure"
                                                                            id="history_of_exposure2" value="0" checked>
                                                                        <label for="radio1">
                                                                            NO
                                                                        </label>
                                                                    </div>
                                                                    <div class="radio">
                                                                        <input type="radio" name="history_of_exposure"
                                                                            id="history_of_exposure3" value="unknown">
                                                                        <label for="radio1">
                                                                            UNKNOWN
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-sm-8">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>(If YES) Date of Contact with known
                                                                                CoViD-19 Case *</label>
                                                                            <input type="text" class="form-control datetimepicker valid"
                                                                                id="date_of_contact_with_covid"
                                                                                name="date_of_contact_with_covid"
                                                                                placeholder="Date of Contact with known CoViD-19 Case"
                                                                                aria-invalid="false" disabled>
                                                                        </div>
                                                                    </div>    
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Exposure Risk Type</label>
                                                                            <select class="form-control selectpicker" id="exposure_risk" name="exposure_risk">
                                                                                <option disabled selected value="">Select exposure risk</option>
                                                                                <option value="HIGH RISK">Hish Risk Exposure</option>
                                                                                <option value="LOW RISK">Low Risk Exposure</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>    
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF EXPOSURE HISTORY-->
                                    </div>

                                    <div class="tab-pane" id="tab6">
                                        <!--START OF CLINICAL INFORMATION -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>CLINICAL INFORMATION </b>
                                                        </h4>
                                                    </div>
                                                    <div class="card-content">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <p>Clinical Status at Time of Report:</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="radio">
                                                                    <div class="col-sm-2">
                                                                        <input type="radio" name="clinical_status"
                                                                            id="clinical_status1" value="Inpatient" checked>
                                                                        <label>Inpatient</label>
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <input type="radio" name="clinical_status"
                                                                            id="clinical_status2" value="Outpatient">
                                                                        <label>Outpatient</label>
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <input type="radio" name="clinical_status"
                                                                            id="clinical_status3" value="Died">
                                                                        <label>Died</label>
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <input type="radio" name="clinical_status"
                                                                            id="clinical_status4" value="Discharge">
                                                                        <label>Discharge</label>
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <input type="radio" name="clinical_status"
                                                                            id="clinical_status5" value="Unknown">
                                                                        <label>Unknown</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Date of Onset of Illness *</label>
                                                                    <input type="text" class="form-control datetimepicker valid"
                                                                        id="date_of_onset_of_illness"
                                                                        name="date_of_onset_of_illness"
                                                                        aria-invalid="false">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Date of Admission/Consultation *</label>
                                                                    <input type="text" class="form-control valid datetimepicker "
                                                                        id="date_of_addmission"
                                                                        name="date_of_addmission" aria-invalid="false">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Fever (Indicate the temperature) </label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Fever (Indicate the temperature)" name="fever_temperature"
                                                                        id="fever_temperature">
                                                                </div>
                                                            </div>
                                                            <div class="checkbox">
                                                                <div class="col-sm-2">
                                                                    <input type="checkbox" name="checkbox_cough"
                                                                        id="checkbox_cough" value="Cough">
                                                                    <label>Cough <small style="color: red;"><em>(Ckeck
                                                                                If YES)</em></small></label>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <input type="checkbox" name="checkbox_sore_throat"
                                                                        id="checkbox_sore_throat" value="Sore throat">
                                                                    <label>Sore throat <small
                                                                            style="color: red;"><em>(Ckeck If
                                                                                YES)</em></small></label>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <input type="checkbox" name="checkbox_colds"
                                                                        id="checkbox_colds" value="Colds">
                                                                    <label>Colds <small style="color: red;"><em>(
                                                                                CkeckIf YES)</em></small></label>
                                                                </div>

                                                                <div class="col-sm-3">
                                                                    <input type="checkbox"
                                                                        name="checkbox_shortness_of_breathing"
                                                                        id="checkbox_shortness_of_breathing"
                                                                        value="Shortness/difficulty of breathing ">
                                                                    <label>Shortness/difficulty of breathing <small
                                                                            style="color: red;"><em>(Ckeck If
                                                                                YES)</em></small></label>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Other symptoms, specify </label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Other symptoms"
                                                                        name="other_symptoms_specify"
                                                                        id="other_symptoms_specify">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Is there any history of other illness? <small
                                                                            style="color: red;"><em>if YES specify
                                                                                </em></small> </label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="History of other illness" name="his_of_other_illness"
                                                                        id="his_of_other_illness">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Chest XRAY done? <small
                                                                            style="color: red;"><em>if YES, when?
                                                                                </em></small> </label>
                                                                    <input type="text" class="form-control datetimepicker border-input"
                                                                        name="chest_xray_done" id="chest_xray_done">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Are you pregnant? <em>if YES, enter the
                                                                            LMP</em> *</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Are you pregnant?" name="pregnant"
                                                                        id="pregnant">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <div class="radio">
                                                                        <p>CXR Result</p>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio" name="cxr_result"
                                                                                 value="YES">
                                                                            <label>YES</label>
                                                                        </div>

                                                                        <div class="col-sm-2">
                                                                            <input type="radio" name="cxr_result"
                                                                                 value="NO" checked>
                                                                            <label>NO</label>
                                                                        </div>

                                                                        <div class="col-sm-2">
                                                                            <input type="radio" name="cxr_result"
                                                                                 value="PENDING">
                                                                            <label>PENDING</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Other Radiologic Findings *</label>
                                                                    <input type="text" class="form-control border-input"
                                                                        placeholder="Other Radiologic Findings"
                                                                        name="other_radiologic_findings"
                                                                        id="other_radiologic_findings">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF CLINICAL INFORMATION-->
                                    </div>

                                    <div class="tab-pane" id="tab7">
                                        <!--START OF SPECIMEN INFORMATION -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>SPECIMEN INFORMATION </b>
                                                        </h4>
                                                    </div>
                                                    <div class="card-content">
                                                        <div class="table-responsive">
                                                            <table id="tbl_specimen" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Specimen Collected</th>
                                                                        <th>Date Collected</th>
                                                                        <th>Date sent to RITM</th>
                                                                        <th>Date receive in RITM</th>
                                                                        <th>Virus Isolation Result</th>
                                                                        <th>PCR Result</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><input type="text"
                                                                                name="specimen_collected[]"
                                                                                class="form-control"></td>
                                                                        <td><input type="date" name="date_collected[]"
                                                                                class="form-control"></td>
                                                                        <td><input type="date"
                                                                                name="date_sent_to_ritm[]"
                                                                                class="form-control"></td>
                                                                        <td><input type="date"
                                                                                name="date_receive_in_ritm[]"
                                                                                class="form-control"></td>
                                                                        <td><input type="text"
                                                                                name="virus_isolation_result[]"
                                                                                class="form-control"></td>
                                                                        <td><input type="text" name="pcr_result[]"
                                                                                class="form-control"></td>
                                                                        <td><a class="btn btn-sm btn-info btn-fill btn-rotate"
                                                                                id="add_speciment"><i
                                                                                    class="fa fa-plus"></i></a></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF SPECIMEN INFORMATION-->
                                    </div>

                                    <div class="tab-pane" id="tab8">

                                        <!--START OF FINAL CLASSIFICATION -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>FINAL CLASSIFICATION</b>
                                                        </h4>
                                                    </div>
                                                    <div class="card-content">

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="radio">
                                                                    <div class="col-sm-3">
                                                                        <input type="radio" name="final_classification"
                                                                            id="final_classification1" value="PUI" checked>
                                                                        <label>Patient Under Investigation (PUI)</label>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <input type="radio" name="final_classification"
                                                                            id="final_classification2" value="PUM">
                                                                        <label>Person Under Monitoring (PUM)</label>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <input type="radio" name="final_classification"
                                                                            id="final_classification3"
                                                                            value="Confirmed">
                                                                        <label>Confirmed COViD-19 Case</label>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label for="final_classification" class="error"
                                                                            style="display:none;">Please choose
                                                                            one.</label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF FINAL CLASSIFICATION-->
                                    </div>

                                    <div class="tab-pane" id="tab9">
                                        <!--START OF FINAL CLASSIFICATION -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><b>OUTCOME</b></h4>
                                                    </div>
                                                    <div class="card-content">

                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <div class="form-group">
                                                                    <label>Date of Discharge</label>
                                                                    <input type="text" class="form-control valid datetimepicker"
                                                                        id="date_of_discharge" name="date_of_discharge"
                                                                        placeholder="Date of Birth"
                                                                        aria-invalid="false">
                                                                </div>
                                                            </div>
                                                            <div class="radio">
                                                                <p>Condition on Discharge: </p>
                                                                <div class="col-sm-1">
                                                                    <input type="radio" name="condition_on_discharge"
                                                                        id="condition_on_discharge1" value="Died">
                                                                    <label>Died</label>
                                                                </div>

                                                                <div class="col-sm-1">
                                                                    <input type="radio" name="condition_on_discharge"
                                                                        id="condition_on_discharge2" value="Improve">
                                                                    <label>Improve</label>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <input type="radio" name="condition_on_discharge"
                                                                        id="condition_on_discharge3" value="Recovered">
                                                                    <label>Recovered</label>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <input type="radio" name="condition_on_discharge"
                                                                        id="condition_on_discharge4"
                                                                        value="Transferred">
                                                                    <label>Transferred</label>
                                                                </div>

                                                                <div class="col-sm-2">
                                                                    <input type="radio" name="condition_on_discharge"
                                                                        id="condition_on_discharge5" value="Absconded">
                                                                    <label>Absconded</label>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <label for="condition_on_discharge" class="error"
                                                                        style="display:none;">Please choose one.</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Name of Informant: (if patient not
                                                                        available)</label>
                                                                    <input type="text" class="form-control valid"
                                                                        id="name_of_imformant" name="name_of_imformant"
                                                                        placeholder="Name of Informant"
                                                                        aria-invalid="false">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Relationship</label>
                                                                    <input type="text" class="form-control valid"
                                                                        id="relationship" name="relationship"
                                                                        placeholder="Relationship" aria-invalid="false">
                                                                </div>
                                                            </div>


                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>Phone No.</label>
                                                                    <input type="text" class="form-control valid"
                                                                        id="outcome_phone_number"
                                                                        name="outcome_phone_number"
                                                                        placeholder="Phone No." aria-invalid="false">
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END OF FINAL CLASSIFICATION-->
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button"
                                    class="btn btn-default btn-fill btn-wd btn-back pull-left">Back</button>
                                <button type="button"
                                    class="btn btn-info btn-fill btn-wd btn-next pull-right">Next</button>
                                <button type="button" class="btn btn-info btn-fill btn-wd btn-finish pull-right"
                                    onclick="onFinishWizard()">Finish and Submit</button>
                                <div class="clearfix"></div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer text-center">
            <button type="button" class="btn btn-danger btn-fill" data-dismiss="modal"><i class="fa fa-times"></i>
                Close</button>
        </div>
    </div>

</div>
</div>
<!-- End Modal -->

<!-- start modal for add patient -->
<div class="modal fade" id="add_new_patient" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title w-100 text-center" id="add_new_modal">User Profiles</h4>
            </div>
            <div class="modal-body"
                style="max-height: calc(100vh - 200px); overflow-y: auto; background-color:#f7f7f7;">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><b>Profile list</b></h4>
                                </div>
                                <div class="card-content">
                                    <table id="profile_datatable" class="table table-bordered table-sm table-hover"
                                        cellspacing="0" width="100%">
                                        <!--Table head-->
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th style="width: 200px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <!--Table head-->
                                        <!--Table body-->
                                        <tbody>
                                        </tbody>
                                        <!--Table body-->
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end col-md-12 -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- End Modalfor add patient -->




@endsection

@section('js')
<script src="{{asset('assets/js/ph_address.js')}}"></script>
<script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
<script>
    var datatable2 = "";
    $(document).ready(function () {

        /* checkbox on history exposure */
        $('input[name="history_of_exposure"]').click(function(){
            if ($(this).is(':checked')){
                if($(this).val() == '1'){
                    $('#date_of_contact_with_covid').prop('disabled', false);
                }else{
                    $('#date_of_contact_with_covid').prop('disabled', true);
                    $('#date_of_contact_with_covid').val("");
                }
            }
        });


        var validator = $("#investigationWizardForm").validate({
            rules: {
                //step 1
                place_of_assignment: { required: true }, 
                investigator_option: { required: true }, 
                POA_description: { required: true }, 
                place_of_interview: { required: true }, 
                date_of_interview: { required: true }, 
                classification: { required: true }, 
                first_name: { required: true }, 
                last_name: { required: true }, 
                date_of_birth: { required: true }, 
                nationality: { required: true }, 
                social_sector: { required: true }, 
                email: { required: true, email: true }, 
                sex: { required: true }, 
                contact_number: { required: true, phoneno: true }, 
                civil_status: { required: true }, 

                //step 2
                home_address: { required: true },
                street: { required: true },
                region_ph: { required: true },
                province_ph: { required: true },
                city_ph: { required: true },
                brgy_ph: { required: true },

                date_of_contact_with_covid: {required:true},
                history_of_exposure: {required:true},

                //step 4
                port_of_exit: { required: true },
                airline_sea_vessel: { required: true },
                flight_vessel: { required: true },
                date_of_departure: { required: true },
                date_of_arrival_in_phil: { required: true },
                
                //step 9
                date_of_discharge: { required: true },
                name_of_imformant: { required: true },
                relationship: { required: true },
                outcome_phone_number: { required: true, phoneno: true },
                eoa_cellphone_number: { phoneno: true },
                exposure_risk: { required: true },
                facility: { required: true },
            },
            // Specify validation error messages
            messages: {

                first_name: "Please enter firstname",
                last_name: "Please enter lastname",
                email: "Please enter a valid email address",
                date_of_birth: "Please enter date of birth",
                nationality: "Please enter nationality",
                social_sector: "Please enter social sector",
                sex: "Please enter sex",
                contact_number: "Please enter contact",
                civil_status: "Please enter civil status",
                home_address: "Please enter House No./Lot/Bldg",
                street: "Please enter street",
                region_ph: "Please enter region",
                province_ph: "Please enter provice",
                city_ph: "Please enter city",
                brgy_ph: "Please enter barangay",

                port_of_exit: "Please enter port of exit",
                airline_sea_vessel: "Please enter airline sea vessel",
                flight_vessel: "Please enter flight vessel",
                date_of_departure: "Please enter date of departure",
                date_of_arrival_in_phil: "Please enter date of arrival",

                clinical_status: "Please enter clinical status",

                name_of_imformant: "Please enter name of informant",
                relationship: "Please enter relationship",
                outcome_phone_number: "Please enter phone number"

            }
        });

        /* contact number */
        jQuery.validator.addMethod("phoneno", function (phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(09|\+639)\d{9}$/);
        }, "<br />Please specify a valid phone number");

        // you can also use the nav-pills-[blue | azure | green | orange | red] for a different color of wizard
        $('#wizardCard').bootstrapWizard({
            tabClass: 'nav nav-pills',
            nextSelector: '.btn-next',
            previousSelector: '.btn-back',
            onPrevious: function (tab, navigation, index) {
                var $valid = $('#investigationWizardForm').valid();

                if (!$valid) {
                    validator.focusInvalid();
                    return false;
                }
            },
            onNext: function (tab, navigation, index) {
                var $valid = $('#investigationWizardForm').valid();

                if (!$valid) {
                    validator.focusInvalid();
                    return false;
                }
            },
            onInit: function (tab, navigation, index) {

                //check number of tabs and fill the entire row
                var $total = navigation.find('li').length;
                $width = 100 / $total;

                $display_width = $(document).width();

                if ($display_width < 600 && $total > 3) {
                    $width = 50;
                }

                navigation.find('li').css('width', $width + '%');
            },
            onTabClick: function (tab, navigation, index) {
                // Disable the posibility to click on tabs
                return false;
            },
            onTabShow: function (tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index + 1;

                var wizard = navigation.closest('.card-wizard');

                // If it's the last tab then hide the last button and show the finish instead
                if ($current >= $total) {
                    $(wizard).find('.btn-next').hide();
                    $(wizard).find('.btn-finish').show();
                } else if ($current == 1) {
                    $(wizard).find('.btn-back').hide();
                } else {
                    $(wizard).find('.btn-next').show();
                    $(wizard).find('.btn-back').show();
                    $(wizard).find('.btn-finish').hide();
                }
            }
        });
    });

    function onFinishWizard() {
        //here you can do something, sent the form to server via ajax and show a success message with swal
        var valid = $('#investigationWizardForm').valid();

        if (!valid) {
            validator.focusInvalid();
            return false;
        } else {

            Swal.fire({
                title: 'Save new patient information?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    
                    var formData = new FormData($("#investigationWizardForm").get(0));
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: "{{ route('encoding.store') }}",
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function (response) {
                            if(response.success){
                                swal({
                                    title: "Success!",
                                    text: response.messages,
                                    type: "success"
                                }).then(function() {
                                    location.reload();
                                    // $('#encode_new_modal').modal('hide');
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }else{
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: response.messages,
                                    type: "error"
                                })
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: errorThrown,
                                type: "error"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    });
                }
            })
        }
    }

    //Start of Function for Address
    let myData = data;
    let region = '';
    let province = '';
    $(document).ready(function () {
        addressAutoFill('#region_ph', '#province_ph', '#city_ph', '#brgy_ph');
    });


    function addressAutoFill(selectRegion, selectProvince, selectCity, selectBarangay) {
        var $select = $(selectRegion);
        $.each(myData, function (index, value) {
            $select.append('<option value="' + index + '">' + value.region_name + '</option>');
        });

        $(selectRegion).on('change', function () {
            var selectedRegion = $(this).children("option:selected").val();
            region = selectedRegion;
            var $select = $(selectProvince);
            var $select_city = $(selectCity);
            var $select_brgy = $(selectBarangay);
            $select.empty()
            $select_city.empty()
            $select_brgy.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $select_city.append('<option value="" disabled selected>Select.....</option>');
            $select_brgy.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[selectedRegion].province_list, function (index, value) {
                $select.append('<option value="' + index + '">' + index + '</option>');
            });
        });

        $(selectProvince).on('change', function () {
            var selectedProvince = $(this).children("option:selected").val();
            province = selectedProvince;
            var $select = $(selectCity);
            var $select_brgy = $(selectBarangay);
            $select.empty()
            $select_brgy.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $select_brgy.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region].province_list[selectedProvince].municipality_list, function (index, value) {
                $select.append('<option value="' + index + '">' + index + '</option>');
            });
        });

        $(selectCity).on('change', function () {
            var selectedCity = $(this).children("option:selected").val();
            var $select = $(selectBarangay);
            $select.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region].province_list[province].municipality_list[selectedCity].barangay_list,
                function (index, value) {
                    $select.append('<option value="' + value + '">' + value + '</option>');
                });

        });
    }
    //End of Function for Address

    //start of add option value to investigator
    $(document).ready(function () {
        //get investigator
        $.ajax({
            url: '{{ route('encoding.find-all-barangay') }}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                for (let index = 0; index < response.length; index++) {
                    $('[name="place_of_assignment"]').append('<option value=' + response[index].id +
                        '>' + response[index].barangay + '</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    });
    //end of add option value to investigator

    //start of add option value to place of assignment
    $(document).ready(function () {
        //get investigator
        $.ajax({
            url: '{{ route('encoding.find-all-investigator') }}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                for (let index = 0; index < response.length; index++) {
                    var fullName = response[index].first_name + " " + response[index].last_name;
                    $('[name="investigator_option"]').append('<option value=' + response[index].id +
                        '>' + fullName + '</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    });
    //end of add option value to investigator


    //start datatable for patient profile

    $(document).ready(function () {
        var datatable = "";
        datatable = $('#profile_datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax": {
                "url": '{{ route('account.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data": {
                    _token: "{{csrf_token()}}",
                    action: 'selectUser'
                }
            },
            colReorder: {
                realtime: true
            },
            "columns": [{
                    "data": "fullname"
                },
                {
                    "data": "actions"
                },
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": [1]
            }, ]
        });


        datatable2 = $('#covid_patient_list').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax": {
                "url": '{{ route('covidtracer.patient-profile.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data": {
                    _token: "{{csrf_token()}}"
                }
            },
            "columns": [
                    @if(Gate::check('permission','viewEncodingPrint') || Gate::check('permission','updateEncoding'))
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    @endif
                    {"data": "fullname" },
                    {"data": "dateOnsetOfIllness"},
                    {"data": "dateOfAdmissionConsultation"},
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": [0,1,2]
            }, ]
        });

        $('#covid_patient_list tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = datatable2.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                if (datatable2.row( '.shown' ).length ) {
                    $('.details-control', datatable2.row( '.shown' ).node()).click();
                }
                format(row, row.data());
                tr.addClass('shown');
            }
        });

    });
    // end datatable for patient profile

    //Start of clinical information dynamic add page
    var maxfield = 7;
    var ctr = 1;
    $('#add_speciment').on('click', function (e) {
        e.preventDefault();

        if (ctr < maxfield) {
            ctr++;
            $('#tbl_specimen tbody').append(
                '<tr><td><input type="text" name="specimen_collected[]" class="form-control"></td><td><input type="date" name="date_collected[]" class="form-control"></td><td><input type="date" name="date_sent_to_ritm[]" class="form-control"></td><td><input type="date" name="date_receive_in_ritm[]" class="form-control"></td><td><input type="text" name="virus_isolation_result[]" class="form-control"></td><td><input type="text" name="pcr_result[]" class="form-control"></td><td><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_specimen"><i class="fa fa-trash"></i></a></td></tr>'
                );
        } else {
            swal('Warning!', 'Maximun of 7 fields only!', 'warning');
        }
    });

    $('#tbl_specimen tbody').on("click", "#remove_specimen", function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        ctr--;
    });
    //Start of clinical information dynamic add page

    //Start of investegator modal
    function encode_new_patient() {
        $('#encode_new_modal').modal('show'); // show bootstrap modal when complete loaded
        $('.modal-title').text('CASE INVESTIGATION FORM'); // Set title to Bootstrap modal title
    }
    //End of investegator modal

    //Start of add new patient modal
    function add_new_patient() {
        $('#add_new_patient').modal('show'); // show bootstrap modal when complete loaded
        $('#add_new_modal').text('SELECT PATIENT PROFILE'); // Set title to Bootstrap modal title
    }
    //End of add new patient modal

    // start of adding data to profile , from datatable to form
    function addProfile(id) {

        Swal.fire({
            title: 'Add this person?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                    url: '/covidtracer/encoding/find-user-by-id/' + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {
                        $('#add_new_patient').modal('hide');

                        $('#first_name').val(data[0].first_name);
                        $('#last_name').val(data[0].last_name);
                        $('#middle_name').val(data[0].middle_name);
                        $('#date_of_birth').val(data[0].date_of_birth);
                        $('#suffix').val(data[0].suffix);
                        $('#nationality').val(data[0].nationality);
                        $('#contact_number').val(data[0].contact_number);

                        $('#email').val(data[0].email);
                        $('#civil_status').prop('selectedIndex', data[0].civil_status);
                        $('#sex').prop('selectedIndex', data[0].gender);
                        $('.selectpicker').selectpicker('refresh');
                        swal({
                            title: "Added!",
                            text: "Successfully!",
                            type: "success"
                        });
                        //console.log(data[0].first_name);
                        //process loader false
                        processObject.hideProcessLoader();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            title: "Oops! something went wrong.",
                            text: errorThrown,
                            type: "error"
                        })
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });

            }
        });
    }
    // end of adding data to profile , from datatable to form





    let flag = true;
    $('#travel_his_checkbox').on('change',function(event){
        //  alert( event.target.value );
        if(flag){
            $('#port_of_exit').prop('disabled', false);
            $('#airline_sea_vessel').prop('disabled', false);
            $('#flight_vessel').prop('disabled', false);
            $('#date_of_departure').prop('disabled', false);
            $('#date_of_arrival_in_phil').prop('disabled', false);
            flag = false;
        }else{
            $('#port_of_exit').prop('disabled', true);
            $('#airline_sea_vessel').prop('disabled', true);
            $('#flight_vessel').prop('disabled', true);
            $('#date_of_departure').prop('disabled', true);
            $('#date_of_arrival_in_phil').prop('disabled', true);
            
            $('#port_of_exit').val("");
            $('#airline_sea_vessel').val("");
            $('#flight_vessel').val("");
            $('#date_of_departure').val("");
            $('#date_of_arrival_in_phil').val("");
            flag = true;
        }
    });

    $.ajax({
        url:'{{ route('covidtracer.patient-profile.counter') }}',
        type:'GET',
        dataType:'json',
        success:function(response){
            /* active */
            jQuery({ Counter: 0 }).animate({ Counter: response }, {
                duration: 3000,
                easing: 'swing',
                step: function (now) {
                    $('#patientCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                }
            });
        }
    });

    
    @if(Gate::check('permission','viewEncodingPrint') || Gate::check('permission','updateEncoding'))
    const format = (row, data) => {
        const result = data.detailed;
        const address = data.home_address;
        const assign = data.place_of_assignment;
        const symptoms = data.sign_n_sypmtoms;
        const specimen = data.specimen;

        let form = `<div class="row">
                    <div class="col-md-12">
                        <form id="update_form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="patient_id" value="${ result.id }">
                            <input type="hidden" name="address_1_id" value="${ address[0].id }">
                            <input type="hidden" name="address_2_id" value="${ address[1].id }">
                            <input type="hidden" name="assign_id" value="${ assign.id }">
                            <input type="hidden" name="symptoms_id" value="${ symptoms.id }">

                            <div style="display: flex; justify-content: flex-end">
                            
                                @can('permission', 'updateEncoding')
                                <input type="submit" value="UPDATE FORM" name="edit" class="btn btn-info btn-fill btn-wd"/>&nbsp;
                                @endcan
                                
                                @can('permission', 'viewEncodingPrint')
                                <input type="button" class="btn btn-primary btn-fill btn-wd" onclick="printDiv('${ result.id }')" value="PRINT FORM" />
                                @endcan
                            </div>
                            <div class="table-responsive"  id="covid_form">
                                <div id="barcode"></div>
                                <h3 class="text-center">Case Investigation Form <br> <b>Coronavirus Disease (COVID-19)</b> <br> <p style="font-size: 19px">City of Cabuyao, Laguna 4025</p></h3>
                                <table id="edit_table" class="table table-bordered" width="100%">
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Place of Assignment (Barangay)</label>
                                                    </div>
                                                    <select name="edit_place_of_assignment" style="width:100%" class="form-control input-sm" >
                                                        <option disabled value="" selected>SELECT BARANGAY</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Place of Assignment (Description)</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_place_of_assginment_desc" value="${ !(assign.description == null || assign.description == "" ) ? assign.description: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Date of Interview</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_date_of_interview" value="${ !(result.date_interview == null || result.date_interview == "" ) ? result.date_interview: '' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Place of Interview</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_place_of_interview" value="${ !(result.place_of_interview == null || result.place_of_interview == "" ) ? result.place_of_interview: 'N/A' }">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Name of Investigator</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control" name="edit_investigator_option">
                                                        <option disabled value="" selected>SELECT INVESTIGATOR</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Isolation Facility</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm" 
                                                    name="edit_facility" value="${ !(result.isolation_facility == null || result.isolation_facility == "" ) ? result.isolation_facility: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Classification</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control" name="edit_classification">
                                                        <option disabled value="" selected>SELECT CLASSIFICATION</option>
                                                        <option ${ (result.classification == 'CONFIRMED')? 'selected':'' } value="CONFIRMED">CONFIRMED</option>
                                                        <option ${ (result.classification == 'PROBABLE')? 'selected':'' } value="PROBABLE">PROBABLE</option>
                                                        <option ${ (result.classification == 'SUSPECT')? 'selected':'' } value="SUSPECTED">SUSPECT</option>
                                                        <option ${ (result.classification == 'POSSIBLE CASE')? 'selected':'' } value="POSSIBLE CASE">POSSIBLE CASE</option>
                                                        <option ${ (result.classification == 'NON-COVID')? 'selected':'' } value="NON-COVI">NON-COVID</option>
                                                        <option ${ (result.classification == 'UNCLASSIFIED')? 'selected':'' } value="UNCLASSIFIED">UNCLASSIFIED</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">1. Patient Profile</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Last Name</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm" 
                                                    name="edit_last_name" value="${ !(result.last_name == null || result.last_name == "" ) ? result.last_name: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">First Name</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_first_name" value="${ !(result.first_name == null || result.first_name == "" ) ? result.first_name: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Middle Name</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_middle_name" value="${ !(result.middle_name == null || result.middle_name == "" ) ? result.middle_name: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Birthday</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_date_of_birth" value="${ !(result.date_of_birth == null || result.date_of_birth == "" ) ? result.date_of_birth: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Suffix</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_affiliation" value="${ !(result.affiliation == null || result.affiliation == "" ) ? result.affiliation: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Sex</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control input-sm" name="edit_gender">
                                                        <option value="" selected disabled>Select Gender</option>
                                                        <option ${ (result.gender == "1" ) ? 'selected': '' } value="1">Male</option>
                                                        <option ${ (result.gender == "2" ) ? 'selected': '' } value="2">Female</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Contact Number</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_cellphone_no" value="${ !(address[0].cellphone_no == null || address[0].cellphone_no == "" ) ? address[0].cellphone_no: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Email Address</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_email" value="${ !(result.email == null || result.email == "" ) ? result.email: 'N/A' }">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Civil Status</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control" name="edit_civil_status">
                                                        <option disabled value="" selected>SELECT STATUS</option>
                                                        <option ${ (result.civil_status == 'SINGLE')? 'selected':'' } value="Single">SINGLE</option>
                                                        <option ${ (result.civil_status == 'MARRIED')? 'selected':'' } value="Married">MARRIED</option>
                                                        <option ${ (result.civil_status == 'DIVORCED')? 'selected':'' } value="Divorced">DIVORCED</option>
                                                        <option ${ (result.civil_status == 'SEPARATED')? 'selected':'' } value="Seperated">SEPARATED</option>
                                                        <option ${ (result.civil_status == 'WIDOWED')? 'selected':'' } value="Widowed">WIDOWED</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Nationality</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_nationality" value="${ !(result.nationality == null || result.nationality == "" ) ? result.nationality: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Passport No.</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_passport_number" value="${ !(result.passport_number == null || result.passport_number == "" ) ? result.passport_number: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Social Sector</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">2. Philippines Residence</label>
                                            </td>
                                        </tr>

                                        
                                        <tr>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">House No./Lot/Bldg</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_house_no" value="${ !(address[0].house_no == null || address[0].house_no == "" ) ? address[0].house_no: 'N/A' }">
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Street</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_street" value="${ !(address[0].street == null || address[0].street == "" ) ? address[0].street: 'N/A' }">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Region</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control input-sm" id="edit_region_country" name="edit_region_country">
                                                        <option selected disabled value="">SELECT REGION</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Province</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control input-sm" id="edit_province_state" name="edit_province_state">
                                                        <option selected disabled value="">SELECT PROVINCE</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">City</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control input-sm" id="edit_city_municipality" name="edit_city_municipality">
                                                        <option selected disabled value="">SELECT CITY</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Barangay</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control input-sm" id="edit_barangay" name="edit_barangay">
                                                        <option selected disabled value="">SELECT BARANGAY</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>

                                        
                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">3. Overseas Employment Address (for Overseas Filipino Workers)</label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Employer's name</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_employer_name" value="${ !(result.employer_name == null || result.employer_name == "" ) ? result.employer_name: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Occupation</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_occupation" value="${ !(result.occupation == null || result.occupation == "" ) ? result.occupation: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Place of Work</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_place_of_work_overseas" value="${ !(result.place_of_work_overseas == null || result.place_of_work_overseas == "" ) ? result.place_of_work_overseas: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">House No./Bldg. Name</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_house_no" value="${ !(address[1].house_no == null || address[1].house_no == "" ) ? address[1].house_no: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Street</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_street" value="${ !(address[1].street == null || address[1].street == "" ) ? address[1].street: 'N/A' }">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Region</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_region_country" value="${ !(address[1].region_country == null || address[1].region_country == "" ) ? address[1].region_country: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">City/Municipality</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_city_municipality" value="${ !(address[1].city_municipality == null || address[1].city_municipality == "" ) ? address[1].city_municipality: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Provice or State</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_province_state" value="${ !(address[1].province_state == null || address[1].province_state == "" ) ? address[1].province_state: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Country</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_region_country" value="${ !(address[1].region_country == null || address[1].region_country == "" ) ? address[1].region_country: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Office Phone No.</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_home_office_no" value="${ !(address[1].home_office_no == null || address[1].home_office_no == "" ) ? address[1].home_office_no: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Cellphone No.</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_eoa_cellphone_no" value="${ !(address[1].cellphone_no == null || address[1].cellphone_no == "" ) ? address[1].cellphone_no: 'N/A' }">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">4. Travel History</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">History of travel/visit/work in other countries within last 14 days:</label>
                                                    </div>
                                                    <label><input ${ (result.port_of_exit != '' || 
                                                            result.airline_sea_vessel != '' || 
                                                            result.flight_vessel_number != '' || 
                                                            result.date_of_departure != '' || 
                                                            result .date_of_arrival_in_philippines != '')? 'checked':''  } type="checkbox" name="edit_travel_history" id="edit_travel_history" > (Ckeck If YES)</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Port of Exit *</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_port_of_exit" id="edit_port_of_exit" value="${ !(result.port_of_exit == null || result.port_of_exit == "" ) ? result.port_of_exit: 'N/A' }">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Airline/Sea vessel *</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_airline_sea_vessel" id="edit_airline_sea_vessel" value="${ !(result.airline_sea_vessel == null || result.airline_sea_vessel == "" ) ? result.airline_sea_vessel: 'N/A' }">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Flight/Vessel Number *</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_flight_vessel_number" id="edit_flight_vessel_number" value="${ !(result.flight_vessel_number == null || result.flight_vessel_number == "" ) ? result.flight_vessel_number: 'N/A' }">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Date of Departure *</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_date_of_departure" id="edit_date_of_departure" value="${ !(result.date_of_departure == null || result.date_of_departure == "" ) ? result.date_of_departure: ''}">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Date of Arrival in Philippines *</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_date_of_arrival_in_philippines" id="edit_date_of_arrival_in_philippines" value="${ !(result.date_of_arrival_in_philippines == null || result.date_of_arrival_in_philippines == "" ) ? result.date_of_arrival_in_philippines: '' }">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">5. Exposure History</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">History of Exposure to Known CoViD-19 Case:</label>
                                                    </div>
                                                    <label><input type="radio" ${ ((result.his_of_exposure == '1')?'checked':'') } name="edit_history_of_exposure"  value="1"> YES</label>
                                                    <label><input type="radio" ${ ((result.his_of_exposure == '0')?'checked':'') } name="edit_history_of_exposure" value="0"> NO</label>
                                                    <label><input type="radio" ${ ((result.his_of_exposure == 'UNKNOWN')?'checked':'') } name="edit_history_of_exposure" value="unknown"> UNKNOWN</label>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">(If YES) Date of Contact with known CoViD-19 Case *</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_date_of_contact_if_yes" id="edit_date_of_contact_if_yes" value="${ !(result.date_of_contact_if_yes == null || result.date_of_contact_if_yes == "" ) ? result.date_of_contact_if_yes: '' }">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Exposure Risk Type</label>
                                                    </div>
                                                    <select style="width:100%" class="form-control input-sm" id="edit_exposure_risk" name="edit_exposure_risk">
                                                        <option disabled="" value="">SELECT RISK EXPOSURE</option>
                                                        <option ${ ((result.risk_exposure == 'HIGH RISK')?'selected':'') } value="HIGH RISK">HIGH RISK EXPOSURE</option>
                                                        <option ${ ((result.risk_exposure == 'LOW RISK')?'selected':'') } value="LOW RISK">LOW RISK EXPOSURE</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">6. Clinical Information</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                <div class="input-group-sm mb-3">
                                                    <label style="margin-right:15px">Clinical Status at Time of Report:</label>
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.clinical_status == 'INPATIENT')?'checked':'') } name="edit_clinical_status" value="INPATIENT"> INPATIENT</label>
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.clinical_status == 'OUTPATIENT')?'checked':'') } name="edit_clinical_status" value="OUTPATIENT"> OUTPATIENT</label>
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.clinical_status == 'DIED')?'checked':'') } name="edit_clinical_status" value="DIED"> DIED</label>
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.clinical_status == 'DISCHARGE')?'checked':'') } name="edit_clinical_status" value="DISCHARGE"> DISCHARGE</label>
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.clinical_status == 'UNKNOWN')?'checked':'') } name="edit_clinical_status" value="UNKNOWN"> UNKNOWN</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Date of Onset of Illness *</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_date_of_onset_of_illness" value="${ !(result.date_of_onset_of_illness == null || result.date_of_onset_of_illness == "" ) ? result.date_of_onset_of_illness: '' }">
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Date of Admission/Consultation *</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_date_of_admission_consultation" value="${ !(result.date_of_admission_consultation == null || result.date_of_admission_consultation == "" ) ? result.date_of_admission_consultation: '' }">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Fever (Indicate the temperature)</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_fever_degree" value="${ !(symptoms.fever_degree == null || symptoms.fever_degree == "" ) ? symptoms.fever_degree: 'N/A' }">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Cough </label>
                                                    </div>
                                                    <label><input name="edit_checkbox_cough" ${ (symptoms.cough == '1')? 'checked':'' } type="checkbox"> (Ckeck If YES)</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Sore throat </label>
                                                    </div>
                                                    <label><input name="edit_checkbox_sore_throat" ${ (symptoms.sore_throat == '1')? 'checked':'' } type="checkbox"> (Ckeck If YES)</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Colds </label>
                                                    </div>
                                                    <label><input name="edit_checkbox_colds" ${ (symptoms.colds == '1')? 'checked':'' } type="checkbox"> ( CkeckIf YES)</label>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Shortness/difficulty of breathing </label>
                                                    </div>
                                                    <label><input name="edit_checkbox_shortness_difficulty_of_breathing" ${ (symptoms.shortness_difficulty_of_breathing == '1')? 'checked':'' } type="checkbox"> (Ckeck If YES)</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Other symptoms, specify</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_other_symptoms" value="${ !(symptoms.other_symptoms == null || symptoms.other_symptoms == "" ) ? symptoms.other_symptoms: 'N/A' }">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Is there any history of other illness? if YES specify</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_history_of_other_illness" value="${ !(result.history_of_other_illness == null || result.history_of_other_illness == "" ) ? result.history_of_other_illness: 'N/A' }">
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Chest XRAY done? if YES, when?</label>
                                                    </div>
                                                    <input style="width:100%" type="date" class="form-control input-sm"
                                                    name="edit_chest_xray" value="${ !(result.chest_xray == null || result.chest_xray == "" ) ? result.chest_xray: '' }">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Are you pregnant? if YES, enter the LMP</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_pregnant" value="${ !(result.pregnant == null || result.pregnant == "" ) ? result.pregnant: 'N/A' }">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">CXR Result</label>
                                                    </div>
                                                    <label><input type="radio" ${ (result.cxr_result == 'YES')? 'checked':'' } name="edit_cxr_result" value="YES"> YES</label>
                                                    <label><input type="radio" ${ (result.cxr_result == 'NO')? 'checked':'' } name="edit_cxr_result" value="NO" checked=""> NO</label>
                                                    <label><input type="radio" ${ (result.cxr_result == 'PENDING')? 'checked':'' } name="edit_cxr_result" value="PENDING"> PENDING</label>
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Other Radiologic Findings</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_other_radiologic_findings" value="${ !(result.other_radiologic_findings == null || result.other_radiologic_findings == "" ) ? result.other_radiologic_findings: 'N/A' }">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">7. Specimen Information</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <label style="font-size:14px">Specimen Collected</label>
                                            </td>
                                            <td class="text-center">
                                                <label style="font-size:14px">Date Collected</label>
                                            </td>
                                            <td class="text-center">
                                                <label style="font-size:14px">Date sent to RITM</label>
                                            </td>
                                            <td class="text-center">
                                                <label style="font-size:14px">Date receive in RITM</label>
                                            </td>
                                            <td class="text-center">
                                                <label style="font-size:14px">Virus Isolation Result</label>
                                            </td>
                                            <td class="text-center">
                                                <label style="font-size:14px">PCR Result</label>
                                            </td>
                                        </tr>
                                        ${ specimen.map(specimenTemplate).join('') }

                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">8. Final Classification</label>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                <div class="input-group-sm mb-3">
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.final_classification == 'PUI')? 'checked':'') } name="final_classification" value="PUI"> Patient Under Investigation (PUI)</label>
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.final_classification == 'PUM')? 'checked':'') } name="final_classification" value="PUM"> Person Under Monitoring (PUM)</label>
                                                    <label style="margin-right:15px"><input type="radio" ${ ((result.final_classification == 'CONFIRMED')? 'checked':'') } name="final_classification" value="CONFIRMED"> Confirmed COViD-19 Case</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="formTitle text-center" colspan="6">
                                                <label style="font-size:18px">9. Outcome</label>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Date of Discharge</label>
                                                    </div>
                                                    <input style="width:100%" type="date  " class="form-control input-sm"
                                                    name="edit_date_of_discharge" value="${ !(symptoms.date_of_discharge == null || symptoms.date_of_discharge == "" ) ? symptoms.date_of_discharge: '' }">
                                                </div>
                                            </td>
                                            
                                            <td class="text-center" colspan="5">
                                                <div class="input-group-sm mb-3">
                                                    <label style="margin-right:15px"><input type="radio" name="outcome" ${ (result.outcome == 'DIED')? 'checked':'' } value="Died"> Died</label>
                                                    <label style="margin-right:15px"><input type="radio" name="outcome" ${ (result.outcome == 'IMPROVE')? 'checked':'' } value="Improve"> Improve</label>
                                                    <label style="margin-right:15px"><input type="radio" name="outcome" ${ (result.outcome == 'RECOVERED')? 'checked':'' } value="Recovered">Recovered</label>
                                                    <label style="margin-right:15px"><input type="radio" name="outcome" ${ (result.outcome == 'TRANSFERRED')? 'checked':'' } value="Transferred">Transferred</label>
                                                    <label style="margin-right:15px"><input type="radio" name="outcome" ${ (result.outcome == 'ABSCONDED')? 'checked':'' } value="Absconded">Absconded</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Name of Informant: (if patient not available)</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_name_of_informant" value="${ !(symptoms.name_of_informant == null || symptoms.name_of_informant == "" ) ? symptoms.name_of_informant: 'N/A' }">
                                                </div>
                                            </td>
                                            
                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Relationship</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_relationship" value="${ !(symptoms.relationship == null || symptoms.relationship == "" ) ? symptoms.relationship: 'N/A' }">
                                                </div>
                                            </td>

                                            <td colspan="2">
                                                <div class="input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label for="basic-url">Phone No.</label>
                                                    </div>
                                                    <input style="width:100%" type="text" class="form-control input-sm"
                                                    name="edit_relationship_phone_no" value="${ !(symptoms.relationship_phone_no == null || symptoms.relationship_phone_no == "" ) ? symptoms.relationship_phone_no: 'N/A' }">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div style="float:right;">
                                    <p style="font-size:10pt">This copy is system generated document.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>`;
        

        row.child(form).show();

        $.ajax({
            url: '{{ route('encoding.find-all-barangay') }}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                for (let index = 0; index < response.length; index++) {
                    $('[name="edit_place_of_assignment"]').append('<option '+ ((data.place_of_assignment.barangay_id == response[index].id)? 'selected':'') +' value=' + response[index].id +
                        '>' + response[index].barangay + '</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });

        //get investigator
        $.ajax({
            url: '{{ route('encoding.find-all-investigator') }}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                for (let index = 0; index < response.length; index++) {
                    var fullName = response[index].first_name + " " + response[index].last_name;
                    $('[name="edit_investigator_option"]').append('<option '+ ((assign.investigator_id == response[index].id)? 'selected':'') +' value=' + response[index].id +
                        '>' + fullName + '</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
        
        /* set data on address fields */
        addressAutoFill('#edit_region_country', '#edit_province_state', '#edit_city_municipality', '#edit_barangay');
    
        if(address[0].region_country){
            $("#edit_region_country").val(address[0].region_country);
            //province combo box
            $.each(myData[$("#edit_region_country").val()].province_list, function(index, value) {
                $("#edit_province_state").append('<option value="' + index + '">' + index + '</option>');
            });
            if(address[0].province_state){
                $("#edit_province_state").val(address[0].province_state);
                //city combo box
                $.each(myData[$("#edit_region_country").val()].province_list[$("#edit_province_state").val()].municipality_list, function(index, value) {
                    $("#edit_city_municipality").append('<option value="' + index + '">' + index + '</option>');
                });
            }
            if(address[0].city_municipality){
                $("#edit_city_municipality").val(address[0].city_municipality);
                //barangay combo box
                $.each(myData[$("#edit_region_country").val()].province_list[$("#edit_province_state").val()].municipality_list[$("#edit_city_municipality").val()].barangay_list, function(index, value) {
                    $("#edit_barangay").append('<option value="' + value + '">' + value + '</option>');
                });
            }
            if(address[0].barangay)$("#edit_barangay").val(address[0].barangay);

        }
        
        // let edit_flag = true;
        $('#edit_travel_history').on('change',function(){
            //  alert( event.target.value );
            if(this.checked){
                $('#edit_port_of_exit').prop('disabled', false);
                $('#edit_airline_sea_vessel').prop('disabled', false);
                $('#edit_flight_vessel_number').prop('disabled', false);
                $('#edit_date_of_departure').prop('disabled', false);
                $('#edit_date_of_arrival_in_philippines').prop('disabled', false);
            }else{
                $('#edit_port_of_exit').prop('disabled', true);
                $('#edit_airline_sea_vessel').prop('disabled', true);
                $('#edit_flight_vessel_number').prop('disabled', true);
                $('#edit_date_of_departure').prop('disabled', true);
                $('#edit_date_of_arrival_in_philippines').prop('disabled', true);

                 
                $('#edit_airline_sea_vessel').val('');
                $('#edit_flight_vessel_number').val('');
                $('#edit_date_of_departure').val('');
                $('#edit_date_of_arrival_in_philippines').val('');
            }
        });

        /* checkbox on history exposure */
        $('input[name="edit_history_of_exposure"]').click(function(){
            if ($(this).is(':checked')){
                if($(this).val() == '1'){
                    $('#edit_date_of_contact_if_yes').prop('disabled', false);
                }else{
                    $('#edit_date_of_contact_if_yes').prop('disabled', true);
                    $('#edit_date_of_contact_if_yes').val("");
                }
            }
        });

        
        $("#update_form").validate({
            rules: {
                edit_place_of_assignment: {
                    required: true
                    // minlength: 3
                },
                edit_place_of_interview: { required: true, },
                edit_date_interview: { required: true, },
                edit_place_of_assginment_desc: { required: true, },
                edit_investigator_option: { required: true, },
                edit_facility: { required: true, },
                edit_classification: { required: true, },
                edit_last_name: { required: true, },
                edit_middle_name: { required: true, },
                edit_first_name: { required: true, },
                edit_date_of_birth: { required: true, },
                edit_affiliation: { required: true, },
                edit_gender: { required: true, },
                edit_cellphone_no: { required: true, },
                edit_email: { required: true, },
                edit_civil_status: { required: true, },
                edit_nationality: { required: true, },
                edit_passport_number: { required: true, },
                edit_house_no: { required: true, },
                edit_street: { required: true, },
                edit_region_country: { required: true, },
                edit_province_state: { required: true, },
                edit_city_municipality: { required: true, },
                edit_barangay: { required: true, },
                edit_date_of_discharge: { required: true, },
                edit_name_of_informant: { required: true, },
                edit_relationship: { required: true, },
                edit_relationship_phone_no: { required: true, },
                
            },
            submitHandler: function (form) {   
                // var id = $('#edit_id').val();
                Swal.fire({
                    title: 'Update patie  nt profile?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {      
                    if (result.value) {
                        var formData = new FormData($("#update_form").get(0));
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '/covidtracer/encoding/1',
                            type: "POST",
                            data: $("#update_form").serialize(),
                            dataType: "JSON",
                            success: function (response) {
                                if(response.success){
                                    swal({
                                        title: "Updated!",
                                        text: response.messages,
                                        type: "success"
                                    }).then(function() {
                                        $("#update_form")[0].reset();
                                        $('#update_modal').modal('hide');
                                        datatable2.ajax.reload( null, false);
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }else{
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: response.messages,
                                        type: "error"
                                    })
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: errorThrown,
                                    type: "error"
                                })
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });

    }

    function printDiv(patient_id) {
        processObject.showProcessLoader();
        $.ajax({
            url:'{{ route('covidtracer.print-docs.store') }}',
            type:'POST',
            data:{ _token:'{{ csrf_token() }}', module:'PATIENT ENCODING - Patient: '+ patient_id },
            dataType:'JSON',
            success:function(response){
                imageCode = '<div style="position:absolute; top:10; right:10px; text-align:center"><img style="width:250px" src="data:image/png;base64,' + response.data + '"></div>';
                $('#barcode').append(imageCode);

                $("#covid_form").print();
                processObject.hideProcessLoader();
            }
        });
    }

    const specimenTemplate = (sample) => {
        return `<tr>
            <td class="text-center">
                <input type="hidden" name="specimen_id[]" value="${ sample.id }">
                <input style="width:100%" type="text" name="edit_specimen_collected[]" value="${ !(sample.speciment_category == null || sample.speciment_category == "" ) ? sample.speciment_category: 'N/A' }" class="form-control input-sm">
            </td>
            <td class="text-center">
                <input style="width:100%" type="date" name="edit_date_collected[]" value="${ !(sample.date_collected == null || sample.date_collected == "" ) ? sample.date_collected: '' }" class="form-control input-sm">
            </td>
            <td class="text-center">
                <input style="width:100%" type="date" name="edit_date_sent_to_ritm[]" value="${ !(sample.date_sent_to_RITM == null || sample.date_sent_to_RITM == "" ) ? sample.date_sent_to_RITM: '' }" class="form-control input-sm">
            </td>
            <td class="text-center">
                <input style="width:100%" type="date" name="edit_date_receive_in_ritm[]" value="${ !(sample.date_received_in_RITM == null || sample.date_received_in_RITM == "" ) ? sample.date_received_in_RITM: '' }" class="form-control input-sm">
            </td>
            <td class="text-center">
                <input style="width:100%" type="text" name="edit_virus_isolation_result[]" value="${ !(sample.virus_isolation_result == null || sample.virus_isolation_result == "" ) ? sample.virus_isolation_result: 'N/A' }" class="form-control input-sm">
            </td>
            <td class="text-center">
                <input style="width:100%" type="text" name="edit_pcr_result[]" value="${ !(sample.virus_isolation_result == null || sample.virus_isolation_result == "" ) ? sample.virus_isolation_result: 'N/A' }" class="form-control input-sm">
            </td>
        </tr>`;
    }
    @endif

</script>
@endsection
