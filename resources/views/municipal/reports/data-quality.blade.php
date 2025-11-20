@extends('municipal.shell')

@section('title', 'Data Quality & Completeness Report')
@section('municipal-content')
@php
    $title = 'Data Quality & Completeness Report';
    $description = 'Monitor data completeness and accuracy across the system';
    $showFilters = false;
    $exportButton = false;

    $stats = [
        [
            'label' => 'Total Records',
            'value' => $reportData['total_records'],
            'icon' => '📊',
        ],
        [
            'label' => 'Complete Records',
            'value' => $reportData['complete_records'],
            'icon' => '✓',
            'subtitle' => round(($reportData['complete_records'] / max(1, $reportData['total_records'])) * 100, 1) . '%'
        ],
        [
            'label' => 'Missing Contacts',
            'value' => $reportData['missing_contacts'],
            'icon' => '⚠️',
            'subtitle' => round(($reportData['missing_contacts'] / max(1, $reportData['total_records'])) * 100, 1) . '%'
        ],
        [
            'label' => 'Data Quality Score',
            'value' => $reportData['accuracy_score'] . '%',
            'icon' => '🎯',
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

    <!-- Quality Score Visualization -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Overall Quality Score -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Overall Quality Score</h3>
            <canvas id="qualityChart"></canvas>
            <p class="text-center text-sm text-slate-600 mt-4">
                @if($reportData['accuracy_score'] >= 80)
                    <span class="text-green-600 font-medium">✓ Excellent data quality</span>
                @elseif($reportData['accuracy_score'] >= 60)
                    <span class="text-blue-600 font-medium">△ Good quality with room for improvement</span>
                @elseif($reportData['accuracy_score'] >= 40)
                    <span class="text-orange-600 font-medium">⚠ Moderate quality - action recommended</span>
                @else
                    <span class="text-red-600 font-medium">✗ Poor quality - urgent improvements needed</span>
                @endif
            </p>
        </div>

        <!-- Data Completeness -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Data Completeness</h3>
            <canvas id="completenessChart"></canvas>
        </div>
    </div>

    <!-- Issues & Recommendations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Critical Issues -->
        <div class="bg-red-50 rounded-lg border border-red-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <span class="text-xl">⚠️</span> Issues Requiring Attention
            </h3>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600 font-semibold flex-shrink-0 mt-0.5">1</span>
                    <div>
                        <p class="font-medium text-slate-900">Missing Contact Information</p>
                        <p class="text-sm text-slate-600">{{ $reportData['missing_contacts'] }} records have no contact number</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600 font-semibold flex-shrink-0 mt-0.5">2</span>
                    <div>
                        <p class="font-medium text-slate-900">Incomplete Profiles</p>
                        <p class="text-sm text-slate-600">{{ $reportData['incomplete_profiles'] }} records are missing required fields</p>
                    </div>
                </li>
                @if($reportData['accuracy_score'] < 70)
                <li class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600 font-semibold flex-shrink-0 mt-0.5">3</span>
                    <div>
                        <p class="font-medium text-slate-900">Low Data Quality Score</p>
                        <p class="text-sm text-slate-600">Overall quality is {{ $reportData['accuracy_score'] }}% - priority improvements needed</p>
                    </div>
                </li>
                @endif
            </ul>
        </div>

        <!-- Recommendations -->
        <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <span class="text-xl">💡</span> Recommendations
            </h3>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold flex-shrink-0 mt-0.5">1</span>
                    <div>
                        <p class="font-medium text-slate-900">Complete Missing Data</p>
                        <p class="text-sm text-slate-600">Prioritize updating records with missing contact information</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold flex-shrink-0 mt-0.5">2</span>
                    <div>
                        <p class="font-medium text-slate-900">Regular Audits</p>
                        <p class="text-sm text-slate-600">Schedule monthly data quality checks to maintain standards</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold flex-shrink-0 mt-0.5">3</span>
                    <div>
                        <p class="font-medium text-slate-900">Data Entry Guidelines</p>
                        <p class="text-sm text-slate-600">Enforce required fields during data entry to prevent gaps</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Detailed Metrics Table -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Detailed Metrics</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-200 text-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Metric</th>
                        <th class="px-4 py-3 text-left font-semibold">Count</th>
                        <th class="px-4 py-3 text-left font-semibold">Percentage</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                        <td class="px-4 py-3 font-medium">Total Records</td>
                        <td class="px-4 py-3">{{ $reportData['total_records'] }}</td>
                        <td class="px-4 py-3">100%</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Base
                            </span>
                        </td>
                    </tr>
                    <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                        <td class="px-4 py-3 font-medium">Complete Records</td>
                        <td class="px-4 py-3">{{ $reportData['complete_records'] }}</td>
                        <td class="px-4 py-3">{{ round(($reportData['complete_records'] / max(1, $reportData['total_records'])) * 100, 1) }}%</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ round(($reportData['complete_records'] / max(1, $reportData['total_records'])) * 100, 1) >= 80 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ round(($reportData['complete_records'] / max(1, $reportData['total_records'])) * 100, 1) >= 80 ? '✓ Good' : '△ Needs Work' }}
                            </span>
                        </td>
                    </tr>
                    <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                        <td class="px-4 py-3 font-medium">Records with Contact Info</td>
                        <td class="px-4 py-3">{{ $reportData['total_records'] - $reportData['missing_contacts'] }}</td>
                        <td class="px-4 py-3">{{ round((($reportData['total_records'] - $reportData['missing_contacts']) / max(1, $reportData['total_records'])) * 100, 1) }}%</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reportData['missing_contacts'] < ($reportData['total_records'] * 0.2) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $reportData['missing_contacts'] < ($reportData['total_records'] * 0.2) ? '✓ OK' : '✗ Alert' }}
                            </span>
                        </td>
                    </tr>
                    <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                        <td class="px-4 py-3 font-medium">Incomplete Records</td>
                        <td class="px-4 py-3">{{ $reportData['incomplete_profiles'] }}</td>
                        <td class="px-4 py-3">{{ round(($reportData['incomplete_profiles'] / max(1, $reportData['total_records'])) * 100, 1) }}%</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                △ Needs Attention
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Items -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Next Steps</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></path></svg>
                    </div>
                </div>
                <div>
                    <p class="font-medium text-slate-900">Import Missing Data</p>
                    <p class="text-xs text-slate-600 mt-1">Use the import tool to add missing contact numbers</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></path></svg>
                    </div>
                </div>
                <div>
                    <p class="font-medium text-slate-900">Schedule Data Review</p>
                    <p class="text-xs text-slate-600 mt-1">Set a recurring audit to verify data completeness</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></path></svg>
                    </div>
                </div>
                <div>
                    <p class="font-medium text-slate-900">Train Data Entry Team</p>
                    <p class="text-xs text-slate-600 mt-1">Provide guidelines on required fields during entry</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Quality Score Gauge Chart
        new Chart(document.getElementById('qualityChart'), {
            type: 'doughnut',
            data: {
                labels: ['Good Quality', 'Needs Improvement'],
                datasets: [{
                    data: [{{ $reportData['accuracy_score'] }}, {{ 100 - $reportData['accuracy_score'] }}],
                    backgroundColor: [
                        '{{ $reportData['accuracy_score'] >= 80 ? "#10B981" : ($reportData['accuracy_score'] >= 60 ? "#3B82F6" : ($reportData['accuracy_score'] >= 40 ? "#F97316" : "#EF4444")) }}',
                        '#E5E7EB'
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            }
        });

        // Completeness Chart
        new Chart(document.getElementById('completenessChart'), {
            type: 'bar',
            data: {
                labels: ['Complete', 'Incomplete', 'Missing Contact'],
                datasets: [{
                    label: 'Records',
                    data: [
                        {{ $reportData['complete_records'] }},
                        {{ $reportData['incomplete_profiles'] }},
                        {{ $reportData['missing_contacts'] }}
                    ],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderColor: ['#059669', '#D97706', '#DC2626'],
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
    </script>
@endpush
@endsection
@endsection
