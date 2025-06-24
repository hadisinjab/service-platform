<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Welcome to Admin Dashboard</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-100 p-6 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">User Management</h4>
                            <p class="text-blue-600 mb-4">View and manage all users in the system</p>
                            <a href="{{ route('admin.users') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                View Users
                            </a>
                        </div>

                        <div class="bg-green-100 p-6 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">Providers</h4>
                            <p class="text-green-600 mb-4">Manage service providers</p>
                            <a href="{{ route('admin.providers') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                View Providers
                            </a>
                        </div>

                        <div class="bg-purple-100 p-6 rounded-lg">
                            <h4 class="font-semibold text-purple-800 mb-2">Clients</h4>
                            <p class="text-purple-600 mb-4">Manage system clients</p>
                            <a href="{{ route('admin.clients') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                                View Clients
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
