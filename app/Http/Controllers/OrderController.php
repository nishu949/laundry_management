<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RateCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with('items.rateCard')->latest();

        if ($status && in_array($status, Order::statuses())) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        $counts = [
            'all'       => Order::count(),
            'pickup'    => Order::where('status', Order::STATUS_PICKUP)->count(),
            'washing'   => Order::where('status', Order::STATUS_WASHING)->count(),
            'ready'     => Order::where('status', Order::STATUS_READY)->count(),
            'delivered' => Order::where('status', Order::STATUS_DELIVERED)->count(),
        ];

        return view('orders.index', compact('orders', 'status', 'counts'));
    }

    public function create()
    {
        $rateCards = RateCard::all();
        return view('orders.create', compact('rateCards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'        => 'required|string|max:255',
            'customer_phone'       => 'required|string|max:30',
            'customer_address'     => 'nullable|string|max:500',
            'service_type'         => 'required|string',
            'pickup_scheduled_at'  => 'nullable|date',
            'items'                => 'required|array|min:1',
            'items.*.rate_card_id' => 'required|exists:rate_cards,id',
            'items.*.count'        => 'required|integer|min:1',
        ], [
            'customer_name.required'        => 'Customer name is required.',
            'customer_phone.required'       => 'Phone number is required.',
            'items.required'                => 'Add at least one item to the order.',
            'items.min'                     => 'Add at least one item to the order.',
            'items.*.rate_card_id.required' => 'Please select a rate card for each item.',
            'items.*.rate_card_id.exists'   => 'Selected rate card is invalid.',
            'items.*.count.required'        => 'Item count is required.',
            'items.*.count.min'             => 'Item count must be at least 1.',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'order_number'        => 'ORD-' . strtoupper(uniqid()),
                'customer_name'       => $validated['customer_name'],
                'customer_phone'      => $validated['customer_phone'],
                'customer_address'    => $validated['customer_address'] ?? null,
                'service_type'        => $validated['service_type'],
                'status'              => Order::STATUS_PICKUP,
                'pickup_scheduled_at' => $validated['pickup_scheduled_at'] ?? null,
            ]);

            $total = 0;

            foreach ($validated['items'] as $row) {
                $rateCard  = RateCard::findOrFail($row['rate_card_id']);
                $count     = (int) $row['count'];
                $unitRate  = $rateCard->getRateForQuantity($count);
                $lineTotal = round($unitRate * $count, 2);

                OrderItem::create([
                    'order_id'     => $order->id,
                    'rate_card_id' => $rateCard->id,
                    'count'        => $count,
                    'unit_rate'    => $unitRate,
                    'line_total'   => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $order->update(['total_amount' => round($total, 2)]);

            return $order;
        });

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order booked successfully.');
    }

    public function show(Order $order)
    {
        $order->load('items.rateCard');
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pickup,washing,ready,delivered',
        ]);

        $order->updateStatus($validated['status']);

        return back()->with('success', 'Status updated to ' . ucfirst($validated['status']));
    }

    public function updatePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid',
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return back()->with('success', 'Payment status updated to ' . $validated['payment_status']);
    }
}