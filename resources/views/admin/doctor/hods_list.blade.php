<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Clinical Pro :: Department Heads</title>
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
<!-- Overlay For Sidebars -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Department Heads</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Doctors</a></li>
                    <li class="breadcrumb-item active">Department Heads</li>
                </ul>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <div class="d-flex justify-content-end align-items-center">
                    <button class="btn btn-primary btn-icon right_icon_toggle_btn" type="button">
                        <i class="zmdi zmdi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Department</strong> Heads</h2>
                        <!-- Search Form -->
                        <div class="col-md-4 float-right">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search HODs..." id="hodSearch" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="hodTableBody">
                                    @forelse($hods as $hod)
                                    <tr class="hod-row" data-name="{{ strtolower($hod->name ?? '') }}" data-email="{{ strtolower($hod->email ?? '') }}">
                                        <td>
                                            @if($hod->photo)
                                                <img src="{{ asset('storage/' . $hod->photo) }}" class="rounded-circle" alt="profile-image" width="40">
                                            @else
                                                <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="profile-image" width="40">
                                            @endif
                                            <span>{{ $hod->name }}</span>
                                        </td>
                                        <td>
                                            @if($hod->doctor && $hod->doctor->department)
                                                {{ $hod->doctor->department->name }}
                                            @else
                                                Not assigned
                                            @endif
                                        </td>
                                        <td>{{ $hod->email }}</td>
                                        <td>{{ $hod->phone ?? 'Not provided' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="zmdi zmdi-eye"></i> View Profile
                                                    </a>
                                                    <!-- Assign Doctor Role Button -->
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to assign this HOD back to doctor role?')) { document.getElementById('assign-doctor-form-{{ $hod->id }}').submit(); }">
                                                        <i class="zmdi zmdi-account"></i> Assign as Doctor
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <!-- Assign Doctor Role Form -->
                                            <form id="assign-doctor-form-{{ $hod->id }}" action="{{ route('admin.doctor.assign-doctor-role', $hod->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No department heads found</td>
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

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 

<!-- Live Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('hodSearch');
    const hodRows = document.querySelectorAll('.hod-row');
    
    // Function to filter HODs
    function filterHODs() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        hodRows.forEach(function(row) {
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
        searchInput.addEventListener('input', filterHODs);
        
        // Also trigger search on page load if there's a search term
        if (searchInput.value) {
            filterHODs();
        }
    }
});
</script>
</body>
</html>