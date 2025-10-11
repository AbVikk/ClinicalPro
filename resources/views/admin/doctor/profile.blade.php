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
                        <h2><strong>Working</strong> Time</h2>
                    </div>
                    <div class="body">
                        <div class="workingtime">
                            @if(isset($doctor->availability) && !empty($doctor->availability))
                                @foreach($doctor->availability as $day => $schedule)
                                    @if(isset($schedule['available']) && $schedule['available'])
                                        <h6>{{ $day }}</h6>
                                        @if(isset($schedule['slots']))
                                            @foreach($schedule['slots'] as $slot)
                                                <p>{{ $slot }}</p>
                                            @endforeach
                                        @else
                                            <p>Available</p>
                                        @endif
                                        <hr>
                                    @endif
                                @endforeach
                            @else
                                <p>No availability schedule set</p>
                            @endif
                        </div>
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
                            <div class="workingtime">
                                @if(isset($doctor->availability) && !empty($doctor->availability))
                                    @foreach($doctor->availability as $day => $schedule)
                                        @if(isset($schedule['available']) && $schedule['available'])
                                            <h6>{{ $day }}</h6>
                                            @if(isset($schedule['slots']) && !empty($schedule['slots']))
                                                @foreach($schedule['slots'] as $slot)
                                                    <p>{{ $slot }}</p>
                                                @endforeach
                                            @else
                                                <p>Available all day</p>
                                            @endif
                                            <hr>
                                        @endif
                                    @endforeach
                                @else
                                    <p>No availability schedule set</p>
                                @endif
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
                                        @php
                                            // Combine old appointments and new consultations
                                            $allAppointments = collect();
                                            
                                            // Add old appointments
                                            foreach($doctor->appointments->take(10) as $appointment) {
                                                $allAppointments->push([
                                                    'id' => $appointment->id,
                                                    'date' => $appointment->appointment_time,
                                                    'patient' => $appointment->patient,
                                                    'service' => $appointment->type ?? 'General Consultation',
                                                    'status' => $appointment->status,
                                                    'type' => 'appointment'
                                                ]);
                                            }
                                            
                                            // Add new consultations if they exist
                                            if(isset($consultations)) {
                                                foreach($consultations->take(10) as $consultation) {
                                                    $allAppointments->push([
                                                        'id' => $consultation->id,
                                                        'date' => $consultation->start_time,
                                                        'patient' => $consultation->patient,
                                                        'service' => $consultation->service_type ?? 'General Consultation',
                                                        'status' => $consultation->status,
                                                        'type' => 'consultation'
                                                    ]);
                                                }
                                            }
                                            
                                            // Sort by date, newest first
                                            $allAppointments = $allAppointments->sortByDesc('date')->take(10);
                                        @endphp
                                        
                                        @forelse($allAppointments as $item)
                                            <tr>
                                                <td>{{ $item['date']->format('M d, Y H:i') }}</td>
                                                <td>
                                                    @if($item['patient'])
                                                        @if($item['patient']->photo)
                                                            <img src="{{ asset('storage/' . $item['patient']->photo) }}" class="rounded-circle" alt="Avatar" width="30">
                                                        @else
                                                            <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                        @endif
                                                        <span>{{ $item['patient']->name ?? 'Unknown Patient' }}</span>
                                                    @else
                                                        <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="Avatar" width="30">
                                                        <span>Unknown Patient</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item['service'] }}</td>
                                                <td>
                                                    @if($item['status'] == 'confirmed' || $item['status'] == 'scheduled')
                                                        <span class="badge badge-success">Confirmed</span>
                                                    @elseif($item['status'] == 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($item['status'] == 'cancelled')
                                                        <span class="badge badge-danger">Cancelled</span>
                                                    @elseif($item['status'] == 'completed')
                                                        <span class="badge badge-info">Completed</span>
                                                    @else
                                                        <span class="badge badge-default">{{ ucfirst($item['status']) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item['type'] == 'consultation')
                                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                                    @else
                                                        <a href="{{ route('appointment.show', $item['id']) }}" class="btn btn-sm btn-info">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No appointments found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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