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
<!-- Display All Data -->
<div class="content" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h4 class="card-title"><b>Announcement List</b></h4>
                                <p class="category">Create | Update | Remove Data | Restore Data</p>
                            </div>
                            <div class="col-md-2 text-right">
                                
                            @can('permission', 'createUpdates')
                            <div data-toggle="modal">
                                <a data-toggle="tooltip" title="Click here to remove Announcement" id="add" href="/updates/create" class="btn btn-primary pull-right">
                                    <i class="ti-plus"></i> Add new
                                </a>
                            </div>
                            @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th style="width: 20px;"></th>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Department</th>
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
                </div>
            </div> <!-- end col-md-12 -->
        </div>
    </div>
</div>

@can('permission', 'updateUpdates')
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <div class="modal-body">
                <!--Edit Form -->
                <form id="edit_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Category -->
                        <div class="col-md-6 col-md-offset-1">
                            <div class="form-group">
                                <label>Category *</label>
                                <select class="selectpicker form-control" data-live-search="true" name="category" id="category">
                                    <option value="" disabled selected>Select.....</option>
                                    <option value="Blog">Blog</option>
                                    <option value="Entertainment">Entertainment</option>
                                    <option value="News">News</option>
                                </select>
                            </div>
                        </div>
                        @isset($department_status)
                            @if($department_status == 1)
                                <!-- Department -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Department *</label>
                                        <select class="selectpicker form-control" data-live-search="true" name="department" id="department"
                                        @isset($department_status)
                                            @if($department_status == 0)
                                                {{"disabled=disabled"}}
                                            @endif
                                        @endisset >
                                            <option value="" disabled selected>Select.....</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endisset
                        <!--Title-->
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label>Title *</label>
                                <input type="text" class="form-control border-input" placeholder="Title" name="updates_title" id="updates_title">
                            </div>
                        </div>
                    </div>
                    
                    <!--Text Editor-->
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <label id="lbl_content">Content *</label>
                            <div id="parent_content">
                                <div id="content" name="content"></div>
                                <div class="ck-reset ck-editor">
                                    <!-- This is the editable element -->
                                    <div class="ck-blurred ck-editor__editable ck-rounded-corners ck-editor__editable_inline" role="textbox" aria-label="Rich Text Editor, main" contenteditable="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 col-md-offset-1">
                            <div class="kv-avatar-hint">
                                <small><b>Note:</b> Maximum of 10 Images (Select file < 1500 KB)</small>
                            </div>
                            <div class="kv-avatar">
                                <div id="parent_images">
                                    <div class="file-loading">
                                        <input type="file" id="updates_image" name="updates_image[]" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="text" name="edit_id" id="edit_id" hidden>
                    <input type="text" name="edit_image" id="edit_image" hidden>

                    <div class="row">
                        <div class="text-center">
                            <input type="submit" name="submit" class="btn btn-info btn-fill btn-wd" />
                        </div>
                    </div>
                </form>
                <!--End Edit Form -->
            </div>
        </div>
    </div>
</div>
@endcan

@endsection

