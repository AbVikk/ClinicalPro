@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Prescribe Medication</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item active">Prescribe</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Create New Prescription</h2>
                </div>
                <div class="body">
                    <form id="prescription-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_id">Patient</label>
                                    <select class="form-control" id="patient_id" name="patient_id" required>
                                        <option value="">Select a patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->user_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="consultation_id">Consultation (Optional)</label>
                                    <select class="form-control" id="consultation_id" name="consultation_id">
                                        <option value="">Select a consultation</option>
                                        @foreach($consultations as $consultation)
                                            <option value="{{ $consultation->id }}">
                                                Consultation #{{ $consultation->id }} - 
                                                {{ $consultation->service_type }} - 
                                                {{ $consultation->start_time->format('Y-m-d H:i') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes (Optional)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="header">
                            <h2>Prescription Items</h2>
                            <button type="button" class="btn btn-success" id="add-item">Add Item</button>
                        </div>
                        
                        <div id="prescription-items">
                            <div class="prescription-item row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Drug</label>
                                        <select class="form-control drug-select" name="items[0][drug_id]" required>
                                            <option value="">Select a drug</option>
                                            @foreach($drugs as $drug)
                                                <option value="{{ $drug->id }}">
                                                    {{ $drug->name }} ({{ $drug->strength_mg }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" class="form-control" name="items[0][quantity]" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Dosage Instructions</label>
                                        <input type="text" class="form-control" name="items[0][dosage_instructions]" placeholder="e.g., Take 1 tablet twice daily" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Issue Prescription</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let itemIndex = 1;
    
    document.getElementById('add-item').addEventListener('click', function() {
        const itemContainer = document.getElementById('prescription-items');
        const newItem = document.createElement('div');
        newItem.className = 'prescription-item row';
        newItem.innerHTML = `
            <div class="col-md-4">
                <div class="form-group">
                    <label>Drug</label>
                    <select class="form-control drug-select" name="items[${itemIndex}][drug_id]" required>
                        <option value="">Select a drug</option>
                        @foreach($drugs as $drug)
                            <option value="{{ $drug->id }}">
                                {{ $drug->name }} ({{ $drug->strength_mg }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" class="form-control" name="items[${itemIndex}][quantity]" min="1" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Dosage Instructions</label>
                    <input type="text" class="form-control" name="items[${itemIndex}][dosage_instructions]" placeholder="e.g., Take 1 tablet twice daily" required>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
        `;
        itemContainer.appendChild(newItem);
        itemIndex++;
        
        // Add event listener to the remove button
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
        });
    });
    
    // Add event listener to existing remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.prescription-item').remove();
        });
    });
    
    document.getElementById('prescription-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("doctor.consultations.prescribe") }}', {
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
                alert('Prescription issued successfully!');
                // Reset form
                this.reset();
                // Remove all items except the first one
                const items = document.querySelectorAll('.prescription-item');
                for (let i = 1; i < items.length; i++) {
                    items[i].remove();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while issuing the prescription.');
        });
    });
</script>
@endsection