@extends('layouts.app2')

@section('location')
{{$title}}
@endsection
@section('style')
    <style>
        #summary_datatable_paginate{
            display: none;
        }
        
        .choice{
            text-align: center;
        }
        
        input[type="radio"]{
            zoom: 1.75;
        }
        
        input[type=checkbox]{
            zoom: 1.3;
        }
        
        .surveyLabel{
            font-size: 17px;
        }
        .radio-toolbar label {
            display: inline-block;
            background-color: #ddd;
            padding: 10px 20px;
            font-family: sans-serif, Arial;
            font-size: 16px;
            border: 2px solid #444;
            border-radius: 4px;
        }
        
        label.btn-success:hover{
            background: green;
        }
        
        td.details-control {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('../assets/image/minus.png') no-repeat center center;
        }
    </style>
@endsection
@section('content')
    <!-- Display All Data -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10">
                                    <h4 class="card-title"><b><i class="fa fa-user-md" aria-hidden="true"></i> Patient List</b></h4>
                                    <p class="category">Vaccination Monitoring</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Patient Fullname</th>
                                        <th>Status</th>
                                        <th style="width: 600px;">Actions</th>
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
    <!-- End Display All Data -->
    
    <!-- Modal Patient Monitoring -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="monitor_vaccination">
        <div class="modal-dialog modal-lg" role="document">
            <form id="monitor_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Patient Monitoring</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    <label  id="vaccineAcquired"></label><br>
                                    <label  id="vaccineAcquired2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Fullname -->
                            <div class="form-group col-md-12">
                                <label for="fullname">Patient Fullname</label>
                                <input type="text" class="form-control" name="fullname" id="fullname"
                                    placeholder="Enter Fullname" disabled>
                            </div>
                            <!-- End Fullname -->
                        </div>    
                    
                        <div class="row">
                            <!-- Dosage -->
                            <div class="form-group col-md-6">
                                <label for="dosage">Dosage</label>
                                <select type="text" class="form-control selectpicker" name="dosage" id="dosage"
                                    placeholder="Enter Dosage">
                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                                </select>
                            </div>
                            <!-- End Dosage -->
                            
                            <!-- Vaccination Date -->
                            <div class="form-group col-md-6">
                                <label for="vaccination_date">Vaccination Date</label>
                                <input type="text" class="form-control datetimepicker valid"
                                    id="vaccination_date" name="vaccination_date"
                                    placeholder="Date of Vaccination" aria-invalid="false" max="9999-12-31">
                            </div>
                            <!-- End Vaccination Date -->
                        </div>
                        
                        <div class="row">
                            <!-- Vaccine Manufacturer -->
                            <div class="form-group col-md-6">
                                <label for="vaccine_manufacturer">Vaccine Manufacturer</label>
                                <select type="text" class="form-control selectpicker" name="vaccine_manufacturer" id="vaccine_manufacturer"
                                    placeholder="Enter Vaccine Manufacturer">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                            <!-- End Vaccine Manufacturer -->
                            
                            <!-- Batch Number -->
                            <div class="form-group col-md-6">
                                <label for="batch_number">Batch Number</label>
                                <input type="text" class="form-control" name="batch_number" id="batch_number"
                                    placeholder="Enter Batch Number">
                            </div>
                            <!-- End Batch Number -->
                        </div>
                        
                        <div class="row">
                            <!-- Lot Number -->
                            <div class="form-group col-md-6">
                                <label for="lot_number">Lot Number</label>
                                <input type="text" class="form-control" name="lot_number" id="lot_number"
                                    placeholder="Enter Lot Number">
                            </div>
                            <!-- End Lot Number -->
                            
                            <!-- Vaccinator -->
                            <div class="form-group col-md-6">
                                <label for="vaccinator">Vaccinator</label>
                                <select class="selectpicker form-control" data-live-search="true"
                                    name="vaccinator" id="vaccinator">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                             </div>
                             <!-- End Vaccinator -->
                        </div>
                        
                        <div class="row">
                            <!-- Consent -->
                            <div class="form-group col-md-6">
                                <label for="consent">Consent</label>
                                <select type="text" class="form-control selectpicker" name="consent" id="consent" placeholder="Enter Consent">
                                    <option value="YES">Yes</option>
                                    <option value="NO">No</option>
                                </select>
                            </div>
                            <!-- End Consent -->
                        </div>
                        
                        <div class="row">
                            <!-- Reason for Refusal -->
                            <div class="form-group col-md-12">
                                <label for="reason_for_refusal">Reason for Refusal</label>
                                <textarea type="text" class="form-control" name="reason_for_refusal" id="reason_for_refusal"
                                    placeholder="Enter Reason for Refusal" disabled></textarea>
                            </div>
                            <!-- End Reason for Refusal -->
                        </div>
                        
                        <div class="row">
                            <!-- Deferral -->
                            <div class="form-group col-md-12">
                                <label for="deferral">Deferral <i style="font-weight: normal; font-size:11px">(Optional)</i></label>
                                <textarea type="text" class="form-control" name="deferral" id="deferral"
                                    placeholder="Enter Deferral"></textarea>
                            </div>
                            <!-- End Deferral -->
                        </div>
                        
                        <div>
                            <div class="panel panel-border panel-primary">
                                <a data-toggle="collapse" href="#monitorOtherInformationcollapse">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            Other Informations
                                            <i class="ti-angle-down"></i>
                                        </h4>
                                    </div>
                                </a>
                                <div id="monitorOtherInformationcollapse" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <input type="hidden" name="regID" id="regID">
                                        <table class="table table-bordered table-sm table-hover surveyLabel" cellspacing="0" id="tbl_monitoring_questions"
                                            width="100%" style="background-color: rgb(255, 255, 255);">
                                            <!--Table head-->
                                            <thead>
                                                <tr>
                                                    <th style="width: 70%; font-weight: bold;">Questions</th>
                                                    <th class="choice" style="font-weight: bold">Remarks</th>
                                                </tr>
                                            </thead>
                                            <!--Table head-->
    
                                            <!--Table body-->
                                            <tbody>
                                                <tr>
                                                    <td>Edad ay mas mababa sa 18 taong gulang?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question1" name="question1" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question1" name="question1" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May alerhiya sa PEG or polysorbate?</td>
                                                    
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question2" name="question2" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question2" name="question2" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May malubhang alerhiya (severe allergin reaction) matapos ang unang dose ng bakuna?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question3" name="question3" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question3" name="question3" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May alerhiya sa pagkain, itlog, gamit? May hika (asthma)?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question4" name="question4" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question4" name="question4" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr id="question4Collapse" class="panel-collapse collapse">
                                                    <td>*Kung may alerhiya o hika, may problema ba sa pag-monitor sa pasyente ng 30 minuto?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question5" name="question5" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question5" name="question5" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May sakit kaugnay ng pagdudugo o sa kasalukuyan ay umiinom ng anti-coagulants (pampalabnaw ng dugo)?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question6" name="question6" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question6" name="question6" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr id="question6Collapse" class="panel-collapse collapse">
                                                    <td>*Kung may sakit kaugnay ng pagdudugo o kasalukuyang umiinom ng anti-coagulants (pampalabnaw ng dugo), mayroon bang problema sa pagkuha/paggamit ng gauge 23-25 na siringhilya (syring) para sa pagturok?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question7" name="question7" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question7" name="question7" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Mayroon ng kahit alinman sa sumusunod na sintomas? <br>
                                                        <table class="table table-sm table-hover" cellspacing="0" style="border: 0px; font-size:15px">
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="symptoms" id="symptom1" value="Lagnat / panginginig dahil sa lamig"> <span class="surveyLabel">Lagnat / panginginig dahil sa lamig</span></td>
                                                                    <td><input type="checkbox"  name="symptoms" value="Pagkapagod"> <span class="surveyLabel">Pagkapagod</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="symptoms" value="Sakit ng ulo"> <span class="surveyLabel">Sakit ng ulo</span></td>
                                                                    <td><input type="checkbox"  name="symptoms" value="Panghihina"> <span class="surveyLabel">Panghihina</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="symptoms" value="Ubo"> <span class="surveyLabel">Ubo</span></td>
                                                                    <td><input type="checkbox"  name="symptoms" value="Kawalan ng panlasa o pang-amoy"> <span class="surveyLabel">Kawalan ng panlasa o pang-amoy</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="symptoms" value="Sipon"> <span class="surveyLabel">Sipon</span></td>
                                                                    <td><input type="checkbox"  name="symptoms" value="Pagtatae"> <span class="surveyLabel">Pagtatae</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="symptoms" value="Pananakit ng lalamunan"> <span class="surveyLabel">Pananakit ng lalamunan</span></td>
                                                                    <td><input type="checkbox"  name="symptoms" value="Hirap sa paghinga"> <span class="surveyLabel">Hirap sa paghinga</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="symptoms" value="Pananakit ng kalamnan"> <span class="surveyLabel">Pananakit ng kalamnan</span></td>
                                                                    <td><input type="checkbox"  name="symptoms" value="Rashes"> <span class="surveyLabel">Rashes</span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question8" name="question8" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question8" name="question8" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr hidden>
                                                    <td class="choice"><input type="text" id="question9" name="question9" aria-hidden="true"></td>
                                                </tr>
                                                <tr>
                                                    <td>Kasulukuyang may SBP &#8805; 180 at/o dBP &#8805; 120, at may sintomas ng organ damage?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question19" name="question19" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question19" name="question19" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May exposure sa taong confirmed o suspect na kaso ng COVID-19 nitong nakaraang dalawang linggo (14 na araw)?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question10" name="question10" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question10" name="question10" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Dating ginamot para sa COVID-19 nitong nakaraang 90 na araw?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question11" name="question11" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question11" name="question11" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Nakakuha ng kahit anong bakuna nitong nakaraang 14 na araw o pinaplanong kumuha ng kahit anong bakuna sa susunod na 14 na araw matapos magpabakuna?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question12" name="question12" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question12" name="question12" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Ginamot o nakakuha ng convalescent plasma o monoclonal antibodies para sa COVID-19 nitong nakaraang 90 na araw?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question13" name="question13" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question13" name="question13" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Buntis?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question14" name="question14" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question14" name="question14" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr id="question14Collapse" class="panel-collapse collapse">
                                                    <td>*Kung buntis, nasa unang tatlong buwan ng pagbubuntis?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question15" name="question15" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question15" name="question15" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Mayroon ng kahit alinman sa sumusunod na sakit o kundisyon?<br>
                                                        <table class="table table-sm table-hover" cellspacing="0" style="border: 0px">
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="conditions" value="Human Immunodeficiency Virus(HIV)"> <span class="surveyLabel">Human Immunodeficiency Virus(HIV)</span></td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td><input type="checkbox"  name="conditions" value="Kanser(Cancer o Malignancy)"> <span class="surveyLabel">Kanser(Cancer o Malignancy)</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="conditions" value="Sumailalim sa organ transplant"> <span class="surveyLabel">Sumailalim sa organ transplant</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="conditions" value="Kasalukuyang umiinom ng steroids"> <span class="surveyLabel">Kasalukuyang umiinom ng steroids</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="conditions" value="Nakaratay na lang sa kama (bed-ridden), may sakit (terminal illness) na hindi tataas sa anim (6) na buwan ang taning"> <span class="surveyLabel">Nakaratay na lang sa kama (bed-ridden), may sakit (terminal illness) na hindi tataas sa anim (6) na buwan ang taning</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="conditions" value="May autoimmune disease"> <span class="surveyLabel">May autoimmune disease</span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question16" name="question16" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question16" name="question16" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr hidden>
                                                    <td class="choice"><input type="text" id="question17" name="question17" aria-hidden="true"></td>
                                                </tr>
                                                <tr id="question16Collapse" class="panel-collapse collapse">
                                                    <td>*If with mentioned condition, has presented medical clearance prior to vaccination day?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="question18" name="question18" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="question18" name="question18" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <!--Table body-->
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" id="qualified_patient_id" name="qualified_patient_id">
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <button type="submit" class="btn btn-success" id="save">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Patient Monitoring -->
    
    <!-- Modal For Viewing Details -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="view_patient">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title"> Verifying Patient</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body" style="max-height: calc(100vh - 300px); overflow-y: auto; background-color:#f7f7f7;">
                        <!-- Course Code -->
                        <div class="row">
                            <div style="margin:20px">
                                <div class="row">
                                    <div class="col-md-5">
                                        <img class="img-responsive img-thumbnail" id="show_avatar"/>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="">Full Name:</label>
                                            <b><p style="font-weight:bold; font-size:15" id="show_full_name"></p></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email Address:</label>
                                            <p id="show_email"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Mobile Number:</label>
                                            <p id="show_contact"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="">PhilHealth Number:</label>
                                            <p id="show_philhealth_id"></p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Sex:</label>
                                            <p id="show_sex"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Age:</label>
                                            <p id="show_dob"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Civil Status:</label>
                                            <p id="show_civilstatus"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Address:</label>
                                            <p id="show_address"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-border panel-primary">
                                    <a data-toggle="collapse" href="#otherInformationcollapse">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                Other Informations
                                                <i class="ti-angle-down"></i>
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="otherInformationcollapse" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <input type="hidden" name="regID" id="regID">
                                        <table class="table table-bordered table-sm table-hover" cellspacing="0" id="tbl_questions"
                                            width="100%" style="background-color: rgb(255, 255, 255);">
                                            <!--Table head-->
                                            <thead>
                                                <tr>
                                                    <th>Questions</th>
                                                    <th>Yes</th>
                                                    <th>No</th>
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

                            </div> 
                        </div>
                        <!-- End Course Code -->
                    </div>
                    <input type="hidden" id="incident_id" name="incident_id">
                <div class="modal-footer">
                    <div class="text-center" id="btnAppend"> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Viewing Details-->
    
    <!-- Modal For Viewing Vaccination Summary -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="vaccination_summary">
        <div class="modal-dialog modal-lg" role="document" style="width: 80%">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title"> Vaccination Summary</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body" style="max-height: calc(100vh - 300px); overflow-y: auto; background-color:#f7f7f7;">
                        <!-- Vaccination Summary -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4><span id="summary_patient" style="color:blue"></span></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="print_div">
                                <table id="summary_datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                    width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Dosage</th>
                                            <th>Vaccination Date</th>
                                            <th>Vaccine Manufacturer</th>
                                            <th>Batch Number</th>
                                            <th>Lot Number</th>
                                            <th>Vaccinator</th>
                                            <th>Date Encoded</th>
                                            <th>Encoded By</th>
                                            <th>Consent</th>
                                            <th>Reason for Refusal</th>
                                            <th>Deferral</th>
                                            <th>Action</th>
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
                        <!-- End Vaccination Summary -->
                    </div>
                    <input type="hidden" id="incident_id" name="incident_id">
                <div class="modal-footer">
                    <div class="text-center" id="btnAppend"> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Viewing Vaccination Summary-->
    
    <!-- Modal For Viewing Vaccination Summary -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="vaccination_other_summary">
        <div class="modal-dialog modal-lg" role="document" style="width: 50%">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title"> Vaccination Summary</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body" style="max-height: calc(100vh - 300px); overflow-y: auto; background-color:#f7f7f7;">
                        <!-- Vaccination Summary -->
                        <div class="row">
                            <div class="col-md-12">
                                <table id="other_information_table" class="table table-bordered table-sm table-hover" cellspacing="0"
                                    width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th>Questions</th>
                                            <th>Yes</th>
                                            <th>No</th>
                                        </tr>
                                    </thead>
                                    <!--Table head-->
                                    <!--Table body-->
                                    <tbody>
                                        <tr>
                                            <td>Age more than 16 years old?</td>
                                            <td class="choice"><input type="radio" id="question1" name="question1" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question1" name="question1" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>May alerhiya sa PEG or polysorbate?</td>
                                            <td class="choice"><input type="radio" id="question2" name="question2" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question2" name="question2" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Has severe allergic reaction after the 1st/2nd dose of the vaccine?</td>
                                            <td class="choice"><input type="radio" id="question3" name="question3" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question3" name="question3" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Has allergy to food, egg, medicines, and has asthma?</td>
                                            <td class="choice"><input type="radio" id="question4" name="question4" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question4" name="question4" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr id="question4Collapse" class="panel-collapse collapse">
                                            <td>*If with allergy or asthma, will the vaccinator able to monitor the patient for 30 minutes?</td>
                                            <td class="choice"><input type="radio" id="question5" name="question5" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question5" name="question5" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Has history of bleeding disorders or currently taking anti-coagulants?</td>
                                            <td class="choice"><input type="radio" id="question6" name="question6" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question6" name="question6" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr id="question6Collapse" class="panel-collapse collapse">
                                            <td>*If with bleeding history, is a gauge 23-25 syringe available for injection?</td>
                                            <td class="choice"><input type="radio" id="question7" name="question7" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question7" name="question7" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Does manifest any of the following symptoms? <br>
                                                <table class="table table-sm table-hover" cellspacing="0" style="border: 0px; font-size:15px">
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="checkbox"  name="symptoms" id="symptom1" value="Fever / Chills"> <span class="surveyLabel">Fever / Chills</span></td>
                                                            <td><input type="checkbox"  name="symptoms" value="Headache"> <span class="surveyLabel">Headache</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"  name="symptoms" value="Cough"> <span class="surveyLabel">Cough</span></td>
                                                            <td><input type="checkbox"  name="symptoms" value="Colds"> <span class="surveyLabel">Colds</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"  name="symptoms" value="Soar throat"> <span class="surveyLabel">Soar throat</span></td>
                                                            <td><input type="checkbox"  name="symptoms" value="Myalgia"> <span class="surveyLabel">Myalgia</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"  name="symptoms" value="Fatigue"> <span class="surveyLabel">Fatigue</span></td>
                                                            <td><input type="checkbox"  name="symptoms" value="Weakness"> <span class="surveyLabel">Weakness</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"  name="symptoms" value="Loss of smell / taste"> <span class="surveyLabel">Loss of smell / taste</span></td>
                                                            <td><input type="checkbox"  name="symptoms" value="Diarrhea"> <span class="surveyLabel">Diarrhea</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><input type="checkbox"  name="symptoms" value="Shortness of breath / difficulty in breathing"> <span class="surveyLabel">Shortness of breath / difficulty in breathing</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td class="choice"><input type="radio" id="question8" name="question8" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question8" name="question8" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr hidden>
                                            <td class="choice"><input type="text" id="question9" name="question9" aria-hidden="true"></td>
                                        </tr>
                                        <tr>
                                            <td>Has history of exposure to a confirmed or suspected COVID-19 case in the past 2 weeks?</td>
                                            <td class="choice"><input type="radio" id="question10" name="question10" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question10" name="question10" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Has previously treated for COVID-19 in the past 90 days?</td>
                                            <td class="choice"><input type="radio" id="question11" name="question11" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question11" name="question11" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Has received any vaccine in the past 2 weeks?</td>
                                            <td class="choice"><input type="radio" id="question12" name="question12" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question12" name="question12" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Has received convalescent plasma or monoclonal antibodies for COVID-19 in the past 90 days?</td>
                                            <td class="choice"><input type="radio" id="question13" name="question13" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question13" name="question13" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Pregnant?</td>
                                            <td class="choice"><input type="radio" id="question14" name="question14" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question14" name="question14" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr id="question14Collapse" class="panel-collapse collapse">
                                            <td>*If pregnant, 2nd trimester or 3rd?</td>
                                            <td class="choice"><input type="radio" id="question15" name="question15" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question15" name="question15" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr>
                                            <td>Does have any of the following: <br>
                                                <table class="table table-sm table-hover" cellspacing="0" style="border: 0px">
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="checkbox"  name="conditions" value="HIV"> <span class="surveyLabel">HIV</span></td>
                                                            <td><input type="checkbox"  name="conditions" value="Cancer / Malignancy"> <span class="surveyLabel">Cancer / Malignancy</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"  name="conditions" value="Underwent Transplant"> <span class="surveyLabel">Underwent Transplant</span></td>
                                                            <td><input type="checkbox"  name="conditions" value="Under Steriod Medication / Treatment"> <span class="surveyLabel">Under Steriod Medication / Treatment</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"  name="conditions" value="Bed ridden"> <span class="surveyLabel">Bed ridden</span></td>
                                                            <td><input type="checkbox"  name="conditions" value="Terminal Illness"> <span class="surveyLabel">Terminal Illness</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td class="choice"><input type="radio" id="question16" name="question16" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question16" name="question16" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                        <tr hidden>
                                            <td class="choice"><input type="text" id="question17" name="question17" aria-hidden="true"></td>
                                        </tr>
                                        <tr id="question16Collapse" class="panel-collapse collapse">
                                            <td>*If with mentioned condition, has presented medical clearance prior to vaccination day?</td>
                                            <td class="choice"><input type="radio" id="question18" name="question18" aria-hidden="true" value="01_Yes"></td>
                                            <td class="choice"><input type="radio" id="question18" name="question18" aria-hidden="true" value="02_No"></td>
                                        </tr>
                                    </tbody>
                                    <!--Table body-->
                                </table>
                            </div>
                        </div>
                        <!-- End Vaccination Summary -->
                    </div>
                    <input type="hidden" id="incident_id" name="incident_id">
                <div class="modal-footer">
                    <div class="text-center" id="btnAppend"> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Viewing Vaccination Summary-->

    
    @can('permission', 'updateVaccinationMonitoring')
    <!-- Modal Patient Monitoring -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="update_monitor_vaccination">
        <div class="modal-dialog modal-lg" role="document">
            <form id="update_monitor_form">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Patient Monitoring</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <div class="row">
                            <!-- Fullname -->
                            <div class="form-group col-md-12">
                                <label for="fullname">Patient Fullname</label>
                                <input type="text" class="form-control" name="edit_fullname" id="edit_fullname"
                                    placeholder="Enter Fullname" disabled>
                            </div>
                            <!-- End Fullname -->
                        </div>    
                    
                        <div class="row">
                            <!-- Dosage -->
                            <div class="form-group col-md-6">
                                <label for="dosage">Dosage</label>
                                <select type="text" class="form-control selectpicker" name="edit_dosage" id="edit_dosage"
                                    placeholder="Enter Dosage">
                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                                </select>
                            </div>
                            <!-- End Dosage -->
                            
                            <!-- Vaccination Date -->
                            <div class="form-group col-md-6">
                                <label for="vaccination_date">Vaccination Date</label>
                                <input type="text" class="form-control datetimepicker valid"
                                    id="edit_vaccination_date" name="edit_vaccination_date"
                                    placeholder="Date of Vaccination" aria-invalid="false" max="9999-12-31">
                            </div>
                            <!-- End Vaccination Date -->
                        </div>
                        
                        <div class="row">
                            <!-- Vaccine Manufacturer -->
                            <div class="form-group col-md-6">
                                <label for="vaccine_manufacturer">Vaccine Manufacturer</label>
                                <select type="text" class="form-control selectpicker" name="vaccine_manufacturer" id="edit_vaccine_manufacturer"
                                    placeholder="Enter Vaccine Manufacturer">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                            <!-- End Vaccine Manufacturer -->
                            
                            <!-- Batch Number -->
                            <div class="form-group col-md-6">
                                <label for="batch_number">Batch Number</label>
                                <input type="text" class="form-control" name="edit_batch_number" id="edit_batch_number"
                                    placeholder="Enter Batch Number">
                            </div>
                            <!-- End Batch Number -->
                        </div>
                        
                        <div class="row">
                            <!-- Lot Number -->
                            <div class="form-group col-md-6">
                                <label for="lot_number">Lot Number</label>
                                <input type="text" class="form-control" name="edit_lot_number" id="edit_lot_number"
                                    placeholder="Enter Lot Number">
                            </div>
                            <!-- End Lot Number -->
                            
                            <!-- Vaccinator -->
                            <div class="form-group col-md-6">
                                <label for="vaccinator">Vaccinator</label>
                                <select class="selectpicker form-control" data-live-search="true"
                                    name="vaccinator" id="edit_vaccinator">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                             </div>
                             <!-- End Vaccinator -->
                        </div>
                        
                        <div class="row">
                            <!-- Consent -->
                            <div class="form-group col-md-6">
                                <label for="consent">Consent</label>
                                <select type="text" class="form-control selectpicker" name="edit_consent" id="edit_consent" placeholder="Enter Consent">
                                    <option value="YES">Yes</option>
                                    <option value="NO">No</option>
                                </select>
                            </div>
                            <!-- End Consent -->
                        </div>
                        
                        <div class="row">
                            <!-- Reason for Refusal -->
                            <div class="form-group col-md-12">
                                <label for="reason_for_refusal">Reason for Refusal</label>
                                <textarea type="text" class="form-control" name="edit_reason_for_refusal" id="edit_reason_for_refusal"
                                    placeholder="Enter Reason for Refusal" disabled></textarea>
                            </div>
                            <!-- End Reason for Refusal -->
                        </div>
                        
                        <div class="row">
                            <!-- Deferral -->
                            <div class="form-group col-md-12">
                                <label for="deferral">Deferral <i style="font-weight: normal; font-size:11px">(Optional)</i></label>
                                <textarea type="text" class="form-control" name="edit_deferral" id="edit_deferral"
                                    placeholder="Enter Deferral"></textarea>
                            </div>
                            <!-- End Deferral -->
                        </div>
                        
                        <div>
                            <div class="panel panel-border panel-primary">
                                <a data-toggle="collapse" href="#updatemonitorOtherInformationcollapse">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            Other Informations
                                            <i class="ti-angle-down"></i>
                                        </h4>
                                    </div>
                                </a>
                                <div id="updatemonitorOtherInformationcollapse" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <input type="hidden" name="regID" id="regID">
                                        <table class="table table-bordered table-sm table-hover surveyLabel" cellspacing="0" id="tbl_monitoring_questions"
                                            width="100%" style="background-color: rgb(255, 255, 255);">
                                            <!--Table head-->
                                            <thead>
                                                <tr>
                                                    <th style="width: 70%; font-weight: bold;">Questions</th>
                                                    <th class="choice" style="font-weight: bold">Remarks</th>
                                                </tr>
                                            </thead>
                                            <!--Table head-->
    
                                            <!--Table body-->
                                            <tbody>
                                                <tr>
                                                    <td>Edad ay mas mababa sa 18 o higit sa 59 na taong gulang?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question1" name="edit_question1" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question1" name="edit_question1" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May alerhiya sa PEG or polysorbate?</td>
                                                    
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question2" name="edit_question2" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question2" name="edit_question2" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May malubhang alerhiya (severe allergin reaction) matapos ang unang dose ng bakuna?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question3" name="edit_question3" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question3" name="edit_question3" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May alerhiya sa pagkain, itlog, gamit? May hika (asthma)?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question4" name="edit_question4" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question4" name="edit_question4" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr id="edit_question4Collapse" class="panel-collapse collapse">
                                                    <td>*Kung may alerhiya o hika, may problema ba sa pag-monitor sa pasyente ng 30 minuto?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question5" name="edit_question5" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question5" name="edit_question5" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>May sakit kaugnay ng pagdudugo o sa kasalukuyan ay umiinom ng anti-coagulants (pampalabnaw ng dugo)?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question6" name="edit_question6" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question6" name="edit_question6" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr id="edit_question6Collapse" class="panel-collapse collapse">
                                                    <td>*Kung may sakit kaugnay ng pagdudugo o kasalukuyang umiinom ng anti-coagulants (pampalabnaw ng dugo), mayroon bang problema sa pagkuha/paggamit ng gauge 23-25 na siringhilya (syring) para sa pagturok?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question7" name="edit_question7" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question7" name="edit_question7" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Mayroon ng kahit alinman sa sumusunod na sintomas? <br>
                                                        <table class="table table-sm table-hover" cellspacing="0" style="border: 0px; font-size:15px">
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Lagnat / panginginig dahil sa lamig"> <span class="surveyLabel">Lagnat / panginginig dahil sa lamig</span></td>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Pagkapagod"> <span class="surveyLabel">Pagkapagod</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Sakit ng ulo"> <span class="surveyLabel">Sakit ng ulo</span></td>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Panghihina"> <span class="surveyLabel">Panghihina</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Ubo"> <span class="surveyLabel">Ubo</span></td>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Kawalan ng panlasa o pang-amoy"> <span class="surveyLabel">Kawalan ng panlasa o pang-amoy</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Sipon"> <span class="surveyLabel">Sipon</span></td>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Pagtatae"> <span class="surveyLabel">Pagtatae</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Pananakit ng lalamunan"> <span class="surveyLabel">Pananakit ng lalamunan</span></td>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Hirap sa paghinga"> <span class="surveyLabel">Hirap sa paghinga</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Pananakit ng kalamnan"> <span class="surveyLabel">Pananakit ng kalamnan</span></td>
                                                                    <td><input type="checkbox"  name="edit_symptoms" value="Rashes"> <span class="surveyLabel">Rashes</span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question8" name="edit_question8" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question8" name="edit_question8" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Kasulukuyang may SBP ≥ 180 at/o dBP ≥ 120, at may sintomas ng organ damage?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question19" name="edit_question19" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question19" name="edit_question19" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr hidden>
                                                    <td class="choice"><input type="text" id="edit_question9" name="edit_question9" aria-hidden="true"></td>
                                                </tr>
                                                <tr>
                                                    <td>May exposure sa taong confirmed o suspect na kaso ng COVID-19 nitong nakaraang dalawang linggo (14 na araw)?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question10" name="edit_question10" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question10" name="edit_question10" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Dating ginamot para sa COVID-19 nitong nakaraang 90 na araw?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question11" name="edit_question11" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question11" name="edit_question11" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Nakakuha ng kahit anong bakuna nitong nakaraang 14 na araw o pinaplanong kumuha ng kahit anong bakuna sa susunod na 14 na araw matapos magpabakuna?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question12" name="edit_question12" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question12" name="edit_question12" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Ginamot o nakakuha ng convalescent plasma o monoclonal antibodies para sa COVID-19 nitong nakaraang 90 na araw?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question13" name="edit_question13" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question13" name="edit_question13" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Buntis?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question14" name="edit_question14" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question14" name="edit_question14" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr id="edit_question14Collapse" class="panel-collapse collapse">
                                                    <td>*Kung buntis, nasa unang tatlong buwan ng pagbubuntis?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question15" name="edit_question15" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question15" name="edit_question15" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Mayroon ng kahit alinman sa sumusunod na sakit o kundisyon?<br>
                                                        <table class="table table-sm table-hover" cellspacing="0" style="border: 0px">
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_conditions" value="Human Immunodeficiency Virus(HIV)"> <span class="surveyLabel">Human Immunodeficiency Virus(HIV)</span></td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_conditions" value="Kanser(Cancer o Malignancy)"> <span class="surveyLabel">Kanser(Cancer o Malignancy)</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_conditions" value="Sumailalim sa organ transplant"> <span class="surveyLabel">Sumailalim sa organ transplant</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_conditions" value="Kasalukuyang umiinom ng steroids"> <span class="surveyLabel">Kasalukuyang umiinom ng steroids</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_conditions" value="Nakaratay na lang sa kama (bed-ridden), may sakit (terminal illness) na hindi tataas sa anim (6) na buwan ang taning"> <span class="surveyLabel">Nakaratay na lang sa kama (bed-ridden), may sakit (terminal illness) na hindi tataas sa anim (6) na buwan ang taning</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="checkbox"  name="edit_conditions" value="May autoimmune disease"> <span class="surveyLabel">May autoimmune disease</span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question16" name="edit_question16" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question16" name="edit_question16" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr hidden>
                                                    <td class="choice"><input type="text" id="edit_question17" name="edit_question17" aria-hidden="true"></td>
                                                </tr>
                                                <tr id="edit_question16Collapse" class="panel-collapse collapse">
                                                    <td>*If with mentioned condition, has presented medical clearance prior to vaccination day?</td>
                                                    <td class="choice">
                                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q1" data-toggle="buttons">
                                                            <label class="btn btn-success" style="margin-left: 5px">
                                                                <input type="radio" id="edit_question18" name="edit_question18" aria-hidden="true" value="02_No">NO 
                                                            </label>
                                                            <label class="btn btn-success">
                                                                <input type="radio" id="edit_question18" name="edit_question18" aria-hidden="true" value="01_Yes"> YES
                                                            </label> 
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <!--Table body-->
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" id="edit_qualified_patient_id" name="edit_qualified_patient_id">
                        <input type="hidden" id="survey_id" name="survey_id">
                        <input type="hidden" id="monitoring_id" name="monitoring_id">
                        <input type="hidden" id="reason_for_update" name="reason_for_update">
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <button type="submit" class="btn btn-success" id="save">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Patient Monitoring -->
    @endcan
    
    <!-- Modal for VAS LINE Information -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="vas_line_info_modal">
        <div class="modal-dialog modal-lg" role="document" style="width: 80%">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title"> VAS LINE INFORMATION</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body" style="max-height: calc(100vh - 300px); overflow-y: auto; background-color:#f7f7f7;">
                        <!-- Vaccination Summary -->
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="copyTextId">
                                <button class="btn btn-primary" onclick="copyToClipboard()">Copy to clipboard</button>
                            </div>
                            <br>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="print_div">
                                <table id="vas_datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                    width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th>CATEGORY</th>
                                            <th>UNIQUE_PERSON_ID</th>
                                            <th>PWD</th>
                                            <th>Indigenous Member</th>
                                            <th>LAST_NAME</th>
                                            <th>FIRST_NAME</th>
                                            <th>MIDDLE_NAME</th>
                                            <th>SUFFIX</th>
                                            <th>CONTACT_NO</th>
                                            <th>REGION</th>
                                            <th>PROVINCE</th>
                                            <th>MUNI_CITY</th>
                                            <th>BARANGAY</th>
                                            <th>SEX</th>
                                            <th>BIRTHDATE</th>
                                            <th>DEFERRAL</th>
                                            <th>REASON_FOR_DEFERRAL</th>
                                            <th>VACCINATION_DATE</th>
                                            <th>VACCINE_MANUFACTURER_NAME</th>
                                            <th>BATCH_NUMBER</th>
                                            <th>LOT_NO</th>
                                            <th>BAKUNA_CENTER_CBCR_ID</th>
                                            <th>VACCINATOR_NAME</th>
                                            <th>1ST_DOSE</th>
                                            <th>2ND_DOSE</th>
                                            <th>Adverse Event</th>
                                            <th>Adverse Event Condition</th>
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
                        <!-- End Vaccination Summary -->
                    </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for VAS LINE Information-->
