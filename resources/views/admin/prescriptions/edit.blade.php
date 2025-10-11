<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Edit Prescription">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>:: Clinical Pro :: Edit Prescription</title>
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
        padding: 15px 0;
    }
    
    .medication-row:last-child {
        border-bottom: none;
    }
    
    .section-title {
        border-left: 4px solid #007bff;
        padding-left: 10px;
        margin-bottom: 20px;
    }
    
    .remove-medication {
        color: #dc3545;
        cursor: pointer;
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

<!-- Validation Errors -->
@if ($errors->any())
<div class="alert alert-danger alert-position alert-dismissible fade show" role="alert">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
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
                <h2>Edit Prescription
                <small>Modify prescription details and medications.</small>
                </h2>
            </div>            
            <div class="col-lg-4 col-md-4 col-sm-12 text-right">
                <a href="{{ route('admin.prescriptions.show', $prescription->id) }}" class="btn btn-secondary btn-round">Back to Prescription</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.prescriptions.update', $prescription->id) }}">
            @csrf
            @method('PUT')
            <div class="row clearfix">
                <!-- Main Prescription Edit Form (md-8) -->
                <div class="col-lg-8 col-md-8">
                    <div class="card prescription-card">
                        <div class="prescription-header">
                            <h2><strong>Prescription</strong> Information</h2>
                            <p>Edit the prescription details</p>
                        </div>
                        <div class="prescription-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">Prescribing Doctor</label>
                                        <select class="form-control" id="doctor_id" name="doctor_id" required>
                                            <option value="">Select Doctor</option>
                                            @foreach(App\Models\User::where('role', 'doctor')->get() as $doctor)
                                                <option value="{{ $doctor->id }}" {{ $prescription->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="active" {{ $prescription->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="filled" {{ $prescription->status == 'filled' ? 'selected' : '' }}>Filled</option>
                                            <option value="expired" {{ $prescription->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                            <option value="cancelled" {{ $prescription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="created_at">Issue Date</label>
                                        <input type="date" class="form-control" id="created_at" name="created_at" value="{{ $prescription->created_at->format('Y-m-d') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ $prescription->created_at->addMonths(3)->format('Y-m-d') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="diagnosis">Diagnosis</label>
                                <input type="text" class="form-control" id="diagnosis" name="diagnosis" value="{{ $prescription->notes ?? '' }}" placeholder="Enter diagnosis">
                            </div>
                            
                            <div class="form-group">
                                <label for="refills_allowed">Refills</label>
                                <input type="number" class="form-control" id="refills_allowed" name="refills_allowed" value="{{ $prescription->refills_allowed ?? 0 }}" min="0">
                            </div>
                            
                            <div class="section-title">
                                <h4><strong>Medications</strong></h4>
                            </div>
                            
                            <div id="medications-container">
                                @foreach($prescription->items as $index => $item)
                                    @php
                                        $dosageInstructions = json_decode($item->dosage_instructions, true);
                                    @endphp
                                    <div class="medication-row" data-medication-index="{{ $index }}">
                                        <input type="hidden" name="medications[{{ $index }}][id]" value="{{ $item->id }}">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Medication {{ $index + 1 }} <span class="remove-medication float-right" data-medication-index="{{ $index }}">Remove</span></h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="medications_{{ $index }}_drug_id">Medication Name</label>
                                                    <select class="form-control" id="medications_{{ $index }}_drug_id" name="medications[{{ $index }}][drug_id]" required>
                                                        <option value="">Select Medication</option>
                                                        @foreach($drugs as $drug)
                                                            <option value="{{ $drug->id }}" {{ $item->drug_id == $drug->id ? 'selected' : '' }}>
                                                                {{ $drug->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="medications_{{ $index }}_dosage">Dosage</label>
                                                    <input type="text" class="form-control" id="medications_{{ $index }}_dosage" name="medications[{{ $index }}][dosage]" value="{{ $dosageInstructions['dosage'] ?? '' }}" placeholder="e.g., 500mg" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="medications_{{ $index }}_frequency">Frequency</label>
                                                    <input type="text" class="form-control" id="medications_{{ $index }}_frequency" name="medications[{{ $index }}][frequency]" value="{{ $dosageInstructions['frequency'] ?? '' }}" placeholder="e.g., Twice daily" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="medications_{{ $index }}_duration">Duration</label>
                                                    <input type="text" class="form-control" id="medications_{{ $index }}_duration" name="medications[{{ $index }}][duration]" value="{{ $dosageInstructions['duration'] ?? '' }}" placeholder="e.g., 30 days" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="medications_{{ $index }}_instructions">Notes</label>
                                                    <input type="text" class="form-control" id="medications_{{ $index }}_instructions" name="medications[{{ $index }}][instructions]" value="{{ $dosageInstructions['instructions'] ?? '' }}" placeholder="Enter special instructions">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="add-medication" class="btn btn-success mb-3">Add More Medications</button>
                            
                            <div class="section-title">
                                <h4><strong>Prescription Notes</strong></h4>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter prescription notes">{{ $prescription->notes ?? '' }}</textarea>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('admin.prescriptions.show', $prescription->id) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Information (md-4) -->
                <div class="col-lg-4 col-md-4">
                    <div class="card prescription-card">
                        <div class="prescription-header">
                            <h2><strong>Patient</strong> Information</h2>
                        </div>
                        <div class="prescription-body text-center">
                            <div class="profile-image mb-3"> 
                                <img src="{{ $prescription->patient->photo ? asset('storage/' . $prescription->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Patient Photo" width="100" height="100">
                            </div>
                            <h4>{{ $prescription->patient->name ?? 'No records' }}</h4>
                            <p class="text-muted">
                                ID: {{ $prescription->patient->user_id ?? 'No records' }}<br>
                                DOB: {{ $prescription->patient->date_of_birth ? $prescription->patient->date_of_birth->format('Y-m-d') : 'No records' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
        
        // Handle form submission with validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Check if at least one medication is filled
                const medicationRows = document.querySelectorAll('.medication-row');
                let hasValidMedication = false;
                
                medicationRows.forEach(row => {
                    const drugSelect = row.querySelector('select[name*="[drug_id]"]');
                    const dosageInput = row.querySelector('input[name*="[dosage]"]');
                    
                    if (drugSelect && dosageInput && drugSelect.value && dosageInput.value) {
                        hasValidMedication = true;
                    }
                });
                
                if (!hasValidMedication) {
                    e.preventDefault();
                    alert('Please add at least one valid medication with name and dosage.');
                    return;
                }
                
                // Show loading state
                const submitButton = document.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = 'Saving...';
                submitButton.disabled = true;
            });
        }
        
        // Handle adding new medications
        let medicationCounter = {{ $prescription->items->count() }};
        document.getElementById('add-medication').addEventListener('click', function() {
            medicationCounter++;
            const medicationsContainer = document.getElementById('medications-container');
            const medicationHtml = `
                <div class="medication-row" data-medication-index="${medicationCounter}">
                    <input type="hidden" name="medications[${medicationCounter}][id]" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Medication ${medicationCounter} <span class="remove-medication float-right" data-medication-index="${medicationCounter}">Remove</span></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medications_${medicationCounter}_drug_id">Medication Name</label>
                                <select class="form-control" id="medications_${medicationCounter}_drug_id" name="medications[${medicationCounter}][drug_id]" required>
                                    <option value="">Select Medication</option>
                                    @foreach($drugs as $drug)
                                        <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medications_${medicationCounter}_dosage">Dosage</label>
                                <input type="text" class="form-control" id="medications_${medicationCounter}_dosage" name="medications[${medicationCounter}][dosage]" placeholder="e.g., 500mg" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medications_${medicationCounter}_frequency">Frequency</label>
                                <input type="text" class="form-control" id="medications_${medicationCounter}_frequency" name="medications[${medicationCounter}][frequency]" placeholder="e.g., Twice daily" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medications_${medicationCounter}_duration">Duration</label>
                                <input type="text" class="form-control" id="medications_${medicationCounter}_duration" name="medications[${medicationCounter}][duration]" placeholder="e.g., 30 days" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="medications_${medicationCounter}_instructions">Notes</label>
                                <input type="text" class="form-control" id="medications_${medicationCounter}_instructions" name="medications[${medicationCounter}][instructions]" placeholder="Enter special instructions">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            medicationsContainer.insertAdjacentHTML('beforeend', medicationHtml);
            
            // Add event listener to the new remove button
            const newRemoveButton = document.querySelector(`.remove-medication[data-medication-index="${medicationCounter}"]`);
            if (newRemoveButton) {
                newRemoveButton.addEventListener('click', function() {
                    const index = this.getAttribute('data-medication-index');
                    const medicationRow = document.querySelector(`.medication-row[data-medication-index="${index}"]`);
                    if (medicationRow) {
                        medicationRow.remove();
                    }
                });
            }
        });
        
        // Handle removing medications for existing items
        document.querySelectorAll('.remove-medication').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-medication-index');
                const medicationRow = document.querySelector(`.medication-row[data-medication-index="${index}"]`);
                if (medicationRow) {
                    medicationRow.remove();
                }
            });
        });
    });
</script>
</body>
</html>