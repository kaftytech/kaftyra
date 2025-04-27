<div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-indigo-600 text-white px-6 py-4">
            <h4 class="text-xl font-semibold">
                @if($currentStep == 1)
                    Create New Invoice
                @elseif($currentStep == 2)
                    Preview Invoice
                @else
                    Invoice Created Successfully
                @endif
            </h4>
        </div>
        
        <div class="p-2">
            <!-- Step indicators -->
            <div class="mb-10">
                <div class="flex items-center justify-between">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center relative">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center {{ $currentStep >= 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                            <span class="text-lg font-bold">1</span>
                        </div>
                        <div class="mt-3 text-center w-32">
                            <span class="text-sm font-medium {{ $currentStep >= 1 ? 'text-indigo-600' : 'text-gray-500' }}">Invoice Details</span>
                        </div>
                    </div>
        
                    <!-- Line between Step 1 and 2 -->
                    <div class="flex-auto h-1 mx-2 {{ $currentStep >= 2 ? 'bg-indigo-600' : 'bg-gray-300' }}"></div>
        
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center relative">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center {{ $currentStep >= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                            <span class="text-lg font-bold">2</span>
                        </div>
                        <div class="mt-3 text-center w-32">
                            <span class="text-sm font-medium {{ $currentStep >= 2 ? 'text-indigo-600' : 'text-gray-500' }}">Preview & Confirm</span>
                        </div>
                    </div>
        
                    <!-- Line between Step 2 and 3 -->
                    <div class="flex-auto h-1 mx-2 {{ $currentStep >= 3 ? 'bg-indigo-600' : 'bg-gray-300' }}"></div>
        
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center relative">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center {{ $currentStep >= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                            <span class="text-lg font-bold">3</span>
                        </div>
                        <div class="mt-3 text-center w-32">
                            <span class="text-sm font-medium {{ $currentStep >= 3 ? 'text-indigo-600' : 'text-gray-500' }}">Print & Download</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if($currentStep == 1)
                <!-- Step 1: Invoice Details Form -->
                <form wire:submit.prevent="nextStep">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Invoice general information -->
                        <div>
                            <label for="orderRequestId" class="block text-sm font-medium text-gray-700 mb-1">Order Request Id</label>
                            <select wire:change="updateOrderRequestId($event.target.value)" id="orderRequestId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="customer" wire:model.live="order_request_id">
                                <option value="">Order Request ID</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}">{{ $order->order_id }}</option>
                                @endforeach
                            </select>
                            @error('order_request_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="invoiceNumber" class="block text-sm font-medium text-gray-700 mb-1">Invoice Number</label>
                            <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="invoiceNumber" wire:model.live="invoice_number">
                            @error('invoice_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="invoiceDate" class="block text-sm font-medium text-gray-700 mb-1">Invoice Date</label>
                            <input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="invoiceDate" wire:model.live="invoice_date">
                            @error('invoice_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="customer" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="customer" wire:model.live="customer_id">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mt-8 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HSN</th> --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th> --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoiceItems as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                            wire:model.live="invoiceItems.{{ $index }}.product_id"
                                            wire:change="productSelected({{ $index }}, $event.target.value)">
                                            <option value="">Select Product</option>
                                            @foreach($availableProducts as $product)
                                                <option value="{{ $product->id }}" {{ $product->id == $item['product_id'] ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->product_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("invoiceItems.$index.product_id") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm" 
                                            value="{{ $item['hsn_code'] ?? '' }}" readonly>
                                    </td> --}}                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                            wire:model.live="invoiceItems.{{ $index }}.price">
                                        @error("invoiceItems.$index.price") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                            wire:model.live="invoiceItems.{{ $index }}.quantity">
                                        @error("invoiceItems.$index.quantity") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $item['available_stock'] ?? 0 }}
                                        </span>
                                    </td> --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select wire:model.live="invoiceItems.{{ $index }}.discount_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                            <option value="">Discount Type</option>
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="free">Free</option>
                                        </select>
                                        @error("invoiceItems.$index.discount_type") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                            wire:model.live="invoiceItems.{{ $index }}.discount">
                                        @error("invoiceItems.$index.discount") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm" 
                                            value="{{ $item['tax_amount'] ?? 0 }}" readonly>
                                        <small class="text-gray-500">{{ $item['tax_percentage'] ?? 0 }}%</small>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm" 
                                            value="{{ $item['net_total'] ?? 0 }}" readonly>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                                            wire:click="removeItem({{ $index }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click="addItem">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Item
                            </button>
                        </div>
                    </div>

                    <!-- Invoice summary -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Additional Information</h3>
                            </div>
                            <div class="p-4">
                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="notes" rows="3" wire:model.live="notes"></textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="paymentMethod" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="paymentMethod" wire:model.live="payment_method">
                                            <option value="">Select Payment Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="upi">UPI</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="currency" wire:model.live="currency">
                                            <option value="INR">INR</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Invoice Summary</h3>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 gap-2 py-2">
                                    <div class="text-sm text-gray-600">Subtotal:</div>
                                    <div class="text-sm font-medium text-gray-900 text-right">{{ number_format($subtotal, 2) }}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 py-2">
                                    <div class="text-sm text-gray-600">
                                        <div class="flex items-center space-x-2">
                                            <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" wire:model.live="discount_type">
                                                <option value="fixed">Fixed</option>
                                                <option value="percentage">Percentage</option>
                                            </select>
                                            <div class="flex-1">
                                                <input type="number" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                                    placeholder="Discount" wire:model.live="discount">
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                {{ $discount_type == 'percentage' ? '%' : $currency }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 text-right">
                                        {{ $discount_type == 'percentage' ? 
                                            number_format(($subtotal * $discount / 100), 2) : 
                                            number_format($discount, 2) }}
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 py-2">
                                    <div class="text-sm text-gray-600">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1">
                                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                                    placeholder="Tax Type" wire:model.live="tax_type">
                                            </div>
                                            <div class="flex-1">
                                                <input type="number" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                                    placeholder="%" wire:model.live="tax_percentage">
                                            </div>
                                            <span class="text-sm text-gray-500">%</span>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 text-right">{{ number_format($tax_amount, 2) }}</div>
                                </div>
                                <div class="my-3 border-t border-gray-200"></div>
                                <div class="grid grid-cols-2 gap-2 py-2">
                                    <div class="text-sm font-bold text-gray-900">Total:</div>
                                    <div class="text-sm font-bold text-gray-900 text-right">{{ number_format($total_amount, 2) }}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 py-2">
                                    <div class="text-sm text-gray-600">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-500">Paid</span>
                                            <div class="flex-1">
                                                <input type="number" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                                    placeholder="Amount Paid" wire:model.live="paid_amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 text-right">{{ number_format($paid_amount, 2) }}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 py-2">
                                    <div class="text-sm font-bold text-gray-900">Due Amount:</div>
                                    <div class="text-sm font-bold text-gray-900 text-right">{{ number_format($due_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>
            @elseif($currentStep == 2)
            <!-- Step 2: Preview Invoice -->
            <div class="invoice-preview">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-indigo-600 mb-4">INVOICE</h3>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Invoice #:</span> {{ $invoice_number }}</p>
                            <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Date:</span> {{ date('d M, Y', strtotime($invoice_date)) }}</p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium text-gray-800">Status:</span> 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paid_amount >= $total_amount ? 'bg-green-100 text-green-800' : ($paid_amount > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $paid_amount >= $total_amount ? 'Paid' : ($paid_amount > 0 ? 'Partial' : 'Unpaid') }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Company Name</h4>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>123 Company Address</p>
                            <p>City, State, ZIP</p>
                            <p>Phone: (123) 456-7890</p>
                            <p>Email: example@company.com</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h5 class="text-lg font-medium text-gray-800 mb-2">Bill To:</h5>
                        @if($customer_id)
                            @php $customer = $customers->find($customer_id); @endphp
                            <div class="space-y-1 text-sm text-gray-600">
                                <p class="font-medium text-gray-800">{{ $customer->name }}</p>
                                <p>{{ $customer->address ?? '' }}</p>
                                <p>{{ $customer->email ?? '' }}</p>
                                <p>{{ $customer->phone ?? '' }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <h5 class="text-lg font-medium text-gray-800 mb-2">Payment Information:</h5>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium text-gray-800">Method:</span> {{ $payment_method ?? 'Not specified' }}</p>
                            <p><span class="font-medium text-gray-800">Currency:</span> {{ $currency }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Product Code</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Discount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Tax</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white border border-gray-200">
                                @foreach($invoiceItems as $index => $item)
                                    @if(!empty($item['product_id']))
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">{{ $item['product_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">{{ $item['product_code'] ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">{{ number_format($item['price'], 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">{{ $item['quantity'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">{{ number_format($item['discount'] ?? 0, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                                {{ number_format($item['tax_amount'] ?? 0, 2) }}
                                                <span class="text-xs text-gray-400">({{ $item['tax_percentage'] ?? 0 }}%)</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">{{ number_format($item['net_total'], 2) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                
                                <!-- Summary Rows -->
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-right font-bold text-gray-700 border border-gray-200">Subtotal</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 border border-gray-200">{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-right font-bold text-gray-700 border border-gray-200">Discount</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 border border-gray-200">{{ number_format($discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-right font-bold text-gray-700 border border-gray-200">Tax</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 border border-gray-200">{{ number_format($tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-right font-bold text-gray-700 border border-gray-200">Total</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 border border-gray-200">{{ number_format($total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-right font-bold text-green-500 border border-gray-200">Paid</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 border border-gray-200">{{ number_format($paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-right font-bold text-red-500 border border-gray-200">Due</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 border border-gray-200">{{ number_format($due_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end">
                    <select wire:model.live="invoiceType" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">                        
                        <option value="draft">Draft</option>
                        <option value="quotation">Quotation</option>
                        <option value="final">Final</option>
                    </select>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-300 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-4" wire:click="prevStep">
                        Back
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click="createInvoice">
                        Submit
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            
            @elseif($currentStep == 3)
            <!-- Step 3: Invoice Created Successfully -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="bg-indigo-600 text-white px-6 py-4">
                    <h4 class="text-xl font-semibold">Invoice Created Successfully</h4>
                </div>
                <div class="p-6">
                    <p class="text-lg text-gray-800">Invoice has been created successfully.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>