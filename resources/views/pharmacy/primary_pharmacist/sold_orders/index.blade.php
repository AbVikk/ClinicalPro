<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="ClinicalPro - Pharmacy Management System">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Sold Orders - ClinicalPro</title>
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<!-- Favicon-->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/morrisjs/morris.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css') }}" />
<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
<style>
.filter-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: end;
}
.filter-group {
    flex: 1;
    min-width: 200px;
}
.filter-actions {
    display: flex;
    gap: 10px;
    align-self: end;
    margin-bottom: 10px;
}
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
.pagination {
    margin: 0;
}
.table th {
    font-weight: 600;
}
.badge-status {
    font-size: 0.85em;
    padding: 5px 10px;
}
.order-items-list {
    max-height: 100px;
    overflow-y: auto;
}
.loading-spinner {
    display: none;
    text-align: center;
    padding: 20px;
}
.loading-spinner.show {
    display: block;
}
.no-results {
    text-align: center;
    padding: 40px 20px;
}
.live-search-info {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 5px;
}
</style>
</head>

<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

@include('pharmacy.primary_pharmacist.sidemenu')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Sold Pharmacy Orders</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('primary_pharmacist.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sold Orders</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <!-- Filter Card -->
                <div class="filter-card">
                    <h5 class="mb-4">Filter Orders</h5>
                    <form id="live-filter-form" class="filter-form">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label class="form-label">Search All Columns</label>
                                <input type="text" class="form-control" id="search-input" name="search" placeholder="Search by patient, pharmacist, clinic, drug, order ID, amount..." value="{{ $search }}">
                                
                            </div>
                            <div class="filter-group">
                                <label class="form-label">Pharmacist</label>
                                <select class="form-control" id="pharmacist-select" name="pharmacist">
                                    <option value="">All Pharmacists</option>
                                    @foreach($pharmacists as $pharmacist)
                                        <option value="{{ $pharmacist->id }}" {{ $pharmacistId == $pharmacist->id ? 'selected' : '' }}>
                                            {{ $pharmacist->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start-date-input" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="filter-group">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end-date-input" name="end_date" value="{{ $endDate }}">
                            </div>
                            <div class="filter-actions">
                                <button type="button" id="reset-filters" class="btn btn-outline-secondary">
                                    <i class="zmdi zmdi-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Orders Card -->
                <div class="card">
                    <div class="header">
                        <h2>Sold Orders <small>All completed pharmacy orders</small></h2>
                    </div>
                    <div class="body">
                        <!-- Loading Spinner -->
                        <div class="loading-spinner" id="loading-spinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Loading orders...</p>
                        </div>

                        <!-- Orders Table -->
                        <div class="table-responsive" id="orders-table-container">
                            <table class="table table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Patient</th>
                                        <th>Pharmacist</th>
                                        <th>Clinic</th>
                                        <th>Items</th>
                                        <th>Total Amount</th>
                                        <th>Date</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-table-body">
                                    @include('pharmacy.primary_pharmacist.sold_orders.partials.table')
                                </tbody>
                            </table>
                        </div>

                        <!-- Improved Pagination -->
                        <div class="pagination-wrapper" id="pagination-container">
                            @include('pharmacy.primary_pharmacist.sold_orders.partials.pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Details Modals -->
@foreach($orders as $order)
<div class="modal fade" id="orderDetailsModal{{ $order->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details - #{{ $order->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="zmdi zmdi-account"></i> Patient Information</h6>
                        <hr>
                        <p>
                            @if($order->patient)
                                <strong>Name:</strong> {{ $order->patient->name }}<br>
                                <strong>ID:</strong> {{ $order->patient->user_id }}<br>
                                <strong>Phone:</strong> {{ $order->patient->phone ?? 'N/A' }}
                            @else
                                <span class="text-muted">Walk-in Customer</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="zmdi zmdi-info"></i> Order Information</h6>
                        <hr>
                        <p>
                            <strong>Order ID:</strong> #{{ $order->id }}<br>
                            <strong>Pharmacist:</strong> {{ $order->pharmacist->name ?? 'N/A' }}<br>
                            <strong>Clinic:</strong> {{ $order->clinic->name ?? 'N/A' }}<br>
                            <strong>Date:</strong> {{ $order->created_at->format('d M, Y H:i') }}<br>
                            <strong>Status:</strong> 
                            <span class="badge badge-success badge-status">{{ ucfirst($order->status) }}</span>
                        </p>
                    </div>
                </div>

                <h6><i class="zmdi zmdi-shopping-cart"></i> Items</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Drug</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->drug->name ?? 'Unknown Drug' }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">₦{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right">₦{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total Amount:</th>
                                <th class="text-right">₦{{ number_format($order->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Scripts -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>

<script>
// Live search and filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const searchInput = document.getElementById('search-input');
    const pharmacistSelect = document.getElementById('pharmacist-select');
    const startDateInput = document.getElementById('start-date-input');
    const endDateInput = document.getElementById('end-date-input');
    const resetButton = document.getElementById('reset-filters');
    const ordersTableBody = document.getElementById('orders-table-body');
    const loadingSpinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    
    let debounceTimer;
    
    // Function to perform live search
    function performLiveSearch() {
        // Clear any existing timer
        clearTimeout(debounceTimer);
        
        // Show loading spinner
        loadingSpinner.classList.add('show');
        
        // Set a new timer
        debounceTimer = setTimeout(function() {
            const searchValue = searchInput.value;
            const pharmacistValue = pharmacistSelect.value;
            const startDateValue = startDateInput.value;
            const endDateValue = endDateInput.value;
            
            // Build query string
            const params = new URLSearchParams();
            if (searchValue) params.append('search', searchValue);
            if (pharmacistValue) params.append('pharmacist', pharmacistValue);
            if (startDateValue) params.append('start_date', startDateValue);
            if (endDateValue) params.append('end_date', endDateValue);
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make AJAX request
            fetch('{{ route('primary_pharmacist.sold-orders.index') }}?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update table and pagination
                ordersTableBody.innerHTML = data.table;
                paginationContainer.innerHTML = data.pagination;
                
                // Hide loading spinner
                loadingSpinner.classList.remove('show');
            })
            .catch(error => {
                console.error('Error:', error);
                loadingSpinner.classList.remove('show');
                ordersTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>';
            });
        }, 300); // 300ms debounce delay
    }
    
    // Add event listeners for live search
    if (searchInput) searchInput.addEventListener('input', performLiveSearch);
    if (pharmacistSelect) pharmacistSelect.addEventListener('change', performLiveSearch);
    if (startDateInput) startDateInput.addEventListener('change', performLiveSearch);
    if (endDateInput) endDateInput.addEventListener('change', performLiveSearch);
    
    // Reset filters
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (pharmacistSelect) pharmacistSelect.value = '';
            if (startDateInput) startDateInput.value = '';
            if (endDateInput) endDateInput.value = '';
            performLiveSearch();
        });
    }
    
    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.page-link')) {
            e.preventDefault();
            const url = e.target.closest('.page-link').href;
            if (url) {
                // Show loading spinner
                loadingSpinner.classList.add('show');
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Make AJAX request for pagination
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update table and pagination
                    ordersTableBody.innerHTML = data.table;
                    paginationContainer.innerHTML = data.pagination;
                    
                    // Hide loading spinner
                    loadingSpinner.classList.remove('show');
                    
                    // Scroll to top of table
                    document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingSpinner.classList.remove('show');
                    ordersTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>';
                });
            }
        }
    });
});
</script>
</body>
</html>