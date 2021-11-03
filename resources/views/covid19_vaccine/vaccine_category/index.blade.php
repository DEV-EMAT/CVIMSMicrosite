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
                                    <h4 class="card-title"><b>Vaccine Category List</b></h4>
                                    <p class="category">Add | Update | View | Delete Vaccine</p>
                                </div>
                                <div class="col-lg-2">
                                    @can('permission','createVaccineCategory')
                                    <a data-toggle="modal" data-toggle="modal" id="add" data-target="#add_modal" class="btn btn-primary pull-right">
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
                                        <th>Vaccine Name</th>
                                        <th>Vaccine Manufacturer</th>
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="add_modal">
        <div class="modal-dialog" role="document">
            <form id="create_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Vaccine Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Vaccine Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="vaccine_name">Vaccine Name:</label>
                                <input type="text" class="form-control" name="vaccine_name" id="vaccine_name"
                                    placeholder="Enter Vaccine Name">
                            </div>
                        </div>
                        <!-- End Vaccine Name -->
                        <!-- Vaccine Manufacturer -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="vaccine_manufacturer">Vaccine Manufacturer:</label>
                                <input type="text" class="form-control" name="vaccine_manufacturer" id="vaccine_manufacturer"
                                    placeholder="Enter Vaccine Manufacturer">
                            </div>
                        </div>
                        <!-- End Vaccine Manufacturer -->
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="edit_modal">
        <div class="modal-dialog" role="document">
            <form id="edit_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Vaccine Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Edit Vaccine Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_vaccine_name">Vaccine Name:</label>
                                <input type="text" class="form-control" name="edit_vaccine_name" id="edit_vaccine_name"
                                    placeholder="Enter Vaccine Name">
                            </div>
                        </div>
                        <!-- End Edit Vaccine Name -->
                        <!-- Edit Vaccine Manufacturer -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_vaccine_manufacturer">Vaccine Manufacturer:</label>
                                <input type="text" class="form-control" name="edit_vaccine_manufacturer" id="edit_vaccine_manufacturer"
                                    placeholder="Enter Vaccine Manufacturer">
                            </div>
                        </div>
                        <!-- End Edit Vaccine Manufacturer -->
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
                "url": '{{ route('vaccine-category.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "vaccine_name" },
                { "data": "vaccine_manufacturer" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 3 ] }, 
            ]	 	 
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
                vaccine_name: {
                    required: true
                },
                vaccine_manufacturer: {
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
                    html: "<b>Vaccine Category Create",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('vaccine-category.store') }}',
                            type: "POST",
                            data: $('#create_form').serialize(),
                            dataType: "JSON",
                            beforeSend: function(){
                                processObject.showProcessLoader();
                            },
                            success: function (data) {
                                if (data.success) {
                                    $('#add_modal').modal('hide');
                                    $("#create_form")[0].reset();
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success",
                                        html: "<b>Vaccine Category Created",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    });
                                    datatable.ajax.reload( null, false );
                                } else {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        html: "<b>" + data.messages + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                        type: "error",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    });
                                    datatable.ajax.reload( null, false );
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    html: "<b>" + errorThrown + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
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
                    title: 'Register Now?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!',
                    html: "<b>Vaccine Category Update",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            
                            url: '/covid19vaccine/vaccine-category/' + $("#edit_id").val(),
                            type: "PUT",
                            data: $('#edit_form').serialize(),
                            dataType: "JSON",
                            beforeSend: function(){
                                processObject.showProcessLoader();
                            },
                            success: function (data) {
                                if (data.success) {
                                    $('#edit_modal').modal('hide');
                                    $("#edit_form")[0].reset();
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success",
                                        html: "<b>Vaccine Category Updated",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    });
                                    datatable.ajax.reload( null, false );
                                } else {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        html: "<b>" + data.messages + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                        type: "error",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    });
                                    datatable.ajax.reload( null, false );
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    html: "<b>" + errorThrown + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
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
        
    });
    
    //edit data
    const edit = (id) =>{
        $.ajax({
            url: '/covid19vaccine/vaccine-category/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $("#edit_vaccine_name").val(data.vaccine_name);
                $("#edit_vaccine_manufacturer").val(data.vaccine_manufacturer);
                $("#edit_id").val(data.id);
                
                $('label.error').hide();
                $('.error').removeClass('error');
                $('.selectpicker').selectpicker('refresh');
                $("#edit_modal").modal("show");
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
    
    //delete vaccine category
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            html: "<b>Vaccine Category Deletion",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/covid19vaccine/vaccine-category/status/' + id,
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
                                html: "<b>Vaccine Category Deleted",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" + data.messages + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
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
    
    //restore vaccine category
    const restore = (id) =>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!',
            html: "<b>Vaccine Category Restoration",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/covid19vaccine/vaccine-category/status/' + id,
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
                                html: "<b>Vaccine Category Restored",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" + data.messages + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error"
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
