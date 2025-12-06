<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Primary Pharmacist prescriptions management">
<title>ClinicalPro || Prescriptions</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

<!-- Include Primary Pharmacist Sidemenu -->
@include('pharmacy.primary_pharmacist.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                <h2 class="m-0"><i class="zmdi zmdi-file-text"></i> <span>Prescriptions</span></h2>
                <ul class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Prescriptions</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>All</strong> Prescriptions</h2>
                        <p>View and manage all patient prescriptions.</p>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Search and filters -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Search by patient, doctor, or prescription ID..." value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="searchButton"><i class="zmdi zmdi-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="sortSelect">
                                            <option value="">Sort by Date (Newest)</option>
                                            <option value="patient" {{ request('sort') == 'patient' ? 'selected' : '' }}>Sort by Patient</option>
                                            <option value="doctor" {{ request('sort') == 'doctor' ? 'selected' : '' }}>Sort by Doctor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-secondary" id="resetFilters">Reset</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <!-- Actions -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->id }}</td>
                                        <td>
                                            @if($prescription->patient)
                                                {{ $prescription->patient->name }}
                                                <br><small class="text-muted">{{ $prescription->patient->user_id }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($prescription->doctor)
                                                Dr. {{ $prescription->doctor->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $prescription->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $prescription->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($prescription->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $prescription->items->count() }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('primary_pharmacist.prescriptions.show', $prescription->id) }}">View Details</a>
                                                    <a class="dropdown-item" href="{{ route('primary_pharmacist.prescriptions.show', $prescription->id) }}">Print Prescription</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No prescriptions found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="pagination-container">
                            {{ $prescriptions->links() }}
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
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const sortSelect = document.getElementById('sortSelect');
    const resetButton = document.getElementById('resetFilters');
    
    function updateUrl() {
        const url = new URL(window.location);
        const search = searchInput.value.trim();
        const sort = sortSelect.value;
        
        // Clear existing params
        url.searchParams.delete('search');
        url.searchParams.delete('sort');
        
        // Add new params
        if (search) {
            url.searchParams.set('search', search);
        }
        if (sort) {
            url.searchParams.set('sort', sort);
        }
        
        window.location.href = url;
    }
    
    if (searchButton) {
        searchButton.addEventListener('click', updateUrl);
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateUrl();
            }
        });
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', updateUrl);
    }
    
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    }
});
</script>
</body>
</html>