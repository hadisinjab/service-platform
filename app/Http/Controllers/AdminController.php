<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Order;
use App\Models\Review;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{

    /**
     * Admin Dashboard with comprehensive statistics
     */
    public function dashboard()
    {
        // User Statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();

        $usersByRole = User::with('roles')
            ->get()
            ->groupBy(function($user) {
                $role = $user->roles->first();
                return $role ? $role->name : 'No Role';
            })
            ->map->count();

        // Service Statistics
        $totalServices = Service::count();
        $activeServices = Service::where('is_active', true)->count();
        $servicesByCategory = Service::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        // Order Statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $completedOrders = Order::where('status', Order::STATUS_COMPLETED)->count();
        $totalRevenue = Order::where('status', Order::STATUS_COMPLETED)->sum('price');

        // Recent Activity
        $recentOrders = Order::with(['client', 'provider', 'service'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Monthly Statistics for Charts
        $monthlyStats = $this->getMonthlyStatistics();

        // System Health
        $systemHealth = $this->getSystemHealth();

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'activeUsers',
            'usersByRole',
            'totalServices',
            'activeServices',
            'servicesByCategory',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders',
            'recentUsers',
            'monthlyStats',
            'systemHealth'
        ));
    }

    /**
     * User Management
     */
    public function users(Request $request)
    {
        $query = User::with('roles');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('last_login_at', '>=', Carbon::now()->subDays(30));
            } elseif ($request->status === 'inactive') {
                $query->where('last_login_at', '<', Carbon::now()->subDays(30))
                      ->orWhereNull('last_login_at');
            }
        }

        $users = $query->latest()->paginate(15);
        $roles = DB::table('roles')->pluck('name');

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Service Management
     */
    public function services(Request $request)
    {
        $query = Service::with(['provider']);

        // Search
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

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $services = $query->latest()->paginate(15);
        $categories = Service::distinct()->pluck('category');

        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Order Management
     */
    public function orders(Request $request)
    {
        $query = Order::with(['client', 'provider', 'service']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User {$user->name} has been {$status} successfully.");
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Check if user has orders
        if ($user->orders()->exists() || $user->services()->exists()) {
            return back()->with('error', 'Cannot delete user with existing orders or services.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle service status
     */
    public function toggleServiceStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);

        $status = $service->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Service '{$service->title}' has been {$status} successfully.");
    }

    /**
     * Delete service
     */
    public function deleteService(Service $service)
    {
        // Check if service has orders
        if ($service->orders()->exists()) {
            return back()->with('error', 'Cannot delete service with existing orders.');
        }

        $service->delete();
        return back()->with('success', 'Service deleted successfully.');
    }

    /**
     * Get monthly statistics for charts
     */
    private function getMonthlyStatistics()
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'orders' => Order::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'revenue' => Order::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->sum('price'),
            ]);
        }

        return $months;
    }

    /**
     * Get system health metrics
     */
    private function getSystemHealth()
    {
        return [
            'database_size' => $this->getDatabaseSize(),
            'storage_usage' => $this->getStorageUsage(),
            'recent_errors' => $this->getRecentErrors(),
            'performance_metrics' => $this->getPerformanceMetrics(),
        ];
    }

    private function getDatabaseSize()
    {
        // This would typically query the database for size information
        // For now, return a placeholder
        return '2.5 GB';
    }

    private function getStorageUsage()
    {
        // This would check file storage usage
        return '45%';
    }

    private function getRecentErrors()
    {
        // This would check recent error logs
        return 0;
    }

    private function getPerformanceMetrics()
    {
        // This would include response times, etc.
        return [
            'avg_response_time' => '120ms',
            'uptime' => '99.9%',
        ];
    }

    /**
     * System Settings
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Reports and Analytics
     */
    public function reports()
    {
        $userGrowth = $this->getUserGrowthReport();
        $revenueAnalysis = $this->getRevenueAnalysis();
        $servicePerformance = $this->getServicePerformance();
        $orderAnalytics = $this->getOrderAnalytics();

        return view('admin.reports', compact('userGrowth', 'revenueAnalysis', 'servicePerformance', 'orderAnalytics'));
    }

    private function getUserGrowthReport()
    {
        $totalUsers = User::count();
        $monthlyGrowth = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyGrowth[$month] = $count;
        }

        $registrationTrends = User::selectRaw('DATE_FORMAT(created_at, "%M %Y") as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'total_users' => $totalUsers,
            'monthly_growth' => $monthlyGrowth,
            'registration_trends' => $registrationTrends
        ];
    }

    private function getRevenueAnalysis()
    {
        $totalRevenue = Order::where('status', Order::STATUS_COMPLETED)->sum('price');
        $monthlyRevenue = Order::where('status', Order::STATUS_COMPLETED)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('price');

        $averageOrderValue = Order::where('status', Order::STATUS_COMPLETED)->avg('price') ?? 0;

        // Calculate growth percentage (simplified)
        $lastMonthRevenue = Order::where('status', Order::STATUS_COMPLETED)
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->sum('price');

        $growthPercentage = $lastMonthRevenue > 0 ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        $revenueByCategory = Order::join('services', 'orders.service_id', '=', 'services.id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->select('services.category', DB::raw('SUM(orders.price) as revenue'))
            ->groupBy('services.category')
            ->pluck('revenue', 'category')
            ->toArray();

        return [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'average_order_value' => $averageOrderValue,
            'growth_percentage' => round($growthPercentage, 1),
            'revenue_by_category' => $revenueByCategory
        ];
    }

    private function getServicePerformance()
    {
        $activeServices = Service::where('is_active', true)->count();

        $topCategories = Service::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->pluck('count', 'category')
            ->toArray();

        $topServices = Service::withCount('orders')
            ->withSum('orders', 'price')
            ->orderByDesc('orders_sum_price')
            ->take(5)
            ->get()
            ->map(function ($service) {
                $service->total_revenue = $service->orders_sum_price ?? 0;
                return $service;
            });

        return [
            'active_services' => $activeServices,
            'top_categories' => $topCategories,
            'top_services' => $topServices
        ];
    }

    private function getOrderAnalytics()
    {
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', Order::STATUS_COMPLETED)->count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $completionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

        return [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'cancelled_orders' => $cancelledOrders,
            'completion_rate' => $completionRate
        ];
    }

    /**
     * Delete an order
     */
    public function deleteOrder(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted successfully.');
    }
}
