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
        
        .disabled a {
            text-decoration: line-through;
            pointer-events:none;
            
        }
    </style>
@endsection
@section('content')

<!-- Display All Data -->
<div class="content">
    <div class="container-fluid">
        
    @can('permission', 'createScholarProgram')
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
                                    <p>Total Scholarship Program</p>
                                    <b id="patientCounter">00</b>
                                </div>
                            </div>
                        </div>
    
                    </div>
                    <div class="card-content">
                        <a class="btn btn-block btn-primary" onclick="generateModule()" data-toggle="tooltip" title="" data-original-title="Click here to generate new program.">
                            <span class="btn-label">
                                <i class="fa fa-cog"></i>
                            </span>
                            Generate New Scholarship Program Module
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <h4 class="card-title"><b>Scholarship Program Module/s</b></h4>
                                <p class="category">Update Delete Category</p>
                            </div>
                            <div class="col-lg-2">

                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <table id="moduleDataTable" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Module Name</th>
                                    <th>Status</th>
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
                    <div class="card-footer text-center">
                        <button id="show_scholarship_modal" class="btn btn-info btn-fill btn-wd">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            SAVE NEW SCHOLARSHIP PROGRAM</button>
                    </div>

                </div>
            </div>
        </div>
        @endcan

        <!-- List of Programs -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <h4 class="card-title"><b>Program List</b></h4>
                                <p class="category">Manage programs</p>
                            </div>
                            <div class="col-lg-2">

                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <table id="programDataTable" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th style="width: 20px;"></th>
                                    <th>Program</th>
                                    <th>Department</th>
                                    <th>Program Status</th>
                                    <th>Modules</th>
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

<!-- Modal For Add New Program-->
<div class="modal fade in" tabindex="-1" role="dialog" id="create_scholarship_modal">
    <div class="modal-dialog" role="document">
        <form id="create_program_form" method="post">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Add Scholarship Program</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <!-- Course Code -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="programTitle">PROGRAM TITLE:</label>
                            <input type="text" class="form-control" name="programTitle" id="programTitle"
                                placeholder="Enter Course Code">
                        </div>
                    </div>
                    <!-- End Course Code -->
                    <!-- Course Description -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="programDescription">PROGRAM DESCRIPTION:</label>
                            <input type="text" class="form-control" name="programDescription"
                                id="programDescription" placeholder="Enter Course Description">
                        </div>
                    </div>
                    <!-- End Course Description -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="saveNewProgram">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Modal for Add New Program -->

<!--Modal for Adding -->
<div class="modal fade" id="show_modal_module_category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title">Program Module Information</h4>
            </div>
            <div class="modal-body" style="min-height: calc(40vh - 80px); max-height: calc(100vh - 200px); overflow-y: auto; background-color:#f7f7f7;">
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="col-md-1">
                            <label class="form-check-label" style="font-size:17px">Module:</label>
                        </div>
                        <div class="col-md-5">
                            <!-- Educational Attainment -->
                            <div class="form-group">
                                <select class="form-control selectpicker" data-live-search="true" id="educational_attainment" name="educational_attainment">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="form-check-label" style="font-size:18px">
                                    <input type="checkbox" class="form-check-input" name="year_level" id="year_level"> Year Level
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="form-check-label" style="font-size:16px">
                                    <input type="checkbox" class="form-check-input" name="passing_grade" id="passing_grade"> Accept Passing Grade
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr style="border:0.5px solid #F1EAE0">
                </div>
                <div id="moduleBody" hidden>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>Add EXAMINATION for this scholarship program module</b></td>
                                    <td>
                                        <div class="bootstrap-switch-container">
                                            <input type="checkbox" class="switch-icon" id="questionnaireSwitch">
                                        </div>
                                    </td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none; opacity: 0.4;" id="examDataTable" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Questionnaire</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>Add REQUIREMENTS for this scholarship program module</b></td>
                                    <td>
                                        <div class="bootstrap-switch-container">
                                            <input type="checkbox" class="switch-icon" id="requirementsSwitch">
                                        </div>
                                    </td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none; opacity: 0.4;" id="requirementsDataTable" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Requirement</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>Add EVENTS for this scholarship program module</b></td>
                                    <td>
                                        <div class="bootstrap-switch-container">
                                            <input type="checkbox" class="switch-icon" id="eventSwitch">
                                        </div>
                                    </td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none; opacity: 0.4;" id="eventDataTable" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>Add GRADES for this scholarship program module</b></td>
                                    <td>
                                        <div class="bootstrap-switch-container">
                                            <input type="checkbox" class="switch-icon" id="gradeSwitch">
                                        </div>
                                    </td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none; opacity: 0.4;" id="tableGrade" class="table table-bordered table-sm table-hover" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th colspan="5" class="text-center" style="background:aqua">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Grade <b>(From)</b></b></th>
                                        <th>Grade <b>(To)</b></th>
                                        <th>Required Exam</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="cat_name[]" placeholder="Enter Category Name" class="form-control" value="EA1" disabled></td>
                                        <td><input type="number" name="grade_from[]" placeholder="Grade From?" class="form-control" min="0" ></td>
                                        <td><input type="number" name="grade_to[]" placeholder="Grade To?" class="form-control" min="0" ></td>
                                        <td>
                                            <input type="radio" name="required_exam[]" value="1"> <label>Required</label><br>
                                            <input type="radio" name="required_exam[]" value="0" checked> <label>Not Required</label>
                                        </td>
                                        <td><a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields"><span class="btn-label"><i class="ti-plus"></i></span></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-check-label" style="font-size:18px" for="">Required Units</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" id="units" name="units" value="0" class="form-control" min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                        
                             </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" id="addProgramModule" class="btn btn-info btn-fill btn-wd">Add Program Module</button>
                       
            </div>
        </div>
    </div>
