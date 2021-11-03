
@extends('layouts.app2')

@section('location')
{{$title}}
@endsection
@section('style')
<!-- Styles -->
<style>
#chartdiv {
    width: 100%;
    height: 500px;
}
#chartdiv2, #chartdiv3, #chartdiv4 {
    width: 100%;
    height: 400px;
}

/* #slideContent{
    background: #fff;
    box-shadow: 0 0 5px rgba(0,0,0,.3);
    color: #333;
    position: fixed;
    top: 100px;
    width: 500px;
    -webkit-transition-duration: 0.3s;
    -moz-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;
}
#slideContent.on {
  left: 500px;
} */
</style>
@endsection

@section('content')
    <!-- Display All Data -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        <p>Registered</p>
                                        <b id="registered">00</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="card-footer">
                        <hr>
                        <div class="stats" style="color: black;">
                            <div class="pull-right" style="position:relative; display:inline-block;"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" rel="tooltip" title="" data-original-title="Total Registed Vaccinee Via Online and Paper Base Forms."></i></div>
                            <b><i class="fa fa-bar-chart" aria-hidden="true"></i> <span id="txtPreRegisteredToday"> </span> </b> Newly Registered Today <span id="txtDateToday"> </span>  <br>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-success text-center">
                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        <p>Evaluated</p>
                                        <b id="evaluated">00</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats" style="color: black;">
                                <div class="pull-right" style="position:relative; display:inline-block;"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" rel="tooltip" title="" data-original-title="Calculated Percentage of Evaluated Vaccinee."></i></div>
                                <b><i class="fa fa-bar-chart" aria-hidden="true"></i> <span id="txtEvaluatedPercent"> </span>% </b> Evaluated from Registered Vaccinee
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-danger text-center">
                                        <i class="fa fa-medkit" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        <p>Vaccinated <b>(First Dose)</b></p>
                                        <b id="vaccinatedFirstDose">00</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats" style="color: black;">
                                <div class="pull-right" style="position:relative; display:inline-block;"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" rel="tooltip" title="" data-original-title="Calculated Percentage of Vaccinated Patients."></i></div>
                                <b><i class="fa fa-bar-chart" aria-hidden="true"></i>  <span id="txtVaccinatedPercent"></span> % </b> Vaccinated from Evaluated Vaccinee
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="icon-big icon-info text-center">
                                        <i class="fa fa-medkit" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-xs-8">
                                    <div class="numbers">
                                        <p>Vaccinated <b>(Second Dose)</b></p>
                                        <b id="vaccinatedSecondDose">00</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats" style="color: black;">
                                <div class="pull-right" style="position:relative; display:inline-block;"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" rel="tooltip" title="" data-original-title="Calculated Percentage of Vaccinated of Second Dose."></i></div>
                                <b><i class="fa fa-bar-chart" aria-hidden="true"></i><span id="txtVaccinatedPercentSecondDose"></span> % </b> Vaccinated from Evaluated Vaccinee
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--Filter -->
            
            <div id="slideContainer" class="panel panel-border panel-default">

                <a data-toggle="collapse" href="#collapseSix" class="collapsed" aria-expanded="false">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            FILTERING OF DATA FOR STATISTICS
                            <i class="ti-angle-down"></i>
                        </h4>
                    </div>
                </a>
                
                <div id="collapseSix" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                    
                        <div class="card">
                            <div class="card-header">
                            </div>
                            
                            <div class="card-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Date (From)</label>
                                            <input type='text' class="form-control datetimepicker" id='date_from' name="date_from"  max="9999-12-31"
                                            placeholder="Date From"/>
                                        </div>
                                    </div>
                                        
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Date (To)</label>
                                            <input type='text' class="form-control datetimepicker" id='date_to' name="date_to"  max="9999-12-31"
                                            placeholder="Date To"/>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">                                        
                                        <div class="form-group">
                                            <label>Vaccine</label>
                                            <select class="form-control selectpicker" id="vaccine" name="vaccine"></select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">                                        
                                        <div class="form-group">
                                            <label>Facility</label>
                                            <select class="form-control selectpicker" id="health_facility" name="health_facility"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="display:flex; justify-content:center">
                                        <div>
                                            <input type="button" id="clear" class="btn btn-warning btn-fill" value="CLEAR" data-toggle="tooltip" title="Click here to clear search filters.">
                                            <input type="button" id="search" class="btn btn-primary btn-fill" value="SEARCH">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title"><b>FIRST DOSE</b></h4>
                                        <hr>
                                    </div>
                                    <div class="card-content" id="firstDosage"></div>
                                    <div class="card-footer">                            
                                        <table class="table table-bordered table-hover" id="firstDoseTable">
                                            <thead>
                                                <th>Category</th>
                                                <th>No. of Vaccinated</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>    
                                        &nbsp
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title"><b>SECOND DOSE</b></h4>
                                        <hr>
                                    </div>
                                    <div class="card-content" id="secondDosage"></div>
                                    <div class="card-footer">                            
                                        <table class="table table-bordered table-hover" id="secondDoseTable">
                                            <thead>
                                                <th>Category</th>
                                                <th>No. of Vaccinated</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>    
                                        &nbsp
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                </div>
                
            </div>
            
            <!-- Per Barangay} -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Immunization Statistics per Barangay</h4>
                            <p class="category">Cabuyao City GO CABVax</p>
                        </div>
                        <div class="card-content">
                            <div id="chartdiv" style="background-color: white;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pre Registered -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pre Registered</h4>
                            <p class="category">Cabuyao City GO CABVax</p>
                        </div>
                        <div class="card-content">
                            <div id="chartdiv2" style="background-color: white;"></div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Vaccinated First Dose -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Vaccinated First Dose</h4>
                            <p class="category">Cabuyao City GO CABVax</p>
                        </div>
                        <div class="card-content">
                            <div id="chartdiv3" style="background-color: white;"></div>
                            <hr>
                        </div>
                    </div>
                </div>
                
                <!-- Vaccinated Second Dose -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Vaccinated Second Dose</h4>
                            <p class="category">Cabuyao City GO CABVax</p>
                        </div>
                        <div class="card-content">
                            <div id="chartdiv4" style="background-color: white;"></div>
                            <hr>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    <!-- End Display All Data -->
