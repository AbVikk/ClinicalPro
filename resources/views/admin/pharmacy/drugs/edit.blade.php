<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Pharmacy Management System">
<title>:: Telehealth Pharmacy :: Edit Drug</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2>
                    <a href="{{ route('admin.pharmacy.drugs.view', $drug->id) }}" class="btn btn-sm btn-primary">
                        <i class="zmdi zmdi-arrow-back"></i>
                    </a>
                    Edit {{ $drug->name }} {{ $drug->strength_mg }}
                    
                    <button type="submit" form="edit-drug-form" class="btn btn-sm btn-success float-right">
                        <i class="zmdi zmdi-save"></i> Save Changes
                    </button>
                </h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="body">
                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                        
                        <form method="POST" action="{{ route('admin.pharmacy.drugs.update', $drug->id) }}" id="edit-drug-form">
                            @csrf
                            @method('PUT')
                            
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs padding-0">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#basic">Basic Information</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#inventory">Inventory Details</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#clinical">Clinical Information</a></li>
                            </ul>
                            
                            <!-- Tab panes -->
                            <div class="tab-content m-t-10">
                                <div class="tab-pane active" id="basic">
                                    <div class="header">
                                        <h2><strong>Basic</strong> Information</h2>
                                        <p>Edit the basic details of this medicine</p>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="name">Medicine Name</label>
                                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $drug->name) }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="generic_name">Generic Name</label>
                                                <input type="text" class="form-control" name="generic_name" id="generic_name" value="{{ old('generic_name', $drug->details['generic_name'] ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="category">Category</label>
                                                <select class="form-control" name="category" id="category">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->name }}" {{ old('category', $drug->category) == $category->name ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="medicine_type">Medicine Type</label>
                                                <select class="form-control" name="medicine_type" id="medicine_type">
                                                    <option value="OTC" {{ old('medicine_type', $drug->is_controlled ? 'Controlled' : 'OTC') == 'OTC' ? 'selected' : '' }}>OTC</option>
                                                    <option value="Controlled" {{ old('medicine_type', $drug->is_controlled ? 'Controlled' : 'OTC') == 'Controlled' ? 'selected' : '' }}>Controlled</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="manufacturer">Manufacturer</label>
                                                <input type="text" class="form-control" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $drug->details['manufacturer'] ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="inventory">
                                    <div class="header">
                                        <h2><strong>Inventory</strong> Details</h2>
                                        <p>Edit inventory and pricing information</p>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="current_stock">Current Stock</label>
                                                <input type="text" class="form-control" id="current_stock" value="{{ $drug->batches->sum('received_quantity') }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="batch_number">Batch Number</label>
                                                <input type="text" class="form-control" id="batch_number" value="{{ $drug->batches->first() ? $drug->batches->first()->batch_uuid : '' }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="expiry_date">Expiry Date</label>
                                                <input type="date" class="form-control" id="expiry_date" value="{{ $drug->batches->first() && $drug->batches->first()->expiry_date ? $drug->batches->first()->expiry_date->format('Y-m-d') : '' }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="purchase_date">Purchase Date</label>
                                                <input type="date" class="form-control" id="purchase_date" value="{{ $drug->batches->first() && $drug->batches->first()->created_at ? $drug->batches->first()->created_at->format('Y-m-d') : '' }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="purchase_price">Purchase Price ($)</label>
                                                <input type="number" class="form-control" name="purchase_price" id="purchase_price" step="0.01" value="{{ old('purchase_price', $drug->details['purchase_price'] ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="selling_price">Selling Price ($)</label>
                                                <input type="number" class="form-control" name="unit_price" id="selling_price" step="0.01" value="{{ old('unit_price', $drug->unit_price) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="storage_location">Storage Location</label>
                                                <input type="text" class="form-control" name="storage_location" id="storage_location" value="{{ old('storage_location', 'Shelf A-12') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" name="status" id="status">
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                        $status = 'In Stock';
                                                        if ($totalStock == 0) {
                                                            $status = 'Out of Stock';
                                                        } elseif ($totalStock < 50) {
                                                            $status = 'Low Stock';
                                                        }
                                                    @endphp
                                                    <option value="In Stock" {{ old('status', $status) == 'In Stock' ? 'selected' : '' }}>In Stock</option>
                                                    <option value="Low Stock" {{ old('status', $status) == 'Low Stock' ? 'selected' : '' }}>Low Stock</option>
                                                    <option value="Out of Stock" {{ old('status', $status) == 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="clinical">
                                    <div class="header">
                                        <h2><strong>Clinical</strong> Information</h2>
                                        <p>Edit medical details and usage information</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" id="description" rows="3">{{ old('description', $drug->details['description'] ?? '') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="dosage">Dosage Instructions</label>
                                        <textarea class="form-control" name="dosage" id="dosage" rows="2">{{ old('dosage', $drug->details['dosage'] ?? '') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="side_effects">Side Effects</label>
                                        <textarea class="form-control" name="side_effects" id="side_effects" rows="2">{{ old('side_effects', $drug->details['side_effects'] ?? '') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="contraindications">Contraindications</label>
                                        <textarea class="form-control" name="contraindications" id="contraindications" rows="2">{{ old('contraindications', 'Known hypersensitivity to penicillins or cephalosporins.') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="storage_instructions">Storage Instructions</label>
                                        <textarea class="form-control" name="storage_instructions" id="storage_instructions" rows="2">{{ old('storage_instructions', 'Store at room temperature between 15-30°C (59-86°F). Keep away from moisture and heat.') }}</textarea>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>