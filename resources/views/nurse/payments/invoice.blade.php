<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Payment Invoice</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('nurse.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Payment Invoice
                <small class="text-muted">Payment receipt</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('nurse.payments.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">Invoice #{{ $payment->id }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-header">
                                    <h2>INVOICE</h2>
                                    <p>Payment Receipt</p>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <address>
                                    <strong>Clinical Pro Hospital</strong><br>
                                    123 Medical Avenue<br>
                                    Health City, HC 10001<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                </address>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <address>
                                    <strong>Bill To:</strong><br>
                                    {{ $payment->user->name ?? 'N/A' }}<br>
                                    {{ $payment->user->email ?? 'N/A' }}<br>
                                    Patient ID: {{ $payment->user->user_id ?? 'N/A' }}
                                </address>
                            </div>
                            <div class="col-md-6 text-right">
                                <address>
                                    <strong>Payment Details:</strong><br>
                                    Invoice #: {{ $payment->id }}<br>
                                    Payment Date: {{ $payment->created_at->format('M d, Y') }}<br>
                                    Reference #: {{ $payment->reference ?? 'N/A' }}
                                </address>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($payment->consultation)
                                            <tr>
                                                <td>
                                                    {{ $payment->consultation->service_type }}<br>
                                                    <small>
                                                        Doctor: {{ $payment->consultation->doctor->name ?? 'N/A' }}<br>
                                                        Date: {{ $payment->consultation->start_time->format('M d, Y g:i A') }}<br>
                                                        Duration: {{ $payment->consultation->duration_minutes }} minutes
                                                    </small>
                                                </td>
                                                <td class="text-right">₦{{ number_format($payment->amount, 2) }}</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td>Medical Service</td>
                                                <td class="text-right">₦{{ number_format($payment->amount, 2) }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Payment Method:</strong> 
                                    @if($payment->method == 'cash_in_clinic')
                                        Cash
                                    @elseif($payment->method == 'card_online')
                                        Credit/Debit Card
                                    @elseif($payment->method == 'bank_transfer')
                                        Bank Transfer
                                    @else
                                        {{ ucfirst($payment->method) }}
                                    @endif
                                </p>
                                <p><strong>Payment Status:</strong> 
                                    @if($payment->status == 'paid')
                                        <span class="badge badge-success">Paid</span>
                                    @elseif($payment->status == 'pending_cash_verification')
                                        <span class="badge badge-warning">Pending Cash Verification</span>
                                    @elseif($payment->status == 'failed')
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="total">
                                    <h3><strong>Total: </strong> ₦{{ number_format($payment->amount, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-12 text-center no-print">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-lg" onclick="window.print()">Print Invoice</button>
                                    <a href="{{ route('nurse.payments.index') }}" class="btn btn-default btn-lg">Back to Payments</a>
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
</body>
</html>