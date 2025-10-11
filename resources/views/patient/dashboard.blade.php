@extends('layouts.template')

@section('title', 'Patient Dashboard')
@section('description', 'Patient dashboard for appointments and medical records')

@section('css')
<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('template/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
@endsection

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2><i class="zmdi zmdi-view-dashboard"></i> <span>Patient Dashboard</span></h2>
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Patient Info -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="{{ asset('template/images/profile_av.jpg') }}" class="rounded-circle" alt="Profile Image" width="150">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h3>{{ Auth::user()->name }}</h3>
                            <p><strong>Patient ID:</strong> PT-{{ Auth::user()->id }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                            <p><strong>Address:</strong> 123 Main Street, City, State 12345</p>
                            <a href="{{ url('/patient/profile') }}" class="btn btn-primary">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                    </div>
                    <span class="text-muted">Upcoming Appointments</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="3" data-speed="1000" data-fresh-interval="700">3</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"></div>
                    </div>
                    <span class="text-muted">Medical Records</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="15" data-speed="1000" data-fresh-interval="700">15</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                    </div>
                    <span class="text-muted">Prescriptions</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="7" data-speed="1000" data-fresh-interval="700">7</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%"></div>
                    </div>
                    <span class="text-muted">Insurance Claims</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="2" data-speed="1000" data-fresh-interval="700">2</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Upcoming</strong> Appointments</h2>
                    <ul class="header-dropdown">
                        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ url('/patient/appointments') }}">View All</a></li>
                                <li><a href="{{ url('/patient/appointments/create') }}">Book New</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Doctor</th>
                                    <th>Date & Time</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/doctors/member1.png') }}" alt="Doctor">
                                        </div>
                                        <span>Dr. John Smith</span>
                                    </td>
                                    <td>Oct 18, 2025<br>09:00 AM</td>
                                    <td>Cardiology</td>
                                    <td><span class="badge badge-info">Confirmed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                        <a href="#" class="btn btn-sm btn-warning">Reschedule</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/doctors/member2.png') }}" alt="Doctor">
                                        </div>
                                        <span>Dr. Emily Davis</span>
                                    </td>
                                    <td>Oct 22, 2025<br>02:30 PM</td>
                                    <td>Orthopedics</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                        <a href="#" class="btn btn-sm btn-danger">Cancel</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/doctors/member3.png') }}" alt="Doctor">
                                        </div>
                                        <span>Dr. Michael Wilson</span>
                                    </td>
                                    <td>Oct 25, 2025<br>11:00 AM</td>
                                    <td>General Checkup</td>
                                    <td><span class="badge badge-info">Confirmed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                        <a href="#" class="btn btn-sm btn-warning">Reschedule</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Quick</strong> Actions</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <a href="{{ url('/patient/appointments') }}" class="btn btn-primary btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-calendar"></i>
                                <span>My Appointments</span>
                            </a>
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="{{ url('/patient/records') }}" class="btn btn-success btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-file"></i>
                                <span>Medical Records</span>
                            </a>
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="{{ url('/patient/prescriptions') }}" class="btn btn-warning btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-local-hospital"></i>
                                <span>Prescriptions</span>
                            </a>
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="{{ url('/patient/bills') }}" class="btn btn-info btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-money"></i>
                                <span>Billing & Payments</span>
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