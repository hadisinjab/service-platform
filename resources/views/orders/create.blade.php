<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

                    <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                        @csrf

                        <!-- Service Selection -->
                        <div>
                            <x-input-label for="service_id" :value="__('Select Service')" />
                            <select id="service_id" name="service_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Choose a service...</option>
                                @foreach($categories as $category)
                                    <optgroup label="{{ $category }}">
                                        @foreach($services->where('category', $category) as $service)
                                            <option value="{{ $service->id }}"
                                                    data-price="{{ $service->price }}"
                                                    data-provider="{{ $service->provider->name }}"
                                                    data-description="{{ $service->description }}"
                                                    {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->title }} - ${{ number_format($service->price, 2) }} ({{ $service->provider->name }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                        </div>

                        <!-- Service Details Preview -->
                        <div id="service-preview" class="hidden bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Service Details</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Provider:</span>
                                    <span id="provider-name"></span>
                                </div>
                                <div>
                                    <span class="font-medium">Price:</span>
                                    <span id="service-price" class="text-green-600 font-semibold"></span>
                                </div>
                                <div class="col-span-2">
                                    <span class="font-medium">Description:</span>
                                    <p id="service-description" class="text-gray-600 mt-1"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Title -->
                        <div>
                            <x-input-label for="title" :value="__('Order Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Order Description -->
                        <div>
                            <x-input-label for="description" :value="__('Order Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Scheduled Date -->
                        <div>
                            <x-input-label for="scheduled_date" :value="__('Scheduled Date (Optional)')" />
                            <x-text-input id="scheduled_date" class="block mt-1 w-full" type="datetime-local" name="scheduled_date" :value="old('scheduled_date')" />
                            <x-input-error :messages="$errors->get('scheduled_date')" class="mt-2" />
                        </div>

                        <!-- Location -->
                        <div>
                            <x-input-label for="location" :value="__('Service Location (Optional)')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" placeholder="Enter service location" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="contact_phone" :value="__('Contact Phone (Optional)')" />
                                <x-text-input id="contact_phone" class="block mt-1 w-full" type="tel" name="contact_phone" :value="old('contact_phone')" placeholder="+1 (555) 123-4567" />
                                <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="contact_email" :value="__('Contact Email (Optional)')" />
                                <x-text-input id="contact_email" class="block mt-1 w-full" type="email" name="contact_email" :value="old('contact_email')" placeholder="contact@example.com" />
                                <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Client Notes -->
                        <div>
                            <x-input-label for="client_notes" :value="__('Additional Notes (Optional)')" />
                            <textarea id="client_notes" name="client_notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Any special requirements or notes for the provider...">{{ old('client_notes') }}</textarea>
                            <x-input-error :messages="$errors->get('client_notes')" class="mt-2" />
                        </div>

                        <!-- Order Summary -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-900 mb-2">Order Summary</h4>
                            <div class="text-sm text-blue-800">
                                <p><strong>Service:</strong> <span id="summary-service">Not selected</span></p>
                                <p><strong>Provider:</strong> <span id="summary-provider">Not selected</span></p>
                                <p><strong>Total Price:</strong> <span id="summary-price" class="font-semibold">$0.00</span></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Create Order') }}
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
            const serviceSelect = document.getElementById('service_id');
            const servicePreview = document.getElementById('service-preview');
            const providerName = document.getElementById('provider-name');
            const servicePrice = document.getElementById('service-price');
            const serviceDescription = document.getElementById('service-description');
            const summaryService = document.getElementById('summary-service');
            const summaryProvider = document.getElementById('summary-provider');
            const summaryPrice = document.getElementById('summary-price');

            const services = @json($services);

            function updateServicePreview(serviceId) {
                if (serviceId) {
                    const service = services.find(s => s.id == serviceId);
                    if (service) {
                        // Show service preview
                        servicePreview.classList.remove('hidden');
                        providerName.textContent = service.provider.name;
                        servicePrice.textContent = '$' + parseFloat(service.price).toFixed(2);
                        serviceDescription.textContent = service.description;

                        // Update summary
                        summaryService.textContent = service.title;
                        summaryProvider.textContent = service.provider.name;
                        summaryPrice.textContent = '$' + parseFloat(service.price).toFixed(2);
                    }
                } else {
                    // Hide service preview
                    servicePreview.classList.add('hidden');
                    summaryService.textContent = 'Not selected';
                    summaryProvider.textContent = 'Not selected';
                    summaryPrice.textContent = '$0.00';
                }
            }

            serviceSelect.addEventListener('change', function() {
                updateServicePreview(this.value);
            });

            // Initialize with selected service if exists
            const selectedServiceId = serviceSelect.value;
            if (selectedServiceId) {
                updateServicePreview(selectedServiceId);
            }
        });
    </script>
    @endpush
</x-app-layout>
