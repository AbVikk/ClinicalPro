<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<title>:: Oreo Hospital :: Doctor Dashboard</title>
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
<body class="theme-cyan">
<!-- Page Loader -->

@include('admin.sidemenu')
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12">
                <h2>Doctor Dashboard
                <small>Welcome, {{ Auth::user()->name }}</small>
                </h2>
            </div>            
            <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                <button class="btn btn-white btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item active">Doctor Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Doctors Card -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $doctorsCount ?? 0 }}" data-speed="2500" data-fresh-interval="700">{{ $doctorsCount ?? 0 }} <i class="zmdi zmdi-account-add float-right"></i></h3>
                        <p class="text-muted">Total Doctors</p>
                        <div class="progress">
                            <div class="progress-bar l-blue" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                        </div>
                        <small><a href="{{ route('admin.doctor.index') }}">View All</a></small>
                    </div>
                </div>
            </div>
            
            <!-- Appointments Card -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $appointmentsCount ?? 0 }}" data-speed="2500" data-fresh-interval="700">{{ $appointmentsCount ?? 0 }} <i class="zmdi zmdi-calendar-check float-right"></i></h3>
                        <p class="text-muted">Today's Appointments</p>
                        <div class="progress">
                            <div class="progress-bar l-blue" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                        </div>
                        <small><a href="{{ url('/admin/appointments') }}">View All</a></small>
                    </div>
                </div>
            </div>
            
            <!-- Active Patients Card -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $patientsCount ?? 0 }}" data-speed="2500" data-fresh-interval="1000">{{ $patientsCount ?? 0 }} <i class="zmdi zmdi-accounts float-right"></i></h3>
                        <p class="text-muted">Total Patients</p>
                        <div class="progress">
                            <div class="progress-bar l-green" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>
                        </div>
                        <small><a href="{{ url('/admin/patients') }}">View All</a></small>
                    </div>
                </div>
            </div>
            
            <!-- Disbursements Card -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $disbursementsCount ?? 0 }}" data-speed="2500" data-fresh-interval="1000">{{ $disbursementsCount ?? 0 }} <i class="zmdi zmdi-money float-right"></i></h3>
                        <p class="text-muted">This Month Payments</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;"></div>
                        </div>
                        <small><a href="{{ url('/admin/payments') }}">View All</a></small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            <!-- Today's Appointments -->
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Today's</strong> Appointments</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp float-right">
                                    <li><a href="{{ url('/admin/appointments') }}">View All</a></li>
                                    <li><a href="{{ url('/admin/book-appointment') }}">Book New</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>                    
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todaysAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->appointment_time->format('h:i A') }}</td>
                                        <td>
                                            @if($appointment->patient)
                                                @if($appointment->patient->photo)
                                                    <img src="{{ asset('storage/' . $appointment->patient->photo) }}" class="rounded-circle" alt="Avatar" width="30">
                                                @else
                                                    <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                @endif
                                                <span>{{ $appointment->patient->name ?? 'Unknown Patient' }}</span>
                                            @else
                                                <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                <span>Unknown Patient</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($appointment->doctor)
                                                @if($appointment->doctor->photo)
                                                    <img src="{{ asset('storage/' . $appointment->doctor->photo) }}" class="rounded-circle" alt="Avatar" width="30">
                                                @else
                                                    <img src="{{ asset('assets/images/xs/avatar2.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                @endif
                                                <span>{{ $appointment->doctor->name ?? 'Unknown Doctor' }}</span>
                                            @else
                                                <img src="{{ asset('assets/images/xs/avatar2.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                <span>Unknown Doctor</span>
                                            @endif
                                        </td>
                                        <td>{{ $appointment->type ?? 'General Consultation' }}</td>
                                        <td>
                                            @if($appointment->status == 'confirmed')
                                                <span class="badge badge-success">Confirmed</span>
                                            @elseif($appointment->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($appointment->status == 'cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                            @elseif($appointment->status == 'completed')
                                                <span class="badge badge-info">Completed</span>
                                            @else
                                                <span class="badge badge-default">{{ ucfirst($appointment->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('appointment.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No appointments scheduled for today</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>                    
                </div>
            </div>
            
            <!-- Today's Schedule -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Quick</strong> Actions</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp float-right">
                                    <li><a href="{{ route('admin.doctor.index') }}">Manage Doctors</a></li>
                                    <li><a href="{{ route('admin.doctor.add') }}">Add Doctor</a></li>
                                    <li><a href="{{ url('/admin/patients') }}">Manage Patients</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="event-name b-lightred row">
                            <div class="col-3 text-center">
                                <h5>Doc</h5>
                            </div>
                            <div class="col-9">
                                <p><a href="{{ route('admin.doctor.index') }}">Manage Doctors</a></p>
                            </div>
                        </div>
                        <div class="event-name b-greensea row">
                            <div class="col-3 text-center">
                                <h5>Pat</h5>
                            </div>
                            <div class="col-9">
                                <p><a href="{{ url('/admin/patients') }}">Manage Patients</a></p>
                            </div>
                        </div>
                        <div class="event-name b-primary row">
                            <div class="col-3 text-center">
                                <h5>App</h5>
                            </div>
                            <div class="col-9">
                                <p><a href="{{ url('/admin/appointments') }}">Manage Appointments</a></p>
                            </div>
                        </div>
                        <div class="event-name b-slategray row">
                            <div class="col-3 text-center">
                                <h5>Pay</h5>
                            </div>
                            <div class="col-9">
                                <p><a href="{{ url('/admin/payments') }}">Manage Payments</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 
</body>
</html>