@extends('layouts.app2')

@section('location')
{{$title}}
@endsection
@section('style')
    <style>
        td.details-control, .categoryPerDate {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('../assets/image/minus.png') no-repeat center center;
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
                                    <h4 class="card-title"><b>Search Facility VAS Report</b></h4>
                                    <p class="category">Select Facilities</p>
                                </div>
                                <div class="col-lg-2">
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="selectpicker form-control" data-style="btn-info" name="facilities" id="facilities">
                                        <option value="" disabled selected>Select Health Facilities.....</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="button" class="btn btn-info btn-block" id="searchFacilityVASReport">Search VAS Report</button>
                                </div>
                            </div>

                        </div>
                        </div>
                    </div>
                </div> <!-- end col-md-12 -->
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10">
                                    <h4 class="card-title"><b>VAS Reports Per Date</b></h4>
                                    <p class="category">File Export List</p>
                                </div>
                                <div class="col-lg-2">
                                    <input type="hidden" id="vaccination_facility">
                                    <select class="selectpicker form-control" name="vas_reports_dates" id="vas_reports_dates">
                                        <option value='0' seelcted>Select Date.....</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                                width="100%">
                                <!--Table head-->
                                <thead style="background-color: rgb(214, 214, 214)">
                                    <tr>
                                        <th style="width:20px;"></th>
                                        <th>Vaccination Date</th>
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
    <div class="modal fade in" tabindex="-1" role="dialog" id="categoryPerDateModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <label id="modalDate"></label>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body">
                
                    <table class="table table-bordered table-hover text-center" id="categoryPerDateTable">
                        <thead>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Add -->
</div>
@endsection

@section('js')
<script>

    let categoryCountPerDateFirstDose = [];
    let categoryCountPerDateSecondDose = [];
    let dates = [];
    let vaccines = [];
    let flag = false;
    
    $(document).ready(function () {

        $.ajax({
            url:'{{ route('file-export.perspective-facility') }}',
            type:'GET',
            dataType:'JSON',
            success:function(response){
                response.forEach(data => {
                    $("select[name='facilities']").append(`<option data-icon="fa fa-hospital-o" value="${data.hf_id}">${data.hf_name}</option>`);
                });
                $("select[name='facilities']").selectpicker("refresh");
            },
        })
        
        //Show other details
        $('#datatable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = datatable.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                // flag = false;
                tr.removeClass('shown');
            }
            else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
            }
        });
        
        $('#searchFacilityVASReport').on('click', function(){
            if(!($('#facilities').val())){
                swal({
                    title: "Warning!",
                    text: "Please select facility first!",
                    type: "warning",
                    footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                });
                return false;    
            }else{
                $.ajax({
                    url:'/covid19vaccine/vas-report-dates/' + $("#facilities").val(),
                    type:'GET',
                    dataType:'JSON',
                    success:function(response){
                        $("#vaccination_facility").val($("#facilities").val());
                        $("#vas_reports_dates").empty();
                        $("#vas_reports_dates").append("<option value='0' seelcted>Select Date.....</option>");
                        console.log(response);
                        response.forEach(data => {
                            $("select[name='vas_reports_dates']").append(`<option data-icon="fa fa-calendar" value="${data}">${data}</option>`);
                        });
                        $("select[name='vas_reports_dates']").selectpicker("refresh");
                    },
                })
            }
        });
        
        $("#vas_reports_dates").on('change',function(){
            if($("#vas_reports_dates").val() != "0"){
                
                $('#datatable').DataTable().clear().destroy();
                datatable = $('#datatable').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "searching": false,
                    "bPaginate": false,
                    "bInfo": false,
                    "ajax":{
                        "url": '{{ route('file-export.find-all-vas-per-date') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}", "facility" : $("#vaccination_facility").val(), "date" : $("#vas_reports_dates").val()}
                    },
                    "columns": [
                        {
                            "className":'details-control',
                            "orderable":false,
                            "data":null,
                            "defaultContent": ''
                        },
                        { "data": "vaccination_date" },
                        // { "data": "status" },    
                    ],
                    "columnDefs": [
                        { "orderable": false, "targets": [ 0,1] },
                    ]
                });
            }
        });
    });



    const fileExport = (id, type, filename, date, report ="") =>{

        Swal.fire({
                title: 'Export File?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                html: "<b>" + filename +"<br> Date Last Requested: " + date ,
                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        xhrFields: {
                            responseType: 'blob',
                        },
                        url: "/covid19vaccine/file-export/download-file/"+ id + "/" + type + "/" + filename+'.xlsx',
                        type: "GET",
                        data:{
                            "report": report,
                        },
                        beforeSend: function(){
                            processObject.showProcessLoader();
                        },
                        success: function(result, status, xhr) {

                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : 'salary.xlsx');

                        // The actual download
                        var blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();
                        document.body.removeChild(link);
                        swal({
                            title: "Save!",
                            text: "Successfully Exported!",
                            type: "success",
                            html: "<b>" + filename +"<br> Date Last Requested: " + date ,
                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                        });

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" + errorThrown +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                        },
                        complete: function(){
                            processObject.hideProcessLoader();
                        },
                    });
                }
            })
    }
    
    //vaccine
    const format = (d) => {
        console.log(d);
        let grade = "";
        let data = '';
        dates = [];
        categoryCountPerDateFirstDose = [];
        categoryCountPerDateSecondDose = [];
        vaccines = [];
        //set all vaccines
        data += `
                <div class="col-md-6">
                    <table class="table table-bordered table-hover text-center table-responsive">
                        <thead>
                            <th colspan="100" class="text-center" style="background:#f3bb45"><h5><b>VACCINATED FIRST DOSE</b></h5></th>
                        </thead>
                        <tbody>
                            <tr>`;
                                
                    //Count per Vaccine
                    if(d.vaccinatedPerCategory.length > 0){
                        data += `<tr>
                                    <tr>
                                        <td><b>CATEGORY</b></td>`;
                        for(let index = 0; index < d.vaccinatedPerCategory[0].vaccinatedPerVaccine.length; index++){
                            data += `<td><b> ${d.vaccinatedPerCategory[0].vaccinatedPerVaccine[index].vaccine} </b></td>`;
                        }
                        data += `</tr>`;
                                        
                        for(let index = 0; index < d.vaccinatedPerCategory.length; index++){
                            data += "<tr><td>" +  d.vaccinatedPerCategory[index].category + "</td>";
                            
                            for(let index2 = 0; index2 < d.vaccinatedPerCategory[index].vaccinatedPerVaccine.length; index2++){
                                data += `<td> ${(d.vaccinatedPerCategory[index].vaccinatedPerVaccine[index2].firstDoseCount != 0)? d.vaccinatedPerCategory[index].vaccinatedPerVaccine[index2].firstDoseCount : ""} </td>`;
                            }
                        }
                        data += "</div>";
                    }
                    
        data += `</tr>
                    </tbody>
                        </table>
                </div>
                
                <div class="col-md-6">
                    <table class="table table-bordered table-hover text-center table-responsive">
                        <thead>
                            <th colspan="100" class="text-center" style="background:#f3bb45"><h5><b>VACCINATED SECOND DOSE</b></h5></th>
                        </thead>
                        <tbody>
                            <tr>`;
                                
                    //Count per Vaccine
                    if(d.vaccinatedPerCategory.length > 0){
                        data += `<tr>
                                    <tr>
                                        <td><b>CATEGORY</b></td>`;
                        for(let index = 0; index < d.vaccinatedPerCategory[0].vaccinatedPerVaccine.length; index++){
                            data += `<td><b> ${d.vaccinatedPerCategory[0].vaccinatedPerVaccine[index].vaccine} </b></td>`;
                        }
                        data += `</tr>`;
                                        
                        for(let index = 0; index < d.vaccinatedPerCategory.length; index++){
                            data += "<tr><td>" +  d.vaccinatedPerCategory[index].category + "</td>";
                            
                            for(let index2 = 0; index2 < d.vaccinatedPerCategory[index].vaccinatedPerVaccine.length; index2++){
                                // if(d.vaccinatedPerCategory[index].vaccinatedPerVaccine[index2].secondDoseCount != 0)
                                data += `<td> ${(d.vaccinatedPerCategory[index].vaccinatedPerVaccine[index2].secondDoseCount != 0) ? d.vaccinatedPerCategory[index].vaccinatedPerVaccine[index2].secondDoseCount : ""} </td>`;
                            }
                        }
                    }
        data +=         `</tr>
                            </tbody>
                        </table>
                        </div>`;
                        
        return data;
    }
     
    const showCategoryPerDateCount = (counter, dosage) =>{
        $("#categoryPerDateModal").modal("show");
        categoryArray = (dosage == 1)? categoryCountPerDateFirstDose : categoryCountPerDateSecondDose;
        let data;
        $("#modalDate").text();
        
        data = `<th class="text-center" style="background:#f3bb45; font-size:15px"><b>CATEGORY</b></th>`;
        for(let index = 0; index < vaccines.length; index++){
            data += `<th class="text-center" style="background:#f3bb45; font-size:15px"><b> ${vaccines[index]}</b></th>`;
        }
        $("#categoryPerDateTable thead").empty();
        $("#categoryPerDateTable thead").append(data);
        $("#modalDate").text("Date : " + dates[counter]);
        
        data = "";
            if(categoryArray[counter].length > 0){
            
                data = "<tr>";
                
                index = 0
                countCategories = categoryArray[counter].length / vaccines.length;
                while(index < countCategories){
                    data += "<tr>";
                    counter2 = 0;
                    data += "<td>" + categoryArray[counter][index].category + "</td>";
                    index2 = index;
                    while(counter2 < vaccines.length){
                        data += "<td>" + categoryArray[counter][index2].count + "</td>";
                        index2 += countCategories;
                        counter2++;
                    }
                    data += "</tr>";
                    index++;
                }  
                
                data+= "</tr>";
            }
        
        data = (data == null || data == "") ? "<tr><td colspan='100'>No data available</td></tr>" : data;
        $("#categoryPerDateTable tbody").empty();
        $("#categoryPerDateTable tbody").append(data);
    }
    

</script>
@endsection
