<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<!-- Favicons -->
    <link href="{{asset('assets/new-template/img/website-icon.png')}}" rel="icon" />
    <link href="{{asset('assets/new-template/img/website-icon.png')}}" rel="apple-touch-icon" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
     <meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Enterprise Cabuyao</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<meta content="" name="keywords" />
    <meta content="" name="description" />
	<!-- mapbox     -->
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<script src="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.js"></script>
	<link href="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.css" rel="stylesheet" />
	<script src="{{asset('assets/js/geolocation/geolocation.js')}}" type="text/javascript"></script>
	<!-- mapbox     -->

     <!-- Bootstrap core CSS     -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />

    <!--  Paper Dashboard core CSS    -->
	<link href="{{asset('assets/css/paper-dashboard.css')}}" rel="stylesheet"/>

	<link href="{{asset('assets/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{asset('assets/css/demo.css')}}" rel="stylesheet" />

	
    <!--  Fonts and icons     -->
	<link href="{{asset('assets/fonts/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href='{{asset('assets/fonts/font-awesome/fonts/fontawesome-webfont.ttf')}}' rel='stylesheet' type='text/css'>
	<link href="{{asset('assets/css/themify-icons.css')}}" rel="stylesheet">
	<script src="{{asset('assets/js/process-loader.js')}}" type="text/javascript"></script>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
	
	<script>
		let processObject = new ProcessLoader();
	</script>
	<style>

		.nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
			background-color: #ff9900 !important;
		}
		#fakeloader-overlay {
			opacity: 0;
			top: 0px;
			left: 0px;
			position: fixed;
			background-color: rgba(0, 0, 0, 0.3);
			height: 100%;
			width: 100%;
			z-index: 9998;
			-webkit-transition: opacity 0.2s linear;
			-moz-transition: opacity 0.2s linear;
			transition: opacity 0.2s linear;
		}

		#fakeloader-overlay.visible {
			opacity: 1;
		}

		#fakeloader-overlay.hidden {
			opacity: 0;
			height: 0px;
			width: 0px;
			z-index: -10000;
		}

		#fakeloader-overlay .loader-wrapper-outer {
			background-color: transparent;
			z-index: 9999;
			margin: auto;
			width: 100%;
			height: 100%;
			overflow: hidden;
			display: table;
			text-align: center;
			vertical-align: middle;
		}

		#fakeloader-overlay .loader-wrapper-inner {
			display: table-cell;
			vertical-align: middle;
		}

		#fakeloader-overlay .loader {
			margin: auto;
			font-size: 10px;
			position: relative;
			text-indent: -9999em;
			border-top: 8px solid rgba(255, 255, 255, 0.5);
			border-right: 8px solid rgba(255, 255, 255, 0.5);
			border-bottom: 8px solid rgba(255, 255, 255, 0.5);
			border-left: 8px solid #AAA;
			-webkit-transform: translateZ(0);
			-ms-transform: translateZ(0);
			transform: translateZ(0);
			-webkit-animation: 	 1.1s infinite linear;
			animation: fakeloader 1.1s infinite linear;
		}

		#fakeloader-overlay .loader, #fakeloader-overlay .loader:after {
			border-radius: 50%;
			width: 80px;
			height: 80px;
		}

	/*loader save*/
	#fakeloader-overlay-save {
			opacity: 0;
			top: 0px;
			left: 0px;
			position: fixed;
			background-color: rgba(0, 0, 0, 0.3);
			height: 100%;
			width: 100%;
			z-index: 9998;
			-webkit-transition: opacity 0.2s linear;
			-moz-transition: opacity 0.2s linear;
			transition: opacity 0.2s linear;
		}

		#fakeloader-overlay-save.visible {
			opacity: 1;
		}

		#fakeloader-overlay-save.hidden {
			opacity: 0;
			height: 0px;
			width: 0px;
			z-index: -10000;
		}

		#fakeloader-overlay-save .loader-wrapper-outer-save {
			background-color: transparent;
			z-index: 9999;
			margin: auto;
			width: 100%;
			height: 100%;
			overflow: hidden;
			display: table;
			text-align: center;
			vertical-align: middle;
		}

		#fakeloader-overlay-save .loader-wrapper-inner-save {
			display: table-cell;
			vertical-align: middle;
		}

		#fakeloader-overlay-save .loader {
			margin: auto;
			font-size: 10px;
			position: relative;
			text-indent: -9999em;
			border-top: 8px solid rgba(255, 255, 255, 0.5);
			border-right: 8px solid rgba(255, 255, 255, 0.5);
			border-bottom: 8px solid rgba(255, 255, 255, 0.5);
			border-left: 8px solid #AAA;
			-webkit-transform: translateZ(0);
			-ms-transform: translateZ(0);
			transform: translateZ(0);
			-webkit-animation: fakeloader 1.1s infinite linear;
			animation: fakeloader 1.1s infinite linear;
		}

		#fakeloader-overlay-save .loader, #fakeloader-overlay-save .loader:after {
			border-radius: 50%;
			width: 80px;
			height: 80px;
		}


	</style>
	@yield('style')

