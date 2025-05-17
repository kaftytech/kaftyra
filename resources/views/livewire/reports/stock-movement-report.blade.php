<div>
    <div class="flex items-center gap-6 mb-4">
        <select wire:model.live="branch_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            <option value="">All Branches</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="product_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            <option value="">All Products</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="type" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            <option value="">All Types</option>
            <option value="in">Stock In</option>
            <option value="out">Stock Out</option>
        </select>

        <input type="date" wire:model.live="from_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" placeholder="From Date">
        <input type="date" wire:model.live="to_date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" placeholder="To Date">

        <button wire:click="export" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Export Excel</button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Note</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($adjustments as $adjustment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $adjustment->date }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $adjustment->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 inline-flex text-xs rounded-full {{ $adjustment->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($adjustment->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $adjustment->quantity }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $adjustment->branch->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $adjustment->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No stock movements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $adjustments->links() }}
    </div>
</div>
