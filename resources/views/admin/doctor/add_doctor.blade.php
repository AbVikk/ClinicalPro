<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: @isset($doctor) Edit Doctor @else Add Doctor @endisset</title>
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
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Oreo"></div>
        <p>Please wait...</p>
    </div>
</div>
<!-- Overlay For Sidebars -->
<div class="overlay"></div>

@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>@isset($doctor) Edit Doctor @else Add Doctor @endisset
                <small>Healthcare System</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.doctor.index') }}">Doctors</a></li>
                    <li class="breadcrumb-item active">@isset($doctor) Edit Doctor @else Add Doctor @endisset</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Doctor</strong> Information</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="{{ route('admin.doctor.index') }}">All Doctors</a></li>
                                    <li><a href="{{ route('admin.doctor.add') }}">Add Doctor</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{ isset($doctor) ? route('admin.doctor.update', $doctor->user_id) : route('admin.doctor.store') }}" enctype="multipart/form-data">
                            @csrf
                            @isset($doctor)
                                @method('PUT')
                            @endisset
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name', $doctor->user->name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name', explode(' ', $doctor->user->name ?? '', 2)[1] ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $doctor->user->email ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone', $doctor->user->phone ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="date" name="date_of_birth" class="form-control" placeholder="Date of Birth" value="{{ old('date_of_birth', $doctor->user->date_of_birth ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="gender" class="form-control show-tick">
                                            <option value="">-- Gender --</option>
                                            <option value="male" {{ old('gender', $doctor->user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $doctor->user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $doctor->user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="address" class="form-control" placeholder="Address" value="{{ old('address', $doctor->user->address ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="city" class="form-control" placeholder="City" value="{{ old('city', $doctor->user->city ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="state" class="form-control" placeholder="State" value="{{ old('state', $doctor->user->state ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="zip_code" class="form-control" placeholder="Zip Code" value="{{ old('zip_code', $doctor->user->zip_code ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="country" class="form-control" placeholder="Country" value="{{ old('country', $doctor->user->country ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Professional</strong> Information</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="license_number" class="form-control" placeholder="Medical License Number" value="{{ old('license_number', $doctor->license_number ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="specialization_id" class="form-control show-tick" required>
                                            <option value="">-- Select Specialization --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('specialization_id', $doctor->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="department_id" class="form-control show-tick" required>
                                            <option value="">-- Select Department --</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id', $doctor->department_id ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="medical_school" class="form-control" placeholder="Medical School" value="{{ old('medical_school', $doctor->medical_school ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="residency" class="form-control" placeholder="Residency" value="{{ old('residency', $doctor->residency ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="fellowship" class="form-control" placeholder="Fellowship" value="{{ old('fellowship', $doctor->fellowship ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="number" name="years_of_experience" class="form-control" placeholder="Years of Experience" value="{{ old('years_of_experience', $doctor->years_of_experience ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="status" class="form-control show-tick">
                                            <option value="active" {{ old('status', $doctor->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $doctor->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="suspended" {{ old('status', $doctor->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            <option value="verified" {{ old('status', $doctor->status ?? '') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea name="bio" rows="4" class="form-control no-resize" placeholder="Biography">{{ old('bio', $doctor->bio ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Account</strong> Information</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                @if(!isset($doctor))
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Profile</strong> Image</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="dz-message">
                                        <div class="drag-icon-cph"> <i class="material-icons">touch_app</i> </div>
                                        <h3>Drop files here or click to upload.</h3>
                                        <em>(Upload doctor's profile image)</em>
                                    </div>
                                    <div class="fallback">
                                        <input name="profile_image" type="file" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-round">@isset($doctor) Update Doctor @else Add Doctor @endisset</button>
                                    <a href="{{ route('admin.doctor.index') }}" class="btn btn-default btn-round">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 
</body>
</html>