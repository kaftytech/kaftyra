@props(['type', 'message'])

@php
    $colors = [
        'success' => 'green',
        'error' => 'red',
        'warning' => 'yellow',
        'info' => 'blue',
    ];
    $color = $colors[$type] ?? 'gray';
@endphp

<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition
    class="flex items-center justify-between gap-4 p-4 mb-4 text-sm text-{{ $color }}-700 bg-{{ $color }}-100 border border-{{ $color }}-200 rounded-lg shadow"
    role="alert"
>
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-{{ $color }}-600" fill="currentColor" viewBox="0 0 20 20">
            @if ($type === 'success')
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5l4-4-1.4-1.4L9 10.2 7.4 8.6 6 10l3 3z" clip-rule="evenodd" />
            @elseif ($type === 'error')
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11h-2v4h2V7zm0 6h-2v2h2v-2z" clip-rule="evenodd" />
            @elseif ($type === 'warning')
                <path fill-rule="evenodd" d="M8.257 3.099c.366-.446.995-.533 1.441-.167.172.14.29.331.343.541l3.387 12.387c.162.592-.194 1.205-.786 1.367-.592.162-1.205-.194-1.367-.786L9.293 7H7.707l-.982 3.6c-.162.592-.775.948-1.367.786-.592-.162-.948-.775-.786-1.367l2.146-7.834a1 1 0 011.539-.486z" clip-rule="evenodd"/>
            @else
                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-9-4h2v2H9V6zm0 4h2v4H9v-4z" clip-rule="evenodd"/>
            @endif
        </svg>

        <div>
            <span class="font-medium capitalize">{{ $type }}:</span> {{ $message }}
        </div>
    </div>

    <button
        x-on:click="show = false"
        class="text-{{ $color }}-500 hover:text-{{ $color }}-700"
    >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
</div>
