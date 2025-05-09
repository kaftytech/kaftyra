<!-- sidebar.blade.php -->
<div x-data="{ isOpen: window.innerWidth >= 768, activeDropdown: null }" 
    @resize.window="isOpen = window.innerWidth >= 768"
    class="min-h-screen transition-all duration-300 ease-in-out"
    :class="isOpen ? 'md:w-64' : 'md:w-20'">
   
   <div class="fixed top-0 left-0 h-full bg-gray-800 text-white shadow-lg transition-all duration-300 z-30"
        :class="isOpen ? 'w-64' : 'w-20'">
       
       <!-- Logo Section -->
       <div class="flex items-center justify-between px-4 py-5">
           <div class="flex items-center">
               <!-- Logo icon always visible -->
               <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"></path>
               </svg>
               
               <!-- Logo text - only visible when expanded -->
               <span class="ml-3 text-xl font-bold transition-opacity duration-300"
                     :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                   Kaftyra
               </span>
           </div>
           
           <!-- Toggle button - only visible on mobile -->
           <button @click="isOpen = !isOpen" class="md:hidden text-gray-300 hover:text-white focus:outline-none">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
               </svg>
           </button>
           
           <!-- Toggle button - only visible on desktop -->
           <button @click="isOpen = !isOpen" class="hidden md:block text-gray-300 hover:text-white focus:outline-none">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                         :d="isOpen ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7'"></path>
               </svg>
           </button>
       </div>
       
       <!-- Navigation -->
       <nav class="mt-5 overflow-y-auto" style="max-height: calc(100vh - 160px);">
           <div class="px-2">
               <!-- Dashboard Link -->
               <a href="{{ route('dashboard') }}" class="flex items-center py-3 px-3 mb-2 rounded-lg transition-all hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-blue-600 hover:bg-blue-700' : '' }}">
                   <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                   </svg>
                   <span class="ml-3 transition-opacity duration-200"
                         :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                       Dashboard
                   </span>
               </a>
               
               <!-- CRM Section -->
               <div x-data="{ open: false }" class="mb-2">
                   <button @click="open = !open; activeDropdown = open ? 'crm' : null" 
                           class="w-full flex items-center justify-between py-3 px-3 rounded-lg transition-all hover:bg-gray-700 focus:outline-none group {{ request()->routeIs('crm.*') ? 'bg-gray-700' : '' }}">
                       <div class="flex items-center">
                           <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                           </svg>
                           <span class="ml-3 transition-opacity duration-200"
                                 :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                               CRM
                           </span>
                       </div>
                       <svg class="w-4 h-4 transition-transform duration-200 transform"
                            :class="{'rotate-180': open, 'opacity-0': !isOpen && window.innerWidth >= 768}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                       </svg>
                   </button>
                   <div x-show="open || (!isOpen && window.innerWidth >= 768 && activeDropdown === 'crm')" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="pl-10 mt-1 space-y-1">
                       <a href="{{ route('leads.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('crm.leads') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Leads</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Leads">L</span>
                       </a>
                       <a href="{{ route('customers.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('crm.customers') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Customers</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Customers">C</span>
                       </a>
                   </div>
               </div>
               
               <!-- Inventory Section -->
               <div x-data="{ open: false }" class="mb-2">
                   <button @click="open = !open; activeDropdown = open ? 'inventory' : null" 
                           class="w-full flex items-center justify-between py-3 px-3 rounded-lg transition-all hover:bg-gray-700 focus:outline-none group {{ request()->routeIs('inventory.*') ? 'bg-gray-700' : '' }}">
                       <div class="flex items-center">
                           <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                           </svg>
                           <span class="ml-3 transition-opacity duration-200"
                                 :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                               Inventory
                           </span>
                       </div>
                       <svg class="w-4 h-4 transition-transform duration-200 transform"
                            :class="{'rotate-180': open, 'opacity-0': !isOpen && window.innerWidth >= 768}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                       </svg>
                   </button>
                   <div x-show="open || (!isOpen && window.innerWidth >= 768 && activeDropdown === 'inventory')" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="pl-10 mt-1 space-y-1">
                       <a href="{{ route('vendors.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('inventory.vendors') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Vendors</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Vendors">V</span>
                       </a>
                       <a href="{{ route('products.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('inventory.products') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Products</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Products">P</span>
                       </a>
                       <a href="{{ route('categories.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('inventory.categories') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Categories</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Categories">C</span>
                       </a>
                       <a href="{{ route('units.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('inventory.units') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Units</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Units">U</span>
                       </a>
                       <a href="{{ route('stock.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('inventory.stocks') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Stocks</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Stocks">S</span>
                       </a>
                   </div>
               </div>
                <!-- Billing Section -->
                <div x-data="{ open: false }" class="mb-2">
                    <button @click="open = !open; activeDropdown = open ? 'orders' : null" 
                            class="w-full flex items-center justify-between py-3 px-3 rounded-lg transition-all hover:bg-gray-700 focus:outline-none group {{ request()->routeIs('billing.*') ? 'bg-gray-700' : '' }}">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-300 group-hover:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                              </svg>
                              
                            <span class="ml-3 transition-opacity duration-200"
                                    :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                                Orders
                            </span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 transform"
                                :class="{'rotate-180': open, 'opacity-0': !isOpen && window.innerWidth >= 768}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open || (!isOpen && window.innerWidth >= 768 && activeDropdown === 'billing')" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="pl-10 mt-1 space-y-1">
                        <a href="{{ route('order-requests.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('billing.invoices') ? 'bg-gray-700 text-white' : '' }}"
                            :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                            <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Order Requests</span>
                            <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Order Request">I</span>
                        </a>
                        <a href="{{ route('product-returns.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('billing.invoices') ? 'bg-gray-700 text-white' : '' }}"
                            :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                            <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Product Returns</span>
                            <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Product Returns">PR</span>
                        </a>
                    </div>
                </div>
             
               <!-- Billing Section -->
               <div x-data="{ open: false }" class="mb-2">
                   <button @click="open = !open; activeDropdown = open ? 'billing' : null" 
                           class="w-full flex items-center justify-between py-3 px-3 rounded-lg transition-all hover:bg-gray-700 focus:outline-none group {{ request()->routeIs('billing.*') ? 'bg-gray-700' : '' }}">
                       <div class="flex items-center">
                           <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                           </svg>
                           <span class="ml-3 transition-opacity duration-200"
                                 :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                               Billing
                           </span>
                       </div>
                       <svg class="w-4 h-4 transition-transform duration-200 transform"
                            :class="{'rotate-180': open, 'opacity-0': !isOpen && window.innerWidth >= 768}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                       </svg>
                   </button>
                   <div x-show="open || (!isOpen && window.innerWidth >= 768 && activeDropdown === 'billing')" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="pl-10 mt-1 space-y-1">
                       <a href="{{ route('invoices.index') }}" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('billing.invoices') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Invoices</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Invoices">I</span>
                       </a>
                       <a href="#" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('billing.invoice-items') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Invoice Items</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Invoice Items">II</span>
                       </a>
                       <a href="#" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('billing.payments') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Payments</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Payments">P</span>
                       </a>
                   </div>
               </div>
               
               <!-- Settings Section -->
               <div x-data="{ open: false }" class="mb-2">
                   <button @click="open = !open; activeDropdown = open ? 'settings' : null" 
                           class="w-full flex items-center justify-between py-3 px-3 rounded-lg transition-all hover:bg-gray-700 focus:outline-none group {{ request()->routeIs('settings.*') ? 'bg-gray-700' : '' }}">
                       <div class="flex items-center">
                           <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                           </svg>
                           <span class="ml-3 transition-opacity duration-200"
                                 :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                               Settings
                           </span>
                       </div>
                       <svg class="w-4 h-4 transition-transform duration-200 transform"
                            :class="{'rotate-180': open, 'opacity-0': !isOpen && window.innerWidth >= 768}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                       </svg>
                   </button>
                   <div x-show="open || (!isOpen && window.innerWidth >= 768 && activeDropdown === 'settings')" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="pl-10 mt-1 space-y-1">
                       <a href="#" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('settings.users') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Users</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Users">U</span>
                       </a>
                       <a href="#" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('settings.profile') ? 'bg-gray-700 text-white' : '' }}"
                          :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                           <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Roles</span>
                           <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Profile">R</span>
                       </a>
                       <a href="#" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('settings.profile') ? 'bg-gray-700 text-white' : '' }}"
                        :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                         <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">Permissions</span>
                         <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Profile">P</span>
                        </a>
                        <a href="#" class="block py-2 px-3 text-sm text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('settings.profile') ? 'bg-gray-700 text-white' : '' }}"
                        :class="!isOpen && window.innerWidth >= 768 ? 'pl-0 pr-0 ml-1 mr-1' : ''">
                            <span :class="isOpen || window.innerWidth < 768 ? '' : 'hidden'">My Company</span>
                            <span :class="!isOpen && window.innerWidth >= 768 ? '' : 'hidden'" title="Profile">M</span>
                        </a>
                   </div>
               </div>
           </div>
       </nav>
       
       <!-- Profile Section -->
       <div class="absolute bottom-0 left-0 right-0 px-4 py-4 border-t border-gray-700">
           <div class="flex items-center">
               <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User avatar" class="w-10 h-10 rounded-full border-2 border-blue-500">
               <div class="ml-3 transition-opacity duration-300"
                    :class="isOpen ? 'opacity-100' : 'opacity-0 md:hidden'">
                   <p class="text-sm font-medium">John Doe</p>
                   <p class="text-xs text-gray-400">Administrator</p>
               </div>
           </div>
       </div>
   </div>
</div>