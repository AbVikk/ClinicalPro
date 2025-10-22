<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Confirm end appointment">

<title>ClinicalPro || Confirm End Appointment</title>
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

<!-- Include Doctor Sidemenu -->
@include('doctor.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2><i class="zmdi zmdi-calendar"></i> <span>Confirm End Appointment</span></h2>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.appointments') }}">Appointments</a></li>
                    <li class="breadcrumb-item active">Confirm End Appointment</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Confirm</strong> End Appointment</h2>
                    </div>
                    <div class="body">
                        <div class="alert alert-warning">
                            <h4>End Appointment Confirmation</h4>
                            <p>You are about to end the appointment for <strong>{{ $appointment->patient->name }}</strong> (ID: #APT{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}).</p>
                            <p>Are you sure you want to end this appointment session?</p>
                        </div>
                        
                        <form id="end-appointment-form" action="{{ route('doctor.appointments.end', $appointment->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="end-reason">Reason for ending session *</label>
                                <textarea id="end-reason" name="end_reason" class="form-control" rows="3" placeholder="Please provide a reason for ending this session..." required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <a href="{{ route('doctor.appointments.details', $appointment->id) }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-danger">End Appointment</button>
                            </div>
                        </form>
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