@extends('layouts.app2')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Add Account</b></h4>
                    </div>
                    <div class="card-content">
                        <!--Register Form -->
                        @can('permission', 'createGuestAccount')
                        <form id="register_form" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-sm-3 text-center">
                                    <div class="kv-avatar-hint">
                                        <small><b>Note:</b> Select file < 1500 KB</small>
                                    </div>
                                    <div class="kv-avatar">
                                        <div class="file-loading">
                                            <input type="file" name="avatar" id="avatar">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-md-offset-1">
                                    <!-- Last Name -->
                                    <div class="form-group">
                                        <label>Last Name *</label>
                                        <input type="text" class="form-control border-input" placeholder="Last Name" name="last_name" id="last_name">
                                    </div>
                                    
                                    <!-- First Name -->
                                    <div class="form-group">
                                        <label>First Name *</label>
                                        <input type="text" class="form-control border-input" placeholder="First Name" name="first_name" id="first_name">
                                    </div>
                                    
                                    <!-- Middle Name -->
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control border-input" placeholder="Middle Name" name="middle_name" id="middle_name">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Suffix -->
                                    <div class="form-group">
                                        <label>Suffix</label><small>(Jr., Sr., etc.)</small>
                                        <input type='text' class="form-control" id='affiliation' name="affiliation"
                                        placeholder="Suffix" id="affiliation" />
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="form-group">
                                        <label>Date Of Birth *</label>
                                        <input type='text' class="form-control datetimepicker" id='dob' name="dob" max="9999-12-31"
                                        placeholder="Date of Birth"/>
                                    </div>

                                    <!-- Sex -->
                                    <div class="form-group">
                                        <label>Sex *</label>
                                        <select class="selectpicker form-control" name="sex" id="sex">
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
                                            name="email" id="email">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Contact Number -->
                                    <div class="form-group">
                                        <label>Contact Number *</label>
                                        <input type="number" class="form-control border-input" name="contact" placeholder="Contact" id="contact">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Telephone Number -->
                                     <div class="form-group">
                                        <label>Telephone Number</label>
                                        <input type="text" class="form-control border-input" name="telephone" placeholder="Telephone Number" id="telephone">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- <div class="col-md-8"> -->
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
                                    <div class="col-md-8">
                                        <!-- Home Address -->
                                        <div class="form-group">
                                            <label>Home Adrress *</label><small>(e.g. street, block, lot, unit)</small>
                                            <textarea class="form-control" placeholder="Home Address" name="address" id="address"></textarea>
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
                                    
                                    
                                    <div class="col-md-6">
                                        <!-- Civil Status -->
                                        <div class="form-group">
                                            <label>Civil Status *</label>
                                            <!-- "selectpicker" error in footer -->
                                            <select class="selectpicker form-control" data-live-search="true" name="civil_status" id="civil_status">
                                                <option value="0" disabled="" selected="">Select.....</option>
                                                <option value="1">Single</option>
                                                <option value="2">Married</option>
                                                <option value="3">Divorced</option>
                                                <option value="4">Separated</option>
                                                <option value="5">Widowed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Religion -->
                                    <div class="col-md-6">
                                        <label>Religion *</label>
                                        <input type="text" class="form-control border-input" placeholder="Religion" id="religion" name="religion">
                                    </div>
                                <!-- </div> -->
                            </div>

                            <!-- Department id  of non ecabs account -->
                            @isset($department_status)
                                @if($department_status == 0)
                                    <input type="hidden" id="department_id" value="{{$department['id']}}">
                                @endif
                            @endisset 
                            
                        <div class="text-center">
                            <input type="submit" name="submit" id="submit" class="btn btn-info btn-fill btn-wd" />
                        </div>
                        </form>
                        @endcan
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
                $("#access").prop('disabled',false); 
                $("#access").empty();
                $.ajax({
                    url:'{{ route('access.findall3') }}',
                    type: "POST",
                    data: { _token: "{{csrf_token()}}", 'department_id':$("#department").val()},
                    dataType: "JSON",
                    success: function (response) {
                        for (let index = 0; index < response.length; index++)
                        {
                            $('[name="access"]').append('<option value='+response[index].id+'>'+ response[index].position+'</option>');
                            $('.selectpicker').selectpicker('refresh');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            });
            
            
            @can('permission', 'createGuestAccount')
            //create account
            $("#register_form").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength:3
                    },
                    middle_name: {
                        minlength:2
                    },
                    last_name: {
                        required: true,
                        minlength:2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    dob: {
                        required: true
                    },
                    sex: {
                        required: true
                    },
                    contact: {
                        required: true,
                        phoneno: true
                    },
                    region:{
                        required: true
                    },
                    province:{
                        required: true
                    },
                    city:{
                        required: true
                    },
                    address: {
                        required: true,
                        minlength:3
                    },
                    barangay: {
                        required: true
                    },
                    civil_status:{
                        required:true
                    },
                    religion:{
                        required:true
                    },
                },
                submitHandler: function (form) {
                    Swal.fire({
                        title: 'Save new account?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        if (result.value) {
                            
                            var formData = new FormData($("#register_form").get(0));
                                formData.append('txtRegion', $("#region :selected").text());
                                formData.append('txtBarangay', $("#barangay :selected").text());
                            //process loader true
                            processObject.showProcessLoader();
                            $.ajax({
                                url: "{{ route('guest-account.store')}}",
                                type: "POST",
                                data: formData,
                                cache:false,
                                contentType: false,
                                processData: false,
                                dataType: "JSON",
                                success: function (response) {
                                    if(response.success){
                                        swal({
                                            title: "Success!",
                                            text: response.messages,
                                            type: "success"
                                        }).then(function() {
                                            location.reload();
                                        });
                                        //process loader false
                                        processObject.hideProcessLoader();
                                    }else{
                                        swal.fire({
                                            title: "Oops! something went wrong.",
                                            text: response.messages,
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
        });

        jQuery.validator.addMethod("phoneno", function (phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(09|\+639)\d{9}$/);
        }, "<br />Please specify a valid phone number");

        //image content
        $("#avatar").fileinput({
            overwriteInitial: true,
            showClose: false,
            showCaption: false,
            showUpload:false,
            browseLabel: 'Upload',
            removeLabel: 'Remove',
            browseIcon: '<i class="ti-folder"></i>',
            removeIcon: '<i class="ti-close"></i>',
            defaultPreviewContent: '<img src="../../../images/ecabs/profiles/default-avatar.png" alt="Your Avatar">',
            allowedFileExtensions: ["jpg","png"]
        });
    </script>
@endsection
