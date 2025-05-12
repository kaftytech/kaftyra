<div>
    <div class="p-6 bg-white rounded-lg shadow">
    
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Description</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Type</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Opening</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Closing</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Mode</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($transactions as $txn)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($txn->date)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2">{{ $txn->description }}</td>
                        <td class="px-4 py-2 capitalize">{{ $txn->type }}</td>
                        <td class="px-4 py-2 text-right text-{{ $txn->type == 'credit' ? 'green' : 'red' }}-600">
                            {{ number_format($txn->amount, 2) }}
                        </td>
                        <td class="px-4 py-2 text-right">{{ number_format($txn->opening_balance, 2) }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($txn->closing_balance, 2) }}</td>
                        <td class="px-4 py-2">{{ $txn->txn_mode ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
    
</div>
