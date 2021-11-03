@extends('layouts.app2')
@section('style')
<style>
    /* audio { display:none;} */
    .vertical-alignment-helper {
        display:table;
        height: 100%;
        width: 100%;
    }
    .vertical-align-center {
        /* To center vertically */
        display: table-cell;
        vertical-align: middle;
    }
    .loader,
    .loader:before,
    .loader:after {
      background: rgb(255, 0, 0);
      -webkit-animation: load1 1s infinite ease-in-out;
      animation: load1 1s infinite ease-in-out;
      width: 1em;
      height: 4em;
    }
    .loader:before,
    .loader:after {
      position: absolute;
      top: 0;
      content: "";
    }
    .loader:before {
      left: -1.5em;
    }
    .loader {
      text-indent: -9999em;
      margin: 1em auto;
      position: relative;
      font-size: 5px;
      -webkit-animation-delay: -0.16s;
      animation-delay: -0.16s;
    }
    .loader:after {
      left: 1.5em;
      -webkit-animation-delay: -0.32s;
      animation-delay: -0.32s;
    }
    @-webkit-keyframes load1 {
      0%,
      80%,
      100% {
        box-shadow: 0 0 rgb(255, 0, 0) 13);
        height: 4em;
      }
      40% {
        box-shadow: 0 -2em #ff0000;
        height: 5em;
      }
    }
    @keyframes load1 {
      0%,
      80%,
      100% {
        box-shadow: 0 0 rgb(255, 0, 0);
        height: 4em;
      }
      40% {
        box-shadow: 0 -2em #ffffff;
        height: 5em;
      }
    }
    .loader4:before,
    .loader4:after,
    .loader4 {
      border-radius: 50%;
      width: 2.5em;
      height: 2.5em;
      -webkit-animation-fill-mode: both;
      animation-fill-mode: both;
      -webkit-animation: load4 1.8s infinite ease-in-out;
      animation: load4 1.8s infinite ease-in-out;
    }
    .adjust
    {min-height: 25px; height:auto;}
    .mt
    { margin-top:50px;}
    /* .large-alarm .adjust .loader{
        font-size: 16px;
    }
    .large-alarm .adjust{
        min-height: 150px; height:auto;
        } */
    
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
                                    <h4 class="card-title"><b>Emergency Request</b></h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Requestant</th>
                                        <th>Contact Number</th>
                                        <th>Location</th>
                                        <th style="width: 150px;">Incident Status</th>
                                        <th style="width: 70px;">Status</th>
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

   

    {{-- <div id="player">
        <audio controls autoplay >
         <source src="{{ asset('images/ecabs/emergency/alert.mp3') }}" type="audio/mpeg">
                    unsupported !! 
        </audio>
    </div> --}}


    <!-- Modal For Add -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="addNewIncidentCategory">
        <div class="modal-dialog" role="document">
            <form id="addIncidentForm" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Add Incident Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="IncidentCategoryDescription">COURSE CODE:</label>
                                <input type="text" class="form-control" name="IncidentCategoryDescription" id="IncidentCategoryDescription"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="incidentModal">
        <div class="modal-dialog" role="document">
            <form id="editDescriptionForm" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Incident Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_incident_category_desc">COURSE CODE:</label>
                                <input type="text" class="form-control" name="edit_incident_category_desc" id="edit_incident_category_desc"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
                    </div>
                    <input type="hidden" id="incident_id" name="incident_id">
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Edit-->


     <!-- Modal For Edit -->
     <div class="modal fade in" tabindex="-1" role="dialog" id="alarmingModal">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center">
                <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            {{-- <div class="modal-header">
                                <a class="close" data-dismiss="modal">&times;</a>
                            </div> --}}
                            <!-- End Modal Header -->
                            <div class="modal-body">
                                <a class="close" data-dismiss="modal">&times;</a>
                                <!-- Course Code -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <img class="img-responsive img-thumbnail" src="{{ asset('images/ecabs/emergency/alert.gif') }}" />
                                        
                                    </div>
                                  
                                                                        
                                    <audio id="myAudio" controls style="visibility: hidden;">
                                        <source src="{{ asset('images/ecabs/emergency/alert.mp3') }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio><br>
                                    <button onclick="enableAutoplay()" type="button">Enable autoplay</button>
                                </div>
                                
                                <!-- End Course Code -->
                            </div>
                            <div class="modal-footer">
                                <center>
                                    <button type="button" class="btn btn-danger btn-fill btn-wd"> Return to incident Table</button>
                                </center>
                           </div>
                    </div>
                </div>
            </div>
        </div>
</div>
    <!-- End Modal for Edit-->
    
    {{-- <audio src="{{ asset('images/ecabs/emergency/alert.mp3') }}" controls autoplay loop /> --}}

@endsection

@section('js')
<script>

    let myGeocoding = new GeocodingClass();
    // var lat = longLat.latitude;
    // var long = longLat.longitude;
    // var arrLatLong = [long,lat];


    $(document).ready(function () {
        //alert(locationByGeocoding('121.098695','14.313854'));
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('response-request.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "id" },
                { "data": "requestant" },
                { "data": "contact_number" },
                { "data": "incident_location" },
                { "data": "incident_status" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "aoColumnDefs": [
                {
                        "aTargets": [3],
                        "mData": "incident_location",
                        "mRender": function (data, type, full) { 
                            return myGeocoding.locationByGeocoding(data[1],data[0]);
                        }
                    }
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [ 5 ] }, 
                ]	 	 
        });

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");
        enableAutoplay();
         
        
    });

  
    function enableAutoplay() {
        $('#alarmingModal').modal('show');
        var x = document.getElementById("myAudio");
        x.autoplay = true;
        x.load();
        }
    //Add Course

        $("#addIncidentForm").validate({
            rules: {
                IncidentCategoryDescription: {
                    required: true
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Register Now?',
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
                            url: '{{ route('incident-category.store') }}',
                            type: "POST",
                            data: $('#addIncidentForm').serialize(),
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    $('#addNewIncidentCategory').modal('hide');
                                    $("#addIncidentForm")[0].reset();
                                    //process loader false
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success"
                                    })
                                    datatable.ajax.reload( null, false );
                                    processObject.hideProcessLoader();
                                } else {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: data.messages,
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


    //Update Incident

    $("#editDescriptionForm").validate({
        rules: {
            edit_incident_category_desc: {
                required: true
            }
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Update Data?',
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
                        url: '/emergency/incident-category/' + $("#incident_id").val(),
                        type: "PUT",
                        data: $('#editDescriptionForm').serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                $('#incidentModal').modal('hide');
                                $("#editDescriptionForm")[0].reset();
                                swal({
                                    title: "Save!",
                                    text: "Successfully!",
                                    type: "success"
                                })
                                //process loader false
                                processObject.hideProcessLoader();
                                datatable.ajax.reload( null, false );
                            } else {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: data.messages,
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

    //Select Data to edit
   
    const edit = (id) =>{
        $.ajax({
            url: '/emergency/incident-category/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#incidentModal").modal("show");
                $("#edit_incident_category_desc").val(data.description);
                $("#incident_id").val(data.id);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    //Deactivate Data
    
    const deactivate = (id) =>
    {
        Swal.fire({
            title: 'Deactivate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/emergency/incident-category/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deactivate Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
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
 

    
    const activate = (id) =>{
        Swal.fire({
            title: 'Activate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, activate it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/emergency/incident-category/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
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
</script>
@endsection
