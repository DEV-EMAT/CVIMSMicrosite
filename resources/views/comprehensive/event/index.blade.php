@extends('layouts.app2')
@section('location')
    {{$title}}
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <h4 class="card-title"><b>Event List</b></h4>
                                <p class="category">Create | Update | View | Delete Event</p>
                            </div>
                            <div class="col-lg-2">
                                @can('permission', 'createEvent')
                                <a data-toggle="modal" data-target="#create_modal" class="btn btn-primary pull-right">
                                    <i class="ti-plus"></i> Add new
                                </a>
                                @endcan
                            </div>
                        </div>

                    </div>
                    <div class="card-content">
                    <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0" width="100%">
                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Date of Event</th>
                                    <th>Department</th>
                                    <th>Attendees Capacity</th>
                                    <th>Attendance Status</th>
                                    <th>Status</th>
                                    <th style="width: 250px;">Action</th>
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

@can('permission', 'createEvent')
<div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">Events</h4>
            </div>
            <form id="create_form">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="divfield">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="well">
                                    <label><input type="checkbox" name="required_attendance" id="required_attendance" value="1"> Required Attendance</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="well">
                                    <label><input type="checkbox" name="event_type" id="event_type" value="1"> Exclusive Event</label>
                                </div>
                            </div>
                        </div>
                        @if($department_status)
                        <div class="form-group">
                            <label for="">Department</label>
                            <select class="form-control selectpicker" data-live-search="true" name="department" id="department">
                                <option selected disabled value="">Select Department</option>
                            </select>
                        </div>
                        @endif
                        <div class="form-group">
                          <label for="">Event</label>
                          <input type="text" class="form-control" name="event" id="event" placeholder="Event">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="">Attendees Capacity</label>
                                  <input type="number" min='0' class="form-control" name="attendees_capacity" id="attendees_capacity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="">Date of Event</label>
                                  <input type="text" class="form-control datetimepicker" name="date_of_event" id="date_of_event" max="9999-12-31">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Event Venue</label>
                            <input type="text" class="form-control" name="venue" id="venue" placeholder="Event venue">
                          </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Start Time of Event</label>
                                    <input type="time" class="form-control" name="start_of_event" id="start_of_event">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">End Time of Event</label>
                                    <input type="time" class="form-control" name="end_of_event" id="end_of_event">
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Time In Allowance</label>
                                    <input type="time" onchange="checkTime('start_of_event', 'time_in_allowance', 'before')" class="form-control" name="time_in_allowance" id="time_in_allowance">
                                    <small class="label label-danger time_in_error" style="display: none"> Set time BEFORE start time</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Time Out Allowance</label>
                                    <input type="time" onchange="checkTime('end_of_event', 'time_out_allowance', 'after')" class="form-control" name="time_out_allowance" id="time_out_allowance">
                                    <small class="label label-danger time_out_error" style="display: none"> Set time After end time</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="">Description</label>
                          <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <input type="submit" name="edit" class="btn btn-info btn-fill btn-wd"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@can('permission', 'updateEvent')
<div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">Event</h4>
            </div>
            <form id="update_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="editid" id="editid">
                <div class="modal-body">
                    <div class="divfield">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="well">
                                    <label><input type="checkbox" name="edit_required_attendance" id="edit_required_attendance" value="1"> Required Attendance</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="well">
                                    <label><input type="checkbox" name="edit_event_type" id="edit_event_type" value="1"> Exclusive Event</label>
                                </div>
                            </div>
                        </div>
                        @if($department_status)
                        <div class="form-group">
                            <label for="">Department</label>
                            <select class="form-control selectpicker" data-live-search="true" name="department" id="edit_department">
                                <option selected disabled value="">Select Department</option>
                            </select>
                          </div>
                        @endif

                        <div class="form-group">
                            <label for="">Event</label>
                            <input type="text" class="form-control" name="edit_event" id="edit_event" placeholder="Event">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="">Attendees Capacity</label>
                                  <input type="number" min='0' class="form-control edit_event_schedules" name="edit_attendees_capacity" id="edit_attendees_capacity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="">Date of Event</label>
                                  <input type="text" class="form-control datetimepicker edit_event_schedules" name="edit_date_of_event" id="edit_date_of_event" max="9999-12-31">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="">Event Venue</label>
                          <input type="text" class="form-control edit_event_schedules" name="edit_venue" id="edit_venue" placeholder="Event venue">
                        </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Start Time of Event</label>
                                    <input type="time" class="form-control edit_event_schedules" name="edit_start_of_event" id="edit_start_of_event">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">End Time of Event</label>
                                    <input type="time" class="form-control edit_event_schedules" name="edit_end_of_event" id="edit_end_of_event">
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Time In Allowance <b>(Hours : Minutes)</b></label>
                                    <input type="time" onchange="checkTime('edit_start_of_event', 'edit_time_in_allowance', 'before')"  class="form-control edit_event_schedules" name="edit_time_in_allowance" id="edit_time_in_allowance">
                                    <small class="label label-danger time_in_error" style="display: none"> Set time Before start time</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Time Out Allowance <b>(Hours : Minutes)</b></label>
                                    <input type="time" onchange="checkTime('edit_end_of_event', 'edit_time_out_allowance', 'after')" class="form-control edit_event_schedules" name="edit_time_out_allowance" id="edit_time_out_allowance">
                                    <small class="label label-danger time_out_error" style="display: none"> Set time After end time</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="">Description</label>
                          <textarea class="form-control" name="edit_description" id="edit_description" rows="3"></textarea>
                        </div>
                        <div class="form-group" id="reason" style="display: none">
                          <label for="">Reason</label>
                          <textarea class="form-control" name="edit_reason" id="edit_reason" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btn-fill btn-wd" data-dismiss="modal" aria-hidden="true">Cancel</a>
                    <input type="submit" name="edit" class="btn btn-info btn-fill btn-wd"/>
                </div>
            </form>
        </div>
    </div>
