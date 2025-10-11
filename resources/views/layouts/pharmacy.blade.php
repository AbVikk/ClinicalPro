<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Telehealth Pharmacy System">
    <meta name="author" content="Telehealth System">
    <title>Telehealth Pharmacy System</title>
    
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.min.css') }}" rel="stylesheet">
</head>

<body class="theme-cyan">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="{{ asset('assets/images/logo-icon.svg') }}" width="48" height="48" alt="Telehealth"></div>
            <p>Please wait...</p>
        </div>
    </div>
    
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="col-12">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ route('pharmacy.dashboard') }}">Telehealth Pharmacy</a>
            </div>
            <ul class="nav navbar-nav navbar-left">
                <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap"></i></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('logout') }}" class="mega-menu" data-close="true" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="zmdi zmdi-power"></i></a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <div class="user-info">
            <div class="image">
                <img src="{{ asset('assets/images/user.png') }}" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div>
                <div class="email">{{ Auth::user()->email }}</div>
            </div>
        </div>
        
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <li class="{{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('pharmacy.dashboard') }}">
                        <i class="zmdi zmdi-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                @if(Auth::user()->role === 'primary_pharmacist')
                <li class="header">PRIMARY PHARMACIST</li>
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="zmdi zmdi-collection-item"></i>
                        <span>Stock Management</span>
                    </a>
                    <ul class="ml-menu">
                        <li><a href="{{ route('admin.pharmacy.stock.receive') }}">Receive Stock</a></li>
                        <li><a href="{{ route('admin.pharmacy.transfers.approve', 1) }}">Approve Transfers</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="zmdi zmdi-collection-bookmark"></i>
                        <span>Drug Catalog</span>
                    </a>
                    <ul class="ml-menu">
                        <li><a href="{{ route('admin.pharmacy.drugs.create') }}">Add New Drug</a></li>
                        <li><a href="{{ route('admin.pharmacy.drugs.update', 1) }}">Update Drug</a></li>
                    </ul>
                </li>
                @endif
                
                @if(Auth::user()->role === 'senior_pharmacist')
                <li class="header">SENIOR PHARMACIST</li>
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="zmdi zmdi-collection-item"></i>
                        <span>Inventory</span>
                    </a>
                    <ul class="ml-menu">
                        <li><a href="{{ route('admin.clinic.request-stock') }}">Request Stock</a></li>
                        <li><a href="{{ route('admin.clinic.transfer.receive', 1) }}">Receive Stock</a></li>
                        <li><a href="{{ route('admin.clinic.alerts') }}">Low Stock Alerts</a></li>
                    </ul>
                </li>
                @endif
                
                @if(Auth::user()->role === 'clinic_pharmacist')
                <li class="header">CLINIC PHARMACIST</li>
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="zmdi zmdi-shopping-cart"></i>
                        <span>Sales</span>
                    </a>
                    <ul class="ml-menu">
                        <li><a href="{{ route('admin.clinic.sell') }}">Process Sale</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </aside>
    
    <!-- Main Content -->
    <section class="content">
        @yield('content')
    </section>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    <script src="{{ asset('assets/js/pages/index.js') }}"></script>
</body>
</html>