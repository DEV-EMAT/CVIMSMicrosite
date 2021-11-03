@extends('layouts.app2')
@section('location')
{{$title}}
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row"> 
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-lg-10">
                            <h4 class="card-title"><b>Subject List</b></h4>
                            <p class="category">Create | Update | View | Delete Subject</p>
                        </div>
                        <div class="col-lg-2">
                            @can('permission', 'createSubject')
                            <a data-toggle="modal" data-target="#create_modal" class="btn btn-primary pull-right">
                                <i class="ti-plus"></i> Add new
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-content">
                    <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th>Subject</th>
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

@can('permission', 'createSubject')
<div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">Examination Subject</h4>
            </div>
            <form id="create_form">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-control border-input" id="subject" name="subject">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <input type="submit" name="edit" class="btn btn-info btn-fill btn-wd"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@can('permission', 'updateSubject')
<div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">Examination Subject</h4>
            </div>
            <form id="update_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="editid" id="editid">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-control border-input" id="editsubject" name="editsubject">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="editdescription" id="editdescription"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <input type="submit" name="edit" class="btn btn-info btn-fill btn-wd"/>
                </div>
            </form>
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
                    "url": '{{ route('exam-subject.find-all') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"},
                },
                "columns": [
                    { "data": "id" },
                    { "data": "subject" },
                    { "data": "status" },
                    { "data": "action" }
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [2,3] }
                ]
            });
        });
        
        @can('permission', 'createSubject')
        $("#create_form").validate({
            rules: {
                subject: {
                    required: true,
                    minlength:3
                },
                description: {
                    minlength:3
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Save new subject?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        
                        var formData = new FormData($("#create_form").get(0));

                        $.ajax({
                            url: "{{ route('exam-subject.store') }}",
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
                                        $('#create_modal').modal('hide');
                                        datatable.ajax.reload( null, false );
                                    });
                                }else{
                                    swal("Error", response.messages, "error");
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

        
        @can('permission', 'updateSubject')
        $("#update_form").validate({
            rules: {
                editsubject: {
                    required: true,
                    minlength:3
                },
                editdescription: {
                    minlength:3
                }
            },
            submitHandler: function (form) {   

                var id = $('#editid').val();
                
                Swal.fire({
                    title: 'Update subject?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {      
                    if (result.value) {
                        $.ajax({
                            url: '/comprehensive/exam-subject/'+id,
                            type: "POST",
                            data: $("#update_form").serialize(),
                            dataType: "JSON",
                            success: function (response) {
                                if(response.success){
                                    swal({
                                        title: "Updated!",
                                        text: response.messages,
                                        type: "success"
                                    }).then(function() {
                                        $("#update_form")[0].reset();
                                        $('#update_modal').modal('hide');
                                        datatable.ajax.reload( null, false );
                                    });
                                }else{
                                    swal("Error", response.messages, "error");
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

        const edit = (subjectid) =>{
            $.ajax({
                url:'/comprehensive/exam-subject/'+subjectid,
                type:'GET',
                dataType:'json',
                success:function(success){
                    $('#editid').val(success.id);
                    $('#editsubject').val(success.subject);
                    $('#editdescription').val(success.description);

                    $('#update_modal').modal('show');
                }
            });
        }
        @endcan

        @can('permission', 'deleteSubject')
        const toggleStatus = (id)=>{
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Save it!'
            }).then((result) => {
              if (result.value) {
                // ajax delete data to database
                  $.ajax({
                    url : '/comprehensive/exam-subject/toggle/'+id,
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
