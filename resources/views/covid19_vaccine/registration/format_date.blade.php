
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
        font-weight: bold;
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
        margin-top: -10px;
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
                                        <th>Status</th>
                                        <th>Date of Birth</th>
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
    
    <!-- Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="date_modal">
        <div class="modal-dialog modal-sm" role="document">
            <form id="update_date_form" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color: rgb(253, 245, 218)">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h5 class="modal-title text-center"><strong> Update Date of Birth</strong></h5>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body" style="max-height: calc(100vh - 200px);">
                        <div class="row">
                            <div class="col-md-12">
                            
                                <div class="form-group">
                                    <label>Fullname</label>
                                    <input disabled type='text' class="form-control" id='fullname' name="fullname"/>
                                </div>
                            
                                <!-- Date of Birth -->
                                <div class="form-group">
                                    <label>Date Of Birth </label><small>(OLD)</small> <label for="old_date_of_birth" class="error"></label>
                                    <input type='text' class="form-control" id='old_date_of_birth' name="old_date_of_birth" disabled
                                    placeholder="Date of Birth"/>
                                </div>
                                
                                
                                <!-- Date of Birth -->
                                <div class="form-group">
                                    <label>Date Of Birth </label><small>(mm/dd/yyyy)</small> <label for="date_of_birth" class="error"></label>
                                    
                                    <input type='text' class="form-control datetimepicker" id='date_of_birth' name="date_of_birth" max="9999-12-31"
                                    placeholder="Date of Birth"/>
                                </div>
                            </div>
                        </div>
                    
                    <input type="hidden" id="edit_id">
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-fill"> Save</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
<script>
    let vaccine = [];
    
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('registration.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "status" },
                { "data": "date_of_birth" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2, 3 ] },
            ]
        });
        
        //create account
        $("#update_date_form").validate({
            rules: {
                date_of_birth: {
                    required: true,
                    minlength:3,
                    
                },
            },
            messages:{
                last_name:'Date of Birth is required!',
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Update Date of Birth?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!',
                    html: "<b>Data Pre-Registration",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        var formData = new FormData($("#update_date_form").get(0));
                        
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url: "/covid19vaccine/registration/update-date-of-birth/" + $("#edit_id").val(),
                            type: "POST",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            beforeSend: function(){
                                processObject.showProcessLoader();
                            },
                            success: function (response) {
                                if(response.success){
                                    swal({
                                        title: "Date of Birth Updated!",
                                        text: "Successfully!",
                                        type: "success",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    }).then(function() {
                                        $("#date_modal").modal("hide");
                                    });
                                    datatable.ajax.reload( null, false );
                                }else{
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        html: "<br>" + response.messages + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</br>",
                                        type: "error",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    });
                                }
    
                                //process loader false
                                processObject.hideProcessLoader();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    html: "<b>" + errorThrown + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                    type: "error",
                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                                
                                $('#submit').prop('disabled', false);
                            },
                            complete: function(){
                                processObject.hideProcessLoader();
                            },
                        });
                            
                    }
                });
            }
        });
    });
    
    @can('permission', 'changeDateFormat')
    const changeDate = (id) =>{
    
        $.ajax({
            url:'/covid19vaccine/registration/get-info/' + id,
            type:'GET',
            dataType:'JSON',
            success:function(response){
                $("#fullname").val(response.fullname);
                $("#old_date_of_birth").val(response.date_of_birth);
                $("#edit_id").val(id);
            },
        })
        
        $("#date_modal").modal("show");
    }
    @endcan
</script>
@endsection
