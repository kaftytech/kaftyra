<div>
    <div class="p-4">

        @if ($showDetails && $selectedTransaction)
            <div class="bg-white p-4 rounded shadow mb-4">
                <h2 class="text-lg font-semibold mb-2">Transaction Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Date:</strong> {{ $selectedTransaction->date }}</div>
                    <div><strong>Type:</strong> {{ ucfirst($selectedTransaction->type) }}</div>
                    <div><strong>Amount:</strong> ₹{{ number_format($selectedTransaction->amount, 2) }}</div>
                    <div><strong>Description:</strong> {{ $selectedTransaction->description }}</div>
                    <div><strong>Opening Balance:</strong> ₹{{ number_format($selectedTransaction->opening_balance, 2) }}</div>
                    <div><strong>Closing Balance:</strong> ₹{{ number_format($selectedTransaction->closing_balance, 2) }}</div>
                    <div class="col-span-2"><strong>Branch ID:</strong> {{ $selectedTransaction->branch_id ?? 'N/A' }}</div>
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
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Description</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Type</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="px-4 py-2">{{ $transaction->date }}</td>
                        <td class="px-4 py-2">{{ $transaction->description }}</td>
                        <td class="px-4 py-2">{{ ucfirst($transaction->type) }}</td>
                        <td class="px-4 py-2">₹{{ number_format($transaction->amount, 2) }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="view({{ $transaction->id }})" class="text-blue-600 hover:underline">
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
