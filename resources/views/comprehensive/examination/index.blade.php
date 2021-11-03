@extends('layouts.app2',['empindex' => true])
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
                                <h4 class="card-title"><b>Questionnaire List</b></h4>
                                <p class="category">Create | Update | View | Delete Examination</p>
                            </div>
                            <div class="col-lg-2">
                                @can('permission', 'createExamination')
                                <a href="{{ route('examination.create') }}" class="btn btn-primary pull-right">
                                    <i class="ti-plus"></i> Add new
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                    <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th>Examination</th>
                                    <th>Time</th>
                                    <th>Number</th>
                                    <th>Passing</th>
                                    <th>Status</th>
                                    <th style="width: 250px;">Action</th>
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

@can('permission', 'updateExamination')
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title">Question List</h4>
            </div>
            <form id="update_form">
                @csrf
                @method("PUT")
                <input type="hidden" id="editid">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Department</label>
                        <select class="selectpicker form-control" data-live-search="true" name="department" id="department">
                            <option value="" disabled selected>Select.....</option>
                        </select>
                    </div> 
                    <div class="form-group">
                        <label>Exam Title</label>
                        <input type="text" name="txtExamTitle" id="txtExamTitle" class="form-control">
                    </div> 
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="description"></textarea>                                
                    </div>
                    <div class="form-group">
                        <label>Time Duration <b>(Hours : Minutes)</b></label>
                        <input type='text' class="datetimepicker5 form-control" id='datetimepicker5' name="txtTime" id="txtTime" placeholder="Time  To?"/>
                    </div>
                    <div class="form-group">
                        <label>No. of Item/s</label>
                        <input type="number" value="0" name="txtItems" id="txtItems" class="form-control" disabled>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Passing Percentage (e.g. 80%)</label>
                                <input type="number" min="1" max="100"  name="txtPassing" id="txtPassing" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <h5><span style="color:red;" id="txtPercentValue"></span></h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <input type="submit" name="submit" class="btn btn-info btn-fill btn-wd" />
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endcan

