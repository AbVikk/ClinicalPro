@forelse($orders as $order)
<tr>
    <td><strong>#{{ $order->id }}</strong></td>
    <td>
        @if($order->patient)
            {{ $order->patient->name }}
            <br><small class="text-muted">{{ $order->patient->user_id }}</small>
        @else
            <span class="text-muted">Walk-in Customer</span>
        @endif
    </td>
    <td>
        @if($order->pharmacist)
            {{ $order->pharmacist->name }}
            <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $order->pharmacist->role)) }}</small>
        @else
            <span class="text-muted">N/A</span>
        @endif
    </td>
    <td>
        @if($order->clinic)
            {{ $order->clinic->name }}
        @else
            <span class="text-muted">N/A</span>
        @endif
    </td>
    <td>
        <div class="order-items-list">
            @foreach($order->items->take(2) as $item)
                <div>{{ $item->drug->name ?? 'Unknown Drug' }} ({{ $item->quantity }})</div>
            @endforeach
            @if($order->items->count() > 2)
                <div><em>+{{ $order->items->count() - 2 }} more items</em></div>
            @endif
        </div>
    </td>
    <td><strong>₦{{ number_format($order->total_amount, 2) }}</strong></td>
    <td>{{ $order->created_at->format('d M, Y') }}<br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small></td>
    <td class="text-right">
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#orderDetailsModal{{ $order->id }}">
            <i class="zmdi zmdi-eye"></i> View
        </button>
    </td>
</tr>
@empty
<tr id="no-results-row">
    <td colspan="8" class="text-center">
        <div class="no-results">
            <i class="zmdi zmdi-shopping-cart" style="font-size: 48px; opacity: 0.3;"></i>
            <h5 class="mt-3">No sold orders found</h5>
            <p class="text-muted">Try adjusting your filters or check back later</p>
        </div>
    </td>
</tr>
@endforelse