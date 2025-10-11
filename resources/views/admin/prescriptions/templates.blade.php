<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Prescription Templates">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>:: Clinical Pro :: Prescription Templates</title>
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
    
    .template-details {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .medication-card {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .usage-stat {
        text-align: center;
        padding: 15px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    
    .usage-stat .number {
        font-size: 2rem;
        font-weight: bold;
        color: #007bff;
    }
    
    .usage-stat .label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .template-table th {
        background-color: #e9ecef;
        font-weight: 600;
    }
    
    .template-table td, .template-table th {
        vertical-align: middle;
    }
    
    .medication-info p {
        margin-bottom: 5px;
    }
    
    .medication-info strong {
        display: block;
        margin-bottom: 2px;
    }
    
    .loading {
        text-align: center;
        padding: 20px;
    }
    
    .search-container {
        position: relative;
        max-width: 300px;
        margin-left: auto;
    }
    
    .search-container input {
        padding-right: 30px;
    }
    
    .search-container .search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
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
                <h2>Medicine Templates
                <small>Manage prescription templates for common medications.</small>
                </h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Templates Tabs -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h2><strong>Prescription</strong> Templates</h2>
                            <p>View and manage prescription templates for common conditions.</p>
                        </div>
                        <div class="d-flex align-items-center mt-2 mt-md-0">
                            <div class="search-container mr-2" style="width: 200px;">
                                <input type="text" id="template-search" class="form-control" placeholder="Search templates...">
                                <span class="search-icon"><i class="zmdi zmdi-search"></i></span>
                            </div>
                            <button class="btn btn-primary btn-round" id="new-template-btn" data-toggle="modal" data-target="#createTemplateModal">
                                <i class="zmdi zmdi-plus"></i> New Template
                            </button>
                        </div>
                    </div>
                    <div class="body">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs" id="templateTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="all-templates-tab" data-toggle="tab" href="#all-templates" role="tab">All Templates</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="recently-used-tab" data-toggle="tab" href="#recently-used" role="tab">Recently Used</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="my-templates-tab" data-toggle="tab" href="#my-templates" role="tab">My Templates</a>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="templateTabsContent">
                            <!-- All Templates Tab -->
                            <div class="tab-pane fade show active" id="all-templates" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover template-table">
                                        <thead>
                                            <tr>
                                                <th>Template Name</th>
                                                <th>Category</th>
                                                <th>Medications</th>
                                                <th>Created By</th>
                                                <th>Last Used</th>
                                                <th>Usage</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="all-templates-table">
                                            @forelse($allTemplates as $template)
                                            <tr>
                                                <td>{{ $template->name }}</td>
                                                <td>{{ $template->diagnosis ?? 'General' }}</td>
                                                <td>{{ is_array($template->medications) ? count($template->medications) : 0 }} medication(s)</td>
                                                <td>{{ $template->creator->name ?? 'Unknown' }}</td>
                                                <td>{{ $template->updated_at->format('Y-m-d') }}</td>
                                                <td>{{ $template->usage_count ?? 0 }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionMenu{{ $template->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="actionMenu{{ $template->id }}">
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.view', $template->id) }}"><i class="zmdi zmdi-eye"></i> View Template</a>
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.use-form', $template->id) }}"><i class="zmdi zmdi-check"></i> Use Template</a>
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.edit', $template->id) }}"><i class="zmdi zmdi-edit"></i> Edit Template</a>
                                                            <a class="dropdown-item" href="#"><i class="zmdi zmdi-delete"></i> Delete Template</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No templates found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Recently Used Tab -->
                            <div class="tab-pane fade" id="recently-used" role="tabpanel">
                                <p><strong>Recently Used Templates</strong></p>
                                <p>Templates that have been used in the last 30 days.</p>
                                <div class="table-responsive">
                                    <table class="table table-hover template-table">
                                        <thead>
                                            <tr>
                                                <th>Template Name</th>
                                                <th>Category</th>
                                                <th>Medications</th>
                                                <th>Created By</th>
                                                <th>Last Used</th>
                                                <th>Usage</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="recently-used-table">
                                            @forelse($recentlyUsed as $template)
                                            <tr>
                                                <td>{{ $template->name }}</td>
                                                <td>{{ $template->diagnosis ?? 'General' }}</td>
                                                <td>{{ is_array($template->medications) ? count($template->medications) : 0 }} medication(s)</td>
                                                <td>{{ $template->creator->name ?? 'Unknown' }}</td>
                                                <td>{{ $template->updated_at->format('Y-m-d') }}</td>
                                                <td>{{ $template->usage_count ?? 0 }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionMenu{{ $template->id }}-recent" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="actionMenu{{ $template->id }}-recent">
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.view', $template->id) }}"><i class="zmdi zmdi-eye"></i> View Template</a>
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.use-form', $template->id) }}"><i class="zmdi zmdi-check"></i> Use Template</a>
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.edit', $template->id) }}"><i class="zmdi zmdi-edit"></i> Edit Template</a>
                                                            <a class="dropdown-item" href="#"><i class="zmdi zmdi-delete"></i> Delete Template</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No recently used templates found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- My Templates Tab -->
                            <div class="tab-pane fade" id="my-templates" role="tabpanel">
                                <p><strong>My Templates</strong></p>
                                <p>Templates created by you.</p>
                                <div class="table-responsive">
                                    <table class="table table-hover template-table">
                                        <thead>
                                            <tr>
                                                <th>Template Name</th>
                                                <th>Category</th>
                                                <th>Medications</th>
                                                <th>Created On</th>
                                                <th>Last Used</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="my-templates-table">
                                            @forelse($myTemplates as $template)
                                            <tr>
                                                <td>{{ $template->name }}</td>
                                                <td>{{ $template->diagnosis ?? 'General' }}</td>
                                                <td>{{ is_array($template->medications) ? count($template->medications) : 0 }} medication(s)</td>
                                                <td>{{ $template->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $template->updated_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionMenu{{ $template->id }}-my" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="actionMenu{{ $template->id }}-my">
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.view', $template->id) }}"><i class="zmdi zmdi-eye"></i> View Template</a>
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.use-form', $template->id) }}"><i class="zmdi zmdi-check"></i> Use Template</a>
                                                            <a class="dropdown-item" href="{{ route('admin.prescriptions.template.edit', $template->id) }}"><i class="zmdi zmdi-edit"></i> Edit Template</a>
                                                            <a class="dropdown-item" href="#"><i class="zmdi zmdi-delete"></i> Delete Template</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No templates found</td>
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
        </div>
        
        <!-- Template Details Section -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Template</strong> Details</h2>
                        <p>View detailed information about a selected template.</p>
                    </div>
                    <div class="body">
                        <!-- Template Header -->
                        <div class="template-details">
                            <div class="row">
                                <div class="col-md-9">
                                    <h3 id="template-name">
                                        @if(isset($sampleTemplate))
                                            {{ $sampleTemplate->name }}
                                        @else
                                            Select a template to view details
                                        @endif
                                    </h3>
                                    <p class="text-muted" id="template-meta">
                                        @if(isset($sampleTemplate))
                                            {{ $sampleTemplate->diagnosis ?? 'General' }} • Created by {{ $sampleTemplate->creator->name ?? 'Unknown' }}
                                        @else
                                            No template selected
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if(isset($sampleTemplate))
                                        <button class="btn btn-primary" id="edit-template-btn">Edit</button>
                                    @else
                                        <button class="btn btn-primary" id="edit-template-btn" style="display:none;">Edit</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Medications Section -->
                        <h4><strong>Medications</strong></h4>
                        <div id="medications-container-details">
                            @if(isset($sampleTemplate) && is_array($sampleTemplate->medications))
                                @forelse($sampleTemplate->medications as $medication)
                                    <div class="medication-card">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>{{ $medication['drug_name'] ?? $medication['drug_id'] ?? 'Unknown Medication' }}</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 medication-info">
                                                <strong>Route</strong>
                                                <p>{{ $medication['route'] ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3 medication-info">
                                                <strong>Frequency</strong>
                                                <p>{{ $medication['frequency'] ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3 medication-info">
                                                <strong>Duration</strong>
                                                <p>{{ $medication['duration'] ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3 medication-info">
                                                <strong>Instructions</strong>
                                                <p>{{ $medication['instructions'] ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>No medications found for this template</p>
                                @endforelse
                            @else
                                <p>Select a template to view medications</p>
                            @endif
                        </div>
                        
                        <!-- Usage Statistics Section -->
                        <h4><strong>Usage Statistics</strong></h4>
                        <div class="row" id="usage-stats-container">
                            <div class="col-md-4">
                                <div class="usage-stat">
                                    <div class="number" id="total-uses">0</div>
                                    <div class="label">Total Uses</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="usage-stat">
                                    <div class="number" id="last-used">
                                        @if(isset($sampleTemplate))
                                            {{ $sampleTemplate->updated_at->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="label">Last Used</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="usage-stat">
                                    <div class="number" id="created-on">
                                        @if(isset($sampleTemplate))
                                            {{ $sampleTemplate->created_at->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="label">Created On</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Template Details Modal (for dynamic content) -->
<script>
    // Ensure modal is properly initialized
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener for add medication button
        if (document.getElementById('add-medication')) {
            document.getElementById('add-medication').addEventListener('click', function() {
                addMedicationField();
            });
        }
        
        // Add event listener for save template button
        if (document.getElementById('save-template')) {
            document.getElementById('save-template').addEventListener('click', function() {
                saveTemplate();
            });
        }
    });
    
    // Function to view template details
    function viewTemplateDetails(templateId) {
        // Show loading indicator
        document.getElementById('medications-container-details').innerHTML = '<div class="loading">Loading template details...</div>';
        
        // Make AJAX request to fetch template details
        fetch('/admin/prescriptions/template/' + templateId + '/details', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTemplateDetails(data);
            } else {
                document.getElementById('medications-container-details').innerHTML = '<p>Error loading template details</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('medications-container-details').innerHTML = '<p>Error loading template details</p>';
        });
    }
    
    // Function to update template details in the UI
    function updateTemplateDetails(data) {
        // Update template header
        document.getElementById('template-name-details').textContent = data.template.name;
        document.getElementById('template-meta').textContent = 
            (data.template.diagnosis || 'General') + ' • Created by ' + 
            (data.template.creator ? data.template.creator.name : 'Unknown');
        document.getElementById('edit-template-btn').style.display = 'inline-block';
        
        // Update usage statistics
        if (data.usage_stats) {
            document.getElementById('total-uses').textContent = data.usage_stats.total_uses;
            document.getElementById('last-used').textContent = data.usage_stats.last_used;
            document.getElementById('created-on').textContent = data.usage_stats.created_on;
        }
        
        // Update medications
        const medicationsContainer = document.getElementById('medications-container-details');
        let medicationsHtml = '';
        
        if (data.template.medications && data.template.medications.length > 0) {
            data.template.medications.forEach(med => {
                medicationsHtml += `
                    <div class="medication-card">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>${med.drug_name || med.drug_id || 'Unknown Medication'}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 medication-info">
                                <strong>Route</strong>
                                <p>${med.route || '-'}</p>
                            </div>
                            <div class="col-md-3 medication-info">
                                <strong>Frequency</strong>
                                <p>${med.frequency || '-'}</p>
                            </div>
                            <div class="col-md-3 medication-info">
                                <strong>Duration</strong>
                                <p>${med.duration || '-'}</p>
                            </div>
                            <div class="col-md-3 medication-info">
                                <strong>Instructions</strong>
                                <p>${med.instructions || '-'}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            medicationsHtml = '<p>No medications found for this template</p>';
        }
        
        medicationsContainer.innerHTML = medicationsHtml;
    }
    
    // Function to use template
    function useTemplate(templateId) {
        // Make AJAX request to increment usage count
        fetch('/admin/prescriptions/template/' + templateId + '/use', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the template details with the new usage count
                if (data.template && data.usage_stats) {
                    updateTemplateDetails(data);
                }
                
                // Show success message
                alert('Template usage count updated to ' + data.usage_stats.total_uses + '. In a real application, this would load the template into a new prescription form.');
            } else {
                alert('Error using template: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error using template');
        });
    }
    
    // Search functionality - improved to search by template name, category, and creator
    if (document.getElementById('template-search')) {
        document.getElementById('template-search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const tables = ['all-templates-table', 'recently-used-table', 'my-templates-table'];
            
            tables.forEach(tableId => {
                const table = document.getElementById(tableId);
                if (!table) return;
                
                const rows = table.getElementsByTagName('tr');
                
                // Skip header row
                for (let i = 1; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;
                    
                    // Search in template name (0), category/diagnosis (1), and created by (3)
                    if (searchTerm === '') {
                        found = true;
                    } else {
                        // Check template name (index 0)
                        if (cells[0] && cells[0].textContent.toLowerCase().includes(searchTerm)) {
                            found = true;
                        }
                        // Check category/diagnosis (index 1)
                        else if (cells[1] && cells[1].textContent.toLowerCase().includes(searchTerm)) {
                            found = true;
                        }
                        // Check created by (index 3)
                        else if (cells[3] && cells[3].textContent.toLowerCase().includes(searchTerm)) {
                            found = true;
                        }
                    }
                    
                    rows[i].style.display = found ? '' : 'none';
                }
            });
        });
    }
    
    // Medication management
    let medicationIndex = 1;
    
    function addMedicationField() {
        const container = document.getElementById('medications-container');
        if (!container) return;
        
        const newRow = document.createElement('div');
        newRow.className = 'medication-row';
        newRow.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Medication #${medicationIndex + 1}</h5>
                <span class="remove-medication" onclick="removeMedication(this)"><i class="zmdi zmdi-delete"></i></span>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name</label>
                        <div class="d-flex">
                            <select class="form-control medication-select" name="medications[${medicationIndex}][drug_id]" style="flex: 1;">
                                <option value="">Select a drug</option>
                                @if(isset($drugs))
                                    @foreach($drugs as $drug)
                                        <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="text" class="form-control medication-name" name="medications[${medicationIndex}][name]" placeholder="Or type a name" style="flex: 1;">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dosage</label>
                        <select class="form-control" name="medications[${medicationIndex}][dosage]">
                            <option value="">Select dosage</option>
                            @if(isset($mgValues))
                                @foreach($mgValues as $mg)
                                    <option value="{{ $mg->mg_value }}">{{ $mg->mg_value }} MG</option>
                                @endforeach
                            @endif
                        </select>
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
                        <label>Frequency</label>
                        <input type="text" class="form-control" name="medications[${medicationIndex}][frequency]" placeholder="e.g., Once daily">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Instructions</label>
                        <input type="text" class="form-control" name="medications[${medicationIndex}][instructions]" placeholder="e.g., Take with food">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        medicationIndex++;
    }
    
    // Function to remove medication field
    function removeMedication(element) {
        if (element && element.closest('.medication-row')) {
            element.closest('.medication-row').remove();
        }
    }
    
    // Save template functionality
    function saveTemplate() {
        const form = document.getElementById('create-template-form');
        const messageDiv = document.getElementById('template-message');
        
        if (!form || !messageDiv) {
            console.error('Form or message div not found');
            return;
        }
        
        // Hide any previous messages
        messageDiv.classList.add('d-none');
        
        // Get form data manually since FormData doesn't work well with arrays
        const name = form.querySelector('#template-name').value;
        const category = form.querySelector('#template-category').value;
        const description = form.querySelector('#template-description').value;
        
        // Get medications data
        const medications = [];
        const medicationRows = form.querySelectorAll('.medication-row');
        
        medicationRows.forEach((row, index) => {
            const drugSelect = row.querySelector(`select[name="medications[${index}][drug_id]"]`);
            const nameInput = row.querySelector(`input[name="medications[${index}][name]"]`);
            const dosageSelect = row.querySelector(`select[name="medications[${index}][dosage]"]`);
            const routeSelect = row.querySelector(`select[name="medications[${index}][route]"]`);
            const frequencyInput = row.querySelector(`input[name="medications[${index}][frequency]"]`);
            const instructionsInput = row.querySelector(`input[name="medications[${index}][instructions]"]`);
            
            // Determine which name to use (selected drug or typed name)
            let medicationName = '';
            let drugId = null;
            
            if (drugSelect && drugSelect.value) {
                // Use selected drug
                drugId = drugSelect.value;
                medicationName = drugSelect.options[drugSelect.selectedIndex].text;
            } else if (nameInput && nameInput.value) {
                // Use typed name
                medicationName = nameInput.value;
            }
            
            if (medicationName && dosageSelect && routeSelect) {
                medications.push({
                    drug_id: drugId,
                    name: medicationName,
                    dosage: dosageSelect.value,
                    route: routeSelect.value,
                    frequency: frequencyInput ? frequencyInput.value : '',
                    instructions: instructionsInput ? instructionsInput.value : ''
                });
            }
        });
        
        // Validate form
        if (!name) {
            showMessage('Template name is required', 'danger');
            return;
        }
        
        if (medications.length === 0) {
            showMessage('At least one medication is required', 'danger');
            return;
        }
        
        // Prepare data for submission
        const data = {
            name: name,
            category: category,
            description: description,
            medications: medications
        };
        
        // Send data to server
        fetch('/admin/prescriptions/templates', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Template saved successfully!', 'success');
                // Close modal after a delay
                setTimeout(function() {
                    $('#createTemplateModal').modal('hide');
                    // Reset form
                    form.reset();
                    // Reset medications container to initial state
                    const medicationsContainer = document.getElementById('medications-container');
                    if (medicationsContainer) {
                        medicationsContainer.innerHTML = `
                            <div class="medication-row">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Medication #1</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="d-flex">
                                                <select class="form-control medication-select" name="medications[0][drug_id]" style="flex: 1;">
                                                    <option value="">Select a drug</option>
                                                    @if(isset($drugs))
                                                        @foreach($drugs as $drug)
                                                            <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="text" class="form-control medication-name" name="medications[0][name]" placeholder="Or type a name" style="flex: 1;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dosage</label>
                                            <select class="form-control" name="medications[0][dosage]">
                                                <option value="">Select dosage</option>
                                                @if(isset($mgValues))
                                                    @foreach($mgValues as $mg)
                                                        <option value="{{ $mg->mg_value }}">{{ $mg->mg_value }} MG</option>
                                                    @endforeach
                                                @endif
                                            </select>
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
                                            <label>Frequency</label>
                                            <input type="text" class="form-control" name="medications[0][frequency]" placeholder="e.g., Once daily">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Instructions</label>
                                            <input type="text" class="form-control" name="medications[0][instructions]" placeholder="e.g., Take with food">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    medicationIndex = 1;
                    // Refresh page to show new template
                    location.reload();
                }, 1500);
            } else {
                showMessage('Error saving template: ' + (data.error || 'Unknown error'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error saving template: ' + error.message, 'danger');
        });
    }
    
    // Function to show messages
    function showMessage(message, type) {
        const messageDiv = document.getElementById('template-message');
        if (!messageDiv) return;
        
        messageDiv.textContent = message;
        messageDiv.className = `alert alert-${type}`;
        messageDiv.classList.remove('d-none');
        
        // Auto hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(function() {
                messageDiv.classList.add('d-none');
            }, 3000);
        }
    }
</script>
<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1" role="dialog" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="createTemplateModalLabel">Create New Template</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Create a reusable medication template for prescriptions.</p>
                
                <div id="template-message" class="alert d-none"></div>
                
                <form id="create-template-form">
                    @csrf
                    <div class="form-group">
                        <label for="template-name">Template Name</label>
                        <input type="text" class="form-control" id="template-name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="template-category">Category</label>
                        <select class="form-control" id="template-category" name="category">
                            <option value="">Select a category</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="template-description">Description</label>
                        <textarea class="form-control" id="template-description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Medications</label>
                        <div id="medications-container">
                            <div class="medication-row">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Medication #1</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="d-flex">
                                                <select class="form-control medication-select" name="medications[0][drug_id]" style="flex: 1;">
                                                    <option value="">Select a drug</option>
                                                    @if(isset($drugs))
                                                        @foreach($drugs as $drug)
                                                            <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="text" class="form-control medication-name" name="medications[0][name]" placeholder="Or type a name" style="flex: 1;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dosage</label>
                                            <select class="form-control" name="medications[0][dosage]">
                                                <option value="">Select dosage</option>
                                                @if(isset($mgValues))
                                                    @foreach($mgValues as $mg)
                                                        <option value="{{ $mg->mg_value }}">{{ $mg->mg_value }} MG</option>
                                                    @endforeach
                                                @endif
                                            </select>
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
                                            <label>Frequency</label>
                                            <input type="text" class="form-control" name="medications[0][frequency]" placeholder="e.g., Once daily">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Instructions</label>
                                            <input type="text" class="form-control" name="medications[0][instructions]" placeholder="e.g., Take with food">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" id="add-medication">
                            <i class="zmdi zmdi-plus"></i> Add Medication
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-template">Save Template</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>