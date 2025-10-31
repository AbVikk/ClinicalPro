<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor requests for appointment management">

<title>ClinicalPro || Doctor Requests</title>
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
    .request-item {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .request-item:last-child {
        border-bottom: none;
    }
    .request-item .d-flex {
        align-items: center;
    }
    .btn-sm {
        padding: 2px 8px;
        font-size: 12px;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

<!-- Include Doctor Sidemenu -->
@include('doctor.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                <h2><i class="zmdi zmdi-notifications"></i> <span>Appointment Requests</span></h2>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Requests</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Appointment</strong> Requests</h2>
                        <small>You have {{ $requests->count() }} new appointment requests</small>
                    </div>
                    <div class="body p-0">
                        @if($requests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>Request ID</th>
                                            <th>Date & Time</th>
                                            <th>Purpose</th>
                                            <th>Appointment Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($request->patient->photo)
                                                        <img src="{{ asset('storage/' . $request->patient->photo) }}" class="rounded-circle" alt="User" width="35" height="35">
                                                    @else
                                                        <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="User" width="35" height="35">
                                                    @endif
                                                    <div class="ml-2">
                                                        <a href="{{ route('doctor.patients.appointment-history', $request->patient->id) }}"><h6 class="mb-0">{{ $request->patient->name }}</h6></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>#Apt{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d M Y g:i A') }}</td>
                                            <td>{{ $request->appointmentReason->name ?? 'General Visit' }}</td>
                                            <td>{{ ucfirst($request->type ?? 'Video Call') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('doctor.patients.appointment-history', $request->patient->id) }}">
                                                            <i class="zmdi zmdi-account"></i> View Profile
                                                        </a>
                                                        <a class="dropdown-item accept-btn" href="#" 
                                                           data-appointment-id="{{ $request->id }}"
                                                           data-appointment-time="{{ $request->appointment_time }}">
                                                            <i class="zmdi zmdi-check"></i> Accept
                                                        </a>
                                                        <a class="dropdown-item reject-btn" href="#" data-appointment-id="{{ $request->id }}">
                                                            <i class="zmdi zmdi-close"></i> Reject
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="zmdi zmdi-edit"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="zmdi zmdi-delete"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="zmdi zmdi-notifications zmdi-hc-3x text-muted mb-3"></i>
                                <h4>No New Requests</h4>
                                <p class="text-muted">You don't have any new appointment requests at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="acceptModalLabel">Accept Appointment</h4>
            </div>
            <form id="accept-appointment-form">
                @csrf
                <div class="modal-body">
                    <p>Confirm the appointment date and time:</p>
                    <div class="form-group">
                        <input type="datetime-local" id="appointment-datetime" class="form-control" required>
                    </div>
                    <input type="hidden" id="accept-appointment-id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Accept</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="rejectModalLabel">Reject Appointment</h4>
            </div>
            <form id="reject-appointment-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reject-reason">Reason for rejection *</label>
                        <textarea id="reject-reason" class="form-control" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="reject-appointment-id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- End Appointment Modal -->
<div class="modal fade" id="endAppointmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="endAppointmentModalLabel">End Appointment Session</h4>
            </div>
            <form id="end-appointment-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="end-reason">Reason for ending session *</label>
                        <textarea id="end-reason" class="form-control" rows="3" placeholder="Please provide a reason for ending this session..." required></textarea>
                    </div>
                    <input type="hidden" id="end-appointment-id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">End Session</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/doctor-links-fix.js') }}"></script><!-- Doctor Links Fix -->

<script>
    $(document).ready(function() {
        // Accept button click handler
        $('.accept-btn').on('click', function() {
            var appointmentId = $(this).data('appointment-id');
            var appointmentDateTime = $(this).data('appointment-time');
            
            $('#accept-appointment-id').val(appointmentId);
            
            // Pre-fill the datetime field with the appointment's current time
            if (appointmentDateTime) {
                // Format the datetime for the input field (YYYY-MM-DDTHH:MM)
                var formattedDateTime = new Date(appointmentDateTime).toISOString().slice(0, 16);
                $('#appointment-datetime').val(formattedDateTime);
            }
            
            $('#acceptModal').modal('show');
        });
        
        // Reject button click handler
        $('.reject-btn').on('click', function() {
            var appointmentId = $(this).data('appointment-id');
            $('#reject-appointment-id').val(appointmentId);
            $('#rejectModal').modal('show');
        });
        
        // Accept appointment form submission
        $('#accept-appointment-form').on('submit', function(e) {
            e.preventDefault();
            var appointmentId = $('#accept-appointment-id').val();
            var appointmentDateTime = $('#appointment-datetime').val();
            
            $.ajax({
                url: '{{ route("doctor.requests.accept", ["appointment" => "_ID_"]) }}'.replace('_ID_', appointmentId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    appointment_time: appointmentDateTime
                },
                success: function(response) {
                    if(response.success) {
                        $('#acceptModal').modal('hide');
                        // Remove the request item from the list
                        $('.accept-btn[data-appointment-id="' + appointmentId + '"]').closest('tr').remove();
                        // Show success message
                        alert('Appointment accepted successfully');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    alert('Error accepting appointment. Please try again. Status: ' + status + ', Error: ' + error);
                }
            });
        });
        
        // Reject appointment form submission
        $('#reject-appointment-form').on('submit', function(e) {
            e.preventDefault();
            var appointmentId = $('#reject-appointment-id').val();
            var rejectReason = $('#reject-reason').val();
            
            $.ajax({
                url: '{{ route("doctor.requests.reject", ["appointment" => "_ID_"]) }}'.replace('_ID_', appointmentId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    cancel_reason: rejectReason
                },
                success: function(response) {
                    if(response.success) {
                        $('#rejectModal').modal('hide');
                        // Remove the request item from the list
                        $('.reject-btn[data-appointment-id="' + appointmentId + '"]').closest('tr').remove();
                        // Show success message
                        alert('Appointment rejected successfully');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    alert('Error rejecting appointment. Please try again. Status: ' + status + ', Error: ' + error);
                }
            });
        });
        
        // Start appointment button click
        $(document).on('click', '.start-appointment', function(e) {
            e.preventDefault();
            var appointmentId = $(this).data('appointment-id');
            
            if (confirm('Are you sure you want to start this appointment session?')) {
                $.ajax({
                    url: '{{ route("doctor.appointments.start", ["appointment" => "_ID_"]) }}'.replace('_ID_', appointmentId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', xhr.responseText);
                        alert('Error starting appointment. Please try again. Status: ' + status + ', Error: ' + error);
                    }
                });
            }
        });
        
        // End appointment button click
        $(document).on('click', '.end-appointment', function(e) {
            e.preventDefault();
            var appointmentId = $(this).data('appointment-id');
            $('#end-appointment-id').val(appointmentId);
            $('#end-reason').val(''); // Clear previous reason
            $('#endAppointmentModal').modal('show');
        });
        
        // End appointment form submission
        $('#end-appointment-form').on('submit', function(e) {
            e.preventDefault();
            var appointmentId = $('#end-appointment-id').val();
            var endReason = $('#end-reason').val();
            
            $.ajax({
                url: '{{ route("doctor.appointments.end", ["appointment" => "_ID_"]) }}'.replace('_ID_', appointmentId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    end_reason: endReason
                },
                success: function(response) {
                    if(response.success) {
                        $('#endAppointmentModal').modal('hide');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    alert('Error ending appointment. Please try again. Status: ' + status + ', Error: ' + error);
                }
            });
        });
        
        // Ensure patient name links are clickable by preventing event bubbling issues
        /*
        $('.request-item').on('click', function(e) {
            // If the click target is not a link, prevent default behavior
            if (!$(e.target).is('a') && !$(e.target).closest('a').length) {
                e.stopPropagation();
            }
            // Log the event for debugging
            console.log('Request item clicked:', e.target.tagName, e.target.className);
        });
        */
        
        // Add specific handler for view profile links
        $('.view-profile-link').off('click').on('click', function(e) {
            console.log('View profile link clicked:', $(this).attr('href'));
            // DO NOT prevent default - allow the link to work
            // e.preventDefault(); // This line is intentionally commented out
        });
    });
</script>
</body>
</html>