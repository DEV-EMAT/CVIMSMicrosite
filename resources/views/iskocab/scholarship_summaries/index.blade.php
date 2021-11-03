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
                                <h4 class="card-title"><b>Scholarship Evaluation</b></h4>
                                <p class="category">Evaluate scholars</p>
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
                                    <th>Application Code</th>
                                    <th>APPLIED BY</th>
                                    <th>EVALUATED BY</th>
                                    <th>ASSESSED BY</th>
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




@endsection

@section('js')
<script>

    $(document).ready(function(){
  //Datatables
  datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('scholarship-summaries.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                 realtime: true
            },
            "columns": [
                { "data": "fullname" },
                { "data": "applicationCode" },
                { "data": "appliedBy" },
                { "data": "evaluatedBy" },
                { "data": "assessedBy" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2 ] }, 
            ]	 
        });
    });
</script>
@endsection