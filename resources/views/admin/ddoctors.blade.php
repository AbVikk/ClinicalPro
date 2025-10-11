<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Doctors</title>
<link rel="icon" href="{{ asset('template/images/favicon.ico') }}" type="image/x-icon">
<!-- Favicon-->
<link rel="stylesheet" href="{{ asset('template/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/plugins/bootstrap-select/css/bootstrap-select.css') }}"/>
<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('template/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('template/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<!-- Main Content -->
<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Doctors
                <small class="text-muted">List of all doctors</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">                
                <button class="btn btn-primary btn-icon btn-round hidden-sm-down float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item active">Doctors</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Doctors</strong> List</h2>
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
                                        <th>Doctor</th>
                                        <th>Department</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="avatar">
                                                <img src="{{ asset('template/images/doctors/member1.png') }}" alt="Doctor">
                                            </div>
                                            <span>Dr. John Smith</span>
                                        </td>
                                        <td>Cardiology</td>
                                        <td>john.smith@hospital.com</td>
                                        <td>+1 (555) 123-4567</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">View</a>
                                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="avatar">
                                                <img src="{{ asset('template/images/doctors/member2.png') }}" alt="Doctor">
                                            </div>
                                            <span>Dr. Emily Davis</span>
                                        </td>
                                        <td>Orthopedics</td>
                                        <td>emily.davis@hospital.com</td>
                                        <td>+1 (555) 234-5678</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">View</a>
                                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="avatar">
                                                <img src="{{ asset('template/images/doctors/member3.png') }}" alt="Doctor">
                                            </div>
                                            <span>Dr. Michael Wilson</span>
                                        </td>
                                        <td>Neurology</td>
                                        <td>michael.wilson@hospital.com</td>
                                        <td>+1 (555) 345-6789</td>
                                        <td><span class="badge badge-warning">Inactive</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">View</a>
                                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script src="{{ asset('template/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('template/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('template/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('template/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('template/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('template/js/pages/tables/jquery-datatable.js') }}"></script>
</body>
</html>