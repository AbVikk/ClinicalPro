﻿﻿﻿﻿﻿﻿﻿﻿﻿<!doctype html>
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
                                            <label for="doctor_id">Select Doctor *</label>
                                            <select id="doctor_id" name="doctor_id" class="form-control show-tick" data-live-search="true" required>
                                                <option value="">- Select Doctor -</option>
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
                                                    <option value="{{ $service->id }}" data-price="{{ $service->price_amount }}">
                                                        {{ $service->service_name }} ({{ $service->formatted_price }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="reason">Reason for Appointment</label>
                                            <input type="text" id="reason" name="reason" class="form-control" placeholder="Reason for appointment">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Total Amount: <span id="service_price_display">₦0.00</span></label>
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
    $(function () {
    //Datetimepicker plugin
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY - HH:mm',
        clearButton: true,
        weekStart: 1
    });
});

    // Patient search functionality
    let searchTimeout;
    $('#patient_search').on('input', function() {
        const searchTerm = $(this).val();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Hide results if search term is too short
        if (searchTerm.length < 2) {
            $('#patient-search-results').hide();
            $('#searching-indicator').hide();
            return;
        }
        
        // Show loading indicator
        $('#searching-indicator').show();
        
        // Set a new timeout to debounce the search
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route('admin.book-appointment.search-patients') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    search: searchTerm
                },
                success: function(response) {
                    // Hide loading indicator
                    $('#searching-indicator').hide();
                    
                    if (response.patients && response.patients.length > 0) {
                        let resultsHtml = '';
                        response.patients.forEach(function(patient) {
                            resultsHtml += `
                                <li class="list-group-item list-group-item-action" 
                                    data-patient-id="${patient.user_id}" 
                                    data-patient-name="${patient.name}" 
                                    data-patient-email="${patient.email}">
                                    ${patient.name} (${patient.user_id}) - ${patient.email}
                                </li>
                            `;
                        });
                        
                        $('#patient-results-list').html(resultsHtml);
                        $('#patient-search-results').show();
                    } else {
                        $('#patient-results-list').html('<li class="list-group-item">No patients found</li>');
                        $('#patient-search-results').show();
                    }
                },
                error: function() {
                    // Hide loading indicator
                    $('#searching-indicator').hide();
                    $('#patient-results-list').html('<li class="list-group-item">Error searching patients</li>');
                    $('#patient-search-results').show();
                }
            });
        }, 300); // 300ms debounce
    });
    
    // Handle Enter key press in search field
    $('#patient_search').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            // If there are search results, select the first one
            const firstResult = $('#patient-results-list li').first();
            if (firstResult.length > 0 && !firstResult.hasClass('list-group-item')) {
                firstResult.click();
            }
        }
    });
    
    // Handle patient selection from search results
    $(document).on('click', '#patient-results-list li', function() {
        const patientId = $(this).data('patient-id');
        const patientName = $(this).data('patient-name');
        const patientEmail = $(this).data('patient-email');
        
        // Fill the form fields
        $('#patient_id').val(patientId);
        $('#patient_name').val(patientName);
        $('#patient_email').val(patientEmail);
        
        // Hide search results
        $('#patient-search-results').hide();
        
        // Clear search field
        $('#patient_search').val('');
        
        // Show patient details section
        $('#patient-details').show();
        
        // Trigger the date field to become active
        $('#appointment_date').focus();
    });
    
    // Highlight first result on arrow down
    $('#patient_search').on('keydown', function(e) {
        if (e.which === 40) { // Down arrow
            e.preventDefault();
            const firstResult = $('#patient-results-list li').first();
            if (firstResult.length > 0) {
                $('#patient-results-list li').removeClass('active');
                firstResult.addClass('active');
            }
        }
    });
    
    // Handle selection with arrow keys and Enter
    $('#patient_search').on('keydown', function(e) {
        if (e.which === 40) { // Down arrow
            e.preventDefault();
            const firstResult = $('#patient-results-list li').first();
            if (firstResult.length > 0) {
                $('#patient-results-list li').removeClass('active');
                firstResult.addClass('active');
            }
        } else if (e.which === 13) { // Enter key
            e.preventDefault();
            const activeResult = $('#patient-results-list li.active');
            if (activeResult.length > 0) {
                activeResult.click();
            } else {
                // If no active result, select first one
                const firstResult = $('#patient-results-list li').first();
                if (firstResult.length > 0 && !firstResult.hasClass('list-group-item')) {
                    firstResult.click();
                }
            }
        }
    });

    // Check patient ID and fetch patient details
    $('#check-patient').on('click', function() {
        var patientId = $('#patient_id').val();
        
        if (!patientId) {
            alert('Please enter a patient ID');
            return;
        }
        
        $.ajax({
            url: '{{ route('admin.book-appointment.patient-info') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                patient_id: patientId
            },
            success: function(response) {
                if (response.patient) {
                    $('#patient_name').val(response.patient.name);
                    $('#patient_email').val(response.patient.email);
                    $('#patient-details').show();
                }
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    alert('Patient not found. Please check the patient ID.');
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });

    // When appointment date changes, fetch available doctors
    $('#appointment_date').on('change', function() {
        var date = $(this).val();
        
        if (!date) {
            return;
        }
        
        $.ajax({
            url: '{{ route('admin.book-appointment.available-doctors') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                date: date
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.doctors) {
                    var doctorSelect = $('#doctor_id');
                    doctorSelect.empty();
                    doctorSelect.append('<option value="">- Select Doctor -</option>');
                    
                    if (response.doctors.length > 0) {
                        $.each(response.doctors, function(index, doctor) {
                            doctorSelect.append('<option value="' + doctor.id + '">' + doctor.name + '</option>');
                        });
                    } else {
                        doctorSelect.append('<option value="">No doctors available for this date</option>');
                    }
                    
                    doctorSelect.selectpicker('refresh');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error details:', xhr, status, error);
                console.log('Response text:', xhr.responseText);
                alert('Failed to fetch available doctors. Please try again. Check console for details.');
            }
        });
    });

    // Handle walk-in patient registration
    $('#newPatientModal').on('shown.bs.modal', function () {
        $('#new-patient-name').focus();
    });

    $('#register-walk-in').on('click', function() {
        var name = $('#new-patient-name').val();
        var phone = $('#new-patient-phone').val();
        var email = $('#new-patient-email').val();
        
        if (!name || !phone) {
            alert('Name and phone are required');
            return;
        }
        
        $.ajax({
            url: '{{ route('admin.book-appointment.walk-in-patient') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: name,
                phone: phone,
                email: email
            },
            success: function(response) {
                if (response.success) {
                    $('#newPatientModal').modal('hide');
                    $('#patient_id').val(response.patient_id);
                    $('#check-patient').click(); // Trigger the patient check
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to register patient. Please try again.');
            }
        });
    });

    // Reset form
    function resetForm() {
        $('#book-appointment-form')[0].reset();
        $('#patient-details').hide();
        $('#doctor_id').empty().append('<option value="">- Select Doctor -</option>').selectpicker('refresh');
    }
    
    // Auto-check patient if pre-populated data is present
    $(document).ready(function() {
        @if(isset($patientData) && !empty($patientData['user_id']))
            // If we have pre-populated patient data, populate fields directly
            $('#patient_id').val('{{ $patientData['user_id'] }}');
            @if(!empty($patientData['name']))
                $('#patient_name').val('{{ $patientData['name'] }}');
            @endif
            @if(!empty($patientData['email']))
                $('#patient_email').val('{{ $patientData['email'] }}');
            @endif
            // Show the patient details section
            $('#patient-details').show();
        @endif
    });
    
    // We'll let the form submit normally to handle redirects and session messages properly
    // The session messages will be displayed at the top of the form

</script>
</body>
</html>