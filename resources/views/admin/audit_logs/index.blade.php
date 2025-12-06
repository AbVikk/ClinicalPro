<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Clinical Pro || Security Audit Logs</title>
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
<style>
    .badge-action-created { background-color: #28a745; color: white; }
    .badge-action-updated { background-color: #ffc107; color: black; }
    .badge-action-deleted { background-color: #dc3545; color: white; }
    .log-details { font-family: monospace; font-size: 11px; color: #555; }
</style>
</head>
<body class="theme-cyan">

@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Security Audit Logs
                <small>Track all system activity</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Clinical Pro</a></li>
                    <li class="breadcrumb-item active">Audit Logs</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>System</strong> Activity Log</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                        <th>Target (Model)</th>
                                        <th>IP Address</th>
                                        <th>Changes / Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                        <td>
                                            @if($log->user)
                                                <strong>{{ $log->user->name }}</strong>
                                            @else
                                                <span class="text-muted">System / Deleted User</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <span class="badge badge-info">{{ ucfirst($log->user->role) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = 'badge-secondary';
                                                if(stripos($log->action, 'Created') !== false) $badgeClass = 'badge-action-created';
                                                if(stripos($log->action, 'Updated') !== false) $badgeClass = 'badge-action-updated';
                                                if(stripos($log->action, 'Deleted') !== false) $badgeClass = 'badge-action-deleted';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $log->action }}</span>
                                        </td>
                                        <td>
                                            <small>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</small>
                                        </td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td class="log-details" title="{{ $log->details }}">
                                            {{ Str::limit($log->details, 50) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $logs->links() }}
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