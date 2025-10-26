<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Patient Profile</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Custom Styles for Cards -->
<style>
    .patient-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .patient-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .patient-card .body {
        padding: 20px;
    }
    
    .patient-card h5 {
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }
    
    .patient-card .text-small {
        color: #777;
        font-size: 13px;
    }
    
    .patient-card h2 {
        font-weight: 700;
        color: #007bff;
        margin: 0;
    }
    
    .patient-card .info {
        color: #999;
        font-size: 12px;
    }
    
    .patient-card p {
        color: #555;
        margin: 10px 0 0 0;
        font-size: 14px;
    }
    
    .cards-container {
        display: flex;
        flex-wrap: nowrap;
        margin: 0 -10px;
    }
    
    .card-item {
        flex: 1;
        min-width: 0;
        padding: 0 10px;
        margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
        .cards-container {
            flex-direction: column;
        }
        
        .card-item {
            min-width: 100%;
        }
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Patient Profile
                <small class="text-muted">Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.patients.index') }}">Patients</a></li>
                    <li class="breadcrumb-item active">Patient Profile</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card profile-card">
                    <div class="card-body">
                        <div class="profile-image text-center">
                            @if($patient->photo)
                                <img src="{{ asset('storage/' . $patient->photo) }}" class="rounded-circle" alt="{{ $patient->name }}" width="150" height="150">
                            @else
                                <img src="http://via.placeholder.com/150x150" class="rounded-circle" alt="{{ $patient->name }}">
                            @endif
                        </div>
                        <h4 class="m-t-10 text-center">{{ $patient->name }}</h4>
                        <div class="text-center">
                            <span class="badge badge-{{ $patient->status == 'verified' ? 'success' : 'warning' }}">{{ ucfirst($patient->status ?? 'pending') }}</span>
                        </div>
                        
                        <!-- Patient Information Sections -->
                        <div class="profile-info">
                            <div class="info-section">
                                <h5 class="heading">Patient Information</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Patient ID:</strong> P{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</li>
                                    <li><strong>Age/Gender:</strong> 
                                        @if($patient->date_of_birth)
                                            @php
                                                $age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                                            @endphp
                                            {{ $age }} years • {{ ucfirst($patient->gender ?? 'N/A') }}
                                        @else
                                            N/A
                                        @endif
                                    </li>
                                    <li><strong>Blood Type:</strong> {{ $patient->blood_type ?? 'N/A' }}</li>
                                </ul>
                            </div>
                            
                            <div class="info-section">
                                <h5 class="heading">Personal Information</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Date of Birth:</strong> 
                                        @if($patient->date_of_birth)
                                            {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') }}
                                        @else
                                            Not provided
                                        @endif
                                    </li>
                                    <li><strong>Phone:</strong> {{ $patient->phone ?? 'Not provided' }}</li>
                                    <li><strong>Email:</strong> {{ $patient->email }}</li>
                                    <li><strong>Address:</strong> {{ $patient->address ?? 'Not provided' }}</li>
                                </ul>
                            </div>
                            
                            <div class="info-section">
                                <h5 class="heading">Medical Information</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Allergies:</strong> {{ $patient->allergies ?? 'None reported' }}</li>
                                    <li><strong>Conditions:</strong> {{ $patient->condition ?? 'None reported' }}</li>
                                    <li><strong>Primary Doctor:</strong> 
                                        @if($patient->doctor)
                                            {{ $patient->doctor->name }}
                                        @else
                                            Not assigned
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="info-section">
                                <h5 class="heading">Insurance Information</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Provider:</strong> {{ $patient->insurance_provider ?? 'Not provided' }}</li>
                                    <li><strong>Policy Number:</strong> {{ $patient->insurance_policy ?? 'Not provided' }}</li>
                                </ul>
                            </div>
                            
                            <div class="info-section">
                                <h5 class="heading">Emergency Contact</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Name:</strong> {{ $patient->emergency_contact_name ?? 'Not provided' }}</li>
                                    <li><strong>Relationship:</strong> {{ $patient->emergency_contact_relationship ?? 'Not provided' }}</li>
                                    <li><strong>Phone:</strong> {{ $patient->emergency_contact_phone ?? 'Not provided' }}</li>
                                </ul>
                            </div>
                            
                            <div class="info-section">
                                <h5 class="heading">Registration Details</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Registered on:</strong> {{ $patient->created_at ? $patient->created_at->format('Y-m-d') : 'N/A' }}</li>
                                    <li><strong>Last Updated:</strong> {{ $patient->updated_at ? $patient->updated_at->format('Y-m-d') : 'N/A' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Patient</strong> Details</h2>
                    </div>
                    <div class="body">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#overview">Overview</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#appointments">Appointments</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#prescriptions">Prescriptions</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#lab-results">Lab Results</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#billing">Billing</a></li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content m-t-10">
                            <!-- Overview Tab -->
                            <div class="tab-pane active" id="overview">
                                <div class="cards-container">
                                    <div class="card-item">
                                        <div class="patient-card">
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h5 class="m-t-0">Next Appointment</h5>
                                                        @php
                                                            $nextAppointment = null;
                                                            try {
                                                                $nextAppointment = $patient->appointmentsAsPatient->where('status', 'scheduled')->first();
                                                            } catch (Exception $e) {
                                                                $nextAppointment = null;
                                                            }
                                                        @endphp
                                                        <small class="text-small">
                                                            @if($nextAppointment)
                                                                {{ $nextAppointment->appointment_time->format('F d, Y') }}
                                                            @else
                                                                No upcoming appointments
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <h2 class="m-b-0">
                                                            @if($nextAppointment)
                                                                {{ $nextAppointment->appointment_time->format('g:i A') }}
                                                            @else
                                                                --
                                                            @endif
                                                        </h2>
                                                        <small class="info">
                                                            @if($nextAppointment)
                                                                {{ $nextAppointment->type ?? 'Consultation' }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="m-t-10">
                                                    <p class="m-b-0">
                                                        @if($nextAppointment && $nextAppointment->doctor)
                                                            Dr. {{ $nextAppointment->doctor->name }}
                                                        @else
                                                            --
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-item">
                                        <div class="patient-card">
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h5 class="m-t-0">Active Medications</h5>
                                                        @php
                                                            $activePrescriptionsCount = 0;
                                                            $lastActivePrescription = null;
                                                            try {
                                                                $activePrescriptionsCount = $patient->prescriptions->where('status', 'active')->count();
                                                                $lastActivePrescription = $patient->prescriptions->where('status', 'active')->last();
                                                            } catch (Exception $e) {
                                                                $activePrescriptionsCount = 0;
                                                                $lastActivePrescription = null;
                                                            }
                                                        @endphp
                                                        <small class="text-small">
                                                            {{ $activePrescriptionsCount }} Active Prescriptions
                                                        </small>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <h2 class="m-b-0">
                                                            {{ $activePrescriptionsCount }}
                                                        </h2>
                                                        <small class="info">Total</small>
                                                    </div>
                                                </div>
                                                <div class="m-t-10">
                                                    <p class="m-b-0">
                                                        Last updated: 
                                                        @if($lastActivePrescription)
                                                            {{ $lastActivePrescription->created_at->format('M j, Y') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-item">
                                        <div class="patient-card">
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h5 class="m-t-0">Recent Lab Results</h5>
                                                        <small class="text-small">
                                                            No recent results
                                                        </small>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <h2 class="m-b-0">--</h2>
                                                        <small class="info">N/A</small>
                                                    </div>
                                                </div>
                                                <div class="m-t-10">
                                                    <p class="m-b-0">No results</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <h6>Recent Appointments</h6>

                                <div class="card-container" style="display: block;">
                                    @if(isset($recentAppointments) && $recentAppointments->count() > 0)
                                        @foreach($recentAppointments as $appointment)
                                            <div class="card-item" style="width: 100%; margin-bottom: 20px;">
                                                <div class="patient-card">
                                                    <div class="body">
                                                        <div class="row">
                                                            <div class="col-7">
                                                                <h5 class="m-t-0">{{ $appointment->appointmentReason->name ?? $appointment->type ?? 'Appointment' }}</h5>
                                                                <small class="text-small">
                                                                    {{ $appointment->appointment_time ? $appointment->appointment_time->format('Y-m-d') : 'N/A' }}
                                                                </small>
                                                            </div>
                                                            <div class="col-5 text-right">
                                                                <h2 class="m-b-0">
                                                                    {{ $appointment->appointment_time ? $appointment->appointment_time->format('g:i A') : '--' }}
                                                                </h2>
                                                                <small class="info">{{ $appointment->status ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="m-t-10">
                                                            <p class="m-b-0">
                                                                @if($appointment->doctor)
                                                                    Dr. {{ $appointment->doctor->name }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="card-item" style="width: 100%; margin-bottom: 20px;">
                                            <div class="patient-card">
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="m-t-0">NIL</h5>
                                                            <small class="text-small">
                                                                No recent results
                                                            </small>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0">--</h2>
                                                            <small class="info">N/A</small>
                                                        </div>
                                                    </div>
                                                    <div class="m-t-10">
                                                        <p class="m-b-0">No results</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Appointments Tab -->
                            <div class="tab-pane" id="appointments">
                                <div class="text-right m-b-20">
                                    <a href="{{ route('admin.book-appointment') }}?patient_id={{ $patient->user_id }}&patient_name={{ urlencode($patient->name) }}&patient_email={{ urlencode($patient->email) }}" class="btn btn-primary">Schedule Appointment</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Type</th>
                                                <th>Doctor</th>
                                                <th>Department</th>
                                                <th>Notes</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($allAppointments) && $allAppointments->count() > 0)
                                                @foreach($allAppointments as $appointment)
                                                    <tr>
                                                        <td>
                                                            @if($appointment->appointment_time)
                                                                {{ $appointment->appointment_time->format('Y-m-d H:i') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>{{ $appointment->appointmentReason->name ?? $appointment->type ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($appointment->doctor)
                                                                Dr. {{ $appointment->doctor->name }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($appointment->doctor && $appointment->doctor->doctor && $appointment->doctor->doctor->department)
                                                                {{ $appointment->doctor->doctor->department->name ?? 'N/A' }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>{{ $appointment->notes ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($appointment->status ?? 'pending') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary">View</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No appointments found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Prescriptions Tab -->
                            <div class="tab-pane" id="prescriptions">
                                <div class="text-right m-b-20">
                                    <button class="btn btn-primary">Add Prescription</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date Range</th>
                                                <th>Medication</th>
                                                <th>Dosage & Frequency</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($patient->prescriptions && $patient->prescriptions->count() > 0)
                                                @foreach($patient->prescriptions as $prescription)
                                                    <tr>
                                                        <td>
                                                            {{ $prescription->created_at ? $prescription->created_at->format('Y-m-d') : 'N/A' }}
                                                            @if($prescription->created_at)
                                                                to {{ $prescription->created_at->addDays(30)->format('Y-m-d') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($prescription->items && $prescription->items->count() > 0)
                                                                @foreach($prescription->items as $item)
                                                                    @if($item->drug)
                                                                        {{ $item->drug->name }} ({{ $item->drug->strength_mg }}mg)<br>
                                                                    @else
                                                                        Unknown Medication<br>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                No medications
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($prescription->items && $prescription->items->count() > 0)
                                                                @foreach($prescription->items as $item)
                                                                    {{ $item->dosage_instructions }}<br>
                                                                @endforeach
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($prescription->doctor)
                                                                Dr. {{ $prescription->doctor->name }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-{{ $prescription->status == 'active' ? 'success' : ($prescription->status == 'completed' ? 'info' : 'secondary') }}">
                                                                {{ ucfirst($prescription->status ?? 'N/A') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary">View</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center">No prescriptions found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Lab Results Tab -->
                            <div class="tab-pane" id="lab-results">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Date</th>
                                                <th>Result</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center">No lab results found.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Billing Tab -->
                            <div class="tab-pane" id="billing">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center">No billing records found.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right m-t-20">
                            <a href="{{ route('admin.patients.index') }}" class="btn btn-default">Back to List</a>
                            <button class="btn btn-primary">Edit Patient</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets/infobox/infobox-1.js') }}"></script>
<script src="{{ asset('assets/js/pages/charts/jquery-knob.js') }}"></script>
<script src="{{ asset('assets/js/pages/cards/basic.js') }}"></script>
</body>
</html>