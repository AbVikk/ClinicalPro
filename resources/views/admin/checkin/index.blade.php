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

            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

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
                                {{-- <th>Patient ID</th> --}}
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patientsWaiting as $appointment)
                                <tr class="checkin-row" 
                                    {{-- data-patient-id="{{ $appointment->patient->id ?? '' }}" --}}
                                    data-patient-name="{{ strtolower($appointment->patient->name ?? '') }}"
                                    data-doctor-name="{{ strtolower($appointment->doctor->name ?? 'Unassigned') }}"
                                    data-time="{{ $appointment->appointment_time->format('g:i A') }}"
                                    data-payment-status="{{ strtolower($appointment->payment->status ?? 'N/A') }}">
                                    {{-- <td>{{ $appointment->patient->id ?? 'N/A' }}</td> --}}
                                    <td>
                                        <strong>{{ $appointment->patient->name ?? 'Unknown' }}</strong><br>
                                        <small>{{ $appointment->patient->user_id ?? '' }}</small>
                                    </td>
                                    <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->appointment_time->format('h:i A') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $appointment->status == 'approved' ? 'success' : 'warning' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($appointment->payment)
                                            @if($appointment->payment->status == 'paid')
                                                <span class="badge badge-success">PAID</span>
                                            @else
                                                <span class="badge badge-warning">PENDING</span>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- SCENARIO 1: Payment is Pending (Cash) --}}
                                            @if($appointment->payment && $appointment->payment->status != 'paid')
                                                <form action="{{ route('admin.checkin.confirm-payment', $appointment->payment->id) }}" method="POST" onsubmit="return confirm('Confirm cash received? This will confirm the appointment.');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success mr-2" title="Confirm Cash Payment">
                                                        <i class="zmdi zmdi-money-box"></i> Confirm Payment
                                                    </button>
                                                </form>
                                            
                                            {{-- SCENARIO 2: Paid, but Doctor hasn't approved yet --}}
                                            @elseif($appointment->status == 'pending')
                                                <button class="btn btn-sm btn-secondary" disabled title="Waiting for Doctor Approval">
                                                    <i class="zmdi zmdi-time"></i> Wait for Doctor
                                                </button>

                                            {{-- SCENARIO 3: Paid & Approved -> Ready to Check In --}}
                                            @else
                                                <form action="{{ route('admin.checkin.store', $appointment) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Send to Nurse">
                                                        <i class="zmdi zmdi-check-circle"></i> Check In
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No patients Today.</td>
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
        document.getElementById('checkin-search').addEventListener('keyup', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('.checkin-row').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
            });
        });
    </script>
    @stack('page-scripts')
</body>
</html>