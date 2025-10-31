<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Admin :: Doctor Profile</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
<!-- JQuery DataTable Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/timeline.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Added a little extra style for the new timeline bullets -->
<style>
    .new_timeline ul li .bullet.red { background-color: #E53935; }
    .new_timeline ul li .bullet.orange { background-color: #FB8C00; }
</style>

</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Doctor Profile
                <small>Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">                
                <a href="{{ route('admin.doctor.edit', $doctor->user_id) }}" class="btn btn-white btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-edit"></i>
                </a>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.doctor.index') }}">Doctors</a></li>
                    <li class="breadcrumb-item active">Doctor Profile</li>
                </ul>                
            </div>
        </div>
    </div>    
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card profile-header">
                    <div class="body text-center">
                        <div class="profile-image"> 
                            @if($doctor->user && $doctor->user->photo)
                                <img src="{{ asset('storage/' . $doctor->user->photo) }}" alt="Doctor Profile"> 
                            @else
                                <img src="{{ asset('assets/images/profile_av.jpg') }}" alt="Doctor Profile"> 
                            @endif
                        </div>

                        <div>
                            <h4 class="m-b-0"><strong>Dr. {{ $doctor->user->name ?? 'Unknown Doctor' }}</strong></h4>
                            <span class="job_post">
                                @if($doctor->category)
                                    {{ $doctor->category->name }}
                                @else
                                    {{ $doctor->specialization ?? 'Specialist' }}
                                @endif
                            </span>
                            @if($doctor->user)
                                <p>{{ $doctor->user->address ?? 'Address not provided' }}</p>
                            @else
                                <p>Address not provided</p>
                            @endif
                        </div>
                        <div>
                            @if($doctor->status == 'verified')
                                <span class="badge badge-success">Verified</span>
                            @elseif($doctor->status == 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </div>
                        <p class="social-icon m-t-5 m-b-0">
                            <a title="Twitter" href="javascript:void(0);"><i class="zmdi zmdi-twitter"></i></a>
                            <a title="Facebook" href="javascript:void(0);"><i class="zmdi zmdi-facebook"></i></a>
                            <a title="Google-plus" href="javascript:void(0);"><i class="zmdi zmdi-google-plus"></i></a>
                            <a title="Behance" href="javascript:void(0);"><i class="zmdi zmdi-behance"></i></a>
                            <a title="Instagram" href="javascript:void(0);"><i class="zmdi zmdi-instagram "></i></a>
                        </p>
                    </div>                    
                </div>                               
                
                <div class="card">
                    <div class="header">
                        <h2><strong>Contact</strong> Information</h2>
                    </div>
                    <div class="body">
                        <small class="text-muted">Email address: </small>
                        <p>{{ $doctor->user->email ?? 'Email not provided' }}</p>
                        <hr>
                        <small class="text-muted">Phone: </small>
                        <p>{{ $doctor->user->phone ?? 'Phone not provided' }}</p>
                        <hr>
                        <small class="text-muted">Address: </small>
                        <p>{{ $doctor->user->address ?? 'Address not provided' }}</p>
                        <hr>
                        <small class="text-muted">License Number: </small>
                        <p>{{ $doctor->license_number ?? 'License number not provided' }}</p>
                        <hr>
                        <small class="text-muted">Status: </small>
                        <p>
                            @if($doctor->status == 'verified')
                                <span class="badge badge-success">Verified</span>
                            @elseif($doctor->status == 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#schedule">Schedule</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#appointments">Appointments</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane body active" id="about">
                            <h6>Biography</h6>
                            <p>{{ $doctor->bio ?? 'No biography provided.' }}</p>
                            
                            <h6>Education & Qualifications</h6>
                            <hr>
                            <ul class="list-unstyled">
                                <li>
                                    <p><strong>Medical School:</strong> {{ $doctor->medical_school ?? 'Not specified' }}</p>
                                </li>
                                <li>
                                    <p><strong>Residency:</strong> {{ $doctor->residency ?? 'Not specified' }}</p>
                                </li>
                                <li>
                                    <p><strong>Fellowship:</strong> {{ $doctor->fellowship ?? 'Not specified' }}</p>
                                </li>
                                <li>
                                    <p><strong>Years of Experience:</strong> 
                                        @if($doctor->years_of_experience)
                                            {{ $doctor->years_of_experience }} year{{ $doctor->years_of_experience != 1 ? 's' : '' }}
                                        @else
                                            @if($doctor->user)
                                                @php
                                                    $experience = \Carbon\Carbon::parse($doctor->user->created_at)->diffInYears(\Carbon\Carbon::now());
                                                    echo $experience . ' year' . ($experience != 1 ? 's' : '');
                                                @endphp
                                            @else
                                                Not specified
                                            @endif
                                        @endif
                                    </p>
                                </li>
                            </ul>
                            
                            <h6>Department & Category</h6>
                            <hr>
                            <ul class="list-unstyled">
                                <li>
                                    <p><strong>Department:</strong> 
                                        @if($doctor->department)
                                            {{ $doctor->department->name }}
                                        @else
                                            Not assigned
                                        @endif
                                    </p>
                                </li>
                                <li>
                                    <p><strong>Category:</strong> 
                                        @if($doctor->category)
                                            {{ $doctor->category->name }}
                                        @else
                                            Not assigned
                                        @endif
                                    </p>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-pane body" id="schedule">
                            <div class="workingtime"> @php
                                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                @endphp
                                @foreach($days as $day)
                                <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                                    <p class="text-dark font-weight-bold mb-0 text-capitalize">{{ $day }}</p>
                
                                    {{-- This code now works because we created $doctorSchedule in the controller --}}
                                    @if(isset($doctorSchedule[$day]) && $doctorSchedule[$day]->isNotEmpty())
                                <div>
                                    @foreach($doctorSchedule[$day] as $schedule)
                                        <small class="d-block text-muted">
                                            <i class="zmdi zmdi-time"></i>
                                            {{-- Format the time --}}
                                            {{ \Carbon\Carbon::parse($schedule->start_time ?? now())->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time ?? now())->format('g:i A') }}
                             
                                            {{-- Get the clinic name or location --}}
                                            ({{ $schedule->location == 'virtual' ? 'Virtual' : ($schedule->clinic?->name ?? 'Clinic '.$schedule->location) }})
                                        </small>
                                    @endforeach
                            </div>
                                @else
                                {{-- Show "Not Available" if no schedule exists for that day --}}
                                    <p class="mb-0 text-muted"><i class="zmdi zmdi-block"></i> Not Available</p>
                                @endif
                            </div>
                                @endforeach
        
                            </div>
    
                        </div>
                        <div class="tab-pane body" id="appointments">
                            <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Date & Time</th>
                                            <th>Patient</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        {{-- 
                                            We loop DIRECTLY over $consultations, which is loaded
                                            by your controller. No more mixing lists!
                                        --}}
                                        @forelse($consultations->take(20) as $consultation)
                                            <tr>
                                                {{-- Use the consultation's date --}}
                                                <td>{{ $consultation->start_time->format('M d, Y g:i A') }}</td>
                                                <td>
                                                    {{-- Use the consultation's patient --}}
                                                    @if($consultation->patient)
                                                        @if($consultation->patient->photo)
                                                            <img src="{{ asset('storage/' . $consultation->patient->photo) }}" class="rounded-circle" alt="Avatar" width="30">
                                                        @else
                                                            <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                        @endif
                                                        <span>{{ $consultation->patient->name ?? 'Unknown Patient' }}</span>
                                                    @else
                                                        <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                        <span>Unknown Patient</span>
                                                    @endif
                                                </td>
                                                
                                                {{-- Use the consultation's service type --}}
                                                <td>{{ $consultation->service_type ?? 'General Consultation' }}</td>
                                                
                                                <td>
                                                    {{-- Use the consultation's status --}}
                                                    @php
                                                        $status = $consultation->status ?? 'pending';
                                                        $badgeClass = 'badge-default';
                                                        if ($status == 'completed') $badgeClass = 'badge-success';
                                                        elseif ($status == 'confirmed' || $status == 'scheduled' || $status == 'in_progress') $badgeClass = 'badge-info';
                                                        elseif ($status == 'pending') $badgeClass = 'badge-warning';
                                                        elseif ($status == 'cancelled' || $status == 'missed') $badgeClass = 'badge-danger';
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                                </td>
                                                
                                                <td>
                                                    {{-- We know this is a consultation, so we can link to its detail page --}}
                                                    {{-- You can update this route when you have one --}}
                                                    <a href="#" class="btn btn-sm btn-info">View</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No consultations found</td>
                                            </tr>
                                        @endforelse
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ############################################################### -->
                <!-- THIS IS THE NEW, BEAUTIFUL "TODAY'S SCHEDULE" CARD -->
                <!-- ############################################################### -->
                <div class="card">
                    <div class="header">
                        <h2><strong>Today's</strong> Schedule</h2>
                        <ul class="header-dropdown">
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="new_timeline">
                            <div class="header">
                                <!-- This header is now dynamic and shows the REAL current date -->
                                <div class="color-overlay">
                                    <div class="day-number">{{ now()->format('d') }}</div>
                                    <div class="date-right">
                                    <div class="day-name">{{ now()->format('l') }}</div>
                                    <div class="month">{{ now()->format('F Y') }}</div>
                                    </div>
                                </div>                                
                            </div>
                            <!-- This list is now dynamic and shows the REAL appointments -->
                            <ul>
                                @forelse($todaysAppointments as $appointment)
                                    @php
                                        // This logic picks the bullet color based on the appointment status
                                        $status = $appointment->status ?? 'pending';
                                        $bulletColor = 'orange'; // default for pending
                                        if ($status == 'confirmed' || $status == 'in_progress') $bulletColor = 'green';
                                        elseif ($status == 'completed') $bulletColor = 'pink';
                                        elseif ($status == 'cancelled' || $status == 'missed') $bulletColor = 'red';
                                    @endphp
                                    <li>
                                        <div class="bullet {{ $bulletColor }}"></div>
                                        <div class="time">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</div>
                                        <div class="desc">
                                            <h3>{{ $appointment->patient->name ?? 'Unknown Patient' }}</h3>
                                            <h4>
                                                {{ $appointment->consultation?->service_type ?? $appointment->reason ?? 'Consultation' }}
                                                
                                                <!-- This is the status badge logic from your old card -->
                                                @php
                                                    $badgeClass = 'badge-default';
                                                    if ($status == 'completed') $badgeClass = 'badge-success';
                                                    elseif ($status == 'confirmed' || $status == 'in_progress') $badgeClass = 'badge-info';
                                                    elseif ($status == 'pending') $badgeClass = 'badge-warning';
                                                    elseif ($status == 'cancelled' || $status == 'missed') $badgeClass = 'badge-danger';
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                            </h4>
                                        </div>
                                    </li>
                                @empty
                                    <!-- This shows if there are no appointments for today -->
                                    <li>
                                        <div class="bullet orange"></div>
                                        <div class="time">All Day</div>
                                        <div class="desc">
                                            <h3>No appointments today</h3>
                                            <h4>The schedule is clear.</h4>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ############################################################### -->
                <!-- END OF NEW CARD -->
                <!-- ############################################################### -->

            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js --> 

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js --> 
</body>
</html>
