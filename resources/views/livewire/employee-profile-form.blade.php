<div>
    <div class="p-4 space-y-4">
        <div class="flex justify-end items-center">
            <button wire:click="openModal" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">+ Add Employee</button>
        </div>
    
        @if (session()->has('message'))
            <div class="text-green-600">{{ session('message') }}</div>
        @endif
    
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Designation</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Dept</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Phone</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($employees as $emp)
                    <tr>
                        <td class="px-4 py-2">{{ $emp->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $emp->designation }}</td>
                        <td class="px-4 py-2">{{ $emp->department }}</td>
                        <td class="px-4 py-2">{{ $emp->phone }}</td>
                        <td class="px-4 py-2">
                            <x-button xs wire:click="edit({{ $emp->id }})">Edit</x-button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    
        {{ $employees->links() }}
    
        {{-- Modal --}}
        <x-modal wire:model="showModal" >
                <div class=" px-4 py-6 ">
                        
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>

                        <x-label label="User" value="User"/>
                        <select label="User" name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" wire:model="user_id">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    <x-input label="Employee Code" name="employee_code" wire:model="employee_code" />
                    <x-input label="Designation" name="designation" wire:model="designation" />
                    <x-input label="Department" name="department" wire:model="department" />
                    <x-input label="Phone" name="phone" wire:model="phone" />
                    <x-input label="Emergency Contact" name="emergency_contact" wire:model="emergency_contact" />
                    <x-input label="National ID" name="national_id" wire:model="national_id" />
                    <x-input label="Joining Date" name="joining_date" wire:model="joining_date" type="date" />
                    <x-input label="DOB" name="dob" wire:model="dob" type="date" />
        
                    <div>
                        <x-label label="Gender" value="Gender"/>
                        <select label="User" name="gender" wire:model="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                            <option value="">-- Select Gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
        
                    <div class="md:col-span-2">
                        <x-textarea label="Address" name="address" wire:model="address" />
                    </div>
                </div>
        
                    <x-button wire:click="save"  primary>Save</x-button>
            </div>
        </x-modal>
    </div>
    
    
</div>
