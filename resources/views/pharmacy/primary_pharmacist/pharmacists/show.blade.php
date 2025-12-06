<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Clinical Pro :: Pharmacist Profile</title>
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

@include('pharmacy.primary_pharmacist.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Pharmacist Profile</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('primary_pharmacist.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('primary_pharmacist.pharmacists.index') }}">Pharmacists</a></li>
                    <li class="breadcrumb-item active">{{ $pharmacist->name }}</li>
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
            <div class="col-lg-4 col-md-12">
                <div class="card profile-card">
                    <div class="profile-header">&nbsp;</div>
                    <div class="profile-body">
                        <div class="image-area">
                            @if($pharmacist->photo)
                                <img src="{{ asset('storage/' . $pharmacist->photo) }}" alt="{{ $pharmacist->name }}" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/user.png') }}" alt="{{ $pharmacist->name }}" style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="content-area">
                            <h3>{{ $pharmacist->name }}</h3>
                            <p>{{ ucwords(str_replace('_', ' ', $pharmacist->role)) }}</p>
                            <p>Status: <span class="badge badge-{{ $pharmacist->status === 'active' ? 'success' : ($pharmacist->status === 'pending' ? 'warning' : 'danger') }}">{{ ucwords($pharmacist->status) }}</span></p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        @if($pharmacist->status !== 'active')
                                            <a href="#" class="btn btn-success btn-sm btn-block" onclick="event.preventDefault(); if(confirm('Are you sure you want to activate this pharmacist?')) { document.getElementById('activate-form').submit(); }">
                                                Activate
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-warning btn-sm btn-block" onclick="event.preventDefault(); if(confirm('Are you sure you want to suspend this pharmacist?')) { document.getElementById('suspend-form').submit(); }">
                                                Suspend
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="btn btn-danger btn-sm btn-block" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this pharmacist? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        
                        <!-- Status Forms -->
                        <form id="activate-form" action="{{ route('primary_pharmacist.pharmacists.update', $pharmacist) }}" method="POST" style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="active">
                        </form>
                        
                        <form id="suspend-form" action="{{ route('primary_pharmacist.pharmacists.update', $pharmacist) }}" method="POST" style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="suspended">
                        </form>
                        
                        <form id="delete-form" action="{{ route('primary_pharmacist.pharmacists.destroy', $pharmacist) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Profile</strong> Details</h2>
                    </div>
                    <div class="body">
                        <small class="text-muted">Name</small>
                        <p>{{ $pharmacist->name }}</p>
                        <hr>
                        
                        <small class="text-muted">Email</small>
                        <p>{{ $pharmacist->email }}</p>
                        <hr>
                        
                        <small class="text-muted">Phone</small>
                        <p>{{ $pharmacist->phone ?? 'N/A' }}</p>
                        <hr>
                        
                        <small class="text-muted">Role</small>
                        <p>{{ ucwords(str_replace('_', ' ', $pharmacist->role)) }}</p>
                        <hr>
                        
                        <small class="text-muted">Status</small>
                        <p><span class="badge badge-{{ $pharmacist->status === 'active' ? 'success' : ($pharmacist->status === 'pending' ? 'warning' : 'danger') }}">{{ ucwords($pharmacist->status) }}</span></p>
                        <hr>
                        
                        <small class="text-muted">Date of Birth</small>
                        <p>{{ $pharmacist->date_of_birth ? $pharmacist->date_of_birth->format('F j, Y') : 'N/A' }}</p>
                        <hr>
                        
                        <small class="text-muted">Gender</small>
                        <p>{{ ucfirst($pharmacist->gender ?? 'N/A') }}</p>
                        <hr>
                        
                        <small class="text-muted">Address</small>
                        <p>{{ $pharmacist->address ?? 'N/A' }}</p>
                        <hr>
                        
                        <small class="text-muted">City</small>
                        <p>{{ $pharmacist->city ?? 'N/A' }}</p>
                        <hr>
                        
                        <small class="text-muted">State</small>
                        <p>{{ $pharmacist->state ?? 'N/A' }}</p>
                        <hr>
                        
                        <small class="text-muted">Country</small>
                        <p>{{ $pharmacist->country ?? 'N/A' }}</p>
                        <hr>
                        
                        <small class="text-muted">Registration Date</small>
                        <p>{{ $pharmacist->created_at->format('F j, Y') }}</p>
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
</body>
</html>