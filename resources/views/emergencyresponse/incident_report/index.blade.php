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
                                    <h4 class="card-title"><b>Incident Categry List</b></h4>
                                    <p class="category">Update | View | Delete Course</p>
                                </div>
                                <div class="col-lg-2">
                                    {{-- @can('permission','createCourse') --}}
                                    <a data-toggle="modal" data-toggle="modal" data-target="#addNewIncidentCategory" class="btn btn-primary pull-right">
                                        <i class="ti-plus"></i> Add new
                                    </a>
                                    {{-- @endcan --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th style="width: 200px;">Actions</th>
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

    <!-- Modal For Add -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="addNewIncidentCategory" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" style="width:80%">
           
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Create Incident Report</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto; background-color:#f7f7f7;">
                        <!-- Course Code -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Incident Location</h4>
                            </div>
                            <div class="card-content">
                                <!--Register Form -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Region -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Region *</label>
                                                <select class="form-control" data-live-search="true" id="region" name="region">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Province -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Province *</label>
                                                <select class="form-control" data-live-search="true" id="province" name="province">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>
                                    
                                        <!-- City -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>City *</label>
                                                <select class="form-control" data-live-search="true" id="city" name="city">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <!-- Barangay -->
                                            <div class="form-group">
                                                <label>Barangay *</label>
                                                <select class="form-control" data-live-search="true" id="barangay" name="barangay">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <!-- Barangay -->
                                            <div class="form-group">
                                                <label>Incident Area *</label><small>(e.g. street, block, lot, unit)</small>
                                                <textarea class="form-control" placeholder="Home Address" name="address" id="address"></textarea>
                                            </div>
                                        </div>                                    
                                    </div>
                                    
                                </div>
                            </div>
                        </div> 


                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Incident Report Details</h4>
                            </div>
                            <div class="card-content">
                                <!--Register Form -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Incident Discription</label>
                                                <input type="text" class="form-control border-input" name="" id="">
                                            </div>    
                                        </div>

                                        <div class="col-md-3">
                                            <!-- Barangay -->
                                            <div class="form-group">
                                                <label>Incident Category</label>
                                                <select class="form-control" data-live-search="true" id="" name="">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <!-- Barangay -->
                                            <div class="form-group">
                                                <label>Weather Condition</label>
                                                <select class="form-control" data-live-search="true" id="" name="">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Date of Incident</label>
                                                <input type="text" class="form-control datetimepicker" id="date_from" name="date_from" max="9999-12-31" placeholder="Date From">
                                            </div>
                                        </div>                   
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <!-- Barangay -->
                                            <div class="form-group">
                                                <label>Weather Condition</label>
                                                <select class="form-control" data-live-search="true" id="" name="">
                                                    <option value="" disabled selected>Select.....</option>
                                                </select>
                                            </div>
                                        </div>   
                                    </div>
                                    
                                </div>
                            </div>
                        </div> 
                        <!-- End Course Code -->

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Respondents Details</h4>
                            </div>
                            <div class="card-content">
                                <div class="divfield">
                                    <div class="table-responsive">
                                        <table class="table" id="tblRespondents">
                                            <thead>
                                                <th>Full Name <small style="color: red"><i>(firstname, lastname, middlename)<i></small></th>
                                                <th>Gender</th>
                                                <th>Contact</th>
                                                <th>Remarks</th>
                                                <th>Address</th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div style="display: flex">
                                                            <input type="text" class="form-control" name="name" id="name" placeholder="add manually..">
                                                            <a class="btn btn-primary btn-fill" style="border-bottom-left-radius: 0px !important; border-top-left-radius: 0px !important; " data-toggle="modal" data-target="#search_user"><i class="fa fa-search"></i> Add</a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="department" id="department">
                                                            <option value="" disabled selected>Select.....</option>
                                                            <option value="MALE" >Male</option>
                                                            <option value="FEMALE" >Female</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="grade_to[]" placeholder="Contact number" class="form-control"></td>
                                                    <td><input type="text" name="grade_to[]" placeholder="Remarks" class="form-control"></td>
                                                    <td><button class="btn btn-success" id="">add address</button></td>   
                                                    <td><a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields"><span class="btn-label"><i class="ti-plus"></i></span></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 


                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Incident Images</h4>
                            </div>
                            <div class="card-content">
                                <div class="kv-avatar-hint">
                                    <small><b>Note:</b> Maximum of 10 Images (Select file < 1500 KB)</small>
                                </div>
                                <div class="kv-avatar">
                                    <div class="file-loading">
                                        <input type="file" id="updates_image" name="updates_image[]" multiple>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Victim Profile</h4>
                            </div>
                            <div class="card-content">
                                <div class="divfield">
                                    <div class="table-responsive">
                                        <table class="table" id="tblVictimProfile">
                                            <thead>
                                                <th></th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="col-md-3">
                                                                    <label for="">Full Name <small style="color: red"><i>(firstname, lastname, middlename)</i></small></label>
                                                                    <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="">Gender</label>
                                                                    <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="">Contact Number</label>
                                                                    <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="">Full Name</label>
                                                                    <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <!-- Region -->
                                                                   <div class="col-md-3">
                                                                       <div class="form-group">
                                                                           <label>Region *</label>
                                                                           <select class="form-control" data-live-search="true" id="region1[]" name="region1[]">
                                                                               <option value="" disabled selected>Select.....</option>
                                                                           </select>
                                                                       </div>
                                                                   </div>
                                                                   <!-- Province -->
                                                                   <div class="col-md-3">
                                                                       <div class="form-group">
                                                                           <label>Province *</label>
                                                                           <select class="form-control" data-live-search="true" id="province1[]" name="province1[]">
                                                                               <option value="" disabled selected>Select.....</option>
                                                                           </select>
                                                                       </div>
                                                                   </div>
                                                               
                                                                   <!-- City -->
                                                                   <div class="col-md-3">
                                                                       <div class="form-group">
                                                                           <label>City *</label>
                                                                           <select class="form-control" data-live-search="true" id="city1[]" name="city1[]">
                                                                               <option value="" disabled selected>Select.....</option>
                                                                           </select>
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-3">
                                                                       <!-- Barangay -->
                                                                       <div class="form-group">
                                                                           <label>Barangay *</label>
                                                                           <select class="form-control" data-live-search="true" id="barangay1[]" name="barangay1[]">
                                                                               <option value="" disabled selected>Select.....</option>
                                                                           </select>
                                                                       </div>
                                                                   </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label>Victim injuries remarks *</label>
                                                                        <textarea class="form-control" placeholder="Remarks" name="address" id="address"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="">Victim Status</label>
                                                                    <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields2"><span class="btn-label"><i class="ti-plus"></i> Add Field </span></a></td>   
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </form>
                    <div class="modal-footer">
                        <button class="btn btn-success" id="save">Save</button>
                    </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Add -->

    <!-- Modal For Edit -->
    <div class="modal fade in" tabindex="-1" role="dialog" id="incidentModal">
        <div class="modal-dialog" role="document">
            <form id="editDescriptionForm" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Edit Incident Category</h4>
                    </div>
                    <!-- End Modal Header -->
                    <div class="modal-body">
                        <!-- Course Code -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_incident_category_desc">COURSE CODE:</label>
                                <input type="text" class="form-control" name="edit_incident_category_desc" id="edit_incident_category_desc"
                                    placeholder="Enter Course Code">
                            </div>
                        </div>
                        <!-- End Course Code -->
                    </div>
                    <input type="hidden" id="incident_id" name="incident_id">
                </form>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>      
    <!-- End Modal for Edit-->


    
@endsection

@section('js')
<script src="{{asset('assets/js/ph_address.js')}}"></script>
<script>//Start of Function for Address
    let myData = data;
    let region = '';
    let province = '';

    
    $(document).ready(function () {
        var incidentAddRegion = $('#region');
        var incidentAddProvince = $('#province');
        var incidentAddCity = $('#city');
        var incidentAddBarangay = $('#barangay');

        var victimAddRegion = $('#region1[]');
        var victimAddProvince = $('#province1[]');
        var victimAddCity = $('#city1[]');
        var victimAddBarangay = $('#barangay1[]');

        //incident address
        selectAddress(incidentAddRegion,incidentAddProvince,incidentAddCity,incidentAddBarangay,'province');
        //victim profile
        selectAddress(victimAddRegion,victimAddProvince,victimAddCity,victimAddBarangay,'province1');

    }); 

    const selectAddress = (region,province,city,barangay,   ) => {
        
        var $_select = region;
        $.each(myData, function(index, value) {
            $_select.append('<option value="' + index + '">' + value.region_name + '</option>');
        });
        region.on('change', function(){
            var selectedRegion = $(this). children("option:selected").val();
            region1 = selectedRegion;
            
            var select = $('#'+provinceName+'');
            var select_city = city;
            var select_brgy = barangay;
            select.empty();
            select_city.empty();
            select_brgy.empty();
            select.append('<option value="" disabled selected>Select.....</option>');
            select_city.append('<option value="" disabled selected>Select.....</option>');
            select_brgy.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[selectedRegion].province_list, function(index, value) {
                select.append('<option value="' + index + '">' + index + '</option>');
            });
        });
        province.on('change', function(){
            var selectedProvince = $(this). children("option:selected"). val();
            province = selectedProvince;
            var select = city;
            var select_brgy = barangay;
            select.empty();
            select_brgy.empty();
            select.append('<option value="" disabled selected>Select.....</option>');
            select_brgy.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region1].province_list[selectedProvince].municipality_list, function(index, value) {
                select.append('<option value="' + index + '">' + index + '</option>');
            });
        });
        city.on('change', function(){
            var selectedCity = $(this). children("option:selected"). val();
            var select = barangay;
            select.empty();
            select.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region1].province_list[province].municipality_list[selectedCity].barangay_list, function(index, value) {
                select.append('<option value="' + index + '">' + value + '</option>');
            });
        });
    }
