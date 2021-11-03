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
                                <h4 class="card-title"><b>Patient List</b>
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
                                        <th>Date Onset of Illness</th>
                                        <th>Date of Admission Consultation</th>
                                        <th style="width: 600px;">Actions</th>
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
            "ajax":{
                "url": '{{ route('covidtracer.patient-profile.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "dateOnsetOfIllness" },
                { "data": "dateOfAdmissionConsultation" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 3] }, 
            ]	 	 
        });

        /* investigator */
        $.ajax({
            url:'{{ route('covidtracer.investigator.all-investigator') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="investigator"]').append('<option value='+response[index].id+'>'+ response[index].last_name +', '+ response[index].first_name +' '+ response[index].middle_name +'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });

        /* barangay combobox */
        $.ajax({
            url:'{{ route('barangay.findall2') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="barangay"]').append('<option value='+response[index].id+'>'+ response[index].barangay +'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    });

    const monitor = (patient_id, fullname, informant) => {
        let informant_data = informant.split('_');

        /* reset form */
        $("#monitor_form")[0].reset();
        /* modal title */
        $('#monitor_title').text(fullname +' - (Daily Monitoring)');
        $('#patient_id').val(patient_id);
        $('input[name=informant]').val(informant_data[0]);
        $('input[name=relationship]').val(informant_data[1]);
        $('input[name=contact]').val(informant_data[2]);
        $('#monitor_modal').modal('show');
    }

    const exposure = (patient_id, fullname) => {
         /* reset form */
         $("#exposure_form")[0].reset();
        /* modal title */
        $('#exposure_title').text(fullname +' - (History Exposure Monitoring)');
        $('#patient_exposure_id').val(patient_id);

        $('#exposure_modal').modal('show');
    } 

    const history = (patient_id, fullname) => {
        $('#name_title').text(fullname +' - (Monitoring History)');
        /* reinitialize datatable */
        $('#historydatatable').DataTable().clear().destroy();

        $('#historydatatable').DataTable({
            "processing": false,
            "serverSide": true,
            "searching": false,
            "ajax":{
                "url": '{{ route('covidtracer.patient-monitoring.history') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", patient_id: patient_id}
            },
            "columns": [
                { "data": "created_at" },
                { "data": "investigator" },
                { "data": "daily_conditions" },
                { "data": "fever_degree" },
                { "data": "cough" },
                { "data": "sore_throat" },
                { "data": "colds" },
                { "data": "shortness_difficulty_of_breathing" },
                { "data": "vomiting" },
                { "data": "diarrhea" },
                { "data": "fatigue_chills" },
                { "data": "headache" },
                { "data": "joint_pains" },
                { "data": "other_symptoms" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1,2,3,4,5,6,7,8,9,10,11] }, 
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
                            data:{ _token:'{{ csrf_token() }}', module:'PATIENT MONITORING' },
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
                        return 'MONITORING' + n;
                    },
                },{
                    extend: 'excel',
                    text: 'EXCEL',
                    className: 'btn btn-success pull-right',
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
                        return 'MONITORING' + n;
                    },
                }
            ],	 
        });

        $('#history_modal').modal('show');
        
    }

    $("#monitor_form").validate({
        rules: {
            informant: {
                required: true,
                minlength: 3
            },
            relationship: {
                required: true,
                minlength: 3
            },
            contact: {
                required: true,
                phoneno: true
            },
            investigator: {
                required: true
            },
            barangay: {
                required: true
            },
            category: {
                required: true
            },
            dailyCondition: {
                required: true,
                minlength: 3
            },
            placeDescription:{
                required: true,
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
                        url: "{{ route('patient-monitoring.store') }}",
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
                                    $(".selectpicker").val('').selectpicker("refresh");
                                    $("#monitor_form")[0].reset();
                                    $('#monitor_modal').modal('hide');
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

    const update_tracked = (id) => {
        Swal.fire({
                title: 'Update exposure history tracked status?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    //process loasder true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: "/covidtracer/patient-monitoring/toggle-tracked-status/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function (response) {
                            if(response.success){
                                swal({
                                    title: "Success!",
                                    text: response.messages,
                                    type: "success"
                                }).then(function() {
                                    // $('#exposure_history_modal').modal('hide');
                                    exposure_history.ajax.reload( null, false);
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

    
    $('#add_column').click(function(){
        if(ctr < 10){
            $('#enteracted_with tbody').append(`
            <tr>
                <td><input type="text" name="fullname[]" class="form-control"></td>
                <td style="text-align: center"><input type="checkbox" name="tracked[]" value="${ctr}" style="zoom:1.75"></td>
                <td><a class="btn btn-danger btn-sm" id="remove_column"><i class="fa fa-minus"></i></a></td>    
            </tr>`);

            ctr++;      
        }else{
            alert('Maximum of 10 fields only!');
        }
    });

    $('#enteracted_with tbody').on("click", "#remove_column", function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        ctr--;
    });

    $("#exposure_form").validate({
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
            personInteracted: {
                required: true
            },
            remarks: {
                minlength:3,
            },
            category: {
                required: true
            },
            investigator: {
                required: true
            },
            barangay: {
                required: true
            },
            placeDescription: {
                required: true
            },
         },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Save new history?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    
                    var formData = new FormData($("#exposure_form").get(0));
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: "{{ route('covidtracer.exposure-history.store') }}",
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
                                    
                                    $(".selectpicker").val('').selectpicker("refresh");
                                    $("#exposure_form")[0].reset();
                                    $('#exposure_modal').modal('hide');
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

    const history_exposure = (patient_id, fullname) => {
        $('#exposure_name_title').text(fullname +' - (Exposure History)');
        /* reinitialize datatable */
        $('#history_exposure_datatable').DataTable().clear().destroy();

        exposure_history = $('#history_exposure_datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "searching": false,
            "ajax":{
                "url": '{{ route('covidtracer.patient-monitoring.exposure-history') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", patient_id: patient_id}
            },
            "columns": [
                { "data": "investigator" },
                { "data": "date_of_exposure" },
                { "data": "time_of_exposure" },
                { "data": "mode_of_transportation" },
                { "data": "places_of_engagement" },
                { "data": "person_enteracted_with" },
                { "data": "remarks" },
                { "data": "tracked_status" },
                { "data": "tracked_action" }
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 0,1,2,3,4,5,6,7] }, 
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
                        columns: [ 0,1,2,3,4,5,6,7 ]
                    },
                    customize: function ( win ) {
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url:'{{ route('covidtracer.print-docs.store') }}',
                            type:'POST',
                            data:{ _token:'{{ csrf_token() }}', module:'EXPOSURE HISTORY' },
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
                        columns: [ 0,1,2,3,4,5,6,7 ]
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
                        columns: [ 0,1,2,3,4,5,6,7 ]
                    },
                    filename: function(){
                        var d = new Date();
                        var n = d.getTime();
                        return 'MONITORING' + n;
                    },
                }
            ],	 
        });

        $('#exposure_history_modal').modal('show');
        
    }


    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(09|\+639)\d{9}$/);
    }, "<br />Please specify a valid phone number");


</script>
@endsection
