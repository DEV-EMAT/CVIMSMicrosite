
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
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h3 class="card-title" style="font-weight: bold">Confirmed COVID-19 Cases</h3>
                        <p class="category">Updated at: August 28, 2020 - 17:08 pm</p>
                    </div> --}}
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
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
                            
                            <div class="col-md-3">
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
                            <div class="col-md-3">
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
            </div>
        </div>
    </div>

    
</div>
</div>

@endsection

@section('js')

    <script>
        $(document).ready(function(){
            $.ajax({
                url:'{{ route('covidtracer.allCases') }}',
                type:'GET',
                dataType:'json',
                success:function(response){
                    /* active */
                    jQuery({ Counter: 0 }).animate({ Counter: active }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $('#activeCounter').text(Math.ceil(now).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false}));
                        }
                    });
                }
            });
        });

    </script>
@endsection