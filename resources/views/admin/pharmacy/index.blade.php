<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Pharmacy Management System">
<title>:: Telehealth Pharmacy :: Dashboard</title>
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
            <div class="col-lg-5 col-md-5 col-sm-12">
                <h2>Pharmacy Dashboard
                <small>Pharmacy Management System</small>
                </h2>
            </div>            
            <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Admin</a></li>
                    <li class="breadcrumb-item active">Pharmacy Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ \App\Models\Drug::count() }}" data-speed="2500" data-fresh-interval="700">
                            {{ \App\Models\Drug::count() }} 
                            <i class="zmdi zmdi-collection-item float-right"></i>
                        </h3>
                        <p class="text-muted">Total Drugs</p>
                        <div class="progress">
                            <div class="progress-bar l-blue" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ \App\Models\DrugBatch::count() }}" data-speed="2500" data-fresh-interval="700">
                            {{ \App\Models\DrugBatch::count() }} 
                            <i class="zmdi zmdi-collection-bookmark float-right"></i>
                        </h3>
                        <p class="text-muted">Total Batches</p>
                        <div class="progress">
                            <div class="progress-bar l-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ \App\Models\StockTransfer::where('status', 'requested')->count() }}" data-speed="2500" data-fresh-interval="700">
                            {{ \App\Models\StockTransfer::where('status', 'requested')->count() }} 
                            <i class="zmdi zmdi-truck float-right"></i>
                        </h3>
                        <p class="text-muted">Pending Requests</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ \App\Models\Prescription::where('status', 'active')->count() }}" data-speed="2500" data-fresh-interval="700">
                            {{ \App\Models\Prescription::where('status', 'active')->count() }} 
                            <i class="zmdi zmdi-assignment float-right"></i>
                        </h3>
                        <p class="text-muted">Active Prescriptions</p>
                        <div class="progress">
                            <div class="progress-bar l-amber" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ \App\Models\Drug::whereHas('batches', function($query) { $query->where('received_quantity', '<', 50); })->count() }}" data-speed="2500" data-fresh-interval="700">
                            {{ \App\Models\Drug::whereHas('batches', function($query) { $query->where('received_quantity', '<', 50); })->count() }} 
                            <i class="zmdi zmdi-alert-triangle float-right"></i>
                        </h3>
                        <p class="text-muted">Low Stock Items</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ \App\Models\DrugBatch::where('expiry_date', '<=', now()->addDays(30))->count() }}" data-speed="2500" data-fresh-interval="700">
                            {{ \App\Models\DrugBatch::where('expiry_date', '<=', now()->addDays(30))->count() }} 
                            <i class="zmdi zmdi-time-restore float-right"></i>
                        </h3>
                        <p class="text-muted">Expiring Soon</p>
                        <div class="progress">
                            <div class="progress-bar l-red" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ \App\Models\Drug::distinct('category')->count('category') }}" data-speed="2500" data-fresh-interval="700">
                            {{ \App\Models\Drug::distinct('category')->count('category') }} 
                            <i class="zmdi zmdi-tag float-right"></i>
                        </h3>
                        <p class="text-muted">Categories</p>
                        <div class="progress">
                            <div class="progress-bar l-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Pharmacy</strong> Overview</h2>
                        <div class="col-md-8 float-right">
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control" id="stockFilter">
                                        <option value="">All Stock</option>
                                        <option value="in_stock">In Stock</option>
                                        <option value="low_stock">Low Stock</option>
                                        <option value="out_of_stock">Out of Stock</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="categoryFilter">
                                        <option value="">All Categories</option>
                                        @foreach(\App\Models\DrugCategory::all() as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search medicines..." id="medicineSearch">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp float-right">
                                    <li><a href="{{ route('admin.pharmacy.drugs.create.form') }}">Add New Drug</a></li>
                                    <li><a href="{{ route('admin.pharmacy.stock.receive') }}">Receive Stock</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>                    
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#all">All</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#prescription">Prescription</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#otc">OTC</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#controlled">Controlled</a></li>
                        </ul>
                            
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane active" id="all">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Medicine Name</th>
                                                <th>Category</th>
                                                <th>Stock</th>
                                                <th>Expiry Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\Drug::with('batches')->limit(10)->get() as $drug)
                                            <tr>
                                                <td>{{ $drug->id }}</td>
                                                <td>{{ $drug->name }}</td>
                                                <td>{{ $drug->category }}</td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                    @endphp
                                                    {{ $totalStock }}
                                                </td>
                                                <td>
                                                    @if($drug->batches->isNotEmpty())
                                                        {{ $drug->batches->first()->expiry_date->format('Y-m-d') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                        $status = 'In Stock';
                                                        if ($totalStock == 0) {
                                                            $status = 'Out of Stock';
                                                        } elseif ($totalStock < 50) { // Assuming 50 is the low stock threshold
                                                            $status = 'Low Stock';
                                                        }
                                                    @endphp
                                                    <span class="badge badge-{{ $status == 'In Stock' ? 'success' : ($status == 'Low Stock' ? 'warning' : 'danger') }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('admin.pharmacy.drugs.view', $drug->id) }}">View Details</a>
                                                            <a class="dropdown-item" href="{{ route('admin.pharmacy.drugs.edit', $drug->id) }}">Edit Medicine</a>
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#updateStockModal" data-drug-id="{{ $drug->id }}" data-drug-name="{{ $drug->name }}" data-current-stock="{{ $drug->batches->sum('received_quantity') }}">Update Stock</a>
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewHistoryModal" data-drug-id="{{ $drug->id }}" data-drug-name="{{ $drug->name }}">View History</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-danger delete-drug" href="#" data-drug-id="{{ $drug->id }}" data-drug-name="{{ $drug->name }}">Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="prescription">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Medicine Name</th>
                                                <th>Category</th>
                                                <th>Stock</th>
                                                <th>Expiry Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\Drug::where('is_controlled', true)->with('batches')->limit(10)->get() as $drug)
                                            <tr>
                                                <td>{{ $drug->id }}</td>
                                                <td>{{ $drug->name }}</td>
                                                <td>{{ $drug->category }}</td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                    @endphp
                                                    {{ $totalStock }}
                                                </td>
                                                <td>
                                                    @if($drug->batches->isNotEmpty())
                                                        {{ $drug->batches->first()->expiry_date->format('Y-m-d') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                        $status = 'In Stock';
                                                        if ($totalStock == 0) {
                                                            $status = 'Out of Stock';
                                                        } elseif ($totalStock < 50) { // Assuming 50 is the low stock threshold
                                                            $status = 'Low Stock';
                                                        }
                                                    @endphp
                                                    <span class="badge badge-{{ $status == 'In Stock' ? 'success' : ($status == 'Low Stock' ? 'warning' : 'danger') }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">View</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="otc">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Medicine Name</th>
                                                <th>Category</th>
                                                <th>Stock</th>
                                                <th>Expiry Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\Drug::where('is_controlled', false)->with('batches')->limit(10)->get() as $drug)
                                            <tr>
                                                <td>{{ $drug->id }}</td>
                                                <td>{{ $drug->name }}</td>
                                                <td>{{ $drug->category }}</td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                    @endphp
                                                    {{ $totalStock }}
                                                </td>
                                                <td>
                                                    @if($drug->batches->isNotEmpty())
                                                        {{ $drug->batches->first()->expiry_date->format('Y-m-d') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                        $status = 'In Stock';
                                                        if ($totalStock == 0) {
                                                            $status = 'Out of Stock';
                                                        } elseif ($totalStock < 50) { // Assuming 50 is the low stock threshold
                                                            $status = 'Low Stock';
                                                        }
                                                    @endphp
                                                    <span class="badge badge-{{ $status == 'In Stock' ? 'success' : ($status == 'Low Stock' ? 'warning' : 'danger') }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">View</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="controlled">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Medicine Name</th>
                                                <th>Category</th>
                                                <th>Stock</th>
                                                <th>Expiry Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\Drug::where('is_controlled', true)->with('batches')->limit(10)->get() as $drug)
                                            <tr>
                                                <td>{{ $drug->id }}</td>
                                                <td>{{ $drug->name }}</td>
                                                <td>{{ $drug->category }}</td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                    @endphp
                                                    {{ $totalStock }}
                                                </td>
                                                <td>
                                                    @if($drug->batches->isNotEmpty())
                                                        {{ $drug->batches->first()->expiry_date->format('Y-m-d') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $totalStock = $drug->batches->sum('received_quantity');
                                                        $status = 'In Stock';
                                                        if ($totalStock == 0) {
                                                            $status = 'Out of Stock';
                                                        } elseif ($totalStock < 50) { // Assuming 50 is the low stock threshold
                                                            $status = 'Low Stock';
                                                        }
                                                    @endphp
                                                    <span class="badge badge-{{ $status == 'In Stock' ? 'success' : ($status == 'Low Stock' ? 'warning' : 'danger') }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">View</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>         
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Recent</strong> Activities</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>User</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Received new stock batch</td>
                                        <td>John Doe (Primary Pharmacist)</td>
                                        <td>2025-10-02 14:30</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Approved stock transfer</td>
                                        <td>Jane Smith (Primary Pharmacist)</td>
                                        <td>2025-10-02 13:45</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Requested stock transfer</td>
                                        <td>Robert Johnson (Senior Pharmacist)</td>
                                        <td>2025-10-02 12:15</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Processed pharmacy sale</td>
                                        <td>Mary Williams (Clinic Pharmacist)</td>
                                        <td>2025-10-02 11:20</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1" role="dialog" aria-labelledby="updateStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStockModalLabel">Update Stock: <span id="modal-drug-name">Drug Name</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Current stock:</strong> <span id="modal-current-stock">0</span> units</p>
                <form id="update-stock-form">
                    @csrf
                    <input type="hidden" id="drug-id" name="drug_id">
                    <div class="form-group">
                        <label for="action-type">Action</label>
                        <select class="form-control" id="action-type" name="action_type" required>
                            <option value="add">Add Stock</option>
                            <option value="remove">Remove Stock</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="batch-number">Batch Number</label>
                        <input type="text" class="form-control" id="batch-number" name="batch_number" required>
                    </div>
                    <div class="form-group">
                        <label for="expiry-date">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry-date" name="expiry_date" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="update-stock-btn">Update Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- View History Modal -->
<div class="modal fade" id="viewHistoryModal" tabindex="-1" role="dialog" aria-labelledby="viewHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewHistoryModalLabel">Transaction History: <span id="history-drug-name">Drug Name</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>View all stock movements and transactions for this medicine all real time information</p>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." id="historySearch">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="historyTypeFilter">
                            <option value="">All Types</option>
                            <option value="received">Received</option>
                            <option value="sold">Sold</option>
                            <option value="transferred">Transferred</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Date Range" id="historyDateRange">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Reference</th>
                                <th>User</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- History items will be populated here -->
                            <tr>
                                <td colspan="6" class="text-center">No transaction history found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDrugModal" tabindex="-1" role="dialog" aria-labelledby="deleteDrugModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDrugModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete-drug-name">this drug</strong>? This action cannot be undone.</p>
                <p class="text-danger">Note: Drugs with existing prescriptions cannot be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/index.js') }}"></script>
<script>
    // Handle modal data population
    $('#updateStockModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var drugId = button.data('drug-id');
        var drugName = button.data('drug-name');
        var currentStock = button.data('current-stock');
        
        var modal = $(this);
        modal.find('#modal-drug-name').text(drugName);
        modal.find('#modal-current-stock').text(currentStock);
        modal.find('#drug-id').val(drugId);
    });
    
    // Handle form submission
    $('#update-stock-btn').on('click', function() {
        var formData = $('#update-stock-form').serialize();
        
        $.ajax({
            url: '{{ route('admin.pharmacy.stock.update') }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#updateStockModal').modal('hide');
                alert('Stock updated successfully!');
                location.reload(); // Refresh the page to show updated stock
            },
            error: function(xhr) {
                alert('Error updating stock. Please try again.');
            }
        });
    });
    
    // Handle view history modal data population
    $('#viewHistoryModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var drugId = button.data('drug-id');
        var drugName = button.data('drug-name');
        
        var modal = $(this);
        modal.find('#history-drug-name').text(drugName);
        
        // Load actual transaction history data via AJAX
        $.ajax({
            url: '/admin/pharmacy/drugs/' + drugId + '/history',
            method: 'GET',
            success: function(response) {
                var historyTableBody = $('#historyTableBody');
                historyTableBody.empty();
                
                if (response.history && response.history.length > 0) {
                    response.history.forEach(function(item) {
                        var row = '<tr>' +
                            '<td>' + item.date + '</td>' +
                            '<td>' + item.type + '</td>' +
                            '<td>' + item.quantity + '</td>' +
                            '<td>' + item.reference + '</td>' +
                            '<td>' + item.user + '</td>' +
                            '<td>' + item.notes + '</td>' +
                            '</tr>';
                        historyTableBody.append(row);
                    });
                } else {
                    historyTableBody.append('<tr><td colspan="6" class="text-center">No transaction history found</td></tr>');
                }
            },
            error: function(xhr) {
                var historyTableBody = $('#historyTableBody');
                historyTableBody.empty();
                historyTableBody.append('<tr><td colspan="6" class="text-center">Error loading transaction history</td></tr>');
            }
        });
    });
    
    // Handle delete confirmation
    var deleteDrugId = null;
    
    $('.delete-drug').on('click', function(e) {
        e.preventDefault();
        var drugId = $(this).data('drug-id');
        var drugName = $(this).data('drug-name');
        
        deleteDrugId = drugId;
        $('#delete-drug-name').text(drugName);
        $('#deleteDrugModal').modal('show');
    });
    
    $('#confirm-delete-btn').on('click', function() {
        if (deleteDrugId) {
            // Create a form dynamically and submit it
            var form = $('<form>', {
                'method': 'POST',
                'action': '/admin/pharmacy/drugs/' + deleteDrugId
            });
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_method',
                'value': 'DELETE'
            }));
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': '{{ csrf_token() }}'
            }));
            
            $('body').append(form);
            form.submit();
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('medicineSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const stockFilter = document.getElementById('stockFilter');
    const medicineRows = document.querySelectorAll('#all tbody tr');
    
    // Function to filter medicines
    function filterMedicines() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCategory = categoryFilter.value;
        const selectedStock = stockFilter.value;
        
        medicineRows.forEach(function(row) {
            const name = row.cells[1].textContent.toLowerCase();
            const category = row.cells[2].textContent;
            const statusCell = row.cells[5].textContent.toLowerCase();
            
            // Check search term
            const matchesSearch = searchTerm === '' || name.includes(searchTerm);
            
            // Check category filter
            const matchesCategory = selectedCategory === '' || category === selectedCategory;
            
            // Check stock filter
            let matchesStock = true;
            if (selectedStock === 'in_stock') {
                matchesStock = statusCell.includes('in stock');
            } else if (selectedStock === 'low_stock') {
                matchesStock = statusCell.includes('low stock');
            } else if (selectedStock === 'out_of_stock') {
                matchesStock = statusCell.includes('out of stock');
            }
            
            // Show/hide row based on all filters
            if (matchesSearch && matchesCategory && matchesStock) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterMedicines);
    }
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterMedicines);
    }
    
    if (stockFilter) {
        stockFilter.addEventListener('change', filterMedicines);
    }
});
</script>
</body>
</html>