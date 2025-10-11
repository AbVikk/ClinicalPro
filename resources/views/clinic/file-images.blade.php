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
                <h2>Images
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
                    <li class="breadcrumb-item active">Images</li>                    
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#a2017">2017</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#b2016">2016</a></li>                        
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="a2017">
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/1.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2017</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/2.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2017</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/3.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2017</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/4.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2017</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/5.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2017</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/8.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2017</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/9.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2017</span></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="b2016">
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/6.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2016</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/7.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2016</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/11.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2016</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/10.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2016</span></small>
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
                                            <div class="image">
                                                <img src="../assets/images/image-gallery/14.jpg" alt="img" class="img-fluid">
                                            </div>
                                            <div class="file-name">
                                                <p class="m-b-5 text-muted">img21545ds.jpg</p>
                                                <small>Size: 2MB <span class="date text-muted">Dec 11, 2016</span></small>
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