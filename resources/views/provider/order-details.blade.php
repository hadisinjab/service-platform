<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('provider.orders') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
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

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Order Status Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $order->title }}</h3>
                            <p class="text-gray-600">Order #{{ $order->id }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <p class="text-sm text-gray-500 mt-1">Created {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Order Details -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Order Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Service</p>
                                    <p class="text-gray-900">{{ $order->service->title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Price</p>
                                    <p class="text-green-600 font-semibold">${{ number_format($order->price, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Client</p>
                                    <p class="text-gray-900">{{ $order->client->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Client Email</p>
                                    <p class="text-gray-900">{{ $order->client->email }}</p>
                                </div>
                                @if($order->scheduled_date)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Scheduled Date</p>
                                    <p class="text-gray-900">{{ $order->scheduled_date->format('M d, Y g:i A') }}</p>
                                </div>
                                @endif
                                @if($order->location)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Location</p>
                                    <p class="text-gray-900">{{ $order->location }}</p>
                                </div>
                                @endif
                                @if($order->contact_phone)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Contact Phone</p>
                                    <p class="text-gray-900">{{ $order->contact_phone }}</p>
                                </div>
                                @endif
                                @if($order->contact_email)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Contact Email</p>
                                    <p class="text-gray-900">{{ $order->contact_email }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Description -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                            <p class="text-gray-700">{{ $order->description }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($order->client_notes || $order->provider_notes)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                            @if($order->client_notes)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 mb-2">Client Notes</p>
                                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $order->client_notes }}</p>
                            </div>
                            @endif
                            @if($order->provider_notes)
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Your Notes</p>
                                <p class="text-gray-700 bg-blue-50 p-3 rounded">{{ $order->provider_notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Review Section -->
                    @if($order->status === 'completed')
                        @if($order->review)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Client Review</h3>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center mb-2">
                                            <div class="text-yellow-600 text-lg">{{ $order->review->stars }}</div>
                                            <span class="ml-2 text-sm text-gray-600">({{ $order->review->rating_text }})</span>
                                        </div>
                                        @if($order->review->comment)
                                            <p class="text-gray-700">{{ $order->review->comment }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">Reviewed on {{ $order->review->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Status</h3>
                                    <p class="text-gray-600">Waiting for client to leave a review.</p>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Status Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>

                            @if($errors->any())
                                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded text-sm">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                                    <select name="status" id="status" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @if($order->canBeAccepted())
                                            <option value="accepted">✅ Accept Order</option>
                                            <option value="rejected">❌ Reject Order</option>
                                        @endif
                                        @if($order->canBeStarted())
                                            <option value="in_progress">🚀 Start Work</option>
                                        @endif
                                        @if($order->canBeCompleted())
                                            <option value="completed">✅ Mark as Completed</option>
                                        @endif
                                    </select>
                                </div>

                                <div>
                                    <label for="provider_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                    <textarea name="provider_notes" id="provider_notes" rows="3" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Add any notes for the client...">{{ old('provider_notes') }}</textarea>
                                </div>

                                <button type="submit" style="background: #2563eb; color: #fff; border: 2px solid #000; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-block; width: 100%;">
                                    UPDATE STATUS
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="mailto:{{ $order->client->email }}" style="background: #16a34a; color: #fff; border: 2px solid #000; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                                    <span>📧</span> Email Client
                                </a>
                                @if($order->contact_phone)
                                <a href="tel:{{ $order->contact_phone }}" style="background: #16a34a; color: #fff; border: 2px solid #000; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                                    <span>📞</span> Call Client
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Order Created</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                @if($order->scheduled_date)
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Scheduled</p>
                                        <p class="text-xs text-gray-500">{{ $order->scheduled_date->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                @endif
                                @if($order->completed_date)
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Completed</p>
                                        <p class="text-xs text-gray-500">{{ $order->completed_date->format('M d, Y g:i A') }}</p>
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
