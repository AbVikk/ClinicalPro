<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Payments</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
<!-- JQuery DataTable Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css') }}">

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
                <h2>Payments
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Payments</a></li>
                    <li class="breadcrumb-item active">Payments</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Payments</strong> List</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">Add Payment</a></li>
                        </ul>
                    </div>
                    <div class="body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>Bill No</th>
                                        <th>Bill date</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Charges</th>
                                        <th>Tax</th>                                            
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>21</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>17</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>02/21/2017</td>
                                        <td>Lori Perkins</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>22</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Jessica Patterson</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>105</td>
                                        <td>02/21/2017</td>
                                        <td>Lori Perkins</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>56</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>34</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Jessica Patterson</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>21</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>17</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>02/21/2017</td>
                                        <td>Lori Perkins</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>22</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>02/21/2017</td>
                                        <td>Christina Thomas</td>
                                        <td>Jessica Patterson</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>105</td>
                                        <td>02/21/2017</td>
                                        <td>Lori Perkins</td>
                                        <td>Juan Freeman</td>
                                        <td>102</td>
                                        <td>10</td>
                                        <td>10%</td>
                                        <td>210</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
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

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js -->

<!-- Lib Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<!-- Jquery DataTable Plugin Js -->
<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- jQuery DataTable Js -->
<script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>
</body>
</html>