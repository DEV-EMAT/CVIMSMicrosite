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
                            <div class="col-md-6">
                                <h4 class="card-title"><b>Investigator List</b>
                                <p class="category">Create | Update | Remove Data</p>
                            </div>
                            <div class="col-md-6 text-right">
                        
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
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Address</th>
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

<div class="modal fade" id="monitor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title" id="monitor_title">Patient Daily Monitoring</h4>
            </div>
            <form id="monitor_form">
                @csrf
                @method('POST')

                <input type="hidden" id="investigator_id" name="investigator_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Date *</label>
                        <input type="text" class="form-control datetimepicker" name="date" id="date">
                    </div>
                    <div class="form-group">
                        <label for="">Time *</label>
                        <input type="time" class="form-control" name="time" id="time">
                    </div>
                    <div class="form-group">
                        <label for="">Mode of Transportation *</label>
                        <input type="text" class="form-control" name="modeOfTranspo" id="modeOfTranspo" placeholder="Transportation">
                    </div>
                    <div class="form-group">
                        <label for="">Place of Engagement *</label>
                        <textarea class="form-control" name="placeOfEngagement" id="placeOfEngagement" rows="2"></textarea>
                    </div> 
                    <div class="form-group">
                        <label for="">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <input type="submit" class="btn btn-info btn-fill btn-wd"/>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="history_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title" id="name_title">Monitoring History</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="historydatatable" class="table table-bordered table-sm" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Place of Engagement</th>
                                <th>Mode of Transportation</th>
                                <th>Remarks</th>
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


@endsection

@section('js')

<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function (){
        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('covidtracer.investigator.find-all-investigator') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "contact" },
                { "data": "address" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2, 3] }, 
            ]	 	 
        });
    });

    const monitor = (investigator_id, fullname) => {
        $("#monitor_form")[0].reset();

        $('#monitor_title').text(fullname +' - (Daily Activity Monitoring)');
        $('#investigator_id').val(investigator_id);
        $('#monitor_modal').modal('show');
    }

    const history = (investigator_id, fullname) => {

        $('#name_title').text(fullname +' - (Activity Monitoring History)');

        $('#historydatatable').DataTable().clear().destroy();

        $('#historydatatable').DataTable({
            "processing": false,
            "serverSide": true,
            "searching": false,
            "ajax":{
                "url": '{{ route('covidtracer.investigator-monitoring.history') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", investigator_id: investigator_id}
            },
            "columns": [
                { "data": "date" },
                { "data": "time" },
                { "data": "place_of_engagement" },
                { "data": "mode_of_transpo" },
                { "data": "remarks" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2, 3, 4 ] }, 
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'PRINT',
                    className: 'btn btn-error pull-right',
                    title: '',
                    exportOptions: {
                        stripHtml: false
                    },
                    customize: function ( win ) {
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url:'{{ route('covidtracer.print-docs.store') }}',
                            type:'POST',
                            data:{ _token:'{{ csrf_token() }}', module:'investigator monitoring' },
                            dataType:'JSON',
                            success:function(response){

                                imageCode = '<div style="position:absolute; top:0; right:0; text-align:center"><img src="data:image/png;base64,' + response.data + '"></div>';

                                $(win.document.body)
                                .css( 'font-size', '10pt' )
                                .prepend(` ${imageCode}
                                    <div style="display:flex; width:100%; max-height:100px; justify-content:center; text-align:center;">
                                        <div style="display:flex; width:50%; justify-content:space-around; align-items:center">
                                            <div>
                                                <img src="{{ asset('/assets/image/cabuyao-city-logo.png') }}" width="80px"/>
                                            </div>
                                            <div>
                                                <p style="line-height:15px;">Republic of the Philippines</p>
                                                <p style="line-height:15px;">Province of Laguna</p>
                                                <p style="font-size:28;font-weight:bold;">City of Cabuyao</p>
                                            </div>
                                            <div>
                                                <img src="{{ asset('/assets/image/cabuyao-city-logo.png') }}" width="80px"/>
                                            </div>
                                        </div>
                                    </div>`
                                ).append(`
                                    <div style="float:right;">
                                        <p style="font-size:10pt">This copy is system generated document.</p>
                                    </div>`);

                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                
                        $(win.document.body).find( 'table' ).addClass( 'compact' ).css( 'font-size', '6pt' );
                        
                        /* set landscape when print */
                        var last = null;
                        var current = null;
                        var bod = [];
        
                        var css = '@page { size: landscape; }',
                            head = win.document.head || win.document.getElementsByTagName('head')[0],
                            style = win.document.createElement('style');
        
                        style.type = 'text/css';
                        style.media = 'print';
        
                        if (style.styleSheet) {
                            style.styleSheet.cssText = css;
                        } else {
                            style.appendChild(win.document.createTextNode(css));
                        }
        
                        head.appendChild(style);
                    },
                },{
                    extend: 'csv',
                    text: 'CSV',
                    className: 'btn btn-primary pull-right',
                    filename: function(){
                        var d = new Date();
                        var n = d.getTime();
                        return 'MONITORING' + n;
                    },
                },{
                    extend: 'excel',
                    text: 'EXCEL',
                    className: 'btn btn-success pull-right',
                    filename: function(){
                        var d = new Date();
                        var n = d.getTime();
                        return 'MONITORING' + n;
                    },
                }
            ],	 
        });

        $('#history_modal').modal('show');
        
    }

    $("#monitor_form").validate({
        rules: {
            date: {
                required: true
            },
            time: {
                required: true
            },
            modeOfTranspo: {
                required: true,
            },
            placeOfEngagement: {
                required: true
            },
            remarks: {
                minlength: 3
            }
         },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Save new monitoring?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    
                    var formData = new FormData($("#monitor_form").get(0));
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: "{{ route('investigator-monitoring.store') }}",
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
                                    $("#monitor_form")[0].reset();
                                    $('#monitor_modal').modal('hide');
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

</script>
@endsection
