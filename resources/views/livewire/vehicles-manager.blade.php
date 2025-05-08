<div>
    <div class="p-4">
        @if($showForm)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded shadow">
                <x-input label="Vehicle Number" name="vehicle_number" wire:model.defer="vehicle_number" />
                <x-input label="Vehicle Type" name="type" wire:model.defer="type" placeholder="e.g., Truck, Van, Bike" />
                <x-input label="Driver Name" name="driver_name" wire:model.defer="driver_name" />
                <x-input label="Driver Contact" name="driver_contact" wire:model.defer="driver_contact" />
    
                <div class="col-span-2">
                    <x-textarea label="Notes" name="notes" wire:model.defer="notes" />
                </div>
    
                {{-- <div class="col-span-2">
                    <x-select label="Branch" name="branch_id" wire:model.defer="branch_id">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </x-select>
                </div> --}}
    
                <div class="col-span-2 flex justify-end gap-2">
                    <x-button wire:click="save">Save</x-button>
                    <x-button wire:click="$set('showForm', false)" color="secondary">Cancel</x-button>
                </div>
            </div>
        @else
            <div class="flex justify-end mb-2">
                <button wire:click="create" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">+ New Vehicle</button>
            </div>
    
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Vehicle No</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Driver</th>
                        {{-- <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Branch</th> --}}
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($vehicles as $vehicle)
                        <tr>
                            <td class="px-4 py-2">{{ $vehicle->vehicle_number }}</td>
                            <td class="px-4 py-2">{{ $vehicle->type }}</td>
                            <td class="px-4 py-2">{{ $vehicle->driver_name }} ({{ $vehicle->driver_contact }})</td>
                            {{-- <td class="px-4 py-2">{{ $vehicle->branch?->name }}</td> --}}
                            <td class="px-4 py-2 space-x-2">
                                <button wire:click="edit({{ $vehicle->id }})" class="text-blue-600 hover:underline">Edit</button>
                                <button wire:click="delete({{ $vehicle->id }})" class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No Vehicles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>   
</div>
