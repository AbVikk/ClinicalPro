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

<!-- Include Doctor Sidemenu -->
@include('doctor.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
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
                <div class="card widget-stat">
                    <div class="body">
                        <div class="row">
                             <div class="col-3">
                                <i class="zmdi zmdi-calendar-check zmdi-hc-3x col-blue"></i>
                            </div>
                            <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $todaysAppointmentsCount ?? 0 }}</h3>
                                <small class="text-muted">Today's Appointments</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                     <div class="body">
                        <div class="row">
                             <div class="col-3">
                                <i class="zmdi zmdi-calendar-note zmdi-hc-3x col-green"></i>
                            </div>
                            <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $totalAppointmentsCount ?? 0 }}</h3>
                                <small class="text-muted">Total Appointments</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                     <div class="body">
                        <div class="row">
                            <div class="col-3">
                                <i class="zmdi zmdi-headset-mic zmdi-hc-3x col-orange"></i>
                            </div>
                             <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $onlineConsultationsCount ?? 0 }}</h3>
                                <small class="text-muted">Online Consultations</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                         <div class="row">
                            <div class="col-3">
                                <i class="zmdi zmdi-close-circle-o zmdi-hc-3x col-red"></i>
                            </div>
                            <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $cancelledAppointmentsCount ?? 0 }}</h3>
                                <small class="text-muted">Cancelled Appointments</small>
                            </div>
                        </div>
                    </div>
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
                                            <div class="header bg-">
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
                                                                        @if($appointment->patient?->photo)
                                                                            <img src="{{ asset('storage/' . $appointment->patient?->photo) }}" class="rounded-circle" alt="profile-image" width="40">
                                                                        @else
                                                                            <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="profile-image" width="40">
                                                                        @endif
                                                                        <div class="ml-3">
                                                                            <h6 class="mb-0"><a href="{{ route('doctor.patient.show', $appointment->patient_id) }}">{{ $appointment->patient?->name }}</a></h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                                                <td>{{ $appointment->consultation?->duration_minutes ?? 30 }} min</td>
                                                                <td>{{ $appointment->consultation?->service_type ?? $appointment->reason ?? 'Consultation' }}</td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Actions
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="{{ route('doctor.patient.show', $appointment->patient_id) }}">
                                                                                <i class="zmdi zmdi-account"></i> View Patient
                                                                            </a>
                                                                             <a class="dropdown-item" href="{{ route('doctor.appointments.details', $appointment->id) }}">
                                                                                <i class="zmdi zmdi-eye"></i> View Details
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
                                            <div class="header bg-">
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
                                                              
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($upcomingAppointments ?? [] as $appointment)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ route('doctor.patients.appointment-history', $appointment->patient_id) }}"><h6 class="mb-0">{{ $appointment->patient?->name }}</h6></a>
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('l, F j \a\t g:i A') }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{ ($appointment->status ?? '') == 'confirmed' ? 'success' : (($appointment->status ?? '') == 'pending' ? 'warning' : 'danger') }}">
                                                                        {{ ucfirst($appointment->status ?? 'confirmed') }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $appointment->consultation?->service_type ?? $appointment->reason ?? 'Consultation' }}</td>
                                                                 {{-- Removed Actions column --}}
                                                                
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
                                            <div class="header bg-">
                                                <h2><strong>Pending</strong> Tasks</h2>
                                                <small class="text-white">Tasks requiring your attention</small>
                                            </div>
                                            <div class="body">
                                                <!-- Pending tasks will be dynamically loaded here -->
                                                <div class="tasks-list">
                                                    @forelse($pendingTasks ?? [] as $task)
                                                    <div class="task-item pending-task" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                 {{-- Assuming $task is an appointment, link to patient --}}
                                                                <h6 class="mb-1">Review appointment for <a href="{{ route('doctor.patient.show', $task->patient_id) }}">{{ $task->patient?->name }}</a></h6>
                                                                <p class="mb-0 text-muted small">Appt. Time: {{ $task->appointment_time?->format('l, g:i A') }}
                                                                     {{-- Simplify status/priority --}}
                                                                    <span class="badge badge-warning ml-2">{{ ucfirst($task->status ?? '') }}</span>
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('doctor.appointments.details', $task->id) }}" class="btn btn-sm btn-outline-info mr-2" title="View Details">
                                                                    <i class="zmdi zmdi-eye"></i>
                                                                </a>
                                                                {{-- Add 'Mark Complete' functionality later --}}
                                                                <button class="btn btn-sm btn-success" title="Mark as Complete" disabled>
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
                                            <div class="header bg-">
                                                <h2><strong>Recent</strong> Prescriptions</h2>
                                                <small class="text-white">Prescriptions you've written recently</small>
                                            </div>
                                            <div class="body">
                                                <!-- Recent prescriptions will be dynamically loaded here -->
                                                <div class="prescriptions-list">
                                                    @forelse($recentPrescriptions ?? [] as $prescription)
                                                    <div class="prescription-item recent-prescription" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                                                        <h6 class="mb-1"><a href="{{ route('doctor.patient.show', $prescription->patient_id) }}">{{ $prescription->patient?->name ?? 'Unknown Patient'}}</a></h6>
                                                        <p class="mb-1 text-muted small">{{ $prescription->created_at?->format('l, g:i A') }}</p>

                                                        @foreach($prescription->items->take(1) as $item)
                                                        <p class="mb-1 small">
                                                            <strong>{{ $item->drug?->name ?? 'Medication' }}</strong>...
                                                        </p>
                                                        @endforeach

                                                        <div>
                                                            <a href="{{ route('doctor.patient.show', $prescription->patient_id) }}" class="btn btn-sm btn-primary mr-2">
                                                                <i class="zmdi zmdi-account"></i> View Patient
                                                            </a>
                                                             {{-- Add View Prescription button? --}}
                                                            {{-- <a href="#" class="btn btn-sm btn-outline-info"><i class="zmdi zmdi-eye"></i> View Rx</a> --}}
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
                                                    <div class="col-md-4 mb-4"> {{-- Adjusted margin --}}
                                                         <div class="card text-center p-3 stat-card stat-card-1">
                                                             <h6 class="stat-title">Avg. Daily</h6>
                                                             <h2 class="stat-value">{{ $avgDailyVisits ?? '0.0' }}</h2>
                                                             <p class="stat-change text-{{ ($monthlyChange ?? 0) >= 0 ? 'success' : 'danger' }} mb-0">
                                                                <i class="zmdi zmdi-trending-{{ ($monthlyChange ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                                                {{ ($monthlyChange ?? 0) >= 0 ? '+' : '' }}{{ $monthlyChange ?? 0 }} vs last month
                                                             </p>
                                                         </div>
                                                     </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card text-center p-3 stat-card stat-card-2">
                                                             <h4 class="stat-title">Total Visits (Year)</h4>
                                                             <h2 class="stat-value">{{ $totalYearlyVisits ?? '0' }}</h2>
                                                             <p class="stat-description mb-0">In {{ date('Y') }}</p>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card text-center p-3 stat-card stat-card-3">
                                                             <h4 class="stat-title">Yearly Trend</h4>
                                                             <h2 class="stat-value text-{{ ($yearlyTrend ?? 0) >= 0 ? 'success' : 'danger' }}">{{ ($yearlyTrend ?? 0) >= 0 ? '+' : '' }}{{ $yearlyTrend ?? '0.0' }}%</h2>
                                                             <p class="stat-description mb-0">Year over year growth</p>
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
                                                        <div class="card text-center p-3 stat-card stat-card-4">
                                                             <h4 class="stat-title">Current Rating</h4>
                                                             <h2 class="stat-value">{{ $currentRating ?? '0.0' }}/5</h2>
                                                             <p class="stat-change text-{{ ($ratingChange ?? 0) >= 0 ? 'success' : 'danger' }} mb-0">
                                                                <i class="zmdi zmdi-trending-{{ ($ratingChange ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                                                {{ ($ratingChange ?? 0) >= 0 ? '+' : '' }}{{ $ratingChange ?? '0.0' }} vs last month
                                                             </p>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card text-center p-3 stat-card stat-card-5">
                                                             <h4 class="stat-title">Total Reviews</h4>
                                                             <h2 class="stat-value">{{ number_format($totalReviews ?? 0) }}</h2>
                                                             <p class="stat-change text-success mb-0">
                                                                 <i class="zmdi zmdi-trending-up"></i>
                                                                 +{{ $reviewsChange ?? 0 }} from last month
                                                             </p>
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


        <div class="row clearfix">
            {{-- 1. Total Patients --}}
            <div class="col-lg-2 col-md-4 col-sm-6">
                 <div class="card info-box-2 hover-zoom-effect">
                    <div class="icon"> <i class="zmdi zmdi-accounts-alt col-blue"></i> </div>
                    <div class="content">
                        <div class="text">Total Patients</div>
                        <div class="number">{{ $totalPatientsCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
             {{-- 2. Online Consultations --}}
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card info-box-2 hover-zoom-effect">
                    <div class="icon"> <i class="zmdi zmdi-headset col-cyan"></i> </div>
                    <div class="content">
                        <div class="text">Online Consultations</div>
                        <div class="number">{{ $videoConsultationsCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
             {{-- 3. Rescheduled --}}
            <div class="col-lg-2 col-md-4 col-sm-6">
                 <div class="card info-box-2 hover-zoom-effect">
                    <div class="icon"> <i class="zmdi zmdi-time-restore col-orange"></i> </div>
                    <div class="content">
                        <div class="text">Rescheduled</div>
                        <div class="number">{{ $rescheduledCount ?? 0 }}</div> {{-- Placeholder --}}
                    </div>
                </div>
            </div>
             {{-- 4. Pre Visit Bookings --}}
             <div class="col-lg-2 col-md-4 col-sm-6">
                 <div class="card info-box-2 hover-zoom-effect">
                    <div class="icon"> <i class="zmdi zmdi-calendar-check col-green"></i> </div>
                    <div class="content">
                        <div class="text">Pre Visit Bookings</div>
                        <div class="number">{{ $preVisitBookingsCount ?? 0 }}</div> {{-- Placeholder --}}
                    </div>
                </div>
            </div>
             {{-- 5. Walk-in Bookings --}}
             <div class="col-lg-2 col-md-4 col-sm-6">
                 <div class="card info-box-2 hover-zoom-effect">
                    <div class="icon"> <i class="zmdi zmdi-walk col-purple"></i> </div>
                    <div class="content">
                        <div class="text">Walk-in Bookings</div>
                        <div class="number">{{ $walkinBookingsCount ?? 0 }}</div> {{-- Placeholder --}}
                    </div>
                </div>
            </div>
             {{-- 6. Follow Ups --}}
             <div class="col-lg-2 col-md-4 col-sm-6">
                 <div class="card info-box-2 hover-zoom-effect">
                    <div class="icon"> <i class="zmdi zmdi-refresh col-red"></i> </div>
                    <div class="content">
                        <div class="text">Follow Ups</div>
                        <div class="number">{{ $followUpsCount ?? 0 }}</div> {{-- Example --}}
                    </div>
                </div>
            </div>
        </div>


         <div class="row clearfix">
             {{-- Availability Card --}}
            <div class="col-lg-4 col-md-12">
                 <div class="card">
                    <div class="header">
                        <h2><strong>My Availability</strong></h2>
                        {{-- Add dropdown or link to edit schedule if needed --}}
                         <ul class="header-dropdown">
                             <li><a href="{{ route('doctor.schedule') }}" class="btn btn-sm btn-outline-primary">Edit Schedule</a></li>
                         </ul>
                    </div>
                    <div class="body">
                        @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        @endphp
                        @foreach($days as $day)
                             <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                                <p class="text-dark font-weight-bold mb-0 text-capitalize">{{ $day }}</p>
                                @if(isset($doctorSchedule[$day]) && $doctorSchedule[$day]->isNotEmpty())
                                    <div>
                                    @foreach($doctorSchedule[$day] as $schedule)
                                         <small class="d-block text-muted">
                                            <i class="zmdi zmdi-time"></i>
                                             {{ \Carbon\Carbon::parse($schedule->start_time ?? now())->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time ?? now())->format('g:i A') }}
                                             ({{ $schedule->location == 'virtual' ? 'Virtual' : ($schedule->clinic?->name ?? 'Clinic '.$schedule->location) }})
                                         </small>
                                    @endforeach
                                    </div>
                                @else
                                     <p class="mb-0 text-muted"><i class="zmdi zmdi-block"></i> Not Available</p>
                                @endif
                             </div>
                        @endforeach
                    </div>
                </div>
            </div>

             {{-- Top Patients Card --}}
             <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="header">
                        <h2><strong>Top Patients</strong> <small>(By Completed Appointments)</small></h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled activity">
                             @forelse($topPatients ?? [] as $patient)
                             <li>
                                <div class="media">
                                    <div class="media-left">
                                        <div class="avatar">
                                            <img src="{{ $patient->photo ? asset('storage/' . $patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" alt="Patient" class="rounded-circle" width="40">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="m-t-0 m-b-0"><a href="{{ route('doctor.patient.show', $patient->id) }}">{{ $patient->name }}</a></h6>
                                        <p class="text-muted">{{ $patient->appointments_as_patient_count }} visits</p>
                                    </div>
                                </div>
                            </li>
                             @empty
                                <li>No patient data available.</li>
                             @endforelse
                        </ul>
                    </div>
                </div>
            </div>

             {{-- Patient Visits Chart --}}
             <div class="col-lg-4 col-md-6">
                 <div class="card">
                     <div class="header">
                         <h2><strong>Patient Visits</strong> <small>This Year</small></h2>
                     </div>
                     <div class="body">
                         {{-- Canvas for the Chart --}}
                         <canvas id="patientVisitsChartMain" height="250"></canvas>
                     </div>
                 </div>
             </div>

         </div>

         <div class="row clearfix">
             <div class="col-lg-12"> {{-- Make full width? Or keep 6/6 split? --}}
                 <div class="card">
                    <div class="header">
                        <h2><strong>Quick</strong> Actions</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 text-center"> {{-- Adjust col sizes --}}
                                <a href="{{ route('doctor.patient.index') }}" class="btn btn-primary btn-lg btn-block waves-effect m-t-20">
                                    <i class="zmdi zmdi-accounts"></i> <span>My Patients</span>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 text-center">
                                <a href="{{ route('doctor.appointments') }}" class="btn btn-success btn-lg btn-block waves-effect m-t-20">
                                    <i class="zmdi zmdi-calendar"></i> <span>Appointments</span>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 text-center"> {{-- m-t-20 not needed if on same row --}}
                                <a href="{{ url('/doctor/prescriptions') }}" class="btn btn-warning btn-lg btn-block waves-effect m-t-20">
                                    <i class="zmdi zmdi-file-text"></i> <span>Prescriptions</span>
                                </a>
                            </div>
                             <div class="col-lg-3 col-md-6 text-center">
                                <a href="{{ url('/doctor/reports') }}" class="btn btn-info btn-lg btn-block waves-effect m-t-20">
                                    <i class="zmdi zmdi-chart"></i> <span>Reports</span>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // --- Initialize Charts ---
    if (typeof Chart !== 'undefined') {

        // Chart 1: Inside the "Stats" Tab
        const visitsCtxTab = document.getElementById('patientVisitsChart');
        if (visitsCtxTab) {
            new Chart(visitsCtxTab.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Patient Visits',
                        data: @json($patientVisits ?? array_fill(0, 12, 0)),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true,
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });
        } else {
            console.warn("Canvas element #patientVisitsChart (Tab) not found.");
        }

        // --- THIS IS THE FIX FOR THE NEW CHART ---
        // Chart 2: New Chart Below Tabs
        const visitsCtxMain = document.getElementById('patientVisitsChartMain'); // <-- LOOKS FOR NEW ID
        if (visitsCtxMain) {
            new Chart(visitsCtxMain.getContext('2d'), {
                type: 'line', // Or 'bar'
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Patient Visits',
                        data: @json($patientVisits ?? array_fill(0, 12, 0)), // Use the same data
                        borderColor: 'rgba(0, 123, 255, 1)', // Different color
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        tension: 0.1,
                        fill: true,
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });
        } else {
            console.warn("Canvas element #patientVisitsChartMain (Main) not found.");
        }
        // --- END OF FIX ---


        // Chart 3: Patient Satisfaction Chart (Simulated)
        const satisfactionCtx = document.getElementById('patientSatisfactionChart');
        if (satisfactionCtx) {
            new Chart(satisfactionCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Avg. Satisfaction',
                        data: @json($satisfactionData ?? array_fill(0, 12, 4)),
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        tension: 0.1,
                        fill: false,
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: false, min: 1, max: 5 } } }
            });
        } else {
            console.warn("Canvas element #patientSatisfactionChart (Tab) not found.");
        }

    } else {
        console.error("Chart.js library not loaded. Charts cannot be initialized.");
    }
});
</script>

</body>
</html>