@endsection

@section('js')

<script src="{{asset('assets/js/amchart/core.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/amchart/charts.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/amchart/animated.js')}}" type="text/javascript"></script>

<script>
    let dateFrom = dateTo = vaccine = healthFacility = "";
    
    $(document).ready(function(){
    
        //get vaccine categories
        $.ajax({
            url:'{{ route('vaccine-category.find-all-vaccine') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                // $('[name="vaccine"]').empty();
                $('[name="vaccine"]').append('<option value="0" disabled selected>Select .....</option>')
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="vaccine"]').append('<option value='+response[index].id+'>'+ response[index].vaccine_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
        
        //get vaccine categories facilities
        $.ajax({
            url:'{{ route('health-facility.find-all-facility') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                $('[name="health_facility"]').append('<option value="0" disabled selected>Select .....</option>')
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="health_facility"]').append('<option value='+response[index].id+'>'+ response[index].facility_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
                $("#vaccine").val(0);
            }
        });
        
        getReports(false);
        
        $("#clear").click(function(){
            $("#date_from").val("");
            $("#date_to").val("");
            $("#vaccine").val(0);
            $("#health_facility").val(0);
            $('.selectpicker').selectpicker('refresh');
        });
        
        $("#search").click(function(){
                if(($("#date_from").val() != "" && $("#date_to").val() != "") || $("#vaccine").val() != ""){
                    getReports(true);
                }else{
                    swal({
                        title: "Oops! something went wrong.",
                        text: "Please provide date",
                        type: "error"
                    });
                }
            // }
        });
        
        $("#slide").click(function(){
            $('.slideContent').slideToggle();
        });
    });
    
    const getReports = (search) =>{
        if(search == true){
            dateFrom = $("#date_from").val();
            dateTo = $("#date_to").val();
            vaccine = $("#vaccine").val();
            healthFacility = $("#health_facility").val();
        }
    
        am4core.ready(function() {
            var indicator;
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end
        
            // Create chart instance
            var chart = am4core.create("chartdiv", am4charts.XYChart);
            // Add data
            $.ajax({
                url:'{{ route('statistics.getReports') }}',
                type:'GET',
                dataType:'json',
                data:{'dateFrom' : dateFrom, 'dateTo' : dateTo, 'vaccine' :  vaccine, 'healthFacility' : healthFacility},
                beforeSend: function(){
                    showIndicator();
                },
                success:function(response){
                    let firstDoseData = secondDoseData = '';
                    
                    $("#firstDosage").empty();
                    $("#secondDosage").empty();
                    
                    //Count per Vaccine
                    if(response.vaccinatedPerVaccine.length > 0){
                        firstDoseData += `<div class="col-xs-7">
                                    <p><b>VACCINE</b></p>`;
                                    
                        for(let index = 0; index < response.vaccinatedPerVaccine.length; index++){
                            firstDoseData += "<p>" +  response.vaccinatedPerVaccine[index].vaccine + "</p>";
                        }
                        
                        firstDoseData += `</div>
                                <div class="col-xs-5 text-center">
                                    <p><b>NUMBER OF VACCINATED</b></p>`;
                        secondDoseData += firstDoseData;
                        
                        for(let index = 0; index < response.vaccinatedPerVaccine.length; index++){
                            firstDoseData += "<p>" +  response.vaccinatedPerVaccine[index].totalCountFirstDose + "</p>";
                            secondDoseData += "<p>" +  response.vaccinatedPerVaccine[index].totalCountSecondDose + "</p>";
                        }
                                    
                        firstDoseData += "</div>";
                        secondDoseData += "</div>";
                    }
                    
                    $("#firstDosage").append(firstDoseData);
                    $("#secondDosage").append(secondDoseData);
                    
                    
                    $("#firstDoseTable tbody").empty();
                    
                    $("#secondDoseTable tbody").empty();
                    firstDoseData = secondDoseData = '';
                    let firstDoseDataTotal = secondDoseDataTotal = firstDoseDataTotalPerVaccine = secondDoseDataTotalPerVaccine = 0;
                    for(let index = 0; index < response.firstDose.length; index++){
                        firstDoseData += "<tr><td>" +  response.firstDose[index].category + "</td><td>" + response.firstDose[index].count + "</td>";
                        secondDoseData += "<tr><td>" +  response.secondDose[index].category + "</td><td>" + response.secondDose[index].count + "</td>";

                        if(search == true){
                        
                            //vaccine wasselected in filtering
                            if($("#vaccine").val() > 0){
                                for(let index2 = 0; index2 < response.vaccinatedPerVaccine.length; index2++){
                                    if(response.vaccinatedPerVaccine[index2].vaccine == $("#vaccine :selected").text()){
                                        firstDoseDataTotalPerVaccine += response.vaccinatedPerVaccine[index2].vaccinatedCategoryAndVaccine[index].firstDoseCount;
                                        secondDoseDataTotalPerVaccine += response.vaccinatedPerVaccine[index2].vaccinatedCategoryAndVaccine[index].secondDoseCount;
                                        firstDoseData += "<td>" +  response.vaccinatedPerVaccine[index2].vaccinatedCategoryAndVaccine[index].firstDoseCount + "</td>";
                                        secondDoseData += "<td>" +  response.vaccinatedPerVaccine[index2].vaccinatedCategoryAndVaccine[index].secondDoseCount + "</td>";
                                    }
                                }
                            //no vaccine selected in filtering
                            }else if(($("#vaccine").val() == 0 || $("#vaccine").val() == null ) && $("#health_facility").val() > 0){
                                firstDoseDataTotalPerVaccine += response.vaccinatedPerVaccine[0].vaccinatedCategoryAndVaccine[index].firstDoseCount;
                                secondDoseDataTotalPerVaccine += response.vaccinatedPerVaccine[0].vaccinatedCategoryAndVaccine[index].secondDoseCount;
                                
                                firstDoseData += "<td>" +  response.vaccinatedPerVaccine[0].vaccinatedCategoryAndVaccine[index].firstDoseCount + "</td>";
                                secondDoseData += "<td>" +  response.vaccinatedPerVaccine[0].vaccinatedCategoryAndVaccine[index].secondDoseCount + "</td>";
                            }
                            
                            //table head
                            $("#firstDoseTable thead").empty();
                            $("#secondDoseTable thead").empty();

                            let theadData = "<tr><th>Category</th>"+
                                        "<th>No. of Vaccinated</th>" +
                                        "<th>Vaccinated";
                                        
                            if($("#vaccine").val() > 0){
                                theadData += " with <b>" + $("#vaccine :selected").text() + "</b><br>";
                            }
                                        
                            if($("#health_facility").val() > 0){
                                theadData += " in <b>" + $("#health_facility :selected").text() + "</b></br>";
                            }
                            theadData += "</th></tr>";
                            $("#firstDoseTable thead").append(theadData);
                            $("#secondDoseTable thead").append(theadData);
                        }
                        
                        firstDoseData += "</tr>";
                        secondDoseData += "</tr>";
                        firstDoseDataTotal += response.firstDose[index].count;
                        secondDoseDataTotal += response.secondDose[index].count;
                    }
                    
                    firstDoseData += "<tr><td><b>TOTAL</b></td><td><b>" + firstDoseDataTotal + "</b></td>";
                    secondDoseData += "<tr><td><b>TOTAL</b></td><td><b>" + secondDoseDataTotal + "</b></td>";
                    if(search == true && $("#vaccine").val() > 0 || $("#health_facility").val() > 0){
                        firstDoseData += "<td><b>" + firstDoseDataTotalPerVaccine + "</b></td>";
                        secondDoseData += "<td><b>" + secondDoseDataTotalPerVaccine + "</b></td>";
                    }
                    firstDoseData += "</tr>";
                    secondDoseData += "</tr>";
                        
                    $("#firstDoseTable tbody").append(firstDoseData);
                    $("#secondDoseTable tbody").append(secondDoseData);
                    
                            
                    /* Graph for pre registered */
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end
                    
                    var chart2 = am4core.create("chartdiv2", am4charts.XYChart);
                    
                    chart2.data = response.preRegistered;
                    
                    chart2.padding(40, 40, 40, 40);
                    
                    var categoryAxis = chart2.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.dataFields.category = "category";
                    categoryAxis.renderer.minGridDistance = 60;
                    categoryAxis.renderer.inversed = true;
                    categoryAxis.renderer.grid.template.disabled = true;
                    
                    categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
                        if (target.dataItem && target.dataItem.index & 2 == 2) {
                            return dy + 25;
                        }
                            return dy;
                    });
                    
                    var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.min = 0;
                    valueAxis.extraMax = 0.1;
                    
                    var series = chart2.series.push(new am4charts.ColumnSeries());
                    series.dataFields.categoryX = "category";
                    series.dataFields.valueY = "count";
                    series.tooltipText = "{valueY.value}"
                    series.columns.template.strokeOpacity = 0;
                    series.columns.template.column.cornerRadiusTopRight = 10;
                    series.columns.template.column.cornerRadiusTopLeft = 10;
                    var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                    labelBullet.label.verticalCenter = "bottom";
                    labelBullet.label.dy = -10;
                    labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#.')}";
                    
                    chart2.zoomOutButton.disabled = true;
                    
                    // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
                    series.columns.template.adapter.add("fill", function (fill, target) {
                     return chart2.colors.getIndex(target.dataItem.index);
                    });
                    
                    categoryAxis.sortBySeries = series;
                    
                    /* Graph for Vaccinated First Dose */
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end
                    
                    var chart3 = am4core.create("chartdiv3", am4charts.XYChart);
                    
                    chart3.data = response.firstDose;
                    
                    chart3.padding(40, 40, 40, 40);
                    
                    var categoryAxis = chart3.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.dataFields.category = "category";
                    categoryAxis.renderer.minGridDistance = 60;
                    categoryAxis.renderer.inversed = true;
                    categoryAxis.renderer.grid.template.disabled = true;
                    
                    categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
                        if (target.dataItem && target.dataItem.index & 2 == 2) {
                            return dy + 25;
                        }
                            return dy;
                    });
                    
                    var valueAxis = chart3.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.min = 0;
                    valueAxis.extraMax = 0.1;
                    
                    var series = chart3.series.push(new am4charts.ColumnSeries());
                    series.dataFields.categoryX = "category";
                    series.dataFields.valueY = "count";
                    series.tooltipText = "{valueY.value}"
                    series.columns.template.strokeOpacity = 0;
                    series.columns.template.column.cornerRadiusTopRight = 10;
                    series.columns.template.column.cornerRadiusTopLeft = 10;
                    var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                    labelBullet.label.verticalCenter = "bottom";
                    labelBullet.label.dy = -10;
                    labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#.')}";
                    
                    chart3.zoomOutButton.disabled = true;
                    
                    // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
                    series.columns.template.adapter.add("fill", function (fill, target) {
                     return chart3.colors.getIndex(target.dataItem.index);
                    });
                    
                    categoryAxis.sortBySeries = series;
                    
                    
                    /* Graph for Vaccinated Second Dose */
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end
                    
                    var chart4 = am4core.create("chartdiv4", am4charts.XYChart);
                    
                    chart4.data = response.secondDose;
                    
                    chart4.padding(40, 40, 40, 40);
                    
                    var categoryAxis = chart4.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.dataFields.category = "category";
                    categoryAxis.renderer.minGridDistance = 60;
                    categoryAxis.renderer.inversed = true;
                    categoryAxis.renderer.grid.template.disabled = true;
                    
                    categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
                        if (target.dataItem && target.dataItem.index & 2 == 2) {
                            return dy + 25;
                        }
                            return dy;
                    });
                    
                    var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.min = 0;
                    valueAxis.extraMax = 0.1;
                    
                    var series = chart4.series.push(new am4charts.ColumnSeries());
                    series.dataFields.categoryX = "category";
                    series.dataFields.valueY = "count";
                    series.tooltipText = "{valueY.value}"
                    series.columns.template.strokeOpacity = 0;
                    series.columns.template.column.cornerRadiusTopRight = 10;
                    series.columns.template.column.cornerRadiusTopLeft = 10;
                    var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                    labelBullet.label.verticalCenter = "bottom";
                    labelBullet.label.dy = -10;
                    labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#.')}";
                    
                    chart4.zoomOutButton.disabled = true;
                    
                    // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
                    series.columns.template.adapter.add("fill", function (fill, target) {
                     return chart4.colors.getIndex(target.dataItem.index);
                    });
                    
                    categoryAxis.sortBySeries = series;
                },
                complete: function(){
                    // processObject.hideProcessLoader();
                    indicator.hide();
                },
            });
            
            // Add data
            $.ajax({
                url:'{{ route('statistics.getPerBarangay') }}',
                type:'GET',
                data:{'dateFrom' : dateFrom, 'dateTo' : dateTo},
                dataType:'json',
                success:function(response){
                    chart.data = response.data;
                }
            });
        
            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "barangay";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            
            categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
              if (target.dataItem && target.dataItem.index & 2 == 2) {
                return dy + 25;
              }
              return dy;
            });
            
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            
            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "vaccinated";
            series.dataFields.categoryX = "barangay";
            series.name = "vaccinated";
            series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
            series.columns.template.fillOpacity = .8;
            
           
                function showIndicator() {
                  indicator = chart.tooltipContainer.createChild(am4core.Container);
                  indicator.background.fill = am4core.color("#fff");
                  indicator.background.fillOpacity = 0.8;
                  indicator.width = am4core.percent(100);
                  indicator.height = am4core.percent(100);
                  
                  var indicatorLabel = indicator.createChild(am4core.Label);
                  indicatorLabel.text = "Processing...";
                  indicatorLabel.align = "center";
                  indicatorLabel.valign = "middle";
                  indicatorLabel.fontSize = 20;
                    indicatorLabel.dy = 50;
                
                  var hourglass = indicator.createChild(am4core.Image);
                  hourglass.href = "https://s3-us-west-2.amazonaws.com/s.cdpn.io/t-160/hourglass.svg";
                  hourglass.align = "center";
                  hourglass.valign = "middle";
                  hourglass.horizontalCenter = "middle";
                  hourglass.verticalCenter = "middle";
                  hourglass.scale = 0.7;
                }
                
            var columnTemplate = series.columns.template;
            columnTemplate.strokeWidth = 2;
            columnTemplate.strokeOpacity = 1;
            this.animateNumbers();
        }); // end am4core.ready()
    }






    function animateNumbers(){
        $.ajax({
            url:'{{ route('statistics.get') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                const evaluatedPercent = (response['evaluatedCounter'] / response['preregisteredCounter']) * 100;
                const vaccinatedPercentFirstDose = (response['vaccinatedCounterFirstDose'] / response['evaluatedCounter']) * 100;
                const vaccinatedPercentSecondDose = (response['vaccinatedCounterSecondDose'] / response['evaluatedCounter']) * 100;
                
                animateCreator(response['preRegisteredToday'],'txtPreRegisteredToday');
                $("#txtDateToday").text(response['dateToday']);
                
                animateCreator(evaluatedPercent,'txtEvaluatedPercent');
                animateCreator(vaccinatedPercentFirstDose,'txtVaccinatedPercent');
                animateCreator(vaccinatedPercentSecondDose,'txtVaccinatedPercentSecondDose');
                

                animateCreator(response['preregisteredCounter'],'registered');
                animateCreator(response['evaluatedCounter'],'evaluated');
                animateCreator(response['vaccinatedCounterFirstDose'],'vaccinatedFirstDose');
                animateCreator(response['vaccinatedCounterSecondDose'],'vaccinatedSecondDose');
            }
        });
    }


    //animate 
    function animateCreator(numbers,resultId){
        jQuery({ Counter: 0 }).animate({ Counter: numbers }, {
            duration: 3000,
            easing: 'swing',
            step: function (now) {
                $('#'+ resultId +'').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:true}));
            }
        });
    }
</script>

    
@endsection
