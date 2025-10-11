@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Pharmacy Dashboard</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pharmacy.dashboard') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="mb-0">{{ \App\Models\Drug::count() }}</h4>
                            <p class="text-muted">Total Drugs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="mb-0">{{ \App\Models\DrugBatch::count() }}</h4>
                            <p class="text-muted">Total Batches</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="mb-0">{{ \App\Models\StockTransfer::where('status', 'requested')->count() }}</h4>
                            <p class="text-muted">Pending Requests</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="body">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="mb-0">{{ \App\Models\Prescription::where('status', 'active')->count() }}</h4>
                            <p class="text-muted">Active Prescriptions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Recent Activities</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Received new stock batch</td>
                                    <td>John Doe (Primary Pharmacist)</td>
                                    <td>2025-10-02 14:30</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Approved stock transfer</td>
                                    <td>Jane Smith (Primary Pharmacist)</td>
                                    <td>2025-10-02 13:45</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Requested stock transfer</td>
                                    <td>Robert Johnson (Senior Pharmacist)</td>
                                    <td>2025-10-02 12:15</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Processed pharmacy sale</td>
                                    <td>Mary Williams (Clinic Pharmacist)</td>
                                    <td>2025-10-02 11:20</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection