@extends('layouts.app2')

@section('location')
{{$title}}
@endsection

@section('content')
    <!-- Display All Data -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="min-height:50vh">
                        
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10">
                                    <h4 class="card-title"><b>FILE UPLOAD 1</b> (Old Format)</h4>
                                </div>
                            </div>

                        </div>
                        <div class="card-content">
                            <div style="display: flex">
                                <input type="file" class="form-control fileUpload" data-type="OLD_FORMAT" style="width: 20%"/>
                                <button class="btn btn-fill" id="uploadAll" onclick="uploadAll('OLD_FORMAT')" disabled>UPLOAD</button>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-6 col-sm-6" id="dvExcel"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="min-height:50vh">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10">
                                    <h4 class="card-title"><b>FILE UPLOAD 2</b>(New Format)</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div style="display: flex">
                                <input type="file" class="form-control fileUpload" data-type="NEW_FORMAT" style="width: 20%"/>
                                <button class="btn btn-fill" id="uploadAll2" onclick="uploadAll('NEW_FORMAT')" disabled>UPLOAD</button>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-6 col-sm-6" id="dvExcel2"></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col-md-12 -->
            </div>
        </div>
    </div>
    <!-- End Display All Data -->
@endsection

@section('js')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
<script type="text/javascript">
    let whole_data = [];
    const uploadAll = (type)=>{
        Swal.fire({
            title: 'Register your Entry?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!',
            html: "<b>Upload File",
            footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    url:'{{ route('file-upload.store') }}',
                    type:'POST',
                    data:{
                        '_token': '{{ csrf_token() }}',
                        'array_of_data': JSON.stringify(whole_data),
                        'file_type': type
                    },
                    beforeSend: function(){
                        processObject.showProcessLoader();
                    },success:function(response){
                        if(response.success){
                            swal.fire({
                                title: response.title,
                                text: response.messages,
                                type: "success",
                                html: "<b>File Uploaded",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            });
                            
                            if(response.type == 'OLD_FORMAT'){
                                $('#dvExcel').empty();
                            }else{
                                $('#dvExcel2').empty();
                            }

                        }else{
                            swal.fire({
                                title: "Oops! something went wrong.",
                                html: "<b>" + response.messages +"! <br>An unexpected error seems to have occured. Why not try refreshing your page? Or you can contact us if the problem persists.</b>",
                                type: "error",
                                footer: '<a href = "mailto: enterprise.cabuyao@gmail.com">Send an email to us!</a>'
                            }); 

                            let fields = '<label class="label label-warning"><b>NOTE: </b> This list of data is not uploded!</label> <div class="panel-group" id="accordion">';
                            response.conflict_data.forEach((data, index) => {
                                fields += `<div class="panel panel-border panel-default">
                                    <a data-toggle="collapse" href="#collapse${index}">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                Conflict on row: ${data.row + 2}
                                                <i class="ti-angle-down"></i>
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapse${index}" class="panel-collapse collapse">
                                        <div class="panel-body"><ul><li>Duplicated data!</li></ul></div>
                                    </div>
                                </div>`;
                            });
                            fields += '</div>';

                            if(response.type == 'OLD_FORMAT'){
                                $('#dvExcel').empty();
                                $('#dvExcel').append(fields);
                            }else{
                                $('#dvExcel2').empty();
                                $('#dvExcel2').append(fields);
                            }
                        }
                    },
                    complete: function(){
                        processObject.hideProcessLoader();
                    },
                });
            }
        })
    }

    /* reset upload fields */
    $('#fileUpload').on('click touchstart' , function(){ $(this).val(''); whole_data = []; $('#dvExcel').empty(); });
    $('#fileUpload2').on('click touchstart' , function(){ $(this).val(''); whole_data = []; $('#dvExcel2').empty(); });

    /* file upload */
    $(".fileUpload").change(function () {
        
        //Reference the FileUpload element.
        var fileUpload = $(this)[0];
        var type = $(this).data("type");

        //Validate whether File is valid Excel file.
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
        if (regex.test(fileUpload.value.toLowerCase())) {
            
            processObject.showProcessLoader();
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();

                //For Browsers other than IE.
                if (reader.readAsBinaryString) {
                    reader.onload = function (e) {
                        ProcessExcel(e.target.result, type);
                    };
                    reader.readAsBinaryString(fileUpload.files[0]);
                } else {
                    //For IE Browser.
                    reader.onload = function (e) {
                        var data = "";
                        var bytes = new Uint8Array(e.target.result);
                        for (var i = 0; i < bytes.byteLength; i++) {
                            data += String.fromCharCode(bytes[i]);
                        }
                        ProcessExcel(data, type);
                    };
                    reader.readAsArrayBuffer(fileUpload.files[0]);
                }
            } else {
                alert("This browser does not support HTML5.");
            }
        } else {
            alert("Please upload a valid Excel file.");
        }
    });

    const ProcessExcel = (data, type) => {
        let btnUpload = "";
        let divDisplay = "";

        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });

        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];

        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
        // console.log('fresh', excelRows);
        // console.log('row 1',excelRows[0]);
        // console.log('row 2', excelRows[1]);

        if(type == 'NEW_FORMAT') { excelRows.push(excelRows[0]); }
        var validate = validateWholeSheet(excelRows, type);
        // console.log(validate);

        if(type == 'OLD_FORMAT'){
            btnUpload = $('#uploadAll');
            divDisplay = $('#dvExcel');
        }else{
            btnUpload = $('#uploadAll2');
            divDisplay = $('#dvExcel2');
        }


        divDisplay.empty();
        divDisplay.append(`<div class="card">
            <div class="card-content">
                <div class="row">
                    <label class="col-md-6">Total number of Entries: </label>
                    <label class="col-md-6">${excelRows ? excelRows.length - 1 : 0} row/s</label>
                </div>
                <div class="row">
                    <label class="col-md-6">Total number of errors: </label>
                    <label class="col-md-6">${validate.rowHasError ? validate.rowHasError.length : 0} row/s</label>
                </div>
            </div>
        </div>`);

        if(!validate.isError){
            processObject.hideProcessLoader();
            btnUpload.prop('disabled', false);

            whole_data = validate.cleanData;
            
            // console.log(Object.keys(whole_data));
        }else{
            processObject.hideProcessLoader();
            btnUpload.prop('disabled', true);

            let fields = '<label class="label label-warning"><b>NOTE: </b> Please re-evaluate the uploaded file to proceed on the uploading!</label> <div class="panel-group" id="accordion">';
            validate.rowHasError.forEach((data, index)=> {
                let row_conflict = (type == 'OLD_FORMAT')? data.row + 2 : data.row + 1; 
                fields += `<div class="panel panel-border panel-default">
                        <a data-toggle="collapse" href="#collapse${type + index}">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Conflict on row: ${ row_conflict }
                                    <i class="ti-angle-down"></i>
                                </h4>
                            </div>
                        </a>
                        <div id="collapse${type + index}" class="panel-collapse collapse">
                            <div class="panel-body"><ul>`;

                        data.fields.forEach(data => {
                            fields +=`<li> ${data} </li>`;
                        });

                fields += `</ul></div>
                        </div>
                    </div>`;

            });
            fields += '</div>';
            divDisplay.append(fields);
            
        }
    };

    const validateWholeSheet  = (sheets, type) => {

        let keys = Object.keys(sheets[0]);
        delete sheets[0];
        let row_has_error = [];
        let error_stat = false;
        let clean_data = [];

        sheets.forEach((data, index) => {
            /* check if has empty fields */
            let validator = validatePerRow(data, keys);

            // error_stat = false;
            
            /* has error */
            if(validator.isError){
                error_stat = true;
                row_has_error.push({'row': index, 'fields': validator.fields });
            }else{
                let $exist_user = false;

                if(type == 'OLD_FORMAT'){
                    clean_data.forEach(row => {
                        if((row.Firstname == data.Firstname) &&
                            (row.Lastname == data.Lastname) &&
                            (row.Birthdate_ == data.Birthdate_)){
                                $exist_user = true;
                                return ;
                        }else{
                            $exist_user = false;
                            return ;
                        }
                    });
                }else{
                    clean_data.forEach(row => {
                        if((row['First_Name*'] == data['First_Name*']) &&
                            (row['Last_Name*'] == data['Last_Name*']) &&
                            (row['Birthdate_mm/dd/yyyy_*'] == data['Birthdate_mm/dd/yyyy_*'])){
                                $exist_user = true;
                                return ;
                        }else{
                            $exist_user = false;
                            return ;
                        }
                    });
                }


                // clean_data.push(data);

                // console.log(clean_data);
                if($exist_user){
                    error_stat = true;
                    row_has_error.push({'row': index, 'fields': [`Duplicated data!`] });
                }else{
                    data["row"] = index;
                    clean_data.push(data);
                }
            }
        });

        if(!error_stat){
            return { 'isError': error_stat, 'rowHasError' : row_has_error, 'cleanData' : clean_data };
        }else{
            return { 'isError': error_stat, 'rowHasError' : row_has_error };
        }
    }

    const validatePerRow = (dataPerRow, keys) => {
        /* if row all columns is empty ignore, else check per columns */

        let fields = [];
        let flag_error = false;
        keys.forEach(key_data => {
            if(dataPerRow[key_data] === undefined || dataPerRow[key_data] === " " || dataPerRow[key_data] === ""){
                flag_error = true;
                fields.push(key_data + ' is empty!');
            }
        });

        return { 'isError' : flag_error, 'fields' : fields };
    }


    //=============================================================================================


</script>
@endsection
