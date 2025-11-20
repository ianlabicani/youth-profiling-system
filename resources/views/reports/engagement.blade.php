@extends('reports.shell')

@section('content')
@php
    $title = 'Youth Engagement & Events Report';
    $description = 'Track youth participation in events and programs';
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

    $stats = [
        [
            'label' => 'Total Events',
            'value' => $reportData['total_events'],
            'icon' => '🎯',
        ],
        [
            'label' => 'Total Participants',
            'value' => $reportData['active_participants'],
            'icon' => '👥',
        ],
        [
            'label' => 'Participation Rate',
            'value' => $reportData['participation_rate'] . '%',
            'icon' => '📊',
        ],
        [
            'label' => 'Avg per Event',
            'value' => $reportData['total_events'] > 0 ? round($reportData['active_participants'] / $reportData['total_events']) : 0,
            'icon' => '📈',
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
        <!-- Events by Barangay -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 border border-orange-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Events by Barangay</h3>
            <canvas id="barangayChart"></canvas>
        </div>

        <!-- Participation Timeline -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Participation Trend</h3>
            <canvas id="participationChart"></canvas>
        </div>
    </div>

    <!-- Events Details Table -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Events List</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-200 text-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Event Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Barangay</th>
                        <th class="px-4 py-3 text-left font-semibold">Date</th>
                        <th class="px-4 py-3 text-left font-semibold">Participants</th>
                        <th class="px-4 py-3 text-left font-semibold">Type</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events->take(20) as $event)
                        <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 font-medium">{{ $event->name }}</td>
                            <td class="px-4 py-3">{{ $event->barangay?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $event->event_date?->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $event->participants_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($event->event_date > now())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Upcoming
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Past
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-600">
                                No events found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->count() > 20)
            <p class="text-xs text-slate-600 mt-4">Showing 20 of {{ $events->count() }} records</p>
        @endif
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Events by Barangay Chart
        new Chart(document.getElementById('barangayChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(@json($reportData['events_by_barangay'])),
                datasets: [{
                    label: 'Number of Events',
                    data: Object.values(@json($reportData['events_by_barangay'])),
                    backgroundColor: '#F97316',
                    borderColor: '#C2410C',
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

        // Participation Trend Chart
        new Chart(document.getElementById('participationChart'), {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8'],
                datasets: [{
                    label: 'Participants',
                    data: [65, 78, 90, 81, 102, 115, 108, 125],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush
@endsection
