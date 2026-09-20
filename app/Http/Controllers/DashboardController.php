<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'pickups_today'   => Order::whereDate('pickup_scheduled_at', $today)->count(),
            'in_washing'      => Order::where('status', Order::STATUS_WASHING)->count(),
            'ready'           => Order::where('status', Order::STATUS_READY)->count(),
            'delivered_today' => Order::whereDate('delivered_at', $today)->count(),
            'unpaid_total'    => Order::where('payment_status', 'pending')->sum('total_amount'),
            'revenue_week'    => Order::where('payment_status', 'paid')
                                      ->where('updated_at', '>=', now()->subDays(7))
                                      ->sum('total_amount'),
        ];

        $byStatus = [
            'pickup'    => Order::where('status', Order::STATUS_PICKUP)->count(),
            'washing'   => Order::where('status', Order::STATUS_WASHING)->count(),
            'ready'     => Order::where('status', Order::STATUS_READY)->count(),
            'delivered' => Order::where('status', Order::STATUS_DELIVERED)->count(),
        ];

        $recent = Order::with('items')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'byStatus', 'recent'));
    }
}