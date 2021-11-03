
@extends('layouts.app2')
@section('location')
{{$title}}
@endsection
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" style="font-weight: bold">Confirmed COVID-19 Cases</h3>
                        <p class="category">Updated at: August 28, 2020 - 17:08 pm</p>
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big icon-success text-center">
                                                    <i class="fa fa-meh-o fa-3x fa-danger" style="color:#EB5E28"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers">
                                                    <p>Active Cases</p>
                                                    <i id="activeCounter" style="font-size: 50px">00</i>
                                                    <p id="newCases">(+0 new case)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big icon-success text-center">
                                                    <i class="fa fa-smile-o fa-3x"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers">
                                                    <p>Recovered</p>
                                                    <i id="recoveredCounter" style="font-size: 50px">00</i>
                                                    <p id="newRecovered">(+0 recoveries)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big icon-success text-center">
                                                    {{-- <i class="fas fa-sad-cry fa-3x fa-danger" style="color:#574d48"></i> --}}
                                                    <i class="fa fa-users fa-3x" style="color: gray"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers">
                                                    <p>Deceased Cases</p>
                                                    <i id="deceasedCounter" style="font-size: 50px">00</i>
                                                    <p id="newDeceased">(+0 deceased)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big icon-success text-center">
                                                    <i class="fa fa-users fa-3x" style="color: #3660d4"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers">
                                                    <p>Total Cases</p>
                                                    <i id="totalCaseCounter" style="font-size: 50px">00</i>
                                                    <p id="newConfirmed">(+0 cases)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" style="font-weight: bold">Suspect</h3>
                        <p class="category">Updated at: August 28, 2020 - 17:08 pm</p>
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big icon-success text-center">
                                                    <i class="fa fa-user-secret fa-3x fa-danger" style="color:#EB5E28"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers">
                                                    <p>Suspected Cases</p>
                                                    <i id="suspectedCounter" style="font-size: 50px">00</i>
                                                    <p id="newSuspected">(+0 new suspected)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big icon-success text-center">
                                                    <i class="fa fa-user-secret fa-3x"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers">
                                                    <p>Probable Cases</p>
                                                    <i id="probableCounter" style="font-size: 50px">00</i>
                                                    <p id="newProbable">(+0 probable cases)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" style="font-weight: bold">COVID 19 Epidemiology</h3>
                        <p class="category">Updated at: August 28, 2020 - 17:08 pm</p>
                    </div>
                    <div class="card-content">
                        <div id="chartdiv"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" style="font-weight: bold">BJMP (Confirmed Cases)</h3>
                        <p class="category">Updated at: August 28, 2020 - 17:08 pm</p>
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-success text-center">
                                        <i class="fa fa-users fa-3x fa-danger" style="color:#EB5E28"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        <p>Total Confirmed Cases</p>
                                        <i id="bjmpCounter" style="font-size: 50px">00</i>
                                        <p id="newBjmp">(+0 new case)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" style="font-weight: bold">COVID 19 Epidemiology for Active Cases</h3>
                        <p class="category">Updated at: August 28, 2020 - 17:08 pm</p>
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-content">
                                    <div id="linechartdiv"></div>
                                </div>
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

    <script src="{{ asset('assets/js/amchart/core.js') }}"></script>
    <script src="{{ asset('assets/js/amchart/charts.js') }}"></script>
    <script src="{{ asset('assets/js/amchart/animated.js') }}"></script>
    <script src="{{ asset('assets/js/amchart/material.js') }}"></script>
    <script>
        $(document).ready(function(){
            dashboard();
        });


        const dashboard = () => {
            //process loader true
            processObject.showProcessLoader();
            //user counter
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
                    
                    $('#newCases').text('(+'+newCases+' new case)');
                    $('#newRecovered').text('(+'+newRecovered+' new recovered)');
                    $('#newDeceased').text('(+'+newDeceased+' new deceased)');
                    $('#newConfirmed').text('(+'+newConfirmed+' cases)');
                    $('#newSuspected').text('(+'+newSuspect+' new suspected)');
                    $('#newProbable').text('(+'+newProbable+' new probable)');
                    $('#newBjmp').text('(+'+newBjmp+' new BJMP)');
                    $('.category').text('Updated at: '+ latestDate + ' - ' + latestTime);

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