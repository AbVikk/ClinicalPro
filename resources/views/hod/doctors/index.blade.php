<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Manage Doctors</title>
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
                <h2>Manage Doctors
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('hod.dashboard') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Doctors</a></li>
                    <li class="breadcrumb-item active">Manage Doctors</li>
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Assign</strong> Doctors</h2>
                        <p>Assign unassigned doctors to your department: {{ $department->name ?? 'Unknown' }}</p>
                    </div>
                    <div class="body">
                        @if($unassignedDoctors->count() > 0)
                            <form method="POST" action="{{ route('hod.doctors.assign') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="doctor_id">Select Doctor</label>
                                            <select class="form-control" name="doctor_id" id="doctor_id" required>
                                                <option value="">Choose a doctor</option>
                                                @foreach($unassignedDoctors as $doctor)
                                                    <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Assign Doctor</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <p class="mb-0">No unassigned doctors available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Department</strong> Doctors</h2>
                        <p>Doctors currently assigned to your department</p>
                    </div>
                    <div class="body">
                        @if($doctors->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($doctors as $doctor)
                                            <tr>
                                                <td>{{ $doctor->name }}</td>
                                                <td>{{ $doctor->email }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('hod.doctors.remove') }}" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('Are you sure you want to remove this doctor from your department?')">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $doctors->links() }}
                        @else
                            <div class="alert alert-info">
                                <p class="mb-0">No doctors assigned to your department yet.</p>
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
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>