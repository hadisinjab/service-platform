<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Write a Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Order Information -->
                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-500">Service:</span>
                                <span class="text-gray-900">{{ $order->service->title }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Provider:</span>
                                <span class="text-gray-900">{{ $order->provider->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Order Date:</span>
                                <span class="text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Price:</span>
                                <span class="text-green-600 font-semibold">${{ number_format($order->price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('reviews.store', $order) }}" class="space-y-6">
                        @csrf

                        <!-- Rating -->
                        <div>
                            <x-input-label for="rating" :value="__('Rating')" />
                            <div class="mt-2 flex items-center space-x-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="flex items-center">
                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <div class="text-2xl cursor-pointer rating-star" data-rating="{{ $i }}">
                                            <span class="text-gray-300 hover:text-yellow-400 transition-colors">★</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <span id="rating-text">Select a rating</span>
                            </div>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <!-- Comment -->
                        <div>
                            <x-input-label for="comment" :value="__('Review Comment')" />
                            <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Share your experience with this service...">{{ old('comment') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Tell others about your experience (optional but helpful)</p>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>

                        <!-- Public Review -->
                        <div class="flex items-center">
                            <input id="is_public" name="is_public" type="checkbox" value="1" {{ old('is_public', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                Make this review public
                            </label>
                        </div>
                        <p class="text-sm text-gray-500">Public reviews help other clients choose the right provider</p>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Submit Review') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingStars = document.querySelectorAll('.rating-star');
            const ratingText = document.getElementById('rating-text');
            const ratingInputs = document.querySelectorAll('input[name="rating"]');

            const ratingDescriptions = {
                1: 'Very Poor - Not satisfied at all',
                2: 'Poor - Below expectations',
                3: 'Average - Met basic expectations',
                4: 'Good - Exceeded expectations',
                5: 'Excellent - Outstanding service'
            };

            function updateRating(rating) {
                ratingStars.forEach((star, index) => {
                    const starNumber = index + 1;
                    const starElement = star.querySelector('span');

                    if (starNumber <= rating) {
                        starElement.className = 'text-yellow-400';
                    } else {
                        starElement.className = 'text-gray-300 hover:text-yellow-400 transition-colors';
                    }
                });

                ratingText.textContent = ratingDescriptions[rating] || 'Select a rating';
            }

            ratingStars.forEach((star, index) => {
                const rating = index + 1;

                star.addEventListener('click', () => {
                    const radio = star.parentElement.querySelector('input[type="radio"]');
                    radio.checked = true;
                    updateRating(rating);
                });

                star.addEventListener('mouseenter', () => {
                    updateRating(rating);
                });

                star.addEventListener('mouseleave', () => {
                    const checkedRating = document.querySelector('input[name="rating"]:checked');
                    updateRating(checkedRating ? parseInt(checkedRating.value) : 0);
                });
            });

            // Initialize with checked rating if exists
            const checkedRating = document.querySelector('input[name="rating"]:checked');
            if (checkedRating) {
                updateRating(parseInt(checkedRating.value));
            }
        });
    </script>
    @endpush
</x-app-layout>
