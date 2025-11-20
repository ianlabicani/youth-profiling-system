@extends('brgy.shell')

@section('title', 'Youth Demographics Report')
@section('brgy-content')
@php
    $title = 'Youth Demographics Report';
    $description = 'Analyze youth population by age, education, income, and status';
    $showFilters = true;
    $exportButton = true;

    // Prepare filters
    $barangayOptions = $barangays->pluck('name', 'id')->toArray();
    $filters = [
        [
            'name' => 'barangay',
            'label' => 'Barangay',
            'type' => 'select',
            'options' => $barangayOptions
        ],
        [
            'name' => 'start_date',
            'label' => 'Start Date',
            'type' => 'date'
        ],
        [
            'name' => 'end_date',
            'label' => 'End Date',
            'type' => 'date'
        ]
    ];

    // Calculate statistics
    $totalYouth = $reportData['total_youth'];
    $stats = [
        [
            'label' => 'Total Youth',
            'value' => $totalYouth,
            'icon' => '👥',
        ],
        [
            'label' => 'Out of School',
            'value' => $reportData['out_of_school'],
            'icon' => '📚',
            'subtitle' => round(($reportData['out_of_school'] / max(1, $totalYouth)) * 100, 1) . '%'
        ],
        [
            'label' => 'Male',
            'value' => $reportData['by_sex']['Male'] ?? 0,
            'icon' => '♂️',
        ],
        [
            'label' => 'Female',
            'value' => $reportData['by_sex']['Female'] ?? 0,
            'icon' => '♀️',
        ],
    ];
@endphp

<div class="p-8">
    <!-- AI Summary Section -->
    @if(isset($aiSummary))
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200 mb-8">
            <div class="flex items-start gap-4">
                <div class="text-3xl">✨</div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Executive Summary</h2>
                    @php
                        $lines = array_filter(array_map('trim', explode("\n", $aiSummary)));
                    @endphp
                    <div class="space-y-4">
                        @foreach($lines as $line)
                            @if(str_starts_with($line, '📊'))
                                <div class="text-lg font-semibold text-indigo-900">{{ $line }}</div>
                            @elseif(str_starts_with($line, 'Finding:'))
                                <div class="border-l-4 border-indigo-400 pl-4 py-2">
                                    <p class="text-slate-700 leading-relaxed">{{ str_replace('Finding:', '', $line) }}</p>
                                </div>
                            @elseif(str_starts_with($line, '🔍'))
                                <div class="flex items-start gap-3 mt-3">
                                    <span class="text-lg flex-shrink-0">🔍</span>
                                    <div>
                                        <p class="font-semibold text-slate-900 mb-1">Key Insight</p>
                                        <p class="text-slate-700 leading-relaxed">{{ str_replace('🔍 Key Insight:', '', $line) }}</p>
                                    </div>
                                </div>
                            @elseif(str_starts_with($line, '💡'))
                                <div class="flex items-start gap-3 mt-3">
                                    <span class="text-lg flex-shrink-0">💡</span>
                                    <div>
                                        <p class="font-semibold text-slate-900 mb-1">Recommendation</p>
                                        <p class="text-slate-700 leading-relaxed">{{ str_replace('💡 Recommendation:', '', $line) }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Report Stats Section -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach($stats as $stat)
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-lg p-4 border border-slate-200">
                <p class="text-xs font-medium text-slate-600 uppercase mb-2">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                @if(isset($stat['subtitle']))
                    <p class="text-xs text-slate-500 mt-1">{{ $stat['subtitle'] }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Age Distribution -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Age Group Distribution</h3>
            <canvas id="ageChart"></canvas>
            @if(isset($aiInsights['age_group']))
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $aiInsights['age_group']['description'] }}</p>
                </div>
            @endif
        </div>

        <!-- Sex Distribution -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">By Sex</h3>
            <canvas id="sexChart"></canvas>
            @if(isset($aiInsights['sex']))
                <div class="mt-4 pt-4 border-t border-purple-200">
                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $aiInsights['sex']['description'] }}</p>
                </div>
            @endif
        </div>

        <!-- Status Distribution -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">By Status</h3>
            <canvas id="statusChart"></canvas>
        </div>

        <!-- Education Distribution -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 border border-orange-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">By Education Status</h3>
            <canvas id="educationChart"></canvas>
            @if(isset($aiInsights['education']))
                <div class="mt-4 pt-4 border-t border-orange-200">
                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $aiInsights['education']['description'] }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Income Distribution -->
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-6 border border-indigo-200 mb-8">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Household Income Distribution</h3>
        <canvas id="incomeChart"></canvas>
        @if(isset($aiInsights['income']))
            <div class="mt-4 pt-4 border-t border-indigo-200">
                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $aiInsights['income']['description'] }}</p>
            </div>
        @endif
    </div>