@section('js')
<script src="{{ asset('assets/ckeditor5/ckeditor.js') }}"></script>
<script>
    let timer = 0;
    let message = "";
    let editor;

    $(document).ready(function () {
        /* disable copy paste */
        $('body').bind('copy paste',function(e) {
            e.preventDefault(); return false; 
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

        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('updates.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                { "data": "id" },
                { "data": "title" },
                { "data": "department" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 0, 3, 4, 5 ] }, 
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
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        
        @if(!Gate::check('permission', 'updateUpdates') && !Gate::check('permission', 'deleteUpdates') && !Gate::check('permission', 'restoreUpdates'))
            datatable.column(4).visible(false);
        @endif

        
    });

    
    @can('permission', 'updateUpdates')
    //select data to edit
    const edit = (id) => {
        //remove error
        $('label.error').hide();
        $('.error').removeClass('error');

        $("#edit_form")[0].reset();
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url: '/updates/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {

                if(data[0].id)$('#edit_id').val(data[0].id);
                if(data[3])$('#edit_image').val(data[3]);

                if(data[0].category) $("#category").val(data[0].category);
                
                if($("#department")){
                    $("#department").val(data[2]);
                }

                $("#parent_content").empty();
                $("#parent_content" ).append( "<div id='content'></div>");
                
                ClassicEditor
                    .create( document.querySelector( '#content' ), {
                        //disable image and media
                        removePlugins: [ 'ImageUpload', 'MediaEmbed', 'Table' ],  
                        link: {
                            defaultProtocol: 'https://'
                        },
                        // alignment: {
                        //     options: [ 'left', 'right' ]
                        // },
                        // toolbar: [
                        //     'heading', '|', 'bulletedList', 'numberedList', 'alignment', 'undo', 'redo'
                        // ] 
                    } )
                    .then( editor => {
                        editor.height = 500;
                        message = editor.getData();
                        editor.model.document.on( 'change:data', () => {
                            message = editor.getData();
                        })
                    })
                    .catch( error => {
                        console.error(error);
                    } ); 
    
                $("#content").append(data[1]);
                $("#parent_images").empty();
                $("#parent_images").append('<div class="file-loading"><input type="file" id="updates_image" name="updates_image[]" multiple></div>');

                //image content
                $("#updates_image").fileinput({
                    overwriteInitial: true,
                    showClose: false,
                    showCaption: false,
                    showUpload:false,
                    browseLabel: 'Upload',
                    removeLabel: 'Remove',
                    browseIcon: '<i class="ti-folder"></i>',
                    removeIcon: '<i class="ti-close"></i>',
                    defaultPreviewContent: data[3],
                    allowedFileExtensions: ["jpg","png"]
                });
        

                if(data[0].title) $("#updates_title").val(data[0].title);

                $('.selectpicker').selectpicker('refresh');
                $("#edit_modal").modal("show");
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

    //update announcement
    $("#edit_form").validate({
        rules: {
            updates_title:{
                required:true,
            },
            updates_image:{
                required:true,
            },
            department:{
                required:true,
            },
            category:{
                required:true,
            },
        },
        submitHandler: function (form) {
            let images = [];
            let max_images = 10;
            if($("#updates_image").get(0).files.length <= max_images){
                if($("#updates_image").val() == ""){                  
                    images = $("#edit_image").val().split(',');
                }
                Swal.fire({
                    title: 'Update Announcement?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => { 
                    if (result.value) {
                        var formData = new FormData($("#edit_form").get(0));
                        formData.append('content', message);
                        formData.append('images', images);
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url: "/updates/" + $("#edit_id").val(),
                            type: "POST",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            success: function (response) {
                                $('#edit_form')[0].reset();
                                if(response.success){
                                    swal({
                                        title: "Success!",
                                        text: response.messages,
                                        type: "success"
                                    });
                                    $("#edit_modal").modal('hide'); 
                                    datatable.ajax.reload( null, false );
                                    //process loader false
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
            else{
                swal({
                    title: "Not Successful!",
                    text: "Exceeding maximum number of images",
                    type: "error"
                })
            }
        }
    });
    @endcan

    
    @can('permission', 'restoreUpdates')
    //activate Data
    const activate = (id)=>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Restore it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                url: '/updates/status/' + id,
                data:{_token: '{{csrf_token()}}'},
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            }).then(function(){
                                datatable.ajax.reload( null, false);
                            });
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
        })
    }
    @endcan

    @can('permission', 'deleteUpdates')
    //deactivate Data
    const deactivate = (id)=>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                url: '/updates/status/' + id,
                data:{_token: '{{csrf_token()}}'},
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deleted Successfully!",
                                type: "success"
                            }).then(function(){
                                datatable.ajax.reload( null, false);
                            });
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
    };
    @endcan

    const format = ( data ) => {

        const { content, images, post_info } = data;
        let img = '';

        images.forEach((value)=> {
            img += `<img src="${value}" title="${value}" style="height:200px; width:auto"/>`;
        })
        

        return `<div class="row col-md-12">
                    <div class="col-md-9">
                        ${(img)? `<div class="well" id="img_container">${img}</div>`:``}
                        <div class="well" style="min-height:500px; max-height:600px; overflow-y:scroll">${content}</div>
                    </div>
                    <div class="col-md-3">
                        <h3>Post Informations</h3>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <i style="padding-right:20px" class="fa fa-user"></i> ${post_info['user']}
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <i style="padding-right:20px" class="fa fa-calendar"></i> ${post_info['date_created'].split(' ')[0]}
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <i style="padding-right:20px" class="fa fa-clock-o"></i> ${post_info['date_created'].split(' ')[1]}
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <i style="padding-right:20px" class="fa fa-sliders"></i> ${post_info['category']}
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <i style="padding-right:20px" class="fa fa-building"></i> ${post_info['department']}
                            </li>
                        </ul>
                    </div>
                </div>`;
    }



</script>
@endsection