</head>

<body onafterprint="afterPrint()">
	<div class="wrapper">

        @include('layouts.sidebar')

	    <div class="main-panel">

            @include('layouts.navbar')

	        <div class="content">

				@yield('content')

			</div>

			@include('layouts.footer')
	    </div>
	</div>
	<div id="save-loader" hidden="true">
		<div id="fakeloader-overlay-save" class="visible incoming">
			<div class="loader-wrapper-outer-save">
			<div class="loader-wrapper-inner-save">
				<img height="120px" src="{{asset('assets/image/loader.gif')}}">
			</div>
			</div>
		</div>
	</div>

	<div id="fakeloader-overlay" class="visible incoming">
		<div class="loader-wrapper-outer">
		<div class="loader-wrapper-inner">
			<img height="120px" src="{{asset('assets/image/loader.gif')}}">
		</div>
		</div>
	</div>
	
	<!--Change password modal -->
	<div class="modal fade" id="modal_password" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">

				<div class="modal-header bg-danger">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-danger" id="modalLabelSmall"><i class="fa fa-exclamation-triangle"></i> System Notification!</h4>
					
				</div>

				<div class="modal-body">
					To secure your account please change your password.<br><br>
				
					<div class="card-content">
						<form id="password_form">
							@csrf
							@method('POST')
							<div class="form-group">
								<label>New Password</label>
								<div id="show_hide_password">
									<input class="form-control" type="password" name="new_password" id="new_password">
								</div>
							</div>

							<div class="form-group">
								<label>Confirm Password</label>
								<div class="input-group" id="show_hide_password">
										<input class="form-control" type="password" name="confirm_new_password" id="confirm_new_password">
									<div class="input-group-addon">
										<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>

							<div class="card-footer text-center">
								<button id="change_password" class="btn btn-fill btn-info"><i class="fa fa-shield"></i> Change</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>


	<!--   Core JS Files. Extra: TouchPunch for touch library inside jquery-ui.min.js   -->
	<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
	<script src="{{asset('assets/js/jquery-ui.min.js')}}" type="text/javascript"></script>
	<script src="{{asset('assets/js/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
	<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>

	<!--  Forms Validations Plugin -->
	<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
{{--
	<script src="{{asset('assets/bootstrap-fileinput/js/plugins/piexif.min.js')}}"></script>
	{{-- <script src="{{asset('assets/bootstrap-fileinput/js/plugins/popper.min.js')}}"></script> --}}
	<script src="{{asset('assets/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
	<!-- Promise Library for SweetAlert2 working on IE -->
	<script src="{{asset('assets/js/es6-promise-auto.min.js')}}"></script>

	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="{{asset('assets/js/moment.min.js')}}"></script>

	<!--  Date Time Picker Plugin is included in this js file -->
	<script src="{{asset('assets/js/bootstrap-datetimepicker.js')}}"></script>

	<!--  Select Picker Plugin -->
	<script src="{{asset('assets/js/bootstrap-selectpicker.js')}}"></script>

	<!--  Switch and Tags Input Plugins -->
	<script src="{{asset('assets/js/bootstrap-switch-tags.js')}}"></script>

	<!--  Notifications Plugin    -->
	<script src="{{asset('assets/js/bootstrap-notify.js')}}"></script>

	<!-- Sweet Alert 2 plugin -->
	<script src="{{asset('assets/js/sweetalert2.js')}}"></script>

	<!-- Wizard Plugin    -->
	<script src="{{asset('assets/js/jquery.bootstrap.wizard.min.js')}}"></script>

	<!--  Bootstrap Table Plugin    -->
	<script src="{{asset('assets/js/bootstrap-table.js')}}"></script>

	<!--  Plugin for DataTables.net  -->
	<script src="{{asset('assets/js/jquery.datatables.js')}}"></script>

	<!--  Full Calendar Plugin    -->
	<script src="{{asset('assets/js/fullcalendar.min.js')}}"></script>

	<!-- Paper Dashboard PRO Core javascript and methods for Demo purpose -->
	<script src="{{asset('assets/js/paper-dashboard.js')}}"></script>

	<!-- Paper Dashboard PRO DEMO methods, don't include it in your project! -->
	<script src="{{ asset('assets/js/demo.js') }}"></script>

	<!--<script src="{{ asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>-->
	
	{{-- this is for pusher updates --}}
	{{-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}
	{{-- <script src="../js/app.js"></script> --}}

	<script type="text/javascript">
		$(document).ready(function(){
			$('body').tooltip({
				selector: '[data-toggle="tooltip"]'
			});
			// Set trigger and container variables
			var trigger = $('.nav li a'),
				container = $('.content');

			// Fire on click
			trigger.on('click', function(){
				// Set $this for re-use. Set target from data attribute
				var $this = $(this),
				target = $this.data('target');

				// Load target page into container
				container.load(target);
				//alert(target);

				// Stop normal link behavior
				return false;
			});

			$('.datetimepicker').datetimepicker({
				format: 'MM/DD/YYYY',    //use this format if you want the 12hours timpiecker with AM/PM toggle
				icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-chevron-up",
					down: "fa fa-chevron-down",
					previous: 'fa fa-chevron-left',
					next: 'fa fa-chevron-right',
					today: 'fa fa-screenshot',
					clear: 'fa fa-trash',
					close: 'fa fa-remove'
				}
			});
			
			$('#modal_password').on('hidden.bs.modal', function () {
                sessionStorage.setItem("firstLogIn", "0");
			});
			
			if(sessionStorage.getItem("firstLogIn") != "0"){
				checkPassword();
			}
			//change password
			$("#password_form").validate({
				rules: {
					new_password: {
						minlength: 5,
						required: true
					},
					confirm_new_password: {
						minlength: 5,
						required: true
					},
				},
				submitHandler: function (form) {
					if($("#new_password").val() == $("#confirm_new_password").val()){
						Swal.fire({
							title: 'Update Now?',
							text: "Update data!",
							type: 'question',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, save it!'
						}).then((result) => {
							if (result.value) {
								var id = {{ Auth::user()->id }};

								var formData = new FormData($("#password_form").get(0));
								//process loader true
								$.ajax({
									url: '/account/changepassword/'+ id,
									type: "POST",
									data: formData,
									cache:false,
									contentType: false,
									processData: false,
									dataType: "JSON",
									beforeSend: function(){
										processObject.showProcessLoader();
									},
									success: function (data) {
										if (data.success) {
											Swal.fire({
												title: 'Updated Successfully !',
												type: 'success'
											}).then(function () {
												$.ajax({
													url : '{{ route('logout') }}',
													type: "POST",
													data:{ _token: "{{csrf_token()}}"},
													dataType: "JSON",
													beforeSend: function(){
														processObject.showProcessLoader();
													},
													success:function(response){
														if(response){
															window.location.reload();
														}
													},
													complete: function(){
														processObject.hideProcessLoader();
													},
												});
											});
										} else {
											swal.fire({
												title: "Oops! something went wrong.",
												text: data.messages,
												type: "error"
											})
										}
									},
									error: function (jqXHR, textStatus, errorThrown) {
										swal.fire({
											title: "Oops! something went wrong.",
											text: errorThrown,
											type: "error"
										})
									},
									complete: function(){
										processObject.hideProcessLoader();
									},
								});
							}
						})
					}
					else{
						Swal.fire({
							title: 'Password do not match !',
							type: 'warning'
						});
					}
				}
			});
		});

		let datatable;
		// Pusher.logToConsole = true;

		// for (let index = 0; index < 50; index++) {
		// var pusher = new Pusher('8264b9ada9cde02c19cc', {
		// 	cluster: 'ap1',
		// });

		// var channel = pusher.subscribe('my-channel');
		// channel.bind('my-event', function(data) {
		// 	console.log('pusher load');
		// 	datatable.ajax.reload( null, false );
		// });

		$(window).on('load', function(){
			setTimeout(function(){
				$( "#fakeloader-overlay" ).fadeOut(300, function() {
					$( "#fakeloader-overlay" ).remove();
				});
			}, 100);
			//1000
		});
		
			//show password
	$("#show_hide_password a").on('click', function(event) {
		event.preventDefault();
		if($('#show_hide_password input').attr("type") == "text"){
			$('#show_hide_password input').attr('type', 'password');
			$('#show_hide_password input').attr('class', 'form-control');
			$('#show_hide_password i').addClass( "fa-eye-slash" );
			$('#show_hide_password i').removeClass( "fa-eye" );
		}else if($('#show_hide_password input').attr("type") == "password"){
			$('#show_hide_password input').attr('type', 'text');
			$('#show_hide_password input').attr('class', 'form-control');
			$('#show_hide_password i').removeClass( "fa-eye-slash" );
			$('#show_hide_password i').addClass( "fa-eye" );
		}
	});

    /* add options */
    const checkPassword = () => {
        $.ajax({
            url:'{{ route('account.check-password') }}',
            type:'GET',
            success:function(response){
                if(response.success){
					$('#modal_password').modal('show');
				}
            }
        })
    }
    
	</script>
	@yield('js')
</html>
