<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Vendors -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Vendor</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalVendors }}</div>
                </div>

                <!-- Total Branches -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Cabang</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalBranches }}</div>
                </div>

                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total User</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalUsers }}</div>
                </div>

                <!-- Total Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Global Transactions</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalTransactions }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
