<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Patients</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>All Patients
                <small class="text-muted">Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="index.blade.php"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Patients</a></li>
                    <li class="breadcrumb-item active">All Patients</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card patients-list">
                    <div class="header">
                        <h2><strong>Patients</strong> List</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
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
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#All">All</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#USA">USA</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#India">India</a></li>
                        </ul>
                            
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane table-responsive active" id="All">
                                <table class="table m-b-0 table-hover">
                                    <thead>
                                        <tr>                                       
                                            <th>Media</th>
                                            <th>Patients ID</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Address</th>
                                            <th>Number</th>
                                            <th>Last Visit</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar1.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00598</span></td>
                                            <td>Daniel</td>
                                            <td>32</td>
                                            <td>71 Pilgrim Avenue Chevy Chase, MD 20815</td>
                                            <td>404-447-6013</td>
                                            <td>11 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar2.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00258</span></td>
                                            <td>Alexander</td>
                                            <td>22</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>15 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar3.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00369</span></td>
                                            <td>Richard</td>
                                            <td>26</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>16 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar4.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00147</span></td>
                                            <td>Samuel</td>
                                            <td>45</td>
                                            <td>71 Pilgrim Avenue Chevy Chase, MD 20815</td>
                                            <td>404-447-6013</td>
                                            <td>17 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar5.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00123</span></td>
                                            <td>Stephen</td>
                                            <td>55</td>
                                            <td>71 Pilgrim Avenue Chevy Chase, MD 20815</td>
                                            <td>404-447-6013</td>
                                            <td>18 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar1.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00456</span></td>
                                            <td>Joseph</td>
                                            <td>27</td>
                                            <td>70 Bowman St. South Windsor, CT 06074</td>
                                            <td>404-447-6013</td>
                                            <td>19 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar2.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00789</span></td>
                                            <td>Cameron</td>
                                            <td>38</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>19 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar3.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00987</span></td>
                                            <td>Alex</td>
                                            <td>39</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>20 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar4.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00951</span></td>
                                            <td>James</td>
                                            <td>32</td>
                                            <td>44 Shirley Ave. West Chicago, IL 60185</td>
                                            <td>404-447-6013</td>
                                            <td>22 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar1.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00953</span></td>
                                            <td>charlie</td>
                                            <td>51</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>22 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar5.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00984</span></td>
                                            <td>William</td>
                                            <td>35</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>22 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar2.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00934</span></td>
                                            <td>thomas</td>
                                            <td>26</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>29 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                    </tbody>
                                </table>                            
                            </div>
                            <div class="tab-pane table-responsive" id="USA">
                                <table class="table m-b-0 table-hover">
                                    <thead>
                                        <tr>                                       
                                            <th>Media</th>
                                            <th>Patients ID</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Address</th>
                                            <th>Number</th>
                                            <th>Last Visit</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar1.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00598</span></td>
                                            <td>Daniel</td>
                                            <td>32</td>
                                            <td>71 Pilgrim Avenue Chevy Chase, MD 20815</td>
                                            <td>404-447-6013</td>
                                            <td>11 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar2.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00258</span></td>
                                            <td>Alexander</td>
                                            <td>22</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>15 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>                                       
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar1.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00456</span></td>
                                            <td>Joseph</td>
                                            <td>27</td>
                                            <td>70 Bowman St. South Windsor, CT 06074</td>
                                            <td>404-447-6013</td>
                                            <td>19 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar2.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00789</span></td>
                                            <td>Cameron</td>
                                            <td>38</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>19 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar3.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00987</span></td>
                                            <td>Alex</td>
                                            <td>39</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>20 Jan 2018</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar4.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00951</span></td>
                                            <td>James</td>
                                            <td>32</td>
                                            <td>44 Shirley Ave. West Chicago, IL 60185</td>
                                            <td>404-447-6013</td>
                                            <td>22 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar1.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00953</span></td>
                                            <td>charlie</td>
                                            <td>51</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>22 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="list-icon"><img class="patients-img" src="../assets/images/xs/avatar2.jpg" alt=""></span></td>
                                            <td><span class="list-name">KU 00934</span></td>
                                            <td>thomas</td>
                                            <td>26</td>
                                            <td>123 6th St. Melbourne, FL 32904</td>
                                            <td>404-447-6013</td>
                                            <td>29 Jan 2018</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                    </tbody>
                                </table>
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

<!-- Lib Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>