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

@include('admin.sidemenu')

<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Pharmacist Profile</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacists.index') }}">Pharmacists</a></li>
                    <li class="breadcrumb-item active">{{ $pharmacist->name }}</li>
                </ul>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card member-card">
                    <div class="header l-blue">
                        <h4 class="m-t-10">{{ $pharmacist->name }}</h4>
                        <p class="text-muted">{{ ucwords(str_replace('_', ' ', $pharmacist->role)) }}</p>
                    </div>
                    <div class="body text-center">
                        <div class="thumb-xl member-thumb m-b-10">
                            @if($pharmacist->photo)
                                <img src="{{ asset('storage/' . $pharmacist->photo) }}" class="rounded-circle" alt="profile-image" width="150">
                            @else
                                <img src="{{ asset('assets/images/sm/avatar1.jpg') }}" class="rounded-circle" alt="profile-image" width="150">
                            @endif
                        </div>
                        <div>
                            <span class="badge badge-{{ $pharmacist->status === 'active' ? 'success' : ($pharmacist->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucwords($pharmacist->status) }}
                            </span>
                        </div>
                        <p class="text-muted">{{ $pharmacist->email }}</p>
                        <a href="tel:{{ $pharmacist->phone }}" class="btn btn-primary btn-round">{{ $pharmacist->phone }}</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Pharmacist</strong> Details</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <p>{{ $pharmacist->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <p>{{ $pharmacist->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <p>{{ $pharmacist->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <p>{{ ucwords(str_replace('_', ' ', $pharmacist->role)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <p>
                                        <span class="badge badge-{{ $pharmacist->status === 'active' ? 'success' : ($pharmacist->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucwords($pharmacist->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Member Since</label>
                                    <p>{{ $pharmacist->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            @if($pharmacist->address)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <p>{{ $pharmacist->address }}</p>
                                </div>
                            </div>
                            @endif
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
</body>
</html>