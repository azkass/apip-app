@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Common chart options
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: window.innerWidth < 768 ? 'top' : 'bottom',
                        align: 'center',
                        labels: {
                            boxWidth: window.innerWidth < 768 ? 10 : 12,
                            padding: window.innerWidth < 768 ? 8 : 12,
                            font: {
                                size: window.innerWidth < 768 ? 10 : 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        align: 'center',
                        font: {
                            size: window.innerWidth < 768 ? 14 : 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 5,
                            bottom: 5
                        }
                    }
                }
            };

            // User Roles Chart
            const userRolesOptions = Object.assign({}, commonOptions);
            userRolesOptions.plugins.title.text = 'Distribusi Pengguna Berdasarkan Role';
            
            new Chart(
                document.getElementById('userRolesChart').getContext('2d'), 
                {
                    type: 'pie',
                    data: {
                        labels: {!! isset($roleLabels) ? json_encode($roleLabels) : '[]' !!},
                        datasets: [{
                            data: {!! isset($roleCounts) ? json_encode($roleCounts) : '[]' !!},
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: userRolesOptions
                }
            );

            // Procedure Status Chart
            const procedureOptions = Object.assign({}, commonOptions);
            procedureOptions.plugins.title.text = 'Status Prosedur Pengawasan Tahun {{ $currentYear ?? date("Y") }}';
            
            new Chart(
                document.getElementById('procedureStatusChart').getContext('2d'), 
                {
                    type: 'pie',
                    data: {
                        labels: {!! isset($statusLabels) ? json_encode($statusLabels) : '[]' !!},
                        datasets: [{
                            data: {!! isset($statusCounts) ? json_encode($statusCounts) : '[]' !!},
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: procedureOptions
                }
            );

            // Handle resize events
            window.addEventListener('resize', function() {
                const legends = Chart.instances.map(chart => chart.options.plugins.legend);
                legends.forEach(legend => {
                    legend.position = window.innerWidth < 768 ? 'top' : 'bottom';
                    legend.labels.boxWidth = window.innerWidth < 768 ? 10 : 12;
                    legend.labels.padding = window.innerWidth < 768 ? 8 : 12;
                    legend.labels.font.size = window.innerWidth < 768 ? 10 : 12;
                });
                
                Chart.instances.forEach(chart => chart.update());
            });
        });
    </script>
@endpush

<div class="p-3 md:p-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- User Summary Card -->
        <div class="bg-white rounded-lg shadow-lg p-3 md:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 md:mb-4">
                <h2 class="text-lg md:text-xl font-semibold mb-2 sm:mb-0">Pengguna Sistem</h2>
                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold self-start sm:self-auto">Total: {{ $totalUsers ?? 0 }}</div>
            </div>
            <div class="h-48 sm:h-56 md:h-64 flex items-center justify-center">
                <canvas id="userRolesChart"></canvas>
            </div>
            <div class="mt-3 md:mt-4 grid grid-cols-1 gap-2">
                @if(isset($userRoles))
                    @foreach($userRoles as $role)
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                            <span class="font-medium text-sm md:text-base truncate mr-2">
                                @if($role->role == 'pjk')
                                    Penanggung Jawab Kegiatan
                                @else
                                    {{ ucfirst($role->role) }}
                                @endif
                            </span>
                            <span class="bg-blue-100 text-blue-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-semibold whitespace-nowrap">{{ $role->count }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Procedure Summary Card -->
        <div class="bg-white rounded-lg shadow-lg p-3 md:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 md:mb-4">
                <h2 class="text-lg md:text-xl font-semibold mb-2 sm:mb-0">Prosedur Pengawasan {{ $currentYear ?? date('Y') }}</h2>
                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold self-start sm:self-auto">Total: {{ $totalProcedures ?? 0 }}</div>
            </div>
            <div class="h-48 sm:h-56 md:h-64 flex items-center justify-center">
                <canvas id="procedureStatusChart"></canvas>
            </div>
            <div class="mt-3 md:mt-4 grid grid-cols-1 gap-2">
                @if(isset($procedureStatuses))
                    @foreach($procedureStatuses as $status)
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                            <span class="font-medium text-sm md:text-base truncate mr-2">{{ ucfirst($status->status) }}</span>
                            <span class="bg-green-100 text-green-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-semibold whitespace-nowrap">{{ $status->count }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
