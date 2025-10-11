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

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}">

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
                <h2>Edit Payment
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
                    <li class="breadcrumb-item active">Edit Payment</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Edit</strong> Payment</h2>
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

                        <form method="POST" action="{{ route('admin.payments.update', $payment->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="user_id">Patient</label>
                                        <select name="user_id" id="user_id" class="form-control show-tick" data-live-search="true" required>
                                            <option value="">-- Select Patient --</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}" {{ (old('user_id', $payment->user_id) == $patient->id) ? 'selected' : '' }}>
                                                    {{ $patient->name }} ({{ $patient->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="amount">Amount (₦)</label>
                                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" value="{{ old('amount', $payment->amount) }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="method">Payment Method</label>
                                        <select name="method" id="method" class="form-control show-tick" required>
                                            <option value="">-- Select Method --</option>
                                            <option value="cash" {{ (old('method', $payment->method) == 'cash') ? 'selected' : '' }}>Cash</option>
                                            <option value="cheque" {{ (old('method', $payment->method) == 'cheque') ? 'selected' : '' }}>Cheque</option>
                                            <option value="credit_card" {{ (old('method', $payment->method) == 'credit_card') ? 'selected' : '' }}>Credit Card</option>
                                            <option value="debit_card" {{ (old('method', $payment->method) == 'debit_card') ? 'selected' : '' }}>Debit Card</option>
                                            <option value="netbanking" {{ (old('method', $payment->method) == 'netbanking') ? 'selected' : '' }}>Net Banking</option>
                                            <option value="insurance" {{ (old('method', $payment->method) == 'insurance') ? 'selected' : '' }}>Insurance</option>
                                            <option value="paystack" {{ (old('method', $payment->method) == 'paystack') ? 'selected' : '' }}>Paystack</option>
                                            <option value="bank_transfer" {{ (old('method', $payment->method) == 'bank_transfer') ? 'selected' : '' }}>Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control show-tick" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="pending" {{ (old('status', $payment->status) == 'pending') ? 'selected' : '' }}>Pending</option>
                                            <option value="completed" {{ (old('status', $payment->status) == 'completed') ? 'selected' : '' }}>Completed</option>
                                            <option value="failed" {{ (old('status', $payment->status) == 'failed') ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ (old('status', $payment->status) == 'refunded') ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="reference">Reference (Optional)</label>
                                        <input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference', $payment->reference) }}">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="transaction_date">Transaction Date</label>
                                        <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="{{ old('transaction_date', $payment->transaction_date ? $payment->transaction_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12 m-t-30">
                                    <button type="submit" class="btn btn-primary btn-round">Update Payment</button>
                                    <a href="{{ route('admin.payments.index') }}" class="btn btn-default btn-round btn-simple">Cancel</a>
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