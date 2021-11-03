
@extends('layouts.without_sidebar')

@section('style')
<style>
    #chartdiv {
      width: 100%;
      height: 640px;
    }

    #linechartdiv {
      width: 100%;
      height: 670px;
    }
</style>
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-4 col-sm-6">
            </div>
            
            <div class="col-lg-4 col-sm-6">
                <div class="card" style="background-color:rgb(71, 71, 71);">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big text-center" style="color: whitesmoke;">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" style="color: whitesmoke;">
                                    <p><b>Total Confirmed Cases</b></p>
                                    <b>00</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr >
                        <div class="stats" style="color: whitesmoke;">
                            <i class="ti-calendar"></i> <span class="category"  style="color: whitesmoke;" >?</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
            </div>
        </div>


        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card" style="background-color:#ff9710;">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big text-center" style="color: whitesmoke;">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" style="color: whitesmoke;">
                                    <p><b>New Cases</b></p>
                                    <b id="newCases">00</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" >
                        <hr >
                        <div class="stats" style="color: whitesmoke;">
                            <i class="ti-calendar"></i> <span class="category"  style="color: whitesmoke;" >?</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card" style="background-color:rgb(0, 81, 255);">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big text-center" style="color: whitesmoke;">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" style="color: whitesmoke;">
                                    <p><b>Active Cases</b></p>
                                    <b id="activeCounter">00</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr >
                        <div class="stats" style="color: whitesmoke;">
                            <i class="ti-calendar"></i> Last Updated<b>  </b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card" style="background-color:rgb(0, 136, 11);">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big text-center" style="color: whitesmoke;">
                                    <i class="fa fa-child" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" style="color: whitesmoke;">
                                    <p><b>Recovered</b></p>
                                    <b id="recoveredCounter">00</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr >
                        <div class="stats" style="color: whitesmoke;">
                            <i class="fa fa-medkit"></i> <b id="newRecovered">(+0 deceased)</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card" style="background-color:rgb(255, 67, 9);">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big text-center" style="color: whitesmoke;">
                                    <i class="fa fa-bed" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" style="color: whitesmoke;">
                                    <p><b>Deceased</b></p>
                                    <b id="deceasedCounter">00</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr >
                        <div class="stats" style="color: whitesmoke;">
                            <i class="fa fa-medkit"></i> <b id="newDeceased">(+0 deceased)</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card" style="background-color:#fdf0d5;">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-warning text-center">
                                    <i class="fa fa-question" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p><b>Suspected</b></p>
                                    <b id="suspectedCounter" >00</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr >
                        <div class="stats">
                            <i class="fa fa-thermometer-full" aria-hidden="true"></i> <b id="newSuspected">  </b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card" style="background-color:rgb(255, 211, 211);">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-danger text-center">
                                    <i class="fa fa-question" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p><b>Probable</b></p>
                                    <b>102</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr >
                        <div class="stats">
                            <i class="ti-calendar"></i> Last Updated<b>  </b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card" style="background-color:rgb(219, 219, 219);">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-active text-center">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p><b>BJMP (Confirmed Cases)</b></p>
                                    <b>102</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr >
                        <div class="stats">
                            <i class="ti-calendar"></i> Last Updated<b>  </b>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card card-circle-chart" style="background-color:rgb(218, 218, 218)">
                    <div class="card-header text-center">
                        <h4 class="card-title"><b>1000</b></h4>
                        <p class="description">Test Done Today</p>
                    </div>
                    <div class="card-content">
                        <div id="chartDashboard" class="chart-circle" data-percent="70">
                            <div class="icon-big icon-active text-center">
                                <i class="fa fa-thermometer-half" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card card-circle-chart" style="background-color:rgb(218, 218, 218)">
                    <div class="card-header text-center">
                        <h4 class="card-title"><b>1000</b></h4>
                        <p class="description">Total Test Done</p>
                    </div>
                    <div class="card-content">
                        <div id="chartDashboard" class="chart-circle" data-percent="70">
                            <div class="icon-big icon-active text-center">
                                <i class="fa fa-user-md" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card card-circle-chart" style="background-color:rgb(218, 218, 218);">
                    <div class="card-header text-center">
                        <h4 class="card-title"><b>2000</b></h4>
                        <p class="description">Total Number of Covid-19 Patients</p>
                    </div>
                    <div class="card-content">
                        <div id="chartDashboard" class="chart-circle" data-percent="70">
                            <div class="icon-big icon-active text-center">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    
</div>
</div>

@endsection

