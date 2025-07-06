<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reports & Analytics') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $userGrowth['total_users'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                    <dd class="text-lg font-medium text-gray-900">${{ number_format($revenueAnalysis['total_revenue'], 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $orderAnalytics['total_orders'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Services</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $servicePerformance['active_services'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- User Growth Chart -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Growth</h3>
                        <div class="space-y-4">
                            @foreach($userGrowth['monthly_growth'] as $month => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ $month }}</span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($count / max($userGrowth['monthly_growth'])) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Revenue Analysis -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Analysis</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Monthly Revenue</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($revenueAnalysis['monthly_revenue'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Average Order Value</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($revenueAnalysis['average_order_value'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Revenue Growth</span>
                                <span class="text-sm font-medium text-green-600">+{{ $revenueAnalysis['growth_percentage'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Performance -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Performance</h3>
                        <div class="space-y-4">
                            @foreach($servicePerformance['top_categories'] as $category => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ ucfirst($category) }}</span>
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($count / max($servicePerformance['top_categories'])) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Analytics -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Analytics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Completed Orders</span>
                                <span class="text-sm font-medium text-green-600">{{ $orderAnalytics['completed_orders'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending Orders</span>
                                <span class="text-sm font-medium text-yellow-600">{{ $orderAnalytics['pending_orders'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Cancelled Orders</span>
                                <span class="text-sm font-medium text-red-600">{{ $orderAnalytics['cancelled_orders'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Completion Rate</span>
                                <span class="text-sm font-medium text-gray-900">{{ $orderAnalytics['completion_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Reports -->
            <div class="mt-8 bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Reports</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Top Performing Services</h4>
                            <div class="space-y-2">
                                @foreach($servicePerformance['top_services'] as $service)
                                    <div class="text-sm text-gray-600">{{ $service->title }} - ${{ number_format($service->total_revenue, 2) }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">User Registration Trends</h4>
                            <div class="space-y-2">
                                @foreach($userGrowth['registration_trends'] as $trend)
                                    <div class="text-sm text-gray-600">{{ $trend->month }} - {{ $trend->count }} users</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Revenue by Category</h4>
                            <div class="space-y-2">
                                @foreach($revenueAnalysis['revenue_by_category'] as $category => $revenue)
                                    <div class="text-sm text-gray-600">{{ ucfirst($category) }} - ${{ number_format($revenue, 2) }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
