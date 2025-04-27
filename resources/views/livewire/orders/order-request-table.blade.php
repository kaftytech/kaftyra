<div>      
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Date</th> --}}
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($orders as $order)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->order_id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->customer->customer_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->request_date }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <span class="px-2 py-1 inline-flex text-xs leading-5 rounded-full bg-green-100 text-green-800">
                {{ $order->status }}
              </span>
            </td>
            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->approved_date }}</td> --}}
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              <button class="text-blue-600 hover:text-blue-900">
                <a href="{{route('order-requests.edit',$order->id)}}">
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
        {{ $orders->links() }}
      </div>
    </div>
  </div>