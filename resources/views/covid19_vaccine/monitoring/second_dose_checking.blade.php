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
                                    <h4 class="card-title"><b><i class="fa fa-user-md" aria-hidden="true"></i> Patients List</b></h4>
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
                                        <th>Vaccine Manufacturer</th>
                                        <th>Vaccination Date - First Dose</th>
                                        <th>Approximate Date for Second Dose</th>
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
            "ajax":{
                "url": '{{ route('vaccination-monitoring.find-all-vaccinated-first-dose') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "vaccine_name" },
                { "data": "vaccination_date" },
                { "data": "approximate_date_second_dose" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 0, 1, 2, 3 ] }, 
            ]	 	 
        });
    });


</script>
@endsection
