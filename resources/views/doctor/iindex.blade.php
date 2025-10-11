<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<title>:: Oreo Hospital :: Home</title>
<link rel="icon" href="{{ asset('template/images/favicon.ico') }}" type="image/x-icon"> <!-- Favicon-->
<link rel="stylesheet" href="{{ asset('template/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('template/plugins/morrisjs/morris.min.css') }}" />
<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('template/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('template/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('template/images/logo.svg') }}" width="48" height="48" alt="Oreo"></div>
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
                <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('template/images/logo.svg') }}" width="30" alt="Oreo"><span class="m-l-10">Oreo</span></a>
            </div>
        </li>
        <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap"></i></a></li>
        <li class="d-none d-lg-inline-block"><a href="{{ url('/events') }}" title="Events"><i class="zmdi zmdi-calendar"></i></a></li>
        <li class="d-none d-lg-inline-block"><a href="{{ url('/messages') }}" title="Inbox"><i class="zmdi zmdi-email"></i></a></li>
        <li><a href="{{ url('/contacts') }}" title="Contact List"><i class="zmdi zmdi-account-box-phone"></i></a></li>
        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-notifications"></i>
            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="body">
                    <ul class="menu list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('template/images/xs/avatar2.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Sophia <span class="time">30min ago</span></span>
                                        <span class="message">There are many variations of passages</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('template/images/xs/avatar3.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Sophia <span class="time">31min ago</span></span>
                                        <span class="message">There are many variations of passages of Lorem Ipsum</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('template/images/xs/avatar4.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Isabella <span class="time">35min ago</span></span>
                                        <span class="message">There are many variations of passages</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('template/images/xs/avatar5.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Alexander <span class="time">35min ago</span></span>
                                        <span class="message">Contrary to popular belief, Lorem Ipsum random</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('template/images/xs/avatar6.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Grayson <span class="time">1hr ago</span></span>
                                        <span class="message">There are many variations of passages</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>                        
                    </ul>
                </li>
                <li class="footer"> <a href="javascript:void(0);">View All</a> </li>
            </ul>
        </li>
        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-flag"></i>
            <div class="notify">
                <span class="heartbit"></span>
                <span class="point"></span>
            </div>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="header">Project</li>
                <li class="body">
                    <ul class="menu tasks list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <div class="progress-container progress-primary">
                                    <span class="progress-badge">Neurology</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                            <span class="progress-value">86%</span>
                                        </div>
                                    </div>                        
                                    <ul class="list-unstyled team-info">
                                        <li class="m-r-15"><small class="text-muted">Team</small></li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar3.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar4.jpg') }}" alt="Avatar">
                                        </li>                            
                                    </ul>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="progress-container progress-info">
                                    <span class="progress-badge">Gynecology</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
                                            <span class="progress-value">45%</span>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled team-info">
                                        <li class="m-r-15"><small class="text-muted">Team</small></li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar10.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar9.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar8.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar7.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar6.jpg') }}" alt="Avatar">
                                        </li>
                                    </ul>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="progress-container progress-warning">
                                    <span class="progress-badge">Cardio Monitoring</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="29" aria-valuemin="0" aria-valuemax="100" style="width: 29%;">
                                            <span class="progress-value">29%</span>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled team-info">
                                        <li class="m-r-15"><small class="text-muted">Team</small></li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar5.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/images/xs/avatar7.jpg') }}" alt="Avatar">
                                        </li>                            
                                    </ul>
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
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                <i class="zmdi zmdi-account-circle"></i>
            </a>
            <ul class="dropdown-menu pullRight">
                <li>
                    <div class="dropdown-header">Profile</div>
                    <li><a href="{{ url('/profile') }}"><i class="zmdi zmdi-account"></i>Profile</a></li>
                    <li><a href="{{ url('/settings') }}"><i class="zmdi zmdi-settings"></i>Settings</a></li>
                    <li><a href="javascript:void(0);"><i class="zmdi zmdi-power"></i>Logout</a></li>
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
                    <div class="image"><img src="{{ asset('template/images/profile_av.jpg') }}" alt="User"></div>
                    <div class="detail">
                        <h4>Michael</h4>
                        <small>Administrator</small>                        
                    </div>
                    <a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a>
                    <a title="twitter" href="#"><i class="zmdi zmdi-twitter"></i></a>
                    <a title="instagram" href="#"><i class="zmdi zmdi-instagram"></i></a>
                </div>
            </li>
            <li class="active open"> <a href="{{ url('/admin') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
            <li class="header">HOSPITAL</li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-calendar-check"></i><span>Appointments</span></a>
                <ul class="ml-menu">
                    <li><a href="#">All Appointments</a></li>
                    <li><a href="#">Add Appointment</a></li>
                    <li><a href="#">Edit Appointment</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-add"></i><span>Doctors</span></a>
                <ul class="ml-menu">
                    <li><a href="#">All Doctors</a></li>
                    <li><a href="#">Add Doctor</a></li>
                    <li><a href="#">Edit Doctor</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-o"></i><span>Patients</span></a>
                <ul class="ml-menu">
                    <li><a href="#">All Patients</a></li>
                    <li><a href="#">Add Patient</a></li>
                    <li><a href="#">Edit Patient</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-bug"></i><span>Departments</span></a>
                <ul class="ml-menu">
                    <li><a href="#">All Departments</a></li>
                    <li><a href="#">Add Department</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-balance-wallet"></i><span>Payments</span></a>
                <ul class="ml-menu">
                    <li><a href="#">All Payments</a></li>
                    <li><a href="#">Add Payment</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-case"></i><span>Staff</span></a>
                <ul class="ml-menu">
                    <li><a href="#">All Staff</a></li>
                    <li><a href="#">Add Staff</a></li>
                    <li><a href="#">Edit Staff</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-file-text"></i><span>Reports</span></a>
                <ul class="ml-menu">
                    <li><a href="#">Annual Reports</a></li>
                    <li><a href="#">Patient Reports</a></li>
                    <li><a href="#">Staff Reports</a></li>
                    <li><a href="#">Finance Reports</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-settings"></i><span>Settings</span></a>
                <ul class="ml-menu">
                    <li><a href="#">General Settings</a></li>
                    <li><a href="#">Profile Settings</a></li>
                    <li><a href="#">SEO Settings</a></li>
                </ul>
            </li>
            <li class="header">EXTRA COMPONENTS</li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-apps"></i><span>App</span></a>
                <ul class="ml-menu">
                    <li><a href="#">Inbox</a></li>
                    <li><a href="#">Chat</a></li>
                    <li><a href="#">Calendar</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-file"></i><span>Pages</span></a>
                <ul class="ml-menu">
                    <li><a href="#">Blank Page</a></li>
                    <li><a href="#">Image Gallery</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Timeline</a></li>
                    <li><a href="#">Invoice</a></li>
                    <li><a href="#">Contact List</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-ui"></i><span>UI Elements</span></a>
                <ul class="ml-menu">
                    <li><a href="#">Bootstrap UI</a></li>
                    <li><a href="#">Icons</a></li>
                    <li><a href="#">Widgets</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- #Menu -->
</aside>

<!-- Main Content -->
<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Dashboard
                <small class="text-muted">Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">                
                <button class="btn btn-primary btn-icon btn-round hidden-sm-down float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat text-center">
                    <div class="body">
                        <div class="progress m-b-10">
                            <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%"></div>
                        </div>
                        <span class="text-muted">Total Users</span>
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="1052" data-speed="1000" data-fresh-interval="700">1052</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat text-center">
                    <div class="body">
                        <div class="progress m-b-10">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%"></div>
                        </div>
                        <span class="text-muted">Doctors</span>
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="251" data-speed="1000" data-fresh-interval="700">251</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat text-center">
                    <div class="body">
                        <div class="progress m-b-10">
                            <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%"></div>
                        </div>
                        <span class="text-muted">Patients</span>
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="1215" data-speed="1000" data-fresh-interval="700">1215</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card widget-stat text-center">
                    <div class="body">
                        <div class="progress m-b-10">
                            <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                        </div>
                        <span class="text-muted">Appointments</span>
                        <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="845" data-speed="1000" data-fresh-interval="700">845</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Recent</strong> Activity</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Activity</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Dr. John Smith</td>
                                        <td>Added new patient record</td>
                                        <td>15 Oct 2025</td>
                                        <td><span class="badge badge-success">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>Patient Mary Johnson</td>
                                        <td>Scheduled appointment</td>
                                        <td>15 Oct 2025</td>
                                        <td><span class="badge badge-info">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>Admin Robert Brown</td>
                                        <td>Updated system settings</td>
                                        <td>14 Oct 2025</td>
                                        <td><span class="badge badge-success">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>Dr. Emily Davis</td>
                                        <td>Prescribed medication</td>
                                        <td>14 Oct 2025</td>
                                        <td><span class="badge badge-success">Completed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>System</strong> Status</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Database Server</td>
                                        <td><span class="badge badge-success">Operational</span></td>
                                        <td>Just now</td>
                                    </tr>
                                    <tr>
                                        <td>Web Server</td>
                                        <td><span class="badge badge-success">Operational</span></td>
                                        <td>5 minutes ago</td>
                                    </tr>
                                    <tr>
                                        <td>File Storage</td>
                                        <td><span class="badge badge-warning">Degraded</span></td>
                                        <td>1 hour ago</td>
                                    </tr>
                                    <tr>
                                        <td>Email Service</td>
                                        <td><span class="badge badge-success">Operational</span></td>
                                        <td>10 minutes ago</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Recent</strong> Registrations</h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled activity">
                            <li>
                                <div class="media">
                                    <div class="media-left">
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar1.jpg') }}" alt="User">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="m-t-0">Dr. Michael Wilson</h6>
                                        <p>Registered as a new doctor</p>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="media-left">
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar2.jpg') }}" alt="User">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="m-t-0">Sarah Thompson</h6>
                                        <p>Registered as a new patient</p>
                                        <small class="text-muted">5 hours ago</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="media-left">
                                        <div class="avatar">
                                            <img src="{{ asset('template/images/xs/avatar3.jpg') }}" alt="User">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="m-t-0">James Miller</h6>
                                        <p>Registered as a new donor</p>
                                        <small class="text-muted">1 day ago</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script src="{{ asset('template/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('template/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('template/bundles/morrisscripts.bundle.js') }}"></script>
<script src="{{ asset('template/bundles/jvectormap.bundle.js') }}"></script>
<script src="{{ asset('template/bundles/knob.bundle.js') }}"></script>
<script src="{{ asset('template/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('template/js/pages/index.js') }}"></script>
</body>
</html>