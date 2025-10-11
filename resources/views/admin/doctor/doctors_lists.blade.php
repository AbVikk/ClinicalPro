<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Doctors List</title>
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
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Oreo"></div>
        <p>Please wait...</p>
    </div>
</div>
<!-- Overlay For Sidebars -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Doctors List</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Doctors</a></li>
                    <li class="breadcrumb-item active">Doctors List</li>
                </ul>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <div class="d-flex justify-content-end align-items-center">
                    <a href="{{ route('admin.doctor.add') }}" class="btn btn-success btn-icon mr-2">
                        <i class="zmdi zmdi-plus"></i>
                    </a>
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
                        <h2><strong>Doctors</strong> List</h2>
                        <!-- Search Form -->
                        <div class="col-md-4 float-right">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search doctors..." id="doctorSearch" value="{{ request('search') }}">
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
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Specialty</th>
                                        <th>Status</th>
                                        <th>Patients</th>
                                        <th>Experience</th>
                                        <th>Contact</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="doctorTableBody">
                                    @foreach($doctors as $doctor)
                                    <tr class="doctor-row" data-name="{{ strtolower($doctor->user->name ?? '') }}" data-email="{{ strtolower($doctor->user->email ?? '') }}" data-phone="{{ strtolower($doctor->user->phone ?? '') }}">
                                        <td>
                                            @if($doctor->user)
                                                @if($doctor->user->photo)
                                                    <img src="{{ asset('storage/' . $doctor->user->photo) }}" class="rounded-circle" alt="profile-image" width="40">
                                                @else
                                                    <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="profile-image" width="40">
                                                @endif
                                                <span>{{ $doctor->user->name }}</span>
                                            @else
                                                <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="profile-image" width="40">
                                                <span>Unknown Doctor</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($doctor->category)
                                                {{ $doctor->category->name }}
                                            @else
                                                {{ $doctor->specialization ?? 'Not specified' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($doctor->status == 'verified')
                                                <span class="badge badge-success">Verified</span>
                                            @elseif($doctor->status == 'suspended')
                                                <span class="badge badge-danger">Suspended</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $doctor->appointments->unique('patient_id')->count() }}</td>
                                        <td>
                                            @if($doctor->years_of_experience)
                                                {{ $doctor->years_of_experience }} year{{ $doctor->years_of_experience != 1 ? 's' : '' }}
                                            @else
                                                @if($doctor->user)
                                                    @php
                                                        $experience = \Carbon\Carbon::parse($doctor->user->created_at)->diffInYears(\Carbon\Carbon::now());
                                                        echo $experience . ' year' . ($experience != 1 ? 's' : '');
                                                    @endphp
                                                @else
                                                    N/A
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($doctor->user)
                                                <span>{{ $doctor->user->email }}</span><br>
                                                <span>{{ $doctor->user->phone ?? 'Not provided' }}</span>
                                            @else
                                                <span>No contact info</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($doctor->user_id)
                                                    <a class="dropdown-item" href="{{ route('admin.doctor.profile', $doctor->user_id) }}">
                                                        <i class="zmdi zmdi-eye"></i> View Profile
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('admin.doctor.edit', $doctor->user_id) }}">
                                                        <i class="zmdi zmdi-edit"></i> Edit
                                                    </a>
                                                    @if($doctor->status == 'verified')
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to suspend this doctor?')) { document.getElementById('suspend-form-{{ $doctor->id }}').submit(); }">
                                                        <i class="zmdi zmdi-block"></i> Suspend
                                                    </a>
                                                    @else
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to activate this doctor?')) { document.getElementById('activate-form-{{ $doctor->id }}').submit(); }">
                                                        <i class="zmdi zmdi-check"></i> Activate
                                                    </a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this doctor? This action cannot be undone.')) { document.getElementById('delete-form-{{ $doctor->id }}').submit(); }">
                                                        <i class="zmdi zmdi-delete"></i> Delete
                                                    </a>
                                                    @else
                                                    <a class="dropdown-item disabled" href="#">
                                                        <i class="zmdi zmdi-eye"></i> View Profile
                                                    </a>
                                                    <a class="dropdown-item disabled" href="#">
                                                        <i class="zmdi zmdi-edit"></i> Edit
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Suspend Form -->
                                            <form id="suspend-form-{{ $doctor->id }}" action="{{ route('admin.doctor.update', $doctor->user_id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="suspended">
                                            </form>
                                            
                                            <!-- Activate Form -->
                                            <form id="activate-form-{{ $doctor->id }}" action="{{ route('admin.doctor.update', $doctor->user_id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="verified">
                                            </form>
                                            
                                            <!-- Delete Form -->
                                            <form id="delete-form-{{ $doctor->id }}" action="{{ route('admin.doctor.destroy', $doctor->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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
    const searchInput = document.getElementById('doctorSearch');
    const doctorRows = document.querySelectorAll('.doctor-row');
    
    // Function to filter doctors
    function filterDoctors() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        doctorRows.forEach(function(row) {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const phone = row.getAttribute('data-phone');
            
            // Check if any of the fields contain the search term
            if (searchTerm === '' || 
                name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                (phone && phone.includes(searchTerm))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listener for live search
    if (searchInput) {
        searchInput.addEventListener('input', filterDoctors);
        
        // Also trigger search on page load if there's a search term
        if (searchInput.value) {
            filterDoctors();
        }
    }
});
</script>
</body>
</html>