<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Hospital Registration">
    <title>Hospital Registration</title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
    <style>
        .register-box {
            margin: 5% auto;
            max-width: 600px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            background-color: #01baf2;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .btn-primary {
            background-color: #01baf2;
            border-color: #01baf2;
        }
        .form-control:focus {
            border-color: #01baf2;
            box-shadow: 0 0 0 0.2rem rgba(1, 186, 242, 0.25);
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body class="theme-cyan">
    <div class="register-box">
        <div class="card">
            <div class="card-header">
                <h3>Register New Hospital</h3>
            </div>
            <div class="card-body">
                <!-- Display Success Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Display Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Display Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('hospital.register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="hospital_name">Hospital Name</label>
                        <input id="hospital_name" type="text" class="form-control @error('hospital_name') is-invalid @enderror" 
                               name="hospital_name" value="{{ old('hospital_name') }}" required autocomplete="hospital_name" autofocus placeholder="Enter hospital name">
                        @error('hospital_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="domain_prefix">Domain Prefix</label>
                        <input id="domain_prefix" type="text" class="form-control @error('domain_prefix') is-invalid @enderror" 
                               name="domain_prefix" value="{{ old('domain_prefix') }}" required autocomplete="domain_prefix" placeholder="Enter domain prefix">
                        @error('domain_prefix')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror" 
                                  name="address" required placeholder="Enter hospital address">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact_email">Contact Email</label>
                        <input id="contact_email" type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                               name="contact_email" value="{{ old('contact_email') }}" required autocomplete="contact_email" placeholder="Enter contact email">
                        @error('contact_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone') }}" required placeholder="Enter phone number">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <hr>

                    <h4>Hospital Administrator Account</h4>

                    <div class="form-group">
                        <label for="admin_name">Administrator Name</label>
                        <input id="admin_name" type="text" class="form-control @error('admin_name') is-invalid @enderror" 
                               name="admin_name" value="{{ old('admin_name') }}" required autocomplete="admin_name" placeholder="Enter administrator name">
                        @error('admin_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="admin_email">Administrator Email</label>
                        <input id="admin_email" type="email" class="form-control @error('admin_email') is-invalid @enderror" 
                               name="admin_email" value="{{ old('admin_email') }}" required autocomplete="admin_email" placeholder="Enter administrator email">
                        @error('admin_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="admin_password">Password</label>
                        <input id="admin_password" type="password" class="form-control @error('admin_password') is-invalid @enderror" 
                               name="admin_password" required autocomplete="new-password" placeholder="Enter password">
                        @error('admin_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="admin_password_confirmation">Confirm Password</label>
                        <input id="admin_password_confirmation" type="password" class="form-control" 
                               name="admin_password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-block">
                            Register Hospital
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-block mt-2">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    
    <!-- Auto-dismiss alerts after 5 seconds -->
    <script>
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            for (var i = 0; i < alerts.length; i++) {
                alerts[i].classList.remove('show');
            }
        }, 5000);
    </script>
</body>
</html>