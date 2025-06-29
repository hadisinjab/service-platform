<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderStatusChangedNotification;
use App\Notifications\OrderCreatedNotification;

class OrderController extends Controller
{
    // Show create order form
    public function create()
    {
        $services = Service::active()->with('provider')->get();
        $categories = Service::distinct()->pluck('category')->filter();

        return view('orders.create', compact('services', 'categories'));
    }

    // Store new order
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_date' => 'nullable|date|after:today',
            'location' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'client_notes' => 'nullable|string',
        ]);

        $service = Service::findOrFail($request->service_id);

        $order = Order::create([
            'client_id' => Auth::id(),
            'provider_id' => $service->provider_id,
            'service_id' => $service->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $service->price,
            'status' => Order::STATUS_PENDING,
            'scheduled_date' => $request->scheduled_date,
            'location' => $request->location,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'client_notes' => $request->client_notes,
        ]);

        // Notify provider
        $order->provider->notify(new NewOrderNotification($order));

        // Notify client about order creation
        $order->client->notify(new OrderCreatedNotification($order));

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully! The provider will review your request.');
    }

    // Show order details
    public function show(Order $order)
    {
        // Manual authorization check
        if ($order->client_id !== Auth::id() && $order->provider_id !== Auth::id()) {
            abort(403, 'You can only view your own orders.');
        }

        $order->load(['client', 'provider', 'service', 'review']);

        return view('orders.show', compact('order'));
    }

    // Update order status (Provider actions)
    public function updateStatus(Request $request, Order $order)
    {
        // Manual authorization check
        if ($order->provider_id !== Auth::id()) {
            abort(403, 'Only the provider can update order status.');
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,in_progress,completed',
            'provider_notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Validate status transition
        if (!$this->canTransitionStatus($order, $newStatus)) {
            return back()->withErrors(['status' => 'Invalid status transition']);
        }

        $order->update([
            'status' => $newStatus,
            'provider_notes' => $request->provider_notes,
            'completed_date' => $newStatus === Order::STATUS_COMPLETED ? now() : null,
        ]);

        // Notify client
        $order->client->notify(new OrderStatusChangedNotification($order, $oldStatus, $newStatus));

        $statusMessages = [
            'accepted' => 'Order accepted successfully!',
            'rejected' => 'Order rejected.',
            'in_progress' => 'Order marked as in progress.',
            'completed' => 'Order completed successfully!',
        ];

        return back()->with('success', $statusMessages[$newStatus] ?? 'Order status updated.');
    }

    // Cancel order (Client action)
    public function cancel(Order $order)
    {
        // Manual authorization check
        if ($order->client_id !== Auth::id()) {
            abort(403, 'Only the client can cancel their own orders.');
        }

        if (!$order->canBeCancelled()) {
            return back()->withErrors(['order' => 'This order cannot be cancelled.']);
        }

        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'client_notes' => $order->client_notes . "\n[CANCELLED] " . now()->format('Y-m-d H:i:s'),
        ]);

        // TODO: Send notification to provider
        // event(new OrderCancelled($order));

        return back()->with('success', 'Order cancelled successfully.');
    }

    // Client orders list
    public function clientOrders()
    {
        $orders = Order::where('client_id', Auth::id())
            ->with(['provider', 'service'])
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    // Provider orders list
    public function providerOrders()
    {
        $orders = Order::where('provider_id', Auth::id())
            ->with(['client', 'service'])
            ->latest()
            ->paginate(10);

        return view('provider.orders', compact('orders'));
    }

    // Search and filter orders
    public function search(Request $request)
    {
        $query = Order::query();

        if (Auth::user()->hasRole('client')) {
            $query->where('client_id', Auth::id());
        } elseif (Auth::user()->hasRole('provider')) {
            $query->where('provider_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $orders = $query->with(['client', 'provider', 'service'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Helper method to validate status transitions
    private function canTransitionStatus(Order $order, string $newStatus): bool
    {
        $validTransitions = [
            Order::STATUS_PENDING => [Order::STATUS_ACCEPTED, Order::STATUS_REJECTED],
            Order::STATUS_ACCEPTED => [Order::STATUS_IN_PROGRESS],
            Order::STATUS_IN_PROGRESS => [Order::STATUS_COMPLETED],
        ];

        return in_array($newStatus, $validTransitions[$order->status] ?? []);
    }
}
