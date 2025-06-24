<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('provider.orders') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Order Status</h3>
                            <p class="text-sm text-gray-600">Order #{{ $order->id }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                                @elseif($order->status === 'in_progress') bg-orange-100 text-orange-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>

                            @if($order->status === 'pending')
                                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Accept Order
                                </button>
                                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Reject Order
                                </button>
                            @elseif($order->status === 'accepted')
                                <button class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                                    Start Work
                                </button>
                            @elseif($order->status === 'in_progress')
                                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Mark Complete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Service Details</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-lg font-semibold text-gray-900">{{ $order->service->title }}</p>
                                        <p class="text-sm text-gray-600 mb-2">{{ $order->service->category }}</p>
                                        <p class="text-gray-700">{{ $order->service->description }}</p>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Order Details</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Order Date:</span>
                                            <span class="text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Total Amount:</span>
                                            <span class="text-gray-900 font-semibold">${{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                        @if($order->scheduled_date)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Scheduled Date:</span>
                                                <span class="text-gray-900">{{ $order->scheduled_date->format('M d, Y') }}</span>
                                            </div>
                                        @endif
                                        @if($order->scheduled_time)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Scheduled Time:</span>
                                                <span class="text-gray-900">{{ $order->scheduled_time }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($order->requirements)
                                <div class="mt-6">
                                    <h4 class="font-medium text-gray-900 mb-2">Client Requirements</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-gray-700">{{ $order->requirements }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($order->notes)
                                <div class="mt-6">
                                    <h4 class="font-medium text-gray-900 mb-2">Notes</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-gray-700">{{ $order->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Client Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Client Information</h3>

                            <div class="text-center mb-4">
                                <div class="w-16 h-16 bg-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900">{{ $order->client->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $order->client->email }}</p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="#" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center block">
                                    Contact Client
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h3>

                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Order Placed</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>

                                @if($order->accepted_at)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Order Accepted</p>
                                            <p class="text-xs text-gray-500">{{ $order->accepted_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($order->completed_at)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Order Completed</p>
                                            <p class="text-xs text-gray-500">{{ $order->completed_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
