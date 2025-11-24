{{-- This file ONLY contains the list, so we can refresh it --}}
<ul class="list-unstyled activity doctor-status-list">
    @forelse($doctors as $doctor)
        @php
            // **THIS IS THE FIX!**
            // We now use the 'real_time_status' we calculated in the Controller.
            $status = $doctor->real_time_status ?? 'Unavailable'; // Default to Unavailable
            
            // This logic now creates our "Three-Light System"
            if ($status == 'In Appointment') {
                $badge_class = 'badge-danger'; // RED
            } elseif ($status == 'Available') {
                $badge_class = 'badge-success'; // GREEN
            } else {
                // This catches 'Unavailable'
                $badge_class = 'badge-dark'; // BLACK 
            }
        @endphp
        <li class="doctor-item">
            <div class="media align-items-center p-3">
                <div class="media-left mr-3">
                    <div class="avatar">
                        @if($doctor->photo)
                            <img src="{{ asset('storage/' . $doctor->photo) }}" alt="Doctor" class="rounded-circle" width="35" height="35" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="Doctor" class="rounded-circle" width="35" height="35" style="object-fit: cover;">
                        @endif
                    </div>
                </div>
                <div class="media-body">
                    <h6 class="m-0">{{ $doctor->name }}</h6>
                    <p class="text-muted mb-0">
                        {{-- This badge now shows Red, Green, or Grey --}}
                        <span class="badge {{ $badge_class }}">{{ $status }}</span>
                    </p>
                </div>
            </div>
        </li>
    @empty
        <li class="p-3">No doctors on duty.</li>
    @endforelse
</ul>