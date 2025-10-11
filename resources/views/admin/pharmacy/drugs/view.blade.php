<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Pharmacy Management System">
<title>:: Telehealth Pharmacy :: Drug Details</title>
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
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2>
                    <a href="{{ route('admin.pharmacy.dashboard') }}" class="btn btn-sm btn-primary">
                        <i class="zmdi zmdi-arrow-back"></i>
                    </a>
                    {{ $drug->name }} {{ $drug->strength_mg }}
                    @php
                        $totalStock = $drug->batches->sum('received_quantity');
                        $status = 'In Stock';
                        $statusClass = 'badge-success';
                        if ($totalStock == 0) {
                            $status = 'Out of Stock';
                            $statusClass = 'badge-danger';
                        } elseif ($totalStock < 50) {
                            $status = 'Low Stock';
                            $statusClass = 'badge-warning';
                        }
                    @endphp
                    <span class="badge {{ $statusClass }} ml-2">{{ $status }}</span>
                    <a href="{{ route('admin.pharmacy.drugs.edit', $drug->id) }}" class="btn btn-sm btn-warning float-right">
                        <i class="zmdi zmdi-edit"></i> Edit
                    </a>
                </h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Medicine</strong> Information</h2>
                    </div>
                    <div class="body">
                        <p>Basic details about this medicine</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td><strong>ID</strong></td>
                                        <td>MED{{ str_pad($drug->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Generic Name</strong></td>
                                        <td>{{ $drug->details['generic_name'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category</strong></td>
                                        <td>{{ $drug->category }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type</strong></td>
                                        <td>{{ $drug->is_controlled ? 'Prescription' : 'OTC' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Manufacturer</strong></td>
                                        <td>{{ $drug->details['manufacturer'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Stock</strong></td>
                                        <td>
                                            @php
                                                $totalStock = $drug->batches->sum('received_quantity');
                                            @endphp
                                            {{ $totalStock }} units
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Batch Number</strong></td>
                                        <td>
                                            @if($drug->batches->isNotEmpty())
                                                {{ $drug->batches->first()->batch_uuid ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Expiry Date</strong></td>
                                        <td>
                                            @if($drug->batches->isNotEmpty() && $drug->batches->first()->expiry_date)
                                                {{ $drug->batches->first()->expiry_date->format('Y-m-d') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Purchase Price</strong></td>
                                        <td>${{ number_format($drug->details['purchase_price'] ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Selling Price</strong></td>
                                        <td>${{ number_format($drug->unit_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Storage Location</strong></td>
                                        <td>Shelf A-12</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Clinical</strong> Information</h2>
                    </div>
                    <div class="body">
                        <p>Medical details and usage information</p>
                        
                        <h6>Description</h6>
                        <p>{{ $drug->details['description'] ?? 'No description available.' }}</p>
                        
                        <h6>Dosage</h6>
                        <p>{{ $drug->details['dosage'] ?? 'No dosage information available.' }}</p>
                        
                        <h6>Side Effects</h6>
                        <p>{{ $drug->details['side_effects'] ?? 'No side effects information available.' }}</p>
                        
                        <h6>Contraindications</h6>
                        <p>Known hypersensitivity to penicillins or cephalosporins.</p>
                        
                        <h6>Storage Instructions</h6>
                        <p>Store at room temperature between 15-30°C (59-86°F). Keep away from moisture and heat.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#transactions">Transaction History</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#batches">Batch History</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#alternatives">Alternatives</a></li>
                        </ul>
                            
                        <!-- Tab panes -->
                        <div class="tab-content m-t-10">
                            <div class="tab-pane active" id="transactions">
                                <div class="header">
                                    <h2><strong>Recent</strong> Transactions</h2>
                                    <ul class="header-dropdown">
                                        <li class="dropdown">
                                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                <i class="zmdi zmdi-filter-list"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right slideUp float-right">
                                                <li><a href="javascript:void(0);">Last 7 Days</a></li>
                                                <li><a href="javascript:void(0);">Last 30 Days</a></li>
                                                <li><a href="javascript:void(0);">Last 90 Days</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Reference</th>
                                                <th>Patient/Supplier</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($prescriptionItems as $item)
                                            <tr>
                                                <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                                <td>Sale</td>
                                                <td>-{{ $item->quantity }}</td>
                                                <td>PRESC{{ str_pad($item->prescription_id, 6, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $item->prescription->patient->name ?? 'N/A' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No transactions found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="batches">
                                <div class="header">
                                    <h2><strong>Batch</strong> History</h2>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Batch Number</th>
                                                <th>Quantity Received</th>
                                                <th>Date Received</th>
                                                <th>Expiry Date</th>
                                                <th>Remaining</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($drug->batches as $batch)
                                            <tr>
                                                <td>{{ $batch->batch_uuid ?? 'N/A' }}</td>
                                                <td>{{ $batch->received_quantity }}</td>
                                                <td>{{ $batch->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $batch->expiry_date->format('Y-m-d') }}</td>
                                                <td>{{ $batch->received_quantity }}</td>
                                                <td>
                                                    @if($batch->expiry_date->isPast())
                                                        <span class="badge badge-danger">Expired</span>
                                                    @elseif($batch->expiry_date->diffInDays(now()) < 30)
                                                        <span class="badge badge-warning">Expiring Soon</span>
                                                    @else
                                                        <span class="badge badge-success">Active</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No batches found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="alternatives">
                                <div class="header">
                                    <h2><strong>Alternative</strong> Medicines</h2>
                                    <ul class="header-dropdown">
                                        <li class="dropdown">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success">
                                                <i class="zmdi zmdi-plus"></i> Add Alternative
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Medicine Name</th>
                                                <th>Generic Name</th>
                                                <th>Current Stock</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($alternatives as $alternative)
                                            <tr>
                                                <td>{{ $alternative->name }} {{ $alternative->strength_mg }}mg</td>
                                                <td>{{ $alternative->details['generic_name'] ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                        $totalStock = $alternative->batches->sum('received_quantity');
                                                    @endphp
                                                    {{ $totalStock }} units
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.pharmacy.drugs.view', $alternative->id) }}" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No alternatives found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->

<!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>