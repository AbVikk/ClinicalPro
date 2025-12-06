<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Senior Pharmacist Dashboard - Clinic Inventory Management">
<title>Clinical Pro || Senior Pharmacist Dashboard</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">

@include('pharmacy.senior_pharmacist.sidemenu')

<section class="content">
<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Senior Pharmacist Dashboard
            <small>Clinic Inventory Management</small>
            </h2>
        </div>            
        <div class="col-lg-7 col-md-7 col-sm-12 text-right">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('senior_pharmacist.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                <li class="breadcrumb-item active">Pharmacy</li>
            </ul>
        </div>
    </div>
</div>
<div class="container-fluid">
    
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <strong>AI Restock Prediction</strong> 
                        <small>Recommendations for the next 30 days based on historical usage.</small>
                    </h2>
                    <ul class="header-dropdown">
                        <li>
                            <button class="btn btn-sm btn-primary" id="refreshPrediction">
                                <i class="zmdi zmdi-refresh"></i> Re-Analyze
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @if($predictions->isEmpty())
                        <div class="alert alert-info" role="alert">
                            <strong>No Predictions Available:</strong> No inventory predictions have been generated yet. 
                            Please run the inventory prediction job to generate forecasts.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Drug Name</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Predicted Quantity</th>
                                        <th>Reorder Point</th>
                                        <th>Recommended Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($predictions as $prediction)
                                    <tr>
                                        <td>{{ $prediction->drug_name }}</td>
                                        <td>{{ $prediction->month }}</td>
                                        <td>{{ $prediction->year }}</td>
                                        <td>{{ $prediction->predicted_quantity }}</td>
                                        <td>{{ $prediction->reorder_point }}</td>
                                        <td>{{ $prediction->recommended_order_quantity }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="body">
                    <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $totalDrugs }}" data-speed="2500" data-fresh-interval="700">
                        {{ $totalDrugs }} 
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
                    <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $totalBatches }}" data-speed="2500" data-fresh-interval="700">
                        {{ $totalBatches }} 
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
                    <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $pendingRequests }}" data-speed="2500" data-fresh-interval="700">
                        {{ $pendingRequests }} 
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
                    <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $activePrescriptions }}" data-speed="2500" data-fresh-interval="700">
                        {{ $activePrescriptions }} 
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
                    <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $lowStockItems }}" data-speed="2500" data-fresh-interval="700">
                        {{ $lowStockItems }} 
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
                    <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $expiringSoon }}" data-speed="2500" data-fresh-interval="700">
                        {{ $expiringSoon }} 
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
                    <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $totalCategories }}" data-speed="2500" data-fresh-interval="700">
                        {{ $totalCategories }} 
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
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <strong>Clinic Inventory</strong> 
                        <small>Current stock levels for clinic inventories.</small>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>Clinic</th>
                                    <th>Drug Name</th>
                                    <th>Current Stock</th>
                                    <th>Unit Price (NGN)</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop through your inventory items --}}
                                @forelse($inventory as $item)
                                <tr>
                                    <td>{{ $item->clinic->name ?? 'N/A' }}</td>
                                    <td>{{ $item->drug->name ?? 'N/A' }}</td>
                                    <td>
                                        <strong>{{ $item->stock_level }}</strong> 
                                        @php
                                            if($item->stock_level < 20)
                                                echo '<span class="badge badge-danger">Critical</span>';
                                            elseif($item->stock_level < 50)
                                                echo '<span class="badge badge-warning">Low</span>';
                                        @endphp
                                    </td>
                                    <td>{{ number_format($item->drug->unit_price ?? 0, 2) }}</td>
                                    <td>{{ $item->batch && $item->batch->expiry_date ? $item->batch->expiry_date->format('M d, Y') : 'N/A' }}</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">Transfer</a>
                                        <a href="#" class="btn btn-sm btn-outline-info">Request</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No inventory items found.</td>
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
</section>

<!-- Scripts -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/index.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Refresh Button Logic (AJAX)
        document.getElementById('refreshPrediction').addEventListener('click', function() {
            alert('Inventory prediction refresh would be implemented here. In a real system, this would trigger the AI analysis.');
        });
    });
</script>
</body>
</html>