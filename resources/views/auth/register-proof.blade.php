<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: Oreo Hospital :: Upload Proof</title>
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
                <form class="form" id="proofUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="header">
                        <div class="logo-container">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="">
                        </div>
                        <h5>Upload Proof of Identity</h5>
                        <span>Please upload a valid ID or license</span>
                    </div>
                    <div class="content">
                        <!-- Hidden user ID -->
                        <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                        
                        <div class="form-group">
                            <label for="proof">Upload ID Document</label>
                            <input type="file" class="form-control-file" id="proof" name="proof" accept="image/*" required>
                            <small class="form-text text-muted" style="color: #2CA8FF;">Upload a clear photo of your ID, license, or other official document</small>
                        </div>
                        
                        <div id="proofPreview" class="mt-3" style="display: none;">
                            <h6>Preview:</h6>
                            <img id="previewImage" src="#" alt="Proof Preview" style="max-width: 100%; max-height: 300px;">
                        </div>
                        
                        <div id="proofUploadSuccess" class="alert alert-success" style="display: none;"></div>
                        <div id="proofUploadError" class="alert alert-danger" style="display: none;"></div>
                    </div>
                    <div class="footer text-center" id="submitButtonContainer">
                        <button type="submit" class="btn btn-primary btn-round btn-block waves-effect waves-light" id="submitBtn">UPLOAD PROOF</button>
                        <h5><a href="{{ route('login') }}" class="link">Skip for now</a></h5>
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
    // Handle file preview
    document.getElementById('proof').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('proofPreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Handle form submission with AJAX
    document.getElementById('proofUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const submitButtonContainer = document.getElementById('submitButtonContainer');
        
        // Disable submit button during request
        submitBtn.disabled = true;
        submitBtn.textContent = 'UPLOADING...';
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit via AJAX
        fetch('{{ route('register.process.proof') }}', {
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
                showError(data.message || 'Failed to upload proof. Please try again.');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'UPLOAD PROOF';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'UPLOAD PROOF';
        });
    });
    
    function showSuccess(message) {
        const successDiv = document.getElementById('proofUploadSuccess');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        document.getElementById('proofUploadError').style.display = 'none';
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('proofUploadError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('proofUploadSuccess').style.display = 'none';
        
        // Hide error after 5 seconds
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
});
</script>
</body>
</html>