</script>

<script>
    $(document).ready(function () {
      //add field of grading system in add form
     
        var tblRespondents_tr = $('#tblRespondents tbody tr');
        var tblRespondents_tbody = $('#tblRespondents tbody');
        var btnAddFields = $('#add_fields');
        var valueHTML = '<tr><td><div style="display: flex"><input type="text" class="form-control" name="name" id="name" placeholder="add manually.."><a class="btn btn-primary btn-fill" style="border-bottom-left-radius: 0px !important; border-top-left-radius: 0px !important; " data-toggle="modal" data-target="#search_user"><i class="fa fa-search"></i> Add</a></div></td><td><select class="form-control" name="department" id="department"><option value="" disabled selected>Select.....</option><option value="MALE" >Male</option><option value="FEMALE" >Female</option></select></td><td><input type="text" name="grade_to[]" placeholder="Contact number" class="form-control"></td><td><input type="text" name="grade_to[]" placeholder="Contact number" class="form-control"> <td><button class="btn btn-success" id="">add address</button></td></td><td><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-trash"></i></a></td></tr>';


        var tblRespondents_tr1 = $('#tblVictimProfile tbody tr');
        var tblRespondents_tbody1 = $('#tblVictimProfile tbody');
        var btnAddFields1 = $('#add_fields2');
        var valueHTML1 = `<tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <label for="">Full Name <small style="color: red"><i>(firstname, lastname, middlename)</i></small></label>
                                            <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">Gender</label>
                                            <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">Contact Number</label>
                                            <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">Full Name</label>
                                            <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Region -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Region *</label>
                                                    <select class="form-control" data-live-search="true" id="region1" name="region1">
                                                        <option value="" disabled selected>Select.....</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Province -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Province *</label>
                                                    <select class="form-control" data-live-search="true" id="province1" name="province1">
                                                        <option value="" disabled selected>Select.....</option>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <!-- City -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>City *</label>
                                                    <select class="form-control" data-live-search="true" id="city1" name="city1">
                                                        <option value="" disabled selected>Select.....</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <!-- Barangay -->
                                                <div class="form-group">
                                                    <label>Barangay *</label>
                                                    <select class="form-control" data-live-search="true" id="barangay1" name="barangay1">
                                                        <option value="" disabled selected>Select.....</option>
                                                    </select>
                                                </div>
                                            </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Victim injuries remarks *</label>
                                                <textarea class="form-control" placeholder="Remarks" name="address" id="address"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Victim Status</label>
                                            <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                                        </div>
                                    </div>
                                </div>
                                
                            </td>
                            <td>
                                <a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-trash"></i> Remove Fields</a>
                            </td> 
                        </tr>`;

        addFieldController(tblRespondents_tr,tblRespondents_tbody,btnAddFields,valueHTML,'tblRespondents');
        addFieldController(tblRespondents_tr1,tblRespondents_tbody1,btnAddFields1,valueHTML1,'tblRespondents');

        });



    const addFieldController = (tblRespondents_tr,tblRespondents_tbody,btnAddFields,valueHTML,valueTableName) => {

        btnAddFields.on('click', function(e){
            ctr = $('#'+valueTableName+' tbody tr').length;
            //console.log(ctr);
            
            if(ctr < 5){
                tblRespondents_tbody.append(valueHTML);
                ctr++;
            }else{
                swal('Warning!', 'Maximum of ' + $("#maxfield").val() + ' fields only!', 'warning');
            }
        });
            //remove field of grading system in add form
            tblRespondents_tbody.on("click", "#remove_field", function(e){ 
                e.preventDefault();
                $(this).parent().parent().remove();
                ctr--;
            });
    }
