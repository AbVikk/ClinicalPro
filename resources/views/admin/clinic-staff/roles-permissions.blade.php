<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Roles and Permissions</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Custom Styles for this page -->
<style>
    .info-box-2 {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .info-box-2:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .info-box-2 .icon {
        transition: all 0.3s ease;
    }
    
    .info-box-2:hover .icon {
        transform: scale(1.1);
    }
    
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #007bff;
        color: #007bff;
        background-color: transparent;
    }
    
    .nav-tabs .nav-link:hover {
        background-color: rgba(0,123,255,0.1);
    }
    
    .table thead.thead-dark th {
        background-color: #343a40;
        border-color: #343a40;
    }
    
    .badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 20px;
    }
    
    .btn-outline-primary, .btn-outline-danger {
        border-width: 2px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .header {
        border-bottom: 1px solid #eee;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Roles and Permissions
                <small class="text-muted">Manage system roles and permissions</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Staff</a></li>
                    <li class="breadcrumb-item active">Roles and Permissions</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <!-- Role Statistics Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-accounts"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $totalRoles }}</div>
                                <div class="number small">
                                    <span class="badge badge-primary">{{ $defaultRoles }} default</span>
                                    <span class="badge badge-info">{{ $customRoles }} custom</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Total Roles</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-assignment-account"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $staffAssigned }}</div>
                                <div class="number small">
                                    <span class="text-muted">Across all roles</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Staff Assigned</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-local-hospital"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $medicalRoles }}</div>
                                <div class="number small">
                                    <span class="text-muted">{{ $medicalStaffAssigned }} staff</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Medical Roles</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-lock"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $permissionSets }}</div>
                                <div class="number small">
                                    <span class="text-muted">{{ $permissionTypes }} types</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Permission Sets</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Roles and Permissions Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="header p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0"><strong>Roles</strong> Management</h2>
                            <ul class="header-dropdown list-unstyled mb-0">
                                <li class="dropdown"> 
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
                                        <i class="zmdi zmdi-more"></i> 
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right slideUp">
                                        <li><a href="javascript:void(0);" id="addRoleBtn" class="dropdown-item"><i class="zmdi zmdi-plus"></i> Add Role</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="body p-3">
                        <!-- Live Search -->
                        <div class="row clearfix mb-3">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="zmdi zmdi-search"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Live search..." id="roleSearch">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-3" role="tablist" id="roles-permissions-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#all-roles" role="tab">
                                    <i class="zmdi zmdi-view-list"></i> All Roles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#medical" role="tab">
                                    <i class="zmdi zmdi-local-hospital"></i> Medical
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#administrative" role="tab">
                                    <i class="zmdi zmdi-account-box"></i> Administrative
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#custom" role="tab">
                                    <i class="zmdi zmdi-star"></i> Custom
                                </a>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="roles-permissions-content">
                            <!-- All Roles Tab -->
                            <div class="tab-pane active" id="all-roles" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Role Name</th>
                                                <th>Category</th>
                                                <th>Description</th>
                                                <th>Users</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allRoles as $role)
                                            <tr>
                                                <td><strong>{{ $role['name'] }}</strong></td>
                                                <td>
                                                    @if($role['category'] === 'Medical')
                                                        <span class="badge badge-success">{{ $role['category'] }}</span>
                                                    @else
                                                        <span class="badge badge-info">{{ $role['category'] }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $role['description'] }}</td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $role['users'] }} users</span>
                                                </td>
                                                <td>{{ $role['last_updated'] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary mr-1">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Medical Roles Tab -->
                            <div class="tab-pane" id="medical" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Role Name</th>
                                                <th>Description</th>
                                                <th>Users</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medicalRolesList as $role)
                                            <tr>
                                                <td><strong>{{ $role['name'] }}</strong></td>
                                                <td>{{ $role['description'] }}</td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $role['users'] }} users</span>
                                                </td>
                                                <td>{{ $role['last_updated'] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary mr-1">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Administrative Roles Tab -->
                            <div class="tab-pane" id="administrative" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Role Name</th>
                                                <th>Description</th>
                                                <th>Users</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($administrativeRolesList as $role)
                                            <tr>
                                                <td><strong>{{ $role['name'] }}</strong></td>
                                                <td>{{ $role['description'] }}</td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $role['users'] }} users</span>
                                                </td>
                                                <td>{{ $role['last_updated'] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary mr-1">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Custom Roles Tab -->
                            <div class="tab-pane" id="custom" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Role Name</th>
                                                <th>Description</th>
                                                <th>Users</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customRolesList as $role)
                                            <tr>
                                                <td><strong>{{ $role['name'] }}</strong></td>
                                                <td>{{ $role['description'] }}</td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $role['users'] }} users</span>
                                                </td>
                                                <td>{{ $role['last_updated'] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary mr-1">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
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
        </div>
    </div>
</section>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
// Ensure the DOM is fully loaded before initializing any components
document.addEventListener('DOMContentLoaded', function() {
    // Live Search Functionality
    const roleSearch = document.getElementById('roleSearch');
    if (roleSearch) {
        roleSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Search in all tabs
            ['all-roles', 'medical', 'administrative', 'custom'].forEach(tabId => {
                const rows = document.querySelectorAll(`#${tabId} tbody tr`);
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    }

    // Add Role Button
    const addRoleBtn = document.getElementById('addRoleBtn');
    if (addRoleBtn) {
        addRoleBtn.addEventListener('click', function() {
            alert('Add Role functionality would be implemented here');
        });
    }

    // Properly handle tab switching without interfering with sidemenu
    // Use event delegation for better performance and to avoid conflicts
    document.addEventListener('click', function(e) {
        // Check if the clicked element is a tab link within our roles-permissions section
        if (e.target.matches('#roles-permissions-tabs .nav-link')) {
            e.preventDefault();
            
            // Get the target tab pane
            const target = e.target.getAttribute('href');
            
            // Hide all tab panes within the roles-permissions section
            document.querySelectorAll('#roles-permissions-content .tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Remove active class from all tab links within the roles-permissions section
            document.querySelectorAll('#roles-permissions-tabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Show the target tab pane
            const targetPane = document.querySelector(target);
            if (targetPane) {
                targetPane.classList.add('active');
            }
            
            // Add active class to the clicked link
            e.target.classList.add('active');
        }
    });
});
</script>
</body>
</html>