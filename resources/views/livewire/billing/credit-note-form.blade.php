<div>
    <div class="bg-white shadow-md mt-4 rounded-lg overflow-hidden">        
        <div class="p-6">
            <!-- Step 1: Invoice Details Form -->
            <form wire:submit.prevent="nextStep">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Invoice general information -->
                    <div>
                        <label for="creditNoteDate" class="block text-sm font-medium text-gray-700 mb-1">Credit Note Date</label>
                        <input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="creditNoteDate" wire:model.live="creditNoteDate">
                        @error('creditNoteDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="customer" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <span>{{ $invoice->customer->customer_name }}</span>
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
                                {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Type</th> --}}
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoiceItems as $index => $item)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap w-64">
                                    <div class="relative">
                                        <input type="text"
                                            placeholder="Search Product"
                                            class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            wire:model.live.debounce.500ms="invoiceItems.{{ $index }}.product_search"
                                            wire:keydown.arrow-down="highlightProductNextWrapper({{ $index }})"
                                            wire:keydown.arrow-up="highlightProductPreviousWrapper({{ $index }})"
                                            wire:keydown.enter="selectHighlightedProductWrapper({{ $index }})"
                                        >
                                
                                        <input type="hidden" wire:model.live="invoiceItems.{{ $index }}.product_id">
                                
                                        @if (!empty($invoiceItems[$index]['product_search']) && $invoiceItems[$index]['search_status'] !== 'selected')
                                            <ul class="absolute z-10 bg-white border w-full mt-1 rounded shadow">
                                                @if (!empty($invoiceItems[$index]['search_results']))
                                                    @foreach ($invoiceItems[$index]['search_results'] as $pIndex => $product)
                                                        <li wire:click.prevent="selectProductWrapper({{ $index }}, {{ $product['id'] }})"
                                                            class="px-3 py-2 hover:bg-slate-100 cursor-pointer @if($invoiceItems[$index]['highlight_index'] === $pIndex) bg-blue-100 @endif">
                                                            {{ $product['name'] }} ({{ $product['product_code'] }})
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li class="px-3 py-2 text-gray-500">No products found</li>
                                                @endif
                                            </ul>
                                        @endif
                                
                                        @error("invoiceItems.$index.product_id")
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </td>                                                                    
                                
                                {{-- <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="text" class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm" 
                                        value="{{ $item['hsn_code'] ?? '' }}" readonly>
                                </td> --}}                                    
                                <td class="px-1 py-4 whitespace-nowrap">
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
                                {{-- <td class="px-1 py-4 whitespace-nowrap">
                                    <select wire:model.live="invoiceItems.{{ $index }}.discount_type" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                        <option value="percentage">%</option>
                                        <option value="fixed">₹</option>
                                        <option value="free">Free</option>
                                    </select>
                                    @error("invoiceItems.$index.discount_type") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </td> --}}
                                <td class="whitespace-nowrap">
                                    <div class="flex w-full">
                                        <!-- Discount Type Dropdown -->
                                        <select wire:model.live="invoiceItems.{{ $index }}.discount_type"
                                            class="rounded-s-md border border-gray-300 bg-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" disabled>
                                            <option value="percentage">%</option>
                                            <option value="fixed">₹</option>
                                        </select>
                                        @if($invoiceItems[$index]['discount_type'] === 'percentage')
                                            <!-- Discount Input Field -->
                                            <input type="number" step="0.01"
                                                wire:model.live="invoiceItems.{{ $index }}.discount"
                                                class="rounded-e-md border border-l-0 border-gray-300 text-sm w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" 
                                                placeholder="Discount" readonly>
                                        @elseif($invoiceItems[$index]['discount_type'] === 'fixed')
                                            <!-- Discount Input Field -->
                                            <input type="number" step="0.01"
                                                wire:model.live="invoiceItems.{{ $index }}.discount_amount"
                                                    class="rounded-e-md border border-l-0 border-gray-300 text-sm w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" 
                                                placeholder="Discount" readonly>
                                        @endif
                                    </div>
                                
                                    <!-- Validation Errors -->
                                    @error("invoiceItems.$index.discount_type") 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                    @error("invoiceItems.$index.discount") 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex w-full">                                          
                                
                                        <!-- Tax Amount Input (readonly) -->
                                        <input type="text"
                                            class="rounded-e-md border border-l-0 border-gray-300 text-sm w-full px-3 py-2 bg-gray-100"
                                            value="{{ $item['tax_amount'] ?? 0 }}"
                                            readonly>
                                            <!-- Static Tax Percentage Display (acts like a disabled select) -->
                                        <span class="inline-flex items-center px-3 bg-gray-200 rounded-s-md border border-gray-300 bg-gray-100 text-sm text-gray-700">
                                            {{ $item['tax_percentage'] ?? 0 }}%
                                        </span>
                                    </div>
                                </td>
                                
                                <td class="text-right whitespace-nowrap">
                                    <input type="text" class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm" 
                                        value="{{ $item['net_total'] ?? 0 }}" readonly>
                                </td>
                                <td class="px-2 text-right whitespace-nowrap">
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
                    {{-- <div class="mt-3">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click="addItem">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add Item
                        </button>
                    </div> --}}
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
                        </div>
                    </div>
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Credit Note Summary</h3>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-2 py-2">
                                <div class="text-sm text-gray-600">Subtotal:</div>
                                <div class="text-sm font-medium text-gray-900 text-right">{{ number_format($subtotal, 2) }}</div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 py-2">
                                <div class="text-sm text-gray-600">
                                    <div class="flex items-center space-x-2">
                                        <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" wire:model.live="discount_type" disabled>
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                        <div class="flex-1">
                                            <input type="number" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                                placeholder="Discount" wire:model.live="discount" readonly>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            {{ $discount_type == 'percentage' ? '%' : $currency }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-gray-900 text-right">
                                    {{
                                        $discount_type == 'percentage'
                                        ? number_format(((float)$subtotal * (float)$discount / 100), 2)
                                        : number_format((float)$discount, 2)
                                    }}
                                </div>                                    
                            </div>
                            @foreach($taxes as $tax)
                                <div class="grid grid-cols-2 gap-2 py-2">
                                    <div class="text-sm text-gray-600">
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.live="selectedTaxes.{{ $tax->name }}" class="text-indigo-500" disabled>
                                            <div class="flex-1">
                                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm text-sm bg-gray-100"
                                                    value="{{ $tax->name }}" readonly>
                                            </div>
                                            <div class="flex-1">
                                                <input type="number" step="0.01"
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" readonly
                                                    wire:model.live.debounce.300ms="taxValues.{{ $tax->name }}"
                                                    @if(!($selectedTaxes[$tax->name] ?? false)) disabled @endif>
                                            </div>
                                            <span class="text-sm text-gray-500">%</span>
                                        </div>
                                    </div>

                                    <div class="text-sm font-medium text-gray-900 text-right">
                                        ₹{{ number_format($this->calculateTax($tax->name), 2) }}
                                    </div>
                                </div>
                            @endforeach

                            <div class="my-3 border-t border-gray-200"></div>
                            <div class="grid grid-cols-2 gap-2 py-2">
                                <div class="text-sm font-bold text-gray-900">Total:</div>
                                <div class="text-sm font-bold text-gray-900 text-right">{{ number_format($total_amount, 2) }}</div>
                            </div>
                            {{-- <div class="grid grid-cols-2 gap-2 py-2">
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
                            </div> --}}
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
        </div>
    </div>
</div>