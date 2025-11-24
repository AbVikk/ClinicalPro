<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>Clinical Pro || Book Appointment</title>
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link href="{{ asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    /* ... (all your existing <style> content is perfect, no change needed) ... */
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
    
    .duration-options {
        display: flex; /* Use flexbox for layout */
        flex-wrap: wrap; /* Allow wrapping */
        gap: 5px; /* Spacing between buttons */
        margin-top: 5px;
    }

    .time-duration-option {
        /* Adjusted for flexbox layout */
        display: inline-block;
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        background-color: #f8f9fa;
        font-size: 14px;
        line-height: 1.2;
        transition: all 0.2s;
    }

    .time-duration-option.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    #service_duration {
        max-width: 150px; /* Keep the custom input reasonably sized */
        display: block; /* Ensure it starts on a new line */
        margin-top: 10px;
    }
</style>
</head>
<body class="theme-cyan ls-closed">
{{-- This includes the nurse's sidebar --}}
@include('nurse.sidemenu')

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
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
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
						<h2><strong>Book</strong> Appointment<small>Nurse booking appointment for patient</small> </h2>
						<ul class="header-dropdown">                            
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
					</div>
					<div class="body">
                        {{-- (Error and success messages) --}}
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
                        
                        {{-- Form action points to nurse route --}}
                        <form id="book-appointment-form" method="POST" action="{{ route('nurse.book-appointment.store') }}">
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
                                        <button type="button" id="check-patient" class="btn btn-info" {{ (isset($patientData) && !empty($patientData)) ? 'style="display:none;"' : 'style="display:inline-block;"' }}>Check Existing Patient</button>
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#newPatientModal" {{ (isset($patientData) && !empty($patientData)) ? 'style="display:none;"' : 'style="display:inline-block;"' }}>New Walk-In Patient</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="patient-details" style="{{ (isset($patientData) && !empty($patientData)) ? 'display: block;' : 'display: none;' }}">
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
                                            <option value="">- Select Date/Time First -</option>
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
                                                            data-duration="{{ $service->default_duration ?? 30 }}">
                                                        {{ $service->service_name }} ({{ $service->formatted_price }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="service_duration">Duration (Minutes)*</label>
                                            <div class="duration-options" id="duration-options">
                                                <div class="time-duration-option" data-duration="15">15 mins</div>
                                                <div class="time-duration-option active" data-duration="30">30 mins</div>
                                                <div class="time-duration-option" data-duration="40">40 mins</div>
                                                <div class="time-duration-option" data-duration="45">45 mins</div>
                                                <div class="time-duration-option" data-duration="60">60 mins</div>
                                                <div class="time-duration-option" data-duration="90">90 mins</div>
                                            </div>
                                            <input type="number" id="service_duration" name="service_duration" class="form-control mt-2" value="30" min="5" placeholder="Custom Minutes" required>
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
                                        {{-- --- PAYMENT BUTTONS --- --}}
                                        
                                        <button type="submit" name="payment_method" value="paystack" class="btn btn-primary btn-round">
                                            <i class="zmdi zmdi-card"></i> Proceed to Pay (Card)
                                        </button>
                                        
                                        <button type="submit" name="payment_method" value="cash" class="btn btn-warning btn-round">
                                            <i class="zmdi zmdi-money"></i> Book (Pay with Cash)
                                        </button>
                                        
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

{{-- (Walk-in Patient Modal) --}}
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

<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script>
    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
</script>

<script src="{{ asset('assets/plugins/momentjs/moment.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

{{-- This file was removed, as the correct JS is below --}}
{{-- <script src="{{ asset('js/services.js') }}"></script> --}}

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script>
    // --- This is the correct JavaScript that handles all the logic ---
    $(function () { 

        // --- Initialize Plugins ---
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY - HH:mm',
            clearButton: true,
            weekStart: 1,
            minDate: new Date()
        });
        $('.show-tick').selectpicker();

        // --- Core Smart Billing Logic ---
        function getServiceData(serviceId) {
            const serviceOption = $('#service_id option[value="' + serviceId + '"]');
            if (!serviceOption.length) return null;
            const basePrice = parseFloat(serviceOption.data('price')) || 0;
            const includedDuration = parseInt(serviceOption.data('duration')) || 30; 
            const ratePerMinute = basePrice / includedDuration; 
            return {
                basePrice: basePrice,
                includedDuration: includedDuration,
                ratePerMinute: ratePerMinute
            };
        }

        function updatePrice() {
            var selectedServiceId = $('#service_id').val();
            var duration = parseInt($('#service_duration').val()); 
            const feeDisplayField = $('#service_price_display');
            const feeValueField = $('#service_price');
            const serviceData = getServiceData(selectedServiceId);

            if (!serviceData || isNaN(duration) || duration < 1) {
                feeDisplayField.text('₦0.00');
                feeValueField.val('0');
                return;
            }
            
            const { basePrice, includedDuration, ratePerMinute } = serviceData;
            let finalFee = duration * ratePerMinute;
            const minBillableDuration = 15;
            const minFee = minBillableDuration * ratePerMinute;
            finalFee = Math.max(finalFee, minFee); 
            finalFee = Math.round(finalFee); 
            
            feeDisplayField.text('₦' + finalFee.toLocaleString('en-NG', { maximumFractionDigits: 0 }));
            feeValueField.val(finalFee); 
            $('#clinic_id').trigger('change');
        }

        // --- Patient Search & Selection (Routes updated) ---
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
                    url: '{{ route('nurse.book-appointment.search-patients') }}', 
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
                    }
                });
            }, 300);
        });

        $(document).on('click', '#patient-results-list li', function() {
            if ($(this).data('patient-id')) {
                const patientId = $(this).data('patient-id');
                const patientName = $(this).data('patient-name');
                const patientEmail = $(this).data('patient-email');

                $('#patient_id').val(patientId);
                $('#patient_name').val(patientName);
                $('#patient_email').val(patientEmail);
                $('#patient-search-results').hide();
                $('#patient_search').val('');
                $('#patient-details').show();
                $('#check-patient').hide();
                $('button[data-target="#newPatientModal"]').hide();
                $('#appointment_date').focus();
            }
        });

        // --- Check Existing Patient Button (Route updated) ---
        $('#check-patient').on('click', function() {
            var patientId = $('#patient_id').val();
            if (!patientId) {
                alert('Please enter a patient ID');
                return;
            }
            $.ajax({
                url: '{{ route('nurse.book-appointment.patient-info') }}', 
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', patient_id: patientId },
                success: function(response) {
                    if (response.patient) {
                        $('#patient_name').val(response.patient.name);
                        $('#patient_email').val(response.patient.email || '');
                        $('#patient-details').show();
                        $('#check-patient').hide();
                        $('button[data-target="#newPatientModal"]').hide();
                    } else {
                         alert('Patient details could not be retrieved.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        alert('Patient not found. Please check the patient ID or register as a New Walk-In Patient.');
                         $('#patient_id').focus();
                    } else {
                        alert('An error occurred checking the patient ID. Please try again.');
                    }
                     $('#check-patient').show();
                     $('button[data-target="#newPatientModal"]').show();
                     $('#patient-details').hide();
                     $('#patient_name').val('');
                     $('#patient_email').val('');
                }
            });
        });

        // --- Walk-In Patient Modal (Route updated) ---
        $('#newPatientModal').on('shown.bs.modal', function () {
           $('#new-patient-name').focus();
        });

        $('#register-walk-in').on('click', function() {
            var name = $('#new-patient-name').val();
            var phone = $('#new-patient-phone').val();
            var email = $('#new-patient-email').val();
            var button = $(this);

            if (!name || !phone) {
                alert('Name and phone are required');
                return;
            }
            button.prop('disabled', true).text('Registering...');

            $.ajax({
                url: '{{ route('nurse.book-appointment.walk-in-patient') }}', 
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', name: name, phone: phone, email: email },
                success: function(response) {
                    if (response.success) {
                        $('#newPatientModal').modal('hide');
                        $('#new-patient-name, #new-patient-phone, #new-patient-email').val('');
                        $('#patient_id').val(response.patient_id);
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
                     button.prop('disabled', false).text('Register Patient');
                }
            });
        });

        // --- Appointment Date/Location/Doctor Logic (Routes updated) ---
        function updateSelectPicker(selectElement, options, placeholder, isDisabled) {
            selectElement.empty();
            selectElement.append('<option value="">- ' + placeholder + ' -</option>');
            if (options && options.length > 0) {
                options.forEach(function(option) {
                    const id = option.id === 'virtual' ? 'virtual' : option.id;
                    selectElement.append('<option value="' + id + '">' + option.name + '</option>');
                });
            }
            selectElement.prop('disabled', isDisabled);
            selectElement.selectpicker('refresh');
        }

        $('#appointment_date').on('change', function() {
            var date = $(this).val();
            const locationSelect = $('#clinic_id');
            const doctorSelect = $('#doctor_id');
            updateSelectPicker(locationSelect, [], 'Loading Locations...', true);
            updateSelectPicker(doctorSelect, [], 'Select Location First', true);
            if (!date) {
                updateSelectPicker(locationSelect, [], 'Select Date/Time First', true);
                return; // <-- THIS IS THE FIX. The extra period is removed.
            }
            $.ajax({
                url: '{{ route('nurse.book-appointment.available-locations') }}', 
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', date: date },
                success: function(response) {
                    if (response.locations && response.locations.length > 0) {
                        updateSelectPicker(locationSelect, response.locations, 'Select Available Location', false);
                    } else {
                         updateSelectPicker(locationSelect, [], 'No Locations Available', true);
                    }
                }
            });
        });

        $('#clinic_id').on('change', function() {
            var clinicId = $(this).val();
            var date = $('#appointment_date').val();
            const doctorSelect = $('#doctor_id');
            updateSelectPicker(doctorSelect, [], 'Loading Doctors...', true);
            if (!clinicId || !date) {
                 updateSelectPicker(doctorSelect, [], 'Select Location First', true);
                return;
            }
            $.ajax({
                url: '{{ route('nurse.book-appointment.available-doctors') }}', 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    date: date,
                    clinic_id: clinicId,
                    duration: $('#service_duration').val() 
                },
                success: function(response) {
                    if (response.doctors && response.doctors.length > 0) {
                         updateSelectPicker(doctorSelect, response.doctors, 'Select Available Doctor', false);
                    } else {
                        updateSelectPicker(doctorSelect, [], 'No Doctors Available', true);
                    }
                }
            });
        });

        // --- Service, Duration, and Price Logic ---
        $('#service_id').on('change', updatePrice);
        $('#service_duration').on('input', updatePrice);
        $(document).on('click', '.time-duration-option', function(e) {
            e.preventDefault();
            $('.time-duration-option').removeClass('active');
            $(this).addClass('active');
            var duration = $(this).data('duration');
            $('#service_duration').val(duration); 
            updatePrice();
        });

        // --- Reset Form Functionality ---
        window.resetForm = function() {
            $('#book-appointment-form')[0].reset();
            $('#clinic_id, #doctor_id, #service_id').val('').selectpicker('refresh');
            $('#patient-details').hide();
            $('#patient_name').val('');
            $('#patient_email').val('');
            $('#check-patient').show();
            $('button[data-target="#newPatientModal"]').show();
            $('.time-duration-option').removeClass('active');
            $('.time-duration-option[data-duration="30"]').addClass('active');
            $('#service_duration').val('30');
            updatePrice();
            updateSelectPicker($('#clinic_id'), [], 'Select Date/Time First', true);
            updateSelectPicker($('#doctor_id'), [], 'Select Location First', true);
            $('#patient_search').val('');
            $('#patient-search-results').hide();
        }

        // --- Initial Page Load Setup ---
        $('.time-duration-option').removeClass('active');
        $('.time-duration-option[data-duration="30"]').addClass('active');
        $('#service_duration').val('30');
        updateSelectPicker($('#clinic_id'), [], 'Select Date/Time First', true);
        updateSelectPicker($('#doctor_id'), [], 'Select Location First', true);
         if ($('#service_id').val()) {
            updatePrice();
         } else {
             updatePrice();
         }
        @if(isset($patientData) && !empty($patientData['user_id']))
            $('#patient_id').val('{{ $patientData['user_id'] }}');
            $('#patient-details').show();
            $('#check-patient').hide();
            $('button[data-target="#newPatientModal"]').hide();
        @endif
    });
</script>
@stack('page-scripts')
</body>
</html>