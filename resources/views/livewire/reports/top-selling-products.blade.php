<div>
    <div class="flex flex-wrap gap-4 mb-4 items-center">
            <input type="date" wire:model.live="from_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">

            <input type="date" wire:model.live="to_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">

            <select wire:model.live="branch_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                <option value="">All Branches</option>
                @foreach(\App\Models\Branch::all() as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>

        <button wire:click="export" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Export to Excel</button>

    </div>
    @if(count($products))
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $product->product->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($product->total_qty, 0) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($product->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="mt-6 text-gray-500">No data available for the selected filters.</div>
    @endif


</div>
