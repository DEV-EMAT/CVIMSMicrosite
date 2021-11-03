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
                                    {{-- @can('permission','createCourse') --}}
                                    <a data-toggle="modal" data-toggle="modal" data-target="#addNewIncidentCategory" class="btn btn-primary pull-right">
                                        <i class="ti-plus"></i> Add new
                                    </a>
                                    {{-- @endcan --}}
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
                                        <th>CFC Status</th>
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

    <!-- Modal For Add -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="addNewIncidentCategory">
        <div class="modal-dialog" role="document">
            <form id="addIncidentForm" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Incident Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="IncidentCategoryDescription">COURSE CODE:</label>
                                <input type="text" class="form-control" name="IncidentCategoryDescription" id="IncidentCategoryDescription"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="incidentModal">
        <div class="modal-dialog" role="document">
            <form id="editDescriptionForm" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Incident Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_incident_category_desc">COURSE CODE:</label>
                                <input type="text" class="form-control" name="edit_incident_category_desc" id="edit_incident_category_desc"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
                    </div>
                    <input type="hidden" id="incident_id" name="incident_id">
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Edit-->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('counseling-and-final-consent.counselingFindAll') }}',
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
            ]d
        });

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");
    });

    //Add Course
    @can('permission', 'createIncidentCategory')
        $("#addIncidentForm").validate({
            rules: {
                IncidentCategoryDescription: {
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
                    confirmButtonText: 'Yes, save it!',
                    html: "<b>Incident Category Registration",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        //show loader
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '{{ route('incident-category.store') }}',
                            type: "POST",
                            data: $('#addIncidentForm').serialize(),
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    $('#addNewIncidentCategory').modal('hide');
                                    $("#addIncidentForm")[0].reset();
                                    //process loader false
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success",
                                        html: "<b>Incident Category Created",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    })
                                    datatable.ajax.reload( null, false );
                                    processObject.hideProcessLoader();
                                } else {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        html: "<b>" + data.messages +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                        type: "error",
                                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                    type: "error",
                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });
    @endcan

    //Update Incident
    @can('permission', 'updateIncidentCategory')
    $("#editDescriptionForm").validate({
        rules: {
            edit_incident_category_desc: {
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
                confirmButtonText: 'Yes, save it!',
                html: "<b>Incident Category Update",
                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
            }).then((result) => {
                if (result.value) {
                    //show loader
                    processObject.showProcessLoader();
                    $.ajax({
                        url: '/emergency/incident-category/' + $("#incident_id").val(),
                        type: "PUT",
                        data: $('#editDescriptionForm').serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                $('#incidentModal').modal('hide');
                                $("#editDescriptionForm")[0].reset();
                                swal({
                                    title: "Save!",
                                    text: "Successfully!",
                                    type: "success",
                                    html: "<b>Incident Category Updated",
                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                })
                                //process loader false
                                processObject.hideProcessLoader();
                                datatable.ajax.reload( null, false );
                            } else {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    html: "<b>" + data.messages +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                    type: "error",
                                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    });

                }
            })
        }
    }); 
    @endcan
    //Select Data to edit
   
    const edit = (id) =>{
        $.ajax({
            url: '/emergency/incident-category/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#incidentModal").modal("show");
                $("#edit_incident_category_desc").val(data.description);
                $("#incident_id").val(data.id);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal.fire({
                    title: "Oops! something went wrong.",
                    html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                    type: "error",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                });
            }
        });
    }
    //Deactivate Data
    @can('permission', 'deleteIncidentCategory')
    const deactivate = (id) =>
    {
        Swal.fire({
            title: 'Deactivate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!',
            html: "<b>Incident Category Deactivation",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/emergency/incident-category/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deactivate Successfully!",
                                type: "success",
                                html: "<b>Incident Category Deactivated",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" + data.messages +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            title: "Oops! something went wrong.",
                            html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                            type: "error",
                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                        });
                        
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });
            }
        })
    }
    @endcan

    @can('permission', 'restoreIncidentCategory')
    const activate = (id) =>{
        Swal.fire({
            title: 'Activate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, activate it!',
            html: "<b>Incident Category Restoration",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/emergency/incident-category/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success",
                                html: "<b>Incident Category Restored",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" + data.messages +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            title: "Oops! something went wrong.",
                            html: "<b>" +errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                            type: "error",
                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                        });
                        
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });
            }
        })
    }
    @endcan
</script>
@endsection
