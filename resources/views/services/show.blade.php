<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $service->title }}
            </h2>
            <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Services
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Service Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $service->title }}</h1>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $service->category }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $service->location }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-blue-600">{{ $service->formatted_price }}</div>
                                    <div class="text-sm text-gray-500">per service</div>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center mb-6">
                                @if($averageRating)
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $averageRating)
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-600">
                                        {{ number_format($averageRating, 1) }} ({{ $totalReviews }} reviews)
                                    </span>
                                @else
                                    <span class="text-gray-500">No reviews yet</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $service->description }}</p>
                            </div>

                            <!-- Availability -->
                            @if($service->availability)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Availability</h3>
                                    <div class="grid grid-cols-7 gap-2">
                                        @php
                                            $days = ['monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun'];
                                        @endphp
                                        @foreach($days as $day => $label)
                                            <div class="text-center">
                                                <div class="text-xs text-gray-500 mb-1">{{ $label }}</div>
                                                <div class="w-8 h-8 rounded-full {{ $service->availability[$day] ?? false ? 'bg-green-500' : 'bg-gray-200' }} flex items-center justify-center">
                                                    @if($service->availability[$day] ?? false)
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    @if($service->reviews->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Reviews ({{ $service->reviews->count() }})</h3>
                                <div class="space-y-4">
                                    @foreach($service->reviews->take(5) as $review)
                                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">{{ substr($review->client->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $review->client->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-yellow-400 text-sm">{{ $review->stars }}</div>
                                                    <p class="text-xs text-gray-500">{{ $review->rating_text }}</p>
                                                </div>
                                            </div>
                                            @if($review->comment)
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if($service->reviews->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">View all {{ $service->reviews->count() }} reviews</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Provider Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Provider</h3>
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-700">{{ substr($service->provider->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $service->provider->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $service->location }}</p>
                                </div>
                            </div>
                            <a href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition-colors">
                                View Provider Profile
                            </a>
                        </div>
                    </div>

                    <!-- Booking Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Book This Service</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Price:</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $service->formatted_price }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Rating:</span>
                                    <span class="text-gray-900">
                                        @if($averageRating)
                                            {{ number_format($averageRating, 1) }}/5
                                        @else
                                            No reviews
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Orders:</span>
                                    <span class="text-gray-900">{{ $service->orders_count }}</span>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('orders.create', ['service_id' => $service->id]) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-md font-semibold transition-colors">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Related Services -->
                    @if($relatedServices->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Services</h3>
                                <div class="space-y-3">
                                    @foreach($relatedServices as $relatedService)
                                        <a href="{{ route('services.show', $relatedService) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900">{{ $relatedService->title }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $relatedService->formatted_price }}</p>
                                                </div>
                                                @if($relatedService->reviews_avg_rating)
                                                    <div class="text-yellow-400 text-sm">{{ number_format($relatedService->reviews_avg_rating, 1) }}</div>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
