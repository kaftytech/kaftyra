<div>
        <div class="flex flex-wrap gap-4 mb-4 items-center">
            <div>
                <select wire:model.live="customer_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                    <option value="">All</option>
                    @foreach($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->customer_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" wire:model="from_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            </div>
            <div>
                <input type="date" wire:model="to_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            </div>
            <button wire:click="export" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Export</button>
        </div>

       <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50">
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Paid</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $index => $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoices->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->invoice_date }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->customer->customer_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($invoice->paid_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($invoice->due_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-2 border text-center text-gray-500">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-2">
            {{ $invoices->links() }}
        </div>
    </div>

</div>