</div>
<!--End Modal for Adding -->

<!--Modal for View -->
<div class="modal fade" id="view_modal_module_category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title">Program Module Information</h4>
            </div>
            <div class="modal-body" style="min-height: calc(30vh - 60px); max-height: calc(100vh - 200px); overflow-y: auto; background-color:#f7f7f7;">
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" style="font-size:17px">
                                    <label>Module :<label>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Educational Attainment -->
                            <div class="form-group">
                                <label id="show_educational_attainment" name="show_educational_attainment"></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="form-check-label" style="font-size:18px">
                                    <input type="checkbox" class="form-check-input" name="show_year_level" id="show_year_level"> Year Level
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="form-check-label" style="font-size:16px">
                                    <input type="checkbox" class="form-check-input" name="show_passing_grade" id="show_passing_grade"> Accept Passing Grade
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr style="border:0.5px solid #F1EAE0">
                </div>
                <div id="examDiv">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>EXAMINATION for this scholarship program module</b></td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none;" id="showExam" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Questionnaire</th>
                                    <th>Status</th>
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
                
                <div id="requirementDiv">
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>REQUIREMENTS for this scholarship program module</b></td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none;" id="showRequirement" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Requirement</th>
                                    <th>Status</th>
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

                <div id="eventDiv">
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>EVENTS for this scholarship program module</b></td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none;" id="showEvent" class="table table-bordered table-sm table-hover" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Status</th>
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

                <div id="gradeDiv">
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-hover">
                                <!--Table body-->
                                <tr class="tbl-program-tr-color">
                                    <td class="tbl-program"><b>GRADES for this scholarship program module</b></td>
                                </tr>
                                <!--Table body-->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <table style="pointer-events:none;" id="showGrade" class="table table-bordered table-sm table-hover" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="text-center" style="background:aqua">
                                            {{-- <a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields"><span class="btn-label"><i class="ti-plus"></i></span></a> Categories --}}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Grade <b>(From)</b></b></th>
                                        <th>Grade <b>(To)</b></th>
                                        <th>Required Exam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-check-label" style="font-size:18px" for="">Required Units</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="number" id="show_units" name="show_units" value="0" class="form-control" min="0" placeholder="0" style="pointer-events:none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Modal for View -->
@endsection

@section('js')
<script>
    let arrayRequirementId = [];
    let arrayEventId = [];
    let arrayExam = [];
    let arrayGrade = [];
    let examId;
    let objectRequirement = {};
    let objectEvent = {};
    let objectExamination = {};
    let objectGrade = {};

    let programModule = [];

    $(document).ready(function () {
       let questionSwitch = $("#questionnaireSwitch" );
       let eventSwitch = $("#eventSwitch" );
       let requirementsSwitch = $("#requirementsSwitch" );
       let gradeSwitch = $("#gradeSwitch" );
       let counter = 1;

       //Datatables
       eventDataTable = $('#eventDataTable').DataTable();    
       requirementsDataTable = $('#requirementsDataTable').DataTable();
       examDataTable = $('#examDataTable').DataTable();
       moduleDataTable = $("#moduleDataTable").DataTable();
       programDataTable = $("#programDataTable" ).DataTable();

        //program datatable
        refreshProgramDataTable();
        
        //get barangays
        $.ajax({
            url:'{{ route('educational-attainment.find-type') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="educational_attainment"]').append('<option value='+response[index].id+'>'+ response[index].title+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        })

        //questionnaire switch 
        $('#questionnaireSwitch').on('switchChange.bootstrapSwitch', function(e) {
            if(questionSwitch.is(':checked')){
                Swal.fire({
                    type: "success",
                    title: "Examination Questionnaire Opened Successfully.",
                    text: "Please select your perspective questionnaire."
                });
                $('#examDataTable').DataTable().clear().destroy();
                $('#examDataTable').DataTable({
                    "serverSide": true,
                    "ajax":{
                        "url": '{{ route('examination.findall') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}", "action": "programManagement"}
                    },
                    "columns": [
                        { "data": "title" },
                        { "data": "status" },
                        { "data": "action" },
                    ],
                    "columnDefs": [
                        { "orderable": false, "targets": [1, 2] }
                    ]
                });
                $('#examDataTable').css({'pointer-events' : 'auto', 'opacity' : '1'});
            }else{
                // Swal.fire({
                //     type: "info",
                //     title: "Examination Questionnaire Closed Successfully.",
                //     text: "Transaction already declined."
                // });
                
                arrayExam = [];
                $('#examDataTable').DataTable().clear().destroy();
                examDataTable = $('#examDataTable').DataTable();
                $('#examDataTable').css({'pointer-events' : 'none', 'opacity' : '0.5'});
            }
        });

        //event switch 
        $('#eventSwitch').on('switchChange.bootstrapSwitch', function(e) {
            if(eventSwitch.is(':checked')){
                Swal.fire({
                    type: "success",
                    title: "Examination Questionnaire Opened Successfully.",
                    text: "Please select your perspective questionnaire."
                });

                $('#eventDataTable').DataTable().clear().destroy();
                $('#eventDataTable').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "ajax":{
                        "url": '{{ route('event.findall') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}", "action": "programManagement"}
                    },
                    "columns": [
                        { "data": "event" },
                        { "data": "status" },
                    ],
                    "columnDefs": [
                        { "orderable": false, "targets": [1, 2] }
                    ],
                    "aoColumnDefs": [
                        {
                            "aTargets": [2],
                            "mData": "id",
                            "mRender": function (data, type, full) {
                                let event = full["event"];
                                if(arrayEventId.length==0){
                                    return '<input type="checkbox" onclick="ctrToggleEvent(this.value, this.name)" name="' + event + '" value="'+ data +'"/>';
                                }else{
                                    var flag =false;
                                    for (let index = 0; index < arrayEventId.length; index++) {
                                        if(arrayEventId[index]==data){
                                            flag = true;
                                            break;
                                        }
                                    }
                                    if(flag){
                                        return '<input type="checkbox" checked onclick="ctrToggleEvent(this.value, this.name)" name="' + event + '" value="'+ data +'"/>';
                                    }else{
                                        return '<input type="checkbox" onclick="ctrToggleEvent(this.value, this.name)" name="' + event + '" value="'+ data +'"/>';
                                    }
                                }
                            }
                        }
                    ],
                });
                $('#eventDataTable').css({'pointer-events' : 'auto', 'opacity' : '1'});
            }else{
                // Swal.fire({
                //     type: "info",
                //     title: "Examination Questionnaire Closed Successfully.",
                //     text: "Transaction already declined."
                // });
                arrayEventId = [];
                $('#eventDataTable').DataTable().clear().destroy();
                eventDataTable = $('#eventDataTable').DataTable();
                $('#eventDataTable').css({'pointer-events' : 'none', 'opacity' : '0.5'});
            }
        });

        //requirements switch 
        $('#requirementsSwitch').on('switchChange.bootstrapSwitch', function(e) {
            if(requirementsSwitch.is(':checked')){
                Swal.fire({
                    type: "success",
                    title: "Examination Questionnaire Opened Successfully.",
                    text: "Please select your perspective questionnaire."
                });
                
                $('#requirementsDataTable').DataTable().clear().destroy();
                $('#requirementsDataTable').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "ajax":{
                        "url": '{{ route('requirement.find-all') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}", "action": "programManagement"}
                    },
                    "columns": [
                        { "data": "name" },
                        { "data": "status" },
                    ],
                    "columnDefs": [
                        { "orderable": false, "targets": [ 1,2] }, 
                    ],
                    "aoColumnDefs": [
                        {
                            "aTargets": [2],
                            "mData": "id",
                            "mRender": function (data, type, full) {
                                let requirement = full["name"];
                                if(arrayRequirementId.length==0){
                                        return '<input type="checkbox" onclick="ctrToggleRequirement(this.value, this.name)" name="' + requirement + '" value="'+ data +'"/>';
                                }else{
                                    var flag =false;
                                    for (let index = 0; index < arrayRequirementId.length; index++) {
                                        if(arrayRequirementId[index]==data){
                                            flag = true;
                                            break;
                                        }
                                    }
                                    if(flag){
                                        return '<input type="checkbox" checked onclick="ctrToggleRequirement(this.value, this.name)" name="' + requirement + '" value="'+ data +'"/>';
                                    }else{
                                        return '<input type="checkbox" onclick="ctrToggleRequirement(this.value, this.name)" name="' + requirement + '" value="'+ data +'"/>';
                                    }
                                }
                            }
                        }
                    ],
                });
                $('#requirementsDataTable').css({'pointer-events' : 'auto', 'opacity' : '1'});
            }else{
                // Swal.fire({
                //     type: "info",
                //     title: "Examination Questionnaire Closed Successfully.",
                //     text: "Transaction already declined."
                // });
                arrayRequirementId = [];
                $('#requirementsDataTable').DataTable().clear().destroy();
                requirementsDataTable = $('#requirementsDataTable').DataTable();
                $('#requirementsDataTable').css({'pointer-events' : 'none', 'opacity' : '0.5'});
            }
        });

        //grades switch 
        $('#gradeSwitch').on('switchChange.bootstrapSwitch', function(e) {
            if(gradeSwitch.is(':checked')){
                Swal.fire({
                    type: "success",
                    title: "Examination Questionnaire Opened Successfully.",
                    text: "Please select your perspective questionnaire."
                });

                $('#tableGrade').css({'pointer-events' : 'auto', 'opacity' : '1'});
                // $('#add_fields').css({'pointer-events' : 'none', 'opacity' : '1'});
            }else{
                // Swal.fire({
                //     type: "info",
                //     title: "Examination Questionnaire Closed Successfully.",
                //     text: "Transaction already declined."
                // });

                $('#tableGrade').css({'pointer-events' : 'none', 'opacity' : '0.5'});
            }
        });

        //add program module
        $("#addProgramModule").click(function(){
            let cat_name = document.getElementsByName('cat_name[]');
            let gradeFromList = document.getElementsByName('grade_from[]'); 
            let gradeToList = document.getElementsByName('grade_to[]');
            let requiredGrade = "required_exam[]"; 
            let yearLevel = 0;
            let passingGrade = 0;
            arrayGrade = [];
            programObject = {};
            objectGrade = {};
            programEA = {};
            
            if($("#year_level").is(':checked')){
                yearLevel = 1;
            }
            if($("#passing_grade").is(':checked')){
                passingGrade = 1;
            }

            if(gradeSwitch.is(':checked')){
                if(gradeFromList[0].value != "" && gradeToList[0].value != ""){
                    for(let index = 0; index < cat_name.length; index++){
                        if(index > 0)
                            requiredGrade = "required_exam" + (index+1) + "[]";
                        objectGrade = {};
                        objectGrade['cat_name'] = cat_name[index].value;
                        objectGrade['grade_from'] = gradeFromList[index].value;
                        objectGrade['grade_to'] = gradeToList[index].value;
                        objectGrade['required_exam'] = $("[name='"+requiredGrade+"']:checked").val();
                        arrayGrade.push(objectGrade);
                    }
                }
            }
            programEA["ea_id"] = $("#educational_attainment").val();
            programEA["ea_title"] = $("#educational_attainment option:selected").text();

            programObject["module"] = programEA;
            programObject["yearLevel"] = yearLevel;
            programObject["passingGrade"] = passingGrade;
            programObject["exam"] = arrayExam;
            programObject["requirement"] = arrayRequirementId;
            programObject["event"] = arrayEventId;
            programObject["grade"] = arrayGrade;
            programObject["requiredUnits"] = $("#units").val();

            //if no module selected
            if(programEA["ea_id"] == null){
                Swal.fire({
                    type: "error",
                    title: "Error!",
                    text: "Please select module!"
                })
            }
            else{
                /* check if exist */
                const findExist = programModule.find(data => { return data.module.ea_id == programEA.ea_id; });
                if(findExist){
                    Swal.fire({
                        type: "error",
                        title: "Error!",
                        text: "Program Module already exist!"
                    }).then(function(){
                        $('#show_modal_module_category').modal('hide');
                    });
                }else{
                    const index = programModule.length;
                    /* push to array */
                    programModule.push(programObject);

                    /* append data to datatable */
                    moduleDataTable.row.add([
                        programEA.ea_title,
                        '<label class="label label-primary">ACTIVE</label>',
                        '<button class="btn btn-xs btn-info btn-fill btn-rotate view" onclick="viewProgram('+ index +')"><i class="ti-eye"></i> VIEW</button> <button class="btn btn-xs btn-danger btn-fill btn-rotate remove" onclick="removeProgram('+ index +')"><i class="ti-trash"></i> DELETE</button>',
                    ]).draw( false );

                    Swal.fire({
                        type: "success",
                        title: "Success!",
                        text: "Program Module Added Successfully."
                    }).then(function(){
                        $('#show_modal_module_category').modal('hide');
                        arrayExam = [];
                        arrayRequirementId = [];
                        arrayEventId = [];
                        arrayGrade = [];

                        if(questionSwitch.is(':checked')) $("#questionnaireSwitch").trigger("click");
                        if(requirementsSwitch.is(':checked')) $("#requirementsSwitch").trigger("click");
                        if(eventSwitch.is(':checked')) $("#eventSwitch").trigger("click");
                        if(gradeSwitch.is(':checked')) $("#gradeSwitch").trigger("click");
                        if($("#year_level").is(':checked')) $("#year_level").trigger("click");
                        if($("#passing_grade").is(':checked')) $("#passing_grade").trigger("click");
                        $("#units").val(0);

                        if(cat_name.length > 1){
                            for(let index = cat_name.length; index > 1; index--){
                                $("#remove_field").trigger("click");
                            }
                        }
                        $("[name='grade_from[]']").val("");
                        $("[name='grade_to[]']").val("");
                        
                        $("#questionnaireSwitch" ).prop( "checked", false );
                        $("#eventSwitch" ).prop( "checked", false );
                        $("#requirementsSwitch" ).prop( "checked", true );
                        $("#gradeSwitch" ).prop( "checked", false );
                        $('[name="educational_attainment"]').val(0);
                        $("#moduleBody").prop("hidden", true);
                        $('.selectpicker').selectpicker('refresh');
                    });
                }           
            }     

        });

        //add grade field
        $('#add_fields').on('click', function(e){
            e.preventDefault();
            if(counter < 5){
                counter++;
                $('#tableGrade tbody').append('<tr><td><input type="text" name="cat_name[]" placeholder="Enter Category Name" class="form-control" value="EA'+counter+'" disabled></td><td><input type="number" name="grade_from[]" min="0" placeholder="Grade From?" class="form-control"></td><td><input type="number" name="grade_to[]" min="0" placeholder="Grade To?" class="form-control"></td><td><input type="radio" name="required_exam'+counter+'[]" value="1"> <label>Required</label><br><input type="radio" name="required_exam'+counter+'[]" value="0" checked> <label>Not Required</label></td><td><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-remove"></i></a></td></tr>');
            }else{
                swal('Warning!', 'Maximun of 5 fields only!', 'warning');
            }
        });
        
        //remove grade field
        $('#tableGrade tbody').on("click", "#remove_field", function(e){ 
            let cat_name = document.getElementsByName('cat_name[]');
            e.preventDefault();
            $(this).parent().parent().remove();

            counter--;
            for(let index = 0; index < cat_name.length; index++){
                cat_name[index].value = "EA" + (index+1);
            }
        });

        $("#educational_attainment").change(function(){
            if($("#educational_attainment").val() > 0 )
                $("#moduleBody").prop("hidden", false);
            else
                $("#moduleBody").prop("hidden", true);
        })
        
        // $("#saveNewProgram").click(function(){
        $("#create_program_form").validate({
            rules: {
                programTitle:{
                    required:true,
                    minlength:3
                }
            },
            submitHandler: function (form) {
                const {module, exam, requirement, event, grade} = programModule;
                Swal.fire({
                    title: 'Create Now?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        // let formData = new FormData($("#create_program_form").get(0));
                        // formData.append('programModule', programModule);
                        let programTitle = $("#programTitle").val();
                        let programDescription = $("#programDescription").val();
                        
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '/iskocab/scholarship-program/',
                            type: "POST",
                            data: {_token: "{{csrf_token()}}", "programModule" : programModule, "programTitle" : programTitle, "programDescription" : programDescription },
                            success: function (data) {
                                if (data.success) {
                                    swal({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success"
                                    })
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    refreshProgramDataTable();
                                    $("#create_scholarship_modal").modal("hide");
                                    $("#programTitle").val();
                                    $("#programDescription").val();
                                    programModule = [];
                                    $('#moduleDataTable').DataTable().clear().destroy();
                                    moduleDataTable = $('#moduleDataTable').DataTable();
                                    datatable.ajax.reload( null, false);
                                } else {
                                    swal.fire({
                                        title: data.messages,
                                        text: "Oops! something went wrong.",
                                        type: "error"
                                    })
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                                
                                $("#create_scholarship_modal").modal("hide");
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
  
        $("#show_scholarship_modal").click(function(){
            if(programModule == ""){
                swal.fire({
                    title: "Please Add Module",
                    text: "Oops! something went wrong.",
                    type: "error"
                })
            }
            else
                $("#create_scholarship_modal").modal("show");
        });
        
        //disabled year level in view
        $("#show_year_level").click(function () { return false; });
        $("#show_passing_grade").click(function () { return false; });

        //Show grading system
        $('#programDataTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = programDataTable.row( tr );
    
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

    const generateModule = () => {
        $('#show_modal_module_category').modal('show');
    }
    
    //toggle status of checkbox in adding of requirement
    const ctrToggleRequirement = (value, requirement) => { 
        let objectRequirement = {};
        let flag = false;
        for (let index = 0; index < arrayRequirementId.length; index++) {
            flag = false;
            if(arrayRequirementId[index]["requirementId"]==value){
                flag = true;
                arrayRequirementId.splice(index,1);
                break;
            }
        }
        if(flag == false){
            objectRequirement["requirementId"] = value;
            objectRequirement["requirement"] = requirement;
            objectRequirement["status"] = "Active"; 
            arrayRequirementId.push(objectRequirement);
        }

        $('#txtItems').val(arrayRequirementId.length);

        //toggle save staff
        if(arrayRequirementId.length > 0){
            $("#saveStaff").prop("disabled", false);
        }else{
            $("#saveStaff").prop("disabled", true);
        }
    }

    //toggle status of checkbox in adding of event
    const ctrToggleEvent = (value, event) => {
        let objectEvent = {};
        let flag =false;
        for (let index = 0; index < arrayEventId.length; index++) {
            flag=false;
            if(arrayEventId[index]["eventId"]==value){
                flag = true;
                arrayEventId.splice(index,1);
                break;
            }   
        }
        if(flag == false){
            objectEvent["eventId"] = value;
            objectEvent["event"] = event;
            objectEvent["status"] = "Active"; 
            arrayEventId.push(objectEvent);
        }
        // console.log(arrayEventId);
        $('#txtItems').val(arrayEventId.length);

        //toggle save staff
        if(arrayEventId.length > 0){
            $("#saveStaff").prop("disabled", false);
        }else{
            $("#saveStaff").prop("disabled", true);
        }
    }

    const addExam = (id, title) =>{
        let objectExamination = {};

        $('#exam' + examId).show();
        $('#exam' + examId).html("<i class='fa fa-plus'></i> SELECT EXAM");
        $('#exam' + examId).prop("class", "btn btn-xs btn-warning btn-fill btn-rotate");

        examId = id;

        objectExamination["id"] = id;
        objectExamination["title"] = title; 
        
        arrayExam.push(objectExamination);

        $('#exam' + id).text("SELECTED");
        $('#exam' + id).prop("class", "btn btn-xs btn-success btn-fill btn-rotate");
        // $('#exam' + id).hide();

        Swal.fire({
            type: "success",
            title: "Examination Questionnaire Added Successfully.",
            text: "Please select your perspective questionnaire."
        });
    }

    //view program module
    const viewProgram = (index) =>{
        $("#showExam tbody").empty();
        $("#showRequirement tbody").empty();
        $("#showEvent tbody").empty();
        $('#showGrade tbody').empty();
        $("#view_modal_module_category").modal("show");

        const {module, exam, requirement, event, grade, requiredUnits, yearLevel, passingGrade} = programModule[index];
        $("#show_educational_attainment").text(module.ea_title);

        //disabled year level in view
        if(yearLevel == 1){
            $('#show_year_level').prop('checked', true);
        }
        else{            
            $('#show_year_level').prop('checked', false);
        }

        //disabled passing grade in view
        if(passingGrade == 1){
            $('#show_passing_grade').prop('checked', true);
        }
        else{            
            $('#show_passing_grade').prop('checked', false);
        }

        //show required units
        $("#show_units").val(requiredUnits);

        if(exam.length){
            $("#examDiv").show();
            $("#showExam tbody").append(`<tr>
                                            <td>
                                                <label id="show_educational_attainment" name="show_educational_attainment">` + exam[0].title + `</label>
                                            </td>
                                            <td>
                                                <label class="label label-primary">ACTIVE</label>
                                            </td>
                                        </tr>`);
        }else{
            $("#examDiv").hide();
        }

        if(requirement.length > 0){
            $("#requirementDiv").show();
            for(let index = 0; index<requirement.length; index++){
                $("#showRequirement tbody").append(`<tr>
                                                <td>
                                                    <label id="show_educational_attainment" name="show_educational_attainment">` + requirement[index].requirement + `</label>
                                                </td>
                                                <td>
                                                    <label class="label label-primary">ACTIVE</label>
                                                </td>
                                            </tr>`);           
            }
        }else{
            $("#requirementDiv").hide();
        }

        if(event.length > 0){
            $("#eventDiv").show();
            for(let index = 0; index<event.length; index++){
                $("#showEvent tbody").append(`<tr>
                                                <td>
                                                    <label id="show_educational_attainment" name="show_educational_attainment">` + event[index].event + `</label>
                                                </td>
                                                <td>
                                                    <label class="label label-primary">ACTIVE</label>
                                                </td>
                                            </tr>`);           
            }
        }else{
            $("#eventDiv").hide();
        }
        if(grade.length > 0){
            if(grade[0].grade_from != "" && grade[0].grade_to != ""){
                $("#gradeDiv").show();
                for(let index = 0; index < grade.length; index++){
                    let requiredExam = "";
                    if(grade[index].required_exam == "1"){
                        requiredExam = `<input type="radio" checked> <label>Required</label><br>
                                        <input type="radio"> <label>Not Required</label>`;
                    }
                    else{
                        requiredExam = `<input type="radio"> <label>Required</label><br>
                                        <input type="radio" checked> <label>Not Required</label>`;
                    }
                    $('#showGrade tbody').append(`
                                <tr>
                                    <td><input type="text" placeholder="Enter Category Name" class="form-control" value="` + grade[index].cat_name +`"></td>
                                    <td><input type="number" placeholder="Grade From?" class="form-control" value="` + grade[index].grade_from +`"></td>
                                    <td><input type="number" placeholder="Grade To?" class="form-control" value="` + grade[index].grade_to +`"></td>
                                    <td>` + requiredExam + `</td>
                                </tr>`);
                }
            }
        }
        else{
            $("#gradeDiv").hide();
        }        
    }

    //remove program
    const removeProgram = (index) =>{

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.value) {
                $("#moduleDataTable").DataTable().clear().draw();
                programModule.splice(index, 1);
                if(programModule.length >= 1){
                    programModule.forEach((data,index) => {
                        /* append data to datatable */
                        moduleDataTable.row.add([
                            data.module.ea_title,
                            '<label class="label label-primary">ACTIVE</label>',
                            '<button class="btn btn-warning btn-sm btn-fill" onclick="viewProgram('+ index +')">VIEW</button> <button class="btn btn-primary btn-sm btn-fill" onclick="removeProgram('+ index +')">DELETE</button>',
                        ]).draw( false );
                    });                   
                }
                Swal.fire({
                    type: "success",
                    title: "Success!",
                    text: "Program module successfully removed!"
                });
            }
        });
    }

    //program list datatable
    const refreshProgramDataTable = () =>{
        $('#programDataTable').DataTable().clear().destroy();
        programDataTable = $('#programDataTable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('scholarship-program.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                realtime: true
            },
            "columns": [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                { "data": "title" },
                { "data": "department" },
                { "data": "status" },
                { "data": "modules" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [0, 2, 3, 4, 5] }
            ]
        });
    }

    
    //View program details
    const format = (d) => {
        
        // `d` is the original data object for the row
        let data = "";
        let btnProg = "enable";

        if(d.scholarshipProgramModule != ''){
            let moduleStatus, updateStatusButton;
            for(let index = 0; index < d.scholarshipProgramModule.length; index++){
                if(d.scholarshipProgramModule[index].application_status == 1){
                    moduleStatus = '<label class="label label-primary">OPEN</label>';
                    // updateStatusButton = `<button class="btn btn-xs btn-danger btn-fill btn-rotate remove" onclick="updateProgramModuleStatus(` + d.scholarshipProgramModule[index].id + `)"><i class="ti-trash"></i> DELETE</button>`;
                }
                else{
                    moduleStatus = '<label class="label label-danger">CLOSED</label>';
                    // updateStatusButton = `<button class="btn btn-xs btn-primary btn-fill btn-rotate remove" onclick="updateProgramModuleStatus(` + d.scholarshipProgramModule[index].id + `)"><i class="ti-reload"></i> RESTORE</button>`;
                }
                // data += `<tr>
                //             <td><label class="label label-primary">` + d.scholarshipProgramModule[index].title + `</label></td>
                //             <td>` + moduleStatus + `</td>
                //             <td><button class="btn btn-xs btn-info btn-fill btn-rotate view" onclick="viewProgramModule(` + d.scholarshipProgramModule[index].id + `)"><i class="ti-eye"></i> VIEW</button> ` +
                //             updateStatusButton + `</td>
                //         </tr>`;
                if(d.programStatus == "0"){
                    btnProg = "disabled";
                }

                data += `<tr>
                            <td><label class="label label-primary">` + d.scholarshipProgramModule[index].title + `</label></td>
                            <td>` + moduleStatus + `</td>
                            <td>                                
                                <div class="dropdown">
                                    <button href="#" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Actions
                                        <b class="caret"></b>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="enable"><a onclick="viewProgramModule(` + d.scholarshipProgramModule[index].id + `)">View</a></li>
                                        <li class="` + btnProg + `"><a onclick="updateModuleApplicationStatus(` + d.scholarshipProgramModule[index].id + `)">Change Application Status</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>`;
            }
        }else{
            data = '<tr>'+
                '<td class="text-center" colspan="4"><label class="label label-primary">Data is deactivated!</label></td>'+
            '</tr>';
        }
        return `<div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th>MODULE NAME</th>
                            <th>APPLICATION STATUS</th>
                            <th>ACTIONS</th>
                        </thead>
                        <tbody>` + data +
                        ` </tbody>
                    </table>
                </div>`;
    }

    //view module in program list
    const viewProgramModule = (id) =>{
        $.ajax({
            url: '/iskocab/scholarship-program/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#showExam tbody").empty();
                $("#showRequirement tbody").empty();
                $("#showEvent tbody").empty();
                $('#showGrade tbody').empty();
                $("#view_modal_module_category").modal("show");

                const {assistanceType, educationalAttainment, scholarshipProgramModule, events, examTitle, requirements} = data;

                $("#show_educational_attainment").text(data.educationalAttainment.title);

                //disabled year level in view
                if(scholarshipProgramModule.required_year == 1){
                    $('#show_year_level').prop('checked', true);
                }
                else{            
                    $('#show_year_level').prop('checked', false);
                }

                //show required units
                $("#show_units").val(scholarshipProgramModule.number_of_units);

                if(scholarshipProgramModule.required_exam == "1"){
                    $("#examDiv").show();
                    $("#showExam tbody").append(`<tr>
                                                    <td>
                                                        <label id="show_educational_attainment" name="show_educational_attainment">` + examTitle + `</label>
                                                    </td>
                                                    <td>
                                                        <label class="label label-primary">ACTIVE</label>
                                                    </td>
                                                </tr>`);
                }else{
                    $("#examDiv").hide();
                }

                if(requirements.length > 0){
                    $("#requirementDiv").show();
                    for(let index = 0; index<requirements.length; index++){
                        $("#showRequirement tbody").append(`<tr>
                                                        <td>
                                                            <label id="show_educational_attainment" name="show_educational_attainment">` + requirements[index] + `</label>
                                                        </td>
                                                        <td>
                                                            <label class="label label-primary">ACTIVE</label>
                                                        </td>
                                                    </tr>`);           
                    }
                }else{
                    $("#requirementDiv").hide();
                }

                if(events.length > 0){
                    $("#eventDiv").show();
                    for(let index = 0; index<events.length; index++){
                        $("#showEvent tbody").append(`<tr>
                                                        <td>
                                                            <label id="show_educational_attainment" name="show_educational_attainment">` + events[index] + `</label>
                                                        </td>
                                                        <td>
                                                            <label class="label label-primary">ACTIVE</label>
                                                        </td>
                                                    </tr>`);           
                    }
                }else{
                    $("#eventDiv").hide();
                }

                if(assistanceType.length > 0){
                    $("#gradeDiv").show();
                    for(let index = 0; index < assistanceType.length; index++){
                        let requiredExam = "";
                        if(assistanceType[index].required_exam == "1"){
                            requiredExam = `<input type="radio" checked> <label>Required</label><br>
                                            <input type="radio"> <label>Not Required</label>`;
                        }
                        else{
                            requiredExam = `<input type="radio"> <label>Required</label><br>
                                            <input type="radio" checked> <label>Not Required</label>`;
                        }
                        $('#showGrade tbody').append(`
                                    <tr>
                                        <td><input type="text" placeholder="Enter Category Name" class="form-control" value="` + assistanceType[index].title +`"></td>
                                        <td><input type="number" placeholder="Grade From?" class="form-control" value="` + assistanceType[index].grade_from +`"></td>
                                        <td><input type="number" placeholder="Grade To?" class="form-control" value="` + assistanceType[index].grade_to +`"></td>
                                        <td>` + requiredExam + `</td>
                                    </tr>`);
                    }
                }
                else{
                    $("#gradeDiv").hide();
                }        
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal.fire({
                    title: "Oops! something went wrong.",
                    text: errorThrown,
                    type: "error"
                });
                //process loader false
                processObject.hideProcessLoader();
            }
        });
    }

    //change application status
    // const changeApplicationStatus = (id) =>{
    //     swal({
    //         title: 'Please enter password!',
    //         input: 'password',
    //         showCancelButton: true,
    //         confirmButtonText: 'Submit',
    //         showLoaderOnConfirm: true,
    //         preConfirm: function (password) {
    //             return new Promise(function (resolve, reject) {
    //                     $.ajax({
    //                         url:'{{ route('account.verify-password')}}',
    //                         type:'POST',
    //                         data:{ _token:"{{ csrf_token() }}",password:password},
    //                         dataType:'json',
    //                         success:function(success){
    //                             if(!success.success){
    //                                 setTimeout(function() { swal.showValidationError('Incorrect PASSWORD!.'); resolve() }, 2000);
    //                             }else{
    //                                 setTimeout(function() { resolve() }, 2000);
    //                             }
    //                         }
    //                     });
                    
    //             });
    //         },
    //         allowOutsideClick: false
    //     }).then(function(password) {
    //         if(password.value){
    //             Swal.fire({
    //                 title: 'Are you sure?',
    //                 text: "You won't be able to revert this!",
    //                 type: 'warning',
    //                 showCancelButton: true,
    //                 confirmButtonColor: '#3085d6',
    //                 cancelButtonColor: '#d33',
    //                 confirmButtonText: 'Yes, update it!'
    //             }).then((result) => {
    //                 if (result.value) {
    //                     //process loader true
    //                     processObject.showProcessLoader();
    //                     $.ajax({
    //                     url: '/iskocab/scholarship-program/toggle-application-status/' + id,
    //                     data:{_token: '{{csrf_token()}}' },
    //                         type: "POST",
    //                         success: function (data) {
    //                             if (data.success) {
    //                                 swal({
    //                                     title: "Save!",
    //                                     text: "Update Successfully!",
    //                                     type: "success"
    //                                 })
    //                                 //process loader false
    //                                 processObject.hideProcessLoader();
    //                                 programDataTable.ajax.reload( null, false);
    //                             }else{
    //                                 swal.fire({
    //                                     title: "Oops! something went wrong.",
    //                                     text: data.messages,
    //                                     type: "error"
    //                                 })
    //                                 //process loader false
    //                                 processObject.hideProcessLoader();
    //                             }
    //                         },
    //                         error: function (jqXHR, textStatus, errorThrown) {
    //                             swal.fire({
    //                                 title: "Oops! something went wrong.",
    //                                 text: errorThrown,
    //                                 type: "error"
    //                             })
    //                             //process loader false
    //                             processObject.hideProcessLoader();
    //                         }
    //                     });
    //                 }
    //             })
    //         }   
    //     });
    // }

    //change program status
    const changeProgramStatus = (id) =>{
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
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                    if (result.value) {
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                        url: '/iskocab/scholarship-program/toggle-program-status/' + id,
                        data:{_token: '{{csrf_token()}}' },
                            type: "POST",
                            success: function (data) {
                                if (data.success) {
                                    swal({
                                        title: "Save!",
                                        text: "Update Successfully!",
                                        type: "success"
                                    })
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    programDataTable.ajax.reload( null, false);
                                }else{
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: data.messages,
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

    //delete/restore program module application in program list
    const updateModuleApplicationStatus = (id) =>{
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                url: '/iskocab/scholarship-program/toggle-assistance-status/' + id,
                data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Update Successfully!",
                                type: "success"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                            programDataTable.ajax.reload( null, false);
                        }else{
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
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

    //print program 
    const printProgram = (id) =>{
        
        window.open("/iskocab/scholarship-program/print/" + id);
        // $.ajax({
        //     url: '/iskocab/scholarship-program/print/' + id,
        //     data:{_token: '{{csrf_token()}}' },
        //     type: "GET",
        //     success: function (data) {
        //         if (data.success) {
        //             swal({
        //                 title: "Save!",
        //                 text: "Update Successfully!",
        //                 type: "success"
        //             })
        //             //process loader false
        //             processObject.hideProcessLoader();
        //             programDataTable.ajax.reload( null, false);
        //         }else{
        //             swal.fire({
        //                 title: "Oops! something went wrong.",
        //                 text: data.messages,
        //                 type: "error"
        //             })
        //             //process loader false
        //             processObject.hideProcessLoader();
        //         }
        //     },
        //     error: function (jqXHR, textStatus, errorThrown) {
        //         swal.fire({
        //             title: "Oops! something went wrong.",
        //             text: errorThrown,
        //             type: "error"
        //         })
        //         //process loader false
        //         processObject.hideProcessLoader();
        //     }
        // });
    }
</script>
@endsection