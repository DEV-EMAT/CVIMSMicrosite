@extends('layouts.app2')

@section('style')
<style>
    input[type=checkbox]
    {
        zoom: 1.75;
    }
</style>

@endsection

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <h4 class="card-title"><b>Access List</b></h4>
                                <p class="category">Create | Update | View | Delete Access</p>
                            </div>
                            <div class="col-lg-2">

                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <form id="create_form">

                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="">Level of Access</label>
                                <input type="text" class="form-control" name="position" id="position" aria-describedby="helpId" placeholder="access name">
                            </div>
                            <div class="form-group">
                                <label for="">Department</label>
                                <select class="selectpicker form-control" data-live-search="true" name="department" id="department">
                                    <option value="" disabled selected>SELECT</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div id="acordeon">
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#collapseOne" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        ECABS PERMISSION
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>ECABS Header</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewECABSHeader"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Account Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createAccount"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateAccount"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewAccount"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteAccount"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreAccount"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="resetAccount"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Account Deletion History</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteHistory"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Account Verification</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="verifyAccount"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Department Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createDepartment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateDepartment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewDepartment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteDepartment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreDepartment"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Updates Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createUpdates"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateUpdates"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewUpdates"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteUpdates"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreUpdates"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Barangay Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createBarangay"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateBarangay"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewBarangay"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteBarangay"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreBarangay"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Access Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createAccess"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateAccess"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewAccess"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteAccess"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreAccess"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Activity Logs</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewLogs"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Pre-Registration Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createPreRegistration"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updatePreRegistration"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewPreRegistration"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#collapseTwo" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        COVID TRACER PERMISSION
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>COVID Tracer Header</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewCovidHeader"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Covid Dashboard</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewCovidDashboard"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Establishment Info Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createEstinfo"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateEstinfo"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewEstinfo"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteEstinfo"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreEstinfo"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Establishment Staff</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createEstStaff"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewEstStaff"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteEstStaff"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Print Establishment Qr Code</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewPrintEstQrCode"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Establishment Category Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createEstcat"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateEstcat"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewEstcat"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteEstcat"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreEstcat"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Emergency Hotline Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createHotline"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateHotline"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewHotline"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteHotline"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreHotline"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Patient Monitoring Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createPatientMonitoring"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Patient Reports</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewPatientReports"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Assign Investigator</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createInvestigator"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Investigator Monitoring</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createInvestigatorMonitoring"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Covid Tracer</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createCovidTracer"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Covid Tracer Summary</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewCovidSummary"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Print User QR Code</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewPrintUserCode"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Covid Cases Updates</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createCovidCasesUpdates"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewCovidCasesUpdates"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Patient Encoding</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createEncoding"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateEncoding"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Patient Encoding (Print Form)</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewEncodingPrint"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>SMS Notification Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createSmsNotification"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateSmsNotification"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSmsNotification"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteSmsNotification"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreSmsNotification"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#collapseThree" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        GUEST ACCOUNT PERMISSION
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Account Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createGuestAccount"></td></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewGuestAccount"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Print Guest QR Code</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewPrintGuestCode"></td></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#collapseFour" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        ISKOCAB PERMISSION
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>ISKOCAB Header</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewISKOCABHeader"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Course Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createCourse"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateCourse"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewCourse"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteCourse"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreCourse"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>School Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createSchool"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateSchool"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSchool"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteSchool"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreSchool"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Educational Attainment Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createEducationalAttainment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateEducationalAttainment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewEducationalAttainment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteEducationalAttainment"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreEducationalAttainment"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Scholar Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createScholar"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateScholar"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewScholar"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteScholar"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreScholar"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Scholar Verification</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="verifyScholar"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Subject Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createSubject"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateSubject"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSubject"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteSubject"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreSubject"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Question Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createQuestion"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateQuestion"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewQuestion"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteQuestion"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreQuestion"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Examination Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createExamination"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateExamination"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewExamination"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteExamination"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreExamination"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Scholar Program Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createScholarProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateScholarProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewScholarProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteScholarProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreScholarProgram"></td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Print Scholar Program</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="printScholarProgram"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Scholarship Management (Application)</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSchApplication"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Scholarship Management (Evaluation)</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSchEvaluation"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Scholarship Management (Assesment)</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSchAssessment"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Scholarship Summary</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewScholarshipSummary"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#collapseFive" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        COMPREHENSIVE PERMISSION
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Comprehensive Header</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewComprehensiveHeader"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Event Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createEvent"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateEvent"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewEvent"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteEvent"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreEvent"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Select Event</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSelectEvent"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Requirement Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createRequirement"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateRequirement"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewRequirement"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteRequirement"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreRequirement"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Program Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteProgram"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreProgram"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Qr Code Printing</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewQrCodePrinting"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>File Manager Management</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewFileManagerManagement"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#collapseSix" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        EMERGENCY RESPONSE PERMISSION
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseSix" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Emergency Response Header</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewEmergencyResponseHeader"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Incident Category Management</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createIncidentCategory"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateIncidentCategory"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteIncidentCategory"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreIncidentCategory"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#collapseSeven" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        COVID-19 VACCINATION
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseSeven" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Vaccination Header</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewVaccinationHeader"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Vaccination Dashboard</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewVaccinationDashboard"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Counseling and Final Consent Evaluation</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewCounselingAndFinalConsentEvaluation"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Registration and Validation</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createRegistrationAndValidation"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateRegistrationAndValidation"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewRegistrationAndValidation"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteRegistrationAndValidation"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreRegistrationAndValidation"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Assessment</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewAssessment"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Assessment Form Printing</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="printAssessment"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Certification Printing</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="printCertificate"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Consent Form Printing</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="printConsent"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Covid 19 File Upload</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewCovid19FileUpload"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Vaccination Monitoring</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateVaccinationMonitoring"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewVaccinationMonitoring"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteVaccinationMonitoring"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Vaccination Monitoring Summary</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewVaccinationPatientMonitoring"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                
                                                        <tr>
                                                            <td>VAS Line Information</td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center"><input type="checkbox" name="permission[]" value="viewVASLineInfo"></td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center">-</td>
                                                        </tr>
                                                                <tr>
                                                                    <td>Vaccinator</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createVaccinator"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateVaccinator"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewVaccinator"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteVaccinator"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreVaccinator"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Health Facility</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createHealthFacility"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateHealthFacility"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewHealthFacility"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteHealthFacility"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreHealthFacility"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Assign Staff every Facility</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewAssignStaff"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Vaccine Category</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="createVaccineCategory"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="updateVaccineCategory"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewVaccineCategory"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="deleteVaccineCategory"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="restoreVaccineCategory"></td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Transfer Pre Registration</td>
                                                                     <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewTransferOnlinePreRegistration"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Second Dose Verification</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="viewSecondDoseVerification"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Generate Masterlist (VIMS-IR)</td>
                                                                    <td class="text-center">-</td>
                                                                     <td class="text-center"><input type="checkbox" name="permission[]" value="exportVIMSIRReport"></td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="generateMasterlistVimsIR"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td>Export VAS Report</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="exportVASReport"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>VAS Report Per Date</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="VASReportPerDate"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Format Date</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="changeDateFormat"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-border panel-default">
                                            <a data-toggle="collapse" href="#databasebackup" class="collapsed" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        DATABASE MNGT
                                                        <i class="ti-angle-down"></i>
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="databasebackup" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting  text-center" style="width: 200px;"> Sub-systems </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Create </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Update </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> View </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Delete </th>
                                                                    <th class="sorting  text-center" style="width: 100px;"> Restore </th>
                                                                    <th class="sorting  text-center" style="width: 150px;"> Reset Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Database Backup</td>
                                                                    <td class="text-center"><input type="checkbox" name="permission[]" value="databaseBackUpAccess"></td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-fill">Save Changes</button>
                                <a href="{{ route('access.index') }}" class="btn btn-warning btn-fill">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col-md-12 -->
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
         /* add barangay on selectbox */
         $.ajax({
            url:'{{ route('department.findall2')}}',
            type:'GET',
            dataType:'JSON',
            success:function(response){
                response.forEach((value)=>{
                    $('[name="department"]').append('<option value='+value.id+'>'+ value.department+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                });
            }
        });

        $("#create_form").validate({
            rules: {
                access: {
                    required: true,
                    minlength:3
                },
                status: {
                    required: true,
                }
            },
            submitHandler: function (form) {
                Swal.fire({
                    title: 'Save new access?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {

                        var formData = new FormData($("#create_form").get(0));
                        //process loader true
                        processObject.showProcessLoader();
                        $.ajax({
                            url: "{{ route('access.store') }}",
                            type: "POST",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            success: function (response) {
                                if(response.success){
                                    swal({
                                        title: "Success!",
                                        text: response.messages,
                                        type: "success"
                                    }).then(function() {
                                        $("#create_form")[0].reset();
                                    });
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }else{
                                    swal.fire({
                                        title: "Oops! something went wrong.",
                                        text: response.messages,
                                        type: "error"
                                    })
                                    //process loader false
                                    processObject.hideProcessLoader();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal.fire({
                                     title: "Oops! something went wrong.",
                                     text: errorThrown,
                                     type: "error"
                                })
                                //process loader false
                                processObject.hideProcessLoader();
                            }
                        });
                    }
                })
            }
        });

    </script>
@endsection
