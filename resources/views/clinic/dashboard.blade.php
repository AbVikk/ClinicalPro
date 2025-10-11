@extends('layouts.template')

@section('title', 'Clinic Dashboard')
@section('description', 'Clinic staff dashboard for patient management and appointments')

@section('css')
<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('template/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
@endsection

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2><i class="zmdi zmdi-view-dashboard"></i> <span>Clinic Dashboard</span></h2>
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Quick Stats -->
    <div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%"></div>
                    </div>
                    <span class="text-muted">Today's Patients</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="24" data-speed="1000" data-fresh-interval="700">24</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%"></div>
                    </div>
                    <span class="text-muted">Appointments</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="36" data-speed="1000" data-fresh-interval="700">36</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                    </div>
                    <span class="text-muted">Pending Tasks</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="12" data-speed="1000" data-fresh-interval="700">12</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%"></div>
                    </div>
                    <span class="text-muted">Completed Today</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="22" data-speed="1000" data-fresh-interval="700">22</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Today's</strong> Appointments</h2>
                    <ul class="header-dropdown">
                        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ url('/clinic/appointments') }}">View All</a></li>
                                <li><a href="{{ url('/clinic/appointments/create') }}">Add New</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Time</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar1.jpg') }}" alt="Patient">
                                        </div>
                                        <span>John Smith</span>
                                    </td>
                                    <td>Dr. John Smith</td>
                                    <td>09:00 AM</td>
                                    <td>Cardiology</td>
                                    <td><span class="badge badge-info">Scheduled</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Check-in</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar2.jpg') }}" alt="Patient">
                                        </div>
                                        <span>Mary Johnson</span>
                                    </td>
                                    <td>Dr. Emily Davis</td>
                                    <td>09:30 AM</td>
                                    <td>Orthopedics</td>
                                    <td><span class="badge badge-warning">In Progress</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-success">Complete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar3.jpg') }}" alt="Patient">
                                        </div>
                                        <span>Robert Brown</span>
                                    </td>
                                    <td>Dr. Michael Wilson</td>
                                    <td>10:00 AM</td>
                                    <td>General Checkup</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Registration -->
    <div class="row clearfix">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>New</strong> Patient Registration</h2>
                </div>
                <div class="body">
                    <form>
                        <div class="form-group">
                            <label for="patient_name">Patient Name</label>
                            <input type="text" class="form-control" id="patient_name" placeholder="Enter patient name">
                        </div>
                        <div class="form-group">
                            <label for="patient_email">Email</label>
                            <input type="email" class="form-control" id="patient_email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="patient_phone">Phone</label>
                            <input type="text" class="form-control" id="patient_phone" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select class="form-control show-tick" id="department">
                                <option value="">-- Select Department --</option>
                                <option value="cardiology">Cardiology</option>
                                <option value="orthopedics">Orthopedics</option>
                                <option value="neurology">Neurology</option>
                                <option value="general">General Checkup</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Register Patient</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Quick</strong> Actions</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <a href="{{ url('/clinic/patients') }}" class="btn btn-primary btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-accounts"></i>
                                <span>Patients</span>
                            </a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="{{ url('/clinic/appointments') }}" class="btn btn-success btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-calendar"></i>
                                <span>Appointments</span>
                            </a>
                        </div>
                        <div class="col-6 text-center m-t-20">
                            <a href="{{ url('/clinic/reports') }}" class="btn btn-warning btn-lg btn-block waves-effect">
                                <i class="zmdi zmdi-chart"></i>
                                <span>Reports</span>
                            </a>
                        </div>
                        <div class="col-6 text-center m-t-20">
                            <a href="{{ url('/clinic/settings') }}" class="btn btn-info btn-lg btn-block waves-effect">
                                <i class="zmdi zmdi-settings"></i>
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Additional Scripts for this page -->
@endsection