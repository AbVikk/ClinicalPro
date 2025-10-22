<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Payment Success</title>
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
                <h2>Payment Success
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Payments</a></li>
                    <li class="breadcrumb-item active">Success</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body text-center">
                        <div class="success-icon">
                            <i class="zmdi zmdi-check-circle" style="font-size: 100px; color: #4CAF50;"></i>
                        </div>
                        <h2 class="text-success">Payment Successful!</h2>
                        <p class="lead">Your payment has been processed successfully.</p>
                        
                        @if(isset($payment))
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
                                                <td><strong>Date:</strong></td>
                                                <td>{{ $payment->transaction_date->format('M d, Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Reference:</strong></td>
                                                <td>{{ $payment->reference ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Method:</strong></td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-primary btn-lg">
                                <i class="zmdi zmdi-arrow-left"></i> Back to Payments
                            </a>
                            @if(isset($payment))
                            <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-info btn-lg">
                                <i class="zmdi zmdi-file-text"></i> View Invoice
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
        window.location.href = "{{ route('admin.payments.index') }}";
    }, 10000);
</script>
</body>
</html>