@extends('reports.shell')

@section('content')
@php
    $title = 'Youth Leadership & Governance Report';
    $description = 'Review SK Councils, youth leaders, and organizations';
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
        ]
    ];

    $stats = [
        [
            'label' => 'Total SK Councils',
            'value' => $reportData['total_councils'],
            'icon' => '🏛️',
        ],
        [
            'label' => 'Active Councils',
            'value' => $reportData['active_councils'],
            'icon' => '✓',
            'subtitle' => round(($reportData['active_councils'] / max(1, $reportData['total_councils'])) * 100, 1) . '%'
        ],
        [
            'label' => 'Youth Leaders',
            'value' => $reportData['total_leaders'],
            'icon' => '👥',
        ],
        [
            'label' => 'Organizations',
            'value' => $reportData['organizations'],
            'icon' => '🏢',
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
        <!-- Council Status -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Council Status</h3>
            <canvas id="councilChart"></canvas>
        </div>

        <!-- Leadership Positions -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Leadership Positions</h3>
            <canvas id="positionsChart"></canvas>
        </div>
    </div>

    <!-- SK Councils Table -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">SK Councils</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-200 text-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Council Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Barangay</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Year Established</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($councils->take(15) as $council)
                        <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 font-medium">{{ $council->name }}</td>
                            <td class="px-4 py-3">{{ $council->barangay?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $council->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($council->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $council->year_established ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-600">
                                No SK Councils found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($councils->count() > 15)
            <p class="text-xs text-slate-600 mt-4">Showing 15 of {{ $councils->count() }} records</p>
        @endif
    </div>

    <!-- Organizations Table -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Youth Organizations</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-200 text-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Organization Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Type</th>
                        <th class="px-4 py-3 text-left font-semibold">Barangay</th>
                        <th class="px-4 py-3 text-left font-semibold">Members</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations->take(15) as $org)
                        <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 font-medium">{{ $org->name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $org->type ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $org->barangay?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $org->members_count ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-600">
                                No organizations found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($organizations->count() > 15)
            <p class="text-xs text-slate-600 mt-4">Showing 15 of {{ $organizations->count() }} records</p>
        @endif
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Council Status Chart
        new Chart(document.getElementById('councilChart'), {
            type: 'doughnut',
            data: {
                labels: ['Active Councils', 'Inactive Councils'],
                datasets: [{
                    data: [
                        {{ $reportData['active_councils'] }},
                        {{ $reportData['total_councils'] - $reportData['active_councils'] }}
                    ],
                    backgroundColor: ['#10B981', '#6B7280'],
                    borderColor: ['#059669', '#4B5563'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Leadership Positions Chart
        new Chart(document.getElementById('positionsChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(@json($reportData['positions_held'])),
                datasets: [{
                    label: 'Number of Leaders',
                    data: Object.values(@json($reportData['positions_held'])),
                    backgroundColor: '#3B82F6',
                    borderColor: '#1E40AF',
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
