@extends('brgy.shell')

@section('title', 'Youth Profiles Report')
@section('brgy-content')
@php
    $title = 'Youth Profiles Report';
    $description = 'Access individual youth profiles and aggregated data';
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
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'options' => ['active' => 'Active', 'archived' => 'Archived']
        ]
    ];

    $stats = [
        [
            'label' => 'Total Youth',
            'value' => $reportData['total_records'],
            'icon' => '👥',
        ],
        [
            'label' => 'Active Youth',
            'value' => $reportData['active_youth'],
            'icon' => '✓',
        ],
        [
            'label' => 'Average Age',
            'value' => $reportData['average_age'],
            'icon' => '📊',
        ],
        [
            'label' => 'With Contact',
            'value' => $reportData['contact_available'],
            'icon' => '📞',
            'subtitle' => round(($reportData['contact_available'] / max(1, $reportData['total_records'])) * 100, 1) . '%'
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

    <!-- Search Bar -->
    <div class="bg-white rounded-lg border border-slate-200 p-6 mb-8">
        <form method="GET" class="flex gap-3">
            <input
                type="text"
                name="search"
                placeholder="Search by name or contact number..."
                value="{{ $searchTerm }}"
                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                Search
            </button>
        </form>
    </div>

    <!-- Youth Profiles Table -->
    <div class="bg-slate-50 rounded-lg border border-slate-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Youth Records</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-200 text-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Age</th>
                        <th class="px-4 py-3 text-left font-semibold">Contact</th>
                        <th class="px-4 py-3 text-left font-semibold">Barangay</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($youth as $y)
                        <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 font-medium">{{ $y->first_name }} {{ $y->last_name }}</td>
                            <td class="px-4 py-3">
                                @if($y->date_of_birth)
                                    {{ $y->date_of_birth->diffInYears(now()) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($y->contact_number)
                                    <a href="tel:{{ $y->contact_number }}" class="text-blue-600 hover:underline">
                                        {{ $y->contact_number }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $y->barangay?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $y->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($y->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="#" class="text-blue-600 hover:underline text-xs font-medium">
                                    View
                                </a>
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
    </div>

    <!-- Pagination -->
    @if($youth->hasPages())
        <div class="flex justify-center mb-8">
            {{ $youth->links() }}
        </div>
    @endif

    <!-- Export Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Export Records</h3>
                <p class="text-sm text-slate-600">Download youth profiles in your preferred format</p>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition">
                    📄 PDF
                </button>
                <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition">
                    📊 Excel
                </button>
                <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition">
                    📋 CSV
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
