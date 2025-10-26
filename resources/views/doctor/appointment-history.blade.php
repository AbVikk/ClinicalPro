<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor appointment history">

<title>ClinicalPro || Appointment History</title>
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
        justify-content: space-between;
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
    
    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .nav-btn {
        background: #1976d2;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .nav-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    .nav-btn:hover:not(:disabled) {
        background: #1565c0;
    }
    
    .appointment-counter {
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
        color: #1976d2;
    }
    
    .lab-tests-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .lab-test-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
        border: 1px solid #e9ecef;
    }
    
    .lab-test-name {
        font-weight: 500;
        color: #495057;
    }
    
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.875rem;
        border-radius: 3px;
    }
    
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }
    
    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }
    
    .btn-secondary.disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
    
    .upload-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .file-input {
        padding: 2px 5px;
        font-size: 0.8rem;
        border: 1px solid #ced4da;
        border-radius: 3px;
    }
    
    .upload-button {
        padding: 3px 8px;
        font-size: 0.8rem;
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
                <h2><i class="zmdi zmdi-calendar"></i> <span>Appointment History</span></h2>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.patient.index') }}">Patients</a></li>
                    <li class="breadcrumb-item active">Appointment History</li>
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
                <!-- Navigation Buttons -->
                <div class="navigation-buttons">
                    <button id="prev-btn" class="nav-btn">
                        <i class="zmdi zmdi-arrow-left"></i> Previous
                    </button>
                    <button id="next-btn" class="nav-btn">
                        Next <i class="zmdi zmdi-arrow-right"></i>
                    </button>
                </div>
                
                <!-- Appointment Counter -->
                <div class="appointment-counter">
                    <span id="appointment-counter">Appointment 1 of {{ $completedAppointments->count() }}</span>
                </div>
                
                <!-- Appointment Header -->
                <div class="appointment-header">
                    <div class="patient-info-section">
                        <img src="{{ $currentAppointment->patient->photo ? asset('storage/' . $currentAppointment->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" alt="Patient" class="patient-image">
                        <div class="patient-details">
                            <h3>{{ $currentAppointment->patient->name }}</h3>
                            {{-- <p>{{ $currentAppointment->patient->email }}</p> --}}
                            <p>{{ $currentAppointment->patient->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="appointment-details-grid">
                        <div class="detail-card">
                            <div class="detail-card-title">Appointment ID</div>
                            <div class="detail-card-value">#APT{{ str_pad($currentAppointment->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Doctor</div>
                            <div class="detail-card-value">{{ $currentAppointment->doctor->name ?? 'N/A' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Type of Appointment</div>
                            <div class="detail-card-value">{{ $currentAppointment->appointmentReason->name ?? $currentAppointment->type ?? 'General Visit' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Status</div>
                            <div class="detail-card-value">{{ ucfirst(str_replace('_', ' ', $currentAppointment->status ?? 'Completed')) }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Consultation Fees</div>
                            <div class="detail-card-value">${{ $currentAppointment->consultation_fee ?? '200' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Appointment Date & Time</div>
                            <div class="detail-card-value">{{ \Carbon\Carbon::parse($currentAppointment->appointment_time)->format('d M Y - g:i A') }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Clinic Location</div>
                            <div class="detail-card-value">{{ $currentAppointment->clinic_location ?? 'Adrian\'s Dentistry' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Location</div>
                            <div class="detail-card-value">{{ $currentAppointment->location ?? 'Newyork, United States' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Visit Type</div>
                            <div class="detail-card-value">{{ $currentAppointment->visit_type ?? 'General' }}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-card-title">Total Visits</div>
                            <div class="detail-card-value">{{ $totalVisits }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Appointment Details Form -->
                <form id="appointment-details-form" action="{{ route('doctor.appointments.save-details', $currentAppointment->id) }}" method="POST">
                    @csrf
                    <div class="form-section">
                        <h3 class="section-title">Patient Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Age / Gender</label>
                                <input type="text" name="age_gender" value="{{ $currentAppointment->patient->age_gender ?? 'N/A' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ $currentAppointment->patient->address ?? 'N/A' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Blood Group</label>
                                <input type="text" name="blood_group" value="{{ $currentAppointmentDetail->blood_group ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>No of Visit</label>
                                <input type="text" name="no_of_visit" value="{{ $currentAppointment->no_of_visit ?? '0' }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Vitals</h3>
                        <div class="vitals-grid">
                            <div class="vital-item">
                                <label>Blood Pressure (mmHg)</label>
                                <input type="text" name="blood_pressure" placeholder="120/80" value="{{ $currentAppointment->vitals->blood_pressure ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Temperature (F)</label>
                                <input type="text" name="temperature" placeholder="98.6" value="{{ $currentAppointment->vitals->temperature ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Pulse (bpm)</label>
                                <input type="text" name="pulse" placeholder="72" value="{{ $currentAppointment->vitals->pulse ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Respiratory Rate (rpm)</label>
                                <input type="text" name="respiratory_rate" placeholder="16" value="{{ $currentAppointment->vitals->respiratory_rate ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>SPO2 (%)</label>
                                <input type="text" name="spo2" placeholder="98" value="{{ $currentAppointment->vitals->spo2 ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Height (cm)</label>
                                <input type="text" name="height" placeholder="165" value="{{ $currentAppointment->vitals->height ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Weight (Kg)</label>
                                <input type="text" name="weight" placeholder="60" value="{{ $currentAppointment->vitals->weight ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>Waist (cm)</label>
                                <input type="text" name="waist" placeholder="70" value="{{ $currentAppointment->vitals->waist ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>BSA (M)</label>
                                <input type="text" name="bsa" placeholder="1.7" value="{{ $currentAppointment->vitals->bsa ?? '' }}">
                            </div>
                            <div class="vital-item">
                                <label>BMI (kg/cm)</label>
                                <input type="text" name="bmi" placeholder="22.0" value="{{ $currentAppointment->vitals->bmi ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Previous Medical History</h3>
                        <div class="form-group full-width">
                            <label>Clinical Notes</label>
                            <textarea name="clinical_notes" placeholder="Enter clinical notes...">{{ $currentAppointment->clinicalNote->note_text ?? '' }}</textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Skin Allergy</label>
                            <textarea name="skin_allergy" placeholder="Enter any skin allergies...">{{ $currentAppointment->clinicalNote->skin_allergy ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Laboratory Tests</h3>
                        <div class="lab-tests-list space-y-3">
                            @if($currentAppointment->labTests && $currentAppointment->labTests->count() > 0)
                                @foreach($currentAppointment->labTests as $labTest)
                                
                                <!-- Enhanced structure to ensure Test Name and Action are on one line -->
                                <div class="lab-test-item flex justify-between items-center p-3 border border-gray-200 rounded-lg bg-white shadow-sm">
                                    
                                    <span class="lab-test-name font-medium text-gray-700">{{ $labTest->test_name }}</span>
                                    
                                    @if(!empty($labTest->file_path))
                                        <!-- File Exists: Show the VIEW FILE button -->
                                        <a 
                                            href="{{ asset('storage/' . $labTest->file_path) }}" 
                                            target="_blank" 
                                            class="btn btn-sm bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md text-sm transition duration-150"
                                        >
                                            View File
                                        </a>
                                    @else
                                        <!-- File Missing: Show a simple message -->
                                        <span class="text-xs text-red-500 mr-2">No File Uploaded</span>
                                    @endif
                                </div>
                                
                                @endforeach
                            @else
                                <div class="lab-test-item p-3 border border-gray-200 rounded-lg text-center text-gray-500 bg-gray-50">
                                    <span class="lab-test-name">No lab tests recorded for this appointment</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Complaints</h3>
                        <div class="complaints-list">
                            @if(!empty($currentAppointmentDetail->complaints) && is_array($currentAppointmentDetail->complaints))
                                @foreach($currentAppointmentDetail->complaints as $complaint)
                                <div class="complaint-tag">
                                    {{ is_array($complaint) ? $complaint['name'] : $complaint }}
                                </div>
                                @endforeach
                            @else
                                <div class="complaint-tag">
                                    No complaints recorded
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Diagnosis</h3>
                        <div class="diagnosis-list">
                            @if(!empty($currentAppointmentDetail->diagnosis) && is_array($currentAppointmentDetail->diagnosis))
                                @foreach($currentAppointmentDetail->diagnosis as $diagnosis)
                                <div class="diagnosis-tag">
                                    {{ is_array($diagnosis) ? $diagnosis['name'] : $diagnosis }}
                                </div>
                                @endforeach
                            @else
                                <div class="diagnosis-tag">
                                    No diagnosis recorded
                                </div>
                            @endif
                        </div>
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
                                </tr>
                            </thead>
                            <tbody>
                                @if($currentAppointment->medications && $currentAppointment->medications->count() > 0)
                                    @foreach($currentAppointment->medications as $medication)
                                    <tr>
                                        <td>{{ $medication->medication_name ?? 'N/A' }}</td>
                                        <td>{{ $medication->type ?? 'N/A' }}</td>
                                        <td>{{ $medication->dosage ?? 'N/A' }}</td>
                                        <td>{{ $medication->duration ?? 'N/A' }}</td>
                                        <td>{{ $medication->instructions ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No medications recorded</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Advice</h3>
                        <div class="form-group full-width">
                            <textarea name="advice" placeholder="Enter medical advice...">{{ $currentAppointmentDetail->advice ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Follow Up</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Follow Up Date</label>
                                <input type="date" name="follow_up_date" value="{{ $currentAppointmentDetail->follow_up_date ? $currentAppointmentDetail->follow_up_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="form-group">
                                <label>Follow Up Time</label>
                                <input type="time" name="follow_up_time" value="{{ $currentAppointmentDetail->follow_up_time ? $currentAppointmentDetail->follow_up_time->format('H:i') : '' }}">
                            </div>
                        </div>
                        @if($currentAppointmentDetail->follow_up_date)
                        <div class="form-group">
                            <label>Follow Up Status</label>
                            <span class="badge badge-{{ now() > $currentAppointmentDetail->follow_up_date ? 'danger' : 'success' }}">
                                {{ now() > $currentAppointmentDetail->follow_up_date ? 'Overdue' : 'Scheduled' }}
                            </span>
                        </div>
                        @endif
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
    // Appointment navigation data
    const appointments = @json($appointmentsData ?? $completedAppointments);
    let currentIndex = 0;
    
    // Debug: Log the appointments data
    console.log('Appointments data:', appointments);
    
    // Update navigation buttons state
    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const counter = document.getElementById('appointment-counter');
        
        if (prevBtn) prevBtn.disabled = currentIndex === 0;
        if (nextBtn) nextBtn.disabled = currentIndex === appointments.length - 1;
        if (counter) counter.textContent = `Appointment ${currentIndex + 1} of ${appointments.length}`;
    }
    
    // Navigate between appointments
    function navigateAppointment(direction) {
        console.log('Navigating with direction:', direction);
        console.log('Current index:', currentIndex);
        console.log('Appointments length:', appointments.length);
        
        const newIndex = currentIndex + direction;
        
        if (newIndex >= 0 && newIndex < appointments.length) {
            console.log('New index:', newIndex);
            currentIndex = newIndex;
            loadAppointmentData(appointments[currentIndex]);
            updateNavigationButtons();
        } else {
            console.log('Navigation blocked - newIndex out of bounds:', newIndex);
        }
    }
    
    // Load appointment data into the form
    function loadAppointmentData(appointment) {
        console.log('Loading appointment data:', appointment);
        
        // Update appointment header
        const patientName = document.querySelector('.patient-details h3');
        const patientEmail = document.querySelector('.patient-details p:first-child');
        const patientPhone = document.querySelector('.patient-details p:nth-child(2)');
        
        if (patientName) patientName.textContent = appointment.patient_name || 'Unknown Patient';
        if (patientEmail) patientEmail.textContent = appointment.patient_email || '';
        if (patientPhone) patientPhone.textContent = appointment.patient_phone || 'N/A';
        
        // Update appointment details
        const detailCards = document.querySelectorAll('.detail-card-value');
        if (detailCards.length > 0) detailCards[0].textContent = '#APT' + String(appointment.id || '').padStart(5, '0');
        if (detailCards.length > 1) detailCards[1].textContent = appointment.doctor_name || 'N/A';
        if (detailCards.length > 2) detailCards[2].textContent = appointment.appointment_reason || appointment.type || 'General Visit';
        if (detailCards.length > 3) detailCards[3].textContent = (appointment.status || '').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        if (detailCards.length > 4) detailCards[4].textContent = '$' + (appointment.consultation_fee || '200');
        if (detailCards.length > 5) {
            detailCards[5].textContent = appointment.appointment_time ? new Date(appointment.appointment_time).toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit'
            }) : 'N/A';
        }
        if (detailCards.length > 6) detailCards[6].textContent = appointment.clinic_location || 'Adrian\'s Dentistry';
        if (detailCards.length > 7) detailCards[7].textContent = appointment.location || 'Newyork, United States';
        if (detailCards.length > 8) detailCards[8].textContent = appointment.visit_type || 'General';
        // Total visits stays static from server-side rendering
        
        // Update form fields
        const bloodGroup = document.querySelector('input[name="blood_group"]');
        const clinicalNotes = document.querySelector('textarea[name="clinical_notes"]');
        const skinAllergy = document.querySelector('textarea[name="skin_allergy"]');
        const advice = document.querySelector('textarea[name="advice"]');
        const followUpDate = document.querySelector('input[name="follow_up_date"]');
        const followUpTime = document.querySelector('input[name="follow_up_time"]');
        
        if (bloodGroup) bloodGroup.value = appointment.blood_group || '';
        if (clinicalNotes) clinicalNotes.value = appointment.clinical_notes || '';
        if (skinAllergy) skinAllergy.value = appointment.skin_allergy || '';
        if (advice) advice.value = appointment.advice || '';
        if (followUpDate) followUpDate.value = appointment.follow_up_date || '';
        if (followUpTime) followUpTime.value = appointment.follow_up_time || '';
        
        // Update vitals
        const bloodPressure = document.querySelector('input[name="blood_pressure"]');
        const temperature = document.querySelector('input[name="temperature"]');
        const pulse = document.querySelector('input[name="pulse"]');
        const respiratoryRate = document.querySelector('input[name="respiratory_rate"]');
        const spo2 = document.querySelector('input[name="spo2"]');
        const height = document.querySelector('input[name="height"]');
        const weight = document.querySelector('input[name="weight"]');
        const waist = document.querySelector('input[name="waist"]');
        const bsa = document.querySelector('input[name="bsa"]');
        const bmi = document.querySelector('input[name="bmi"]');
        
        if (bloodPressure) bloodPressure.value = appointment.blood_pressure || '';
        if (temperature) temperature.value = appointment.temperature || '';
        if (pulse) pulse.value = appointment.pulse || '';
        if (respiratoryRate) respiratoryRate.value = appointment.respiratory_rate || '';
        if (spo2) spo2.value = appointment.spo2 || '';
        if (height) height.value = appointment.height || '';
        if (weight) weight.value = appointment.weight || '';
        if (waist) waist.value = appointment.waist || '';
        if (bsa) bsa.value = appointment.bsa || '';
        if (bmi) bmi.value = appointment.bmi || '';
        
        // Update tags
        updateTags('.complaints-list:nth-child(2)', appointment.complaints || []); // Complaints section
        updateTags('.diagnosis-list', appointment.diagnosis || []); // Diagnosis section
        
        // Update lab tests with file information
        updateLabTests('.lab-tests-list.space-y-3', appointment.lab_tests || []); // Lab tests section
        
        // Update medications table
        console.log('About to update medications table with:', appointment.medications);
        updateMedicationsTable(appointment.medications || []);
        
        // Update form action
        const form = document.getElementById('appointment-details-form');
        if (form) form.action = '/doctor/appointments/' + (appointment.id || '') + '/save-details';
    }
    
    // Update tags display
    function updateTags(containerSelector, items) {
        const container = document.querySelector(containerSelector);
        if (!container) return;
        
        container.innerHTML = '';
        
        if (Array.isArray(items) && items.length > 0) {
            items.forEach(item => {
                const tag = document.createElement('div');
                tag.className = 'complaint-tag';
                // Check if item is an object (for lab tests) or string (for complaints/diagnosis)
                if (typeof item === 'object' && item.name) {
                    tag.textContent = item.name;
                } else {
                    tag.textContent = item;
                }
                container.appendChild(tag);
            });
        } else {
            const tag = document.createElement('div');
            tag.className = 'complaint-tag';
            tag.textContent = 'No items recorded';
            container.appendChild(tag);
        }
    }
    
    // Update lab tests display with file information
    function updateLabTests(containerSelector, labTests) {
        const container = document.querySelector(containerSelector);
        if (!container) return;
        
        container.innerHTML = '';
        
        if (Array.isArray(labTests) && labTests.length > 0) {
            labTests.forEach(labTest => {
                // Create the lab test item div
                const labTestItem = document.createElement('div');
                labTestItem.className = 'lab-test-item flex justify-between items-center p-3 border border-gray-200 rounded-lg bg-white shadow-sm';
                
                // Create the test name span
                const testNameSpan = document.createElement('span');
                testNameSpan.className = 'lab-test-name font-medium text-gray-700';
                testNameSpan.textContent = labTest.name || 'Unknown Test';
                
                // Create the action element (either View File button or No File message)
                let actionElement;
                if (labTest.file_path) {
                    // File exists: create View File button
                    const viewButton = document.createElement('a');
                    viewButton.href = '/storage/' + labTest.file_path;
                    viewButton.target = '_blank';
                    viewButton.className = 'btn btn-sm bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md text-sm transition duration-150';
                    viewButton.textContent = 'View File';
                    actionElement = viewButton;
                } else {
                    // No file: create simple message
                    const noFileSpan = document.createElement('span');
                    noFileSpan.className = 'text-xs text-red-500 mr-2';
                    noFileSpan.textContent = 'No File Uploaded';
                    actionElement = noFileSpan;
                }
                
                // Append elements to the lab test item
                labTestItem.appendChild(testNameSpan);
                labTestItem.appendChild(actionElement);
                
                // Append the lab test item to the container
                container.appendChild(labTestItem);
            });
        } else {
            // No lab tests: show message
            const noTestsItem = document.createElement('div');
            noTestsItem.className = 'lab-test-item p-3 border border-gray-200 rounded-lg text-center text-gray-500 bg-gray-50';
            noTestsItem.innerHTML = '<span class="lab-test-name">No lab tests recorded for this appointment</span>';
            container.appendChild(noTestsItem);
        }
    }
    
    // Update medications table
    function updateMedicationsTable(medications) {
        console.log('Updating medications table with data:', medications);
        const tbody = document.querySelector('.medication-table tbody');
        if (!tbody) {
            console.log('Medications table tbody not found');
            return;
        }
        
        tbody.innerHTML = '';
        
        if (Array.isArray(medications) && medications.length > 0) {
            console.log('Found medications, creating rows');
            medications.forEach(med => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${med.medication_name || 'N/A'}</td>
                    <td>${med.type || 'N/A'}</td>
                    <td>${med.dosage || 'N/A'}</td>
                    <td>${med.duration || 'N/A'}</td>
                    <td>${med.instructions || 'N/A'}</td>
                `;
                tbody.appendChild(row);
            });
        } else {
            console.log('No medications found, showing default message');
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="5">No medications recorded</td>';
            tbody.appendChild(row);
        }
    }
    
    // Initialize navigation when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Document ready, initializing navigation');
        console.log('Number of appointments:', appointments.length);
        
        // Add event listeners to navigation buttons
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        
        if (prevBtn) {
            console.log('Adding event listener to prev button');
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Prev button clicked');
                navigateAppointment(-1);
            });
        }
        
        if (nextBtn) {
            console.log('Adding event listener to next button');
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Next button clicked');
                navigateAppointment(1);
            });
        }
        
        // Initialize navigation buttons state
        updateNavigationButtons();
    });
</script>
</body>
</html>