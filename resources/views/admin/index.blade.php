<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<title>Clinical Pro || Home</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- jVectorMap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}">

<!-- Morris CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/morrisjs/morris.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan ls-closed">
<!-- Page Loader -->

@include('admin.sidemenu')

<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12">
                <h2>Dashboard
                <small>Welcome to Clinical Pro Admin dashboard</small>
                </h2>
            </div>            
            <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                <div class="inlineblock text-center m-r-15 m-l-15 d-none d-lg-inline-block">
                    <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#fff">3,2,6,5,9,8,7,9,5,1,3,5,7,4,6</div>
                    <small class="col-white">Visitors</small>
                </div>
                <div class="inlineblock text-center m-r-15 m-l-15 d-none d-lg-inline-block">
                    <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#fff">1,3,5,7,4,6,3,2,6,5,9,8,7,9,5</div>
                    <small class="col-white">Operations</small>
                </div>
                <button class="btn btn-white btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="index.blade.php"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        
        <div class="row clearfix">
            <div class="col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                <div class="body">
                                    <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ intval(str_replace(',', '', $formattedNetCashFlow ?? '0')) }}" data-speed="1000" data-fresh-interval="700">{{ $formattedNetCashFlow ?? '0.00' }}</h2>
                                    <p class="text-muted">Net Cash Flow (Current Month)</p>
                                    <span id="linecustom1">1,4,2,6,5,2,3,8,5,2</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                <div class="body">
                                    <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ intval(str_replace(',', '', $formattedTotalPayments ?? '0')) }}" data-speed="2000" data-fresh-interval="700">{{ $formattedTotalPayments ?? '0.00' }}</h2>
                                    <p class="text-muted ">Total Payments (Current Month)</p>
                                    <span id="linecustom2">2,9,5,5,8,5,4,2,6</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                <div class="body">
                                    <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ intval(str_replace(',', '', $formattedTotalDisbursements ?? '0')) }}" data-speed="2000" data-fresh-interval="700">{{ $formattedTotalDisbursements ?? '0.00' }}</h2>
                                    <p class="text-muted">Total Disbursements (Current Month)</p>
                                    <span id="linecustom3">1,5,3,6,6,3,6,8,4,2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $totalUsers ?? 1600 }}" data-speed="2500" data-fresh-interval="700">{{ $totalUsers ?? 1600 }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Total Users</p>
                        <div class="progress">
                            <div class="progress-bar l-blush" role="progressbar" aria-valuenow="{{ $progressPercentage ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progressPercentage ?? 68 }}%;"></div>
                        </div>
                        <small>Overall Growth: {{ number_format($actualPercentage ?? 0, 1) }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $newRegistrations ?? 3218 }}" data-speed="2500" data-fresh-interval="1000">{{ $newRegistrations ?? 3218 }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">New Registrations (7 days)</p>
                        <div class="progress">
                            <div class="progress-bar l-green" role="progressbar" aria-valuenow="{{ $newRegProgress ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $newRegProgress ?? 68 }}%;"></div>
                        </div>
                        <small>Change {{ $regChangePercentage ?? 23 }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $pendingInvitations ?? 0 }}" data-speed="2500" data-fresh-interval="1000"><i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Invitations <i class=""></i></p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                        </div>
                        <small>Change 50%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $pendingAppointments ?? 284 }}" data-speed="2500" data-fresh-interval="1000">{{ $pendingAppointments ?? 284 }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Pending Appointments</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="{{ $pendingProgress ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pendingProgress ?? 68 }}%;"></div>
                        </div>
                        <small>Change {{ $pendingChangePercentage ?? 50 }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="284" data-speed="2500" data-fresh-interval="1000">284 <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Well Smiley Faces <i class="zmdi zmdi-mood"></i></p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                        </div>
                        <small>Change 50%</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="1" data-speed="2500" data-fresh-interval="1000">System Status <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">System Update/Backup</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="{{ $systemProgress ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $systemProgress ?? 68 }}%;"></div>
                        </div>
                        <small>{{ $systemInfo ?? 'Update: 2 days ago, Backup: 1 day ago' }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card patient_list">
                    <div class="header">
                        <h2><strong>Recent</strong> Appointments</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="{{ route('admin.appointments.index') }}">View All Appointments</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Patient Name</th>
                                        <th>Day & Time</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                    <tr>
                                        <td>
                                            @if($appointment->patient->photo)
                                                <img src="{{ asset('storage/' . $appointment->patient->photo) }}" alt="{{ $appointment->patient->name }}" class="rounded-circle" width="35" height="35">
                                            @else
                                                <img src="http://via.placeholder.com/35x35" alt="{{ $appointment->patient->name }}" class="rounded-circle">
                                            @endif
                                        </td>
                                        <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            @if($appointment->doctor)
                                                {{ $appointment->doctor->name }}
                                            @else
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="assignDoctorDropdown{{ $appointment->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Assign Doctor
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="assignDoctorDropdown{{ $appointment->id }}">
                                                        @foreach($availableDoctors as $doctor)
                                                            <a class="dropdown-item assign-doctor" href="#" data-appointment-id="{{ $appointment->id }}" data-doctor-id="{{ $doctor->id }}" data-doctor-name="{{ $doctor->name }}">{{ $doctor->name }}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($appointment->status) }}</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"><i class="zmdi zmdi-eye"></i> View Details</a>
                                                    <a class="dropdown-item" href="#"><i class="zmdi zmdi-edit"></i> Edit Appointment</a>
                                                    <a class="dropdown-item" href="#"><i class="zmdi zmdi-delete"></i> Cancel Appointment</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card patient_list">
                    <div class="header">
                        <h2><strong>New</strong> Patients</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="{{ route('admin.patients.index') }}">View All Patients</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registered Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newPatients as $patient)
                                    <tr>
                                        <td>
                                            @if($patient->photo)
                                                <img src="{{ asset('storage/' . $patient->photo) }}" alt="{{ $patient->name }}" class="rounded-circle" width="35" height="35">
                                            @else
                                                <img src="http://via.placeholder.com/35x35" alt="{{ $patient->name }}" class="rounded-circle">
                                            @endif
                                        </td>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->email }}</td>
                                        <td>{{ $patient->created_at ? $patient->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td><span class="badge badge-{{ $patient->status == 'verified' ? 'success' : 'warning' }}">{{ ucfirst($patient->status ?? 'pending') }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Revenue</strong> Growth (Last 6 Months)</h2>
                        <ul class="header-dropdown">
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>                    
                    <div class="body">
                        <canvas id="revenueChart" height="120"></canvas>                               
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Appointment</strong> Status</h2>
                    </div>
                    <div class="body">
                        <canvas id="statusChart" height="285"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Dr.</strong> Timeline</h2>
                        <ul class="header-dropdown">                            
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="new_timeline">
                            <div class="header">
                                <div class="color-overlay">
                                    <div class="day-number">8</div>
                                    <div class="date-right">
                                    <div class="day-name">Monday</div>
                                    <div class="month">February 2018</div>
                                    </div>
                                </div>                                
                            </div>
                            <ul>
                                <li>
                                    <div class="bullet pink"></div>
                                    <div class="time">5pm</div>
                                    <div class="desc">
                                        <h3>New Icon</h3>
                                        <h4>Mobile App</h4>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet green"></div>
                                    <div class="time">3 - 4pm</div>
                                    <div class="desc">
                                        <h3>Design Stand Up</h3>
                                        <h4>Hangouts</h4>
                                        <ul class="list-unstyled team-info margin-0 p-t-5">                                            
                                            <li><img src="http://via.placeholder.com/35x35" alt="Avatar"></li>
                                            <li><img src="http://via.placeholder.com/35x35" alt="Avatar"></li>                                            
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet orange"></div>
                                    <div class="time">12pm</div>
                                    <div class="desc">
                                        <h3>Lunch Break</h3>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet green"></div>
                                    <div class="time">9 - 11am</div>
                                    <div class="desc">
                                        <h3>Finish Home Screen</h3>
                                        <h4>Web App</h4>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-6">
                        <div class="card top_counter">
                            <div class="body">
                                <div class="icon xl-slategray"><i class="zmdi zmdi-account"></i> </div>
                                <div class="content">
                                    <div class="text">New Patient</div>
                                    <h5 class="number">27</h5>
                                </div>
                            </div>                    
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card top_counter">
                            <div class="body">
                                <div class="icon xl-slategray"><i class="zmdi zmdi-account"></i> </div>
                                <div class="content">
                                    <div class="text">OPD Patient</div>
                                    <h5 class="number">19</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card top_counter">
                            <div class="body">
                                <div class="icon xl-slategray"><i class="zmdi zmdi-bug"></i> </div>
                                <div class="content">
                                    <div class="text">Operations</div>
                                    <h5 class="number">08</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>           
        </div>        
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card tasks_report">
                    <div class="header">
                        <h2><strong>Total</strong> Revenue</h2>                        
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="javascript:void(0);">2017 Year</a></li>
                                    <li><a href="javascript:void(0);">2016 Year</a></li>
                                    <li><a href="javascript:void(0);">2015 Year</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body text-center">
                        <h4 class="margin-0">Total Sale</h4>
                        <h6 class="m-b-20">2,45,124</h6>
                        <input type="text" class="knob dial1" value="66" data-width="100" data-height="100" data-thickness="0.1" data-fgColor="#212121" readonly>
                        <h6 class="m-t-20">Satisfaction Rate</h6>
                        <small class="displayblock">47% Average <i class="zmdi zmdi-trending-up"></i></small>
                        <div class="sparkline m-t-20" data-type="bar" data-width="97%" data-height="28px" data-bar-Width="2" data-bar-Spacing="8" data-bar-Color="#212121">3,2,6,5,9,8,7,8,4,5,1,2,9,5,1,3,5,7,4,6</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="card patient_list">
                    <div class="header">
                        <h2><strong>New</strong> Patient List</h2>                        
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="javascript:void(0);">2017 Year</a></li>
                                    <li><a href="javascript:void(0);">2016 Year</a></li>
                                    <li><a href="javascript:void(0);">2015 Year</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Diseases</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><img src="http://via.placeholder.com/35x35" alt="Avatar" class="rounded-circle"></td>
                                        <td>Virginia</td>
                                        <td>123 6th St. Melbourne, FL 32904</td>
                                        <td><span class="badge badge-danger">Fever</span> </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><img src="http://via.placeholder.com/35x35" alt="Avatar" class="rounded-circle"></td>
                                        <td>Julie </td>
                                        <td>71 Pilgrim Avenue Chevy Chase, MD 20815</td>
                                        <td><span class="badge badge-info">Cancer</span> </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><img src="http://via.placeholder.com/35x35" alt="Avatar" class="rounded-circle"></td>
                                        <td>Woods</td>
                                        <td>70 Bowman St. South Windsor, CT 06074</td>
                                        <td><span class="badge badge-warning">Lakva</span> </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><img src="http://via.placeholder.com/35x35" alt="Avatar" class="rounded-circle"></td>
                                        <td>Lewis</td>
                                        <td>4 Goldfield Rd.Honolulu, HI 96815</td>
                                        <td><span class="badge badge-success">Dental</span> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>
<!-- Assign Doctor Confirmation Modal -->
<div class="modal fade" id="assignDoctorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="smallModalLabel">Assign Doctor</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to assign <strong id="doctorName"></strong> to this appointment?
                <input type="hidden" id="appointmentId" value="">
                <input type="hidden" id="doctorId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round waves-effect" id="confirmAssign">ASSIGN</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<!-- Morris Plugin Js -->
<script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script>

<!-- JVectorMap Plugin Js -->
<script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script>

<script src="{{ asset('assets/js/pages/widgets/infobox/infobox-1.js') }}"></script>

<!-- Jquery Knob, Count To, Sparkline Js -->
<script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/index.js') }}"></script>
<script src="{{ asset('assets/js/pages/charts/jquery-knob.js') }}"></script>
<script src="{{ asset('assets/js/pages/cards/basic.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. REVENUE CHART ---
    // We use a try-catch to ensure the page loads even if data is empty
    try {
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        // Pass data from Controller
        const revenueData = @json($revenueData);
        
        if(revenueData && revenueData.length > 0) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.map(d => d.month),
                    datasets: [{
                        label: 'Revenue (NGN)',
                        data: revenueData.map(d => d.total),
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        } else {
            // Empty State for Chart
            const revenueCtx = document.getElementById('revenueChart');
            revenueCtx.parentNode.innerHTML = '<p class="text-center text-muted m-t-20">No financial data available yet.</p>';
        }
    } catch (e) { console.log("Chart Error:", e); }

    // --- 2. STATUS PIE CHART ---
    try {
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusRaw = @json($appointmentStats);
        
        // Check if we have any data keys
        if(Object.keys(statusRaw).length > 0) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusRaw).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                    datasets: [{
                        data: Object.values(statusRaw),
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { 
                        legend: { position: 'bottom', labels: { boxWidth: 10 } } 
                    }
                }
            });
        } else {
             const statusCtx = document.getElementById('statusChart');
             statusCtx.parentNode.innerHTML = '<p class="text-center text-muted m-t-50">No appointment data.</p>';
        }
    } catch (e) { console.log("Chart Error:", e); }

    // --- Doctor Assignment Logic ---
    $(document).on('click', '.assign-doctor', function(e) {
        e.preventDefault();
        var appointmentId = $(this).data('appointment-id');
        
        $('#appointmentId').val(appointmentId);
        // Ensure the modal exists before showing
        if($('#assignDoctorModal').length) {
            $('#assignDoctorModal').modal('show');
        }
    });
    
    $('#confirmAssign').on('click', function() {
        var appointmentId = $('#appointmentId').val();
        var doctorId = $('#doctorId').val();
        
        // Perform AJAX to backend
        $.ajax({
            url: '/admin/appointments/' + appointmentId + '/assign-doctor',
            type: 'PUT',
            data: {
                doctor_id: doctorId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                $('#assignDoctorModal').modal('hide');
                alert('Doctor assigned successfully!');
                location.reload();
            },
            error: function() {
                alert('Failed to assign doctor.');
            }
        });
    });
});
</script>
@stack('page-scripts')
</body>
</html>