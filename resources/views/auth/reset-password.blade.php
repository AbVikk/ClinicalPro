<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: Oreo Hospital :: Reset Password</title>
    <!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
<!-- JQuery DataTable Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/authentication.css') }}">
<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>

<body class="theme-cyan authentication sidebar-collapse">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top navbar-transparent">
    <div class="container">        
        <div class="navbar-translate n_logo">
            <a class="navbar-brand" href="javascript:void(0);" title="" target="_blank">Oreo</a>
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
                    <a class="nav-link btn btn-white btn-round" href="{{ route('register') }}">SIGN UP</a>
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
                <form class="form" id="resetPasswordForm">
                    @csrf
                    <div class="header">
                        <div class="logo-container">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="">
                        </div>
                        <h5>Reset Password</h5>
                        <span>Create a new password</span>
                    </div>
                    <div class="content">                                                
                        <input type="hidden" name="email" value="{{ request('email') }}">
                        <input type="hidden" name="token" value="{{ request('token') }}">
                        
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Enter new password" name="password" id="password" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>
                        <small class="form-text" id="passwordHelp" style="color: #2CA8FF;">
                            Password must be 8 characters with uppercase, lowercase, numbers, and symbols
                        </small>
                        <div id="passwordValidation" class="mt-2"></div>
                        
                        <div class="input-group mt-3">
                            <input type="password" class="form-control" placeholder="Confirm new password" name="password_confirmation" id="password_confirmation" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>
                        <div id="confirmPasswordValidation" class="mt-2"></div>
                        
                        <div id="passwordError" class="alert alert-danger" style="display: none;"></div>
                        <div id="passwordSuccess" class="alert alert-success" style="display: none;"></div>
                    </div>
                    <div class="footer text-center">
                        <button type="submit" class="btn btn-primary btn-round btn-block">RESET PASSWORD</button>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js -->
<!-- Lib Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script>
   $(".navbar-toggler").on('click',function() {
    $("html").toggleClass("nav-open");
});
//=============================================================================
$('.form-control').on("focus", function() {
    $(this).parent('.input-group').addClass("input-group-focus");
}).on("blur", function() {
    $(this).parent(".input-group").removeClass("input-group-focus");
});

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordValidation = document.getElementById('passwordValidation');
    const confirmPasswordValidation = document.getElementById('confirmPasswordValidation');
    
    // Password validation
    passwordInput.addEventListener('input', function() {
        validatePassword(this.value);
    });
    
    // Confirm password validation
    confirmPasswordInput.addEventListener('input', function() {
        validateConfirmPassword(this.value, passwordInput.value);
    });
    
    // Handle form submission with AJAX
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Validate password
        if (!validatePassword(password)) {
            return;
        }
        
        // Check if passwords match
        if (password !== confirmPassword) {
            showError('Passwords do not match');
            return;
        }
        
        // Submit via AJAX
        const formData = new FormData(this);
        
        // Disable submit button during request
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'RESETTING...';
        
        fetch('{{ route('password.update') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Password reset successfully! Redirecting to login...');
                setTimeout(() => {
                    window.location.href = '{{ route('login') }}';
                }, 2000);
            } else {
                showError(data.message || 'Failed to reset password. Please try again.');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'RESET PASSWORD';
            }
        })
        .catch(error => {
            showError('An error occurred. Please try again.');
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'RESET PASSWORD';
        });
    });
    
    function validatePassword(password) {
        const minLength = 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        const hasSymbols = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
        
        // Clear previous validation messages
        passwordValidation.innerHTML = '';
        
        if (password.length === 0) {
            return false;
        }
        
        if (password.length < minLength) {
            showError('Password must be at least 8 characters long');
            return false;
        }
        
        if (!hasUpperCase || !hasLowerCase || !hasNumbers || !hasSymbols) {
            showError('Password must contain uppercase, lowercase, numbers, and symbols');
            return false;
        }
        
        // Show success message when all validations pass
        showPasswordSuccess('Password meets all requirements');
        return true;
    }
    
    function validateConfirmPassword(confirmPassword, password) {
        // Clear previous validation messages
        confirmPasswordValidation.innerHTML = '';
        
        if (confirmPassword.length === 0) {
            return false;
        }
        
        if (confirmPassword === password) {
            showConfirmPasswordSuccess('Passwords match');
            return true;
        } else {
            showConfirmPasswordError('Passwords do not match');
            return false;
        }
    }
    
    function showPasswordSuccess(message) {
        passwordValidation.innerHTML = `<small class="text-success"><i class="zmdi zmdi-check-circle"></i> ${message}</small>`;
    }
    
    function showConfirmPasswordSuccess(message) {
        confirmPasswordValidation.innerHTML = `<small class="text-success"><i class="zmdi zmdi-check-circle"></i> ${message}</small>`;
    }
    
    function showConfirmPasswordError(message) {
        confirmPasswordValidation.innerHTML = `<small class="text-danger"><i class="zmdi zmdi-close-circle"></i> ${message}</small>`;
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('passwordError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('passwordSuccess').style.display = 'none';
        
        // Hide error after 5 seconds
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
    
    function showSuccess(message) {
        const successDiv = document.getElementById('passwordSuccess');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        document.getElementById('passwordError').style.display = 'none';
    }
});
</script>
</body>
</html>