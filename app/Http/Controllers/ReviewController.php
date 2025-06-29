<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewReviewNotification;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show review form
    public function create(Order $order)
    {
        // Only client can review completed orders
        if ($order->client_id !== Auth::id()) {
            abort(403, 'You can only review your own orders.');
        }

        if (!$order->canBeReviewed()) {
            return back()->withErrors(['order' => 'This order cannot be reviewed.']);
        }

        return view('reviews.create', compact('order'));
    }

    // Store review
    public function store(Request $request, Order $order)
    {
        // Only client can review completed orders
        if ($order->client_id !== Auth::id()) {
            abort(403, 'You can only review your own orders.');
        }

        if (!$order->canBeReviewed()) {
            return back()->withErrors(['order' => 'This order cannot be reviewed.']);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $review = Review::create([
            'order_id' => $order->id,
            'client_id' => Auth::id(),
            'provider_id' => $order->provider_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_public' => $request->boolean('is_public', true),
        ]);

        // Notify provider
        $order->provider->notify(new NewReviewNotification($review));

        // TODO: Send notification to provider
        // event(new ReviewCreated($review));

        return redirect()->route('orders.show', $order)
            ->with('success', 'Review submitted successfully!');
    }

    // Show review
    public function show(Review $review)
    {
        if (!$review->is_public && $review->client_id !== Auth::id() && $review->provider_id !== Auth::id()) {
            abort(403, 'This review is private.');
        }

        return view('reviews.show', compact('review'));
    }

    // Update review
    public function update(Request $request, Review $review)
    {
        if ($review->client_id !== Auth::id()) {
            abort(403, 'You can only edit your own reviews.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_public' => $request->boolean('is_public', true),
        ]);

        return back()->with('success', 'Review updated successfully!');
    }

    // Delete review
    public function destroy(Review $review)
    {
        if ($review->client_id !== Auth::id()) {
            abort(403, 'You can only delete your own reviews.');
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully!');
    }

    // Provider reviews list
    public function providerReviews()
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

    // Public reviews for a provider
    public function publicReviews($providerId)
    {
        $provider = \App\Models\User::findOrFail($providerId);

        $reviews = Review::where('provider_id', $providerId)
            ->where('is_public', true)
            ->with(['client', 'order'])
            ->latest()
            ->paginate(10);

        $averageRating = Review::where('provider_id', $providerId)
            ->where('is_public', true)
            ->avg('rating');

        $totalReviews = Review::where('provider_id', $providerId)
            ->where('is_public', true)
            ->count();

        return view('reviews.public', compact('reviews', 'averageRating', 'totalReviews', 'provider'));
    }
}
