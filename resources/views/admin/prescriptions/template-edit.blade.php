<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Edit Prescription Template">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>:: Clinical Pro :: Edit Template</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
<style>
    .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
    
    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    
    .tab-content {
        border: 1px solid #dee2e6;
        border-top: none;
        padding: 20px;
    }
    
    .medication-row {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
    }
    
    .remove-medication {
        cursor: pointer;
        color: #dc3545;
    }
    
    .remove-medication:hover {
        color: #bd2130;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2>Edit Template
                <small>Modify medication template.</small>
                </h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <a href="{{ route('admin.prescriptions.template.view', $template->id) }}" class="btn btn-default mb-3"><i class="zmdi zmdi-arrow-left"></i> Back</a>
                
                <div class="card">
                    <div class="body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        <form id="edit-template-form" action="{{ route('admin.prescriptions.template.update', $template->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs" id="editTemplateTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="basic-info-tab" data-toggle="tab" href="#basic-info" role="tab">Basic Information</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="medications-tab" data-toggle="tab" href="#medications-edit" role="tab">Medications</a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content mt-4" id="editTemplateTabsContent">
                                        <!-- Basic Information Tab -->
                                        <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="template-name"><strong>Template Name*</strong></label>
                                                        <input type="text" class="form-control" id="template-name" name="name" value="{{ old('name', $template->name) }}" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="template-category"><strong>Category*</strong></label>
                                                        <select class="form-control" id="template-category" name="category" required>
                                                            <option value="">Select a category</option>
                                                            @if(isset($categories))
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ old('category', $template->diagnosis) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="template-description"><strong>Description (optional)</strong></label>
                                                        <textarea class="form-control" id="template-description" name="description" rows="4" placeholder="Standard treatment protocol for hypertension management in adults.">{{ old('description', $template->notes) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Medications Tab -->
                                        <div class="tab-pane fade" id="medications-edit" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5><strong>Medications</strong></h5>
                                                    <div id="edit-medications-container">
                                                        @if(is_array(old('medications')) || (isset($template) && is_array($template->medications)))
                                                            @php
                                                                $medications = old('medications', $template->medications ?? []);
                                                            @endphp
                                                            @foreach($medications as $index => $medication)
                                                                <div class="medication-row">
                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <h6 class="mb-0">Medication #{{ $index + 1 }}</h6>
                                                                        @if($index > 0)
                                                                            <span class="remove-medication" onclick="removeMedication(this)"><i class="zmdi zmdi-delete"></i></span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Medication Name*</label>
                                                                                <input type="text" class="form-control" name="medications[{{ $index }}][name]" value="{{ old('medications.' . $index . '.name', $medication['name'] ?? $medication['drug_name'] ?? '') }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Dosage*</label>
                                                                                <input type="text" class="form-control" name="medications[{{ $index }}][dosage]" value="{{ old('medications.' . $index . '.dosage', $medication['dosage'] ?? '') }}" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Route</label>
                                                                                <select class="form-control" name="medications[{{ $index }}][route]">
                                                                                    <option value="oral" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'oral') ? 'selected' : '' }}>Oral</option>
                                                                                    <option value="intravenous" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'intravenous') ? 'selected' : '' }}>Intravenous</option>
                                                                                    <option value="intramuscular" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'intramuscular') ? 'selected' : '' }}>Intramuscular</option>
                                                                                    <option value="subcutaneous" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'subcutaneous') ? 'selected' : '' }}>Subcutaneous</option>
                                                                                    <option value="topical" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'topical') ? 'selected' : '' }}>Topical</option>
                                                                                    <option value="inhalation" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'inhalation') ? 'selected' : '' }}>Inhalation</option>
                                                                                    <option value="rectal" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'rectal') ? 'selected' : '' }}>Rectal</option>
                                                                                    <option value="vaginal" {{ (old('medications.' . $index . '.route', $medication['route'] ?? '') === 'vaginal') ? 'selected' : '' }}>Vaginal</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Frequency*</label>
                                                                                <input type="text" class="form-control" name="medications[{{ $index }}][frequency]" value="{{ old('medications.' . $index . '.frequency', $medication['frequency'] ?? '') }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Duration</label>
                                                                                <input type="text" class="form-control" name="medications[{{ $index }}][duration]" value="{{ old('medications.' . $index . '.duration', $medication['duration'] ?? '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Instructions (optional)</label>
                                                                                <input type="text" class="form-control" name="medications[{{ $index }}][instructions]" value="{{ old('medications.' . $index . '.instructions', $medication['instructions'] ?? '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="medication-row">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <h6 class="mb-0">Medication #1</h6>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Medication Name*</label>
                                                                            <input type="text" class="form-control" name="medications[0][name]" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Dosage*</label>
                                                                            <input type="text" class="form-control" name="medications[0][dosage]" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Route</label>
                                                                            <select class="form-control" name="medications[0][route]">
                                                                                <option value="oral">Oral</option>
                                                                                <option value="intravenous">Intravenous</option>
                                                                                <option value="intramuscular">Intramuscular</option>
                                                                                <option value="subcutaneous">Subcutaneous</option>
                                                                                <option value="topical">Topical</option>
                                                                                <option value="inhalation">Inhalation</option>
                                                                                <option value="rectal">Rectal</option>
                                                                                <option value="vaginal">Vaginal</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Frequency*</label>
                                                                            <input type="text" class="form-control" name="medications[0][frequency]" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Duration</label>
                                                                            <input type="text" class="form-control" name="medications[0][duration]">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Instructions (optional)</label>
                                                                            <input type="text" class="form-control" name="medications[0][instructions]">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <button type="button" class="btn btn-success" id="add-edit-medication">
                                                        <i class="zmdi zmdi-plus"></i> Add Medication
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('admin.prescriptions.template.view', $template->id) }}" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
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
    // Medication management
    let medicationIndex = {{ is_array(old('medications', $template->medications ?? [])) ? count(old('medications', $template->medications ?? [])) : 1 }};
    
    // Function to add a medication field
    function addEditMedicationField() {
        const container = document.getElementById('edit-medications-container');
        const medicationDiv = document.createElement('div');
        medicationDiv.className = 'medication-row';
        medicationDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Medication #${medicationIndex + 1}</h6>
                <span class="remove-medication" onclick="removeMedication(this)"><i class="zmdi zmdi-delete"></i></span>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Medication Name*</label>
                        <input type="text" class="form-control" name="medications[${medicationIndex}][name]" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dosage*</label>
                        <input type="text" class="form-control" name="medications[${medicationIndex}][dosage]" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Route</label>
                        <select class="form-control" name="medications[${medicationIndex}][route]">
                            <option value="oral">Oral</option>
                            <option value="intravenous">Intravenous</option>
                            <option value="intramuscular">Intramuscular</option>
                            <option value="subcutaneous">Subcutaneous</option>
                            <option value="topical">Topical</option>
                            <option value="inhalation">Inhalation</option>
                            <option value="rectal">Rectal</option>
                            <option value="vaginal">Vaginal</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Frequency*</label>
                        <input type="text" class="form-control" name="medications[${medicationIndex}][frequency]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" class="form-control" name="medications[${medicationIndex}][duration]">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Instructions (optional)</label>
                        <input type="text" class="form-control" name="medications[${medicationIndex}][instructions]">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(medicationDiv);
        medicationIndex++;
    }
    
    // Function to remove medication field
    function removeMedication(element) {
        if (element && element.closest('.medication-row')) {
            element.closest('.medication-row').remove();
        }
    }
    
    // Add event listener for add medication button
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('add-edit-medication')) {
            document.getElementById('add-edit-medication').addEventListener('click', function() {
                addEditMedicationField();
            });
        }
    });
</script>
</body>
</html>