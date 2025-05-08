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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="stats-container">
         {{-- <div class="bg-white rounded-lg p-6 shadow-sm">
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
        </div> --}}
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Revenue Overview</h3>
            <div class="flex items-center space-x-2">
              <button data-period="monthly" class="revenue-period-btn text-sm text-gray-600 hover:text-blue-500">Monthly</button>
              <button data-period="weekly" class="revenue-period-btn text-sm text-blue-500 font-medium">Weekly</button>
              <button data-period="daily" class="revenue-period-btn text-sm text-gray-600 hover:text-blue-500">Daily</button>

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
              <select id="categoryFilter" class="border-none bg-transparent focus:outline-none">
                <option value="30">Last 30 days</option>
                <option value="60">Last 60 days</option>
                <option value="90">Last 90 days</option>
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

        <div class="bg-white p-6 rounded-lg shadow-sm lg:col-span-2">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg text-gray-800">Recent Activities</h3>
            <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
          </div>
          <div id="recent-activities" class="space-y-4">
            <!-- Recent activities will be populated here -->
          </div>
        </div>
      </div>
    </div>  

  <script>
    // Revenue Chart
    let revenueChart;

    function loadRevenueChart(period = 'weekly') {
        fetch(`/dashboard/revenue-data?period=${period}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.label);
                const values = data.map(item => parseFloat(item.value));

                if (revenueChart) {
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = values;
                    revenueChart.update();
                } else {
                    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                    revenueChart = new Chart(revenueCtx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Revenue',
                                data: values,
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { drawBorder: false } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
            });
    }

    // Initial load
    loadRevenueChart('weekly');

    // Event listeners for buttons
    document.querySelectorAll('.revenue-period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.revenue-period-btn').forEach(b => b.classList.remove('text-blue-500', 'font-medium'));
            btn.classList.add('text-blue-500', 'font-medium');

            const period = btn.dataset.period;
            loadRevenueChart(period);
        });
    });
    // Category Chart
    let categoryChart;

    function fetchCategoryData(days = 30) {
      fetch(`/dashboard/category-sales?days=${days}`)
        .then(response => response.json())
        .then(data => {
          const labels = data.map(item => item.label);
          const values = data.map(item => item.value);

          if (categoryChart) {
            categoryChart.data.labels = labels;
            categoryChart.data.datasets[0].data = values;
            categoryChart.update();
          } else {
            const ctx = document.getElementById('categoryChart').getContext('2d');
            categoryChart = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [{
                  label: 'Sales',
                  data: values,
                  backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(239, 68, 68, 0.7)'
                  ],
                  borderRadius: 4
                }]
              },
              options: {
                plugins: { legend: { display: false } },
                scales: {
                  y: { beginAtZero: true, grid: { drawBorder: false } },
                  x: { grid: { display: false } }
                }
              }
            });
          }
        });
    }

    document.getElementById('categoryFilter').addEventListener('change', function () {
      fetchCategoryData(this.value);
    });

    // Initial Load
    fetchCategoryData();
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

        function loadRecentActivities() {
        fetch('/dashboard/recent-activities')  // Adjust the URL to your route
            .then(res => res.json())
            .then(activities => {
                const activitiesContainer = document.getElementById('recent-activities');
                activitiesContainer.innerHTML = '';  // Clear the container

                activities.forEach(activity => {
                    // Create the activity elements
                    const activityElement = document.createElement('div');
                    activityElement.classList.add('flex', 'items-start', 'mb-4');

                    const iconContainer = document.createElement('div');
                    iconContainer.classList.add('w-10', 'h-10', 'rounded-full', 'flex', 'items-center', 'justify-center', 'mr-4');
                    iconContainer.classList.add(activity.icon_class); // Example: 'bg-blue-100' or 'bg-green-100'

                    const icon = document.createElement('i');
                    icon.classList.add(activity.icon);  // Example: 'fas fa-plus-circle'

                    iconContainer.appendChild(icon);

                    const textContainer = document.createElement('div');

                    const title = document.createElement('p');
                    title.classList.add('text-sm', 'font-medium', 'text-gray-800');
                    title.textContent = activity.activity_type;  // Activity title

                    const description = document.createElement('p');
                    description.classList.add('text-sm', 'text-gray-600');
                    description.textContent = activity.description;  // Activity description

                    const time = document.createElement('p');
                    time.classList.add('text-xs', 'text-gray-500', 'mt-1');
                    time.textContent = activity.created_at;  // Formatted time (e.g., "2 hours ago")

                    textContainer.appendChild(title);
                    textContainer.appendChild(description);
                    textContainer.appendChild(time);

                    activityElement.appendChild(iconContainer);
                    activityElement.appendChild(textContainer);

                    activitiesContainer.appendChild(activityElement);
                });
            });
    }

    // Load activities when the page loads
    document.addEventListener('DOMContentLoaded', loadRecentActivities);
  </script>
  <script>
    // Function to fetch and display the stats
    function loadStats() {
        fetch('/stats') // Adjust the endpoint if necessary
            .then(response => response.json())
            .then(data => {
                const statsContainer = document.getElementById('stats-container');
                
                // Define the stat cards
                const statCards = [
                    {
                        title: "Total Customers",
                        value: data.totalCustomers,
                        iconClass: "fas fa-users",
                        percentage: "+12%",
                        bgClass: "bg-green-100",
                        textClass: "text-green-800"
                    },
                    {
                        title: "Active Leads",
                        value: data.activeLeads,
                        iconClass: "fas fa-user-plus",
                        percentage: "+5%",
                        bgClass: "bg-purple-100",
                        textClass: "text-purple-500"
                    },
                    {
                        title: "Revenue",
                        value: "₹" + data.revenue.toLocaleString(), // Format revenue with ₹ symbol
                        iconClass: "fas fa-rupee-sign",  // Replace dollar icon with rupee icon
                        percentage: "+18%",
                        bgClass: "bg-green-100",
                        textClass: "text-green-500"
                    },
                    {
                        title: "Pending Orders",
                        value: data.pendingOrders,
                        iconClass: "fas fa-clock",
                        percentage: "+3%",
                        bgClass: "bg-yellow-100",
                        textClass: "text-yellow-500"
                    }
                ];

                // Create HTML for each stat card
                statsContainer.innerHTML = statCards.map(stat => {
                    return `
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-500 text-sm font-medium">${stat.title}</h3>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">${stat.percentage}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-2xl font-bold text-gray-800">${stat.value}</p>
                                <div class="w-12 h-12 ${stat.bgClass} rounded-full flex items-center justify-center ${stat.textClass}">
                                    <i class="${stat.iconClass}"></i>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            })
            .catch(error => {
                console.error('Error fetching stats:', error);
            });
    }

    // Call the function to load the stats when the page loads
    document.addEventListener('DOMContentLoaded', loadStats);
</script>
@endsection