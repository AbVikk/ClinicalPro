<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Departments</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- JQuery DataTable Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css') }}">

<!-- Custom Css -->
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
                <h2>Departments
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.doctor.index') }}">Doctors</a></li>
                    <li class="breadcrumb-item active">Departments</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
         <div class="row clearfix">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $totalDepartments ?? $departments->count() }}" data-speed="2500" data-fresh-interval="700">{{ $totalDepartments ?? $departments->count() }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Total Departments</p>
                        <div class="progress">
                            <div class="progress-bar l-blush" role="progressbar" aria-valuenow="{{ $progressPercentage ?? 20 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progressPercentage ?? 20 }}%"></div>
                        </div>
                        <small>From last month: +{{ $departmentGrowth ?? 2 }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        @php
                            $totalStaff = 0;
                            foreach($departments as $department) {
                                $totalStaff += $department->doctors_count;
                                // Direct database query to count clinic staff for this department
                                $totalStaff += \App\Models\User::where('department_id', $department->id)->where('role', 'nurse')->count();
                            }
                            // For demonstration, assuming 5 were added this month
                            // In a real application, you would retrieve last month's count from database history
                            $lastMonthStaff = max(0, $totalStaff - 5);
                            $staffGrowth = $totalStaff - $lastMonthStaff;
                            $staffProgressPercentage = $lastMonthStaff > 0 ? min(100, ($staffGrowth / $lastMonthStaff) * 100) : 0;
                        @endphp
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $totalStaff }}" data-speed="2500" data-fresh-interval="1000">{{ $totalStaff }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Total Staffs</p>
                        <div class="progress">
                            <div class="progress-bar l-green" role="progressbar" aria-valuenow="{{ $staffProgressPercentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $staffProgressPercentage }}%"></div>
                        </div>
                        <small>From Last Month: +{{ $staffGrowth }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        @php
                            // Direct database query to count all categories
                            $servicesOffered = \App\Models\Category::count();
                            // For demonstration, assuming 12 were added this month
                            // In a real application, you would retrieve last month's count from database history
                            $lastMonthServices = max(0, $servicesOffered - 12);
                            $serviceGrowth = $servicesOffered - $lastMonthServices;
                            $serviceProgressPercentage = $lastMonthServices > 0 ? min(100, ($serviceGrowth / $lastMonthServices) * 100) : 0;
                        @endphp
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $servicesOffered }}" data-speed="2500" data-fresh-interval="1000">{{ $servicesOffered }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Services Offered <i class=""></i></p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="{{ $serviceProgressPercentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $serviceProgressPercentage }}%;"></div>
                        </div>
                        <small>From Last Month: +{{ $serviceGrowth }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Departments</strong> List</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="{{ route('admin.doctor.specialization.add_department_form') }}">Add Department</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Doctors</th>
                                        <th>Head</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departments as $department)
                                        <tr>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->description ?? 'N/A' }}</td>
                                            <td>{{ $department->doctors_count }}</td>
                                            <td>{{ $department->head ? 'Dr. ' . $department->head->name : 'N/A' }}</td>
                                            <td><span class="badge badge-{{ $department->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($department->status) }}</span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('admin.doctor.specialization.department_show', $department) }}">View Details</a>
                                                        <a class="dropdown-item" href="{{ route('admin.doctor.specialization.edit_department', $department) }}">Edit Department</a>
                                                        <a class="dropdown-item" href="#">Manage Staff</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to deactivate this department?')">Deactivate</button>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Bootstrap JS and jQuery v3.2.1 -->
<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<!-- Jquery DataTable Plugin Js -->
<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>
</body>
</html>