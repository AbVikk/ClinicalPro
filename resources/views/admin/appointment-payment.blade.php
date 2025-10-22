<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>Clinical Pro || Appointment Payment</title>
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
                <h2>Appointment Payment
                <small>Welcome to Clinical Pro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Appointment</a></li>
                    <li class="breadcrumb-item active">Payment</li>
                </ul>
            </div>
        </div>
    </div>    
    <div class="container-fluid">        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Appointment</strong> Payment</h2>
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
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="header">
                                        <h2>Appointment Details</h2>
                                    </div>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Patient:</strong> {{ $patient->name }}</p>
                                                <p><strong>Email:</strong> {{ $patient->email }}</p>
                                                <p><strong>Service:</strong> {{ $consultation->service_type }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Appointment Date:</strong> {{ $consultation->start_time->format('d M Y, H:i') }}</p>
                                                <p><strong>Doctor:</strong> {{ $consultation->doctor->name ?? 'Not assigned yet' }}</p>
                                                <p><strong>Amount:</strong> ₦{{ number_format($payment->amount, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="header">
                                        <h2>Payment</h2>
                                    </div>
                                    <div class="body">
                                        <p><strong>Total Amount:</strong> ₦{{ number_format($payment->amount, 2) }}</p>
                                        <button id="pay-button" class="btn btn-primary btn-lg btn-block">Pay with Paystack</button>
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

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- Paystack Inline JS -->
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        // Show pending payment page while initializing
        // Get the payment details from the backend
        fetch('{{ route('admin.appointment.payment.initialize') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                consultation_id: {{ $consultation->id }},
                payment_id: {{ $payment->id }},
                email: '{{ $patient->email }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Payment initialization failed: ' + data.error);
                return;
            }
            
            // Initialize Paystack payment
            var handler = PaystackPop.setup({
                key: '{{ $publicKey }}',
                email: '{{ $patient->email }}',
                amount: {{ $payment->amount * 100 }}, // Paystack expects amount in kobo
                ref: data.data.reference,
                callback: function(response) {
                    // Redirect to verification endpoint
                    window.location.href = '{{ route('admin.payment.verify') }}?reference=' + response.reference;
                },
                onClose: function() {
                    // Show pending page when payment is cancelled
                    window.location.href = '{{ route('admin.payments.pending') }}';
                }
            });
            
            handler.openIframe();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
</script>
</body>
</html>