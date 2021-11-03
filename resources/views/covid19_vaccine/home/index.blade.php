

@extends('layouts.without_sidebar')

@section('style')
<style>
.card-title {
    color: rgb(0, 0, 0) !important;
}
.card-text{
    color: rgb(0, 0, 0) !important;
}
.card-color{
    background-color: #cecece !important;
}

.btn {
    margin-bottom: 10px;
}

.image2{
   display: none;
}

@media only screen and (max-width: 500px){.image1{
     display: none;
   }

   .image2{
     display: block;
   }
}
.btn-success.btn-fill {
    color: #FFFFFF;
    background-color: #505050;
    border-color: #a5a5a5;
    opacity: 1;
    filter: alpha(opacity=100);
}

.btn-warning.btn-fill {
    color: #FFFFFF;
    background-color: #ffbb00;
    border-color: #a5a5a5;
    opacity: 1;
    filter: alpha(opacity=100);
}


.horizontally {
 height: 40px;	
 overflow: hidden;
 position: relative;
 background: rgb(9, 80, 0);
 color: rgb(255, 187, 0);
}
.horizontally p {
 letter-spacing: 2px;
 font-weight: bold;
 position: absolute;
 width: 100%;
 height: 100%;
 margin: 0;
 line-height: 40px;
 text-align: center;
 /* Starting position */
 -moz-transform:translateX(40%);
 -webkit-transform:translateX(40%);	
 transform:translateX(40%);
 /* Apply animation to this element */	
 -moz-animation: horizontally 10s linear infinite alternate;
 -webkit-animation: horizontally 10s linear infinite alternate;
 animation: horizontally 10s linear infinite alternate;
}
/* Move it (define the animation) */
@-moz-keyframes horizontally {
 0%   { -moz-transform: translateX(50%); }
 100% { -moz-transform: translateX(-50%); }
}
@-webkit-keyframes horizontally {
 0%   { -webkit-transform: translateX(50%); }
 100% { -webkit-transform: translateX(-50%); }
}
@keyframes horizontally {
 0%   { 
 -moz-transform: translateX(50%); /* Browser bug fix */
 -webkit-transform: translateX(50%); /* Browser bug fix */
 transform: translateX(50%); 		
 }
 100% { 
 -moz-transform: translateX(-50%); /* Browser bug fix */
 -webkit-transform: translateX(-50%); /* Browser bug fix */
 transform: translateX(-50%); 
 }
}
        

