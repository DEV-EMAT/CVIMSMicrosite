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
                                    <h4 class="card-title"><b>Investigator List</b>
                                    <p class="category">Create | Update | Remove Data</p>
                                </div>
                                <div class="col-md-2 text-right">
                                    @can('permission', 'createInvestigator')
                                    <div data-toggle="modal" data-target="#investigator_modal">
                                        <a data-toggle="tooltip" id="add" class="btn btn-primary" title="Click here to assign a new Investigator">
                                            <i class="ti-plus"></i> Add new
                                        </a>
                                    </div>
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
                                        <th>Full Name</th>
                                        <th>Address</th>
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
    <!-- Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="investigator_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">User Lists</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <blockquote>
                                <p>Select a new <b>Investigator</b>.</p>
                            </blockquote>
                        </div>
                    </div>
                    <table id="datatable_users" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Full Name</th>
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
                "url": '{{ route('covidtracer.investigator.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                realtime: true
            },
            "columns": [
                { "data": "full_name"},
                { "data": "address"},
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 2 ] }, 
            ]	 	 
        });

        datatable_users = $('#datatable_users').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('covidtracer.investigator.findalluser') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                realtime: true
            },
            "columns": [
                { "data": "full_name"},
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1 ] }, 
            ]	 	 
        });
    });

    //Activate or Deactivate Status
    function toggleStatus(id, status){
        if(status == 1)
            deactivate(id);
        else
            activate(id);
    }

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
                   url: '/covidtracer/investigator/status/' + id,
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

    //Activate Data
    const activate = (id) =>{
        Swal.fire({
            title: 'Restore Investigator?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/covidtracer/investigator/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
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

    //add Investigator
    const addInvestigator = (id) =>{
        Swal.fire({
            title: 'Add Investigator?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Add it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/covidtracer/investigator/add-investigator/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            }).then(function(){
                                datatable.ajax.reload( null, false);
                                $('#investigator_modal').modal('hide');
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
    </script>
@endsection
