@extends('layouts.app2')

@section('style')
<style>
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
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <h4 class="card-title"><b>Search Logs</b></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Date</label>
                                        <input type='date' class="form-control" id='date_from' name="date_from"  max="9999-12-31"
                                        placeholder="Date"/>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Department</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="department" id="department">
                                        <option value="" disabled selected>Select.....</option>
                                        <option value="all">ALL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-2"><br>
                                <input type="button" id="search" class="btn btn-primary pull-left" value="Search">
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col-md-12 -->
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <h4 class="card-title"><b>System Activity Logs</b></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                    <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th style="width: 20px;"></th>
                                    <th>Name</th>
                                    <th>Module</th>
                                    <th>ACTION</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>IP Address</th>
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
@endsection

@section('js')
    <script>

        $(document).ready(function(){
            //get department
            $.ajax({
                url:'{{ route('department.findall2') }}',
                type:'GET',
                dataType:'json',
                success:function(response){
                    for (let index = 0; index < response.length; index++)
                    {
                        $('[name="department"]').append('<option value='+response[index].id+'>'+ response[index].department +'</option>');
                        $('.selectpicker').selectpicker('refresh');
                    }
                }
            })

            datatable = $('#datatable').DataTable({
                "ajax":{
                    "url": '{{ route('logs.find-all') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    { "data": "user" },
                    { "data": "module" },
                    { "data": "action" },
                    { "data": "date" },
                    { "data": "time" },
                    { "data": "ip" },
                ]
            });

            $('#datatable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = datatable.row( tr );
        
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            });

            $("#search").click(function(){
                $('#datatable').DataTable().clear().destroy();
                datatable = $('#datatable').DataTable({
                    "ajax":{
                        "url": '{{ route('logs.find-all') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{
                            _token: "{{csrf_token()}}", 
                            "date_from" : $("#date_from").val(),
                            "department" : $("#department").val()
                        }
                    },
                    "columns": [
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": null,
                            "defaultContent": ''
                        },
                        { "data": "user" },
                        { "data": "module" },
                        { "data": "action" },
                        { "data": "date" },
                        { "data": "time" },
                        { "data": "ip" },
                    ]
                });
            });

            $("#date_from").change(function(){
                if($("#date_from").val() == ""){
                    $("#date_to").prop("disabled", true);
                    $("#date_to").val("");
                }
                else
                    $("#date_to").prop("disabled", false);
            })
        });


        const format = (data) => {

            const { updates } = data;
            let content = '';
            array_action = JSON.parse(updates);
            if(array_action){

                const keys = Object.keys(array_action);
                keys.forEach((value, index) => {
                    console.log(value, array_action[value]);
                    content += `<tr>
                                <td>${value.toUpperCase()}</td>
                                <td><label>${array_action[value]}</label></td>
                            </tr>`;
                })
            }else{
                    content += `<tr>
                                <td colspan="2"><label class="label label-primary">No data Changes!</label></td>
                            </tr>`;
            }

            return `<div class="col-md-12">
                        <div class="col-md-4">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <th>COLUMN</th>
                                    <th>CHANGES (update)</th>
                                </thead>
                                <tbody>
                                    ${ content }
                                </tbody>
                            </table>
                        </div>
                    </div>`;
        }
    </script>
@endsection
