@extends('layouts.app2')
@section('style')
@section('location')
{{$title}}
@endsection
<style>
    .kv-preview-thumb{
        display: block !important;
        margin-left: 5px !important;
    }
</style>
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
                                <h4 class="card-title"><b>Account List</b></h4>
                                <p class="category">View Accounts</p>
                            </div>
                            <div class="col-lg-2"></div>
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
                                        <th>Address</th>
                                        <th>Position</th>
                                        <th>Status</th>
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
<script>//Start of Function for Address
    $(document).ready(function () {        
       
        datatable = $('#datatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('guest-account.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "address" },
                { "data": "position" },
                { "data": "status" },
            ],
        });
    });
</script>
@endsection