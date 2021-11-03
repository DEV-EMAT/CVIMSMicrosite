
@extends('layouts.app2')

@section('location')
{{$title}}
@endsection
@section('style')
    <link href="{{asset('assets/css/vaccine-assessment.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/vaccine-astra.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/vaccine-sinovac.css')}}" rel="stylesheet" />
    <style>
    @page{margin:0 ; size: legal; } /*0mm 0mm 0mm 0mm*/

    @media print {
        * {
            -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
            color-adjust: exact !important;  /*Firefox*/
            font-family: 'Arial', Times, serif;
        }

        .mdf-table-style{
            border: 3px solid;
            background-color: black;
        }
    }

    #printDiv{
        background-color: red;
        /* width:96%;
        height:1500px; */
        width:68%;
        height:1020px;
        /* margin-top:-420px; */
        margin-top:-290px;
        margin-left:16px;
        font-family: 'Arial', Times, serif;

        display: flex;
        flex-direction:column;
        align-items:center;
        text-align:center;
        background-position: center left !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        overflow: visible;
    }

    #printInfo{
        margin-top: -180px;
        margin-left: -16px;
        /* margin-top: -170px;
        margin-left: 200px; */
        text-align: left;
        /* font-size: 12px; */
        font-size: 8px;
        padding:0px;
    }
    
    #printInfoFirstDose, #printInfoSecondDose{
        margin-left: 225px;
        text-align: left;
        font-size: 8px;
        padding:0px;
    }
    
    #printInfoFirstDose{
        margin-top: -105px;
    }

    #printInfoSecondDose{
        margin-top: -62px;
    }
    
    /* Consent form */
    #printConsentAstra, #printConsentSinovac{
        background-color: red;
        width:96%;
        height:1400px;
        margin-top:-110px;
        margin-left:16px;
        margin-bottom:50px;
        font-family: 'Arial', Times, serif;

        display: flex;
        flex-direction:column;
        align-items:center;
        text-align:center;
        background-position: center left !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        overflow: visible;
    }
    
    #printConsentAstraInfo, #printConsentSinovacInfo{
        margin-top: 220px;
        margin-left: 10px;
        text-align: left;
        font-size: 13px;
        padding:0px;
    }

    #printQrCode{
        margin-top: 310px;
        margin-left: 400px;
        height: 90px;
        width: 90px;
        /* margin-top: 468px;
        margin-right: -540px;
        height: 130px;
        width: 130px; */
    }

    #assessmentNumber{
        margin-top: -7px;
        margin-left: -375px;
        font-size: 12px;
        /* margin-top: -20px;
        margin-left: -565px;
        font-size: 16px; */
        font-weight: bold;
    }

    .box{
        float: left;
        width: 20%;
        height: 160px;
        padding: 2px;
        border:1px dotted;
    }

    svg{
        margin-top: 10%;
        padding: 5px;
        height: 70px;
        width: 70px;
        border: 3px solid;
    }

    #name{
        font-size: 7px;
        line-height: 1em;
    }

    .txtLine{
        margin: 0px;
    }
    .pdetails{
        width: 75px;
        font-weight:bold;
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

    <div class="modal" id="printModal">
        <div id="printDiv">
            <div id="printQrCode"></div>
            <div id="assessmentNumber"></div>
            <div id="printContainer" style="height:200px; width:200px"></div>

            <div class="row">
                {{-- <table id="printInfo" width="700px"></table> --}}
                <table id="printInfo" width="500px"></table>
            </div>
            <div class="row">
                <table id="printInfoFirstDose" width="500px"></table>
            </div>
            <div class="row">
                <table id="printInfoSecondDose" width="500px"></table>
            </div>
        </div>
    </div>

    <div class="modal">
        <div id="printConsentAstra">

            <div class="row">
                <table id="printConsentAstraInfo" width="700px"></table>
            </div>
        </div>
    </div>

    <div class="modal">
        <div id="printConsentSinovac">

            <div class="row">
                <table id="printConsentSinovacInfo" width="700px"></table>
            </div>
        </div>
    </div>
@endsection

@section('js')

<script type="text/template" id="qrcodeTpl">
	<div class="imgblock">
		<div class="qr" id="qrcode_{i}"></div>
	</div>
</script>

<script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
<script type="text/JavaScript" src="{{asset('assets/js/easy.qrcode.min.js')}}"></script>
<script type="text/JavaScript" src="{{asset('assets/js/base64ecabsologo/base64logo.js')}}"></script>

<script>
    let vaccine = [];
    
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
              "language": {
                processing: '<i style="width: 50px;" class="fa fa-spinner fa-spin fa-lg fa-fw"></i><b> Processing....</b>',
                "sSearch": " <b style='color:red;'><i>(Fistname Lastname) e.g. juan de la cruz</i></b><br>Press Enter to search:"
                },
            "ajax":{
                "url": '{{ route('assessment.findAll') }}',
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
        
        //get vaccine categories
        $.ajax({
            url:'{{ route('vaccine-category.find-all-vaccine') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    vaccine.push(response[index].vaccine_name);
                }
            }
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
    
    //Print Assessment
    @can('permission', 'printAssessment')
    const print = (id, qrcode, form) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination/assessment-details/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $.ajax({
                    url: '/covid19vaccine/vaccination/assessment-status/' + id,
                    data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    }
                });
                let printInfo = printInfoFirstDose = printInfoSecondDose = "";

                let fullname = "";
                if(data.assessment[0].last_name) fullname += data.assessment[0].last_name;
                if(data.assessment[0].suffix)
                    if( data.assessment[0].suffix != "NA")
                        fullname += " " + data.assessment[0].suffix;
                fullname += ", ";
                if(data.assessment[0].first_name) fullname += data.assessment[0].first_name + " ";
                if(data.assessment[0].middle_name && data.assessment[0].middle_name != "NA") fullname += data.assessment[0].middle_name + " ";

                var today = new Date();
                var birthDate = new Date(data.assessment[0].date_of_birth);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if(data.assessment[0].sex != null){
                    var sex='';
                    if(data.assessment[0].sex == "01_MALE") sex='Male';
                    else if(data.assessment[0].sex == "02_FEMALE") sex='Female';
                }

                printInfo += `<tr><td class="pdetails">Fullname:</td><td colspan="8">` + fullname + `</td></tr>`;

                if(form == "certificate"){
                    printInfo += `<tr>
                                <td class="pdetails">Address:</td>
                                <td colspan="8">` + data.assessment[0].home_address + `</td>
                            </tr>

                            <tr>
                                <td class="pdetails">Mobile Number:</td>
                                <td>` + data.assessment[0].contact_number + `</td>

                                <td width="20px"></td>

                                <td class="pdetails">Age:</td>
                                <td>` + age + ` years old</td>

                                <td width="15px"></td>

                                <td class="pdetails">Sex:</td>
                                <td>` + sex + `</td>
                            </tr>

                            <tr>
                                <td class="pdetails">PhilHealth Number:</td>
                                <td>` + data.assessment[0].philhealth_number + `</td>

                                <td width="20px"></td>
                                <td class="pdetails">Category:</td>
                                <td>` + data.assessment[0].category_name + `</td>

                                <td width="15px"></td>

                                <td class="pdetails">Barangay:</td>
                                <td>` + data.assessment[0].barangay + `</td>
                            </tr>
                            `;
                }else{
                    
                    printInfo += `<tr>
                                <td class="pdetails">Address:</td>
                                <td colspan="8">` + data.assessment[0].home_address + `</td>
                            </tr>

                            <tr>
                                <td class="pdetails">Mobile Number:</td>
                                <td>` + data.assessment[0].contact_number + `</td>

                                <td width="20px"></td>

                                <td class="pdetails">Age:</td>
                                <td>` + age + ` years old</td>

                                <td width="25px"></td>

                                <td class="pdetails">Sex:</td>
                                <td>` + sex + `</td>
                            </tr>

                            <tr>
                                <td class="pdetails">PhilHealth Number:</td>
                                <td>` + data.assessment[0].philhealth_number + `</td>

                                <td width="20px"></td>
                                <td class="pdetails">Category:</td>
                                <td>` + data.assessment[0].category_name + `</td>

                                <td width="25px"></td>

                                <td class="pdetails">Barangay:</td>
                                <td>` + data.assessment[0].barangay + `</td>
                            </tr>
                            `;
                }
                            
                if(data.vaccinatedFirstDose){
                    let vaccinationDate = new Date(data.vaccinatedFirstDose.vaccination_date);
                    vaccinator = '';
                    if(data.vaccinatedFirstDose.last_name) vaccinator += data.vaccinatedFirstDose.last_name;
                    if(data.vaccinatedFirstDose.suffix)
                        if( data.vaccinatedFirstDose.suffix != "NA")
                            vaccinator += " " + data.vaccinatedFirstDose.suffix;
                    vaccinator += ", ";
                    if(data.vaccinatedFirstDose.first_name) vaccinator += data.vaccinatedFirstDose.first_name + " ";
                    if(data.vaccinatedFirstDose.middle_name && data.vaccinatedFirstDose.middle_name != "NA") vaccinator += data.vaccinatedFirstDose.middle_name[0] + ".";
                    if(form == "certificate"){
                        printInfoFirstDose = `<tr><td style="width: 100px">` + (vaccinationDate.getMonth() + 1) + "&emsp;&emsp;&nbsp;&nbsp;" + vaccinationDate.getDate() + "&emsp;&emsp;&nbsp;" + vaccinationDate.getFullYear().toString().substr(2,2) + `</td>
                                                    <td style="width:105px">` + data.vaccinatedFirstDose.vaccine_name  + `</td>
                                                    <td style="width:100px">` + data.vaccinatedFirstDose.batch_number + `</td>
                                                    <td>` + data.vaccinatedFirstDose.lot_number + `</td>
                                                </tr>
                                                <tr><td style='height:18px'><br></td></tr>
                                                <tr><td colspan="4">` + vaccinator + `</td></tr>`;
                    
                    }else if(form == "assessment"){
                        printInfoFirstDose = `<tr><td style="width: 140px">` + (vaccinationDate.getMonth() + 1) + "&emsp;&emsp;&nbsp;&nbsp;" + vaccinationDate.getDate() + "&emsp;&emsp;&nbsp;" + vaccinationDate.getFullYear().toString().substr(2,2) + `</td>
                                                <td style="width:145px">` + data.vaccinatedFirstDose.vaccine_name  + `</td>
                                                <td style="width:140px">` + data.vaccinatedFirstDose.batch_number + `</td>
                                                <td>` + data.vaccinatedFirstDose.lot_number + `</td>
                                            </tr>
                                            <tr><td style='height:27px'><br></td></tr>
                                            <tr><td colspan="4">` + vaccinator + `</td></tr>`;
                    }
                }
                
                if(data.vaccinatedSecondDose){
                    let vaccinationDate = new Date(data.vaccinatedSecondDose.vaccination_date);
                    vaccinator = '';
                    if(data.vaccinatedSecondDose.last_name) vaccinator += data.vaccinatedSecondDose.last_name;
                    if(data.vaccinatedSecondDose.suffix)
                        if( data.vaccinatedSecondDose.suffix != "NA")
                            vaccinator += " " + data.vaccinatedSecondDose.suffix;
                    vaccinator += ", ";
                    if(data.vaccinatedSecondDose.first_name) vaccinator += data.vaccinatedSecondDose.first_name + " ";
                    if(data.vaccinatedSecondDose.middle_name && data.vaccinatedSecondDose.middle_name != "NA") vaccinator += data.vaccinatedSecondDose.middle_name[0] + ".";
                    if(form == "certificate"){
                        printInfoSecondDose = `<tr><td style="width: 100px">` + (vaccinationDate.getMonth() + 1) + "&emsp;&emsp;&nbsp;&nbsp;" + vaccinationDate.getDate() + "&emsp;&emsp;&nbsp;" + vaccinationDate.getFullYear().toString().substr(2,2) + `</td>
                                                    <td style="width:105px">` + data.vaccinatedSecondDose.vaccine_name  + `</td>
                                                    <td style="width:100px">` + data.vaccinatedSecondDose.batch_number + `</td>
                                                    <td>` + data.vaccinatedSecondDose.lot_number + `</td>
                                                </tr>
                                                <tr><td style='height:18px'><br></td></tr>
                                                <tr><td colspan="4">` + vaccinator + `</td></tr>`;
                    
                    }else if(form == "assessment"){
                        printInfoSecondDose = `<tr><td style="width: 140px">` + (vaccinationDate.getMonth() + 1) + "&emsp;&emsp;&nbsp;&nbsp;" + vaccinationDate.getDate() + "&emsp;&emsp;&nbsp;" + vaccinationDate.getFullYear().toString().substr(2,2) + `</td>
                                                <td style="width:145px">` + data.vaccinatedSecondDose.vaccine_name  + `</td>
                                                <td style="width:140px">` + data.vaccinatedSecondDose.batch_number + `</td>
                                                <td>` + data.vaccinatedSecondDose.lot_number + `</td>
                                            </tr>
                                            <tr><td style='height:25px'><br></td></tr>
                                            <tr><td colspan="4">` + vaccinator + `</td></tr>`;
                    }
                                            
                }
                generateBR(data.assessment[0].qrcode);
                var canvas = document.getElementsByTagName("canvas")[0];
                var image = new Image();
                
                //edited online (id="printImageQr")
                $("#printImageQr").remove();
                
                $("#printQrCode").append(`<img src="${canvas.toDataURL()}" id="printImageQr"></td>`);

                $("#assessmentNumber").html(data.assessment[0].qrcode);
                
                if(form == "certificate"){
                    
                    $("#assessmentNumber").css({
                        "margin-top" : "-7px",
                        "margin-left" : "-373px",
                        "font-size" : "12px"
                    });
                    
                    $("#printQrCode").css({
                        "margin-top" : "310px",
                        "margin-left" : "400px",
                        "margin-right" : "0px",
                        "height" : "90px",
                        "width" : "90px"
                    });
                    
                    $("#printInfo").css({
                        "width" : "500px",
                        "margin-top" : "-180px",
                        "margin-left" : "-10px",
                        "font-size" : "8px"
                    });
                    
                    $("#printDiv").css({
                        "width" : "68%",
                        "height" : "1020px",
                        "margin-top" : "-290px"
                    });         
                    
                    $(".pdetails").css({
                        "width": "75px"
                    });
                    
                    $("#printInfoFirstDose, #printInfoSecondDose").each(function(){
                        $(this).css({
                            "margin-left": "225px",
                            "text-align": "left",
                            "font-size": "8px",
                            "padding": "0px"
                        });
                    })

                    $("#printInfoFirstDose").css({
                        "margin-top": "-105px"
                    }); 
                    
                    $("#printInfoSecondDose").css({
                        "margin-top": "-62px"
                    });   
                    
                }else if(form == "assessment"){
                    $("#assessmentNumber").css({
                        "margin-top" : "-15px",
                        "margin-left" : "-540px",
                        "font-size" : "16px"
                    });
                    
                    $("#printQrCode").css({
                        "margin-top" : "468px",
                        "margin-left" : "0px",
                        "margin-right" : "-540px",
                        "height" : "130px",
                        "width" : "130px"
                    });
                    
                    $("#printInfo").css({
                        "width" : "700px",
                        "margin-top" : "-182px",
                        "margin-left" : "-20px",
                        "font-size" : "11px"
                    });
                    
                    $("#printDiv").css({
                        "width" : "96%",
                        "height" : "1500px",
                        "margin-top" : "-420px"
                    });
                    
                    $(".pdetails").css({
                        "width": "100px"
                    });
                    
                    
                    $("#printInfoFirstDose, #printInfoSecondDose").each(function(){
                        $(this).css({
                            "margin-left": "120px",
                            "text-align": "left",
                            "font-size": "11px",
                            "padding": "0px"
                        });
                    })

                    $("#printInfoFirstDose").css({
                        "margin-top": "-64px"
                    }); 
                    
                    $("#printInfoSecondDose").css({
                        "margin-top": "-1px"
                    });  
                
                }
                
                $("#printInfo").html(printInfo);
                $("#printInfoFirstDose").html(printInfoFirstDose);
                $("#printInfoSecondDose").html(printInfoSecondDose);
                
                $("#printDiv").print();
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
    
    @can('permission', 'printConsent')
    const printConsent = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccination/assessment-details/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $.ajax({
                    url: '/covid19vaccine/vaccination/assessment-status/' + id,
                    data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    }
                });
            
                let printInfo = "";

                 let fullname = "";
                if(data.assessment[0].last_name) fullname += data.assessment[0].last_name;
                if(data.assessment[0].suffix)
                    if( data.assessment[0].suffix != "NA")
                        fullname += " " + data.assessment[0].suffix;
                fullname += ", ";
                if(data.assessment[0].first_name) fullname += data.assessment[0].first_name + " ";
                if(data.assessment[0].middle_name && data.assessment[0].middle_name != "NA") fullname += data.assessment[0].middle_name + " ";

                var today = new Date();
                var birthDate = new Date(data.assessment[0].date_of_birth);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if(data.assessment[0].sex != null){
                    var sex='';
                    if(data.assessment[0].sex == "01_MALE") sex='Male';
                    else if(data.assessment[0].sex == "02_FEMALE") sex='Female';
                }

                printInfo += `<tr><td class="pdetails">Fullname:</td><td colspan="8">` + fullname + `</td></tr>`;

                printInfo += `<tr>
                                <td class="pdetails">Address:</td>
                                <td colspan="8">` + data.assessment[0].home_address + `</td>
                            </tr>

                            <tr>
                                <td class="pdetails">Mobile Number:</td>
                                <td>` + data.assessment[0].contact_number + `</td>

                                <td width="25px"></td>

                                <td class="pdetails">Age:</td>
                                <td>` + age + ` years old</td>

                                <td width="20px"></td>

                                <td class="pdetails">Sex:</td>
                                <td>` + sex + `</td>
                            </tr>

                            <tr>
                                <td class="pdetails">PhilHealth Number:</td>
                                <td>` + data.assessment[0].philhealth_number + `</td>

                                <td width="25px"></td>
                                <td class="pdetails">Category:</td>
                                <td>` + data.assessment[0].category_name + `</td>

                                <td width="20px"></td>

                                <td class="pdetails">Barangay:</td>
                                <td>` + data.assessment[0].barangay + `</td>
                            </tr>
                            `;

                generateBR(data.assessment[0].qrcode);
                var canvas = document.getElementsByTagName("canvas")[0];
                var image = new Image();
                $("#assessmentNumber").html(data.assessment[0].qrcode);
        	
        	    let options = {};
                $.map(vaccine,
                    function(o) {
                        options[o] = o;
                    });
                const { value: vacc } = Swal.fire({
                  title: 'Select vaccine',
                  input: 'select',
                  inputOptions: options,
                  showCancelButton: true,
                  inputValidator: (value) => {
                    return new Promise((resolve) => {
                      if (value == "ASTRAZENECA") {
                        swal.close();
                        $("#printConsentAstraInfo").html(printInfo);
                        $("#printConsentAstra").print();
                      } else if(value == "SINOVAC") {
                        swal.close();
                        $("#printConsentSinovacInfo").html(printInfo);
                        $("#printConsentSinovac").print();
                      }else{
                        swal.close();
                        swal.fire({
                            title: "Oops! something went wrong.",
                            html: "Consent Form not available",
                            type: "error",
                            footer : '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                        });
                      }
                    })
                  }
                })
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
    
    //Validate Action
    @can('permission', 'viewRegistrationAndValidation')
    const validateAction = (data,action,modalTitle) =>{
        $("#verifying_patient").modal("show");

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
            if(data[0].sex == "01_MALE") sex='Male';
            else if(data[0].sex == "02_FEMALE") sex='Female';
            $('#show_sex').text(sex);
        }
        else
            $('#show_sex').text(" ");
        drawTableBody(data[0]);
    }
    @endcan

    const generateBR = (title) => {
        $(".imgblock").remove();
        var base64images = new base64image();

        var qrcodeTpl = document.getElementById("qrcodeTpl").innerHTML;

        var container = document.getElementById('printContainer');

        var qrcodeHTML = qrcodeTpl.replace(/\{title\}/, base64images.ecabsLogo(title)[0].config.text).replace(/{i}/, 0);

        container.innerHTML+=qrcodeHTML;

        var t=new QRCode(document.getElementById("qrcode_"+0), base64images.ecabsLogo(title)[0].config);
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
</script>
@endsection
