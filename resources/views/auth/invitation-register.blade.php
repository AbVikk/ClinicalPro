<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Clinical Pro :: Register</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Oreo"></div>
        <p>Please wait...</p>
    </div>
</div>

<div class="authentication">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <form class="card" method="POST" action="{{ route('invitations.process', $invitation->token) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="header">
                        <h3>Sign Up</h3>
                        <span>Register as {{ ucfirst(str_replace('_', ' ', $invitation->role)) }}</span>
                    </div>
                    <div class="body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}" required autofocus>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="zmdi zmdi-account"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" placeholder="Email" value="{{ $invitation->email }}" readonly>
                                    <input type="hidden" name="email" value="{{ $invitation->email }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Phone" name="phone" value="{{ old('phone') }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="zmdi zmdi-phone"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                                    </div>
                                </div>
                                <small class="form-text" id="passwordHelp" style="color: #2CA8FF;">
                                    Password must be 8 characters with uppercase, lowercase, numbers, and symbols
                                </small>
                                <div id="passwordValidation" class="mt-2"></div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" id="password_confirmation" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                                    </div>
                                </div>
                                <div id="confirmPasswordValidation" class="mt-2"></div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Role (Assigned)</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}" readonly>
                                    <input type="hidden" name="role" value="{{ $invitation->role }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Role-specific fields -->
                        @if($invitation->role === 'doctor')
                            @include('auth.partials.registration-fields-doctor')
                        @elseif($invitation->role === 'patient')
                            @include('auth.partials.registration-fields-patient')
                        @elseif($invitation->role === 'nurse')
                            @include('auth.partials.registration-fields-clinic_staff')
                        @else
                            @include('auth.partials.registration-fields-default')
                        @endif
                        
                        <button type="submit" class="btn btn-primary btn-round btn-block">REGISTER ACCOUNT</button>
                        
                        <div class="signin_with mt-3">
                            <p class="mb-0">Already have an account? <a href="{{ route('login') }}" title="">Sign In</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
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
        // This is a simplified error display - in a real implementation, you might want to show this in a more specific location
        console.log('Error: ' + message);
    }
});
</script>
</body>
</html>