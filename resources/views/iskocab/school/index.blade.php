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
                                    <h4 class="card-title"><b>School List</b></h4>
                                    <p class="category">Update | View | Delete Schools</p>
                                </div>
                                <div class="col-lg-2">
                                    
                                    @can('permission', 'createSchool')
                                    <a data-toggle="modal" data-target="#schoolModalAdd" id="add" class="btn btn-primary pull-right">
                                        <i class="ti-plus"></i> Add new
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
                                        <th style="width: 20px;"></th>
                                        <th>School Name</th>
                                        <th>Address</th>
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
                    </div>
                </div> <!-- end col-md-12 -->
            </div>
        </div>
    </div>
    <!-- End Display All Data -->

    @can('permission', 'createSchool')
    <!-- Modal For Add -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="schoolModalAdd">
        <div class="modal-dialog" role="document">
            <form id="add_school_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add School</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- School Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="school_name">SCHOOL NAME:</label>
                                <input type="text" class="form-control" name="school_name" id="school_name"
                                placeholder="Enter School Name">
                            </div>
                        </div>
                        <!-- End School Name -->
                        <!-- Address -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="school_address">ADDRESS:</label>
                                <input type="text" class="form-control" name="school_address" id="school_address"
                                    placeholder="Enter Address">
                            </div>
                        </div>
                        <!-- End Address -->
                        
                        <!-- Grading System Type -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="grading_type">Grading Type:</label>
                                <select class="selectpicker form-control" id="grading_type" name="grading_type">
                                    <option value="LETTER">LETTER</option>
                                    <option value="NUMBER">NUMBER</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Grading System Type -->

                        <div class="row">
                            <div class="col-md-4">
                                <label for="maxfield">MAX FIELD:</label>
                                <select id="maxfield" class="selectpicker form-control" style="width:80px; float:right;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>

                        <!-- grading system-->
                        <div class="divfield">
                            <table class="table" id="tblgrade">
                                <thead>
                                    <th>Official Grade </th>
                                    <th>Grade <b>(From)</b></b></th>
                                    <th>Grade <b>(To)</b></th>
                                    <th>Remarks</th>
                                    <th>Remove</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="official_grade[]" placeholder="Official Grade" class="form-control"></td>
                                        <td><input type="number" onchange="checkGrade(this.value, 0, this.name)" name="grade_from[]" placeholder="Grade From?" class="form-control" min="0"></td>
                                        <td><input type="number" onchange="checkGrade(this.value, 0, this.name)" name="grade_to[]" placeholder="Grade To?" class="form-control" min="0"></td>
                                        <td><select name="remarks[]" placeholder="Remarks" class="form-control" style="width: 120px;">
                                            <option value="PASSED">PASSED</option>
                                            <option value="FAILED">FAILED</option>
                                        </td>   
                                        <td><a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields"><span class="btn-label"><i class="ti-plus"></i></span></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button class="btn btn-success" id="save">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->
    @endcan
    
    @can('permission', 'updateSchool')
    <!-- Modal For Edit -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="schoolModalEdit">
        <div class="modal-dialog" role="document">
            <form id="update_school_form">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit School</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- School Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_school_name">SCHOOL NAME:</label>
                                <input type="text" class="form-control" name="edit_school_name" id="edit_school_name"
                                placeholder="Enter School Name">
                            </div>
                        </div>
                        <!-- End School Name -->
                        <!-- Address -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_school_address">ADDRESS:</label>
                                <input type="text" class="form-control" name="edit_school_address" id="edit_school_address"
                                    placeholder="Enter Address">
                            </div>
                        </div>
                        <!-- End Address -->
                        
                        <!-- Grading System Type -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_grading_type">Grading Type:</label>
                                <select class="selectpicker form-control" id="edit_grading_type" name="edit_grading_type">
                                    <option value="LETTER">LETTER</option>
                                    <option value="NUMBER">NUMBER</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Grading System Type -->

                        <div class="row">
                            <div class="col-md-8">
                                <button type="button" class="btn btn-wd btn-info btn-fill btn-rotate" id="add_fields2"><span class="btn-label"><i class="ti-plus"></i></span>&nbsp;Add Field</button>
                            </div>

                            <div class="col-md-4">
                                <label for="editmaxfield">MAX FIELD:</label>
                                <select id="editmaxfield" class="form-control" style="width:80px; float:right;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>

                        <!-- Grading System -->
                        <div class="divfield">
                            <table class="table" id="edit_tblgrade">
                                <thead>
                                    <th>Official Grade </th>
                                    <th>Grade <b>(From)</b></b></th>
                                    <th>Grade <b>(To)</b></th>
                                    <th>Remarks</th>
                                    <th>Remove</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="edit_official_grade[]" placeholder="Official Grade" class="form-control"></td>
                                        <td><input type="number" onchange="checkGrade(this.value, 0, this.name)" id="edit_grade_from[]" name="edit_grade_from[]" placeholder="Grade From?" class="form-control" min="0"></td>
                                        <td><input type="number" onchange="checkGrade(this.value, 0, this.name)" name="edit_grade_to[]" placeholder="Grade To?" class="form-control" min="0"></td>
                                        <td><select name="edit_remarks[]" placeholder="Remarks" class="form-control" style="width: 120px;">
                                            <option value="PASSED">PASSED</option>
                                            <option value="FAILED">FAILED</option>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Grading System -->
                        <input type="hidden" id="school_id" name="school_id">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
    
        </div>
    </div>
    <!-- End Modal -->
    @endcan

    <!--View School History-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="schoolHistoryModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-lg">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">School History</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="schoolHistoryDatatable" class="table table-bordered table-sm" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th style="width:20px;"></th>
                                    <th>School Name</th>
                                    <th>Date Updated</th>
                                    <th>Time Updated</th>
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
    <!-- End Modal -->
