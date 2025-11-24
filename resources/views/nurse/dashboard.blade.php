<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Nurse dashboard for patient management and appointments">

<title>ClinicalPro || Nurse Dashboard</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />

<style>
    /* Custom styles for the Doctor Status list */
    .doctor-status-list .doctor-item:not(:last-child) {
        border-bottom: 1px solid #eee;
    }
    .doctor-status-list .doctor-item:hover {
        background-color: #f8f9fa;
    }
    
    /* Make the tabs look a bit cleaner */
    .nav-tabs .nav-link.active {
        font-weight: 600;
        border-bottom: 2px solid #1976d2; /* Match your theme */
    }

    /* Style for the refresh button */
    .btn-refresh-cache {
        margin-right: 15px;
    }
</style>

</head>
<body class="theme-cyan">
<!-- Page Loader -->

<!-- Include Nurse Sidemenu -->
@include('nurse.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            {{-- === THIS IS THE UPDATED HEADER (UI FIX) === --}}
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="zmdi zmdi-view-dashboard"></i> <span>Nurse Dashboard</span></h2>
                </div>
                
                <!-- This new div wraps your right-side items -->
                <div class="d-flex align-items-center">
                    
                    <!-- **FIX**: Button is now blue ('btn-primary') and uses a simple <a> link -->
                    <a href="{{ route('nurse.dashboard.clear-cache') }}" class="btn btn-primary btn-sm btn-refresh-cache">
                        <i class="zmdi zmdi-refresh-sync"></i> Hard Refresh
                    </a>
                    
                    <!-- **FIX**: Added margin to stop compression -->
                    <ul class="breadcrumb float-md-right" style="margin-left: 20px; margin-bottom: 0;">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
            {{-- === END OF UPDATED HEADER === --}}
        </div>
    </div>

    <div class="container-fluid">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Summary Cards (This card logic is now fixed by the Controller) -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="row">
                            <div class="col-3">
                                <i class="zmdi zmdi-accounts-add zmdi-hc-3x col-blue"></i>
                            </div>
                            <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $patientsWaitingCount ?? 0 }}</h3>
                                <small class="text-muted">Patients Waiting</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="row">
                            <div class="col-3">
                                <i class="zmdi zmdi-male-female zmdi-hc-3x col-green"></i>
                            </div>
                            <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $doctorsAvailableCount ?? 0 }}</h3>
                                <small class="text-muted">Doctors Available</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="row">
                            <div class="col-3">
                                <i class="zmdi zmdi-hospital zmdi-hc-3x col-orange"></i>
                            </div>
                            <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $patientsWithDoctorCount ?? 0 }}</h3>
                                <small class="text-muted">Patients With Doctor</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat">
                    <div class="body">
                        <div class="row">
                            <div class="col-3">
                                <i class="zmdi zmdi-time zmdi-hc-3x col-red"></i>
                            </div>
                            <div class="col-9 text-right">
                                <h3 class="m-b-0">{{ $doctorsOnDutyCount ?? 0 }}</h3>
                                <small class="text-muted">Total Doctors</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========= NEW TABBED WIDGET ROW ========= -->
        <div class="row clearfix">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <!-- Tab Headers -->
                    <div class="header">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#live_queue" role="tab" aria-controls="live_queue" aria-selected="true">
                                    <i class="zmdi zmdi-time-countdown"></i> Live Patient Queue
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#full_schedule" role="tab" aria-controls="full_schedule" aria-selected="false">
                                    <i class="zmdi zmdi-calendar-note"></i> Today's Full Schedule
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Tab 1: Live Queue -->
                        <div class="tab-pane fade show active" id="live_queue" role="tabpanel">
                            {{-- This div is the "empty box" for our robot mechanic --}}
                            <div class="body p-0" id="patient-queue-container">
                                {{-- We @include the "spare part" once, so it loads on page 1 --}}
                                @include('nurse._queue_table', ['patientQueue' => $patientQueue])
                            </div>
                        </div>

                        <!-- Tab 2: Today's Full Schedule -->
                        <div class="tab-pane fade" id="full_schedule" role="tabpanel">
                            <div class="body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Patient</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($allTodaysAppointments as $appointment)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                                    <td>
                                                        <img src="{{ $appointment->patient->photo ? asset('storage/' . $appointment->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" alt="Patient" class="rounded-circle" width="35" height="35" style="object-fit: cover; margin-right: 10px;">
                                                        {{ $appointment->patient->name ?? 'N/A' }}
                                                    </td>
                                                    <td>Dr. {{ $appointment->doctor->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($appointment->status == 'in_progress')
                                                            <span class="badge badge-danger">With Doctor</span>
                                                        @elseif($appointment->status == 'confirmed') 
                                                            <span class="badge badge-warning">Waiting</span>
                                                        @elseif($appointment->status == 'completed') 
                                                            <span class="badge badge-success">Completed</span>
                                                        @elseif($appointment->status == 'pending') 
                                                            <span class="badge badge-info">Booked</span>
                                                        @elseif($appointment->status == 'cancelled') 
                                                            <span class="badge badge-light">Cancelled</span>
                                                        @else
                                                            <span class="badge badge-light">{{ $appointment->status }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center p-4">
                                                        No appointments scheduled for today.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Doctor Status Panel -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Doctor Status</strong></h2>
                    </div>
                    {{-- This div is the other "empty box" for our robot mechanic --}}
                    <div class="body p-0" id="doctor-status-container">
                        {{-- We @include the "spare part" once --}}
                        @include('nurse._doctor_list', ['doctors' => $doctors])
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========= NEW "TAKE VITALS" MODAL (POP-UP) ========= -->
<div class="modal fade" id="vitalsModal" tabindex="-1" role="dialog" aria-labelledby="vitalsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vitalsModalLabel">Take Vitals for: <span id="modal-patient-name">Patient</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="vitals-form" action="#" method="POST"> {{-- We'll set the action with JS --}}
                @csrf
                <div class="modal-body">
                    <p>Recording vitals for Appointment ID: <span id="modal-appointment-id">#</span></p>
                    
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Blood Pressure (mmHg)</label>
                                <input type="text" name="blood_pressure" class="form-control" placeholder="e.g., 120/80">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Temperature (°C)</label>
                                <input type="text" name="temperature" class="form-control" placeholder="e.g., 36.5">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Pulse (bpm)</label>
                                <input type="text" name="pulse" class="form-control" placeholder="e.g., 72">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Height (cm)</label>
                                <input type="text" name="height" class="form-control" placeholder="e.g., 170">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Weight (kg)</label>
                                <input type="text" name="weight" class="form-control" placeholder="e.g., 65">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>SPO2 (%)</label>
                                <input type="text" name="spo2" class="form-control" placeholder="e.g., 98">
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-6">
                            {{-- === **NEW FIELD** === --}}
                            <div class="form-group">
                                <label>Blood Group</label>
                                <input type="text" name="blood_group" class="form-control" placeholder="e.g., O+">
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="form-group">
                                <label>Reason for Visit / Nurse's Note</label>
                                <textarea name="nurse_note" rows="1" class="form-control no-resize" placeholder="Patient complains of..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Vitals</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ========= END OF MODAL ========= -->


<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- ========= THIS IS THE "ROBOT MECHANIC" SCRIPT ========= -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. "Take Vitals" Modal Logic ---
        // We use 'click' on the container because the buttons are loaded by our mechanic
        $('#patient-queue-container').on('click', '.btn-take-vitals', function() {
            var patientName = $(this).data('patient-name');
            var appointmentId = $(this).data('appointment-id');
            
            $('#modal-patient-name').text(patientName);
            $('#modal-appointment-id').text(appointmentId);
            
            // **THE FIX**: This now points to a real route
            var formAction = "{{ url('nurse/appointment') }}/" + appointmentId + "/save-vitals";
            $('#vitals-form').attr('action', formAction);
        });

        // --- 2. AJAX "Live" Refresh Logic ---
        
        // Function to refresh the patient queue
        function refreshQueue() {
            // This is the new URL we added to routes/nurse.php
            fetch("{{ route('nurse.ajax.queue') }}")
                .then(response => response.text())
                .then(html => {
                    // This swaps the "spare part"
                    document.getElementById('patient-queue-container').innerHTML = html;
                })
                .catch(error => console.error('Error refreshing queue:', error));
        }

        // Function to refresh the doctor status list
        function refreshDoctorStatus() {
            // This is the new URL we added to routes/nurse.php
            fetch("{{ route('nurse.ajax.doctors') }}")
                .then(response => response.text())
                .then(html => {
                    // This swaps the "spare part"
                    document.getElementById('doctor-status-container').innerHTML = html;
                })
                .catch(error => console.error('Error refreshing doctors:', error));
        }

        // Set an interval to run these functions every 20 seconds
        // This does NOT refresh the whole page, so it's smooth!
        setInterval(function() {
            console.log("Refreshing dashboard data from cache...");
            refreshQueue();
            refreshDoctorStatus();
        }, 20000); // 20000ms = 20 seconds

    });
</script>
<!-- ========= END OF NEW JAVASCRIPT ========= -->

</body>
</html>