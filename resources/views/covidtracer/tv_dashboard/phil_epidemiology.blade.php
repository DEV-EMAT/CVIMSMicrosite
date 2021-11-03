
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
    blockquote .small:before, blockquote footer:before, blockquote small:before {
    content: '+ ';
}

/* .card-redesign{
    border-radius: 25px;
    height: 180px; 
}
.row-redesign{
    border-radius: 25px ;
    
} */
.card{
    border-radius: 5px !important;
    /* height: 150px !important;  */
}
.card-footer {
    padding: 11px 15px 15px !important;
    border-bottom-left-radius: 5px !important;
    border-bottom-right-radius: 5px !important;
}
</style>
@endsection
@section('content')
<div class="content">
    <br>
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card row-redesign">
                    <div class="card-header">
                        <h4 class="card-title"><img style="width: 40px;" src="{{ asset('assets/image/flag.png') }}" /> <b>Philippines Covid 19 Statistics</b></h4>
                    
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-redesign" style="background-color:#00c0ef;">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big text-center" style="color: #009abf;">
                                                    <i class="fa fa-users" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers" style="color: #009abf;">
                                                    <p><b>Active Cases</b></p>
                                                    <b id="philActiveCases">00</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: #009abf;">
                                        <hr>
                                    </div>
                                </div>                         
                            </div>

                            <div class="col-md-3">
                                <div class="card card-redesign" style="background-color:rgb(243, 156, 18);">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big text-center" style="color: #c27d0e">
                                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers" style="color: #c27d0e">
                                                    <p><b>Today Cases</b></p>
                                                    <b id="philTodayCases">00</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: #c27d0e">
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3" >
                                <div class="card card-redesign" style="background-color:#ff6666;">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big text-center" style="color: #b13c2e;">
                                                    <i class="fa fa-bed" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers" style="color:#b13c2e;">
                                                    <p><b>Total Deaths</b></p>
                                                    <b id="philTotalDeaths">00</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: #b13c2e">
                                        <hr>
                                    </div>
                                </div> 
                            </div>
    
                            <div class="col-md-3">
                                <div class="card card-redesign" style="background-color:#b3b3b3;">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big text-center" style="color: #6d6d6d;">
                                                    <i class="fa fa-bed" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers" style="color: #6d6d6d;">
                                                    <p><b>Today Deaths</b></p>
                                                    <b id="philTodayDeaths">00</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: #6d6d6d;">
                                        <hr>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-redesign" style="background-color:#41ad41;">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big text-center" style="color:#237723;">
                                                    <i class="fa fa-child" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers" style="color:#237723;">
                                                    <p><b>Total Recovered</b></p>
                                                    <b id="philTotalRecovered">00</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: #237723;">
                                        <hr> 
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="card card-redesign" style="background-color:rgb(243, 156, 18);;">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="icon-big text-center" style="color:#c27d0e">
                                                    <i class="fa fa-child" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-xs-7">
                                                <div class="numbers" style="color:#c27d0e">
                                                    <p><b>Today Recovered</b></p>
                                                    <b id="philToRecovered">00</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color:#c27d0e">
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <b><div class="col-md-3" ></div>
                            <div class="col-md-9" ><a href="#" target="blank_" class="pull-right" id="philSource"></a></div></b>
                        </div>
                    </div>
                </div>
            </div>

            {{-- break line --}}
            <div class="col-md-6">
                <div class="card row-redesign">
                    <div class="card-header">
                        <h4 class="card-title"><img style="width: 40px;" src="{{ asset('assets/image/globe.png') }}" /> <b>Global Covid 19 Statistics</b></h4>
                       
                    </div>
                    <div class="card-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card card-redesign" style="background-color:#00c0ef;">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <div class="icon-big text-center" style="color:#009abf;">
                                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="col-xs-7">
                                                    <div class="numbers" style="color: #009abf;">
                                                        <p><b>Infected</b></p>
                                                        <b id="total_cases">00</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer" style="background-color: #009abf;">
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"> 
                                    <div class="card card-redesign" style="background-color: #41ad41;">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <div class="icon-big text-center" style="color: #237723;">
                                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="col-xs-7">
                                                    <div class="numbers" style="color:#237723;;">
                                                        <p><b>Recovered</b></p>
                                                        <b id="total_recovered">00</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer" style="background-color: #237723;">
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card card-redesign" style="background-color:#ff6666;">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <div class="icon-big text-center" style="color:#b13c2e;">
                                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="col-xs-7">
                                                    <div class="numbers" style="color:#b13c2e;">
                                                        <p><b>Deaths</b></p>
                                                        <b id="total_deaths">00</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer" style="background-color: #b13c2e;">
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6" >
                                    <div class="card card-redesign" style="background-color:#00c0ef;">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <div class="icon-big text-center" style="color:#009abf">
                                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="col-xs-7">
                                                    <div class="numbers" style="color: #009abf">
                                                        <p><b>New Cases Today</b></p>
                                                        <b id="total_new_cases_today">00</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer" style="background-color: #009abf">
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-redesign" style="background-color:#ff6666;">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <div class="icon-big text-center" style="color:  #b13c2e;">
                                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="col-xs-7">
                                                    <div class="numbers" style="color:  #b13c2e;">
                                                        <p><b>New Deaths Today</b></p>
                                                        <b id="total_new_deaths_today">00</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer" style="background-color: #b13c2e;">
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <hr>
                            {{-- <span><b><em id="lastUpdated">asdasdasdasd</em></b></span>
                            <span class="ml-auto"><b><em id="lastUpdated2">asdasdasdasd</em></b></span> --}}
                                <div class="row">
                                    <b><div class="col-md-6" ></div>
                                    <div class="col-md-6" ><a href="https://thevirustracker.com/" target="blank_" class="pull-right" id="source"></a></div></b>
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
  
