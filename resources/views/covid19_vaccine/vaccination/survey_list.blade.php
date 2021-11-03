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
                                    <h4 class="card-title"><b><i class="fa fa-user-md" aria-hidden="true"></i> Participants List</b></h4>
                                    {{-- <p class="category">Counseling and Final Consent</p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Participants Fullname</th>
                                        <th>Verification Status</th>
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

@endsection


@section('js')



    <script>

        $(document).ready(function () {
            datatable = $('#datatable').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('surveyList.findAll') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "last_name" },
                    { "data": "status" },
                    { "data": "actions" },
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [ 2 ] },
                ]
            });

            jQuery.validator.addMethod("lettersonly", function (value, element) {
                return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Letters only please");
        });
    </script>



@endsection
