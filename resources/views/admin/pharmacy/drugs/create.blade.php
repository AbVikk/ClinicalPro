<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Add Doctors</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Dropzone CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.css') }}">

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    .tab-content {
        min-height: 400px;
    }
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
    }
    .nav-tabs .nav-link {
        border: 1px solid #dee2e6;
        border-bottom: none;
        border-radius: 0;
    }
    .tab-pane {
        padding: 20px;
        border: 1px solid #dee2e6;
        border-top: none;
    }
    .medicine-form {
        display: none;
    }
    .medicine-form.active {
        display: block;
    }
    .form-navigation {
        margin-top: 20px;
    }
    .medicine-form-section {
        display: none;
    }
    .medicine-form-section.active {
        display: block;
    }
    .upload-area {
        border: 2px dashed #ccc;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
        cursor: pointer;
    }
    .upload-area:hover {
        border-color: #007bff;
    }
    .form-check-inline {
        margin-right: 15px;
    }
    .alert-fixed {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="../assets/images/logo.svg" width="48" height="48" alt="Oreo"></div>
        <p>Please wait...</p>
    </div>
</div>
<!-- Overlay For Sidebars -->
@include('admin.sidemenu')

<section class="content">
       <div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Add Drug
            <small>Add a new drug to the catalog</small>
            </h2>
        </div>            
        <div class="col-lg-7 col-md-7 col-sm-12 text-right">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.dashboard') }}">Pharmacy</a></li>
                <li class="breadcrumb-item active">Add Drug</li>
            </ul>
        </div>
    </div>
</div>
   <div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Add</strong> Drug</h2>
                </div>
                <div class="body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show alert-fixed" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show alert-fixed" role="alert">
                            <strong>Error!</strong> Please correct the following issues:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <!-- Form Submission Error Message (from controller) -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show alert-fixed" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <form id="drugForm" action="{{ route('admin.pharmacy.drugs.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" id="drugTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab">Basic Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="detailed-tab" data-toggle="tab" href="#detailed" role="tab">Detailed Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="inventory-tab" data-toggle="tab" href="#inventory" role="tab">Inventory & Pricing</a>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="drugTabContent">
                            <!-- Basic Information Tab -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <h4>Basic Information</h4>
                                <p>Enter the basic details of the medicine</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Medicine Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="generic_name">Generic Name *</label>
                                            <input type="text" class="form-control" id="generic_name" name="generic_name" value="{{ old('generic_name') }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">Category *</label>
                                            <select class="form-control" id="category" name="category" required>
                                                <option value="">Select a category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <a href="{{ route('admin.pharmacy.categories.index') }}" class="small">Manage categories</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="strength_mg">Strength (MG) *</label>
                                            <select class="form-control" id="strength_mg" name="strength_mg" required>
                                                <option value="">Select strength</option>
                                                @foreach($mgs as $mg)
                                                    <option value="{{ $mg->mg_value }}" {{ old('strength_mg') == $mg->mg_value ? 'selected' : '' }}>
                                                        {{ $mg->mg_value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <a href="{{ route('admin.pharmacy.mg.index') }}" class="small">Manage MG values</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="medicine_type">Medicine Type *</label>
                                            <select class="form-control" id="medicine_type" name="medicine_type" required>
                                                <option value="">Select medicine type</option>
                                                <option value="OTC" {{ old('medicine_type') == 'OTC' ? 'selected' : '' }}>Over the Counter (OTC)</option>
                                                <option value="Controlled" {{ old('medicine_type') == 'Controlled' ? 'selected' : '' }}>Controlled Substance</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Medicine Form *</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicine_form" id="tablet" value="Tablet" {{ old('medicine_form') == 'Tablet' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="tablet">Tablet</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicine_form" id="capsule" value="Capsule" {{ old('medicine_form') == 'Capsule' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="capsule">Capsule</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicine_form" id="syrup" value="Syrup" {{ old('medicine_form') == 'Syrup' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="syrup">Syrup</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicine_form" id="injection" value="Injection" {{ old('medicine_form') == 'Injection' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="injection">Injection</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicine_form" id="cream" value="Cream/Ointment" {{ old('medicine_form') == 'Cream/Ointment' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="cream">Cream/Ointment</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicine_form" id="drops" value="Drops" {{ old('medicine_form') == 'Drops' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="drops">Drops</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicine_form" id="other" value="Other" {{ old('medicine_form') == 'Other' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="other">Other</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-navigation">
                                    <button type="button" class="btn btn-primary next-tab" data-target="#detailed">Next</button>
                                </div>
                            </div>
                            
                            <!-- Detailed Information Tab -->
                            <div class="tab-pane fade" id="detailed" role="tabpanel">
                                <h4>Detailed Information</h4>
                                <p>Enter detailed specifications of the medicine</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="manufacturer">Manufacturer</label>
                                            <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="supplier">Supplier</label>
                                            <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="manufacturing_date">Manufacturing Date</label>
                                            <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date" value="{{ old('manufacturing_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expiry_date">Expiry Date *</label>
                                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="batch_number">Batch Number</label>
                                            <input type="text" class="form-control" id="batch_number" name="batch_number" value="{{ old('batch_number') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dosage">Dosage</label>
                                            <input type="text" class="form-control" id="dosage" name="dosage" value="{{ old('dosage') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="side_effects">Side Effects</label>
                                            <textarea class="form-control" id="side_effects" name="side_effects" rows="3">{{ old('side_effects') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="precautions">Precautions & Warnings</label>
                                            <textarea class="form-control" id="precautions" name="precautions" rows="3">{{ old('precautions') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-navigation">
                                    <button type="button" class="btn btn-secondary prev-tab" data-target="#basic">Previous</button>
                                    <button type="button" class="btn btn-primary next-tab" data-target="#inventory">Next</button>
                                </div>
                            </div>
                            
                            <!-- Inventory & Pricing Tab -->
                            <div class="tab-pane fade" id="inventory" role="tabpanel">
                                <h4>Inventory & Pricing</h4>
                                <p>Enter inventory and pricing details</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="initial_quantity">Initial Quantity *</label>
                                            <input type="number" class="form-control" id="initial_quantity" name="initial_quantity" min="0" value="{{ old('initial_quantity') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reorder_level">Reorder Level</label>
                                            <input type="number" class="form-control" id="reorder_level" name="reorder_level" min="0" value="{{ old('reorder_level') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="maximum_level">Maximum Level</label>
                                            <input type="number" class="form-control" id="maximum_level" name="maximum_level" min="0" value="{{ old('maximum_level') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_price">Purchase Price *</label>
                                            <input type="number" class="form-control" id="purchase_price" name="purchase_price" step="0.01" min="0" value="{{ old('purchase_price') }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selling_price">Selling Price *</label>
                                            <input type="number" class="form-control" id="selling_price" name="selling_price" step="0.01" min="0" value="{{ old('selling_price') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tax_rate">Tax Rate (%)</label>
                                            <input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', 0) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Storage Conditions</label>
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="storage_conditions[]" id="room_temperature" value="Room Temperature" {{ is_array(old('storage_conditions')) && in_array('Room Temperature', old('storage_conditions')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="room_temperature">Room Temperature</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="storage_conditions[]" id="refrigerated" value="Refrigerated" {{ is_array(old('storage_conditions')) && in_array('Refrigerated', old('storage_conditions')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="refrigerated">Refrigerated</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="storage_conditions[]" id="frozen" value="Frozen" {{ is_array(old('storage_conditions')) && in_array('Frozen', old('storage_conditions')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="frozen">Frozen</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="storage_conditions[]" id="protect_from_light" value="Protect from Light" {{ is_array(old('storage_conditions')) && in_array('Protect from Light', old('storage_conditions')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="protect_from_light">Protect from Light</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="upload-area">
                                            <p>Click to upload medicine image</p>
                                            <input type="file" id="medicine_image" name="medicine_image" accept="image/*" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="upload-area">
                                            <p>Click to upload package image</p>
                                            <input type="file" id="package_image" name="package_image" accept="image/*" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="is_active">
                                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                Active (Available for sale)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-navigation">
                                    <button type="button" class="btn btn-secondary prev-tab" data-target="#detailed">Previous</button>
                                    <button type="submit" class="btn btn-raised btn-primary">Save Medicine</button>
                                    <a href="{{ route('admin.pharmacy.dashboard') }}" class="btn btn-raised btn-default">Cancel</a>
                                </div>
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
    <!-- Dropzone Plugin Js -->
    <script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Tab navigation
            $('.next-tab').click(function() {
                var target = $(this).data('target');
                $('.nav-tabs a[href="' + target + '"]').tab('show');
            });
            
            $('.prev-tab').click(function() {
                var target = $(this).data('target');
                $('.nav-tabs a[href="' + target + '"]').tab('show');
            });
            
            // Upload area click handlers
            $('.upload-area').click(function() {
                $(this).find('input[type="file"]').click();
            });
            
            // Prevent event bubbling when clicking on the file input directly
            $('.upload-area input[type="file"]').click(function(e) {
                e.stopPropagation();
            });
            
            // File input change handlers
            $('.upload-area input[type="file"]').change(function() {
                if (this.files && this.files[0]) {
                    var fileName = this.files[0].name;
                    $(this).siblings('p').text('Selected: ' + fileName);
                }
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert-fixed').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>