@extends('admin.layouts.app')

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-7 col-md-5 col-sm-12">
            <h2>Service Test
            <small class="text-muted">Testing service functionality</small>
            </h2>
        </div>
        <div class="col-lg-5 col-md-7 col-sm-12">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                <li class="breadcrumb-item active">Service Test</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Service</strong> Test</h2>
                </div>
                <div class="body">
                    <h3>Available Services</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service Name</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>{{ $service->service_name }}</td>
                                    <td>{{ $service->service_type }}</td>
                                    <td>{{ $service->formatted_price }}</td>
                                    <td>
                                        @if($service->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No services found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <h3>Test Payment Initialization</h3>
                    <form id="payment-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_select">Select Service</label>
                                    <select id="service_select" class="form-control">
                                        <option value="">- Select Service -</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price_amount }}">
                                                {{ $service->service_name }} ({{ $service->formatted_price }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_email">Patient Email</label>
                                    <input type="email" id="patient_email" class="form-control" placeholder="Enter patient email">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="patient_id">Patient ID</label>
                            <input type="text" id="patient_id" class="form-control" placeholder="Enter patient ID">
                        </div>
                        
                        <button type="button" id="initialize-payment" class="btn btn-primary">Initialize Payment</button>
                    </form>
                    
                    <div id="payment-result" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#initialize-payment').on('click', function() {
        var serviceId = $('#service_select').val();
        var patientEmail = $('#patient_email').val();
        var patientId = $('#patient_id').val();
        
        if (!serviceId || !patientEmail || !patientId) {
            alert('Please fill in all fields');
            return;
        }
        
        $.ajax({
            url: '{{ route('admin.appointment.payment.initialize') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                service_id: serviceId,
                patient_id: patientId,
                email: patientEmail
            },
            success: function(response) {
                if (response.data && response.data.authorization_url) {
                    $('#payment-result').html('<div class="alert alert-success">Payment initialized successfully! <a href="' + response.data.authorization_url + '" target="_blank" class="btn btn-success">Proceed to Payment</a></div>');
                } else {
                    $('#payment-result').html('<div class="alert alert-warning">Payment initialized but no authorization URL received.</div>');
                }
            },
            error: function(xhr) {
                var errorMessage = 'Failed to initialize payment.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage += ' ' + xhr.responseJSON.error;
                }
                $('#payment-result').html('<div class="alert alert-danger">' + errorMessage + '</div>');
            }
        });
    });
});
</script>
@endsection