@section('js')

    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/material.js"></script>
    <script>
        $(document).ready(function(){
            dashboard();
        });


        const dashboard = () => {
            
            //user counter
            //process loader true
            processObject.showProcessLoader();
            $.ajax({
                url:'{{ route('covidtracer.allCases') }}',
                type:'GET',
                dataType:'json',
                success:function(response){
                    let active = recovered = deceased = totalcase = suspected = probable = bjmp = newCases = newRecovered = newDeceased = newConfirmed = newSuspect = newProbable = newBjmp = latestDate = latestTime = 0 ;
                    let data = [];
                    let lineGraphData = [];

                    let keys = Object.keys(response[1]);
                    keys.forEach((value) => {
                        // console.log(response[1][value]);
                        lineGraphData.push({date:value, value: response[1][value]});
                    });

                    response[0].forEach(value => {
                        active += value.active;
                        recovered += value.recovered;
                        deceased += value.deceased;
                        totalcase += value.totalCases;
                        suspected += value.suspected;
                        probable += value.probable;
                        bjmp += value.bjmp;

                        newCases += value.newCases;
                        newRecovered += value.newRecovered;
                        newDeceased += value.newDeceased;
                        newConfirmed += value.newConfirmed;
                        newSuspect += value.newSuspect;
                        newProbable += value.newProbable;
                        newBjmp += value.newBjmp;
                        latestDate = value.latestDate;
                        latestTime = value.latestTime;
                        
                        // console.log(value.latestDate);

                        data.push({ barangay: value.barangay, activeCase: value.active, date: value.latestDate})
                        
                    });
                    
                    $('#newCases').text('(+'+newCases+')');
                    $('#newRecovered').text('(+'+newRecovered+' new recovered)');
                    $('#newDeceased').text('(+'+newDeceased+' new deceased)');
                    $('#newConfirmed').text('(+'+newConfirmed+' cases)');
                    $('#newSuspected').text('(+'+newSuspect+' new suspected)');
                    $('#newProbable').text('(+'+newProbable+' new probable)');
                    $('#newBjmp').text('(+'+newBjmp+' new BJMP)');
                    $('.category').html('<b>Updated at: '+ latestDate + ' - ' + latestTime + '</b>');

                    /* active */
                    jQuery({ Counter: 0 }).animate({ Counter: active }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#activeCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });
                     /* recovered */
                     jQuery({ Counter: 0 }).animate({ Counter: recovered }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#recoveredCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });

                    /* deceased */
                    jQuery({ Counter: 0 }).animate({ Counter: deceased }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#deceasedCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });

                    /* total case */
                    jQuery({ Counter: 0 }).animate({ Counter: totalcase }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#totalCaseCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });

                    /* suspected case */
                    jQuery({ Counter: 0 }).animate({ Counter: suspected }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#suspectedCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });

                    /* probable case */
                    jQuery({ Counter: 0 }).animate({ Counter: probable }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#probableCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });

                    /* bjmp case */
                    jQuery({ Counter: 0 }).animate({ Counter: bjmp }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#bjmpCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });


                    am4core.ready(function() {

                        // Themes begin
                        am4core.useTheme(am4themes_animated);
                        // Themes end

                        var chart = am4core.create("chartdiv", am4charts.PieChart3D);
                        chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

                        chart.legend = new am4charts.Legend();

                        chart.data = data;

                        var series = chart.series.push(new am4charts.PieSeries3D());
                        series.dataFields.value = "activeCase";
                        series.dataFields.category = "barangay";

                    }); // end am4core.ready()

                    am4core.ready(function() {

                        am4core.useTheme(am4themes_material);
                        am4core.useTheme(am4themes_animated);
                        // Themes end

                        var chart = am4core.create("linechartdiv", am4charts.XYChart);

                        chart.data = lineGraphData;

                        // Create axes
                        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                        dateAxis.renderer.minGridDistance = 60;

                        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                        valueAxis.renderer.minWidth = 35;

                        // Create series
                        var series = chart.series.push(new am4charts.LineSeries());
                        series.dataFields.valueY = "value";
                        series.dataFields.dateX = "date";
                        series.tooltipText = "{value}"

                        series.tooltip.pointerOrientation = "vertical";

                        chart.cursor = new am4charts.XYCursor();
                        chart.cursor.snapToSeries = series;
                        chart.cursor.xAxis = dateAxis;

                        //chart.scrollbarY = new am4core.Scrollbar();
                        chart.scrollbarX = new am4core.Scrollbar();
                    }); // end am4core.ready()
                    
                    //process loader false
                    processObject.hideProcessLoader();
                }
            });
        }

    </script>
@endsection