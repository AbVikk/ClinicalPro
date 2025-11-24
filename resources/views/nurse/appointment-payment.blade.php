<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
{{-- CSRF Token for AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Clinical Pro || Appointment Payment</title>
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
@include('nurse.sidemenu')

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
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
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
                        
                        <div id="payment-error" class="alert alert-danger" style="display: none;"></div>

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
                                                <p><strong>Amount:</strong> ₦{{ number_format($payment->amount ?? 0, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="header">
                                        <h2>Payment Options</h2>
                                    </div>
                                    <div class="body">
                                        <p><strong>Total Amount:</strong> ₦{{ number_format($payment->amount ?? 0, 2) }}</p>
                                        <button id="pay-button" class="btn btn-primary btn-lg btn-block">Pay with Paystack</button>
                                        <!-- Using the authenticated route for pending payments -->
                                        <a href="{{ route('nurse.payments.pending.public') }}?reference={{ $payment->reference }}" class="btn btn-warning btn-lg btn-block mt-2">Pay with Cash</a>
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

<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- Paystack Inline JS -->
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
    document.getElementById('pay-button').addEventListener('click', function(e) {
        var payButton = e.target;
        payButton.disabled = true;
        payButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Initializing...';
        
        var errorDiv = document.getElementById('payment-error');
        errorDiv.style.display = 'none';
        errorDiv.textContent = '';

        // This function calls our *own* server first
        // It calls the 'nurse.payments.paystack.initialize' route
        fetch('{{ route('nurse.payments.paystack.initialize') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: '{{ $patient->email }}',
                amount: {{ $payment->amount }}, // Send amount in Naira, controller will multiply
                consultation_id: {{ $consultation->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === true && data.reference) {
                // SUCCESS! We got a reference from Paystack via our server
                // Now initialize Paystack payment with proper callbacks
                var handler = PaystackPop.setup({
                    key: '{{ config('services.paystack.public_key') }}',
                    email: '{{ $patient->email }}',
                    amount: {{ $payment->amount * 100 }}, // Paystack expects amount in kobo
                    ref: data.reference,
                    callback: function(response) {
                        // Redirect to verification endpoint
                        window.location.href = '{{ route('admin.payment.verify') }}?reference=' + response.reference;
                    },
                    onClose: function() {
                        // Re-enable the pay button if payment is cancelled
                        payButton.disabled = false;
                        payButton.textContent = 'Pay with Paystack';
                    }
                });
                
                handler.openIframe();
            } else {
                // Handle errors from our server or Paystack
                console.error('Initialization failed:', data);
                errorDiv.textContent = data.message || data.error || 'Payment initialization failed. Please try again.';
                errorDiv.style.display = 'block';
                payButton.disabled = false;
                payButton.textContent = 'Pay with Paystack';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            errorDiv.textContent = 'A network error occurred. Please check your connection and try again.';
            errorDiv.style.display = 'block';
            payButton.disabled = false;
            payButton.textContent = 'Pay with Paystack';
        });
    });
</script>
</body>
</html>