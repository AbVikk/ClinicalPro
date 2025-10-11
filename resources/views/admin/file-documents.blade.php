<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: </title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Font Icon -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content file_manager">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Documents
                <small>Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-white btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-upload"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="index.blade.php"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="file-dashboard.blade.php">File Manager</a></li>
                    <li class="breadcrumb-item active">Documents</li>                    
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#doc">Doc</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pdf">PDF</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#xls">XLS</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="doc">
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-file-text"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Document_2017.doc</p>
                                                <small>Size: 42KB <span class="date text-muted">Nov 02, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-file-text"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Document_2017.doc</p>
                                                <small>Size: 89KB <span class="date text-muted">Dec 15, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-file-text"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Document_2017.doc</p>
                                                <small>Size: 89KB <span class="date text-muted">Dec 15, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-file-text"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Document_2017.doc</p>
                                                <small>Size: 42KB <span class="date text-muted">Nov 02, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-file-text"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Document_2017.doc</p>
                                                <small>Size: 89KB <span class="date text-muted">Dec 15, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-file-text"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Document_2017.doc</p>
                                                <small>Size: 89KB <span class="date text-muted">Dec 15, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="pdf">
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-collection-pdf"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">asdf  hhkj.pdf</p>
                                                <small>Size: 3MB <span class="date text-muted">Aug 18, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-collection-pdf"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">asdf  hhkj.pdf</p>
                                                <small>Size: 3MB <span class="date text-muted">Aug 18, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-collection-pdf"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">asdf  hhkj.pdf</p>
                                                <small>Size: 3MB <span class="date text-muted">Aug 18, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-collection-pdf"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">asdf  hhkj.pdf</p>
                                                <small>Size: 3MB <span class="date text-muted">Aug 18, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="xls">
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2016.xls</p>
                                                <small>Size: 68KB <span class="date text-muted">Dec 12, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2017.xls</p>
                                                <small>Size: 103KB <span class="date text-muted">Jan 24, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2016.xls</p>
                                                <small>Size: 68KB <span class="date text-muted">Dec 12, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2016.xls</p>
                                                <small>Size: 68KB <span class="date text-muted">Dec 12, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2017.xls</p>
                                                <small>Size: 103KB <span class="date text-muted">Jan 24, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2016.xls</p>
                                                <small>Size: 68KB <span class="date text-muted">Dec 12, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2016.xls</p>
                                                <small>Size: 68KB <span class="date text-muted">Dec 12, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2017.xls</p>
                                                <small>Size: 103KB <span class="date text-muted">Jan 24, 2016</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="file">
                                        <a href="javascript:void(0);">
                                            <div class="hover">
                                                <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </div>
                                            <div class="icon">
                                                <i class="zmdi zmdi-chart"></i>
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">Report2016.xls</p>
                                                <small>Size: 68KB <span class="date text-muted">Dec 12, 2016</span></small>
                                            </div>
                                        </a>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>   
</body>
</html>