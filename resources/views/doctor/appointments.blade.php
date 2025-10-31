<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor appointments management">

<title>ClinicalPro || Doctor Appointments</title>
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
    .appointment-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }
    .appointment-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .appointment-item .patient-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }
    .appointment-item .patient-info {
        flex: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }
    .appointment-item .patient-name {
        min-width: 120px;
        font-weight: 600;
        margin-right: 10px;
    }
    .appointment-item .appointment-id {
        min-width: 80px;
        color: #666;
        font-size: 13px;
        background: #f5f5f5;
        padding: 3px 8px;
        border-radius: 12px;
        margin-right: 10px;
    }
    .appointment-item .appointment-date {
        min-width: 140px;
        color: #666;
        font-size: 13px;
        margin-right: 10px;
    }
    .appointment-item .appointment-type {
        min-width: 120px;
        font-size: 12px;
        background: #e3f2fd;
        padding: 3px 8px;
        border-radius: 12px;
        margin-right: 10px;
    }
    .appointment-item .contact-info {
        min-width: 150px;
        font-size: 12px;
        color: #666;
        margin-right: 10px;
    }
    .appointment-item .contact-info i {
        margin-right: 5px;
        color: #1976d2;
    }
    .appointment-item .status-badge {
        min-width: 80px;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 12px;
        text-align: center;
        margin-right: 10px;
        font-weight: 500;
    }
    .status-request {
        background: #fff3e0;
        color: #f57c00;
    }
    .status-confirmed {
        background: #e8f5e9;
        color: #4caf50;
    }
    .status-cancelled {
        background: #ffebee;
        color: #f44336;
    }
    .status-completed {
        background: #e3f2fd;
        color: #2196f3;
    }
    .appointment-item .action-buttons {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }
    .appointment-item .action-buttons .btn {
        padding: 5px 12px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Cancelled and Completed tab specific styles */
    .appointment-section {
        display: flex;
        align-items: center;
        padding: 0 15px;
    }
    .section-left {
        flex: 1;
        justify-content: flex-start;
    }
    .section-middle {
        flex: 2;
        justify-content: center;
    }
    .section-right {
        flex: 1;
        justify-content: flex-end;
    }
    .patient-info-simple {
        display: flex;
        flex-direction: column;
    }
    .appointment-date-time {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .appointment-details {
        display: flex;
        flex-direction: column;
    }
    .visit-type {
        font-size: 12px;
        background: #e3f2fd;
        padding: 3px 8px;
        border-radius: 12px;
        margin-bottom: 5px;
    }
    .call-type {
        font-size: 12px;
        background: #fff3e0;
        padding: 3px 8px;
        border-radius: 12px;
    }
    
    /* Search and filter positioning */
    .search-filter-container {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        padding: 15px;
    }
    .search-bar {
        position: relative;
        width: 250px;
        margin-right: 15px;
    }
    .search-bar input {
        padding-right: 30px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    .search-bar i {
        position: absolute;
        right: 10px;
        top: 10px;
        color: #999;
    }
    .filter-dropdown {
        width: 150px;
    }
    .filter-dropdown select {
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    
    /* Flex container for search and filter */
    .search-filter-container {
        display: flex;
        align-items: center;
    }
    
    .pagination-container {
        display: flex;
        justify-content: center;
        margin: 25px 0;
        padding: 0 15px;
    }
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .pagination li {
        margin: 0;
    }
    .pagination li a {
        display: block;
        padding: 8px 15px;
        text-decoration: none;
        background: #fff;
        color: #333;
        border: 1px solid #eee;
        transition: all 0.2s;
    }
    .pagination li.active a {
        background: #1976d2;
        color: white;
        border-color: #1976d2;
    }
    .pagination li:hover:not(.active) a {
        background: #f5f5f5;
    }
    .pagination li.disabled a {
        background: #fafafa;
        color: #999;
        cursor: not-allowed;
    }
    .tab-content {
        padding: 0;
    }
    .count-badge {
        background: #1976d2;
        color: white;
        border-radius: 10px;
        padding: 2px 8px;
        font-size: 12px;
        margin-left: 5px;
    }
    .text-center.py-5 {
        padding: 50px 15px !important;
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
                <h2 class="m-0"><i class="zmdi zmdi-calendar"></i> <span>Appointments</span></h2>
                <ul class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Appointments</li>
                </ul>
            </div>
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

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            
                            <h2 class="m-0"><strong>Manage</strong> Appointments</h2>
                            
                            <div style="display: flex; align-items: center; gap: 15px;">
                                
                                <div>
                                    <select id="type-filter" class="form-control">
                                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Types</option>
                                        <option value="chat" {{ $filter == 'chat' ? 'selected' : '' }}>Chat</option>
                                        <option value="direct" {{ $filter == 'direct' ? 'selected' : '' }}>Direct Visit</option>
                                    </select>
                                </div>
                                
                                <div style="position: relative;">
                                    <input type="text" id="live-search" class="form-control" placeholder="Search patients..." value="{{ $search ?? '' }}">
                                    <i class="zmdi zmdi-search" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="body p-0">
                        
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'upcoming' || !$tab ? 'active' : '' }}" data-toggle="tab" href="#upcoming">Upcoming <span class="count-badge">{{ $upcomingCount ?? 0 }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'inprogress' ? 'active' : '' }}" data-toggle="tab" href="#inprogress">In Progress <span class="count-badge">{{ $inProgressCount ?? 0 }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'missed' ? 'active' : '' }}" data-toggle="tab" href="#missed">Missed <span class="count-badge">{{ $missedCount ?? 0 }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'cancelled' ? 'active' : '' }}" data-toggle="tab" href="#cancelled">Cancelled <span class="count-badge">{{ $cancelledCount ?? 0 }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'completed' ? 'active' : '' }}" data-toggle="tab" href="#completed">Completed <span class="count-badge">{{ $completedCount ?? 0 }}</span></a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane {{ $tab == 'upcoming' || !$tab ? 'active' : '' }}" id="upcoming">
                                <div class="tab-content-container">
                                    @include('doctor.appointments-tab-content', ['appointments' => $appointments, 'tab' => 'upcoming'])
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane {{ $tab == 'inprogress' ? 'active' : '' }}" id="inprogress">
                                <div class="tab-content-container">
                                    @if($tab == 'inprogress')
                                        @include('doctor.appointments-tab-content', ['appointments' => $appointments, 'tab' => 'inprogress'])
                                    @else
                                        <!-- Content will be loaded via AJAX -->
                                        <div class="loading-spinner" style="display: none; text-align: center; padding: 20px;">
                                            <i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Loading...
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane {{ $tab == 'missed' ? 'active' : '' }}" id="missed">
                                <div class="tab-content-container">
                                    <div class="loading-spinner" style="display: none; text-align: center; padding: 20px;">
                                        <i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Loading...
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane {{ $tab == 'cancelled' ? 'active' : '' }}" id="cancelled">
                                <div class="tab-content-container">
                                    <!-- Content will be loaded via AJAX -->
                                    <div class="loading-spinner" style="display: none; text-align: center; padding: 20px;">
                                        <i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Loading...
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane {{ $tab == 'completed' ? 'active' : '' }}" id="completed">
                                <div class="tab-content-container">
                                    <!-- Content will be loaded via AJAX -->
                                    <div class="loading-spinner" style="display: none; text-align: center; padding: 20px;">
                                        <i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Loading...
                                    </div>
                                </div>
                            </div>
                        </div>
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
        // Store current filter and search values
        var currentFilter = '{{ $filter }}';
        var currentSearch = '{{ $search }}';
        var currentTab = '{{ $tab }}';
        var searchTimeout;
        
        // Live search functionality
        function performSearch() {
            var searchValue = $('#live-search').val();
            var filterValue = $('#type-filter').val();
            
            // Update URL parameters
            var url = new URL(window.location);
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            
            if (filterValue && filterValue !== 'all') {
                url.searchParams.set('filter', filterValue);
            } else {
                url.searchParams.delete('filter');
            }
            
            // Update the URL without reloading
            window.history.replaceState({}, '', url);
            
            // Show loading spinner for active tab
            $('#' + currentTab + ' .loading-spinner').show();
            $('#' + currentTab + ' .tab-content-container').find('.appointment-list, .text-center, .pagination-container').remove();
            
            // Make AJAX request with search and filter parameters
            $.ajax({
                url: '{{ route("doctor.appointments") }}',
                method: 'GET',
                data: {
                    tab: currentTab,
                    filter: filterValue,
                    search: searchValue,
                    ajax: true
                },
                success: function(response) {
                    if(response.success) {
                        // Hide loading spinner
                        $('#' + currentTab + ' .loading-spinner').hide();
                        // Update tab content
                        $('#' + currentTab + ' .tab-content-container').html(response.view);
                        // Rebind event handlers for new content
                        bindEventHandlers();
                    }
                },
                error: function() {
                    // Hide loading spinner
                    $('#' + currentTab + ' .loading-spinner').hide();
                    // Show error message
                    $('#' + currentTab + ' .tab-content-container').html('<div class="text-center py-5"><p class="text-danger">Error loading content. Please try again.</p></div>');
                }
            });
        }
        
        // Live search with debounce
        $('#live-search').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                currentSearch = $('#live-search').val();
                performSearch();
            }, 300); // 300ms delay
        });
        
        // Live filter change
        $('#type-filter').on('change', function() {
            currentFilter = $('#type-filter').val();
            performSearch();
        });
        
        // Bind event handlers for dynamic content
        function bindEventHandlers() {
            // Accept appointment button click
            $('.accept-appointment').off('click').on('click', function(e) {
                e.preventDefault();
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
            
            // Reject appointment button click
            $('.reject-appointment').off('click').on('click', function(e) {
                e.preventDefault();
                var appointmentId = $(this).data('appointment-id');
                $('#reject-appointment-id').val(appointmentId);
                $('#rejectModal').modal('show');
            });
            
            // Ensure dropdown menus work properly
            $('.btn-group').off('click', '.dropdown-toggle').on('click', '.dropdown-toggle', function(e) {
                e.stopPropagation();
                $(this).dropdown('toggle');
            });
            
            // Add specific handler for view profile links
            $('.view-profile-link').off('click').on('click', function(e) {
                console.log('View profile link clicked:', $(this).attr('href'));
                // DO NOT prevent default - allow the link to work
                // e.preventDefault(); // This line is intentionally commented out
            });
        }
        
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
                        location.reload(); // Reload the page to reflect changes
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
                        location.reload(); // Reload the page to reflect changes
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
                            // Check if there's a redirect URL in the response
                            if(response.redirect_url) {
                                // Redirect to the appointment details page
                                window.location.href = response.redirect_url;
                            } else {
                                location.reload(); // Reload the page to reflect changes
                            }
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
        
        // Handle tab switching with AJAX loading
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var tab = $(e.target).attr('href').substring(1); // get the href value without #
            currentTab = tab;
            
            var url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
            
            // Show loading spinner
            $('#' + tab + ' .loading-spinner').show();
            // Remove existing content
            $('#' + tab + ' .tab-content-container').find('.table-responsive, .text-center, .appointment-list, .pagination-container').remove();
            
            // Update current search and filter values
            currentSearch = $('#live-search').val();
            currentFilter = $('#type-filter').val();
            
            performSearch(); // Use the same search function to load tab content
        });
        
        // Load content for all tabs after initial page load
        $(document).ready(function() {
            setTimeout(function() {
                $('.nav-tabs a').each(function() {
                    var tabId = $(this).attr('href').substring(1);
                    var isActive = $(this).hasClass('active');
                    
                    // For non-active tabs, preload content
                    if (!isActive) {
                        // Store current values
                        var originalTab = currentTab;
                        var originalSearch = currentSearch;
                        var originalFilter = currentFilter;
                        
                        // Set values for this tab
                        currentTab = tabId;
                        currentSearch = $('#live-search').val();
                        currentFilter = $('#type-filter').val();
                        
                        // Show loading spinner
                        $('#' + tabId + ' .loading-spinner').show();
                        
                        // Make AJAX request
                        $.ajax({
                            url: '{{ route("doctor.appointments") }}',
                            method: 'GET',
                            data: {
                                tab: tabId,
                                filter: currentFilter,
                                search: currentSearch,
                                ajax: true
                            },
                            success: function(response) {
                                if(response.success) {
                                    // Hide loading spinner
                                    $('#' + tabId + ' .loading-spinner').hide();
                                    // Update tab content
                                    $('#' + tabId + ' .tab-content-container').html(response.view);
                                }
                            },
                            error: function() {
                                // Hide loading spinner
                                $('#' + tabId + ' .loading-spinner').hide();
                                // Show error message
                                $('#' + tabId + ' .tab-content-container').html('<div class="text-center py-5"><p class="text-danger">Error loading content. Please try again.</p></div>');
                            }
                        });
                        
                        // Restore original values
                        currentTab = originalTab;
                        currentSearch = originalSearch;
                        currentFilter = originalFilter;
                    }
                });
            }, 1000);
        });
        
        // Initial binding of event handlers
        bindEventHandlers();
        
        // Ensure dropdowns work properly
        $(document).on('click', '.dropdown-toggle', function(e) {
            e.stopPropagation();
            $(this).dropdown('toggle');
        });
    });
</script>
</body>
</html>