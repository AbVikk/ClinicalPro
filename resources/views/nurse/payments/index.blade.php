<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Payments</title>
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
                <h2>Payments
                <small class="text-muted">Manage patient payments</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item active">Payments</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Payment</strong> Records</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ route('nurse.payments.create') }}">Add Payment</a></li>
                                </ul>
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
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                        <td>₦{{ number_format($payment->amount, 2) }}</td>
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
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('nurse.payments.show', $payment) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="zmdi zmdi-eye"></i>
                                            </a>
                                            <a href="{{ route('nurse.payments.edit', $payment) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                            <a href="{{ route('nurse.payments.invoice', $payment) }}" class="btn btn-sm btn-success" title="Invoice">
                                                <i class="zmdi zmdi-file-text"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No payments found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $payments->links() }}
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