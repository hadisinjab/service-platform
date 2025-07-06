<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of services with search and filter
     */
    public function index(Request $request)
    {
        $query = Service::active()->with(['provider']);

        // Search by service name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by rating (we'll need to join with reviews)
        if ($request->filled('min_rating')) {
            $query->whereHas('reviews', function ($q) use ($request) {
                $q->havingRaw('AVG(rating) >= ?', [$request->min_rating]);
            });
        }

        // Get categories for filter dropdown
        $categories = Service::distinct()->pluck('category')->filter();

        // Get services with average rating
        $services = $query->withAvg('reviews', 'rating')
                         ->withCount('reviews')
                         ->latest()
                         ->paginate(12);

        return view('services.index', compact('services', 'categories'));
    }

    /**
     * Show service details
     */
    public function show(Service $service)
    {
        $service->load(['provider', 'reviews.client']);

        // Get average rating
        $averageRating = $service->reviews()->avg('rating');
        $totalReviews = $service->reviews()->count();

        // Get related services
        $relatedServices = Service::where('category', $service->category)
                                 ->where('id', '!=', $service->id)
                                 ->withAvg('reviews', 'rating')
                                 ->limit(4)
                                 ->get();

        return view('services.show', compact('service', 'averageRating', 'totalReviews', 'relatedServices'));
    }
}
