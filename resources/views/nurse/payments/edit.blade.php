<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>ClinicalPro || Edit Payment</title>
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
                <h2>Edit Payment
                <small class="text-muted">Update payment record</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('nurse.payments.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">Edit Payment</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Edit</strong> Payment #{{ $payment->id }}</h2>
                    </div>
                    <div class="body">
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
                        
                        <form method="POST" action="{{ route('nurse.payments.update', $payment) }}">
                            @csrf
                            @method('PUT')
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="user_id">Patient *</label>
                                        <select id="user_id" name="user_id" class="form-control show-tick" required>
                                            <option value="">- Select Patient -</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}" {{ (old('user_id', $payment->user_id) == $patient->id) ? 'selected' : '' }}>
                                                    {{ $patient->name }} ({{ $patient->user_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="amount">Amount (₦) *</label>
                                        <input type="number" id="amount" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount', $payment->amount) }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="method">Payment Method *</label>
                                        <select id="method" name="method" class="form-control show-tick" required>
                                            <option value="">- Select Method -</option>
                                            <option value="cash_in_clinic" {{ (old('method', $payment->method) == 'cash_in_clinic') ? 'selected' : '' }}>Cash</option>
                                            <option value="card_online" {{ (old('method', $payment->method) == 'card_online') ? 'selected' : '' }}>Card (Paystack)</option>
                                            <option value="bank_transfer" {{ (old('method', $payment->method) == 'bank_transfer') ? 'selected' : '' }}>Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="status">Status *</label>
                                        <select id="status" name="status" class="form-control show-tick" required>
                                            <option value="">- Select Status -</option>
                                            <option value="pending_cash_verification" {{ (old('status', $payment->status) == 'pending_cash_verification') ? 'selected' : '' }}>Pending Cash Verification</option>
                                            <option value="paid" {{ (old('status', $payment->status) == 'paid') ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ (old('status', $payment->status) == 'failed') ? 'selected' : '' }}>Failed</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="reference">Reference</label>
                                        <input type="text" id="reference" name="reference" class="form-control" value="{{ old('reference', $payment->reference) }}">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="transaction_date">Transaction Date</label>
                                        <input type="date" id="transaction_date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $payment->transaction_date ? $payment->transaction_date->format('Y-m-d') : date('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-round">Update Payment</button>
                                    <a href="{{ route('nurse.payments.index') }}" class="btn btn-default btn-round">Cancel</a>
                                </div>
                            </div>
                        </form>
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