@extends('layouts.app2')


@section('style')
    <style>
        td.details-control {
            background: url('../assets/image/plus.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('../assets/image/minus.png') no-repeat center center;
        }
        
        .disabled {
            pointer-events:none; 
            opacity:0.6;         
        }
        .disabled a {
            text-decoration: line-through;
        }
    </style>
@endsection

@section('content')
<!-- Display All Data -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> <b>Scholarship Application</b></h4>
                </div>
                <div class="card-content">
                    <table id="datatable" class="table table-bordered table-sm table-hover" cellspacing="0"
                        width="100%">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th></th>
                                <th>SCHOLAR-ID</th>
                                <th>Fullname</th>
                                <th>Status</th>
                                <th style="width: 300px;">Actions</th>
                            </tr>
                        </thead>
                        <!--Table head-->
                        <!--Table body-->
                        <tbody>
                        </tbody>
                        <!--Table body-->
                    </table>
                </div>
            </div>
        </div> <!-- end col-md-12 -->
    </div>
</div>
<!-- End Display All Data -->


<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="ti-close"></i>
				</button>
				<h4 class="modal-title">Scholarship Application</h4>
			</div>
			<div class="modal-body" style="min-height:600px;max-height: calc(100vh - 200px); overflow-y: auto;">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-12">
                                <p style="color:red; font-size:12px;"><b>The failure of the applicant to disclose truthfully the facts required will subject him/her to disqualification from the program. Subsequent validation will ensue to determine the information given by the applicant upon submission of requirements.</p>
                            </div>
                        </div>
                        <hr>
                        <div id='viewRequirements'>
                        </div>
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <!-- simple select -->
                                <select class="selectpicker" data-style="btn btn-primary"  name="txtYearLevel" id="txtYearLevel"  data-size="3">
                                    <option value="0" disabled selected="">Select Year Level.....</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                    <option value="5th Year">5th Year</option>
                                    <option value="6th Year">6th Year</option>
                                    <option value="7th Year Higher">7th Year Higher...</option>
                                </select>
                            </div>
                            <div class="col-md-2 ">
                                {{-- <button type="button" class="btn btn-wd btn-info btn-fill btn-block btn-rotate" id="add_fields"><span class="btn-label"><i class="ti-plus"></i>&nbsp;&nbsp;Add new </span></button> --}}
                            </div>
                        </div>
                        <div id="grade">
                            <form id="addsubject-form">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Subject code/name</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <label>Number of Units</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <label>Final Grade</label>
                                    </div>
                                    <div class="col-xs-2">
                                        <label>Action</label>
                                    </div>
                                </div>    
                                <div class="divfield">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <input type="text" name="codes[]"  placeholder="Code" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <input type="number" name="units[]" min="0" max="9" placeholder="Units" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-3" id="grades_list">

                                        </div>
                                        <div class="col-xs-2">
                                            {{-- <a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-remove"></i></a> --}}
                                            <button type="button" class="btn btn-info btn-sm btn-fill btn-rotate" id="add_fields"><i class="ti-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <button onclick="application()" class="btn btn-wd btn-danger btn-fill btn-rotate" id="btnComputeGwa"><i class="fa fa-cog"></i> Compute GWA</button>
                        </div>
                    </div>
                </div>
		    </div>
        </div>
	</div>
</div>

<div class="modal fade" id="grade_list_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
                <h4 class="modal-title">Student Grades Submitted</h4>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                <div class="row">
                    <div class="col-xs-12">
                        <table id="tblSubject" class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th colspan="4" style="text-align: center; color: white; background-color: gray;">
                                        <b>SUBJECT AND GRADE</b>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Subject</th>
                                    <th>Units</th>
                                    <th>Grades</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    let timer;
    let programObj = [];
    let gradingObj = [];
    let ctrAddField = 1;
    let maxField = 15;
    let minGradePercentage = 0;
    let scholar_id = 0;

    $(document).ready(function(){
        reset()

        datatable = $('#datatable').DataTable({
            'serverSide': true,
            'ajax':{
                'url': "{{ route('application.findall')}}",
                'datatype':'json',
                'type':'POST',
                'data':{ _token:"{{ csrf_token() }}" }
            },
            "columns": [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                { "data": "barcode" },
                { "data": "fullname" },
                { "data": "status" },
                { "data": "actions" }    
            ],
            "columnDefs": [
                { "orderable": false, "targets": [0, 3, 4] }
            ]
        });

        /* for datatable extend */
        $('#datatable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = datatable.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                timer = setInterval(function() {datatable.ajax.reload( null, false );}, 5000);
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
                clearInterval(timer);
            }
        });

        /* add fields from grade */
        $('.divfield').on('click','#add_fields', () => {

            const fields = `<div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <input type="text" name="codes[]" placeholder="Code" class="form-control">
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <input type="number" name="units[]" min="0" max="9" placeholder="Units" class="form-control">
                    </div>
                </div>
                <div class="col-xs-3">
                    ${set_combobox(gradingObj)}
                </div>
                <div class="col-xs-2">
                    <a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-remove"></i></a>
                </div>
            </div>`;
            
    
            if(ctrAddField >= maxField){
                alert('Reached maximun field!')
            }else{
                $('.divfield').append(fields);
                $('.selectpicker').selectpicker('refresh');
                ctrAddField++;
            } 
        });

        /* remove fields from grade */
        $('.divfield').on("click","#remove_field", function(e){ 
            e.preventDefault();

            $(this).closest('.row').remove();
            ctrAddField--;
        });

    });

    const displayRequirements = (requiredUnits, passingGrade) => {
        $('#viewRequirements').empty();
        $('#viewRequirements').append('<div class="alert alert-warning" role="alert"><i class="fa fa-info-circle"></i> &nbsp; Enter Academic Grades --Required Units: (<b>'+requiredUnits+' units above </b>) --Passing Grade: (<b>'+passingGrade+'% above</b>) <br>Note: Strictly No <b>Officially Drop (OD), Unofficially Dropped (UD), Incomplete (INC) </b> and <b> Failing Grades. </b></div>');
    }

    const format = (data) => {
        return '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<div class="col-md-4 text-center">'+
                            '<img src="{{ asset('images/iskocab/scholar_profile/') }}/' + data.sch_info.image +'" width="200px"/>'+
                        '</div>'+
                        '<div class="col-md-8">'+
                            '<table class="table table-sm table-bordered table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th style="text-align:center; background:aqua;" colspan="2">Personal Information</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>'+
                                    '<tr>'+
                                        '<td>Name</td>'+
                                        '<td>'+data.fullname+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Course</td>'+
                                        '<td>'+data.sch_info.course_description+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>School Name</td>'+
                                        '<td>'+data.sch_info.school_name+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Contact</td>'+
                                        '<td>'+data.sch_info.contact_number+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Date of Birth</td>'+
                                        '<td>'+data.sch_info.date_of_birth+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Barangay</td>'+
                                        '<td>'+data.sch_info.barangay+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Address</td>'+
                                        '<td>'+data.sch_info.address+'</td>'+
                                    '</tr>'+
                                '</tbody>'+
                            '</table>'+
                        '</div>'+
                    '</div>'+
                '</div>';
    } 

    const getProgram = (scholar_id) => {
        return new Promise((resolve, reject) => {
            /* get and set available program */
            $.ajax({
                url:"{{ route('sch-program.find-active') }}",
                type:'GET',
                data:{ 'scholar_id': scholar_id },
                dataType:'JSON',
                success:function(response){
                    resolve(response);
                },
                error:function(a, b, c){
                    reject(b);
                }
            });
        })
    }

    const apply = (id) => {
        /* reset field every apply */
        reset();

        /* set scholar id globally */
        scholar_id = id;

        /* set available program */
        getProgram(scholar_id).then((fromResolve)=>{
            const { available_program_on_attainment, messages, program, assistance_type, grading_system } = fromResolve;
            programObj = program;
            gradingObj = grading_system;

            if(available_program_on_attainment){
                if(program.application_status == 1){
                    reset();
                    console.log(assistance_type);

                    /* get minimum required grades */
                    let min = [];
                    assistance_type.forEach(data => { min.push(data.grade_from); })

                    /* display grade requirements */
                    displayRequirements(programObj.number_of_units, Math.min(...min));

                    /* show category selectbox */
                    (programObj.required_year == '1')? $('#txtYearLevel').selectpicker('show'): $('#txtYearLevel').selectpicker('hide');

                    /* disable grade button */
                    (programObj.required_grade == 0)?  $('#grade').find('input, button').attr('disabled','disabled'): false;
                    
                    /* selected attainment */
                    minGradePercentage = Math.min(...min);

                    /* put data on combobox official grades */
                    $("#grades_list").append(set_combobox(gradingObj));
                    $('.selectpicker').selectpicker('refresh');

                    $('#modal_form').modal('show');
                } else {
                    swal.fire({
                        title: "Scholarship Application Unavailable!",
                        text: 'Scholarship application for '+ programObj.educ_attainment + ' is currently unavailable.',
                        type: "error"
                    })
                }
            }else{
                swal.fire({
                    title: "No Scholarship on this Scholar attainment.",
                    text: messages,
                    type: "error"
                })
            }
        }).catch((frmReject)=>{
            console.log(frmReject);
        })
        
      
    }
    
    const grades_submitted = (application_id) =>{
        $('#tblSubject tbody').empty();

        $.ajax({
            url:'/iskocab/evaluation/'+ application_id,
            type:'GET',
            dataType:'JSON',
            success:function(response){
                console.log(response);
                const { grade_list, gwa, required_grade} = response;
                let totalUnits = 0;
                let subjects = '';

             
                /* grade list */
                if(required_grade == '1'){
                    grade_list.forEach(data => {
                        console.log(data);
                        totalUnits += parseInt(data.no_of_units);
                        
                        subjects += `<tr>
                            <td>${data.subject_code}</td>   
                            <td>${data.no_of_units}</td>
                            <td>${data.grade}</td>
                        </tr>`;
                    });
                    subjects += `<tr><td align="right">TOTAL: </td><td><b style="color:red"> ${totalUnits} Units</b></td><td><b style="color:red"> ${gwa} </b></td></tr>`;
                }else{
                    subjects = `<tr><td class="text-center" colspan="4"><label class="label label-primary">No Required Grades!</label></td></tr>`;
                }

                $('#tblSubject tbody').append(subjects);
                $('#grade_list_form').modal('show');
            }
        });
    }


    /* Reset function */
    const reset = () => {
        const fields = `<div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <input type="text" name="codes[]"  placeholder="Code" class="form-control">
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <input type="number" name="units[]" min="0" max="9" placeholder="Units" class="form-control">
                    </div>
                </div>
                <div class="col-xs-3" id="grades_list">

                </div>
                <div class="col-xs-2">
                    {{-- <a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-remove"></i></a> --}}
                    <button type="button" class="btn btn-info btn-sm btn-fill btn-rotate" id="add_fields"><i class="ti-plus"></i></button>
                </div>
            </div>`;
        
        $(".divfield").html(fields);
        $('#txtCategory').prop('selectedIndex',0);
        $('#viewRequirements').empty();
        $('.selectpicker').selectpicker('refresh');
        $('#txtYearLevel').selectpicker('hide');
        ctrAddField = 1;
    }

    const application = () =>{
        /* validate data */
        const units = validateUnits($("input[name='units[]']").map(function(){ return $(this).val(); }).get());
        const grades = validateInputedData($("select[name='grades[]']").map(function(){ return $(this).val(); }).get());
        const codes = validateInputedData($("input[name='codes[]']").map(function(){ return $(this).val(); }).get());

        if(!codes || !grades || (units[1] < programObj.number_of_units)){
            /* error on sodes, grades or on units */
            const error = !codes ? "Please validate inputed subjects!" : !grades ? "Please validate inputed grades!" : units[1] < programObj.number_of_units ? "Required units not reached, <b>Required units : " + programObj.number_of_units + " and above</b>." : false;
            swal("Error!", error, "error");
        }else{
            Swal.fire({
                title: 'Verifying your grades....',
                html: 'I will close in seconds.',
                timer: 3000,
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                        const content = Swal.getContent()
                    }, 1000)
                },
                onClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    $.ajax({
                        url:'{{ route('application.compute-grades') }}',
                        type:'GET',
                        data:{
                            "scholar_id" : scholar_id,
                            "subject_code" : codes,
                            "grades" : grades,
                            "units" : units,
                            "min_percentage" : minGradePercentage
                        },
                        dataType:'JSON',
                        success:function(response){
                            console.log(response);
                            if(typeof response.isError !== 'undefined' && response.isError === true){
                                swal("Error!", response.messages, "error");
                            }else{
                                if(response.isPassed){
                                    
                                    // const grade_list = []

                                    // codes.forEach((data,index) => {
                                    //     let temp = { 
                                    //         'subject_code' : data,
                                    //         'no_of_units' : units[index],
                                    //         'grades' : grades[index],    
                                    //         'subject_grade_equivalent' : grades[index],   
                                    //         'remarks' : grades[index],   
                                    //     }
                                    // });

                                    Swal.fire({
                                        title: 'Verified Successfully! <br> Please Click Apply Button',
                                        text: "Your grades is: "+ response.GWA,
                                        type: 'success',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Apply Now!'
                                    }).then((result) => {
                                        if (result.value) {
                                            $.ajax({
                                                url:"{{ route('application.store') }}",
                                                type:'POST',
                                                data:{
                                                    "_token" : "{{ csrf_token() }}",
                                                    "program_module_id" : programObj.module_id,
                                                    "gwa" : response.GWA,
                                                    "grades" : response.grades,
                                                    "scholar_id" : scholar_id,
                                                    "year_level" : $('#txtYearLevel').val(),
                                                },
                                                dataType:'JSON',
                                                success:function(results){
                                                    if(results.success){
                                                        swal({
                                                            title: "Success!",
                                                            text: results.messages,
                                                            type: "success"
                                                        }).then(function() {
                                                            $('#modal_form').modal('hide');
                                                            datatable.ajax.reload( null, false);
                                                        });
                                                    }else{
                                                        swal("Error",results.messages, "error");
                                                    }
                                                },
                                                error:function(a, b, c){
                                                    swal('Error!',c,"error");
                                                }
                                            });
                                        }
                                    })
                                }else{
                                    swal("("+ response.GWA +") Grades not Applicable!", "Your Grdaes DOESN'T meet the required grade for this application! Minimun Percentage: "+ response.passing.from , "error");
                                }
                            }
                        }
                    })

                   
                }
            })
        }
    };

    const validateUnits = (units) => {
        let unit_sum = 0;

        const formatUnits = units.map((value) => {
            if(value === '' || isNaN(value)){
                return 3
            }else{
                return Number(value)
            }
        })
        /* compute total units */
        formatUnits.forEach((value)=> {  
            unit_sum += parseInt(value); 
        });

        return [formatUnits, unit_sum]
    }

    const validateInputedData = (data) => {
        /* find if there is an empty value */
        const findEpmtyData = data.findIndex((value) => { return value == ''})
        
        /* -1 if there is no empty value */
        return (findEpmtyData == -1) ? data : false ;
    }

    const set_combobox = (grading) => {
        let option = '';
        const { official_grade, grade_to, grade_from, remarks } = grading;
        
        official_grade.forEach((data, index) => {  
            if(remarks[index].toUpperCase() == 'PASSED'){
                option += '<option value='+ data +'>'+ data +'</option>'; 
            }
        });

        return `<div class="form-group">
            <select class="form-control selectpicker" data-live-search="true" name="grades[]">
                <option value="" disabled selected>Official Grade</option>
                ${option}
            </select>
        </div>`;


    }

</script>
@endsection