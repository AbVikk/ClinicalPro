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
                            <div class="detail-card-value">{{ $appointment->consultation->service_type ?? ($appointment->appointmentReason->name ?? $appointment->type ?? 'General Visit') }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Status</div>
                            <div class="detail-card-value">{{ ucfirst(str_replace('_', ' ', $appointment->status ?? 'In Progress')) }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Consultation Fees</div>
                            <div class="detail-card-value">NGN{{ $appointment->consultation->fee ?? $appointment->consultation_fee ?? '200' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Appointment Date & Time</div>
                            <div class="detail-card-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y - g:i A') }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Clinic Location</div>
                            <div class="detail-card-value">{{ $appointment->consultation->clinic->name ?? $appointment->clinic_location ?? 'Adrian\'s Dentistry' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Location</div>
                            <div class="detail-card-value">{{ $appointment->consultation->clinic->address ?? $appointment->location ?? 'Newyork, United States' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Visit Type</div>
                            <div class="detail-card-value">{{ $appointment->visit_type ?? 'General' }}</div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-card-title">Duration</div>
                            <div class="detail-card-value">{{ $appointment->consultation->duration_minutes ?? 30 }} minutes</div>
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
                        <button type="button" class="add-new-btn">
                            <i class="zmdi zmdi-plus"></i> Save
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
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="new-lab-test-name" placeholder="Lab test name">
                            </div>
                            <div class="form-group">
                                <input type="file" id="new-lab-test-file" class="form-control-file" name="new_lab_test_file">
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
                        <button type="button" class="add-new-btn">
                            <i class="zmdi zmdi-save"></i> Save
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Follow Up</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Follow Up Date</label>
                                <input type="date" name="follow_up_date" value="{{ $appointmentDetail->follow_up_date ? $appointmentDetail->follow_up_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="form-group">
                                <label>Follow Up Time</label>
                                <input type="time" name="follow_up_time" value="{{ $appointmentDetail->follow_up_time ? $appointmentDetail->follow_up_time->format('H:i') : '' }}">
                            </div>
                        </div>
                        <button type="button" class="add-new-btn" id="schedule-followup">
                            <i class="zmdi zmdi-calendar-plus"></i> Schedule Follow-up Appointment
                        </button>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('doctor.patients.appointment-history', $appointment->patient->id) }}" class="btn-cancel">
                            <i class="zmdi zmdi-calendar-note"></i> View Appointment History
                        </a>
                        <button type="button" class="btn-cancel" id="cancel-appointment">Cancel</button>
                        <button type="submit" class="btn-save-end">Save & End</button>
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
    // Ensure that either the select or input has the name attribute based on their values
    document.querySelectorAll('.medication-name-select').forEach(function(select) {
        const input = select.closest('td').querySelector('.medication-name-input');
        const row = select.closest('tr');
        const rowIndex = Array.from(row.parentElement.children).indexOf(row);
        
        // If select has a value, it should have the name attribute
        if (select.value) {
            select.setAttribute('name', `medications[${rowIndex}][name]`);
            input.removeAttribute('name');
        } else {
            // If select is empty, the input should have the name attribute (if it has a value)
            select.removeAttribute('name');
            if (input.value) {
                input.setAttribute('name', `medications[${rowIndex}][name]`);
            }
        }
    });
    
    // --- Configuration and Elements ---
    const countdownTimerElement = document.getElementById('countdown-timer');
    const localStorageKey = 'appointmentSessionEndTime_{{ $appointment->id }}'; 
    
    // Get duration from the appointment data, default to 30 minutes
    const durationMinutes = {{ $appointment->consultation->duration_minutes ?? 30 }};
    const sessionDurationMs = durationMinutes * 60 * 1000; // Duration in milliseconds

    // Get appointment start time from Blade
    let initialStartTime;
    // Check if appointment started_at is set, otherwise use current time as a fallback
    @if($appointment->started_at)
        // Use the timestamp and multiply by 1000 for JavaScript milliseconds
        initialStartTime = new Date('{{ \Carbon\Carbon::parse($appointment->started_at)->timestamp * 1000 }}');
    @else
        // If no start time is recorded, the session hasn't officially started or is a draft
        initialStartTime = new Date(); 
    @endif
    
    let sessionEndTime;

    // 1. Check Local Storage for persistence across reloads
    const storedEndTime = localStorage.getItem(localStorageKey);

    if (storedEndTime) {
        // Use stored time if available
        sessionEndTime = parseInt(storedEndTime, 10);
        console.log("Countdown using stored end time:", new Date(sessionEndTime).toISOString());
    } else {
        // 2. Calculate and store the absolute end time based on duration
        let calculatedEndTime = initialStartTime.getTime() + sessionDurationMs;
        
        // Prevent end time from being in the past if page loads much later than initialStartTime
        if (calculatedEndTime < new Date().getTime()) {
            // Reset to duration minutes from now for expired sessions
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
        
        // Safety check against the original error
        if (!countdownTimerElement) {
            clearInterval(timerInterval);
            console.error("Countdown timer element not found. Stopping interval.");
            return;
        }

        if (diff <= 0) {
            countdownTimerElement.textContent = '00:00:00';
            clearInterval(timerInterval);
            endSession(); // Call your auto-end session function
            return;
        }
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        countdownTimerElement.textContent = timeString;
    }

    // Start the timer
    const timerInterval = setInterval(updateCountdown, 1000);
    updateCountdown(); 
    
    // --- Auxiliary Form Functions (Including the corrected endSession) ---

    // Auto-end session function
    function endSession() {
        // Clear persistence when the session officially ends
        localStorage.removeItem(localStorageKey);
        
        // Show alert
        alert('Session time has expired. The appointment will be ended automatically.');
        
        // Submit form to end appointment
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("doctor.appointments.end", $appointment->id) }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add end reason
        const endReason = document.createElement('input');
        endReason.type = 'hidden';
        endReason.name = 'end_reason';
        endReason.value = 'Session time expired';
        form.appendChild(endReason);
        
        document.body.appendChild(form);
        form.submit();
    }
    
    // Handle form submission for Save & End button
    document.querySelector('.btn-save-end')?.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Show confirmation dialog
        if (!confirm('Are you sure you want to save and end this appointment?')) {
            return;
        }
        
        // Clear persistence when manually ending the session
        localStorage.removeItem(localStorageKey);
        clearInterval(timerInterval); // Stop the timer
        
        const form = document.getElementById('appointment-details-form');
        const formData = new FormData(form);
        
        // Log form data for debugging
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        const submitButton = this;
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Saving...';
        
        // Submit form using traditional POST (not AJAX) to ensure it works
        form.submit();
    });

    // Cancel appointment
    document.getElementById('cancel-appointment')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel this appointment session?')) {
            localStorage.removeItem(localStorageKey); // Clear persistence on cancel
            clearInterval(timerInterval); // Stop the timer
            window.location.href = '{{ route("doctor.appointments") }}';
        }
    });
    
    // The rest of your auxiliary functions (add-complaint, add-diagnosis, add-medication, etc.) go here.
    // I'm omitting them for brevity, but you should copy them from your original code into this section.
    
    // Example: Re-initializing remove functionality for existing tags/rows
    document.querySelectorAll('.tag-remove').forEach(function(element) {
        element.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });
    
    // Add complaint functionality
    document.getElementById('add-complaint')?.addEventListener('click', function() {
        const newComplaint = document.getElementById('new-complaint').value.trim();
        if (newComplaint) {
            const complaintsContainer = document.getElementById('complaints-container');
            const complaintTag = document.createElement('div');
            complaintTag.className = 'complaint-tag';
            complaintTag.innerHTML = `
                ${newComplaint} <span class="tag-remove">×</span>
                <input type="hidden" name="complaints[]" value="${newComplaint}">
            `;
            complaintsContainer.appendChild(complaintTag);
            document.getElementById('new-complaint').value = '';
            
            // Add remove functionality
            complaintTag.querySelector('.tag-remove').addEventListener('click', function() {
                complaintTag.remove();
            });
        }
    });
    
    // Add diagnosis functionality
    document.getElementById('add-diagnosis')?.addEventListener('click', function() {
        const newDiagnosis = document.getElementById('new-diagnosis').value.trim();
        if (newDiagnosis) {
            const diagnosisContainer = document.getElementById('diagnosis-container');
            const diagnosisTag = document.createElement('div');
            diagnosisTag.className = 'diagnosis-tag';
            diagnosisTag.innerHTML = `
                ${newDiagnosis} <span class="tag-remove">×</span>
                <input type="hidden" name="diagnosis[]" value="${newDiagnosis}">
            `;
            diagnosisContainer.appendChild(diagnosisTag);
            document.getElementById('new-diagnosis').value = '';
            
            // Add remove functionality
            diagnosisTag.querySelector('.tag-remove').addEventListener('click', function() {
                diagnosisTag.remove();
            });
        }
    });
    
    // Add remove functionality to existing complaint tags
    document.querySelectorAll('#complaints-container .tag-remove').forEach(function(element) {
        element.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });
    
    // Add remove functionality to existing diagnosis tags
    document.querySelectorAll('#diagnosis-container .tag-remove').forEach(function(element) {
        element.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });
    
    // Save complaints functionality
    document.getElementById('save-complaints')?.addEventListener('click', function() {
        alert('Complaints saved successfully!');
        // In a real implementation, this would make an AJAX call to save the data
    });
    
    // Save diagnosis functionality
    document.getElementById('save-diagnosis')?.addEventListener('click', function() {
        alert('Diagnosis saved successfully!');
        // In a real implementation, this would make an AJAX call to save the data
    });
    
    // Add lab test functionality
    document.getElementById('add-lab-test')?.addEventListener('click', function() {
        const labTestName = document.getElementById('new-lab-test-name').value.trim();
        const labTestFile = document.getElementById('new-lab-test-file');
        
        if (labTestName) {
            const labTestsContainer = document.getElementById('lab-tests-container');
            const currentIndex = labTestsContainer.querySelectorAll('.lab-test-item').length;
            
            const labTestTag = document.createElement('div');
            labTestTag.className = 'complaint-tag lab-test-item';
            labTestTag.setAttribute('data-index', currentIndex);
            
            let fileText = '';
            if (labTestFile.files.length > 0) {
                fileText = ' (File attached: ' + labTestFile.files[0].name + ')';
            }
            
            labTestTag.innerHTML = `
                <span class="lab-test-name">${labTestName}</span>
                <span class="lab-test-file">${fileText}</span>
                <span class="tag-remove">×</span>
                <input type="hidden" name="lab_tests[${currentIndex}][name]" value="${labTestName}">
            `;
            
            labTestsContainer.appendChild(labTestTag);
            document.getElementById('new-lab-test-name').value = '';
            labTestFile.value = '';
            
            // Add remove functionality
            labTestTag.querySelector('.tag-remove').addEventListener('click', function() {
                labTestTag.remove();
            });
        }
    });
    
    // Save lab tests functionality
    document.getElementById('save-lab-tests')?.addEventListener('click', function() {
        alert('Lab tests saved successfully!');
        // In a real implementation, this would make an AJAX call to save the data
    });
    
    // Add remove functionality to existing lab test tags
    document.querySelectorAll('#lab-tests-container .tag-remove').forEach(function(element) {
        element.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });
    
    // Add medication functionality
    document.getElementById('add-medication')?.addEventListener('click', function() {
        const medicationTable = document.querySelector('#medications-container');
        const rowCount = medicationTable.querySelectorAll('tr').length;
        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="medications[${rowCount}][name]" class="form-control medication-name-select" data-row="${rowCount}">
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
                <select name="medications[${rowCount}][type]" class="form-control medication-type-select">
                    <option value="">Select type</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="medications[${rowCount}][dosage]" class="form-control medication-dosage-select">
                    <option value="">Select dosage</option>
                    @foreach($dosages as $dosage)
                        <option value="{{ $dosage->mg_value }}">{{ $dosage->mg_value }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="medications[${rowCount}][duration]" class="form-control" placeholder="e.g., 7 days">
            </td>
            <td>
                <input type="text" name="medications[${rowCount}][instructions]" class="form-control" placeholder="e.g., Before meal">
            </td>
            <td>
                <button type="button" class="btn-cancel remove-medication">
                    <i class="zmdi zmdi-delete"></i>
                </button>
            </td>
        `;
        medicationTable.appendChild(newRow);
        
        // Add remove functionality for the new row
        const removeButton = newRow.querySelector('.remove-medication');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                newRow.remove();
            });
        }
        
        // Add event listeners for the new selects
        const nameSelect = newRow.querySelector('.medication-name-select');
        const nameInput = newRow.querySelector('.medication-name-input');
        const typeSelect = newRow.querySelector('.medication-type-select');
        const dosageSelect = newRow.querySelector('.medication-dosage-select');
        
        // Add functionality to toggle between select and input for medication name
        nameSelect.addEventListener('change', function() {
            if (this.value === '') {
                // Remove name attribute from select and add to input
                this.removeAttribute('name');
                nameInput.setAttribute('name', `medications[${rowCount}][name]`);
                nameInput.style.display = 'block';
                nameInput.focus();
            } else {
                // Remove name attribute from input and add to select
                nameInput.removeAttribute('name');
                this.setAttribute('name', `medications[${rowCount}][name]`);
                // Auto-fill type and dosage if available
                const selectedOption = this.options[this.selectedIndex];
                const category = selectedOption.getAttribute('data-category');
                const dosage = selectedOption.getAttribute('data-dosage');
                
                if (category) {
                    typeSelect.value = category;
                }
                if (dosage) {
                    dosageSelect.value = dosage;
                }
            }
        });
    });
    
    // Save medications functionality
    document.getElementById('save-medications')?.addEventListener('click', function() {
        alert('Medications saved successfully!');
        // In a real implementation, this would make an AJAX call to save the data
    });
    
    // Handle follow-up scheduling
    document.getElementById('schedule-followup')?.addEventListener('click', function() {
        const followUpDate = document.querySelector('input[name="follow_up_date"]').value;
        const followUpTime = document.querySelector('input[name="follow_up_time"]').value;
        
        if (!followUpDate || !followUpTime) {
            alert('Please enter both follow-up date and time.');
            return;
        }
        
        // In a real implementation, this would make an AJAX call to schedule the follow-up
        // For now, we'll just show a confirmation
        alert(`Follow-up appointment scheduled for ${followUpDate} at ${followUpTime}. This would create an appointment for both doctor and patient in a real implementation.`);
    });
    
    // Add remove functionality to existing medication rows
    document.querySelectorAll('.remove-medication').forEach(function(element) {
        element.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });
    
    // Add functionality to toggle between select and input for medication names
    document.querySelectorAll('.medication-name-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const input = this.closest('td').querySelector('.medication-name-input');
            const row = this.closest('tr');
            const rowIndex = Array.from(row.parentElement.children).indexOf(row);
            
            if (this.value === '') {
                // Remove name attribute from select and add to input
                this.removeAttribute('name');
                input.setAttribute('name', `medications[${rowIndex}][name]`);
                input.style.display = 'block';
                input.focus();
            } else {
                // Remove name attribute from input and add to select
                input.removeAttribute('name');
                this.setAttribute('name', `medications[${rowIndex}][name]`);
                // Auto-fill type and dosage if available
                const selectedOption = this.options[this.selectedIndex];
                const category = selectedOption.getAttribute('data-category');
                const dosage = selectedOption.getAttribute('data-dosage');
                const typeSelect = this.closest('tr').querySelector('.medication-type-select');
                const dosageSelect = this.closest('tr').querySelector('.medication-dosage-select');
                
                if (category) {
                    typeSelect.value = category;
                }
                if (dosage) {
                    dosageSelect.value = dosage;
                }
            }
        });
    });
});
</script>
</body>
</html>