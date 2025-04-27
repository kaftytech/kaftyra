<div>      
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">phone</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">city</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($vendors as $vendor)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->company_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->email }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->phone }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->city }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->created_at }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              <button class="text-blue-600 hover:text-blue-900">
                <a href="{{route('vendors.edit',$vendor->id)}}">
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
        {{ $vendors->links() }}
      </div>
    </div>
  </div>