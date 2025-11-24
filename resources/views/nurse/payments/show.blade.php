<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Payment Details</title>
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
                <h2>Payment Details
                <small class="text-muted">View payment information</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('nurse.payments.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">Payment #{{ $payment->id }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Payment</strong> Details</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ route('nurse.payments.edit', $payment) }}">Edit</a></li>
                                    <li><a href="{{ route('nurse.payments.invoice', $payment) }}">View Invoice</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Payment ID:</th>
                                        <td>{{ $payment->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Patient:</th>
                                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount:</th>
                                        <td>₦{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td>
                                            @if($payment->method == 'cash_in_clinic')
                                                <span class="badge badge-success">Cash</span>
                                            @elseif($payment->method == 'card_online')
                                                <span class="badge badge-primary">Card</span>
                                            @elseif($payment->method == 'bank_transfer')
                                                <span class="badge badge-info">Bank Transfer</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($payment->method) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($payment->status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($payment->status == 'pending_cash_verification')
                                                <span class="badge badge-warning">Pending Cash</span>
                                            @elseif($payment->status == 'failed')
                                                <span class="badge badge-danger">Failed</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Reference:</th>
                                        <td>{{ $payment->reference ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction Date:</th>
                                        <td>{{ $payment->transaction_date ? $payment->transaction_date->format('M d, Y g:i A') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $payment->created_at->format('M d, Y g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $payment->updated_at->format('M d, Y g:i A') }}</td>
                                    </tr>
                                    @if($payment->consultation)
                                    <tr>
                                        <th>Consultation:</th>
                                        <td>#{{ $payment->consultation->id }} - {{ $payment->consultation->service_type }}</td>
                                    </tr>
                                    @endif
                                    @if($payment->appointment)
                                    <tr>
                                        <th>Appointment:</th>
                                        <td>#{{ $payment->appointment->id }} - {{ $payment->appointment->doctor->name ?? 'N/A' }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <a href="{{ route('nurse.payments.index') }}" class="btn btn-default btn-round">Back to Payments</a>
                                <a href="{{ route('nurse.payments.edit', $payment) }}" class="btn btn-primary btn-round">Edit Payment</a>
                                <a href="{{ route('nurse.payments.invoice', $payment) }}" class="btn btn-success btn-round">View Invoice</a>
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