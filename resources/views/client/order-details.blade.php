<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('client.orders') }}" class="text-blue-600 hover:text-blue-800">
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
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                            @elseif($order->status === 'in_progress') bg-orange-100 text-orange-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
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
                                    <h4 class="font-medium text-gray-900 mb-2">Requirements</h4>
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

                    <!-- Review Section -->
                    @if($order->status === 'completed')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review</h3>

                                @if($order->review)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $order->review->rating)
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-600">{{ $order->review->rating }}/5</span>
                                        </div>
                                        @if($order->review->comment)
                                            <p class="text-gray-700">{{ $order->review->comment }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500 mt-2">Reviewed on {{ $order->review->created_at->format('M d, Y') }}</p>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-gray-500 mb-4">No review yet</p>
                                        <a href="#" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                            Write Review
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Provider Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Provider Information</h3>

                            <div class="text-center mb-4">
                                <div class="w-16 h-16 bg-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900">{{ $order->provider->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $order->provider->email }}</p>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Service Category:</span>
                                    <span class="text-gray-900">{{ $order->service->category }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Location:</span>
                                    <span class="text-gray-900">{{ $order->service->location ?? 'Not specified' }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="#" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center block">
                                    Contact Provider
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
