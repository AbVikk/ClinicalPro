<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor appointment details">

<meta name="doctor-id" content="{{ (Auth::check() && Auth::user()->role == 'doctor') ? Auth::user()->id : '' }}">

<title>ClinicalPro || Appointment Details</title>
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

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
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

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
                 
                <div class="countdown-section">
                    <div class="countdown-label">Session Time Remaining</div>
                    <div class="countdown-timer" id="countdown-timer">--:--:--</div>
                    <div class="countdown-label">This session will end automatically</div>
                </div>
                
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
                                <input type="text" name="blood_group" value="{{ $appointmentDetail->blood_group ?? '' }}" readonly>
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
                        <h3 class="section-title">Vitals (from Nurse)</h3>
                        <div class="vitals-grid">
                            <div class="vital-item">
                                <label>Blood Pressure (mmHg)</label>
                                <input type="text" name="blood_pressure" placeholder="120/80" value="{{ $appointment->vitals->blood_pressure ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>Temperature (F)</label>
                                <input type="text" name="temperature" placeholder="98.6" value="{{ $appointment->vitals->temperature ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>Pulse (bpm)</label>
                                <input type="text" name="pulse" placeholder="72" value="{{ $appointment->vitals->pulse ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>Respiratory Rate (rpm)</label>
                                <input type="text" name="respiratory_rate" placeholder="16" value="{{ $appointment->vitals->respiratory_rate ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>SPO2 (%)</label>
                                <input type="text" name="spo2" placeholder="98" value="{{ $appointment->vitals->spo2 ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>Height (cm)</label>
                                <input type="text" name="height" placeholder="165" value="{{ $appointment->vitals->height ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>Weight (Kg)</label>
                                <input type="text" name="weight" placeholder="60" value="{{ $appointment->vitals->weight ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>Waist (cm)</label>
                                <input type="text" name="waist" placeholder="70" value="{{ $appointment->vitals->waist ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>BSA (M)</label>
                                <input type="text" name="bsa" placeholder="1.7" value="{{ $appointment->vitals->bsa ?? '' }}" readonly>
                            </div>
                            <div class="vital-item">
                                <label>BMI (kg/cm)</label>
                                <input type="text" name="bmi" placeholder="22.0" value="{{ $appointment->vitals->bmi ?? '' }}" readonly>
                            </div>
                        </div>
                        {{-- <button type="button" class="add-new-btn" id="save-vitals">
                            <i class="zmdi zmdi-save"></i> Save
                        </button> --}}
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Nurse's Note & Medical History</h3>
                        <div class="form-group full-width">
                            <label>Clinical Notes (from Nurse)</label>
                            <textarea name="clinical_notes" placeholder="Enter clinical notes..." readonly>{{ $appointment->clinicalNote->note_text ?? '' }}</textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Skin Allergy</label>
                            <textarea name="skin_allergy" placeholder="Enter any skin allergies..." readonly>{{ $appointment->clinicalNote->skin_allergy ?? '' }}</textarea>
                        </div>
                        {{-- **FIX**: Removed the save button for this section
                        <button type="button" class="add-new-btn" id="save-clinical-notes">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                        --}}
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
                                    <input type="file" name="lab_tests[{{ $index }}][file]" class="form-control-file lab-test-file-input" style="display: none;">
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="new-lab-test-name" placeholder="Lab test name">
                            </div>
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
                                    <th>Use Pattern</th>
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
                                            <select name="medications[{{ $index }}][use_pattern]" class="form-control medication-use-pattern-select">
                                                <option value="{{ $medication->use_pattern ?? '' }}" selected>{{ $medication->use_pattern ?? 'Select pattern' }}</option>
                                            </select>
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
                                            <select name="medications[0][use_pattern]" class="form-control medication-use-pattern-select">
                                                <option value="">Select pattern</option>
                                            </select>
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

