<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Payment Pending</title>
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
@include('nurse.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Payment Pending
                <small class="text-muted">Payment awaiting verification</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('nurse.payments.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">Payment Pending</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body text-center">
                        <div class="pending-icon">
                            <i class="zmdi zmdi-time" style="font-size: 100px; color: #ff9800;"></i>
                        </div>
                        <h2>Payment Pending Verification</h2>
                        <p class="lead">Your payment is awaiting verification.</p>
                        
                        @if($payment)
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="header">
                                        <h2>Payment Details</h2>
                                    </div>
                                    <div class="body">
                                        <table class="table">
                                            <tr>
                                                <td><strong>Payment ID:</strong></td>
                                                <td>{{ $payment->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Amount:</strong></td>
                                                <td>₦{{ number_format($payment->amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Reference:</strong></td>
                                                <td>{{ $payment->reference ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date:</strong></td>
                                                <td>{{ $payment->created_at->format('M d, Y g:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    @if($payment->status == 'pending_cash_verification')
                                                        <span class="badge badge-warning">Pending Cash Verification</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ ucfirst($payment->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="alert alert-info mt-3">
                            <strong>Note:</strong> This payment is pending verification. Please contact the accounts department to complete the verification process.
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('nurse.payments.index') }}" class="btn btn-primary btn-lg">
                                <i class="zmdi zmdi-arrow-left"></i> Back to Payments
                            </a>
                            @if($payment)
                            <a href="{{ route('nurse.payments.show', $payment) }}" class="btn btn-info btn-lg">
                                <i class="zmdi zmdi-eye"></i> View Payment Details
                            </a>
                            @endif
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

<script>
    // Auto redirect after 10 seconds
    setTimeout(function() {
        window.location.href = "{{ route('nurse.dashboard') }}";
    }, 10000);
</script>
</body>
</html>