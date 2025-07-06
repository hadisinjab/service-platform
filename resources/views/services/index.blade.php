<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Services') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search and Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('services.index') }}" class="space-y-4">
                        <!-- Search Bar -->
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Services</label>
                                <input
                                    type="text"
                                    name="search"
                                    id="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search by service name, description, or category..."
                                    class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                >
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                    Search
                                </button>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="category" id="category" class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Min Price Filter -->
                            <div>
                                <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                                <input
                                    type="number"
                                    name="min_price"
                                    id="min_price"
                                    value="{{ request('min_price') }}"
                                    placeholder="0"
                                    min="0"
                                    step="0.01"
                                    class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                >
                            </div>

                            <!-- Max Price Filter -->
                            <div>
                                <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                                <input
                                    type="number"
                                    name="max_price"
                                    id="max_price"
                                    value="{{ request('max_price') }}"
                                    placeholder="1000"
                                    min="0"
                                    step="0.01"
                                    class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                >
                            </div>

                            <!-- Rating Filter -->
                            <div>
                                <label for="min_rating" class="block text-sm font-medium text-gray-700 mb-2">Min Rating</label>
                                <select name="min_rating" id="min_rating" class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="">Any Rating</option>
                                    <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                    <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                                    <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
                                </select>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        @if(request('search') || request('category') || request('min_price') || request('max_price') || request('min_rating'))
                            <div class="flex justify-between items-center">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                    Apply Filters
                                </button>
                                <a href="{{ route('services.index') }}" class="text-gray-600 hover:text-gray-800 underline">
                                    Clear All Filters
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-4">
                <p class="text-gray-600">
                    Found {{ $services->total() }} service(s)
                    @if(request('search') || request('category') || request('min_price') || request('max_price') || request('min_rating'))
                        matching your criteria
                    @endif
                </p>
            </div>

            <!-- Services Grid -->
            @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $service->title }}</h3>
                                    <span class="text-2xl font-bold text-blue-600">{{ $service->formatted_price }}</span>
                                </div>

                                <p class="text-gray-600 mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($service->description, 120) }}</p>

                                <!-- Rating -->
                                <div class="flex items-center mb-4">
                                    @if($service->reviews_avg_rating)
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $service->reviews_avg_rating)
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ number_format($service->reviews_avg_rating, 1) }} ({{ $service->reviews_count }} reviews)
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">No reviews yet</span>
                                    @endif
                                </div>

                                <!-- Service Details -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $service->category }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $service->location }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $service->provider->name }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <a href="{{ route('services.show', $service) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition-colors">
                                        View Details
                                    </a>
                                    <a href="{{ route('orders.create', ['service_id' => $service->id]) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-md transition-colors">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $services->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No services found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request('search') || request('category') || request('min_price') || request('max_price') || request('min_rating'))
                                Try adjusting your search criteria.
                            @else
                                No services are available at the moment.
                            @endif
                        </p>
                        @if(request('search') || request('category') || request('min_price') || request('max_price') || request('min_rating'))
                            <div class="mt-6">
                                <a href="{{ route('services.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    Clear Filters
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
