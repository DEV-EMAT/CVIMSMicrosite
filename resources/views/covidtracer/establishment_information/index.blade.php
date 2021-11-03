@extends('layouts.app2')

@section('style')
    <link href="{{asset('assets/css/background-image.css')}}" rel="stylesheet" />
    <style>
    @page{margin:0 ; size: letter; size: auto } /*0mm 0mm 0mm 0mm*/
    
    @media print {
        * {
            -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
            color-adjust: exact !important;  /*Firefox*/
        }
        
        /* body {transform: scale(.7);}
        table {page-break-inside: avoid;} */
        /* #printDiv {display: block;} */
        
    }
    #printDiv{
        height:1050px;
        width:100%;
        display: flex; 
        flex-direction:column; 
        align-items:center; 
        text-align:center;
        background-position: center left !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        overflow: visible;
        font-family: 'Times New Roman', Times, serif;
    }

    svg{
        margin-top: 350px;
        padding: 20px;
        height: 270px;
        width: 270px;
        border: 3px solid;
    }

    #name{
        font-size: 40px;
        line-height: 1em;
    }

    #printAddress{
        font-size: 20px;
        padding:5px;
        font-style: italic;
    }
    </style>
@endsection
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
                                    <h4 class="card-title"><b>Establishment Information List</b>
                                    <p class="category">Create | Update | Remove Data</p>
                                </div>
                                <div class="col-md-2 text-right">
                                    @can('permission', 'createEstinfo')
                                    <div data-toggle="modal" data-target="#estinfomodal">
                                        <a id="add" data-toggle="tooltip" class="btn btn-primary" title="Click here to add new Establishment Information">
                                            <i class="ti-plus"></i> Add new
                                        </a>
                                    </div>
                                    @endcan
                                </div>
                            </div> 


                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-sm" cellspacing="0"
                                    width="100%">
                                    <!--Table head-->
                                    <thead>
                                        <tr>
                                            <th>Business Name</th>
                                            <th>Status</th>
                                            <th style="width: 500px;">Actions</th>
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
</div>
    <!-- End Display All Data -->

<!--Edit Information Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="estinfomodal">
    <div class="modal-dialog" role="document">
        <form id="estinfo_form">
            @csrf
            @method('POST')
            <div class="modal-content modal-lg">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Add Establishment Information</h4>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <blockquote>
                                <p>Fill-up this form to create a new <b>Establishment Information</b>.</p>
                            </blockquote>
                        </div>
                    </div>
                    <!-- Category -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="category">Choose Category:</label>
                            <select class="selectpicker form-control" name="category" id="category">
                                <option value="" disabled selected>Select.....</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Category -->
                    
                    <!-- Owner -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="owner">Owner:</label>
                            {{-- <select class="selectpicker form-control" name="owner" id="owner">
                                <option value="" disabled selected>Select.....</option>
                            </select> --}}
                            <a class="btn btn-block btn-primary"
                                onclick="searchOwner()">
                                <span class="btn-label">
                                    <i class="fa fa-search"></i>
                                </span>
                                Search for Owner
                            </a><br>
                            <input type="text" class="form-control" name="owner" id="owner"
                                placeholder="Enter Owner" disabled>
                        </div>
                    </div>
                    <!-- End Owner -->
                    
                    <!-- Business Name -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="business_name">Business Name:</label>
                            <input type="text" class="form-control" name="business_name" id="business_name"
                                placeholder="Enter Business name">
                        </div>
                    </div>
                    <!-- End Business Name -->
                    
                    <!-- Business Permit -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="business_permit">Business Permit Number:</label>
                            <input type="text" class="form-control" name="business_permit" id="business_permit"
                                placeholder="Enter Business Permit Number">
                        </div>
                    </div>
                    <!-- End Business Permit -->

                    <!-- Address -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control" name="address" id="address"
                                placeholder="Enter Address">
                        </div>
                    </div>
                    <!-- End Address -->

                    <!-- Barangay -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="barangay">Barangay:</label>
                            <select class="selectpicker form-control" name="barangay" id="barangay">
                                <option value="" disabled selected>Select.....</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Barangay -->
                    <input type="hidden" id="estinfo_id" name="estinfo_id">
                    <input type="hidden" id="ownerId" name="ownerId">
                </div>
        </form>

        <div class="modal-footer">
            <button class="btn btn-success" id="save">Save</button>
        </div>
    </div>
    </div>
