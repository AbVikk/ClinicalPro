<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Add Doctors</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Dropzone CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.css') }}">

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="../assets/images/logo.svg" width="48" height="48" alt="Oreo"></div>
        <p>Please wait...</p>
    </div>
</div>
<!-- Overlay For Sidebars -->
@include('admin.sidemenu')

<section class="content">
<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Add Drug MG Value
            <small>Add a new drug mg value</small>
            </h2>
        </div>            
        <div class="col-lg-7 col-md-7 col-sm-12 text-right">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.dashboard') }}">Pharmacy</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.mg.index') }}">MG Values</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Add</strong> Drug MG Value</h2>
                </div>
                <div class="body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.pharmacy.mg.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mg_value">MG Value</label>
                                    <input type="text" class="form-control" id="mg_value" name="mg_value" value="{{ old('mg_value') }}" placeholder="e.g., 500mg, 200mg, 10mg" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-raised btn-primary">Save MG Value</button>
                        <a href="{{ route('admin.pharmacy.mg.index') }}" class="btn btn-raised btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</section>
    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Bootstrap JS and jQuery v3.2.1 -->
    <!-- slimscroll, waves Scripts Plugin Js -->
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    <!-- Dropzone Plugin Js -->
    <script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>
