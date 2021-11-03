@extends('layouts.app2')
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
                                <h4 class="card-title"><b>Reset Password</b></h4>
                                <p class="category">Resetting default password of accounts</p>
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
@endsection

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
                "data":{ _token: "{{csrf_token()}}", 'action':'reset_password'}
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

    //Reset Password
    const resetpassword = (id)=>{    
        Swal.fire({
            title: 'Reset Password?',
            text: "Reset to default password",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reset it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/account/resetpassword/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            datatable.ajax.reload( null, false );
                            swal({
                                title: "Save!",
                                text: "Reset Successfully!",
                                type: "success"
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
</script>
@endsection