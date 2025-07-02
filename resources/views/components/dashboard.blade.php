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
                        position: 'bottom',
                        align: 'center'
                    },
                    title: {
                        display: true,
                        align: 'center',
                        font: {
                            size: 16,
                            weight: 'bold'
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
                    type: 'doughnut',
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
        });
    </script>
@endpush

<div class="p-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- User Summary Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Pengguna Sistem</h2>
                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">Total: {{ $totalUsers ?? 0 }}</div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="userRolesChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-2">
                @if(isset($userRoles))
                    @foreach($userRoles as $role)
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                            <span class="font-medium">
                                @if($role->role == 'pjk')
                                    Penanggung Jawab Kegiatan
                                @else
                                    {{ ucfirst($role->role) }}
                                @endif
                            </span>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $role->count }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Procedure Summary Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Prosedur Pengawasan {{ $currentYear ?? date('Y') }}</h2>
                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Total: {{ $totalProcedures ?? 0 }}</div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="procedureStatusChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-2">
                @if(isset($procedureStatuses))
                    @foreach($procedureStatuses as $status)
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                            <span class="font-medium">{{ ucfirst($status->status) }}</span>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $status->count }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