</div>
<!-- End Modal -->

@can('permission', 'createEstinfo')
<!--Add Owner Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="addOwnerModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <!-- Modal Header -->
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title">Add Owner</h4>
            </div>
            <!-- End Modal Header -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <blockquote>
                            <p>Select <b>Establishment Owner</b>.</p>
                        </blockquote></span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="ownerDatatable" class="table table-bordered table-sm" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Fullname</th>
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
    </div>
</div>
<!-- End Modal -->

<!--Add Staff Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="addStaffModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <!-- Modal Header -->
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title">Add Staff</h4>
            </div>
            <!-- End Modal Header -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <blockquote>
                            <p>Select <b>Establishment Staff</b>.</p>
                        </blockquote>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="accountDatatable" class="table table-bordered table-sm" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Fullname</th>
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
                <input type="hidden" id="staff_estinfo_id" name="staff_estinfo_id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="saveStaff" disabled>Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@endcan

<!--View Staff Modal-->
<div class="modal fade in" tabindex="-1" role="dialog" id="viewStaffModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <!-- Modal Header -->
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title">View Staff</h4>
            </div>
            <!-- End Modal Header -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="staffDatatable" class="table table-bordered table-sm" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>Fullname</th>
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
                <input type="hidden" id="staff_estinfo_id" name="staff_estinfo_id">
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<div class="modal">
    <div id="printDiv">
        <span id="qrCode"></span>
        <b><span id="name"></span></b>
        <span id="printAddress"></span>
    </div>
</div>
@endsection

