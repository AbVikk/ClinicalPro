@extends('layouts.template')

@section('title', 'Donor Dashboard')
@section('description', 'Donor dashboard for managing donations and contributions')

@section('css')
<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('template/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
@endsection

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2><i class="zmdi zmdi-view-dashboard"></i> <span>Donor Dashboard</span></h2>
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Donor Stats -->
    <div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"></div>
                    </div>
                    <span class="text-muted">Total Donations</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="15" data-speed="1000" data-fresh-interval="700">15</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%"></div>
                    </div>
                    <span class="text-muted">Total Amount</span>
                    <h4 class="m-t-10">$<span class="number count-to" data-from="0" data-to="4500" data-speed="1000" data-fresh-interval="700">4500</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                    </div>
                    <span class="text-muted">Active Projects</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="8" data-speed="1000" data-fresh-interval="700">8</span></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card widget-stat text-center">
                <div class="body">
                    <div class="progress m-b-10">
                        <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%"></div>
                    </div>
                    <span class="text-muted">Impact Lives</span>
                    <h4 class="m-t-10"><span class="number count-to" data-from="0" data-to="120" data-speed="1000" data-fresh-interval="700">120</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Recent</strong> Donations</h2>
                    <ul class="header-dropdown">
                        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ url('/donor/donations') }}">View All</a></li>
                                <li><a href="{{ url('/donor/donations/create') }}">Make New Donation</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Children's Hospital Equipment</td>
                                    <td>Oct 15, 2025</td>
                                    <td>$500.00</td>
                                    <td>Credit Card</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Medical Research Fund</td>
                                    <td>Oct 10, 2025</td>
                                    <td>$1000.00</td>
                                    <td>Bank Transfer</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emergency Relief Fund</td>
                                    <td>Oct 5, 2025</td>
                                    <td>$750.00</td>
                                    <td>PayPal</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation Projects -->
    <div class="row clearfix">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Active</strong> Projects</h2>
                </div>
                <div class="body">
                    <ul class="list-unstyled activity">
                        <li>
                            <div class="media">
                                <div class="media-left">
                                    <div class="avatar">
                                        <img src="{{ asset('template/images/image-gallery/1.jpg') }}" alt="Project">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="m-t-0">New MRI Machine</h6>
                                    <p>$15,000 raised of $50,000 goal</p>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%"></div>
                                    </div>
                                    <small class="text-muted">30% funded</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <div class="media-left">
                                    <div class="avatar">
                                        <img src="{{ asset('template/images/image-gallery/2.jpg') }}" alt="Project">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="m-t-0">Pediatric Ward Renovation</h6>
                                    <p>$8,500 raised of $25,000 goal</p>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 34%"></div>
                                    </div>
                                    <small class="text-muted">34% funded</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <div class="media-left">
                                    <div class="avatar">
                                        <img src="{{ asset('template/images/image-gallery/3.jpg') }}" alt="Project">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="m-t-0">Cancer Research Grant</h6>
                                    <p>$12,000 raised of $30,000 goal</p>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                                    </div>
                                    <small class="text-muted">40% funded</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Make</strong> Donation</h2>
                </div>
                <div class="body">
                    <form>
                        <div class="form-group">
                            <label for="project">Select Project</label>
                            <select class="form-control show-tick" id="project">
                                <option value="">-- Select Project --</option>
                                <option value="mri">New MRI Machine</option>
                                <option value="pediatric">Pediatric Ward Renovation</option>
                                <option value="cancer">Cancer Research Grant</option>
                                <option value="emergency">Emergency Relief Fund</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount ($)</label>
                            <input type="number" class="form-control" id="amount" placeholder="Enter donation amount">
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select class="form-control show-tick" id="payment_method">
                                <option value="">-- Select Payment Method --</option>
                                <option value="credit">Credit Card</option>
                                <option value="debit">Debit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Donate Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Additional Scripts for this page -->
@endsection