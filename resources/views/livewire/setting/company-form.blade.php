<div>
    <form wire:submit.prevent="save" class="mt-4">
        @csrf
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Name -->
                <x-input label="Company Name" wire:model="name" />

                <!-- Company Registration Number -->
                <x-input label="Registration Number" wire:model="registration_number" />

                <!-- Email -->
                <x-input label="Email" wire:model="email" type="email" />

                <!-- Phone -->
                <x-input label="Phone" wire:model="phone" />

                <!-- Address -->
                <x-textarea label="Address" name="address_line_1" wire:model="address_line_1" />

                <!-- Address -->
                <x-textarea label="Address" name="address_line_2" wire:model="address_line_2" />

                <!-- Notes -->
                {{-- <x-textarea label="Notes" name="notes" wire:model="notes" /> --}}

                <!-- City -->
                <x-input label="City" wire:model="city" />

                <!-- State -->
                <x-input label="State" wire:model="state" />

                <!-- Country -->
                <x-input label="Country" wire:model="country" />

                <!-- Zip Code -->
                <x-input label="Zip Code" wire:model="postal_code" />

                <!-- Bank Account Number -->
                <x-input label="Bank Account Number" wire:model="bank_account_number" />

                <!-- Bank IFSC Code -->
                <x-input label="Bank IFSC Code" wire:model="bank_ifsc_code" />

                <!-- Contact Person -->
                <x-input label="Contact Person" wire:model="contact_person" />

                <!-- Contact Email -->
                <x-input label="Contact Email" wire:model="contact_person_email" type="email" />

                <!-- Contact Mobile -->
                <x-input label="Contact Mobile" wire:model="contact_person_mobile" />                 
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
                    <i class="fa fa-save"></i> {{ $company ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>    
    </form>
</div>
