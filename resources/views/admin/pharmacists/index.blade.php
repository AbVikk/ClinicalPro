<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Clinical Pro :: Pharmacists</title>
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
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

@include('admin.sidemenu')

<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Pharmacists</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item active">Pharmacists</li>
                </ul>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>{{ $pageTitle ?? 'All Pharmacists List' }}</h2>
                        <!-- Search Form -->
                        <div class="col-md-4 float-right">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search pharmacists..." id="pharmacistSearch" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            
                            <li class="nav-item">
                                <a class="nav-link {{ is_null($roleFilter) ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index') }}" 
                                   role="tab">
                                    All Pharmacists
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $roleFilter === 'primary_pharmacist' ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index', ['role' => 'primary_pharmacist']) }}" 
                                   role="tab">
                                    Primary Pharmacists
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $roleFilter === 'senior_pharmacist' ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index', ['role' => 'senior_pharmacist']) }}" 
                                   role="tab">
                                    Senior Pharmacists
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $roleFilter === 'clinic_pharmacist' ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index', ['role' => 'clinic_pharmacist']) }}" 
                                   role="tab">
                                    Clinic Pharmacists
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pharmacistTableBody">
                                        @forelse($pharmacists as $pharmacist)
                                            <tr class="pharmacist-row" data-name="{{ strtolower($pharmacist->name) }}" data-email="{{ strtolower($pharmacist->email) }}">
                                                <td>{{ $loop->iteration + ($pharmacists->currentPage() - 1) * $pharmacists->perPage() }}</td>
                                                <td><a href="{{ route('admin.pharmacists.show', $pharmacist) }}">{{ $pharmacist->name }}</a></td>
                                                <td>{{ $pharmacist->email }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $pharmacist->role)) }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $pharmacist->status === 'active' ? 'success' : ($pharmacist->status === 'pending' ? 'warning' : 'danger') }}">
                                                         {{ ucwords($pharmacist->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('admin.pharmacists.show', $pharmacist) }}">
                                                                <i class="zmdi zmdi-eye"></i> View Profile
                                                            </a>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="zmdi zmdi-edit"></i> Edit
                                                            </a>
                                                            @if($pharmacist->status !== 'active')
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to activate this pharmacist?')) { document.getElementById('activate-form-{{ $pharmacist->id }}').submit(); }">
                                                                    <i class="zmdi zmdi-check"></i> Activate
                                                                </a>
                                                            @else
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to suspend this pharmacist?')) { document.getElementById('suspend-form-{{ $pharmacist->id }}').submit(); }">
                                                                    <i class="zmdi zmdi-block"></i> Suspend
                                                                </a>
                                                            @endif
                                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this pharmacist? This action cannot be undone.')) { document.getElementById('delete-form-{{ $pharmacist->id }}').submit(); }">
                                                                <i class="zmdi zmdi-delete"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Activate Form -->
                                                    <form id="activate-form-{{ $pharmacist->id }}" action="{{ route('admin.pharmacists.update', $pharmacist) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="active">
                                                    </form>
                                                    
                                                    <!-- Suspend Form -->
                                                    <form id="suspend-form-{{ $pharmacist->id }}" action="{{ route('admin.pharmacists.update', $pharmacist) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="suspended">
                                                    </form>
                                                    
                                                    <!-- Delete Form -->
                                                    <form id="delete-form-{{ $pharmacist->id }}" action="{{ route('admin.pharmacists.destroy', $pharmacist) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No pharmacists found for this role.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $pharmacists->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 

<!-- Live Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pharmacistSearch');
    const pharmacistRows = document.querySelectorAll('.pharmacist-row');
    
    // Function to filter pharmacists
    function filterPharmacists() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        pharmacistRows.forEach(function(row) {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            
            // Check if any of the fields contain the search term
            if (searchTerm === '' || 
                name.includes(searchTerm) || 
                email.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listener for live search
    if (searchInput) {
        searchInput.addEventListener('input', filterPharmacists);
        
        // Also trigger search on page load if there's a search term
        if (searchInput.value) {
            filterPharmacists();
        }
    }
});
</script>
</body>
</html>