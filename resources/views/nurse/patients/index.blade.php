<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Patients</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('nurse.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>All Patients
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
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
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane table-responsive active" id="All">
                                <table class="table m-b-0 table-hover">
                                    <thead>
                                        <tr>                                       
                                            <th>Name</th>
                                            <th>Age/Gender</th>
                                            <th>Status</th>
                                            <th>Contact</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="patientTableBody">
                                        @forelse($patients as $patient)
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
                                            <td><span class="badge badge-{{ $patient->status == 'active' ? 'success' : 'warning' }}">{{ ucfirst($patient->status ?? 'inactive') }}</span></td>
                                            <td>
                                                @if($patient->phone)
                                                    {{ $patient->phone }}
                                                @else
                                                    <span class="text-muted">No phone</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-eye"></i> View Profile</a>
                                                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-file-text"></i> Medical History</a>
                                                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-prescription"></i> Prescriptions</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No patients found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    {{ $patients->links() }}
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
</script>
</body>
</html>