</script>

<script>
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            // "processing": false,
            // "serverSide": true,
            // "ajax":{
            //     "url": '{{ route('incident-category.findall') }}',
            //     "dataType": "json",
            //     "type": "POST",
            //     "data":{ _token: "{{csrf_token()}}"}
            // },
            // "columns": [
            //     { "data": "description" },
            //     { "data": "status" },
            //     { "data": "actions" },
            // ],
            // "columnDefs": [
            //     { "orderable": false, "targets": [ 2 ] }, 
            // ]	 	 
        });

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Letters only please");
    });

    //Add Course
    @can('permission', 'createIncidentCategory')
        $("#addIncidentForm").validate({
            rules: {
                IncidentCategoryDescription: {
                    required: true
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Register Now?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        //show loader
                        processObject.showProcessLoader();
                        $.ajax({
                            url: '{{ route('incident-category.store') }}',
                            type: "POST",
                            data: $('#addIncidentForm').serialize(),
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    $('#addNewIncidentCategory').modal('hide');
                                    $("#addIncidentForm")[0].reset();
                                    //process loader false
                                    swal.fire({
                                        title: "Save!",
                                        text: "Successfully!",
                                        type: "success"
                                    })
                                    datatable.ajax.reload( null, false );
                                    processObject.hideProcessLoader();
                                } else {
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: data.messages,
                                        type: "error"
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: errorThrown,
                                    type: "error"
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });
    @endcan

    //Update Incident
    @can('permission', 'updateIncidentCategory')
    $("#editDescriptionForm").validate({
        rules: {
            edit_incident_category_desc: {
                required: true
            }
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Update Data?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    //show loader
                    processObject.showProcessLoader();
                    $.ajax({
                        url: '/emergency/incident-category/' + $("#incident_id").val(),
                        type: "PUT",
                        data: $('#editDescriptionForm').serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                $('#incidentModal').modal('hide');
                                $("#editDescriptionForm")[0].reset();
                                swal({
                                    title: "Save!",
                                    text: "Successfully!",
                                    type: "success"
                                })
                                //process loader false
                                processObject.hideProcessLoader();
                                datatable.ajax.reload( null, false );
                            } else {
                                swal.fire({
                                    title: "Oops! something went wrong.",
                                    text: data.messages,
                                    type: "error"
                                });
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: errorThrown,
                                type: "error"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    });

                }
            })
        }
    }); 
    @endcan
    //Select Data to edit
   
    const edit = (id) =>{
        $.ajax({
            url: '/emergency/incident-category/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#incidentModal").modal("show");
                $("#edit_incident_category_desc").val(data.description);
                $("#incident_id").val(data.id);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    //Deactivate Data
    @can('permission', 'deleteIncidentCategory')
    const deactivate = (id) =>
    {
        Swal.fire({
            title: 'Deactivate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/emergency/incident-category/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deactivate Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
                                type: "error"
                            });
                            
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        
                        swal.fire({
                            title: "Oops! something went wrong.",
                            text: errorThrown,
                            type: "error"
                        });
                        
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });
            }
        })
    }
    @endcan

    @can('permission', 'restoreIncidentCategory')
    const activate = (id) =>{
        Swal.fire({
            title: 'Activate Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, activate it!'
        }).then((result) => {
            if (result.value) {
                //show loader
                processObject.showProcessLoader();
                $.ajax({
                   url: '/emergency/incident-category/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            });
                            //process loader false
                            processObject.hideProcessLoader();
                            datatable.ajax.reload( null, false );
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
                                type: "error"
                            });
                            
                            //process loader false
                            processObject.hideProcessLoader();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            title: "Oops! something went wrong.",
                            text: errorThrown,
                            type: "error"
                        });
                        
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });
            }
        })
    }
    @endcan

     //image content
     $("#updates_image").fileinput({
        overwriteInitial: false,
        showClose: false,
        showCaption: false,
        showUpload:false,
        browseLabel: 'Upload',
        removeLabel: 'Remove',
        browseIcon: '<i class="ti-folder"></i>',
        removeIcon: '<i class="ti-close"></i>',
        allowedFileExtensions: ["jpg","png"]
    });
</script>




<script>
    `<tr>
        <td>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <label for="">Full Name <small style="color: red"><i>(firstname, lastname, middlename)</i></small></label>
                        <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                    </div>
                    <div class="col-md-3">
                        <label for="">Gender</label>
                        <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                    </div>
                    <div class="col-md-3">
                        <label for="">Contact Number</label>
                        <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                    </div>
                    <div class="col-md-3">
                        <label for="">Full Name</label>
                        <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Region *</label>
                                <select class="form-control" data-live-search="true" id="region1" name="region1">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        <!-- Province -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Province *</label>
                                <select class="form-control" data-live-search="true" id="province1" name="province1">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>City *</label>
                                <select class="form-control" data-live-search="true" id="city1" name="city1">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- Barangay -->
                            <div class="form-group">
                                <label>Barangay *</label>
                                <select class="form-control" data-live-search="true" id="barangay1" name="barangay1">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Victim injuries remarks *</label>
                            <textarea class="form-control" placeholder="Remarks" name="address" id="address"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="">Victim Status</label>
                        <input type="text" class="form-control valid" name="" id="" placeholder="Enter full name" aria-invalid="false">
                    </div>
                </div>
            </div>
        </td>   
    </tr>`
</script>
@endsection
