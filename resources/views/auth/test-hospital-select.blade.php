<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: Hospital Selection Test</title>
  <!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/authentication.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>

<body class="theme-cyan authentication sidebar-collapse">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top navbar-transparent">
    <div class="container">        
        <div class="navbar-translate n_logo">
            <a class="navbar-brand" href="{{ url('/') }}" title="" target="_blank">Oreo</a>
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </button>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<div class="page-header">
    <div class="page-header-image" style="background-image:url({{ asset('assets/images/login.jpg') }})"></div>
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="card-plain">
                <div class="header">
                    <div class="logo-container">
                        <img src="{{ asset('assets/images/logo.svg') }}" alt="">
                    </div>
                    <h5>Hospital Selection Test</h5>
                    <span>Test the hospital selection dropdown</span>
                </div>
                <div class="content">
                    <div class="input-group">
                        <select class="form-control" id="hospitalSelect">
                            <option value="">Select Hospital</option>
                            @foreach(App\Models\Hospital::where('is_active', true)->get() as $hospital)
                                <option value="{{ $hospital->id }}" data-domain="{{ $hospital->domain_prefix }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-addon">
                            <i class="zmdi zmdi-hospital"></i>
                        </span>
                    </div>
                    
                    <div id="hospitalInfo" style="margin-top: 20px; display: none;">
                        <h6>Selected Hospital Info:</h6>
                        <p><strong>Name:</strong> <span id="hospitalName"></span></p>
                        <p><strong>Domain:</strong> <span id="hospitalDomain"></span></p>
                        <p><strong>Registration URL:</strong> <a href="#" id="registrationLink" target="_blank"></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <nav>
                <ul>
                    <li><a href="http://thememakker.com/contact/" target="_blank">Contact Us</a></li>
                    <li><a href="http://thememakker.com/about/" target="_blank">About Us</a></li>
                    <li><a href="javascript:void(0);">FAQ</a></li>
                </ul>
            </nav>
            <div class="copyright">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>,
                <span>Designed by <a href="http://thememakker.com/" target="_blank">ThemeMakker</a></span>
            </div>
        </div>
    </footer>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js -->
<script>
   $(".navbar-toggler").on('click',function() {
    $("html").toggleClass("nav-open");
});

document.addEventListener('DOMContentLoaded', function() {
    const hospitalSelect = document.getElementById('hospitalSelect');
    const hospitalInfo = document.getElementById('hospitalInfo');
    const hospitalName = document.getElementById('hospitalName');
    const hospitalDomain = document.getElementById('hospitalDomain');
    const registrationLink = document.getElementById('registrationLink');
    
    hospitalSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const domain = selectedOption.getAttribute('data-domain');
            const name = selectedOption.text;
            
            hospitalName.textContent = name;
            hospitalDomain.textContent = domain;
            registrationLink.href = `/register/${domain}`;
            registrationLink.textContent = `/register/${domain}`;
            
            hospitalInfo.style.display = 'block';
        } else {
            hospitalInfo.style.display = 'none';
        }
    });
});
</script>
</body>
</html>