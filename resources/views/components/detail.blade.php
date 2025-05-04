@props(['title'=>null, 'value'])

<div class="border p-4 rounded-lg shadow-sm bg-white">
    <h3 class="text-sm font-semibold text-gray-700">{{ $title }}</h3>
    <p class="text-gray-600 text-sm mt-1">{{ $value ?? 'N/A' }}</p>
</div>
