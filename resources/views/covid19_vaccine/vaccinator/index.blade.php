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
                                    <h4 class="card-title"><b>Vaccinator List</b></h4>
                                    <p class="category">Add | Update | View | Delete Vaccinator</p>
                                </div>
                                <div class="col-lg-2">
                                    @can('permission','createVaccinator')
                                    <a data-toggle="modal" data-toggle="modal" id="add" data-target="#addVaccinatorModal" class="btn btn-primary pull-right">
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
                                        <th style="20px"></th>
                                        <th>Fullname</th>
                                        <th>Health Faciliy</th>
                                        <th>Status</th>
                                        <th style="width: 500px;">Actions</th>
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

    <!-- Modal For Add -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="addVaccinatorModal">
        <div class="modal-dialog" role="document">
            <form id="create_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Vaccinator</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Last Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="last_name">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="last_name"
                                    placeholder="Enter Last Name">
                            </div>
                        </div>
                        <!-- End Last Name -->
                        <!-- First Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="first_name">First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="first_name"
                                    placeholder="Enter First Name">
                            </div>
                        </div>
                        <!-- End First Name -->
                        <!-- Middle Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="middle_name">Middle Name:</label>
                                <input type="text" class="form-control" name="middle_name" id="middle_name"
                                    placeholder="Enter Middle Name">
                            </div>
                        </div>
                        <!-- End Middle Name -->
                        <!-- Suffix -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="suffix">Suffix:</label>
                                <input type="text" class="form-control" name="suffix" id="suffix"
                                    placeholder="Enter Suffix">
                            </div>
                        </div>
                        <!-- End Suffix -->
                        <!-- Health Facility -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="health_facility">Health Facility:</label>
                                <select type="text" class="form-control selectpicker" name="health_facility" id="health_facility"
                                    placeholder="Enter Health Facility">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Health Facility -->
                        <!-- PRC License Number -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="prc_license_number">PRC License Number:</label>
                                <input type="text" class="form-control" name="prc_license_number" id="prc_license_number"
                                    placeholder="Enter PRC License Number">
                            </div>
                        </div>
                        <!-- End PRC License Number -->
                        <!-- Profession -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="profession">Profession:</label>
                                <input type="text" class="form-control" name="profession" id="profession"
                                    placeholder="Enter Profession">
                            </div>
                        </div>
                        <!-- End Profession -->
                        <!-- Role -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="role">Role:</label>
                                <select type="text" class="form-control selectpicker" name="role" id="role"
                                    placeholder="Enter Role">
                                    <option value="" disabled selected>Select.....</option>
                                    <option value="Team_Lead">Team Lead</option>
                                    <option value="Counseling_Nurse">Counseling Nurse</option>
                                    <option value="Encoder">Encoder</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Role -->
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success" id="save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Add -->

    
    <!-- Modal For Edit -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="editVaccinatorModal">
        <div class="modal-dialog" role="document">
            <form id="edit_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Vaccinator</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Last Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_last_name">Last Name:</label>
                                <input type="text" class="form-control" name="edit_last_name" id="edit_last_name"
                                    placeholder="Enter Last Name">
                            </div>
                        </div>
                        <!-- End Last Name -->
                        <!-- First Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_first_name">First Name:</label>
                                <input type="text" class="form-control" name="edit_first_name" id="edit_first_name"
                                    placeholder="Enter First Name">
                            </div>
                        </div>
                        <!-- End First Name -->
                        <!-- Middle Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_middle_name">Middle Name:</label>
                                <input type="text" class="form-control" name="edit_middle_name" id="edit_middle_name"
                                    placeholder="Enter Middle Name">
                            </div>
                        </div>
                        <!-- End Middle Name -->
                        <!-- Suffix -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_suffix">Suffix:</label>
                                <input type="text" class="form-control" name="edit_suffix" id="edit_suffix"
                                    placeholder="Enter Suffix">
                            </div>
                        </div>
                        <!-- End Suffix -->
                        <!-- Health Facility -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_health_facility">Health Facility:</label>
                                <select type="text" class="form-control selectpicker" name="edit_health_facility" id="edit_health_facility"
                                    placeholder="Enter Health Facility">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Health Facility -->
                        <!-- PRC License Number -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_prc_license_number">PRC License Number:</label>
                                <input type="text" class="form-control" name="edit_prc_license_number" id="edit_prc_license_number"
                                    placeholder="Enter PRC License Number">
                            </div>
                        </div>
                        <!-- End PRC License Number -->
                        <!-- Profession -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_profession">Profession:</label>
                                <input type="text" class="form-control" name="edit_profession" id="edit_profession"
                                    placeholder="Enter Profession">
                            </div>
                        </div>
                        <!-- End Profession -->
                        <!-- Role -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_role">Role:</label>
                                <select type="text" class="form-control selectpicker" name="edit_role" id="edit_role"
                                    placeholder="Enter Role">
                                    <option value="" disabled selected>Select.....</option>
                                    <option value="Team_Lead">Team Lead</option>
                                    <option value="Counseling_Nurse">Counseling Nurse</option>
                                    <option value="Encoder">Encoder</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Role -->
                    </div>
                    <input type="hidden" id="edit_id">
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success" id="save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Edit -->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('vaccinator.find-all') }}',
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
                { "data": "fullname" },
                { "data": "facility_name" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 0, ,2, 3, 4 ] }, 
            ]	 	 
        });
        
        //get health facilityes
        $.ajax({
            url:'{{ route('health-facility.find-all-facility') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="health_facility"]').append('<option value='+response[index].id+'>'+ response[index].facility_name+'</option>');
                    $('[name="edit_health_facility"]').append('<option value='+response[index].id+'>'+ response[index].facility_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });

        //reset error
        $("#add").click(function(){
            $("#create_form")[0].reset();
            $('label.error').hide();
            $('.error').removeClass('error');
            $('.selectpicker').selectpicker('refresh');
        });

        $("#create_form").validate({
            rules: {
                last_name: {
                    minlength: 2,
                    required: true
                },
                first_name: {
                    minlength: 2,
                    required: true
                },
                health_facility: {
                    required: true
                },
                prc_license_number: {
                    required: true
                },
                profession: {
                    required: true
                },
                role: {
                    required: true
                },
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Register Now?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!',
                    html: "<b>Create Vaccinator",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('vaccinator.store') }}',
                            type: "POST",
                            data: $('#create_form').serialize(),
                            dataType: "JSON",
                            beforeSend: function(){
                                processObject.showProcessLoader();
                            },
                            success: function (data) {
                                if (data.success) {
                                    $('#addVaccinatorModal').modal('hide');
                                    $("#create_form")[0].reset();
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success",
                                        html: "<b>Vaccinator Created",
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
                                    datatable.ajax.reload( null, false );
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
        });
        
        $("#edit_form").validate({
            rules: {
                last_name: {
                    minlength: 2,
                    required: true
                },
                first_name: {
                    minlength: 2,
                    required: true
                },
                health_facility: {
                    required: true
                },
                prc_license_number: {
                    required: true
                },
                profession: {
                    required: true
                },
                role: {
                    required: true
                },
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Update Now?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!',
                    html: "<b>Vaccinator Update Data",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            
                            url: '/covid19vaccine/vaccinator/' + $("#edit_id").val(),
                            type: "PUT",
                            data: $('#edit_form').serialize(),
                            dataType: "JSON",
                            beforeSend: function(){
                                processObject.showProcessLoader();
                            },
                            success: function (data) {
                                if (data.success) {
                                    $('#editVaccinatorModal').modal('hide');
                                    $("#edit_form")[0].reset();
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success",
                                        html: "<b>Vaccinator Data Updated",
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
                                    datatable.ajax.reload( null, false );
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
        });
        
        //Show other details
        $('#datatable tbody').on('click', 'td.details-control', function () {
        
            let datatable = $('#datatable').DataTable();
            var tr = $(this).closest('tr');
            var row = datatable.row( tr );
    
            console.log(row.data());
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
        output += `<div class="col-md-6 col-md-offset-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th style="width: 60%;">Other Information</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Health Facility</td>
                                <td style="text-align:center">
                                ` + d.facility_name + `
                                </td>
                            </tr>
                            <tr>
                                <td>PRC License Number</td>
                                <td style="text-align:center">
                                ` + d.prc_license_number + `
                                </td>
                            </tr>
                            <tr>
                                <td>Profession</td>
                                <td style="text-align:center">
                                ` + d.profession + `
                                </td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td style="text-align:center">
                                ` + d.role + `
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>`;
        return output;
    }
    
    //edit data
    const edit = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccinator/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $("#edit_last_name").val(data.last_name);
                $("#edit_first_name").val(data.first_name);
                $("#edit_middle_name").val(data.middle_name);
                $("#edit_suffix").val(data.suffix);
                $("#edit_health_facility").val(data.health_facilities_id);
                $("#edit_prc_license_number").val(data.prc_license_number);
                $("#edit_profession").val(data.profession);
                $("#edit_role").val(data.role);
                $("#edit_id").val(data.id);
                
                $('label.error').hide();
                $('.error').removeClass('error');
                $('.selectpicker').selectpicker('refresh');
                $("#editVaccinatorModal").modal("show");
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
    
    //delete vaccine
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            html: "<b>Vaccinator Deactivation",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/covid19vaccine/vaccinator/status/' + id,
                    data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Delete Successfully!",
                                type: "success",
                                html: "<b>Vaccinator Data Deactivated",
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
    
    //restore vaccine
    const restore = (id) =>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!',
            html: "<b>Vaccinator Restoration",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/covid19vaccine/vaccinator/status/' + id,
                    data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success",
                                html: "<b>Vaccinator Data Restored",
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
    
</script>
@endsection