<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');

    // --- Configuration for Use Patterns ---
    const usePatterns = [
        '1-0-1', '1-1-1', '2-0-0', '0-0-1', 
        '1-0-0', '0-1-0', '0-1-1', '2-1-1',
        '2-1-2', '1-2-1', '2-2-2', '0-0-0'
    ];

    // Function to populate the use pattern dropdown
    function populateUsePatterns(selectElement, selectedValue = '') {
        // Clear all options first
        selectElement.innerHTML = ''; 
        
        // 1. Add the initial saved/placeholder option
        const initialOption = document.createElement('option');
        initialOption.value = selectedValue;
        initialOption.textContent = selectedValue || 'Select pattern';
        initialOption.selected = true;
        selectElement.appendChild(initialOption);

        // 2. Add all other options, skipping the one already added (if it's in the list)
        usePatterns.forEach(pattern => {
            if (pattern !== selectedValue) {
                const option = document.createElement('option');
                option.value = pattern;
                option.textContent = pattern;
                selectElement.appendChild(option);
            }
        });
    }

    // --- Select2 Initialization Function ---
    function initializeSelect2OnRow(rowElement) {
        // Medication Name (Select or Type)
        $(rowElement).find('.medication-name-select').select2({
            tags: true, // Allows typing a name not in the list
            placeholder: "Select or type medication name",
            allowClear: true,
            theme: "default"
        });

        // Medication Type/Category
        $(rowElement).find('.medication-type-select').select2({
            placeholder: "Select category",
            allowClear: true,
            theme: "default"
        });
        
        // Medication Dosage
        $(rowElement).find('.medication-dosage-select').select2({
            placeholder: "Select dosage",
            allowClear: true,
            theme: "default"
        });
        
        // Use Pattern
        $(rowElement).find('.medication-use-pattern-select').select2({
            placeholder: "Select pattern",
            allowClear: true,
            theme: "default"
        });
    }

    // 🛑 CRITICAL FIX: Removed the buggy initial medication name fields logic here.
    // The fields will now retain their name from Blade, and the 'change' listener will manage the rest.

    // --- Core Session Countdown Logic ---
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

    let timerInterval;
    if (countdownTimerElement) {
         timerInterval = setInterval(updateCountdown, 1000);
         updateCountdown();
    }


    function endSession() {
        localStorage.removeItem(localStorageKey);
        alert('Session time has expired. The appointment will be ended automatically.');

        const endForm = document.createElement('form');
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

    // --- Form Submission and Section Saving ---

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

    // Generic function to save a section via AJAX
    function saveSection(formData, successMsg, errorMsgBase) {
         formData.append('_token', '{{ csrf_token() }}');

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
            this.closest('.complaint-tag, .diagnosis-tag, .lab-test-item, tr').remove();
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
            const input = document.querySelector(`input[name="${field}"]`);
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
            addRemoveListener(tag);
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
            addRemoveListener(tag);
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
            tag.className = 'complaint-tag lab-test-item';
            tag.dataset.index = index;
            tag.innerHTML = `
                <span class="lab-test-name">${value}</span>
                <span class="lab-test-file"></span>
                <span class="tag-remove">×</span>
                <input type="hidden" name="lab_tests[${index}][name]" value="${value}">
                <input type="file" name="lab_tests[${index}][file]" class="form-control-file lab-test-file-input" style="display: inline-block; width: auto; margin-left: 10px;">`;
            container.appendChild(tag);
            addRemoveListener(tag);

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
         alert('To save lab tests with files, please use the main "Save & End" button.');
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
            <td>
                <select name="medications[${rowCount}][use_pattern]" class="form-control medication-use-pattern-select"></select>
            </td>
            <td><input type="text" name="medications[${rowCount}][instructions]" class="form-control" placeholder="e.g., Before meal"></td>
            <td><button type="button" class="btn-cancel remove-medication"><i class="zmdi zmdi-delete"></i></button></td>`;

        addRemoveListener(newRow);
        addMedicationNameToggleListener(newRow.querySelector('.medication-name-select'));
        populateUsePatterns(newRow.querySelector('.medication-use-pattern-select'));
        initializeSelect2OnRow(newRow); // Initialize Select2 on the new row
    });

     // Save Medications
     document.getElementById('save-medications')?.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('appointment-details-form'));
        const medicationData = new FormData();
        // Since we removed the buggy initialization, ALL fields should be present.
        // We rely on Laravel's input processing to correctly merge the data.
        
        // This loop is redundant and can cause issues. It's best to rely on Laravel to process
        // all input fields named 'medications[...]'

        // The current implementation of saving medications relies on ALL fields being passed,
        // so we must send ALL data, not just medication data.
        // Let's modify the function to send a simplified form of medication data.
        
        // --- FIX: Gather all medication-related fields for partial update ---
        const medicationForm = document.createElement('form');
        document.querySelectorAll('#medications-container tr').forEach((row, index) => {
            const nameSelect = row.querySelector('.medication-name-select');
            const nameInput = row.querySelector('.medication-name-input');
            
            // Re-assert name attributes for submission coherence
            if (nameSelect && nameInput) {
                 if (nameSelect.value) { // Name selected from list
                    nameSelect.setAttribute('name', `medications[${index}][name]`);
                    nameInput.removeAttribute('name');
                } else if (nameInput.value) { // Custom name typed
                    nameInput.setAttribute('name', `medications[${index}][name]`);
                    nameSelect.removeAttribute('name');
                } else {
                    // Remove both names if the row is empty
                    nameSelect.removeAttribute('name');
                    nameInput.removeAttribute('name');
                }
            }
            
            // Clone and append all inputs and selects from the row to the temporary form
            row.querySelectorAll('input, select').forEach(control => {
                if (control.name && control.name.startsWith('medications')) {
                    const clonedControl = control.cloneNode(true);
                    medicationForm.appendChild(clonedControl);
                }
            });
        });
        
        const medicationFormData = new FormData(medicationForm);
        // --- END FIX ---
        
        saveSection(medicationFormData, 'Medications saved!', 'Error saving medications');
    });

    // Function to add toggle listener for medication name select/input
    function addMedicationNameToggleListener(selectElement) {
        selectElement.addEventListener('change', function() {
            const input = this.closest('td').querySelector('.medication-name-input');
            const row = this.closest('tr');
            // Re-calculate index dynamically
            const rowIndex = Array.from(row.parentElement.children).indexOf(row);

            if (this.value) {
                input.value = ''; // Clear custom input
                input.style.display = 'none';
                
                // Set name on SELECT (Standard)
                this.setAttribute('name', `medications[${rowIndex}][name]`);
                input.removeAttribute('name');
                
                // Auto-fill Type and Dosage from data attributes
                const selectedOption = this.options[this.selectedIndex];
                const category = selectedOption ? selectedOption.dataset.category : '';
                const dosage = selectedOption ? selectedOption.dataset.dosage : '';
                
                const typeSelect = row.querySelector('.medication-type-select');
                const dosageSelect = row.querySelector('.medication-dosage-select');
                
                // Use Select2's method to set value if Select2 is initialized
                if ($(typeSelect).data('select2')) {
                    $(typeSelect).val(category).trigger('change');
                } else if (typeSelect) {
                    typeSelect.value = category;
                }
                
                if ($(dosageSelect).data('select2')) {
                    $(dosageSelect).val(dosage).trigger('change');
                } else if (dosageSelect) {
                    dosageSelect.value = dosage;
                }
            } else {
                // If SELECT is cleared, show custom input
                input.style.display = 'block';
                input.focus();

                // Set name on INPUT (Custom)
                input.setAttribute('name', `medications[${rowIndex}][name]`);
                this.removeAttribute('name');
            }
        });
        
        // Handle when a custom tag is entered in Select2
        $(selectElement).on('select2:close', function() {
            const row = this.closest('tr');
            const rowIndex = Array.from(row.parentElement.children).indexOf(row);
            const input = this.closest('td').querySelector('.medication-name-input');
            const customValue = $(this).val();

            if (!customValue || (customValue.length === 1 && customValue[0] === '')) {
                 // Nothing selected/cleared
                 $(this).val(null).trigger('change'); 
                 // Force switch to custom input
                 this.removeAttribute('name');
                 input.setAttribute('name', `medications[${rowIndex}][name]`);
                 input.style.display = 'block';
                 input.focus();
            } else if (typeof customValue === 'object' && customValue.length > 0 && customValue[0] && !this.querySelector(`option[value="${customValue[0]}"]`)) {
                // This is a custom tag entered by the doctor (Select2 with tags enabled)
                
                // Clear select name/value
                $(this).val(null).trigger('change');
                this.removeAttribute('name');
                
                // Set name on INPUT and use the custom value
                input.setAttribute('name', `medications[${rowIndex}][name]`);
                input.value = customValue[0]; // Take the first custom value
                input.style.display = 'block';
            }
        });
        
        // Handle custom input changes (just ensure name is present)
        selectElement.closest('td').querySelector('.medication-name-input')?.addEventListener('input', function() {
            const row = this.closest('tr');
            const rowIndex = Array.from(row.parentElement.children).indexOf(row);
            this.setAttribute('name', `medications[${rowIndex}][name]`);
        });
    }

    // --- Initializers ---

    // 1. Initialize Select2 on all existing rows
    document.querySelectorAll('.medication-table tbody tr').forEach(row => {
        initializeSelect2OnRow(row);
    });
    
    // 2. Add toggle listener to medication name fields present on page load
    document.querySelectorAll('.medication-name-select').forEach(addMedicationNameToggleListener);
    
    // 3. Populate use pattern for existing rows
    document.querySelectorAll('.medication-use-pattern-select').forEach(select => {
        const savedValue = select.querySelector('option:checked')?.value || '';
        populateUsePatterns(select, savedValue);
    });

    // Handle follow-up scheduling with availability check
    document.getElementById('schedule-followup-btn')?.addEventListener('click', function() {
        const followUpDate = document.getElementById('follow_up_date').value;
        const followUpTime = document.getElementById('follow_up_time').value;
        const feedbackDiv = document.getElementById('followup-availability-feedback');
        const saveButton = this;

        feedbackDiv.textContent = '';

        if (!followUpDate || !followUpTime) {
            feedbackDiv.style.color = 'red';
            feedbackDiv.textContent = 'Please enter both follow-up date and time.';
            return;
        }

        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Checking...';
        feedbackDiv.style.color = '#ff9800';
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

                saveSection(formData, 'Follow-up date and time saved successfully!', 'Error saving follow-up');

                 saveButton.disabled = false;
                 saveButton.innerHTML = '<i class="zmdi zmdi-calendar-check"></i> Schedule Follow-up Appointment';


            } else {
                feedbackDiv.style.color = 'red';
                feedbackDiv.textContent = data.message || 'Selected time slot is not available.';
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="zmdi zmdi-calendar-check"></i> Schedule Follow-up Appointment';
            }
        })
        .catch(error => {
            console.error('Error checking/saving follow-up availability:', error);
            feedbackDiv.style.color = 'red';
            feedbackDiv.textContent = 'Error: ' + error.message;
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="zmdi zmdi-calendar-check"></i> Schedule Follow-up Appointment';
        });
    });


}); // <-- END OF THE DOMContentLoaded WRAPPER
</script>
</body>
</html>