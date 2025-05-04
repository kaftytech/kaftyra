<div class="mt-8 overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                {{-- @if($isAddingDiscountAndTax) --}}
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Type</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                {{-- @endif --}}
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($returnItems as $index => $item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <select wire:model="returnItems.{{ $index }}.product_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                    wire:change="productSelected({{ $index }}, $event.target.value)">
                        <option value="">Select Product</option>
                        @foreach($availableProducts as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error("returnItems.$index.product_id") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" wire:model="returnItems.{{ $index }}.price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" readonly>
                    @error("returnItems.$index.price") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" wire:model.live="returnItems.{{ $index }}.quantity" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error("returnItems.$index.quantity") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <select wire:model.live="returnItems.{{ $index }}.discount_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" disabled>
                        <option value="">Discount Type</option>
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed Amount</option>
                        <option value="free">Free</option>
                    </select>
                    @error("returnItems.$index.discount_type") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                        wire:model.live="returnItems.{{ $index }}.discount" value="{{ $item['discount'] ?? 0 }}" disabled>
                    @error("returnItems.$index.discount") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm" 
                        value="{{ $item['tax_amount'] ?? 0 }}" readonly>
                    <small class="text-gray-500">{{ $item['tax_percentage'] ?? 0 }}%</small>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        ₹{{ number_format($item['total'] ?? 0, 2) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" wire:click="removeReturnItem({{ $index }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-2 px-6">
        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click="addReturnItem">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Product
        </button>
    </div>
</div>
