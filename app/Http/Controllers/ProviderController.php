<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:provider');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = $user->providerOrders()->with(['service', 'client'])->latest()->take(5)->get();
        $totalOrders = $user->providerOrders()->count();
        $pendingOrders = $user->providerOrders()->where('status', 'pending')->count();
        $completedOrders = $user->providerOrders()->where('status', 'completed')->count();
        $totalServices = $user->services()->count();
        $activeServices = $user->services()->where('status', 'active')->count();

        return view('provider.dashboard', compact('recentOrders', 'totalOrders', 'pendingOrders', 'completedOrders', 'totalServices', 'activeServices'));
    }

    public function services()
    {
        $user = Auth::user();
        $services = $user->services()->latest()->paginate(10);

        return view('provider.services', compact('services'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->providerOrders()->with(['service', 'client'])->latest()->paginate(10);

        return view('provider.orders', compact('orders'));
    }

    public function orderDetails($id)
    {
        $user = Auth::user();
        $order = $user->providerOrders()->with(['service', 'client', 'review'])->findOrFail($id);

        return view('provider.order-details', compact('order'));
    }

    public function profile()
    {
        return view('provider.profile');
    }
}
