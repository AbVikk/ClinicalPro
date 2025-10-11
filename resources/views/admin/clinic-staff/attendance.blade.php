<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>ClinicalPro || Staff Attendance</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Custom Styles for this page -->
<style>
    .info-box-2 {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .info-box-2:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .info-box-2 .icon {
        transition: all 0.3s ease;
    }
    
    .info-box-2:hover .icon {
        transform: scale(1.1);
    }
    
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #007bff;
        color: #007bff;
        background-color: transparent;
    }
    
    .nav-tabs .nav-link:hover {
        background-color: rgba(0,123,255,0.1);
    }
    
    .table thead.thead-dark th {
        background-color: #343a40;
        border-color: #343a40;
    }
    
    .badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 20px;
    }
    
    .btn-outline-primary, .btn-outline-danger {
        border-width: 2px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .header {
        border-bottom: 1px solid #eee;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .attendance-status {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        text-align: center;
        line-height: 20px;
        font-size: 12px;
    }
    
    .status-present {
        background-color: #28a745;
        color: white;
    }
    
    .status-absent {
        background-color: #dc3545;
        color: white;
    }
    
    .status-late {
        background-color: #ffc107;
        color: black;
    }
    
    .status-leave {
        background-color: #17a2b8;
        color: white;
    }
</style>
</head>
<body class="theme-cyan">
<!-- Page Loader -->
@include('admin.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12">
                <h2>Staff Attendance
                <small class="text-muted">Manage staff attendance records</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-7 col-sm-12">
                <button class="btn btn-primary btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button" data-toggle="modal" data-target="#recordAttendanceModal">
                    <i class="zmdi zmdi-plus"></i>
                </button>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="zmdi zmdi-home"></i> ClinicalPro</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Staff</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <!-- Attendance Statistics Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-check"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $presentToday }}</div>
                                <div class="number small">
                                    <span class="text-muted">{{ $presentChange }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Present Today</span>
                            <div class="text-muted small">Out of {{ $totalStaff }} staff members</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-close"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $absentToday }}</div>
                                <div class="number small">
                                    <span class="text-muted">{{ $absentChange }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Absent Today</span>
                            <div class="text-muted small">Unplanned absences</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-calendar-check"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $onLeave }}</div>
                                <div class="number small">
                                    <span class="text-muted">{{ $onLeaveChange }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">On Leave</span>
                            <div class="text-muted small">Approved leave requests</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card info-box-2 hover-zoom-effect shadow-sm border-0 rounded-lg">
                    <div class="body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white" style="width: 50px; height: 50px;">
                                <i class="zmdi zmdi-alarm"></i>
                            </div>
                            <div class="content text-right">
                                <div class="text font-weight-bold" style="font-size: 1.5rem;">{{ $lateArrivals }}</div>
                                <div class="number small">
                                    <span class="text-muted">{{ $lateArrivalsChange }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Late Arrivals</span>
                            <div class="text-muted small">More than 30 minutes late</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Tabs -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="header p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0"><strong>Attendance</strong> Management</h2>
                        </div>
                    </div>
                    <div class="body p-3">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-3" role="tablist" id="attendance-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#daily-attendance" role="tab">
                                    <i class="zmdi zmdi-view-list"></i> Daily Attendance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#calendar-view" role="tab">
                                    <i class="zmdi zmdi-calendar"></i> Calendar View
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#timesheets" role="tab">
                                    <i class="zmdi zmdi-time"></i> Timesheets
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#leave-requests" role="tab">
                                    <i class="zmdi zmdi-airplane"></i> Leave Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#reports" role="tab">
                                    <i class="zmdi zmdi-chart"></i> Reports
                                </a>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="attendance-content">
                            <!-- Daily Attendance Tab -->
                            <div class="tab-pane active" id="daily-attendance" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Staff</th>
                                                <th>Department</th>
                                                <th>Role</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Hours</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Dr. John Smith</td>
                                                <td>Cardiology</td>
                                                <td>Doctor</td>
                                                <td>08:45 AM</td>
                                                <td>05:30 PM</td>
                                                <td>8.75</td>
                                                <td><span class="badge badge-success">Present</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary mr-1">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nurse Jane Doe</td>
                                                <td>Emergency</td>
                                                <td>Nurse</td>
                                                <td>09:15 AM</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td><span class="badge badge-warning">Late</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary mr-1">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Dr. Robert Johnson</td>
                                                <td>Orthopedics</td>
                                                <td>Doctor</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td><span class="badge badge-danger">Absent</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary mr-1">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Calendar View Tab -->
                            <div class="tab-pane" id="calendar-view" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4>Monthly Attendance Calendar</h4>
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary mr-1" id="prevMonth">
                                            <i class="zmdi zmdi-chevron-left"></i>
                                        </button>
                                        <span id="currentMonthDisplay">{{ $currentMonthName ?? now()->format('F Y') }}</span>
                                        <button class="btn btn-sm btn-outline-secondary ml-1" id="nextMonth">
                                            <i class="zmdi zmdi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered m-b-0" id="calendarTable">
                                        <thead>
                                            <tr>
                                                <th>Staff</th>
                                                @for($day = 1; $day <= 31; $day++)
                                                    <th>{{ $day }}</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($staffMembers) && count($staffMembers) > 0)
                                                @foreach($staffMembers as $staff)
                                                    <tr>
                                                        <td>{{ $staff->name }}</td>
                                                        @for($day = 1; $day <= 31; $day++)
                                                            <td>
                                                                @php
                                                                    // Check if this staff member has attendance for this day
                                                                    $hasAttendance = false;
                                                                    if (isset($calendarData[$staff->id]['attendance'][$day])) {
                                                                        $hasAttendance = true;
                                                                    }
                                                                @endphp
                                                                @if($hasAttendance)
                                                                    <span class="attendance-status status-present">✓</span>
                                                                @else
                                                                    <span class="attendance-status status-absent">✗</span>
                                                                @endif
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="32" class="text-center">No staff members found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Timesheets Tab -->
                            <div class="tab-pane" id="timesheets" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4>Staff Timesheets</h4>
                                    <button class="btn btn-primary">
                                        <i class="zmdi zmdi-plus"></i> Add Timesheet
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Staff</th>
                                                <th>Department</th>
                                                <th>Week Starting</th>
                                                <th>Total Hours</th>
                                                <th>Overtime</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($timesheetData) && count($timesheetData) > 0)
                                                @foreach($timesheetData as $timesheet)
                                                    <tr>
                                                        <td>{{ $timesheet['staff'] }}</td>
                                                        <td>{{ $timesheet['department'] }}</td>
                                                        <td>{{ $timesheet['week_starting'] }}</td>
                                                        <td>{{ $timesheet['total_hours'] }}</td>
                                                        <td>{{ $timesheet['overtime'] }}</td>
                                                        <td><span class="badge badge-{{ $timesheet['status_class'] }}">{{ $timesheet['status'] }}</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary mr-1">
                                                                <i class="zmdi zmdi-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger">
                                                                <i class="zmdi zmdi-delete"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No timesheet records found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Leave Requests Tab -->
                            <div class="tab-pane" id="leave-requests" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4>Leave Requests</h4>
                                    <button class="btn btn-primary">
                                        <i class="zmdi zmdi-plus"></i> New Request
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Staff</th>
                                                <th>Department</th>
                                                <th>Leave Type</th>
                                                <th>Duration</th>
                                                <th>Dates</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($leaveRequestData) && count($leaveRequestData) > 0)
                                                @foreach($leaveRequestData as $leave)
                                                    <tr>
                                                        <td>{{ $leave['staff'] }}</td>
                                                        <td>{{ $leave['department'] }}</td>
                                                        <td>{{ $leave['leave_type'] }}</td>
                                                        <td>{{ $leave['duration'] }}</td>
                                                        <td>{{ $leave['dates'] }}</td>
                                                        <td><span class="badge badge-{{ $leave['status_class'] }}">{{ $leave['status'] }}</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary mr-1">
                                                                <i class="zmdi zmdi-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger">
                                                                <i class="zmdi zmdi-delete"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No leave requests found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Reports Tab -->
                            <div class="tab-pane" id="reports" role="tabpanel">
                                <h4>Attendance Reports</h4>
                                
                                <!-- Report Filters -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Month</label>
                                            <select class="form-control">
                                                <option>This Month</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <select class="form-control">
                                                <option>All Departments</option>
                                                <option>Medical</option>
                                                <option>Nursing</option>
                                                <option>Administration</option>
                                                <option>Support</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Report Statistics -->
                                <div class="row clearfix">
                                    <!-- Attendance Summary -->
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2><strong>Attendance</strong> Summary</h2>
                                            </div>
                                            <div class="body">
                                                @if(isset($reportData['attendanceSummary']))
                                                    <ul class="list-unstyled">
                                                        <li>Present: {{ $reportData['attendanceSummary']['present'] }}%</li>
                                                        <li>Absent: {{ $reportData['attendanceSummary']['absent'] }}%</li>
                                                        <li>On Leave: {{ $reportData['attendanceSummary']['onLeave'] }}%</li>
                                                        <li>Late: {{ $reportData['attendanceSummary']['late'] }}%</li>
                                                    </ul>
                                                @else
                                                    <p class="text-center">No attendance summary data available</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Department Breakdown -->
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2><strong>Department</strong> Breakdown</h2>
                                            </div>
                                            <div class="body">
                                                @if(isset($reportData['departmentBreakdown']))
                                                    <ul class="list-unstyled">
                                                        @foreach($reportData['departmentBreakdown'] as $department => $percentage)
                                                            <li>{{ $department }}: {{ $percentage }}% present</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-center">No department breakdown data available</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Leave Statistics -->
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2><strong>Leave</strong> Statistics</h2>
                                            </div>
                                            <div class="body">
                                                @if(isset($reportData['leaveStatistics']))
                                                    <ul class="list-unstyled">
                                                        @foreach($reportData['leaveStatistics'] as $leaveType => $percentage)
                                                            <li>{{ $leaveType }}: {{ $percentage }}%</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-center">No leave statistics data available</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Monthly Attendance Trends -->
                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2><strong>Monthly</strong> Attendance Trends</h2>
                                            </div>
                                            <div class="body">
                                                <div id="attendance-trend-chart" style="height: 300px;">
                                                    <!-- Attendance trend chart would be displayed here -->
                                                    <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                                        <p class="text-muted">Attendance trend chart would be displayed here with data from the database</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

<!-- Record Attendance Modal -->
<div class="modal fade" id="recordAttendanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title">Record Attendance</h4>
            </div>
            <div class="modal-body">
                <p>Record check-in or check-out time for staff members.</p>
                
                <form id="attendanceForm">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Staff Member</label>
                                <select class="form-control" name="user_id" required>
                                    <option value="">Select Staff Member</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Record Type</label>
                                <select class="form-control" name="record_type" required>
                                    <option value="">Select Record Type</option>
                                    <option value="check-in">Check In</option>
                                    <option value="check-out">Check Out</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Time</label>
                                <input type="datetime-local" class="form-control" name="recorded_at" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Notes (Optional)</label>
                                <textarea class="form-control" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round waves-effect" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-primary btn-round waves-effect" id="saveAttendanceBtn">SAVE RECORD</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
// Initialize Bootstrap tabs properly to prevent conflicts with sidemenu
document.addEventListener('DOMContentLoaded', function() {
    // Handle tab switching without interfering with sidemenu
    document.addEventListener('click', function(e) {
        // Check if the clicked element is a tab link within our attendance section
        if (e.target.matches('#attendance-tabs .nav-link')) {
            e.preventDefault();
            
            // Get the target tab pane
            const target = e.target.getAttribute('href');
            
            // Hide all tab panes within the attendance section
            document.querySelectorAll('#attendance-content .tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Remove active class from all tab links within the attendance section
            document.querySelectorAll('#attendance-tabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Show the target tab pane
            const targetPane = document.querySelector(target);
            if (targetPane) {
                targetPane.classList.add('active');
            }
            
            // Add active class to the clicked link
            e.target.classList.add('active');
        }
    });
    
    // Handle month navigation in calendar view
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const monthDisplay = document.getElementById('currentMonthDisplay');
    const calendarTable = document.getElementById('calendarTable');
    
    // Track current month and year (starting with current date)
    let currentMonth = {{ now()->month }} - 1; // JavaScript months are 0-indexed
    let currentYear = {{ now()->year }};
    
    // Function to update calendar data via AJAX
    function updateCalendarData() {
        const monthString = currentYear + '-' + String(currentMonth + 1).padStart(2, '0');
        
        // Use Laravel's route helper to generate the URL
        const url = '/admin/clinic-staff/attendance/calendar-data?month=' + monthString;
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update month display
                monthDisplay.textContent = data.monthName;
                
                // Update calendar table
                updateCalendarTable(data.calendarData);
            }
        })
        .catch(error => {
            console.error('Error fetching calendar data:', error);
        });
    }
    
    // Function to update the calendar table with new data
    function updateCalendarTable(calendarData) {
        // Clear existing rows except header
        const tbody = calendarTable.querySelector('tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        // Add new rows
        let hasData = false;
        for (const userId in calendarData) {
            hasData = true;
            const staffData = calendarData[userId];
            const row = document.createElement('tr');
            
            // Staff name cell
            const nameCell = document.createElement('td');
            nameCell.textContent = staffData.name;
            row.appendChild(nameCell);
            
            // Attendance cells for each day
            for (let day = 1; day <= 31; day++) {
                const dayCell = document.createElement('td');
                const statusSpan = document.createElement('span');
                statusSpan.classList.add('attendance-status');
                
                if (staffData.attendance && staffData.attendance[day]) {
                    statusSpan.classList.add('status-present');
                    statusSpan.textContent = '✓';
                } else {
                    statusSpan.classList.add('status-absent');
                    statusSpan.textContent = '✗';
                }
                
                dayCell.appendChild(statusSpan);
                row.appendChild(dayCell);
            }
            
            tbody.appendChild(row);
        }
        
        // If no data, show message
        if (!hasData) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 32;
            cell.className = 'text-center';
            cell.textContent = 'No staff members found';
            row.appendChild(cell);
            tbody.appendChild(row);
        }
    }
    
    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', function() {
            // Move to previous month
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            
            // Update calendar data
            updateCalendarData();
        });
    }
    
    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', function() {
            // Move to next month
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            
            // Update calendar data
            updateCalendarData();
        });
    }
    
    // Handle attendance form submission
    const saveAttendanceBtn = document.getElementById('saveAttendanceBtn');
    if (saveAttendanceBtn) {
        saveAttendanceBtn.addEventListener('click', function() {
            const form = document.getElementById('attendanceForm');
            if (!form) return;
            
            const formData = new FormData(form);
            
            // In a real application, you would send this data to the server
            // For now, we'll just show an alert
            alert('Attendance record would be saved in a real application');
            
            // Close the modal
            const modal = document.getElementById('recordAttendanceModal');
            if (modal) {
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }
            }
            
            // Reset the form
            form.reset();
        });
    }
});
</script>
</body>
</html>