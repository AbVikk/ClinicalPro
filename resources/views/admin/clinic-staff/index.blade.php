<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Nurses</title>
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
                <h2>All Nurses
                <small class="text-muted">Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Nurses</a></li>
                    <li class="breadcrumb-item active">All Nurses</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card patients-list">
                    <div class="header">
                        <h2><strong>Nurses</strong></h2>
                        <!-- Search Form -->
                        <div class="col-md-4 float-right">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search nurses..." id="nurseSearch" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="{{ route('admin.clinic-staff.add') }}">Add Nurse</a></li>
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
                        
                        <div class="table-responsive">
                            <table class="table m-b-0 table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Date of Birth</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="nurseTableBody">
                                    @forelse($staffMembers as $staff)
                                    <tr class="nurse-row" data-name="{{ strtolower($staff->name) }}" data-email="{{ strtolower($staff->email) }}" data-phone="{{ $staff->phone ? strtolower($staff->phone) : '' }}">
                                        <td>{{ $staff->user_id }}</td>
                                        <td>{{ $staff->name }}</td>
                                        <td>{{ $staff->email }}</td>
                                        <td>{{ $staff->phone }}</td>
                                        <td>{{ ucfirst($staff->gender ?? 'N/A') }}</td>
                                        <td>{{ $staff->date_of_birth ? \Carbon\Carbon::parse($staff->date_of_birth)->format('d M, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                        <td colspan="7" class="text-center">No nurses found.</td>
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
    const searchInput = document.getElementById('nurseSearch');
    const nurseRows = document.querySelectorAll('.nurse-row');
    
    // Function to filter nurses
    function filterNurses() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        nurseRows.forEach(function(row) {
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
        searchInput.addEventListener('input', filterNurses);
        
        // Also trigger search on page load if there's a search term
        if (searchInput.value) {
            filterNurses();
        }
    }
});

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