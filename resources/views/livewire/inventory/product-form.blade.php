<div class="p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Product Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
            <input type="text" wire:model="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Product Name">
            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Product Code -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Code</label>
            <input type="text" wire:model="product_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Product Code">
            @error('product_code') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Unit Select -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
            <select wire:model="unit_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                <option value="">Select Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                @endforeach
            </select>
            @error('unit_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Category Select -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select wire:model="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- MRP -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">MRP</label>
            <input type="number" wire:model="mrp" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="MRP">
            @error('mrp') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Selling Price -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Selling Price</label>
            <input type="number" wire:model="selling_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Selling Price">
            @error('selling_price') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- GST Percentage -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">GST Percentage</label>
            <input type="number" wire:model="gst_percentage" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="GST Percentage">
            @error('gst_percentage') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea wire:model="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" rows="4" placeholder="Product Description"></textarea>
            @error('description') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mt-6 border border-gray-300 p-4">
        <h5 class="font-medium text-gray-700 mb-2">Vendor Prices</h5>
    
        <!-- Vendor Select + Add Button -->
        <div class="flex items-center space-x-4 mb-4">
            <div class="flex-1">
                <label for="customer" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                    <div class="relative">
                        <input id="search_by_query" 
                            name="search_by_query" 
                            type="text"
                            wire:model.live.debounce.500ms="search_by_query"
                            wire:keydown.arrow-down="highlightNext" 
                            wire:keydown.arrow-up="highlightPrevious" 
                            wire:keydown.enter="selectHighlighted" 
                            placeholder="Search Vendor" 
                            class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            
                        <input wire:model.live="search_by" type="text" name="search_by" id="search_by" hidden>
                        
                        @if (!empty($search_by_query) && $search_by_query_status !== 'selected')
                            <ul class="list-group absolute z-10 bg-white border w-full mt-1 rounded shadow">
                                @if (!empty($vendors))
                                    @foreach ($vendors as $index => $data)
                                        <li>
                                            <a class="py-2 px-3 list-group-item hover:bg-slate-100 cursor-pointer @if($highlightIndex === $index) bg-blue-100 @endif" 
                                            wire:click.prevent="sbSelect({{ $data['id'] }})" 
                                            data-index="{{ $index }}">
                                                {{ $data['company_name'] }} | {{ $data['phone'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <div class="py-2 px-3 hover:bg-slate-100 w-full cursor-pointer">No data!</div>
                                @endif
                            </ul>
                        @endif
                    </div>
            </div>
            <button type="button" wire:click="addVendor" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add
            </button>
        </div>
    
        <!-- Vendor Price Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="py-2 px-4 text-left border-b">Vendor Name</th>
                        <th class="py-2 px-4 text-left border-b">Vendor Price</th>
                        <th class="py-2 px-4 text-left border-b w-20">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendorPrices as $id => $vendor)
                        <tr class="text-sm text-gray-800">
                            <td class="py-2 px-4 border-b">
                                <input type="hidden" name="vendors[{{ $id }}][id]" value="{{ $id }}">
                                {{ $vendor['name'] }}
                            </td>
                            <td class="py-2 px-4 border-b">
                                <input type="number" name="vendors[{{ $id }}][price]" wire:model="vendorPrices.{{ $id }}.price"
                                       class="w-32 px-2 py-1 border border-gray-300 rounded" placeholder="Vendor Price" step="0.01">
                            </td>
                            <td class="py-2 px-4 border-b">
                                <button type="button" wire:click="removeVendor({{ $id }})"
                                        class="text-red-600 hover:text-red-800">
                                    <i class="fa fa-times-circle text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 px-4 text-center text-gray-500">No vendors added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-6 flex justify-end">
        <button type="button" wire:click="save" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
            <i class="fa fa-save"></i> Save
        </button>
        <button type="button" wire:click="$emit('closeModal')" class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150">
            <i class="fa fa-times"></i> Cancel
        </button>
    </div>
</div>
