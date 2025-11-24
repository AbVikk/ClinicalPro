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
                            {{-- *** SAFE CHECK for patient photo *** --}}
                            <img src="{{ $appointment->patient?->photo ? asset('storage/' . $appointment->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" alt="Patient" class="rounded-circle" width="40">
                            <div class="ml-3">
                                {{-- *** SAFE CHECK for patient name and uses patient_id for link *** --}}
                                <h6 class="mb-0">
                                    @if($appointment->patient)
                                        <a href="{{ route('doctor.patients.appointment-history', $appointment->patient_id) }}">{{ $appointment->patient->name }}</a>
                                    @else
                                        Unknown Patient
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>#APT{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y g:i A') }}</td>
                    {{-- *** SAFE CHECK for appointmentReason and consultation *** --}}
                    <td>{{ $appointment->consultation?->service_type ?? $appointment->appointmentReason?->name ?? 'General Visit' }}</td>
                    <td>{{ ucfirst($appointment->type ?? 'Video Call') }}</td>
                    <td>
                        {{-- *** THIS IS THE NEW LOGIC FOR STATUS BADGES *** --}}
                        @php
                            $statusClass = 'badge-secondary'; // Default
                            $statusText = ucfirst(str_replace('_', ' ', $appointment->status));
                            
                            if ($appointment->status == 'pending' || $appointment->status == 'new') {
                                $statusClass = 'badge-warning';
                                $statusText = 'Request';
                            } elseif ($appointment->status == 'approved') { // Your new Admin status
                                $statusClass = 'badge-info';
                                $statusText = 'Awaiting Check-in';
                            } elseif ($appointment->status == 'confirmed') { // Now means "Ready" for Virtual
                                $statusClass = 'badge-success';
                                $statusText = 'Ready (Virtual)';
                            } elseif ($appointment->status == 'checked_in') { // Your new Nurse status
                                $statusClass = 'badge-warning';
                                $statusText = 'Awaiting Vitals';
                            } elseif ($appointment->status == 'vitals_taken') { // Your new Doctor "Ready" status
                                $statusClass = 'badge-success';
                                $statusText = 'Ready for Doctor';
                            } elseif ($appointment->status == 'in_progress') {
                                $statusClass = 'badge-primary';
                            } elseif ($appointment->status == 'completed') {
                                $statusClass = 'badge-success';
                            } elseif ($appointment->status == 'cancelled' || $appointment->status == 'missed' || $appointment->status == 'rejected') {
                                $statusClass = 'badge-danger';
                            }
                        @endphp
                        
                        @if($appointment->status == 'in_progress')
                            <a href="{{ route('doctor.appointments.details', $appointment->id) }}" class="badge {{ $statusClass }}">{{ $statusText }}</a>
                        @else
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                {{-- *** SAFE CHECK for patient link *** --}}
                                @if($appointment->patient)
                                    <a class="dropdown-item" href="{{ route('doctor.patients.appointment-history', $appointment->patient_id) }}">
                                        <i class="zmdi zmdi-account"></i> View Profile
                                    </a>
                                @endif
                                
                                {{-- 1. View History (for past appointments) --}}
                                @if($tab == 'completed' || $tab == 'missed' || $tab == 'cancelled')
                                    <a class="dropdown-item" href="{{ route('doctor.patients.appointment-history', $appointment->patient_id) }}">
                                        <i class="zmdi zmdi-time-restore"></i> View History
                                    </a>
                                @endif

                                {{-- 2. Chat (for all active appointments) --}}
                                @if($tab == 'upcoming' || $tab == 'inprogress')
                                    <a class="dropdown-item" href="#">
                                        <i class="zmdi zmdi-comment-alt-text"></i> Chat
                                    </a>
                                @endif

                                {{-- 3. THE VITALS GATE & START/END LOGIC --}}

                                {{-- Case A: VIRTUAL & 'confirmed' (Ready to Start) --}}
                                @if($appointment->type == 'telehealth' && $appointment->status == 'confirmed' && $tab == 'upcoming')
                                     <a class="dropdown-item start-appointment" href="#" data-appointment-id="{{ $appointment->id }}">
                                        <i class="zmdi zmdi-play"></i> Begin
                                    </a>

                                {{-- Case B: PHYSICAL & 'vitals_taken' (Ready to Start) --}}
                                @elseif($appointment->type == 'in_person' && $appointment->status == 'vitals_taken' && $tab == 'upcoming')
                                     <a class="dropdown-item start-appointment" href="#" data-appointment-id="{{ $appointment->id }}">
                                        <i class="zmdi zmdi-play"></i> Begin
                                    </a>

                                {{-- Case C: PHYSICAL & 'checked_in' (Waiting for Vitals) --}}
                                @elseif($appointment->type == 'in_person' && $appointment->status == 'checked_in' && $tab == 'upcoming')
                                    <a class="dropdown-item" href="#" style="color: #ff9800; cursor: not-allowed;" onclick="return false;">
                                        <i class="zmdi zmdi-time-wait"></i> Awaiting Vitals
                                    </a>

                                {{-- Case D: PHYSICAL & 'approved' (Waiting for Check-in) --}}
                                @elseif($appointment->type == 'in_person' && $appointment->status == 'approved' && $tab == 'upcoming')
                                    <a class="dropdown-item" href="#" style="color: #03a9f4; cursor: not-allowed;" onclick="return false;">
                                        <i class="zmdi zmdi-time-wait"></i> Awaiting Check-in
                                    </a>
                                
                                {{-- Case E: Already In Progress (Can View/End) --}}
                                @elseif($appointment->status == 'in_progress')
                                     <a class="dropdown-item" href="{{ route('doctor.appointments.details', $appointment->id) }}">
                                        <i class="zmdi zmdi-file-text"></i> View Details
                                    </a>
                                    <a class="dropdown-item end-appointment" href="#" data-appointment-id="{{ $appointment->id }}">
                                        <i class="zmdi zmdi-stop"></i> End
                                    </a>
                                @endif
                                
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
    
    <div classs="pagination-container">
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
        @elseif($tab == 'missed')
            <i class="zmdi zmdi-time-off zmdi-hc-3x text-muted mb-3"></i>
            <h4>No Missed Appointments</h4>
            <p class="text-muted">You don't have any missed appointments.</p>
        @else {{-- Default is 'upcoming' --}}
            <i class="zmdi zmdi-calendar zmdi-hc-3x text-muted mb-3"></i>
            <h4>No Upcoming Appointments</h4>
            <p class="text-muted">You don't have any upcoming appointments at the moment.</p>
        @endif
    </div>
@endif