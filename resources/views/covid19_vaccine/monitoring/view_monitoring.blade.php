@extends('layouts.app2')

@section('location')
{{$title}}
@endsection
@section('style')
    <style>
        #summary_datatable_paginate{
            display: none;
        }
        
        .choice{
            text-align: center;
        }
        
        input[type="radio"]{
            zoom: 1.75;
        }
        
        input[type=checkbox]{
            zoom: 1.3;
        }
        
        .surveyLabel{
            font-size: 17px;
        }
        .radio-toolbar label {
            display: inline-block;
            background-color: #ddd;
            padding: 10px 20px;
            font-family: sans-serif, Arial;
            font-size: 16px;
            border: 2px solid #444;
            border-radius: 4px;
        }
        
        label.btn-success:hover{
            background: green;
        }
        
        td.details-control {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('../assets/image/minus.png') no-repeat center center;
        }
    </style>
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
                                    <h4 class="card-title"><b><i class="fa fa-user-md" aria-hidden="true"></i> Monitoring Summary List</b></h4>
                                    <p class="category">Vaccination Monitoring</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Fullname</th>
                                        <th>Vaccination Date</th>
                                        <th>Dosage</th>
                                        <th>Vaccine Manufacturer</th>
                                        <th>Batch Number</th>
                                        <th>Lot Number</th>
                                        <th>Vaccinator</th>
                                        <th>Health Facility</th>
                                        <th>Date Encoded</th>
                                        <th>Time Encoded</th>
                                        <th>Encoded By</th>
                                        <th>Consent</th>
                                        <th>Reason for Refusal</th>
                                        <th>Deferral</th>
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
@endsection

@section('js')
<script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
<script>
    $(document).ready(function () {
        
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "language": {
            processing: '<i style="width: 50px;" class="fa fa-spinner fa-spin fa-lg fa-fw"></i><b> Processing....</b>',
            "sSearch": " <b style='color:red;'><i>(Fistname Lastname) e.g. juan de la cruz</i></b><br>Press Enter to search:"
            },
            "ajax":{
                "url": '{{ route('vaccination-monitoring.find-all-summary') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "vaccination_date" },
                { "data": "dosage" },
                { "data": "vaccine_name" },
                { "data": "batch_number" },
                { "data": "lot_number" },
                { "data": "vaccinator" },
                { "data": "facility" },
                { "data": "date_encoded" },
                { "data": "time_encoded" },
                { "data": "encoded_by" },
                { "data": "consent" },
                { "data": "reason_for_refusal" },
                { "data": "deferral" },
            ],
            lengthMenu: [10, 25, 50, 100, 500, 1000, 10000],
            "columnDefs": [
                { "orderable": false, "targets": [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ] }, 
            ],
            initComplete: function() {
                $('.dataTables_filter input').unbind();
                $('.dataTables_filter input').bind('keyup', function(e){
                    var code = e.keyCode || e.which;
                    if (code == 13) {
                        datatable.search(this.value).draw();
                    }
                });
            }
        });
        
        //Show other details
        $('#datatable tbody').on('click', 'td.details-control', function () {
        
            let datatable = $('#datatable').DataTable();
            var tr = $(this).closest('tr');
            var row = datatable.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });
    });
    
    //View other information
    const format = (d) => {
        var output = "";
        output += `<div class="col-md-4 col-md-offset-4">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th style="width: 90%;">Questions</th>
                            <th>Remarks</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Edad ay mas mababa sa 18 taong gulang?</td>
                                <td style="text-align:center">`;
        
        return output;
    }


</script>
@endsection
