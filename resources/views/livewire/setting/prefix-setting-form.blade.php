<div class="space-y-6 bg-white p-6 rounded-lg shadow-md">
    @foreach ($types as $model => $label)
        <div class="grid grid-cols-6 gap-4 items-center border-b pb-4">
            <!-- Label with slight text color and bold style -->
            <div class="col-span-1 font-semibold text-gray-700">{{ $label }}</div>

            <!-- Prefix input with light border, rounded edges, and subtle hover effect -->
            <div>
                <input type="text" wire:model.defer="settings.{{ $model }}.prefix"
                       class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Prefix">
            </div>

            <!-- Suffix input with similar styling -->
            <div>
                <input type="text" wire:model.defer="settings.{{ $model }}.suffix"
                       class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Suffix">
            </div>

            <!-- Start Number input with rounded corners and consistent styling -->
            <div>
                <input type="number" wire:model.defer="settings.{{ $model }}.start_number"
                       class="border border-gray-300 text-gray-700 p-3 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Start Number">
            </div>

            <!-- Save button with a soft hover effect -->
            <div class="col-span-1">
                <button wire:click="save('{{ $model }}')" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors duration-300">
                    Save
                </button>
            </div>
        </div>
    @endforeach

    <!-- Success message with light styling -->
    @if (session()->has('message'))
        <div class="mt-4 text-green-600 text-sm font-semibold">{{ session('message') }}</div>
    @endif
</div>
