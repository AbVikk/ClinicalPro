<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Nurse Profile</title>
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
                <h2>Nurse Profile
                <small class="text-muted">Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Nurses</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card profile-card">
                    <div class="profile-header">&nbsp;</div>
                    <div class="profile-body">
                        <div class="image-area">
                            @if($staffMember->photo)
                                <img src="{{ asset('storage/' . $staffMember->photo) }}" alt="{{ $staffMember->name }}" style="width: 150px; height: 150px; border-radius: 50%;">
                            @else
                                <img src="{{ asset('assets/images/profile_av.jpg') }}" alt="{{ $staffMember->name }}" style="width: 150px; height: 150px; border-radius: 50%;">
                            @endif
                        </div>
                        <div class="content-area">
                            <h3>{{ $staffMember->name }}</h3>
                            <p>{{ ucfirst($staffMember->role) }}</p>
                            <p>ID: {{ $staffMember->user_id }}</p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                <span>Joined</span>
                                <span>{{ $staffMember->created_at->format('d M, Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Nurse</strong> Information</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="zmdi zmdi-more"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ route('admin.clinic-staff.edit', $staffMember->id) }}">Edit Profile</a></li>
                                    <li><a href="#" onclick="confirmDelete({{ $staffMember->id }})">Delete Nurse</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Full Name</small>
                                <p>{{ $staffMember->name }}</p>
                                <hr>
                                
                                <small class="text-muted">Email address</small>
                                <p>{{ $staffMember->email }}</p>
                                <hr>
                                
                                <small class="text-muted">Phone</small>
                                <p>{{ $staffMember->phone }}</p>
                                <hr>
                                
                                <small class="text-muted">Gender</small>
                                <p>{{ ucfirst($staffMember->gender ?? 'N/A') }}</p>
                                <hr>
                            </div>
                            
                            <div class="col-md-6">
                                <small class="text-muted">Date of Birth</small>
                                <p>{{ $staffMember->date_of_birth ? \Carbon\Carbon::parse($staffMember->date_of_birth)->format('d M, Y') : 'N/A' }}</p>
                                <hr>
                                
                                <small class="text-muted">Address</small>
                                <p>{{ $staffMember->address ?? 'N/A' }}</p>
                                <hr>
                                
                                <small class="text-muted">Account Status</small>
                                <p>
                                    <span class="badge badge-success">Active</span>
                                </p>
                                <hr>
                                
                                <small class="text-muted">Last Updated</small>
                                <p>{{ $staffMember->updated_at->format('d M, Y H:i') }}</p>
                                <hr>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <a href="{{ route('admin.clinic-staff.index') }}" class="btn btn-default">Back to List</a>
                            <a href="{{ route('admin.clinic-staff.edit', $staffMember->id) }}" class="btn btn-primary">Edit Profile</a>
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

<script>
// Delete confirmation function
function confirmDelete(staffId) {
    if (confirm('Are you sure you want to delete this nurse? This action cannot be undone.')) {
        // Create a form dynamically and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/clinic-staff/' + staffId;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add method spoofing for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Append form to body and submit
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
</body>
</html>