#searchRegisteredUser .row{
    margin-top:10px;
}
</style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-text text-center">
                    <img class="image1" width="100%" style="margin-top: 15px;" src="{{asset('assets/image/vaccine-home.jpg')}}">

                    <img class="image2" width="100%" style="margin-top: 15px;" src="{{asset('assets/image/home1.jpg')}}">
                    {{-- <br>
                    <p style="margin-bottom: 0%"><strong>Republic of the Philippines <br> City of Cabuyao, Provice of Laguna</strong></p><br> --}}
                    {{-- <p style="line-height:5px !important;"><strong>City of Cabuyao, Provice of Laguna</strong></p> --}}


                    {{-- <h5 ><strong>CABUYAO CITY COVID-19 VACCINE</strong></h5> --}}
                    {{-- <marquee class="marquee" behavior="alternate" >
                        <p> Ang CORONA ay KAYANG KAYA! Basta't TAYO ay SAMA-SAMA!</p>
                    </marquee> --}}
                    <div class="horizontally">
                        <p>Let's &nbsp; GO &nbsp; CabVax! &nbsp; MAGpabakuna &nbsp; na! ... Ang &nbsp; CORONA &nbsp; ay &nbsp; KAYANG &nbsp; KAYA! &nbsp; Basta't &nbsp; TAYO &nbsp; ay &nbsp; SAMA-SAMA! ...</p>
                    </div>
                    <hr>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group text-justify">   
                    Mga Mahal kong kababayan,<br> <br>

                    Ang pandemyang <strong>COVID-19</strong> na ating patuloy na kinakaharap ay lubos nang nakaapekto sa maraming buhay kaya nating hinahanap ang pinakamabisang paraan upang ito ay malabanan. Ayon sa mga pananaliksik ng mga siyentista, ang isa sa mahusay na panlaban dito ay ang pagkakaroon ng bakuna laban dito.<br><br>
                    
                    Tungkulin ko po bilang ama ng Lungsod ng Cabuyao na makagawa ng hakbang upang mabigyan ng proteksiyon ang bawat kababayan. Sa pagtataguyod ng <strong>World Health Organization</strong> at ng ating gobyerno ay minamabuti na ang mamamayan ay magpabakuna ng sa ganoon ay hindi mahawa ng nasabing pandemya.<br><br>
                    
                    Akin pong isinasaalang-alang ang inyong personal na desisyon, at ang pagbabakuna laban sa <strong>COVID-19</strong> ay pinagkasunduang mahusay na solusyon sa kasalukuyang pandemya. Ang atin pong direksyon ay yaong bakuna na certified ng <strong>Food and Drug Administration</strong> ang siya nating gagamitin at yaong mga kondisyones ayon sa batas ay ating susundin upang masiguro na tumatalima tayo sa wastong ipinag-uutos.<br><br>
                    
                    Ang dokumentong ito ay magsisilbing Masterlist at Pre-registration ng ating lungsod sa libreng <strong>COVID-19 Vaccine</strong> isang paghahanda at pagpaplano bago pa man dumating ang nasabing bakuna. Nagbuo na po tayo ng team upang magsuri at magkaroon ng sapat na evaluation sa pamamagitan ng ating <strong>City Health Office</strong>.<br><br>
                    
                    Malalampasan po natin ang pandemyang ito at sa ating lungsod, sa Diyos tayo nagtitiwala. <strong>God bless po!</strong> <br><br>
                    Lubos na gumagalang,
                    
                </div>                    
            </div>
            <div class="col-md-12">
                <img height="100px" style="margin-top: 15px; margin-bottom: 15px;" src="{{asset('assets/image/mayormelsign.png')}}">
            </div>
        </div>



        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-block btn-fill btn-warning btn-lg" href="covid19vaccine/registration">
                    Click to Register or Reserve
                </a>
            </div>

            <div class="col-md-4">
                <button class="btn btn-block btn-fill btn-primary btn-lg" data-toggle="modal" data-target=".bs-cabvax-modal-lg"> <i class="fa fa-question" aria-hidden="true"></i> Find out more info about CABVax</button>
            </div>
            <div class="col-md-4">
                <button class="btn btn-block btn-fill btn-primary btn-lg video-modal" data-video="https://www.youtube.com/embed/HBEaP0N2Jww" data-toggle="modal" data-target="#videoModal"> <i class="fa fa-play" aria-hidden="true"></i> Play Toturial</button>
            </div>
            <div class="col-md-4">
                <button class="btn btn-block btn-fill btn-primary btn-lg" data-toggle="modal" data-target="#searchRegisteredUser"> <i class="fa fa-search" aria-hidden="true"></i> Search My Record</button>
            </div>

            <div class="col-md-4">
                <div class="card card-color">
                    <div class="card-content text-center">

                        <div class="card-body">
                            <!-- Title -->
                            <h6 class="card-title"><b>Total Registered:</b></h6>
                            <!-- Text -->
                            
                            <b><p class="card-text green-text"><i class="fa fa-users" aria-hidden="true"></i> <span class="ml-2" style="font-size: 30px;"><b id="total_preregistered">00</b></span></b>,Total</p>
                        </div>
                            <!-- Card content -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-color">
                    <div class="card-content text-center">

                        <div class="card-body">
                            <!-- Title -->
                            <h6 class="card-title"><b>Total Vaccinated 1st Dose:</b></h6>
                            <!-- Text -->
                            <b><p class="card-text green-text"><i class="fa fa-medkit" aria-hidden="true"></i> <span class="ml-2" style="font-size: 30px;"><b id="total_1st_dose_vaccinated">00</b></span></b>,Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-color">
                    <div class="card-content text-center">

                        <div class="card-body">
                            <!-- Title -->
                            <h6 class="card-title"><b>Total Vaccinated 2nd Dose:</b></h6>
                            <!-- Text -->
                            <b><p class="card-text green-text"><i class="fa fa-medkit" aria-hidden="true"></i> <span class="ml-2" style="font-size: 30px;"><b id="total_2nd_dose_vaccinated">00</b></span></b>,Total</p>
                        </div>
                            <!-- Card content -->
                    </div>
                </div>
            </div>

        </div>
        
              <!-- carousel-->
            <div class="modal fade bs-cabvax-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div id="carousel-cabvax-generic" class="carousel slide" data-ride="carousel">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">

                                <div class="item active">
                                    <img class="img-responsive" src="{{asset('assets/image/step1.jpg')}}">
                                </div>

                                <div class="item">
                                    <img class="img-responsive" src="{{asset('assets/image/step2.jpg')}}">
                                </div>

                                <div class="item">
                                    <img class="img-responsive"  src="{{asset('assets/image/step3.jpg')}}">
                                </div>

                                <div class="item">
                                    <img class="img-responsive"  src="{{asset('assets/image/step4.jpg')}}">
                                </div>

                                <div class="item">
                                    <img class="img-responsive"  src="{{asset('assets/image/step5.jpg')}}">
                                </div>
                            </div>
    
                            <!-- Controls -->
                            <a class="left carousel-control" href="#carousel-cabvax-generic" role="button" data-slide="prev">
                                <span class="fa fa-chevron-left" style="color: rgb(33, 110, 74);"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-cabvax-generic" role="button" data-slide="next">
                                <span class="fa fa-chevron-right" style="color: rgb(33, 110, 74);"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                
                <div class="modal-body">
                    <div class="embed-container">
                    <iframe width="100%" height="400" src="" frameborder="" allowfullscreen></iframe>
                    </div>
                </div>
                </div>
            </div>
            </div>

    </div>
    
    
    <!-- Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="searchRegisteredUser">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header" style="background-color: rgb(161, 161, 161)">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h5 class="modal-title text-center"><strong style="color: whitesmoke"> Search My Record</strong></h5>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="col-md-4" style="padding:30px;" id="userInfoCard">
                        <div id="ssButton"></div>
                        <div style="display: flex; min-height:350px; flex-direction:column; align-items:center; width:100%;">
                            <div style="width:100%; display:flex; justify-content:center">
                                <img id='cabuyaoLogo' style="width:70%;" src="{{asset('assets/image/qricon.png')}}" />
                                <div id='filterqrcode' style='display:none' class="text-center"></div>
                            </div>
                            
                            <p class="text-center" style="font-weight: 600; margin-top:10px; font-size: calc(0.75em + 0.2vmin);" id="filterName"></p>
                            <p class="text-center" style="font-weight: 600; font-size: calc(0.75em + 0.2vmin);" id="filterDate"></p>
                            <br>
                            <p class="text-center" style="text-align:center; font-size: 13px;" id="responseMessage">
                                <b>CABUYAO VACCINE INFORMATION MANAGEMENT SYSTEM (CVIMS)</b>
                            </p>
                        </div>
                        <div id="cardContent"></div>
                    </div>
                    <div class="col-md-8" style="border-left: 1px solid rgb(228, 228, 228);">
                        <form id="searchUserForm">
                            @method("POST")
                            @csrf
                            
                            <div class="alert alert-warning">
                                <span><b> Note: </b> Kindly check your first name, last name, middle name, gender, and birthday. Please put "NA" in the <b>Middle Name</b> if is not applicable. </span>
                            </div>
                            <div class="row">
                                <label class="col-md-3">Last Name</label>
                                <div class="col-md-9">
                                    <input class="form-control" name='last_name' placeholder="Enter you Last Name"/>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-3">First Name</label>
                                <div class="col-md-8">
                                    <input class="form-control" name='first_name' placeholder="Enter you First Name"/>
                                </div>
                            </div>
    
                            <div class="row">
                                <label class="col-md-3">Middle Name</label>
                                <div class="col-md-7">
                                    <input class="form-control" name='middle_name' placeholder="Enter you Middle Name"/>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3">Suffix</label>
                                <div class="col-md-5">
                                    <select class="selectpicker form-control" name="affiliation">
                                        <option value="" disabled selected>Select.....</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                        <option value="V">V</option>
                                        <option value="JR">JR</option>
                                        <option value="SR">SR</option>
                                        <option value="NA">NA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3">Gender</label>
                                <div class="col-md-4">
                                    <select class="selectpicker form-control" name='sex'>
                                        <option disabled value="" selected >SELECT ...</option>
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3">Date of Birth</label>
                                <div class="col-md-5">
                                    <input type="date" name="dob" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-offset-3 col-md-5">
                                    <button type="submit" class="btn btn-primary btn-fill">Search</button>
                                    <button type="button" class="btn btn-warning btn-fill" id="clearBTN">Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
