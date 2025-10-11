<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: Oreo Hospital :: Photo Capture</title>
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
                <form class="form" id="registerPhotoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="header">
                        <div class="logo-container">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="">
                        </div>
                        <h5>Photo Capture</h5>
                        <span>Welcome, {{ $name }}! Please take or upload your photo</span>
                    </div>
                    <div class="content">
                        <!-- Hidden fields -->
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        
                        <div class="form-group">
                            <div class="text-center mb-3">
                                <video id="video" width="320" height="240" autoplay style="display: none;"></video>
                                <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                                <img id="photoPreview" src="" alt="Photo Preview" style="display: none; max-width: 320px; max-height: 240px;">
                            </div>
                            
                            <div class="text-center mb-3">
                                <button type="button" id="startCamera" class="btn btn-primary btn-round waves-effect waves-light">
                                    <i class="zmdi zmdi-camera"></i> Start Camera
                                </button>
                                <button type="button" id="capturePhoto" class="btn btn-success btn-round waves-effect waves-light" style="display: none;">
                                    <i class="zmdi zmdi-camera"></i> Capture Photo
                                </button>
                                <button type="button" id="retakePhoto" class="btn btn-warning btn-round waves-effect waves-light" style="display: none;">
                                    <i class="zmdi zmdi-refresh"></i> Retake
                                </button>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-group-btn">
                                    <span class="btn btn-info btn-round waves-effect waves-light">
                                        <i class="zmdi zmdi-folder"></i> Choose File
                                        <input type="file" name="photo" id="photoFile" accept="image/*" style="display: none;">
                                    </span>
                                </label>
                                <input type="text" class="form-control" readonly placeholder="No file selected">
                            </div>
                            
                            <input type="hidden" id="capturedImageData" name="captured_image">
                        </div>
                        
                        <div id="registerPhotoSuccess" class="alert alert-success" style="display: none;"></div>
                        <div id="registerPhotoError" class="alert alert-danger" style="display: none;"></div>
                    </div>
                    <div class="footer text-center" id="submitButtonContainer">
                        <button type="submit" class="btn btn-primary btn-round btn-block waves-effect waves-light" id="submitBtn">SAVE AND CONTINUE</button>
                        <h5><a href="{{ route('register.continue') }}" class="link">Back</a></h5>
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
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photoPreview = document.getElementById('photoPreview');
    const startCameraBtn = document.getElementById('startCamera');
    const capturePhotoBtn = document.getElementById('capturePhoto');
    const retakePhotoBtn = document.getElementById('retakePhoto');
    const photoFileInput = document.getElementById('photoFile');
    const fileTextInput = document.querySelector('.form-control[readonly]');
    const capturedImageData = document.getElementById('capturedImageData');
    const context = canvas.getContext('2d');
    let stream = null;
    
    // Start camera
    startCameraBtn.addEventListener('click', function() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    video.srcObject = mediaStream;
                    video.style.display = 'block';
                    startCameraBtn.style.display = 'none';
                    capturePhotoBtn.style.display = 'inline-block';
                })
                .catch(function(error) {
                    showError('Unable to access camera. Please upload a photo instead.');
                    console.error('Camera error:', error);
                });
        } else {
            showError('Your browser does not support camera access. Please upload a photo instead.');
        }
    });
    
    // Capture photo
    capturePhotoBtn.addEventListener('click', function() {
        if (stream) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Stop camera
            stream.getTracks().forEach(track => track.stop());
            video.style.display = 'none';
            
            // Show captured photo
            const dataUrl = canvas.toDataURL('image/png');
            photoPreview.src = dataUrl;
            photoPreview.style.display = 'block';
            capturedImageData.value = dataUrl;
            
            // Show retake button and hide capture button
            capturePhotoBtn.style.display = 'none';
            retakePhotoBtn.style.display = 'inline-block';
        }
    });
    
    // Retake photo
    retakePhotoBtn.addEventListener('click', function() {
        // Hide photo preview and show video
        photoPreview.style.display = 'none';
        video.style.display = 'block';
        
        // Start camera again
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    video.srcObject = mediaStream;
                })
                .catch(function(error) {
                    showError('Unable to access camera.');
                    console.error('Camera error:', error);
                });
        }
        
        // Reset buttons
        retakePhotoBtn.style.display = 'none';
        capturePhotoBtn.style.display = 'inline-block';
        capturedImageData.value = '';
    });
    
    // File input change
    photoFileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileTextInput.value = file.name;
            
            // Hide camera elements if they were active
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.style.display = 'none';
                startCameraBtn.style.display = 'inline-block';
                capturePhotoBtn.style.display = 'none';
                retakePhotoBtn.style.display = 'none';
            }
            
            // Preview selected file
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
                capturedImageData.value = ''; // Clear captured image data
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Handle form submission with AJAX
    document.getElementById('registerPhotoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const submitButtonContainer = document.getElementById('submitButtonContainer');
        
        // Disable submit button during request
        submitBtn.disabled = true;
        submitBtn.textContent = 'SAVING...';
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit via AJAX
        fetch('{{ route('register.process.photo') }}', {
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
                showError(data.message || 'Failed to save photo. Please try again.');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'SAVE AND CONTINUE';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'SAVE AND CONTINUE';
        });
    });
    
    function showSuccess(message) {
        const successDiv = document.getElementById('registerPhotoSuccess');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        document.getElementById('registerPhotoError').style.display = 'none';
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('registerPhotoError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('registerPhotoSuccess').style.display = 'none';
        
        // Hide error after 5 seconds
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
});
</script>
</body>
</html>