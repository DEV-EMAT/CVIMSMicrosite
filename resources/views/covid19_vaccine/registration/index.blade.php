
@extends('layouts.without_sidebar')

@section('style')
<style>

.btn {
    margin-bottom: 10px;
}
.image2{
   display: none;
}
.questions {
    /* margin:50px 0 50px 0; */
    /* padding: 10px; */
    /* border-bottom: 1px solid #e8e7e3; */
}

@media only screen and (max-width: 500px){.image1{
     display: none;
   }
   .image2{
    display: block;
    margin-left: auto;
    margin-right: auto;

   }
   
    .questions {
        margin:10px 0 10px 0;
    }
}

#customHR{
    border-bottom: 1px solid red;
}

/* .question {
    font-size: 20px;
} */

/* .sub-question {
    font-size: 18px;
} */

.error {
    font-size: 0.8em;
    font-weight: 300;
    
}

#ptStyle{
    cursor: pointer;
}
.content-hider{
    display: none;
}
.divider{
    background-color: black;
    margin-right: 15px !important;
    margin-left: 15px !important;
    height: 1px !important;
    display: block !important;
    overflow: hidden !important;
}

hr {
    margin-top: 10px;
    margin-bottom: 10px;
    border: 0;
    border-top: 2px solid rgb(250, 250, 250);
}
.resize-font{
    font-size: 15px;
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

</style>
@endsection
@section('content')

    <form id="register_form" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="header-text text-center">

                        <img class="image1" width="100%" style="margin-top: 15px;" src="{{asset('assets/image/vaccine-home.jpg')}}">

                        <img class="image2" width="100%" style="margin-top: 15px;" src="{{asset('assets/image/home1.jpg')}}">
                        
                    </div>
                    <div class="horizontally">
                        <p>Let's &nbsp; GO &nbsp; CabVax! &nbsp; MAGpabakuna &nbsp; na! ... Ang &nbsp; CORONA &nbsp; ay &nbsp; KAYANG &nbsp; KAYA! &nbsp; Basta't &nbsp; TAYO &nbsp; ay &nbsp; SAMA-SAMA! ...</p>
                    </div>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="card-header">
                        <h4 class="text-center">PRE-REGISTRATION FORM</h4>
                        <b>IMPORTANTENG MENSAHE:</b><br>
                        18 taong gulang pataas lamang ang bibigyan ng pagbabakuna sa naka-iskedyul na petsa.
                        <br><br>
                        Kung kayo ay may mga sumusunod na karamdaman (Cancer, Leukemia, with history of allergic reactions, at iba pang malubhang karamdaman) kayo po ay hindi pinahihintulutan na mabakunahan maliban na lamang kung kayo ay bibigyan ng Medical Clearance o pahintulot ng inyong doctor.
                    </div>

                    <hr>
                    <div class="card-content">
                        <!--Register Form -->
                        <div class="row">
                            {{-- <div class="col-md-4 text-center">
                                <div class="kv-avatar-hint">
                                    <small><b>Note:</b> Select file < 1000 KB</small>
                                </div>
                                <div class="kv-avatar">
                                <div> <label for="avatar" class="error"></label> </div>
                                    <div class="file-loading">
                                        <input type="file" id="avatar" required>
                                    </div>
                                </div>
                            </div> --}}
                            
                            <div class="col-md-6">
                                <!-- Last Name -->
                                <div class="form-group">
                                    <label>Last Name *</label> <label for="last_name" class="error"></label>
                                    <input type="text" class="form-control border-input" placeholder="Last Name" name="last_name" id="last_name">
                                </div>
                                
                                <!-- First Name -->
                                <div class="form-group">
                                    <label>First Name *</label> <label for="first_name" class="error"></label>
                                    <input type="text" class="form-control border-input" placeholder="First Name" name="first_name" id="first_name">
                                </div>
                                
                                <!-- Middle Name -->
                                <div class="form-group">
                                    <label>Middle Name *</label><small style="font-size: x-small;"><i>Put "NA" If not Applicable</i></small><label for="middle_name" class="error"></label>
                                    <input type="text" class="form-control border-input" placeholder="Middle Name" name="middle_name" id="middle_name">
                                </div>
                                
                                <!-- Suffix -->
                                <div class="form-group">
                                    <label>Suffix</label><small>(Jr., Sr., etc.)</small><label for="affiliation" class="error"></label>
                                    <select class="selectpicker form-control" name="affiliation" id="affiliation">
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

                            <div class="col-md-6">
                                <!-- Date of Birth -->
                                <div class="form-group">
                                    <label>Date Of Birth </label><small>(mm/dd/yyyy)</small> <label for="dob" class="error"></label>
                                    <input type='text' class="form-control datetimepicker" id='dob' name="dob" max="9999-12-31"
                                    placeholder="Date of Birth"/>
                                </div>

                                <div class="form-group">
                                    <label>Civil Status *</label> <label for="civil_status" class="error"></label>
                                    <select class="selectpicker form-control" name="civil_status" id="civil_status">
                                        <option value="" disabled selected>Select.....</option>
                                        <option value="02_Married">Married</option>
                                        <option value="01_Single">Single</option>
                                        <option value="03_Widow/Widower">Widow/Widower</option>
                                        <option value="04_Separated/Annulled">Separated/Annulled</option>
                                        <option value="05_Living_with_Partner">Living with Partner</option>
                                    </select>
                                </div>

                                <!-- Sex -->
                                <div class="form-group">
                                    <label>Sex *</label> <label for="sex" class="error"></label>
                                    <select class="selectpicker form-control" name="sex" id="sex">
                                        <option value="" disabled selected>Select.....</option>
                                        <option value="01_Male">Male</option>
                                        <option value="02_Female">Female</option>
                                    </select>
                                </div>
                                
                                <!-- Contact Number -->
                                <div class="form-group">
                                    <label>Contact Number *</label> <label for="contact" class="error"></label>
                                    <input type="number" class="form-control border-input" name="contact" placeholder="Contact" id="contact">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                    <!-- Barangay -->
                                    <div class="form-group">
                                    <label>Barangay *</label> <label for="barangay" class="error"></label>
                                    <select class="form-control selectpicker" data-live-search="true" id="barangay" name="barangay">
                                        <option value="" disabled selected>Select.....</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <!-- Home Address -->
                                <div class="form-group">
                                    <label>Home Adrress </label><small>(e.g. street, block, lot, unit)</small> <label for="address" class="error"></label>
                                    <textarea class="form-control" placeholder="Home Address" name="address" id="address"></textarea>
                                </div>
                            </div>
                        </div>

                        <legend style="font-size: 15px;font-style:italic; color:red;"><b>(Additional Information): -Note :</b> Put "NA" If not Applicable</legend>
                        
                        <div class="row">
                            <div class="col-md-3">
                                    <div class="form-group">
                                    <label>Category *</label> <label for="category" class="error"></label>
                                    <select class="form-control selectpicker" id="category" name="category">
                                        <option value="" disabled selected>Select.....</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                <label>ID Category *</label> <label for="category_for_id" class="error"></label>
                                    <select class="selectpicker form-control" name="category_for_id" id="category_for_id">
                                        <option value="" disabled selected>Select.....</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label id="label_number"> ID Number * </label> <label for="category_id_number" class="error"></label>
                                    <input type="text" class="form-control border-input" name="category_id_number" id="category_id_number" placeholder="Put (NA) If not Applicable">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PhilHealth ID * </label> <label for="philhealth" class="error"></label>
                                    <input type="text" class="form-control border-input" name="philhealth" id="philhealth" placeholder="Put (NA) If not Applicable">
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employee Status *</label> <label for="employment" class="error"></label>
                                    <select class="form-control selectpicker" id="employment" name="employment">
                                        <option value="" disabled selected>Select.....</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                    <div class="form-group">
                                    <label>Profession *</label> <label for="profession" class="error"></label>
                                    <select class="form-control selectpicker" data-live-search="true" id="profession" name="profession">
                                        <option value="" disabled selected>Select.....</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4" id="other_profession" style="display:none">
                                <div class="form-group">
                                    <label id="label_number"> Specify Profession * </label> <label for="specific_profession" class="error"></label>
                                    <input type="text" class="form-control border-input" name="specific_profession" id="specific_profession" placeholder="Put (NA) If not Applicable">
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employer Name * </label> <label for="employer_name" class="error"></label>
                                    <input type="text" class="form-control border-input" name="employer_name" id="employer_name" placeholder="Put (NA) If not Applicable">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employer Contact *</label> <label for="employer_contact" class="error"></label>
                                    <input type="text" class="form-control border-input" name="employer_contact" id="employer_contact" placeholder="Put (NA) If not Applicable">
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Employer Adrress </label><small>(e.g. street, block, lot, unit, barangay, city)</small> <label for="employer_address" class="error"></label>
                                    <textarea class="form-control"  name="employer_address" id="employer_address" placeholder="Put (NA) If not Applicable"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div class="row">

                <div class="col-md-12"><hr></div>
                 {{-- QUESTION 7 --}}
                <div class="content" >
                    <label class="col-md-6">With Allergy * <label for="question2" class="error"></label> </label>
                    <div class="col-md-6">
                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q2" data-toggle="buttons">
                            <label class="btn btn-success">
                                <input type="radio" name="question2" value="YES"> YES
                            </label>
                            <label class="btn btn-success">
                                <input type="radio" name="question2" value="NO"> NO
                            </label>
                        </div>
                    </div>
                </div>
                
                 {{-- QUESTION 7 --}}
                
                <div class="content content-hider" id="allergy">
                    <div class="col-md-12"><hr></div>
                    <label class="col-md-6">Type of Allergy * <label for="question3[]" class="error"></label></label>
                    <div class="col-md-6">
                        <div class="btn-group mt-5" role="group" aria-label="q3_0" data-toggle="buttons">
                            <label class="btn btn-success">
                                <input type="checkbox" name="question3[]" value="DRUGS"> DRUGS
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" name="question3[]" value="FOOD"> FOOD
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" name="question3[]" value="INSECTS"> INSECTS
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" name="question3[]" value="LATEX"> LATEX
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" name="question3[]" value="MOLD"> MOLD
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" name="question3[]" value="PET"> PET
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" name="question3[]" value="POLLEN"> POLLEN
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" id="other_allergy" name="question3[]" > OTHERS
                            </label>
                            <div><input type="text" class="form-control" style="display: none" name="specific_allergy" onkeyup="passedValue(this, '#other_allergy')" style="text-transform: uppercase" id="specific_allergy"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12"><hr></div>
                 {{-- QUESTION 7 --}}
                <div class="content">
                    <label class="col-md-6">With Comorbidities *  <label for="question4" class="error"></label></label>
                    <div class="col-md-6">
                        <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q4" data-toggle="buttons">
                            <label class="btn btn-success">
                                <input type="radio" name="question4" value="YES"> YES
                            </label>
                            <label class="btn btn-success">
                                <input type="radio" name="question4" value="NO"> NO
                            </label>
                        </div>
                    </div> 
                </div>
                <div class="col-md-12"><hr></div>
                <div class="content content-hider" id="comorbidities">
                    <label class="col-md-6">(If YES, Please indicate your comorbidities) *  <label for="question5[]" class="error"></label></label>
                        <div class="col-md-6">
                            <div class="btn-group mt-5" role="group" aria-label="q5_0" data-toggle="buttons">
                                <label class="btn btn-success">
                                    <input type="checkbox" name="question5[]" value="HYPERTENSION"> HYPERTENSION
                                </label>
                                <label class="btn btn-success">
                                    <input type="checkbox" name="question5[]" value="HEART DISEASE"> HEART DISEASE
                                </label>
                                <label class="btn btn-success">
                                    <input type="checkbox" name="question5[]" value="KIDNEY DISEASE"> KIDNEY DISEASE
                                </label>
                                <label class="btn btn-success">
                                    <input type="checkbox" name="question5[]" value="DIABETES MELLITUS"> DIABETES MELLITUS
                                </label>
                                <label class="btn btn-success">
                                    <input type="checkbox" name="question5[]" value="BRONCHIAL ASTHMA"> BRONCHIAL ASTHMA
                                </label>
                                <label class="btn btn-success">
                                    <input type="checkbox" name="question5[]" value="IMMUNODEFICIENCY STATE"> IMMUNODEFICIENCY STATE
                                </label>
                                <label class="btn btn-success">
                                    <input type="checkbox" name="question5[]" value="CANCER"> CANCER
                                </label>
                                <label class="btn btn-success">
                                    <input type="checkbox" id="other_comorbidities" name="question5[]" > OTHERS
                                </label>
                                <div><input type="text" class="form-control" style="display: none" name="specific_comorbidities" onkeyup="passedValue(this, '#other_comorbidities')" style="text-transform: uppercase" id="specific_comorbidities"></div>
                            </div>
                        </div>
                        <div class="col-md-12"><hr></div>
                    </div>
                    
                    <div class="content">
                    <label class="col-md-6">Provide Electronic Informed Consent? (Yes/No) * <label for="question9" class="error"></label></label>
                        <div class="col-md-6">
                            <div class="btn-group btn-group-lg mt-5" role="group" aria-label="q9" data-toggle="buttons">
                                <label class="btn btn-success">
                                    <input type="radio" name="question9" value="YES"> YES
                                </label>
                                <label class="btn btn-success">
                                    <input type="radio" name="question9" value="NO"> NO
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                </div>

            <div class="row">
                <div class="col-md-12" style="margin-bottom: 30px; margin-top: 30px;">
                    <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}" data-callback="enableBtn" data-expired-callback="disableBtn"></div>
                </div>
                
                <div class="col-md-12" style="margin-bottom: 15px;">
                    <span>
                        <!-- <input type="checkbox" style="zoom:2" name="question10" id="i_agree"> -->
                        By submitting this form, you confirm that you have read and agree to the E-CABS 
                        <a onclick="privacyAndTermShow()" id="ptStyle">Privacy and Terms</a>
                        <!-- I do hereby declare that all the information given above is true to the best of my knowledge and belief.  -->
                    </span>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                        <button id="submit" type="submit" class="btn btn-success btn-lg btn-block login-btn" disabled onclick="">Verify</button>
                    </div>
                </div>
            </div>
        </div>
        
    </form>
    
    <!-- Modal-->
    <div class="modal fade in" tabindex="-1" role="dialog" id="privacy-term-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header" style="background-color: rgb(253, 245, 218)">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h5 class="modal-title text-center"><strong> Privacy and Terms</strong></h5>
                    <p style="text-align: center;">Last updated Sept. 07, 2020</p>
                </div>
                <!-- End Modal Header -->
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <p class="text-justify">
                        The <b>City Government of Cabuyao</b> respects the privacy of our users 
                        and wants you to understand how we collect, use, and share your personal information. 
                        This Privacy Policy covers our data processing practices and describes your data 
                        privacy rights under Republic Act No. 10173 known as the Data Privacy Act of 2012.
                    </p>

                    <h4 class="resize-font"><b>COLLECTION OF YOUR INFORMATION</b></h4>

                    <p class="text-justify">
                    We collect the following personal information from you when you sign up electronically submit to us your
                    inquiries or requests in relation to Cabuyao City COVID-19 Vaccine Pre-Registration program:
                    </p>
                    
                    <h4 class="resize-font"><b>Personal Data</b></h4>
                    <p class="text-justify">Demographic and other personally identifiable information that you voluntarily give to us when choosing to participate in various activities related to the Application.</p>
                    
                    <p class="resize-font">For Personal</p>

                    <ul class="resize-font">
                        <li>Name</li>
                        <li>Age</li>
                        <li>Gender</li>
                        <li>Civil Status</li>
                        <li>Date of Birth</li>
                        <li>Contact Information</li>
                        <li>Email Address</li>
                        <li>Address</li>
                        <li>Device Location</li>
                        <li>Mac Address</li>
                    </ul>

                    <h4 class="resize-font"><b>SHARING OF INFORMATION</b></h4>
                    <p class="text-justify">
                        You voluntarily and freely consent to the collection and sharing of personal data such as name, postal address, contact information, 
                        and health information as required by the Republic Act No. 11469 known as the “Bayanihan to Heal as One Act”.
                    </p>
                    <h4 class="resize-font"><b>DATA PRIVACY RIGHTS</b></h4>
                    <p class="text-justify">
                        Under the DPA, we are committed to upholding your rights concerning your personal information. 
                        You have the right to correct or modify or erase your personal information from our systems, databases, and processes.
                    </p>
                    <h4 class="resize-font"><b>CONTACT US</b></h4>
                    <p class="resize-font">If you have questions or comments about this Privacy Policy, please contact our team:</p>

                    <p style="margin-bottom: 0; margin-top: 30px;">E-Cabs Software Developers Team</p>
                    <a href="enterprise.cabuyao@gmail.com">enterprise.cabuyao@gmail.com</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-fill" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
            </div>
        </div>
    </div>
    </div>
