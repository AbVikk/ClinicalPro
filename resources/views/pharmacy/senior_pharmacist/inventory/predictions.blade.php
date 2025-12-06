<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Senior Pharmacist - Inventory Predictions">
<title>Clinical Pro || Inventory Predictions</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">

@include('pharmacy.senior_pharmacist.sidemenu')

<section class="content">
<div class="block-header">
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12">
            <h2>Inventory Predictions
            <small>AI-Powered Forecasting for Optimal Stock Levels</small>
            </h2>
        </div>
        <div class="col-lg-5 col-md-6 col-sm-12 text-right">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('senior_pharmacist.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                <li class="breadcrumb-item"><a href="{{ route('senior_pharmacist.dashboard') }}">Pharmacy</a></li>
                <li class="breadcrumb-item active">Predictions</li>
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
                    <small>Based on historical prescription data and seasonal trends</small>
                    <ul class="header-dropdown">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
                                <i class="zmdi zmdi-more"></i> 
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="javascript:void(0);" id="refreshPredictions">Refresh Predictions</a></li>
                                <li><a href="javascript:void(0);" id="exportPredictions">Export to CSV</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @if($predictions->isEmpty())
                        <div class="alert alert-info">
                            <h4>No Predictions Available</h4>
                            <p>No inventory predictions have been generated yet. The AI system will automatically generate predictions nightly based on prescription history.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>Drug Name</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Historical Usage</th>
                                        <th>Predicted Demand</th>
                                        <th>Current Stock</th>
                                        <th>Reorder Point</th>
                                        <th>Recommended Order</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($predictions as $prediction)
                                    @php
                                        // Determine stock status class
                                        $stockStatusClass = '';
                                        $stockStatusText = '';
                                        if ($prediction->current_stock <= $prediction->reorder_point) {
                                            $stockStatusClass = 'critical-stock';
                                            $stockStatusText = 'Critical';
                                        } elseif ($prediction->current_stock <= ($prediction->reorder_point * 1.5)) {
                                            $stockStatusClass = 'warning-stock';
                                            $stockStatusText = 'Low';
                                        } else {
                                            $stockStatusClass = 'normal-stock';
                                            $stockStatusText = 'Adequate';
                                        }
                                    @endphp
                                    <tr class="{{ $stockStatusClass }}">
                                        <td>{{ $prediction->drug_name }}</td>
                                        <td>{{ $prediction->month }}</td>
                                        <td>{{ $prediction->year }}</td>
                                        <td>{{ $prediction->historical_usage }}</td>
                                        <td>{{ $prediction->predicted_quantity }}</td>
                                        <td>{{ $prediction->current_stock }}</td>
                                        <td>{{ $prediction->reorder_point }}</td>
                                        <td>{{ $prediction->recommended_order_quantity }}</td>
                                        <td><span class="badge badge-{{ $stockStatusText === 'Critical' ? 'danger' : ($stockStatusText === 'Low' ? 'warning' : 'success') }}">{{ $stockStatusText }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>How It Works</strong></h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="icon-box">
                                <div class="icon-box-icon">
                                    <i class="zmdi zmdi-chart"></i>
                                </div>
                                <div class="icon-box-content">
                                    <h4>Data Analysis</h4>
                                    <p>The AI analyzes historical prescription data, seasonal trends, and expiration dates to forecast demand.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="icon-box">
                                <div class="icon-box-icon">
                                    <i class="zmdi zmdi-balance-wallet"></i>
                                </div>
                                <div class="icon-box-content">
                                    <h4>Cost Optimization</h4>
                                    <p>Minimize waste by predicting optimal stock levels that reduce both stockouts and expired inventory.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="icon-box">
                                <div class="icon-box-icon">
                                    <i class="zmdi zmdi-notifications-active"></i>
                                </div>
                                <div class="icon-box-content">
                                    <h4>Automated Alerts</h4>
                                    <p>Receive actionable recommendations for when and how much to order based on predictive analytics.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<style>
    .prediction-card {
        transition: transform 0.3s ease;
    }
    .prediction-card:hover {
        transform: translateY(-5px);
    }
    .critical-stock {
        background-color: #ffebee !important;
        border-left: 4px solid #f44336;
    }
    .normal-stock {
        background-color: #e8f5e9 !important;
        border-left: 4px solid #4caf50;
    }
    .warning-stock {
        background-color: #fff3e0 !important;
        border-left: 4px solid #ff9800;
    }
</style>

<!-- Scripts -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh Predictions Handler
    document.getElementById('refreshPredictions').addEventListener('click', function(e) {
        e.preventDefault();
        alert('In a production environment, this would trigger the AI analysis to regenerate predictions.');
    });

    // Export Predictions Handler
    document.getElementById('exportPredictions').addEventListener('click', function(e) {
        e.preventDefault();
        alert('In a production environment, this would export the predictions to a CSV file.');
    });
});
</script>
</body>
</html>