<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Section -->
            <div class="bg-white rounded-lg shadow-lg mb-8 border-l-4 border-blue-500">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600">Here's what's happening with your platform today.</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Users Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalUsers) }}</p>
                                <p class="text-xs text-green-600">+{{ $newUsersThisMonth }} this month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active Services</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($activeServices) }}</p>
                                <p class="text-xs text-gray-600">of {{ number_format($totalServices) }} total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($pendingOrders) }}</p>
                                <p class="text-xs text-yellow-600">of {{ number_format($totalOrders) }} total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
                                <p class="text-xs text-green-600">from {{ number_format($completedOrders) }} orders</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Monthly Statistics Chart -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Growth</h3>
                        <div class="h-64 flex items-end justify-between space-x-2">
                            @foreach($monthlyStats->take(6) as $stat)
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full bg-blue-200 rounded-t" style="height: {{ ($stat['users'] / max($monthlyStats->pluck('users')->toArray())) * 200 }}px">
                                        <div class="w-full bg-blue-500 rounded-t" style="height: {{ ($stat['orders'] / max($monthlyStats->pluck('orders')->toArray())) * 200 }}px"></div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">{{ $stat['month'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-center space-x-4 mt-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Users</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-200 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Orders</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services by Category -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Services by Category</h3>
                        <div class="space-y-3">
                            @foreach($servicesByCategory as $category)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ $category->category }}</span>
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($category->count / $totalServices) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $category->count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <div>
                                <p class="font-medium text-blue-900">Manage Users</p>
                                <p class="text-sm text-blue-600">View and manage all users</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.services') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                            </svg>
                            <div>
                                <p class="font-medium text-green-900">Manage Services</p>
                                <p class="text-sm text-green-600">View and manage all services</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.orders') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div>
                                <p class="font-medium text-yellow-900">View Orders</p>
                                <p class="text-sm text-yellow-600">Monitor all orders</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.reports') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <div>
                                <p class="font-medium text-purple-900">Reports</p>
                                <p class="text-sm text-purple-600">View analytics & reports</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                            <a href="{{ route('admin.orders') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentOrders as $order)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($order->title, 30) }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->client->name }} → {{ $order->provider->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">${{ number_format($order->price, 2) }}</p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No recent orders</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                            <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentUsers as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                        @php
                                            $userRole = $user->roles->first();
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $userRole ? ucfirst($userRole->name) : 'No Role' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No recent users</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="mt-8 bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">Database</p>
                            <p class="text-xs text-gray-500">{{ $systemHealth['database_size'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">Storage</p>
                            <p class="text-xs text-gray-500">{{ $systemHealth['storage_usage'] }} used</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">Performance</p>
                            <p class="text-xs text-gray-500">{{ $systemHealth['performance_metrics']['avg_response_time'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">Uptime</p>
                            <p class="text-xs text-gray-500">{{ $systemHealth['performance_metrics']['uptime'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
