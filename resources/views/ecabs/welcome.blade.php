<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Enterprising Cabuyao</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Favicons -->
    <link href="{{ asset('assets/new-template/img/website-icon.png') }}" rel="icon" />
    <link href="{{ asset('assets/new-template/img/website-icon.png') }}" rel="apple-touch-icon" />
    
    @include('layouts.widgets.style')
</head>
<body>
    {{-- <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
    window.fbAsyncInit = function() {
        FB.init({
        xfbml            : true,
        version          : 'v8.0'
        });
    };

    (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <!-- Your Chat Plugin code -->
    <div class="fb-customerchat"
    attribution=install_email
    page_id="118581353310665"
    theme_color="#67b868">
    </div> --}}
    
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->
    
    @include('layouts.widgets.header')
    @include('layouts.widgets.carousel')
    <main id="main">
        @include('layouts.widgets.about_us')
        @include('layouts.widgets.services')
        @include('layouts.widgets.features')
        @include('layouts.widgets.preview')
        @include('layouts.widgets.contact_us')
    </main>
    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    @include('layouts.widgets.footer')
    @include('layouts.widgets.script')
</body>
</html>

