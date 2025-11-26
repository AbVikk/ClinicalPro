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
                    <li><a href="{{ route('admin.checkin.index') }}"><i class="zmdi zmdi-account-add"></i><span>Patient Check-in</span> </a></li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-add"></i><span>Doctors</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.doctor.dashboard') }}">Doctors Dashboard</a></li>
                            <li><a href="{{ route('admin.doctor.index') }}">All Doctors</a></li>
                            <li><a href="{{ route('admin.doctor.add') }}">Add Doctor</a></li>                       
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
                            {{-- <li><a href="{{ route('admin.wallet.test-webhook') }}">Test Webhook</a></li> --}}
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
                    <div class="row m-b-2Gf">
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
            <div class="slim_scroll" id="ai-chat-container"> 
                
                <div class="chat-header">
                    <h6 class="m-b-0">🤖 AI Scheduler Assistant</h6>
                    <span id="ai-clear-chat-btn" title="Clear Chat History">
                        <i class="zmdi zmdi-delete"></i>
                    </span>
                </div>

                <div id="ai-chat-body">
                    <div class="chat-scroll-controls">
                        <span id="ai-scroll-top" title="Scroll to Top"><i class="zmdi zmdi-arrow-up"></i></span>
                        <span id="ai-scroll-bottom" title="Scroll to Bottom"><i class="zmdi zmdi-arrow-down"></i></span>
                    </div>
                    
                    <ul class="ai-chat-message-list">
                        </ul>
                </div>
                
                <div id="ai-file-preview-area" style="display: none;">
                    <small>Attached: <span id="ai-file-name"></span> 
                        <button id="ai-remove-file">&times;</button>
                    </small>
                </div>
                <div id="ai-chat-input-area">
                    <label for="ai-file-input" id="ai-attach-btn">
                        <i class="zmdi zmdi-attachment-alt"></i>
                    </label>
                    <input type="file" id="ai-file-input" accept="image/jpeg,image/png,image/webp" style="display: none;" multiple>
                    
                    <textarea id="ai-chat-input" 
                              placeholder="Ask me to find a doctor..." 
                              rows="1"></textarea>
                    
                    <span id="ai-chat-forward-btn" title="Fast-forward" style="display:none;">
                        <i class="zmdi zmdi-fast-forward" id="ai-chat-forward-btn-icon"></i>
                    </span>

                    <span id="ai-chat-send-btn" title="Send / Stop">
                        <i class="zmdi zmdi-mail-send" id="ai-chat-send-btn-icon"></i>
                    </span>
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
    /* === NEW: THIN SCROLLER === */
    #ai-chat-body {
        /* Firefox */
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 #f1f1f1;
    }
    #ai-chat-body::-webkit-scrollbar {
        width: 6px;
    }
    #ai-chat-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    #ai-chat-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    #ai-chat-body::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    /* === END OF NEW SCROLLER === */

    /* 1. Make the chat container use Flexbox and fill all vertical space */
    #ai-chat-container {
        display: flex;
        flex-direction: column;
        height: 100%; /* Fill the parent (slim_scroll) */
        overflow: hidden; /* Prevent double scrollbars */
        background: #ffffff; /* Clean white background */
    }

    /* 2. Pinned Header */
    .chat-header {
        flex-shrink: 0; /* Don't let it shrink */
        border-bottom: 1px solid #e0e0e0;
        background: #f9f9f9;
        padding: 15px;
        display: flex; /* <-- NEW: Use flexbox */
        justify-content: space-between; /* <-- NEW: Pushes title and icon apart */
        align-items: center; /* <-- NEW: Vertically center */
    }
    .chat-header h5 {
        font-weight: 600;
        color: #333;
        margin-bottom: 0; /* Remove default margin */
    }
    
    /* === NEW: "Clear Chat" Button Style === */
    #ai-clear-chat-btn {
        color: #f44336; /* Red color for delete */
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        display: inline-block;
        line-height: 1;
    }
    #ai-clear-chat-btn:hover {
        background-color: #fde0e0;
    }

    /* 3. "Growable" Message List */
    #ai-chat-body {
        flex-grow: 1; /* This is the magic: it fills all available space */
        overflow-y: auto; /* Adds a scrollbar ONLY to the messages */
        padding: 15px;
        background-color: #ffffff; 
        position: relative; /* <-- NEW: For positioning scroll buttons */
    }
    
    /* === NEW: Scroll Controls (Fixed Positioning) === */
    .chat-scroll-controls {
        position: absolute;
        bottom: 15px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 10;
    }
    #ai-scroll-top, #ai-scroll-bottom {
        width: 36px;
        height: 36px;
        background-color: rgba(0, 0, 0, 0.4);
        color: #fff;
        border-radius: 50%;
        display: none; /* <-- HIDDEN BY DEFAULT */
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    #ai-scroll-top:hover, #ai-scroll-bottom:hover {
        background-color: rgba(0, 0, 0, 0.6);
        opacity: 1;
    }
    #ai-scroll-top.visible, #ai-scroll-bottom.visible {
        display: flex; /* <-- SHOWN BY JAVASCRIPT */
    }
    /* === END: Scroll Controls === */
    
    .ai-chat-message-list { 
        list-style: none; 
        padding: 0; 
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px; /* Adds space between bubbles */
    }

    /* 4. Sleek Chat Bubbles */
    .chat-row-ai .chat-info {
        display: flex; 
        justify-content: flex-start;
    }
    .chat-row-user .chat-info {
        display: flex; 
        justify-content: flex-end;
    }

    /* AI responses: full width plain text */
    .chat-bubble-ai { 
        background-color: transparent; 
        color: #333; 
        padding: 10px 0; 
        border-radius: 0;
        max-width: 100%; 
        display: block; 
        text-align: left; 
        white-space: pre-wrap;
        line-height: 1.5;
        font-size: 14px;
    }
    
    /* === NEW: User Bubble (Full-Width from Right) === */
    .chat-bubble-user { 
        background-color: #007bff; 
        color: #ffffff;
        padding: 10px 15px;
        border-radius: 18px 18px 0 18px; 
        display: inline-block; /* This is correct, let the parent handle alignment */
        max-width: 80%; /* Good for readability */
        white-space: pre-wrap; 
        line-height: 1.5;
        font-size: 14px;
        text-align: left; 
    }
    /* === END OF UI FIX === */


    /* 5. Pinned Footer (Input Area) */
    #ai-chat-input-area {
        flex-shrink: 0; 
        display: flex;
        padding: 12px;
        border-top: 1px solid #e0e0e0;
        align-items: center; 
        background: #f9f9f9;
        gap: 10px; 
    }
    
    #ai-chat-input {
        flex-grow: 1;
        border: none;
        box-shadow: none;
        border-radius: 20px; 
        background: #e9ecef; 
        padding: 10px 15px;
        font-size: 14px;
        resize: none; 
        overflow-y: hidden; 
        max-height: 100px; 
        line-height: 1.5;
    }
    #ai-chat-input:focus {
        background: #fff;
        border: none;
        box-shadow: none;
        outline: none;
    }
    
    #ai-attach-btn, #ai-chat-send-btn, #ai-chat-forward-btn {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%; 
        cursor: pointer;
        color: #555;
        transition: background-color 0.2s;
    }
    #ai-attach-btn:hover {
        background-color: #e0e0e0;
    }
    #ai-chat-send-btn {
        background-color: #007bff; 
        color: #ffffff;
    }
    #ai-chat-send-btn:hover {
        background-color: #0056b3;
    }
    
    /* --- NEW: Style for the forward button --- */
    #ai-chat-forward-btn {
        background-color: #6c757d; /* Muted grey */
        color: #ffffff;
    }
    #ai-chat-forward-btn:hover {
        background-color: #5a6268;
    }
    
    /* File preview area */
    #ai-file-preview-area {
        flex-shrink: 0;
        padding: 8px 12px; 
        background: #e9ecef;
        font-size: 12px;
        display: none; 
        border-top: 1px solid #e0e0e0;
    }
    #ai-file-preview-area small {
        color: #333;
    }
    #ai-file-preview-area button {
        border:none; 
        background:none; 
        color:red; 
        cursor:pointer;
        font-weight: bold;
        margin-left: 5px;
    }

    /* 6. Typing Indicator */
    #ai-typing-indicator .chat-bubble-ai {
        display: inline-flex;
        align-items: center;
        padding: 10px 12px;
        background-color: #f1f1f1; 
        border-radius: 18px 18px 18px 0; 
    }
    .typing-dot {
        height: 8px;
        width: 8px;
        background-color: #868e96;
        border-radius: 50%;
        margin: 0 2px;
        animation: typing-bounce 1.4s infinite both;
    }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing-bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1.0); }
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

