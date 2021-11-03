@extends('layouts.app2')

@section('location')
{{$title}}
@endsection
@section('style')
    <style>
        
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
                                    <p class="category">Second Dose Verification</p>
                                    <div style="width: 25%; height:auto; margin: 0 auto" id="reader"></div>
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
                                        <th>QR Code</th>
                                        <th>Vaccination Date of First Dosage </th>
                                        <th>Status </th>
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
                        <!-- Patient -->
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
                        <!-- End Patient -->
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
    
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/1.2.4/html5-qrcode.min.js"></script>
<script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
<script>
    $(document).ready(function () {    
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('vaccination-monitoring.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", "action": "second_dose_verification"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "qr_code" },
                { "data": "vaccination_date" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 3, 4 ] }, 
            ]	 	 
        });
    });
    
     //Verify Patients
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
    
    var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    
    html5QrcodeScanner.render(onScanSuccess, onScanError);
    
    $('#datatable_filter input').val();
    // $('#scanner_modal').modal('show');
    $('#reader button').click();
        
    function onScanError(qrCodeError) {
        // This callback would be called in case of qr code scan error or setup error.
        // You can avoid this callback completely, as it can be very verbose in nature.
    }
    
    function onScanSuccess(qrCodeMessage) {
        // if(temp_stat){
            $('#datatable_filter input').val(qrCodeMessage);
            $('#datatable_filter input').keyup();
            // $('#scanner_modal').modal('hide');
            $('#datatable a').click();
            // temp_stat = false;
            
            // html5QrcodeScanner.clear();
        // }
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
            html: "<b>Vaccination Patient Approval for Second Dose",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                //show loader
                $.ajax({
                   url: '/covid19vaccine/second-registration-approval/' + id,
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
                                html: "<b>Vaccination Patient Approval",
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
</script>
@endsection