<!-- End Modal -->

                
@endsection

@section('js')
  
<script type="text/JavaScript" src="{{asset('assets/js/htmltocanvas/html2canvas.min.js')}}"></script>
<script type="text/JavaScript" src="{{asset('assets/js/easy.qrcode.min.js')}}"></script>
<script type="text/template" id="qrcodeTpl">
	<div class="imgblock">
		<div class="qr" id="qrcode_{i}"></div>
	</div>
</script>
<script>


$(".video-modal").click(function () {
  var theModal = $(this).data("target"),
      videoSRC = $(this).attr("data-video"),
      videoSRCauto = videoSRC + "?modestbranding=1&rel=0&controls=0&showinfo=0&html5=1&autoplay=1";
  $(theModal + ' iframe').attr('src', videoSRCauto);
  $(theModal).on('hidden.bs.modal', function () {
    $(theModal + ' iframe').attr('src', videoSRC);
  });
});

$(document).ready(function () {
    registerdCounter();
});


function registerdCounter(){
    $.ajax({
        url: '{{ route('public.statistics') }}',
        type: "GET",
        dataType: "json",
        success: function (data) {
            var total_preregistered = JSON.parse(data.preregisteredCounter);
            var total_vaccinated_fist_dose = JSON.parse(data.vaccinatedCounterFirstDose);
            var total_vaccinated_second_dose = JSON.parse(data.vaccinatedCounterSecondDose);
            animateNumbers(total_preregistered,'total_preregistered');
            animateNumbers(total_vaccinated_fist_dose,'total_1st_dose_vaccinated');
            animateNumbers(total_vaccinated_second_dose,'total_2nd_dose_vaccinated');
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
 
 

 $("#searchUserForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength:3,
                
            },
            dob: {
                required: true,
            },
            last_name: { required: true},
            middle_name: { required: true},
            sex: { required: true },
            affiliation: { required: true },
        },
        messages:{
            last_name:'Last name is required!',
            first_name:'First name is required!',
            sex:'Sex field is required!',
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Start Searching',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, search it!',
                html: "<b>Search my record!",
                footer: '----'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('covid19vaccine.findRegisterUser') }}',
                        type: 'POST',
                        beforeSend:function(){
                            processObject.showProcessLoader();
                        },
                        data:{
                            '_token' : "{{ csrf_token() }}",
                            'last_name' : $('#searchUserForm input[name="last_name"]').val(),
                            'first_name' : $('#searchUserForm input[name="first_name"]').val(),
                            'middle_name' : $('#searchUserForm input[name="middle_name"]').val(),
                            'suffix' : $('#searchUserForm select[name="affiliation"]').val(),
                            'date_of_birth' : $('#searchUserForm input[name="dob"]').val(),
                            'sex' : $('#searchUserForm select[name="sex"]').val(),
                        },
                        dataType: 'JSON',
                        success:function(response){
                            $("#filterqrcode").empty();
                            $('#filterName').text('');
                            $('#filterDate').text('');
                            $('#responseMessage').html('');
                            $('#cardContent').empty();
                            $('#ssButton').empty();
                            $('#cabuyaoLogo').css('display', 'block');
                            $('#filterqrcode').css('display', 'none');
                            $("#searchRegisteredUser .modal-body").animate({ scrollTop: 0 }, "slow");

                            if(response.success){
                                $('#ssButton').prepend("<a onclick='btn_convert(\""+ response.data.fullname +"\")' style='color:black;' class='btn-rotate'><i style='font-size:30px' class='fa fa-camera'></i></a><br><br>")

                                $('#filterName').text(response.data.fullname);
                                $('#filterDate').text(response.data.date_registered);
                                $('#responseMessage').css('text-align', 'justify');
                                $('#cardContent').append("<hr><span style='font-size:11px; color:red'><b>Note:</b> Please capture this for your reference</span>");
                                $('#responseMessage').html("Upon checking your information, we found out that you are <b>ALREADY REGISTER</b> on our System on "+response.data.date_registered+". Please coordinate with your <b>Barangay Health Center</b> for your vaccination schedule. Thank you!");

                                if(response.data.registration_code){
                                    $('#cabuyaoLogo').css('display', 'none');
                                    $('#filterqrcode').css('display', 'block');
                                    
                                    var qrcode = new QRCode(document.getElementById("filterqrcode"), {
                                        width : 120,
                                        height : 120
                                    });
                                    qrcode.makeCode(response.data.registration_code);
                                }

                            }else{
                                $('#responseMessage').css('text-align', 'justify');
                                $('#responseMessage').html("Upon checking your information, we found out that you are <b style='color:red'>NOT REGISTER</b> on our System. Please go to <a href='https://cabuyaovaccine.com/covid19vaccine/registration'>Cabuyao Vaccine Registration Portal</a> and Register. Thank you!");
                            }
                        },
                        complete:function(){
                            processObject.hideProcessLoader();
                        }
                    });
                }
            })
        }
    });

    const btn_convert = (fullname) => {
            html2canvas(document.getElementById("userInfoCard"),		{
                allowTaint: true,
                useCORS: true
            }).then(function (canvas) {
                var anchorTag = document.createElement("a");
                document.body.appendChild(anchorTag);
                anchorTag.download = fullname +".jpg";
                anchorTag.href = canvas.toDataURL();
                anchorTag.target = '_blank';
                anchorTag.click();
            });
    };


    $('#clearBTN').on('click', function(){
        
        $('#ssButton').empty();
        $('#cabuyaoLogo').css('display', 'block');
        $('#filterqrcode').css('display', 'none');
        $('#filterName').text('');
        $('#filterDate').text('');
        $('#responseMessage').css('text-align', 'center');
        $('#responseMessage').html('CABUYAO VACCINE MANAGEMENT SYSTEM - CVIMS');
        $('#cardContent').empty();

        $('#searchUserForm input[name="last_name"]').val('');
        $('#searchUserForm input[name="first_name"]').val('');
        $('#searchUserForm input[name="middle_name"]').val('');
        $('#searchUserForm select[name="affiliation"]').val('');
        $('#searchUserForm input[name="dob"]').val('');
        $('#searchUserForm select[name="sex"]').val('');
        $('.selectpicker').selectpicker('refresh');
    });

</script>
@endsection