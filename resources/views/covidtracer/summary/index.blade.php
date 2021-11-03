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
                                <h4 class="card-title"><b>Summary</b></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm" cellspacing="0" width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Person</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Info Date</th>
                                        <th>Info Time</th>
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

<!--Involved Person Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="involvedModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <!-- Modal Header -->
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title">Involved Person</h4>
            </div>
            <!-- End Modal Header -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="involvedDatatable" class="table table-bordered table-sm" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Action</th>
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

<!-- SMS Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="smsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title">Send Message</h4>
            </div>
            <!-- End Modal Header -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <blockquote>
                            <p>Select a <b>SMS</b>.</p>
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
@endsection

@section('js')

<script src="{{asset('assets/js/printing/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/printing/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/js/printing/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/printing/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/printing/buttons.print.min.js')}}"></script>
    <script>
        let search_id = [];
        $(document).ready(function(){
            $('#involvedDatatable').DataTable();
            
            datatables = $('#datatable').DataTable({
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('covidtracer.summary.find-all-summaries') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "fullname" },
                    { "data": "category" },
                    { "data": "status"},
                    { "data": "date"},
                    { "data": "time"},
                    { "data": "buttons"},
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [ 1, 2, 3, 4, 5 ] }, 
                ],
            });

            //change message description
            $("#description").change(function(){
                // alert($("#description").val());
                $.ajax({
                    url:'/covidtracer/sms-notification/get-message/' + $("#description").val(),
                    type:'GET',
                    dataType:'json',
                    success:function(response){
                        $("#message").val(response.message);
                    }
                })
            });

            //send sms
            $("#send").click(function(){
                if($("#description").val() == null){
                    swal.fire({
                        title: "Please select a message.",
                        type: "error"
                    })
                }
                else{
                    Swal.fire({
                        title: 'Are you sure you sure?',
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
                                // url:'{{ route('covidtracer.sms-notification.send-sms') }}',
                                url:'/covidtracer/sms-notification/send-sms',
                                type:'GET',
                                dataType:'json',
                                data:{ 'personId': $("#person_id").val(), 'messageId': $("#description").val()},
                                success:function(data){
                                    if (data.success) {
                                        swal({
                                            title: "SMS Sent!",
                                            text: "Message Sent Successfully!",
                                            type: "success"
                                        }).then(function(){
                                            $("#smsModal").modal("hide");
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
                                }
                            })
                        }
                    })
                }
            });
        });

        //open involved modal
        const viewInvolved = (id) => {
            $("#involvedModal").modal("show");

            $('#involvedDatatable').DataTable().clear().destroy();
            involvedDatatable = $('#involvedDatatable').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('covidtracer.summary.find-all-involved') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", "identifier" : id}
                },
                "columns": [
                    { "data": "fullname" },
                    { "data": "address" },
                    { "data": "contact" },
                    { "data": "status" },
                    { "data": "buttons" },
                ],
                "aLengthMenu": [[10, 25, 50, -1], [10,  25, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": [ 1, 2, 3, 4 ] }, 
                ],
                "searching": false,
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'PRINT',
                        className: 'btn btn-error pull-right',
                        title: '',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3 ]
                        },

                        customize: function ( win ) {
                            $.ajax({
                                url:'{{ route('covidtracer.print-docs.store') }}',
                                type:'POST',
                                data:{ _token:'{{ csrf_token() }}', module:'covid tracer' },
                                dataType:'JSON',
                                success:function(response){
                                    imageCode = '<div style="position:absolute; top:0; right:0; text-align:center"><img src="data:image/png;base64,' + response.data + '"></div>';

                                    $(win.document.body)
                                    .css( 'font-size', '10pt' )
                                    .append(`
                                        <div style="float:right;">
                                            <p style="font-size:10pt">This copy is system generated document.</p>
                                            </div>`);
                                }
                            });

                            
                            $(win.document.body).find( 'table' ).addClass( 'compact' ).css( 'font-size', '6pt' );
                        },
                    }
                ],
            });
        }

        //open involved history
        const viewHistory = (id) => {
            $("#involvedModal").modal("show");
    
            $('#involvedDatatable').DataTable().clear().destroy();
            involvedDatatable = $('#involvedDatatable').DataTable({
            "processing": false,
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('covidtracer.summary.find-tracer-history') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", "identifier" : id}
                },
                "columns": [
                    { "data": "name" },
                    { "data": "establishment" },
                    { "data": "contact" },
                    { "data": "date" },
                    { "data": "time" },
                ],
                "aLengthMenu": [[10, 25, 50], [10,  25, 50]],
                "columnDefs": [
                    { "orderable": false, "targets": [ 1, 2, 3 ] }, 
                ],
            });
        }

        //open sms modal
        const sendSms = (id) => {
            $("#message").val("");
            $("#description").empty();

            $("#smsModal").modal("show");
            $("#person_id").val(id);
            //process loader true
            processObject.showProcessLoader();
            $.ajax({
                url:'{{ route('covidtracer.sms-notification.find-all-for-combobox') }}',
                type:'GET',
                dataType:'json',
                success:function(response){
                    $('[name="description"]').append('<option value ="" disabled selected>Select....</option>');
                    for (let index = 0; index < response.length; index++)
                    {
                        $('[name="description"]').append('<option value='+response[index].id+'>'+ response[index].description+'</option>');
                        $('.selectpicker').selectpicker('refresh');
                    }
                    //process loader false
                    processObject.hideProcessLoader();
                }
            })
        }
    </script>
@endsection