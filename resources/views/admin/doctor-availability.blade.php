<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>Clinical Pro || Doctor Availability</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Bootstrap Select CSS -->
<link href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Doctor Availability
                <small>Manage doctor schedules and availability</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Doctors</a></li>
                    <li class="breadcrumb-item active">Availability</li>
                </ul>
            </div>
        </div>
    </div>    
    <div class="container-fluid">        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Manage</strong> Doctor Availability</h2>
                        <ul class="header-dropdown">                            
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form id="availability-form" method="POST" action="{{ route('admin.doctors.availability.update') }}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="doctor_id">Select Doctor *</label>
                                        <select id="doctor_id" name="doctor_id" class="form-control show-tick" data-live-search="true" required>
                                            <option value="">- Select Doctor -</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="availability-section" style="display: none;">
                                <h4>Set Availability</h4>
                                <p>Check the days when the doctor is available and set time slots if needed.</p>
                                
                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                @endphp
                                
                                @foreach($days as $day)
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input id="available_{{ strtolower($day) }}" name="availability[{{ $day }}][available]" type="checkbox" value="1">
                                                    <label for="available_{{ strtolower($day) }}">{{ $day }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label>Time Slots (optional)</label>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" name="availability[{{ $day }}][slots][]" placeholder="e.g., 09:00-12:00">
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" name="availability[{{ $day }}][slots][]" placeholder="e.g., 14:00-17:00">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button type="button" class="btn btn-default add-slot" data-day="{{ $day }}">+</button>
                                                    </div>
                                                </div>
                                                <div id="slots-container-{{ $day }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Update Availability</button>
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

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script>
    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
</script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
    // Show availability section when doctor is selected
    $('#doctor_id').on('change', function() {
        if ($(this).val()) {
            $('#availability-section').show();
        } else {
            $('#availability-section').hide();
        }
    });
    
    // Add slot button functionality
    $(document).on('click', '.add-slot', function() {
        var day = $(this).data('day');
        var slotHtml = '<div class="row mt-2">' +
            '<div class="col-sm-5">' +
                '<input type="text" class="form-control" name="availability[' + day + '][slots][]" placeholder="e.g., 09:00-12:00">' +
            '</div>' +
            '<div class="col-sm-5">' +
                '<input type="text" class="form-control" name="availability[' + day + '][slots][]" placeholder="e.g., 14:00-17:00">' +
            '</div>' +
            '<div class="col-sm-2">' +
                '<button type="button" class="btn btn-danger remove-slot">-</button>' +
            '</div>' +
        '</div>';
        
        $('#slots-container-' + day).append(slotHtml);
    });
    
    // Remove slot button functionality
    $(document).on('click', '.remove-slot', function() {
        $(this).closest('.row').remove();
    });
    
    // Reset form
    function resetForm() {
        $('#availability-form')[0].reset();
        $('#availability-section').hide();
        $('.add-slot').closest('.row').find('.remove-slot').closest('.row').remove();
    }
</script>
</body>
</html>