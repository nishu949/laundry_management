@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h2 style="margin-bottom:0.25rem;">Order <code>{{ $order->order_number }}</code></h2>
            <div class="sub">Booked {{ $order->created_at->format('M j, Y · g:i A') }}</div>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
    </div>

    {{-- Summary --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1.5rem;">
            <div>
                <dl class="kv">
                    <dt>Customer</dt>       <dd>{{ $order->customer_name }}</dd>
                    <dt>Phone</dt>          <dd>{{ $order->customer_phone }}</dd>
                    <dt>Address</dt>        <dd>{{ $order->customer_address ?: '—' }}</dd>
                    <dt>Service Type</dt>   <dd>{{ $order->service_label }}</dd>
                    <dt>Current Status</dt> <dd><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></dd>
                    <dt>Payment</dt>        <dd><span class="badge badge-{{ $order->payment_status }}">{{ $order->payment_label }}</span></dd>
                </dl>
            </div>
            <div style="text-align:right;">
                <small class="muted">Total Amount</small>
                <div class="amount-lg">{{ $order->total_formatted }}</div>
                <small class="muted">{{ $order->items_count }} items</small>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="card">
        <h3 style="margin-top:0;">Items &amp; Applicable Rates</h3>
        <div class="table-wrap" style="box-shadow:none;">
            <table>
                <thead>
                    <tr>
                        <th>Rate Card</th>
                        <th style="text-align:center;">Count</th>
                        <th style="text-align:right;">Unit Rate</th>
                        <th style="text-align:right;">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->rateCard->name }}</td>
                            <td style="text-align:center;">{{ $item->count }}</td>
                            <td style="text-align:right;" class="amount">{{ $item->unit_rate_formatted }}</td>
                            <td style="text-align:right;" class="amount">{{ $item->line_total_formatted }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right;">Total</th>
                        <th style="text-align:right;" class="amount">{{ $order->total_formatted }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Actions --}}
    <div class="card">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div>
                <h3 style="margin-top:0;">Update Status</h3>
                <form method="POST" action="{{ route('orders.update-status', $order) }}"
                      style="display:flex; gap:0.5rem; align-items:center;">
                    @csrf
                    @method('PATCH')
                    <select name="status" style="flex:1;">
                        @foreach(\App\Models\Order::statuses() as $s)
                            <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn">Update</button>
                </form>
            </div>

            <div>
                <h3 style="margin-top:0;">Payment</h3>
                <form method="POST" action="{{ route('orders.update-payment', $order) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="payment_status"
                           value="{{ $order->payment_status === 'paid' ? 'pending' : 'paid' }}">
                    <button type="submit" class="btn {{ $order->payment_status === 'paid' ? 'btn-secondary' : '' }}">
                        Mark as {{ $order->payment_status === 'paid' ? 'Pending' : 'Paid' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    @php
        $events = array_filter([
            $order->pickup_scheduled_at  ? ['📅', 'Pickup scheduled',   $order->pickup_scheduled_at]  : null,
            $order->picked_up_at         ? ['📦', 'Picked up',          $order->picked_up_at]         : null,
            $order->washing_started_at   ? ['🧼', 'Washing started',    $order->washing_started_at]   : null,
            $order->washing_completed_at ? ['✅', 'Washing complete',   $order->washing_completed_at] : null,
            $order->ready_at             ? ['🎉', 'Ready for delivery', $order->ready_at]             : null,
            $order->delivered_at         ? ['🚚', 'Delivered',          $order->delivered_at]         : null,
        ]);
    @endphp

    <div class="card">
        <h3 style="margin-top:0;">Status Timeline</h3>
        @if(empty($events))
            <p class="muted">No status events recorded yet.</p>
        @else
            <ul class="timeline">
                @foreach($events as [$icon, $label, $time])
                    <li>{{ $icon }} {{ $label }}<time>{{ $time->format('M j, Y · g:i A') }}</time></li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection