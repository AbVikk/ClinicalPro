<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Invoice</title>
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
                <h2>Invoice
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
                    <li class="breadcrumb-item active">Invoice</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Payment</strong> Invoice</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown">
                                <button onclick="window.print()" class="btn btn-info">
                                    <i class="zmdi zmdi-print"></i> Print Invoice
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row" id="invoice-content">
                            <div class="col-12 text-center">
                                <h3>ClinicalPro Hospital</h3>
                                <p>123 Medical Avenue, Health City<br>
                                Phone: (123) 456-7890 | Email: info@clinicalpro.com</p>
                                <hr>
                            </div>
                            
                            <div class="col-6">
                                <h5>Bill To:</h5>
                                <p>
                                    <strong>{{ $payment->user->name ?? 'N/A' }}</strong><br>
                                    {{ $payment->user->email ?? 'N/A' }}<br>
                                    {{ $payment->user->phone ?? 'N/A' }}<br>
                                    {{ $payment->user->address ?? 'N/A' }}
                                </p>
                            </div>
                            
                            <div class="col-6 text-right">
                                <h5>Invoice Details:</h5>
                                <p>
                                    <strong>Invoice #:</strong> {{ $payment->id }}<br>
                                    <strong>Date:</strong> {{ $payment->transaction_date->format('M d, Y') }}<br>
                                    <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->method)) }}<br>
                                    <strong>Status:</strong> 
                                    @if($payment->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($payment->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Amount (₦)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($payment->consultation)
                                        <tr>
                                            <td>
                                                <strong>Consultation Fee</strong><br>
                                                Doctor: {{ $payment->consultation->doctor->name ?? 'N/A' }}<br>
                                                Service: {{ $payment->consultation->service_type }}<br>
                                                Date: {{ $payment->consultation->start_time->format('M d, Y H:i') }}
                                            </td>
                                            <td>₦{{ number_format($payment->consultation->fee, 2) }}</td>
                                        </tr>
                                        @elseif($payment->appointment)
                                        <tr>
                                            <td>
                                                <strong>Appointment Fee</strong><br>
                                                Doctor: {{ $payment->appointment->doctor->name ?? 'N/A' }}<br>
                                                Date: {{ $payment->appointment->date->format('M d, Y') }}<br>
                                                Time: {{ $payment->appointment->time }}
                                            </td>
                                            <td>₦{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td>
                                                <strong>General Payment</strong><br>
                                                Payment for medical services
                                            </td>
                                            <td>₦{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-right">Total:</th>
                                            <th>₦{{ number_format($payment->amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="col-12">
                                <p><strong>Notes:</strong></p>
                                <p>Thank you for choosing ClinicalPro Hospital. Please keep this invoice for your records.</p>
                                
                                <div class="text-center m-t-30">
                                    <p>This is a computer generated invoice. No signature required.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice-content, #invoice-content * {
        visibility: visible;
    }
    #invoice-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    .card {
        box-shadow: none;
        border: none;
    }
    
    .btn, .header-dropdown {
        display: none !important;
    }
}
</style>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>