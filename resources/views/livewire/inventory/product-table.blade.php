<div>      
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Code</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">unit</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">mrp</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">selling price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">gst %</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created at</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($products as $product)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->product_code }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->unit->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->category->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->mrp }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->selling_price }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->gst_percentage }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->created_at }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              <button class="text-blue-600 hover:text-blue-900">
                <a href="{{route('products.edit',$product->id)}}">
                  <i class="fas fa-edit"></i>
                </a>
              </button>
              <button class="ml-2 text-red-600 hover:text-red-900">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No records found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    
      <div class="mt-4">
        {{ $products->links() }}
      </div>
    </div>
  </div>