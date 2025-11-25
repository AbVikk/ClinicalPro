﻿﻿﻿﻿﻿﻿﻿<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>Clinical Pro || Edit Service</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Bootstrap Select CSS -->
<link href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    .time-pricing-row {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f9f9f9;
    }
    
    .remove-pricing {
        color: #dc3545;
        cursor: pointer;
        font-size: 1.2em;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Edit Service
                <small>Welcome to Clinical Pro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                    <li class="breadcrumb-item active">Edit Service</li>
                </ul>
            </div>
        </div>
    </div>    
    <div class="container-fluid">        
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="header">
						<h2><strong>Edit</strong> Service</h2>
						<ul class="header-dropdown">                            
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
					</div>
					<div class="body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
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
                        
                        <form action="{{ route('admin.services.update', $service) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="service_name">Service Name *</label>
                                        <input type="text" class="form-control" id="service_name" name="service_name" value="{{ old('service_name', $service->service_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="service_type">Service Type *</label>
                                        <input type="text" class="form-control" id="service_type" name="service_type" value="{{ old('service_type', $service->service_type) }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="price_amount">Default Price (₦) *</label>
                                        <input type="number" class="form-control" id="price_amount" name="price_amount" step="0.01" min="0.01" value="{{ old('price_amount', $service->price_amount) }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="default_duration">Default Duration (minutes) *</label>
                                        <select class="form-control show-tick" id="default_duration" name="default_duration" required>
                                            <option value="30" {{ old('default_duration', $service->default_duration) == 30 ? 'selected' : '' }}>30 minutes</option>
                                            <option value="40" {{ old('default_duration', $service->default_duration) == 40 ? 'selected' : '' }}>40 minutes</option>
                                            <option value="60" {{ old('default_duration', $service->default_duration) == 60 ? 'selected' : '' }}>60 minutes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="price_currency">Currency</label>
                                        <input type="text" class="form-control" id="price_currency" name="price_currency" value="{{ old('price_currency', $service->price_currency) }}" maxlength="3" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="is_active">Status</label>
                                        <select class="form-control show-tick" id="is_active" name="is_active">
                                            <option value="1" {{ old('is_active', $service->is_active) ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', !$service->is_active) ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $service->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <h4>Time-Based Pricing</h4>
                                    <p>Set different prices for different time durations of this service.</p>
                                    
                                    <div id="time-pricing-container">
                                        @foreach($service->timePricings as $index => $pricing)
                                        <div class="time-pricing-row">
                                            <input type="hidden" name="time_pricing[{{ $index }}][id]" value="{{ $pricing->id }}">
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label>Duration (minutes) *</label>
                                                        <select class="form-control show-tick" name="time_pricing[{{ $index }}][duration]" required>
                                                            <option value="30" {{ $pricing->duration_minutes == 30 ? 'selected' : '' }}>30 minutes</option>
                                                            <option value="40" {{ $pricing->duration_minutes == 40 ? 'selected' : '' }}>40 minutes</option>
                                                            <option value="60" {{ $pricing->duration_minutes == 60 ? 'selected' : '' }}>60 minutes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label>Price (₦) *</label>
                                                        <input type="number" class="form-control" name="time_pricing[{{ $index }}][price]" step="0.01" min="0.01" value="{{ $pricing->price }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>Status</label><br>
                                                        <select class="form-control show-tick" name="time_pricing[{{ $index }}][is_active]">
                                                            <option value="1" {{ $pricing->is_active ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !$pricing->is_active ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <button type="button" id="add-time-pricing" class="btn btn-info">Add Time Pricing</button>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-round">Update Service</button>
                                    <a href="{{ route('admin.services.index') }}" class="btn btn-default btn-round btn-simple">Cancel</a>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Bootstrap JS and jQuery v3.2.1 -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script>
    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
</script>

<!-- Bootstrap Select Js -->
<script src="{{ asset('assets/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add time pricing row
        document.getElementById('add-time-pricing').addEventListener('click', function() {
            const container = document.getElementById('time-pricing-container');
            const rowIndex = container.children.length;
            
            const row = document.createElement('div');
            row.className = 'time-pricing-row';
            row.innerHTML = `
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label>Duration (minutes) *</label>
                            <select class="form-control show-tick" name="time_pricing[${rowIndex}][duration]" required>
                                <option value="30">30 minutes</option>
                                <option value="40">40 minutes</option>
                                <option value="60">60 minutes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label>Price (₦) *</label>
                            <input type="number" class="form-control" name="time_pricing[${rowIndex}][price]" step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Status</label><br>
                            <select class="form-control show-tick" name="time_pricing[${rowIndex}][is_active]">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(row);
            
            // Initialize selectpicker for the new select elements
            $('.show-tick').selectpicker('refresh');
        });
        
        // Remove time pricing row
        document.getElementById('time-pricing-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-pricing')) {
                e.target.closest('.time-pricing-row').remove();
                
                // Re-index the remaining rows
                const rows = document.querySelectorAll('.time-pricing-row');
                rows.forEach((row, index) => {
                    const inputs = row.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/\[\d+\]/, `[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });
                });
                
                // Refresh selectpicker after removing elements
                $('.show-tick').selectpicker('refresh');
            }
        });
    });
</script>
</body>
</html>