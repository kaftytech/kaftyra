<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> {{-- ✅ Crucial for mobile responsiveness --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Styles --}}
    @livewireStyles
</head>
<body class="min-h-screen font-sans text-gray-900 antialiased bg-cover bg-center"
      style="background-image: url('/images/background.jpg');">

    <div class="bg-black bg-opacity-50 min-h-screen flex items-center justify-center px-4 md:justify-end md:pr-32">
        {{-- 📱 This slot content will now be padded on small screens --}}
        <div class="w-full max-w-md md:max-w-full">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
