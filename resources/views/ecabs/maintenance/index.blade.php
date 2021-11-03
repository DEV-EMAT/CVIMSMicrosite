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
                            </div>
                            <div class="col-md-2 text-right">
                                @can('permission', 'createPreRegistration')
                                <div data-toggle="modal" data-target="#create_modal">
                                    <a id="add" data-toggle="tooltip" class="btn btn-primary" title="Click here to add new PreRegistration">
                                        <i class="ti-plus"></i> Add new
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>                         
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-smr" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Description</th>
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

@can('permission', 'createPreRegistration')
<div class="modal fade in" tabindex="-1" role="dialog" id="create_modal">
    <div class="modal-dialog" role="document">
        <form id="create_form">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Pre-registration Maintenance</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <blockquote>
                                <p>Fill-up this form to create a new <b>Pre-registration</b>.</p>
                            </blockquote>
                        </div>
                    </div>
                    <div class="row">
                       <div class="form-group col-md-12">
                           <label for="zipcode">Description</label>
                           <input type="text" class="form-control" name="description" id="description" placeholder="Enter description">
                       </div>
                   </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <button class="btn btn-success btn-fill" id="save">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@can('permission', 'updatePreRegistration')
<div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <form id="update_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <blockquote>
                                <p>Fill-up this form to update <b>Pre-registration</b>.</p>
                            </blockquote>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="desc">Description:</label>
                            <input type="text" class="form-control" name="edit_description" id="edit_description"
                                placeholder="Enter Description">
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

<div class="modal fade in" tabindex="-1" role="dialog" id="setup_modal">
    <div class="modal-dialog" role="document">
        <form id="setup_form">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Pre-registration Maintenance</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Status</label>
                        <input type="hidden" name="maintenance_id" id="maintenance_id">
                        <select class="form-control" name="active_status" id="active_status">
                          <option value="1">OPEN REGISTRATION</option>
                          <option value="0">CLOSE REGISTRATION</option>
                        </select>
                      </div>
                    <div class="form-group">
                        <label for="">Platform</label>
                        <select class="form-control" name="platform" id="platform">
                          <option value="1">MOBILE PLATFORM</option>
                          <option value="2">WEB PLATFORM</option>
                          <option value="3">BOTH PLATFORM</option>
                        </select>
                      </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <button class="btn btn-success btn-fill" id="save">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan


@endsection

@section('js')

<script src="{{asset('assets/js/ph_address.js')}}"></script>    
<script>//Start of Function for Address
    let myData = data;
    let region = '';
    let province = '';
    $(document).ready(function () {

        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('maintenance.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "description" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2] }, 
            ]	 	 
        });
        
        @if(!Gate::check('permission', 'updatePreRegistration'))
            datatable.column(2).visible(false);
        @endif
    });

    
    @can('permission', 'createPreRegistration')
    $("#create_form").validate({
        rules: {
            description: {
                required: true,
                minlength: 3
            }
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Save new registration?',
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
                        url: "{{ route('maintenance.store') }}",
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
                                    datatable.ajax.reload( null, false);
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


    @can('permission', 'updatePreRegistration')
    $("#update_form").validate({
        rules: {
            edit_description: {
                required: true,
                minlength: 3
            }
        },
        submitHandler: function (form) {   

            var id = $('#edit_id').val();
            Swal.fire({
                title: 'Update registration?',
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
                        url: '/maintenance/'+id,
                        type: "POST",
                        data: $("#update_form").serialize(),
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
                                    datatable.ajax.reload( null, false);
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
            url:'/maintenance/'+id,
            type:'GET',
            dataType:'json',
            success:function(success){
                $('#edit_id').val(success.id);
                    
                $('#edit_description').val(success.description);

                $('#update_modal').modal('show');
                //process loader false
                processObject.hideProcessLoader();
            }
        });
    }
    

    const setup = (id) => {
        //process loader true
        // processObject.showProcessLoader();
        $.ajax({
            url:'/maintenance/'+id,
            type:'GET',
            dataType:'json',
            success:function(success){
                $('#maintenance_id').val(success.id);
                $('#active_status').val(success.status);
                $('#platform').val(success.platform_id);
                    

                $('#setup_modal').modal('show');
                //process loader false
                // processObject.hideProcessLoader();
            }
        });
    }

    $("#setup_form").validate({
        rules: { },
        submitHandler: function (form) {   
            Swal.fire({
                title: 'Update Registration Status?',
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
                        url: '/maintenance/update-status',
                        type: "POST",
                        data: $("#setup_form").serialize(),
                        dataType: "JSON",
                        success: function (response) {
                            if(response.success){
                                swal({
                                    title: "Updated!",
                                    text: response.messages,
                                    type: "success"
                                }).then(function() {
                                    $("#setup_form")[0].reset();
                                    $('#setup_modal').modal('hide');
                                    datatable.ajax.reload( null, false);
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

</script>
@endsection
