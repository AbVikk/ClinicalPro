<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>        
    </div>
</div>
<div class="overlay"></div>
<nav class="navbar p-l-5 p-r-5">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ url('/admin/index') }}"><img src="{{ asset('assets/images/logo.svg') }}" width="30" alt="Clinical Pro"><span class="m-l-10">Clinical Pro</span></a>
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
                                    <img class="media-object" src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('assets/images/xs/avatar3.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="">
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
                                    <img class="media-object" src="{{ asset('assets/images/xs/avatar6.jpg') }}" alt="">
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
                                            <img src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar3.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="Avatar">
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
                                            <img src="{{ asset('assets/images/xs/avatar10.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar9.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar8.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar7.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar6.jpg') }}" alt="Avatar">
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
                                            <img src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar7.jpg') }}" alt="Avatar">
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
        <li class="float-right">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" class="mega-menu" data-close="true" onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) { document.getElementById('logout-form').submit(); }"><i class="zmdi zmdi-power"></i></a>
            <a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a>
        </li>
    </ul>
</nav>
<aside id="leftsidebar" class="sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#dashboard"><i class="zmdi zmdi-home m-r-5"></i>Clinical</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user">Admin</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info">
                            <div class="image"><a href="{{ url('/admin/profile') }}">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/'. auth()->user()->photo) }}" alt="User">
                                @else
                                    <img src="{{ asset('assets/images/profile_av.jpg') }}" alt="User">
                                @endif
                            </a></div>
                            <div class="detail">
                                <h4>{{ auth()->user()->name }}</h4>
                                <small>Admin</small>                        
                            </div>
                        </div>
                    </li>
                    <li class="header">MAIN</li>
                    <li><a href="{{ url('/admin/index') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>            
                    <li><a href="{{ url('/admin/book-appointment') }}"><i class="zmdi zmdi-calendar-check"></i><span>Appointment</span> </a></li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-add"></i><span>Doctors</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.doctor.dashboard') }}">Doctors Dashboard</a></li>
                            <li><a href="{{ route('admin.doctor.index') }}">All Doctors</a></li>
                            <li><a href="{{ route('admin.doctor.add') }}">Add Doctor</a></li>                       
                            {{-- <li><a href="{{ route('admin.doctor.profile') }}">Doctor Profile</a></li> --}}
                            {{-- <li><a href="{{ route('admin.doctors.availability') }}">Doctor Availability</a></li> --}}
                            {{-- <li><a href="{{ route('admin.doctor.dashboard') }}">Doctor Dashboard</a></li> --}}
                            <li><a href="{{ route('admin.doctor.schedule') }}">Schedule</a></li>
                            <li><a href="{{ route('admin.doctor.specialization.index') }}">Specializations</a></li>
                            <li><a href="{{ route('admin.doctor.specialization.add_categories') }}">Add Category</a></li>
                            <li><a href="{{ route('admin.doctor.specialization.add_department') }}">Add Department</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-accounts"></i><span>Nurses</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.clinic-staff.index') }}">All Nurses</a></li>
                            <li><a href="{{ route('admin.clinic-staff.add') }}">Add Nurse</a></li>
                        </ul>
                    </li>
                                      
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-email"></i><span>Invitations</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.invitations.index') }}"><i class=""></i><span>All Invitations</span> </a></li>
                            <li><a href="{{ route('admin.invitations.create') }}">Create Invitation</a></li>
                        </ul>
                    </li>  
                    
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-o"></i><span>Patients</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/patients') }}">All Patients</a></li>
                            <li><a href="{{ url('/admin/add-patient') }}">Add Patient</a></li>                       
                        </ul>
                    </li>
                    <li class="header">FINANCE</li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-balance-wallet"></i><span>Payments</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/payments') }}">Payments</a></li> 
                            <li><a href="{{ url('/admin/payments/create') }}">Add Payment</a></li>
                            <li><a href="{{ url('/admin/invoice') }}">Invoice</a></li>
                            <li><a href="{{ route('admin.payment.topup') }}">Wallet Top-Up</a></li>
                            <li><a href="{{ route('admin.wallet.test-webhook') }}">Test Webhook</a></li>
                        </ul>
                    </li>
                    <li class="header">PHARMACY</li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-hospital"></i><span>Pharmacy</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.pharmacy.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('admin.pharmacy.categories.index') }}">Categories</a></li>
                            <li><a href="{{ route('admin.pharmacy.mg.index') }}">MG Values</a></li>
                            <li><a href="{{ route('admin.pharmacy.drugs.create.form') }}">Add Drug</a></li>
                            <li><a href="{{ route('admin.pharmacy.stock.receive') }}">Receive Stock</a></li>
                            <li><a href="{{ route('admin.clinic.request-stock') }}">Request Stock</a></li>
                            <li><a href="{{ route('admin.clinic.sell') }}">Process Sale</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-accounts"></i><span>Pharmacists</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.pharmacists.index') }}">All Pharmacists</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-collection-item"></i><span>Prescriptions</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.prescriptions.index') }}">All Prescriptions</a></li>
                            <li><a href="{{ route('admin.prescriptions.create') }}">Create Prescription</a></li>
                            <li><a href="{{ route('admin.prescriptions.templates') }}">Medicine Templates</a></li>
                        </ul>
                    </li>
                    <li class="header">MANAGEMENT</li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-accounts"></i><span>Departments</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/doctor/specialization/departments/add') }}">Add</a></li>
                            <li><a href="{{ url('/admin/doctor/specialization/departments') }}">All Departments</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-accounts"></i><span>Department Heads</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.doctor.hods') }}">All Department Heads</a></li>
                            <li><a href="{{ route('admin.doctor.specialization.departments') }}">Assign HOD</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-accounts"></i><span>Matrons</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.matrons.index') }}">All Matrons</a></li>
                            <li><a href="{{ route('admin.matrons.create') }}">Add Matron</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-accounts"></i><span>Staff</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.clinic-staff.index') }}">All Staffs</a></li>
                            <li><a href="{{ route('admin.clinic-staff.add') }}">Add Staffs</a></li>
                            <li><a href="{{ route('admin.clinic-staff.roles-permissions') }}">Roles and Permission</a></li>
                            <li><a href="{{ route('admin.clinic-staff.attendance') }}">Attendance</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-hospital"></i><span>Services</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.services.index') }}">All Services</a></li>
                            <li><a href="{{ route('admin.services.create') }}">Add Service</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-lock"></i><span>Authentication</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('login') }}">Sign In</a> </li>
                            <li><a href="{{ route('register.initial') }}">Sign Up</a> </li>
                            <li><a href="{{ route('password.request') }}">Forgot Password</a> </li>
                            <li><a href="{{ url('/admin/404') }}">Page 404</a> </li>
                            <li><a href="{{ url('/admin/500') }}">Page 500</a> </li>
                            <li><a href="{{ url('/admin/page-offline') }}">Page Offline</a> </li>
                            <li><a href="{{ url('/admin/locked') }}">Locked Screen</a> </li>
                        </ul>
                    </li>
                    <li class="header">EXTRA COMPONENTS</li>
                    <li class="active open"><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-blogger"></i><span>Blog</span></a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/blog-dashboard') }}">Blog Dashboard</a></li>
                            <li><a href="{{ url('/admin/blog-post') }}">New Post</a></li>
                            <li><a href="{{ url('/admin/blog-list') }}">Blog List</a></li>
                            <li><a href="{{ url('/admin/blog-grid') }}">Blog Grid</a></li>
                            <li class="active"><a href="{{ url('/admin/blog-details') }}">Blog Single</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-folder"></i><span>File Manager</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/file-dashboard') }}">All File</a></li>
                            <li><a href="{{ url('/admin/file-documents') }}">Documents</a></li>
                            <li><a href="{{ url('/admin/file-media') }}">Media</a></li>
                            <li><a href="{{ url('/admin/file-images') }}">Images</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-apps"></i><span>App</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/mail-inbox') }}">Inbox</a></li>
                            <li><a href="{{ url('/admin/chat') }}">Chat</a></li>                                                        
                            <li><a href="{{ url('/admin/contact') }}">Contact list</a></li>                            
                        </ul>
                    </li>                    
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-delicious"></i><span>Widgets</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/widgets-app') }}">Apps Widgetse</a></li>
                            <li><a href="{{ url('/admin/widgets-data') }}">Data Widgetse</a></li>
                        </ul>
                    </li>                    
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-copy"></i><span>Sample Pages</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/blank') }}">Blank Page</a> </li>
                            <li><a href="../rtl/index.blade.php">RTL Support</a></li>
                            <li><a href="{{ url('/admin/image-gallery') }}">Image Gallery</a> </li>
                            <li><a href="{{ url('/admin/profile') }}">Profile</a></li>
                            <li><a href="{{ url('/admin/timeline') }}">Timeline</a></li>                            
                            <li><a href="{{ url('/admin/invoice') }}">Invoices</a></li>
                            <li><a href="{{ url('/admin/search-results') }}">Search Results</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-swap-alt"></i><span>User Interface (UI)</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ url('/admin/ui-kit') }}">UI KIT</a></li>
                            <li><a href="{{ url('/admin/alerts') }}">Alerts</a></li>
                            <li><a href="{{ url('/admin/collapse') }}">Collapse</a></li>
                            <li><a href="{{ url('/admin/colors') }}">Colors</a></li>
                            <li><a href="{{ url('/admin/dialogs') }}">Dialogs</a></li>
                            <li><a href="{{ url('/admin/icons') }}">Icons</a></li>
                            <li><a href="{{ url('/admin/list-group') }}">List Group</a></li>
                            <li><a href="{{ url('/admin/media-object') }}">Media Object</a></li>
                            <li><a href="{{ url('/admin/modals') }}">Modals</a></li>
                            <li><a href="{{ url('/admin/notifications') }}">Notifications</a></li>                    
                            <li><a href="{{ url('/admin/progressbars') }}">Progress Bars</a></li>
                            <li><a href="{{ url('/admin/range-sliders') }}">Range Sliders</a></li>
                            <li><a href="{{ url('/admin/sortable-nestable') }}">Sortable & Nestable</a></li>
                            <li><a href="{{ url('/admin/tabs') }}">Tabs</a></li>
                            <li><a href="{{ url('/admin/waves') }}">Waves</a></li>
                        </ul>
                    </li>
                    <li class="header">Extra</li>
                    <li>
                        <div class="progress-container progress-primary m-t-10">
                            <span class="progress-badge">Traffic this Month</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" style="width: 67%;">
                                    <span class="progress-value">67%</span>
                                </div>
                            </div>
                        </div>
                        <div class="progress-container progress-info">
                            <span class="progress-badge">Server Load</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                    <span class="progress-value">86%</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane stretchLeft" id="user">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info m-b-20 p-b-15">
                            <div class="image"><a href="{{ url('/admin/profile') }}">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/'. auth()->user()->photo) }}" alt="User">
                                @else
                                    <img src="{{ asset('assets/images/profile_av.jpg') }}" alt="User">
                                @endif
                            </a></div>
                            <div class="detail">
                                <h4>{{ auth()->user()->name }}</h4>
                                <small>Admin</small>                        
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a>
                                    <a title="twitter" href="#"><i class="zmdi zmdi-twitter"></i></a>
                                    <a title="instagram" href="#"><i class="zmdi zmdi-instagram"></i></a>
                                </div>
                                <div class="col-4 p-r-0">
                                    <h5 class="m-b-5">18</h5>
                                    <small>Exp</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="m-b-5">125</h5>
                                    <small>Awards</small>
                                </div>
                                <div class="col-4 p-l-0">
                                    <h5 class="m-b-5">148</h5>
                                    <small>Clients</small>
                                </div>                                
                            </div>
                        </div>
                    </li>
                    <li>
                        <small class="text-muted">Location: </small>
                        <p>{{ auth()->user()->address }}</p>
                        <hr>
                        <small class="text-muted">Email address: </small>
                        <p>{{ auth()->user()->email }}</p>
                        <hr>
                        <small class="text-muted">Phone: </small>
                        <p>{{ auth()->user()->phone }}</p>
                        <hr>
                        <small class="text-muted">Website: </small>
                        <p>http://dr.charlotte.com/ </p>
                        <hr>
                        <ul class="list-unstyled">
                            <li>
                                <div>Colorectal Surgery</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-blue " role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="width: 89%"> <span class="sr-only">62% Complete</span> </div>
                                </div>
                            </li>
                            <li>
                                <div>Endocrinology</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-green " role="progressbar" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100" style="width: 56%"> <span class="sr-only">87% Complete</span> </div>
                                </div>
                            </li>
                            <li>
                                <div>Dermatology</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-amber" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100" style="width: 78%"> <span class="sr-only">32% Complete</span> </div>
                                </div>
                            </li>
                            <li>
                                <div>Neurophysiology</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-blush" role="progressbar" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100" style="width: 43%"> <span class="sr-only">56% Complete</span> </div>
                                </div>
                            </li>
                        </ul>                        
                    </li>                    
                </ul>
            </div>
        </div>
    </div>    
</aside>
<aside id="rightsidebar" class="right-sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#setting"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chat"><i class="zmdi zmdi-comments"></i></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#activity">Activity</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane slideRight active" id="setting">
            <div class="slim_scroll">
                <div class="card">
                    <h6>General Settings</h6>
                    <ul class="setting-list list-unstyled">
                        <li>
                            <div class="checkbox">
                                <input id="checkbox1" type="checkbox">
                                <label for="checkbox1">Report Panel Usage</label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox2" type="checkbox" checked="">
                                <label for="checkbox2">Email Redirect</label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox3" type="checkbox" checked="">
                                <label for="checkbox3">Notifications</label>
                            </div>                        
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox4" type="checkbox" checked="">
                                <label for="checkbox4">Auto Updates</label>
                            </div>
                        </li>
                    </ul>
                </div>                
                <div class="card">
                    <h6>Skins</h6>
                    <ul class="choose-skin list-unstyled">
                        <li data-theme="purple">
                            <div class="purple"></div>
                        </li>                   
                        <li data-theme="blue">
                            <div class="blue"></div>
                        </li>
                        <li data-theme="cyan" class="active">
                            <div class="cyan"></div>                    
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                        </li>
                        <li data-theme="blush">
                            <div classs="blush"></div>                    
                        </li>
                    </ul>                    
                </div>
                <div class="card">
                    <h6>Account Settings</h6>
                    <ul class="setting-list list-unstyled">
                        <li>
                            <div class="checkbox">
                                <input id="checkbox5" type="checkbox" checked="">
                                <label for="checkbox5">Offline</label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox6" type="checkbox" checked="">
                                <label for="checkbox6">Location Permission</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card theme-light-dark">
                    <h6>Left Menu</h6>
                    <button class="t-light btn btn-default btn-simple btn-round btn-block">Light</button>
                    <button class="t-dark btn btn-default btn-round btn-block">Dark</button>
					<button class="m_img_btn btn btn-primary btn-round btn-block">Sidebar Image</button>                    
                </div>                
                <div class="card">
                    <h6>Information Summary</h6>
                    <div class="row m-b-20">
                        <div class="col-7">                            
                            <small class="displayblock">MEMORY USAGE</small>
                            <h5 class="m-b-0 h6">512</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#00ced1">8,7,9,5,6,4,6,8</div>
                        </div>
                    </div>
                    <div class="row m-b-20">
                        <div class="col-7">                            
                            <small class="displayblock">CPU USAGE</small>
                            <h5 class="m-b-0 h6">90%</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#F15F79">6,5,8,2,6,4,6,4</div>
                        </div>
                    </div>
                    <div class="row m-b-20">
                        <div class="col-7">                            
                            <small class="displayblock">DAILY TRAFFIC</small>
                            <h5 class="m-b-0 h6">25 142</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#78b83e">7,5,8,7,4,2,6,5</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">                            
                            <small class="displayblock">DISK USAGE</small>
                            <h5 class="m-b-0 h6">60.10%</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#457fca">7,5,2,5,6,7,6,4</div>
                        </div>
                    </div>
                </div>
            </div>                
        </div>       
        <div class="tab-pane right_chat stretchLeft" id="chat">
            <div class="slim_scroll">
                <div class="card">
                    <div class="search">                        
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <h6>Recent</h6>
                    <ul class="list-unstyled">
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Sophia</span>
                                        <span class="message">There are many variations of passages of Lorem Ipsum available</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>                            
                        </li>
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Grayson</span>
                                        <span class="message">All the Lorem Ipsum generators on the</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>                            
                        </li>
                        <li class="offline">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Isabella</span>
                                        <span class="message">Contrary to popular belief, Lorem Ipsum</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>                            
                        </li>
                        <li class="me">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">John</span>
                                        <span class="message">It is a long established fact that a reader</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>                            
                        </li>
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar3.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Alexander</span>
                                        <span class="message">Richard McClintock, a Latin professor</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>                            
                        </li>                        
                    </ul>
                </div>
                <div class="card">
                    <h6>Contacts</h6>
                    <ul class="list-unstyled">
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar10.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar6.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar7.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar8.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar9.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="online inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar3.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="online inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-pane slideLeft" id="activity">
            <div class="slim_scroll">
                <div class="card user_activity">
                    <h6>Recent Activity</h6>
                    <div class="streamline b-accent">
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">Admin Birthday</h5>
                                <small>Jan 21 <a href="javascript:void(0);" class="text-info">Sophia</a>.</small>
                            </div>
                        </div>
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">Add New Contact</h5>
                                <small>30min ago <a href="javascript:void(0);">Alexander</a>.</small>
                                <small><strong>P:</strong> +264-625-2323</small>
                                <small><strong>E:</strong> maryamamiri@gmail.com</small>
                            </div>
                        </div>
                        <div classs="sl-item">
                            <img class="user rounded-circle" src="{{ asset('assets/images/xs/avatar6.jpg') }}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">Code Change</h5>
                                <small>Today <a href="javascript:void(0);">Grayson</a>.</small>
                                <small>The standard chunk of Lorem Ipsum used since the 1500s is reproduced</small>
                            </div>
                        </div>
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{ asset('assets/images/xs/avatar7.jpg') }}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">New Email</h5>
                                <small>45min ago <a href="javascript:void(0);" class="text-info">Fidel Tonn</a>.</small>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="card">
                    <h6>Recent Attachments</h6>
                    <ul class="list-unstyled activity">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-collection-pdf l-blush"></i>                    
                                <div class="info">
                                    <h4>info_258.pdf</h4>                    
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
    </div>
