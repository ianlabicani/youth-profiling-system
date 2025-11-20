@extends('brgy.shell')

@section('title', 'Youth Leadership & Governance Report')
@section('brgy-content')
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
    <!-- AI Insights Section -->
    @if(isset($insights))
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-6 border border-indigo-200 mb-8">
            <div class="flex items-start gap-4">
                <div class="text-3xl">✨</div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Executive Insights</h2>
                    @php
                        $lines = array_filter(array_map('trim', explode("\n", $insights)));
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
                        <th class="px-4 py-3 text-left font-semibold">Barangay</th>
                        <th class="px-4 py-3 text-left font-semibold">Term</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Chairperson</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($councils->take(15) as $council)
                        <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 font-medium">{{ $council->barangay?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $council->term ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $council->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $council->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $council->chairperson?->first_name ?? 'N/A' }} {{ $council->chairperson?->last_name ?? '' }}</td>
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
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations->take(15) as $org)
                        <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 font-medium">{{ $org->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $org->type ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $org->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($org->status ?? 'Active') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-600">
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
@endsection
