@extends('layouts.app2',['empindex' => true])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row"> 
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Educational Attainment</b>
                            
                            @can('permission', 'createEducationalAttainment')
                            <a data-toggle="modal" data-target="#create_modal" class="btn btn-primary pull-right">
                                <i class="ti-plus"></i> Add new
                            </a>
                            @endcan
                        </h4><br>
                    </div>
                    <div class="card-content">
                    <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th>Educational Attainment</th>
                                    <th>Description</th>
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

@can('permission', 'createEducationalAttainment')
<div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">Educational Attainment</h4>
            </div>
            <form id="create_form">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Educational Attainment</label>
                        <input type="text" class="form-control border-input" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control border-input" id="description" name="description">
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

@can('permission', 'updateEducationalAttainment')
<div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">Scholar Type</h4>
            </div>
            <form id="update_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="editid" id="editid">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Educational Attainment</label>
                        <input type="text" class="form-control border-input" id="edit_title" name="edit_title">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control border-input" id="edit_description" name="edit_description">
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
                    "url": '{{ route('educational-attainment.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "title" },
                    { "data": "description" },
                    { "data": "status" },
                    { "data": "action" }
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [3,4] }
                ]
            });
            
            @if(!Gate::check('permission', 'updateEducationalAttainment') && !Gate::check('permission', 'deleteEducationalAttainment'))
                datatable.column(4).visible(false);
            @endif
        });
        
        @can('permission', 'createEducationalAttainment')
        $("#create_form").validate({
            rules: {
                title: {
                    required: true,
                    minlength:2
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Save new Educational Attainment?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        
                        //show loader
                        processObject.showProcessLoader();

                        var formData = new FormData($("#create_form").get(0));

                        $.ajax({
                            url: "{{ route('educational-attainment.store') }}",
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
                                    })
                                    $("#create_form")[0].reset();
                                    $('#create_modal').modal('hide');
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    datatable.ajax.reload( null, false );
                                }else{
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: response.messages,
                                        type: "error"
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    datatable.ajax.reload( null, false );
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: errorThrown,
                                    type: "error"
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });
        @endcan

        
        @can('permission', 'updateEducationalAttainment')
        $("#update_form").validate({
            rules: {
                edit_title: {
                    required: true,
                    minlength:2
                }
            },
            submitHandler: function (form) {   

                var id = $('#editid').val();
                
                Swal.fire({
                    title: 'Update Type?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {      
                    if (result.value) {
                        
                        //show loader
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '/iskocab/educational-attainment/'+id,
                            type: "POST",
                            data: $("#update_form").serialize(),
                            dataType: "JSON",
                            success: function (response) {
                                if(response.success){
                                    swal({
                                        title: "Updated!",
                                        text: response.messages,
                                        type: "success"
                                    })
                                    
                                    $("#update_form")[0].reset();
                                    $('#update_modal').modal('hide');
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    datatable.ajax.reload( null, false );
                                }else{
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: response.messages,
                                        type: "error"
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: errorThrown,
                                    type: "error"
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });

        function edit(subjectid)
        {
            $.ajax({
                url:'/iskocab/educational-attainment/'+subjectid,
                type:'GET',
                dataType:'json',
                success:function(success){
                    $('#editid').val(success.id);
                    $('#edit_title').val(success.title);
                    $('#edit_description').val(success.description);

                    $('#update_modal').modal('show');
                }
            });
        }
        @endcan

        @can('permission', 'deleteEducationalAttainment')
        function del(id)
        {
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
              if (result.value) {
                //show loader
                processObject.showProcessLoader();
                // ajax delete data to database
                  $.ajax({
                    url : '/iskocab/educational-attainment/toggle/'+id,
                    type: "POST",
                    data:{ _token: "{{csrf_token()}}"},
                    dataType: "JSON",
                    success: function(response)
                    { 
                        swal({
                            title: "Success!",
                            text: response.messages,
                            type: "success"
                        })
                        //process loader false
                        processObject.hideProcessLoader();
                        datatable.ajax.reload( null, false );
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            title: "Oops! something went wrong.",
                            text: errorThrown,
                            type: "error"
                        });
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });
                
              }
            });
        }
        @endcan

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z]+$/i.test(value);
        }, "Letters only please");

        
    </script>
@endsection
