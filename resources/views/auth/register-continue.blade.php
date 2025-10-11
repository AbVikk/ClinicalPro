<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: Oreo Hospital :: Continue Registration</title>
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
                <form class="form" id="registerContinueForm">
                    @csrf
                    <div class="header">
                        <div class="logo-container">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="">
                        </div>
                        <h5>Complete Registration</h5>
                        <span>Welcome, {{ $name }}! Please provide additional information</span>
                    </div>
                    <div class="content">
                        <!-- Hidden fields -->
                        <input type="hidden" name="registration_date" value="{{ now() }}">
                        
                        <div class="input-group">
                            <select class="form-control" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-male-female"></i>
                            </span>
                        </div>
                        <div class="input-group">
                            <textarea class="form-control" name="address" placeholder="Enter Full Address" rows="3" required></textarea>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-pin"></i>
                            </span>
                        </div>
                        <small class="form-text text-muted" style="color: #2CA8FF;">Please enter your Date of Birth</small>
                        <div class="input-group">
                            <input type="date" class="form-control" name="date_of_birth" placeholder="Date of Birth" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                        </div>
                        
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" placeholder="Enter Password" id="password" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>
                        <small class="form-text" id="passwordHelp" style="color: #2CA8FF;">
                            Password must be 8 characters with uppercase, lowercase, numbers, and symbols
                        </small>
                        <div id="passwordValidation" class="mt-2"></div>
                        
                        <div class="input-group mt-3">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation" required>
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span>
                        </div>
                        <div id="confirmPasswordValidation" class="mt-2"></div>
                        <div id="registerContinueSuccess" class="alert alert-success" style="display: none;"></div>
                        <div id="registerContinueError" class="alert alert-danger" style="display: none;"></div>
                    </div>
                    <div class="footer text-center" id="submitButtonContainer">
                        <button type="submit" class="btn btn-primary btn-round btn-block waves-effect waves-light" id="submitBtn">COMPLETE REGISTRATION</button>
                        <h5><a href="{{ route('register.initial') }}" class="link">Start Over</a></h5>
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
    document.getElementById('registerContinueForm').addEventListener('submit', function(e) {
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
        
        const submitBtn = document.getElementById('submitBtn');
        const submitButtonContainer = document.getElementById('submitButtonContainer');
        
        // Disable submit button during request
        submitBtn.disabled = true;
        submitBtn.textContent = 'PROCESSING...';
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit via AJAX
        fetch('{{ route('register.process.continue') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                // Hide submit button
                submitButtonContainer.style.display = 'none';
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                showError(data.message || 'Failed to complete registration. Please try again.');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'COMPLETE REGISTRATION';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'COMPLETE REGISTRATION';
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
    
    function showSuccess(message) {
        const successDiv = document.getElementById('registerContinueSuccess');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        document.getElementById('registerContinueError').style.display = 'none';
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('registerContinueError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('registerContinueSuccess').style.display = 'none';
        
        // Hide error after 5 seconds
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
});
</script>
</body>
</html>