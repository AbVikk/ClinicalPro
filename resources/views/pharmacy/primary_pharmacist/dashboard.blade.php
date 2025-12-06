<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Primary Pharmacist Dashboard - AI-Powered Inventory Management">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Clinical Pro || Primary Pharmacist Dashboard</title>
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
.dashboard-card {
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    border: none;
    margin-bottom: 25px;
}
.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 20px rgba(0,0,0,0.15);
}
.card-header-custom {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    padding: 20px 25px;
    background-color: #fff;
    border-radius: 12px 12px 0 0 !important;
}
.card-body-custom {
    padding: 25px;
}
.card-icon {
    font-size: 2.8rem;
    opacity: 0.85;
    margin-bottom: 15px;
}
.sales-card .card-icon { color: #4CAF50; }
.products-card .card-icon { color: #2196F3; }
.inventory-card .card-icon { color: #FF9800; }
.profit-card .card-icon { color: #9C27B0; }
.metric-value { font-size: 2rem; font-weight: 700; margin-bottom: 5px; color: #333; }
.metric-label { font-size: 1rem; opacity: 0.7; font-weight: 500; }
.top-product-rank {
    display: inline-block; width: 30px; height: 30px; line-height: 30px;
    text-align: center; border-radius: 50%; background: #2196F3; color: white;
    font-size: 0.9rem; font-weight: 600; margin-right: 15px;
}
.rank-1 { background: #FFD700; color: #333; }
.rank-2 { background: #C0C0C0; }
.rank-3 { background: #CD7F32; }
.card-spacing { padding: 0 15px; margin-bottom: 30px; }
@media (max-width: 768px) { .card-spacing { padding: 0; margin-bottom: 20px; } }
</style>
</head>
<body class="theme-cyan">

@include('pharmacy.primary_pharmacist.sidemenu')

<section class="content">
<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Primary Pharmacist Dashboard
            <small>AI-Powered Inventory Management</small>
            </h2>
        </div>            
        <div class="col-lg-7 col-md-7 col-sm-12 text-right">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('primary_pharmacist.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                <li class="breadcrumb-item active">Pharmacy</li>
            </ul>
        </div>
    </div>
</div>
<div class="container-fluid">
    
    <div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card sales-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-money-box card-icon"></i>
                    <div class="metric-value">₦{{ number_format($totalSalesAmount ?? 0, 2) }}</div>
                    <div class="metric-label">Total Sales Value</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card sales-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-shopping-cart card-icon"></i>
                    <div class="metric-value">{{ number_format($totalSalesCount ?? 0) }}</div>
                    <div class="metric-label">Total Sales</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card profit-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-trending-up card-icon"></i>
                    <div class="metric-value">₦{{ number_format($totalProfit ?? 0, 2) }}</div>
                    <div class="metric-label">Est. Profit (20%)</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card products-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-collection-item card-icon"></i>
                    <div class="metric-value">{{ number_format($totalDrugs ?? 0) }}</div>
                    <div class="metric-label">Total Products</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card inventory-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-alert-triangle card-icon"></i>
                    <div class="metric-value">{{ number_format($lowStockItems ?? 0) }}</div>
                    <div class="metric-label">Low Stock Items</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card inventory-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-block card-icon"></i>
                    <div class="metric-value">{{ number_format($outOfStockItems ?? 0) }}</div>
                    <div class="metric-label">Out of Stock</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card inventory-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-time-restore card-icon"></i>
                    <div class="metric-value">{{ number_format($expiringSoon ?? 0) }}</div>
                    <div class="metric-label">Expiring Soon</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 card-spacing">
            <div class="card dashboard-card inventory-card">
                <div class="card-body card-body-custom text-center">
                    <i class="zmdi zmdi-truck card-icon"></i>
                    <div class="metric-value">{{ number_format($pendingRequests ?? 0) }}</div>
                    <div class="metric-label">Pending Requests</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row clearfix row-spacing">
        <div class="col-lg-12">
            <div class="card dashboard-card">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <strong>🧠 AI Inventory Forecast</strong> 
                        <small class="text-muted ml-2">Powered by Google Gemini</small>
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" id="refreshPrediction">
                        <i class="zmdi zmdi-refresh"></i> Run Analysis Now
                    </button>
                </div>
                <div class="card-body card-body-custom">
                    @if($predictions->isEmpty())
                        <div class="text-center py-4">
                            <i class="zmdi zmdi-brain" style="font-size: 3rem; color: #ddd;"></i>
                            <p class="mt-3 text-muted">No predictions generated yet. Click "Run Analysis Now" to analyze sales trends.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Drug Name</th>
                                        <th>Target Month</th>
                                        <th class="text-center">Predicted Sales</th>
                                        <th class="text-center">Reorder Point</th>
                                        <th class="text-center">Rec. Order Qty</th>
                                        <th>AI Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($predictions as $prediction)
                                    <tr>
                                        <td class="font-weight-bold">{{ $prediction->drug_name }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $prediction->month }} {{ $prediction->year }}</span>
                                        </td>
                                        <td class="text-center text-primary font-weight-bold">{{ $prediction->predicted_quantity }}</td>
                                        <td class="text-center text-warning">{{ $prediction->reorder_point }}</td>
                                        <td class="text-center text-success font-weight-bold">{{ $prediction->recommended_order_quantity }}</td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($prediction->notes, 50) }}</small>
                                        </td>
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
        <div class="col-lg-12">
            <div class="card dashboard-card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0"><strong>Current Inventory</strong> <small class="text-muted ml-2">Live Stock Levels</small></h5>
                </div>
                <div class="card-body card-body-custom">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Total Stock</th>
                                    <th>Unit Price</th>
                                    <th>Last Updated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventory as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <strong>{{ $item->batches->sum('received_quantity') }}</strong> 
                                        @php
                                            $totalStock = $item->batches->sum('received_quantity');
                                            $badge = $totalStock <= 0 ? 'danger' : ($totalStock < 20 ? 'warning' : 'success');
                                            $text = $totalStock <= 0 ? 'Out' : ($totalStock < 20 ? 'Low' : 'Good');
                                        @endphp
                                        <span class="badge badge-{{ $badge }} ml-1">{{ $text }}</span>
                                    </td>
                                    <td>₦{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->updated_at->format('M d, Y') }}</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <a href="{{ route('primary_pharmacist.pharmacy.drugs.view', $item->id) }}" class="btn btn-sm btn-default"><i class="zmdi zmdi-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No inventory found.</td>
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

<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/index.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Trigger AI Analysis
        document.getElementById('refreshPrediction').addEventListener('click', function() {
            // 1. Show Loading
            Swal.fire({
                title: 'Running AI Analysis...',
                text: 'Connecting to Google Gemini. Please wait (this may take 10-20 seconds).',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });

            // 2. Call Backend
            fetch("{{ route('primary_pharmacist.inventory.run-analysis') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Analysis Complete!',
                        text: 'Inventory predictions have been updated.',
                        confirmButtonText: 'Refresh Page'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire('Error', 'Analysis failed to complete.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to run analysis. Please check logs.', 'error');
            });
        });
    });
</script>
</body>
</html>