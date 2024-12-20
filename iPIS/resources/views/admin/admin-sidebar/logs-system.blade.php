<x-app-layout>
    <!-- Main Dashboard Container -->
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Logs Management Dashboard</h1>

        <!-- Metrics Cards Container -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Active Users Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-medium">Active Users</h3>
                <p class="text-3xl font-bold mt-2">{{ $activeUsers }}</p>
                <p class="text-sm text-gray-500">Last 30 days</p>
            </div>

            <!-- Active Admins Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-medium">Active Admins</h3>
                <p class="text-3xl font-bold mt-2">{{ $activeAdmins }}</p>
                <p class="text-sm text-gray-500">Last 30 days</p>
            </div>

            <!-- Current Active Users Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-medium">Current Active Users</h3>
                <p class="text-3xl font-bold mt-2">{{ $currentActiveUsers }}</p>
                <p class="text-sm text-gray-500">Last 15 minutes</p>
            </div>
        </div>

        <!-- User Activity Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">User Activity (Last 7 Days)</h2>
            <div class="h-[300px]" id="activityChart"></div>
        </div>

        <!-- User Profiles Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Recent Active Users</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentUsers as $user)
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-{{ $user->role === 'admin' ? 'red' : 'blue' }}-500 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold">{{ $user->first_name }} {{ $user->last_name }}</h3>
                                <span class="inline-block bg-{{ $user->role === 'admin' ? 'red' : 'blue' }}-100 text-{{ $user->role === 'admin' ? 'red' : 'blue' }}-600 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                        <p class="text-sm mt-2">
                            <span class="inline-block bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">Active</span>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        // Chart Configuration
        var options = {
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Active Users',
                data: @json($userCounts)
            }],
            colors: ['#10B981'],
            xaxis: {
                categories: @json($dates)
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            legend: {
                position: 'bottom'
            }
        };

        // Initialize Chart
        var chart = new ApexCharts(document.querySelector("#activityChart"), options);
        chart.render();
    </script>
</x-app-layout>