<div>
    <form wire:submit.prevent="submitRequest">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4 p-4">
            <!-- Request general information -->
            <div>
                <label for="requestDate" class="block text-sm font-medium text-gray-700 mb-1">Request Date</label>
                <input type="date" id="RequestDate" wire:model.live="request_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('request_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="customer" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select id="customer" wire:model.live="customer_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>
                @error('customer_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-textarea label="Notes" name="notes" wire:model.live="notes" />
                @error('request_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
       
        <!-- Request Items -->
        <!-- Different Table Based on Request Type -->
        <div class="mt-8">            
            <div class="mt-8 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requestItems as $index => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:model="requestItems.{{ $index }}.product_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                >
                                    <option value="">Select Product</option>
                                    @foreach($availableProducts as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error("requestItems.$index.product_id") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" wire:model.live="requestItems.{{ $index }}.quantity" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                @error("requestItems.$index.quantity") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" wire:click="removeRequestItem({{ $index }})">
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
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click="addRequestItem">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Product
                    </button>
                </div>
            </div>
            
    
        </div>
    
        <!-- Submit -->
        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit Request
            </button>
        </div>
    </form>   
    
</div>
