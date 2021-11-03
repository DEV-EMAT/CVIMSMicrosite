@extends('layouts.app2')
@section('location')
{{$title}}
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="card-title"><b>History</b></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm" cellspacing="0" width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Fullname</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <!--Table head-->
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

<!-- Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="smsModal">
        <div class="modal-dialog" role="document">
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
                                <p>Please select a <b>SMS</b>.</p>
                            </blockquote>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="description">Description:</label>
                            <!-- <input type="text" class="form-control" name="description" id="description"
                                placeholder="Enter Est. Description"> -->
                            <select class="selectpicker form-control" name="description" id="description">
                                <option value="" disabled selected>Select.....</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="message">Message:</label>
                            <textarea class="form-control" type="text" name="message" id="message" disabled></textarea>
                        </div>
                    </div>
                    <!-- End Description -->
                    <input type="hidden" id="person_id" name="person_id">
                </div>    

                <div class="modal-footer">
                    <button class="btn btn-success" id="send">Send SMS</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
 
    <!--View Sms History-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="smsHistory">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-lg">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Sms History</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="table-responsive">
                        <table id="historyDatatable" class="table table-bordered table-sm" cellspacing="0"
                            width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Sender</th>
                                    <th>Message</th>
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
    <!-- End Modal -->
@endsection

@section('js')
    <script>
        let search_id = [];
        $(document).ready(function(){
            $('#historyDatatable').DataTable();
            
            datatable = $('#datatable').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('account.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", 'action' : 'smsHistory'}
                },
                "columns": [
                    { "data": "fullname" },
                    { "data": "department" },
                    { "data": "status" },
                    { "data": "actions" },
                ],
            });
        });

        const viewSmsHistory = (id) => {
            $('#historyDatatable').DataTable().clear().destroy();
            historyDatatable = $('#historyDatatable').DataTable({
            "processing": false,
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('covidtracer.sms-notification.find-history') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", 'receiverId' : id}
                },
                "columns": [
                    { "data": "sender" },
                    { "data": "message" },
                    { "data": "status" },
                    { "data": "date" },
                    { "data": "time" },
                ],
                "aLengthMenu": [[10, 25, 50], [10,  25, 50]],
                "columnDefs": [
                    { "orderable": false, "targets": [ 2, 3, 4 ] }, 
                ]
            });
            $("#smsHistory").modal("show");
        }

    </script>
@endsection