@extends('layouts.app2',['color'=>'white'])
@section('location')
{{ $title }}
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row" style="margin-top:200px">
            <div class="col-md-12 text-center">
                @if($success)
                    <img src="{{ asset('assets/image/check.gif') }}" height="100px">
                @else
                    <img src="{{ asset('assets/image/giphy.gif') }}" height="100px">
                @endif
                <h4>{{ $message }}</h4>
                <div align="center">
                    <p style="width:70%;text-align:center">{!! $description !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection