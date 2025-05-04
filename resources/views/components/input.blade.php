@props(['label' => null, 'name' => null, 'type' => 'text', 'value' => ''])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        @unless($attributes->has('wire:model'))
            value="{{ old($name, is_array($value) ? '' : $value) }}"
        @endunless
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150'
        ]) }} />
</div>
