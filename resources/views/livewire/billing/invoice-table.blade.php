<div>      
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created at</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($invoices as $invoice)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->invoice_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->customer->customer_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->total_amount }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->paid_amount }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->status }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->type }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->created_at }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              <button class="text-blue-600 hover:text-blue-900">
                <a href="{{route('invoices.edit',$invoice->id)}}">
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
        {{ $invoices->links() }}
      </div>
    </div>
  </div>