<div class="modal fade" id="show_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title">Question List</h4>
            </div>
            <div class="modal-body">
                <table id="datatable2" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                    <!--Table head-->
                    <thead>
                        <tr>
                            <th style="width: 20px;"></th>
                            <th>Question</th>
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
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        
        let datatable2;
        $(document).ready(function(){

            datatable = $('#datatable').DataTable({
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('examination.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "title" },
                    { "data": "time" },
                    { "data": "number" },
                    { "data": "passing" },
                    { "data": "status" },
                    { "data": "action" },
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [0, 2, 3, 4, 5, 6] }
                ]
            });
            
            //get department
            $.ajax({
                url:'{{ route('department.findall3') }}',
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

            //for datatable 2
            $('#datatable2 tbody').on('click', 'td.details-control', function () {
                let tr = $(this).closest('tr');
                let row = datatable2.row( tr );
        
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                    // timer2 = setInterval(function() {datatable2.ajax.reload( null, false );}, 8000);
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                    // clearInterval(timer2);
                }
            });
        });

        $('.datetimepicker5').datetimepicker({
            format: 'HH:mm:ss',    //use this format if you want the 12hours timpiecker with AM/PM toggle
            icons: {
                time: "fa fa-clock-o",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        });

        $("#txtPassing" ).keyup(function() {
            
            if(parseFloat($("#txtPassing").val()) > 100){
                $("#txtPassing").val(100);
            }

            if($("#txtPassing").val() == ''){
                compute = '?';
            }else{
                compute = Math.ceil((parseFloat($("#txtPassing").val()) * parseFloat($("#txtItems" ).val())) / 100);
            }
            $("#txtPercentValue").text( $("#txtPassing").val()+" % of "+ $("#txtItems" ).val()+ " is:  " + compute);
        
        });

        const show = (id) =>{
            $('#datatable2').DataTable().clear().destroy();
            datatable2 = $('#datatable2').DataTable({
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('examination.findquestion') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", exam_id: id}
                },
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "question" },
                    { "data": "status" },
                    { "data": "action" },
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [0, 2] }
                ]
            });

            // @if(!Gate::check('permission', 'updateExamination'))
            //     datatable2.column(3).visible(false)
            // @endif

            $('#show_modal').modal('show');
        }

        const format = ( d ) => {
            // `d` is the original data object for the row
            let row = '';
            let option = ['T', 'F'];
            if(d.type !='TRUE OR FALSE'){
                option = ['A','B', 'C', 'D'];
            }
            for(let index = 0; index < d.choices.length; index++){
                if(d.choices[index] == d.answer){
                    row += '<tr style="background-color:yellow"><td>'+ option[index] +'. (answer)</td><td>'+ d.choices[index] +'</td></tr>';
                }else{
                    row += '<tr><td>'+ option[index] +'.</td><td>'+ d.choices[index] +'</td></tr>';
                }
            }

            return '<div class="col-md-12">'+
                        '<div class="col-md-4">'+
                            '<table class="table table-bordered table-hover">'+
                                '<thead>'+
                                    '<th>SUBJECT</th>'+
                                    '<th>TYPE</th>'+
                                '</thead>'+
                                '<tbody>'+
                                    '<tr>'+
                                        '<td><label class="label label-primary">'+d.subject+'</label></td>'+
                                        '<td><label class="label label-primary">'+d.type+'</label></td>'+
                                    '</tr>'+
                                '</tbody>'+
                            '</table>'+
                        '</div>'+
                        '<div class="col-md-8">'+
                            '<table class="table table-bordered table-sm table-hover">'+
                                '<thead>'+
                                    '<th style="width: 90px;"></th>'+
                                    '<th>CHOICES</th>'+
                                '</thead>'+
                                '<tbody>'+ row +'</tbody>'+
                            '</table>'+
                        '</div>';
        }

        @can('permission', 'updateExamination')
        const edit = (id)=>{
            $.ajax({
                url:'/comprehensive/examination/'+id,
                type:'GET',
                dataType:'json',
                success:function(success){
                    console.log(success.department);
                    $('#department').val(success.department);
                    $('#editid').val(success.examTitle.id);
                    $('#txtExamTitle').val(success.examTitle.title);
                    $('#description').val(success.examTitle.description);
                    $('#datetimepicker5').val(success.examTitle.time);
                    $('#txtItems').val(success.examTitle.item_number);
                    $('#txtPassing').val(success.examTitle.passing);

                    $('.selectpicker').selectpicker('refresh');
                    $('#edit_modal').modal('show');
                }
            })
        }

        $("#update_form").validate({
            rules: {
                txtExamTitle:{
                    required:true,
                    minlength:3
                },
                txtTime:{
                    required:true
                },
                txtPassing:{
                    required:true
                },
                txtItems:{
                    required:true,
                },
                description:{
                    minlength:3
                },
                department:{
                    required:true,
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Update examination?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        
                        let id = $('#editid').val();

                        let formData = new FormData($("#update_form").get(0));

                        $.ajax({
                            url: '/comprehensive/examination/'+id,
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
                                        $("#update_form")[0].reset();
                                        $('#edit_modal').modal('hide');
                                        datatable.ajax.reload( null, false );
                                    });
                                }else{
                                    swal(response.error, response.messages, "error");
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal("Error", errorThrown, "warning");
                            }
                        });
                    }
                })
            }
        });

        const togglequestion = (id)=>{
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes!'
            }).then((result) => {
              if (result.value) {
                // ajax delete data to database
                  $.ajax({
                    url : '/comprehensive/examination/togglequestion/'+id,
                    type: "POST",
                    data:{ _token: "{{csrf_token()}}"},
                    dataType: "JSON",
                    success: function(response)
                    { 
                        swal({
                            title: "Success!",
                            text: response.messages,
                            type: "success"
                        }).then(function() {
                            datatable2.ajax.reload( null, false );
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        swal('Error adding / update data');
                    }
                });
                
              }
            });
        }
        @endcan

        @can('permission', 'deleteExamination')
        const toggleStatus = (id)=> {
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Update it!'
            }).then((result) => {
              if (result.value) {
                // ajax delete data to database
                  $.ajax({
                    url : '/comprehensive/examination/toggle/'+id,
                    type: "POST",
                    data:{ _token: "{{csrf_token()}}"},
                    dataType: "JSON",
                    success: function(response)
                    { 
                        swal({
                            title: "Success!",
                            text: response.messages,
                            type: "success"
                        }).then(function() {
                            datatable.ajax.reload( null, false );
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        swal('Error adding / update data');
                    }
                });
                
              }
            });
        }
        @endcan

    </script>
@endsection
