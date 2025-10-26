﻿﻿﻿<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor Patients List">

<title>ClinicalPro || My Patients</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('doctor.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>My Patients
                <small class="text-muted">Patients you've treated</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item active">My Patients</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card patients-list">
                    <div class="header">
                        <h2><strong>My</strong> Patients</h2>
                        <small>Patients you have appointments with</small>
                    </div>
                    <div class="body">
                        @if($patients->count() > 0)
                            <div class="table-responsive">
                                <table class="table m-b-0 table-hover">
                                    <thead>
                                        <tr>                                       
                                            <th>Media</th>
                                            <th>Patient Name</th>
                                            <th>Last Appointment</th>
                                            <th>Total Appointments</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($patients as $patient)
                                        <tr>
                                            <td>
                                                <span class="list-icon">
                                                    @if($patient->photo)
                                                        <img class="patients-img" src="{{ asset('storage/' . $patient->photo) }}" alt="{{ $patient->name }}">
                                                    @else
                                                        <img class="patients-img" src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="{{ $patient->name }}">
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">{{ $patient->name }}</h6>
                                                <small class="text-muted">
                                                    @if($patient->date_of_birth)
                                                        @php
                                                            $age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                                                        @endphp
                                                        {{ $age }} years • {{ ucfirst($patient->gender ?? 'N/A') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                @if($patient->appointmentsAsPatient->first())
                                                    {{ $patient->appointmentsAsPatient->first()->appointment_time->format('M d, Y') }}
                                                    <br>
                                                    <small class="text-muted">{{ $patient->appointmentsAsPatient->first()->appointment_time->format('g:i A') }}</small>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $patient->appointmentsAsPatient->count() }}</td>
                                            <td>
                                                <span class="badge badge-{{ $patient->status == 'verified' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($patient->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('doctor.patients.appointment-history', $patient->id) }}">
                                                            <i class="zmdi zmdi-account"></i> View Profile
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('doctor.patients.appointment-history', $patient->id) }}">
                                                            <i class="zmdi zmdi-calendar"></i> View Appointment History
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="zmdi zmdi-edit"></i> Edit
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="pagination-container">
                                {{ $patients->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="zmdi zmdi-accounts zmdi-hc-3x text-muted mb-3"></i>
                                <h4>No Patients Found</h4>
                                <p class="text-muted">You don't have any patients yet. Patients will appear here once you have appointments with them.</p>
                            </div>
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

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>