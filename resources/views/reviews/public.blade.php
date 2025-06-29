<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reviews for') }} {{ $provider->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Provider Info & Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-xl font-medium text-gray-700">{{ substr($provider->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $provider->name }}</h3>
                                <p class="text-gray-600">{{ $provider->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl text-yellow-400 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($averageRating, 1) }}/5</p>
                            <p class="text-sm text-gray-500">{{ $totalReviews }} reviews</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>

                    @if($reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">{{ substr($review->client->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $review->client->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-yellow-400 text-lg">{{ $review->stars }}</div>
                                            <p class="text-xs text-gray-500">{{ $review->rating_text }}</p>
                                        </div>
                                    </div>

                                    @if($review->comment)
                                        <div class="mt-3">
                                            <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                                        </div>
                                    @endif

                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <p class="text-xs text-gray-500">
                                            Service: <span class="font-medium">{{ $review->order->service->title }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                            <p class="mt-1 text-sm text-gray-500">This provider hasn't received any reviews yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
