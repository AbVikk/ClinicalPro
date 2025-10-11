<!doctype html>
<html class="no-js " lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Healthcare System - @yield('description', 'Comprehensive healthcare management system')">
    <title>@yield('title', 'Healthcare System') :: Hospital Management</title>
    
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('template/images/favicon.ico') }}" type="image/x-icon">
    
    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('template/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('template/plugins/morrisjs/morris.min.css') }}" />
    
    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('template/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/color_skins.css') }}">
    
    <!-- Additional CSS -->
    @yield('css')
</head>
<body class="theme-cyan">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('template/images/logo.svg') }}" width="48" height="48" alt="Healthcare System"></div>
            <p>Please wait...</p>        
        </div>
    </div>
    
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    
    <!-- Top Bar -->
    <nav class="navbar p-l-5 p-r-5">
        <ul class="nav navbar-nav navbar-left">
            <li>
                <div class="navbar-header">
                    <a href="javascript:void(0);" class="bars"></a>
                    <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('template/images/logo.svg') }}" width="30" alt="Healthcare System"><span class="m-l-10">Healthcare System</span></a>
                </div>
            </li>
            <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap"></i></a></li>
            <li class="d-none d-lg-inline-block"><a href="{{ url('/events') }}" title="Events"><i class="zmdi zmdi-calendar"></i></a></li>
            <li class="d-none d-lg-inline-block"><a href="{{ url('/messages') }}" title="Messages"><i class="zmdi zmdi-email"></i></a></li>
            <li><a href="{{ url('/contacts') }}" title="Contact List"><i class="zmdi zmdi-account-box-phone"></i></a></li>
            
            <!-- Notifications -->
            <li class="dropdown"> 
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                    <i class="zmdi zmdi-notifications"></i>
                    <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                </a>
                <ul class="dropdown-menu pullDown">
                    <li class="body">
                        <ul class="menu list-unstyled">
                            @if(Auth::check())
                                <!-- Sample notifications - you can replace with dynamic data -->
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="media">
                                            <img class="media-object" src="{{ asset('template/images/xs/avatar2.jpg') }}" alt="">
                                            <div class="media-body">
                                                <span class="name">Dr. Smith <span class="time">30min ago</span></span>
                                                <span class="message">Your appointment has been confirmed</span>                                        
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="media">
                                            <img class="media-object" src="{{ asset('template/images/xs/avatar3.jpg') }}" alt="">
                                            <div class="media-body">
                                                <span class="name">Nurse Johnson <span class="time">1hr ago</span></span>
                                                <span class="message">Please update your medical records</span>                                        
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('login') }}">
                                        <div class="media">
                                            <div class="media-body">
                                                <span class="name">Please login to see notifications</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="footer"> <a href="javascript:void(0);">View All</a> </li>
                </ul>
            </li>
            
            <!-- Tasks/Projects -->
            <li class="dropdown"> 
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                    <i class="zmdi zmdi-flag"></i>
                    <div class="notify">
                        <span class="heartbit"></span>
                        <span class="point"></span>
                    </div>
                </a>
                <ul class="dropdown-menu pullDown">
                    <li class="header">Projects</li>
                    <li class="body">
                        <ul class="menu tasks list-unstyled">
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="progress-container progress-primary">
                                        <span class="progress-badge">Patient Management</span>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                                <span class="progress-value">86%</span>
                                            </div>
                                        </div>                        
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="progress-container progress-info">
                                        <span class="progress-badge">Appointment System</span>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
                                                <span class="progress-value">45%</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="footer"><a href="javascript:void(0);">View All</a></li>
                </ul>
            </li>
            
            <li class="d-none d-md-inline-block">
                <div class="input-group">                
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-addon">
                        <i class="zmdi zmdi-search"></i>
                    </span>
                </div>
            </li>
            
            <!-- User Account -->
            <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                    <i class="zmdi zmdi-account-circle"></i>
                </a>
                <ul class="dropdown-menu pullRight">
                    <li>
                        @auth
                            <div class="dropdown-header">Welcome, {{ Auth::user()->name }}</div>
                            <li><a href="{{ url('/profile') }}"><i class="zmdi zmdi-account"></i>Profile</a></li>
                            <li><a href="{{ url('/settings') }}"><i class="zmdi zmdi-settings"></i>Settings</a></li>
                            <li>
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="zmdi zmdi-power"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @else
                            <div class="dropdown-header">Guest User</div>
                            <li><a href="{{ route('login') }}"><i class="zmdi zmdi-lock"></i>Login</a></li>
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}"><i class="zmdi zmdi-account-add"></i>Register</a></li>
                            @endif
                        @endauth
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <div class="menu">
            <ul class="list">
                <li>
                    <div class="user-info">
                        <div class="image">
                            @if(Auth::check())
                                <img src="{{ asset('template/images/profile_av.jpg') }}" alt="User">
                            @else
                                <img src="{{ asset('template/images/profile_av.jpg') }}" alt="Guest">
                            @endif
                        </div>
                        <div class="detail">
                            @if(Auth::check())
                                <h4>{{ Auth::user()->name }}</h4>
                                <small>{{ ucfirst(Auth::user()->role) }}</small>
                            @else
                                <h4>Guest User</h4>
                                <small>Visitor</small>
                            @endif
                        </div>
                        <a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a>
                        <a title="twitter" href="#"><i class="zmdi zmdi-twitter"></i></a>
                        <a title="instagram" href="#"><i class="zmdi zmdi-instagram"></i></a>
                    </div>
                </li>
                
                <li class="{{ request()->is('/') ? 'active' : '' }}"> 
                    <a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a> 
                </li>
                
                @auth
                    @switch(Auth::user()->role)
                        @case('admin')
                            <li class="header">ADMINISTRATOR</li>
                            <li class="{{ request()->is('admin/*') ? 'active' : '' }}"> 
                                <a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-view-dashboard"></i><span>Admin Dashboard</span></a> 
                            </li>
                            <li> 
                                <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-accounts"></i><span>Users</span></a>
                                <ul class="ml-menu">
                                    <li><a href="{{ url('/admin/users') }}">All Users</a></li>
                                    <li><a href="{{ url('/admin/doctors') }}">Doctors</a></li>
                                    <li><a href="{{ url('/admin/patients') }}">Patients</a></li>
                                </ul>
                            </li>
                            @break

                        @case('doctor')
                            <li class="header">DOCTOR</li>
                            <li class="{{ request()->is('doctor/*') ? 'active' : '' }}"> 
                                <a href="{{ route('doctor.dashboard') }}"><i class="zmdi zmdi-view-dashboard"></i><span>Doctor Dashboard</span></a> 
                            </li>
                            <li> 
                                <a href="{{ url('/doctor/patients') }}"><i class="zmdi zmdi-accounts"></i><span>My Patients</span></a>
                            </li>
                            <li> 
                                <a href="{{ url('/doctor/appointments') }}"><i class="zmdi zmdi-calendar"></i><span>Appointments</span></a>
                            </li>
                            @break

                        @case('nurse')
                            <li class="header">NURSE</li>
                            <li class="{{ request()->is('clinic/*') ? 'active' : '' }}"> 
                                <a href="{{ route('clinic.dashboard') }}"><i class="zmdi zmdi-view-dashboard"></i><span>Clinic Dashboard</span></a> 
                            </li>
                            <li> 
                                <a href="{{ url('/clinic/patients') }}"><i class="zmdi zmdi-accounts"></i><span>Patients</span></a>
                            </li>
                            <li> 
                                <a href="{{ url('/clinic/appointments') }}"><i class="zmdi zmdi-calendar"></i><span>Appointments</span></a>
                            </li>
                            @break

                        @case('clinic_staff')
                            <li class="header">CLINIC STAFF</li>
                            <li class="{{ request()->is('clinic/*') ? 'active' : '' }}"> 
                                <a href="{{ route('clinic.dashboard') }}"><i class="zmdi zmdi-view-dashboard"></i><span>Clinic Dashboard</span></a> 
                            </li>
                            <li> 
                                <a href="{{ url('/clinic/patients') }}"><i class="zmdi zmdi-accounts"></i><span>Patients</span></a>
                            </li>
                            <li> 
                                <a href="{{ url('/clinic/appointments') }}"><i class="zmdi zmdi-calendar"></i><span>Appointments</span></a>
                            </li>
                            @break

                        @case('patient')
                            <li class="header">PATIENT</li>
                            <li class="{{ request()->is('patient/*') ? 'active' : '' }}"> 
                                <a href="{{ route('patient.dashboard') }}"><i class="zmdi zmdi-view-dashboard"></i><span>Patient Dashboard</span></a> 
                            </li>
                            <li> 
                                <a href="{{ url('/patient/appointments') }}"><i class="zmdi zmdi-calendar"></i><span>My Appointments</span></a>
                            </li>
                            <li> 
                                <a href="{{ url('/patient/records') }}"><i class="zmdi zmdi-file"></i><span>Medical Records</span></a>
                            </li>
                            @break

                        @case('donor')
                            <li class="header">DONOR</li>
                            <li class="{{ request()->is('donor/*') ? 'active' : '' }}"> 
                                <a href="{{ route('donor.dashboard') }}"><i class="zmdi zmdi-view-dashboard"></i><span>Donor Dashboard</span></a> 
                            </li>
                            <li> 
                                <a href="{{ url('/donor/donations') }}"><i class="zmdi zmdi-money"></i><span>My Donations</span></a>
                            </li>
                            @break
                    @endswitch
                @endauth
                
                <li class="header">MAIN</li>
                <li> 
                    <a href="{{ url('/about') }}"><i class="zmdi zmdi-info"></i><span>About</span></a>
                </li>
                <li> 
                    <a href="{{ url('/contact') }}"><i class="zmdi zmdi-email"></i><span>Contact</span></a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <section class="content">
        @yield('content')
    </section>

    <!-- Scripts -->
    <script src="{{ asset('template/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('template/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('template/bundles/morrisscripts.bundle.js') }}"></script>
    <script src="{{ asset('template/bundles/jvectormap.bundle.js') }}"></script>
    <script src="{{ asset('template/bundles/knob.bundle.js') }}"></script>
    <script src="{{ asset('template/bundles/mainscripts.bundle.js') }}"></script>
    <script src="{{ asset('template/js/pages/index.js') }}"></script>
    
    <!-- Additional Scripts -->
    @yield('scripts')
</body>
</html>