</div>

@endcan

@endsection

@section('js')
    <script>
        let save_stat = false;
        $(document).ready(function(){

            datatable = $('#datatable').DataTable({
                "serverSide": true,
                "ajax":{
                    "url": '{{ route('event.findall') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "event" },
                    { "data": "date_event" },
                    { "data": "department" },
                    { "data": "capacity" },
                    { "data": "status2" },
                    { "data": "status" },
                    { "data": "action" }
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [0, 3, 4] }
                ]
            });

            @if(!Gate::check('permission', 'updateEvent') && !Gate::check('permission', 'deleteEvent'))
                datatable.column(4).visible(false);
            @endif

            @if($department_status)
             //get department
             $.ajax({
                url:'{{ route('department.findall2') }}',
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
            @endif

        });

        @can('permission', 'createEvent')
        $("#create_form").validate({
            rules: {
                event: {
                    required: true,
                    minlength: 3
                },
                venue: {
                    required: true,
                },
                description: {
                    minlength: 3
                },
                date_of_event: {
                    required: true
                },
                department: {
                    required: true
                },
                start_of_event: {
                    required: true
                },
                end_of_event: {
                    required: true
                },
                attendees_capacity: {
                    required: true
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Save new event?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {

                        if(save_stat){
                            swal("Error", 'Please provide valid inputs!', "error");
                            return false;
                        }

                        //show loader
                        processObject.showProcessLoader();

                        var formData = new FormData($("#create_form").get(0));

                        $.ajax({
                            url: "{{ route('event.store') }}",
                            type: "POST",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            success: function (response) {
                                if(response.success){

                                    $("#create_form")[0].reset();
                                    $('#create_modal').modal('hide');
                                    swal({
                                        title: "Success!",
                                        text: response.messages,
                                        type: "success"
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    datatable.ajax.reload( null, false );
                                }else{
                                    swal("Error", response.messages, "error");
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal("Error", errorThrown, "warning");
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });
        @endcan


        @can('permission', 'updateEvent')
        $("#update_form").validate({
            rules: {
                edit_venue: {
                    required: true,
                },
                edit_event: {
                    required: true,
                    minlength: 3
                },
                edit_description: {
                    minlength: 3
                },
                edit_date_of_event: {
                    required: true
                },
                edit_department: {
                    required: true
                },
                edit_start_of_event: {
                    required: true
                },
                edit_end_of_event: {
                    required: true
                },
                edit_attendees_capacity: {
                    required: true
                },
                edit_reason: {
                    minlength: 3,
                    required: true
                }
            },
            submitHandler: function (form) {

                var id = $('#editid').val();

                Swal.fire({
                    title: 'Update event?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {

                        if(save_stat){
                            swal("Error", 'Please provide valid inputs!', "error");
                            return false;
                        }

                        //show loader
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '/comprehensive/event/'+id,
                            type: "POST",
                            data: $("#update_form").serialize(),
                            dataType: "JSON",
                            success: function (response) {
                                if(response.success){
                                        $('#reason').hide();
                                        $("#update_form")[0].reset();
                                        $('#update_modal').modal('hide');
                                    swal({
                                        title: "Updated!",
                                        text: response.messages,
                                        type: "success"
                                    })
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    datatable.ajax.reload( null, false );
                                }else{
                                    swal("Error", response.messages, "error");
                                    //process loader false
                                    processObject.hideProcessLoader();
                                    datatable.ajax.reload( null, false );
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal("Error", errorThrown, "warning");
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });
        @endcan

        @can('permission', 'updateEvent')
        function edit(id)
        {
            $.ajax({
                url:'/comprehensive/event/'+id,
                type:'GET',
                dataType:'json',
                success:function(success){
                    $('#editid').val(success.id);
                    $('#edit_event').val(success.title);
                    $('#edit_venue').val(success.event_summary[0].venue);
                    $('#edit_department').val(success.dapartment_id);
                    $('#edit_date_of_event').val(success.event_summary[0].date_of_event);
                    $('#edit_description').val(success.description);
                    $('#edit_start_of_event').val(success.event_summary[0].time_of_event_from);
                    $('#edit_end_of_event').val(success.event_summary[0].time_of_event_to);
                    $('#edit_attendees_capacity').val(success.event_summary[0].attendees_capacity);
                    $('#edit_time_in_allowance').val(success.event_summary[0].time_in_allowance);
                    $('#edit_time_out_allowance').val(success.event_summary[0].time_out_allowance);
                    if(success.event_summary[0].exclusive == 1){
                        $('#edit_event_type').prop('checked', true);
                    }else{
                        $('#edit_event_type').prop('checked', false);
                    }

                    if(success.event_summary[0].required_attendance == 1){
                        $('#edit_required_attendance').prop('checked', true);
                    }else{
                        $('#edit_required_attendance').prop('checked', false);
                    }

                    $("#edit_department").val(success.department_id).selectpicker("refresh");
                    $('#update_modal').modal('show');
                }
            });
        }


        $('#edit_date_of_event').on('dp.change', function(){
            $('#reason').show(500);
        });

        $('.edit_event_schedules').on('change keyup', function(){
            $('#reason').show(500);
        });
        @endcan

        const checkTime = (element_from, element_to, stat)   => {
            let start = $('#'+element_from).val();
            let allowance = $('#'+element_to).val();


            if(stat == 'before'){
                if(allowance > start){
                    save_stat = true;
                    $('.time_in_error').show();
                }else{
                    save_stat = false;
                    $('.time_in_error').hide();
                }
            }else{
                if(allowance < start){
                    save_stat = true;
                    $('.time_out_error').show();
                }else{
                    save_stat = false;
                    $('.time_out_error').hide();
                }
            }
        }

        @if(Gate::check('permission', 'restoreEvent') || Gate::check('permission', 'deleteEvent'))
        function del(id)
        {
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
              if (result.value) {
                    //show loader
                    processObject.showProcessLoader();
                    // ajax delete data to database
                  $.ajax({
                    url : '/comprehensive/event/toggle/'+id,
                    type: "POST",
                    data:{ _token: "{{csrf_token()}}"},
                    dataType: "JSON",
                    success: function(response)
                    {
                        swal({
                            title: "Success!",
                            text: response.messages,
                            type: "success"
                        })
                        //process loader false
                        processObject.hideProcessLoader();
                        datatable.ajax.reload( null, false );
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        swal('error', errorThrown, 'error');
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });

              }
            });
        }
        @endif


        function openevent(id)
        {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                if (result.value) {
                    //show loader
                    processObject.showProcessLoader();
                    // ajax delete data to database
                    $.ajax({
                        url : '/comprehensive/event/toggleinout/'+id,
                        type: "PUT",
                        data:{ _token: "{{csrf_token()}}"},
                        dataType: "JSON",
                        success: function(response)
                        {
                            swal({
                                title: "Success!",
                                text: response.messages,
                                type: "success"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('error', errorThrown, 'error');
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    });

                }
            });
        }

        function closeevent(id)
        {
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes!'
            }).then((result) => {
              if (result.value) {
                    //show loader
                    processObject.showProcessLoader();
                    // ajax delete data to database
                    $.ajax({
                        url : '/comprehensive/event/closeevent/'+id,
                        type: "POST",
                        data:{ _token: "{{csrf_token()}}"},
                        dataType: "JSON",
                        success: function(response)
                        {
                            swal({
                                title: "Success!",
                                text: response.messages,
                                type: "success"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('error', errorThrown, 'error');
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    });

                }
            });
        }

    </script>
@endsection