</aside>
<style>
    /* 1. Ensure the AI button floats and is clearly visible */
    #ai-assistant-launcher {
        position: fixed;
        bottom: 20px;
        right: 20px; 
        z-index: 1000; 
        cursor: pointer;
        padding: 10px 15px;
        background: #9c27b0; /* Distinct color (Purple) for the AI */
        color: white;
        border-radius: 50px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        font-weight: bold;
        transition: transform 0.3s;
    }
    #ai-assistant-launcher:hover {
        transform: scale(1.05);
    }
    
    /* 2. Chat Window Container */
    #ai-chat-window {
        position: fixed;
        bottom: 75px;
        right: 20px;
        width: 350px;
        height: 450px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 999;
        display: none; /* Starts hidden */
        flex-direction: column;
        overflow: hidden;
    }
    
    /* 3. Chat Messages Styling */
    #ai-chat-body {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
        background-color: #f8f9fa; 
        /* Use list-unstyled for consistency with your theme */
    }
    .ai-chat-message-list { 
        list-style: none; 
        padding: 0; 
        margin: 0;
    }
    .chat-bubble-ai { 
        background-color: #e9ecef; 
        padding: 8px; 
        border-radius: 15px 15px 15px 0; 
        display: inline-block;
        white-space: pre-wrap; /* Allows formatting from AI response */
    }
    .chat-bubble-user { 
        background-color: #d1e7ff; 
        padding: 8px; 
        border-radius: 15px 15px 0 15px; 
        display: inline-block;
    }
    .chat-row-user { text-align: right; margin-bottom: 10px; }
    .chat-row-ai { text-align: left; margin-bottom: 10px; }

    /* 4. Input Area Styling */
    #ai-chat-input-area {
        display: flex;
        padding: 10px;
        border-top: 1px solid #ddd;
        align-items: flex-end;
    }
    #ai-chat-input {
        flex-grow: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 5px;
        font-family: inherit;
        resize: none; 
        overflow-y: auto; 
        max-height: 100px; 
        box-sizing: border-box;
        line-height: 1.5;
    }
    #ai-chat-send-btn-icon {
        color: white;
        background: #28a745; /* Green */
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        line-height: 1;
    }
    #ai-typing-indicator .chat-bubble-ai {
        display: inline-flex; /* Use flex to align dots */
        align-items: center;
        padding: 10px 12px;
    }
    .typing-dot {
        height: 8px;
        width: 8px;
        background-color: #868e96; /* Muted color */
        border-radius: 50%;
        margin: 0 2px;
        animation: typing-bounce 1.4s infinite both;
    }
    /* Stagger the animation */
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing-bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1.0); }
    }
