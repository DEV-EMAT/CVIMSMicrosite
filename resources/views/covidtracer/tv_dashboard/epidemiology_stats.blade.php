
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

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    
                    <p class="category">?</p>
                </div>
                <div class="card-content">
                    <div id="chartdiv"></div>
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
                    //process loader false
                    processObject.hideProcessLoader();
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
                    
                    $('.category').html('<b> Updated at: '+ latestDate + ' - ' + latestTime+ '</b>');

                   
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
                }
            });
        }

    </script>
@endsection