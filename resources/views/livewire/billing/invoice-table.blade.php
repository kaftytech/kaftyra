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
              <button class="hover:bg-gray-50 text-gray-700 p-2 transition duration-150">
                <a href="{{route('invoices.show',$invoice->id)}}">
                  <i class="fas fa-eye"></i>
                </a>
              </button>
              <button class="hover:bg-gray-50 text-gray-700 p-2 transition duration-150" wire:click="payment({{$invoice->id}})">
                  <i class="fas fa-money-bill-wave"></i>
              </button>
              <button class="hover:bg-gray-50 text-gray-700 p-2 transition duration-150" wire:click="shipping({{$invoice->id}})">
                    <i class="fas fa-truck"></i>
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
    <!-- Payment Modal -->
    @if($showPaymentModal)
    <div class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 relative">
    
            <!-- Background Overlay (Fix applied: absolute + z-index) -->
            <div class="fixed inset-0 bg-opacity-30 z-40"></div>
    
            <!-- Modal Box -->
            <div class="bg-gray-200  rounded-xl shadow-lg w-full max-w-xl z-50 p-6 relative">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Add Payment</h2>
                  @if($errorMessage)
                      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                          <span class="block sm:inline">{{$errorMessage}}</span>
                      </div>
                  @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                  <div class="mb-3">
                      <label class="text-sm text-gray-500">Invoice Number</label>
                      <div class="font-medium text-gray-700">{{ $selectedInvoice->invoice_number ?? '-' }}</div>
                  </div>
      
                  <div class="mb-3">
                      <label class="text-sm text-gray-500">Customer Name</label>
                      <div class="font-medium text-gray-700">{{ $selectedInvoice->customer->customer_name ?? '-' }}</div>
                  </div>
    
                </div>
    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Payment Date</label>
                        <input type="date" wire:model.defer="paymentData.payment_date"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                
                    <div>
                        <label class="text-sm text-gray-600">Amount</label>
                        <input type="number" wire:model.defer="paymentData.amount"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                
                    <div>
                        <label class="text-sm text-gray-600">Reference Number</label>
                        <input type="text" wire:model.defer="paymentData.reference_number"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                
                    <div>
                        <label class="text-sm text-gray-600">Transaction ID</label>
                        <input type="text" wire:model.defer="paymentData.transaction_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                </div>
                  
                <div class="mb-3">
                    <label class="text-sm text-gray-600">Notes</label>
                    <textarea wire:model.defer="paymentData.notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
    
                <div class="flex justify-end gap-2 mt-5">
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200" wire:click="$set('showPaymentModal', false)">Cancel</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" wire:click="storePayment">Save</button>
                </div>

                @if($paymentDetails->count() > 0)
                    <div class="mt-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Details</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Reference Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Transaction ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Notes
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($paymentDetails as $paymentDetail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $paymentDetail->payment_date }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $paymentDetail->amount }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $paymentDetail->reference_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $paymentDetail->transaction_id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $paymentDetail->notes }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    @if($showShippingModal)
    <div class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 relative">
    
            <!-- Background Overlay (Fix applied: absolute + z-index) -->
            <div class="fixed inset-0 bg-opacity-30 z-40"></div>

            <div class="bg-gray-200 rounded-lg shadow-xl w-full max-w-2xl z-50 p-6 relative">
                <h2 class="text-xl font-semibold mb-4">Shipping Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="mb-3">
                        <label class="text-sm text-gray-500">Invoice Number</label>
                        <div class="font-medium text-gray-700">{{ $selectedInvoice->invoice_number ?? '-' }}</div>
                    </div>
        
                    <div class="mb-3">
                        <label class="text-sm text-gray-500">Customer Name</label>
                        <div class="font-medium text-gray-700">{{ $selectedInvoice->customer->customer_name ?? '-' }}</div>
                    </div>
      
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- <div>
                        <label class="text-sm text-gray-600">Delivery Date</label>
                        <input type="date" wire:model.defer="shippingData.delivery_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-400" />
                    </div> --}}
                    <div>
                        <label class="text-sm text-gray-600">Delivery Type</label>
                        <select wire:model.live="shippingData.delivery_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="vehicle">Vehicle</option>
                            <option value="courier">Courier</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <select wire:model.defer="shippingData.status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="pending">Pending</option>
                            <option value="packed">Packed</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                    @if($shippingData['delivery_type'] == 'courier')
                        <div>
                            <label class="text-sm text-gray-600">Tracking Number</label>
                            <input type="text" wire:model.defer="shippingData.tracking_number" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Courier Name</label>
                            <input type="text" wire:model.defer="shippingData.courier_name" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                        </div>    
                    @endif

                    {{-- <div>
                        <label class="text-sm text-gray-600">Courier Number</label>
                        <input type="text" wire:model.defer="shippingData.courier_number" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                    </div> --}}
                    <div>
                        <label class="text-sm text-gray-600">Assigned To (Salesman)</label>
                        <select wire:model.defer="shippingData.assigned_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Salesman</option>
                            @foreach ($salesmen as $salesman)
                            <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($shippingData['delivery_type'] == 'vehicle')
                    <div>
                        <label class="text-sm text-gray-600">Vehicle</label>
                        <select wire:model.defer="shippingData.vehicle_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Vehicle</option>
                            @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600">Notes</label>
                    <textarea wire:model.defer="shippingData.notes" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button class="px-4 py-2 bg-gray-200 rounded" wire:click="$set('showShippingModal', false)">Cancel</button>
                    <button class="px-4 py-2 bg-green-600 text-white rounded" wire:click="storeShipping">Save</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    
</div>