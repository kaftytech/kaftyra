<div>
    <div class="p-4">
        @if($showForm)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded shadow">
                <x-input label="Title" name="title" wire:model.defer="title" />
                <x-input label="Amount" name="amount" wire:model.defer="amount" type="number" />
                <x-input label="Date" name="date"  wire:model.defer="expense_date" type="date" />
                <x-input label="Payment Mode" name="payment_mode" wire:model.defer="payment_mode" />
                <div>
                    <x-label for="status" value="Status" />
                    <select id="status" wire:model="status" class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <x-input label="Transaction ID" name="transaction_id" wire:model.defer="transaction_id" />
                <x-input label="Reference Number" name="reference_number" wire:model.defer="reference_number" />
                <div class="col-span-2">
                    <x-textarea label="Note" name="note" wire:model.defer="note" />
                </div>
                <div class="col-span-2 flex justify-end gap-2">
                    <x-button wire:click="save">Save</x-button>
                    <x-button wire:click="$set('showForm', false)" color="secondary">Cancel</x-button>
                </div>
            </div>
        @else
            <div class="flex justify-end mb-2">
                <button wire:click="create" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">+ New Expense</button>
            </div>
    
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Title</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($expenses as $exp)
                        <tr>
                            <td class="px-4 py-2">{{ $exp->title }}</td>
                            <td class="px-4 py-2">{{ number_format($exp->amount, 2) }}</td>
                            <td class="px-4 py-2">{{ $exp->expense_date }}</td>
                            <td class="px-4 py-2">{{ ucfirst($exp->status) }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <button wire:click="edit({{ $exp->id }})" class="text-blue-600 hover:underline">Edit</button>
                                <button wire:click="delete({{ $exp->id }})" class="text-red-600 hover:underline" color="red">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
</div>
