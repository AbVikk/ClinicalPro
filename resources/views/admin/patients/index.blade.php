<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Patients</title>
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
                <h2>All Patients
                <small class="text-muted">Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Patients</a></li>
                    <li class="breadcrumb-item active">All Patients</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card patients-list">
                    <div class="header">
                        <h2><strong>Patients</strong> List</h2>
                        <!-- Search Form -->
                        <div class="col-md-4 float-right">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search patients..." id="patientSearch" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="zmdi zmdi-search"></i></button>
                                </div>
                            </div>
                        </div>
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
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Verified">Verified</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Pending">Pending</a></li>
                        </ul>
                            
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane table-responsive active" id="All">
                                <table class="table m-b-0 table-hover">
                                    <thead>
                                        <tr>                                       
                                            <th>Name</th>
                                            <th>Age/Gender</th>
                                            <th>Status</th>
                                            <th>Last Visit</th>
                                            <th>Condition</th>
                                            <th>Doctor</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="patientTableBody">
                                        @foreach($patients as $patient)
                                        <tr class="patient-row" data-name="{{ strtolower($patient->name) }}" data-email="{{ strtolower($patient->email) }}" data-phone="{{ $patient->phone ? strtolower($patient->phone) : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($patient->photo)
                                                        <img class="patients-img" src="{{ asset('storage/' . $patient->photo) }}" alt="{{ $patient->name }}" width="35" height="35">
                                                    @else
                                                        <img class="patients-img" src="http://via.placeholder.com/35x35" alt="{{ $patient->name }}" width="35" height="35">
                                                    @endif
                                                    <div class="ml-2">
                                                        <h6 class="mb-0">{{ $patient->name }}</h6>
                                                        <span class="text-muted">{{ $patient->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($patient->date_of_birth)
                                                    @php
                                                        $age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                                                    @endphp
                                                    {{ $age }} / {{ ucfirst($patient->gender ?? 'N/A') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td><span class="badge badge-{{ $patient->status == 'verified' ? 'success' : 'warning' }}">{{ ucfirst($patient->status ?? 'pending') }}</span></td>
                                            <td>
                                                @if($patient->last_visit)
                                                    {{ \Carbon\Carbon::parse($patient->last_visit)->format('M d, Y') }}
                                                @else
                                                    No visits yet
                                                @endif
                                            </td>
                                            <td>
                                                @if($patient->condition)
                                                    <span class="badge badge-info">{{ $patient->condition }}</span>
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($patient->doctor)
                                                    {{ $patient->doctor->name }}
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('admin.patient.show', $patient->id) }}"><i class="zmdi zmdi-eye"></i> View Profile</a>
                                                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-edit"></i> Edit Details</a>
                                                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-file-text"></i> Medical History</a>
                                                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-prescription"></i> Prescriptions</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $patient->id }})"><i class="zmdi zmdi-delete"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    {{ $patients->links() }}
                                </div>
                            </div>
                            <div class="tab-pane table-responsive" id="Verified">
                                <!-- Verified patients table -->
                                <p>Verified patients will be shown here.</p>
                            </div>
                            <div class="tab-pane table-responsive" id="Pending">
                                <!-- Pending patients table -->
                                <p>Pending patients will be shown here.</p>
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

<!-- Live Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('patientSearch');
    const patientRows = document.querySelectorAll('.patient-row');
    
    // Function to filter patients
    function filterPatients() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        patientRows.forEach(function(row) {
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
        searchInput.addEventListener('input', filterPatients);
        
        // Also trigger search on page load if there's a search term
        if (searchInput.value) {
            filterPatients();
        }
    }
});

// Delete confirmation function
function confirmDelete(patientId) {
    if (confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
        // Create a form dynamically and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/patient/' + patientId;
        
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