@extends('layouts.app2')
@section('content')
@auth
  <?php session()->flash('firstlogin', '0') ?>
@endauth

<!-- Profile -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="modal-title">Profile Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <form id="update_account_form" >
                                @method('PUT')
                                @csrf
                                <input type="hidden" id="edit_id" name="edit_id">
                                <div class="col-sm-4 text-center">
                                    <div class="kv-avatar-hint">
                                        <small><b>Note:</b> Select file < 1500 KB</small> 
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input type="file" name="avatar" id="avatar">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>First Name *</label>
                                                        <input type="text" class="form-control border-input" id="edit_first_name" name="edit_first_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Last Name *</label>
                                                        <input type="text" class="form-control border-input" id="edit_last_name" name="edit_last_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Middle Name *</label>
                                                        <input type="text" class="form-control border-input" id="edit_middle_name" name="edit_middle_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Suffix</label>
                                                        <input type="text" class="form-control border-input" id="edit_suffix" name="edit_suffix">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email Address *</label>
                                                        <input type="text" class="form-control border-input" id="edit_email" readonly name="edit_email">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Contact *</label>
                                                        <input type="text" class="form-control border-input" id="edit_contact" name="edit_contact">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Telephone</label>
                                                        <input type="text" class="form-control border-input" id="edit_telephone" name="edit_telephone">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Date of Birth *</label>
                                                        <input type="text" class="form-control border-input datetimepicker" id="edit_dob" name="edit_dob">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- Civil Status -->
                                                    <div class="form-group">
                                                        <label>Civil Status *</label>
                                                        <!-- "selectpicker" error in footer -->
                                                        <select class="selectpicker form-control" data-live-search="true" name="edit_civil_status" id="edit_civil_status">
                                                            <option value="0" disabled="" selected="">Select.....</option>
                                                            <option value="1">Single</option>
                                                            <option value="2">Married</option>
                                                            <option value="3">Divorced</option>
                                                            <option value="4">Separated</option>
                                                            <option value="5">Widowed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Sex *</label>
                                                        <select class="form-control" name="edit_sex" id="edit_sex">
                                                            <option value="" disabled selected>Select.....</option>
                                                            <option value="1">MALE</option>
                                                            <option value="2">FEMALE</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Religion *</label>
                                                        <input type="text" class="form-control border-input" id="edit_religion" name="edit_religion">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group text-center">
                                                        <br>
                                                        <a data-toggle="modal" id="add" data-toggle="modal" data-target="#modal_password" class="btn btn-primary">
                                                            <span>Update Account Password<span>
                                                        </a>
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

                                            <div class="text-center">
                                                <input type="submit" name="edit" value="UPDATE INFORMATION" class="btn btn-info btn-fill btn-wd"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{asset('assets/js/ph_address.js')}}"></script>    
<script>//Start of Function for Address
    let myData = data;
    let region = province = city = '';
    $(document).ready(function () {

        //get all region
        var $select = $('#region');
        $.each(myData, function(index, value) {
            $select.append('<option value="' + index + '">' + value.region_name + '</option>');
        });
        
        //get province of the selected region 
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

        //get cities of the selected province 
        $('#province').on('change', function(){
            var selectedProvince = $(this). children("option:selected"). val();
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

        //get barangay of the selected city
        $('#city').on('change', function(){
            var selectedCity = $(this). children("option:selected"). val();
            region = $("#region"). children("option:selected"). val();
            province = $("#province"). children("option:selected"). val();
            var $select = $('#barangay');
            $select.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region].province_list[province].municipality_list[selectedCity].barangay_list, function(index, value) {
                $select.append('<option value="' + index + '">' + value + '</option>');
            });
        });

        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url:'/account/'+ {{ \Auth::user()->id }},
            type:'GET',
            dataType:'JSON',
            success:function(response){
                //image content
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
                    defaultPreviewContent: '<img src="../../../images/'+response[0].image+'" alt="Your Avatar">',
                    allowedFileExtensions: ["jpg","png"]
                });
                
                $('#edit_id').val(response[1].id);
                $('#edit_first_name').val(response[0].first_name.toUpperCase());
                $('#edit_last_name').val(response[0].last_name.toUpperCase());
                $('#edit_middle_name').val(response[0].middle_name.toUpperCase());
                $('#edit_suffix').val(response[0].affiliation.toUpperCase());
                $('#edit_email').val(response[1].email);
                $('#edit_contact').val(response[1].contact_number);
                $('#edit_telephone').val(response[0].telephone_number);
                $('#edit_dob').val(response[0].date_of_birth);
                $('#edit_religion').val(response[0].religion);
                $('#edit_sex').prop('selectedIndex', (response[0].gender == 1)? 1:2);
                $('#edit_civil_status').val(response[0].civil_status);
                $("#edit_address").val(response[0].address);
                // $('#edit_civil_status').prop('selectedIndex', response[0].civil_status);
                
                
                $("#region").val(response[6].region_id);
                
                //province combo box
                $.each(myData[$("#region").val()].province_list, function(index, value) {
                    $("#province").append('<option value="' + index + '">' + index + '</option>');
                });
                $("#province").val(response[6].province);;

                //city combo box
                $.each(myData[$("#region").val()].province_list[$("#province").val()].municipality_list, function(index, value) {
                    $("#city").append('<option value="' + index + '">' + index + '</option>');
                });
                $("#city").val(response[6].city);

                //barangay combo box
                $.each(myData[$("#region").val()].province_list[$("#province").val()].municipality_list[$("#city").val()].barangay_list, function(index, value) {
                    $("#barangay").append('<option value="' + index + '">' + value + '</option>');
                });
                $("#barangay").val(response[6].barangay_id);

                
                $('.selectpicker').selectpicker('refresh');
                //process loader false
                processObject.hideProcessLoader();
            }
        });

        //update profile
        $("#update_account_form").validate({
            rules: {
                first_name: {
                    minlength: 2,
                    required: true
                },
                middle_name: {
                    minlength: 2,
                    required: true
                },
                last_name: {
                    minlength: 2,
                    required: true
                },
                edit_bob: {
                    required: true
                },
                sex: {
                    required: true
                },
                contact: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                address: {
                    required: true
                },
                barangay: {
                    required: true
                },
                religion: {
                    required: true
                },
                civil_status: {
                    required: true
                }
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

                        var formData = new FormData($("#update_account_form").get(0));
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
        });

    });
</script>
@endsection