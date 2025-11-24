{{-- This file ONLY contains the table, so we can refresh it --}}
<div class="table-responsive">
    <table class="table table-hover m-b-0">
        <thead>
            <tr>
                <th>Appointment Time</th>
                <th>Patient Name</th>
                <th>Assigned Doctor</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($patientQueue as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                    <td>
                        <img src="{{ $appointment->patient->photo ? asset('storage/' . $appointment->patient->photo) : asset('assets/images/xs/avatar1.jpg') }}" alt="Patient" class="rounded-circle" width="35" height="35" style="object-fit: cover; margin-right: 10px;">
                        <a href="#">{{ $appointment->patient->name ?? 'N/A' }}</a>
                    </td>
                    <td>Dr. {{ $appointment->doctor->name ?? 'N/A' }}</td>
                    <td>
                        @if($appointment->status == 'in_progress')
                            <span class="badge badge-danger">With Doctor</span>
                        @elseif($appointment->status == 'confirmed') 
                            <span class="badge badge-warning">Waiting for Vitals</span>
                        @else
                            <span class="badge badge-light">{{ $appointment->status }}</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm btn-take-vitals" 
                                data-toggle="modal" 
                                data-target="#vitalsModal"
                                data-patient-name="{{ $appointment->patient->name ?? 'N/A' }}"
                                data-appointment-id="{{ $appointment->id }}">
                            Take Vitals
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center p-4">
                        No patients in the queue right now.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>