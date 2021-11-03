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
                                <h4 class="card-title"><b>Question List</b></h4>
                                <p class="category">Create | Update | View | Delete Question</p>
                            </div>
                            <div class="col-lg-2">
                                @can('permission', 'createQuestion')
                                <a href="{{ route('exam-question.create') }}" class="btn btn-primary pull-right">
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
                                    <th style="width: 20px;"></th>
                                    <th style="width: 50px;">ID</th>
                                    <th>Question</th>
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

@can('permission', 'updateQuestion')
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title">Profile</h4>
            </div>
            <form id="update_form">
                @csrf
                @method("PUT")
                <input type="hidden" id="editid">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Subject *</label>
                        <select class="form-control selectpicker" name="subject">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Question</label>
                        <textarea class="form-control" name="question" id="question" rows="3"></textarea>
                        <input type="hidden" name="examtype" id="examtype" value="1">
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade in" id="multiplechoicetab" role="tabpanel" aria-labelledby="multiplechoice">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div id="multiplechoice_form">
                                                <table class="table">
                                                    <thead>
                                                        <th style="width: 50px;">Options</th>
                                                        <th>Choices</th>
                                                        <th>Answers</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>A</td>
                                                            <td><input type="text" style="text-transform:uppercase" id="txtoptiona" class="form-control" name="choices[]" aria-describedby="helpId" placeholder=""></td>
                                                            <td>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="answer" value="0" autocomplete="off ">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>B</td>
                                                            <td><input type="text" style="text-transform:uppercase" id="txtoptionb" class="form-control" name="choices[]" aria-describedby="helpId" placeholder=""></td>
                                                            <td>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="answer" value="1" autocomplete="off ">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>C</td>
                                                            <td><input type="text" style="text-transform:uppercase" id="txtoptionc" class="form-control" name="choices[]" aria-describedby="helpId" placeholder=""></td>
                                                            <td>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="answer" value="2" autocomplete="off ">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>D</td>
                                                            <td><input type="text" style="text-transform:uppercase" id="txtoptiond" class="form-control" name="choices[]" aria-describedby="helpId" placeholder=""></td>
                                                            <td>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="answer" value="3" autocomplete="off ">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="trueorfalsetab" role="tabpanel" aria-labelledby="trueorfalse">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div id="trueorfalse_form">
                                                <table class="table ">
                                                    <thead>
                                                        <th style="width:50px">True</th>
                                                        <th>False</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="answer" value="TRUE" id="" autocomplete="off ">
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label class="btn btn-primary">
                                                                        <input type="radio" name="answer" value="FALSE" id="" autocomplete="off ">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@endsection
@section('js')
    <script>
        
        $(document).ready(function(){
            datatable = $('#datatable').DataTable({
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('exam-question.find-all') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "ctr" },
                    { "data": "question" },
                    { "data": "status" },
                    { "data": "action" },
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1, 3, 4] }
                ]
            });

            $.ajax({
                url:'{{ route('exam-subject.find-subject') }}',
                type:'GET',
                dataType:'json',
                success:function(response){
                    for (let index = 0; index < response.length; index++)
                    {
                        $('[name="subject"]').append('<option value='+response[index].id+'>'+ response[index].subject+'</option>');
                        $('.selectpicker').selectpicker('refresh');
                    }
                }
            })

            $('#datatable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = datatable.row( tr );
        
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                    timer = setInterval(function() {datatable.ajax.reload( null, false );}, 8000);
                }
                else {
                    // Open this row
                    // console.log(row.data());
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                    clearInterval(timer);
                }
            });
            
            @if(!Gate::check('permission', 'updateQuestion') && !Gate::check('permission', 'deleteQuestion'))
                datatable.column(4).visible(false);
            @endif
        });

        const format = ( d ) => {
            // `d` is the original data object for the row
            var row = '';
            var option = ['T', 'F'];
            if(d.type !='TRUE OR FALSE'){
                option = ['A','B', 'C', 'D'];
            }
            for(var index = 0; index < d.choices.length; index++){
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

        @can('permission','updateQuestion')
        const edit = (id) =>{
            $.ajax({
                url:'/comprehensive/exam-question/'+id,
                type:'GET',
                dataType:'json',
                success:function(success){
                    $('#editid').val(success.id);
                    $('#question').val(success.question);
                    $('select[name=subject]').val(success.exam_subject_id);
                    $('.selectpicker').selectpicker('refresh');
                    
                    console.log(success);
                    if(success.exam_type_id == '1'){
                        $('#examtype').val('1');
                        $('#trueorfalsetab').removeClass('active');
                        $('#multiplechoicetab').addClass('active');

                        var option = ['a','b','c','d'];
                        for (var index = 0; index < success.choices.length; index++) {
                            if(success.choices[index]==success.answer){
                                $('input:radio[name=answer]')[index].checked = true;
                            }
                            $('#txtoption'+option[index]).val(success.choices[index]);
                        }
                    }else{
                        
                        $('#examtype').val('2');
                        $('#multiplechoicetab').removeClass('active');
                        $('#trueorfalsetab').addClass('active');

                        for (var index = 0; index < success.choices.length; index++) {
                            if(success.choices[index]==success.answer){
                                $('input:radio[name=answer]').filter('[value='+success.answer+']').prop('checked', true);
                            }
                        }
                    }

                    $('#edit_modal').modal('show');
                }
            })
        }
        @endcan

        $('input[type=radio][name=answer]').on('change', function() {
            if($(this).val() == '0')
            {
                if($("#txtoptiona").val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).prop('checked', false);
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Your are selecting empty field!'
                    });
                }
                
            }
            else if($(this).val() == '1')
            {
                if($("#txtoptionb").val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).prop('checked', false);
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Your are selecting empty field!'
                    });
                }
            }
            else if($(this).val() == '2')
            {
                if($("#txtoptionc").val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).prop('checked', false);
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Your are selecting empty field!'
                    });
                }
            }
            else if($(this).val() == '3')
            {
                if($("#txtoptiond").val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).prop('checked', false);
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Your are selecting empty field!'
                    });
                }
            }
        });

        @can('permission','updateQuestion')
        $("#update_form").validate({
            rules: {
                subject:{
                    required:true
                },
                question:{
                    required:true
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Update questions?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        
                        var id = $('#editid').val();

                        var formData = new FormData($("#update_form").get(0));

                        $.ajax({
                            url: '/comprehensive/exam-question/'+id,
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
        @endcan

        @can('permission','deleteQuestion')
        const toggleStatus = (id) =>{
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, update it!'
            }).then((result) => {
              if (result.value) {
                // ajax delete data to database
                  $.ajax({
                    url : '/comprehensive/exam-question/toggle/'+id,
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
                        swal('Error updating data');
                    }
                });
                
              }
            });
        }
        @endcan
    </script>
@endsection