@push('page-scripts')
 <script>
    $(document).ready(function() {
        // --- ALL OUR JQUERY SELECTORS ---
        const chatBody = $('#ai-chat-body'); // The scrolling DIV
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
        
        const forwardBtn = $('#ai-chat-forward-btn');
        const clearChatBtn = $('#ai-clear-chat-btn');
        const scrollControls = $('.chat-scroll-controls');
        const scrollTopBtn = $('#ai-scroll-top');
        const scrollBottomBtn = $('#ai-scroll-bottom');

        // --- STATE VARIABLES ---
        let currentAiRequest = null;
        let selectedFiles = []; 
        let typewriterTimeout = null; 
        let fullAiResponse = ''; 
        let scrollTimer = null; 
        let isFastForward = false; 

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
        
        // === THIS IS THE ARCHITECTURAL FIX ===
        
        // 1. We get the user's role from Laravel
        const userRole = "{{ Auth::user()->role }}";

        // 2. We let Laravel build the *correct* URLs based on the user's role.
        // This creates the proper URL (e.g., /admin/ai/chat-history or /doctor/ai/chat-history)
        // by finding the *named routes* we created in Fix 2 and Fix 3.
        const AiRoutes = {
            history: '{{ route(Auth::user()->role . ".api.ai.chat-history") }}',
            send: '{{ route(Auth::user()->role . ".api.ai.scheduling") }}',
            clear: '{{ route(Auth::user()->role . ".api.ai.chat-history.clear") }}'
        };

        // --- HELPER FUNCTIONS ---

        function scrollToBottom() {
            chatBody.stop().animate({
                scrollTop: chatBody[0].scrollHeight
            }, 500);
        }

        function addUserMessage(text) {
            const safeText = text || '';
            const messageHtml = `
                <li class="chat-row-user">
                    <div class="chat-info">
                        <span class="message chat-bubble-user">${safeText.replace(/\n/g, '<br>')}</span>
                    </div>
                </li>
            `;
            chatBodyList.append(messageHtml);
            scrollToBottom();
        }

        function addAiMessage(text) {
            const safeText = text || '...';
            const messageHtml = `
                <li class="chat-row-ai">
                    <div class="chat-info">
                        <span class="message chat-bubble-ai">${safeText.replace(/\n/g, '<br>')}</span>
                    </div>
                </li>
            `;
            chatBodyList.append(messageHtml);
            scrollToBottom();
        }

        function resetChatForm() {
            if (typewriterTimeout) {
                clearTimeout(typewriterTimeout);
                typewriterTimeout = null;
            }
            fullAiResponse = ''; 
            
            sendIcon.removeClass('zmdi-stop').addClass('zmdi-mail-send');
            forwardBtn.hide(); 
            sendBtn.prop('disabled', false); 
            chatInput.prop('disabled', false); 
            currentAiRequest = null;
            chatInput.focus();
        }

        function startTypewriter(text) {
            fullAiResponse = text; 
            isFastForward = false; 

            const messageHtml = `<li class="chat-row-ai"><div class="chat-info"><span class="message chat-bubble-ai"></span></div></li>`;
            chatBodyList.append(messageHtml);

            scrollToBottom();

            const targetElement = chatBodyList.find('li').last().find('.message');
            let index = 0;
            let speed = 5; 

            function type() {
                if (index < text.length) {
                    if (isFastForward) {
                        targetElement.html(fullAiResponse.replace(/\n/g, '<br>')); 
                        index = text.length; 
                    } else {
                        let char = text[index];
                        if (char === '\n') {
                            targetElement.append('<br>');
                        } else {
                            targetElement.append(char);
                        }
                        index++;
                    }
                    
                    typewriterTimeout = setTimeout(type, speed);
                } else {
                    typewriterTimeout = null;
                    fullAiResponse = '';
                    resetChatForm(); 
                }
            }
            type();
        }

        function removeSelectedFile() {
            selectedFiles = []; 
            fileInput.val(null); 
            filePreviewArea.hide();
            fileNameDisplay.html(''); 
        }
        
        
        function loadChatHistory() {
            chatBodyList.html(''); 
            chatBodyList.append(typingIndicatorHtml); 
            
            $.ajax({
                url: AiRoutes.history, // <-- USES THE CORRECT, PRE-BUILT URL
                type: 'GET',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    $('#ai-typing-indicator').remove();
                    if (response && response.history && response.history.length > 0) {
                        response.history.forEach(turn => {
                            const content = turn.content || ''; 
                            if (turn.role === 'user') {
                                addUserMessage(content);
                            } else {
                                addAiMessage(content);
                            }
                        });
                    }
                    scrollToBottom();
                },
                error: function(xhr) {
                    $('#ai-typing-indicator').remove();
                    if (xhr.status == 403) {
                         addAiMessage("❌ Error: You are not authorized to use this chat.");
                    } else if (xhr.status == 404) {
                         addAiMessage("❌ Error: Could not load chat history. (Route not found: " + AiRoutes.history + ")");
                    } else {
                         addAiMessage("❌ Error: Could not load chat history. (Server error)");
                    }
                }
            });
        }

        function sendMessage() {
            const userQuery = chatInput.val().trim();
            
            if (userQuery === '' && selectedFiles.length === 0) {
                return; 
            }

            const formData = new FormData();
            formData.append('query', userQuery);
            
            let fileNames = [];
            if (selectedFiles.length > 0) {
                selectedFiles.forEach((file) => {
                    formData.append('image_uploads[]', file, file.name); 
                    fileNames.push(file.name);
                });
                addUserMessage(userQuery + ` (Files: ${fileNames.join(', ')})`);
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
            forwardBtn.show(); 
            isFastForward = false;
            
            currentAiRequest = $.ajax({
                url: AiRoutes.send, // <-- USES THE CORRECT, PRE-BUILT URL
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: 'json',
                timeout: 60000, 
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    currentAiRequest = null; 
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
                }
            });
        }

        // --- ALL EVENT LISTENERS ---

        sendBtn.on('click', function(e) {
            e.preventDefault();
            
            if (currentAiRequest) {
                currentAiRequest.abort();
            } else if (typewriterTimeout) {
                clearTimeout(typewriterTimeout);
                typewriterTimeout = null;
                resetChatForm();
                addAiMessage("... (Stopped)");
            } else {
                sendMessage();
            }
        });

        forwardBtn.on('click', function(e) {
            e.preventDefault();
            if (!typewriterTimeout) return; 
            isFastForward = true; 
            $(this).css('background', '#28a745'); 
        });

        chatInput.on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                if (!currentAiRequest && !typewriterTimeout) {
                    sendMessage();
                }
            }
        });
        
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
        
        fileInput.on('change', function() {
            if (this.files && this.files.length > 0) {
                Array.from(this.files).forEach(file => {
                    if (file.size > 5 * 1024 * 1024) { 
                        addAiMessage(`❌ Error: File ${file.name} is too large (max 5MB).`);
                    } else if (selectedFiles.length < 5) { 
                        selectedFiles.push(file);
                    }
                });

                if (selectedFiles.length > 0) {
                    let fileNamesHtml = selectedFiles.map(f => f.name).join('<br>');
                    fileNameDisplay.html(fileNamesHtml);
                    filePreviewArea.show();
                } else {
                    removeSelectedFile();
                }
            }
        });
        
        removeFileBtn.on('click', function() {
            removeSelectedFile();
        });
        
        clearChatBtn.on('click', function() {
            if (confirm('Are you sure you want to delete this chat history? This cannot be undone.')) {
                
                if (currentAiRequest) currentAiRequest.abort();
                if (typewriterTimeout) clearTimeout(typewriterTimeout);
                resetChatForm();
                
                chatBodyList.html('');
                addAiMessage("Chat history cleared."); 
                
                $.ajax({
                    url: AiRoutes.clear, // <-- USES THE CORRECT, PRE-BUILT URL
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                    success: function() {
                        loadChatHistory();
                    },
                    error: function(xhr) {
                        addAiMessage("❌ Error: Could not clear history on server. " + (xhr.responseJSON?.error || ''));
                    }
                });
            }
        });

        // --- Scroll Button Listeners ---
        chatBody.on('scroll', function() {
            clearTimeout(scrollTimer);
            const el = chatBody[0];
            const scrollTop = el.scrollTop;
            const scrollHeight = el.scrollHeight;
            const clientHeight = el.clientHeight;
            
            (scrollTop > 50) ? scrollTopBtn.addClass('visible') : scrollTopBtn.removeClass('visible');
            (scrollHeight - (scrollTop + clientHeight) > 50) ? scrollBottomBtn.addClass('visible') : scrollBottomBtn.removeClass('visible');

            scrollTimer = setTimeout(() => {
                scrollTopBtn.removeClass('visible');
                scrollBottomBtn.removeClass('visible');
            }, 1500);
        });

        scrollTopBtn.on('click', function() {
            chatBody.stop().animate({ scrollTop: 0 }, 500);
        });

        scrollBottomBtn.on('click', function() {
            scrollToBottom(); 
        });

        // --- Load chat history on page load ---
        loadChatHistory();
    });
</script>
@endpush