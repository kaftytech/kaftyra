<div>
    <div class="p-4">

        @if ($showDetails && $selectedPayment)
            <div class="bg-white p-4 rounded shadow mb-4">
                <h2 class="text-lg font-semibold mb-2">Payment Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Amount:</strong> ₹{{ number_format($selectedPayment->amount, 2) }}</div>
                    <div><strong>Date:</strong> {{ $selectedPayment->payment_date }}</div>
                    <div><strong>Method:</strong> {{ ucfirst($selectedPayment->payment_method) }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($selectedPayment->status) }}</div>
                    <div><strong>Transaction ID:</strong> {{ $selectedPayment->transaction_id }}</div>
                    <div><strong>Reference Number:</strong> {{ $selectedPayment->reference_number }}</div>
                    <div class="col-span-2"><strong>Notes:</strong> {{ $selectedPayment->notes }}</div>
                </div>
                <div class="mt-4 text-right">
                    <button wire:click="closeDetails" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        @else
    
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Method</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($payments as $payment)
                    <tr>
                        <td class="px-4 py-2">{{ $payment->payment_date }}</td>
                        <td class="px-4 py-2">₹{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-2">{{ ucfirst($payment->payment_method) }}</td>
                        <td class="px-4 py-2">{{ ucfirst($payment->status) }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="view({{ $payment->id }})" class="text-blue-600 hover:underline">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    
</div>
