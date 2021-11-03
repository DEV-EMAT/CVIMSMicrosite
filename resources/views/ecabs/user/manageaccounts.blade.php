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
                                <p class="category">View | Update | Deactivate Account </p>
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
                                        <th>Department</th>
                                        <th>Position</th>
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
                            <img class="img-responsive img-thumbnail" id="show_avatar"/>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">City:</label>
                                <p id="show_city"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Province:</label>
                                <p id="show_province"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Region:</label>
                                <p id="show_region"></p>
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

@can('permission', 'updateAccount')
<!--Modal for Edit -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="ti-close"></i>
				</button>
				<h4 class="modal-title"><span id="modalTitle"></span></h4>
			</div>
			<div class="modal-body">
                <form id="edit_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="kv-avatar-hint">
                                <small><b>Note:</b> Select file < 1500 KB</small>
                            </div>
                            <div class="kv-avatar img-responsive">
                                <div class="file-loading">
                                    <input type="file" class="avatar" name="avatar" id="avatar">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Last Name -->
                            <div class="form-group">
                                <label>Last Name *</label>
                                <input type="text" class="form-control border-input" placeholder="Last Name" name="edit_last_name" id="edit_last_name">
                            </div>
                            
                            <!-- First Name -->
                            <div class="form-group">
                                <label>First Name *</label>
                                <input type="text" class="form-control border-input" placeholder="First Name" name="edit_first_name" id="edit_first_name">
                            </div>
                            
                            <!-- Middle Name -->
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" class="form-control border-input" placeholder="Middle Name" name="edit_middle_name" id="edit_middle_name">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Suffix -->
                            <div class="form-group">
                                <label>Suffix</label><small>(Jr., Sr., etc.)</small>
                                <input type='text' class="form-control" id='edit_affiliation' name="edit_affiliation"
                                placeholder="Suffix" />
                            </div>

                            <!-- Date of Birth -->
                            <div class="form-group">
                                <label>Date Of Birth *</label>
                                <input type='text' class="form-control datetimepicker" id='edit_dob' name="edit_dob" max="9999-12-31"
                                placeholder="Date of Birth"/>
                            </div>


                            <!-- Sex -->
                            <div class="form-group">
                                <label>Sex *</label>
                                <select class="form-control" name="edit_sex" id="edit_sex">
                                    <option value="" disabled selected>Select.....</option>
                                    <option value="1">MALE</option>
                                    <option value="2">FEMALE</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <!-- Email -->
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address *</label>
                                <input type="email" class="form-control border-input" placeholder="Email"
                                    name="edit_email" id="edit_email">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Contact Number -->
                            <div class="form-group">
                                <label>Contact Number *</label>
                                <input type="text" class="form-control border-input" name="edit_contact" placeholder="Contact" id="edit_contact">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Telephone Number -->
                             <div class="form-group">
                                <label>Telephone Number</label>
                                <input type="text" class="form-control border-input" name="edit_telephone" placeholder="Telephone Number" id="edit_telephone">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Region -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Region *</label>
                                <select class="form-control" data-live-search="true" id="region" name="region">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        <!-- Province -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Province *</label>
                                <select class="form-control" data-live-search="true" id="province" name="province">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        <!-- City -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City *</label>
                                <select class="form-control" data-live-search="true" id="city" name="city">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Home Address -->
                            <div class="form-group">
                                <label>Home Adrress *</label><small>(e.g. street, block, lot, unit)</small>
                                <textarea class="form-control" placeholder="Home Address" name="edit_address" id="edit_address"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Barangay -->
                            <div class="form-group">
                                <label>Barangay *</label>
                                <select class="form-control" data-live-search="true" id="barangay" name="barangay">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <!-- Civil Status -->
                            <div class="form-group">
                                <label>Civil Status *</label>
                                <!-- "selectpicker" error in footer -->
                                <select class="form-control" data-live-search="true" name="edit_civil_status" id="edit_civil_status">
                                    <option value="0" disabled="" selected="">Select.....</option>
                                    <option value="1">Single</option>
                                    <option value="2">Married</option>
                                    <option value="3">Divorced</option>
                                    <option value="4">Separated</option>
                                    <option value="5">Widowed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Religion *</label>
                            <input type="text" class="form-control border-input" placeholder="Religion" id="edit_religion" name="edit_religion">
                        </div><br>

                        <div class="col-md-4">
                            <!-- Department -->
                            @isset($department_status)
                                @if($department_status == 1)
                                    <div class="well">
                                        <div class="form-group">
                                            <label>Department *</label>
                                            <select class="selectpicker form-control" data-live-search="true" name="department" id="department">
                                                <option value="" disabled selected>Select.....</option>
                                            </select>
                                        </div>
                                @endif
                            @endisset    
                                <!-- Access -->
                                <div class="form-group">
                                    <label>Level Of Access *</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="access" id="access" disabled>
                                        <option value="" disabled selected>Select.....</option>
                                    </select>
                                </div>
                            @isset($department_status)
                                @if($department_status == 1)
                                    </div>  
                                @endif
                            @endisset
                        </div>
                    </div>

                    <!-- Department id of non ecabs account -->
                    @isset($department_status)
                        @if($department_status == 0)
                            <input type="hidden" id="department_id" value="{{$department['id']}}">
                        @endif
                    @endisset 
                    
                    <input type="text" name="edit_id" id="edit_id" hidden>

                    <div class="text-center">
                        <input type="submit" name="submit" class="btn btn-info btn-fill btn-wd" />
                    </div>
                </form>
    		</div>
    	</div>
    </div>
