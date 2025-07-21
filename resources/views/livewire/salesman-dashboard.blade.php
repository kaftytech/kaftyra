<!-- resources/views/livewire/salesman-dashboard.blade.php -->
<div class="salesman-app bg-gray-50 max-w-md mx-auto min-h-screen relative pb-16">
    <!-- App Header -->
    <div class="bg-indigo-700 text-white px-4 py-3 shadow-md">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h1 class="text-xl font-bold">SalesPro</h1>
            </div>
            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                    <div class="relative">
                        <div class="bg-indigo-600 rounded-full w-8 h-8 flex items-center justify-center">
                            <span class="text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                        <span class="absolute bottom-0 right-0 block h-2 w-2 rounded-full bg-green-400 ring-2 ring-indigo-700"></span>
                    </div>
                </button>
                
                <!-- Dropdown menu -->
                <div x-show="open" 
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-1 z-50 border border-gray-100">
                    <div class="px-4 py-3 border-b">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-3 flex space-x-2 overflow-x-auto pb-2 hide-scrollbar">
            <button wire:click="switchTab('new-order')" class="flex-shrink-0 bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full text-sm flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>New Order</span>
            </button>
            <button wire:click="switchTab('assigned-orders')" class="flex-shrink-0 bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full text-sm flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>My Orders</span>
            </button>
            <button wire:click="switchTab('stock')" class="flex-shrink-0 bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full text-sm flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Inventory</span>
            </button>
        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    
    <!-- Dashboard Content -->
    @if($currentTab === 'dashboard')
    <div class="p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Assigned Orders Card -->
            <div wire:click="switchTab('assigned-orders')" class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 shadow-md text-white cursor-pointer transform hover:scale-[1.02] transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium">Assigned Orders</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $dashboardData['assigned_orders'] }}</h3>
                        <p class="text-xs opacity-90 mt-2">Pending delivery</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- New Order Card -->
            <div wire:click="switchTab('new-order')" class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 shadow-md text-white cursor-pointer transform hover:scale-[1.02] transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium">New Order</p>
                        <h3 class="text-2xl font-bold mt-1">+ Create</h3>
                        <p class="text-xs opacity-90 mt-2">Start new transaction</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stock Track Card -->
            <div wire:click="switchTab('stock')" class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-4 shadow-md text-white cursor-pointer transform hover:scale-[1.02] transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium">Stock Track</p>
                        <h3 class="text-2xl font-bold mt-1">Inventory</h3>
                        <p class="text-xs opacity-90 mt-2">Check product availability</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today's Line Products Card -->
            <div wire:click="switchTab('todayLineProducts')" class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 shadow-md text-white cursor-pointer transform hover:scale-[1.02] transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium">Today's Line</p>
                        <h3 class="text-2xl font-bold mt-1">Products</h3>
                        <p class="text-xs opacity-90 mt-2">View today's orders</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Recent Activity</h3>
            </div>
            <div class="divide-y divide-gray-100">
                <!-- Sample activity items - replace with real data -->
                <div class="px-4 py-3 flex items-center">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Order #1234 completed</p>
                        <p class="text-xs text-gray-500">2 hours ago</p>
                    </div>
                </div>
                <div class="px-4 py-3 flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Payment received from ABC Store</p>
                        <p class="text-xs text-gray-500">5 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Today's Line Products Table -->
    @elseif($currentTab === 'todayLineProducts')
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Today's Line Products</h2>
                    <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <button wire:click="switchTab('dashboard')" class="text-gray-500 hover:text-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </button>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="Search products..." 
                            wire:model.live.debounce.500ms="searchQuery">
                    </div>
                    <button wire:click="loadTodayLineProducts" class="ml-2 p-2 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Code
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($todayLineProducts as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-blue-50 p-2 rounded-lg mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $product['name'] }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $product['product_code'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $product['product_code'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <button wire:click="showCustomerQuantities('{{ $product['product_id'] }}')"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors cursor-pointer">
                                        {{ $product['total_quantity'] }} pcs
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h4 class="mt-3 text-lg font-medium text-gray-700">No products ordered today</h4>
                                        <p class="mt-1 text-gray-500">Products will appear here once ordered</p>
                                        <button wire:click="switchTab('new-order')" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Create New Order
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        <!-- Customer Quantities Modal -->
        @if($showCustomerModal)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ $selectedProduct->name }} Distribution
                                </h3>
                                <div class="mt-4">
                                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Customer
                                                    </th>
                                                    <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Quantity
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse($customerQuantities as $customer)
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $customer['customer_name'] }}
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $customer['total_quantity'] }} pcs
                                                        </span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="px-4 py-4 text-center text-sm text-gray-500">
                                                        No customer orders found for today
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeCustomerModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    <!-- Assigned Orders -->
    @elseif($currentTab === 'assigned-orders')
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">Assigned Orders</h5>
                <button class="btn btn-sm btn-outline-secondary" wire:click="switchTab('dashboard')">
                    Back
                </button>
            </div>
            
            @foreach($assignedOrders as $delivery)
                <div class="bg-white rounded-lg shadow-sm p-4 mb-3" 
                     wire:click="viewInvoice({{ $delivery->invoice_id }})">
                    <div class="flex justify-between">
                        <div>
                            <h6 class="font-bold">Order #{{ $delivery->invoice->invoice_number }}</h6>
                            <p class="text-sm text-gray-600 mb-1">Customer: {{ $delivery->invoice->customer->customer_name }}</p>
                            <p class="text-sm text-gray-600">Due: {{ $delivery->invoice->due_date ? $delivery->invoice->due_date->format('M d, Y') : '' }}</p>
                        </div>
                        <div class="bg-green-500 rounded-full w-8 h-8 flex items-center justify-center">
                            <span class="text-white">›</span>
                        </div>
                    </div>
                </div>
            @endforeach
            
            {{ $assignedOrders->links() }}
        </div>
    
    <!-- Invoice Details -->
    @elseif($currentTab === 'invoice')
    <div class="p-4">
        <div class="flex justify-between items-center mb-3">
            <h5 class="text-lg font-bold">Order #{{ $invoiceDetails->invoice_number }}</h5>
            <button class="text-gray-500" wire:click="switchTab('assigned-orders')">
                ← Back
            </button>
        </div>
        
        <!-- Customer Details -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-3">
            <h6 class="font-bold mb-2">Customer Details:</h6>
            <p class="mb-1">{{ $invoiceDetails->customer->customer_name }}</p>
            <p class="mb-1">{{ $invoiceDetails->customer->address_line_1 }}</p>
            <p class="mb-1">{{ $invoiceDetails->customer->address_line_2 }}</p>
            <p class="mb-1">Phone: {{ $invoiceDetails->customer->phone }}</p>
        </div>
        
        <!-- Items Table -->
        <div class="bg-white rounded-lg shadow-sm p-0 mb-3 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left p-2">Item</th>
                        <th class="text-left p-2">Qty</th>
                        <th class="text-left p-2">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoiceDetails->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="p-2">{{ $item->product->name }}</td>
                            <td class="p-2">{{ $item->quantity }}</td>
                            <td class="p-2">₹{{ number_format($item->price, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="border-t border-gray-200">
                        <td colspan="2" class="p-2 font-bold">Total:</td>
                        <td class="p-2 font-bold">₹{{ number_format($invoiceDetails->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="p-2">Paid:</td>
                        <td class="p-2">₹{{ number_format($invoiceDetails->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="p-2">Due:</td>
                        <td class="p-2">₹{{ number_format($invoiceDetails->due_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Payment Section -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-3">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Amount Received</label>
                <input type="number" class="w-full rounded border-gray-300" 
                    wire:model="amount" step="0.01" min="0.01">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Payment Method</label>
                <select class="w-full rounded border-gray-300" wire:model="paymentMethod">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
        </div>       
        <!-- Delivery Button -->
        <button class="w-full bg-green-500 text-white py-2 rounded-lg font-medium" 
                wire:click="markAsDelivered"
                @if(!$invoiceDetails->signature && $requireSignature) disabled @endif>
            Mark as Delivered
        </button>
    </div>
    <!-- Orders List -->
    @elseif($currentTab === 'orders')
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">All Orders</h5>
                <div>
                    <button class="bg-green-500 text-white text-sm px-3 py-1 rounded-full mr-2" 
                            wire:click="switchTab('new-order')">
                        + New
                    </button>
                    <button class="text-gray-500" wire:click="switchTab('dashboard')">
                        ← Back
                    </button>
                </div>
            </div>
            
            @foreach($allOrders as $order)
                <div class="bg-white rounded-lg shadow-sm p-4 mb-3" wire:click="showOrder({{ $order->id }})">
                    <div class="flex justify-between">
                        <div>
                            <h6 class="font-bold">Order #{{ $order->id }}</h6>
                            <p class="text-sm text-gray-600 mb-1">Customer: {{ $order->customer->customer_name }}</p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            @if($order->status === 'approved')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">✓</span>
                            @elseif($order->status === 'processing')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">⟳</span>
                            @elseif($order->status === 'pending')
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">!</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">⌛</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            
            {{ $allOrders->links() }}
        </div>
    @elseif($currentTab === 'order-show')
    <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">Order #{{ $orderDetails->order_id }}</h5>
                <button class="text-gray-500" wire:click="switchTab('orders')">
                    ← Back
                </button>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-4 mb-3">
                <h6 class="font-bold mb-2">Customer Details:</h6>
                <p class="mb-1">{{ $orderDetails->customer->customer_name }}</p>
                <p class="mb-1">{{ $orderDetails->customer->address_line_1 }}</p>
                <p class="mb-1">{{ $orderDetails->customer->address_line_2 }}</p>
                <p class="mb-1">Phone: {{ $orderDetails->customer->phone }}</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-0 mb-3 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left p-2">Item</th>
                            <th class="text-left p-2">Qty</th>
                            <th class="text-left p-2">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderDetails->orderItems as $item)
                            <tr class="border-b border-gray-100">
                                <td class="p-2">{{ $item->product->name }}</td>
                                <td class="p-2">{{ $item->quantity }}</td>
                                <td class="p-2">${{ number_format($item->price, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="border-t border-gray-200">
                            <td colspan="2" class="p-2 font-bold">Total:</td>
                            <td class="p-2 font-bold">${{ number_format($orderDetails->total_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- New Order Form -->
    @elseif($currentTab === 'new-order')
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">Create New Order</h5>
                <button class="text-gray-500" wire:click="switchTab('orders')">
                    ← Back
                </button>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-4 mb-3">
                <!-- Customer Search -->
                <div class="mb-3" x-data="{
                    customerSearch: '',
                    customerDropdownOpen: false,
                    customers: [],
                    isLoading: false,
                    
                    async searchCustomers() {
                        if (this.customerSearch.length < 2) return;
                        
                        this.isLoading = true;
                        this.customers = await $wire.searchCustomers(this.customerSearch);
                        this.isLoading = false;
                        this.customerDropdownOpen = true;
                    },
                    
                    selectCustomer(id, name) {
                        @this.set('customerId', id);
                        this.customerSearch = name;
                        this.customerDropdownOpen = false;
                    }
                }">
                    <label class="block text-sm font-medium mb-1">Customer</label>
                    <div class="relative">
                        <input x-model="customerSearch" 
                            @input.debounce.300ms="searchCustomers"
                            @click="customerDropdownOpen = true"
                            @click.away="customerDropdownOpen = false"
                            class="w-full rounded border-gray-300"
                            placeholder="Search customer...">
                        
                        <div x-show="customerDropdownOpen" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-auto">
                            <template x-if="isLoading">
                                <div class="px-4 py-2 text-gray-500">Loading...</div>
                            </template>
                            
                            <template x-if="!isLoading && customers && customers.length">
                                <template x-for="customer in customers">
                                    <div @click="selectCustomer(customer.id, customer.customer_name)"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        <span x-text="customer.customer_name"></span>
                                    </div>
                                </template>
                            </template>
                            
                            <div x-show="!isLoading && customers && !customers.length" class="px-4 py-2 text-gray-500">
                                No customers found
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Search -->
                <div class="mb-3" x-data="{
                    productSearch: '',
                    productDropdownOpen: false,
                    products: [],
                    isLoading: false,
                    
                    async searchProducts() {
                        if (this.productSearch.length < 2) return;
                        
                        this.isLoading = true;
                        this.products = await $wire.searchProducts(this.productSearch);
                        this.isLoading = false;
                        this.productDropdownOpen = true;
                    },
                    
                    selectProduct(id, name, code) {
                        @this.set('productId', id);
                        this.productSearch = name + ' (' + code + ')';
                        this.productDropdownOpen = false;
                    }
                }">
                    <label class="block text-sm font-medium mb-1">Product</label>
                    <div class="relative">
                        <input x-model="productSearch" 
                            @input.debounce.300ms="searchProducts"
                            @click="productDropdownOpen = true"
                            @click.away="productDropdownOpen = false"
                            class="w-full rounded border-gray-300"
                            placeholder="Search product...">
                        
                        <div x-show="productDropdownOpen" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-auto">
                            <template x-if="isLoading">
                                <div class="px-4 py-2 text-gray-500">Loading...</div>
                            </template>
                            
                            <template x-if="!isLoading && products && products.length">
                                <template x-for="product in products">
                                    <div @click="selectProduct(product.id, product.name, product.product_code)"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        <span x-text="product.name + ' (' + product.product_code + ')'"></span>
                                    </div>
                                </template>
                            </template>
                            
                            <div x-show="!isLoading && products && !products.length" class="px-4 py-2 text-gray-500">
                                No products found
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Quantity</label>
                    <input type="number" class="w-full rounded border-gray-300" 
                           wire:model.live="quantity" min="1">
                </div>
                
                <button class="w-full bg-green-500 text-white py-2 rounded-lg font-medium" 
                        wire:click="addOrderItem" 
                        @if(!$productId || !$quantity) disabled @endif>
                    + Add Item
                </button>
            </div>
            
            @if(count($orderItems) > 0)
                <div class="bg-white rounded-lg shadow-sm p-0 mb-3 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left p-2">Item</th>
                                <th class="text-left p-2">Qty</th>
                                <th class="text-left p-2">Price</th>
                                <th class="text-right p-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderItems as $index => $item)
                                <tr class="border-b border-gray-100">
                                    <td class="p-2">{{ $item['name'] }}</td>
                                    <td class="p-2">{{ $item['quantity'] }}</td>
                                    <td class="p-2">{{ number_format($item['price'], 2) }}</td>
                                    <td class="p-2 text-right">
                                        <button class="text-red-500 text-sm" 
                                                wire:click="removeOrderItem({{ $index }})">
                                            ×
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="border-t border-gray-200">
                                <td colspan="2" class="p-2 font-bold">Total:</td>
                                <td colspan="2" class="p-2 font-bold">{{ number_format($this->calculateTotal(), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                 <textarea type="text" class="w-full rounded border-gray-300" 
                           wire:model="orderNotes" placeholder="Notes"></textarea>
                <button class="w-full bg-blue-500 text-white py-2 rounded-lg font-medium" 
                        wire:click="submitOrder" 
                        @if(!$customerId) disabled @endif>
                    Create Order
                </button>
            @endif
        </div>
    
    <!-- Stock Tracker -->
    @elseif($currentTab === 'stock')
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">Stock Tracker</h5>
                <button class="text-gray-500" wire:click="switchTab('dashboard')">
                    ← Back
                </button>
            </div>
            
          <div class="bg-white rounded-lg shadow-sm p-4 mb-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search products..." 
                        wire:model.live.debounce.500ms="searchQuery">
                </div>
            </div>
            
            @foreach($stockProducts as $product)
                <div class="bg-white rounded-lg shadow-sm p-4 mb-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <h6 class="font-bold mb-1">{{ $product->name }}</h6>
                            <p class="text-sm text-gray-600">HSN Code: {{ $product->hsn_code }}</p>
                        </div>
                        <div>
                           @php
                                $stock = optional($product->stock)->current_stock;
                            @endphp

                            @if($stock > 20)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $stock }}
                                </span>
                            @elseif($stock > 5)
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $stock }}
                                </span>
                            @elseif($stock > 0)
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $stock }}
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $stock ?? 0 }}
                                </span>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
            
            {{ $stockProducts->links() }}
            
            <div class="bg-white rounded-lg shadow-sm p-4 mt-3">
                <h6 class="font-bold mb-2">Color Legend:</h6>
                <div class="flex items-center mb-2">
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">10</span>
                    <span class="text-sm">In Stock (Good quantity)</span>
                </div>
                <div class="flex items-center mb-2">
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">5</span>
                    <span class="text-sm">Running Low (Order soon)</span>
                </div>
                <div class="flex items-center mb-2">
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">2</span>
                    <span class="text-sm">Critical Low (Urgent restock)</span>
                </div>
                <div class="flex items-center">
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">0</span>
                    <span class="text-sm">Out of Stock</span>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg">
        <div class="flex justify-around">
            <button wire:click="switchTab('dashboard')" class="flex flex-col items-center justify-center p-2 text-center w-full hover:bg-gray-50 transition-colors">
                <div class="text-2xl @if($currentTab === 'dashboard') text-indigo-600 @else text-gray-400 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span class="text-xs @if($currentTab === 'dashboard') text-indigo-600 font-medium @else text-gray-500 @endif">Dashboard</span>
            </button>
            
            <button wire:click="switchTab('assigned-orders')" class="flex flex-col items-center justify-center p-2 text-center w-full hover:bg-gray-50 transition-colors">
                <div class="text-2xl @if($currentTab === 'assigned-orders') text-indigo-600 @else text-gray-400 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-xs @if($currentTab === 'assigned-orders') text-indigo-600 font-medium @else text-gray-500 @endif">Orders</span>
            </button>
            
            <button wire:click="switchTab('new-order')" class="flex flex-col items-center justify-center p-2 text-center w-full hover:bg-gray-50 transition-colors">
                <div class="text-2xl @if($currentTab === 'new-order') text-indigo-600 @else text-gray-400 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <span class="text-xs @if($currentTab === 'new-order') text-indigo-600 font-medium @else text-gray-500 @endif">New</span>
            </button>
            
            <button wire:click="switchTab('stock')" class="flex flex-col items-center justify-center p-2 text-center w-full hover:bg-gray-50 transition-colors">
                <div class="text-2xl @if($currentTab === 'stock') text-indigo-600 @else text-gray-400 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-xs @if($currentTab === 'stock') text-indigo-600 font-medium @else text-gray-500 @endif">Stock</span>
            </button>
        </div>
    </div>
</div>