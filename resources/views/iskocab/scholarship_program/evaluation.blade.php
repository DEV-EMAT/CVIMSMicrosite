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

}
    </style>
@endsection

@section('content')
<!-- Display All Data -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div style="width: 500px" id="reader"></div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> <b>Scholarship Evaluation</b></h4>
                </div>
                <div class="card-content">
                    <table id="datatable" class="table table-bordered table-sm" cellspacing="0"
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
                <h4 class="modal-title">Evaluation Student</h4>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                <div class="row">
                    <form method="POST" id="evaluation-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="application_id" name="application_id">
                        <div class="col-xs-12">
                            <table id="tblSubject" class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="4" style="text-align: center; color: white; background-color: gray;">
                                            <b>SUBJECT AND GRADE</b>
                                            <div class="pull-right">
                                                <button type="button" class="btn-update btn btn-warning btn-fill btn-sm" onclick="application()"><i class="fa fa-save"></i> SAVE</button>
                                                <a class="btn-update btn btn-primary btn-fill btn-sm" id="addField"><i class="fa fa-plus"></i> ADD FIELDS</a>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Units</th>
                                        <th>Grades</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <table id="tblEvents" class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="5"
                                            style="text-align: center; color: white; background-color: gray;">
                                            <b>REQUIRED EVENTS</b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Date</th>
                                        <th>In/Out</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            
                            <table id="tblExamination" class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="3"
                                            style="text-align: center; color: white; background-color: gray;">
                                            <b>EXAMINATION</b>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <table id="tblRequirements" class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="3"
                                            style="text-align: center; color: white; background-color: gray;">
                                            <b>REQUIREMENTS</b>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <div class="card-footer text-center">
                                <button id='btnSub' class="btn btn-info btn-fill btn-wd">Evaluate</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="grade_modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body" id="grade_content" style="max-height: calc(100vh - 200px); overflow-y: auto;"> </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/1.2.4/html5-qrcode.min.js"></script>
<script>
    let timer;
    let objResponse = [];
    let gradingObj = [];
    let ctrAddField = 0;
    let maxField = 15;
    let minGradePercentage = {};
    let scholar_id_active = 0;
    let required_list_event = [];
    let required_requirement = [];
    let exempt_event = [];
    let exempt_exam_reason = [];

    function onScanSuccess(qrCodeMessage) {
	// handle on success condition with the decoded message
}