</div>
<!--End Modal for Edit -->
@endcan

<!-- Modal For Delete -->
<div class="modal fade in" tabindex="-1" role="dialog" id="deleteModal">
    <div class="modal-dialog" role="document">
        <form id="delete_account_form" method="post">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">DELETE ACCOUNT</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <!-- Reason -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="reason">Reason for deleting account:</label>
                            <textarea type="text" class="form-control" name="reason" id="reason"
                                placeholder="Enter Reason"></textarea>
                        </div>
                    </div>
                    <!-- End Reason -->
                    
                </div>
                <input type="hidden" id="delete_id">
            </form>
            <div class="modal-footer">
                <button class="btn btn-success" id="save">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal for Delete -->

@endsection

@section('js')
<script src="{{asset('assets/js/ph_address.js')}}"></script>    
<script>//Start of Function for Address
    let myData = data;
    let region = province = city = '';
    $(document).ready(function () {

        var $select = $('#region');
        $.each(myData, function(index, value) {
            $select.append('<option value="' + index + '">' + value.region_name + '</option>');
        });
        
        $('#region').on('change', function(){
            var selectedRegion = $(this). children("option:selected"). val();
            region = selectedRegion;
            var $select = $('#province');
            var $select_city = $('#city');
            var $select_brgy = $('#barangay');
            $select.empty()
            $select_city.empty()
            $select_brgy.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $select_city.append('<option value="" disabled selected>Select.....</option>');
            $select_brgy.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[selectedRegion].province_list, function(index, value) {
                $select.append('<option value="' + index + '">' + index + '</option>');
            });
        });

        $('#province').on('change', function(){
            var selectedProvince = $(this).children("option:selected"). val();
            region = $("#region"). children("option:selected"). val();
            province = selectedProvince;
            var $select = $('#city');
            var $select_brgy = $('#barangay');
            $select.empty()
            $select_brgy.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $select_brgy.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region].province_list[selectedProvince].municipality_list, function(index, value) {
                $select.append('<option value="' + index + '">' + index + '</option>');
            });
        });

        $('#city').on('change', function(){
            var selectedCity = $(this).children("option:selected"). val();
            region = $("#region"). children("option:selected"). val();
            province = $("#province"). children("option:selected"). val();
            var $select = $('#barangay');
            $select.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region].province_list[province].municipality_list[selectedCity].barangay_list, function(index, value) {
                $select.append('<option value="' + index + '">' + value + '</option>');
            });
        });

        //get barangays
        $.ajax({
            url:'{{ route('barangay.findall2') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="barangay"]').append('<option value='+response[index].id+'>'+ response[index].barangay+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        })
        
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

        //get positions of department
        $("#department").change(function(){
            let department = $("#department").val();
            $("#access").prop('disabled',false); 
            $("#access").empty();
            $.ajax({
                url:'{{ route('access.findall3')}}',
                type: "POST",
                data: { _token: "{{csrf_token()}}", 'department_id':department},
                dataType: "JSON",
                success: function (response) {
                    for (let index = 0; index < response.length; index++){
                        $('[name="access"]').append('<option value='+response[index].id+'>'+ response[index].position+'</option>');
                        $('.selectpicker').selectpicker('refresh');
                    } 
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        });

        //department id of non ecabs account
        if($('#department_id') != null){     
            // let department = $("#department_id").val();
            // $.ajax({
            //     url:'{{ route('access.findall3')}}',
            //     type: "POST",
            //     data: { _token: "{{csrf_token()}}", 'department_id': department},
            //     dataType: "JSON",
            //     success: function (response) {
            //         for (let index = 0; index < response.length; index++)
            //         {
            //             $('[name="access"]').append('<option value='+response[index].id+'>'+ response[index].position+'</option>');
            //         }
            //         $("#access").val(access);
            //         $('.selectpicker').selectpicker('refresh');

            //     },
            //     error: function (jqXHR, textStatus, errorThrown) {
            //         alert(errorThrown);
            //     }
            // });
            // $("#access").prop('disabled', false); 
            // $('.selectpicker').selectpicker('refresh');
        }
        
        
        datatable = $('#datatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('account.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "department" },
                { "data": "position" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2, 3, 4 ] }, 
            ]
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
                let fullname = "";
                if(data[0].last_name) fullname += data[0].last_name + " ";
                if(data[0].affiliation) fullname += data[0].affiliation;
                fullname += ", ";
                if(data[0].first_name) fullname += data[0].first_name + " ";
                if(data[0].middle_name) fullname += data[0].middle_name + " ";
                $('#show_full_name').text(fullname);
                if(data[1].contact_number)$('#show_contact').text(data[1].contact_number);
                if(data[1].email)$('#show_email').text(data[1].email);
                if(data[0].telephone_number)$('#show_telephone').text(data[0].telephone_number);
                if(data[0].address)$('#show_address').text(data[0].address);
                if(data[0].date_of_birth)$('#show_dob').text(data[0].date_of_birth);

                if(data[6].region)$("#show_region").text(data[6].region);
                if(data[6].province)$("#show_province").text(data[6].province);
                if(data[6].city)$("#show_city").text(data[6].city);
                if(data[6].barangay)$("#show_barangay").text(data[6].barangay);
                
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

    @can('permission', 'updateAccount')
    //select data to edit
    const edit = (id) => {
        //remove error
        $('label.error').hide();
        $('.error').removeClass('error');

        $("#edit_form")[0].reset();
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url: '/account/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#avatar").fileinput("destroy");
                $("#avatar").fileinput({
                    overwriteInitial: true,
                    showClose: false,
                    showCaption: false,
                    showUpload: false,
                    browseLabel: 'Update',
                    removeLabel: 'Remove',
                    browseIcon: '<i class="ti-folder"></i>',
                    removeIcon: '<i class="ti-close"></i>',
                    defaultPreviewContent: '<img src="../../../images/'+data[0].image+'" alt="Your Avatar">',
                    allowedFileExtensions: ["jpg","png"]
                });
                
                if(data[1].id)$('#edit_id').val(data[1].id);
                if(data[0].last_name)$('#edit_last_name').val(data[0].last_name);
                if(data[0].first_name)$('#edit_first_name').val(data[0].first_name);
                if(data[0].middle_name)$('#edit_middle_name').val(data[0].middle_name);
                if(data[0].affiliation)$('#edit_affiliation').val(data[0].affiliation);
                if(data[1].email)$('#edit_email').val(data[1].email);
                if(data[1].contact_number)$('#edit_contact').val(data[1].contact_number);
                if(data[0].telephone_number)$('#edit_telephone').val(data[0].telephone_number);
                if(data[0].date_of_birth)$('#edit_dob').val(data[0].date_of_birth);
                if(data[0].religion) $('#edit_religion').val(data[0].religion);
                if(data[0].gender) $('#edit_sex').val(data[0].gender);
                if(data[0].civil_status) $('#edit_civil_status').val(data[0].civil_status);
                if(data[0].address)$('#edit_address').val(data[0].address);
                // if(data[0].barangay_id)$('#barangay').val(data[0].barangay_id);
                // $("#region").empty();
                $("#province").empty();
                $("#city").empty();
                $("#barangay").empty();
                
                if(data[6].region_id){
                    $("#region").val(data[6].region_id);
                    
                    //province combo box
                    $.each(myData[$("#region").val()].province_list, function(index, value) {
                        $("#province").append('<option value="' + index + '">' + index + '</option>');
                    });

                    if(data[6].province){
                        $("#province").val(data[6].province);

                        //city combo box
                        $.each(myData[$("#region").val()].province_list[$("#province").val()].municipality_list, function(index, value) {
                            $("#city").append('<option value="' + index + '">' + index + '</option>');
                        });
                    }

                    if(data[6].city){
                        $("#city").val(data[6].city);

                        //barangay combo box
                        $.each(myData[$("#region").val()].province_list[$("#province").val()].municipality_list[$("#city").val()].barangay_list, function(index, value) {
                            $("#barangay").append('<option value="' + index + '">' + value + '</option>');
                        });
                    }
                    if(data[6].barangay_id)$("#barangay").val(data[6].barangay_id);  
                }
                // if(data[6].barangay)$("#barangay").val(data[6].barangay);
                getaccess(data[3], data[4]);
                if(data[0].id)$("#modalTitle").text("Edit Profile");  
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

    //update data
    $("#edit_form").validate({
        rules: {
            edit_first_name: {
                minlength: 2,
                required: true
            },
            edit_last_name: {
                minlength: 2,
                required: true
            },
            edit_dob: {
                required: true
            },
            edit_sex: {
                required: true
            },
            edit_contact: {
                required: true,
                phoneno: true
            },
            edit_email: {
                required: true,
                email: true
            },
            edit_address: {
                required: true
            },
            barangay: {
                required: true
            },
            edit_religion: {
                required: true
            },
            edit_civil_status: {
                required: true
            },
            applicant_status: {
                required: true
            },
            access: {
                required: true
            },
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Update Now?',
                text: "Update data!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    var id = $('#edit_id').val();

                    var formData = new FormData($("#edit_form").get(0));
                    formData.append('txtRegion', $("#region :selected").text());
                    formData.append('txtBarangay', $("#barangay :selected").text());
                    formData.append('department_id', $("#department_id").val());
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: '/account/'+ id,
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Updated Successfully !',
                                    type: 'success'
                                }).then(function () {
                                    $("#edit_modal").modal('hide');
                                    datatable.ajax.reload( null, false );
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            } else {
                                //process loader false
                                processObject.hideProcessLoader();
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: data.messages,
                                    type: "error"
                                })
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

    
    //get access in edit form
    const getaccess = (department, access) =>{
        $("#department").val(department);

        let departmentId;
        if($("#department").val() != ""){
            departmentId = $("#department").val();
        }
        if($("#department_id").val() > 1){
            departmentId = $("#department_id").val();
        }
        
        $("#access").prop('disabled', false); 
        $("#access").empty();
        $.ajax({
            url:'{{ route('access.findall3')}}',
            type: "POST",
            data: { _token: "{{csrf_token()}}", 'department_id': departmentId},
            dataType: "JSON",
            success: function (response) {
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="access"]').append('<option value='+response[index].id+'>'+ response[index].position+'</option>');
                }
                $("#access").val(access);
                $('.selectpicker').selectpicker('refresh');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    } 
    @endcan
    
    @can('permission', 'deleteAccount')
    //deactivate Data
    const deactivate = (id)=>{
        $("#delete_id").val(id);
        $("#reason").val("");
        $("#deleteModal").modal("show"); 
    }

   //delete form
   $("#delete_account_form").validate({
        rules: {
            reason: {
                required: true
            },
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Delete Data?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //process loader true
                    // var formData = new FormData($("#delete_account_form").get(0));
                    processObject.showProcessLoader();
                    $.ajax({
                    url: '/account/update/status/' + $("#delete_id").val(),
                    data:$('#delete_account_form').serialize(),
                        type: "POST",
                        success: function (data) {
                            if (data.success) {
                                swal({
                                    title: "Save!",
                                    text: "Deleted Successfully!",
                                    type: "success"
                                }).then(function () {
                                    datatable.ajax.reload( null, false);
                                });
                                
                                $("#deleteModal").modal("hide");
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
    @endcan
    
    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(09|\+639)\d{9}$/);
    }, "<br />Please specify a valid phone number");
</script>
@endsection