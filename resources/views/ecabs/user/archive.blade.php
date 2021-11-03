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
                                <h4 class="card-title"><b>Accounts Archive</b></h4>
                                <p class="category">Restoring of deleted Accounts</p>
                            </div>
                            <div class="col-lg-2">

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
                                        <th>Fullname</th>
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
<!-- End Display All Data -->

<!--Modal for View -->
<div class="modal fade" id="show_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title">Profile Information</h4>
            </div>
            <div class="modal-body">
                <div style="margin:20px">
                    <div class="row">
                        <div class="col-md-5">
                            <img id="show_avatar"/>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Full Name:</label>
                                <b><p style="font-weight:bold; font-size:15" id="show_full_name"></p></b>
                            </div>
                            <div class="form-group">
                                <label for="">Email Address:</label>
                                <p id="show_email"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Mobile Number:</label>
                                <p id="show_contact"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Telephone Number:</label>
                                <p id="show_telephone"></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Sex:</label>
                                <p id="show_sex"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Address:</label>
                                <p id="show_address"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Date of Birth:</label>
                                <p id="show_dob"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Barangay:</label>
                                <p id="show_barangay"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Civil Status:</label>
                                <p id="show_civilstatus"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Religion:</label>
                                <p id="show_religion"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                        </div>
                    </div>
                </div>      
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--End Modal for View -->
@endsection


<!-- Modal For Restore -->
<div class="modal fade in" tabindex="-1" role="dialog" id="restoreModal">
    <div class="modal-dialog" role="document">
        <form id="restore_account_form" method="post">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">RESTORE ACCOUNT</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <!-- Reason -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="reason">Reason for restoring account:</label>
                            <textarea type="text" class="form-control" name="reason" id="reason"
                                placeholder="Enter Reason"></textarea>
                        </div>
                    </div>
                    <!-- End Reason -->
                    
                </div>
                <input type="hidden" id="restore_id">
            </form>
            <div class="modal-footer">
                <button class="btn btn-success" id="save">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal for Restore -->

@section('js')
<script>
    $(document).ready(function () {
        
        datatable = $('#datatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('account.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", 'action':'archive'}
            },
            colReorder: {
                 realtime: true
            },
            "columns": [
                { "data": "fullname" },
                { "data": "status" },
                { "data": "actions" },
            ],
        });
    });

    //select data to view
    const view = (id) => {
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url: '/account/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#show_avatar').attr('src','../../../images/' + data[0].image);
                $('#show_full_name').text(data[0].last_name + " "+ data[0].affiliation + ", "+ data[0].first_name +" "+ data[0].middle_name);
                $('#show_contact').text(data[1].contact_number);
                $('#show_email').text(data[1].email);
                $('#show_telephone').text(data[0].telephone_number);
                $('#show_address').text(data[0].address);
                $('#show_dob').text(data[0].date_of_birth);

                $("#show_region").text(data[6].region);
                $("#show_province").text(data[6].province);
                $("#show_city").text(data[6].city);
                $("#show_barangay").text(data[6].barangay);
                
                if(data[0].civil_status != null){
                    var civilstatus=''; 
                    if(data[0].civil_status == '1')
                        civilstatus='SINGLE';
                    else if(data[0].civil_status == '2')
                        civilstatus='MARRIED';
                    else if(data[0].civil_status == '3')
                        civilstatus='DIVORCED';
                    else if(data[0].civil_status == '4')
                        civilstatus='SEPARATED';
                    else
                        civil_status='WIDOWED';

                    $('#show_civilstatus').text(civilstatus);
                }
                else
                    $('#show_civilstatus').text(" ");

                if(data[0].religion != null)
                    $('#show_religion').text(data[0].religion);
                else
                    $('#show_religion').text(" ");
    
                if(data[0].gender != null){
                    var sex=''; 
                    if(data[0].gender == 1) sex='MALE';
                    else if(data[0].gender == 2) sex='FEMALE';
                    $('#show_sex').text(sex);
                }
                else
                    $('#show_sex').text(" ");  
                    
                $('#show_modal').modal('show');
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

    //Activate Data
    const activate = (id)=>{   
        $("#restore_id").val(id);
        $("#reason").val("");
        $("#restoreModal").modal("show");  
    }

    //restore form
   $("#restore_account_form").validate({
        rules: {
            reason: {
                required: true
            },
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Restore Data?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                    url: '/account/update/status/' + $("#restore_id").val(),
                    data:$('#restore_account_form').serialize(),
                        type: "POST",
                        success: function (data) {
                            if (data.success) {
                                swal({
                                    title: "Save!",
                                    text: "Restore Successfully!",
                                    type: "success"
                                }).then(function () {
                                    datatable.ajax.reload( null, false);
                                });
                                
                                $("#restoreModal").modal("hide");
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
    });
</script>
@endsection