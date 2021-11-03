
@extends('layouts.app2')

@section('style')
    <style>
    .ck-editor__editable_inline {
            min-height: 20em;
    }
    </style>
@endsection
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
                                <h4 class="card-title"><b>Add Announcement</b></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <!--Register Form -->
                        <form id="register_form" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="row">
                                <!-- Category -->
                                <div class="col-sm-6 col-sm-offset-1">
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
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Department *</label>
                                                <select class="selectpicker form-control" data-live-search="true" name="department" id="department">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @endisset
                                <!--Title-->
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group">
                                        <label>Title *</label>
                                        <input type="text" class="form-control border-input" placeholder="Title" name="updates_title" id="updates_title">
                                    </div>
                                </div>
                            </div>
                            
                            <!--Text Editor-->
                            <div class="row">
                                <div class="col-sm-10 col-md-offset-1">
                                    {{-- <div class="form-group"> --}}
                                        <label>Content *</label>
                                        <div id="content" name="content"></div>
                                        <div class="ck-reset ck-editor">
                                        </div>
                                    {{-- </div> --}}
                                </div>

                                <div class="col-sm-10 col-md-offset-1">
                                    <div class="kv-avatar-hint">
                                        <small><b>Note:</b> Maximum of 10 Images (Select file < 1500 KB)</small>
                                    </div>
                                    <div class="kv-avatar">
                                        <div class="file-loading">
                                            <input type="file" id="updates_image" name="updates_image[]" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-center">
                                    <input type="submit" name="submit" class="btn btn-info btn-fill btn-wd" />
                                </div>
                            </div>
                        </form>
                        <!--End Register Form -->
                    </div>
                </div>
            </div> <!-- end col-md-12 -->
        </div>
    </div>
</div>
<!-- End Display All Data -->

@endsection

@section('js')
<script src="{{ asset('assets/ckeditor5/ckeditor.js') }}"></script>
<script>
    $(document).ready(function () {
        
        /* disable copy paste */
        $('body').bind('copy paste',function(e) {
            e.preventDefault(); return false; 
        });
       
        let message = "";
        let counter = 1;

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

        //text editor
        ClassicEditor
            .create( document.querySelector( '#content' ), {
                //disable image and media
                removePlugins: [ 'ImageUpload', 'MediaEmbed', 'Table' ],
                link: {
                    defaultProtocol: 'https://'
                }   
            } )
            .then( editor => {
                editor.model.document.on( 'change:data', () => {
                    message = editor.getData();
                })
            })
            .catch( error => {
                console.error(error);
            } );
            
        //create update
        $("#register_form").validate({
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
                for (var i = 0; i < $("#updates_image").get(0).files.length; i++) {
                    let fileSize = $("#updates_image").get(0).files[i];
                    alert(fileSize.size);
                }
                let images = [];
                let max_images = 10;
                if($("#updates_image").get(0).files.length <= max_images){
                    for (var i = 0; i < $("#updates_image").get(0).files.length; ++i) {
                        images.push($("#updates_image").get(0).files[i].name);
                    }
                    Swal.fire({
                        title: 'Save new announcement?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        if (result.value) {
                            var formData = new FormData($("#register_form").get(0));
                            formData.append('content', message);
                            formData.append('images', images);
                            //process loader true
                            processObject.showProcessLoader();
                            $.ajax({
                                url: "{{ route('updates.store')}}",
                                type: "POST",
                                data: formData,
                                cache:false,
                                contentType: false,
                                processData: false,
                                dataType: "JSON",
                                success: function (response) {
                                    $('#register_form')[0].reset();
                                    if(response.success){
                                        swal({
                                            title: "Success!",
                                            text: response.messages,
                                            type: "success"
                                        }).then(function() {
                                            location.reload();
                                        });
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
    });

    /* ctr + v banning function */
//     $('#updates_title').on("cut copy paste",function(e) {
//       e.preventDefault();
//    });
    $('#updates_title').keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '118' || event.which == '86')) {
            swal({
                title: "Oops! something went wrong.",
                text:'(Ctr + V) banned on this fields!',
                type: "error"
            })
            event.preventDefault();
         }
    });

    //image content
    $("#updates_image").fileinput({
        overwriteInitial: false,
        showClose: false,
        showCaption: false,
        showUpload:false,
        browseLabel: 'Upload',
        removeLabel: 'Remove',
        browseIcon: '<i class="ti-folder"></i>',
        removeIcon: '<i class="ti-close"></i>',
        allowedFileExtensions: ["jpg","png"]
    });
</script>
@endsection