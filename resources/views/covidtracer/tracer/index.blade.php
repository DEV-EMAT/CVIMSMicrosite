@extends('layouts.app2')

@section('location')
{{$title}}
@endsection
@section('style')
    <style>
        td.details-control {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('../assets/image/minus.png') no-repeat center center;
        }
        
        td.details-control2 {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control2 {
            background: url('../assets/image/minus.png') no-repeat center center;
        }
        
        td.details-control3 {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control3 {
            background: url('../assets/image/minus.png') no-repeat center center;
        }
        
        td.details-control4 {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control4 {
            background: url('../assets/image/minus.png') no-repeat center center;
        }
        
        td.details-control5 {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control5 {
            background: url('../assets/image/minus.png') no-repeat center center;
        }



        #img_container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center
        }

        #img_container img{
            padding:10px
        }

        .ck-editor__editable_inline {
            min-height: 20em;
        }

        .file-default-preview img{
            height:200px;
            width:auto;
        }
    </style>
@endsection
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
                                    <label for="">Fullname:</label>
                                    <input type="hidden" name="person_code" id="person_code">
                                    <div style="display: flex">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Search fullname.." disabled>
                                        <a class="btn btn-primary btn-fill" data-toggle="modal" data-target="#search_user"><i class="fa fa-search"></i> SEARCH</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Establishment</label>
                                    <select class="selectpicker form-control" name="establishment" id="establishment123">
                                        <option value ="" disabled selected>Select....</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date (From) *</label>
                                        <input type='text' class="form-control datetimepicker" id='date_from' name="date_from"  max="9999-12-31"
                                        placeholder="Date From"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Time (From)</label>
                                        <input type="time" class="form-control" name="time_from" id="time_from">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date (To) *</label>
                                        <input type='text' class="form-control datetimepicker" id='date_to' name="date_to"  max="9999-12-31"
                                        placeholder="Date To"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Time (To)</label>
                                        <input type="time" class="form-control" name="time_to" id="time_to" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="display:flex; justify-content:center">
                                <div>
                                    <input type="button" id="clear" class="btn btn-warning btn-fill" value="CLEAR" data-toggle="tooltip" title="Click here to clear search filters.">
                                    <input type="button" id="search" class="btn btn-primary btn-fill" value="SEARCH">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="card-title"><b>COVID19 Tracer </b>
                                    <button id="generate_reports" class="btn btn-primary pull-right" disabled>
                                        <div data-toggle="tooltip" title="Click here to generate reports.">
                                            <i class="ti-plus"></i> Generate Reports
                                        </div>
                                    </button>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th style="width: 20px;"></th>
                                    <th>Transaction 1 (who scanned)</th>
                                    <th>Transaction 2 (who was scanned)</th>
                                    <th>Date</th>
                                    <th>Time</th>
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

            </div> <!-- end col-md-12 -->
        </div>
    </div>
</div>

<!-- Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="reports_modal">
    <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Covid Case History of Exposure</h4>
                </div>  
                <div class="form-group positive_person">
                    <div class="col-md-6 positive_person">
                        <label for="">Name:</label>
                    </div>
                    <div class="col-md-6 positive_person">
                        <label for="">Date Positive:</label>
                    </div>
                    <div class="col-md-6 positive_person">
                        <label id="positive_name" class="text-danger" disabled></label>
                    </div>
                    <div class="col-md-6 positive_person">
                        <input type='text' class="form-control datetimepicker" id='positive_date' name="positive_date" max="9999-12-31">
                    </div>
                </div><br><br>
                <!-- End Modal Header -->
                <div class="modal-body">
                   
                    <table id="datatableForReports" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Transaction 1(who scanned)</th>
                                <th>Transaction 2(who was scanned)</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <!--Table head-->
                        <tbody>
                        </tbody>
                        <!--Table body-->
                    </table>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" id="save_report">Save History of Exposure</button>
                </div>
            </div>
    </div>
</div>
<!-- End Modal -->


<!-- start modal for add patient -->
<div class="modal fade" id="search_user" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title w-100 text-center" id="add_new_modal">Modal title</h4>
            </div>
            <div class="modal-body"
                style="max-height: calc(100vh - 200px); overflow-y: auto; background-color:#f7f7f7;">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><b>Profile list</b></h4>
                                </div>
                                <div class="card-content">
                                    <table id="profile_datatable" class="table table-bordered table-sm table-hover"
                                        cellspacing="0" width="100%">
                                        <!--Table head-->
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th style="width: 200px;">Actions</th>
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
            <div class="modal-footer text-center">
                {{-- <button type="button" class="btn btn-danger btn-fill" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-success btn-fill"><i class="fa fa-save"></i> Save</button> --}}
            </div>
        </div>

    </div>
</div>
<!-- End Modalfor add patient -->


<!-- Searching field -->
<input type="hidden" id="hidden_search">
{{-- <input type="hidden" id="positive_person_code"> --}}

@endsection

@section('js')
    {{-- <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script> --}}

    <script src="{{asset('assets/js/printing/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/printing/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/js/printing/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/printing/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/js/printing/buttons.print.min.js')}}"></script>
    <script>
        let search_id = [];
        let establishment = [];
        $(document).ready(function(){
            datatable =  $('#datatable').DataTable({
                "searching":false,
                "columnDefs": [
                    { "orderable": false, "targets": [ 1,2,3,4] }, 
                ],
            }); 

            datatable =  $('#datatable_p2p').DataTable({
                "searching":false,
                "columnDefs": [
                    { "orderable": false, "targets": [ 1,2,3,4] }, 
                ],
            }); 

            user_datatable = $('#profile_datatable').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": '{{ route('account.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{csrf_token()}}",
                        action: 'selectUser'
                    }
                },
                colReorder: {
                    realtime: true
                },
                "columns": [{
                        "data": "fullname"
                    },
                    {
                        "data": "actions"
                    },
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [1]
                }, ]
            });

            //get all establishment
            $.ajax({
                url:'{{ route('covidtracer.est_info.findallforcombobox') }}',
                type:'GET',
                dataType:'json',
                success:function(response){
                    for (let index = 0; index < response.length; index++)
                    {
                        establishment.push(response[index].business_name); 
                        $('[name="establishment"]').append('<option value='+response[index].establishment_identification_code+'>'+ response[index].business_name +'</option>');
                        $('.selectpicker').selectpicker('refresh');
                    }
                }
            });

            // oTable = $('#datatable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
            $('#search').on('click', function(){
                $("#hidden_search").val("");

                search_id = [];
                $("#generate_reports").prop("disabled", true);

                if($("#date_from").val() && $("#date_to").val()){
                    $("#hidden_search").val($("#name").val());
                    $('#datatable').DataTable().clear().destroy();

                    $('#datatable').DataTable({
                        "searching":false,
                        "serverSide": true,
                        "columnDefs": [
                            { "orderable": false, "targets": [ 0,1,2,3,4,5] }, 
                        ],
                        "ajax":{
                            "url": '{{ route('covidtracer.tracer.findall') }}',
                            "dataType": "json",
                            "type": "POST",
                            "data":{ 
                                _token: "{{csrf_token()}}",
                                name:$('#name').val(),
                                establishment:$('[name="establishment"]').val(),
                                date_from:$('#date_from').val(),
                                time_from:$('#time_from').val(),
                                date_to:$('#date_to').val(),
                                time_to:$('#time_to').val(),
                                person_code:$('#person_code').val()
                            }
                        },
                        "columns": [
                            {
                                "className": 'details-control table1',
                                "orderable": false,
                                "data": null,
                                "defaultContent": ''
                            },
                            { "data": "trans1" },
                            { "data": "trans2" },
                            { "data": "date"},
                            { "data": "time"},
                        ],
                        "bInfo" : false,
                        "aoColumnDefs": [
                            {
                                "aTargets": [5],
                                "mData": "id",
                                "mRender": function (data, type, full) {
                                    if(search_id.length==0){
                                        return '<input type="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                                    }else{
                                        var flag =false;
                                        for (let index = 0; index < search_id.length; index++) {
                                            if(search_id[index]==data){
                                                flag = true;
                                                break;
                                            }
                                        }
                                        if(flag){
                                            return '<input type="checkbox" checked onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                                        }else{
                                            return '<input type="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                                        }
                                    }
                                    
                                }
                            }
                        ],
                    });
                }else{
                    swal({
                        title: "Field Required!",
                        text: "Please Provide Required Fields",
                        type: "error"
                    })
                }
            })

            
            /* Person to establishment */
            $('#datatable tbody').on('click', 'td.details-control', function (event) {
                    
                // event.stopImmediatePropagation();
                var tr  = $(this).closest('tr'),
                row = $('#datatable').DataTable().row(tr);
            
                    
                if (row.child.isShown()) {
                    destroyChild(row);
                    tr.removeClass('shown');
                }
                else {
                    displayChildOfTable1(row,row.data());
                    tr.addClass('shown');
                }
            });

            //generate breakdown
            $("#generate_reports").click(function(){
                //process loader true
                // processObject.showProcessLoader();
                $("#reports_modal").modal("show");

                $("#positive_name").html($("#name").val());
                $('#datatableForReports').DataTable().clear().destroy();
                        
                //process loader true
                // processObject.showProcessLoader();
                $('#datatableForReports').DataTable({
                    "serverSide": true,
                    "ajax":{
                        "url": '{{ route('covidtracer.tracer.generate_breakdown') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ 
                            _token: "{{csrf_token()}}",
                            search_id: search_id,
                            search: $('#person_code').val(),
                            date_from:$('#date_from').val(),
                            time_from:$('#time_from').val(),
                            date_to:$('#date_to').val(),
                            time_to:$('#time_to').val()
                            
                        }
                    },
                    "processing": true,
                    "language": {
                        processing: `<div id="fakeloader-overlay-save" class="visible incoming">
                                <div class="loader-wrapper-outer-save">
                                <div class="loader-wrapper-inner-save">
                                    <img height="120px" src="{{asset('assets/image/loader.gif')}}">
                                </div>
                                </div>
                            </div>`
                    },
                    "columns": [
                        { "data": "trans_1" },
                        { "data": "trans_2" },
                        { "data": "date" },
                        { "data": "time" },
                    ],
                    "processing": true,
                    "language": {
                        processing: `<div id="fakeloader-overlay-save" class="visible incoming">
                                <div class="loader-wrapper-outer-save">
                                <div class="loader-wrapper-inner-save">
                                    <img height="120px" src="{{asset('assets/image/loader.gif')}}">
                                </div>
                                </div>
                            </div>`
                    },
                    "searching": false,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                });
            });

            //save report
            $("#save_report").click(function(){
                let datePositive = "";
                if(!$(".positive_person").is(":hidden")){
                    datePositive = $("#positive_date").val();
                    if( datePositive == ""){
                        swal({
                            title: "Error!",
                            text: "Please input date for positive!",
                            type: "error"
                        })
                    }
                    else{
                        Swal.fire({
                            title: 'Add Now?',
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
                                    url: "{{ route('tracer.store')}}",
                                    type: "POST",
                                    "data":{ 
                                        _token: "{{csrf_token()}}",
                                        search_id: search_id,
                                        date_from:$('#date_from').val(),
                                        time_from:$('#time_from').val(),
                                        date_to:$('#date_to').val(),
                                        time_to:$('#time_to').val(),
                                        date_positive: datePositive,
                                        positive_person_code : $("#person_code").val()
                                    },
                                    dataType: "json",
                                    success: function (response) {

                                        if(response.success){
                                            swal({
                                                title: "Success!",
                                                text: response.messages,
                                                type: "success"
                                            })
                                            $("#positive_date").val("");
                                            $("#reports_modal").modal("hide");
                                            //process loader false
                                            var $a = $("<a>");
                                            $a.attr("href",response.file);
                                            $("body").append($a);
                                            $a.attr("download","file.xls");
                                            $a[0].click();
                                            $a.remove();

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
                }                
            });
            
            $('#clear').click(function(){
                $('#name').val('');
                $(".selectpicker").val('').selectpicker("refresh");
                $('#date_from').val('');
                $('#time_from').val('');
                $('#date_to').val('');
                $('#time_to').val('');
                $('#person_code').val('');
                $('#datatable').DataTable().clear().destroy();
                $('#datatable').DataTable()
            });
        });

        const addProfile = (id) => {
            //process loader true
            processObject.showProcessLoader();
            $.ajax({
                url: '/covidtracer/encoding/find-user-by-id/' + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#search_user').modal('hide');
                    let fullname = "";
                    if(data[0].first_name != null) fullname += ' ' + data[0].first_name;
                    if(data[0].affiliation != null) fullname += ' ' + data[0].affiliation;
                    if(data[0].last_name != null) fullname += ', ' + data[0].last_name;
                    if(data[0].middle_name != null) fullname += ' ' + data[0].middle_name;
                    $('#name').val(fullname);
                    $('#person_code').val(data[0].person_code);
                   
                    swal({
                        title: "Success!",
                        text: data.messages,
                        type: "success"
                    })
                    //process loader false
                    processObject.hideProcessLoader();
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

        const ctrToggle = (value) => {
            let flag =false;
            for (let index = 0; index < search_id.length; index++) {
                if(search_id[index]==value){
                    flag = true;
                    search_id.splice(index,1);
                    break;
                }
            }
            if(!flag){
                search_id.push(value);
            }

            if(search_id.length > 0){
                $("#generate_reports").prop("disabled", false);
            }
            else{
                $("#generate_reports").prop("disabled", true);
            }
        }

        /* ================================================================ */
        const displayChildOfTable1 = ( row, data) => {

            let active = '';
            let trans_stat = '';
            let flag = true;

            if(data.trans1_code[0] == 'E'){
                active = data.trans1_code;
                trans_stat = 'p2e';
                flag = false;
            }

            if(data.trans2_code[0] == 'E'){
                active = data.trans2_code;
                trans_stat = 'p2e';
                flag = false;
            }

            // person to person
            if(flag){
                if(data.trans1_code != $('#person_code').val()){
                    active = data.trans1_code;
                }
                if(data.trans2_code != $('#person_code').val()){
                    active = data.trans2_code; 
                }
            }  

            let table = `<div class="row col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="card-title"><b></b></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <table id="datatable_2" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th style="width: 20px;"></th>
                                            <th>Transaction 1 (who scanned)</th>
                                            <th>Transaction 2 (who was scanned)</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <!--Table head-->
                                    <tbody>
                                    </tbody>
                                    <!--Table body-->
                                </table>
                            </div>
                        </div>
                    </div>`;
 
            row.child(table).show();

            $('#datatable_2').DataTable({
                "searching":false,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": [ 0,1,2,3,4] }, 
                ],
                "ajax":{
                    "url": '{{ route('covidtracer.tracer.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ 
                        _token: "{{csrf_token()}}",
                        establishment:active,
                        date_from:$('#date_from').val(),
                        time_from:$('#time_from').val(),
                        date_to:$('#date_to').val(),
                        time_to:$('#time_to').val(),
                        search_id:data.id
                    }
                },
                "columns": [
                    {
                        "className": 'details-control2',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    { "data": "trans1" },
                    { "data": "trans2" },
                    { "data": "date"},
                    { "data": "time"},
                ],
            });
            
            $('#datatable_2 tbody').on('click', 'td.details-control2', function (event) {
                event.stopImmediatePropagation();
                var tr  = $(this).closest('tr'),
                row = $('#datatable_2').DataTable().row(tr);
        
                if (row.child.isShown()) {
                    destroyChild2(row);
                    tr.removeClass('shown');
            
                }else {
                    displayChildOfTable2(row,row.data(), active);
                    tr.addClass('shown');
                }
            });
        }

        const displayChildOfTable2 = ( row, data, root) => {
            let table = `<div class="row col-md-12">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-content">
                                <table id="datatable_3" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th style="width: 20px;"></th>
                                            <th>Transaction 1 (who scanned)</th>
                                            <th>Transaction 2 (who was scanned)</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <!--Table head-->
                                    <tbody>
                                    </tbody>
                                    <!--Table body-->
                                </table>
                            </div>
                        </div>
                    </div>`;


            row.child(table).show();

            let active = '';
            
            if(root[0] != 'E'){
                let flag = true;
                if(data.trans1_code[0] == 'E'){
                    active = data.trans1_code;
                    flag = false;
                }

                if(data.trans2_code[0] == 'E'){
                    active = data.trans2_code;
                    flag = false;
                }

                if(flag){
                    if(data.trans1_code != root){
                        active = data.trans1_code;
                    }
                    
                    if(data.trans2_code != root){
                        active = data.trans2_code;
                    }
                }
            }else{
                if(data.trans1_code[0] == 'P'){
                    active = data.trans1_code;
                }

                if(data.trans2_code[0] == 'P'){
                    active = data.trans2_code;
                }
            }

            $('#datatable_3').DataTable({
                "searching":false,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": [ 0,1,2,3,4] }, 
                ],
                "ajax":{
                    "url": '{{ route('covidtracer.tracer.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ 
                        _token: "{{csrf_token()}}",
                        // establishment:active,
                        date_from:$('#date_from').val(),
                        time_from:$('#time_from').val(),
                        date_to:$('#date_to').val(),
                        time_to:$('#time_to').val(),
                        person_code:active,
                        search_id:data.id
                    }
                },
                "columns": [
                    {
                        "className": 'details-control3',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    { "data": "trans1" },
                    { "data": "trans2" },
                    { "data": "date"},
                    { "data": "time"},
                ],
            });

            $('#datatable_3 tbody').on('click', 'td.details-control3', function (event) {
                event.stopImmediatePropagation();
                var tr  = $(this).closest('tr'),
                row = $('#datatable_3').DataTable().row(tr);

                if (row.child.isShown()) {
                    destroyChild2(row);
                    tr.removeClass('shown');

                }else {
                    displayChildOfTable3(row,row.data(), active);
                    tr.addClass('shown');
                }
            });
        }

        const displayChildOfTable3 = ( row, data ,root) => {

            let table = `<div class="row col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="card-title"><b></b></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <table id="datatable_4" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th style="width: 20px;"></th>
                                            <th>Transaction 1 (who scanned)</th>
                                            <th>Transaction 2 (who was scanned)</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <!--Table head-->
                                    <tbody>
                                    </tbody>
                                    <!--Table body-->
                                </table>
                            </div>
                        </div>
                    </div>`;

            
            row.child(table).show();

            let active = '';
            if(root[0] != 'E'){
                let flag = true;
                if(data.trans1_code[0] == 'E'){
                    active = data.trans1_code;
                    flag = false;
                }

                if(data.trans2_code[0] == 'E'){
                    active = data.trans2_code;
                    flag = false;
                }

                if(flag){
                    if(data.trans1_code != root){
                        active = data.trans1_code;
                    }
                    
                    if(data.trans2_code != root){
                        active = data.trans2_code;
                    }
                }


            }else{
                console.log('est')
                if(data.trans1_code[0] == 'P'){
                    active = data.trans1_code;
                }

                if(data.trans2_code[0] == 'P'){
                    active = data.trans2_code;
                }
            }

            $('#datatable_4').DataTable({
                "searching":false,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": [ 0,1,2,3,4] }, 
                ],
                "ajax":{
                    "url": '{{ route('covidtracer.tracer.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ 
                        _token: "{{csrf_token()}}",
                        establishment:active,
                        date_from:$('#date_from').val(),
                        time_from:$('#time_from').val(),
                        date_to:$('#date_to').val(),
                        time_to:$('#time_to').val(),
                        // person_code:active,
                        search_id:data.id
                    }
                },
                "columns": [
                    {
                        "className": 'details-control4',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    { "data": "trans1" },
                    { "data": "trans2" },
                    { "data": "date"},
                    { "data": "time"},
                ],
            });

            $('#datatable_4 tbody').on('click', 'td.details-control4', function (event) {
                event.stopImmediatePropagation();
                var tr  = $(this).closest('tr'),
                row = $('#datatable_4').DataTable().row(tr);

                if (row.child.isShown()) {
                    destroyChild2(row);
                    tr.removeClass('shown');

                }else {
                    displayChildOfTable4(row,row.data(), active);
                    tr.addClass('shown');
                        
                }
            });
        }

        const displayChildOfTable4 = ( row, data, root ) => {

            let table = `<div class="row col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="card-title"><b></b></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <table id="datatable_5" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th style="width: 20px;"></th>
                                            <th>Transaction 1 (who scanned)</th>
                                            <th>Transaction 2 (who was scanned)</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <!--Table head-->
                                    <tbody>
                                    </tbody>
                                    <!--Table body-->
                                </table>
                            </div>
                        </div>
                    </div>`;


            row.child(table).show();

            let active = '';
            if(root[0] != 'E'){
                let flag = true;
                if(data.trans1_code[0] == 'E'){
                    active = data.trans1_code;
                    flag = false;
                }

                if(data.trans2_code[0] == 'E'){
                    active = data.trans2_code;
                    flag = false;
                }

                if(flag){
                    if(data.trans1_code != root){
                        active = data.trans1_code;
                    }
                    
                    if(data.trans2_code != root){
                        active = data.trans2_code;
                    }
                }


            }else{
                console.log('est')
                if(data.trans1_code[0] == 'P'){
                    active = data.trans1_code;
                }

                if(data.trans2_code[0] == 'P'){
                    active = data.trans2_code;
                }
            }

            $('#datatable_5').DataTable({
                "searching":false,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": [ 0,1,2,3,4] }, 
                ],
                "ajax":{
                    "url": '{{ route('covidtracer.tracer.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ 
                        _token: "{{csrf_token()}}",
                        // establishment:active,
                        date_from:$('#date_from').val(),
                        time_from:$('#time_from').val(),
                        date_to:$('#date_to').val(),
                        time_to:$('#time_to').val(),
                        person_code:active,
                        search_id:data.id
                    }
                },
                "columns": [
                    {
                        "className": 'details-control5',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    { "data": "trans1" },
                    { "data": "trans2" },
                    { "data": "date"},
                    { "data": "time"},
                ],
            });

            $('#datatable_5 tbody').on('click', 'td.details-control5', function (event) {
                event.stopImmediatePropagation();
                var tr  = $(this).closest('tr'),
                row = $('#datatable_5').DataTable().row(tr);

                if (row.child.isShown()) {
                    destroyChild2(row);
                    tr.removeClass('shown');

                }else {
                    displayChildOfTable5(row,row.data(), active);
                    tr.addClass('shown');
                }
            });
        }

        const displayChildOfTable5 = ( row, data, root ) => {

            let table = `<div class="row col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="card-title"><b></b></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <table id="datatable_6" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th>Transaction 1 (who scanned)</th>
                                            <th>Transaction 2 (who was scanned)</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <!--Table head-->
                                    <tbody>
                                    </tbody>
                                    <!--Table body-->
                                </table>
                            </div>
                        </div>
                    </div>`;


            row.child(table).show();

           
            let active = '';
            if(root[0] != 'E'){
                let flag = true;
                if(data.trans1_code[0] == 'E'){
                    active = data.trans1_code;
                    flag = false;
                }

                if(data.trans2_code[0] == 'E'){
                    active = data.trans2_code;
                    flag = false;
                }

                if(flag){
                    if(data.trans1_code != root){
                        active = data.trans1_code;
                    }
                    
                    if(data.trans2_code != root){
                        active = data.trans2_code;
                    }
                }


            }else{
                console.log('est')
                if(data.trans1_code[0] == 'P'){
                    active = data.trans1_code;
                }

                if(data.trans2_code[0] == 'P'){
                    active = data.trans2_code;
                }
            }

            $('#datatable_6').DataTable({
                "searching":false,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": [ 0,1,2,3] }, 
                ],
                "ajax":{
                    "url": '{{ route('covidtracer.tracer.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ 
                        _token: "{{csrf_token()}}",
                        establishment:active,
                        date_from:$('#date_from').val(),
                        time_from:$('#time_from').val(),
                        date_to:$('#date_to').val(),
                        time_to:$('#time_to').val(),
                        // person_code:active,
                        search_id:data.id
                    }
                },
                "columns": [
                    { "data": "trans1" },
                    { "data": "trans2" },
                    { "data": "date"},
                    { "data": "time"},
                ],
            });

            $('#datatable_6 tbody').on('click', 'td.details-control6', function (event) {
                event.stopImmediatePropagation();
                var tr  = $(this).closest('tr'),
                row = $('#datatable_6').DataTable().row(tr);

                if (row.child.isShown()) {
                    destroyChild2(row);
                    tr.removeClass('shown');
                }
            });
        }

        function destroyChild(row) {
            var table = $("Table2", row.child());
            table.detach();
            table.DataTable().destroy();
        
            // And then hide the row
            row.child.hide();
        }

        function destroyChild2(row) {
            var table = $("Table3", row.child());
            table.detach();
            table.DataTable().destroy();
        
            // And then hide the row
            row.child.hide();
        }
        /* ================================================================ */
        
    </script>
@endsection