<script>


$(document).ready(function () {
    globalStatistics();
    philippinesStatistics();
});

function philippinesStatistics(){

   
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "https://corona.lmao.ninja/v2/countries/philippines?yesterday=true&strict=true&query=",
        success: function (data) {
            animateNumbers(data.deaths,'philTotalDeaths');
            animateNumbers(data.todayDeaths,'philTodayDeaths');
            animateNumbers(data.active,'philActiveCases');
            animateNumbers(data.todayCases,'philTodayCases');
            animateNumbers(data.todayRecovered,'philToRecovered');
            animateNumbers(data.recovered,'philTotalRecovered');
            var conDate = Date(data.updated)
            //var d = conDate.format("mm/dd/yy");
            // date_timestamp_set($conDate,1371803321);
            // date_format($date,"U = Y-m-d H:i:s");
            
            $('#philSource').text("Updated : " + conDate);
            processObject.hideProcessLoader();

        },
        error: function (error) {
            jsonValue = jQuery.parseJSON(error.responseText);
            alert("error" + error.responseText);
            processObject.hideProcessLoader();
        }
    });
 }



 function globalStatistics(){
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "https://api.thevirustracker.com/free-api?global=stats",
        success: function (data) {
            var total_cases = data['results'][0].total_cases,
            total_recovered = data['results'][0].total_recovered,
            total_unresolved = data['results'][0].total_unresolved,
            total_deaths = data['results'][0].total_deaths,
            total_new_cases_today = data['results'][0].total_new_cases_today,
            total_new_deaths_today = data['results'][0].total_new_deaths_today,
            total_active_cases = data['results'][0].total_active_cases,
            total_serious_cases = data['results'][0].total_serious_cases,
            total_affected_countries = data['results'][0].total_affected_countries,
            source = data['results'][0].source.url;
            
            animateNumbers(total_cases,'total_cases');
            animateNumbers(total_recovered,'total_recovered');
            animateNumbers(total_unresolved,'total_unresolved');
            animateNumbers(total_deaths,'total_deaths');
            animateNumbers(total_new_cases_today,'total_new_cases_today');
            animateNumbers(total_new_deaths_today,'total_new_deaths_today');
            animateNumbers(total_active_cases,'total_active_cases');
            animateNumbers(total_serious_cases,'total_serious_cases');
            animateNumbers(total_affected_countries,'total_affected_countries');
            $('#source').text("Source: " + source);
            processObject.hideProcessLoader();
        },
        error: function (error) {
            jsonValue = jQuery.parseJSON(error.responseText);
            alert("error" + error.responseText);
            processObject.hideProcessLoader();
        }
    });
 }



 function animateNumbers(numbers,resultId){
    /* active */
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