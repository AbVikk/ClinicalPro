<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Add Departments</title>
<link rel="icon" href="{{ asset('template/html/light/favicon.ico') }}" type="image/x-icon">
<!-- Favicon-->
<link rel="stylesheet" href="{{ asset('template/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"/>
<link rel="stylesheet" href="{{ asset('template/assets/plugins/dropzone/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('template/assets/plugins/bootstrap-select/css/bootstrap-select.css') }}"/>
<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('template/html/light/assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('template/html/light/assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('template/assets/images/logo.svg') }}" width="48" height="48" alt="Oreo"></div>
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
                <a class="navbar-brand" href="{{ url('/admin') }}"><img src="{{ asset('template/assets/images/logo.svg') }}" width="30" alt="Oreo"><span class="m-l-10">Oreo</span></a>
            </div>
        </li>
        <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap"></i></a></li>
        <li class="d-none d-lg-inline-block"><a href="{{ url('/admin/events') }}" title="Events"><i class="zmdi zmdi-calendar"></i></a></li>
        <li class="d-none d-lg-inline-block"><a href="{{ url('/admin/mail-inbox') }}" title="Inbox"><i class="zmdi zmdi-email"></i></a></li>
        <li><a href="{{ url('/admin/contact') }}" title="Contact List"><i class="zmdi zmdi-account-box-phone"></i></a></li>
        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-notifications"></i>
            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="body">
                    <ul class="menu list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('template/assets/images/xs/avatar2.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('template/assets/images/xs/avatar3.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('template/assets/images/xs/avatar4.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('template/assets/images/xs/avatar5.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('template/assets/images/xs/avatar6.jpg') }}" alt="">
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
                                            <img src="{{ asset('template/assets/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar3.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar4.jpg') }}" alt="Avatar">
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
                                            <img src="{{ asset('template/assets/images/xs/avatar10.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar9.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar8.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar7.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar6.jpg') }}" alt="Avatar">
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
                                            <img src="{{ asset('template/assets/images/xs/avatar5.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('template/assets/images/xs/avatar7.jpg') }}" alt="Avatar">
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
                    <li><a href="{{ url('/admin/profile') }}"><i class="zmdi zmdi-account"></i>Profile</a></li>
                    <li><a href="{{ url('/admin/settings') }}"><i class="zmdi zmdi-settings"></i>Settings</a></li>
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
                    <div class="image"><img src="{{ asset('template/assets/images/profile_av.jpg') }}" alt="User"></div>
                    <div class="detail">
                        <h4>Michael</h4>
                        <small>Administrator</small>                        
                    </div>
                    <a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a>
                    <a title="twitter" href="#"><i class="zmdi zmdi-twitter"></i></a>
                    <a title="instagram" href="#"><i class="zmdi zmdi-instagram"></i></a>
                </div>
            </li>
            <li> <a href="{{ url('/admin') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
            <li class="header">HOSPITAL</li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-calendar-check"></i><span>Appointments</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/appointments') }}">All Appointments</a></li>
                    <li><a href="{{ url('/admin/appointments/add') }}">Add Appointment</a></li>
                    <li><a href="{{ url('/admin/appointments/edit') }}">Edit Appointment</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-add"></i><span>Doctors</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/doctors') }}">All Doctors</a></li>
                    <li><a href="{{ url('/admin/doctors/add') }}">Add Doctor</a></li>
                    <li><a href="{{ url('/admin/doctors/edit') }}">Edit Doctor</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-o"></i><span>Patients</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/patients') }}">All Patients</a></li>
                    <li><a href="{{ url('/admin/patients/add') }}">Add Patient</a></li>
                    <li><a href="{{ url('/admin/patients/edit') }}">Edit Patient</a></li>
                </ul>
            </li>
            <li class="active open"> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-bug"></i><span>Departments</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/departments') }}">All Departments</a></li>
                    <li><a href="{{ url('/admin/departments/add') }}">Add Department</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-balance-wallet"></i><span>Payments</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/payments') }}">All Payments</a></li>
                    <li><a href="{{ url('/admin/payments/add') }}">Add Payment</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-case"></i><span>Staff</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/staff') }}">All Staff</a></li>
                    <li><a href="{{ url('/admin/staff/add') }}">Add Staff</a></li>
                    <li><a href="{{ url('/admin/staff/edit') }}">Edit Staff</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-file-text"></i><span>Reports</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/reports/annual') }}">Annual Reports</a></li>
                    <li><a href="{{ url('/admin/reports/patient') }}">Patient Reports</a></li>
                    <li><a href="{{ url('/admin/reports/staff') }}">Staff Reports</a></li>
                    <li><a href="{{ url('/admin/reports/finance') }}">Finance Reports</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-settings"></i><span>Settings</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/settings/general') }}">General Settings</a></li>
                    <li><a href="{{ url('/admin/settings/profile') }}">Profile Settings</a></li>
                    <li><a href="{{ url('/admin/settings/seo') }}">SEO Settings</a></li>
                </ul>
            </li>
            <li class="header">EXTRA COMPONENTS</li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-apps"></i><span>App</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/app/inbox') }}">Inbox</a></li>
                    <li><a href="{{ url('/admin/app/chat') }}">Chat</a></li>
                    <li><a href="{{ url('/admin/app/calendar') }}">Calendar</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-file"></i><span>Pages</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/pages/blank') }}">Blank Page</a></li>
                    <li><a href="{{ url('/admin/pages/gallery') }}">Image Gallery</a></li>
                    <li><a href="{{ url('/admin/pages/profile') }}">Profile</a></li>
                    <li><a href="{{ url('/admin/pages/timeline') }}">Timeline</a></li>
                    <li><a href="{{ url('/admin/pages/invoice') }}">Invoice</a></li>
                    <li><a href="{{ url('/admin/pages/contact') }}">Contact List</a></li>
                </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-ui"></i><span>UI Elements</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ url('/admin/ui/bootstrap') }}">Bootstrap UI</a></li>
                    <li><a href="{{ url('/admin/ui/icons') }}">Icons</a></li>
                    <li><a href="{{ url('/admin/ui/widgets') }}">Widgets</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- #Menu -->
