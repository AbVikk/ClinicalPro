<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Pharmacy Management System">
<title>Clinical Pro || Pharmacy</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical"></div>
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
            <a class="navbar-brand" href="{{ Auth::user()->role === 'primary_pharmacist' ? route('primary_pharmacist.dashboard') : (Auth::user()->role === 'senior_pharmacist' ? route('senior_pharmacist.dashboard') : route('clinic_pharmacist.dashboard')) }}">Telehealth Pharmacy</a>
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
            @if(Auth::user()->photo)
                <img src="{{ asset('storage/' . Auth::user()->photo) }}" width="48" height="48" alt="User" />
            @else
                <img src="{{ asset('assets/images/user.png') }}" width="48" height="48" alt="User" />
            @endif
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div>
            <div class="email">{{ Auth::user()->email }}</div>
            <div class="role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
        </div>
    </div>
    
    <div class="menu">
        <ul class="list">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <a href="{{ Auth::user()->role === 'primary_pharmacist' ? route('primary_pharmacist.dashboard') : (Auth::user()->role === 'senior_pharmacist' ? route('senior_pharmacist.dashboard') : route('clinic_pharmacist.dashboard')) }}">
                    <i class="zmdi zmdi-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="{{ request()->routeIs('*.inventory.predictions') ? 'active' : '' }}">
                <a href="{{ Auth::user()->role === 'primary_pharmacist' ? route('primary_pharmacist.inventory.predictions') : (Auth::user()->role === 'senior_pharmacist' ? route('senior_pharmacist.inventory.predictions') : route('clinic_pharmacist.inventory.predictions')) }}">
                    <i class="zmdi zmdi-chart"></i>
                    <span>Inventory Predictions</span>
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
                    <li><a href="{{ route('admin.pharmacy.drugs.create.form') }}">Add New Drug</a></li>
                    <li><a href="{{ route('admin.pharmacy.drugs.update', 1) }}">Update Drug</a></li>
                    <li><a href="{{ route('admin.pharmacy.categories.index') }}">Categories</a></li>
                    <li><a href="{{ route('admin.pharmacy.mg.index') }}">MG Values</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="zmdi zmdi-accounts"></i>
                    <span>Pharmacist Management</span>
                </a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.pharmacists.index') }}">All Pharmacists</a></li>
                    <li><a href="{{ route('admin.invitations.create') }}">Invite Senior Pharmacist</a></li>
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
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="zmdi zmdi-accounts"></i>
                    <span>Clinic Management</span>
                </a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.invitations.create') }}">Invite Clinic Pharmacist</a></li>
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
            
            <li class="header">PRESCRIPTIONS</li>
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="zmdi zmdi-collection-item"></i>
                    <span>Prescriptions</span>
                </a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.prescriptions.index') }}">All Prescriptions</a></li>
                    <li><a href="{{ route('admin.prescriptions.create') }}">Create Prescription</a></li>
                </ul>
            </li>
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

<!-- Additional Scripts -->
@stack('page-scripts')
</body>
</html>