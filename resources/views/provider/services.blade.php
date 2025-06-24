<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Services') }}
            </h2>
            <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add New Service
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($services->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($services as $service)
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $service->title }}</h3>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($service->status === 'active') bg-green-100 text-green-800
                                                @elseif($service->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($service->status) }}
                                            </span>
                                        </div>

                                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 100) }}</p>

                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-2xl font-bold text-blue-600">${{ number_format($service->price, 2) }}</span>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($service->category) }}
                                            </span>
                                        </div>

                                        @if($service->location)
                                            <p class="text-sm text-gray-500 mb-4">{{ $service->location }}</p>
                                        @endif

                                        <div class="flex space-x-2">
                                            <a href="#" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded text-center hover:bg-blue-600">
                                                Edit
                                            </a>
                                            <a href="#" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $services->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No services yet</h3>
                            <p class="text-gray-500 mb-6">Start by adding your first service to attract clients.</p>
                            <a href="#" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                                Add Your First Service
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