</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const colorPalette = ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#84cc16'];

        function getColors(n) {
            const out = [];
            for (let i = 0; i < n; i++) out.push(colorPalette[i % colorPalette.length]);
            return out;
        }

        // Prepare data
        const ageLabels = {!! json_encode(array_keys($reportData['by_age_group'])) !!};
        const ageData = {!! json_encode(array_values($reportData['by_age_group'])) !!};

        const sexLabels = {!! json_encode(array_keys($reportData['by_sex'])) !!};
        const sexData = {!! json_encode(array_values($reportData['by_sex'])) !!};

        const statusLabels = {!! json_encode(array_keys($reportData['by_status'])) !!};
        const statusData = {!! json_encode(array_values($reportData['by_status'])) !!};

        const educationLabels = {!! json_encode(array_keys($reportData['by_education'])) !!};
        const educationData = {!! json_encode(array_values($reportData['by_education'])) !!};

        // Filter income data to exclude empty keys
        const incomeRaw = {!! json_encode($reportData['by_income']) !!};
        const incomeLabels = Object.keys(incomeRaw).filter(k => k !== '' && k.trim() !== '');
        const incomeData = incomeLabels.map(k => incomeRaw[k]);

        // Age Distribution Chart
        if (ageLabels.length) {
            const ageCtx = document.getElementById('ageChart').getContext('2d');
            new Chart(ageCtx, {
                type: 'bar',
                data: {
                    labels: ageLabels,
                    datasets: [{
                        label: 'Number of Youth',
                        data: ageData,
                        backgroundColor: getColors(ageLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Sex Distribution Chart
        if (sexLabels.length) {
            const sexCtx = document.getElementById('sexChart').getContext('2d');
            new Chart(sexCtx, {
                type: 'doughnut',
                data: {
                    labels: sexLabels,
                    datasets: [{
                        data: sexData,
                        backgroundColor: getColors(sexLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // Status Distribution Chart
        if (statusLabels.length) {
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: getColors(statusLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // Education Distribution Chart
        if (educationLabels.length) {
            const eduCtx = document.getElementById('educationChart').getContext('2d');
            new Chart(eduCtx, {
                type: 'bar',
                data: {
                    labels: educationLabels,
                    datasets: [{
                        label: 'Number of Youth',
                        data: educationData,
                        backgroundColor: getColors(educationLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    indexAxis: 'y',
                    scales: { x: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Income Distribution Chart
        if (incomeLabels.length) {
            const incomeCtx = document.getElementById('incomeChart').getContext('2d');
            new Chart(incomeCtx, {
                type: 'bar',
                data: {
                    labels: incomeLabels,
                    datasets: [{
                        label: 'Number of Youth',
                        data: incomeData,
                        backgroundColor: getColors(incomeLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    indexAxis: 'y',
                    scales: { x: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }
    </script>
@endpush
            type: 'bar',
            data: {
                labels: Object.keys(@json($reportData['by_age_group'])),
                datasets: [{
                    label: 'Number of Youth',
                    data: Object.values(@json($reportData['by_age_group'])),
                    backgroundColor: '#3B82F6',
                    borderColor: '#1E40AF',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Sex Distribution Chart
        const sexColors = ['#3B82F6', '#EC4899', '#8B5CF6', '#EC9323'];
        new Chart(document.getElementById('sexChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(@json($reportData['by_sex'])),
                datasets: [{
                    data: Object.values(@json($reportData['by_sex'])),
                    backgroundColor: sexColors.slice(0, Object.keys(@json($reportData['by_sex'])).length),
                    borderColor: ['#1E40AF', '#831843', '#6D28D9', '#D97706'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Status Distribution Chart
        const statusColors = ['#10B981', '#6B7280', '#F59E0B', '#EF4444'];
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(@json($reportData['by_status'])),
                datasets: [{
                    data: Object.values(@json($reportData['by_status'])),
                    backgroundColor: statusColors.slice(0, Object.keys(@json($reportData['by_status'])).length),
                    borderColor: ['#059669', '#4B5563', '#D97706', '#DC2626'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Education Status Chart
        new Chart(document.getElementById('educationChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(@json($reportData['by_education'])),
                datasets: [{
                    label: 'Number of Youth',
                    data: Object.values(@json($reportData['by_education'])),
                    backgroundColor: '#F97316',
                    borderColor: '#C2410C',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'x',
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Income Distribution Chart
        const incomeData = @json($reportData['by_income']);
        const incomeLabels = Object.keys(incomeData).filter(k => k !== '').map(k => k || 'Not Specified');
        const incomeValues = Object.entries(incomeData)
            .filter(([k]) => k !== '')
            .map(([, v]) => v);

        new Chart(document.getElementById('incomeChart'), {
            type: 'bar',
            data: {
                labels: incomeLabels.length > 0 ? incomeLabels : ['No Data'],
                datasets: [{
                    label: 'Number of Youth',
                    data: incomeValues.length > 0 ? incomeValues : [0],
                    backgroundColor: '#6366F1',
                    borderColor: '#4F46E5',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });
    </script>
@endpush
@endsection
@endsection
