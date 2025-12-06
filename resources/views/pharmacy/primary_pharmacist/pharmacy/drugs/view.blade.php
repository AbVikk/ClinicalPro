<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>:: Clinical Pro :: Drug Details</title>
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
@include('pharmacy.primary_pharmacist.sidemenu')
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12">
                <h2>
                    <a href="{{ route('primary_pharmacist.pharmacy.drugs.all') }}" class="btn btn-sm btn-primary"><i class="zmdi zmdi-arrow-back"></i></a>
                    {{ $drug->name }} {{ $drug->strength_mg }}
                    
                    @php
                        // Fix: Check GLOBAL stock (sum of all batches)
                        $totalStock = $drug->batches->sum('received_quantity');
                        $badge = $totalStock <= 0 ? 'badge-danger' : ($totalStock < 50 ? 'badge-warning' : 'badge-success');
                        $text = $totalStock <= 0 ? 'Out of Stock' : ($totalStock < 50 ? 'Low Stock' : 'In Stock');
                    @endphp
                    <span class="badge {{ $badge }} ml-2">{{ $text }}</span>

                    <a href="{{ route('primary_pharmacist.pharmacy.drugs.edit', $drug->id) }}" class="btn btn-sm btn-warning float-right"><i class="zmdi zmdi-edit"></i> Edit</a>
                </h2>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                
                <div class="card">
                    <div class="header">
                        <h2><strong>Medicine</strong> Image</h2>
                    </div>
                    <div class="body text-center">
                        @if(!empty($drug->details['medicine_image']))
                            <img src="{{ asset('storage/' . $drug->details['medicine_image']) }}" alt="Medicine Image" class="img-fluid rounded mb-3" style="max-height: 200px;">
                        @else
                            <div class="alert alert-secondary">No Medicine Image Uploaded</div>
                        @endif

                        @if(!empty($drug->details['package_image']))
                            <hr>
                            <p class="text-muted">Package Image</p>
                            <img src="{{ asset('storage/' . $drug->details['package_image']) }}" alt="Package Image" class="img-fluid rounded" style="max-height: 150px;">
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="header"><h2><strong>Details</strong></h2></div>
                    <div class="body">
                        <table class="table table-hover">
                            <tbody>
                                <tr><td><strong>ID</strong></td><td>MED{{ str_pad($drug->id, 3, '0', STR_PAD_LEFT) }}</td></tr>
                                <tr><td><strong>Generic</strong></td><td>{{ $drug->details['generic_name'] ?? 'N/A' }}</td></tr>
                                <tr><td><strong>Category</strong></td><td>{{ $drug->category }}</td></tr>
                                <tr><td><strong>Type</strong></td><td>{{ $drug->is_controlled ? 'Controlled' : 'OTC' }}</td></tr>
                                <tr><td><strong>Manufacturer</strong></td><td>{{ $drug->details['manufacturer'] ?? 'N/A' }}</td></tr>
                                <tr><td><strong>Global Stock</strong></td><td><strong>{{ $totalStock }}</strong></td></tr>
                                <tr><td><strong>Price</strong></td><td>₦{{ number_format($drug->unit_price, 2) }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header"><h2><strong>Clinical</strong> Information</h2></div>
                    <div class="body">
                        <h6>Description</h6>
                        <p>{{ $drug->details['description'] ?? 'N/A' }}</p>
                        
                        <h6>Dosage</h6>
                        <p>{{ $drug->details['dosage'] ?? 'N/A' }}</p>
                        
                        <h6>Side Effects</h6>
                        <p>{{ $drug->details['side_effects'] ?? 'N/A' }}</p>
                        
                        <h6>Storage</h6>
                        <p>
                            {{-- FIX: Handle Array vs String for Storage Conditions --}}
                            @if(isset($drug->details['storage_conditions']))
                                @if(is_array($drug->details['storage_conditions']))
                                    {{ implode(', ', $drug->details['storage_conditions']) }}
                                @else
                                    {{ $drug->details['storage_conditions'] }}
                                @endif
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="body">
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#batches">Batch History</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#alternatives">Alternatives</a></li>
                        </ul>
                        
                        <div class="tab-content m-t-10">
                            <div class="tab-pane active" id="batches">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Batch #</th>
                                                <th>Qty</th>
                                                <th>Expiry</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($drug->batches as $batch)
                                            <tr>
                                                <td>{{ $batch->batch_uuid ?? 'N/A' }}</td>
                                                <td>{{ $batch->received_quantity }}</td>
                                                <td>{{ $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : 'N/A' }}</td>
                                                <td>
                                                    @if($batch->expiry_date && $batch->expiry_date->isPast())
                                                        <span class="badge badge-danger">Expired</span>
                                                    @else
                                                        <span class="badge badge-success">Valid</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="4" class="text-center">No batches found.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="alternatives">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead><tr><th>Name</th><th>Action</th></tr></thead>
                                        <tbody>
                                            @forelse($alternatives as $alt)
                                            <tr>
                                                <td>{{ $alt->name }} {{ $alt->strength_mg }}</td>
                                                <td><a href="{{ route('primary_pharmacist.pharmacy.drugs.view', $alt->id) }}" class="btn btn-sm btn-primary">View</a></td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="2">No alternatives found.</td></tr>
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
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>