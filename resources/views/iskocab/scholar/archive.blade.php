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
                                <h4 class="card-title"><b>Archive</b></h4>
                                <p class="category">Restoring of deleted Scholar Account</p>
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
                            <img id="showavatar"/>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Full Name:</label>
                                <b><p style="font-weight:bold; font-size:15" id="showfullname"></p></b>
                            </div>
                            <div class="form-group">
                                <label for="">Email Address:</label>
                                <p id="showemail"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Contact:</label>
                                <p id="showcontact"></p>
                            </div>
                            <div class="form-group">
                                <label for="">School:</label>
                                <p id="showschool"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Course:</label>
                                <p id="showcourse"></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Barcode:</label>
                                <p id="showbarcode"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Sex:</label>
                                <p id="showsex"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Date of Birth:</label>
                                <p id="showdateofbirth"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Barangay:</label>
                                <p id="showbarangay"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Civil Statys:</label>
                                <p id="showcivilstatus"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Religion:</label>
                                <p id="showreligion"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Address:</label>
                                <p id="showaddress"></p>
                            </div>
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

@section('js')
<script>
    $(document).ready(function () {
        
       //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('scholar.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", "action":"archive" }
            },
            colReorder: {
                 realtime: true
            },
            "columns": [
                { "data": "fullname" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2 ] }, 
            ]	 
        });        
    });

    //Select Data to view
        // function view(id){
        //     $.ajax({
        //         url: 'scholar/' + id,
        //         type: "GET",
        //         dataType: "JSON",
        //         success: function (data) {
        //             if(data[0].image == null)
        //                 $('#showavatar').attr('src','../../bootstrap-fileinput/img/default-avatar-male.png');
        //             else
        //                 $('#showavatar').attr('src','../../images/'+data[0].image);
        //             $('#showfullname').text(data[0].lastname +", "+ data[0].firstname +" "+ data[0].middlename);
        //             $('#showemail').text(data[1].email);
        //             $('#showcontact').text(data[0].contact);
        //             $('#showschool').text(data[2].school_name);
        //             $('#showcourse').text(data[3].course_description);
        //             $('#showbarcode').text(data[0].barcode);
        //             $('#showbarangay').text(data[4].barangay);
        //             var civilstatus=''; 
        //             if(data[0].civil_status == '1')
        //                 civilstatus='SINGLE';
        //             else if(data[0].civil_status == '2')
        //                 civilstatus='MARRIED';
        //             else if(data[0].civil_status == '3')
        //                 civilstatus='DIVORCED';
        //             else if(data[0].civil_status == '4')
        //                 civilstatus='SEPARATED';
        //             else
        //                 civil_status='WIDOWED';
        //             $('#showcivilstatus').text(civilstatus);
        //             $('#showreligion').text(data[0].religion);
        //             var sex=''; if(data[0].sex == 1){ sex='MALE'; }else{ sex='FEMALE'; }
        //             $('#showsex').text(sex);
        //             $('#showdateofbirth').text(data[0].dateofbirth);
        //             $('#showaddress').text(data[0].address);
        //             $('#show_modal').modal('show');
        //         },
        //         error: function (jqXHR, textStatus, errorThrown) {
        //             alert(errorThrown);
        //         }
        //     });
        // }

    //Restore Data
    function restore(id){
        Swal.fire({
            title: 'Activate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Activate it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/iskocab/scholar/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Activate Successfully!",
                                type: "success"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false);
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
</script>
@endsection