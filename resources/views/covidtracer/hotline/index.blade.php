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
                            <div class="col-md-10">
                                <h4 class="card-title"><b>Emergency Hotline List</b>
                                <p class="category">Create | Update | Remove Data</p>
                            </div>
                            <div class="col-md-2 text-right">
                                @can('permission', 'createHotline')
                                <div data-toggle="modal" data-target="#create_modal">
                                    <a id="add" data-toggle="tooltip" class="btn btn-primary" title="Click here to add new Hotline">
                                        <i class="ti-plus"></i> Add new
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>                         
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-smr" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Barangay</th>
                                        <th>City</th>
                                        <th>Province</th>
                                        <th>Region</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th style="width: 400px;">Actions</th>
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

@can('permission', 'createHotline')
<div class="modal fade in" tabindex="-1" role="dialog" id="create_modal">
    <div class="modal-dialog" role="document">
        <form id="create_form">
            @csrf
            @method('POST')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Add Emergency Hotline</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <blockquote>
                                <p>Fill-up this form to create a new <b>Hotline</b>.</p>
                            </blockquote>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="fullname">NAME:</label>
                            <input type="text" class="form-control" name="fullname" id="fullname"
                                placeholder="Enter fullname">
                        </div>
                    </div>
                    <div class="row">     
                        <div class="col-md-12">                   
                            <label for="zipcode">CONTACT</label>
                        </div>
                    </div>
                    <div class="row" id="contactDiv">
                        <div class="group">
                            <div class="form-group col-md-10">
                            <input type="number" class="form-control" name="contact[]" id="contact[]" placeholder="Enter contact number">
                            </div>
                            <div class="form-group col-md-2">
                                <a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields"><span class="btn-label"><i class="ti-plus"></i></span></a>
                            </div>
                        </div>
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>REGION:</label>
                                <select class="form-control" data-live-search="true" id="region" name="region">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>PROVINCE:</label>
                                <select class="form-control" data-live-search="true" id="province" name="province">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>CITY:</label>
                                <select class="form-control" data-live-search="true" id="city" name="city">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>BARANGAY:</label>
                                <select class="form-control" data-live-search="true" id="barangay" name="barangay">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="province">ADDRESS</label>
                            <textarea class="form-control" name="address" id="address"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" id="save">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@can('permission', 'updateHotline')
<div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <form id="update_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-body">
                    <div class="alert alert-success" data-notify="container">
                        <span data-notify="message" id="message">This will update hotline</span>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="fullname">FULLNAME:</label>
                            <input type="text" class="form-control" name="editFullname" id="editFullname"
                                placeholder="Enter fullname">
                        </div>
                    </div>
                    <label for="zipcode">CONTACT</label>
                    <div class="row" id="editContactDiv">
                       {{-- <div class="form-group col-md-10">
                           <input type="number" class="form-control" name="editContact[]" id="editContact[]" placeholder="Enter contact number">
                       </div>
                       <div class="form-group col-md-2">
                            <a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields2"><span class="btn-label"><i class="ti-plus"></i></span></a>
                        </div> --}}
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>REGION:</label>
                                <select class="form-control" data-live-search="true" id="editRegion" name="editRegion">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>PROVINCE:</label>
                                <select class="form-control" data-live-search="true" id="editProvince" name="editProvince">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>CITY:</label>
                                <select class="form-control" data-live-search="true" id="editCity" name="editCity">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>BARANGAY:</label>
                                <select class="form-control" data-live-search="true" id="editBarangay" name="editBarangay">
                                    <option value="" disabled selected>Select.....</option>
                                </select>
                            </div>
                       </div>
                   </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="province">ADDRESS</label>
                            <textarea class="form-control" name="editAddress" id="editAddress"></textarea>
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

