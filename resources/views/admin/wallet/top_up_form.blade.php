<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Hospital Wallet Top-Up</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

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
                <h2>Hospital Wallet Top-Up
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Wallet</a></li>
                    <li class="breadcrumb-item active">Top-Up</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Hospital Wallet</strong> Top-Up</h2>
                    </div>
                    <div class="body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

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

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="body">
                                        <h4>Hospital Wallet Top-Up</h4>
                                        <p>Use this form to add funds to your hospital's wallet. These top-ups will appear in the payments list as hospital fund transactions.</p>
                                        
                                        <form id="topup-form">
                                            <div class="form-group">
                                                <label for="email">Admin Email</label>
                                                <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="amount">Amount (₦)</label>
                                                <input type="number" id="amount" class="form-control" placeholder="Enter amount" step="0.01" min="100" required>
                                            </div>
                                            
                                            <button type="button" id="initialize-topup" class="btn btn-primary btn-round">Initialize Top-Up</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="body">
                                        <h4>How It Works</h4>
                                        <ol>
                                            <li>Enter your email and the amount you want to top up</li>
                                            <li>Click "Initialize Top-Up" to start the payment process</li>
                                            <li>You'll be redirected to Paystack to complete the payment</li>
                                            <li>After successful payment, the transaction will appear in the payments list</li>
                                        </ol>
                                        
                                        <div class="alert alert-info">
                                            <strong>Note:</strong> Minimum top-up amount is ₦100.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Paystack Inline JS -->
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('initialize-topup').addEventListener('click', function() {
        const email = document.getElementById('email').value;
        const amount = document.getElementById('amount').value;
        
        if (!email || !amount) {
            alert('Please fill in all fields');
            return;
        }
        
        if (amount < 100) {
            alert('Minimum top-up amount is ₦100');
            return;
        }
        
        // Call the backend to initialize the payment
        fetch('{{ route("admin.payment.initialize-topup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email: email,
                amount: amount
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else if (data.data && data.data.authorization_url) {
                // Redirect to Paystack payment page
                window.location.href = data.data.authorization_url;
            } else {
                alert('Unexpected response from server');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>