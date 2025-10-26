@if($appointments->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Appointment ID</th>
                    <th>Date & Time</th>
                    <th>Visit Type</th>
                    <th>Call Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $appointment->patient->photo ? asset('storage/' . $appointment->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" alt="Patient" class="rounded-circle" width="40">
                            <div class="ml-3">
                                <h6 class="mb-0">{{ $appointment->patient->name ?? 'Unknown Patient' }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>#APT{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y g:i A') }}</td>
                    <td>{{ $appointment->appointmentReason->name ?? 'General Visit' }}</td>
                    <td>{{ ucfirst($appointment->type ?? 'Video Call') }}</td>
                    <td>
                        @if($tab == 'cancelled')
                            <span class="badge badge-danger">Cancelled</span>
                        @elseif($tab == 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif($tab == 'inprogress')
                            <a href="{{ route('doctor.appointments.details', $appointment->id) }}" class="badge badge-info">In Progress</a>
                        @else
                            @if($appointment->status == 'in_progress')
                                <a href="{{ route('doctor.appointments.details', $appointment->id) }}" class="badge badge-info">In Progress</a>
                            @elseif($appointment->status == 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @else
                                <span class="badge badge-{{ $appointment->status == 'pending' || $appointment->status == 'new' ? 'warning' : 'success' }}">
                                    {{ $appointment->status == 'pending' || $appointment->status == 'new' ? 'Request' : 'Confirmed' }}
                                </span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('doctor.patients.appointment-history', $appointment->patient->id) }}">
                                    <i class="zmdi zmdi-account"></i> View Profile
                                </a>
                                
                                @if($tab != 'cancelled' && $tab != 'completed')
                                    <a class="dropdown-item" href="#">
                                        <i class="zmdi zmdi-comment-alt-text"></i> Chat
                                    </a>
                                    
                                    @if($tab == 'upcoming' && $appointment->status == 'confirmed' && $appointment->appointment_time > now())
                                        @if($appointment->status != 'in_progress')
                                            <a class="dropdown-item start-appointment" href="#" data-appointment-id="{{ $appointment->id }}">
                                                <i class="zmdi zmdi-play"></i> Begin
                                            </a>
                                        @else
                                            <a class="dropdown-item end-appointment" href="#" data-appointment-id="{{ $appointment->id }}">
                                                <i class="zmdi zmdi-stop"></i> End
                                            </a>
                                        @endif
                                    @elseif($tab == 'inprogress' && $appointment->status == 'in_progress')
                                        <a class="dropdown-item" href="{{ route('doctor.appointments.details', $appointment->id) }}">
                                            <i class="zmdi zmdi-file-text"></i> View Details
                                        </a>
                                        <a class="dropdown-item end-appointment" href="#" data-appointment-id="{{ $appointment->id }}">
                                            <i class="zmdi zmdi-stop"></i> End
                                        </a>
                                    @elseif($appointment->status == 'in_progress')
                                        <a class="dropdown-item" href="{{ route('doctor.appointments.details', $appointment->id) }}">
                                            <i class="zmdi zmdi-file-text"></i> View Details
                                        </a>
                                        <a class="dropdown-item end-appointment" href="#" data-appointment-id="{{ $appointment->id }}">
                                            <i class="zmdi zmdi-stop"></i> End
                                        </a>
                                    @endif
                                @endif
                                
                                <a class="dropdown-item" href="#">
                                    <i class="zmdi zmdi-edit"></i> Edit
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="zmdi zmdi-delete"></i> Delete
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-container">
        {{ $appointments->links() }}
    </div>
@else
    <div class="text-center py-5">
        @if($tab == 'cancelled')
            <i class="zmdi zmdi-close-circle zmdi-hc-3x text-muted mb-3"></i>
            <h4>No Cancelled Appointments</h4>
            <p class="text-muted">You don't have any cancelled appointments.</p>
        @elseif($tab == 'completed')
            <i class="zmdi zmdi-check-circle zmdi-hc-3x text-muted mb-3"></i>
            <h4>No Completed Appointments</h4>
            <p class="text-muted">You don't have any completed appointments yet.</p>
        @elseif($tab == 'inprogress')
            <i class="zmdi zmdi-hourglass zmdi-hc-3x text-muted mb-3"></i>
            <h4>No In Progress Appointments</h4>
            <p class="text-muted">You don't have any appointments in progress.</p>
        @else
            <i class="zmdi zmdi-calendar zmdi-hc-3x text-muted mb-3"></i>
            <h4>No Upcoming Appointments</h4>
            <p class="text-muted">You don't have any upcoming appointments at the moment.</p>
        @endif
    </div>
@endif