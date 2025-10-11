<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: image gallery</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Light Gallery Plugin Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/light-gallery/css/lightgallery.css') }}">

<!-- Custom Css -->
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
                <h2>Image Gallery
                <small>Welcome to Oreo</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="index.blade.php"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                    <li class="breadcrumb-item active">Image Gallery</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid"> 
        <div class="row">            
            <div class="col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Gallery</strong> <small>All pictures taken from <a href="https://pexels.com/" target="_blank">pexels.com</a></small> </h2>
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
                        <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/1.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-1.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/2.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-2.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/3.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-3.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/4.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-4.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/5.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-5.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/6.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-6.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/7.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-7.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/8.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-8.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/9.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-9.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/10.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-10.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/11.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-11.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/12.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-12.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/13.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-13.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/14.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-14.jpg" alt=""> </a> </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b-20"> <a href="../assets/images/image-gallery/15.jpg"> <img class="img-fluid img-thumbnail" src="../assets/images/image-gallery/thumb/thumb-15.jpg" alt=""> </a> </div>
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

<!-- Light Gallery Plugin Js -->
<script src="{{ asset('assets/plugins/light-gallery/js/lightgallery-all.min.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- Image Gallery Js -->
<script src="{{ asset('assets/js/pages/medias/image-gallery.js') }}"></script>
</body>
</html>