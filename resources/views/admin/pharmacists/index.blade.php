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
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item">
                                <a class="nav-link {{ is_null($roleFilter) ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index') }}" 
                                   role="tab" data-role="all">
                                    All
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $roleFilter === 'primary_pharmacist' ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index', ['role' => 'primary_pharmacist']) }}" 
                                   role="tab" data-role="primary_pharmacist">
                                    Primary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $roleFilter === 'senior_pharmacist' ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index', ['role' => 'senior_pharmacist']) }}" 
                                   role="tab" data-role="senior_pharmacist">
                                    Senior
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $roleFilter === 'clinic_pharmacist' ? 'active' : '' }}" 
                                   href="{{ route('admin.pharmacists.index', ['role' => 'clinic_pharmacist']) }}" 
                                   role="tab" data-role="clinic_pharmacist">
                                    Clinic
                                </a>
                            </li>
                        </ul>
                        
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane table-responsive active" id="All">
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
                                            @include('admin.pharmacists.partials.table')
                                        </tbody>
                                    </table>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 

<!-- Live Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching without page reload
    const tabLinks = document.querySelectorAll('.nav-link[data-role]');
    
    tabLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabLinks.forEach(tab => tab.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Get the role filter
            const role = this.getAttribute('data-role');
            
            // Build URL with role parameter
            let url = "{{ route('admin.pharmacists.index') }}";
            if (role !== 'all') {
                url += '?role=' + role;
            }
            
            // Get search term if exists
            const searchInput = document.getElementById('pharmacistSearch');
            const searchTerm = searchInput ? searchInput.value : '';
            if (searchTerm) {
                url += (role !== 'all' ? '&' : '?') + 'search=' + encodeURIComponent(searchTerm);
            }
            
            // Fetch content via AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
                }
            })
            .then(response => response.text())
            .then(html => {
                // Update the table body directly with the returned HTML
                const currentTableBody = document.querySelector('#pharmacistTableBody');
                if (currentTableBody) {
                    currentTableBody.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
    
    // Live search functionality
    const searchInput = document.getElementById('pharmacistSearch');
    
    // Function to filter pharmacists
    function filterPharmacists() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const pharmacistRows = document.querySelectorAll('.pharmacist-row');
        
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