</aside>

<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#skins">Skins</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chat">Chat</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#settings">Settings</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane in active in active" id="skins">
            <div class="slim_scroll">
                <h6>Theme Option</h6>
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="control-label">Light Sidebar Menu</label>
                        <label class="switch">
                            <input type="checkbox" class="ls-toggle-btn" data-color="theme-cyan" data-switch="false">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="control-label">Light Top Bar</label>
                        <label class="switch">
                            <input type="checkbox" class="ls-toggle-btn" data-color="theme-cyan" data-switch="true">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="control-label">RTL Layout</label>
                        <label class="switch">
                            <input type="checkbox" class="ls-toggle-btn" data-color="theme-cyan" data-switch="false">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="control-label">Boxed Layout</label>
                        <label class="switch">
                            <input type="checkbox" class="ls-toggle-btn" data-color="theme-cyan" data-switch="false">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="control-label">Mini Sidebar</label>
                        <label class="switch">
                            <input type="checkbox" class="ls-toggle-btn" data-color="theme-cyan" data-switch="false">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="control-label">Right Sidebar</label>
                        <label class="switch">
                            <input type="checkbox" class="ls-toggle-btn" data-color="theme-cyan" data-switch="false">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <h6>Choose Skin</h6>
                <ul class="choose-skin list-unstyled">
                    <li data-theme="purple">
                        <div class="purple"></div>
                        <span>Purple</span>
                    </li>                   
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="cyan" class="active">
                        <div class="cyan"></div>
                        <span>Cyan</span>
                    </li>
                    <li data-theme="green">
                        <div class="green"></div>
                        <span>Green</span>
                    </li>
                    <li data-theme="orange">
                        <div class="orange"></div>
                        <span>Orange</span>
                    </li>
                    <li data-theme="blush">
                        <div class="blush"></div>
                        <span>Blush</span>
                    </li>
                </ul>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="chat">
            <div class="slim_scroll">
                <div class="card">
                    <h6>Recent</h6>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar1.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">John Smith</h6>
                                    <small class="text-muted">Online</small>
                                </div>
                            </div>
                            <span class="badge badge-success">Online</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar2.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">Elizabeth</h6>
                                    <small class="text-muted">Online</small>
                                </div>
                            </div>
                            <span class="badge badge-success">Online</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar3.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">Alexander</h6>
                                    <small class="text-muted">Offline</small>
                                </div>
                            </div>
                            <span class="badge badge-danger">Offline</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar4.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">Sophia</h6>
                                    <small class="text-muted">Online</small>
                                </div>
                            </div>
                            <span class="badge badge-success">Online</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar5.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">Isabella</h6>
                                    <small class="text-muted">Online</small>
                                </div>
                            </div>
                            <span class="badge badge-success">Online</span>
                        </li>
                    </ul>
                </div>
                <div class="card">
                    <h6>Groups</h6>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar-group1.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">Neurology</h6>
                                    <small class="text-muted">5 Members</small>
                                </div>
                            </div>
                            <span class="badge badge-info">5</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar-group2.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">Gynecology</h6>
                                    <small class="text-muted">8 Members</small>
                                </div>
                            </div>
                            <span class="badge badge-info">8</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('template/assets/images/xs/avatar-group3.jpg') }}" alt="Avatar" class="media-object">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">Cardiology</h6>
                                    <small class="text-muted">3 Members</small>
                                </div>
                            </div>
                            <span class="badge badge-info">3</span>
                        </li>
                    </ul>
                </div>
                <div class="card">
                    <h6>Files</h6>
                    <ul class="list-unstyled">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-file-text l-blue"></i>                    
                                <div class="info">
                                    <h4>Annual Report.doc</h4>                    
                                    <small>2MB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-collection-text l-amber"></i>                    
                                <div class="info">
                                    <h4>newdoc_214.doc</h4>                    
                                    <small>900KB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-image l-parpl"></i>                    
                                <div class="info">
                                    <h4>MG_4145.jpg</h4>                    
                                    <small>5.6MB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-image l-parpl"></i>                    
                                <div class="info">
                                    <h4>MG_4100.jpg</h4>                    
                                    <small>5MB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-collection-text l-amber"></i>                    
                                <div class="info">
                                    <h4>Reports_end.doc</h4>                    
                                    <small>780KB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-videocam l-turquoise"></i>                    
                                <div class="info">
                                    <h4>movie2018.MKV</h4>                    
                                    <small>750MB</small>
                                </div>
                            </a>
                        </li>                        
                    </ul>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="settings">
            <div class="slim_scroll">
                <div class="card">
                    <h6>General Settings</h6>
                    <ul class="list-unstyled">
                        <li>
                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" name="checkbox">
                                    <span>Report Panel Usage</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" name="checkbox">
                                    <span>Email Redirect</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" checked name="checkbox">
                                    <span>Notifications</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" name="checkbox">
                                    <span>Auto Updates</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" name="checkbox">
                                    <span>Offline</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" checked name="checkbox">
                                    <span>Location Permission</span>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>
