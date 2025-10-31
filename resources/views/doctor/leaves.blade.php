<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor leave management">

<title>ClinicalPro || Leaves</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Additional CSS for this page -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

<!-- Include Doctor Sidemenu -->
@include('doctor.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2><i class="zmdi zmdi-airline-seat-recline-normal"></i> <span>Leave Management</span></h2>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Leaves</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="input-group" style="width: 300px; margin-right: 15px;">
                                    <input type="text" class="form-control" placeholder="Search..." id="leaveSearch">
                                    <span class="input-group-addon">
                                        <i class="zmdi zmdi-search"></i>
                                    </span>
                                </div>
                                <div class="form-group mb-0">
                                    <select class="form-control" id="sortLeaves">
                                        <option value="recent">Recent First</option>
                                        <option value="old">Old First</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLeaveModal">
                                <i class="zmdi zmdi-plus"></i> Add New Leave
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Leave Type</th>
                                        <th>Days</th>
                                        <th>Applied On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="leavesTableBody">
                                    @forelse($leaves as $leave)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                        <td>{{ $leave->leave_type }}</td>
                                        <td>
                                            @php
                                                $startDate = \Carbon\Carbon::parse($leave->start_date);
                                                $endDate = \Carbon\Carbon::parse($leave->end_date);
                                                $days = $startDate->diffInDays($endDate) + 1;
                                            @endphp
                                            {{ $days }} day{{ $days > 1 ? 's' : '' }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y') }}</td>
                                        <td>
                                            @if($leave->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($leave->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($leave->status == 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif($leave->status == 'cancelled')
                                                <span class="badge badge-secondary">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item edit-leave" href="javascript:void(0);" data-leave="{{ json_encode($leave) }}">Edit</a>
                                                    @if($leave->status == 'pending')
                                                    <a class="dropdown-item cancel-leave" href="javascript:void(0);" data-id="{{ $leave->id }}">Cancel</a>
                                                    @endif
                                                    <a class="dropdown-item delete-leave" href="javascript:void(0);" data-id="{{ $leave->id }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No leave requests found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Leave Modal -->
<div class="modal fade" id="addLeaveModal" tabindex="-1" role="dialog" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLeaveModalLabel">Add New Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addLeaveForm">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Leave Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_type" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No of Days</label>
                                <input type="text" class="form-control" id="addNoOfDays" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" id="addStartDate" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" id="addEndDate" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason</label>
                                <textarea class="form-control" name="reason" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveLeaveBtn">Add New Leave</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Leave Modal -->
<div class="modal fade" id="editLeaveModal" tabindex="-1" role="dialog" aria-labelledby="editLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeaveModalLabel">Edit Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editLeaveForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="editLeaveId" name="id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Leave Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_type" id="editLeaveType" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No of Days</label>
                                <input type="text" class="form-control" id="editNoOfDays" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" id="editStartDate" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" id="editEndDate" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason</label>
                                <textarea class="form-control" name="reason" id="editReason" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateLeaveBtn">Update Leave</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="text-center">
                    <i class="zmdi zmdi-delete zmdi-hc-3x text-danger"></i>
                    <h5 class="modal-title mt-2" id="deleteConfirmModalLabel">Delete Confirmation</h5>
                </div>
                <button type="button" class="close position-absolute" style="right: 15px; top: 15px;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this leave request?</p>
                <input type="hidden" id="deleteLeaveId">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Libs plugin -->
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- Libs plugin -->
<script src="{{ asset('assets/plugins/bootstrap-notify/bootstrap-notify.js') }}"></script> <!-- Bootstrap Notify plugin -->

<!-- Custom Js --> 
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script>
    $(document).ready(function() {
        // Function to show messages using Bootstrap Notify
        function showMessage(message, type) {
            // Use Bootstrap Notify instead of Toastr
            $.notify({
                message: message
            }, {
                type: type === 'success' ? 'success' : 'danger',
                z_index: 10032,
                delay: 5000,
                placement: {
                    from: "top",
                    align: "right"
                }
            });
        }
        
        // Calculate days when start date or end date changes in add form
        $('#addStartDate, #addEndDate').on('change', function() {
            calculateDays('add');
        });
        
        // Calculate days when start date or end date changes in edit form
        $('#editStartDate, #editEndDate').on('change', function() {
            calculateDays('edit');
        });
        
        // Function to calculate days between two dates
        function calculateDays(formType) {
            const startDate = $(`#${formType}StartDate`).val();
            const endDate = $(`#${formType}EndDate`).val();
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end >= start) {
                    const timeDiff = end.getTime() - start.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                    $(`#${formType}NoOfDays`).val(daysDiff + ' day' + (daysDiff > 1 ? 's' : ''));
                } else {
                    $(`#${formType}NoOfDays`).val('Invalid date range');
                }
            } else {
                $(`#${formType}NoOfDays`).val('');
            }
        }
        
        // Save new leave
        $('#saveLeaveBtn').off('click').on('click', function(e) {
            e.preventDefault();
            console.log('Save button clicked');
            
            // Check if leave type is selected
            if (!$('select[name="leave_type"]').val()) {
                showMessage('Please select a leave type', 'error');
                return;
            }
            
            const formData = $('#addLeaveForm').serialize();
            console.log('Form data:', formData);
            
            // Show loading indicator
            const originalText = $(this).html();
            $(this).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Saving...');
            $(this).prop('disabled', true);
            
            $.ajax({
                url: "{{ route('doctor.leaves.store') }}",
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('Success response:', response);
                    // Always reset button first
                    $('#saveLeaveBtn').html(originalText);
                    $('#saveLeaveBtn').prop('disabled', false);
                    
                    if (response && response.success) {
                        showMessage(response.message || 'Leave request submitted successfully', 'success');
                        $('#addLeaveModal').modal('hide');
                        $('#addLeaveForm')[0].reset();
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showMessage(response.message || 'Failed to submit leave request', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error response:', xhr);
                    // Always reset button on error
                    $('#saveLeaveBtn').html(originalText);
                    $('#saveLeaveBtn').prop('disabled', false);
                    
                    let errorMessage = 'Failed to submit leave request';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            errorMessage = '';
                            for (let key in xhr.responseJSON.errors) {
                                errorMessage += xhr.responseJSON.errors[key][0] + '\n';
                            }
                        }
                    }
                    
                    showMessage(errorMessage, 'error');
                }
            });
        });
        
        // Edit leave
        $('.edit-leave').off('click').on('click', function() {
            const leave = $(this).data('leave');
            $('#editLeaveId').val(leave.id);
            $('#editStartDate').val(leave.start_date);
            $('#editEndDate').val(leave.end_date);
            $('#editReason').val(leave.reason);
            $('#editLeaveType').val(leave.leave_type);
            
            calculateDays('edit');
            $('#editLeaveModal').modal('show');
        });
        
        // Update leave
        $('#updateLeaveBtn').off('click').on('click', function(e) {
            e.preventDefault();
            const leaveId = $('#editLeaveId').val();
            
            // Check if leave type is selected
            if (!$('#editLeaveType').val()) {
                showMessage('Please select a leave type', 'error');
                return;
            }
            
            const formData = $('#editLeaveForm').serialize();
            
            // Show loading indicator
            const originalText = $(this).html();
            $(this).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Updating...');
            $(this).prop('disabled', true);
            
            $.ajax({
                url: "{{ url('doctor/leaves') }}/" + leaveId,
                method: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('Update success response:', response);
                    // Always reset button first
                    $('#updateLeaveBtn').html(originalText);
                    $('#updateLeaveBtn').prop('disabled', false);
                    
                    if (response && response.success) {
                        showMessage(response.message || 'Leave request updated successfully', 'success');
                        $('#editLeaveModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showMessage(response.message || 'Failed to update leave request', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Update error response:', xhr);
                    // Always reset button on error
                    $('#updateLeaveBtn').html(originalText);
                    $('#updateLeaveBtn').prop('disabled', false);
                    
                    let errorMessage = 'Failed to update leave request';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            errorMessage = '';
                            for (let key in xhr.responseJSON.errors) {
                                errorMessage += xhr.responseJSON.errors[key][0] + '\n';
                            }
                        }
                    }
                    
                    showMessage(errorMessage, 'error');
                }
            });
        });

        // Delete leave
        $('.delete-leave').off('click').on('click', function() {
            const leaveId = $(this).data('id');
            $('#deleteLeaveId').val(leaveId);
            $('#deleteConfirmModal').modal('show');
        });

        $('#confirmDeleteBtn').off('click').on('click', function(e) {
            const leaveId = $('#deleteLeaveId').val();
            
            if (leaveId) {
                $.ajax({
                    url: "{{ url('doctor/leaves') }}/" + leaveId,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success) {
                            showMessage(response.message || 'Leave request deleted successfully', 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            showMessage(response.message || 'Failed to delete leave request', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Failed to delete leave request';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        showMessage(errorMessage, 'error');
                    }
                });
            }
        });
        
        // Cancel leave
        $('.cancel-leave').off('click').on('click', function() {
            const leaveId = $(this).data('id');
            
            if (confirm('Are you sure you want to cancel this leave request?')) {
                $.ajax({
                    url: "{{ url('doctor/leaves') }}/" + leaveId,
                    method: 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: 'cancelled'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success) {
                            showMessage(response.message || 'Leave request cancelled successfully', 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            showMessage(response.message || 'Failed to cancel leave request', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Failed to cancel leave request';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        showMessage(errorMessage, 'error');
                    }
                });
            }
        });
        
        // Search functionality
        $('#leaveSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#leavesTableBody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchTerm) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
        
        // Sort functionality
        $('#sortLeaves').on('change', function() {
            const sortBy = $(this).val();
            const tbody = $('#leavesTableBody');
            const rows = tbody.find('tr').toArray();
            
            rows.sort(function(a, b) {
                const dateA = new Date($(a).find('td:eq(3)').text());
                const dateB = new Date($(b).find('td:eq(3)').text());
                
                if (sortBy === 'recent') {
                    return dateB - dateA; // Descending order
                } else {
                    return dateA - dateB; // Ascending order
                }
            });
            
            $.each(rows, function(index, row) {
                tbody.append(row);
            });
        });
    });
</script>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="text-center">
                    <i class="zmdi zmdi-delete zmdi-hc-3x text-danger"></i>
                    <h5 class="modal-title mt-2" id="deleteConfirmModalLabel">Delete Confirmation</h5>
                </div>
                <button type="button" class="close position-absolute" style="right: 15px; top: 15px;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this leave request?</p>
                <input type="hidden" id="deleteLeaveId">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>