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
                                    <h4 class="card-title"><b>Course List</b></h4>
                                    <p class="category">Update | View | Delete Course</p>
                                </div>
                                <div class="col-lg-2">
                                    @can('permission','createCourse')
                                    <a data-toggle="modal" data-toggle="modal" data-target="#courseModalAdd" class="btn btn-primary pull-right">
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
                                        <th>Course Code</th>
                                        <th>Course Description</th>
                                        <th>Status</th>
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="courseModalAdd">
        <div class="modal-dialog" role="document">
            <form id="add_course-form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Course</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="course_code">COURSE CODE:</label>
                                <input type="text" class="form-control" name="course_code" id="course_code"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
                        <!-- Course Description -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="course_description">COURSE DESCRIPTION:</label>
                                <input type="text" class="form-control" name="course_description"
                                    id="course_description" placeholder="Enter Course Description">
                            </div>
                        </div>
                        <!-- End Course Description -->
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="courseModalEdit">
        <div class="modal-dialog" role="document">
            <form id="edit_course-form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Course</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_course_code">COURSE CODE:</label>
                                <input type="text" class="form-control" name="edit_course_code" id="edit_course_code"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
                        <!-- Course Description -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_course_description">COURSE DESCRIPTION:</label>
                                <input type="text" class="form-control" name="edit_course_description"
                                    id="edit_course_description" placeholder="Enter Course Description">
                            </div>
                        </div>
                        <!-- End Course Description -->
                    </div>
                    <input type="hidden" id="course_id" name="course_id">
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
                "url": '{{ route('course.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "course_code" },
                { "data": "course_description" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 3 ] }, 
            ]	 	 
        });

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");
    });

    //Add Course
    @can('permission', 'createCourse')
        $("#add_course-form").validate({
            rules: {
                course_code: {
                    minlength: 2,
                    required: true
                },
                course_description: {
                    minlength: 3,
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
                        //show loader
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '{{ route('course.store') }}',
                            type: "POST",
                            data: $('#add_course-form').serialize(),
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    $('#courseModalAdd').modal('hide');
                                    $("#add_course-form")[0].reset();
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
    @endcan

    //Update Course
    @can('permission', 'updateCourse')
    $("#edit_course-form").validate({
        rules: {
            course_name: {
                minlength: 2,
                required: true
            },
            course_address: {
                minlength: 3,
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
                    //show loader
                    processObject.showProcessLoader();
                    $.ajax({
                        url: '/iskocab/course/' + $("#course_id").val(),
                        type: "PUT",
                        data: $('#edit_course-form').serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                $('#courseModalEdit').modal('hide');
                                $("#edit_course-form")[0].reset();
                                swal({
                                    title: "Save!",
                                    text: "Successfully!",
                                    type: "success"
                                })
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
    }); 
    @endcan


    //Select Data to edit
    const edit = (id) =>{
        $.ajax({
            url: '/iskocab/course/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#courseModalEdit").modal("show");
                $("#edit_course_code").val(data.course_code);
                $("#edit_course_description").val(data.course_description);
                $("#course_id").val(data.id);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    
    //Activate or Deactivate Status
    const toggleStatus =(id, status) =>{
        if(status == 1)
            deactivate(id);
        else
            activate(id);
    }

    //Deactivate Data
    @can('permission', 'deleteCourse')
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Deactivate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/iskocab/course/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deactivate Successfully!",
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
    @endcan

    //Activate Data
    @can('permission', 'restoreCourse')
    const activate = (id) =>{
        Swal.fire({
            title: 'Activate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, activate it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/iskocab/course/status/' + id,
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
    @endcan
</script>
@endsection
