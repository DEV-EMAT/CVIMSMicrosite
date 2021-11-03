@extends('layouts.app',['color'=>'linear-gradient(45deg, #d4d10b, #21b407)'])

@section('content')
    <section id="login">
      <div class="container">
        <div class="text-center mt-4">
          <img src="{{ asset('assets/new-template/img/logo.png') }}" style="max-width: 12%; min-width: 130px;" alt=""/>
            <div class="section-header">
              <h3 style="color: whitesmoke !important;">Enterprising Cabuyao</h3>
                <p style="color: whitesmoke !important;">A modernization project of City of Cabuyao.</p>
            </div>
          </div>
          <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
              <form class="col-lg-6 form form-card" action="" method="post" role="form">
                @csrf
                <div class="section-header">
                  <h3>Login</h3>
                  <p style="margin:0 !important;">Enter your details below and get connected!</p>
                </div>

                @error('email')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Error! </strong>{{ $message }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @enderror

                <div class="form-group">
                    <input id="email" type="text" class="form-control fadeIn second" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control fadeIn third"
                    name="password" required autocomplete="current-password" placeholder="Enter your Password">
                </div>

              <div class="text-center">
                  <input type="submit" class="fadeIn fourth" value="{{ __('Login') }}">
              </div>
                <br>
              <div class="text-center">
                <a href="{{ route('website_home') }}">HOME</a>
              </div>
            </form>   
          </div>
        </div>
      </div>
    </section>
    @include('layouts.widgets.footer')
@endsection

@section('css')
<style>
    #footer { background-color: transparent !important; }
    /* .social-links a { color: #fff; } */
    #login img { margin-bottom: 10px !important; }
    #main { margin-bottom: 20px !important; }
</style>
@endsection
