@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h2>Orders</h2>
            <div class="sub">Manage pickups, washing, and deliveries</div>
        </div>
    </div>

    @php
        $tabs = [
            'all'       => 'All',
            'pickup'    => 'Pickup',
            'washing'   => 'Washing',
            'ready'     => 'Ready',
            'delivered' => 'Delivered',
        ];
        $active = $status ?? 'all';
    @endphp

    <div class="tabs">
        @foreach($tabs as $key => $label)
            <a href="{{ $key === 'all' ? route('orders.index') : route('orders.index', ['status' => $key]) }}"
               class="{{ $active === $key ? 'active' : '' }}">
                {{ $label }}
                <span class="count">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <div class="empty">
            <div class="empty-icon">🧺</div>
            @if($active !== 'all')
                <h3>No orders in "{{ ucfirst($active) }}"</h3>
                <p>There are currently no orders with this status.</p>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Show all orders</a>
            @else
                <h3>No orders yet</h3>
                <p>Book your first laundry order to get started.</p>
                <a href="{{ route('orders.create') }}" class="btn">+ Book New Order</a>
            @endif
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th style="text-align:center;">Items</th>
                        <th>Status</th>
                        <th style="text-align:right;">Total</th>
                        <th>Payment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><code>{{ $order->order_number }}</code></td>
                            <td>
                                <div style="font-weight:500;">{{ $order->customer_name }}</div>
                                <small class="muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td>{{ $order->service_label }}</td>
                            <td style="text-align:center;">{{ $order->items_count }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
                            </td>
                            <td style="text-align:right;" class="amount">{{ $order->total_formatted }}</td>
                            <td>
                                <span class="badge badge-{{ $order->payment_status }}">
                                    {{ $order->payment_label }}
                                </span>
                            </td>
                            <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-secondary">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection