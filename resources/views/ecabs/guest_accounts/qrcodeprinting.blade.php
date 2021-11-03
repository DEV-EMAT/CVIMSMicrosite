@extends('layouts.app2')
@section('location')
{{$title}}
@endsection
@section('style')
<link href="{{asset('assets/css/background-image.css')}}" rel="stylesheet" />
    <style>
    /* @media print {
    @page { margin: 0; }
    body { margin: 1.6cm; }
    } */
    @page{margin: 0mm 0mm 0mm 0mm; size: landscape; }
    @media print {
        * {
            -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
            color-adjust: exact !important;  /*Firefox*/
            
        }
        #printDiv {display: block;}
    }
    #printDiv{
        height:380px;
        width:25%;
        padding: 12% 0 15% 0;
        display: flex; 
        flex-direction:column; 
        align-items:center; 
        text-align:center;
        background-position: center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        /* display:none; */
        position: absolute; right:0px;
        font-family: 'Times New Roman', Times, serif;
    }

    svg{
        height: 80px;
        width: 80px;
        border: 1px solid;
        padding: 5px;
    }

    #name{
        font-size: 12px;
        font-weight: bold;
    }

    #address{
        font-size: 9px;
        padding:0px;
        font-style: italic;
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
                                <h4 class="card-title"><b>Users List</b></h4>
                                <p class="category">Printing of person Qr Code</p>
                            </div>
                            <div class="col-lg-2">

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
                                        <th>Fullname</th>
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

<div class="modal">
    <div id="printDiv">
        <span id="qrCode"></span>
        <b><span id="name"></span></b>
        <span id="address"></span>
    </div>
</div>
@endsection

@section('js')
<script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
<script>
    $(document).ready(function () {
        
        datatable = $('#datatable').DataTable({
           "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('guest-account.findall') }}',
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", 'action':'qrcodeprinting'}
            },
            colReorder: {
                 realtime: true
            },
            "columns": [
                { "data": "fullname" },
                { "data": "status" },
                { "data": "actions" },
            ],
        });
    });
    
    @can('permission', 'viewPrintGuestCode')
    function print_form(id){
        //process loader true
        processObject.showProcessLoader();
        $.ajax({
            url:'/guest-account/print-qr-code/' + id,
            type:'GET',
            dataType:'json',
            success:function(response){
                $("#qrCode").html(response.qrcode);
                $("#name").html(response.name);
                $("#address").html(response.address);
                //process loader false
                processObject.hideProcessLoader();
                $("#printDiv").print();
            }
        });
    }
    @endcan
</script>
@endsection