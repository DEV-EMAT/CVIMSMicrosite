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
                            <div class="col-md-9">
                                <h4 class="card-title"><b>Patient Reports</b>
                                <p class="category">View Data</p>
                            </div>
                            <div class="col-md-3 text-right">
                                <label for="patientStatus" style="float: left">Status: </label>
                                <select id="patientStatus" class="form-control selectpicker">
                                    <option value="" disabled selected>Select.....</option>
                                    <option value="0">All</option>
                                    <option value="1">On Going</option>
                                    <option value="2">Recovered</option>
                                    <option value="3">Deceased</option>
                                </select>
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
                                        <th>Date Onset of Illness</th>
                                        <th>Date of Admission Consultation</th>
                                        <th>Status</th>
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

                <input type="hidden" id="patient_id" name="patient_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th class="text-center">SIGNS/SYMPTOMS</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Fever ≥ 38 °C?</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="fever" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sore Throat</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="soreThroat" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Cough</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="cough" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nasal Congestion/Colds</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="nasalCongestion" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Shortness of Breath ( ≥ 30/minutes )</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="shortnessOfBreath" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Vomiting</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="vomiting" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Diarrhea</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="diarrhea" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Fatigue/Chills</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="fatigue" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Headache</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="headache" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Joint Pains</td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label text-center">
                                                    <input style="width: 20px; height: 20px;" class="form-check-input" type="checkbox" name="jointPains" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <label for="">Other Sysmptoms</label>
                                <textarea class="form-control" name="otherSysmtoms" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="">Daily Condition *</label>
                              <input type="text" class="form-control" name="dailyCondition" placeholder="Daily Condition">
                            </div>
                            <div class="form-group">
                                <label for="">Status *</label>
                                <select class="selectpicker form-control" name="status">
                                    <option disabled selected>SELECT</option>
                                    <option value="1">On Going</option>
                                    <option value="2">Monitoring Completed</option>
                                    <option value="3">Deceased</option>
                                </select>
                            </div> 
                            <div class="well">
                                <div class="form-group">
                                  <label for="">Name of Informant *</label>
                                  <input type="text" class="form-control" name="informant" placeholder="Informant">
                                </div>
                                <div class="form-group">
                                  <label for="">Relationship *</label>
                                  <input type="text" class="form-control" name="relationship" placeholder="Relationship">
                                </div>
                                <div class="form-group">
                                  <label for="">Contact # *</label>
                                  <input type="number" class="form-control" name="contact">
                                </div>
                            </div>
                            <div class="well">
                                <div class="form-group">
                                    <label for="">Assign Category *</label>
                                    <select class="selectpicker form-control" name="category">
                                        <option disabled selected>SELECT</option>
                                        <option value="COMMAND CENTER">COMMAND CENTER</option>
                                        <option value="BHERT">BHERT</option>
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="">Investigator *</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="investigator">
                                        <option disabled value="" selected>SELECT</option>
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="">Place of Assignment (Barangay) *</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="barangay">
                                        <option disabled value="" selected>SELECT</option>
                                    </select>
                                </div> 

                                <div class="form-group">
                                  <label for="">Place of Assignment (Description) *</label>
                                  <textarea class="form-control" rows="2" name="placeDescription"></textarea>
                                </div>
                            </div>
                        </div>
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


<div class="modal fade" id="exposure_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title" id="exposure_title">History Exposure Monitoring</h4>
            </div>
            <form id="exposure_form">
                @csrf
                @method('POST')

                <input type="hidden" id="patient_exposure_id" name="patient_exposure_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Date of Exposure *</label>
                                <input type="text" class="form-control datetimepicker" name="date" id="date">
                            </div>
                            <div class="form-group">
                                <label for="">Time of Exposure *</label>
                                <input type="time" class="form-control" name="time" id="time">
                            </div>
                            <div class="form-group">
                                <label for="">Mode of Transportation *</label>
                                <input type="text" class="form-control" name="modeOfTranspo" id="modeOfTranspo" placeholder="Transportation">
                            </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Place of Engagement *</label>
                                <textarea class="form-control" name="placeOfEngagement" id="placeOfEngagement" rows="1"></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="overflow:auto !important; max-height:400px">
                            <label>Person Interacted With</label>
                            <table id="enteracted_with" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fullname</th>
                                        <th>Tracked</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="fullname[]" class="form-control"></td>
                                        <td style="text-align: center"><input name="tracked[]" type="checkbox" style="zoom:1.75" value="1"></td>
                                        <td><a class="btn btn-primary btn-sm" id="add_column"><i class="fa fa-plus"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="well">
                                <div class="form-group">
                                    <label for="">Assign Category *</label>
                                    <select class="selectpicker form-control" name="category">
                                        <option disabled selected>SELECT</option>
                                        <option value="COMMAND CENTER">COMMAND CENTER</option>
                                        <option value="BHERT">BHERT</option>
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="">Investigator *</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="investigator">
                                        <option disabled value="" selected>SELECT</option>
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="">Place of Assignment (Barangay) *</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="barangay">
                                        <option disabled value="" selected>SELECT</option>
                                    </select>
                                </div> 

                                <div class="form-group">
                                <label for="">Place of Assignment (Description) *</label>
                                <textarea class="form-control" rows="2" name="placeDescription"></textarea>
                                </div>
                            </div>
                        </div>
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
                                <th>Date Monitor</th>
                                <th>Investigator</th>
                                <th>Daily Condition</th>
                                <th>Fever</th>
                                <th>Cough</th>
                                <th>Sore Throat</th>
                                <th>Colds</th>
                                <th>Shortness of Breathing</th>
                                <th>Vomiting</th>
                                <th>Diarrhea</th>
                                <th>Fatigue</th>
                                <th>Headache</th>
                                <th>Join Pain</th>
                                <th>Other Symptoms</th>
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

