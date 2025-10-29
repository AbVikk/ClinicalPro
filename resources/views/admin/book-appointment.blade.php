﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>Clinical Pro || Book Appointment</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Bootstrap Material Datetimepicker CSS -->
<link href="{{ asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

<!-- Bootstrap Select CSS -->
<link href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    #patient-search-results {
        position: absolute;
        z-index: 1000;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    #patient-results-list {
        margin-bottom: 0;
    }
    
    #patient-results-list li {
        cursor: pointer;
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }
    
    #patient-results-list li:hover, 
    #patient-results-list li.active {
        background-color: #e9ecef;
    }
    
    #patient-results-list li:last-child {
        border-bottom: none;
    }
    
    .searching-indicator {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    
    .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }
    
    .time-duration-option {
        display: inline-block;
        padding: 8px 15px;
        margin: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        background-color: #f8f9fa;
    }

    .time-duration-option.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Book Appointment
                <small>Welcome to Clinical Pro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Appointment</a></li>
                    <li class="breadcrumb-item active">Book Appointment</li>
                </ul>
            </div>
        </div>
    </div>    
    <div class="container-fluid">        
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="header">
						<h2><strong>Book</strong> Appointment<small>Admin booking appointment for patient</small> </h2>
						<ul class="header-dropdown">                            
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
					</div>
					<div class="body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        <form id="book-appointment-form" method="POST" action="{{ route('admin.book-appointment.store') }}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="patient_id">Patient ID</label>
                                        <input type="text" id="patient_id" name="patient_id" class="form-control" placeholder="Enter Patient ID" value="{{ $patientData['user_id'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="patient_search">Search Patient by Name</label>
                                        <div class="input-group">
                                            <input type="text" id="patient_search" class="form-control" placeholder="Search patient by name">
                                            <div class="searching-indicator" id="searching-indicator">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div id="patient-search-results" class="mt-2" style="display: none;">
                                            <ul class="list-group" id="patient-results-list"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="button" id="check-patient" class="btn btn-info" {{ isset($patientData) ? 'style=display:none;' : '' }}>Check Existing Patient</button>
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#newPatientModal" {{ isset($patientData) ? 'style=display:none;' : '' }}>New Walk-In Patient</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="patient-details" style="{{ isset($patientData) ? 'display: block;' : 'display: none;' }}">
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="patient_name">Patient Name</label>
                                            <input type="text" id="patient_name" class="form-control" value="{{ $patientData['name'] ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="patient_email">Patient Email</label>
                                            <input type="text" id="patient_email" class="form-control" value="{{ $patientData['email'] ?? '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="appointment_date">Appointment Date *</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="zmdi zmdi-calendar"></i>
                                                </span>
                                                <input type="text" id="appointment_date" name="appointment_date" class="form-control datetimepicker" placeholder="Please choose date & time..." required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="clinic_id">Select Location *</label>
                                        <select id="clinic_id" name="clinic_id" class="form-control show-tick" required>
                                            <option value="">- Select Location -</option>
                                            <option value="virtual">Virtual Session</option>
                                            @foreach($clinics as $clinic)
                                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="doctor_id">Select Doctor *</label>
                                            <select id="doctor_id" name="doctor_id" class="form-control show-tick" data-live-search="true" required>
                                                <option value="">- Select Location and Date First -</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="service_id">Service *</label>
                                            <select id="service_id" name="service_id" class="form-control show-tick" required>
                                                <option value="">- Select Service -</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" 
                                                            data-price="{{ $service->price_amount }}" 
                                                            data-duration="{{ $service->default_duration }}">
                                                        {{ $service->service_name }} ({{ $service->formatted_price }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="service_duration">Duration *</label>
                                            <div class="duration-options" id="duration-options">
                                                <div class="time-duration-option active" data-duration="30">30 mins</div>
                                                <div class="time-duration-option" data-duration="40">40 mins</div>
                                                <div class="time-duration-option" data-duration="60">60 mins</div>
                                            </div>
                                            <input type="hidden" id="service_duration" name="service_duration" value="30">
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="reason">Reason for Appointment</label>
                                            <input type="text" id="reason" name="reason" class="form-control" placeholder="Reason for appointment">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Total Amount: <span id="service_price_display">₦0.00</span></label>
                                            
                                            <input type="hidden" id="service_price" name="service_price" value="0">
                                            <small class="text-info">Price updates based on service and duration.</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Book Appointment</button>
                                        <button type="button" class="btn btn-default btn-round btn-simple" onclick="resetForm()">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
				</div>
			</div>
		</div>
    </div>
</section>

<!-- Walk-In Patient Modal -->
<div class="modal fade" id="newPatientModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Register Walk-In Patient</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="new-patient-name">Full Name *</label>
                    <input type="text" id="new-patient-name" class="form-control" placeholder="Enter patient's full name" required>
                </div>
                <div class="form-group">
                    <label for="new-patient-phone">Phone Number *</label>
                    <input type="text" id="new-patient-phone" class="form-control" placeholder="Enter phone number" required>
                </div>
                <div class="form-group">
                    <label for="new-patient-email">Email (Optional)</label>
                    <input type="email" id="new-patient-email" class="form-control" placeholder="Enter email address">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="register-walk-in">Register Patient</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Bootstrap JS and jQuery v3.2.1 -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script>
    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
</script>

<!-- Moment Plugin Js -->
<script src="{{ asset('assets/plugins/momentjs/moment.js') }}"></script>

<!-- Bootstrap Material Datetimepicker Js -->
<script src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<!-- Service Management Js -->
<script src="{{ asset('js/services.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script>
    $(function () { // Use jQuery's ready function ONCE for all code

        // --- Initialize Plugins ---
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY - HH:mm', // Matched PHP format: 'l d F Y - H:i'
            clearButton: true,
            weekStart: 1,
            minDate: new Date() // Prevent selecting past dates
        });

        // Initialize Bootstrap Select
        $('.show-tick').selectpicker();

        // --- Helper Function to Update Dropdown ---
        function updateSelectPicker(selector, options, defaultText, disabled = false) {
            const select = $(selector);
            select.empty(); // Clear existing options
            select.append(`<option value="">- ${defaultText} -</option>`); // Add default
            if (options && options.length > 0) {
                $.each(options, function(index, item) {
                    select.append(`<option value="${item.id}">${item.name}</option>`);
                });
            } else if (!disabled) { // Only show 'No options' if it's enabled but empty
                 select.append(`<option value="" disabled>No options available</option>`);
            }
            select.prop('disabled', disabled); // Set disabled state
            select.selectpicker('refresh'); // Refresh the plugin
        }

        // --- Patient Search & Selection ---
        let searchTimeout;
        $('#patient_search').on('input', function() {
            const searchTerm = $(this).val();
            clearTimeout(searchTimeout);

            if (searchTerm.length < 2) {
                $('#patient-search-results').hide();
                $('#searching-indicator').hide();
                return;
            }
            $('#searching-indicator').show();

            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: '{{ route('admin.book-appointment.search-patients') }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', search: searchTerm },
                    success: function(response) {
                        $('#searching-indicator').hide();
                        let resultsHtml = '';
                        if (response.patients && response.patients.length > 0) {
                            response.patients.forEach(function(patient) {
                                resultsHtml += `
                                    <li class="list-group-item list-group-item-action"
                                        data-patient-id="${patient.user_id}"
                                        data-patient-name="${patient.name}"
                                        data-patient-email="${patient.email || ''}">
                                        ${patient.name} (${patient.user_id}) - ${patient.email || 'No Email'}
                                    </li>`;
                            });
                        } else {
                            resultsHtml = '<li class="list-group-item">No patients found</li>';
                        }
                        $('#patient-results-list').html(resultsHtml);
                        $('#patient-search-results').show();
                    },
                    error: function() {
                        $('#searching-indicator').hide();
                        $('#patient-results-list').html('<li class="list-group-item">Error searching patients</li>');
                        $('#patient-search-results').show();
                    }
                });
            }, 300);
        });

        // Handle patient selection from search results
        $(document).on('click', '#patient-results-list li', function() {
            if ($(this).data('patient-id')) { // Ensure it's a real patient item
                const patientId = $(this).data('patient-id');
                const patientName = $(this).data('patient-name');
                const patientEmail = $(this).data('patient-email');

                $('#patient_id').val(patientId);
                $('#patient_name').val(patientName);
                $('#patient_email').val(patientEmail);
                $('#patient-search-results').hide();
                $('#patient_search').val('');
                $('#patient-details').show();
                // Hide the Check/New buttons as patient is selected
                $('#check-patient').hide();
                $('#newPatientModal').closest('div').find('button[data-target="#newPatientModal"]').hide();

                // Trigger date focus or maybe location/date fetch
                 $('#appointment_date').focus();
            }
        });

        // --- Check Existing Patient Button ---
        // **** THIS IS THE FIX ****
        $('#check-patient').on('click', function() {
            var patientId = $('#patient_id').val();
            if (!patientId) {
                alert('Please enter a patient ID');
                return;
            }
            $.ajax({
                url: '{{ route('admin.book-appointment.patient-info') }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', patient_id: patientId },
                success: function(response) {
                    if (response.patient) {
                        $('#patient_name').val(response.patient.name);
                        $('#patient_email').val(response.patient.email || ''); // Handle null email
                        $('#patient-details').show();
                         // Hide the Check/New buttons as patient is found
                        $('#check-patient').hide();
                        $('#newPatientModal').closest('div').find('button[data-target="#newPatientModal"]').hide();

                    } else {
                         // Should ideally not happen if patient exists, but handle just in case
                         alert('Patient details could not be retrieved.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        alert('Patient not found. Please check the patient ID or register as a New Walk-In Patient.');
                         $('#patient_id').focus(); // Focus back on the ID field
                    } else {
                        alert('An error occurred checking the patient ID. Please try again.');
                    }
                     // Keep Check/New buttons visible
                     $('#check-patient').show();
                     $('#newPatientModal').closest('div').find('button[data-target="#newPatientModal"]').show();
                     $('#patient-details').hide(); // Hide details section
                     $('#patient_name').val(''); // Clear name/email
                     $('#patient_email').val('');

                }
            });
        });

        // --- Walk-In Patient Modal ---
        $('#newPatientModal').on('shown.bs.modal', function () {
           $('#new-patient-name').focus();
        });

        $('#register-walk-in').on('click', function() {
            var name = $('#new-patient-name').val();
            var phone = $('#new-patient-phone').val();
            var email = $('#new-patient-email').val();
            var button = $(this); // Reference the button

            if (!name || !phone) {
                alert('Name and phone are required');
                return;
            }
            button.prop('disabled', true).text('Registering...'); // Disable button

            $.ajax({
                url: '{{ route('admin.book-appointment.walk-in-patient') }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', name: name, phone: phone, email: email },
                success: function(response) {
                    if (response.success) {
                        $('#newPatientModal').modal('hide');
                        // Clear modal fields for next time
                        $('#new-patient-name, #new-patient-phone, #new-patient-email').val('');

                        $('#patient_id').val(response.patient_id);
                        // Trigger the check patient button click to populate fields
                        $('#check-patient').click();
                        alert(response.message);
                    } else {
                         alert(response.message || 'Failed to register patient.');
                    }
                },
                error: function(xhr) {
                    alert('Failed to register patient: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Please try again.'));
                },
                complete: function() {
                     button.prop('disabled', false).text('Register Patient'); // Re-enable button
                }
            });
        });

        // --- Appointment Date/Location/Doctor Logic ---
        // When Appointment Date/Time Changes
        $('#appointment_date').on('change', function() {
            var date = $(this).val();
            const locationSelect = $('#clinic_id');
            const doctorSelect = $('#doctor_id');

            // Reset dependent dropdowns
            updateSelectPicker(locationSelect, [], 'Loading Locations...', true); // Show loading, keep disabled
            updateSelectPicker(doctorSelect, [], 'Select Location First', true);

            if (!date) {
                updateSelectPicker(locationSelect, [], 'Select Date/Time First', true); // Reset text if date cleared
                return;
            }

            // AJAX Call 1: Get Available Locations
            $.ajax({
                url: '{{ route('admin.book-appointment.available-locations') }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', date: date },
                success: function(response) {
                    if (response.locations && response.locations.length > 0) {
                        updateSelectPicker(locationSelect, response.locations, 'Select Available Location', false); // Enable
                    } else {
                         updateSelectPicker(locationSelect, [], 'No Locations Available', true); // Keep disabled
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching locations:', xhr.responseText);
                    updateSelectPicker(locationSelect, [], 'Error Loading Locations', true);
                    alert('Failed to fetch available locations. Please try again.');
                }
            });
        });

        // When Location Changes
        $('#clinic_id').on('change', function() {
            var clinicId = $(this).val();
            var date = $('#appointment_date').val();
            const doctorSelect = $('#doctor_id');

            updateSelectPicker(doctorSelect, [], 'Loading Doctors...', true); // Show loading, keep disabled

            if (!clinicId || !date) {
                 updateSelectPicker(doctorSelect, [], 'Select Location First', true); // Reset text
                return;
            }

            // AJAX Call 2: Get Available Doctors
            $.ajax({
                url: '{{ route('admin.book-appointment.available-doctors') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    date: date,
                    clinic_id: clinicId,
                    duration: $('#service_duration').val() // Send duration
                },
                success: function(response) {
                    if (response.doctors && response.doctors.length > 0) {
                         updateSelectPicker(doctorSelect, response.doctors, 'Select Available Doctor', false); // Enable
                    } else {
                        updateSelectPicker(doctorSelect, [], 'No Doctors Available', true); // Keep disabled
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching doctors:', xhr.responseText);
                    updateSelectPicker(doctorSelect, [], 'Error Loading Doctors', true);
                    alert('Failed to fetch available doctors for this location. Please try again.');
                }
            });
        });

        // --- Service, Duration, and Price Logic ---
        function updatePrice() {
            var selectedService = $('#service_id option:selected');
            if (!selectedService.length || !selectedService.val()) {
                $('#service_price_display').text('₦0.00');
                $('#service_price').val('0');
                return;
            }

            var basePrice = parseFloat(selectedService.data('price'));
            var selectedDuration = parseInt($('#service_duration').val());
            // Use a default base duration if not provided, e.g., 30 minutes
            var baseDuration = parseInt(selectedService.data('duration')) || 30;

            if (isNaN(basePrice) || isNaN(selectedDuration) || isNaN(baseDuration) || baseDuration <= 0) {
                $('#service_price_display').text('₦0.00'); // Or show an error/base price
                $('#service_price').val(basePrice.toFixed(2)); // Default to base price on error?
                console.error("Price calculation error: Invalid data", { basePrice, selectedDuration, baseDuration });
                return;
            }

            var calculatedPrice = basePrice * (selectedDuration / baseDuration);
            $('#service_price_display').text('₦' + calculatedPrice.toFixed(2));
            $('#service_price').val(calculatedPrice.toFixed(2));
        }

        // Handle service selection
        $('#service_id').on('change', function() {
             // Maybe reset duration to default for the service?
             // const defaultDuration = $(this).find('option:selected').data('duration') || 30;
             // $('#service_duration').val(defaultDuration);
             // $('.time-duration-option').removeClass('active');
             // $(`.time-duration-option[data-duration="${defaultDuration}"]`).addClass('active');
            updatePrice();
        });

        // Handle duration selection
        $(document).on('click', '.time-duration-option', function(e) {
            e.preventDefault();
            $('.time-duration-option').removeClass('active');
            $(this).addClass('active');
            var duration = $(this).data('duration');
            $('#service_duration').val(duration);
            updatePrice();
             // IMPORTANT: Re-fetch doctors if duration changes, as conflicts depend on it
             $('#clinic_id').trigger('change');
        });

        // --- Reset Form Functionality ---
        window.resetForm = function() { // Make it globally accessible if needed, or keep local
            $('#book-appointment-form')[0].reset(); // Reset native form elements

            // Reset Bootstrap Select pickers
            $('#clinic_id, #doctor_id, #service_id').val('').selectpicker('refresh');

            // Reset patient details visibility and buttons
            $('#patient-details').hide();
            $('#patient_name').val('');
            $('#patient_email').val('');
            $('#check-patient').show();
            $('#newPatientModal').closest('div').find('button[data-target="#newPatientModal"]').show();


            // Reset duration buttons and hidden input
            $('.time-duration-option').removeClass('active');
            $('.time-duration-option[data-duration="30"]').addClass('active');
            $('#service_duration').val('30');

            // Reset price display
            updatePrice();

            // Reset and disable location/doctor dropdowns
            updateSelectPicker($('#clinic_id'), [], 'Select Date/Time First', true);
            updateSelectPicker($('#doctor_id'), [], 'Select Location First', true);

             // Clear search field and results
            $('#patient_search').val('');
            $('#patient-search-results').hide();
        }

        // --- Initial Page Load Setup ---
        // Set default duration option as active
        $('.time-duration-option[data-duration="30"]').addClass('active');
        $('#service_duration').val('30'); // Ensure hidden input matches

        // Initialize Location/Doctor dropdowns as disabled
        updateSelectPicker($('#clinic_id'), [], 'Select Date/Time First', true);
        updateSelectPicker($('#doctor_id'), [], 'Select Location First', true);

         // If a service is already selected (e.g., from validation error), calculate the initial price
         if ($('#service_id').val()) {
            updatePrice(); // Use updatePrice which reads current duration
         } else {
             updatePrice(); // Calculate initial price (likely ₦0.00)
         }


        // Auto-check patient if pre-populated data is present (from redirect)
        @if(isset($patientData) && !empty($patientData['user_id']))
            $('#patient_id').val('{{ $patientData['user_id'] }}');
            // Trigger check to populate name/email and hide buttons
            $('#check-patient').click();
        @endif

    }); // <-- End of jQuery ready function
</script>
</body>
</html>