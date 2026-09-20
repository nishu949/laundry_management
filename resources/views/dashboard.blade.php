@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h2>Dashboard</h2>
            <div class="sub">{{ now()->format('l, F j, Y') }}</div>
        </div>
        <a href="{{ route('orders.create') }}" class="btn">+ New Order</a>
    </div>

    {{-- Operational stat cards --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(210px, 1fr)); gap:1rem; margin-bottom:1.25rem;">
        @php
            $cards = [
                ['Pickups Today',    $stats['pickups_today'],   '#fbbf24', '📅'],
                ['In Washing',       $stats['in_washing'],      '#60a5fa', '🧼'],
                ['Ready to Deliver', $stats['ready'],           '#34d399', '🎉'],
                ['Delivered Today',  $stats['delivered_today'], '#94a3b8', '🚚'],
            ];
        @endphp
        @foreach($cards as [$label, $value, $color, $icon])
            <div class="card" style="padding:1.25rem;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.5rem; font-weight:600;">{{ $label }}</div>
                        <div style="font-size:2rem; font-weight:700; color:{{ $color }}; line-height:1;">{{ $value }}</div>
                    </div>
                    <div style="font-size:1.75rem; opacity:0.55;">{{ $icon }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Revenue cards --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1rem; margin-bottom:1.25rem;">
        <div class="card" style="padding:1.5rem;">
            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.5rem; font-weight:600;">Unpaid Balance</div>
            <div class="amount-lg" style="background:linear-gradient(135deg,#fcd34d,#fbbf24); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">
                ${{ number_format($stats['unpaid_total'], 2) }}
            </div>
            <small class="muted">Across all pending orders</small>
        </div>
        <div class="card" style="padding:1.5rem;">
            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.5rem; font-weight:600;">Revenue (Last 7 Days)</div>
            <div class="amount-lg">${{ number_format($stats['revenue_week'], 2) }}</div>
            <small class="muted">Paid orders in the past week</small>
        </div>
    </div>

    {{-- Order pipeline --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <h3 style="margin-top:0;">Order Pipeline</h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:0.75rem;">
            @foreach(['pickup' => 'Pickup', 'washing' => 'Washing', 'ready' => 'Ready', 'delivered' => 'Delivered'] as $key => $label)
                <a href="{{ route('orders.index', ['status' => $key]) }}"
                   style="text-decoration:none; padding:1.1rem; border:1px solid var(--border-2);
                          border-radius:12px; background:var(--bg-2); text-align:center;
                          transition: border-color 0.15s, transform 0.08s;"
                   onmouseover="this.style.borderColor='var(--brand)'"
                   onmouseout="this.style.borderColor='var(--border-2)'">
                    <div class="badge badge-{{ $key }}" style="margin-bottom:0.7rem;">{{ $label }}</div>
                    <div style="font-size:1.6rem; font-weight:700; color:var(--text); line-height:1;">{{ $byStatus[$key] }}</div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Recent orders --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.1rem;">
            <h3 style="margin:0;">Recent Orders</h3>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">View all →</a>
        </div>

        @if($recent->isEmpty())
            <p class="muted" style="margin:0;">No orders yet. Book your first one above.</p>
        @else
            <div class="table-wrap" style="box-shadow:none;">
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th style="text-align:right;">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $o)
                            <tr>
                                <td><code>{{ $o->order_number }}</code></td>
                                <td>
                                    <div style="font-weight:500;">{{ $o->customer_name }}</div>
                                    <small class="muted">{{ $o->customer_phone }}</small>
                                </td>
                                <td><span class="badge badge-{{ $o->status }}">{{ $o->status_label }}</span></td>
                                <td><span class="badge badge-{{ $o->payment_status }}">{{ $o->payment_label }}</span></td>
                                <td style="text-align:right;" class="amount">{{ $o->total_formatted }}</td>
                                <td><a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-secondary">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection