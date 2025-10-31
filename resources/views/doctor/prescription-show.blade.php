<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor prescription details">

<title>ClinicalPro || Prescription Details</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Additional CSS for this page -->
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
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                <h2 class="m-0"><i class="zmdi zmdi-file-text"></i> <span>Prescription Details</span></h2>
                <ul class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.prescriptions') }}">Prescriptions</a></li>
                    <li class="breadcrumb-item active">Details</li>
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
                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Prescription #{{ $prescription->id }}</h4>
                                <p><strong>Date:</strong> {{ $prescription->created_at->format('F d, Y g:i A') }}</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
                                <a href="{{ route('doctor.prescriptions') }}" class="btn btn-secondary">Back to Prescriptions</a>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Patient Information</h5>
                                <p><strong>Name:</strong> {{ $prescription->patient->name ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $prescription->patient->email ?? 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ $prescription->patient->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Doctor Information</h5>
                                <p><strong>Name:</strong> {{ $prescription->doctor->name ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $prescription->doctor->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5>Medications</h5>
                        @if($prescription->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Medication</th>
                                        <th>Type</th>
                                        <th>Dosage</th>
                                        <th>Duration</th>
                                        <th>Instructions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescription->items as $item)
                                    <tr>
                                        <td>{{ $item->drug->name ?? $item->medication_name }}</td>
                                        <td>{{ $item->type ?? 'N/A' }}</td>
                                        <td>{{ $item->dosage ?? 'N/A' }}</td>
                                        <td>{{ $item->duration ?? 'N/A' }}</td>
                                        <td>{{ $item->instructions ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p>No medications prescribed.</p>
                        @endif
                        
                        @if($prescription->notes)
                        <hr>
                        <h5>Notes</h5>
                        <p>{{ $prescription->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Libs plugin -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Libs plugin -->

<!-- Custom Js --> 
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>