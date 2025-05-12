<div>    
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill #</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO #</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">vendor</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($purchase_bills as $bill)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->bill_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->purchaseOrder?->po_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->bill_date }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->purchaseOrder->vendor?->company_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->total_amount }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->paid_amount }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->due_amount }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bill->status }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              <button class="text-blue-600 hover:text-blue-900">
                <a href="{{route('purchase-bills.edit',$bill->id)}}">
                  <i class="fas fa-edit"></i>
                </a>
              </button>
               <button class="hover:bg-gray-50 text-gray-700 p-2 transition duration-150" wire:click="payment({{$bill->id}})">
                  <i class="fas fa-money-bill-wave"></i>
              </button>
              <button class="ml-2 text-red-600 hover:text-red-900">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">No records found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    
      <div class="mt-4">
        {{ $purchase_bills->links() }}
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                  <div class="mb-3">
                      <label class="text-sm text-gray-500">Bill Number</label>
                      <div class="font-medium text-gray-700">{{ $selectedPurchaseBill->bill_number ?? '-' }}</div>
                  </div>
                    <div class="mb-3">
                        <label class="text-sm text-gray-500">PO Number</label>
                        <div class="font-medium text-gray-700">{{ $selectedPurchaseBill->purchaseOrder->po_number ?? '-' }}</div>
                    </div>
                  <div class="mb-3">
                      <label class="text-sm text-gray-500">Vendor Name</label>
                      <div class="font-medium text-gray-700">{{ $selectedPurchaseBill->purchaseOrder->vendor->company_name ?? '-' }}</div>
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
                        <input type="text" wire:model.defer="paymentData.reference"
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

</div>
