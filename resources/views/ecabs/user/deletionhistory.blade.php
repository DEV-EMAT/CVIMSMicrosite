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
                                <h4 class="card-title"><b>Account List</b></h4>
                                <p class="category">View | Update | Deactivate Account </p>
                            </div>
                            <div class="col-lg-2"></div>
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

<!--View Deletion History Modal -->
<div class="modal fade in" tabindex="-1" role="dialog" id="deletionHistory">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <!-- Modal Header -->
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title">Deletion History</h4>
            </div>
            <!-- End Modal Header -->
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                <div class="table-responsive">
                    <table id="historyDatatable" class="table table-bordered table-sm" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Updated By</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Date Info</th>
                                <th>Time Info</th>
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
</div>
<!-- Deletion History Modal -->

@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#historyDatatable').DataTable();
        datatable = $('#datatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('account-deletion-history.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                // { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1] }, 
            ]
        });
    });

    //select data to view
    const view = (id) => {
        $('#historyDatatable').DataTable().clear().destroy();
            historyDatatable = $('#historyDatatable').DataTable({
            "processing": false,
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('account-deletion-history.find-history') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", 'userId' : id}
                },
                "columns": [
                    { "data": "updatedBy" },
                    { "data": "reason" },
                    { "data": "status" },
                    { "data": "date" },
                    { "data": "time" },
                ],
                "aLengthMenu": [[10, 25, 50], [10,  25, 50]],
                "columnDefs": [
                    { "orderable": false, "targets": [ 2, 3, 4 ] }, 
                ]
            });
            $("#deletionHistory").modal("show");
    }
</script>
@endsection