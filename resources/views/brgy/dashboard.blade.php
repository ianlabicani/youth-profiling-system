@extends('brgy.shell')

@section('brgy-content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $userBarangay->name }} Dashboard</h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        Youth Profiling System Analytics
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="exportDashboard('pdf')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l-6-2m6 2l6-2"/></svg>
                        Export PDF
                    </button>
                    <button onclick="exportDashboard('excel')" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- KPI cards with icons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Youth Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 h-1"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-blue-100 p-3 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 16a5 5 0 01-10 0"/></svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Youth</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalYouth) }}</p>
                    @if(isset($descriptions['total_youth']))
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $descriptions['total_youth'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active SK Councils Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 h-1"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-purple-100 p-3 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active SK Councils</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeCouncils }}</p>
                    @if(isset($descriptions['active_councils']))
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $descriptions['active_councils'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Events Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 h-1"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-orange-100 p-3 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Upcoming Events (30d)</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $upcomingEvents }}</p>
                    @if(isset($descriptions['upcoming_events']))
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $descriptions['upcoming_events'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Events This Year Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-br from-green-500 to-green-600 h-1"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-green-100 p-3 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Events This Year</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $eventsThisYear }}</p>
                    @if(isset($descriptions['events_this_year']))
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $descriptions['events_this_year'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Registrations chart (server-side data) -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        New Registrations
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Last 12 months activity</p>
                </div>
                <div class="p-6">
                    <canvas id="registrationsChart" height="100"></canvas>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Upcoming Events
                    </h3>
                </div>
                <div class="p-6">
                    @if($upcomingList->count())
                        <ul class="space-y-3">
                            @foreach($upcomingList as $ev)
                                <li class="p-4 bg-gradient-to-r from-orange-50 to-transparent border-l-4 border-orange-500 rounded-r-lg hover:shadow-md transition-shadow">
                                    <p class="font-semibold text-gray-900">{{ $ev->title }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-sm text-gray-600">
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $ev->date->format('M d, Y') }}
                                            </span>
                                        </p>
                                        @if($ev->skCouncil)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">{{ $ev->skCouncil->term }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">📍 {{ \Illuminate\Support\Str::limit($ev->venue, 60) }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-gray-500 font-medium">No upcoming events</p>
                        </div>
                    @endif
                </div>
            </div>

             <!-- Education distribution -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.75 2 10.5 2 15.5S6.5 24 12 24s10-3.75 10-8.5S17.5 6.75 12 6.253z"/></svg>
                        Education Levels
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <canvas id="educationChart" height="110"></canvas>
                    </div>
                    @if(isset($descriptions['education']))
                        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r mb-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $descriptions['education'] }}</p>
                        </div>
                    @endif
                    @if(isset($education) && $education->count())
                        <ul class="space-y-2">
                            @foreach($education as $ed)
                                <li class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm text-gray-700">{{ $ed->educational_attainment ?: 'Unknown' }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">{{ $ed->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-center py-4">No education data available</p>
                    @endif
                </div>
            </div>

             <!-- Youth positions summary -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Youth Positions in Councils
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if(isset($descriptions['council_positions']))
                        <p class="text-xs text-gray-600 mb-3 italic border-b pb-2">{{ $descriptions['council_positions'] }}</p>
                    @endif
                    <div class="flex justify-between"><span>Distinct youths holding any council position</span><span class="font-semibold">{{ $distinctPositionsCount }}</span></div>
                    <div class="flex justify-between"><span>Chairpersons</span><span class="font-semibold">{{ $chairCount }}</span></div>
                    <div class="flex justify-between"><span>Secretaries</span><span class="font-semibold">{{ $secretaryCount }}</span></div>
                    <div class="flex justify-between"><span>Treasurers</span><span class="font-semibold">{{ $treasurerCount }}</span></div>
                    <div class="flex justify-between"><span>Total kagawad (members) entries</span><span class="font-semibold">{{ $kagawadTotal }}</span></div>
                </div>
            </div>

            <!-- Recent youths -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Recent Youth Registrations</h3>
                @if($recentYouth->count())
                    <ul class="mt-3 space-y-2 text-sm">
                        @foreach($recentYouth as $y)
                            <li class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium">{{ $y->first_name }} {{ $y->last_name }}</div>
                                    <div class="text-gray-500">{{ $y->purok ?? '' }}</div>
                                </div>
                                <div class="text-gray-500 text-sm">{{ $y->created_at->diffForHumans() }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 mt-3">No recent registrations.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <!-- Youth by sex -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 16a5 5 0 01-10 0"/></svg>
                        Youth by Sex
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <canvas id="sexChart" height="100"></canvas>
                    </div>
                    @if(isset($descriptions['youth_by_sex']))
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r mb-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $descriptions['youth_by_sex'] }}</p>
                        </div>
                    @endif
                    @if(count($youthBySex))
                        <div class="space-y-2">
                            @foreach($youthBySex as $sex => $count)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm font-medium text-gray-700 capitalize">{{ $sex ?: 'Unknown' }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No data available</p>
                    @endif
                </div>
            </div>

            <!-- Youth by status -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Youth by Status
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <canvas id="statusChart" height="100"></canvas>
                    </div>
                    @if(isset($descriptions['youth_by_status']))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r mb-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $descriptions['youth_by_status'] }}</p>
                        </div>
                    @endif
                    @if(count($youthByStatus))
                        <div class="space-y-2">
                            @foreach($youthByStatus as $status => $count)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm font-medium text-gray-700 capitalize">{{ $status ?: 'Unknown' }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No data available</p>
                    @endif
                </div>
            </div>

            <!-- Age buckets -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Age Distribution
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <canvas id="ageBucketsChart" height="100"></canvas>
                    </div>
                    @if(isset($descriptions['age_distribution']))
                        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r mb-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $descriptions['age_distribution'] }}</p>
                        </div>
                    @endif
                    @if(isset($ageBuckets) && count($ageBuckets))
                        <div class="space-y-2">
                            @foreach($ageBuckets as $bucket => $val)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm font-medium text-gray-700">{{ $bucket }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">{{ $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No age data available</p>
                    @endif
                </div>
            </div>

            <!-- Household Income Ranges -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Household Income
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <canvas id="incomeChart" height="110"></canvas>
                    </div>
                    @if(isset($descriptions['household_income']))
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r mb-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $descriptions['household_income'] }}</p>
                        </div>
                    @endif
                    @if(isset($incomeRanges) && count($incomeRanges))
                        <div class="space-y-2">
                            @foreach($incomeRanges as $range => $val)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm font-medium text-gray-700">{{ $range }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">{{ $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No income data available</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Councils table -->
    <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
        <div class="border-b border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                SK Councils
            </h3>
        </div>
        <div class="p-6">
        @if($councils->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="p-2">Term</th>
                            <th class="p-2">Active</th>
                            <th class="p-2">Members (distinct)</th>
                            <th class="p-2">Events</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($councils as $c)
                            <tr class="border-t">
                                <td class="p-2 font-medium">{{ $c->term }}</td>
                                <td class="p-2">@if($c->is_active) <span class="text-green-600">Yes</span> @else <span class="text-gray-500">No</span> @endif</td>
                                <td class="p-2">{{ count(array_filter(array_merge([$c->chairperson_id, $c->secretary_id, $c->treasurer_id], is_array($c->kagawad_ids) ? $c->kagawad_ids : []))) }}</td>
                                <td class="p-2">{{ $c->events_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No councils found.</p>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = {!! json_encode($months) !!};
    const regs = {!! json_encode($dataRegs) !!};

    const ctx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Registrations',
                data: regs,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.08)',
                fill: true,
                tension: 0.2,
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Prepare data for additional charts
    @php
        $sexLabels = array_values(array_map(function($k){ return $k === null ? 'Unknown' : $k; }, array_keys($youthBySex ?? [])));
        $sexData = array_values($youthBySex ?? []);

        $statusLabels = array_values(array_map(function($k){ return $k === null ? 'Unknown' : $k; }, array_keys($youthByStatus ?? [])));
        $statusData = array_values($youthByStatus ?? []);

        $ageLabels = array_keys($ageBuckets ?? []);
        $ageData = array_values($ageBuckets ?? []);

        $eduLabels = [];
        $eduData = [];
        if(isset($education)){
            foreach($education as $e){
                $eduLabels[] = $e->educational_attainment ?: 'Unknown';
                $eduData[] = $e->total;
            }
        }

        $incomeLabels = array_keys($incomeRanges ?? []);
        $incomeData = array_values($incomeRanges ?? []);
    @endphp

    const sexLabels = {!! json_encode($sexLabels) !!};
    const sexData = {!! json_encode($sexData) !!};

    const statusLabels = {!! json_encode($statusLabels) !!};
    const statusData = {!! json_encode($statusData) !!};

    const ageLabels = {!! json_encode($ageLabels) !!};
    const ageData = {!! json_encode($ageData) !!};

    const eduLabels = {!! json_encode($eduLabels) !!};
    const eduData = {!! json_encode($eduData) !!};

    const incomeLabels = {!! json_encode($incomeLabels) !!};
    const incomeData = {!! json_encode($incomeData) !!};

    const colorPalette = ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316','#84cc16'];

    function getColors(n){
        const out = [];
        for(let i=0;i<n;i++) out.push(colorPalette[i % colorPalette.length]);
        return out;
    }

    // Sex doughnut
    if(sexLabels.length){
        const sCtx = document.getElementById('sexChart').getContext('2d');
        new Chart(sCtx, {
            type: 'doughnut',
            data: { labels: sexLabels, datasets: [{ data: sexData, backgroundColor: getColors(sexLabels.length) }] },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Status doughnut
    if(statusLabels.length){
        const stCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(stCtx, {
            type: 'doughnut',
            data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: getColors(statusLabels.length) }] },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Education bar (top N)
    if(eduLabels.length){
        const edCtx = document.getElementById('educationChart').getContext('2d');
        new Chart(edCtx, {
            type: 'bar',
            data: { labels: eduLabels, datasets: [{ label: 'Count', data: eduData, backgroundColor: getColors(eduLabels.length) }] },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }

    // Age buckets bar
    if(ageLabels.length){
        const ageCtx = document.getElementById('ageBucketsChart').getContext('2d');
        new Chart(ageCtx, {
            type: 'bar',
            data: { labels: ageLabels, datasets: [{ label: 'Count', data: ageData, backgroundColor: getColors(ageLabels.length) }] },
            options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }

    // Income ranges bar
    if(incomeLabels.length){
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'bar',
            data: { labels: incomeLabels, datasets: [{ label: 'Count', data: incomeData, backgroundColor: getColors(incomeLabels.length) }] },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }

    // Export function
    function exportDashboard(format) {
        const url = `{{ route('brgy.dashboard.export') }}?format=${format}`;
        window.location.href = url;
    }
</script>
@endsection
