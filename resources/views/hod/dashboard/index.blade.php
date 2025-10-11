<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || HOD Dashboard</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('hod.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>HOD Dashboard
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('hod.dashboard') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item active">HOD Dashboard</li>
                </ul>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="col-12">
                            <div class="icon">
                                <i class="zmdi zmdi-account-add"></i>
                            </div>
                            <div class="content">
                                <div class="text">{{ $doctorCount }}</div>
                                <div class="text">Doctors</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="col-12">
                            <div class="icon">
                                <i class="zmdi zmdi-accounts-alt"></i>
                            </div>
                            <div class="content">
                                <div class="text">{{ $staffCount }}</div>
                                <div class="text">Clinic Staff</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="col-12">
                            <div class="icon">
                                <i class="zmdi zmdi-calendar-check"></i>
                            </div>
                            <div class="content">
                                <div class="text">0</div>
                                <div class="text">Appointments</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="col-12">
                            <div class="icon">
                                <i class="zmdi zmdi-chart"></i>
                            </div>
                            <div class="content">
                                <div class="text">0</div>
                                <div class="text">Reports</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Department</strong> Information</h2>
                    </div>
                    <div class="body">
                        @if($department)
                            <h4>{{ $department->name }}</h4>
                            @if($department->head)
                                <p><strong>Head of Department:</strong> {{ $department->head->name }}</p>
                            @else
                                <p><strong>Head of Department:</strong> Not assigned</p>
                            @endif
                        @else
                            <p>You are not currently assigned to a department.</p>
                        @endif
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
</body>
</html>