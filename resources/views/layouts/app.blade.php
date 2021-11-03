<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
    <title>Enterprise Cabuyao</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Open+Sans:300,300i,400,400i,700,700i"
        rel="stylesheet">

    <!-- Bootstrap CSS File -->
    <link href="{{ asset('assets/webpage/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Libraries CSS Files -->
    <link href="{{ asset('assets/webpage/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/webpage/lib/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/webpage/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/webpage/lib/magnific-popup/magnific-popup.css') }}" rel="stylesheet">

    @include('layouts.widgets.style')

    @yield('css')
    <style>
    html {
        height: 100% !important;
    }
    body {
        height: 100% !important;
        margin: 0 !important;
        background-repeat: no-repeat !important;
        background-attachment: fixed !important;
    }
    </style>
</head> 
<body style="background:{{ isset($color)? $color:'white' }} background">
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->
    @yield('content')
</body>
@include('layouts.widgets.script')
@yield('js')
</html>
