@extends('layouts.app2')

@section('location')
{{$title}}
@endsection

@section('style')
<style>

    .error {
        font-size: 0.8em;
        font-weight: 300;
        
    }

    #ptStyle{
        cursor: pointer;
    }
    .content-hider{
        display: none;
    }
    .divider{
        background-color: black;
        margin-right: 15px !important;
        margin-left: 15px !important;
        height: 1px !important;
        display: block !important;
        overflow: hidden !important;
    }

    hr {
        margin-top: 10px;
        margin-bottom: 10px;
        border: 0;
        border-top: 2px solid rgb(250, 250, 250);
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
                                    <p class="category">Counseling and Final Consent</p>
                                </div>
                                <div class="col-lg-2">
                                    @can('permission','createRegistrationAndValidation')
                                    <a onclick="create()" class="btn btn-primary pull-right">
                                        <i class="ti-plus"></i> Add New Patient
                                    </a>
                                    @endcan
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
                                        <th>Verification Status</th>
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
    <!-- End Display All Data -->

    <!-- Modal For Edit -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="verifying_patient">
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
                                            <p id="show_philhealt_id"></p>
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
                    {{-- <button class="btn btn-success">Registration Approval</button> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Edit-->


    
    @can('permission', 'updateRegistrationAndValidation')
    <!-- Modal For Edit -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="update_patient_profile">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title"> Update Patient Profile</h4>
                    </div>
                    <!--Register Form -->
                    <form id="register_form">
                        @csrf
                        @method('POST')
                        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto; background-color:#f7f7f7;">
                            <input type="hidden" id="editid">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Last Name -->
                                    <div class="form-group">
                                        <label>Last Name *</label> <label for="last_name" class="error"></label>
                                        <input type="text" class="form-control border-input" placeholder="Last Name" name="last_name" id="last_name">
                                    </div>
                                    
                                    <!-- First Name -->
                                    <div class="form-group">
                                        <label>First Name *</label> <label for="first_name" class="error"></label>
                                        <input type="text" class="form-control border-input" placeholder="First Name" name="first_name" id="first_name">
                                    </div>
                                    
                                    <!-- Middle Name -->
                                    <div class="form-group">
                                        <label>Middle Name *</label><small style="font-size: x-small;"><i>Put "NA" If not Applicable</i></small><label for="middle_name" class="error"></label>
                                        <input type="text" class="form-control border-input" placeholder="Middle Name" name="middle_name" id="middle_name">
                                    </div>
                                    
                                </div>
    
                                <div class="col-md-4">
                                    <!-- Date of Birth -->
                                    <div class="form-group">
                                        <label>Date Of Birth </label><small>(mm/dd/yyyy)</small> <label for="dob" class="error"></label>
                                        <input type='text' class="form-control datetimepicker" id='dob' name="dob" max="9999-12-31"
                                        placeholder="Date of Birth"/>
                                    </div>
    
                                    <div class="form-group">
                                        <label>Civil Status *</label> <label for="civil_status" class="error"></label>
                                        <select class="selectpicker form-control" name="civil_status" id="civil_status">
                                            <option value="" disabled selected>Select.....</option>
                                            <option value="02_Married">Married</option>
                                            <option value="01_Single">Single</option>
                                            <option value="03_Widow/Widower">Widow/Widower</option>
                                            <option value="04_Separated/Annulled">Separated/Annulled</option>
                                            <option value="05_Living_with_Partner">Living with Partner</option>
                                        </select>
                                    </div>
    
                                    <!-- Sex -->
                                    <div class="form-group">
                                        <label>Sex *</label> <label for="sex" class="error"></label>
                                        <select class="selectpicker form-control" name="sex" id="sex">
                                            <option value="" disabled selected>Select.....</option>
                                            <option value="01_Male">Male</option>
                                            <option value="02_Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <!-- Suffix -->
                                    <div class="form-group">
                                        <label>Suffix</label><small>(Jr., Sr., etc.)</small> <label for="affiliation" class="error"></label>
                                        <select class="selectpicker form-control" name="affiliation" id="affiliation"> 
                                            <option value="" disabled selected>Select.....</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                            <option value="V">V</option>
                                            <option value="JR">JR</option>
                                            <option value="SR">SR</option>
                                            <option value="NA">NA</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Contact Number -->
                                    <div class="form-group">
                                        <label>Contact Number *</label> <label for="contact" class="error"></label>
                                        <input type="number" class="form-control border-input" name="contact" placeholder="Contact" id="contact">
                                    </div>

                                    <!-- Barangay -->
                                    <div class="form-group">
                                        <label>Barangay *</label> <label for="barangay" class="error"></label>
                                        <select class="form-control selectpicker" data-live-search="true" id="barangay" name="barangay">
                                            <option value="" disabled selected>Select.....</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Home Address -->
                                    <div class="form-group">
                                        <label>Home Adrress </label><small>(e.g. street, block, lot, unit)</small> <label for="address" class="error"></label>
                                        <textarea class="form-control" placeholder="Home Address" name="address" id="address"></textarea>
                                    </div>
                                </div>
                            </div>
    
                            <legend style="font-size: 15px;font-style:italic; color:red;"><b>(Additional Information): -Note :</b> Put "NA" If not Applicable</legend>
                            
                            <div class="row">
                                <div class="col-md-3">
                                        <div class="form-group">
                                        <label>Category *</label> <label for="category" class="error" readonly></label>
                                        <select class="form-control selectpicker" id="category" name="category">
                                            <option value="" disabled selected>Select.....</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <input type="text" id="categoryIfVaccinated" name="categoryIfVaccinated" hidden>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label>ID Category *</label> <label for="category_for_id" class="error"></label>
                                        <select class="selectpicker form-control" name="category_for_id" id="category_for_id">
                                            <option value="" disabled selected>Select.....</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label id="label_number"> ID Number * </label> <label for="category_id_number" class="error"></label>
                                        <input type="text" class="form-control border-input" name="category_id_number" id="category_id_number" placeholder="Put (NA) If not Applicable">
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>PhilHealth ID * </label> <label for="philhealth" class="error"></label>
                                        <input type="text" class="form-control border-input" name="philhealth" id="philhealth" placeholder="Put (NA) If not Applicable">
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Employee Status *</label> <label for="employment" class="error"></label>
                                        <select class="form-control selectpicker" id="employment" name="employment">
                                            <option value="" disabled selected>Select.....</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                        <div class="form-group">
                                        <label>Profession *</label> <label for="profession" class="error"></label>
                                        <select class="form-control selectpicker" data-live-search="true" id="profession" name="profession">
                                            <option value="" disabled selected>Select.....</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4" id="other_profession" style="display:none">
                                    <div class="form-group">
                                        <label id="label_number"> Specify Profession * </label> <label for="specific_profession" class="error"></label>
                                        <input type="text" class="form-control border-input" name="specific_profession" id="specific_profession" placeholder="Put (NA) If not Applicable">
                                    </div>
                                </div>
                                
                            </div>
    
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Employer Name * </label> <label for="employer_name" class="error"></label>
                                        <input type="text" class="form-control border-input" name="employer_name" id="employer_name" placeholder="Put (NA) If not Applicable">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Employer Contact *</label> <label for="employer_contact" class="error"></label>
                                        <input type="text" class="form-control border-input" name="employer_contact" id="employer_contact" placeholder="Put (NA) If not Applicable">
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Employer Adrress </label><small>(e.g. street, block, lot, unit, barangay, city)</small> <label for="employer_address" class="error"></label>
                                        <textarea class="form-control"  name="employer_address" id="employer_address" placeholder="Put (NA) If not Applicable"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="survey_div">

                                <div class="col-md-12"><hr></div>
                                {{-- QUESTION 7 --}}
                                <div class="content" >
                                    <label class="col-md-6">With Allergy * <label for="question2" class="error"></label> </label>
                                    <div class="col-md-6">
                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q2" data-toggle="buttons">
                                            <label class="btn btn-success">
                                                <input type="radio" name="question2" value="YES"> YES
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="radio" name="question2" value="NO"> NO
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- QUESTION 7 --}}
                                <div class="content content-hider" id="allergy">
                                    <div class="col-md-12"><hr></div>
                                    <label class="col-md-6">Type of Allergy * <label for="question3[]" class="error"></label></label>
                                    <div class="col-md-6">
                                        <div class="btn-group mt-5" role="group" aria-label="q3_0" data-toggle="buttons">
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question3[]" value="DRUGS"> DRUGS
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question3[]" value="FOOD"> FOOD
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question3[]" value="INSECTS"> INSECTS
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question3[]" value="LATEX"> LATEX
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question3[]" value="MOLD"> MOLD
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question3[]" value="PET"> PET
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question3[]" value="POLLEN"> POLLEN
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" id="other_allergy" name="question3[]" > OTHERS
                                            </label>
                                            <div><input type="text" class="form-control" style="display: none" name="specific_allergy" onkeyup="passedValue(this, '#other_allergy')" style="text-transform: uppercase" id="specific_allergy"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12"><hr></div>
                                {{-- QUESTION 7 --}}
                                <div class="content">
                                    <label class="col-md-6">With Comorbidities *  <label for="question4" class="error"></label></label>
                                    <div class="col-md-6">
                                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q4" data-toggle="buttons">
                                            <label class="btn btn-success">
                                                <input type="radio" name="question4" value="YES"> YES
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="radio" name="question4" value="NO"> NO
                                            </label>
                                        </div>
                                    </div> 
                                </div>

                                <div class="col-md-12"><hr></div>
                                <div class="content content-hider" id="comorbidities">
                                    <label class="col-md-6">(If YES, Please indicate your comorbidities) *  <label for="question5[]" class="error"></label></label>
                                    <div class="col-md-6">
                                        <div class="btn-group mt-5" role="group" aria-label="q5_0" data-toggle="buttons">
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question5[]" value="HYPERTENSION"> HYPERTENSION
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question5[]" value="HEART DISEASE"> HEART DISEASE
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question5[]" value="KIDNEY DISEASE"> KIDNEY DISEASE
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question5[]" value="DIABETES MELLITUS"> DIABETES MELLITUS
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question5[]" value="BRONCHIAL ASTHMA"> BRONCHIAL ASTHMA
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question5[]" value="IMMUNODEFICIENCY STATE"> IMMUNODEFICIENCY STATE
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" name="question5[]" value="CANCER"> CANCER
                                            </label>
                                            <label class="btn btn-success">
                                                <input type="checkbox" id="other_comorbidities" name="question5[]" > OTHERS
                                            </label>
                                            <div><input type="text" class="form-control" style="display: none" name="specific_comorbidities" onkeyup="passedValue(this, '#other_comorbidities')" style="text-transform: uppercase" id="specific_comorbidities"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12"><hr></div>
                                </div>
                                <div class="col-md-12"><hr></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <button type="submit" id="actionBTN" class="btn btn-success " onclick="">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Edit-->
    @endcan

@endsection

@section('js')
<script>
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
                 "language": {
            processing: '<i style="width: 50px;" class="fa fa-spinner fa-spin fa-lg fa-fw"></i><b> Processing....</b>',
            "sSearch": " <b style='color:red;'><i>(Fistname Lastname) e.g. juan de la cruz</i></b><br>Press Enter to search:"
            },
            "ajax":{
                "url": '{{ route('registration-and-validation.findAll') }}',
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

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");
    });

    //View Patient Details
    @can('permission', 'viewRegistrationAndValidation')
    const viewPatient = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                validateAction(data,"view","Patient Details");
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
    @endcan
    //Verify Patients
    @can('permission', 'viewRegistrationAndValidation')
    const verifyPatient = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                validateAction(data,"validate","Verify Patient");
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
    @endcan
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

    //Registration Approval
    @can('permission', 'viewRegistrationAndValidation')
    const registrationApproval = (id) =>{
        Swal.fire({
            title: 'Approve Patient?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it!',
            html: "<b>Vaccination Patient Approval " ,
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                //show loader
                $.ajax({
                   url: '/covid19vaccine/registration-approval/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Successfully Approved!",
                                type: "success",
                                html: "<b><br> Vaccination Patient Approval " ,
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            datatable.ajax.reload( null, false );
                            $("#verifying_patient").modal("hide");
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
    }
    @endcan


    //Restore
    @can('permission', 'restoreRegistrationAndValidation')
    const registrationRestore = (id) =>{
        Swal.fire({
            title: 'Restore Patient?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Restore it!',
            html: "<b>Vaccination Patient Restore Data",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                   url: '/covid19vaccine/registration-restore/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Successfully Restore!",
                                type: "success",
                                html: "<b>Restore Vaccination Patient",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
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
                            html: "<b>" + errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
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
    }
    @endcan
    //Validate Action

    @can('permission', 'viewRegistrationAndValidation')
    const validateAction = (data,action,modalTitle) =>{
        $("#verifying_patient").modal("show");
               $('.modal-title').html('<i class="fa fa-user-md" aria-hidden="true"></i> '+ modalTitle);
               if(action == "validate"){
                   $('#btnAppend').empty();
                   $('#btnAppend').append('<button id="btnRegistrationApproval" name="btnRegistrationApproval" onclick="registrationApproval('+data[0].id+')" class="btn btn-danger"><i class="fa fa-check" aria-hidden="true"></i> Registration Approval!</button> ');
                }else{
                    $('#btnAppend').empty();
                }
                $("#btnRegistrationApproval").attr('value',data[0].id);
                $("#show_avatar").attr('src', '../../../images/' + data[0].image);
                let fullname = "";
                if(data[0].last_name) fullname += data[0].last_name + " ";
                if(data[0].affiliation) fullname += data[0].affiliation;
                fullname += ", ";
                if(data[0].first_name) fullname += data[0].first_name + " ";
                if(data[0].middle_name && data[0].middle_name != "NA") fullname += data[0].middle_name + " ";
                $('#show_full_name').text(fullname);
                if(data[0].contact_number)$('#show_contact').text(data[0].contact_number);
                if(data[0].email)$('#show_email').text(data[0].email);
                if(data[0].philhealth_number)$('#show_philhealt_id').text(data[0].philhealth_number);
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
                    else if(data[0].civil_status == '04_SEPARATED/ANNULLE')
                        civilstatus='Separated/Annulle';
                    else
                        civil_status='Living with Partner';

                    $('#show_civilstatus').text(civilstatus);
                }
                else{
                    $('#show_civilstatus').text(" ");
                }
                if(data[0].sex != null){
                    var sex=''; 
                    if(data[0].sex == "02_FEMALE") sex='Female';
                    else if(data[0].sex == "01_MALE") sex='Male';
                    $('#show_sex').text(sex);
                }
                else
                    $('#show_sex').text(" "); 
                drawTableBody(data[0]);
    }
    @endcan

    // ======================================

    

    jQuery.validator.addMethod("nowhitespace", function(value, element) {
        return this.optional(element) || /^\S+$/i.test(value);
    }, "No white space please");


    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(09|\+639)\d{9}$/);
    }, "<br />Please specify a valid phone number");

    /* age validator */
    jQuery.validator.addMethod("minAge", function (value, ele, min) {
        var today = new Date();
        var birthDate = new Date(value);
        var age = today.getFullYear() - birthDate.getFullYear();

        if(age > min+1) { return true; }

        var m = today.getMonth() - birthDate.getMonth();

        if(m < 0 || (m ===0 && today.getDate() < birthDate.getDate())) { age--; }

        return age >= min;
    }, 'You\'re age is not qualified for vaccination!');
    
    /* alphanumeric */
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^\w+$/i.test(value);
    }, "Letters, numbers, and underscores only please");

    //get barangays
    $.ajax({
        url:'{{ route('covid19vaccine.all-barangay-for-combobox') }}',
        type:'GET',
        dataType:'json',
        success:function(response){
            for (let index = 0; index < response.length; index++)
            {
                $('[name="barangay"]').append('<option value='+response[index].id+'>'+ response[index].barangay+'</option>');
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
    
    //get category
    $.ajax({
        url:'{{ route('covid19vaccine.all-category-for-combobox') }}',
        type:'GET',
        dataType:'json',
        success:function(response){
            for (let index = 0; index < response.length; index++)
            {
                $('[name="category"]').append('<option value='+response[index].id+'>'+ response[index].category_name+'</option>');
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
    
    //get profession
    $.ajax({
        url:'{{ route('covid19vaccine.all-profession-for-combobox') }}',
        type:'GET',
        dataType:'json',
        success:function(response){
            for (let index = 0; index < response.length; index++)
            {
                $('[name="profession"]').append('<option value='+response[index].id+'>'+ response[index].profession_name+'</option>');
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });

    //get employer type
    $.ajax({
        url:'{{ route('covid19vaccine.all-employertype-for-combobox') }}',
        type:'GET',
        dataType:'json',
        success:function(response){
            for (let index = 0; index < response.length; index++)
            {
                $('[name="employment"]').append('<option value='+response[index].id+'>'+ response[index].employment_type+'</option>');
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });


    //get all id category
    $.ajax({
        url:'{{ route('covid19vaccine.all-idcategory-for-combobox') }}',
        type:'GET',
        dataType:'json',
        success:function(response){
            for (let index = 0; index < response.length; index++)
            {
                $('[name="category_for_id"]').append('<option value='+response[index].id+'>'+ response[index].id_category_name+'</option>');
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });

    $('select[name="profession"]').on('change', function(){
        if($(this)[0].value == '19'){
            $('#other_profession').show();
            $('[name="specific_profession"]').val('');
        }else{
            $('#other_profession').hide();
        }
    });

    
    $('#profession').on('change', function(){
        if($(this)[0].value == '19'){
            $('#other_profession').show();
            $('#specific_profession').val('');
        }else{
            $('#other_profession').hide();
        }
    });

    // zipcode: "4025"

    @can('permission', 'updateRegistrationAndValidation')
    const updatePatientPatient = (id) =>{
        $('#survey_div').hide();
        $(".error").html('');
        $(".error").removeClass("error");
        $('#actionBTN').text('Update');
        $('#update_patient_profile .modal-title').text('Update Patient Profile');

        $.ajax({
            url: '/covid19vaccine/registration/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $('#editid').val(data.preRegistration[0].registration_id);
                $('#last_name').val(data.preRegistration[0].last_name);
                $('#first_name').val(data.preRegistration[0].first_name);
                $('#middle_name').val(data.preRegistration[0].middle_name);
                $('#dob').val(data.preRegistration[0].date_of_birth);
                $('#sex').val(data.preRegistration[0].sex == "02_FEMALE"? '02_Female' : '01_Male');
                $('#civil_status').val(
                    data.preRegistration[0].civil_status == "02_MARRIED"? '02_Married' : 
                    data.preRegistration[0].civil_status == "01_SINGLE"? '01_Single' : 
                    data.preRegistration[0].civil_status == "03_WIDOW/WWIDOWER"? '03_Widow/Widower' : 
                    data.preRegistration[0].civil_status == "04_SEPARATED/ANNULLED"? '04_Separated/Annulled' : 
                    data.preRegistration[0].civil_status == "05_LIVING_WITH_PARTNER"? '05_Living_with_Partner' : ''
                );
                $('#affiliation').val(data.preRegistration[0].suffix);
                $('#contact').val((data.preRegistration[0].contact_number[0] == '0')? data.preRegistration[0].contact_number : '0'+ data.preRegistration[0].contact_number );
                $('#address').val(data.preRegistration[0].home_address);
                $('#barangay').val(data.preRegistration[0].barangay_id);
                $('#category').val(data.preRegistration[0].category_id);
                $('#category_id_number').val(data.preRegistration[0].category_id_number);
                $('#category_for_id').val(data.preRegistration[0].id_for_category_id);
                
                
                //if patient is vaccinated
                // if(data.isVaccinated.length > 0){
                //     $('#category').prop('disabled', true);
                //     $("#categoryIfVaccinated").val(data.preRegistration[0].category_id);
                // }else{
                //     $('#category').prop('disabled', false);
                //     $("#categoryIfVaccinated").val();
                // }

                $('#philhealth').val(data.preRegistration[0].philhealth_number);
                $('#employment').val(data.preRegistration[0].employment_status_id);
                $('#profession').val(data.preRegistration[0].profession_id);
                $("#profession").trigger("change");
                $('#specific_profession').val(data.preRegistration[0].specific_profession);
                $('#employer_name').val(data.preRegistration[0].employer_name);
                $('#employer_contact').val(data.preRegistration[0].employer_contact);
                $('#employer_address').val(data.preRegistration[0].employer_barangay_name);
                $('.selectpicker').selectpicker('refresh');
                
                $('#update_patient_profile').modal('show');
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

    //update account
    $("#register_form").validate({
        rules: {
            first_name: {
                required: true,
                minlength:3,
            },
            dob: {
                required: true,
                minAge:18,
            },
            contact: {
                required: true,
                phoneno: true
            },
            last_name: { required: true},
            middle_name: { required: true},
            sex: { required: true },
            barangay: { required: true },
            address: { required: true },
            civil_status: { required: true },
            employment: { required: true },
            profession: { required: true },
            category: { required: true },
            category_id_number: { required: true },
            category_for_id: { required: true },
            philhealth: { required: true },
            employer_name: { required: true },
            employer_contact: { required: true},
            employer_address: { required: true },
            profession: { required: true },
            specific_profession: { required: true },
            affiliation: { required: true },
        },
        messages:{
            last_name:'Last name is required!',
            first_name:'First name is required!',
            contact:'Contact number is required!',
            sex:'Sex field is required!',
            barangay:'Barangay is required!',
            address:'Home address is required!',
            profession:'Profession is required!',
            affiliation:'Suffix is required!',
        },
        submitHandler: function (form) {
            
            let url = "";
            let type = "";
            
            if($('#actionBTN').text() == 'Save'){
                url = '{{ route('covid19vaccine.createPatientProfile') }}';
                type = "POST";
                title = 'Create profile information?';
            } else{
                url = "/covid19vaccine/registration/"+$('#editid').val();
                type = "PUT";
                title = 'Update profile information?';
            }
            
            Swal.fire({
                title: title,
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                html: "<b>Vaccination Patient Information ",
                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: url,
                        type: type,
                        data: $("#register_form").serialize(),
                        dataType: "JSON",
                        beforeSend: function(){
                            processObject.showProcessLoader();
                        },
                        success: function (response) {
                            if(response.success){
                                swal({
                                    title: "Success",
                                    text: response.messages,
                                    type: "success",
                                    html: "<b>Vaccination Patient Information",
                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                });

                                $("#register_form")[0].reset();
                                datatable.ajax.reload(null, false);
                                $('#update_patient_profile').modal('hide');
                            }else{
                                swal.fire({
                                    title: response.title,
                                    text: response.messages,
                                    type: "error",
                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                });
                            }
                        },error: function (jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                        },
                        complete: function(){
                            processObject.hideProcessLoader();
                        }
                    });
                }
            })
        }
    });
    @endcan
    
    
    @can('permission','createRegistrationAndValidation')
    /* allergy */
    $('input[name="question2"]').on('change', function(e){
        if($(this)[0].value == "YES"){
            $('#allergy').show();
        } else {
            $('#allergy').hide();
            $('#allergy label').removeClass('active');
            $('#allergy input').prop('checked', false);
        }
    });

    /* other allergy */
    $('#other_allergy').on('change', function(e){
        if($(this)[0].checked){
            $('#specific_allergy').show();
        } else {
            $('#other_allergy').val('');
            $('#specific_allergy').val('');
            $('#specific_allergy').hide();
        }
    });

    /* comorbidities */
    $('input[name="question4"]').on('change', function(e){
        if($(this)[0].value == "YES"){
            $('#comorbidities').show();
        } else {
            $('#comorbidities').hide();
            $('#comorbidities label').removeClass('active');
            $('#comorbidities input').prop('checked', false);
        }
    });
    
    /* other comorbidities */
    $('#other_comorbidities').on('change', function(e){
        if($(this)[0].checked){
            $('#specific_comorbidities').show();
        } else {
            $('#other_comorbidities').val('');
            $('#specific_comorbidities').val('');
            $('#specific_comorbidities').hide();
        }
    });

    const create = () => {
        $("#register_form")[0].reset();
        $('input[name="question2"][value="NO"]').click();
        $('input[name="question4"][value="NO"]').click();
        $('#survey_div').show();
        $('#update_patient_profile').modal('show');
        $('#actionBTN').text('Save');
        $('#update_patient_profile .modal-title').text('Create Patient Profile');
    }
    @endcan


</script>
@endsection
