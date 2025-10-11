<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Department Details</title>
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
                <h2>Department Details
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.doctor.specialization.departments') }}">Departments</a></li>
                    <li class="breadcrumb-item active">{{ $department->name }}</li>
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

        <!-- Department Header -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('admin.doctor.specialization.departments') }}" class="btn btn-sm btn-default">
                                    <i class="zmdi zmdi-arrow-left"></i> Back to Departments
                                </a>
                                <h2 class="mt-3">{{ $department->name }}</h2>
                            </div>
                            <div>
                                <span class="badge badge-{{ $department->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($department->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row clearfix">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $staffMembers }}" data-speed="2500" data-fresh-interval="700">{{ $staffMembers }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Staff Members</p>
                        <div class="progress">
                            <div class="progress-bar l-blush" role="progressbar" aria-valuenow="{{ $staffGrowth }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $staffGrowth > 0 ? min(100, $staffGrowth * 10) : 0 }}%;"></div>
                        </div>
                        <small>{{ $staffGrowth > 0 ? '+' . $staffGrowth : 'No change' }} from last month</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $servicesOffered }}" data-speed="2500" data-fresh-interval="1000">{{ $servicesOffered }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Services Offered</p>
                        <div class="progress">
                            <div class="progress-bar l-green" role="progressbar" aria-valuenow="{{ $serviceGrowth }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $serviceGrowth > 0 ? min(100, $serviceGrowth * 10) : 0 }}%;"></div>
                        </div>
                        <small>{{ $serviceGrowth > 0 ? '+' . $serviceGrowth . ' new services added' : 'No new services' }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $monthlyAppointments }}" data-speed="2500" data-fresh-interval="1000">{{ $monthlyAppointments }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Monthly Appointments</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="{{ $appointmentGrowth }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $appointmentGrowth > 0 ? min(100, $appointmentGrowth) : 0 }}%;"></div>
                        </div>
                        <small>{{ $appointmentGrowth != 0 ? ($appointmentGrowth > 0 ? '+' : '') . $appointmentGrowth . '% from last month' : 'No change from last month' }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Information and Overview -->
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h5>Department Information</h5>
                        @if($department->description)
                            <p>{{ $department->description }}</p>
                        @else
                            <p>No description available for this department.</p>
                        @endif
                        
                        <ul class="list-unstyled">
                            <li><strong>Established:</strong> {{ $department->created_at ? $department->created_at->format('F Y') : 'N/A' }}</li>
                            @if($department->location)
                                <li><strong>Location:</strong> {{ $department->location }}</li>
                            @endif
                            @if($department->contact)
                                <li><strong>Contact:</strong> {{ $department->contact }}</li>
                            @endif
                            @if($department->email)
                                <li><strong>Email:</strong> {{ $department->email }}</li>
                            @endif
                        </ul>
                        
                        <h5>Department Capacity</h5>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{ $capacityPercentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $capacityPercentage }}%;">
                                {{ $capacityPercentage }}% of maximum capacity
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('admin.doctor.specialization.edit_department', $department) }}" class="btn btn-primary">Edit Department</a>
                            <button type="button" class="btn btn-secondary">Manage Staff</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="body">
                        <h5>Department Overview</h5>
                        <p>Key information and statistics</p>
                        
                        <!-- Tab panes -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#key_staffs">Key Staffs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#services">Services</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content mt-3">
                            <div role="tabpanel" class="tab-pane in active" id="about">
                                @if($department->about)
                                    <p>{{ $department->about }}</p>
                                @else
                                    <p>No detailed information available for the {{ $department->name }} department.</p>
                                @endif
                                
                                @if($department->history)
                                    <p>{{ $department->history }}</p>
                                @endif
                                
                                @if($department->goals)
                                    <h6>Department Goals</h6>
                                    {!! $department->goals !!}
                                @endif
                            </div>
                            
                            <div role="tabpanel" class="tab-pane" id="key_staffs">
                                <h6>Department Staff</h6>
                                @if($doctors->count() > 0 || $nurses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Contact</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($doctors as $doctor)
                                                    <tr>
                                                        <td>Dr. {{ $doctor->user->name ?? 'N/A' }}</td>
                                                        <td>Doctor</td>
                                                        <td>{{ $doctor->user->email ?? 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3">No doctors assigned to this department yet.</td>
                                                    </tr>
                                                @endforelse
                                                
                                                @forelse($nurses as $nurse)
                                                    <tr>
                                                        <td>{{ $nurse->name ?? 'N/A' }}</td>
                                                        <td>Nurse</td>
                                                        <td>{{ $nurse->email ?? 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3">No nurses assigned to this department yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>No staff members assigned to this department yet.</p>
                                @endif
                            </div>
                            
                            <div role="tabpanel" class="tab-pane" id="services">
                                <h6>Department Services</h6>
                                @if($categories->count() > 0)
                                    <ul>
                                        @forelse($categories as $category)
                                            <li>{{ $category->name ?? 'Unnamed Category' }}</li>
                                        @empty
                                            <li>No categories found</li>
                                        @endforelse
                                    </ul>
                                @else
                                    <p>No services offered by this department yet.</p>
                                @endif
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