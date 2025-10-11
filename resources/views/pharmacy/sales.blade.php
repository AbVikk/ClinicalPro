@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Pharmacy Sales</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pharmacy.dashboard') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item active">Sales</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Process Sale</h2>
                </div>
                <div class="body">
                    <form id="sale-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="drug_id">Drug</label>
                                    <select class="form-control" id="drug_id" name="drug_id" required>
                                        <option value="">Select a drug</option>
                                        @foreach($drugs as $drug)
                                            <option value="{{ $drug->id }}" data-controlled="{{ $drug->is_controlled ? 'true' : 'false' }}">
                                                {{ $drug->name }} ({{ $drug->strength_mg }}) - 
                                                Price: ${{ $drug->unit_price }}
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
                        <div class="row" id="prescription-section" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prescription_id">Prescription</label>
                                    <select class="form-control" id="prescription_id" name="prescription_id">
                                        <option value="">Select a prescription</option>
                                        @foreach($prescriptions as $prescription)
                                            <option value="{{ $prescription->id }}">
                                                Prescription #{{ $prescription->id }} - 
                                                Doctor: {{ $prescription->doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_id">Patient (Optional)</label>
                                    <select class="form-control" id="patient_id" name="patient_id">
                                        <option value="">Select a patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->user_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Process Sale</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Recent Sales</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Drug</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Patient</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->prescription->items->first()->drug->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->prescription->items->first()->quantity ?? 'N/A' }}</td>
                                    <td>${{ $sale->total_amount }}</td>
                                    <td>{{ $sale->patient->name }}</td>
                                    <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
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

<script>
    document.getElementById('drug_id').addEventListener('change', function() {
        const isControlled = this.options[this.selectedIndex].getAttribute('data-controlled') === 'true';
        const prescriptionSection = document.getElementById('prescription-section');
        
        if (isControlled) {
            prescriptionSection.style.display = 'block';
        } else {
            prescriptionSection.style.display = 'none';
        }
    });

    document.getElementById('sale-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.clinic.sell") }}', {
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
                alert('Sale processed successfully!');
                // Reset form
                this.reset();
                // Hide prescription section if it was visible
                document.getElementById('prescription-section').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the sale.');
        });
    });
</script>
@endsection