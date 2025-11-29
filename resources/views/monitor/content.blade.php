<div class="section-serving">
    <div class="section-header">Now Serving</div>
    
    @forelse($serving as $appt)
        <div class="serving-card">
            <div class="room-number">
                {{-- You can change this to Room # if you have it in DB --}}
                <i class="zmdi zmdi-stethoscope"></i>
            </div>
            {{-- Privacy: Show First Name + Last Initial --}}
            @php
                $parts = explode(' ', $appt->patient->name);
                $privacyName = $parts[0] . ' ' . (isset($parts[1]) ? substr($parts[1], 0, 1) . '.' : '');
            @endphp
            <p class="patient-name">{{ $privacyName }}</p>
            <p class="doctor-name">With Dr. {{ $appt->doctor->name }}</p>
        </div>
    @empty
        <div class="empty-state">
            <p>No patients currently in consultation.</p>
        </div>
    @endforelse
</div>

<div class="section-waiting">
    <div class="section-header">Up Next</div>
    
    @forelse($waiting as $appt)
        <div class="waiting-row">
            @php
                $parts = explode(' ', $appt->patient->name);
                $privacyName = $parts[0] . ' ' . (isset($parts[1]) ? substr($parts[1], 0, 1) . '.' : '');
            @endphp
            <div>
                <strong>{{ $privacyName }}</strong><br>
                <small style="font-size: 14px; color: #888;">{{ $appt->status == 'checked_in' ? 'Checked In' : 'Vitals Done' }}</small>
            </div>
            <span>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</span>
        </div>
    @empty
        <div class="empty-state">
            <p style="color: #666;">Queue is empty.</p>
        </div>
    @endforelse
</div>