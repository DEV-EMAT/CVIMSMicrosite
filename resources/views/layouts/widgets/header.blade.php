<header id="header">
    <div class="container-fluid">
        <div id="logo" class="pull-left">
            <h1> 
                <a href="#intro" class="scrollto">E-CABUYAO</a>
            </h1>
        </div>

        <nav id="nav-menu-container">
            @if (Route::has('login'))
                <input type="hidden" value="<?php session()->flash('firstlogin', '0') ?>">
                <ul class="nav-menu">
                    @auth
                        <li class="menu-active"><a href="#intro">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                        <li><a href="{{ route('privacy_and_terms') }}">Privacy & Terms</a></li>
                        @php
                            $route = '/covidtracer/dashboard';
                        @endphp
                        <li><a href="{{ url($route) }}">Go to Dashboard <i class="fa fa-arrow-right" style="color:white"></i></a></li>
            @else
                <li class="menu-active"><a href="#intro">Home</a></li>
                    @if (Route::has('register'))
                        <li><a href="#register">Register</a></li>
                    @endif
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="#services">Services</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('privacy_and_terms') }}">Privacy & Terms</a></li>
                @endauth
            </ul>
            @endif
        </nav>
    </div>
</header>