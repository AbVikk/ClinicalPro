<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Create Prescription">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>:: Clinical Pro :: Create Prescription</title>
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
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2>Create Prescription
                <small>Create a new patient prescription.</small>
                </h2>
            </div>            
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Main Prescription Form (md-8) -->
            <div class="col-lg-8 col-md-8">
                <div class="card" id="prescription_form" style="display: none;">
                    <div class="header">
                        <h2><strong>Prescription</strong> Details</h2>
                        <p>Enter the details for the new prescription.</p>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{ route('admin.prescriptions.store') }}" id="prescription-form">
                            @csrf
                            <input type="hidden" id="patient_id" name="patient_id" value="">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prescription_date">Prescription Date</label>
                                        <input type="date" class="form-control" id="prescription_date" name="prescription_date" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prescription_type">Prescription Type</label>
                                        <select class="form-control" id="prescription_type" name="prescription_type">
                                            <option value="acute">Acute</option>
                                            <option value="chronic">Chronic</option>
                                            <option value="repeat">Repeat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="diagnosis">Diagnosis</label>
                                <input type="text" class="form-control" id="diagnosis" name="diagnosis" placeholder="Enter diagnosis" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="medication_template">Use Medication Template</label>
                                <select class="form-control" id="medication_template" name="medication_template">
                                    <option value="">Select a template</option>
                                    <option value="hypertension">Hypertension Template</option>
                                    <option value="diabetes">Diabetes Template</option>
                                </select>
                            </div>
                            
                            <h5>Medications</h5>
                            
                            <!-- Medication #1 -->
                            <div class="card mb-3">
                                <div class="body">
                                    <h6>Medication #1</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med1_name">Medication Name</label>
                                                <select class="form-control" id="med1_name" name="medications[0][name]">
                                                    <option value="">Select Medication</option>
                                                    @foreach($drugs as $drug)
                                                        <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med1_dosage">Dosage</label>
                                                <input type="text" class="form-control" id="med1_dosage" name="medications[0][dosage]" placeholder="e.g., 500mg">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med1_route">Route</label>
                                                <select class="form-control" id="med1_route" name="medications[0][route]">
                                                    <option value="oral">Oral</option>
                                                    <option value="intravenous">Intravenous</option>
                                                    <option value="topical">Topical</option>
                                                    <option value="subcutaneous">Subcutaneous</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med1_frequency">Frequency</label>
                                                <input type="text" class="form-control" id="med1_frequency" name="medications[0][frequency]" placeholder="e.g., Twice daily">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med1_duration">Duration</label>
                                                <input type="text" class="form-control" id="med1_duration" name="medications[0][duration]" placeholder="e.g., 30 days">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med1_instructions">Special Instructions</label>
                                                <input type="text" class="form-control" id="med1_instructions" name="medications[0][instructions]" placeholder="e.g., Take with food">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fancy-checkbox">
                                                    <input type="checkbox" id="med1_allow_refills" name="medications[0][allow_refills]" value="1">
                                                    <span>Allow Refills</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="med1_refills_group" style="display: none;">
                                                <label for="med1_refills">Number of Refills:</label>
                                                <input type="number" class="form-control" id="med1_refills" name="medications[0][refills]" min="0" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Medication #2 -->
                            <div class="card mb-3">
                                <div class="body">
                                    <h6>Medication #2</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med2_name">Medication Name</label>
                                                <select class="form-control" id="med2_name" name="medications[1][name]">
                                                    <option value="">Select Medication</option>
                                                    @foreach($drugs as $drug)
                                                        <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med2_dosage">Dosage</label>
                                                <input type="text" class="form-control" id="med2_dosage" name="medications[1][dosage]" placeholder="e.g., 500mg">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med2_route">Route</label>
                                                <select class="form-control" id="med2_route" name="medications[1][route]">
                                                    <option value="oral">Oral</option>
                                                    <option value="intravenous">Intravenous</option>
                                                    <option value="topical">Topical</option>
                                                    <option value="subcutaneous">Subcutaneous</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med2_frequency">Frequency</label>
                                                <input type="text" class="form-control" id="med2_frequency" name="medications[1][frequency]" placeholder="e.g., Twice daily">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med2_duration">Duration</label>
                                                <input type="text" class="form-control" id="med2_duration" name="medications[1][duration]" placeholder="e.g., 30 days">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="med2_instructions">Special Instructions</label>
                                                <input type="text" class="form-control" id="med2_instructions" name="medications[1][instructions]" value="Take with meals to reduce gastrointestinal side effects.">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fancy-checkbox">
                                                    <input type="checkbox" id="med2_allow_refills" name="medications[1][allow_refills]" value="1">
                                                    <span>Allow Refills</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="med2_refills_group" style="display: none;">
                                                <label for="med2_refills">Number of Refills:</label>
                                                <input type="number" class="form-control" id="med2_refills" name="medications[1][refills]" min="0" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="add-medication" class="btn btn-success mb-3">Add More Medications</button>
                            
                            <div id="additional-medications"></div>
                            
                            <h5 id="additional-info-header">Additional Information</h5>
                            
                            <div class="form-group">
                                <label for="pharmacist_notes">Notes for Pharmacist</label>
                                <textarea class="form-control" id="pharmacist_notes" name="pharmacist_notes" rows="3" placeholder="Enter any notes for the pharmacist"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="save_as_template" name="save_as_template" value="1">
                                    <span>Save as Template</span>
                                </label>
                            </div>
                            
                            <div class="form-group" id="template_name_group" style="display: none;">
                                <label for="template_name">Template Name</label>
                                <input type="text" class="form-control" id="template_name" name="template_name" placeholder="Enter template name">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Create Prescription</button>
                            <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar (md-4) -->
            <div class="col-lg-4 col-md-4">
                <!-- Patient Information -->
                <div class="card">
                    <div class="header">
                        <h2><strong>Patient</strong> Information</h2>
                        <p>Select a patient for this prescription.</p>
                    </div>
                    <div class="body">
                        <!-- Search input for patient name or ID -->
                        <div class="form-group">
                            <label for="patient_search">Search Patient (by name or ID)</label>
                            <input type="text" class="form-control" id="patient_search" placeholder="Enter patient name or ID">
                        </div>
                        
                        <!-- Existing select dropdown -->
                        <div class="form-group">
                            <label for="patient_select">Select Patient</label>
                            <select class="form-control" id="patient_select" name="patient">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="patient_details" style="display: none;">
                            <div class="text-center">
                                <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle patient-image" alt="Patient" width="80" height="80" id="patient_photo">
                                <h5 class="mt-2" id="patient_name">John Smith</h5>
                                <p class="patient-info" id="patient_info">45 • Male • DOB: 1978-05-15</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Allergies:</h6>
                                    <ul class="list-unstyled" id="patient_allergies">
                                        <!-- Allergies will be populated dynamically -->
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Conditions:</h6>
                                    <ul class="list-unstyled" id="patient_conditions">
                                        <!-- Conditions will be populated dynamically -->
                                    </ul>
                                </div>
                            </div>
                            
                            <a href="#" id="view_patient_details" class="btn btn-sm btn-primary btn-block">View Patient Details</a>
                        </div>
                        
                        <button class="btn btn-sm btn-secondary btn-block mt-3">Search Patient</button>
                    </div>
                </div>
                
                <!-- Prescription History -->
                <div class="card" id="prescription_history_card" style="display: none;">
                    <div class="header">
                        <h2><strong>Prescription</strong> History</h2>
                        <p>Recent prescriptions for this patient.</p>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled" id="prescription_history">
                            <!-- Prescription history will be populated dynamically -->
                        </ul>
                        <a href="#" id="view_all_prescriptions" class="btn btn-sm btn-primary btn-block">View all prescriptions</a>
                    </div>
                </div>
                
                <!-- Prescription Options -->
                <div class="card" id="prescription_options_card" style="display: none;">
                    <div class="header">
                        <h2><strong>Prescription</strong> Options</h2>
                    </div>
                    <div class="body">
                        <h6>Prescription Format</h6>
                        <div class="form-group">
                            <div class="fancy-radio">
                                <label><input name="prescription_format" value="electronic" type="radio" checked><span><i></i>Electronic Prescription</span></label>
                            </div>
                            <div class="fancy-radio">
                                <label><input name="prescription_format" value="print" type="radio"><span><i></i>Print Prescription</span></label>
                            </div>
                            <div class="fancy-radio">
                                <label><input name="prescription_format" value="both" type="radio"><span><i></i>Both Electronic and Print</span></label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" id="notify_patient" name="notify_patient" value="1">
                                <span>Notify Patient</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" id="mark_urgent" name="mark_urgent" value="1">
                                <span>Mark as Urgent</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
    // Counter for medication items
    let medicationCounter = 2;
    
    // Add more medications functionality
    document.getElementById('add-medication').addEventListener('click', function() {
        medicationCounter++;
        
        const medicationHtml = `
        <!-- Medication #${medicationCounter} -->
        <div class="card mb-3 medication-item" id="medication-${medicationCounter}">
            <div class="body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6>Medication #${medicationCounter}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-medication" data-id="${medicationCounter}">Remove</button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="med${medicationCounter}_name">Medication Name</label>
                            <select class="form-control" id="med${medicationCounter}_name" name="medications[${medicationCounter-1}][name]">
                                <option value="">Select Medication</option>
                                @foreach($drugs as $drug)
                                    <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="med${medicationCounter}_dosage">Dosage</label>
                            <input type="text" class="form-control" id="med${medicationCounter}_dosage" name="medications[${medicationCounter-1}][dosage]" placeholder="e.g., 500mg">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="med${medicationCounter}_route">Route</label>
                            <select class="form-control" id="med${medicationCounter}_route" name="medications[${medicationCounter-1}][route]">
                                <option value="oral">Oral</option>
                                <option value="intravenous">Intravenous</option>
                                <option value="topical">Topical</option>
                                <option value="subcutaneous">Subcutaneous</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="med${medicationCounter}_frequency">Frequency</label>
                            <input type="text" class="form-control" id="med${medicationCounter}_frequency" name="medications[${medicationCounter-1}][frequency]" placeholder="e.g., Twice daily">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="med${medicationCounter}_duration">Duration</label>
                            <input type="text" class="form-control" id="med${medicationCounter}_duration" name="medications[${medicationCounter-1}][duration]" placeholder="e.g., 30 days">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="med${medicationCounter}_instructions">Special Instructions</label>
                            <input type="text" class="form-control" id="med${medicationCounter}_instructions" name="medications[${medicationCounter-1}][instructions]" placeholder="e.g., Take with food">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" id="med${medicationCounter}_allow_refills" name="medications[${medicationCounter-1}][allow_refills]" value="1">
                                <span>Allow Refills</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="med${medicationCounter}_refills_group" style="display: none;">
                            <label for="med${medicationCounter}_refills">Number of Refills:</label>
                            <input type="number" class="form-control" id="med${medicationCounter}_refills" name="medications[${medicationCounter-1}][refills]" min="0" value="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        
        // Insert in the additional medications container
        const container = document.getElementById('additional-medications');
        container.insertAdjacentHTML('beforeend', medicationHtml);
        
        // Add event listener for the new refills checkbox
        document.getElementById(`med${medicationCounter}_allow_refills`).addEventListener('change', function() {
            document.getElementById(`med${medicationCounter}_refills_group`).style.display = this.checked ? 'block' : 'none';
        });
        
        // Add event listener for the remove button
        document.querySelector(`.remove-medication[data-id="${medicationCounter}"]`).addEventListener('click', function() {
            document.getElementById(`medication-${medicationCounter}`).remove();
        });
    });
    
    // Show/hide refills input when allow refills is checked
    document.getElementById('med1_allow_refills').addEventListener('change', function() {
        document.getElementById('med1_refills_group').style.display = this.checked ? 'block' : 'none';
    });
    
    document.getElementById('med2_allow_refills').addEventListener('change', function() {
        document.getElementById('med2_refills_group').style.display = this.checked ? 'block' : 'none';
    });
    
    // Show/hide template name input when save as template is checked
    document.getElementById('save_as_template').addEventListener('change', function() {
        document.getElementById('template_name_group').style.display = this.checked ? 'block' : 'none';
    });
    
    // Handle patient search input with live search
    let searchTimeout;
    document.getElementById('patient_search').addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        // Clear previous timeout to debounce the search
        clearTimeout(searchTimeout);
        
        // If search term is empty, reset the select dropdown
        if (searchTerm === '') {
            document.getElementById('patient_select').value = '';
            // Hide all cards except patient information
            document.getElementById('prescription_form').style.display = 'none';
            document.getElementById('prescription_history_card').style.display = 'none';
            document.getElementById('prescription_options_card').style.display = 'none';
            document.getElementById('patient_details').style.display = 'none';
            return;
        }
        
        // Debounce the search to avoid too many requests
        searchTimeout = setTimeout(() => {
            // Make AJAX request to search patients
            fetch("{{ route('admin.prescriptions.search-patients') }}?search=" + encodeURIComponent(searchTerm))
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('patient_select');
                    
                    // Clear existing options except the first one
                    while (select.options.length > 1) {
                        select.remove(1);
                    }
                    
                    // Add search results as options
                    if (data.patients.length > 0) {
                        data.patients.forEach(patient => {
                            const option = document.createElement('option');
                            option.value = patient.id;
                            option.text = patient.name + ' (' + patient.user_id + ')';
                            select.appendChild(option);
                        });
                    } else {
                        // Add a "No results" option
                        const option = document.createElement('option');
                        option.value = '';
                        option.text = 'No patients found';
                        option.disabled = true;
                        select.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error searching patients:', error);
                });
        }, 300); // 300ms debounce
    });
    
    // Show patient details when a patient is selected and fetch real-time data
    document.getElementById('patient_select').addEventListener('change', function() {
        const patientId = this.value;
        document.getElementById('patient_id').value = patientId;
        
        if (patientId) {
            // Update the "View Patient Details" link
            document.getElementById('view_patient_details').href = '/admin/patient/' + patientId;
            
            // Update the "View all prescriptions" link
            document.getElementById('view_all_prescriptions').href = '/admin/prescriptions?patient_id=' + patientId;
            
            // Show the prescription form and other cards
            document.getElementById('prescription_form').style.display = 'block';
            document.getElementById('prescription_history_card').style.display = 'block';
            document.getElementById('prescription_options_card').style.display = 'block';
            
            // Fetch patient details via AJAX
            fetch("{{ route('admin.prescriptions.patient-details') }}?patient_id=" + patientId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }
                    
                    // Update patient photo (assuming the patient has a photo field)
                    const patientPhoto = document.getElementById('patient_photo');
                    if (data.patient.photo) {
                        patientPhoto.src = '/storage/' + data.patient.photo; // Assuming photos are stored in storage
                    } else {
                        // Use default photo if none exists
                        patientPhoto.src = "{{ asset('assets/images/xs/avatar1.jpg') }}";
                    }
                    
                    // Update patient information
                    document.getElementById('patient_name').textContent = data.patient.name;
                    const dob = new Date(data.patient.date_of_birth);
                    const age = Math.floor((new Date() - dob) / (365.25 * 24 * 60 * 60 * 1000));
                    const gender = data.patient.gender === 'male' ? 'Male' : 'Female';
                    document.getElementById('patient_info').textContent = age + " • " + gender + " • DOB: " + dob.toISOString().split('T')[0];
                    
                    // Update allergies
                    const allergiesContainer = document.getElementById('patient_allergies');
                    allergiesContainer.innerHTML = '';
                    if (data.allergies && data.allergies.length > 0) {
                        data.allergies.forEach(allergy => {
                            const li = document.createElement('li');
                            li.innerHTML = '<span class="badge badge-danger">' + allergy + '</span>';
                            allergiesContainer.appendChild(li);
                        });
                    } else {
                        allergiesContainer.innerHTML = '<li><span class="text-muted">No known allergies</span></li>';
                    }
                    
                    // Update conditions
                    const conditionsContainer = document.getElementById('patient_conditions');
                    conditionsContainer.innerHTML = '';
                    if (data.conditions && data.conditions.length > 0) {
                        data.conditions.forEach(condition => {
                            const li = document.createElement('li');
                            li.innerHTML = '<span class="badge badge-info">' + condition + '</span>';
                            conditionsContainer.appendChild(li);
                        });
                    } else {
                        conditionsContainer.innerHTML = '<li><span class="text-muted">No known conditions</span></li>';
                    }
                    
                    // Update prescription history
                    const historyContainer = document.getElementById('prescription_history');
                    historyContainer.innerHTML = '';
                    if (data.recent_prescriptions.length > 0) {
                        data.recent_prescriptions.forEach(prescription => {
                            prescription.items.forEach(item => {
                                const li = document.createElement('li');
                                li.className = 'mb-3';
                                // Parse the dosage instructions JSON
                                const dosageInstructions = JSON.parse(item.dosage_instructions);
                                li.innerHTML = `
                                    <h6>${item.drug.name} ${dosageInstructions.dosage}</h6>
                                    <p class="text-muted">${dosageInstructions.frequency} • ${dosageInstructions.duration}</p>
                                `;
                                // Format the date properly
                                const prescriptionDate = new Date(prescription.created_at);
                                li.innerHTML += `<p class="text-muted">Prescribed on: ${prescriptionDate.toLocaleDateString()}</p>`;
                                historyContainer.appendChild(li);
                            });
                        });
                    } else {
                        historyContainer.innerHTML = '<li class="mb-3"><p class="text-muted">No prescription history found.</p></li>';
                    }
                    
                    // Show patient details
                    document.getElementById('patient_details').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching patient details:', error);
                });
        } else {
            // Hide all cards except patient information
            document.getElementById('prescription_form').style.display = 'none';
            document.getElementById('prescription_history_card').style.display = 'none';
            document.getElementById('prescription_options_card').style.display = 'none';
            document.getElementById('patient_details').style.display = 'none';
        }
    });
    
    // Handle form submission with error handling
    const prescriptionForm = document.getElementById('prescription-form');
    if (prescriptionForm) {
        prescriptionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate that a patient is selected
            const patientId = document.getElementById('patient_id').value;
            if (!patientId) {
                alert('Please select a patient before creating a prescription.');
                return;
            }
            
            // Validate that at least one medication is filled
            const medicationRows = document.querySelectorAll('.card.mb-3, .medication-item');
            let hasValidMedication = false;
            
            medicationRows.forEach(row => {
                const medicationName = row.querySelector('select[name*="[name]"]');
                const dosage = row.querySelector('input[name*="[dosage]"]');
                
                // Check if this is a valid medication entry (has name and dosage)
                if (medicationName && dosage && medicationName.value && dosage.value) {
                    hasValidMedication = true;
                }
            });
            
            if (!hasValidMedication) {
                alert('Please add at least one valid medication with name and dosage.');
                return;
            }
            
            // Remove empty medication fields before submission
            medicationRows.forEach(row => {
                const medicationName = row.querySelector('select[name*="[name]"]');
                const dosage = row.querySelector('input[name*="[dosage]"]');
                
                // If medication name is empty, remove the entire medication entry
                if (medicationName && !medicationName.value) {
                    row.remove();
                }
            });
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = 'Creating...';
            submitButton.disabled = true;
            
            // Get form data
            const formData = new FormData(this);
            
            // Submit form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                // Check if response is redirect
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.indexOf('application/json') !== -1) {
                    return response.json();
                } else {
                    // If not JSON, assume it's HTML and redirect
                    window.location.href = "{{ route('admin.prescriptions.index') }}";
                    return;
                }
            })
            .then(data => {
                if (data && data.success) {
                    // Redirect to prescriptions index
                    window.location.href = "{{ route('admin.prescriptions.index') }}";
                } else if (data && data.error) {
                    // Show error message
                    alert('Error creating prescription: ' + data.error);
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                } else if (data && data.errors) {
                    // Handle validation errors
                    let errorMessages = 'Validation errors:\n';
                    for (const field in data.errors) {
                        errorMessages += field + ': ' + data.errors[field].join(', ') + '\n';
                    }
                    alert(errorMessages);
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                } else {
                    // Handle unexpected response
                    alert('Prescription created successfully.');
                    window.location.href = "{{ route('admin.prescriptions.index') }}";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error occurred. Please try again.');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }
    
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