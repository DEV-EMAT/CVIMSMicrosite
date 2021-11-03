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
                                <h4 class="card-title"><b>Program List</b></h4>
                                <p class="category">Create | View | Update | Delete Program </p>
                            </div>
                            <div class="col-lg-2">
                                @can('permission', 'createProgram')
                                <a data-toggle="modal" data-toggle="modal" data-target="#programModalAdd" class="btn btn-primary pull-right">
                                    <i class="ti-plus"></i> Add new
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Fullname</th>
                                        <th>Department</th>
                                        {{-- <th>Status</th> --}}
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
                </div>
            </div> <!-- end col-md-12 -->
        </div>
    </div>
</div>
<!-- End Display All Data -->

@can('permission', 'createProgram')
<!-- Modal For Add -->
<div class="modal fade in" tabindex="-1" role="dialog" id="programModalAdd">
    <div class="modal-dialog" role="document">
        <form id="addProgramForm" method="post">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Add Program</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <!-- Requirement Name -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="name">Requirement Name:</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Enter Requirement Name">
                        </div>
                    </div>
                    <!-- End Requirement Name -->
                    <!-- Requirement Description -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="description">DESCRIPTION:</label>
                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        </div>
                    </div>
                    <!-- End Requirement Description -->
                    
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="department">Department *</label>
                            <select class="selectpicker form-control" data-live-search="true" name="department" id="department">
                                <option value="" disabled selected>Select.....</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="save">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal for Add -->
@endcan

@can('permission', 'updateProgram')
<!-- Modal For Edit -->
<div class="modal fade in" tabindex="-1" role="dialog" id="requirementModalEdit">
    <div class="modal-dialog" role="document">
        <form id="editRequirementsForm" method="post">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Add Requirement</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <!-- Requirement Name -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="edit_name">REQUIREMENT NAME:</label>
                            <input type="text" class="form-control" name="edit_name" id="edit_name"
                                placeholder="Enter Requirement Name">
                        </div>
                    </div>
                    <!-- End Requirement Name -->

                    <!-- Requirement Description -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="edit_description">DESCRIPTION:</label>
                            <input type="text" class="form-control" name="edit_description"
                                id="edit_description" placeholder="Enter Requirement Description">
                        </div>
                    </div>
                    <!-- End Requirement Description -->
                    
                    <!-- Department -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="edit_department">Department *</label>
                            <select class="selectpicker form-control" data-live-search="true" name="edit_department" id="edit_department">
                                <option value="" disabled selected>Select.....</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Department -->
                </div>
                <input type="hidden" id="requirement_id" name="requirement_id">
            </form>
            <div class="modal-footer">
                <button class="btn btn-success">Save</button>
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
                "url": '{{ route('requirement.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "name" },
                { "data": "department" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1] }, 
            ]
        });

        //get department
        $.ajax({
            url:'{{ route('department.findall2') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="department"]').append('<option value='+response[index].id+'>' + response[index].department +'</option>');
                    $('[name="edit_department"]').append('<option value='+response[index].id+'>' + response[index].department +'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        })

        @if(Gate::check('permission', 'createProgram'))
        //add requirements
        $("#addProgramForm").validate({
            rules: {
                name: {
                    minlength: 2,
                    required: true
                },
                description: {
                    minlength: 3,
                    required: true
                },
                department:{
                    required:true
                }
            },
            submitHandler: function (form) {
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
                        //show loader
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '{{ route('requirement.store') }}',
                            type: "POST",
                            data: $('#addProgramForm').serialize(),
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    $('#programModalAdd').modal('hide');
                                    $("#addProgramForm")[0].reset();
                                    //process loader false
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success"
                                    })
                                    datatable.ajax.reload( null, false );
                                    processObject.hideProcessLoader();
                                } else {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: data.messages,
                                        type: "error"
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
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
                })
            }
        });
        @endif

        //update requirements
        $("#editRequirementsForm").validate({
            rules: {
                edit_name: {
                    minlength: 2,
                    required: true
                },
                edit_description: {
                    minlength: 3,
                    required: true
                },
                edit_department:{
                    required:true
                }
            },
            submitHandler: function (form) {
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
                        //show loader
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '/comprehensive/requirement/' + $("#requirement_id").val(),
                            type: "PUT",
                            data: $('#editRequirementsForm').serialize(),
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    $('#requirementModalEdit').modal('hide');
                                    $("#editRequirementsForm")[0].reset();
                                    //process loader false
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success"
                                    })
                                    datatable.ajax.reload( null, false );
                                    processObject.hideProcessLoader();
                                } else {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: data.messages,
                                        type: "error"
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
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
                })
            }
        });
    });

    @if(Gate::check('permission', 'updateProgram')) 
    //Select Data to edit
    const edit = (id) =>{
        $.ajax({
            url: '/comprehensive/requirement/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#edit_name").val(data['requirement'].name);
                $("#edit_description").val(data['requirement'].description);
                $("#edit_department").val(data['department'].id);
                $('.selectpicker').selectpicker('refresh');
                $("#requirement_id").val(data['requirement'].id);
                $("#requirementModalEdit").modal("show");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    @endif

    @if(Gate::check('permission', 'deleteRequirement'))
    //Delete Data
    const del = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/comprehensive/requirement/toggle/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deleted Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
                                type: "error"
                            });
                            
                            //process loader false
                            processObject.hideProcessLoader();
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
        })
    }
    @endif

    @if(Gate::check('permission', 'restoreRequirement'))
    //Restore Data
    const restore = (id) =>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/comprehensive/requirement/toggle/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
                                type: "error"
                            });
                            
                            //process loader false
                            processObject.hideProcessLoader();
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
        })
    }
    @endif
</script>
@endsection