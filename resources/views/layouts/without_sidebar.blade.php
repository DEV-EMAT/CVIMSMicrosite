<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/ecabsicon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/ecabsicon.png')}}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>eCabuyao | CABVax</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<!-- mapbox     -->
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<!-- mapbox     -->

     <!-- Bootstrap core CSS     -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />

    <!--  Paper Dashboard core CSS    -->
	<link href="{{asset('assets/css/paper-dashboard.css')}}" rel="stylesheet"/>

    <link href="{{asset('assets/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet"/>
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{asset('assets/css/demo.css')}}" rel="stylesheet" />

    <!--  Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
	<link href="{{asset('assets/css/themify-icons.css')}}" rel="stylesheet">
	<script src="{{asset('assets/js/process-loader.js')}}" type="text/javascript"></script>

	<script src="{{asset('assets/js/geolocation/geolocation.js')}}" type="text/javascript"></script>
	<script>
		let processObject = new ProcessLoader();
	</script>
	<style>

		body {
			color: #000000;
			font-size: 16px;
			font-family: sans-serif;
		}
		strong {
			color: #000000;
		}
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

		/* @-webkit-keyframes fakeloader {
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg);
			}

			100% {
				-webkit-transform: rotate(360deg);
				transform: rotate(360deg);
		}
		}

		@keyframes fakeloader {
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg);
			}

			100% {
				-webkit-transform: rotate(360deg);
				transform: rotate(360deg);
			}
		} */
		.home-header{
			height: 150px;
		}
	</style>
	@yield('style')

</head>

<body >
    <div class="content" >
		{{-- <header style="background-color: #f1f1f1;" class="home-header">
			<div class="container clearfix text-center" > --}}
				{{-- <img height="120px" src="{{asset('assets/image/loader.gif')}}"> --}}
			{{-- </div>
		</header> --}}
		<!-- /header -->
		@yield('content')
		<footer class="footer footer-transparent">
			<div class="container">
				<div class="copyright">
					<b>&copy; Copyright <script>document.write(new Date().getFullYear());</script> CVIMS v2.0.0</b>, Enterprise Cabuyao.
                    <div class="credits">All Rights Reserved.</div>
					<div class="credits">Powered by <a href="#" class="webappliex">WebAppliExTeam</a></div>
				</div>
			</div>
		</footer>
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


	<script src="{{ asset('assets/js/cavasjs/jquery.canvasjs.min.js') }}"></script>

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
		});

		$(window).on('load', function(){
			setTimeout(function(){
				$( "#fakeloader-overlay" ).fadeOut(300, function() {
					$( "#fakeloader-overlay" ).remove();
				});
			}, 100);
			//1000
		});
	</script>
	@yield('js')

</html>
