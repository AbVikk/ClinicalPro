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
                <a class="navbar-brand" href="{{ route('senior_pharmacist.dashboard') }}"><img src="{{ asset('assets/images/logo.svg') }}" width="30" alt="Clinical Pro"><span class="m-l-10">Clinical Pro</span></a>
            </div>
        </li>
        <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap"></i></a></li>
        <li class="d-none d-lg-inline-block"><a href="{{ route('senior_pharmacist.dashboard') }}" title="Events"><i class="zmdi zmdi-calendar"></i></a></li>
        <li class="d-none d-lg-inline-block"><a href="{{ route('senior_pharmacist.inventory.predictions') }}" title="Inventory Predictions"><i class="zmdi zmdi-chart"></i></a></li>
        <li><a href="{{ route('senior_pharmacist.inventory.predictions') }}" title="Inventory Predictions"><i class="zmdi zmdi-account-box-phone"></i></a></li>
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
                                        <span class="name">System <span class="time">30min ago</span></span>
                                        <span class="message">Low stock alert for Paracetamol</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('assets/images/xs/avatar3.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">AI System <span class="time">31min ago</span></span>
                                        <span class="message">New inventory predictions generated</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">System <span class="time">35min ago</span></span>
                                        <span class="message">Stock transfer request received</span>                                        
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
                <li class="header">Tasks</li>
                <li class="body">
                    <ul class="menu tasks list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <div class="progress-container progress-primary">
                                    <span class="progress-badge">Inventory Management</span>
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
                                    <span class="progress-badge">Stock Transfers</span>
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
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#dashboard"><i class="zmdi zmdi-home m-r-5"></i>Pharmacy</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user">Profile</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info">
                            <div class="image"><a href="{{ route('senior_pharmacist.dashboard') }}">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/'. auth()->user()->photo) }}" alt="User">
                                @else
                                    <img src="{{ asset('assets/images/profile_av.jpg') }}" alt="User">
                                @endif
                            </a></div>
                            <div class="detail">
                                <h4>{{ auth()->user()->name }}</h4>
                                <small>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>                        
                            </div>
                        </div>
                    </li>
                    <li class="header">MAIN</li>
                    <li><a href="{{ route('senior_pharmacist.dashboard') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>            
                    <li><a href="{{ route('senior_pharmacist.inventory.predictions') }}"><i class="zmdi zmdi-chart"></i><span>Inventory Predictions</span> </a></li>
                    
                    <li class="header">CLINIC MANAGEMENT</li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-hospital"></i><span>Clinic Inventory</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.clinic.request-stock') }}">Request Stock</a></li>
                            <li><a href="{{ route('admin.clinic.sell') }}">Process Sale</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('senior_pharmacist.dashboard') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>            
                    
                    <li>
                        <a href="{{ route('senior_pharmacist.sales.index') }}" target="_blank">
                            <i class="zmdi zmdi-shopping-cart"></i><span>POS Terminal</span>
                        </a>
                    </li>
                    
                    <li class="header">PRESCRIPTIONS</li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-collection-item"></i><span>Prescriptions</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('admin.prescriptions.index') }}">All Prescriptions</a></li>
                            <li><a href="{{ route('admin.prescriptions.create') }}">Create Prescription</a></li>
                        </ul>
                    </li>
                    <li class="header">INVITATIONS</li>
                    <li><a href="{{ route('admin.invitations.create') }}"><i class="zmdi zmdi-email"></i><span>Create Invite Link</span> </a></li>
                    
                    <li class="header">ACCOUNT</li>
                    <li><a href="{{ route('senior_pharmacist.dashboard') }}"><i class="zmdi zmdi-account"></i><span>My Profile</span> </a></li>
                    <li>
                        <form id="logout-form-tab" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) { document.getElementById('logout-form-tab').submit(); }">
                            <i class="zmdi zmdi-power"></i><span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane slideLeft" id="user">
            <div class="row p-l-15 p-r-15">
                <div class="col-12">
                    <h6>Profile</h6>
                    <div class="m-t-10">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Role</label>
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}" readonly>
                        </div>
                    </div>
                </div>
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
            </div>
        </div>
        <div class="tab-pane slideLeft" id="chat">
            <div class="slim_scroll">
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
                        <li class="offline">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Joselyn</span>
                                        <span class="message">It is a long established fact that a reader</span>
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
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Sarah</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>                            
                        </li>
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar7.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Lisa</span>
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
                                        <span class="name">Matthew</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>                            
                        </li>
                        <li class="offline">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{ asset('assets/images/xs/avatar10.jpg') }}" alt="">
                                    <div class="media-body">
                                        <span class="name">Emma</span>
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
                                <h5 class="m-b-0">Admin</h5>
                                <small>Stock alert for Paracetamol <span class="text-info">Updated</span> 1 day ago</small>
                            </div>
                        </div>
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">Jane</h5>
                                <small>Processed 5 prescriptions <span class="text-info">Updated</span> 2 days ago</small>
                            </div>
                        </div>
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{ asset('assets/images/xs/avatar6.jpg') }}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">John</h5>
                                <small>Received new stock shipment <span class="text-info">Updated</span> 3 days ago</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <h6>System Updates</h6>
                    <ul class="list-unstyled">
                        <li>
                            <div class="progress-container progress-primary">
                                <span class="progress-badge">Database</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                        <span class="progress-value">86%</span>
                                    </div>
                                </div>                        
                            </div>
                        </li>
                        <li>
                            <div class="progress-container progress-warning">
                                <span class="progress-badge">Inventory Sync</span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
                                        <span class="progress-value">45%</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>