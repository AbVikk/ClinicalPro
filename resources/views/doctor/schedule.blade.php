<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor schedule management">

<title>ClinicalPro || My Schedule</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
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
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2><i class="zmdi zmdi-calendar"></i> <span>My Schedule</span></h2>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">My Schedule</li>
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

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="header">
                        <h2><strong>Schedule</strong> Detail</h2>
                    </div>
                    <div class="body">
                        <form id="schedule-form">
                            <div class="row">
                                
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="date" class="form-control" name="start_date" placeholder="Start Date">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="date" class="form-control" name="end_date" placeholder="End Date">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Recurs Every</label>
                                        <select class="form-control show-tick" name="recurrence">
                                            <option value="">-- Select Recurrence --</option>
                                            <option value="weekly">1 Week</option>
                                            <option value="monthly">1 Month</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <hr>
                            
                            <h5>Schedules</h5>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group btn-group-justified" role="group">
                                        <button type="button" class="btn btn-outline-primary day-btn" data-day="monday">Monday</button>
                                        <button type="button" class="btn btn-outline-primary day-btn" data-day="tuesday">Tuesday</button>
                                        <button type="button" class="btn btn-outline-primary day-btn" data-day="wednesday">Wednesday</button>
                                        <button type="button" class="btn btn-outline-primary day-btn" data-day="thursday">Thursday</button>
                                        <button type="button" class="btn btn-outline-primary day-btn" data-day="friday">Friday</button>
                                        <button type="button" class="btn btn-outline-primary day-btn" data-day="saturday">Saturday</button>
                                        <button type="button" class="btn btn-outline-primary day-btn" data-day="sunday">Sunday</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="sessions-container">
                                <!-- Sessions will be added here dynamically -->
                            </div>
                            
                            <div class="row m-t-20">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary" id="add-session">Add Session</button>
                                    <button type="button" class="btn btn-secondary" id="cancel-schedule">Cancel</button>
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </form>
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

