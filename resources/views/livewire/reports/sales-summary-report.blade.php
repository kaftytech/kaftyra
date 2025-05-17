<div>
    <div class="flex flex-wrap gap-4 mb-4 items-center">
        <select wire:model.live="branch_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            <option value="">All Branches</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="product_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            <option value="">All Products</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>

        <input type="date" wire:model.live="from_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
        <input type="date" wire:model.live="to_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">

        <select wire:model.live="perPage" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="100">100</option>
        </select>

        <button wire:click="export" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Export</button>
    </div>

     <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50">
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Gross Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tax</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Net Total</th>
                </tr>
        </thead>
        <tbody>
            @forelse($sales as $row)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $row->product->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $row->total_qty }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($row->gross_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($row->discount_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($row->tax_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($row->net_total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
