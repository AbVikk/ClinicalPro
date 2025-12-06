@extends('layouts.admin')

@section('title', 'Inventory Predictions')

@section('content')
<div class="block-header">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                <li class="active">Inventory Predictions</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>AI-Powered</strong> Inventory Predictions</h2>
                    <small>Based on prescription history and seasonal trends</small>
                    <ul class="header-dropdown">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="zmdi zmdi-more"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="javascript:void(0);" onclick="refreshPredictions()">Refresh Predictions</a></li>
                                <li><a href="javascript:void(0);">Export Report</a></li>
                            </ul>
                        </li>
                        <li class="remove">
                            <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @if($groupedPredictions->isEmpty())
                        <div class="alert alert-info">
                            <p>No inventory predictions available. The system generates predictions nightly based on prescription history.</p>
                            <p><button class="btn btn-primary" onclick="generatePredictions()">Generate Predictions Now</button></p>
                        </div>
                    @else
                        <div class="table-responsive">
                            @foreach($groupedPredictions as $drugName => $predictions)
                                <h4>{{ $drugName }}</h4>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Predicted Quantity</th>
                                            <th>Reorder Point</th>
                                            <th>Recommended Order</th>
                                            <th>Risk Factors</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($predictions as $prediction)
                                            <tr>
                                                <td>{{ $prediction->month }}</td>
                                                <td>{{ $prediction->year }}</td>
                                                <td>{{ $prediction->predicted_quantity }}</td>
                                                <td>{{ $prediction->reorder_point }}</td>
                                                <td>{{ $prediction->recommended_order_quantity }}</td>
                                                <td>
                                                    @if(!empty($prediction->risk_factors))
                                                        <ul class="list-unstyled">
                                                            @foreach($prediction->risk_factors as $factor)
                                                                <li><span class="badge badge-warning">{{ $factor }}</span></li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function refreshPredictions() {
        // In a real implementation, this would trigger the job
        alert('In a real implementation, this would refresh the predictions.');
    }
    
    function generatePredictions() {
        // In a real implementation, this would trigger the job
        alert('In a real implementation, this would generate new predictions.');
    }
</script>
@endsection