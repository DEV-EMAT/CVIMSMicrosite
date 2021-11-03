@extends('layouts.app2')
@section('location')
    {{$title}}
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
                                <h4 class="card-title"><b>Verification</b></h4>
                                <p class="category">verifying and creation of scholars account</p>
                            </div>
                            <div class="col-lg-2">

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
                                    <th>Date Register</th>
                                    <th>Status</th>
                                    <th style="width: 300px;">Actions</th>
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

<!--Modal for Edit -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="ti-close"></i>
				</button>
				<h4 class="modal-title"><span id="modalTitle">Verify Scholar</span></h4>
			</div>
			<div class="modal-body">
            <form id="update_form" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <div class="kv-avatar-hint">
                            <small><b>Note:</b> Select file < 1500 KB</small> 
                        </div>
                        <div class="kv-avatar">
                            <div class="file-loading">
                                <input type="file" name="avatar" id="avatar">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Last Name -->
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="hidden" name="image_filename" id="image_filename">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="pre_reg_id" id="pre_reg_id">
                            <input type="text" class="form-control border-input" placeholder="Last Name" name="lastname" id="lastname" disabled>
                        </div>

                        <!-- First Name -->
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control border-input" placeholder="First Name" name="firstname" id="firstname" disabled>
                        </div>

                        <!-- Middle Name -->
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" class="form-control border-input" placeholder="Middle Name" name="middlename" id="middlename" disabled>
                        </div>

                        <!-- Middle Name -->
                        <div class="form-group">
                            <label>Suffix</label>
                            <input type="text" class="form-control border-input" placeholder="Suffix" name="suffix" id="suffix" disabled>
                        </div>
                        <div class="form-group">
                            <label>School Name</label>
                            <input type="text" class="form-control border-input" placeholder="School Name" name="school_name" id="school_name" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label>Course</label>
                            <input type="text" class="form-control border-input" placeholder="Course" name="course" id="course" disabled>
                        </div>

                        <div class="form-group">
                            <label>School List</label>
                            <select class="form-control selectpicker" data-live-search="true" name="school_list" id="school_list">
                                <option disabled selected value="">Select ...</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Course List</label>
                            <select class="form-control selectpicker" data-live-search="true" name="course_list" id="course_list">
                                <option disabled selected value="">Select ...</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Scholar Educational Level</label>
                            <select class="form-control selectpicker" data-live-search="true" name="type_list" id="type_list">
                                <option disabled selected value="">Select ...</option>
                            </select>
                        </div>
                    </div>
                </div>
    			<div class="modal-footer">
                    <input type="submit" id="submit" name="submit" class="btn btn-info btn-fill btn-wd">
    				<button type="button" class="btn btn-danger btn-fill btn-wd" data-dismiss="modal">Close</button>
                </div>
            </form>
		</div>
	</div>
</div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        
        /* type list */
        $.ajax({
            url: '{{ route('educational-attainment.find-type') }}',
            dataType: "JSON",
            type: "GET",
            success: function (data) {
                data.forEach((value)=>{
                    $('[name="type_list"]').append('<option value='+value.id+'>'+ value.title+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                });
            }
        })

        /* school list */
        $.ajax({
            url: '/iskocab/school/find-all-school',
            dataType: "JSON",
            type: "GET",
            success: function (data) {
                data.forEach((value)=>{
                    $('[name="school_list"]').append('<option value='+value.id+'>'+ value.school_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                });
            }
        })

        /* course list */
        $.ajax({
            url: '{{ route('course.find-all-course') }}',
            dataType: "JSON",
            type: "get",
            data: { _token: '{{ csrf_token() }}'},
            success: function (data) {
                data.forEach((value)=>{
                    $('[name="course_list"]').append('<option value='+value.id+'>'+ value.course_description+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                });
            }
        })
        
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            'responsive':true,
            "ajax":{
                "url": '{{ route('scholar.findunverified') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "date_register" },
                { "data": "status" },
                { "data": "action" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [1, 2, 3 ] }, 
            ]	 
        });
    });
    
    $("#update_form").validate({
        rules: {
            school_list: {
                required: true
            },
            course_list: {
                required: true
            }
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Verified Now?',
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

                    var formData = new FormData($("#update_form").get(0));

                    $.ajax({
                        url: '{{ route('scholar.verify-store') }}',
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    Swal.fire({
                                        title: data.messages,
                                        type: 'success'
                                    }).then(function () {
                                        $("#edit_modal").modal('hide');
                                        $("#update_form")[0].reset();
                                        datatable.ajax.reload( null, false);
                                    });
                                } else {
                                    Swal.fire({
                                        title: data.messages,
                                        type: 'warning'
                                    });
                                }
                                
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
            })
        } 
    });

    //Verify Data
    function verify(id){
        
        $('.selectpicker').selectpicker('refresh');

        $.ajax({
            url: '/iskocab/scholar/unverified/' + id,
            dataType: "JSON",
            type: "GET",
            success: function (data) {
                $("#avatar").fileinput("destroy");
                $("#avatar").fileinput({
                    overwriteInitial: true,
                    showClose: false,
                    showCaption: false,
                    showUpload: false,
                    showBrowse:false,
                    browseLabel: 'Update',
                    removeLabel: 'Remove',
                    browseIcon: '<i class="ti-folder"></i>',
                    removeIcon: '<i class="ti-close"></i>',
                    defaultPreviewContent: '<img src="{{ asset('images/iskocab/pre_registration/') }}/' + data.image +'" alt="Your Avatar">',
                    allowedFileExtensions: ["jpg","png"]
                });

                // $('#editid').val(data.userData.id);
                $('#lastname').val(data.last_name);
                $('#lastname').val(data.last_name);
                $('#firstname').val(data.first_name);
                $('#middlename').val(data.middle_name);
                $('#school_name').val(data.school_name);
                $('#course').val(data.course);
                $('#course_list').val(data.course_id).selectpicker('refresh');
                $('#school_list').val(data.school_id).selectpicker('refresh');
                $('#image_filename').val(data.image);
                $('#pre_reg_id').val(data.id);
                $('#user_id').val(data.user_id);



                $("#edit_modal").modal("show"); 
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
        
    }
</script>
@endsection