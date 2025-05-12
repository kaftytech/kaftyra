<div>
  <!-- Filters -->
  <div class="flex flex-wrap gap-4 mb-4 ml-2">
    <select wire:model.live="branch_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
      <option value="">All Branches</option>
      @foreach ($branches as $branch)
        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
      @endforeach
    </select>

    <select wire:model.live="category_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
      <option value="">All Categories</option>
      @foreach ($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
      @endforeach
    </select>

    <button wire:click="export" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
      Export Excel
    </button>
  </div>

  <!-- Table -->
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 shadow-md">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
          {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MRP</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selling Price</th> --}}
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($products as $product)
          <tr>
            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->name }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->category->name ?? '-' }}</td>
            {{-- <td class="px-6 py-4 text-sm text-gray-700">{{ $product->unit->name ?? '-' }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->mrp }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->selling_price }}</td> --}}
            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->stock->current_stock ?? 0 }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No products found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="mt-4 ml-2">
    {{ $products->links() }}
  </div>
</div>
