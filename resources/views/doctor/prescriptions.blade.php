<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor prescriptions management">

<title>ClinicalPro || Prescriptions</title>
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
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                <h2 class="m-0"><i class="zmdi zmdi-file-text"></i> <span>Prescriptions</span></h2>
                <ul class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Prescriptions</li>
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
                            <div class="input-group" style="width: 300px;">
                                <input type="text" class="form-control" placeholder="Search..." id="prescriptionSearch" value="{{ $search ?? '' }}">
                                <span class="input-group-addon">
                                    <i class="zmdi zmdi-search"></i>
                                </span>
                            </div>
    
                            <div class="d-flex align-items-center">
                                <div class="form-group mb-0" style="margin-right: 15px;">
                                    <select class="form-control" id="sortPrescriptions">
                                        <option value="new" {{ ($sort ?? 'new') == 'new' ? 'selected' : '' }}>New First</option>
                                        <option value="old" {{ ($sort ?? 'new') == 'old' ? 'selected' : '' }}>Old First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Prescription ID</th>
                                        <th>Patient</th>
                                        <th>Prescribed On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="prescriptionsTableBody">
                                    @forelse($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->id }}</td>
                                        <td>{{ $prescription->patient->name ?? 'N/A' }}</td>
                                        <td>{{ $prescription->created_at->format('F d, Y g:i A') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('doctor.prescriptions.show', $prescription->id) }}">View</a>
                                                    <a class="dropdown-item delete-prescription" href="javascript:void(0);" data-id="{{ $prescription->id }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No prescriptions found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($prescriptions->hasPages())
                        <div class="pagination-wrapper">
                            {{ $prescriptions->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
                <p>Are you sure you want to delete this prescription?</p>
                <input type="hidden" id="deletePrescriptionId">
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
        
        // Search functionality
        $('#prescriptionSearch').on('keyup', function() {
            const searchTerm = $(this).val();
            const sort = $('#sortPrescriptions').val();
            
            // Redirect with search parameters
            const url = new URL(window.location);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            if (sort) {
                url.searchParams.set('sort', sort);
            }
            window.location.href = url;
        });
        
        // Sort functionality
        $('#sortPrescriptions').on('change', function() {
            const sort = $(this).val();
            const search = $('#prescriptionSearch').val();
            
            // Redirect with sort parameters
            const url = new URL(window.location);
            if (sort) {
                url.searchParams.set('sort', sort);
            }
            if (search) {
                url.searchParams.set('search', search);
            }
            window.location.href = url;
        });
        
        // Delete prescription - show modal
        $('.delete-prescription').off('click').on('click', function() {
            const prescriptionId = $(this).data('id');
            $('#deletePrescriptionId').val(prescriptionId);
            $('#deleteConfirmModal').modal('show');
        });
        
        // Confirm delete
        $('#confirmDeleteBtn').off('click').on('click', function() {
            const prescriptionId = $('#deletePrescriptionId').val();
            
            if (prescriptionId) {
                $.ajax({
                    url: "{{ url('doctor/prescriptions') }}/" + prescriptionId,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success) {
                            showMessage(response.message || 'Prescription deleted successfully', 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            showMessage(response.message || 'Failed to delete prescription', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Failed to delete prescription';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        showMessage(errorMessage, 'error');
                    }
                });
            }
        });
    });
</script>
</body>
</html>