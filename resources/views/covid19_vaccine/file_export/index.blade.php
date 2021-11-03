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
                                    <h4 class="card-title"><b>CVIMS File Export</b></h4>
                                    <p class="category">File Export List</p>
                                </div>
                                <div class="col-lg-2">
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead style="background-color: rgb(214, 214, 214)">
                                    <tr>
                                        <th>Date Time Reported</th>
                                        <th>Total of Data</th>
                                        <th>Report Type</th>
                                        <th>File Name</th>
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
    <!-- End Display All Data -->

    <!-- Modal For Add -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="courseModalAdd">
        <div class="modal-dialog" role="document">
            <form id="add_course-form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Course</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="course_code">COURSE CODE:</label>
                                <input type="text" class="form-control" name="course_code" id="course_code"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
                        <!-- Course Description -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="course_description">COURSE DESCRIPTION:</label>
                                <input type="text" class="form-control" name="course_description"
                                    id="course_description" placeholder="Enter Course Description">
                            </div>
                        </div>
                        <!-- End Course Description -->
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success" id="save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Add -->

    <!-- Modal For Edit -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="courseModalEdit">
        <div class="modal-dialog" role="document">
            <form id="edit_course-form" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Course</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_course_code">COURSE CODE:</label>
                                <input type="text" class="form-control" name="edit_course_code" id="edit_course_code"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
                        <!-- Course Description -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_course_description">COURSE DESCRIPTION:</label>
                                <input type="text" class="form-control" name="edit_course_description"
                                    id="edit_course_description" placeholder="Enter Course Description">
                            </div>
                        </div>
                        <!-- End Course Description -->
                    </div>
                    <input type="hidden" id="course_id" name="course_id">
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Edit-->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('file-export.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "datetime_requested" },
                { "data": "total_of_data" },
                { "data": "export_type" },
                { "data": "remarks" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 0,1,2,3,4 ] }, 
            ]
        });
    });



    const fileExport = (id,type,filename,date) =>{
        
        Swal.fire({
                title: 'Export File?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                html: "<b>" + filename +"<br> Date Last Requested: " + date ,
                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        xhrFields: {
                            responseType: 'blob',
                        },
                        url: "/covid19vaccine/file-export/download-file/"+ id + "/" + type + "/" + filename+'.xlsx',
                        type: "GET",
                        beforeSend: function(){
                            processObject.showProcessLoader();
                        },
                        success: function(result, status, xhr) {

                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : 'salary.xlsx');

                        // The actual download
                        var blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();
                        document.body.removeChild(link);
                        swal({
                            title: "Save!",
                            text: "Successfully Exported!",
                            type: "success",
                            html: "<b>" + filename +"<br> Date Last Requested: " + date ,
                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                        });

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" + errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                        },
                        complete: function(){
                            processObject.hideProcessLoader();
                        },
                    });
                }
            })
    }

</script>
@endsection
