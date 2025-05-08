<div>
    <div class="p-6 bg-white shadow rounded -auto">
        <h2 class="text-xl font-semibold mb-6">App Settings</h2>
    
        @if (session()->has('success'))
            <div class="p-2 mb-4 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif
    
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Site Name -->
                <x-input label="Site Name" wire:model="site_name" />
    
                <!-- Date Format -->
                <div>
                    <x-label for="date_format" value="Date Format" />
                    <select id="date_format" wire:model="date_format" class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="d/m/Y">dd/mm/yyyy ({{ now()->format('d/m/Y') }})</option>
                        <option value="Y-m-d">yyyy-mm-dd ({{ now()->format('Y-m-d') }})</option>
                        <option value="m/d/Y">mm/dd/yyyy ({{ now()->format('m/d/Y') }})</option>
                    </select>
                    @error('date_format') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <!-- Timezone -->
                <div>
                    <x-label for="timezone" value="Timezone" />
                    <select id="timezone" wire:model="timezone" class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @foreach (timezone_identifiers_list() as $tz)
                            <option value="{{ $tz }}">{{ $tz }}</option>
                        @endforeach
                    </select>
                    @error('timezone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <!-- Items Per Page -->
                  <!-- Timezone -->
                  <div>
                    <x-label for="items_per_page" value="Items Per Page" />
                    <select id="items_per_page" wire:model="items_per_page" class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    @error('items_per_page') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
    
            <div class="mt-6">
                <x-button type="submit">Save Settings</x-button>
            </div>
        </form>
    </div>
    
    
</div>
