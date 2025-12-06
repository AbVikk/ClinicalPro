<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Clinical Pro :: Pharmacy Dashboard</title>
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
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

@include('pharmacy.primary_pharmacist.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Pharmacy Dashboard</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('primary_pharmacist.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item active">Pharmacy</li>
                </ul>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Pharmacy</strong> Management</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Welcome to the Pharmacy Management section. Here you can manage all aspects of your pharmacy operations.</p>
                                
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.pharmacy.categories.index') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/categories.png') }}" alt="Categories" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.pharmacy.categories.index') }}">Drug Categories</a></h6>
                                                <p>Manage drug categories</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.pharmacy.mg.index') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/mg.png') }}" alt="MG Values" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.pharmacy.mg.index') }}">MG Values</a></h6>
                                                <p>Manage drug MG values</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.pharmacy.drugs.create.form') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/add_drug.png') }}" alt="Add Drug" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.pharmacy.drugs.create.form') }}">Add Drug</a></h6>
                                                <p>Add new drugs to inventory</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.pharmacy.stock.receive') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/receive_stock.png') }}" alt="Receive Stock" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.pharmacy.stock.receive') }}">Receive Stock</a></h6>
                                                <p>Receive new stock</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.clinic.request-stock') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/request_stock.png') }}" alt="Request Stock" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.clinic.request-stock') }}">Request Stock</a></h6>
                                                <p>Request stock for clinics</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.clinic.sell') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/process_sale.png') }}" alt="Process Sale" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.clinic.sell') }}">Process Sale</a></h6>
                                                <p>Process drug sales</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.pharmacists.index') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/pharmacists.png') }}" alt="Pharmacists" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.pharmacists.index') }}">Pharmacists</a></h6>
                                                <p>Manage pharmacists</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card product_item">
                                            <div class="body text-center">
                                                <a href="{{ route('primary_pharmacist.invitations.create') }}">
                                                    <img class="img-fluid" src="{{ asset('assets/images/invite.png') }}" alt="Invite" style="height: 150px; object-fit: contain;">
                                                </a>
                                                <h6><a href="{{ route('primary_pharmacist.invitations.create') }}">Invite Pharmacists</a></h6>
                                                <p>Invite new pharmacists</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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