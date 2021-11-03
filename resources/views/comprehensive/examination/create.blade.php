@extends('layouts.app2')
@section('location')
{{$title}}
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <h4 class="card-title"><b>Examination Form</b></h4>
                            <p class="category">Create New Examination</p>
                        </div>
                        <div class="col-lg-2">

                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <form id="create_form">
                        @csrf
                        @method("POST")
                        <div class="row">
                            <div class="col-md-4">
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
                                    <label>Time Duration <b>(Hours : Minutes : Seconds)</b></label>
                                    <input type='text' class="datetimepicker5 form-control" id='datetimepicker5' name="txtTime" id="txtTime" placeholder="Time  To?"/>
                                </div>
                                <div class="form-group">
                                    <label>No. of Item/s</label>
                                    <input type="number" value="0" name="txtItems" id="txtItems" class="form-control">
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
                                <div class="row">
                                    <div class="text-center">
                                        <input type="submit" name="submit" class="btn btn-info btn-fill btn-wd" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="50px"></th>
                                            <th>Question</th>
                                            <th>Type</th>
                                            <th style="width:100px;">Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                    <!--Table body-->
                                </table> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        window.questionID = [];

        $(document).ready(function () {
           
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

            datatable = $('#datatable').DataTable({
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('exam-question.find-all') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", "examination" : "createExam"}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "question" },
                    { "data": "type" },
                    { "data": "subject" },
                ],
                "aoColumnDefs": [
                {
                        "aTargets": [0],
                        "mData": "id",
                        "mRender": function (data, type, full) {
                            // alert(data);
                            if(questionID.length==0){
                                return '<input type="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                            }else{
                                var flag =false;
                                for (let index = 0; index < questionID.length; index++) {
                                    if(questionID[index]==data){
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
                "columnDefs": [
                    { "orderable": false, "targets": [0,1] }
                ]
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

        const ctrToggle = (value) =>{
            var flag =false;
            for (let index = 0; index < questionID.length; index++) {
                if(questionID[index]==value){
                    flag = true;
                    questionID.splice(index,1);
                    break;
                }
            }
            if(!flag){
                questionID.push(value);
            }
            $('#txtItems').val(questionID.length);
        }

        $("#create_form").validate({
            rules: {
                department:{
                    required:true,
                },
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
                }
            },
            submitHandler: function (form) {
                if(questionID.length > 0){
                    Swal.fire({
                        title: 'Save new examination?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        if (result.value) {
                            let formData = new FormData($("#create_form").get(0));
                            formData.append('question', questionID);

                            $.ajax({
                                url: "{{ route('examination.store')}}",
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
                                            $("#create_form")[0].reset();
                                            $("#department").val("");
                                            $('.selectpicker').selectpicker('refresh');
                                            questionID = [];
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
                else{
                    swal({
                        title: "Error!",
                        text: "Please select question!",
                        type: "error"
                    })
                }
            }
        });

    </script>
@endsection
