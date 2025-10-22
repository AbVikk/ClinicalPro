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
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Payment Details
                <small class="text-muted">Welcome to ClinicalPro</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">Payment Details</li>
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
                                <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-primary">
                                    <i class="zmdi zmdi-edit"></i> Edit Payment
                                </a>
                            </li>
                        </ul>
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
                                        <th>Email:</th>
                                        <td>{{ $payment->user->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $payment->user->phone ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Amount:</th>
                                        <td>₦{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Method:</th>
                                        <td>
                                            <span class="badge bg-{{ $payment->method === 'paystack' ? 'success' : 'info' }}">
                                                {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($payment->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($payment->status === 'pending' || $payment->status === 'pending_cash_verification')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($payment->status === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reference:</th>
                                        <td>{{ $payment->reference ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction Date:</th>
                                        <td>{{ $payment->transaction_date->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($payment->appointment)
                        <div class="row">
                            <div class="col-12">
                                <h5>Appointment Details</h5>
                                <table class="table table-hover">
                                    <tr>
                                        <th>Appointment ID:</th>
                                        <td>{{ $payment->appointment->id }}</td>
                                        <th>Doctor:</th>
                                        <td>{{ $payment->appointment->doctor->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <td>{{ $payment->appointment->date->format('M d, Y') }}</td>
                                        <th>Time:</th>
                                        <td>{{ $payment->appointment->time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>{{ ucfirst($payment->appointment->status) }}</td>
                                        <th>Notes:</th>
                                        <td>{{ $payment->appointment->notes ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif

                        @if($payment->consultation)
                        <div class="row">
                            <div class="col-12">
                                <h5>Consultation Details</h5>
                                <table class="table table-hover">
                                    <tr>
                                        <th>Consultation ID:</th>
                                        <td>{{ $payment->consultation->id }}</td>
                                        <th>Doctor:</th>
                                        <td>{{ $payment->consultation->doctor->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Service Type:</th>
                                        <td>{{ $payment->consultation->service_type }}</td>
                                        <th>Delivery Channel:</th>
                                        <td>{{ ucfirst(str_replace('_', ' ', $payment->consultation->delivery_channel)) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Start Time:</th>
                                        <td>{{ $payment->consultation->start_time->format('M d, Y H:i') }}</td>
                                        <th>End Time:</th>
                                        <td>{{ $payment->consultation->end_time->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>{{ ucfirst($payment->consultation->status) }}</td>
                                        <th>Fee:</th>
                                        <td>₦{{ number_format($payment->consultation->fee, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif

                        <div class="row m-t-30">
                            <div class="col-12">
                                <a href="{{ route('admin.payments.index') }}" class="btn btn-default btn-round">Back to Payments</a>
                                <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-info btn-round" target="_blank">
                                    <i class="zmdi zmdi-print"></i> Print Invoice
                                </a>
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