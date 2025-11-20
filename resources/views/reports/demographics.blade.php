@extends('reports.shell')

@section('content')
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
        </div>

        <!-- Sex Distribution -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">By Sex</h3>
            <canvas id="sexChart"></canvas>
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
        </div>
    </div>

    <!-- Income Distribution -->
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-6 border border-indigo-200 mb-8">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Household Income Distribution</h3>
        <canvas id="incomeChart"></canvas>
    </div>

    <!-- Details Table -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Youth Records</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-200 text-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Age</th>
                        <th class="px-4 py-3 text-left font-semibold">Sex</th>
                        <th class="px-4 py-3 text-left font-semibold">Barangay</th>
                        <th class="px-4 py-3 text-left font-semibold">Education</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($youth->take(20) as $y)
                        <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                            <td class="px-4 py-3">{{ $y->first_name }} {{ $y->last_name }}</td>
                            <td class="px-4 py-3">
                                @if($y->date_of_birth)
                                    {{ $y->date_of_birth->diffInYears(now()) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $y->sex === 'Male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $y->sex }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $y->barangay?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ $y->educational_status ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $y->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($y->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-600">
                                No youth records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($youth->count() > 20)
            <p class="text-xs text-slate-600 mt-4">Showing 20 of {{ $youth->count() }} records</p>
        @endif
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Age Distribution Chart
        new Chart(document.getElementById('ageChart'), {
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
        new Chart(document.getElementById('sexChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(@json($reportData['by_sex'])),
                datasets: [{
                    data: Object.values(@json($reportData['by_sex'])),
                    backgroundColor: ['#3B82F6', '#EC4899'],
                    borderColor: ['#1E40AF', '#831843'],
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
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(@json($reportData['by_status'])),
                datasets: [{
                    data: Object.values(@json($reportData['by_status'])),
                    backgroundColor: ['#10B981', '#6B7280', '#F59E0B'],
                    borderColor: ['#059669', '#4B5563', '#D97706'],
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
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Income Distribution Chart
        new Chart(document.getElementById('incomeChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(@json($reportData['by_income'])),
                datasets: [{
                    label: 'Number of Youth',
                    data: Object.values(@json($reportData['by_income'])),
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
