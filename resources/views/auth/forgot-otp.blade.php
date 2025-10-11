<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: Oreo Hospital :: OTP Verification</title>
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
                <form class="form" id="otpForm">
                    @csrf
                    <div class="header">
                        <div class="logo-container">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="">
                        </div>
                        <h5>OTP Verification</h5>
                        <span>Enter the 4-digit code sent to your email</span>
                    </div>
                    <div class="content">                                                
                        <input type="hidden" name="email" value="{{ request('email') }}">
                        <div class="input-group mb-3">
                            <div class="otp-inputs d-flex justify-content-center">
                                <input type="text" class="form-control otp-input text-center mx-2" maxlength="1" data-index="0" required>
                                <input type="text" class="form-control otp-input text-center mx-2" maxlength="1" data-index="1" required>
                                <input type="text" class="form-control otp-input text-center mx-2" maxlength="1" data-index="2" required>
                                <input type="text" class="form-control otp-input text-center mx-2" maxlength="1" data-index="3" required>
                            </div>
                        </div>
                        
                        <div id="otpError" class="alert alert-danger" style="display: none;"></div>
                        <div id="otpSuccess" class="alert alert-success" style="display: none;"></div>
                        
                        <div class="text-center mt-3">
                            <small>Didn't receive the code? 
                                <a href="javascript:void(0);" id="resendOtp" class="link">Resend</a>
                                <span id="resendCountdown" style="display: none;">(available in <span id="resendTimer">60</span>s)</span>
                            </small>
                        </div>
                        <div id="countdownTimer" class="text-center" style="display: none; margin-top: 10px;">
                            <small>OTP expires in <span id="countdown">300</span> seconds</small>
                        </div>
                    </div>
                    <div class="footer text-center">
                        <button type="submit" class="btn btn-primary btn-round btn-block">VERIFY OTP</button>
                        <h5><a href="{{ route('password.request') }}" class="link">Back to Forgot Password</a></h5>
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
    // Focus on first input
    document.querySelector('.otp-input').focus();
    
    // Start OTP expiration countdown
    startOtpCountdown(300); // 5 minutes = 300 seconds
    
    // Handle OTP input navigation
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach(input => {
        // Auto advance to next input when a digit is entered
        input.addEventListener('input', function() {
            if (this.value.length === 1) {
                const nextIndex = parseInt(this.getAttribute('data-index')) + 1;
                if (nextIndex < otpInputs.length) {
                    otpInputs[nextIndex].focus();
                }
            }
        });
        
        // Handle backspace to move to previous input
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '') {
                const prevIndex = parseInt(this.getAttribute('data-index')) - 1;
                if (prevIndex >= 0) {
                    otpInputs[prevIndex].focus();
                }
            }
        });
    });
    
    // Handle form submission with AJAX
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Collect OTP digits
        let otp = '';
        otpInputs.forEach(input => {
            otp += input.value;
        });
        
        // Validate OTP length
        if (otp.length !== 4) {
            showError('Please enter all 4 digits');
            return;
        }
        
        // Submit via AJAX
        const formData = new FormData(this);
        formData.append('otp', otp);
        
        // Disable submit button during request
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'VERIFYING...';
        
        fetch('{{ route('password.verify-otp') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('OTP verified successfully! Redirecting...');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showError(data.message || 'Invalid OTP. Please try again.');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'VERIFY OTP';
            }
        })
        .catch(error => {
            showError('An error occurred. Please try again.');
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'VERIFY OTP';
        });
    });
    
    // Handle resend OTP
    document.getElementById('resendOtp').addEventListener('click', function(e) {
        e.preventDefault();
        
        const email = document.querySelector('input[name="email"]').value;
        const resendLink = this;
        const countdownElement = document.getElementById('resendCountdown');
        const timerElement = document.getElementById('resendTimer');
        
        // Disable resend link
        resendLink.style.display = 'none';
        countdownElement.style.display = 'inline';
        
        // Submit via AJAX
        fetch('{{ route('password.resend-otp') }}', {
            method: 'POST',
            body: new URLSearchParams({
                '_token': document.querySelector('input[name="_token"]').value,
                'email': email
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showSuccess(data.message);
                
                // Start 60-second countdown
                let seconds = 60;
                timerElement.textContent = seconds;
                
                const countdown = setInterval(() => {
                    seconds--;
                    timerElement.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        resendLink.style.display = 'inline';
                        countdownElement.style.display = 'none';
                    }
                }, 1000);
            } else {
                showError(data.message || 'Failed to resend OTP. Please try again.');
                resendLink.style.display = 'inline';
                countdownElement.style.display = 'none';
            }
        })
        .catch(error => {
            showError('An error occurred. Please try again.');
            resendLink.style.display = 'inline';
            countdownElement.style.display = 'none';
        });
    });
    
    function showError(message) {
        const errorDiv = document.getElementById('otpError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('otpSuccess').style.display = 'none';
        
        // Hide error after 5 seconds
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
    
    function showSuccess(message) {
        const successDiv = document.getElementById('otpSuccess');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        document.getElementById('otpError').style.display = 'none';
    }
    
    function startOtpCountdown(seconds) {
        const countdownElement = document.getElementById('countdown');
        const countdownTimer = document.getElementById('countdownTimer');
        countdownTimer.style.display = 'block';
        
        let remainingTime = seconds;
        countdownElement.textContent = remainingTime;
        
        const timer = setInterval(() => {
            remainingTime--;
            countdownElement.textContent = remainingTime;
            
            if (remainingTime <= 0) {
                clearInterval(timer);
                countdownTimer.style.display = 'none';
                showError('OTP has expired. Please request a new one.');
            }
        }, 1000);
    }
});
</script>
<style>
.otp-input {
    width: 60px !important;
    height: 60px !important;
    font-size: 1.5rem;
    text-align: center;
    border-radius: 8px;
}
</style>
</body>
</html>