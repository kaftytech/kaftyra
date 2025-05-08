<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
        <!-- Styles -->
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex" x-data="{ sidebarOpen: window.innerWidth >= 768 }">
            <!-- Sidebar -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
                 class="fixed inset-y-0 left-0 z-40 md:relative md:translate-x-0">
                @include('layouts.sidebar')
            </div>
            
            <!-- Backdrop for mobile sidebar -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 md:hidden">
            </div>
    
            <div class="flex-1 flex flex-col">
                <!-- Navbar -->
                @include('layouts.navbar')
                @if (session('success'))
                    <x-message type="success" :message="session('success')" />
                @endif

                @if (session('error'))
                    <x-message type="error" :message="session('error')" />
                @endif

                @if (session('warning'))
                    <x-message type="warning" :message="session('warning')" />
                @endif


                <!-- Page Content -->
                <main class="flex-1 px-4 sm:px-6 lg:px-4 py-6 overflow-y-auto">
                    @yield('content')
                </main>
                
                <!-- Footer -->
                <footer class="bg-white dark:bg-gray-800 py-4 px-4 sm:px-6 lg:px-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </div>
                </footer>
            </div>
        </div>        
        @livewireScripts
        @stack('scripts') 
    </body>
</html>
