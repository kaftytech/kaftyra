<div>
    <div class="flex flex-wrap gap-4 mb-4 items-center">
        <select wire:model.live="branch_id" class="border-gray-300 rounded-md shadow-sm focus:ring">
            <option value="">All Branches</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>

        <input type="date" wire:model.live="from_date" class="border-gray-300 rounded-md shadow-sm focus:ring">
        <input type="date" wire:model.live="to_date" class="border-gray-300 rounded-md shadow-sm focus:ring">

        <select wire:model.live="perPage" class="border-gray-300 rounded-md shadow-sm focus:ring">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="100">100</option>
        </select>

        <button wire:click="export" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Export</button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Invoice #</th>
                    <th class="px-4 py-2">Customer</th>
                    <th class="px-4 py-2">GSTIN</th>
                    <th class="px-4 py-2">Taxable</th>
                    <th class="px-4 py-2">CGST</th>
                    <th class="px-4 py-2">SGST</th>
                    <th class="px-4 py-2">IGST</th>
                    <th class="px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gstr as $row)
                    <tr>
                        <td class="px-4 py-2 text-sm">{{ $row->invoice_date }}</td>
                        <td class="px-4 py-2 text-sm">{{ $row->invoice_number }}</td>
                        <td class="px-4 py-2 text-sm">{{ $row->customer_name }}</td>
                        <td class="px-4 py-2 text-sm">{{ $row->gst_number ?? 'Unregistered' }}</td>
                        {{-- <td class="px-4 py-2 text-sm">{{ $row->qty }}</td> --}}
                        <td class="px-4 py-2 text-sm">{{ number_format($row->taxable_value, 2) }}</td>
                        <td class="px-4 py-2 text-sm">{{ number_format($row->cgst, 2) }}</td>
                        <td class="px-4 py-2 text-sm">{{ number_format($row->sgst, 2) }}</td>
                        <td class="px-4 py-2 text-sm">{{ number_format($row->igst, 2) }}</td>
                        <td class="px-4 py-2 text-sm">{{ number_format($row->total_invoice_value, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-gray-500 py-4">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $gstr->links() }}
        </div>
    </div>
</div>
