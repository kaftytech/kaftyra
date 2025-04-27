<!-- navbar.blade.php -->
<div class="sticky top-0 z-20 bg-white dark:bg-gray-900 shadow-md"
     x-data="{ isOpen: false, profileDropdown: false }" 
     :class="{'shadow-lg': isOpen}">
    
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side - Hamburger menu (mobile only) -->
            <div class="flex items-center md:hidden">
                <button @click="$dispatch('toggle-sidebar')" type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
            <!-- Center - Search (hidden on mobile) -->
            <div class="hidden md:flex md:flex-1 justify-center px-2 lg:ml-6 lg:justify-end">
                <div class="max-w-lg w-full lg:max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input id="search" name="search" class="block w-full pl-10 pr-3 py-2 rounded-md text-sm border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Search" type="search">
                    </div>
                </div>
            </div>

            <!-- Right side - Notification and Profile -->
            <div class="flex items-center">
                <!-- Notification dropdown -->
                <div class="relative ml-3" x-data="{ notificationOpen: false }">
                    <button @click="notificationOpen = !notificationOpen" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1">
                        <div class="relative">
                            <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                        </div>
                    </button>
                    
                    <!-- Notification panel -->
                    <div x-show="notificationOpen" 
                         @click.away="notificationOpen = false"
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95" 
                         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <!-- Notification items -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New message received</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">From: support@example.com</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">2 minutes ago</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Project approved</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Your proposal has been accepted</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2 text-center border-t border-gray-200 dark:border-gray-700">
                            <a href="#" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile dropdown -->
                <div class="relative ml-3" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1" id="user-menu-button">
                            <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            <span class="hidden md:block text-gray-700 dark:text-gray-300">John Doe</span>
                            <svg class="hidden md:block h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95" 
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" 
                         role="menu" 
                         aria-orientation="vertical" 
                         aria-labelledby="user-menu-button" 
                         tabindex="-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Your Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="isOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <div class="block px-3 py-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input id="mobile-search" name="search" class="block w-full pl-10 pr-3 py-2 rounded-md text-sm border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Search" type="search">
                </div>
            </div>
        </div>
    </div>
</div>