@extends('layouts.app2')

@section('location')
{{$title}}
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
                                    <label>Status:</label> 
                                    <select class="selectpicker form-control" id="status">
                                        <option value="2">All</option>
                                        <option value="1">Transferred</option>
                                        <option value="0">Not Transferred</option>
                                    </select>
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
                                        <th>Date Registered</th>
                                        <th style="width: 300px;">Actions</th>
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
                                            <option value="02_Male">Male</option>
                                            <option value="01_Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <!-- Suffix -->
                                    <div class="form-group">
                                        <label>Suffix</label><small>(Jr., Sr., etc..)</small> <label for="affiliation" class="error"></label>
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
                                        <label>Category *</label> <label for="category" class="error"></label>
                                        <select class="form-control selectpicker" id="category" name="category">
                                            <option value="" disabled selected>Select.....</option>
                                        </select>
                                    </div>
                                </div>
    
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
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-lg" onclick="">Verify</button>
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
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('pre-registration-online.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", "status":$("#status").val()}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "status" },
                { "data": "created_at" },
                { "data": "actions" },
            ],
            "order": [[ 2, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": [ 2 ] }, 
            ]	 	 
        });
        
        $("#status").on("change",function(){
            let length = 10;
            if($("#status").val() == "0"){
                length = 100;
            }
            $('#datatable').DataTable().clear().destroy();
            datatable = $('#datatable').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('pre-registration-online.find-all') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", "status":$("#status").val()}
                },
                "columns": [
                    { "data": "fullname" },
                    { "data": "status" },
                    { "data": "created_at" },
                    { "data": "actions" },
                ],
                "order": [[ 2, "asc" ]],
                
                "columnDefs": [
                    { "orderable": false, "targets": [ 2,3 ] }, 
                ],
                "pageLength": length, 
            });
        });

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");
    });

    //Transfer Patients
    @can('permission', 'viewTransferOnlinePreRegistration')
    const transferPatient = (id) =>{
        $.ajax({
            url: '/covid19vaccine/pre-registration-online/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                validateAction(data,"validate","Verify Patient");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
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

    //Transfer Approval
    @can('permission', 'viewTransferOnlinePreRegistration')
    const TransferApproval = (id) =>{
        Swal.fire({
            title: 'Transfer Patient?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Transfer it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                $.ajax({
                   url: '/covid19vaccine/pre-registration-online/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "PUT",
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Successfully Approved!",
                                type: "success"
                            });
                            datatable.ajax.reload( null, false );
                            $("#verifying_patient").modal("hide");
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
                                type: "error"
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            title: "Oops! something went wrong.",
                            text: errorThrown,
                            type: "error"
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
    @can('permission', 'viewTransferOnlinePreRegistration')
    const validateAction = (data,action,modalTitle) =>{
        $("#verifying_patient").modal("show");
               $('.modal-title').html('<i class="fa fa-user-md" aria-hidden="true"></i> '+ modalTitle);
               if(action == "validate"){
                   $('#btnAppend').empty();
                   $('#btnAppend').append('<button id="btnTransferApproval" name="btnTransferApproval" onclick="TransferApproval('+data[0].id+')" class="btn btn-danger"><i class="fa fa-check" aria-hidden="true"></i> Transfer Approval!</button> ');
                }else{
                    $('#btnAppend').empty();
                }
                $("#btnTransferApproval").attr('value',data[0].id);
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
                //if(data[0].date_of_birth)$('#show_dob').text(data[0].date_of_birth);

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
                    if(data[0].sex == "01_FEMALE") sex='Female';
                    else if(data[0].sex == "02_MALE") sex='Male';
                    $('#show_sex').text(sex);
                }
                else
                    $('#show_sex').text(" "); 
                drawTableBody(data[0]);
    }
    @endcan

    // ======================================

    

    @can('permission', 'updateRegistrationAndValidation')
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
            affiliation:{ required: true},
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
        },
        messages:{
            last_name:'Last name is required!',
            first_name:'First name is required!',
            affiliation:'Suffix is required!',
            contact:'Contact number is required!',
            sex:'Sex field is required!',
            barangay:'Barangay is required!',
            address:'Home address is required!',
            profession:'Profession is required!',
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Update profile information?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "/covid19vaccine/registration/"+$('#editid').val(),
                        type: "PUT",
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
                                    type: "success"
                                });

                                $("#register_form")[0].reset();
                                datatable.ajax.reload(null, false);
                                $('#update_patient_profile').modal('hide');
                            }else{
                                swal.fire({
                                    title: response.title,
                                    text: response.messages,
                                    type: "error"
                                });
                            }
                        },error: function (jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                title: 'Error',
                                text: errorThrown,
                                type: "error"
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

</script>
@endsection
