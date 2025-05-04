<div>      
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
             <!-- New Checkbox Header -->
             <th class="px-6 py-3">
                <input type="checkbox" wire:model="selectAll" class="form-checkbox">
              </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Date</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($deliveries as $order)
          <tr>
            <!-- New Checkbox -->
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
            <input type="checkbox" wire:model="selectedInvoices" value="{{ $order->invoice->id }}" class="form-checkbox">
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->invoice->invoice_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->invoice->customer->customer_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->assigned_to }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <span class="px-2 py-1 inline-flex text-xs leading-5 rounded-full bg-green-100 text-green-800">
                {{ $order->status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->delivery_date }}</td>
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
        {{ $deliveries->links() }}
      </div>
      <!-- Salesman Selection and Assign Button -->
    <div class="flex items-center mt-6 space-x-4">
        <select wire:model="selectedSalesman" class="border rounded p-2">
          <option value="">Select Salesman</option>
          @foreach($users as $salesman)
            <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
          @endforeach
        </select>
  
        <button wire:click="assignSelectedInvoices" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Assign Selected Invoices
        </button>
      </div>
    </div>
  </div>