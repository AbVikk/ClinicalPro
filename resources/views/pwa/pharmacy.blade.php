@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Pharmacy</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('patient.dashboard') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item active">Pharmacy</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Your Active Prescriptions</h2>
                </div>
                <div class="body">
                    @if($activePrescriptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Prescription ID</th>
                                    <th>Doctor</th>
                                    <th>Drugs</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activePrescriptions as $prescription)
                                <tr>
                                    <td>{{ $prescription->id }}</td>
                    <td>{{ $prescription->doctor->name }}</td>
                                    <td>
                                        <ul>
                                            @foreach($prescription->items as $item)
                                            <li>{{ $item->drug->name }} ({{ $item->drug->strength_mg }}) - Qty: {{ $item->quantity }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <button class="btn btn-success fill-prescription" data-prescription-id="{{ $prescription->id }}">
                                            Fill Prescription
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p>You have no active prescriptions.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Over-the-Counter Drugs</h2>
                    <div class="form-group">
                        <input type="text" class="form-control" id="search-drugs" placeholder="Search OTC drugs...">
                    </div>
                </div>
                <div class="body">
                    <div class="row" id="otc-drugs-container">
                        @foreach($otcDrugs as $drug)
                        <div class="col-lg-3 col-md-4 col-sm-6 drug-item" data-name="{{ strtolower($drug->name) }}" data-category="{{ strtolower($drug->category) }}">
                            <div class="card">
                                <div class="body text-center">
                                    <h6>{{ $drug->name }}</h6>
                                    <p>{{ $drug->category }}</p>
                                    <p>{{ $drug->strength_mg }}</p>
                                    <p class="text-success">${{ $drug->unit_price }}</p>
                                    <button class="btn btn-primary buy-drug" data-drug-id="{{ $drug->id }}">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality for OTC drugs
    document.getElementById('search-drugs').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const drugItems = document.querySelectorAll('.drug-item');
        
        drugItems.forEach(item => {
            const name = item.getAttribute('data-name');
            const category = item.getAttribute('data-category');
            
            if (name.includes(searchTerm) || category.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Fill prescription functionality
    document.querySelectorAll('.fill-prescription').forEach(button => {
        button.addEventListener('click', function() {
            const prescriptionId = this.getAttribute('data-prescription-id');
            alert('Prescription #' + prescriptionId + ' would be filled. In a real application, this would redirect to a payment page.');
        });
    });

    // Buy OTC drug functionality
    document.querySelectorAll('.buy-drug').forEach(button => {
        button.addEventListener('click', function() {
            const drugId = this.getAttribute('data-drug-id');
            alert('Drug #' + drugId + ' would be added to cart. In a real application, this would redirect to a payment page.');
        });
    });
</script>
@endsection