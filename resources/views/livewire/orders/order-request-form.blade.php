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
                <div class="relative">
                    <input id="search_by_query" 
                        name="search_by_query" 
                        type="text"
                        wire:model.live.debounce.500ms="search_by_query"
                        wire:keydown.arrow-down="highlightNext" 
                        wire:keydown.arrow-up="highlightPrevious" 
                        wire:keydown.enter="selectHighlighted" 
                        placeholder="Search Customer" 
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        
                    <input wire:model.live="search_by" type="text" name="search_by" id="search_by" hidden>
                    
                    @if (!empty($search_by_query) && $search_by_query_status !== 'selected')
                        <ul class="list-group absolute z-10 bg-white border w-full mt-1 rounded shadow">
                            @if (!empty($customers))
                                @foreach ($customers as $index => $data)
                                    <li>
                                        <a class="py-2 px-3 list-group-item hover:bg-slate-100 cursor-pointer @if($highlightIndex === $index) bg-blue-100 @endif" 
                                        wire:click.prevent="sbSelect({{ $data['id'] }})" 
                                        data-index="{{ $index }}">
                                            {{ $data['customer_name'] }} | {{ $data['phone'] }}
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <div class="py-2 px-3 hover:bg-slate-100 w-full cursor-pointer">No data!</div>
                            @endif
                        </ul>
                    @endif
                </div>

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
                            {{-- <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:model="requestItems.{{ $index }}.product_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                >
                                    <option value="">Select Product</option>
                                    @foreach($availableProducts as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error("requestItems.$index.product_id") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </td> --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="relative">
                                    <input type="text"
                                        placeholder="Search Product"
                                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        wire:model.live.debounce.500ms="requestItems.{{ $index }}.product_search"
                                        wire:keydown.arrow-down="highlightProductNextWrapper({{ $index }})"
                                        wire:keydown.arrow-up="highlightProductPreviousWrapper({{ $index }})"
                                        wire:keydown.enter="selectHighlightedProductWrapper({{ $index }})"
                                    >
                            
                                    <input type="hidden" wire:model.live="requestItems.{{ $index }}.product_id">
                            
                                    @if (!empty($requestItems[$index]['product_search']) && $requestItems[$index]['search_status'] !== 'selected')
                                        <ul class="absolute z-10 bg-white border w-full mt-1 rounded shadow">
                                            @if (!empty($requestItems[$index]['search_results']))
                                                @foreach ($requestItems[$index]['search_results'] as $pIndex => $product)
                                                    <li wire:click.prevent="selectProductWrapper({{ $index }}, {{ $product['id'] }})"
                                                        class="px-3 py-2 hover:bg-slate-100 cursor-pointer @if($requestItems[$index]['highlight_index'] === $pIndex) bg-blue-100 @endif">
                                                        {{ $product['name'] }} ({{ $product['product_code'] }})
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="px-3 py-2 text-gray-500">No products found</li>
                                            @endif
                                        </ul>
                                    @endif
                            
                                    @error("requestItems.$index.product_id")
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
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
