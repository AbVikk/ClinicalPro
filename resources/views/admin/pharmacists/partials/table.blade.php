@if($pharmacists->count() > 0)
    @foreach($pharmacists as $pharmacist)
        <tr class="pharmacist-row" data-name="{{ strtolower($pharmacist->name) }}" data-email="{{ strtolower($pharmacist->email) }}">
            <td>{{ $loop->iteration + ($pharmacists->currentPage() - 1) * $pharmacists->perPage() }}</td>
            <td><a href="{{ route('admin.pharmacists.show', $pharmacist) }}">{{ $pharmacist->name }}</a></td>
            <td>{{ $pharmacist->email }}</td>
            <td>
                <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $pharmacist->role)) }}</span>
            </td>
            <td>
                <span class="badge badge-{{ $pharmacist->status === 'active' ? 'success' : ($pharmacist->status === 'pending' ? 'warning' : 'danger') }}">
                     {{ ucwords($pharmacist->status) }}
                </span>
            </td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.pharmacists.show', $pharmacist) }}">
                            <i class="zmdi zmdi-eye"></i> View Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="zmdi zmdi-edit"></i> Edit
                        </a>
                        @if($pharmacist->status !== 'active')
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to activate this pharmacist?')) { document.getElementById('activate-form-{{ $pharmacist->id }}').submit(); }">
                                <i class="zmdi zmdi-check"></i> Activate
                            </a>
                        @else
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to suspend this pharmacist?')) { document.getElementById('suspend-form-{{ $pharmacist->id }}').submit(); }">
                                <i class="zmdi zmdi-block"></i> Suspend
                            </a>
                        @endif
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this pharmacist? This action cannot be undone.')) { document.getElementById('delete-form-{{ $pharmacist->id }}').submit(); }">
                            <i class="zmdi zmdi-delete"></i> Delete
                        </a>
                    </div>
                </div>
                
                <!-- Activate Form -->
                <form id="activate-form-{{ $pharmacist->id }}" action="{{ route('admin.pharmacists.update', $pharmacist) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="active">
                </form>
                
                <!-- Suspend Form -->
                <form id="suspend-form-{{ $pharmacist->id }}" action="{{ route('admin.pharmacists.update', $pharmacist) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="suspended">
                </form>
                
                <!-- Delete Form -->
                <form id="delete-form-{{ $pharmacist->id }}" action="{{ route('admin.pharmacists.destroy', $pharmacist) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center">No pharmacists found for this role.</td>
    </tr>
@endif
{{ $pharmacists->appends(request()->query())->links() }}