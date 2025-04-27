<div>
    <form wire:submit.prevent="submitReturn">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Return general information -->
            <div>
                <label for="returnType" class="block text-sm font-medium text-gray-700 mb-1">Return Type</label>
                <select id="returnType" wire:model.live="return_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Select Return Type</option>
                    <option value="manual">Manual</option>
                    <option value="customer">Customer</option>
                    <option value="invoice">Invoice</option>
                </select>
                @error('return_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="returnDate" class="block text-sm font-medium text-gray-700 mb-1">Return Date</label>
                <input type="date" id="returnDate" wire:model.live="return_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('return_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @if($return_type === 'customer')
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
            @endif
            @if($return_type === 'invoice')
                <div>
                    <label for="invoice" class="block text-sm font-medium text-gray-700 mb-1">Invoice</label>
                    <select id="invoice" wire:change="updateOrderInvoiceId($event.target.value)" wire:model.live="invoice_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Select Invoice</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }}</option>
                        @endforeach
                    </select>
                    @error('invoice_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif
            @if($return_type === 'invoice' || $return_type === 'customer')
            <div>
                <label for="returnDate" class="block text-sm font-medium text-gray-700 mb-1">Add Discount and Tax</label>
                <input type="checkbox" id="addDiscountAndTax" wire:model.live="isAddingDiscountAndTax" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('isAddingDiscountAndTax') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>
       
        <!-- Return Items -->
        <!-- Different Table Based on Return Type -->
        <div class="mt-8">            
            @if($return_type === 'manual')
                @include('orders.returns.manual-items')
            
            @elseif($return_type === 'customer')
                @include('orders.returns.customer-items')
            
            @elseif($return_type === 'invoice')
                @include('orders.returns.invoice-items')
            @endif
    
        </div>
    
        <!-- Total Summary -->
        <div class="mt-6 flex justify-end">
            <div class="text-lg font-semibold">
                Total Amount: ₹{{ number_format($total_amount, 2) }}
            </div>
        </div>
    
        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit Return
            </button>
        </div>
    </form>   
    
</div>