<div class="modal fade" id="exposure_history_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title" id="exposure_name_title">Exposure History</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="history_exposure_datatable" class="table table-bordered table-sm" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Inspector</th>
                                <th>Date of Exposure</th>
                                <th>Time of Exposure</th>
                                <th>Mode of Transportation</th>
                                <th>Place of Engagement</th>
                                <th>Person Contacted With</th>
                                <th>Remarks</th>
                                <th>Tracked Status</th>
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


@endsection

@section('js')

<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<script>
    let ctr = 1;
    let max_field = 10;
    let exposure_history = [];
    $(document).ready(function (){
        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "searching": false,
            "ajax":{
                "url": '{{ route('covidtracer.patient-monitoring.find-all-reports') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", "patientStatus" : $("#patientStatus").val()}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "dateOnsetOfIllness" },
                { "data": "dateOfAdmissionConsultation" },
                { "data": "status" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 3] }, 
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
                            data:{ _token:'{{ csrf_token() }}', module:'PATIENT REPORTS' },
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

                       
                
                        $(win.document.body).find( 'table' ).css( 'font-size', '6pt' );
                        
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
                    exportOptions: {
                        format: {
                            body: function( data, row, column, node ) {
                               return (data == "<label class='label label-success'>RECOVERED</label>")? data.replace("<label class='label label-success'>RECOVERED</label>", 'RECOVERED' ):data.replace("<label class='label label-danger'>ON GOING</label>", 'ON GOING' );
                            }
                        }
                    },
                    filename: function(){
                        var d = new Date();
                        var n = d.getTime();
                        return 'MONITORING' + n;
                    },
                },{
                    extend: 'excel',
                    text: 'EXCEL',
                    className: 'btn btn-success pull-right',
                    exportOptions: {
                        format: {
                            body: function( data, row, column, node ) {
                                return (data == "<label class='label label-success'>RECOVERED</label>")? data.replace("<label class='label label-success'>RECOVERED</label>", 'RECOVERED' ):data.replace("<label class='label label-danger'>ON GOING</label>", 'ON GOING' );
                            }
                        }
                    },
                    filename: function(){
                        var d = new Date();
                        var n = d.getTime();
                        return 'MONITORING' + n;
                    },
                }
            ], 	 
        });

        $("#patientStatus").change(function(){
            $('#datatable').DataTable().clear().destroy();
            //Datatables
            $('#datatable').DataTable({
                "processing": false,
                "serverSide": true,
                "searching": false,
                "ajax":{
                    "url": '{{ route('covidtracer.patient-monitoring.find-all-reports') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", "patientStatus" : $("#patientStatus").val()}
                },
                "columns": [
                    { "data": "fullname" },
                    { "data": "dateOnsetOfIllness" },
                    { "data": "dateOfAdmissionConsultation" },
                    { "data": "status" },
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [ 1, 3] }, 
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
                        stripHtml: false,
                        format: {
                            body: function( data, row, column, node ) {
                                
                                console.log(data);
                                // return (data == "<i class='fa fa-check'></i>")? data.replace("<i class='fa fa-check'></i>", 'YES' ):data.replace("--", 'NO' );
                            }
                        }
                    },
                    customize: function ( win ) {
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url:'{{ route('covidtracer.print-docs.store') }}',
                            type:'POST',
                            data:{ _token:'{{ csrf_token() }}', module:'PATIENT REPORTS' },
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

                        $(win.document.body).find( 'table' ).css( 'font-size', '6pt' );
                        
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
                    exportOptions: {
                        format: {
                            body: function( data, row, column, node ) {
                                return (data == "<i class='fa fa-check'></i>")? data.replace("<i class='fa fa-check'></i>", 'YES' ):data.replace("--", 'NO' );
                            }
                        }
                    },  
                    filename: function(){
                        var d = new Date();
                        var n = d.getTime();
                        return 'REPORTS ' + n;
                    },
                },{
                    extend: 'excel',
                    text: 'EXCEL',
                    className: 'btn btn-success pull-right',
                    exportOptions: {
                        format: {
                            body: function( data, row, column, node ) {
                                return(data);
                                // return (data == "<label class='label label-success'>RECOVERED</label>")? data.replace("<label class='label label-success'>RECOVERED</label>", 'RECOVERED' ):data.replace("<label class='label label-danger'>ON GOING</label>", 'ON GOING' );
                            }
                        }
                    },
                    filename: function(){
                        var d = new Date();
                        var n = d.getTime();
                        return 'MONITORING' + n;
                    },
                }
            ], 		 	 
            });
        })
    });

</script>
@endsection
