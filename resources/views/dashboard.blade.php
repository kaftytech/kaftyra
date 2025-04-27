@extends('layouts.app')
@section('content')
  <div class="min-h-screen">
    <!-- Main Content -->
    <div class="p-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Analytics Dashboard</h1>
          <p class="text-gray-600">Welcome back, Admin</p>
        </div>
        <div class="flex items-center space-x-4">
          <div class="relative">
            <input type="text" placeholder="Search..." class="bg-white rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
          </div>
          <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
            <i class="fas fa-user"></i>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium">Total Customers</h3>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">+12%</span>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-2xl font-bold text-gray-800">1,482</p>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium">Active Leads</h3>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">+5%</span>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-2xl font-bold text-gray-800">385</p>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-500">
              <i class="fas fa-user-plus"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium">Revenue</h3>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">+18%</span>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-2xl font-bold text-gray-800">$42,892</p>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-500">
              <i class="fas fa-dollar-sign"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium">Pending Orders</h3>
            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">+3%</span>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-2xl font-bold text-gray-800">62</p>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-500">
              <i class="fas fa-clock"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Revenue Overview</h3>
            <div class="flex items-center space-x-2">
              <button class="text-sm text-gray-600 hover:text-blue-500">Monthly</button>
              <button class="text-sm text-blue-500 font-medium">Weekly</button>
              <button class="text-sm text-gray-600 hover:text-blue-500">Daily</button>
            </div>
          </div>
          <div>
            <canvas id="revenueChart" height="300"></canvas>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Sales by Category</h3>
            <div class="text-sm text-gray-500">
              <select class="border-none bg-transparent focus:outline-none">
                <option>Last 30 days</option>
                <option>Last 60 days</option>
                <option>Last 90 days</option>
              </select>
            </div>
          </div>
          <div>
            <canvas id="categoryChart" height="300"></canvas>
          </div>
        </div>
      </div>

      <!-- Tables -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Sales -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Recent Invoices</h3>
            <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice ID</th>
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">INV-0012</td>
                  <td class="py-4 text-sm text-gray-600">John Smith</td>
                  <td class="py-4 text-sm text-gray-600">$1,240</td>
                  <td class="py-4">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">INV-0011</td>
                  <td class="py-4 text-sm text-gray-600">Sarah Johnson</td>
                  <td class="py-4 text-sm text-gray-600">$890</td>
                  <td class="py-4">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">INV-0010</td>
                  <td class="py-4 text-sm text-gray-600">Michael Davis</td>
                  <td class="py-4 text-sm text-gray-600">$2,600</td>
                  <td class="py-4">
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">INV-0009</td>
                  <td class="py-4 text-sm text-gray-600">Emily Wilson</td>
                  <td class="py-4 text-sm text-gray-600">$450</td>
                  <td class="py-4">
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Overdue</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Top Products</h3>
            <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                  <th class="pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">Premium Headphones</td>
                  <td class="py-4 text-sm text-gray-600">Electronics</td>
                  <td class="py-4 text-sm text-gray-600">142 units</td>
                  <td class="py-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-green-500 h-2 rounded-full" style="width: 78%"></div>
                    </div>
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">Wireless Keyboard</td>
                  <td class="py-4 text-sm text-gray-600">Electronics</td>
                  <td class="py-4 text-sm text-gray-600">96 units</td>
                  <td class="py-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-yellow-500 h-2 rounded-full" style="width: 42%"></div>
                    </div>
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">Office Chair</td>
                  <td class="py-4 text-sm text-gray-600">Furniture</td>
                  <td class="py-4 text-sm text-gray-600">78 units</td>
                  <td class="py-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-green-500 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-4 text-sm font-medium text-gray-800">Smart Watch</td>
                  <td class="py-4 text-sm text-gray-600">Electronics</td>
                  <td class="py-4 text-sm text-gray-600">65 units</td>
                  <td class="py-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-red-500 h-2 rounded-full" style="width: 15%"></div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Bottom Row -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lead Conversion -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Lead Conversion</h3>
            <div class="text-sm text-gray-500">
              <select class="border-none bg-transparent focus:outline-none">
                <option>This Month</option>
                <option>Last Month</option>
              </select>
            </div>
          </div>
          <div>
            <canvas id="conversionChart" height="250"></canvas>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white p-6 rounded-lg shadow-sm lg:col-span-2">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Recent Activities</h3>
            <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
          </div>
          <div class="space-y-4">
            <div class="flex items-start">
              <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-4">
                <i class="fas fa-user-plus"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-800">New lead assigned</p>
                <p class="text-sm text-gray-600">James Wilson from Acme Corp has been assigned to Sarah</p>
                <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500 mr-4">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-800">New payment received</p>
                <p class="text-sm text-gray-600">$2,450 received from TechStart Inc. for Invoice #INV-0008</p>
                <p class="text-xs text-gray-500 mt-1">5 hours ago</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500 mr-4">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-800">Low stock alert</p>
                <p class="text-sm text-gray-600">Smart Watch inventory is below threshold (5 units remaining)</p>
                <p class="text-xs text-gray-500 mt-1">1 day ago</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500 mr-4">
                <i class="fas fa-user-check"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-800">Lead converted to customer</p>
                <p class="text-sm text-gray-600">GlobalTech Ltd. has been converted from lead to customer</p>
                <p class="text-xs text-gray-500 mt-1">2 days ago</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>    <!-- Sidebar -->

    <!-- Main Content -->
    <div class="p-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">UI Components</h1>
          <p class="text-gray-600">Ready-to-use components for your CRM</p>
        </div>
      </div>

      <!-- Buttons Section -->
      <section id="buttons" class="mb-16">
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="text-xl font-semibold text-gray-800 mb-6">Buttons</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Primary Buttons -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-700 mb-2">Primary</h3>
              <div class="space-y-2">
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition duration-150 ease-in-out">
                  Primary Button
                </button>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition duration-150 flex items-center justify-center">
                  <i class="fas fa-plus mr-2"></i> Add New
                </button>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm font-medium transition duration-150">
                  Small Button
                </button>
              </div>
            </div>
            
            <!-- Secondary Buttons -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-700 mb-2">Secondary</h3>
              <div class="space-y-2">
                <button class="w-full border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 py-2 px-4 rounded-lg font-medium transition duration-150">
                  Secondary Button
                </button>
                <button class="w-full border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 py-2 px-4 rounded-lg font-medium transition duration-150 flex items-center justify-center">
                  <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <button class="w-full border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 py-1 px-3 rounded-lg text-sm font-medium transition duration-150">
                  Small Button
                </button>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-700 mb-2">Action</h3>
              <div class="space-y-2">
                <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition duration-150">
                  Save
                </button>
                <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition duration-150">
                  Delete
                </button>
                <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg font-medium transition duration-150">
                  Warning
                </button>
              </div>
            </div>
          </div>
          
          <!-- Icon Buttons -->
          <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Icon Buttons</h3>
            <div class="flex flex-wrap gap-4">
              <button class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition duration-150">
                <i class="fas fa-edit"></i>
              </button>
              <button class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition duration-150">
                <i class="fas fa-check"></i>
              </button>
              <button class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition duration-150">
                <i class="fas fa-trash"></i>
              </button>
              <button class="border border-gray-300 hover:bg-gray-50 text-gray-700 p-2 rounded-lg transition duration-150">
                <i class="fas fa-eye"></i>
              </button>
              <button class="border border-gray-300 hover:bg-gray-50 text-gray-700 p-2 rounded-lg transition duration-150">
                <i class="fas fa-download"></i>
              </button>
              <button class="border border-gray-300 hover:bg-gray-50 text-gray-700 p-2 rounded-lg transition duration-150">
                <i class="fas fa-print"></i>
              </button>
            </div>
          </div>
          
          <!-- Button Groups -->
          <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Button Groups</h3>
            <div class="inline-flex rounded-lg overflow-hidden">
              <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 font-medium transition duration-150">
                Day
              </button>
              <button class="bg-blue-700 text-white py-2 px-4 font-medium transition duration-150">
                Week
              </button>
              <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 font-medium transition duration-150">
                Month
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Tables Section -->
      <section id="tables" class="mb-16">
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="text-xl font-semibold text-gray-800 mb-6">Tables</h2>
          
          <!-- Standard Table -->
          <div class="mb-12">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Standard Table</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white">
                <thead>
                  <tr class="bg-gray-50 border-b">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">John Smith</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">john@example.com</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Admin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                      <button class="text-blue-600 hover:text-blue-900">Edit</button>
                      <button class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                    </td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Jane Cooper</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">jane@example.com</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">User</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                      <button class="text-blue-600 hover:text-blue-900">Edit</button>
                      <button class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                    </td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Robert Fox</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">robert@example.com</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">User</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                      <button class="text-blue-600 hover:text-blue-900">Edit</button>
                      <button class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Striped Table -->
          <div class="mb-12">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Striped Table</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white">
                <thead>
                  <tr class="bg-gray-50 border-b">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="bg-white">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">INV-0012</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">John Smith</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Apr 15, 2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$1,240</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                    </td>
                  </tr>
                  <tr class="bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">INV-0011</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Sarah Johnson</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Apr 12, 2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$890</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                    </td>
                  </tr>
                  <tr class="bg-white">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">INV-0010</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Michael Davis</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Apr 10, 2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$2,600</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                    </td>
                  </tr>
                  <tr class="bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">INV-0009</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Emily Wilson</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Apr 5, 2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$450</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Overdue</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Card Table With Pagination -->
          <div>
            <h3 class="text-lg font-medium text-gray-700 mb-4">Card Table With Pagination</h3>
            <div class="bg-white rounded-lg border border-gray-200">
              <div class="flex justify-between items-center p-4 border-b">
                <h4 class="font-medium text-gray-700">Products</h4>
                <div class="flex space-x-2">
                  <button class="bg-white border border-gray-300 text-gray-700 py-1 px-3 rounded text-sm hover:bg-gray-50">
                    <i class="fas fa-filter mr-1"></i> Filter
                  </button>
                  <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                    <i class="fas fa-plus mr-1"></i> Add
                  </button>
                </div>
              </div>
              
              <div class="overflow-x-auto">
                <table class="min-w-full">
                  <thead>
                    <tr class="bg-gray-50">
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                      <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-box text-gray-500"></i>
                          </div>
                          <div>
                            <div class="text-sm font-medium text-gray-800">Premium Headphones</div>
                            <div class="text-xs text-gray-500">#PRD-0123</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Electronics</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$129.99</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">In Stock</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <button class="text-blue-600 hover:text-blue-900">Edit</button>
                        <button class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                      </td>
                    </tr>
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-box text-gray-500"></i>
                          </div>
                          <div>
                            <div class="text-sm font-medium text-gray-800">Wireless Keyboard</div>
                            <div class="text-xs text-gray-500">#PRD-0124</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Electronics</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$79.99</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <button class="text-blue-600 hover:text-blue-900">Edit</button>
                        <button class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                      </td>
                    </tr>
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-box text-gray-500"></i>
                          </div>
                          <div>
                            <div class="text-sm font-medium text-gray-800">Office Chair</div>
                            <div class="text-xs text-gray-500">#PRD-0125</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Furniture</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$199.99</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">In Stock</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <button class="text-blue-600 hover:text-blue-900">Edit</button>
                        <button class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <div class="px-6 py-4 border-t flex items-center justify-between">
                <div class="text-sm text-gray-600">
                  Showing 1 to 3 of 12 entries
                </div>
                <div class="flex space-x-2">
                  <button class="px-3 py-1 bg-white border border-gray-300 rounded text-gray-600 hover:bg-gray-50 disabled:opacity-50" disabled>
                    Previous
                  </button>
                  <button class="px-3 py-1 bg-blue-600 border border-blue-600 rounded text-white hover:bg-blue-700">
                    1
                  </button>
                  <button class="px-3 py-1 bg-white border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    2
                  </button>
                  <button class="px-3 py-1 bg-white border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    3
                  </button>
                  <button class="px-3 py-1 bg-white border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    Next
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Forms Section -->
      <section id="forms" class="mb-16">
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="text-xl font-semibold text-gray-800 mb-6">Forms</h2>
          
          <!-- Input Fields -->
          <div class="mb-12">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Input Fields</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Basic Input -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Full Name
                </label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter your name">
              </div>
              
              <!-- Email Input -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Email Address
                </label>
                <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="email@example.com">
              </div>
              
              <!-- Input with Icon -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Search
                </label>
                <div class="relative">
                  <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Search...">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                  </div>
                </div>
              </div>
              
              <!-- Input with Help Text -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Password
                </label>
                <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter password">
                <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long.</p>
              </div>
            </div>
          </div>
          
          <!-- Select & Checkboxes -->
          <div class="mb-12">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Select & Checkboxes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Select -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Country
                </label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 bg-white">
                  <option value="">Select a country</option>
                  <option value="us">United States</option>
                  <option value="ca">Canada</option>
                  <option value="uk">United Kingdom</option>
                  <option value="au">Australia</option>
                </select>
              </div>
               <!-- Multi Select -->
               <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Role
                </label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 bg-white" multiple>
                  <option value="admin">Admin</option>
                  <option value="editor">Editor</option>
                  <option value="user">User</option>
                  <option value="guest">Guest</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple options.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Tables Section -->
  </div>

  <script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'Revenue',
          data: [4800, 5200, 4700, 6500, 5900, 7000, 6800],
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 2,
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
      type: 'bar',
      data: {
        labels: ['Electronics', 'Furniture', 'Clothing', 'Software', 'Services'],
        datasets: [{
          label: 'Sales',
          data: [12500, 8700, 6200, 9300, 7800],
          backgroundColor: [
            'rgba(59, 130, 246, 0.7)',
            'rgba(139, 92, 246, 0.7)',
            'rgba(16, 185, 129, 0.7)',
            'rgba(245, 158, 11, 0.7)',
            'rgba(239, 68, 68, 0.7)'
          ],
          borderWidth: 0,
          borderRadius: 4
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    // Conversion Chart
    const conversionCtx = document.getElementById('conversionChart').getContext('2d');
    const conversionChart = new Chart(conversionCtx, {
      type: 'doughnut',
      data: {
        labels: ['Converted', 'In Progress', 'Lost'],
        datasets: [{
          data: [65, 25, 10],
          backgroundColor: [
            'rgba(16, 185, 129, 0.7)',
            'rgba(245, 158, 11, 0.7)',
            'rgba(239, 68, 68, 0.7)'
          ],
          borderWidth: 0
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'bottom'
          }
        },
        cutout: '65%'
      }
    });
  </script>
@endsection