<!-- End Modal -->
@endsection

@section('js')
<script type="text/template" id="qrcodeTpl">
	<div class="imgblock">
		<div class="qr" id="qrcode_{i}"></div>
	</div>
</script>
<script type="text/JavaScript" src="{{asset('assets/js/easy.qrcode.min.js')}}"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

<script>
    function enableBtn(){
        $("#submit").prop('disabled', false);
    }
    function disableBtn(){
        $("#submit").prop('disabled', true);
    }

    $(document).ready(function () {

        //get barangays
        $.ajax({
            url:'{{ route('covid19vaccine.all-barangay-for-combobox') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="barangay"]').append('<option value='+response[index].id+'>'+ response[index].barangay+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
        
        //get category
        $.ajax({
            url:'{{ route('covid19vaccine.all-category-for-combobox') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="category"]').append('<option value='+response[index].id+'>'+ response[index].category_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
        
        //get profession
        $.ajax({
            url:'{{ route('covid19vaccine.all-profession-for-combobox') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="profession"]').append('<option value='+response[index].id+'>'+ response[index].profession_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });

        //get employer type
        $.ajax({
            url:'{{ route('covid19vaccine.all-employertype-for-combobox') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="employment"]').append('<option value='+response[index].id+'>'+ response[index].employment_type+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });


         //get all id category
         $.ajax({
            url:'{{ route('covid19vaccine.all-idcategory-for-combobox') }}',
            type:'GET',
            dataType:'json',
            success:function(response){
                for (let index = 0; index < response.length; index++)
                {
                    $('[name="category_for_id"]').append('<option value='+response[index].id+'>'+ response[index].id_category_name+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    
        //create account
        $("#register_form").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength:3,
                    
                },
                dob: {
                    required: true,
                    minAge:18,
                },
                contact: {
                    required: true,
                    phoneno: true
                },
                // avatar:{required: true},
                last_name: { required: true},
                middle_name: { required: true},
                affiliation: { required: true},
                sex: { required: true },
                barangay: { required: true },
                address: { required: true },
                civil_status: { required: true },
                employment: { required: true },
                profession: { required: true },
                category: { required: true },
                category_id_number: { required: true },
                category_for_id: { required: true },
                philhealth: { required: true },
                employer_name: { required: true },
                employer_contact: { required: true},
                employer_address: { required: true },
                profession: { required: true },
                specific_profession: { required: true },
                specific_comorbidities: {
                    required: function() {
                        return $('#other_comorbidities').is(':checked') ? true : false;
                    }
                 },
                 specific_allergy: {
                    required: function() {
                        return $('#other_allergy').is(':checked') ? true : false;
                    }
                 },
                'question2': { required: true },
                'question3[]': { required: true }, 
                'question4': { required: true }, 
                'question5[]': { required: true }, 
                'question9': { required: true },
            },
            messages:{
                last_name:'Last name is required!',
                first_name:'First name is required!',
                affiliation:'Suffix is required!',
                // dob:'Date of birth is required!',
                // category_for_id:'Category ID is required!',
                contact:'Contact number is required!',
                sex:'Sex field is required!',
                barangay:'Barangay is required!',
                address:'Home address is required!',
                profession:'Profession is required!',
                avatar:'Image is required!',
            },
            submitHandler: function (form) {
                if(grecaptcha.getResponse().length !== 0)
                {
                    Swal.fire({
                        title: 'Register your Entry?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!',
                        html: "<b>Data Pre-Registration",
                        footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                    }).then((result) => {
                        if (result.value) {

                            $('#submit').prop('disabled', true);
                            

                                var formData = new FormData($("#register_form").get(0));
                                // formData.append('imagefile', data);

                                //process loader true
                                processObject.showProcessLoader();
                                $.ajax({
                                    url: "{{ route('registration.store')}}",
                                    type: "POST",
                                    data: formData,
                                    cache:false,
                                    contentType: false,
                                    processData: false,
                                    dataType: "JSON",
                                    success: function (response) {
                                        if(response.success){
                                            swal({
                                                title: "<table><tr><td><p id='qrcode' style='width: 18px; margin-left:90px; height: auto;'></p></td></tr> <tr><td><p style='min-width:300px; max-width:300px; font-size:12px; margin-left:-5px; color:black '><b>" + response.fullname + "</b><br>" + response.date_registered + "</br></p></td></tr></table>",
                                                html: "Pre-Registration Submitted Success! Please coordinate with your BARANGAY HEALTH CENTER for your vaccination schedule <br><br><div class='alert alert-danger'><span style='font-size:10px'><b>(Please capture this notification for your reference)</span></div>",
                                                type: "success",
                                                footer: '---'
                                            }).then(function() {
                                                location.reload();
                                            });
                                            var qrcode = new QRCode(document.getElementById("qrcode"), {
                                                width : 120,
                                                height : 120
                                            });
                                            qrcode.makeCode(response.registration_code);
                                        }else{
                                            swal.fire({
                                                title: response.title,
                                                html: "<br>" + response.messages,
                                                type: "error",
                                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                            });
                                        }
        
                                        //process loader false
                                        processObject.hideProcessLoader();
                                        $('#submit').prop('disabled', false);
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        swal.fire({
                                            title: "Oops! something went wrong.",
                                            html: "<b>" + errorThrown + "! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                            type: "error",
                                            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                                        });
                                        //process loader false
                                        processObject.hideProcessLoader();
                                        
                                        $('#submit').prop('disabled', false);
                                    }
                                });
                                
                        }
                    })
                }
                else{
                    swal({
                        title: "reCAPTCHA Error!",
                        text: 'Your recatpcha has expired, please verify again ...',
                        type: "error"
                    }).then(function() {
                        grecaptcha.reset();
                    });
                }
            }
        });
    });
    
    const generateBR = (title) => {
        $(".imgblock").remove();
        var base64images = new base64image();

        var qrcodeTpl = document.getElementById("qrcodeTpl").innerHTML;

        // var container = document.getElementById('printContainer');

        var qrcodeHTML = qrcodeTpl.replace(/\{title\}/, base64images.ecabsLogo(title)[0].config.text).replace(/{i}/, 0);

        // container.innerHTML+=qrcodeHTML;

        var t=new QRCode(document.getElementById("qrcode_"+0), base64images.ecabsLogo(title)[0].config);
    }

    jQuery.validator.addMethod("nowhitespace", function(value, element) {
        return this.optional(element) || /^\S+$/i.test(value);
    }, "No white space please");

    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(09|\+639)\d{9}$/);
    }, "<br />Please specify a valid phone number");

    /* age validator */
    jQuery.validator.addMethod("minAge", function (value, ele, min) {
        var today = new Date();
        var birthDate = new Date(value);
        var age = today.getFullYear() - birthDate.getFullYear();

        if(age > min+1) { return true; }

        var m = today.getMonth() - birthDate.getMonth();

        if(m < 0 || (m ===0 && today.getDate() < birthDate.getDate())) { age--; }

        return age >= min;
    }, 'You\'re age is not qualified for vaccination!');
    
    /* alphanumeric */
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^\w+$/i.test(value);
    }, "Letters, numbers, and underscores only please");

    /* allergy */
    $('input[name="question2"]').on('change', function(e){
        if($(this)[0].value == "YES"){
            $('#allergy').show();
        } else {
            $('#allergy').hide();
            $('#allergy label').removeClass('active');
            $('#allergy input').prop('checked', false);
        }
    });

    /* other allergy */
    $('#other_allergy').on('change', function(e){
        if($(this)[0].checked){
            $('#specific_allergy').show();
        } else {
            $('#other_allergy').val('');
            $('#specific_allergy').val('');
            $('#specific_allergy').hide();
        }
    });

    /* comorbidities */
    $('input[name="question4"]').on('change', function(e){
        if($(this)[0].value == "YES"){
            $('#comorbidities').show();
        } else {
            $('#comorbidities').hide();
            $('#comorbidities label').removeClass('active');
            $('#comorbidities input').prop('checked', false);
        }
    });
    
    /* other comorbidities */
    $('#other_comorbidities').on('change', function(e){
        if($(this)[0].checked){
            $('#specific_comorbidities').show();
        } else {
            $('#other_comorbidities').val('');
            $('#specific_comorbidities').val('');
            $('#specific_comorbidities').hide();
        }
    });
    
    $('input[name="question6"]').on('change', function(e){
        if($(this)[0].value == "YES"){
            $('.infection').show();
        } else {
            $('.infection').hide();
            $('.infection label').removeClass('active');
            $('.infection input').prop('checked', false);
            $('input[name="question7"]')[0].value = '';
        }
    });
    
    function privacyAndTermShow(){
        $("#privacy-term-modal").modal("show");
    }
    
    
    $('#profession').on('change', function(){
        if($(this)[0].value == '19'){
            $('#other_profession').show();
            $('#specific_profession').val('');
        }else{
            $('#other_profession').hide();
        }
    });
    
    $('#i_agree').on('change', function(e){
        if($(this)[0].checked == true){
            $('#submit').prop('disabled', false);
        } else {
            $('#submit').prop('disabled', true);
        }
    })

     //other fields checker
     const passedValue = (element, passedTo) => {
        $(passedTo).val($(element).val());
    }
    
    
</script>
@endsection
