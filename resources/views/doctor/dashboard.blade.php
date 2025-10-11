@extends('layouts.template')

@section('title', 'Doctor Dashboard')
@section('description', 'Doctor dashboard for patient management and appointments')

@section('css')
<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('template/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
@endsection

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2><i class="zmdi zmdi-view-dashboard"></i> <span>Doctor Dashboard</span></h2>
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Widgets -->
    <div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                    </div>
                    <span class="text-muted">Today's Appointments</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="12" data-speed="1000" data-fresh-interval="700">12</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%"></div>
                    </div>
                    <span class="text-muted">My Patients</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="142" data-speed="1000" data-fresh-interval="700">142</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%"></div>
                    </div>
                    <span class="text-muted">Pending Prescriptions</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="8" data-speed="1000" data-fresh-interval="700">8</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%"></div>
                    </div>
                    <span class="text-muted">Completed Today</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="9" data-speed="1000" data-fresh-interval="700">9</span></h4>
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
                                <li><a href="{{ url('/doctor/appointments') }}">View All</a></li>
                                <li><a href="{{ url('/doctor/appointments/create') }}">Add New</a></li>
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
                                    <th>Time</th>
                                    <th>Reason</th>
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
                                    <td>09:00 AM</td>
                                    <td>Regular Checkup</td>
                                    <td><span class="badge badge-info">Scheduled</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar2.jpg') }}" alt="Patient">
                                        </div>
                                        <span>Mary Johnson</span>
                                    </td>
                                    <td>10:30 AM</td>
                                    <td>Follow-up</td>
                                    <td><span class="badge badge-warning">In Progress</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar3.jpg') }}" alt="Patient">
                                        </div>
                                        <span>Robert Brown</span>
                                    </td>
                                    <td>02:00 PM</td>
                                    <td>Consultation</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="row clearfix">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Recent</strong> Patients</h2>
                </div>
                <div class="body">
                    <ul class="list-unstyled activity">
                        <li>
                            <div class="media">
                                <div class="media-left">
                                    <div class="avatar">
                                        <img src="{{ asset('template/images/xs/avatar4.jpg') }}" alt="Patient">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="m-t-0">Jennifer Davis</h6>
                                    <p>Last visit: 2 days ago</p>
                                    <small class="text-muted">Cardiology</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <div class="media-left">
                                    <div class="avatar">
                                        <img src="{{ asset('template/images/xs/avatar5.jpg') }}" alt="Patient">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="m-t-0">Michael Wilson</h6>
                                    <p>Last visit: 1 week ago</p>
                                    <small class="text-muted">Orthopedics</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <div class="media-left">
                                    <div class="avatar">
                                        <img src="{{ asset('template/images/xs/avatar6.jpg') }}" alt="Patient">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="m-t-0">Sarah Thompson</h6>
                                    <p>Last visit: 2 weeks ago</p>
                                    <small class="text-muted">Pediatrics</small>
                                </div>
                            </div>
                        </li>
                    </ul>
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
                            <a href="{{ url('/doctor/patients') }}" class="btn btn-primary btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-accounts"></i>
                                <span>My Patients</span>
                            </a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="{{ url('/doctor/appointments') }}" class="btn btn-success btn-lg btn-block waves-effect m-t-20">
                                <i class="zmdi zmdi-calendar"></i>
                                <span>Appointments</span>
                            </a>
                        </div>
                        <div class="col-6 text-center m-t-20">
                            <a href="{{ url('/doctor/prescriptions') }}" class="btn btn-warning btn-lg btn-block waves-effect">
                                <i class="zmdi zmdi-file"></i>
                                <span>Prescriptions</span>
                            </a>
                        </div>
                        <div class="col-6 text-center m-t-20">
                            <a href="{{ url('/doctor/reports') }}" class="btn btn-info btn-lg btn-block waves-effect">
                                <i class="zmdi zmdi-chart"></i>
                                <span>Reports</span>
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
<script src="{{ asset('template/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('template/plugins/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
@endsection