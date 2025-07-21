<div>
    <div class="p-4 space-y-4">
        @if ($showDetails && $selectedEmployee)
            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                {{-- <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Employee Details</h2> --}}
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-sm">
                    <div>
                        <span class="font-medium text-black">Employee Code:</span>
                        <div class="mt-1">{{ $selectedEmployee->employee_code }}</div>
                    </div>
                    <div>
                        <span class="font-medium text-black">Name:</span>
                        <div class="mt-1">{{ $selectedEmployee->employee_name }}</div>
                    </div>
                    <div>
                        <span class="font-medium text-black">Designation:</span>
                        <div class="mt-1">{{ $selectedEmployee->designation }}</div>
                    </div>
                    <div>
                        <span class="font-medium text-black">Department:</span>
                        <div class="mt-1">{{ $selectedEmployee->department }}</div>
                    </div>
                    <div>
                        <span class="font-medium text-black">Phone:</span>
                        <div class="mt-1">{{ $selectedEmployee->phone }}</div>
                    </div>
                    <div>
                        <span class="font-medium text-black">Email:</span>
                        <div class="mt-1">{{ $selectedEmployee->email }}</div>
                    </div>
                    <div>
                        <span class="font-medium text-black">Joining Date:</span>
                        <div class="mt-1">{{ $selectedEmployee->joining_date }}</div>
                    </div>
                    <div>
                        <span class="font-medium text-black">Date of Birth:</span>
                        <div class="mt-1">{{ $selectedEmployee->dob }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-medium text-black">Address:</span>
                        <div class="mt-1">{{ $selectedEmployee->address }}</div>
                    </div>
                </div>
                {{-- <x-log :auditLogs="$this->auditLogs" /> --}}
                <div class="mt-4 text-right">
                    <button wire:click="closeDetails" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        @else
            <div class="flex justify-end items-center">
                <button wire:click="openModal" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">+ Add Employee</button>
            </div>
        
            @if (session()->has('message'))
                <div class="text-green-600">{{ session('message') }}</div>
            @endif
        
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Employee Code</th>
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
                            <td class="px-4 py-2"><button wire:click="view({{ $emp->id }})" class="text-blue-600 hover:underline">{{ $emp->employee_code }}</button></td>
                            <td class="px-4 py-2">{{ $emp->employee_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $emp->designation }}</td>
                            <td class="px-4 py-2">{{ $emp->department }}</td>
                            <td class="px-4 py-2">{{ $emp->phone }}</td>
                            <td class="px-4 py-2">
                                <button wire:click="edit({{ $emp->id }})" class="text-blue-600 hover:underline">Edit</button>
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
                        <x-input label="Employee Name" name="employee_name" wire:model="employee_name" />
                        <x-input label="Email" name="email" wire:model="email" />
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
                        <div class="my-4">
                            <label class="block text-sm font-medium">Assign Branches</label>
                            @foreach($branches as $branch)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" wire:model="selectedBranches" value="{{ $branch->id }}">
                                    <span>{{ $branch->name }}</span>
                                </label>
                            @endforeach
                        </div>
                         <div class="my-4">
                            <label class="block text-sm font-medium">Roles</label>
                            @foreach($roles as $role)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" wire:model.live="selectedRoles" value="{{ $role->id }}">
                                    <span>{{ $role->display_name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedRoles')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                    </div>
            
                        <x-button wire:click="save"  primary>Save</x-button>
                </div>
            </x-modal>
        @endif
    </div>
    
    
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#user_id').select2({
            placeholder: "Select User",
            allowClear: true,
            closeOnSelect: false
        });

        // Sync Livewire with Select2
        $('#user_id').on('change', function () {
            let selectedValues = $(this).val();
            @this.set('user_id', selectedValues);
        });

        Livewire.hook('message.processed', (message, component) => {
            $('#user_id').select2();
        });
    });
</script>