@section('js')
    <script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
    <script>

    window.staffId = [];
    let activeStaff = [];
     
    $(document).ready(function () {
        $('#accountDatatable').DataTable();
        $('#staffDatatable').DataTable();
        $('#ownerDatatable').DataTable();

        //get category
        $.ajax({
            url:'{{ route('covidtracer.est_cat.findallforcombobox') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="category"]').append('<option value='+response[index].id+'>'+ response[index].description +'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
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

        //Datatables
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('covidtracer.est_info.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            colReorder: {
                realtime: true
            },
            "columns": [
                { "data": "business_name"},
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                { "orderable": false, "targets": [ 2 ] }, 
            ]	 	 
        });

        @can('permission', 'createEstinfo')
        //Submit Establishment Information Form
        $("#estinfo_form").validate({
            rules: {
                category : {
                    required: true
                },
                owner : {
                    required: true
                },
                business_name : {
                    required: true
                },
                business_permit : {
                    required: true
                },
                address : {
                    required: true
                },
                barangay : {
                    required: true
                },
            },
            submitHandler: function (form) {
                //Add Establishment Information
                if($("#estinfo_id").val() == ""){
                    Swal.fire({
                        title: 'Add Now?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        if (result.value) {
                            //process loader true
                            processObject.showProcessLoader();
                            $.ajax({
                                url: '{{ route('estinfo.store') }}',
                                type: "POST",
                                data: $('#estinfo_form').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        $('#estinfomodal').modal('hide');
                                        $("#estinfo_form")[0].reset();
                                        swal({
                                            title: "Save!",
                                            text: "Successfully!",
                                            type: "success"
                                        })
                                        //process loader false
                                        processObject.hideProcessLoader();
                                        datatable.ajax.reload( null, false);
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
                //Update Establishment Information
                else{
                    Swal.fire({
                        title: 'Update Data?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        if (result.value) {
                            //process loader true
                            processObject.showProcessLoader();
                            $.ajax({
                                url: '/covidtracer/estinfo/' + $('#estinfo_id').val() ,
                                type: "PUT",
                                data: $('#estinfo_form').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        $('#estinfomodal').modal('hide');
                                        $("#estinfo_form")[0].reset();
                                        swal({
                                            title: "Update!",
                                            text: "Successfully!",
                                            type: "success"
                                        })
                                        //process loader false
                                        processObject.hideProcessLoader();
                                        datatable.ajax.reload( null, false);
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
            }
        });
        @endcan

        //Reset data if Click the Add New
        $("#add").click(function(){
            $("#estinfo_form")[0].reset();
            $("#estinfo_id").val("");
            $("#ownerId").val("");
            $('label.error').hide();
            $('.error').removeClass('error');

            $(".selectpicker").val('').selectpicker("refresh");

            //change modal title to add
            $('#estinfomodal .modal-title').html("Add Establishment");
            //change message
            $("#message").text("This will add a new establishment information.");
        });

        //Save Staff Added
        $("#saveStaff").click(function(){
            Swal.fire({
                title: 'Add Staff?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    //process loader true
                    processObject.showProcessLoader();
                    $.ajax({
                        url: "{{ route('est-staff.store')}}",
                        type: "POST",
                        "data":{ 
                            _token: "{{csrf_token()}}",
                            staffId: staffId,
                            staffEstInfoId : $("#staff_estinfo_id").val()
                        },
                        dataType: "JSON",
                        success: function (response) {
                            if(response.success){
                                $("#addStaffModal").modal("hide");
                                swal({
                                    title: "Success!",
                                    text: response.messages,
                                    type: "success"
                                })
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
        });
    });

    //Select Data to edit
    function edit(id){
        //remove error
        $("label.error").hide();
        $(".error").removeClass("error");

        //change modal title to edit
        $('.modal-title').html("Edit Category");
        //change message
        $("#message").text("This will update the establishment information.");

        var url = "{{ route('estinfo.show', ":id") }}";
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#estinfo_id").val(data.estInfo.id);
                $("#category").val(data.estInfo.establishment_category_id);
                $("#owner").val(data.owner);
                $("#ownerId").val(data.estInfo.owner_id);
                $("#business_name").val(data.estInfo.business_name);
                $("#business_permit").val(data.estInfo.business_permit_number);
                $("#address").val(data.estInfo.address);
                $("#barangay").val(data.estInfo.barangay_id);
                $('.selectpicker').selectpicker('refresh');
                $("#estinfomodal").modal("show");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    //Activate or Deactivate Status
    function toggleStatus(id, status){
        if(status == 1)
            deactivate(id);
        else
            activate(id);
    }


    @can('permission', 'deleteEstinfo')
    //Deactivate Data
    const deactivate = (id) =>{
        Swal.fire({
            title: 'Delete Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/covidtracer/estinfo/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
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
                            //process loader false
                            processObject.hideProcessLoader();
                        } else {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                text: data.messages,
                                type: "error"
                            }).then(function () {
                                datatable.ajax.reload( null, false);
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
                        })
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });
            }
        })
    }
    @endcan

    @can('permission', 'restoreEstinfo')
    //Activate Data
    const activate = (id) =>{
        Swal.fire({
            title: 'Restore Data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/covidtracer/estinfo/status/' + id,
                   data:{_token: '{{csrf_token()}}' },
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Restore Successfully!",
                                type: "success"
                            }).then(function () {
                                datatable.ajax.reload( null, false);
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
                        }).then(function () {
                            datatable.ajax.reload( null, false);
                        });
                        //process loader false
                        processObject.hideProcessLoader();
                    }
                });
            }
        })
    }
    @endcan

    //search owner
    const searchOwner = () => {
        let establishmentId = '';
        if($("#estinfo_id").val() != ""){
            establishmentId = $("#estinfo_id").val();
        }

        $('#ownerDatatable').DataTable().clear().destroy();
        ownerDatatable = $('#ownerDatatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('covidtracer.est-info.find-owner') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", "establishmentId" : establishmentId}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "buttons" },
            ],
            "aLengthMenu": [[10, 25, 50], [10,  25, 50]],
        });
        
        // $('.modal-title').html("Add Owner");
        $("#addOwnerModal").modal("show");
    }

    //add owner
    const addOwner = (id) => {
        $.ajax({
            url:'/covidtracer/estinfo/get-owner/' + id,
            type:'GET',
            success:function(response){
                $("#ownerId").val(id);
                $("#owner").val(response.name);
                $("#addOwnerModal").modal("hide");
            }
        });
    }
    
    //add staff
    const addStaff = (id) =>{
        $("#staff_estinfo_id").val(id);
        staffId = [];
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url: '/covidtracer/est-staff/' + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                data.forEach(value => {
                    staffId.push(value.user_id);
                });
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
        
        $('#accountDatatable').DataTable().clear().destroy();
        //accounts for adding staff
        accountDatatable = $('#accountDatatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('est-staff.find-all') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", 'action':'addStaff', 'establishmentId': id}
            },
            "columns": [
                { "data": "fullname" },
            ],
            "aoColumnDefs": [
                {
                    "aTargets": [1],
                    "mData": "id",
                    "mRender": function (data, type, full) {
                        if(staffId.length==0){
                                return '<input type="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                        }else{
                            var flag =false;
                            for (let index = 0; index < staffId.length; index++) {
                                if(staffId[index]==data){
                                    flag = true;
                                    break;
                                }
                            }
                            if(flag){
                                return '<input type="checkbox" checked onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                            }else{
                                return '<input type="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                            }
                        }
                    }
                }
            ],
            "aLengthMenu": [[10, 25, 50], [10,  25, 50]],
        });
        
        $("#saveStaff").prop("disabled", true);
        //change modal title to edit
        $('.modal-title').html("Add Staff");
        //change message
        $("#addStaffMessage").text("This will add new staff/s.");
        $("#addStaffModal").modal("show");
    }

    //view staffs of establishment
    const viewStaff = (id) =>{
        $("#staff_estinfo_id").val(id);
        
        $('.modal-title').html("View Staffs");
        $('#staffDatatable').DataTable().clear().destroy();
        //show all staff
        staffDatatable = $('#staffDatatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('est-staff.find-all-staff') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", 'establishmentId': id}
            },
            "columns": [
                { "data": "fullname" },
                { "data": "buttons" },
            ],
            "aLengthMenu": [[10, 25, 50], [10,  25, 50]],
        });
        
        $("#viewStaffModal").modal("show");
    }

    //toggle status of checkbox in adding of staff
    const ctrToggle = (value) => {
        let flag =false;
        for (let index = 0; index < staffId.length; index++) {
            flag=false;
            if(staffId[index]==value){
                flag = true;
                staffId.splice(index,1);
                break;
            }   
        }
        if(!flag){
            staffId.push(value);
        }
        $('#txtItems').val(staffId.length);

        //toggle save staff
        if(staffId.length > 0){
            $("#saveStaff").prop("disabled", false);
        }else{
            $("#saveStaff").prop("disabled", true);
        }
    }

    //remove staff
    const removeStaff = (id) => {
        Swal.fire({
            title: 'Delete Data?',
            text: 'Are you sure you want to remove this staff?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                   url: '/covidtracer/est-staff/remove-staff/' + id,
                   data:{_token: '{{csrf_token()}}', 'establishmentId': $("#staff_estinfo_id").val()},
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Save!",
                                text: "Deleted Successfully!",
                                type: "success"
                            })
                            //process loader false
                            processObject.hideProcessLoader();
                            staffDatatable.ajax.reload( null, false);
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
        });                     
    }

    //print qr code
    const printQrCode = (id) => {
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url:'/covidtracer/estinfo/print-qrcode/' + id,
            type:'GET',
            dataType:'json',
            success:function(response){
                $("#qrCode").html(response.qrcode);
                $("#name").html(response.establishment);
                $("#printAddress").html(response.address);
                //process loader false
                processObject.hideProcessLoader();
                // var winPrint = window.open('', '', 'left=0,top=0,width=800,height=600,toolbar=0,scrollbars=0,status=0');
                // winPrint.document.write('<title>Print  Report</title><br /><br /> Hellow World');
                // winPrint.document.close();
                // winPrint.focus();
                // winPrint.print();
                // winPrint.close();
                $("#printDiv").print();
            }
        });
    }
    </script>
@endsection
