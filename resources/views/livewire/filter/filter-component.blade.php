<div>
    <div class="relative">
        <!-- Filter Button -->
        <button wire:click="toggleFilters" class="bg-white border border-gray-300 text-gray-700 py-1 px-3 rounded text-sm hover:bg-gray-50 flex items-center">
            <i class="fas fa-filter mr-1"></i> Filter
            @if($this->hasActiveFilters())
            <span class="ml-1 flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
            @endif
        </button>
    
        <!-- Filter Dropdown -->
        <div x-data="{ show: @entangle('showFilters') }"
             x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute right-0 mt-2 w-80 md:w-96 bg-white rounded-md shadow-lg z-50"
             @click.away="show = false">
            
            <div class="p-4 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-700">Filters</h3>
                    <button wire:click="toggleFilters" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-4 max-h-96 overflow-y-auto">
                <!-- Search -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Search
                    </label>
                    <input wire:model.defer="search" type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                <!-- Dynamic Filters -->
                @foreach($filterConfig as $key => $config)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $config['label'] }}
                        </label>
                        
                        @if($config['type'] === 'select')
                            <select wire:model.defer="activeFilters.{{ $key }}" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="">All</option>
                                @foreach($config['options'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        
                        @elseif($config['type'] === 'multi-select')
                            <div class="space-y-2">
                                @foreach($config['options'] as $value => $label)
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model.defer="activeFilters.{{ $key }}" value="{{ $value }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        
                        @elseif($config['type'] === 'date-range')
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">From</label>
                                    <input type="date" wire:model.defer="activeFilters.{{ $key }}.min" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">To</label>
                                    <input type="date" wire:model.defer="activeFilters.{{ $key }}.max" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>
                        
                        @elseif($config['type'] === 'range')
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Min</label>
                                    <input type="number" wire:model.defer="activeFilters.{{ $key }}.min" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Max</label>
                                    <input type="number" wire:model.defer="activeFilters.{{ $key }}.max" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
                
                <div class="mt-6 flex justify-between">
                    <button wire:click="resetFilters" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50">
                        Reset
                    </button>
                    <button wire:click="applyFilters" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>