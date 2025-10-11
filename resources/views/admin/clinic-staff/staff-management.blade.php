<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Staff Management</title>
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
                <h2>Staff Management
                <small class="text-muted">Manage clinic staff, roles, and permissions</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Staff</a></li>
                    <li class="breadcrumb-item active">All Staffs</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Staff Directory - md-8 -->
            <div class="col-md-8">
                <div class="card patients-list">
                    <div class="header">
                        <h2><strong>Staff Directory</strong></h2>
                        <!-- Search Form -->
                        <div class="col-md-4 float-right">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search staff..." id="staffSearch" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="{{ route('admin.clinic-staff.add') }}">Add Staff</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
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
                        
                        <div class="table-responsive">
                            <table class="table m-b-0 table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Contact</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="staffTableBody">
                                    @forelse($staffMembers as $staff)
                                    <tr class="staff-row" data-name="{{ strtolower($staff->name) }}" data-email="{{ strtolower($staff->email) }}" data-phone="{{ $staff->phone ? strtolower($staff->phone) : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($staff->photo)
                                                    <img src="{{ asset('storage/' . $staff->photo) }}" class="rounded-circle" width="35" height="35" alt="{{ $staff->name }}">
                                                @else
                                                    <img src="{{ asset('assets/images/profile_av.jpg') }}" class="rounded-circle" width="35" height="35" alt="{{ $staff->name }}">
                                                @endif
                                                <div class="ml-2">
                                                    <h6 class="mb-0">{{ $staff->name }}</h6>
                                                    <span class="text-muted">{{ $staff->user_id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $staff->role == 'admin' ? 'primary' : ($staff->role == 'doctor' ? 'success' : ($staff->role == 'nurse' ? 'info' : ($staff->role == 'hod' ? 'warning' : ($staff->role == 'matron' ? 'danger' : 'default')))) }}">
                                                {{ ucfirst($staff->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($staff->department)
                                                {{ $staff->department->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $staff->email }}</div>
                                            <div class="text-muted">{{ $staff->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td>{{ $staff->created_at ? $staff->created_at->format('d M, Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $staff->status == 'active' ? 'success' : ($staff->status == 'on_leave' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $staff->status ?? 'active')) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.clinic-staff.show', $staff->id) }}"><i class="zmdi zmdi-eye"></i> View Profile</a>
                                                    <a class="dropdown-item" href="{{ route('admin.clinic-staff.edit', $staff->id) }}"><i class="zmdi zmdi-edit"></i> Edit Details</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $staff->id }})"><i class="zmdi zmdi-delete"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No staff members found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination justify-content-center">
                            {{ $staffMembers->links() }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar - md-4 -->
            <div class="col-md-4">
                <!-- Staff Overview -->
                <div class="card">
                    <div class="header">
                        <h2><strong>Staff Overview</strong></h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-0">Total Staff</label>
                                    <h3 class="mb-0">{{ $totalStaff }}</h3>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-0">Active</label>
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $activeStaff }}</span>
                                        <span class="text-success">{{ $activePercentage }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $activePercentage }}%" aria-valuenow="{{ $activePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-0">On Leave</label>
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $onLeaveStaff }}</span>
                                        <span class="text-warning">{{ $onLeavePercentage }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $onLeavePercentage }}%" aria-valuenow="{{ $onLeavePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="mb-0">Inactive</label>
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $inactiveStaff }}</span>
                                        <span class="text-danger">{{ $inactivePercentage }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $inactivePercentage }}%" aria-valuenow="{{ $inactivePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Departments -->
                <div class="card">
                    <div class="header">
                        <h2><strong>Departments</strong></h2>
                    </div>
                    <div class="body">
                        <ul class="list-group">
                            @foreach($departments as $departmentName => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $departmentName }}
                                <span class="badge badge-primary badge-pill">{{ $count }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('admin.doctor.specialization.departments') }}" class="btn btn-block btn-primary">Manage Departments</a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="card">
                    <div class="header">
                        <h2><strong>Quick Actions</strong></h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <a href="{{ route('admin.clinic-staff.add') }}" class="btn btn-block btn-primary">Add New Staff</a>
                            </div>
                            <div class="col-12 mb-2">
                                <button type="button" class="btn btn-block btn-secondary">Manage Roles</button>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-block btn-info">Attendance</button>
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
// Live Search Script
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('staffSearch');
    const staffRows = document.querySelectorAll('.staff-row');
    
    // Function to filter staff
    function filterStaff() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        staffRows.forEach(function(row) {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const phone = row.getAttribute('data-phone');
            
            // Check if any of the fields contain the search term
            if (searchTerm === '' || 
                name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                (phone && phone.includes(searchTerm))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listener for live search
    if (searchInput) {
        searchInput.addEventListener('input', filterStaff);
        
        // Also trigger search on page load if there's a search term
        if (searchInput.value) {
            filterStaff();
        }
    }
});

// Delete confirmation function
function confirmDelete(staffId) {
    if (confirm('Are you sure you want to delete this staff member? This action cannot be undone.')) {
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