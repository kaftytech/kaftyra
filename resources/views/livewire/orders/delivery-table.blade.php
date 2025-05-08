<div>      
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivered Date</th>
            {{-- <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th> --}}
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($deliveries as $order)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->invoice->invoice_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->invoice->customer->customer_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->delivery_type }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->user->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <select wire:model.live="statuses.{{ $order->id }}"
                        class="text-xs rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @foreach (['pending', 'packed', 'shipped', 'delivered', 'canceled'] as $status)
                        <option value="{{ $status }}" @if ($order->status === $status) selected @endif>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </td>          
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->delivered_date }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              {{-- <button class="text-blue-600 hover:text-blue-900">
                <a href="{{route('order-requests.edit',$order->id)}}">
                  <i class="fas fa-edit"></i>
                </a>
              </button>
              <button class="ml-2 text-red-600 hover:text-red-900">
                <i class="fas fa-trash"></i>
              </button> --}}
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
        {{ $deliveries->links() }}
      </div>
    </div>
  </div>