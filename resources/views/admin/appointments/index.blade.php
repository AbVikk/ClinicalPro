<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Appointments</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>All Appointments
                <small class="text-muted">Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Appointments</a></li>
                    <li class="breadcrumb-item active">All Appointments</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card patients-list">
                    <div class="header">
                        <h2><strong>Appointments</strong> List</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#All">All</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Pending">Pending</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Confirmed">Confirmed</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Completed">Completed</a></li>
                        </ul>
                            
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane table-responsive active" id="All">
                                <table class="table m-b-0 table-hover">
                                    <thead>
                                        <tr>                                       
                                            <th>Media</th>
                                            <th>Patient Name</th>
                                            <th>Doctor Name</th>
                                            <th>Appointment Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                @if($appointment->patient->photo)
                                                    <span class="list-icon"><img class="patients-img" src="{{ asset('storage/' . $appointment->patient->photo) }}" alt="{{ $appointment->patient->name }}"></span>
                                                @else
                                                    <span class="list-icon"><img class="patients-img" src="http://via.placeholder.com/35x35" alt="{{ $appointment->patient->name }}"></span>
                                                @endif
                                            </td>
                                            <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->doctor->name ?? 'Not Assigned' }}</td>
                                            <td>{{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y H:i') : 'N/A' }}</td>
                                            <td><span class="badge badge-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($appointment->status) }}</span></td>
                                            <td>
                                                <a href="{{ route('appointment.show', $appointment->id) }}" class="btn btn-sm btn-primary">View</a>
                                                <button class="btn btn-sm btn-danger delete-appointment" data-id="{{ $appointment->id }}">Delete</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    {{ $appointments->links() }}
                                </div>
                            </div>
                            <div class="tab-pane table-responsive" id="Pending">
                                <!-- Pending appointments table -->
                                <p>Pending appointments will be shown here.</p>
                            </div>
                            <div class="tab-pane table-responsive" id="Confirmed">
                                <!-- Confirmed appointments table -->
                                <p>Confirmed appointments will be shown here.</p>
                            </div>
                            <div class="tab-pane table-responsive" id="Completed">
                                <!-- Completed appointments table -->
                                <p>Completed appointments will be shown here.</p>
                            </div>
                        </div>
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