<script src="{{asset('assets/js/ph_address.js')}}"></script>    
<script>//Start of Function for Address
    let myData = data;
    let region = '';
    let province = '';
    let counter = 1;
    $(document).ready(function () {

        var $select = $('#region');
        $.each(myData, function(index, value) {
            $select.append('<option value="' + index + '">' + value.region_name + '</option>');
        });
        var $select = $('#editRegion');
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
        
        $('#editRegion').on('change', function(){
            var selectedRegion = $(this). children("option:selected"). val();
            region = selectedRegion;
            var $select = $('#editProvince');
            var $select_city = $('#editCity');
            var $select_brgy = $('#editBarangay');
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

        $('#editProvince').on('change', function(){
            var selectedProvince = $(this). children("option:selected"). val();
            province = selectedProvince;
            var $select = $('#editCity');
            var $select_brgy = $('#editBarangay');
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

        $('#editCity').on('change', function(){
            var selectedCity = $(this). children("option:selected"). val();
            var $select = $('#editBarangay');
            $select.empty()
            $select.append('<option value="" disabled selected>Select.....</option>');
            $.each(myData[region].province_list[province].municipality_list[selectedCity].barangay_list, function(index, value) {
                $select.append('<option value="' + index + '">' + value + '</option>');
            });
        });

        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('covidtracer.hotline.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "name" },
                { "data": "address" },
                { "data": "barangay" },
                { "data": "city" },
                { "data": "province" },
                { "data": "region" },
                { "data": "contact" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 1, 2, 3, 4, 5, 6, 7, 8] }, 
            ]	 	 
        });
        
        @if(!Gate::check('permission', 'updateHotline') && !Gate::check('permission', 'deleteHotline') && !Gate::check('permission', 'restoreHotline'))
            datatable.column(8).visible(false);
        @endif
        
        //add field of hotline in add form
        $('#add_fields').on('click', function(e){
            // counter = $('#contactDiv').length;
            const input = document.getElementsByName('grade_from[]'); 
            let min=0; 
            for (var index = 0; index < input.length; index++) { 
                if(input[index].value != "" && index == input.length-1){
                    min = input[index].value - 1;
                }
            }

            e.preventDefault();
            if(counter < 5){
                $('#contactDiv').append(`<div><br><div class="form-group col-md-10">
                    <input type="number" class="form-control" name="contact[]" id="contact[]" placeholder="Enter contact number"></div>
                    <div class="form-group col-md-2"><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field">
                    <i class="fa fa-trash"></i></a></div><div>`);
                counter++;
            }else{
                swal('Warning!', 'Maximum of 5 contacts only!', 'warning');
            }
        });        
        
        
    });

    //add field of contacts in edit form
    const add_field = () => {
        let ctr = $('#editContactDiv input').length;

        if(ctr < 5){
            $('#editContactDiv').append('<div><br><div class="form-group col-md-10"><input type="number" class="form-control" name="editContact[]" id="editContact[]" placeholder="Enter contact number"></div><div class="form-group col-md-2"><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-trash"></i></a></div></div>');
            ctr++;
        }else{
            swal('Warning!', 'Maximum of 5 fields only!', 'warning');
        }
    }   

    //remove field of contacts in add form
    $('#contactDiv').on("click", "#remove_field", function(e){ 
        e.preventDefault();
        $(this).parent().parent().remove();
        counter--;
    });

    //remove field of contacts in add form
    $('#editContactDiv').on("click", "#remove_field", function(e){ 
        e.preventDefault();
        $(this).parent().parent().remove();
    });
    
    @can('permission', 'createHotline')
    $("#create_form").validate({
        rules: {
            fullname: {
                required: true,
                minlength: 3
            },
            address: {
                required: true,
                minlength: 3
            },
            contact: {
                required: true,
                phoneno: true,
            },
            region: {
                required: true,
            },
            province: {
                required: true,
            },
            city: {
                required: true,
            },
            barangay: {
                required: true,
            },
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Save new hotline?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    
                    var formData = new FormData($("#create_form").get(0));
                    formData.append('txtRegion', $("#region :selected").text());
                    formData.append('txtBarangay', $("#barangay :selected").text());
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: "{{ route('emergency-hotline.store') }}",
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
                                    $("#create_form")[0].reset();
                                    $('#create_modal').modal('hide');
                                    datatable.ajax.reload( null, false);
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

    @can('permission', 'updateHotline')
    $("#update_form").validate({
        rules: {
            editFullname: {
                required: true,
                minlength: 3
            },
            editAddress: {
                required: true,
                minlength: 3
            },
            editContact: {
                required: true,
                phoneno: true,
            },
            editRegion: {
                required: true,
            },
            editProvince: {
                required: true,
            },
            editCity: {
                required: true,
            },
            editBarangay: {
                required: true,
            },
        },
        submitHandler: function (form) {   

            var id = $('#edit_id').val();
            Swal.fire({
                title: 'Update hotline?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {      
                if (result.value) {
                    // var formData = new FormData($("#update_form").get(0));
                        // formData.append('txtRegion', $("#editRegion :selected").text());
                        // formData.append('txtBarangay', $("#editBarangay :selected").text());
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: '/covidtracer/emergency-hotline/'+id,
                        type: "POST",
                        data: $("#update_form").serialize() + "&txtRegion=" + $("#editRegion :selected").text() + "&txtBarangay=" + $("#editBarangay :selected").text(),
                        dataType: "JSON",
                        success: function (response) {
                            if(response.success){
                                swal({
                                    title: "Updated!",
                                    text: response.messages,
                                    type: "success"
                                }).then(function() {
                                    $("#update_form")[0].reset();
                                    $('#update_modal').modal('hide');
                                    datatable.ajax.reload( null, false);
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

    const edit = (id) => {
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url:'/covidtracer/emergency-hotline/'+id,
            type:'GET',
            dataType:'json',
            success:function(success){
                $('#edit_id').val(success[0].id);
                $('#editContactDiv').children().remove();
                    
                $('#editFullname').val(success[0].name);
                console.log(success[2]);
                // $('#editContact').val(success[0].contact);
                for(var index=0; index < success[2].length; index++){
                    let rowData = `<div><div class="form-group col-md-10"><input type="number" class="form-control" name="editContact[]" 
                                id="editContact[]" placeholder="Enter contact number" value="` + success[2][index] + `"></div>`;

                    if(index == 0){
                        rowData += `<div class="form-group col-md-2">
                                <a class="btn btn-info btn-fill btn-rotate btn-sm" id="add_fields2" onclick="add_field()"><span class="btn-label"><i class="ti-plus"></i></span></a><div><div>`;
                    }
                    else{
                        rowData += `<div class="form-group col-md-2">
                                <a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-trash"></i></a></div><br><div>`;
                    }
                    $('#editContactDiv').append(rowData);
                }

                $('#editAddress').val(success[0].address);

                $("#editProvince").empty();
                $("#editCity").empty();
                $("#editBarangay").empty();
                
                if(success[1].region_id)$("#editRegion").val(success[1].region_id);
                //province combo box
                $.each(myData[$("#editRegion").val()].province_list, function(index, value) {
                    $("#editProvince").append('<option value="' + index + '">' + index + '</option>');
                });
                if(success[1].province)$("#editProvince").val(success[1].province);;

                //city combo box
                $.each(myData[$("#editRegion").val()].province_list[$("#editProvince").val()].municipality_list, function(index, value) {
                    $("#editCity").append('<option value="' + index + '">' + index + '</option>');
                });
                if(success[1].city)$("#editCity").val(success[1].city);

                //barangay combo box
                $.each(myData[$("#editRegion").val()].province_list[$("#editProvince").val()].municipality_list[$("#editCity").val()].barangay_list, function(index, value) {
                    $("#editBarangay").append('<option value="' + index + '">' + value + '</option>');
                });
                if(success[1].barangay_id)$("#editBarangay").val(success[1].barangay_id);
                $('.selectpicker').selectpicker('refresh');

                $('#update_modal').modal('show');
                //process loader false
                processObject.hideProcessLoader();
            }
        });
    }
    @endcan
    
    @if(Gate::check('permission','deleteHotline') || Gate::check('permission','restoreHotline'))
    const deactivate = (id) => {
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
            // ajax delete data to database
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                url : '/covidtracer/emergency-hotline/toggle/'+id,
                type: "POST",
                data:{ _token: "{{csrf_token()}}"},
                dataType: "JSON",
                success: function(response){ 
                    swal({
                        title: "Success!",
                        text: response.messages,
                        type: "success"
                    });
                    datatable.ajax.reload( null, false);
                    //process loader false
                    processObject.hideProcessLoader();
                },
                error: function (jqXHR, textStatus, errorThrown){
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
        });
    }
    @endif

    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(09|\+639)\d{9}$/);
        }, "<br />Please specify a valid phone number");


</script>
@endsection
