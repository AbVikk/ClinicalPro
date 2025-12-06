<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Pharmacy Stock Management - Request and Manage Inventory">
<title>Clinical Pro || Stock Management</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">

@include('pharmacy.pharmacy_sidemenu')

<section class="content">
<div class="container-fluid">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Stock Management</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ Auth::user()->role === 'primary_pharmacist' ? route('primary_pharmacist.dashboard') : (Auth::user()->role === 'senior_pharmacist' ? route('senior_pharmacist.dashboard') : route('clinic_pharmacist.dashboard')) }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item active">Stock Management</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Request Stock from Warehouse</h2>
                </div>
                <div class="body">
                    <form id="request-stock-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_id">Drug Batch</label>
                                    <select class="form-control" id="batch_id" name="batch_id" required>
                                        <option value="">Select a batch</option>
                                        @foreach($batches as $batch)
                                            <option value="{{ $batch->id }}">
                                                {{ $batch->drug->name }} ({{ $batch->drug->strength_mg }}) - 
                                                Batch: {{ $batch->batch_uuid }} - 
                                                Available: {{ $batch->clinicInventories->where('clinic_id', Auth::user()->clinic_id)->first()->stock_level ?? 0 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_id">Warehouse</label>
                                    <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                                        <option value="">Select warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Request Stock</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Low Stock Alerts</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Drug Name</th>
                                    <th>Strength</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Point</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->drug->name }}</td>
                                    <td>{{ $item->drug->strength_mg }}</td>
                                    <td>{{ $item->stock_level }}</td>
                                    <td>{{ $item->reorder_point }}</td>
                                    <td>
                                        @if($item->stock_level == 0)
                                            <span class="badge badge-danger">Out of Stock</span>
                                        @else
                                            <span class="badge badge-warning">Low Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<!-- Scripts -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/admin.js') }}"></script>

<script>
    document.getElementById('request-stock-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.clinic.request-stock") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                alert('Stock request submitted successfully!');
                // Reset form
                this.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the request.');
        });
    });
</script>
</body>
</html>