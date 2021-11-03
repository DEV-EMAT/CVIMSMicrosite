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
                            <div class="col-md-10">
                                <h4 class="card-title"><b>Department List</b>
                                <p class="category">Create | Update | Remove Data</p>
                            </div>
                            
                            <div class="col-md-2 text-right">
                                @can('permission', 'createDepartment')
                                <div data-toggle="modal" data-target="#create_modal">
                                    <a data-toggle="tooltip" id="add" class="btn btn-primary" title="Click here to add new Department">
                                        <i class="ti-plus"></i> Add new
                                    </a>
                                </div>
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
                                        <th>Logo</th>
                                        <th>Department</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th style="width: 400px;">Actions</th>
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

@can('permission', 'createDepartment')
<div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <form id="create_form" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <blockquote>
                                <p>Fill-up this form to create a new <b>Department</b>.</p>
                            </blockquote>
                        </div>
                    </div> 
                    <div class="divfield">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="kv-avatar-hint">
                                    <small><b>Note:</b> Select file < 1500 KB</small> 
                                    <div class="kv-avatar">
                                        <div class="file-loading">
                                            <input type="file" name="logo" class="logo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Department</label>
                                    <input type="text" class="form-control" name="department" id="department" placeholder="Department">
                                </div>
                                <div class="form-group">
                                    <label for="">Office Hour</label>
                                    <input type="time" class="form-control" name="from" id="from"><hr/>
                                    <input type="time" class="form-control" name="to" id="to">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Department Acronym</label>
                                    <input type="text" class="form-control" name="acronym" id="acronym">
                                </div>
                                <div class="form-group">
                                    <label for="">Mobile Number</label>
                                    <input type="text" class="form-control" name="mobile" id="mobile">
                                </div>
                                <div class="form-group">
                                    <label for="">Telephone Number</label>
                                    <input type="text" class="form-control" name="telephone" id="telephone">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Email Address</label>
                                    <input type="text" class="form-control" name="email" id="email">
                                </div>
                                <div class="form-group">
                                    <label for="">Website</label>
                                    <input type="text" class="form-control" name="website" id="website">
                                </div>
                                <div class="form-group">
                                    <label for="">Barangay</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="barangay">
                                        <option value="" disabled selected>SELECT</option>
                                    </select>
                                </div> 
                                {{-- <div class="form-group">
                                    <label for="">Logo</label>
                                    <input type="file" class="form-control" name="logo" id="logo">
                                </div> --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Mission</label>
                                    <textarea class="form-control" name="mission" id="mission" placeholder="Mission"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Vision</label>
                                    <textarea class="form-control" name="vision" id="vision" placeholder="Vision"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">About</label>
                                    <textarea class="form-control" name="about" id="about" placeholder="About"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <textarea class="form-control" name="address" id="address" placeholder="Address"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <input type="submit" name="create" class="btn btn-info btn-fill btn-wd"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@can('permission', 'updateDepartment')
<div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <form id="update_form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-body">
                    <div class="divfield">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="kv-avatar-hint">
                                    <small><b>Note:</b> Select file < 1500 KB</small> 
                                    <div class="kv-avatar">
                                        <div class="file-loading">
                                            <input type="file" name="logo" id="update_logo" class="logo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Department</label>
                                    <input type="text" class="form-control" name="edit_department" id="edit_department" placeholder="Department">
                                </div>
                                <div class="form-group">
                                    <label for="">Office Hour</label>
                                    <input type="time" class="form-control" name="edit_from" id="edit_from"><hr/>
                                    <input type="time" class="form-control" name="edit_to" id="edit_to">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Department Acronym</label>
                                    <input type="text" class="form-control" name="edit_acronym" id="edit_acronym">
                                </div>
                                <div class="form-group">
                                    <label for="">Mobile Number</label>
                                    <input type="text" class="form-control" name="edit_mobile" id="edit_mobile">
                                </div>
                                <div class="form-group">
                                    <label for="">Telephone Number</label>
                                    <input type="text" class="form-control" name="edit_telephone" id="edit_telephone">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Email Address</label>
                                    <input type="text" class="form-control" name="edit_email" id="edit_email">
                                </div>
                                <div class="form-group">
                                    <label for="">Website</label>
                                    <input type="text" class="form-control" name="edit_website" id="edit_website">
                                </div>
                                <div class="form-group">
                                    <label for="">Barangay</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="barangay" id="edit_barangay">
                                        <option value="" disabled selected>SELECT</option>
                                    </select>
                                </div> 
                                {{-- <div class="form-group">
                                    <label for="">Logo</label>
                                    <input type="file" class="form-control" name="edit_logo" id="edit_logo">
                                </div> --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Mission</label>
                                    <textarea class="form-control" name="edit_mission" id="edit_mission" placeholder="Mission"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Vision</label>
                                    <textarea class="form-control" name="edit_vision" id="edit_vision" placeholder="Vision"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">About</label>
                                    <textarea class="form-control" name="edit_about" id="edit_about" placeholder="About"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <textarea class="form-control" name="edit_address" id="edit_address" placeholder="Address"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <input type="submit" name="edit" class="btn btn-info btn-fill btn-wd"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection

@section('js')
<script src="{{ asset('assets/js/nestable/jquery.nestable.js') }}"></script>
<script>
    let timer = 0;
    let department_id = '';

    $(document).ready(function (){

        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('department.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "logo" },
                { "data": "department" },
                { "data": "address" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 0, 2, 3, 4 ] }, 
            ]
        });   


        /* add barangay on selectbox */
        $.ajax({
            url:'{{ route('barangay.findall2')}}',
            type:'GET',
            dataType:'JSON',
            success:function(response){
                response.forEach((value)=>{
                    $('[name="barangay"]').append('<option value='+value.id+'>'+ value.barangay+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                });
            }
        });
        
        @if(!Gate::check('permission', 'updateDepartment') && !Gate::check('permission', 'deleteDepartment') && !Gate::check('permission', 'restoreDepartment'))
            datatable.column(3).visible(false);
        @endif
    });

    @can('permission', 'createDepartment')
    $("#create_form").validate({
        rules: {
            department: {
                required: true,
                minlength: 3
            },
            department: {
                required: true,
                minlength: 3
            },
            mobile: {
                required: true,
                phoneno:true
            }
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Save new department?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    
                    var formData = new FormData($("#create_form").get(0));
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: "{{ route('department.store') }}",
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function (response) {
                            if(response.success){
                                swal({
                                    title: "Success!",
                                    text: response.messages,
                                    type: "success"
                                }).then(function() {
                                    $("#create_form")[0].reset();
                                    $('#create_modal').modal('hide');
                                    datatable.ajax.reload( null, false );
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }else{
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: response.messages,
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
    @endcan

    @can('permission', 'updateDepartment')
    $("#update_form").validate({
        rules: {
            edit_department: {
                required: true,
                minlength:3
            },
            edit_description: {
                minlength:3
            },
            edit_mobile: {
                required: true,
                phoneno:true
            }
        },
        submitHandler: function (form) {   

            var id = $('#edit_id').val();
            
            Swal.fire({
                title: 'Update department?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {      
                if (result.value) {
                    var formData = new FormData($("#update_form").get(0));

                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: '/department/'+id,
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function (response) {
                            if(response.success){
                                swal({
                                    title: "Updated!",
                                    text: response.messages,
                                    type: "success"
                                }).then(function() {
                                    $("#update_form")[0].reset();
                                    $('#update_modal').modal('hide');
                                    datatable.ajax.reload( null, false );
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }else{
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: response.messages,
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

    const edit = (id) => {
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url:'/department/'+id,
            type:'GET',
            dataType:'json',
            success:function(success){
                $("#update_logo").fileinput("destroy");
                $("#update_logo").fileinput({
                    overwriteInitial: true,
                    showClose: false,
                    showCaption: false,
                    showUpload: false,
                    browseLabel: 'Update',
                    removeLabel: 'Remove',
                    browseIcon: '<i class="ti-folder"></i>',
                    removeIcon: '<i class="ti-close"></i>',
                    defaultPreviewContent: '<img src="/storage/'+success.logo+'" alt="Default Logo">',
                    allowedFileExtensions: ["jpg","png"]
                });

                $('#edit_id').val(success.id);
                $('#edit_department').val(success.department);
                $('#edit_acronym').val(success.acronym);

                $('#edit_from').val(success.office_hours.split('-')[0]);
                $('#edit_to').val(success.office_hours.split('-')[1]);
                
                $('#edit_email').val(success.email_address);
                $('#edit_mobile').val(success.mobile);
                $('#edit_telephone').val(success.telephone);
                $('#edit_website').val(success.website);
                $('#edit_mission').val(success.mission);
                $('#edit_vision').val(success.vision);
                $('#edit_about').val(success.about);
                $('#edit_address').val(success.address);
                $('#edit_barangay').val(success.barangay_id);
                $('.selectpicker').selectpicker('refresh');

                $('#update_modal').modal('show');
                //process loader false
                processObject.hideProcessLoader();
            }
        });
    }
    @endcan

    @if(Gate::check('permission', 'restoreDepartment') || Gate::check('permission', 'deleteDepartment'))
    const deactivate = (id) => {
        
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.value) {
                // ajax delete data to database
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                url : '/department/toggle/'+id,
                type: "POST",
                data:{ _token: "{{csrf_token()}}"},
                dataType: "JSON",
                success: function(response)
                { 
                    datatable.ajax.reload( null, false );
                    swal({
                        title: "Success!",
                        text: response.messages,
                        type: "success"
                    });
                    //process loader false
                    processObject.hideProcessLoader();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
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
        });
    }
    @endif

    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(09|\+639)\d{9}$/);
    }, "<br />Please specify a valid phone number");

    $(".logo").fileinput({
        overwriteInitial: true,
        showClose: false,
        showCaption: false,
        showUpload:false,
        browseLabel: 'Upload',
        removeLabel: 'Remove',
        browseIcon: '<i class="ti-folder"></i>',
        removeIcon: '<i class="ti-close"></i>',
        defaultPreviewContent: '<img src="/storage/ecabs/images/logo/default-logo.png" alt="Default Logo">',
        allowedFileExtensions: ["jpg","png"]
    });
</script>
@endsection
