<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('client.orders') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Back to Orders
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Order Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $order->title }}</h3>
                            <p class="text-sm text-gray-600">Order #{{ $order->id }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $order->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>

                            @if($order->canBeCancelled())
                                <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                        Cancel Order
                                    </button>
                                </form>
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
                                            <span class="text-gray-900 font-semibold">${{ number_format($order->price, 2) }}</span>
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

                            @if($order->description)
                                <div class="mt-6">
                                    <h4 class="font-medium text-gray-900 mb-2">Your Requirements</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-gray-700">{{ $order->description }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($order->provider_notes)
                                <div class="mt-6">
                                    <h4 class="font-medium text-gray-900 mb-2">Provider Notes</h4>
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <p class="text-gray-700">{{ $order->provider_notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
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

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('messages.index', $order) }}" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center block">
                                    Contact Provider
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Messages</h3>
                                <a href="{{ route('messages.index', $order) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    View All Messages
                                </a>
                            </div>

                            @php
                                $recentMessages = \App\Models\Message::where('order_id', $order->id)
                                    ->with(['sender'])
                                    ->latest()
                                    ->take(3)
                                    ->get();
                            @endphp

                            @if($recentMessages->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentMessages as $message)
                                        <div class="border-l-4 border-blue-500 pl-3">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">{{ $message->sender->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $message->time_ago }}</p>
                                            </div>
                                            <p class="text-sm text-gray-600 truncate">{{ Str::limit($message->message, 50) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('messages.index', $order) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded transition duration-150 ease-in-out">
                                        Send Message
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 text-sm mb-3">No messages yet</p>
                                    <a href="{{ route('messages.index', $order) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded transition duration-150 ease-in-out">
                                        Start Conversation
                                    </a>
                                </div>
                            @endif
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
