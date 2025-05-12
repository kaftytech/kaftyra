<div>
    <form wire:submit.prevent="submitOrder">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4 p-4">
            <div>
                <label for="requestDate" class="block text-sm font-medium text-gray-700 mb-1">Bill Date</label>
                <input type="date" id="RequestDate" wire:model.live="bill_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('bill_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
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
                @error('search_by') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-textarea label="Notes" name="notes" wire:model.live="notes" />
                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div wiere:ignore>
                <x-label label="Purchase Order" value="Purchase Order"/>
                 {{-- <select label="User" id="purchase_order_id" name="purchase_order_id" 
                    class="w-80 px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 select2" 
                    wire:model="purchase_order_id">
                        <option value=""> Select PO</option>
                        @foreach($purchaseOrders as $po)
                        <option value="{{ $po->id }}">{{ $po->po_number }}</option>
                        @endforeach
                    </select> --}}
                    <input id="order_search_by_query" 
                        name="order_search_by_query" 
                        type="text"
                        wire:model.live.debounce.500ms="order_search_by_query"
                        wire:keydown.arrow-down="highlightNextOrder" 
                        wire:keydown.arrow-up="highlightPreviousOrder" 
                        wire:keydown.enter="selectHighlightedOrder" 
                        placeholder="Search Purchase Order" 
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            
                <input wire:model.live="order_search_by" type="text" name="order_search_by" id="order_search_by" hidden>
            
                @if (!empty($order_search_by_query) && $order_search_by_query_status !== 'selected')
                    <ul class="list-group absolute z-10 bg-white border w-full mt-1 rounded shadow">
                        @if (!empty($orders))
                            @foreach ($orders as $index => $data)
                                <li>
                                    <a class="py-2 px-3 list-group-item hover:bg-slate-100 cursor-pointer @if($highlightIndexOrder === $index) bg-blue-100 @endif" 
                                        wire:click.prevent="selectPurchaseOrder({{ $data['id'] }})" 
                                        data-index="{{ $index }}">
                                        {{ $data['po_number'] }}  <!-- Adjust this based on what info you want to show -->
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
        @if($showOrderSelection)
        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">#</th>
                        <th class="px-4 py-2 text-left font-semibold">Product</th>
                        <th class="px-4 py-2 text-left font-semibold">Product Code</th>
                        <th class="px-4 py-2 text-left font-semibold">Price</th>
                        <th class="px-4 py-2 text-left font-semibold">Quantity</th>
                        <th class="px-4 py-2 text-left font-semibold">Discount</th>
                        <th class="px-4 py-2 text-left font-semibold">Tax</th>
                        <th class="px-4 py-2 text-left font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($purchaseOrderItems as $index => $item)
                        @if(!empty($item['product_id']))
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $item->product->name }}</td>
                                <td class="px-4 py-2">{{ $item->product->product_code ?? '-' }}</td>
                                <td class="px-4 py-2">{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                <td class="px-4 py-2">{{ number_format($item->discount ?? 0, 2) }}</td>
                                <td class="px-4 py-2">
                                    {{ number_format($item->tax_amount ?? 0, 2) }}
                                    <span class="text-xs text-gray-400">({{ $item->tax_percentage ?? 0 }}%)</span>
                                </td>
                                <td class="px-4 py-2 font-semibold">{{ number_format($item->net_total, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
    
                    <!-- Totals -->
                    <tr class="border-t">
                        <td colspan="7" class="px-4 py-2 text-right font-bold">Subtotal</td>
                        <td class="px-4 py-2 font-semibold">{{ number_format($purchaseOrder->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-right font-bold">Discount</td>
                        <td class="px-4 py-2 font-semibold">{{ number_format($purchaseOrder->discount, 2) }}</td>
                    </tr>
                    @foreach($purchaseOrder->taxables as $tax)
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-right font-bold">{{ $tax['tax_name'] }}({{ rtrim(rtrim($tax['rate'], '0'), '.') }}%)</td>
                        <td class="px-4 py-2 font-semibold">{{ number_format($tax['amount'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-right font-bold">Total</td>
                        <td class="px-4 py-2 font-semibold">{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-right font-bold text-green-600">Paid</td>
                        <td class="px-4 py-2 font-semibold">{{ number_format($purchaseOrder->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-right font-bold text-red-500">Due</td>
                        <td class="px-4 py-2 font-semibold">{{ number_format($purchaseOrder->due_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>            
        </div>
        @endif
        <!-- Submit -->
        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </div>
    </form>   
    
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#purchase_order_id').select2({
            placeholder: "Select PO",
            allowClear: true,
            closeOnSelect: false
        });

        // Sync Livewire with Select2
        $('#purchase_order_id').on('change', function () {
            let selectedValues = $(this).val();
            @this.set('purchase_order_id', selectedValues);
        });

        Livewire.hook('message.processed', (message, component) => {
            $('#purchase_order_id').select2();
        });
    });
</script>