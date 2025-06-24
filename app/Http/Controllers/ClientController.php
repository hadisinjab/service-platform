<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:client');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = $user->clientOrders()->with(['service', 'provider'])->latest()->take(5)->get();
        $totalOrders = $user->clientOrders()->count();
        $pendingOrders = $user->clientOrders()->where('status', 'pending')->count();
        $completedOrders = $user->clientOrders()->where('status', 'completed')->count();

        return view('client.dashboard', compact('recentOrders', 'totalOrders', 'pendingOrders', 'completedOrders'));
    }

    public function services()
    {
        $services = Service::with('provider')->active()->latest()->paginate(12);
        $categories = Service::distinct()->pluck('category');

        return view('client.services', compact('services', 'categories'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->clientOrders()->with(['service', 'provider'])->latest()->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function orderDetails($id)
    {
        $user = Auth::user();
        $order = $user->clientOrders()->with(['service', 'provider', 'review'])->findOrFail($id);

        return view('client.order-details', compact('order'));
    }

    public function profile()
    {
        return view('client.profile');
    }
}
