<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $recentOrders = Order::where('client_id', $user->id)
            ->with(['provider', 'service'])
            ->latest()
            ->take(5)
            ->get();

        $pendingOrders = Order::where('client_id', $user->id)
            ->where('status', Order::STATUS_PENDING)
            ->count();

        $completedOrders = Order::where('client_id', $user->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->count();

        $totalSpent = Order::where('client_id', $user->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('price');

        return view('client.dashboard', compact(
            'recentOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent'
        ));
    }

    public function services()
    {
        $services = Service::active()
            ->with(['provider', 'reviews'])
            ->latest()
            ->paginate(12);

        $categories = Service::distinct()->pluck('category')->filter();

        return view('client.services', compact('services', 'categories'));
    }

    public function orders()
    {
        $orders = Order::where('client_id', Auth::id())
            ->with(['provider', 'service', 'review'])
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        if ($order->client_id !== Auth::id()) {
            abort(403, 'You can only view your own orders.');
        }

        $order->load(['provider', 'service', 'review']);

        return view('client.order-details', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = Order::where('client_id', $user->id)
            ->with(['provider', 'service'])
            ->latest()
            ->take(10)
            ->get();

        return view('client.profile', compact('user', 'orders'));
    }
}
