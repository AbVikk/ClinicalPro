<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor appointment details">

<title>ClinicalPro || Appointment Details</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    .appointment-header {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .patient-info-section {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .patient-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-right: 20px;
        object-fit: cover;
    }
    
    .patient-details h3 {
        margin: 0 0 5px 0;
        font-weight: 600;
    }
    
    .patient-details p {
        margin: 0;
        color: #666;
    }
    
    .appointment-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .detail-card {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 15px;
        border-left: 3px solid #1976d2;
    }
    
    .detail-card-title {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    
    .detail-card-value {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }
    
    .countdown-section {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .countdown-timer {
        font-size: 36px;
        font-weight: 700;
        color: #1976d2;
        margin: 10px 0;
    }
    
    .countdown-label {
        font-size: 14px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .form-section {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .section-title {
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-weight: 600;
        color: #333;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px 15px -10px;
    }
    
    .form-group {
        padding: 0 10px;
        margin-bottom: 15px;
        flex: 1 0 300px;
    }
    
    .form-group.full-width {
        flex: 1 0 100%;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #555;
    }
    
    .form-group input, 
    .form-group textarea, 
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .vitals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .vital-item {
        display: flex;
        flex-direction: column;
    }
    
    .vital-item label {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    
    .vital-item input {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .complaints-list, .diagnosis-list, .medications-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .complaint-tag, .diagnosis-tag, .medication-tag {
        background: #e3f2fd;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        display: flex;
        align-items: center;
    }
    
    .tag-remove {
        margin-left: 5px;
        cursor: pointer;
        color: #f44336;
    }
    
    .add-new-btn {
        background: #1976d2;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .add-new-btn:hover {
        background: #1565c0;
    }
    
    .medication-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
    }
    
    .medication-table th,
    .medication-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .medication-table th {
        background: #f5f5f5;
        font-weight: 600;
    }
    
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .btn-cancel {
        background: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .btn-save-end {
        background: #4caf50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .btn-save-end:hover {
        background: #43a047;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
</style>
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
                <h2><i class="zmdi zmdi-calendar"></i> <span>Appointment Details</span></h2>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.appointments') }}">Appointments</a></li>
                    <li class="breadcrumb-item active">Appointment Details</li>
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

        <div class="row clearfix">
            <div class="col-lg-12">
                <!-- Appointment Header -->
                <div class="appointment-header">
                    <div class="patient-info-section">
                        <img src="{{ $appointment->patient->photo ? asset('storage/' . $appointment->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" alt="Patient" class="patient-image">
                        <div class="patient-details">
                            <h3>{{ $appointment->patient->name }}</h3>
                            <p>{{ $appointment->patient->email }}</p>
                            <p>{{ $appointment->patient->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="appointment-details-grid">
                        <div class="detail-card">
                            <div class="detail-card-title">Appointment ID</div>
                            <div class="detail-card-value">#APT{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Person with Patient</div>
                            <div class="detail-card-value">{{ $appointment->doctor->name ?? 'N/A' }} ({{ $appointment->doctor->id ?? 'N/A' }})</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Type of Appointment</div>
                            <div class="detail-card-value">
                                {{ $appointment->consultation?->delivery_channel == 'virtual' ? 'Virtual' : 'Physical' }}
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Status</div>
                            <div class="detail-card-value">{{ ucfirst(str_replace('_', ' ', $appointment->status ?? 'In Progress')) }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Consultation Fees</div>
                            <div class="detail-card-value">₦{{ $appointment->consultation?->fee ?? $appointment->consultation_fee ?? '200' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Appointment Date & Time</div>
                            <div class="detail-card-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y - g:i A') }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Clinic Location</div>
                            <div class="detail-card-value">
                                {{ $appointment->consultation?->delivery_channel == 'virtual' ? 'Virtual Session' : ($appointment->consultation?->clinic?->name ?? 'N/A') }}
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Location (Address)</div>
                            <div class="detail-card-value">
                                {{ $appointment->consultation?->delivery_channel == 'virtual' ? 'N/A' : ($appointment->consultation?->clinic?->address ?? 'N/A') }}
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Visit Type (Service)</div>
                            <div class="detail-card-value">
                                {{ $appointment->consultation?->service_type ?? ($appointment->appointmentReason?->name ?? 'N/A') }}
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-card-title">Duration</div>
                            <div class="detail-card-value">
                                {{ $appointment->consultation?->duration_minutes ?? 30 }} minutes
                            </div>
                        </div>
                        </div>
                </div>
                 
                <!-- Countdown Timer -->
                <div class="countdown-section">
                    <div class="countdown-label">Session Time Remaining</div>
                    <div class="countdown-timer" id="countdown-timer">--:--:--</div>
                    <div class="countdown-label">This session will end automatically</div>
                </div>
                
                <!-- Appointment Details Form -->
                <form id="appointment-details-form" action="{{ route('doctor.appointments.save-details', $appointment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-section">
                        <h3 class="section-title">Patient Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Age / Gender</label>
                                <input type="text" name="age_gender" value="{{ $appointment->patient->age_gender ?? 'N/A' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ $appointment->patient->address ?? 'N/A' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Blood Group</label>
                                <input type="text" name="blood_group" value="{{ $appointmentDetail->blood_group ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>No of Visit</label>
                                <input type="text" name="no_of_visit" value="{{ $noOfVisits ?? '0' }}" readonly>
                            </div>
                        </div>
                        <button type="button" class="add-new-btn" id="save-patient-info">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Vitals</h3>
                        <div class="vitals-grid">
                            <div class="vital-item">
                                <label>Blood Pressure (mmHg)</label>
                                <input type="text" name="blood_pressure" placeholder="120/80" value="{{ $appointment->vitals->blood_pressure ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Temperature (F)</label>
                                <input type="text" name="temperature" placeholder="98.6" value="{{ $appointment->vitals->temperature ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Pulse (bpm)</label>
                                <input type="text" name="pulse" placeholder="72" value="{{ $appointment->vitals->pulse ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Respiratory Rate (rpm)</label>
                                <input type="text" name="respiratory_rate" placeholder="16" value="{{ $appointment->vitals->respiratory_rate ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>SPO2 (%)</label>
                                <input type="text" name="spo2" placeholder="98" value="{{ $appointment->vitals->spo2 ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Height (cm)</label>
                                <input type="text" name="height" placeholder="165" value="{{ $appointment->vitals->height ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Weight (Kg)</label>
                                <input type="text" name="weight" placeholder="60" value="{{ $appointment->vitals->weight ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Waist (cm)</label>
                                <input type="text" name="waist" placeholder="70" value="{{ $appointment->vitals->waist ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>BSA (M)</label>
                                <input type="text" name="bsa" placeholder="1.7" value="{{ $appointment->vitals->bsa ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>BMI (kg/cm)</label>
                                <input type="text" name="bmi" placeholder="22.0" value="{{ $appointment->vitals->bmi ?? '' }}">
                            </div>
                        </div>
                        <button type="button" class="add-new-btn" id="save-vitals">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Previous Medical History</h3>
                        <div class="form-group full-width">
                            <label>Clinical Notes</label>
                            <textarea name="clinical_notes" placeholder="Enter clinical notes...">{{ $appointment->clinicalNote->note_text ?? '' }}</textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Skin Allergy</label>
                            <textarea name="skin_allergy" placeholder="Enter any skin allergies...">{{ $appointment->clinicalNote->skin_allergy ?? '' }}</textarea>
                        </div>
                        <button type="button" class="add-new-btn" id="save-clinical-notes">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Laboratory Tests</h3>
                        <div class="complaints-list" id="lab-tests-container">
                            @if($appointment->labTests && $appointment->labTests->count() > 0)
                                @foreach($appointment->labTests as $index => $labTest)
                                <div class="complaint-tag lab-test-item" data-index="{{ $index }}">
                                    <span class="lab-test-name">{{ $labTest->test_name }}</span>
                                    @if(!empty($labTest->file_path))
                                        <a href="{{ asset('storage/' . $labTest->file_path) }}" target="_blank" class="lab-test-file">(View File)</a>
                                    @endif
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="lab_tests[{{ $index }}][name]" value="{{ $labTest->test_name }}">
                                    @if(!empty($labTest->file_path))
                                        <input type="hidden" name="lab_tests[{{ $index }}][file_path]" value="{{ $labTest->file_path }}">
                                    @endif
                                    <!-- Add file input for existing lab tests -->
                                    <input type="file" name="lab_tests[{{ $index }}][file]" class="form-control-file lab-test-file-input" style="display: none;">
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="new-lab-test-name" placeholder="Lab test name">
                            </div>
                            <!-- Remove the global file input and add a placeholder for new tests -->
                            <div class="form-group">
                                <div id="new-lab-test-file-placeholder"></div>
                            </div>
                        </div>
                        <button type="button" class="add-new-btn" id="add-lab-test">
                            <i class="zmdi zmdi-plus"></i> Add New
                        </button>
                        <button type="button" class="add-new-btn" id="save-lab-tests" style="margin-left: 10px;">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Complaints</h3>
                        <div class="complaints-list" id="complaints-container">
                            @if(!empty($appointmentDetail->complaints) && is_array($appointmentDetail->complaints))
                                @foreach($appointmentDetail->complaints as $index => $complaint)
                                <div class="complaint-tag">
                                    {{ $complaint }}
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="complaints[]" value="{{ $complaint }}">
                                </div>
                                @endforeach
                            @else
                                <div class="complaint-tag">
                                    Fever
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="complaints[]" value="Fever">
                                </div>
                                <div class="complaint-tag">
                                    Headache
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="complaints[]" value="Headache">
                                </div>
                                <div class="complaint-tag">
                                    Stomach Pain
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="complaints[]" value="Stomach Pain">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="text" id="new-complaint" placeholder="Add new complaint">
                        </div>
                        <button type="button" class="add-new-btn" id="add-complaint">
                            <i class="zmdi zmdi-plus"></i> Add New
                        </button>
                        <button type="button" class="add-new-btn" style="margin-left: 10px;" id="save-complaints">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Diagnosis</h3>
                        <div class="diagnosis-list" id="diagnosis-container">
                            @if(!empty($appointmentDetail->diagnosis) && is_array($appointmentDetail->diagnosis))
                                @foreach($appointmentDetail->diagnosis as $index => $diagnosis)
                                <div class="diagnosis-tag">
                                    {{ $diagnosis }}
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="diagnosis[]" value="{{ $diagnosis }}">
                                </div>
                                @endforeach
                            @else
                                <div class="diagnosis-tag">
                                    Fever
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="diagnosis[]" value="Fever">
                                </div>
                                <div class="diagnosis-tag">
                                    Headache
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="diagnosis[]" value="Headache">
                                </div>
                                <div class="diagnosis-tag">
                                    Stomach Pain
                                    <span class="tag-remove">×</span>
                                    <input type="hidden" name="diagnosis[]" value="Stomach Pain">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="text" id="new-diagnosis" placeholder="Add new diagnosis">
                        </div>
                        <button type="button" class="add-new-btn" id="add-diagnosis">
                            <i class="zmdi zmdi-plus"></i> Add New
                        </button>
                        <button type="button" class="add-new-btn" style="margin-left: 10px;" id="save-diagnosis">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Medications</h3>
                        <table class="medication-table">
                            <thead>
                                <tr>
                                    <th>Medication Name</th>
                                    <th>Type/Category</th>
                                    <th>Dosage</th>
                                    <th>Duration</th>
                                    <th>Instructions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="medications-container">
                                @if($appointment->medications->count() > 0)
                                    @foreach($appointment->medications as $index => $medication)
                                    <tr>
                                        <td>
                                            <select name="medications[{{ $index }}][name]" class="form-control medication-name-select" data-row="{{ $index }}">
                                                <option value="">Select or type medication</option>
                                                @foreach($drugs as $drug)
                                                    <option value="{{ $drug->name }}" {{ $medication->medication_name == $drug->name ? 'selected' : '' }} data-category="{{ $drug->category->name ?? '' }}" data-dosage="{{ $drug->dosage->mg_value ?? '' }}">
                                                        {{ $drug->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control medication-name-input" placeholder="Or type medication name" value="{{ $medication->medication_name }}" style="display: none; margin-top: 5px;">
                                        </td>
                                        <td>
                                            <select name="medications[{{ $index }}][type]" class="form-control medication-type-select">
                                                <option value="">Select type</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->name }}" {{ $medication->type == $category->name ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="medications[{{ $index }}][dosage]" class="form-control medication-dosage-select">
                                                <option value="">Select dosage</option>
                                                @foreach($dosages as $dosage)
                                                    <option value="{{ $dosage->mg_value }}" {{ $medication->dosage == $dosage->mg_value ? 'selected' : '' }}>
                                                        {{ $dosage->mg_value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="medications[{{ $index }}][duration]" class="form-control" placeholder="e.g., 7 days" value="{{ $medication->duration }}">
                                        </td>
                                        <td>
                                            <input type="text" name="medications[{{ $index }}][instructions]" class="form-control" placeholder="e.g., Before meal" value="{{ $medication->instructions }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn-cancel remove-medication">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            <select name="medications[0][name]" class="form-control medication-name-select" data-row="0">
                                                <option value="">Select or type medication</option>
                                                @foreach($drugs as $drug)
                                                    <option value="{{ $drug->name }}" data-category="{{ $drug->category->name ?? '' }}" data-dosage="{{ $drug->dosage->mg_value ?? '' }}">
                                                        {{ $drug->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control medication-name-input" placeholder="Or type medication name" style="display: none; margin-top: 5px;">
                                        </td>
                                        <td>
                                            <select name="medications[0][type]" class="form-control medication-type-select">
                                                <option value="">Select type</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->name }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="medications[0][dosage]" class="form-control medication-dosage-select">
                                                <option value="">Select dosage</option>
                                                @foreach($dosages as $dosage)
                                                    <option value="{{ $dosage->mg_value }}">
                                                        {{ $dosage->mg_value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="medications[0][duration]" class="form-control" placeholder="e.g., 7 days">
                                        </td>
                                        <td>
                                            <input type="text" name="medications[0][instructions]" class="form-control" placeholder="e.g., Before meal">
                                        </td>
                                        <td>
                                            <button type="button" class="btn-cancel remove-medication">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <button type="button" class="add-new-btn" id="add-medication" style="margin-top: 15px;">
                            <i class="zmdi zmdi-plus"></i> Add New Medication
                        </button>
                        <button type="button" class="add-new-btn" id="save-medications" style="margin-left: 10px; margin-top: 15px;">
                            <i class="zmdi zmdi-save"></i> Save Medications
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Advice</h3>
                        <div class="form-group full-width">
                            <textarea name="advice" placeholder="Enter medical advice...">{{ $appointmentDetail->advice ?? '' }}</textarea>
                        </div>
                        <button type="button" class="add-new-btn" id="save-advice">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Follow Up</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Follow Up Date</label>
                                <input type="date" id="follow_up_date" name="follow_up_date" value="{{ $appointmentDetail->follow_up_date ? $appointmentDetail->follow_up_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="form-group">
                                <label>Follow Up Time</label>
                                <input type="time" id="follow_up_time" name="follow_up_time" value="{{ $appointmentDetail->follow_up_time ? \Carbon\Carbon::parse($appointmentDetail->follow_up_time)->format('H:i') : '' }}">
                            </div>
                        </div>
                        <div id="followup-availability-feedback" style="margin-bottom: 15px; font-weight: bold;"></div>
                        <button type="button" class="add-new-btn" id="schedule-followup-btn">
                            <i class="zmdi zmdi-calendar-check"></i> Schedule Follow-up Appointment
                        </button>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('doctor.patients.appointment-history', $appointment->patient->id) }}" class="btn-cancel">
                            <i class="zmdi zmdi-calendar-note"></i> View Appointment History
                        </a>
                        <button type="button" class="btn-cancel" id="cancel-appointment">Cancel</button>
                        <button type="submit" class="btn-save-end" id="save-and-end-btn">Save & End</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');

    // Check if the save button exists
    const saveButton = document.querySelector('.btn-save-end');
    if (saveButton) {
        console.log('Save button found');
    } else {
        console.error('Save button not found');
    }

    // Check if the form exists
    const form = document.getElementById('appointment-details-form');
    if (form) {
        console.log('Form found');
    } else {
        console.error('Form not found');
    }

    // Initialize medication name fields
    document.querySelectorAll('.medication-name-select').forEach(function(select) {
        const input = select.closest('td').querySelector('.medication-name-input');
        const row = select.closest('tr');
        const rowIndex = Array.from(row.parentElement.children).indexOf(row);

        if (select.value) {
            select.setAttribute('name', `medications[${rowIndex}][name]`);
            input.removeAttribute('name');
        } else {
            select.removeAttribute('name');
            if (input.value) {
                input.setAttribute('name', `medications[${rowIndex}][name]`);
            }
        }
    });

    // --- Configuration and Elements ---
    const countdownTimerElement = document.getElementById('countdown-timer');
    const localStorageKey = 'appointmentSessionEndTime_{{ $appointment->id }}';
    const durationMinutes = {{ $appointment->consultation?->duration_minutes ?? 30 }};
    const sessionDurationMs = durationMinutes * 60 * 1000;

    let initialStartTime;
    @if($appointment->started_at)
        initialStartTime = new Date("{{ \Carbon\Carbon::parse($appointment->started_at)->toISOString() }}");
    @else
        initialStartTime = new Date();
    @endif

    let sessionEndTime;
    const storedEndTime = localStorage.getItem(localStorageKey);

    if (storedEndTime) {
        sessionEndTime = parseInt(storedEndTime, 10);
        console.log("Countdown using stored end time:", new Date(sessionEndTime).toISOString());
    } else {
        let calculatedEndTime = initialStartTime.getTime() + sessionDurationMs;
        if (calculatedEndTime < new Date().getTime()) {
            sessionEndTime = new Date().getTime() + sessionDurationMs;
            console.warn("Calculated end time was in the past. Resetting to " + durationMinutes + " minutes from now.");
        } else {
            sessionEndTime = calculatedEndTime;
        }
        localStorage.setItem(localStorageKey, sessionEndTime.toString());
        console.log("Countdown initialized with " + durationMinutes + " minute duration. End time:", new Date(sessionEndTime).toISOString());
    }

    // --- Core Countdown Function ---
    function updateCountdown() {
        const now = new Date().getTime();
        const diff = sessionEndTime - now;

        if (!countdownTimerElement) {
            clearInterval(timerInterval);
            console.error("Countdown timer element not found. Stopping interval.");
            return;
        }

        if (diff <= 0) {
            countdownTimerElement.textContent = '00:00:00';
            clearInterval(timerInterval);
            endSession();
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        countdownTimerElement.textContent = timeString;
    }

    // Start the timer
    let timerInterval; // Define timerInterval here
    if (countdownTimerElement) { // Only start if element exists
         timerInterval = setInterval(updateCountdown, 1000);
         updateCountdown(); // Initial call
    }


    // --- Auxiliary Form Functions ---
    function endSession() {
        localStorage.removeItem(localStorageKey);
        alert('Session time has expired. The appointment will be ended automatically.');

        const endForm = document.createElement('form'); // Renamed to avoid conflict
        endForm.method = 'POST';
        endForm.action = '{{ route("doctor.appointments.end", $appointment->id) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        endForm.appendChild(csrfToken);

        const endReason = document.createElement('input');
        endReason.type = 'hidden';
        endReason.name = 'end_reason';
        endReason.value = 'Session time expired';
        endForm.appendChild(endReason);

        document.body.appendChild(endForm);
        endForm.submit();
    }

    // Handle form submission for Save & End button
    document.getElementById('save-and-end-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to save and end this appointment?')) return;

        localStorage.removeItem(localStorageKey);
        clearInterval(timerInterval);

        const form = document.getElementById('appointment-details-form');
        const formData = new FormData(form);
        formData.append('is_full_update', 'true');
        const submitButton = this;
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Saving...';

        fetch('{{ route("doctor.appointments.save-details", $appointment->id) }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Appointment saved and completed successfully!');
                window.location.href = data.redirect_url || '{{ route("doctor.dashboard") }}';
            } else {
                alert('Error: ' + data.message);
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving appointment. Please try again.');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    });

    // Cancel appointment
    document.getElementById('cancel-appointment')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel this appointment session?')) {
            localStorage.removeItem(localStorageKey);
            clearInterval(timerInterval);
            window.location.href = '{{ route("doctor.appointments") }}';
        }
    });

    // --- Section Save Functions & Add/Remove Logic ---

    // Generic function to save a section via AJAX
    function saveSection(formData, successMsg, errorMsgBase) {
         formData.append('_token', '{{ csrf_token() }}'); // Ensure CSRF token is added

        return fetch('{{ route("doctor.appointments.save-details", $appointment->id) }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(successMsg);
            } else {
                alert(errorMsgBase + ': ' + data.message);
            }
            return data;
        })
        .catch(error => {
            console.error('Error saving section:', error);
            alert(errorMsgBase + '. Please try again.');
        });
    }

     // Add remove functionality to dynamically added tags/rows
     function addRemoveListener(element) {
        element.querySelector('.tag-remove, .remove-medication')?.addEventListener('click', function() {
            element.remove();
        });
    }

    // Initialize remove listeners for elements present on page load
    document.querySelectorAll('.tag-remove, .remove-medication').forEach(function(element) {
        element.addEventListener('click', function() {
            this.closest('.complaint-tag, .diagnosis-tag, .lab-test-item, tr').remove();
        });
    });

    // Save Patient Info
    document.getElementById('save-patient-info')?.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('blood_group', document.querySelector('input[name="blood_group"]').value);
        saveSection(formData, 'Patient information saved!', 'Error saving patient info');
    });

    // Save Vitals
    document.getElementById('save-vitals')?.addEventListener('click', function() {
        const formData = new FormData();
        const vitalsFields = ['blood_pressure', 'temperature', 'pulse', 'respiratory_rate', 'spo2', 'height', 'weight', 'waist', 'bsa', 'bmi'];
        vitalsFields.forEach(field => {
            const input = document.querySelector(`input[name="${field}"]`); // Use the main form variable
            if (input) formData.append(field, input.value);
        });
        saveSection(formData, 'Vitals saved!', 'Error saving vitals');
    });

    // Save Clinical Notes
    document.getElementById('save-clinical-notes')?.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('clinical_notes', document.querySelector('textarea[name="clinical_notes"]').value);
        formData.append('skin_allergy', document.querySelector('textarea[name="skin_allergy"]').value);
        saveSection(formData, 'Clinical notes saved!', 'Error saving clinical notes');
    });

    // Save Advice
    document.getElementById('save-advice')?.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('advice', document.querySelector('textarea[name="advice"]').value);
        saveSection(formData, 'Advice saved!', 'Error saving advice');
    });


    // Add Complaint
    document.getElementById('add-complaint')?.addEventListener('click', function() {
        const input = document.getElementById('new-complaint');
        const value = input.value.trim();
        if (value) {
            const container = document.getElementById('complaints-container');
            const tag = document.createElement('div');
            tag.className = 'complaint-tag';
            tag.innerHTML = `${value} <span class="tag-remove">×</span><input type="hidden" name="complaints[]" value="${value}">`;
            container.appendChild(tag);
            addRemoveListener(tag); // Add listener to the new tag
            input.value = '';
        }
    });

     // Save Complaints
     document.getElementById('save-complaints')?.addEventListener('click', function() {
        const formData = new FormData();
        const complaints = [];
        document.querySelectorAll('#complaints-container input[name^="complaints"]').forEach(input => {
             complaints.push(input.value);
        });
        // Add complaints as an array
        complaints.forEach((complaint, index) => {
            formData.append(`complaints[${index}]`, complaint);
        });
        saveSection(formData, 'Complaints saved!', 'Error saving complaints');
    });


    // Add Diagnosis
    document.getElementById('add-diagnosis')?.addEventListener('click', function() {
        const input = document.getElementById('new-diagnosis');
        const value = input.value.trim();
        if (value) {
            const container = document.getElementById('diagnosis-container');
            const tag = document.createElement('div');
            tag.className = 'diagnosis-tag';
            tag.innerHTML = `${value} <span class="tag-remove">×</span><input type="hidden" name="diagnosis[]" value="${value}">`;
            container.appendChild(tag);
            addRemoveListener(tag); // Add listener
            input.value = '';
        }
    });

     // Save Diagnosis
     document.getElementById('save-diagnosis')?.addEventListener('click', function() {
        const formData = new FormData();
        const diagnosis = [];
        document.querySelectorAll('#diagnosis-container input[name^="diagnosis"]').forEach(input => {
             diagnosis.push(input.value);
        });
        // Add diagnosis as an array
        diagnosis.forEach((diag, index) => {
            formData.append(`diagnosis[${index}]`, diag);
        });
        saveSection(formData, 'Diagnosis saved!', 'Error saving diagnosis');
    });

    // Add Lab Test
    document.getElementById('add-lab-test')?.addEventListener('click', function() {
        const input = document.getElementById('new-lab-test-name');
        const value = input.value.trim();
        if (value) {
            const container = document.getElementById('lab-tests-container');
            const index = container.querySelectorAll('.lab-test-item').length;
            const tag = document.createElement('div');
            tag.className = 'complaint-tag lab-test-item'; // Re-using style, adjust if needed
            tag.dataset.index = index;
            tag.innerHTML = `
                <span class="lab-test-name">${value}</span>
                <span class="lab-test-file"></span>
                <span class="tag-remove">×</span>
                <input type="hidden" name="lab_tests[${index}][name]" value="${value}">
                <input type="file" name="lab_tests[${index}][file]" class="form-control-file lab-test-file-input" style="display: inline-block; width: auto; margin-left: 10px;">`;
            container.appendChild(tag);
            addRemoveListener(tag); // Add listener

            // Add listener for file name display
            const fileInput = tag.querySelector('.lab-test-file-input');
            fileInput.addEventListener('change', function() {
                const fileNameSpan = tag.querySelector('.lab-test-file');
                fileNameSpan.textContent = this.files.length > 0 ? ` (File: ${this.files[0].name})` : '';
            });

            input.value = '';
        }
    });

     // Save Lab Tests
     document.getElementById('save-lab-tests')?.addEventListener('click', function() {
         // Saving lab tests requires file handling, better done via full form submit or specific AJAX
         alert('Please use the main "Save & End" button to save lab tests with files.');
         // Or implement specific FormData handling for files if needed for partial saves
         /*
         const formData = new FormData();
         document.querySelectorAll('#lab-tests-container .lab-test-item').forEach((item, i) => {
             const nameInput = item.querySelector('input[type="hidden"]');
             const fileInput = item.querySelector('input[type="file"]');
             if (nameInput) formData.append(`lab_tests[${i}][name]`, nameInput.value);
             // Append file only if selected
             if (fileInput && fileInput.files.length > 0) {
                 formData.append(`lab_tests[${i}][file]`, fileInput.files[0]);
             } else {
                  // Check if there was an existing file path hidden input
                  const existingPathInput = item.querySelector('input[name$="[file_path]"]');
                  if (existingPathInput) {
                      formData.append(`lab_tests[${i}][file_path]`, existingPathInput.value);
                  }
             }
         });
         saveSection(formData, 'Lab tests saved!', 'Error saving lab tests');
         */
    });

    // Add Medication
    document.getElementById('add-medication')?.addEventListener('click', function() {
        const tableBody = document.getElementById('medications-container');
        const rowCount = tableBody.rows.length;
        const newRow = tableBody.insertRow();
        newRow.innerHTML = `
            <td>
                <select name="medications[${rowCount}][name]" class="form-control medication-name-select" data-row="${rowCount}">
                    <option value="">Select or type medication</option>
                    @foreach($drugs as $drug)
                        <option value="{{ $drug->name }}" data-category="{{ $drug->category->name ?? '' }}" data-dosage="{{ $drug->dosage->mg_value ?? '' }}">{{ $drug->name }}</option>
                    @endforeach
                </select>
                <input type="text" class="form-control medication-name-input" placeholder="Or type name" style="display: none; margin-top: 5px;">
            </td>
            <td>
                <select name="medications[${rowCount}][type]" class="form-control medication-type-select">
                    <option value="">Select type</option>
                    @foreach($categories as $category) <option value="{{ $category->name }}">{{ $category->name }}</option> @endforeach
                </select>
            </td>
            <td>
                <select name="medications[${rowCount}][dosage]" class="form-control medication-dosage-select">
                    <option value="">Select dosage</option>
                    @foreach($dosages as $dosage) <option value="{{ $dosage->mg_value }}">{{ $dosage->mg_value }}</option> @endforeach
                </select>
            </td>
            <td><input type="text" name="medications[${rowCount}][duration]" class="form-control" placeholder="e.g., 7 days"></td>
            <td><input type="text" name="medications[${rowCount}][instructions]" class="form-control" placeholder="e.g., Before meal"></td>
            <td><button type="button" class="btn-cancel remove-medication"><i class="zmdi zmdi-delete"></i></button></td>`;

        addRemoveListener(newRow); // Add listener
        addMedicationNameToggleListener(newRow.querySelector('.medication-name-select')); // Add toggle listener
    });

     // Save Medications
     document.getElementById('save-medications')?.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('appointment-details-form'));
        // Filter to only include medication data
        const medicationData = new FormData();
        for (const [key, value] of formData.entries()) {
            if (key.startsWith('medications')) {
                medicationData.append(key, value);
            }
        }
        saveSection(medicationData, 'Medications saved!', 'Error saving medications');
    });

    // Function to add toggle listener for medication name select/input
    function addMedicationNameToggleListener(selectElement) {
        selectElement.addEventListener('change', function() {
            const input = this.closest('td').querySelector('.medication-name-input');
            const row = this.closest('tr');
            // Re-calculate index dynamically on change, might be safer
            const rowIndex = Array.from(row.parentElement.children).indexOf(row);

            if (this.value === '') {
                this.removeAttribute('name');
                input.setAttribute('name', `medications[${rowIndex}][name]`);
                input.style.display = 'block';
                input.focus();
            } else {
                input.removeAttribute('name');
                 input.style.display = 'none'; // Hide input when select is used
                this.setAttribute('name', `medications[${rowIndex}][name]`);
                // Auto-fill
                const selectedOption = this.options[this.selectedIndex];
                const category = selectedOption.dataset.category;
                const dosage = selectedOption.dataset.dosage;
                const typeSelect = row.querySelector('.medication-type-select');
                const dosageSelect = row.querySelector('.medication-dosage-select');
                if (category && typeSelect) typeSelect.value = category;
                if (dosage && dosageSelect) dosageSelect.value = dosage;
            }
        });
    }

    // Add toggle listener to medication name fields present on page load
    document.querySelectorAll('.medication-name-select').forEach(addMedicationNameToggleListener);


    // Handle follow-up scheduling with availability check
    // *** THIS IS THE MOVED CODE ***
    document.getElementById('schedule-followup-btn')?.addEventListener('click', function() {
        const followUpDate = document.getElementById('follow_up_date').value;
        const followUpTime = document.getElementById('follow_up_time').value;
        const feedbackDiv = document.getElementById('followup-availability-feedback');
        const saveButton = this;

        feedbackDiv.textContent = ''; // Clear previous feedback

        if (!followUpDate || !followUpTime) {
            feedbackDiv.style.color = 'red';
            feedbackDiv.textContent = 'Please enter both follow-up date and time.';
            return;
        }

        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Checking...';
        feedbackDiv.style.color = '#ff9800'; // Orange for checking
        feedbackDiv.textContent = 'Checking availability...';

        fetch('{{ route("doctor.appointments.check-followup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                follow_up_date: followUpDate,
                follow_up_time: followUpTime
            })
        })
        .then(response => {
            if (!response.ok) {
                 return response.json().then(errData => {
                     throw new Error(errData.message || `HTTP error! status: ${response.status}`);
                 }).catch(() => {
                     throw new Error(`HTTP error! status: ${response.status}`);
                 });
            }
            return response.json();
        })
        
        .then(data => {
            if (data.available) {
                feedbackDiv.style.color = 'green';
                feedbackDiv.textContent = data.message + ' Saving date/time...';

                // Save the Date/Time using AJAX
                const formData = new FormData();
                formData.append('follow_up_date', followUpDate);
                formData.append('follow_up_time', followUpTime);
                // formData.append('_token', '{{ csrf_token() }}'); // Already handled by saveSection

                // Use the generic saveSection function
                saveSection(formData, 'Follow-up date and time saved successfully!', 'Error saving follow-up');

                // Re-enable button after save attempt (inside saveSection might be better)
                // We'll reset it here for simplicity for now
                 saveButton.disabled = false;
                 saveButton.innerHTML = '<i class="zmdi zmdi-calendar-check"></i> Check Availability & Save';


            } else {
                feedbackDiv.style.color = 'red';
                feedbackDiv.textContent = data.message || 'Selected time slot is not available.';
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="zmdi zmdi-calendar-check"></i> Check Availability & Save';
            }
        })
        .catch(error => {
            console.error('Error checking/saving follow-up availability:', error);
            feedbackDiv.style.color = 'red';
            feedbackDiv.textContent = 'Error: ' + error.message;
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="zmdi zmdi-calendar-check"></i> Check Availability & Save';
        });
    });
    // *** END OF MOVED CODE ***


}); // <-- END OF THE DOMContentLoaded WRAPPER
</script>
</body>
</html>