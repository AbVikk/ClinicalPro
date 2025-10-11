<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Prescription Management System">
<title>:: Clinical Pro :: Prescriptions</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
<style>
    .alert-position {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 500px;
        animation: fadeIn 0.5s, fadeOut 0.5s 4.5s;
    }
    
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    
    @keyframes fadeOut {
        from {opacity: 1; transform: translateY(0);}
        to {opacity: 0; transform: translateY(-20px);}
    }
    
    .alert-dismissible .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<!-- Success Message -->
@if(session('success'))
<div class="alert alert-success alert-position alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Error Message -->
@if(session('error'))
<div class="alert alert-danger alert-position alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <h2>Prescriptions
                <small>Manage patient prescriptions and medications.</small>
                </h2>
            </div>            
            <div class="col-lg-4 col-md-4 col-sm-12 text-right">
                <a href="{{ route('admin.prescriptions.create') }}" class="btn btn-primary btn-round">Create Prescription</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>All</strong> Prescriptions</h2>
                        <p>View and manage all patient prescriptions.</p>
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Search and filters will go here -->
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <select class="form-control">
                                    <option>All Status</option>
                                    <option>Active</option>
                                    <option>Expired</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Medications</th>
                                        <th>Refills</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->patient->name ?? 'N/A' }}</td>
                                        <td>{{ $prescription->doctor->name ?? 'N/A' }}</td>
                                        <td>{{ $prescription->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $prescription->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($prescription->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @foreach($prescription->items as $item)
                                                <div>{{ $item->drug->name ?? 'N/A' }}</div>
                                            @endforeach
                                        </td>
                                        <td>{{ $prescription->refills_allowed ?? 0 }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.prescriptions.show', $prescription->id) }}">View Details</a>
                                                    <a class="dropdown-item" href="{{ route('admin.prescriptions.edit', $prescription->id) }}">Edit Prescription</a>
                                                    <a class="dropdown-item" href="{{ route('admin.prescriptions.renew', $prescription->id) }}">Renew Prescription</a>
                                                    <a class="dropdown-item" href="{{ route('admin.prescriptions.print', $prescription->id) }}" target="_blank">Print Prescription</a>
                                                    
                                                    <!-- Cancel Prescription Form -->
                                                    <form action="{{ route('admin.prescriptions.cancel', $prescription->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this prescription?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item btn btn-link text-left" style="width:100%">Cancel Prescription</button>
                                                    </form>
                                                </div>
                                            </div>
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
<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-position');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }
            }, 5000);
        });
    });
</script>
</body>
</html>