@endsection

@section('js')
<script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
<script>
    $(document).ready(function () {
        let copyToClipboard = [];
        
        $('#update_monitor_vaccination').on('shown.bs.modal', function() {
            $(document).off('focusin.modal');
        });

        let summary_datatable = $('#summary_datatable').DataTable();
    
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "language": {
                processing: '<i style="width: 50px;" class="fa fa-spinner fa-spin fa-lg fa-fw"></i><b> Processing....</b>',
                "sSearch": " <b style='color:red;'><i>(Fistname Lastname) e.g. juan de la cruz</i></b><br>Press Enter to search:"
            },
            "ajax":{
                "url": '{{ route('vaccination-monitoring.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 2 ] }, 
            ],
            initComplete: function() {
                $('.dataTables_filter input').unbind();
                $('.dataTables_filter input').bind('keyup', function(e){
                    var code = e.keyCode || e.which;
                    if (code == 13) {
                        datatable.search(this.value).draw();
                    }
                });
            },	 	 
        });
        
        //get vaccinators
        $.ajax({
            url:'{{ route('vaccinator.find-all-vaccinator') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    let fullname = "";
                    if(response[index].last_name) fullname += response[index].last_name;
                    if(response[index].suffix) 
                        if( response[index].suffix != "NA")
                            fullname += " " + response[index].suffix;
                    fullname += ", ";
                    if(response[index].first_name) fullname += response[index].first_name + " ";
                    if(response[index].middle_name && response[index].middle_name != "NA") fullname += response[index].middle_name[0] + ".";
                    $('[name="vaccinator"]').append('<option value='+response[index].id+'>'+ fullname+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
        
        //get vaccine categories
        $.ajax({
            url:'{{ route('vaccine-category.find-all-vaccine') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="vaccine_manufacturer"]').append('<option value='+response[index].id+'>'+ response[index].vaccine_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
        
        $('label [name="question4"]').change(function(){
            if($('[name="question4"]:first').is(':checked')){
                $('#question4Collapse').hide();
                $('[name="question5"]:first').prop('checked', true);
            }else{
                $('[name="question5"]').prop('checked', false);
                $('#question4Collapse').show();
            }
        });
        
        $('[name="question6"]').change(function(){
            if($('[name="question6"]:first').is(':checked')){
                $('#question6Collapse').hide();
                $('[name="question7"]:first').prop('checked', true);
            }else{
                $('[name="question7"]').prop('checked', false);
                $('#question6Collapse').show();
            }
        });
        
        $('[name="question8"]').change(function(){
            if($('[name="question8"]:first').is(':checked')){
                $('[name="symptoms"]').prop("disabled", true);
                $('[name="symptoms"]').prop("checked", false);
            }else{
                $('[name="symptoms"]').prop("disabled", false);
            }
        });
        
        $('[name="question14"]').change(function(){
            if($('[name="question14"]:first').is(':checked')){
                $('#question14Collapse').hide();
                $('[name="question15"]:first').prop('checked', true);
            }else{
                $('[name="question15"]').prop('checked', false);
                $('#question14Collapse').show();
            }
        });
        
        $('[name="question16"]').change(function(){
            if($('[name="question16"]:first').is(':checked')){
                $('#question16Collapse').hide();
                $('[name="conditions"]').prop("disabled", true);
                $('[name="conditions"]').prop("checked", false);
                $('[name="question18"]:first').prop('checked', true);
            }else{
                $('[name="question18"]').prop('checked', false);
                $('[name="conditions"]').prop("disabled", false);
                $('#question16Collapse').show();
            }
        });
        
        //Save Monitoring
        $("#monitor_form").validate({
            rules: {
                dosage: {
                    required: true
                },
                vaccination_date: {
                    required: true
                },
                vaccine_manufacturer: {
                    required: true
                },
                batch_number: {
                    required: true
                },
                lot_number: {
                    required: true
                },
                vaccinator: {
                    required: true
                },
                consent: {
                    required: true
                }
            },
            submitHandler: function (form) {
            
                let monitoring_survey = true;
                let symptoms = "";
                let conditions = "";
                if($('[name="question8"]').is(':checked')){
                    if(!$('[name="question8"]:first').is(':checked')){
                        $('input[name="symptoms"]:checked').each(function() {
                            symptoms += this.value + ", ";
                        });
                        symptoms = symptoms.slice(0, -2);
                        $("#question9").val(symptoms);
                        monitoring_survey = (symptoms == "") ? false : true;
                    }
                }
                if(monitoring_survey == true){
                    if($('[name="question16"]').is(':checked')){
                        if(!$('[name="question16"]:first').is(':checked')){
                            $('input[name="conditions"]:checked').each(function() {
                                conditions += this.value + ", ";
                            });
                            conditions = conditions.slice(0, -2);
                            $("#question17").val(conditions);
                            monitoring_survey = (conditions == "") ? false : true;
                        }
                    }
                    if(monitoring_survey == true){
                        for(let counter = 1; counter <= 19; counter++){
                            if(counter != 9 && counter != 17){
                                if(counter == 5 || counter == 7 || counter == 15){
                                    //for question 5 and 15
                                    if($('#question' + (counter-1)).is(':checked'))
                                        monitoring_survey = ($("#question" + counter + ":checked").length < 1) ? false : true;
                                }else if(counter == 18){
                                    //for question 18
                                    if($('#question' + (counter-2)).is(':checked'))
                                        monitoring_survey = ($("#question" + counter + ":checked").length < 1) ? false : true;
                                }else{
                                    if($("#question" + counter + ":checked").length < 1){
                                        monitoring_survey = false;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                
                if($("#consent").val() == "NO"){ 
                    if($("#reason_for_refusal").val() == ""){
                        monitoring_survey = false;
                        $("#reason_for_refusal").addClass("error");
                        $("#reason_for_refusal").append('<label id="lot_number-error" class="error" for="lot_number">This field is required.</label>');
                    }else{
                        monitoring_survey = true;
                    }
                }
                
                if(monitoring_survey == true){
                    Swal.fire({
                        title: 'Save Now?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!',
                        html: "<b>Monitor Patient",
                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '{{ route('vaccination-monitoring.store') }}',
                                type: "POST",
                                data: $('#monitor_form').serialize(),
                                dataType: "JSON",
                                beforeSend: function(){
                                    processObject.showProcessLoader();
                                },
                                success: function (data) {
                                    if (data.success) {
                                        $('#monitor_vaccination').modal('hide');
                                        $("#monitor_form")[0].reset();
                                        $('#monitorOtherInformationcollapse').collapse("hide");
                                        $("#vaccinator").val(0);
                                        $('.selectpicker').selectpicker('refresh');
                                        
                                        for(let index = 1; index <=19; index++){
                                            $('[name="question' + index + '"]').prop('checked', false);
                                        }
                                        
                                        swal.fire({
                                            title: "Save!",
                                            text: "Successfully!",
                                            type: "success",
                                            html: "<b>Monitor Patient",
                                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                        })
                                        datatable.ajax.reload( null, false );
                                    } else {
                                        swal.fire({
                                            title: "Oops! something went wrong.",
                                            html: "<b>" + data.messages +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                            type: "error",
                                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                        });
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                        type: "error",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    });
                                },
                                complete: function(){
                                    processObject.hideProcessLoader();
                                },
                            });
                        }
                    })
                }else{
                    Swal.fire({
                        title: 'Please complete the required fields!',
                        type: 'warning'
                    });
                }
                
            },
        });
        
        $("#consent").change(function(){
            if($("#consent").val() == "YES"){
                $("#reason_for_refusal").val("");
                $("#reason_for_refusal").prop("disabled", true);
            }else{
                $("#reason_for_refusal").prop("disabled", false);
            }
        });
        
        //Show other details
        $('#summary_datatable tbody').on('click', 'td.details-control', function () {
        
            let summary_datatable = $('#summary_datatable').DataTable();
            var tr = $(this).closest('tr');
            var row = summary_datatable.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });
    });
    
    //View other information
    const format = (d) => {
        var output = "";
        output += `<div class="col-md-4 col-md-offset-4">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th style="width: 90%;">Questions</th>
                            <th>Remarks</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Edad ay mas mababa sa 18 taong gulang?</td>
                                <td style="text-align:center">`;
        
        output += (d.data.question_1 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>   
                            <tr>
                                <td>May alerhiya sa PEG or polysorbate?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_2 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>May malubhang alerhiya (severe allergin reaction) matapos ang unang dose ng bakuna?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_3 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>May alerhiya sa pagkain, itlog, gamit? May hika (asthma)?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_4 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Kung may alerhiya o hika, may problema ba sa pag-monitor sa pasyente ng 30 minuto?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_5 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>May sakit kaugnay ng pagdudugo o sa kasalukuyan ay umiinom ng anti-coagulants (pampalabnaw ng dugo)?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_6 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Kung may sakit kaugnay ng pagdudugo o kasalukuyang umiinom ng anti-coagulants (pampalabnaw ng dugo), mayroon bang problema sa pagkuha/paggamit ng gauge 23-25 na siringhilya (syring) para sa pagturok?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_7 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Mayroon ng kahit alinman sa sumusunod na sintomas? Lagnat / panginginig dahil sa lamig, Sakit ng ulo, Ubo, Sipon, Pananakit ng lalamunan, Pananakit ng kalamnan, Pagkapagod, Panghihina, Kawalan ng panlasa o pang-amoy, Pagtatae, Hirap sa paghinga, Rashes</td>
                                <td style="text-align:center">`;
        output += (d.data.question_8 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td colspan="2">Sintomas : `;
        d.data.question_9 = (d.data.question_9 == null) ? "N/A" : d.data.question_9;
        output += d.data.question_9;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Kasulukuyang may SBP &#8805; 180 at/o dBP &#8805; 120, at may sintomas ng organ damage?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_19 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        
        output += ` </td>
                            </tr>
                            <tr>
                                <td>May exposure sa taong confirmed o suspect na kaso ng COVID-19 nitong nakaraang dalawang linggo (14 na araw)?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_10 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Dating ginamot para sa COVID-19 nitong nakaraang 90 na araw?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_11 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Nakakuha ng kahit anong bakuna nitong nakaraang 14 na araw o pinaplanong kumuha ng kahit anong bakuna sa susunod na 14 na araw matapos magpabakuna?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_12 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Ginamot o nakakuha ng convalescent plasma o monoclonal antibodies para sa COVID-19 nitong nakaraang 90 na araw?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_13 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Buntis?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_14 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Kung buntis, nasa unang tatlong buwan ng pagbubuntis?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_15 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td>Mayroon ng kahit alinman sa sumusunod na sakit o kundisyon? Human Immunodeficiency Virus(HIV), Kanser(Cancer o Malignancy), Sumailalim sa organ transplant, Kasalukuyang umiinom ng steroids, Nakaratay na lang sa kama (bed-ridden), may sakit (terminal illness) na hindi tataas sa anim (6) na buwan ang taning, may autoimmune disease</td>
                                <td style="text-align:center">`;
        output += (d.data.question_16 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            <tr>
                                <td colspan="2"> Sakit / Kundisyon : `;
        d.data.question_17 = (d.data.question_17 == null) ? "N/A" : d.data.question_17;
        output += d.data.question_17;
        output += ` </td>
                            </tr>
                                <td>Kung may alinman sa mga nabanggit, tutol ba ang doktor sa pagbabakuna sa dalang medical clearane bago ang araw ng pagbabakuna?</td>
                                <td style="text-align:center">`;
        output += (d.data.question_18 == "01_Yes") ? `<i class="fa fa-check text-success" aria-hidden="true"></i>` : `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
        output += ` </td>
                            </tr>
                            </tr>
                        </tbody>
                    </table>
                </div>`;
        return output;
    }
    
    //monitor patient
    const monitor = (id) =>{
    
        $('.btn-success').removeClass('active');
        $('label.error').hide();
        $('.error').removeClass('error');
        
        $("#monitor_form")[0].reset();
        $('#monitorOtherInformationcollapse').collapse("hide");
        $("#vaccinator").val(0);
        $('.selectpicker').selectpicker('refresh');
        
        $.ajax({
            url: '/covid19vaccine/vaccination-monitoring/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $("#dosage option:contains('1')").prop("disabled", false);
                $("#dosage option:contains('2')").prop("disabled", false);
                $("#dosage").val(1);
                $("#qualified_patient_id").val(data.patient.id);
                
                if(data.vaccine == "" && data.vaccine2 == ""){
                    $("#vaccineAcquired2").text("");
                    $("#vaccineAcquired").text("NO DOSAGE ACQUIRED");
                }else{
                    if(data.vaccine != ""){
                        $("#vaccineAcquired").text("FIRST DOSE ACQUIRED : " + data.vaccine + " - " + data.vaccineDate);
                    }else{
                        $("#vaccineAcquired").text("");
                    }
                    if(data.vaccine2 != ""){
                        $("#vaccineAcquired2").text("SECOND DOSE ACQUIRED: " + data.vaccine2 + " - " + data.vaccineDate2);
                    }else{
                        $("#vaccineAcquired2").text("");
                    }
                }
                
                let fullname = "";
                if(data.patient.last_name) fullname += data.patient.last_name;
                if(data.patient.suffix) 
                    if( data.patient.suffix != "NA")
                        fullname += " " + data.patient.suffix;
                fullname += ", ";
                if(data.patient.first_name) fullname += data.patient.first_name + " ";
                if(data.patient.middle_name && data.patient.middle_name != "NA") fullname += data.patient.middle_name[0] + ".";
                
                $('#fullname').val(fullname);
                if(data.checkSecondDose == true){
                   
                    $("#dosage option:contains('1')").attr("disabled","disabled");
                    $("#dosage").val(2);
                }else{
                    $("#dosage option:contains('2')").attr("disabled","disabled");
                    $("#dosage").val(1);
                }
                $('.selectpicker').selectpicker('refresh');
                $("#monitor_vaccination").modal("show");
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
            complete: function(){
                processObject.hideProcessLoader();
            },
        });
    }
    
    //view patient data
    const viewPatient = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                validateAction(data,"view","Patient Details");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });
    }
    
    const validateAction = (data,action,modalTitle) =>{
        $("#view_patient").modal("show");
               $('.modal-title').html('<i class="fa fa-user-md" aria-hidden="true"></i> '+ modalTitle);
               $('#show_avatar').attr('src','../../../images/' + data[0].image);
               if(action == "validate"){
                   $('#btnAppend').empty();
                   $('#btnAppend').append('<button id="btnRegistrationApproval" name="btnRegistrationApproval" onclick="registrationApproval('+data[0].id+')" class="btn btn-danger"><i class="fa fa-check" aria-hidden="true"></i> Registration Approval!</button> ');
                }else{
                    $('#btnAppend').empty();
                }
                $("#btnRegistrationApproval").attr('value',data[0].id);
                let fullname = "";
                if(data[0].last_name) fullname += data[0].last_name + " ";
                if(data[0].affiliation) fullname += data[0].affiliation;
                fullname += ", ";
                if(data[0].first_name) fullname += data[0].first_name + " ";
                if(data[0].middle_name && data[0].middle_name != "NA") fullname += data[0].middle_name + " ";
                $('#show_full_name').text(fullname);
                if(data[0].contact_number)$('#show_contact').text(data[0].contact_number);
                if(data[0].email)$('#show_email').text(data[0].email);
                if(data[0].philhealth_number)$('#show_philhealth_id').text(data[0].philhealth_number);
                if(data[0].home_address)$('#show_address').text(data[0].home_address);

                var today = new Date();
                var birthDate = new Date(data[0].date_of_birth);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                if(data[0].date_of_birth)$('#show_dob').text(age+ ' years old');

                if(data[0].civil_status != null){
                    var civilstatus=''; 
                    if(data[0].civil_status == '01_SINGLE')
                        civilstatus='Single';
                    else if(data[0].civil_status == '02_MARRIED')
                        civilstatus='Married';
                    else if(data[0].civil_status == '03_WIDOW/WIDOWER')
                        civilstatus='Widow/Widower';
                    else if(data[0].civil_status == '04_SEPARATED/ANNULLED')
                        civilstatus='Separated/Annulled';
                    else
                        civil_status='Living with Partner';

                    $('#show_civilstatus').text(civilstatus);
                }
                else{
                    $('#show_civilstatus').text(" ");
                }
                if(data[0].sex != null){
                    var sex=''; 
                    if(data[0].sex == "01_FEMALE") sex='Male';
                    else if(data[0].sex == "02_MALE") sex='Female';
                    $('#show_sex').text(sex);
                }
                else
                    $('#show_sex').text(" "); 
                drawTableBody(data[0]);
    }
    
    //Draw Datatable
    const drawTableBody = (data) =>{
        $("#tbl_questions tbody").empty();
        const newRowContent = ` <tr>
                                    <td>Breast feeding/Pregnant</td>
                                    <td><i class="${ (data.question_1 == 'YES')? 'fa fa-check text-success': 'fa fa-times text-danger' }" aria-hidden="true"></i></td>
                                    <td><i class="${ (data.question_1 == 'YES')? 'fa fa-times text-danger': 'fa fa-check text-success' }" aria-hidden="true"></i></td>
                                </tr>
                                <tr>
                                    <td>Directly in Interaction with COVID Patient</td>
                                    <td><i class="${ (data.question_10 == 'YES')? 'fa fa-check text-success': 'fa fa-times text-danger' }" aria-hidden="true"></i></td>
                                    <td><i class="${ (data.question_10 == 'YES')? 'fa fa-times text-danger': 'fa fa-check text-success' }" aria-hidden="true"></i></td>
                                </tr>
                                <tr>
                                    <td>With history of COVID-19 infection</td>
                                    <td><i class="${ (data.question_6 == 'YES')? 'fa fa-check text-success': 'fa fa-times text-danger' }" aria-hidden="true"></i></td>
                                    <td><i class="${ (data.question_6 == 'YES')? 'fa fa-times text-danger': 'fa fa-check text-success' }" aria-hidden="true"></i></td>
                                </tr>
                                ${ (data.question_6 == 'YES')? '<tr><td colspan="3">Date of infections:'+data.question_7+'</td></tr>': '""' }
                                <tr>
                                    <td>With Allergy</td>
                                    <td><i class="${ (data.question_2 == 'YES')? 'fa fa-check text-success': 'fa fa-times text-danger' }" aria-hidden="true"></i></td>
                                    <td><i class="${ (data.question_2 == 'YES')? 'fa fa-times text-danger': 'fa fa-check text-success' }" aria-hidden="true"></i></td>
                                </tr>
                                ${ (data.question_2 == 'YES')? '<tr><td colspan="3">Alergies: '+data.question_3+'</td></tr>': '""' }
                                <tr>
                                    <td>With Comorbidities</td>
                                    <td><i class="${ (data.question_4 == 'YES')? 'fa fa-check text-success': 'fa fa-times text-danger' }" aria-hidden="true"></i></td>
                                    <td><i class="${ (data.question_4 == 'YES')? 'fa fa-times text-danger': 'fa fa-check text-success' }" aria-hidden="true"></i></td>
                                </tr>
                                ${ (data.question_4 == 'YES')? '<tr><td colspan="3">Comorbidities: '+data.question_5+'</td></tr>': '""' }
                                <tr>
                                    <td>Provide Electronic Informed Consent?</td>
                                    <td><i class="${ (data.question_9 == 'YES')? 'fa fa-check text-success': 'fa fa-times text-danger' }" aria-hidden="true"></i></td>
                                    <td><i class="${ (data.question_9 == 'YES')? 'fa fa-times text-danger': 'fa fa-check text-success' }" aria-hidden="true"></i></td>
                                </tr>`;
        $("#tbl_questions tbody").append(newRowContent);
    }
    
    //view vaccination summary
    const viewSummary = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination-monitoring/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                let fullname = "";
                if(data.patient.last_name) fullname += data.patient.last_name;
                if(data.patient.suffix) 
                    if( data.patient.suffix != "NA")
                        fullname += " " + data.patient.suffix;
                fullname += ", ";
                if(data.patient.first_name) fullname += data.patient.first_name + " ";
                if(data.patient.middle_name && data.patient.middle_name != "NA") fullname += data.patient.middle_name[0] + ".";
                
                $('#summary_patient').text(fullname);        
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
            complete: function(){
                processObject.hideProcessLoader();
            },
        });
        
        $('#summary_datatable').DataTable().clear().destroy();
        summary_datatable = $('#summary_datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "searching":false,
            "bInfo": false,
            "lengthChange": false,
            "ajax":{
                "url": '/covid19vaccine/vaccination-monitoring/find-summary/' + id,
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                { "data": "dosage" },
                { "data": "vaccination_date" },
                { "data": "vaccine_name" },
                { "data": "batch_number" },
                { "data": "lot_number" },
                { "data": "vaccinator" },
                { "data": "date_encoded" },
                { "data": "encoded_by" },
                { "data": "consent" },
                { "data": "reason_for_refusal" },
                { "data": "deferral" },
                { "data": "action" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 10 ] }, 
            ]	 	 
        });
        $("#vaccination_summary").modal("show");
    }
    
    //summary other information
    const viewOtherInformation = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination-monitoring/summary-other-information/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $("#vaccination_summary").modal("hide");
                $("#vaccination_other_summary").modal("show");
                 
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
            complete: function(){
                processObject.hideProcessLoader();
            },
        });
        
    }
    
    $('#vaccination_other_summary').on('hidden.bs.modal', function (e) {
        $("#vaccination_summary").modal("show");
    });
    
    @can('permission', 'updateVaccinationMonitoring')
    // ========================================Update Monitoring===========================================
    
    $('[name="edit_question4"]').change(function(){
        if($('[name="edit_question4"]:first').is(':checked')){
            $('#edit_question4Collapse').hide();
            $('[name="edit_question5"]:first').prop('checked', true);
        }else{
            $('[name="edit_question5"]').prop('checked', false);
            $('#edit_question4Collapse').show();
        }
    });
    
    $('[name="edit_question6"]').change(function(){
        if($('[name="edit_question6"]:first').is(':checked')){
            $('#edit_question6Collapse').hide();
            $('[name="edit_question7"]:first').prop('checked', true);
        }else{
            $('[name="edit_question7"]').prop('checked', false);
            $('#edit_question6Collapse').show();
        }
    });

    
    $('[name="edit_question8"]').change(function(){
        if($('[name="edit_question8"]:first').is(':checked')){
            $('[name="edit_symptoms"]').prop("disabled", true);
            $('[name="edit_symptoms"]').prop("checked", false);
        }else{
            $('[name="edit_symptoms"]').prop("disabled", false);
        }
    });
    
    $('[name="edit_question14"]').change(function(){
        if($('[name="edit_question14"]:first').is(':checked')){
            $('#edit_question14Collapse').hide();
            $('[name="edit_question15"]:first').prop('checked', true);
        }else{
            $('[name="edit_question15"]').prop('checked', false);
            $('#edit_question14Collapse').show();
        }
    });
    
    $('[name="edit_question16"]').change(function(){
        if($('[name="edit_question16"]:first').is(':checked')){
            $('#edit_question16Collapse').hide();
            $('[name="edit_conditions"]').prop("disabled", true);
            $('[name="edit_conditions"]').prop("checked", false);
            $('[name="edit_question18"]:first').prop('checked', true);
        }else{
            $('[name="edit_question18"]').prop('checked', false);
            $('[name="edit_conditions"]').prop("disabled", false);
            $('#edit_question16Collapse').show();
        }
    });
    
    $("#edit_consent").change(function(){
        if($("#edit_consent").val() == "YES"){
            $("#edit_reason_for_refusal").val("");
            $("#edit_reason_for_refusal").prop("disabled", true);
        }else{
            $("#edit_reason_for_refusal").prop("disabled", false);
        }
    });

    const updateVacinnationSummary = (id) => {
        $("#vaccination_summary").modal("hide");
        swal({
            title: 'Please enter password!',
            input: 'password',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: function (password) {
                return new Promise(function (resolve, reject) {
                        $.ajax({
                            url:'{{ route('account.verify-password')}}',
                            type:'POST',
                            data:{ _token:"{{ csrf_token() }}",password:password},
                            dataType:'json',
                            success:function(success){
                                if(!success.success){
                                    setTimeout(function() { swal.showValidationError('Incorrect PASSWORD!.'); resolve() }, 2000);
                                }else{
                                    setTimeout(function() { resolve() }, 2000);
                                }
                            }
                        });
                    
                });
            },
            allowOutsideClick: false
        }).then(function(password) {
            if(password.value){
                
                $.ajax({
                    url: '/covid19vaccine/vaccination-monitoring/find-specific-summary/' + id,
                    type: "GET",
                    dataType: "JSON",
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },
                    success: function (data) {
                        console.log(data);
                        $("#edit_qualified_patient_id").val(data.id);
                        $("#survey_id").val(data.survey_id);
                        $("#monitoring_id").val(data.monitoring_id);
                        
                        let fullname = "";
                        if(data.last_name) fullname += data.last_name;
                        if(data.suffix) 
                            if( data.suffix != "NA")
                                fullname += " " + data.suffix;
                        fullname += ", ";
                        if(data.first_name) fullname += data.first_name + " ";
                        if(data.middle_name && data.middle_name != "NA") fullname += data.middle_name[0] + ".";
                        
                        $('#edit_fullname').val(fullname);
                        $("#edit_dosage").attr("disabled","disabled");
                        $("#edit_dosage").val(data.dosage);
                        $("#edit_vaccination_date").val(data.vaccination_date);
                        $("#edit_vaccine_manufacturer").val(data.vaccine_id);
                        $("#edit_batch_number").val(data.batch_number);
                        $("#edit_lot_number").val(data.lot_number);
                        $("#edit_vaccinator").val(data.vaccinator_id);
                        $("#edit_consent").val(data.consent);
                        $("#edit_consent").change();
                        $("#edit_reason_for_refusal").val(data.reason_for_refusal);
                        $("#edit_deferral").val(data.deferral);
                        $("#edit_reason_for_refusal").val(data.reason_for_refusal);


                        if(data.question_1 == "01_Yes"){ $('input[name="edit_question1"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question1"][value="02_No"]').click(); }
                        
                        if(data.question_2 == "01_Yes"){ $('input[name="edit_question2"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question2"][value="02_No"]').click(); }
                        
                        if(data.question_3 == "01_Yes"){ $('input[name="edit_question3"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question3"][value="02_No"]').click(); }
                        
                        if(data.question_4 == "01_Yes"){ $('input[name="edit_question4"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question4"][value="02_No"]').click(); }
                        
                        if(data.question_5 == "01_Yes"){ $('input[name="edit_question5"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question5"][value="02_No"]').click(); }
                        
                        if(data.question_6 == "01_Yes"){ $('input[name="edit_question6"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question6"][value="02_No"]').click(); }
                        
                        if(data.question_7 == "01_Yes"){ $('input[name="edit_question7"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question7"][value="02_No"]').click(); }
                        
                        if(data.question_8 == "01_Yes"){ $('input[name="edit_question8"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question8"][value="02_No"]').click(); }
                        
                        if(data.question_9){ 
                            data.question_9.forEach(data => {
                                $('input[name="edit_symptoms"][value="'+data.trim()+'"]').click();
                            })
                        }
                        
                        if(data.question_10 == "01_Yes"){ $('input[name="edit_question10"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question10"][value="02_No"]').click(); }
                        
                        if(data.question_11 == "01_Yes"){ $('input[name="edit_question11"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question11"][value="02_No"]').click(); }
                        
                        if(data.question_12 == "01_Yes"){ $('input[name="edit_question12"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question12"][value="02_No"]').click(); }
                        
                        if(data.question_13 == "01_Yes"){ $('input[name="edit_question13"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question13"][value="02_No"]').click(); }

                        if(data.question_14 == "01_Yes"){ $('input[name="edit_question14"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question14"][value="02_No"]').click(); }
                        
                        if(data.question_15 == "01_Yes"){ $('input[name="edit_question15"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question15"][value="02_No"]').click(); }
                        
                        if(data.question_16 == "01_Yes"){ $('input[name="edit_question16"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question16"][value="02_No"]').click(); }
                        
                        if(data.question_17){ 
                            data.question_17.forEach(data => {
                                $('input[name="edit_conditions"][value="'+data.trim()+'"]').click();
                            })
                        }

                        if(data.question_18 == "01_Yes"){ $('input[name="edit_question18"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question18"][value="02_No"]').click(); }
                        
                        if(data.question_19 == "01_Yes"){ $('input[name="edit_question19"][value="01_Yes"]').click(); }
                        else{ $('input[name="edit_question19"][value="02_No"]').click(); }
                        
                        $('.selectpicker').selectpicker('refresh');
                        $('#update_monitor_vaccination').modal('show');
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    },
                    complete: function(){
                        processObject.hideProcessLoader();
                    },
                });
            }
        });
    }

    //Update Monitoring
    $("#update_monitor_form").validate({
        rules: {
            edit_dosage: {
                required: true
            },
            edit_vaccination_date: {
                required: true
            },
            edit_vaccine_manufacturer: {
                required: true
            },
            edit_batch_number: {
                required: true
            },
            edit_lot_number: {
                required: true
            },
            edit_vaccinator: {
                required: true
            },
            edit_consent: {
                required: true
            }
        },
        submitHandler: function (form) {
        
            let monitoring_survey = true;
            let symptoms = "";
            let conditions = "";
            if($('[name="edit_question8"]').is(':checked')){
                if(!$('[name="edit_question8"]:first').is(':checked')){
                    $('input[name="edit_symptoms"]:checked').each(function() {
                        symptoms += this.value + ", ";
                    });
                    symptoms = symptoms.slice(0, -2);
                    $("#edit_question9").val(symptoms);
                    monitoring_survey = (symptoms == "") ? false : true;
                }
            }

            if(monitoring_survey == true){
                if($('[name="edit_question16"]').is(':checked')){
                    if(!$('[name="edit_question16"]:first').is(':checked')){
                        $('input[name="edit_conditions"]:checked').each(function() {
                            conditions += this.value + ", ";
                        });
                        conditions = conditions.slice(0, -2);
                        $("#edit_question17").val(conditions);
                        monitoring_survey = (conditions == "") ? false : true;
                    }
                }
                if(monitoring_survey == true){
                    for(let counter = 1; counter <= 18; counter++){
                    //  console.log(counter, monitoring_survey);
                    //  console.log($('input[name="edit_question' + counter+'"]'));
                        if(counter != 9 && counter != 17){
                            if(counter == 5 || counter == 7 || counter == 15){
                                //for edit_question 5 and 15
                                // console.log($('#edit_question' + (counter-1)));
                                if($('#edit_question' + (counter-1)).is(':checked'))
                                    monitoring_survey = ($("#edit_question" + counter + ":checked").length < 1) ? false : true;
                            }else if(counter == 18){
                                //for edit_question 18
                                if($('#edit_question' + (counter-2)).is(':checked'))
                                    monitoring_survey = ($("#edit_question" + counter + ":checked").length < 1) ? false : true;
                            }else{
                                if($("#edit_question" + counter + ":checked").length < 1){
                                    monitoring_survey = false;
                                    break;
                                }
                            }
                        }
                    }
                }
                
            }
            
            if($("#edit_consent").val() == "NO"){ 
                if($("#edit_reason_for_refusal").val() == ""){
                    monitoring_survey = false;
                    $("#edit_reason_for_refusal").addClass("error");
                    $("#edit_reason_for_refusal").append('<label id="lot_number-error" class="error" for="lot_number">This field is required.</label>');
                }else{
                    monitoring_survey = true;
                }
            }
            
            if(monitoring_survey == true){
                Swal.fire({
                    title: 'Reason for updating Record?',
                    input: 'textarea',
                    inputPlaceholder: "Enter your reason for updating record",
                    showCancelButton: true,
                    confirmButtonText: 'Proceed',
                    allowOutsideClick: false,
                }).then((result1) => {
                    if (result1.dismiss != 'cancel') {
                        if(result1.value != ''){
                            $('#reason_for_update').val(result1.value);

                            Swal.fire({
                                title: 'Save Now?',
                                text: "You won't be able to revert this!",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, save it!',
                                html: "<b>Monitor Patient",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            }).then((result) => {
                                if (result.value) {
                                    $.ajax({
                                        url: '/covid19vaccine/vaccination-monitoring/'+1,
                                        type: "PUT",
                                        data: $('#update_monitor_form').serialize(),
                                        dataType: "JSON",
                                        beforeSend: function(){
                                            processObject.showProcessLoader();
                                        },
                                        success: function (data) {
                                            if (data.success) {
                                                $('#update_monitor_vaccination').modal('hide');
                                                $("#update_monitor_form")[0].reset();
                                                $('#updatemonitorOtherInformationcollapse').collapse("hide");
                                                $("#edit_vaccinator").val(0);
                                                $('.selectpicker').selectpicker('refresh');
                                                
                                                for(let index = 1; index <=18; index++){
                                                    $('[name="edit_question' + index + '"]').prop('checked', false);
                                                }
                                                
                                                swal.fire({
                                                    title: "Save!",
                                                    text: "Successfully!",
                                                    type: "success",
                                                    html: "<b>Monitor Patient",
                                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                                })
                                                datatable.ajax.reload( null, false );
                                            } else {
                                                swal.fire({
                                                    title: "Oops! something went wrong.",
                                                    html: "<b>" + data.messages +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                                    type: "error",
                                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                                });
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            swal.fire({
                                                title: "Oops! something went wrong.",
                                                html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                                type: "error",
                                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                            });
                                        },
                                        complete: function(){
                                            processObject.hideProcessLoader();
                                        },
                                    });
                                }
                            })
                        }else{
                            swal("Error", 'Please provide valid reasons', "error");
                        }
                    }
                })

            }else{
                Swal.fire({
                    title: 'Please complete the required fields!',
                    type: 'warning'
                });
            }
            
        },
    });
    @endcan
    
    
    @can('permission', 'deleteVaccinationMonitoring')
    const voidSummary = (id) =>{
        Swal.fire({
            title: 'Void Monitoring Summary?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, void it!',
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/covid19vaccine/vaccination-monitoring/' + id,
                    type: "DELETE",
                    dataType: "JSON",
                    data:{ _token: "{{csrf_token()}}"},
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },
                    success: function (data) {
                        swal.fire({
                            title: "Deleted!",
                            text: "Successfully!",
                            type: "success",
                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                        })
                        summary_datatable.ajax.reload( null, false );
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            title: "Oops! something went wrong.",
                            html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                            type: "error",
                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                        });
                    },
                    complete: function(){
                        processObject.hideProcessLoader();
                    },
                });
            }
        });
    }
    @endcan

    const viewVasInfo = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination-monitoring/vas-info/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                console.log(data);
                let info = "";
                
                for(let index = 0; index < data.length; index++){
                
                info += `<tr>
                            <td>${data[index][0]}</td>
                            <td>${data[index][1]}</td>
                            <td>${data[index][2]}</td>
                            <td>${data[index][3]}</td>
                            <td>${data[index][4]}</td>
                            <td>${data[index][5]}</td>
                            <td>${data[index][6]}</td>
                            <td>${data[index][7]}</td>
                            <td>${data[index][8]}</td>
                            <td>${data[index][9]}</td>
                            <td>${data[index][10]}</td>
                            <td>${data[index][11]}</td>
                            <td>${data[index][12]}</td>
                            <td>${data[index][13]}</td>
                            <td>${data[index][14]}</td>
                            <td>${data[index][15]}</td>
                            <td>${data[index][16]}</td>
                            <td>${data[index][17]}</td>
                            <td>${data[index][18]}</td>
                            <td>${data[index][19]}</td>
                            <td>${data[index][20]}</td>
                            <td>${data[index][21]}</td>
                            <td>${data[index][22]}</td>
                            <td>${data[index][23]}</td>
                            <td>${data[index][24]}</td>
                            <td>${data[index][25]}</td>
                            <td>${data[index][26]}</td>
                        </tr>`;
                }
                
                $("#copyTextId").val(id);
                $("#vas_datatable tbody").empty();
                $("#vas_datatable tbody").append(info);
                
                $("#vas_line_info_modal").modal("show");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
            complete: function(){
                processObject.hideProcessLoader();
            },
        });
    }
    
    const copyToClipboard = () =>{
        $.ajax({
            url: '/covid19vaccine/vaccination-monitoring/vas-info/' + $("#copyTextId").val(),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                copy2DToClipboard(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
            complete: function(){
                processObject.hideProcessLoader();
            },
        });
    }
    
    
    function copy2DToClipboard(array) {
      var csv = '', row, cell;
      for (row = 0; row < array.length; row++) {
        for (cell = 0; cell < array[row].length; cell++) {
          csv += (array[row][cell]+'').replace(/[]/, ' ');
          if (cell+1 < array[row].length) csv += '\t';
        }
        csv += '\n';
      }
      copyTextToClipboard(csv);
    }
    
    function copyTextToClipboard(text) {
      if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
      }
      navigator.clipboard.writeText(text).then(function() {
      }, function(err) {
        console.error('Async: Could not copy text: ', err);
      });
    }
</script>
@endsection
