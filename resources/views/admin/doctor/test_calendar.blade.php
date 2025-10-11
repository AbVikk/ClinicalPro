<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Test Calendar ::</title>
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
<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Test Calendar
                <small>Testing FullCalendar functionality</small>
                </h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Test</strong> Calendar</h2>
                    </div>
                    <div class="body">
                        <div id="test-calendar" style="min-height: 600px;">
                            <div id="calendar-loading" style="text-align: center; padding: 50px;">
                                <p>Loading calendar...</p>
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

<!-- Lib Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- Calender Javascripts -->
<script src="{{ asset('assets/bundles/fullcalendarscripts.bundle.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded for test calendar');
    
    var calendarEl = document.getElementById('test-calendar');
    console.log('Test calendar element:', calendarEl);
    
    // Check if calendar element exists
    if (!calendarEl) {
        console.error('Test calendar element not found');
        return;
    }
    
    // Check if FullCalendar is available
    if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar is not loaded for test');
        var loadingEl = document.getElementById('calendar-loading');
        if (loadingEl) {
            loadingEl.innerHTML = '<p>Error: Calendar library not loaded. Please try refreshing the page.</p>';
        }
        return;
    }
    
    console.log('FullCalendar is available for test, creating calendar instance');
    
    // Simple test events
    var testEvents = [
        {
            title: 'Test Event 1',
            start: new Date().toISOString().split('T')[0] + 'T10:00:00',
            end: new Date().toISOString().split('T')[0] + 'T11:00:00',
            color: '#007bff'
        },
        {
            title: 'Test Event 2',
            start: new Date().toISOString().split('T')[0] + 'T14:00:00',
            end: new Date().toISOString().split('T')[0] + 'T15:00:00',
            color: '#28a745'
        }
    ];
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: testEvents,
        initialDate: new Date() // Set to current date
    });
    
    console.log('Test calendar instance created, attempting to render');
    
    try {
        calendar.render();
        console.log('Test calendar rendered successfully');
        // Hide loading message
        var loadingEl = document.getElementById('calendar-loading');
        if (loadingEl) {
            loadingEl.style.display = 'none';
        }
    } catch (error) {
        console.error('Error rendering test calendar:', error);
        console.error('Error stack:', error.stack);
        // Show error message
        var loadingEl = document.getElementById('calendar-loading');
        if (loadingEl) {
            loadingEl.innerHTML = '<p>Error loading test calendar: ' + error.message + '. Please try refreshing the page.</p>';
        }
    }
});
</script>
</body>
</html>