<!-- Chat-launcher -->
<div class="chat-launcher"></div>
<div class="chat-wrapper">
    <div class="card">
        <div class="header">
            <ul class="list-unstyled team-info margin-0">
                <li class="m-r-15"><h2>Doctor Team</h2></li>
                <li>
                    <img src="{{ asset('template/assets/images/xs/avatar2.jpg') }}" alt="Avatar">
                </li>
                <li>
                    <img src="{{ asset('template/assets/images/xs/avatar3.jpg') }}" alt="Avatar">
                </li>
                <li>
                    <img src="{{ asset('template/assets/images/xs/avatar4.jpg') }}" alt="Avatar">
                </li>
                <li>
                    <img src="{{ asset('template/assets/images/xs/avatar6.jpg') }}" alt="Avatar">
                </li>
                <li>
                    <a href="javascript:void(0);" title="Add Member"><i class="zmdi zmdi-plus-circle"></i></a>
                </li>
            </ul>                       
        </div>
        <div class="body">
            <div class="chat-widget">
            <ul class="chat-scroll-list clearfix">
                <li class="left float-left">
                    <img src="{{ asset('template/assets/images/xs/avatar3.jpg') }}" class="rounded-circle" alt="">
                    <div class="chat-info">
                        <a class="name" href="#">Alexander</a>
                        <span class="datetime">6:12</span>                            
                        <span class="message">Hello, John </span>
                    </div>
                </li>
                <li class="right">
                    <div class="chat-info"><span class="datetime">6:15</span> <span class="message">Hi, Alexander<br> How are you!</span> </div>
                </li>
                <li class="right">
                    <div class="chat-info"><span class="datetime">6:16</span> <span class="message">There are many variations of passages of Lorem Ipsum available</span> </div>
                </li>
                <li class="left float-left"> <img src="{{ asset('template/assets/images/xs/avatar2.jpg') }}" class="rounded-circle" alt="">
                    <div class="chat-info"> <a class="name" href="#">Elizabeth</a> <span class="datetime">6:25</span> <span class="message">Hi, Alexander,<br> John <br> What are you doing?</span> </div>
                </li>
                <li class="left float-left"> <img src="{{ asset('template/assets/images/xs/avatar1.jpg') }}" class="rounded-circle" alt="">
                    <div class="chat-info"> <a class="name" href="#">Michael</a> <span class="datetime">6:28</span> <span class="message">I would love to join the team.</span> </div>
                </li>
                    <li class="right">
                    <div class="chat-info"><span class="datetime">7:02</span> <span class="message">Hello, <br>Michael</span> </div>
                </li>
            </ul>
            </div>
            <div class="input-group p-t-15">
                <input type="text" class="form-control" placeholder="Enter text here...">
                <span class="input-group-addon">
                    <i class="zmdi zmdi-mail-send"></i>
                </span>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Add Departments
                <small>Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Departments</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ul>
            </div>
        </div>
    </div>    
    <div class="container-fluid">        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Add</strong> Departments<small>Description text here...</small> </h2>
                        <ul class="header-dropdown">                            
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Departments Name">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea rows="4" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <form action="/" id="frmFileUpload" class="dropzone m-b-20" method="post" enctype="multipart/form-data">
                                    <div class="dz-message">
                                        <div class="drag-icon-cph"> <i class="material-icons">touch_app</i> </div>
                                        <h3>Drop files here or click to upload.</h3>
                                        <em>(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</em> </div>
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row clearfix">                            
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-round">Submit</button>
                                <button type="submit" class="btn btn-default btn-round btn-simple">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Jquery Core Js --> 
<script src="{{ asset('template/html/light/assets/bundles/libscripts.bundle.js') }}"></script> <!-- Bootstrap JS and jQuery v3.2.1 -->
<script src="{{ asset('template/html/light/assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- slimscroll, waves Scripts Plugin Js -->

<script src="{{ asset('template/assets/plugins/dropzone/dropzone.js') }}"></script> <!-- Dropzone Plugin Js -->
<script src="{{ asset('template/assets/plugins/momentjs/moment.js') }}"></script> <!-- Moment Plugin Js -->
<script src="{{ asset('template/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ asset('template/html/light/assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js -->
<script>
    $(function () {
    //Datetimepicker plugin
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY - HH:mm',
        clearButton: true,
        weekStart: 1
    });
});
</script>
</body>
</html>