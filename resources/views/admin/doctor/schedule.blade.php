<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>:: Oreo Hospital :: Doctor Schedule</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- FullCalendar CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content page-calendar">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Doctor Schedule
                <small>Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Doctor</a></li>
                    <li class="breadcrumb-item active">Schedule</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">        
        <div class="row">
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="card">
                    <div class="header">
                        <h2><strong>Schedule</strong> Management</h2>
                    </div>
                    <div class="body">
                        <button type="button" class="btn btn-sm btn-round btn-success waves-effect" data-toggle="modal" data-target="#addSchedule">Add Schedule</button>
                        <button type="button" class="btn btn-sm btn-round btn-warning waves-effect" data-toggle="modal" data-target="#editSchedule">Edit Schedule</button>
                        <button class="btn btn-simple btn-sm btn-primary btn-round d-xl-none m-t-0 float-right" data-toggle="collapse" data-target="#open-schedule" aria-expanded="false" aria-controls="collapseExample"><i class="zmdi zmdi-chevron-down"></i></button>                        
                    </div>
                </div>
                
                <div class="collapse-xs collapse-sm collapse" id="open-schedule">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Weekly</strong> Availability</h2>
                        </div>
                        <div class="body">
                            @php
                                // Get a sample doctor's availability (in a real app, you might want to filter by specific doctor)
                                $doctor = $doctor ?? \App\Models\Doctor::with('schedules')->first();
                                $schedules = $doctor ? $doctor->schedules : collect();
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $colors = ['b-lightred', 'b-greensea', 'b-primary', 'b-slategray', 'b-blush', 'b-orange', 'b-cyan'];
                            @endphp
                            
                            @if($schedules->count() > 0)
                                @foreach($days as $index => $day)
                                    @php
                                        $daySchedule = $schedules->where('day_of_week', $day)->first();
                                        $colorClass = $colors[$index % count($colors)];
                                    @endphp
                                    <div class="event-name {{ $colorClass }} row">
                                        <div class="col-3 text-center">
                                            <h5>{{ substr($day, 0, 3) }}</h5>
                                        </div>
                                        <div class="col-9">
                                            @if($daySchedule && $daySchedule->is_available)
                                                @if($daySchedule->start_time && $daySchedule->end_time)
                                                    <p>{{ date('H:i', strtotime($daySchedule->start_time)) }} - {{ date('H:i', strtotime($daySchedule->end_time)) }}</p>
                                                @else
                                                    <p>Available</p>
                                                @endif
                                            @else
                                                <p>Closed</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default schedule if no availability data -->
                                <div class="event-name b-lightred row">
                                    <div class="col-3 text-center">
                                        <h5>Mon</h5>
                                    </div>
                                    <div class="col-9">
                                        <p>09:00 - 17:00</p>
                                    </div>
                                </div>
                                <div class="event-name b-greensea row">
                                    <div class="col-3 text-center">
                                        <h5>Tue</h5>
                                    </div>
                                    <div class="col-9">
                                        <p>10:00 - 16:00</p>
                                    </div>
                                </div>
                                <div class="event-name b-primary row">
                                    <div class="col-3 text-center">
                                        <h5>Wed</h5>
                                    </div>
                                    <div class="col-9">
                                        <p>09:00 - 17:00</p>
                                    </div>
                                </div>
                                <div class="event-name b-slategray row">
                                    <div class="col-3 text-center">
                                        <h5>Thu</h5>
                                    </div>
                                    <div class="col-9">
                                        <p>10:00 - 16:00</p>
                                    </div>
                                </div>
                                <div class="event-name b-blush row">
                                    <div class="col-3 text-center">
                                        <h5>Fri</h5>
                                    </div>
                                    <div class="col-9">
                                        <p>09:00 - 13:00</p>
                                    </div>
                                </div>
                                <div class="event-name b-orange row">
                                    <div class="col-3 text-center">
                                        <h5>Sat</h5>
                                    </div>
                                    <div class="col-9">
                                        <p>10:00 - 12:00</p>
                                    </div>
                                </div>
                                <div class="event-name b-cyan row">
                                    <div class="col-3 text-center">
                                        <h5>Sun</h5>
                                    </div>
                                    <div class="col-9">
                                        <p>Closed</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="header">
                        <h2><strong>Upcoming</strong> Appointments</h2>
                    </div>
                    <div class="body">
                        @php
                            // Combine appointments and consultations, sort by date, and take the next 3
                            $allAppointments = collect();
                            
                            // Add old appointments (if we have them in the view)
                            if (isset($events)) {
                                foreach ($events as $event) {
                                    $allAppointments->push($event);
                                }
                            }
                            
                            // Sort by start date and take the next 3 upcoming
                            $upcomingAppointments = $allAppointments->filter(function ($item) {
                                return \Carbon\Carbon::parse($item['start'])->isFuture();
                            })->sortBy('start')->take(3);
                        @endphp
                        
                        @forelse($upcomingAppointments as $appointment)
                            <div class="event-name b-primary row">
                                <div class="col-2 text-center">
                                    @php
                                        $date = \Carbon\Carbon::parse($appointment['start']);
                                    @endphp
                                    <h4>{{ $date->format('d') }}<span>{{ $date->format('M') }}</span><span>{{ $date->format('Y') }}</span></h4>
                                </div>
                                <div class="col-10">
                                    <h6>{{ explode(' - ', $appointment['title'])[0] }}</h6>
                                    <p>{{ $date->format('g:i A') }} - {{ explode(' - ', $appointment['title'])[1] ?? 'Appointment' }}</p>
                                    <address><i class="zmdi zmdi-pin"></i> Room 101</address>
                                </div>
                            </div>
                        @empty
                            <p>No upcoming appointments</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 col-lg-8 col-xl-8">
                <div class="card">
                    <div class="header">
                        <h2><strong>Full</strong> Schedule</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="javascript:void(0);" id="dropdown-today">Today</a></li>
                                    <li><a href="javascript:void(0);" id="dropdown-day">Day</a></li>
                                    <li><a href="javascript:void(0);" id="dropdown-week">Week</a></li>
                                    <li><a href="javascript:void(0);" id="dropdown-month">Month</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <button class="btn btn-primary btn-sm btn-round waves-effect" id="change-view-today">today</button>
                        <button class="btn btn-default btn-sm btn-simple btn-round waves-effect" id="change-view-day" >Day</button>
                        <button class="btn btn-default btn-sm btn-simple btn-round waves-effect" id="change-view-week">Week</button>
                        <button class="btn btn-default btn-sm btn-simple btn-round waves-effect" id="change-view-month">Month</button>                        
                    </div>
                </div>
                
                <div class="card">
                    <div class="body">
                        <div id="calendar" style="min-height: 600px;"></div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addSchedule" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="defaultModalLabel">Add Schedule</h4>
            </div>
            <div class="modal-body">
                <form id="add-schedule-form">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id ?? 1 }}">
                    <div class="form-group">
                        <label>Date</label>
                        <div class="form-line">
                            <input type="date" class="form-control" name="date" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <select class="form-control show-tick" name="start_time" required>
                                    <option value="">Select Start Time</option>
                                    @for($i = 0; $i < 24; $i++)
                                        @for($j = 0; $j < 60; $j += 30)
                                            <option value="{{ sprintf('%02d:%02d', $i, $j) }}">{{ sprintf('%02d:%02d', $i, $j) }}</option>
                                        @endfor
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control show-tick" name="end_time" required>
                                    <option value="">Select End Time</option>
                                    @for($i = 0; $i < 24; $i++)
                                        @for($j = 0; $j < 60; $j += 30)
                                            <option value="{{ sprintf('%02d:%02d', $i, $j) }}">{{ sprintf('%02d:%02d', $i, $j) }}</option>
                                        @endfor
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Service Type</label>
                        <div class="form-line">
                            <select class="form-control show-tick" name="service_type" required>
                                <option value="">Select Service Type</option>
                                <option value="General Checkup">General Checkup</option>
                                <option value="Consultation">Consultation</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Vaccination">Vaccination</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <div class="form-line">
                            <textarea class="form-control no-resize" name="notes" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>       
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-round waves-effect" id="save-schedule-btn">Add</button>
                <button type="button" class="btn btn-simple btn-round waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editSchedule" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="defaultModalLabel">Edit Weekly Schedule</h4>
            </div>
            <div class="modal-body">
                <form id="edit-schedule-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id ?? 1 }}">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Available</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    $schedulesByDay = $schedules ? $schedules->keyBy('day_of_week') : collect();
                                @endphp
                                
                                @foreach($days as $day)
                                    @php
                                        $schedule = $schedulesByDay->get($day);
                                    @endphp
                                    <tr>
                                        <td>{{ $day }}</td>
                                        <td>
                                            <input type="checkbox" id="available_{{ strtolower($day) }}" name="schedules[{{ $day }}][is_available]" class="filled-in chk-col-blue" {{ $schedule && $schedule->is_available ? 'checked' : '' }}>
                                            <label for="available_{{ strtolower($day) }}"></label>
                                        </td>
                                        <td>
                                            <select class="form-control" name="schedules[{{ $day }}][start_time]">
                                                <option value="">Select Start Time</option>
                                                @for($i = 0; $i < 24; $i++)
                                                    @for($j = 0; $j < 60; $j += 30)
                                                        @php
                                                            $timeValue = sprintf('%02d:%02d', $i, $j);
                                                        @endphp
                                                        <option value="{{ $timeValue }}" {{ $schedule && $schedule->start_time == $timeValue ? 'selected' : '' }}>{{ $timeValue }}</option>
                                                    @endfor
                                                @endfor
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="schedules[{{ $day }}][end_time]">
                                                <option value="">Select End Time</option>
                                                @for($i = 0; $i < 24; $i++)
                                                    @for($j = 0; $j < 60; $j += 30)
                                                        @php
                                                            $timeValue = sprintf('%02d:%02d', $i, $j);
                                                        @endphp
                                                        <option value="{{ $timeValue }}" {{ $schedule && $schedule->end_time == $timeValue ? 'selected' : '' }}>{{ $timeValue }}</option>
                                                    @endfor
                                                @endfor
                                            </select>
                                        </td>
                                        <input type="hidden" name="schedules[{{ $day }}][day_of_week]" value="{{ $day }}">
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-round waves-effect" id="save-edit-schedule-btn">Save Changes</button>
                <button type="button" class="btn btn-simple btn-round waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- FullCalendar CDN -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    // Get events data
    var eventsData = @json($events ?? []);
    console.log('Events data:', eventsData);
    
    // Format events for FullCalendar v5
    var formattedEvents = [];
    if (eventsData && Array.isArray(eventsData)) {
        for (var i = 0; i < eventsData.length; i++) {
            var event = eventsData[i];
            if (event.title && event.start) {
                formattedEvents.push({
                    title: event.title,
                    start: event.start,
                    end: event.end || null,
                    backgroundColor: event.color || '#007bff',
                    borderColor: event.color || '#007bff'
                });
            }
        }
    }
    
    console.log('Formatted events:', formattedEvents);
    
    // Initialize FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: formattedEvents,
        editable: true,
        selectable: true,
        nowIndicator: true,
        dayMaxEvents: true
    });
    
    calendar.render();
    console.log('Calendar initialized successfully');
    
    // Button click handlers
    document.getElementById('change-view-today').addEventListener('click', function() {
        calendar.today();
    });
    
    document.getElementById('change-view-day').addEventListener('click', function() {
        calendar.changeView('timeGridDay');
    });
    
    document.getElementById('change-view-week').addEventListener('click', function() {
        calendar.changeView('timeGridWeek');
    });
    
    document.getElementById('change-view-month').addEventListener('click', function() {
        calendar.changeView('dayGridMonth');
    });
    
    // Dropdown menu handlers
    document.getElementById('dropdown-today').addEventListener('click', function() {
        calendar.today();
    });
    
    document.getElementById('dropdown-day').addEventListener('click', function() {
        calendar.changeView('timeGridDay');
    });
    
    document.getElementById('dropdown-week').addEventListener('click', function() {
        calendar.changeView('timeGridWeek');
    });
    
    document.getElementById('dropdown-month').addEventListener('click', function() {
        calendar.changeView('dayGridMonth');
    });
    
    // Save schedule button handler
    document.getElementById('save-schedule-btn').addEventListener('click', function() {
        var form = document.getElementById('add-schedule-form');
        var formData = new FormData(form);
        
        fetch('{{ route('admin.doctor.schedule.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Schedule added successfully!');
                $('#addSchedule').modal('hide');
                form.reset();
                // Refresh calendar
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the schedule.');
        });
    });
    
    // Save edit schedule button handler
    document.getElementById('save-edit-schedule-btn').addEventListener('click', function() {
        var form = document.getElementById('edit-schedule-form');
        var formData = new FormData(form);
        
        fetch('{{ route('admin.doctor.schedule.update') }}', {
            method: 'POST', // Use POST for Laravel's fake PUT/PATCH method
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Schedule updated successfully!');
                $('#editSchedule').modal('hide');
                // Refresh page to show updated schedule
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the schedule.');
        });
    });
});
</script>
</body>
</html>