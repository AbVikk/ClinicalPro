<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor dashboard for patient management and appointments">

<title>ClinicalPro || Doctor Dashboard</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

<!-- Include Doctor Sidemenu -->
@include('doctor.sidemenu')

<section class="content">
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
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Widgets -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat text-center">
                    <div class="body">
                        <div class="progress m-b-10">
                            <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                        </div>
                        <span class="text-muted">Today's Appointments</span>
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="12" data-speed="1000" data-fresh-interval="700">12</h4>
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
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="142" data-speed="1000" data-fresh-interval="700">142</h4>
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
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="8" data-speed="1000" data-fresh-interval="700">8</h4>
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
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="9" data-speed="1000" data-fresh-interval="700">9</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#schedule">Schedule</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tasks">Tasks</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#stats">Stats</a></li>
                        </ul>
                        
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane active" id="schedule">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="card schedule-card">
                                            <div class="header bg-primary">
                                                <h2><strong>Today's</strong> Schedule</h2>
                                                <small class="text-white">You have {{ $todaysAppointmentsCount ?? 12 }} appointments scheduled for today</small>
                                            </div>
                                            <div class="body">
                                                <!-- Today's appointments will be dynamically loaded here -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Patient</th>
                                                                <th>Time</th>
                                                                <th>Duration</th>
                                                                <th>Purpose</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($todaysAppointments ?? [] as $appointment)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        @if($appointment->patient->photo)
                                                                            <img src="{{ asset('storage/' . $appointment->patient->photo) }}" class="rounded-circle" alt="profile-image" width="40">
                                                                        @else
                                                                            <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="profile-image" width="40">
                                                                        @endif
                                                                        <div class="ml-3">
                                                                            <h6 class="mb-0"><a href="{{ route('doctor.patient.show', $appointment->patient->id) }}">{{ $appointment->patient->name }}</a></h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                                                <td>{{ $appointment->duration ?? 30 }} min</td>
                                                                <td>{{ $appointment->purpose ?? 'Consultation' }}</td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Actions
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="{{ route('doctor.patient.show', $appointment->patient->id) }}">
                                                                                <i class="zmdi zmdi-account"></i> View Profile
                                                                            </a>
                                                                            <a class="dropdown-item" href="#">
                                                                                <i class="zmdi zmdi-edit"></i> Edit
                                                                            </a>
                                                                            <a class="dropdown-item" href="#">
                                                                                <i class="zmdi zmdi-delete"></i> Cancel
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center">No appointments scheduled for today</td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="text-center mt-3">
                                                    <a href="{{ url('/doctor/appointments') }}" class="btn btn-outline-primary">View All Appointments</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="card upcoming-card">
                                            <div class="header bg-success">
                                                <h2><strong>Upcoming</strong> Appointments</h2>
                                                <small class="text-white">Your upcoming appointments for the week</small>
                                            </div>
                                            <div class="body">
                                                <!-- Upcoming appointments will be dynamically loaded here -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Patient</th>
                                                                <th>Date & Time</th>
                                                                <th>Status</th>
                                                                <th>Purpose</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($upcomingAppointments ?? [] as $appointment)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ route('doctor.patient.show', $appointment->patient->id) }}"><h6 class="mb-0">{{ $appointment->patient->name }}</h6></a>
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('l, F j \a\t g:i A') }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                                                        {{ ucfirst($appointment->status ?? 'confirmed') }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $appointment->purpose ?? 'Consultation' }}</td>
                                                                <td>
                                                                    @php
                                                                        // Safely get the patient ID
                                                                        $patientId = null;
                                                                        if (isset($appointment->patient) && $appointment->patient && isset($appointment->patient->patient)) {
                                                                            $patientId = $appointment->patient->patient->id ?? null;
                                                                        }
                                                                    @endphp
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Actions
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            @if($patientId)
                                                                                <a class="dropdown-item" href="{{ route('doctor.patient.show', $appointment->patient->id) }}">
                                                                                    <i class="zmdi zmdi-account"></i> View Profile
                                                                                </a>
                                                                            @else
                                                                                <a class="dropdown-item disabled" href="#">
                                                                                    <i class="zmdi zmdi-account"></i> View Profile
                                                                                </a>
                                                                            @endif
                                                                            <a class="dropdown-item" href="#">
                                                                                <i class="zmdi zmdi-edit"></i> Edit
                                                                            </a>
                                                                            <a class="dropdown-item" href="#">
                                                                                <i class="zmdi zmdi-delete"></i> Cancel
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center">No upcoming appointments</td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tasks">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="card tasks-card">
                                            <div class="header bg-warning">
                                                <h2><strong>Pending</strong> Tasks</h2>
                                                <small class="text-white">Tasks requiring your attention</small>
                                            </div>
                                            <div class="body">
                                                <!-- Pending tasks will be dynamically loaded here -->
                                                <div class="tasks-list">
                                                    @forelse($pendingTasks ?? [] as $task)
                                                    <div class="task-item pending-task">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="mb-1">Review lab results for <a href="{{ route('doctor.patient.show', $task->patient->id) }}">{{ $task->patient->name }}</a></h6>
                                                                <p class="mb-0 text-muted small">{{ \Carbon\Carbon::parse($task->appointment_time)->format('l, g:i A') }}
                                                                    @if($task->status == 'urgent')
                                                                        <span class="badge badge-danger ml-2">High</span>
                                                                    @elseif($task->status == 'pending')
                                                                        <span class="badge badge-warning ml-2">Medium</span>
                                                                    @else
                                                                        <span class="badge badge-info ml-2">Normal</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div>
                                                                @php
                                                                    // Safely get the patient ID
                                                                    $patientId = null;
                                                                    $patientModel = null;
                                                                    if (isset($task->patient) && $task->patient && isset($task->patient->patient)) {
                                                                        $patientId = $task->patient->patient->id ?? null;
                                                                        $patientModel = $task->patient->patient; // Get the actual Patient model
                                                                    }
                                                                @endphp
                                                                    
                                                               
                                                                <button class="btn btn-sm btn-outline-secondary mr-2" title="Read Note">
                                                                    <i class="zmdi zmdi-file-text"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-success" title="Mark as Complete">
                                                                    <i class="zmdi zmdi-check"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(!$loop->last)<hr>@endif
                                                    @empty
                                                    <p class="text-center">No pending tasks</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="card prescriptions-card">
                                            <div class="header bg-info">
                                                <h2><strong>Recent</strong> Prescriptions</h2>
                                                <small class="text-white">Prescriptions you've written recently</small>
                                            </div>
                                            <div class="body">
                                                <!-- Recent prescriptions will be dynamically loaded here -->
                                                <div class="prescriptions-list">
                                                    @forelse($recentPrescriptions ?? [] as $prescription)
                                                    <div class="prescription-item recent-prescription">
                                                        <h6 class="mb-1"><a href="{{ route('doctor.patient.show', $prescription->patient->id) }}">{{ $prescription->patient->name }}</a></h6>
                                                        <p class="mb-1 text-muted small">{{ \Carbon\Carbon::parse($prescription->created_at)->format('l, g:i A') }}</p>
                                                        
                                                        @foreach($prescription->items->take(1) as $item)
                                                        <p class="mb-2">
                                                            <strong>{{ $item->drug->name ?? 'Medication' }}</strong>, 
                                                            {{ $item->quantity }} {{ $item->unit ?? 'tablet' }} as needed, 
                                                            {{ $item->dosage_instructions }}
                                                        </p>
                                                        @endforeach
                                                        
                                                        <div>
                                                            @php
                                                                // Safely get the patient ID
                                                                $patientId = null;
                                                                $patientModel = null;
                                                                if (isset($prescription->patient) && $prescription->patient && isset($prescription->patient->patient)) {
                                                                    $patientId = $prescription->patient->patient->id ?? null;
                                                                    $patientModel = $prescription->patient->patient; // Get the actual Patient model
                                                                }
                                                            @endphp
                                                            
                                                            @if($patientId && $patientModel)
                                                                <a href="{{ route('doctor.patient.show', $patientModel->id) }}" class="btn btn-sm btn-primary mr-2">
                                                                    <i class="zmdi zmdi-account"></i> View Profile
                                                                </a>
                                                            @else
                                                                <button class="btn btn-sm btn-secondary mr-2" disabled>
                                                                    <i class="zmdi zmdi-account"></i> View Profile
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if(!$loop->last)<hr>@endif
                                                    @empty
                                                    <p class="text-center">No recent prescriptions</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="stats">
                                <div class="card stats-card">
                                    <div class="header bg-dark">
                                        <h2><strong>Performance</strong> Metrics</h2>
                                        <small class="text-white">Your clinical performance and patient outcomes</small>
                                    </div>
                                    <div class="body">
                                        <!-- Stats tabs -->
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#patient-visits">Patient Visits</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#patient-satisfaction">Patient Satisfaction</a>
                                            </li>
                                        </ul>
                                        
                                        <div class="tab-content mt-3">
                                            <div class="tab-pane active" id="patient-visits">
                                                <!-- Patient visits chart -->
                                                <div class="chart-container" style="position: relative; height:400px">
                                                    <canvas id="patientVisitsChart"></canvas>
                                                </div>
                                                
                                                <!-- Stats summary -->
                                                <div class="row mt-4">
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card stat-card stat-card-1">
                                                            <div class="body text-center p-4">
                                                                <h4 class="stat-title">Avg. Daily</h4>
                                                                <h2 class="stat-value">{{ $avgDailyVisits ?? '0.0' }}</h2>
                                                                <p class="stat-change text-{{ ($monthlyChange ?? 0) >= 0 ? 'success' : 'danger' }} mb-0">
                                                                    <i class="zmdi zmdi-trending-{{ ($monthlyChange ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                                                    {{ ($monthlyChange ?? 0) >= 0 ? '+' : '' }}{{ $monthlyChange ?? 0 }} from last month
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card stat-card stat-card-2">
                                                            <div class="body text-center p-4">
                                                                <h4 class="stat-title">Total Monthly</h4>
                                                                <h2 class="stat-value">{{ $totalYearlyVisits ?? '0' }}</h2>
                                                                <p class="stat-change text-{{ ($monthlyChange ?? 0) >= 0 ? 'success' : 'danger' }} mb-0">
                                                                    <i class="zmdi zmdi-trending-{{ ($monthlyChange ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                                                    {{ ($monthlyChange ?? 0) >= 0 ? '+' : '' }}{{ $monthlyChange ?? 0 }} from last month
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card stat-card stat-card-3">
                                                            <div class="body text-center p-4">
                                                                <h4 class="stat-title">Yearly Trend</h4>
                                                                <h2 class="stat-value">{{ ($yearlyTrend ?? 0) >= 0 ? '+' : '' }}{{ $yearlyTrend ?? '0.0' }}%</h2>
                                                                <p class="stat-description mb-0">Year over year growth</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="tab-pane" id="patient-satisfaction">
                                                <!-- Patient satisfaction chart -->
                                                <div class="chart-container" style="position: relative; height:400px">
                                                    <canvas id="patientSatisfactionChart"></canvas>
                                                </div>
                                                
                                                <!-- Stats summary -->
                                                <div class="row mt-4">
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card stat-card stat-card-4">
                                                            <div class="body text-center p-4">
                                                                <h4 class="stat-title">Current Rating</h4>
                                                                <h2 class="stat-value">{{ $currentRating ?? '0.0' }}/5</h2>
                                                                <p class="stat-change text-{{ ($ratingChange ?? 0) >= 0 ? 'success' : 'danger' }} mb-0">
                                                                    <i class="zmdi zmdi-trending-{{ ($ratingChange ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                                                    {{ ($ratingChange ?? 0) >= 0 ? '+' : '' }}{{ $ratingChange ?? '0.0' }} from last month
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card stat-card stat-card-5">
                                                            <div class="body text-center p-4">
                                                                <h4 class="stat-title">Total Reviews</h4>
                                                                <h2 class="stat-value">{{ number_format($totalReviews ?? 0) }}</h2>
                                                                <p class="stat-change text-success mb-0">
                                                                    <i class="zmdi zmdi-trending-up"></i>
                                                                    +{{ $reviewsChange ?? 0 }} from last month
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card stat-card stat-card-6">
                                                            <div class="body text-center p-4">
                                                                <h4 class="stat-title">Recommendation</h4>
                                                                <h2 class="stat-value">{{ $recommendationPercentage ?? 0 }}%</h2>
                                                                <p class="stat-description mb-0">Would recommend to others</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="Patient">
                                            </div>
                                            <span><a href="{{ route('doctor.patient.show', 1) }}">John Smith</a></span>
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
                                                <img src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="Patient">
                                            </div>
                                            <span><a href="{{ route('doctor.patient.show', 2) }}">Mary Johnson</a></span>
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
                                                <img src="{{ asset('assets/images/xs/avatar3.jpg') }}" alt="Patient">
                                            </div>
                                            <span><a href="{{ route('doctor.patient.show', 3) }}">Robert Brown</a></span>
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
                                            <img src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="Patient">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="m-t-0"><a href="{{ route('doctor.patient.show', 4) }}">Jennifer Davis</a></h6>
                                        <p>Last visit: 2 days ago</p>
                                        <small class="text-muted">Cardiology</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="media-left">
                                        <div class="avatar">
                                            <img src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="Patient">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="m-t-0"><a href="{{ route('doctor.patient.show', 5) }}">Michael Wilson</a></h6>
                                        <p>Last visit: 1 week ago</p>
                                        <small class="text-muted">Orthopedics</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="media-left">
                                        <div class="avatar">
                                            <img src="{{ asset('assets/images/xs/avatar6.jpg') }}" alt="Patient">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="m-t-0"><a href="{{ route('doctor.patient.show', 6) }}">Sarah Thompson</a></h6>
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
                                <a href="{{ route('doctor.patient.index') }}" class="btn btn-primary btn-lg btn-block waves-effect m-t-20">
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
</section>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 
<script src="{{ asset('assets/js/doctor-links-fix.js') }}"></script><!-- Doctor Links Fix -->

<!-- Additional Scripts for this page -->
<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>

<script>
$(document).ready(function() {
    // TEMPORARILY REMOVE the problematic event handler to test if it's causing the issue
    // Ensure patient name links are clickable by preventing event bubbling issues
    /*
    $('.appointment-item, .upcoming-item, .patient-item, .note-item, .task-item').on('click', function(e) {
        // If the click target is not a link, prevent default behavior
        if (!$(e.target).is('a') && !$(e.target).closest('a').length) {
            e.stopPropagation();
        }
        // Log the event for debugging
        console.log('Dashboard appointment item clicked:', e.target.tagName, e.target.className);
    });
    */
    
    // Add specific handler for view profile links
    $('.view-profile-link').off('click').on('click', function(e) {
        console.log('View profile link clicked:', $(this).attr('href'));
        // DO NOT prevent default - allow the link to work
        // e.preventDefault(); // This line is intentionally commented out
    });
});
</script>

</body>
</html>