var html5QrcodeScanner = new Html5QrcodeScanner(
	"reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess);

    $(document).ready(function(){

        datatable = $('#datatable').DataTable({
            'serverSide': true,
            'ajax':{
                'url': "{{ route('evaluation.findall')}}",
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
            }
            else {
                // Open this row
                // row.child(row, ).show()
                format(row, row.data());
                tr.addClass('shown');
            }
        });

        /* add fields from grade */
        $('#addField').on('click', () => {

            const fields = `<tr>
                <td><input type="text" name="codes[]" placeholder="Code" class="form-control"></td>
                <td><input type="number" name="units[]" min="0" max="9" placeholder="Units" class="form-control"></td>
                <td>${set_combobox(gradingObj)}</td>
                <td><a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-remove"></i></a></td>
            </tr>`;


            if(ctrAddField >= maxField){
                alert('Reached maximun field!')
            }else{
                $('#tblSubject tbody').prepend(fields);
                $('.selectpicker').selectpicker('refresh');
                ctrAddField++;
            } 
        });


        /* remove fields from grade */
        $('#tblSubject').on("click","#remove_field", function(e){ 
            e.preventDefault();

            $(this).closest('tr').remove();
            ctrAddField--;
        });

        
        $('#tblEvents').on('click','.checkbox_event', function (event) {
            if (this.checked) {
                reason('events', this);
            } else {
                exempt_event.splice(this.value, 1);
                console.log(exempt_event);
            }
            
        });
    });

    const format = (row, data) => {
        console.log(data);
        const {official_grade, grade_from, grade_to, remarks} = data.grading_system;

        var grade = "";
        var achievements = "";
        
        if(data.grading_system != ''){
            for(var index=0; index < official_grade.length; index++){
                grade += '<tr>'+
                    '<td><label class="label label-primary">' + official_grade[index] + '</label></td>'+
                    '<td><label class="label label-primary">' + grade_from[index] + '</label></td>'+
                    '<td><label class="label label-primary">' + grade_to[index] + '</label></td>'+
                    '<td><label class="label label-primary">' + remarks[index] + '</label></td>'+
                '</tr>';
            }
        }else{
            grade = '<tr>'+
                '<td class="text-center" colspan="4"><label class="label label-primary">Data is deactivated!</label></td>'+
            '</tr>';
        }
        
        let grade_table = '<table class="table table-sm table-bordered">'+
            '<thead>'+
                '<tr>'+
                    '<th style="text-align:center; background:aqua;" colspan="4">'+data.sch_info.school_name+' Grading System</th>'+
                '</tr>'+
                '<tr>'+
                    '<th>OFFICIAL GRADE</th>'+
                    '<th>GRADE (FROM)</th>'+
                    '<th>GRADE (TO)</th>'+
                    '<th>REMARKS</th>'+
                '</tr>'+
            '</thead>'+
            '<tbody>'+grade+'</tbody>'+
        '</table>';

        $('#grade_content').empty();
        $('#grade_content').append(grade_table);
        // data.sch_info.image
        let response = '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<div class="row">'+
                            '<div class="col-md-3">'+
                                '<img height="100%" width="100%" style="padding:0 70px 0 70px " src="{{ asset('images/iskocab/scholar_profile/') }}/' + data.sch_info.image +'"/>'+
                            '</div>'+
                            '<div class="col-md-9">'+
                                '<table class="table table-sm table-bordered">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th style="text-align:center; background:aqua;" colspan="2">Personal Information </th>'+
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
                                            '<td>'+data.sch_info.school_name+' <a class="btn btn-primary btn-sm btn-fill" data-toggle="modal" data-target="#grade_modal_form">View Grading System</a></td>'+
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
                        '<div>'+
                            `<div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"> <b>SCHOLAR ACHIEVEMENTS</b></h4>
                                </div>
                                <div class="card-content">
                                    <table id="datatable_achievements" class="table table-bordered table-sm" cellspacing="0"
                                        width="100%">
                                        <!--Table head-->
                                        <thead>
                                            <tr>
                                                <th>Program</th>
                                                <th>Descriptions</th>
                                                <th>GWA</th>
                                                <th>Assistance Type</th>
                                            </tr>
                                        </thead>
                                        <!--Table head-->
                                        <!--Table body-->
                                        <tbody>
                                        </tbody>
                                        <!--Table body-->
                                    </table>
                                </div>
                            </div>`
                        '</div>'+
                    '</div>'+
                '</div>';

                
        if ( datatable.row( '.shown' ).length ) {
            $('.details-control', table.row( '.shown' ).node()).click();
        }
        row.child(response).show()
        
        $('#datatable_achievements').DataTable({
            'serverSide': true,
            'ajax':{
                'url': "{{ route('scholar.findall-achievements')}}",
                'datatype':'json',
                'type':'POST',
                'data':{ 
                    _token:"{{ csrf_token() }}",
                    scholar_id: data.sch_info.scholar_id
                }
            },
            "columns": [
                { "data": "title" },
                { "data": "description" },
                { "data": "gwa" },
                { "data": "assistance_type" }
            ],
            "columnDefs": [
                { "orderable": false, "targets": [3] }
            ]
        });

                
    } 

    const eval = (id) => {
        $('#tblSubject tbody').empty();
        $('#tblRequirements tbody').empty();
        $('#tblExamination tbody').empty();
        $('#tblEvents tbody').empty();
        let subjects = '';
        
        $('#modal_form').on('shown.bs.modal', function() {
            $(document).off('focusin.modal');
        });


        $.ajax({
            url:'/iskocab/evaluation/'+ id,
            type:'GET',
            dataType:'JSON',
            success:function(response){
                const { 
                        grade_list, gwa, requirement, id, required_exam, examination, exam_qualification,
                        events,required_event, required_requirements, required_grade, requirement_status, 
                        event_status, grading_system, assistance_type, scholar_id 
                    } = response;


                let totalUnits = 0;
                ctrAddField = 0;

                $('#application_id').val(id);
                required_list_event = events;
                scholar_id_active = scholar_id;
                required_requirement = requirement;
                gradingObj = grading_system;
                objResponse = response;

                
                /* get minimum required grades */
                let min = [];
                assistance_type.forEach(data => { min.push(data.grade_from); })

                /* selected min attainment */
                minGradePercentage = Math.min(...min);
                
                /* grade list */
                if(required_grade == '1'){
                    grade_list.forEach(data => {
                        ctrAddField++;
                        totalUnits += parseInt(data.no_of_units);
                        
                        subjects += `<tr>
                            <td>
                                <div class="input-group-sm mb-3">
                                    <input type="text" class="form-control input-sm" name="codes[]" placeholder="Code" value="${data.subject_code}">
                                </div>
                            </td>   
                            <td>
                                <div class="input-group-sm mb-3">
                                    <input type="number" class="form-control input-sm" name="units[]" min="0" max="9" value="${data.no_of_units}" placeholder="Units">
                                </div>
                            </td>
                            <td> ${set_combobox(gradingObj, data.grade)}</td>
                            <td>
                                <div class="input-group-sm mb-3">
                                    <a class="btn btn-sm btn-danger btn-fill btn-rotate" id="remove_field"><i class="fa fa-remove"></i></a>
                                </div>
                            </td>
                        </tr>`;
                    });
                    subjects += `<tr><td align="right">TOTAL: </td><td><b style="color:red"> ${totalUnits} Units</b></td><td colspan="2"><b style="color:red"> ${gwa} </b></td></tr>`;
                }else{
                    subjects = `<tr><td class="text-center" colspan="4"><label class="label label-primary">No Required Grades!</label></td></tr>`;
                }

                /* requirements */
                if(required_requirements == '1'){
                    requirement.forEach((element, index) => {
                        $('#tblRequirements tbody').append('<tr><td>'+element['name']+'</td><td><input type="checkbox" '+ (element['submitted']?'checked':'') +' name="document[]" value="'+element['id']+'"></td><</tr>')
                    });
                }else{
                    $('#tblRequirements tbody').append(`<tr><td class="text-center" colspan="2"><label class="label label-warning">No Required Requirements!</label></td></tr>`);
                }

                /* events */
                if(required_event == '1'){
                    events.forEach((element, index) => {
                        let content = `<tr style="background-color:${ ((element['attended'])?'':'#ff4d4d') }">
                                    <td>${element['title']}</td>
                                    <td>${element['date_of_event']}</td>
                                    <td>${((element['attended'])? element['in'] +" - "+element['out']:'<label class="label label-danger">Not Attended!</label>') }</td>
                                    <td><label class="label label-primary">${ ((element['attendee_remarks'])?element['attendee_remarks']:'No Remarks') }</label></td>
                                    <td><input type="checkbox" ${ ((element['attended'])?'checked':'') } name="event[]" class=" ${ ((element['attended'])?'disabled':'') } checkbox_event" value="${element['id']}"></td>
                                </tr>`;
                        $('#tblEvents tbody').append(content);
                    });
                }else{
                    let content = `<tr><td class="text-center" colspan="5"><label class="label label-warning">No Required Events!</label></td></tr>`;
                    $('#tblEvents tbody').append(content);
                }

                /* exam */
                if(required_exam == '1'){
                    let status = '';
                    let action = '';

                    if(exam_qualification == "QUALIFIED"){
                        status = '<label class="label label-primary">QUALIFIED</label>';
                        action = '<a onclick="reason(\'exam\')" class="btn btn-primary btn-sm btn-fill">Exemption on Exam</a>';
                    }else if(exam_qualification == "UNQUALIFIED"){
                        status = '<label class="label label-warning">UNQUALIFIED</label>';
                        action = '<label class="label label-warning">Not qualified to take Exam!</label>';
                    }else{
                        status = '<label class="label label-info">EXEMPTED ON EXAM</label>';
                        action = '<label class="label label-info">Exempted to take Exam!</label>';
                    }
                    // '+ ((exam_qualification == 'EXEMPTION')? 'disabled':'') +'
                    $('#tblExamination tbody').append(`<tr>
                        <td>${examination.title}</td>
                        <td>${ status }</td>
                        <td class="text-center">${ action }</td>
                    </tr>`);
                }else{
                    $('#tblExamination tbody').append(`<tr><td class="text-center" colspan="2"><label class="label label-warning">No Required Examination!</label></td></tr>`);
                }

                $('#tblSubject tbody').append(subjects);
                $('.selectpicker').selectpicker('refresh');
                $('#modal_form').modal('show');
            }
        })
    }

    const reason = (type, element="") => {

        Swal.fire({
            title: 'Reason for Exemption on '+ type,
            input: 'textarea',
            inputValue: ((type == 'exam')?((exempt_exam_reason != '')? exempt_exam_reason:''):''),
            inputPlaceholder: "Enter your reason for exemption on "+ type,
            showCancelButton: true,
            confirmButtonText: 'Exempt this Scholar on '+ type,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.dismiss != 'cancel') {

                if(type == 'events'){
                    if(result.value != ''){
                        exempt_event[element.value] = ({'event_id' : element.value, 'reason' : result.value });
                        element.checked = true;
                    }else{
                        swal("Error", 'Please provide valid reasons', "error");
                        element.checked = false;
                    }
                    console.log(exempt_event);
                }else{
                    exempt_exam_reason = result.value ;
                }
            }else{
                element.checked = false;
            }
        })
    }

    const set_combobox = (grading, grade) => {
        let option = '';
        const { official_grade, grade_to, grade_from, remarks } = grading;
        
        official_grade.forEach((data, index) => {  
            if(remarks[index].toUpperCase() == 'PASSED'){
                option += '<option '+ ((data == grade)?'selected':'')+' value='+ data +'>'+ data +'</option>'; 
            }
        });

        return `<div class="input-group-sm mb-3">
            <select class="form-control" name="grades[]">
                <option value="" disabled>Official Grade</option>
                ${option}
            </select>
        </div>`;


    }

    $("#evaluation-form").validate({
        rules: { },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Evaluate?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, evaluate it!'
            }).then((result) => {
                if (result.value) {
                    
                    var formData = new FormData($("#evaluation-form").get(0));
                    formData.append('required_requirement', JSON.stringify(required_requirement));
                    formData.append('required_event', JSON.stringify(required_list_event));
                    formData.append('exempt_event', JSON.stringify(exempt_event));
                    formData.append('exempt_exam_reason', JSON.stringify(exempt_exam_reason));

                    $.ajax({
                        url: "{{ route('evaluation.store') }}",
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function (response) {
                            // console.log(response);
                            if(response.success){
                                swal({
                                    title: response.title,
                                    text: response.messages,
                                    type: "success"
                                }).then(function() {
                                    $("#evaluation-form")[0].reset();
                                    datatable.ajax.reload( null, false );
                                    $('#modal_form').modal('hide');
                                });
                            }else{
                                swal({
                                    title: response.title,
                                    text: response.messages,
                                    type: "error"
                                });
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal("Error", errorThrown, "warning");
                        }
                    });
                }
            })
        }
    });

    const application = () =>{
        /* validate data */
        const units = validateUnits($("input[name='units[]']").map(function(){ return $(this).val(); }).get());
        const grades = validateInputedData($("select[name='grades[]']").map(function(){ return $(this).val(); }).get());
        const codes = validateInputedData($("input[name='codes[]']").map(function(){ return $(this).val(); }).get());

        if(!codes || !grades ){
            /* error on sodes, grades or on units */
            const error = !codes ? "Please validate inputed subjects!" : !grades ? "Please validate inputed grades!" :  false;
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
                            "scholar_id" : scholar_id_active,
                            "subject_code" : codes,
                            "grades" : grades,
                            "units" : units,
                            "min_percentage" : minGradePercentage
                        },
                        dataType:'JSON',
                        success:function(response){

                            if(response.isPassed == false){
                                swal("Error!", response.messages, "error");
                            }else{
                                
                                let title = (response.isPassed)? 'Grade Computed Successfully! <br> Please Click Save Button':"("+ response.GWA +") Grades not Applicable!";
                                let text = (response.isPassed)? "Your grades is: "+ response.GWA:"Your Grdaes DOESN'T meet the required grade for this application! Minimun Percentage: "+ response.passing.from;
                                let type = (response.isPassed)? 'success':'error';
                                let is_error = false;

                                if((units[1] < objResponse.number_of_units)){
                                    title = 'Error!';
                                    text = "Required units not reached, Required units : " + objResponse.number_of_units + " and above.";
                                    type = 'error';
                                    is_error = true;
                                }


                                Swal.fire({
                                    title: title,
                                    text: text,
                                    type: type,
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Update Application!'
                                }).then((result) => {
                                    if (result.value) {
                                        $.ajax({
                                            url:"/iskocab/application/"+objResponse.id,
                                            type:'PUT',
                                            data:{
                                                "_token" : "{{ csrf_token() }}",
                                                "program_module_id" : objResponse.module_id,
                                                "gwa" : response.GWA,
                                                "grades" : response.grades,
                                                "scholar_id" : scholar_id_active,
                                                "units_error" : is_error,
                                                "passed" : (is_error)? false : response.isPassed
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

    const viewGradeHistory = (application_id) => {
        
        
        $.ajax({
            url:'/iskocab/evaluation/grades-history/'+ application_id,
            type:'GET',
            dataType:'JSON',
            success:function(response){

                $('#grade_content').empty();
                response.forEach(grades_list => {
                    let subjects = `<div class="card col-md-6">
                        <div class="row"> 
                            <div class="col-md-4"><label class="label label-primary">DATE :</label></div> 
                            <div class="col-md-8">${grades_list['date']}</div>    
                        </div>
                        <div class="row"> 
                            <div class="col-md-4"><label class="label label-primary">REMARKS :</label></div> 
                            <div class="col-md-8">${grades_list['overall_remarks']}</div>    
                        </div>
                        <div class="row"> 
                            <div class="col-md-4"><label class="label label-primary">GWA :</label></div> 
                            <div class="col-md-8">${grades_list['gwa']}</div>    
                        </div>
                    </div>`;
                    let totalUnits = 0;

                    subjects += '<table class="table table-bordered"><tr><thead><th>SUBJECT</th><th>UNITS</th><th>GRADES</th></thead></tr>';
                    
                    /* grade list */
                    grades_list['grade_list'].forEach(data => {
                        totalUnits += parseInt(data.no_of_units);
                        
                        subjects += `<tr>
                            <td>${data.subject_code}</td>   
                            <td>${data.no_of_units}</td>
                            <td>${data.grade}</td>
                        </tr>`;
                    });
                    subjects += `<tr><td align="right">TOTAL: </td><td><b style="color:red"> ${totalUnits} Units</b></td><td><b style="color:red"> ${grades_list['gwa']} </b></td></tr></table><hr>`;
                    
                    $('#grade_content').append(subjects);
                })

                $('#grade_modal_form').modal('show');

                // const { grade_list, gwa, required_grade} = response;
                // let totalUnits = 0;
                // let subjects = '';

             

                // $('#tblSubject tbody').append(subjects);
                // $('#grade_list_form').modal('show');
            }
        });
    }

</script>
@endsection