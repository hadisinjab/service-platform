<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $recentOrders = Order::where('provider_id', $user->id)
            ->with(['client', 'service'])
            ->latest()
            ->take(5)
            ->get();

        $pendingOrders = Order::where('provider_id', $user->id)
            ->where('status', Order::STATUS_PENDING)
            ->count();

        $completedOrders = Order::where('provider_id', $user->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->count();

        $totalEarnings = Order::where('provider_id', $user->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('price');

        $averageRating = Review::where('provider_id', $user->id)
            ->where('is_public', true)
            ->avg('rating');

        $totalReviews = Review::where('provider_id', $user->id)
            ->where('is_public', true)
            ->count();

        return view('provider.dashboard', compact(
            'recentOrders',
            'pendingOrders',
            'completedOrders',
            'totalEarnings',
            'averageRating',
            'totalReviews'
        ));
    }

    public function services()
    {
        $services = Service::where('provider_id', Auth::id())
            ->with(['orders', 'reviews'])
            ->latest()
            ->paginate(10);

        return view('provider.services', compact('services'));
    }

    public function orders()
    {
        $orders = Order::where('provider_id', Auth::id())
            ->with(['client', 'service', 'review'])
            ->latest()
            ->paginate(10);

        return view('provider.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        if ($order->provider_id !== Auth::id()) {
            abort(403, 'You can only view your own orders.');
        }

        $order->load(['client', 'service', 'review']);

        return view('provider.order-details', compact('order'));
    }

    public function reviews()
    {
        $reviews = Review::where('provider_id', Auth::id())
            ->where('is_public', true)
            ->with(['client', 'order'])
            ->latest()
            ->paginate(10);

        $averageRating = Review::where('provider_id', Auth::id())
            ->where('is_public', true)
            ->avg('rating');

        $totalReviews = Review::where('provider_id', Auth::id())
            ->where('is_public', true)
            ->count();

        return view('provider.reviews', compact('reviews', 'averageRating', 'totalReviews'));
    }

    public function profile()
    {
        $user = Auth::user();
        $services = Service::where('provider_id', $user->id)->count();
        $orders = Order::where('provider_id', $user->id)->count();
        $reviews = Review::where('provider_id', $user->id)->count();
        $averageRating = Review::where('provider_id', $user->id)->avg('rating');

        return view('provider.profile', compact('user', 'services', 'orders', 'reviews', 'averageRating'));
    }
}