</style>
<!-- Make sure CSRF token is available -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="ai-assistant-launcher">
    🤖
</div>

<div id="ai-chat-window">
    <div class="card m-b-0">
        <div class="header p-t-15 p-b-15">
            <h5 class="m-b-0">🤖 AI Scheduler Assistant</h5>
        </div>
        <div class="body p-0">
            <div id="ai-chat-body" class="chat-widget">
                <ul class="ai-chat-message-list">
                    <li class="chat-row-ai">
                        <div class="chat-info">
                            <span class="chat-bubble-ai">Hello! I'm your ClinicalPro AI Assistant. Ask me to find an available doctor for a specific specialty, date, and time!</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div id="ai-chat-input-area">
                <label for="ai-file-input" class="input-group-addon" id="ai-attach-btn">
                    <i class="zmdi zmdi-attachment-alt" style="cursor: pointer;"></i>
                </label>
                
                <input type="file" id="ai-file-input" accept="image/jpeg,image/png,image/webp" style="display: none;">

                <textarea id="ai-chat-input" 
                          placeholder="Add a message or upload an image..." 
                          rows="1"></textarea>
                
                <span id="ai-chat-send-btn" class="input-group-addon">
                    <i class="zmdi zmdi-mail-send" id="ai-chat-send-btn-icon"></i>
                </span>
            </div>

            <div id="ai-file-preview-area" style="display: none; padding: 5px 10px; background: #f0f0f0;">
                <small>Attached: <span id="ai-file-name"></span> 
                    <button id="ai-remove-file" style="border:none; background:none; color:red; cursor:pointer;">&times;</button>
                </small>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
 <script>
    $(document).ready(function() {
        // --- ALL OUR JQUERY SELECTORS ---
        const chatWindow = $('#ai-chat-window');
        const chatBodyList = $('#ai-chat-body ul'); 
        const chatInput = $('#ai-chat-input');
        const sendBtn = $('#ai-chat-send-btn');
        const sendIcon = $('#ai-chat-send-btn-icon');
        const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val(); 
        
        const fileInput = $('#ai-file-input');
        const attachBtn = $('#ai-attach-btn');
        const filePreviewArea = $('#ai-file-preview-area');
        const fileNameDisplay = $('#ai-file-name');
        const removeFileBtn = $('#ai-remove-file');
        const launcherBtn = $('#ai-assistant-launcher'); // The launcher button
        
        // --- STATE VARIABLES ---
        let currentAiRequest = null;
        let selectedFile = null;

        const typingIndicatorHtml = `
            <li class="chat-row-ai" id="ai-typing-indicator">
                <div class="chat-info">
                    <span class="chat-bubble-ai">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </span>
                </div>
            </li>
        `;

        // --- HELPER FUNCTIONS ---

        function scrollToBottom() {
            chatBodyList.parent().stop().animate({
                scrollTop: chatBodyList.parent()[0].scrollHeight
            }, 500);
        }

        function addUserMessage(text) {
            const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            const messageHtml = `
                <li class="chat-row-user">
                    <div class="chat-info">
                        <span class="datetime">${time}</span>
                        <span class="message chat-bubble-user">${text.replace(/\n/g, '<br>')}</span>
                    </div>
                </li>
            `;
            chatBodyList.append(messageHtml);
            scrollToBottom();
        }

        function addAiMessage(text) {
            const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            const messageHtml = `
                <li class="chat-row-ai">
                    <div class="chat-info">
                        <span class="datetime">${time}</span>
                        <span class="message chat-bubble-ai">${text.replace(/\n/g, '<br>')}</span>
                    </div>
                </li>
            `;
            chatBodyList.append(messageHtml);
            scrollToBottom();
        }

        function resetChatForm() {
            sendIcon.removeClass('zmdi-stop').addClass('zmdi-mail-send');
            sendBtn.prop('disabled', false);
            chatInput.prop('disabled', false);
            currentAiRequest = null;
            chatInput.focus();
        }

        function startTypewriter(text) {
            const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            const messageHtml = `
                <li class="chat-row-ai">
                    <div class="chat-info">
                        <span class="datetime">${time}</span>
                        <span class="message chat-bubble-ai"></span>
                    </div>
                </li>
            `;
            chatBodyList.append(messageHtml);

            const targetElement = chatBodyList.find('li').last().find('.message');
            let index = 0;
            let speed = 30;

            function type() {
                if (index < text.length) {
                    let char = text[index];
                    if (char === '\n') {
                        targetElement.append('<br>');
                    } else {
                        targetElement.append(char);
                    }
                    index++;
                    scrollToBottom();
                    setTimeout(type, speed);
                } else {
                    resetChatForm();
                }
            }
            type();
        }

        function removeSelectedFile() {
            selectedFile = null;
            fileInput.val(null);
            filePreviewArea.hide();
        }

        // --- MAIN SEND FUNCTION ---

        function sendMessage() {
            const userQuery = chatInput.val().trim();
            
            if (userQuery === '' && !selectedFile) {
                return;
            }

            const formData = new FormData();
            formData.append('query', userQuery);
            
            if (selectedFile) {
                formData.append('image_upload', selectedFile);
                addUserMessage(userQuery + ` (File: ${selectedFile.name})`);
            } else {
                addUserMessage(userQuery);
            }

            chatInput.val('');
            chatInput.css('height', 'auto');
            chatInput.css('overflow-y', 'hidden');
            removeSelectedFile(); 
            
            chatBodyList.append(typingIndicatorHtml);
            scrollToBottom();

            sendBtn.prop('disabled', false); 
            chatInput.prop('disabled', true);
            sendIcon.removeClass('zmdi-mail-send').addClass('zmdi-stop');
            
            currentAiRequest = $.ajax({
                url: '{{ route('api.ai.scheduling') }}', 
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: 'json',
                timeout: 60000,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#ai-typing-indicator').remove();
                    if (response && response.response) {
                        startTypewriter(response.response);
                    } else {
                        addAiMessage("❌ Error: Received empty response.");
                        resetChatForm();
                    }
                },
                error: function(xhr, status, error) {
                    $('#ai-typing-indicator').remove();
                    
                    if (status === 'abort') {
                        addAiMessage("Request cancelled.");
                    } else {
                        let errorMessage = "❌ Error: I couldn't connect. Please check logs.";
                        if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.error) {
                             errorMessage = "❌ Error: " + xhr.responseJSON.error;
                        } else if (xhr.status === 500) {
                             errorMessage = "❌ Error: Server error. Please try again later.";
                        } else if (status === 'timeout') {
                             errorMessage = "❌ Error: Request timed out.";
                        }
                        addAiMessage(errorMessage);
                    }
                    resetChatForm();
                },
                complete: function(xhr, status) {
                    currentAiRequest = null;
                    // Form reset is now handled by startTypewriter() or error()
                }
            });
        }

        // --- ALL EVENT LISTENERS ---

        // ************* THIS WAS THE MISSING PIECE *************
        launcherBtn.on('click', function() {
            chatWindow.toggle();
            if (chatWindow.is(':visible')) {
                scrollToBottom();
                chatInput.focus();
            }
        });
        // ******************************************************

        // Send Button (Send or Stop)
        sendBtn.on('click', function(e) {
            e.preventDefault();
            if (currentAiRequest) {
                currentAiRequest.abort();
            } else {
                sendMessage();
            }
        });

        // Enter Key
        chatInput.on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                if (!currentAiRequest) {
                    sendMessage();
                }
            }
        });
        
        // Auto-grow Textarea
        chatInput.on('input', function() {
            this.style.height = 'auto';
            let newHeight = this.scrollHeight;
            let maxHeight = 100;
            
            if (newHeight > maxHeight) {
                this.style.height = maxHeight + 'px';
                this.style.overflowY = 'auto';
            } else {
                this.style.height = newHeight + 'px';
                this.style.overflowY = 'hidden';
            }
        });
        
        // File Input Change
        fileInput.on('change', function() {
            if (this.files && this.files[0]) {
                selectedFile = this.files[0];
                
                if (selectedFile.size > 5 * 1024 * 1024) { // Max 5MB
                    addAiMessage("❌ Error: File is too large (max 5MB).");
                    removeSelectedFile();
                    return;
                }
                
                fileNameDisplay.text(selectedFile.name);
                filePreviewArea.show();
            }
        });
        
        // Remove File Button
        removeFileBtn.on('click', function() {
            removeSelectedFile();
        });
        
        // --- INITIAL RUN ---
        scrollToBottom();
    });
</script>
@endpush