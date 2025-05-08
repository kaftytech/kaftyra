<div>
    <div class="p-4">
        @if($showForm)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded shadow">
                <x-input label="Branch Name" name="name" wire:model.defer="name" />
                <x-input label="Phone" name="phone" wire:model.defer="phone" />
                <x-input label="Email" name="email" wire:model.defer="email" type="email" />
                <x-input label="City" name="city" wire:model.defer="city" />
                <x-input label="State" name="state" wire:model.defer="state" />
                <x-input label="Country" name="country" wire:model.defer="country" />
                <x-input label="ZIP" name="zip" wire:model.defer="zip" />
                
                <div class="col-span-2">
                    <x-label for="address" value="Address" />
                    <textarea id="address" wire:model.defer="address" rows="3" class="border border-gray-300 text-gray-700 p-2 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <div>
                    <x-label for="status" value="Status" />
                    <select id="status" wire:model.defer="status" class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-span-2 flex justify-end gap-2">
                    <x-button wire:click="save">Save</x-button>
                    <x-button wire:click="$set('showForm', false)" color="secondary">Cancel</x-button>
                </div>
            </div>
        @else
            <div class="flex justify-end mb-2">
                <button wire:click="create" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">+ New Branch</button>
            </div>

            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Phone</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Email</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">City</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($branches as $branch)
                        <tr>
                            <td class="px-4 py-2">{{ $branch->name }}</td>
                            <td class="px-4 py-2">{{ $branch->phone }}</td>
                            <td class="px-4 py-2">{{ $branch->email }}</td>
                            <td class="px-4 py-2">{{ $branch->city }}</td>
                            <td class="px-4 py-2 capitalize">{{ $branch->status }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <button wire:click="edit({{ $branch->id }})" class="text-blue-600 hover:underline">Edit</button>
                                {{-- <button wire:click="delete({{ $branch->id }})" class="text-red-600 hover:underline">Delete</button> --}}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-2 text-center text-gray-500">No Branches found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</div>
