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
                                    <h4 class="card-title"><b>Health Facility List</b></h4>
                                    <p class="category">Add | Update | View | Delete Facility</p>
                                </div>
                                <div class="col-lg-2">
                                    {{-- @can('permission','createVaccinator') --}}
                                    <a data-toggle="modal" data-toggle="modal" id="add" data-target="#addFacilityModal" class="btn btn-primary pull-right">
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
                                        <th>Fullname</th>
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="addFacilityModal">
        <div class="modal-dialog" role="document">
            <form id="create_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Facility</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Facility Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="facility_name">Facility Name:</label>
                                <input type="text" class="form-control" name="facility_name" id="facility_name"
                                    placeholder="Enter Facility Name">
                            </div>
                        </div>
                        <!-- End Facility Name -->
                        <!-- Address -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" name="address" id="address"
                                    placeholder="Enter Address">
                            </div>
                        </div>
                        <!-- End Address -->
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success" id="save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Add -->


    @can('permission', 'viewAssignStaff')
    <!-- Modal For Assign User -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="assignUserModal">
        <div class="modal-dialog" role="document">
            <form id="assigned_user_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Facility</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <input type="hidden" name="facility_id" id="facility_id">
                        <table id="datatableUsers" class="table table-bordered table-sm table-hover" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Fullname</th>
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
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Assign User -->
    @endcan


    <!-- Modal For Edit -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="editFacilityModal">
        <div class="modal-dialog" role="document">
            <form id="edit_form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Facility</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Facility Name -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_facility_name">Facility Name:</label>
                                <input type="text" class="form-control" name="edit_facility_name" id="edit_facility_name"
                                    placeholder="Enter Facility Name">
                            </div>
                        </div>
                        <!-- End Facility Name -->
                        <!-- Address -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_address">Address:</label>
                                <input type="text" class="form-control" name="edit_address" id="edit_address"
                                    placeholder="Enter Address">
                            </div>
                        </div>
                        <!-- End Address -->
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
    let assignUserID = [];
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('health-facility.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "facility_name" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 2 ] }, 
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
                facility_name: {
                    minlength: 2,
                    required: true
                },
                description: {
                    minlength: 2,
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
                    html: "<b>Create Health Facility",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('health-facility.store') }}',
                            type: "POST",
                            data: $('#create_form').serialize(),
                            dataType: "JSON",
                            beforeSend: function(){
                                processObject.showProcessLoader();
                            },
                            success: function (data) {
                                if (data.success) {
                                    $('#addFacilityModal').modal('hide');
                                    $("#create_form")[0].reset();
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success",
                                        html: "<b>Health Facility Created",
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
        
        //update health facility
        $("#edit_form").validate({
            rules: {
                facility_name: {
                    minlength: 2,
                    required: true
                },
                description: {
                    minlength: 2,
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
                    html: "<b>Health Facility Update",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/covid19vaccine/health-facility/' + $("#edit_id").val(),
                            type: "PUT",
                            data: $('#edit_form').serialize(),
                            dataType: "JSON",
                            beforeSend: function(){
                                processObject.showProcessLoader();
                            },
                            success: function (data) {
                                if (data.success) {
                                    $('#editFacilityModal').modal('hide');
                                    $("#edit_form")[0].reset();
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success",
                                        html: "<b>Health Facility Updated",
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
    });
    
    //edit data
    const edit = (id) =>{
        $.ajax({
            url: '/covid19vaccine/health-facility/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                processObject.showProcessLoader();
            },
            success: function (data) {
                $("#edit_facility_name").val(data.facility_name);
                $("#edit_address").val(data.address);
                $("#edit_id").val(data.id);
                
                $('label.error').hide();
                $('.error').removeClass('error');
                $('.selectpicker').selectpicker('refresh');
                $("#editFacilityModal").modal("show");
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
    
    //delete health facility
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            html: "<b>Health Facility Deletion",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/covid19vaccine/health-facility/status/' + id,
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
                                html: "<b>Health Facility Deleted",
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
    
    //restore health facility
    const restore = (id) =>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!',
            html: "<b>Health Facility Restoration",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/covid19vaccine/health-facility/status/' + id,
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
                                html: "<b>Health Facility Restored.",
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
    
    @can('permission', 'viewAssignStaff')
     //assign user
     const assignUser = (assigned_user, id) =>{
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
        
                assignUserID = assigned_user;
                $('#facility_id').val(id);

                $('#datatableUsers').DataTable().clear().destroy();
                $('#datatableUsers').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "ajax":{
                        "url": '{{ route('find-all-users') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}"}
                    },
                    "columns": [
                        { "data": "fullname" },
                        { "data": "status" },
                        { "data": "id" },
                    ],
                    "columnDefs": [
                        { "orderable": false, "targets": [ 1 ] }, 
                    ],
                    "aoColumnDefs": [{
                            "aTargets": [2],
                            "mData": "id",
                            "mRender": function (data, type, full) {
                                // alert(data);
                                if(assigned_user.length==0){
                                    return '<input type="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                                }else{
                                    var flag =false;
                                    for (let index = 0; index < assigned_user.length; index++) {
                                        if(assigned_user[index]==data){
                                            flag = true;
                                            break;
                                        }
                                    }
                                    if(flag){
                                        return '<input type="checkbox" checked onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                                    }else{
                                        return '<input type="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                                    }
                                }
                            }
                        }
                    ],	 	 
                });

                $("#assignUserModal").modal("show");
            }
        });
    }

    const ctrToggle = (value) =>{
        var flag =false;
        for (let index = 0; index < assignUserID.length; index++) {
            if(assignUserID[index]==value){
                flag = true;
                assignUserID.splice(index,1);
                break;
            }
        }
        if(!flag){
            assignUserID.push(value);
        }
    }
    
    $("#assigned_user_form").validate({
        rules: { },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Register Now?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                html: "<b>Assigned User",
                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('health-facility.assignedUser') }}',
                        type: "POST",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "assinged_user": JSON.stringify(assignUserID),
                            "facility_id": $('#facility_id').val()
                        },
                        dataType: "JSON",
                        beforeSend: function(){
                            processObject.showProcessLoader();
                        },
                        success: function (data) {
                            if (data.success) {
                                $('#assignUserModal').modal('hide');
                                $("#assigned_user_form")[0].reset();
                                swal.fire({
                                    title: "Save!",
                                    text: "Successfully!",
                                    type: "success",
                                    html: "<b>Health Facility Created",
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
    @endcan

</script>
@endsection
