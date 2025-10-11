<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Prescription Template Details">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>:: Clinical Pro :: Template Details</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
<style>
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
    
    .medication-info p {
        margin-bottom: 5px;
    }
    
    .medication-info strong {
        display: block;
        margin-bottom: 2px;
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
                <h2>Template Details
                <small>View medication template details.</small>
                </h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <a href="{{ route('admin.prescriptions.templates') }}" class="btn btn-default mb-3"><i class="zmdi zmdi-arrow-left"></i> Back</a>
                
                <div class="card">
                    <div class="body">
                        <h2><strong>Template</strong> Details</h2>
                        <p class="text-muted">View and manage medication template details.</p>
                        
                        <div class="template-details mt-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3>{{ $template->name ?? 'Template Name' }}</h3>
                                    <p class="text-muted">
                                        {{ $template->diagnosis ?? 'General' }}<br>
                                        Created by {{ $template->creator->name ?? 'Unknown' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{ route('admin.prescriptions.template.edit', $template->id) }}" class="btn btn-primary mr-2"><i class="zmdi zmdi-edit"></i> Edit Template</a>
                                    <button class="btn btn-success mr-2" onclick="useTemplate({{ $template->id }})"><i class="zmdi zmdi-check"></i> Use Template</button>
                                    <button class="btn btn-danger"><i class="zmdi zmdi-delete"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-4">
                            <div class="body">
                                <h4><strong>Template Information</strong></h4>
                                <p class="text-muted">Detailed information about this medication template.</p>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><strong>Description</strong></h5>
                                        <p>{{ $template->notes ?? 'No description provided' }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><strong>Medications ({{ is_array($template->medications) ? count($template->medications) : 0 }})</strong></h5>
                                        @if(is_array($template->medications))
                                            @forelse($template->medications as $medication)
                                                <div class="medication-card">
                                                    <h6><strong>{{ $medication['drug_name'] ?? $medication['name'] ?? 'Unknown Medication' }} {{ $medication['dosage'] ?? '' }}</strong></h6>
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
                                            <p>No medications found for this template</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-4">
                            <div class="body">
                                <h4><strong>Usage Statistics</strong></h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="usage-stat">
                                            <div class="number">{{ $usageStats['total_uses'] ?? 0 }}</div>
                                            <div class="label">Total Uses</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="usage-stat">
                                            <div class="number">{{ $usageStats['last_used'] ?? '-' }}</div>
                                            <div class="label">Last Used</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="usage-stat">
                                            <div class="number">{{ $usageStats['created_on'] ?? '-' }}</div>
                                            <div class="label">Created On</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
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
                // Show success message
                alert('Template usage count updated to ' + data.usage_stats.total_uses + '. In a real application, this would load the template into a new prescription form.');
                // Refresh the page to show updated usage count
                location.reload();
            } else {
                alert('Error using template: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error using template');
        });
    }
</script>
</body>
</html>