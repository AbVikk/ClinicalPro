<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Prescription Details">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>:: Clinical Pro :: Prescription Details</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
<style>
    .alert-position {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 500px;
        animation: fadeIn 0.5s, fadeOut 0.5s 4.5s;
    }
    
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    
    @keyframes fadeOut {
        from {opacity: 1; transform: translateY(0);}
        to {opacity: 0; transform: translateY(-20px);}
    }
    
    .alert-dismissible .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }
    
    .prescription-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .prescription-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
    }
    
    .prescription-body {
        padding: 20px;
    }
    
    .medication-row {
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }
    
    .medication-row:last-child {
        border-bottom: none;
    }
    
    .section-title {
        border-left: 4px solid #007bff;
        padding-left: 10px;
        margin-bottom: 20px;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<!-- Success Message -->
@if(session('success'))
<div class="alert alert-success alert-position alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Error Message -->
@if(session('error'))
<div class="alert alert-danger alert-position alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <h2>Prescription Details
                <small>View prescription information and medications.</small>
                </h2>
            </div>            
            <div class="col-lg-4 col-md-4 col-sm-12 text-right">
                <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary btn-round">Back to Prescriptions</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Main Prescription Details (md-8) -->
            <div class="col-lg-8 col-md-8">
                <!-- Prescription Information -->
                <div class="card prescription-card">
                    <div class="prescription-header">
                        <h2><strong>Prescription</strong> Information</h2>
                        <p>Details about this prescription</p>
                    </div>
                    <div class="prescription-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status:</strong> 
                                    <span class="badge badge-{{ $prescription->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($prescription->status) }}
                                    </span>
                                </p>
                                <p><strong>Prescription ID:</strong> {{ $prescription->id }}</p>
                                <p><strong>Prescribed By:</strong> 
                                    @if($prescription->doctor)
                                        Dr. {{ $prescription->doctor->name }}
                                    @else
                                        No information yet
                                    @endif
                                </p>
                                <p><strong>Issue Date:</strong> {{ $prescription->created_at->format('Y-m-d') }}</p>
                                <p><strong>Expiry Date:</strong> {{ $prescription->created_at->addMonths(3)->format('Y-m-d') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Diagnosis:</strong> 
                                    @if($prescription->notes)
                                        {{ $prescription->notes }}
                                    @else
                                        No information yet
                                    @endif
                                </p>
                                <p><strong>Refills Remaining:</strong> {{ $prescription->refills_allowed ?? 0 }}</p>
                            </div>
                        </div>
                        
                        <div class="section-title">
                            <h4><strong>Medications</strong></h4>
                        </div>
                        
                        @if($prescription->items->count() > 0)
                            @foreach($prescription->items as $item)
                                @php
                                    $dosageInstructions = json_decode($item->dosage_instructions, true);
                                @endphp
                                <div class="medication-row">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>Medication:</strong></p>
                                            <p>{{ $item->drug->name ?? 'No information yet' }}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>Dosage:</strong></p>
                                            <p>{{ $dosageInstructions['dosage'] ?? 'No information yet' }}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>Frequency:</strong></p>
                                            <p>{{ $dosageInstructions['frequency'] ?? 'No information yet' }}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>Duration:</strong></p>
                                            <p>{{ $dosageInstructions['duration'] ?? 'No information yet' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Notes:</strong></p>
                                            <p>{{ $dosageInstructions['instructions'] ?? 'No information yet' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No medications found for this prescription.</p>
                        @endif
                        
                        <div class="section-title">
                            <h4><strong>Notes</strong></h4>
                        </div>
                        <p>{{ $prescription->notes ?? 'No additional notes provided.' }}</p>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-primary">Edit Prescription</a>
                            <a href="{{ route('admin.prescriptions.renew', $prescription->id) }}" class="btn btn-success">Renew Prescription</a>
                        </div>
                    </div>
                </div>
                
                <!-- Refill History -->
                <div class="card prescription-card">
                    <div class="prescription-header">
                        <h2><strong>Refill</strong> History</h2>
                        <p>History of prescription refills</p>
                    </div>
                    <div class="prescription-body">
                        <div class="table-responsive">
                            @if(count($refillHistory) > 0)
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Pharmacy</th>
                                            <th>Dispensed by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($refillHistory as $refill)
                                        <tr>
                                            <td>{{ $refill['date'] }}</td>
                                            <td>{{ $refill['pharmacy'] }}</td>
                                            <td>{{ $refill['dispensed_by'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No refill history available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Information (md-4) -->
            <div class="col-lg-4 col-md-4">
                <!-- Patient Information -->
                <div class="card prescription-card">
                    <div class="prescription-header">
                        <h2><strong>Patient</strong> Information</h2>
                    </div>
                    <div class="prescription-body text-center">
                        <div class="profile-image mb-3"> 
                            <img src="{{ $prescription->patient->photo ? asset('storage/' . $prescription->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Patient Photo" width="100" height="100">
                        </div>
                        <h4>{{ $prescription->patient->name ?? 'N/A' }}</h4>
                        <p class="text-muted">
                            ID: {{ $prescription->patient->user_id ?? 'N/A' }}<br>
                            DOB: {{ $prescription->patient->date_of_birth ? $prescription->patient->date_of_birth->format('Y-m-d') : 'N/A' }}
                        </p>
                        <a href="{{ route('patient.show', $prescription->patient->id) }}" class="btn btn-primary btn-block">View Patient Profile</a>
                    </div>
                </div>
                
               
                <!-- Actions -->
                <div class="card prescription-card">
                    <div class="prescription-header">
                        <h2><strong>Actions</strong> </h2>
                    </div>
                    <div class="prescription-body text-center">
                        <a href="{{ route('admin.prescriptions.renew', $prescription->id) }}" class="btn btn-success btn-block">Renew Prescription</a>
                        <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-secondary btn-block">Edit Prescription</a>
                        <a href="{{ route('admin.prescriptions.print', $prescription->id) }}" class="btn btn-info btn-block" target="_blank">
                            <i class="zmdi zmdi-print"></i> Print Prescription
                        </a>
                        
                        <!-- Cancel Prescription Form -->
                        <form action="{{ route('admin.prescriptions.cancel', $prescription->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this prescription?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="zmdi zmdi-delete"></i> Cancel Prescription
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-position');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }
            }, 5000);
        });
    });
</script>
</body>
</html>