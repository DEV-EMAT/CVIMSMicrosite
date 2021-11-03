@extends('layouts.app2')
@section('location')
{{$title}}
@endsection
@section('style')
    <style>
    @page{margin:0 ; size: letter; size: auto } /*0mm 0mm 0mm 0mm*/
    
    @media print {
        * {
            -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
            color-adjust: exact !important;  /*Firefox*/
        }
    }
    
    #printDiv{
        width:96%;
        margin-top:5px;
        margin-left:16px;
        font-family: 'Times New Roman', Times, serif;
    }

    .box{
        float: left;
        width: 20%;
        height: 160px;
        padding: 2px;
        border:1px dotted;
    }

    svg{
        margin-top: 10%;
        /* margin-left: 25%; */
        padding: 5px;
        height: 70px;
        width: 70px;
        border: 3px solid;
    }

    #name{
        font-size: 7px;
        line-height: 1em;
        /* font-style: underline; */
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
                                <a id="print" data-toggle="tooltip" class="btn btn-primary" title="Click here to Print Qr Code">
                                    <i class="fa fa-print"></i> Print Qr Code
                                </a>
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
                                        <th style="width: 300px;">
                                            <input type="checkbox" id="checkAll" value="'+ data +'"/> Select All
                                        </th>
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
        <div class="row">
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/JavaScript" src="{{asset('assets/js/printing/jQuery.print.js')}}"></script>
<script>
    window.userId = [];
    $(document).ready(function () {
        datatable = $('#datatable').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax":{
                "url": '{{ route('qr-code.find-all') }}',
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
            ],
            "aoColumnDefs": [
                {
                    "aTargets": [2],
                    "mData": "id",
                    "mRender": function (data, type, full) {
                        if(userId.length==0){
                                return '<input type="checkbox" class="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                        }else{
                            let flag =false;
                            for (let index = 0; index < userId.length; index++) {
                                if(userId[index]==data){
                                    flag = true;
                                    break;
                                }
                            }
                            if(flag){
                                return '<input type="checkbox" class="checkbox" checked onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                            }else{
                                return '<input type="checkbox" class="checkbox" onclick="ctrToggle(this.value)" value="'+ data +'"/>';
                            }
                        }
                    }
                }
            ],
        });

        $("#print").click(function(){
            if(userId.length > 0){
                //process loader true
                processObject.showProcessLoader();
                $.ajax({
                    url:'/comprehensive/qr-code/print',
                    type:'GET',
                    dataType:'json',
                    data:{userId : userId},
                    success:function(response){
                        $('#printDiv .row').children().remove();
                        let qrCodeId = "qrCode";
                        let nameId = "name";
                        for(let index = 0; index < response.length; index++){
                            if(index > 0){
                                qrCodeId = "qrCode" + index;
                                nameId = "name" + index;
                            }

                            $('#printDiv .row').append(`<div class="col-lg-3 box">
                                                            <div class="col-sm-12 text-center">
                                                                <p id="qrCode` + index + `">` + response[index]["qrCode"] + `</p>
                                                            </div>
                                                            <div class="col-sm-12 text-center">
                                                                <b><span style="font-size:1vw !important" id="name` + index + `"> ` + response[index]["name"] + 
                                                                    `</span></b>
                                                            </div>
                                                        </div>`);
                        }                    
                        //process loader false
                        processObject.hideProcessLoader();
                        $("#printDiv").print();
                    }
                });
            }else{
                swal({
                    title: "Error!",
                    text: "No data selected!",
                    type: "error"
                })
            }
        });

        $("#checkAll").click(function(){
            let rows = datatable.rows({'search': 'applied'}).nodes();

            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            for(let index = 0; index<rows.length; index++){
                userId.push($('input[type="checkbox"]', rows[index]).val());
            }
            if(!$("#checkAll").is(':checked')){
                userId = [];
            }
        });

        $('#datatable tbody').on('change', 'input[type="checkbox"]', function(){
            // If checkbox is not checked
            if(!this.checked){
                let checkBox = $('#checkAll').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if(checkBox && checkBox.checked && ('indeterminate' in checkBox)){
                    // Set visual state of "Select all" control
                    // as 'indeterminate'
                    checkBox.indeterminate = true;
                }
            }
        });
    });    

    //toggle status of checkbox in adding of staff
    const ctrToggle = (value) => {
        let flag =false;
        for (let index = 0; index < userId.length; index++) {
            flag=false;
            if(userId[index]==value){
                flag = true;
                userId.splice(index,1);
                break;
            }   
        }
        if(!flag){
            userId.push(value);
        }
    }
</script>
@endsection