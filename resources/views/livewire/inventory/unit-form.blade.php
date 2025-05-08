<div>
    <div class="p-4">
        @if($showForm)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded shadow">
                <x-input label="Unit Name" name="name" wire:model.defer="name" />
                <x-input label="Symbol" name="symbol" wire:model.defer="symbol" />
    
                <div class="col-span-2 flex justify-end gap-2">
                    <x-button wire:click="save">Save</x-button>
                    <x-button wire:click="$set('showForm', false)" color="secondary">Cancel</x-button>
                </div>
            </div>
        @else
            <div class="flex justify-end mb-2">
                <button wire:click="create" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">+ New Unit</button>
            </div>
    
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Symbol</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($units as $unit)
                        <tr>
                            <td class="px-4 py-2">{{ $unit->name }}</td>
                            <td class="px-4 py-2">{{ $unit->symbol }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <button wire:click="edit({{ $unit->id }})" class="text-blue-600 hover:underline">Edit</button>
                                {{-- <button wire:click="delete({{ $unit->id }})" class="text-red-600 hover:underline">Delete</button> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
</div>
