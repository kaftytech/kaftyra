@props(['label'=>null, 'name', 'options' => [], 'value' => ''])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full px-6 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150']) }}>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