<script>
    // --- WAIT FOR PAGE TO LOAD ---
    document.addEventListener('DOMContentLoaded', function() {
    
        // --- DATA FROM CONTROLLER ---
        let daySessions = @json($scheduleData ?? []);
        const mainSettings = @json($mainSettings ?? null);
        
        // Fix if $scheduleData is an empty array []
        if (Array.isArray(daySessions)) {
            daySessions = {};
        }

        // Create a simple list of clinics for the JavaScript to use
       const clinicsList = @json($clinicsForJs);

        // Current day being edited
        let currentDay = null;
        
        // Session counter
        let sessionCounter = 0;
        
        // --- INITIALIZE FORM ON PAGE LOAD ---
        if (mainSettings) {
            // We no longer set location here
            document.querySelector('input[name="start_date"]').value = mainSettings.start_date;
            document.querySelector('input[name="end_date"]').value = mainSettings.end_date;
            document.querySelector('select[name="recurrence"]').value = mainSettings.recurrence;
            
            if (window.jQuery && window.jQuery.fn.selectpicker) {
                window.jQuery('.show-tick').selectpicker('refresh');
            }
        }
        
        let maxId = 0;
        Object.values(daySessions).flat().forEach(session => {
            if (session.sessionId > maxId) {
                maxId = session.sessionId;
            }
        });
        sessionCounter = maxId;

        // Auto-click the first day (Monday) to show its sessions
        document.querySelector('.day-btn[data-day="monday"]').click();

        // Add event listeners to day buttons
        document.querySelectorAll('.day-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.day-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentDay = this.getAttribute('data-day');
                showSessionsForDay(currentDay);
            });
        });
        
        // Show sessions for a specific day
        function showSessionsForDay(day) {
            document.getElementById('sessions-container').innerHTML = '';
            if (daySessions[day] && daySessions[day].length > 0) {
                daySessions[day].forEach(session => {
                    addSessionWithData(session);
                });
            } else {
                // If no sessions exist for this day, initialize an empty array
                if (!daySessions[day]) {
                    daySessions[day] = [];
                }
            }
        }
        
        // Add a new session
        document.getElementById('add-session').addEventListener('click', function() {
            if (!currentDay) {
                alert('Please select a day first');
                return;
            }
            addSession();
        });
        
        function addSession() {
            sessionCounter++;
            const sessionId = 'session-' + sessionCounter;
            
            // Create session data structure
            const sessionData = {
                id: sessionId,
                sessionId: sessionCounter,
                type: '',
                start: '',
                end: '',
                location: '' // <-- ADDED LOCATION
            };
            
            if (!daySessions[currentDay]) {
                daySessions[currentDay] = [];
            }
            daySessions[currentDay].push(sessionData);
            addSessionWithData(sessionData);
        }
        
        function addSessionWithData(sessionData) {
            const sessionId = sessionData.id;
            
            const fullSessionData = {
                id: sessionId,
                sessionId: sessionData.sessionId,
                type: sessionData.type || '',
                start: sessionData.start || '',
                end: sessionData.end || '',
                location: sessionData.location || '' // <-- GET LOCATION
            };

            // --- Create the Location Dropdown ---
            let locationOptions = '<option value="">-- Select Location --</option>';
            clinicsList.forEach(clinic => {
                // Use == instead of === for type flexibility (e.g., '1' vs 1)
                const selected = (fullSessionData.location == clinic.id) ? 'selected' : '';
                locationOptions += `<option value="${clinic.id}" ${selected}>${clinic.name}</option>`;
            });
            // --- End Location Dropdown ---

            const sessionHtml = `
                <div class="session-item" id="${sessionId}" data-session-id="${fullSessionData.sessionId}">
                    <div class="row m-t-20">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Location</label>
                                <select class="form-control session-location" name="sessions[${fullSessionData.sessionId}][location]">
                                    ${locationOptions}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Session</label>
                                <select class="form-control session-type" name="sessions[${fullSessionData.sessionId}][type]">
                                    <option value="">-- Select Session --</option>
                                    <option value="morning" ${fullSessionData.type === 'morning' ? 'selected' : ''}>Morning</option>
                                    <option value="noon" ${fullSessionData.type === 'noon' ? 'selected' : ''}>Noon</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Time</label>
                                <input type="time" class="form-control session-start" name="sessions[${fullSessionData.sessionId}][start]" placeholder="Start Time" value="${fullSessionData.start}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Time</label>
                                <input type="time" class="form-control session-end" name="sessions[${fullSessionData.sessionId}][end]" placeholder="End Time" value="${fullSessionData.end}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-sm remove-session" data-session-id="${sessionId}">
                                    <i class="zmdi zmdi-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('sessions-container').insertAdjacentHTML('beforeend', sessionHtml);
            
            // Add event listener to remove button
            document.querySelector(`#${sessionId} .remove-session`).addEventListener('click', function() {
                const sessionId = this.getAttribute('data-session-id');
                removeSession(sessionId);
            });
            
            // Add event listeners for form elements to update the 'daySessions' object
            const sessionItem = document.getElementById(sessionId);
            
            // NEW: Add listener for the location dropdown
            sessionItem.querySelector('.session-location').addEventListener('change', function() {
                updateSessionData(sessionId, 'location', this.value);
            });

            sessionItem.querySelector('.session-type').addEventListener('change', function() {
                updateSessionData(sessionId, 'type', this.value);
            });
            
            sessionItem.querySelector('.session-start').addEventListener('change', function() {
                updateSessionData(sessionId, 'start', this.value);
            });
            
            sessionItem.querySelector('.session-end').addEventListener('change', function() {
                updateSessionData(sessionId, 'end', this.value);
            });
        }
        
        function updateSessionData(sessionId, field, value) {
            if (daySessions[currentDay]) {
                const sessionIndex = daySessions[currentDay].findIndex(session => session.id === sessionId);
                if (sessionIndex !== -1) {
                    daySessions[currentDay][sessionIndex][field] = value;
                }
            }
        }
        
        function removeSession(sessionId) {
            const sessionElement = document.getElementById(sessionId);
            if (sessionElement) {
                sessionElement.remove();
            }
            if (daySessions[currentDay]) {
                daySessions[currentDay] = daySessions[currentDay].filter(session => session.id !== sessionId);
            }
        }
        
        // --- FORM SUBMISSION ---
        document.getElementById('schedule-form').addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';

            const formData = {
                // We no longer send location from here
                start_date: document.querySelector('input[name="start_date"]').value,
                end_date: document.querySelector('input[name="end_date"]').value,
                recurrence: document.querySelector('select[name="recurrence"]').value,
                sessions: daySessions, // This object NOW CONTAINS the location for each session
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route("doctor.doctor.schedule.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
                submitButton.disabled = false;
                submitButton.textContent = 'Save Changes';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred. Please try again.');
                submitButton.disabled = false;
                submitButton.textContent = 'Save Changes';
            });
        });
        
        // Handle cancel button
        document.getElementById('cancel-schedule').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.reload();
            }
        });

    }); // <-- END OF THE DOMContentLoaded WRAPPER
</script>
</body>
</html>