<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl font-bold mb-2">Total Users</div>
                    <div class="text-4xl text-indigo-600">{{ $totalUsers }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl font-bold mb-2">Total Installs (PWA)</div>
                    <div class="text-4xl text-green-600">{{ $totalInstalls }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Visit History (Last 30 Days)</h3>
                    <canvas id="visitsChart" class="w-full h-64"></canvas>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Growth (Last 30 Days)</h3>
                    <canvas id="usersChart" class="w-full h-64"></canvas>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">PWA Installs (Last 30 Days)</h3>
                    <canvas id="installsChart" class="w-full h-64"></canvas>
                </div>
            </div>

            <!-- Management Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.posts.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-900">Manage Blogs</div>
                            <div class="text-sm text-gray-500">Create, edit, and publish articles</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createChart = (ctxId, label, data, color) => {
                const ctx = document.getElementById(ctxId).getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.date),
                        datasets: [{
                            label: label,
                            data: data.map(item => item.count),
                            borderColor: color,
                            tension: 0.1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 }
                            }
                        }
                    }
                });
            };

            createChart('visitsChart', 'Daily Visits', @json($visits), 'rgb(79, 70, 229)');
            createChart('usersChart', 'New Users', @json($userGrowth), 'rgb(14, 165, 233)');
            createChart('installsChart', 'New Installs', @json($installGrowth), 'rgb(22, 163, 74)');
        });
    </script>
</x-app-layout>
