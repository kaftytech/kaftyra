<div>      
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Account Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Balance</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($accounts as $account)
                    <tr>
                        <td class="px-4 py-2">{{ $account->user->name }}</td>
                        <td class="px-4 py-2">{{ $account->balance }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-2 text-center text-gray-500">No Records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