@endsection
@section('js')
<script>
    $(document).ready(function () {
        $('#schoolHistoryDatatable').DataTable();
        let ctr = 1;
        
        let maxfield = $("#maxfield").val();
        let editmaxfield = $("#editmaxfield").val();
        let timer = '';
        
        let gradingTypeRules = {
            school_name:
            {
                lettersonly:true,
            },
        }

        // $("#editmaxfield").change(function(){
        //     /* validate data */
        //     let grade_from = $("input[name='edit_grade_from[]']").map(function(){ return parseInt($(this).val()); }).get();
        //     let grade_to = $("input[name='edit_grade_to[]']").map(function(){ return parseInt($(this).val()); }).get();
        // });

        $("#grading_type").change(function(){
            $(this).val() == "NUMBER" ? $("input[name='official_grade[]']").prop('type', 'number') : $("input[name='official_grade[]']").prop('type', 'text');
        });

        $("#edit_grading_type").change(function(){
            $(this).val() == "NUMBER" ? $("input[name='edit_official_grade[]']").prop('type', 'number') : $("input[name='edit_official_grade[]']").prop('type', 'text');
        });
        
        //Add school
        $("#add_school_form").validate({
            rules: {
                school_name: {
                    minlength: 3,
                    required: true 
                },
                school_address: {
                    minlength: 3,
                    required: true
                },
                grading_type:{
                    required: true
                },
                "official_grade[]": {
                    required: true
                },
                "grade_from[]": {
                    required: true
                },
                "grade_to[]": {
                    required: true
                },
                "remarks[]": {
                    required: true
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Register Now?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '{{ route('school.store') }}',
                            type: "POST",
                            data: $('#add_school_form').serialize(),
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    swal({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success"
                                    }).then(function(){
                                        $('#schoolModalAdd').modal('hide');
                                        $("#add_school_form")[0].reset();
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
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

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");
        

        //Find all data
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('school.find-all') }}',
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
                { "data": "school_name" },
                { "data": "address" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [ 
                { "orderable": false, "targets": [0, 3] }, 
            ]	 
        });

        $("#add").click(function(){ 
            $('label.error').hide();
            $('.error').removeClass('error');
        });

        //add field of grading system in add form
        $('#add_fields').on('click', function(e){
            ctr = $('#tblgrade tbody tr').length;

            const input = document.getElementsByName('grade_from[]'); 
            let min=0; 
            for (var index = 0; index < input.length; index++) { 
                if(input[index].value != "" && index == input.length-1){
                    min = input[index].value - 1;
                }
            } 

            e.preventDefault();
            if(ctr < $("#maxfield").val()){
                $('#tblgrade tbody').append('<tr><td><input type="text" name="official_grade[]" placeholder="Official Grade" class="form-control"></td><td><input type="number" min="0" name="grade_from[]" onchange="checkGrade(this.value,' + ctr + ', this.name)"  placeholder="Grade From?" class="form-control"></td><td><input type="number" min="0" name="grade_to[]" onchange="checkGrade(this.value,' + ctr + ', this.name)" placeholder="Grade To?" class="form-control" value="' + min + '"></td><td><select name="remarks[]" placeholder="Remarks" class="form-control" style="width: 120px;"><option value="PASSED">PASSED</option><option value="FAILED">FAILED</option></td><td><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-trash"></i></a></td></tr>');
                ctr++;
            }else{
                swal('Warning!', 'Maximum of ' + $("#maxfield").val() + ' fields only!', 'warning');
            }
        });

        //add field of grading system in edit form
        $('#add_fields2').on('click', function(e){
            ctr = $('#edit_tblgrade tbody tr').length;
            e.preventDefault();
            if(ctr < $("#editmaxfield").val()){
                $('#edit_tblgrade tbody').append('<tr><td><input type="text" name="official_grade[]" placeholder="Official Grade" class="form-control"></td><td><input type="number" min="0" name="grade_from[]" onchange="checkGrade(this.value,' + ctr + ', this.name)"  placeholder="Grade From?" class="form-control"></td><td><input type="number" min="0" name="grade_to[]" onchange="checkGrade(this.value,' + ctr + ', this.name)" placeholder="Grade To?" class="form-control"></td><td><select name="edit_remarks[]" placeholder="Remarks" class="form-control" style="width: 120px;"><option value="PASSED">PASSED</option><option value="FAILED">FAILED</option></td><td><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-trash"></i></a></td></tr>');
                ctr++;
            }else{
                swal('Warning!', 'Maximum of ' + $("#editmaxfield").val() + ' fields only!', 'warning');
            }
        });

        //if you change max field and rows are more than max field, remove extra rows
        $('#maxfield').change(function(){
            var row = $('#tblgrade tbody tr').length;
            if($('#tblgrade tbody tr').length > $('#maxfield').val()){
                while(row >= $('#maxfield').val()){
                    $('#tblgrade tbody tr').eq(row).remove();
                    row--;
                }
            }
        });
        $('#editmaxfield').change(function(){
            var row = $('#edit_tblgrade tbody tr').length;
            if($('#edit_tblgrade tbody tr').length > $('#editmaxfield').val()){
                while(row >= $('#editmaxfield').val()){
                    $('#edit_tblgrade tbody tr').eq(row).remove();
                    row--;
                }
            }
        });
        
        //remove field of grading system in add form
        $('#tblgrade tbody').on("click", "#remove_field", function(e){ 
            e.preventDefault();
            $(this).parent().parent().remove();
            ctr--;
        });

        //remove field of grading system in edit form
        $('#edit_tblgrade tbody').on("click", "#remove_field", function(e){ 
            e.preventDefault();
            $(this).parent().parent().remove();
            ctr--;
        });
        
        //Show grading system
        $('#datatable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = datatable.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                // timer = setInterval(function() {datatable.ajax.reload( null, false );}, 8000);
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        $('#schoolHistoryDatatable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = schoolHistoryDatatable.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                // timer = setInterval(function() {datatable.ajax.reload( null, false );}, 8000);
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });
    });    
    
    
    //Update School
    $("#update_school_form").validate({
        rules: {
            edit_school_name: {
                minlength: 3,
                required: true
            },
            edit_school_address: {
                minlength: 3,
                required: true
            },
            edit_grade_type: {
                required:true
            },
            "edit_official_grade[]": {
                required: true
            },
            "edit_grade_from[]": {
                required: true
            },
            "edit_grade_to[]": {
                required: true
            },
            "edit_remarks[]": {
                required: true
            }
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Update Data?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: '/iskocab/school/' + $("#school_id").val(),
                        type: "POST",
                        data: $('#update_school_form').serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                swal({
                                    title: "Save!",
                                    text: "Successfully!",
                                    type: "success"
                                }).then(function(){
                                    $('#schoolModalEdit').modal('hide');
                                    $("#update_school_form")[0].reset();
                                });
                                //process loader false
                                processObject.hideProcessLoader();
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
    
    //Select Data to edit
    const edit = (id) =>{
        $('#datatable tbody').trigger("click");
        $.ajax({
            url: '/iskocab/school/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#edit_tblgrade tbody').children().remove();
                $("#schoolModalEdit").modal("show");
                $("#edit_school_name").val(data["school"].school_name);
                $("#edit_school_address").val(data["school"].address);
                $("#edit_grading_type").val(data["grading_type"]);
                
                $("#school_id").val(data["school"].id);
                $('.selectpicker').selectpicker('refresh');
                var grade = "";
                for(var index=0; index < data["grading_system"].official_grade.length; index++){
                    let rowData = '<tr><td><input type="text" id="edit_official_grade[]" name="edit_official_grade[]" placeholder="Official Grade" class="form-control" value="' + data["grading_system"].official_grade[index] +
                        '"></td><td><input type="number" onchange="checkGrade(this.value,' + index + ', this.name)" name="edit_grade_from[]" placeholder="Grade From?" class="form-control" min="0" value="'  + data["grading_system"].grade_from[index] +  
                        '"></td><td><input type="number" onchange="checkGrade(this.value,' + index + ', this.name)" name="edit_grade_to[]" placeholder="Grade To?" class="form-control" min="0" value="' + data["grading_system"].grade_to[index] + 
                        '"></td> <td><select name="edit_remarks[]" placeholder="Remarks" class="form-control" style="width: 120px;">';
                    if(data["grading_system"].remarks[index] == "PASSED"){
                        rowData += '<option value="PASSED" selected>PASSED</option>';
                        rowData += '<option value="FAILED">FAILED</option>';
                    }
                    if(data["grading_system"].remarks[index] == "FAILED"){
                        rowData += '<option value="PASSED">PASSED</option>';
                        rowData += '<option value="FAILED" selected>FAILED</option>';
                    }
                    if(index == 0)
                        rowData += '></td><td></td></tr>';
                    else
                        rowData += '></td><td><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-trash"></i></a></td></tr>';
                    
                    if(data["grading_type"] == "NUMBER") $("input[name='edit_official_grade[]']").prop('type', 'number');
                    $('#edit_tblgrade tbody').append(rowData);
                    
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }

    //View grading system
    const format = (d) => {
        // `d` is the original data object for the row
        var grade = "";
        
        if(d.grading_system != ''){
            for(var x=0; x < d.grading_system.official_grade.length; x++){
                grade += '<tr>'+
                    '<td><label class="label label-primary">' + d.grading_system.official_grade[x] + '</label></td>'+
                    '<td><label class="label label-primary">' + d.grading_system.grade_from[x] + '</label></td>'+
                    '<td><label class="label label-primary">' + d.grading_system.grade_to[x] + '</label></td>'+
                    '<td><label class="label label-primary">' + d.grading_system.remarks[x] + '</label></td>'+
                '</tr>';
            }
        }else{
            grade = '<tr>'+
                '<td class="text-center" colspan="4"><label class="label label-primary">Data is deactivated!</label></td>'+
            '</tr>';
        }
        return '<div class="col-md-12">'+
                    '<table class="table table-bordered table-hover">'+
                        '<thead>'+
                            '<th>OFFICIAL GRADE</th>'+
                            '<th>GRADE (FROM)</th>'+
                            '<th>GRADE (TO)</th>'+
                            '<th>REMARKS</th>'+
                        '</thead>'+
                        '<tbody>'+
                            grade +
                        '</tbody>'+
                    '</table>'+
                '</div>';
    }

    //Deactivate Data
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/iskocab/school/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deactivate Successfully!",
                                type: "success"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false);
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

    //Activate Data
    const activate = (id) =>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/iskocab/school/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false);
                        } else {
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

    //check if grade is valid and not conflict with other grades
    const checkGrade = (value, counter, type) => {
        let gradeFromList = document.getElementsByName('grade_from[]'); 
        let gradeToList = document.getElementsByName('grade_to[]'); 
        let gradeFrom = [];
        let gradeTo = [];

        if(type == "edit_grade_from[]" || type == "edit_grade_to[]" ){
            gradeFromList = document.getElementsByName('edit_grade_from[]'); 
            gradeToList = document.getElementsByName('edit_grade_to[]'); 
        }
        
        for (var i = 0; i < gradeFromList.length; i++) { 
            gradeFrom.push(parseInt(gradeFromList[i].value)); 
        } 
        for (var i = 0; i < gradeToList.length; i++) { 
            gradeTo.push(parseInt(gradeToList[i].value)); 
        } 

        let min = parseInt(gradeFrom[0]);
        let max = parseInt(gradeTo[0]);
        let errorGrade = false;

        for(var index = 0; index < gradeFrom.length; index++){
            if(gradeFrom[index] > gradeTo[index]){
                errorGrade = true;
                break;
            }
            
            if(index > 0){
                if((gradeFrom[index] >= min && gradeFrom[index] <= max)  ){
                    errorGrade = true;
                    break;
                }

                if((gradeTo[index] >= min && gradeTo[index] <= max) ){
                    errorGrade = true;
                    break;
                }
            }

            if(gradeFrom[index] < min && errorGrade == false){
                min = parseInt(gradeFrom[index]);
            }
        }
        if(errorGrade == true){
            swal.fire({
                title: "Please input valid grades",
                type: "error"
            });
            $("#save").prop("disabled", true);
        }
        else{
            $("#save").prop("disabled", false);
        }
    }

    const viewHistory = (id) =>{

        $('#schoolHistoryDatatable').DataTable().clear().destroy();
        
        //Find all data
        schoolHistoryDatatable = $('#schoolHistoryDatatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('school.view-history') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", "schoolId": id}
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
                { "data": "school_name" },
                { "data": "date_updated" },
                { "data": "time_updated" },
            ],
            "columnDefs": [ 
                { "orderable": false, "targets": [0, 1, 2] }, 
            ]	 
        });
        
        $("#schoolHistoryModal").modal("show");
    }
</script>
@endsection
