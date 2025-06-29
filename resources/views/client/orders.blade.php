<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Order List</h3>

                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $order->id }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $order->service->title ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $order->provider->name ?? '-' }}</td>
                                        <td class="px-4 py-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $order->status_badge }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-green-700 font-semibold">${{ number_format($order->price, 2) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <a href="{{ route('orders.show', $order) }}"
                                               style="background: #e3342f; color: #fff; border: 2px solid #000; padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-block;">
                                                VIEW DETAILS TEST
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
