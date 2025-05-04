<!-- navbar.blade.php -->
<div class="sticky top-0 z-20 bg-white dark:bg-gray-900 shadow-md" x-data="{ isOpen: false, profileDropdown: false }">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Left: Hamburger (Mobile only) -->
            <div class="flex items-center md:hidden">
                <button @click="$dispatch('toggle-sidebar')" type="button"
                    class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Center (optional search or spacer for alignment) -->
            <div class="flex-1 hidden md:flex justify-center">
                {{-- Add content here if needed (like search) --}}
            </div>

            <!-- Right: Notification + Profile -->
            <div class="flex items-center space-x-4">
                <!-- Notification -->
                <div class="relative" x-data="{ notificationOpen: false }">
                    <button @click="notificationOpen = !notificationOpen"
                        class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1">
                        <div class="relative">
                            <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                        </div>
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="notificationOpen" @click.away="notificationOpen = false"
                        x-transition
                        class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-30">
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications->take(5) as $notification) 
                                <a href="{{ route('notifications.read', $notification->id) }}"
                                    class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">No new notifications</div>
                            @endforelse
                        </div>
                        <div class="px-4 py-2 text-center border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('notifications.index') }}"
                                class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1">
                        <img class="h-8 w-8 rounded-full object-cover"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=128"
                            alt="{{ Auth::user()->name }}">
                        <span class="hidden md:block text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                        <svg class="hidden md:block h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Profile dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition
                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-30">
                        <a href="/user/profile"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Your Profile
                        </a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="isOpen" class="md:hidden px-2 pt-2 pb-3 space-y-1">
        <!-- Add mobile-specific links if needed -->
    </div>
</div>
