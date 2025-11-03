@if($patients->count() > 0)
    <div class="table-responsive">
        <table class="table m-b-0 table-hover" id="patients-table">
            <thead>
                <tr>                                       
                    <th>Media</th>
                    <th>Patient Name</th>
                    <th>Last Appointment</th>
                    <th>Total Appointments</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                <tr>
                    <td>
                        <span class="list-icon">
                            @if($patient->photo)
                                <img class="patients-img" src="{{ asset('storage/' . $patient->photo) }}" alt="{{ $patient->name }}">
                            @else
                                <img class="patients-img" src="{{ asset('assets/images/xs/avatar1.jpg') }}" alt="{{ $patient->name }}">
                            @endif
                        </span>
                    </td>
                    <td>
                        <h6 class="mb-0">{{ $patient->name }}</h6>
                        <small class="text-muted">
                            @if($patient->date_of_birth)
                                @php
                                    $age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                                @endphp
                                {{ $age }} years • {{ ucfirst($patient->gender ?? 'N/A') }}
                            @else
                                N/A
                            @endif
                        </small>
                    </td>
                    <td>
                        @if($patient->appointmentsAsPatient->first())
                            {{ $patient->appointmentsAsPatient->first()->appointment_time->format('M d, Y') }}
                            <br>
                            <small class="text-muted">{{ $patient->appointmentsAsPatient->first()->appointment_time->format('g:i A') }}</small>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $patient->appointmentsAsPatient->count() }}</td>
                    <td>
                        <span class="badge badge-{{ $patient->status == 'verified' ? 'success' : 'warning' }}" data-status="{{ $patient->status }}">
                            {{ ucfirst($patient->status ?? 'pending') }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                
                                <a class="dropdown-item" href="{{ route('doctor.patients.appointment-history', $patient->id) }}">
                                    <i class="zmdi zmdi-calendar"></i> View Appointment History
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="zmdi zmdi-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination - only show for non-AJAX requests -->
    @if(request()->ajax())
        <!-- For AJAX requests, we don't need pagination -->
    @else
        <!-- Pagination -->
        <div class="pagination-container">
            {{ $patients->appends(['search' => request('search')])->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="zmdi zmdi-accounts zmdi-hc-3x text-muted mb-3"></i>
        <h4>No Patients Found</h4>
        <p class="text-muted">No patients match your search criteria.</p>
    </div>
@endif