<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Clinical Pro || Home</title>
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/plugins/morrisjs/morris.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan ls-closed">
@include('admin.sidemenu')

<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12">
                <h2>Dashboard
                <small>Welcome to Clinical Pro Admin dashboard</small>
                </h2>
            </div>            
            <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                <div class="inlineblock text-center m-r-15 m-l-15 d-none d-lg-inline-block">
                    <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#fff">3,2,6,5,9,8,7,9,5,1,3,5,7,4,6</div>
                    <small class="col-white">Visitors</small>
                </div>
                <div class="inlineblock text-center m-r-15 m-l-15 d-none d-lg-inline-block">
                    <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#fff">1,3,5,7,4,6,3,2,6,5,9,8,7,9,5</div>
                    <small class="col-white">Operations</small>
                </div>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        
        <div class="row clearfix">
            <div class="col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                <div class="body">
                                    <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ intval(str_replace(',', '', $formattedNetCashFlow ?? '0')) }}" data-speed="1000" data-fresh-interval="700">{{ $formattedNetCashFlow ?? '0.00' }}</h2>
                                    <p class="text-muted">Net Cash Flow (Current Month)</p>
                                    <span id="linecustom1">1,4,2,6,5,2,3,8,5,2</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                <div class="body">
                                    <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ intval(str_replace(',', '', $formattedTotalPayments ?? '0')) }}" data-speed="2000" data-fresh-interval="700">{{ $formattedTotalPayments ?? '0.00' }}</h2>
                                    <p class="text-muted ">Total Payments (Current Month)</p>
                                    <span id="linecustom2">2,9,5,5,8,5,4,2,6</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                <div class="body">
                                    <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ intval(str_replace(',', '', $formattedTotalDisbursements ?? '0')) }}" data-speed="2000" data-fresh-interval="700">{{ $formattedTotalDisbursements ?? '0.00' }}</h2>
                                    <p class="text-muted">Total Disbursements (Current Month)</p>
                                    <span id="linecustom3">1,5,3,6,6,3,6,8,4,2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $totalUsers ?? 1600 }}" data-speed="2500" data-fresh-interval="700">{{ $totalUsers ?? 1600 }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Total Users</p>
                        <div class="progress">
                            <div class="progress-bar l-blush" role="progressbar" aria-valuenow="{{ $progressPercentage ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progressPercentage ?? 68 }}%;"></div>
                        </div>
                        <small>Overall Growth: {{ number_format($actualPercentage ?? 0, 1) }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $newRegistrations ?? 3218 }}" data-speed="2500" data-fresh-interval="1000">{{ $newRegistrations ?? 3218 }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">New Registrations (7 days)</p>
                        <div class="progress">
                            <div class="progress-bar l-green" role="progressbar" aria-valuenow="{{ $newRegProgress ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $newRegProgress ?? 68 }}%;"></div>
                        </div>
                        <small>Change {{ $regChangePercentage ?? 23 }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $pendingInvitations ?? 0 }}" data-speed="2500" data-fresh-interval="1000"><i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Invitations <i class=""></i></p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                        </div>
                        <small>Change 50%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $pendingAppointments ?? 284 }}" data-speed="2500" data-fresh-interval="1000">{{ $pendingAppointments ?? 284 }} <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">Pending Appointments</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="{{ $pendingProgress ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pendingProgress ?? 68 }}%;"></div>
                        </div>
                        <small>Change {{ $pendingChangePercentage ?? 50 }}%</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="1" data-speed="2500" data-fresh-interval="1000">System Status <i class="zmdi zmdi-trending-up float-right"></i></h3>
                        <p class="text-muted">System Update/Backup</p>
                        <div class="progress">
                            <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="{{ $systemProgress ?? 68 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $systemProgress ?? 68 }}%;"></div>
                        </div>
                        <small>{{ $systemInfo ?? 'Update: 2 days ago, Backup: 1 day ago' }}</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="body">
                        <h3 class="number count-to m-b-0" data-from="0" data-to="{{ $metrics['noShowRate'] ?? 0 }}" data-speed="2500" data-fresh-interval="1000">{{ $metrics['noShowRate'] ?? 0 }}% <i class="zmdi zmdi-alert-triangle float-right text-danger"></i></h3>
                        <p class="text-muted">No-Show Rate</p>
                        <div class="progress">
                            <div class="progress-bar l-coral" role="progressbar" aria-valuenow="{{ $metrics['noShowRate'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics['noShowRate'] ?? 0 }}%;"></div>
                        </div>
                        <small>Efficiency Metric</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Revenue</strong> Growth (Last 6 Months)</h2>
                    </div>                    
                    <div class="body">
                        <canvas id="revenueChart" height="120"></canvas>                               
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Appointment</strong> Status</h2>
                    </div>
                    <div class="body">
                        <canvas id="statusChart" height="285"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Busiest</strong> Hours <small>Patient Traffic (Last 30 Days)</small></h2>
                    </div>
                    <div class="body">
                        <canvas id="busiestHoursChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Revenue</strong> Breakdown</h2>
                    </div>
                    <div class="body">
                        <div class="row text-center">
                            <div class="col-6 border-right pb-4 pt-4">
                                <label class="mb-0">Consultations</label>
                                <h4 class="font-30 font-weight-bold text-col-blue">₦{{ number_format($metrics['clinicRevenue'] ?? 0) }}</h4>
                            </div>
                            <div class="col-6 pb-4 pt-4">
                                <label class="mb-0">Pharmacy</label>
                                <h4 class="font-30 font-weight-bold text-col-pink">₦{{ number_format($metrics['pharmacyRevenue'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="header">
                        <h2><strong>Top</strong> Revenue Doctors</h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Doctor</th>
                                    <th class="text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenuePerDoctor as $doc)
                                <tr>
                                    <td>{{ $doc->doctor_name }}</td>
                                    <td class="text-right text-success">
                                        <strong>₦{{ number_format($doc->total_revenue) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card patient_list">
                    <div class="header">
                        <h2><strong>Recent</strong> Appointments</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Patient Name</th>
                                        <th>Day & Time</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                    <tr>
                                        <td>
                                            @if($appointment->patient && $appointment->patient->photo)
                                                <img src="{{ asset('storage/' . $appointment->patient->photo) }}" alt="{{ $appointment->patient->name }}" class="rounded-circle" width="35" height="35">
                                            @else
                                                <img src="http://via.placeholder.com/35x35" alt="Avatar" class="rounded-circle">
                                            @endif
                                        </td>
                                        <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            @if($appointment->doctor)
                                                {{ $appointment->doctor->name }}
                                            @else
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="assignDoctorDropdown{{ $appointment->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Assign Doctor
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="assignDoctorDropdown{{ $appointment->id }}">
                                                        @foreach($availableDoctors as $doctor)
                                                            <a class="dropdown-item assign-doctor" href="#" data-appointment-id="{{ $appointment->id }}" data-doctor-id="{{ $doctor->id }}" data-doctor-name="{{ $doctor->name }}">{{ $doctor->name }}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($appointment->status) }}</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"><i class="zmdi zmdi-eye"></i> View Details</a>
                                                    <a class="dropdown-item" href="#"><i class="zmdi zmdi-edit"></i> Edit Appointment</a>
                                                    <a class="dropdown-item" href="#"><i class="zmdi zmdi-delete"></i> Cancel Appointment</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card patient_list">
                    <div class="header">
                        <h2><strong>New</strong> Patients</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registered Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newPatients as $patient)
                                    <tr>
                                        <td>
                                            @if($patient->photo)
                                                <img src="{{ asset('storage/' . $patient->photo) }}" alt="{{ $patient->name }}" class="rounded-circle" width="35" height="35">
                                            @else
                                                <img src="http://via.placeholder.com/35x35" alt="{{ $patient->name }}" class="rounded-circle">
                                            @endif
                                        </td>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->email }}</td>
                                        <td>{{ $patient->created_at ? $patient->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td><span class="badge badge-{{ $patient->status == 'verified' ? 'success' : 'warning' }}">{{ ucfirst($patient->status ?? 'pending') }}</span></td>
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
</section>

<div class="modal fade" id="assignDoctorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="smallModalLabel">Assign Doctor</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to assign <strong id="doctorName"></strong> to this appointment?
                <input type="hidden" id="appointmentId" value="">
                <input type="hidden" id="doctorId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round waves-effect" id="confirmAssign">ASSIGN</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets/infobox/infobox-1.js') }}"></script>
<script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/index.js') }}"></script>
<script src="{{ asset('assets/js/pages/charts/jquery-knob.js') }}"></script>
<script src="{{ asset('assets/js/pages/cards/basic.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. REVENUE CHART ---
    try {
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($revenueData);
        
        if(revenueData && revenueData.length > 0) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.map(d => d.month),
                    datasets: [{
                        label: 'Revenue (NGN)',
                        data: revenueData.map(d => d.total),
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    } catch (e) { console.log("Revenue Chart Error:", e); }

    // --- 2. STATUS PIE CHART ---
    try {
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusRaw = @json($appointmentStats);
        
        if(Object.keys(statusRaw).length > 0) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusRaw).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                    datasets: [{
                        data: Object.values(statusRaw),
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { 
                        legend: { position: 'bottom', labels: { boxWidth: 10 } } 
                    }
                }
            });
        }
    } catch (e) { console.log("Status Chart Error:", e); }

    // --- 3. BUSIEST HOURS CHART (NEW) ---
    try {
        const busyCtx = document.getElementById('busiestHoursChart').getContext('2d');
        const busyData = @json($busiestHours);
        
        if(busyData && busyData.length > 0) {
            new Chart(busyCtx, {
                type: 'bar',
                data: {
                    labels: busyData.map(d => d.hour),
                    datasets: [{
                        label: 'Patient Visits',
                        data: busyData.map(d => d.count),
                        backgroundColor: 'rgba(33, 150, 243, 0.6)',
                        borderColor: 'rgba(33, 150, 243, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        } else {
            document.getElementById('busiestHoursChart').parentNode.innerHTML = '<p class="text-center text-muted m-t-20">No enough data to show trends.</p>';
        }
    } catch (e) { console.log("Busy Hours Chart Error:", e); }

    // --- Doctor Assignment Logic ---
    $(document).on('click', '.assign-doctor', function(e) {
        e.preventDefault();
        var appointmentId = $(this).data('appointment-id');
        var doctorId = $(this).data('doctor-id');
        var doctorName = $(this).data('doctor-name');
        
        $('#appointmentId').val(appointmentId);
        $('#doctorId').val(doctorId);
        $('#doctorName').text(doctorName);
        $('#assignDoctorModal').modal('show');
    });
    
    $('#confirmAssign').on('click', function() {
        var appointmentId = $('#appointmentId').val();
        var doctorId = $('#doctorId').val();
        
        $.ajax({
            url: '/admin/appointments/' + appointmentId + '/assign-doctor',
            type: 'PUT',
            data: {
                doctor_id: doctorId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                $('#assignDoctorModal').modal('hide');
                alert('Doctor assigned successfully!');
                location.reload();
            },
            error: function() {
                alert('Failed to assign doctor.');
            }
        });
    });
});
</script>
@stack('page-scripts')
</body>
</html>