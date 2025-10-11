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
                    <a class="nav-link" href="javascript:void(0);">Search Result</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" title="Follow us on Twitter" href="javascript:void(0);" target="_blank">
                        <i class="zmdi zmdi-twitter"></i>
                        <p class="d-lg-none d-xl-none">Twitter</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" title="Like us on Facebook" href="javascript:void(0);" target="_blank">
                        <i class="zmdi zmdi-facebook"></i>
                        <p class="d-lg-none d-xl-none">Facebook</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" title="Follow us on Instagram" href="javascript:void(0);" target="_blank">                        
                        <i class="zmdi zmdi-instagram"></i>
                        <p class="d-lg-none d-xl-none">Instagram</p>
                    </a>
                </li>                
                <li class="nav-item">
                    <a class="nav-link btn btn-white btn-round" href="{{ route('login') }}">SIGN IN</a>
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
                <form class="form" id="forgotPasswordForm">
                    @csrf
                    <div class="header">
                        <div class="logo-container">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="">
                        </div>
                        <h5>Forgot Password?</h5>
                        <span>Enter your e-mail address below to reset your password.</span>
                    </div>
                    <div class="content">
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-email"></i>
                            </span>
                        </div>
                        <div id="forgotPasswordSuccess" class="alert alert-success" style="display: none;"></div>
                        <div id="forgotPasswordError" class="alert alert-danger" style="display: none;"></div>
                    </div>
                    <div class="footer text-center" id="submitButtonContainer">
                        <button type="submit" class="btn btn-primary btn-round btn-block waves-effect waves-light" id="submitBtn">SUBMIT</button>
                        <h5><a href="{{ route('login') }}" class="link">Back to Login</a></h5>
                    </div>
                </form>
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
    // Handle form submission with AJAX
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const submitButtonContainer = document.getElementById('submitButtonContainer');
        
        // Disable submit button during request
        submitBtn.disabled = true;
        submitBtn.textContent = 'SENDING...';
        
        // Get form data
        const email = document.querySelector('input[name="email"]').value;
        
        // Submit via AJAX
        fetch('{{ route('password.email') }}', {
            method: 'POST',
            body: JSON.stringify({
                email: email,
                _token: document.querySelector('input[name="_token"]').value
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showSuccess(data.message);
                // Hide submit button
                submitButtonContainer.style.display = 'none';
                
                // Redirect to OTP page after a short delay
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                showError(data.message || 'Failed to send reset link. Please try again.');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'SUBMIT';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'SUBMIT';
        });
    });
    
    function showSuccess(message) {
        const successDiv = document.getElementById('forgotPasswordSuccess');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        document.getElementById('forgotPasswordError').style.display = 'none';
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('forgotPasswordError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('forgotPasswordSuccess').style.display = 'none';
        
        // Hide error after 5 seconds
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
});
</script>
</body>
</html>