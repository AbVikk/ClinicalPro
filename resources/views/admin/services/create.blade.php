@extends('admin.layouts.app')

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-7 col-md-5 col-sm-12">
            <h2>Add New Service
            <small class="text-muted">Create a new hospital service with pricing</small>
            </h2>
        </div>
        <div class="col-lg-5 col-md-7 col-sm-12">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Add Service Form -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Add</strong> Service</h2>
                </div>
                <div class="body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.services.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_name">Service Name</label>
                                    <input type="text" class="form-control" id="service_name" name="service_name" value="{{ old('service_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_type">Service Type</label>
                                    <select class="form-control" id="service_type" name="service_type" required>
                                        <option value="">Select Type</option>
                                        <option value="Consultation" {{ old('service_type') == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                                        <option value="Treatment" {{ old('service_type') == 'Treatment' ? 'selected' : '' }}>Treatment</option>
                                        <option value="Diagnostic" {{ old('service_type') == 'Diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                                        <option value="Procedure" {{ old('service_type') == 'Procedure' ? 'selected' : '' }}>Procedure</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price_amount">Price Amount (₦)</label>
                                    <input type="number" step="0.01" class="form-control" id="price_amount" name="price_amount" value="{{ old('price_amount') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price_currency">Currency</label>
                                    <select class="form-control" id="price_currency" name="price_currency" required>
                                        <option value="NGN" selected>NGN</option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create Service</button>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection