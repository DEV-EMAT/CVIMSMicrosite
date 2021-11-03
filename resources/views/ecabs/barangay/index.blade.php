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
                                    <h4 class="card-title"><b>Barangay List</b>
                                    <p class="category">Create | Update | Remove Data</p> 
                                </div>
                                <div class="col-md-2 text-right">
                                    @can('permission', 'createBarangay')
                                    <div data-toggle="modal" data-target="#barangaymodal">
                                        <a data-toggle="tooltip" id="add" class="btn btn-primary" title="Click here to add new Barangay">
                                            <i class="ti-plus"></i> Add new
                                        </a>
                                    </div>
                                    @endcan
                                </div>
                            </div> 
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-sm" width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th>Barangay</th>
                                            <th>City</th>
                                            <th>Province</th>
                                            <th>ZIP Code</th>
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
                    </div>
                </div> <!-- end col-md-12 -->
            </div>
        </div>
    </div>
    <!-- End Display All Data -->

    @if(Gate::check('permission','createBarangay') || Gate::check('permission','updateBarangay'))
    <!-- Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="barangaymodal">
        <div class="modal-dialog" role="document">
            <form id="barangayform" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Barangay</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <blockquote>
                                    <p>Fill-up this form to create a new <b>Barangay Information</b>.</p>
                                </blockquote>
                            </div>
                        </div>
                        <!-- Barangay -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="barangay">BARANGAY:</label>
                                <input type="text" class="form-control" name="barangay" id="barangay"
                                    placeholder="Enter Barangay">
                            </div>
                        </div>
                        <!-- End Barangay -->
                        <!-- City -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="city">CITY:</label>
                                <input type="text" class="form-control" name="city"
                                    id="city" placeholder="Enter City">
                            </div>
                        </div>
                        <!-- End City --> 
                        <!-- Province -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="province">PROVINCE:</label>
                                <input type="text" class="form-control" name="province"
                                    id="province" placeholder="Enter Province">
                            </div>
                        </div>
                        <!-- End Province -->
                         <!-- Zip Code -->
                         <div class="row">
                            <div class="form-group col-md-12">
                                <label for="zipcode">ZIP CODE:</label>
                                <input type="text" class="form-control" name="zipcode"
                                    id="zipcode" placeholder="Enter Zip Code">
                            </div>
                        </div>
                        <!-- End Zip Code -->
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" id="save">Save</button>
                    </div>
                    <input type="hidden" id="barangayid" name="barangayid">
                </div>
            </form>

        </div>
    </div>
    <!-- End Modal -->
    @endif
@endsection

@section('js')
<script>



    $(document).ready(function () {
        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('barangay.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                realtime: true
            },
            "columns": [
                { "data": "barangay" },
                { "data": "city" },
                { "data": "province" },
                { "data": "zipcode" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 3, 4, 5 ] }, 
            ]	 	 
        });

        @if(Gate::check('permission','createBarangay') || Gate::check('permission','updateBarangay'))
        //Add Barangay
        $("#barangayform").validate({
            rules: {
                barangay: {
                    minlength: 2,
                    required: true
                },
                city: {
                    minlength: 3,
                    required: true
                },
                province: {
                    minlength: 3,
                    required: true
                },
                zipcode: {
                    minlength: 3,
                    required: true
                }
            },
            submitHandler: function (form) {
                //Add Barangay
                if($("#barangayid").val() == ""){
                    Swal.fire({
                        title: 'Add Now?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        //process loader true
                        processObject.showProcessLoader();
                        if (result.value) {
                            //process loader true
                            processObject.showProcessLoader();
                            $.ajax({
                                url: '{{ route('barangay.store') }}',
                                type: "POST",
                                data: $('#barangayform').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        $('#barangaymodal').modal('hide');
                                        $("#barangayform")[0].reset();
                                        datatable.ajax.reload( null, false );
                                        swal({
                                            title: "Save!",
                                            text: "Successfully!",
                                            type: "success"
                                        })
                                        //process loader false
                                        processObject.hideProcessLoader();
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
                //Update Barangay
                else{
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
                                url: '/barangay/' + $("#barangayid").val(),
                                type: "PUT",
                                data: $('#barangayform').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        $('#barangaymodal').modal('hide');
                                        $("#barangayform")[0].reset();
                                        datatable.ajax.reload( null, false );
                                        swal({
                                            title: "Update!",
                                            text: "Successfully!",
                                            type: "success"
                                        })
                                        //process loader false
                                        processObject.hideProcessLoader();
                                    } else {
                                        //process loader false
                                        processObject.hideProcessLoader();
                                        swal.fire({
                                            title: "Oops! something went wrong.",
                                            text: data.messages,
                                            type: "error"
                                        })
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
            }
        });

        //Reset data if Click the Add New
        $("#add").click(function(){
            $("#barangayform")[0].reset();
            $('label.error').hide();
            $('.error').removeClass('error');

            //change modal title to edit
            $('.modal-title').html("Add Barangay");
            //change message
            $('#message').html("This will add a new barangay information.");
        });
        @endif


        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");

        
        @if(!Gate::check('permission', 'updateBarangay') && !Gate::check('permission', 'deleteBarangay') && !Gate::check('permission', 'restoreBarangay'))
            datatable.column(5).visible(false);
        @endif
    });

    @can('permission', 'updateBarangay')
    //Select Data to edit
    function edit(id){
        //remove error
        $('label.error').hide();
        $('.error').removeClass('error');
        
        //change modal title to edit
        $('.modal-title').html("Edit Barangay");
        //change message
        $('#message').html("This will update barangay information.");
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url: '/barangay/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#barangaymodal").modal("show");
                $("#barangay").val(data.barangay);
                $("#city").val(data.city);
                $("#province").val(data.province);
                $("#zipcode").val(data.zipcode);
                $("#barangayid").val(data.id);
                //process loader false
                processObject.hideProcessLoader();
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
    @endcan
    
    //Activate or Deactivate Status
    function toggleStatus(id, status){
        if(status == 1)
            deactivate(id);
        else
            activate(id);
    }

    @can('permission', 'deleteBarangay')
    //Deactivate Data
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/barangay/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            datatable.ajax.reload( null, false );
                            swal({
                                title: "Save!",
                                text: "Deleted Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
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
    @endcan

    @can('permission', 'restoreBarangay')
    //Activate Data
    const activate = (id) =>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Restore it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/barangay/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            datatable.ajax.reload( null, false );
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
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
    @endcan
</script>
@endsection
