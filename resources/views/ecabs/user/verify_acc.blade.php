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
                                <h4 class="card-title"><b>Pre-registration List</b></h4>
                                <p class="category">View | Verify Account </p>
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
                                        <th>Last Name</th>
                                        <th>First Name</th>
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
                            <div class="col-md-12">
                                <img id="show_avatar1" class="cstm-avtr"/>
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
                        </div>
                    </div>
                    
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

@endsection

@section('js')
<script src="{{asset('assets/js/ph_address.js')}}"></script>    
<script>//Start of Function for Address
    let myData = data;
    let region = '';
    let province = '';
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
            var selectedProvince = $(this). children("option:selected"). val();
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
            var selectedCity = $(this). children("option:selected"). val();
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
        
        datatable = $('#datatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('pre-register.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "last_name" },
                { "data": "first_name" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 2, 3] }, 
            ]
        });
    });

    //select data to edit
    const edit = (id) => {
        //remove error
        $('label.error').hide();
        $('.error').removeClass('error');

        $("#edit_form")[0].reset();
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url: '/pre-register/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#show_avatar1').attr('src','../images/ecabs/profiles/default-avatar.png');
                
                if(data.id)$('#edit_id').val(data.id);
                if(data.last_name)$('#edit_last_name').val(data.last_name);
                if(data.first_name)$('#edit_first_name').val(data.first_name);
                if(data.middle_name)$('#edit_middle_name').val(data.middle_name);
                if(data.affiliation)$('#edit_affiliation').val(data.affiliation);
                if(data.email)$('#edit_email').val(data.email);
                if(data.contact_number)$('#edit_contact').val(data.contact_number);
                if(data.telephone_number)$('#edit_telephone').val(data.telephone_number);
                if(data.date_of_birth)$('#edit_dob').val(data.date_of_birth);
                if(data.religion) $('#edit_religion').val(data.religion);
                if(data.gender) $('#edit_sex').val(data.gender);
                if(data.civil_status) $('#edit_civil_status').val(data.civil_status);
                if(data.address)$('#edit_address').val(data.address);
                $("#province").empty();
                $("#city").empty();
                $("#barangay").empty();
                
                if(data.region_id){
                    $("#region").val(data.region_id);
                    
                    //province combo box
                    $.each(myData[$("#region").val()].province_list, function(index, value) {
                        $("#province").append('<option value="' + index + '">' + index + '</option>');
                    });

                    if(data.province){
                        $("#province").val(data.province);

                        //city combo box
                        $.each(myData[$("#region").val()].province_list[$("#province").val()].municipality_list, function(index, value) {
                            $("#city").append('<option value="' + index + '">' + index + '</option>');
                        });
                    }

                    if(data.city){
                        $("#city").val(data.city);

                        //barangay combo box
                        $.each(myData[$("#region").val()].province_list[$("#province").val()].municipality_list[$("#city").val()].barangay_list, function(index, value) {
                            $("#barangay").append('<option value="' + index + '">' + value + '</option>');
                        });
                    }
                    if(data.barangay_id)$("#barangay").val(data.barangay_id);

                    
                }
                if(data.id)$("#modalTitle").text("Edit Profile");  
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
                phoneno: true
            },
            edit_email: {
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
                title: 'Verify Now?',
                text: "Verifying data!",
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
                        url: '/pre-register/'+ id,
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Verified Successfully !',
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
    
    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(09|\+639)\d{9}$/);
    }, "<br />Please specify a valid phone number");
</script>
@endsection