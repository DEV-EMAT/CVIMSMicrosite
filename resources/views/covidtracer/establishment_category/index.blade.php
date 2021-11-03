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
                                    <h4 class="card-title"><b>Establishment Category List</b>
                                    <p class="category">Create | Update | Remove Data</p>
                                </div>
                                <div class="col-md-2 text-right">
                                    @can('permission', 'createEstcat')
                                    <div data-toggle="modal" data-target="#estcatmodal">
                                        <a data-toggle="tooltip" title="Click here to add new Establishment Category" id="add" class="btn btn-primary pull-right">
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
                                            <th>Description</th>
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
</div>
    <!-- End Display All Data -->

    <!-- Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="estcatmodal">
        <div class="modal-dialog" role="document">
            <form id="estcatform">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Est. Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <blockquote>
                                    <p>Fill-up this form to create a new <b>Establishment Category</b>.</p>
                                </blockquote>
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">Description:</label>
                                <input type="text" class="form-control" name="description" id="description"
                                    placeholder="Enter Est. Description">
                            </div>
                        </div>
                        <!-- End Description -->
                        <input type="hidden" id="estcat_id" name="estcat_id">
                    </div>    
            </form>

            <div class="modal-footer">
                <button class="btn btn-success" id="save">Save</button>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('covidtracer.est_cat.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                realtime: true
            },
            "columns": [
                { "data": "description"},
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 2 ] }, 
            ]	 	 
        });
        
        @if(!Gate::check('permission', 'updateEstcat') && !Gate::check('permission', 'deleteEstcat') && !Gate::check('permission', 'restoreEstcat'))
            datatable.column(2).visible(false);
        @endif

        @if(Gate::check('permission', 'restoreEstcat') || Gate::check('permission', 'deleteEstcat'))
        //Submit Establishment Category Form
        $("#estcatform").validate({
            rules: {
                description : {
                    required: true
                },
            },
            submitHandler: function (form) {
                //Add Establishment Category
                if($("#estcat_id").val() == ""){
                    Swal.fire({
                        title: 'Add Now?',
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
                                url: '{{ route('estcat.store') }}',
                                type: "POST",
                                data: $('#estcatform').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        $('#estcatmodal').modal('hide');
                                        $("#estcatform")[0].reset();
                                        $('.selectpicker').selectpicker('refresh');
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
                //Update Establishment Category
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
                                url: '/covidtracer/estcat/' + $('#estcat_id').val() ,
                                type: "PUT",
                                data: $('#estcatform').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        $('#estcatmodal').modal('hide');
                                        $("#estcatform")[0].reset();
                                        $('.selectpicker').selectpicker('refresh');
                                        swal({
                                            title: "Update!",
                                            text: "Successfully!",
                                            type: "success"
                                        })
                                        datatable.ajax.reload( null, false );
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
            }
        });

        //Reset data if Click the Add New
        $("#add").click(function(){
            $('#estcatform')[0].reset();
            $("label.error").hide();
            $(".error").removeClass("error");
            
            //change modal title to add
            $('.modal-title').html("Add Category");
            //change message
            $("#message").html("This will add a new establishment category.");
        });
        @endif
    });

    @can('permission', 'updateEstcat')
    //Select Data to edit
    function edit(id){
        //remove error
        $("label.error").hide();
        $(".error").removeClass("error");

        //change modal title to edit
        $('.modal-title').html("Edit Category");
        //change message
        $("#message").html("This will update the establishment category.");

        var url = "{{ route('estcat.show', ":id") }}";
        url = url.replace(':id', id);
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#estcatmodal").modal("show");
                $("#description").val(data.description);
                $("#estcat_id").val(data.id);
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

    @can('permission', 'deleteEstcat')
    //Deactivate Data
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/covidtracer/estcat/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deleted Successfully!",
                                type: "success"
                            }).then(function(){
                                datatable.ajax.reload( null, false);
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

    
    @can('permission', 'restoreEstcat')
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
                   url: '/covidtracer/estcat/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            }).then(function () {
                                datatable.ajax.reload( null, false);
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
                                type: "error"
                            }).then(function () {
                                datatable.ajax.reload( null, false);
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
