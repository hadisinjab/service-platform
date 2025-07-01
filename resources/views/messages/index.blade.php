<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Messages') }} - Order #{{ $order->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('orders.show', $order) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Back to Order
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

            <!-- Order Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $order->title }}</h3>
                            <p class="text-gray-600">{{ $order->service->title }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Messages</h3>
                        <button id="markAllRead" class="text-sm text-blue-600 hover:text-blue-800">
                            Mark all as read
                        </button>
                    </div>

                    <!-- Messages List -->
                    <div id="messagesContainer" class="space-y-4 max-h-96 overflow-y-auto mb-6">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="bg-gray-100 rounded-lg px-4 py-2 {{ $message->sender_id === Auth::id() ? 'bg-blue-100' : 'bg-gray-100' }}">
                                        <div class="flex items-start space-x-2">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ substr($message->sender->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $message->sender->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $message->time_ago }}
                                                    </p>
                                                </div>

                                                <div class="mt-1">
                                                    <p class="text-sm text-gray-700">{{ $message->message }}</p>

                                                    @if($message->hasAttachment())
                                                        <div class="mt-2">
                                                            @if($message->isImage())
                                                <img src="{{ Storage::url($message->file_path) }}" alt="Attachment" class="max-w-xs rounded">
                                            @else
                                                <a href="{{ route('messages.download', $message) }}"
                                                   class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Download {{ $message->file_extension }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                @if(!$message->is_read && $message->receiver_id === Auth::id())
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            New
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No messages yet</h3>
                        <p class="text-gray-500">Start the conversation by sending a message below.</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Form -->
            <div class="bg-blue-50 border-2 border-blue-400 shadow-sm sm:rounded-lg my-8">
                <div class="p-6">
                    <h4 class="text-lg font-bold text-blue-700 mb-4">أرسل رسالة جديدة</h4>
                    <form method="POST" action="{{ route('messages.store', $order) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                            <textarea
                                name="message"
                                id="message"
                                rows="3"
                                class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                placeholder="Type your message here..."
                                required
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Attachment (Optional)</label>
                            <input
                                type="file"
                                name="file"
                                id="file"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200"
                                accept="image/*,.pdf,.doc,.docx,.txt"
                            >
                            <p class="mt-1 text-sm text-gray-500">Max file size: 10MB. Supported: Images, PDF, DOC, TXT</p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="border-2 border-blue-700 bg-blue-600 hover:bg-blue-800 text-blue-900 font-bold py-2 px-6 rounded transition duration-150 ease-in-out shadow">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-scroll to bottom of messages
        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
        }

        // Mark all messages as read
        document.getElementById('markAllRead').addEventListener('click', function() {
            fetch('{{ route("messages.mark-all-read", $order) }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove "New" badges
                    document.querySelectorAll('.bg-red-100').forEach(badge => {
                        badge.remove();
                    });
                }
            });
        });

        // Scroll to bottom on page load
        scrollToBottom();

        // Auto-refresh messages every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
    @endpush
</x-app-layout>
