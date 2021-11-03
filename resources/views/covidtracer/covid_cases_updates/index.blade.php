@extends('layouts.app2')
@section('location')
{{$title}}
@endsection
@section('style')
    <style>
        #reportTable th{
            text-align: center;
            padding: 2px;
            width: 5%;
        }
        #reportTable td{
            padding:2px;
            width: 5%;
        }
        input{
            /* font-size: 20px; */
        }
    </style>    
@endsection('style')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date (From)</label>
                                        <input type='text' class="form-control datetimepicker" id='date_from' name="date_from" max="9999-12-31"
                                        placeholder="Date"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Date (To)</label>
                                    <input type='text' class="form-control datetimepicker" id='date_to' name="date_to" max="9999-12-31"
                                    placeholder="Date" disabled/>
                                </div>
                                <div class="form-group col-md-6"><br>
                                    <input type="button" id="search" class="btn btn-primary pull-right" value="Search">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col-md-12 -->
            </div>
        </div>
    </div>
    <!-- Display All Data -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="row">
                                <div class="col-md-10">
                                    <h4 class="card-title"><b>Covid Cases Updates</b></h4>
                                </div>
                                <div class="col-md-2">
                                    @can('permission', 'createCovidCasesUpdates')
                                    <div data-toggle="modal">
                                    <a data-toggle="tooltip" id="add" class="btn btn-primary pull-right" title="Click here to update COVID statistics.">
                                        <i class="ti-plus"></i> Add new
                                    </a>
                                    @endcan
                                </div>
                            </div>                        
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-lg" cellspacing="0"
                                    width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th>Barangay</th>
                                            <th>Confirmed</th>
                                            <th>Active</th>
                                            <th>Recovered</th>
                                            <th>Deceased</th>
                                            <th>Suspect</th>
                                            <th>Probable</th>
                                            <th>BJMP Cases</th>
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

    <!--Search Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="covidSearchModal">
        <div class="modal-dialog modal-lg" style="width: 90%" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Covid Report</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="overflow-x:auto;">
                            <table id="searchTable" class="table table-bordered table-lg" cellspacing="0"
                            width="100%" style="text-align: center">
                                <thead>
                                    <th>Barangay</th>
                                    <th>NEW CASES</th>
                                    <th>ACTIVE CASES</th>
                                    <th>CONFIRMED CASES</th>
                                    <th>RECOVERED</th>
                                    <th>DECEASED</th>
                                    <th>SUSPECT</th>
                                    <th>PROBABLE</th>
                                    <th>BJMP CASES</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
    <!-- End Modal -->

    @can('permission', 'createCovidCasesUpdates')
    <!-- Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="covidReportModal">
        <div class="modal-dialog modal-lg" style="width: 80%" role="document">
            <form id="covidReportForm">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Covid Report</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="overflow-x:auto;">
                                <table id="reportTable" style="text-align: center">
                                    <thead>
                                        <th></th>
                                        <th>NEW CASES</th>
                                        <th>RECOVERED</th>
                                        <th>DECEASED</th>
                                        <th>SUSPECT</th>
                                        <th>PROBABLE</th>
                                        <th>BJMP CASES</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>    

                <div class="modal-footer">
                    <button class="btn btn-success" id="save">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->
    @endcan
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#searchTable').DataTable();

        @can('permission', 'createCovidCasesUpdates')
        //get barangays
        $.ajax({
            url:'{{ route('barangay.findall2') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 1; index < response.length; index++){
                    $('#reportTable tbody').append('<tr>'+
                        '<td><label>' + response[index].barangay + '</label></td>' +
                        '<td><input value="0" min="0" step="1" type="number" class="form-control border-input numericOnly" name="newCases'+ response[index].id +'"></td>'+
                        '<td><input value="0" min="0" step="1" type="number" class="form-control border-input numericOnly" name="recovered'+ response[index].id +'"></td>'+
                        '<td><input value="0" min="0" step="1" type="number" class="form-control border-input numericOnly" name="deceased'+ response[index].id +'"></td>'+
                        '<td><input value="0" min="0" step="1" type="number" class="form-control border-input numericOnly" name="suspect'+ response[index].id +'"></td>'+
                        '<td><input value="0" min="0" step="1" type="number" class="form-control border-input numericOnly" name="probable'+ response[index].id +'"></td>'+
                        '<td><input value="0" min="0" step="1" type="number" class="form-control border-input numericOnly" name="bjmp'+ response[index].id +'"></td>'+
                    '</tr>');
                        
                }
            }
        })
        @endcan

        //Datatables for Viewing
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('covidtracer.cases-updates.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                realtime: true
            },
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            "bLengthChange": false,
            "columns": [
                { "data": "barangay"},
                { "data": "confirmed"},
                { "data": "active"},
                { "data": "recovered"},
                { "data": "deceased"},
                { "data": "suspect"},
                { "data": "probable"},
                { "data": "bjmp"},
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 2 ] }, 
            ],
            
        });

        @can('permission', 'createCovidCasesUpdates')
        $("#add").click(function(){
                swal({
                    title: 'Please enter password!',
                    input: 'password',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                    preConfirm: function (password) {
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url:'{{ route('account.verify-password')}}',
                            type:'POST',
                            data:{ _token:"{{ csrf_token() }}",password:password},
                            dataType:'json',
                            success:function(success){
                                if(success.success){
                                    $('#covidReportModal').modal('show');
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }else{
                                    swal({
                                        title: "Password do not match!",
                                        text: "Please input correct password",
                                        type: "error"
                                    })
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                            }
                        }); 
                            
                    },
                    allowOutsideClick: false
                })
            })

            //Submit Report Form
            $("#covidReportForm").validate({
                barangay: {
                        required: true
                },
                newCases: {
                        required: true
                },
                recovered: {
                        required: true
                },
                deceased: {
                        required: true
                },
                suspected: {
                        required: true
                },
                bjmp: {
                        required: true
                },
                probable: {
                        required: true
                },
                submitHandler: function (form) {
                    Swal.fire({
                        title: 'Save Data?',
                        text: 'Are you sure you want to add data?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, add it!'
                    }).then((result) => {
                        if (result.value) {
                           //Add Cases Report
                           //process loader true
                            processObject.showProcessLoader();
                            $.ajax({
                                url: '{{ route('cases-updates.store') }}',
                                type: "POST",
                                data: $('#covidReportForm').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        $('#covidReportModal').modal('hide');
                                        $("#covidReportForm")[0].reset();
                                        $('.selectpicker').selectpicker('refresh');
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
                    }); 


                    
                }
            });
            @endcan

        $("#search").click(function(){
            if($("#date_from").val() != ""){

                //Datatables for Search
                $('#searchTable').DataTable().clear().destroy();
                searchTable = $('#searchTable').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "searching":false,
                    "ajax":{
                        "url": "/covidtracer/cases-updates/" + $('#date_from').val(),
                        "type": "GET",
                        "data":{"date_to" : $("#date_to").val()}
                    },
                    "colReorder": {
                        realtime: true
                    },
                    "columns": [
                        { "data": "barangay"},
                        { "data": "newCases"},
                        { "data": "activeCases"},
                        { "data": "confirmedCases"},
                        { "data": "recovered"},
                        { "data": "deceased"},
                        { "data": "suspected"},
                        { "data": "probable"},
                        { "data": "bjmp"},
                    ],
                });
                $('#covidSearchModal').modal('show');
            }else{
                swal({
                    title: "Date Required!",
                    text: "Please enter a date",
                    type: "error"
                })
            }
        });
        
        $("#date_from").on('dp.change', function(){
            if($("#date_from").val() == ""){
                $("#date_to").prop("disabled", true);
                $("#date_to").val("");
            }
            else
                $("#date_to").prop("disabled", false);
        })
    });  

    

    $(".numericOnly").keypress(function (e) {
            console.log(e.keyCode);
            // if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
        });
</script>
@endsection
