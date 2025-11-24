<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<title>:: ClinicalPro :: Checkin</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- jVectorMap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}">

<!-- Morris CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/morrisjs/morris.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
    <!-- Page Loader -->

    @include('admin.sidemenu')
    <!-- Main Content -->
    <section class="content home">
        <div class="container-fluid">

            <div class="block-header">

                <h2>Patient Check-in Queue</h2>

                <small>Patients who are physical and approved by their doctor.</small>

            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Live Search Input -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="zmdi zmdi-search"></i></span>
                        </div>
                        <input type="text" id="checkin-search" class="form-control" placeholder="Search by patient name, doctor name, patient ID, time, or payment status...">
                    </div>
                </div>
            </div>

            <div class="card">

                <div class="body table-responsive">

                    <table class="table table-hover" id="checkin-table">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Time</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patientsWaiting as $appointment)
                                <tr class="checkin-row" 
                                    data-patient-id="{{ $appointment->patient->id ?? '' }}"
                                    data-patient-name="{{ strtolower($appointment->patient->name ?? '') }}"
                                    data-doctor-name="{{ strtolower($appointment->doctor->name ?? '') }}"
                                    data-time="{{ $appointment->appointment_time->format('g:i A') }}"
                                    data-payment-status="{{ strtolower($appointment->payment->status ?? 'N/A') }}">
                                    <td>{{ $appointment->patient->id ?? 'N/A' }}</td>
                                    <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->appointment_time->format('g:i A') }}</td>
                                    <td>
                                        @if($appointment->payment)
                                            <span class="badge badge-{{ $appointment->payment->status == 'paid' ? 'success' : ($appointment->payment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($appointment->payment->status) }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Add payment confirmation logic here if needed --}}
                                        <form action="{{ route('admin.checkin.store', $appointment) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Check In Patient</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No patients are waiting for check-in.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Jquery Core Js --> 
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
    <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 
    
    <!-- Live Search Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('checkin-search');
            const tableRows = document.querySelectorAll('.checkin-row');
            
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                tableRows.forEach(function(row) {
                    const patientId = row.getAttribute('data-patient-id');
                    const patientName = row.getAttribute('data-patient-name');
                    const doctorName = row.getAttribute('data-doctor-name');
                    const time = row.getAttribute('data-time');
                    const paymentStatus = row.getAttribute('data-payment-status');
                    
                    // Check if any of the fields contain the search term
                    if (searchTerm === '' || 
                        patientId.includes(searchTerm) ||
                        patientName.includes(searchTerm) ||
                        doctorName.includes(searchTerm) ||
                        time.includes(searchTerm) ||
                        paymentStatus.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @stack('page-scripts')
</body>
</html>