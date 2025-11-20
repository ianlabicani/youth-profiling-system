@extends('municipal.shell')

@section('title', 'Municipal Dashboard')
@section('municipal-content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Municipal Dashboard</h1>
            <p class="text-gray-600 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                Overview and analytics across all barangays
            </p>
        </div>

        <!-- KPI cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
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

            <!-- Barangays Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 h-1"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-indigo-100 p-3 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zM9 7h.01M9 11h.01M9 15h.01M12 7h.01M12 11h.01M12 15h.01M15 7h.01M15 11h.01M15 15h.01"/></svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Barangays</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalBarangays }}</p>
                    @if(isset($descriptions['total_barangays']))
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $descriptions['total_barangays'] }}</p>
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

            <!-- Organizations Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-br from-green-500 to-green-600 h-1"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-green-100 p-3 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Organizations</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalOrganizations }}</p>
                    @if(isset($descriptions['total_organizations']))
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $descriptions['total_organizations'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Out of School Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 h-1"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-orange-100 p-3 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Out of School</p>
                    <p class="text-3xl font-bold text-orange-600">{{ number_format($outOfSchoolCount) }}</p>
                    @if(isset($descriptions['out_of_school']))
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $descriptions['out_of_school'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
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
                        Upcoming Events (Next 30 Days)
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
                                    @if($ev->barangay)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ $ev->barangay->name }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-2">📍 {{ \Illuminate\Support\Str::limit($ev->venue, 60) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-gray-500 font-medium">No upcoming events in the next 30 days</p>
                    </div>
                @endif
                </div>
            </div>

            <!-- Education distribution -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.75 2 10.5 2 15.5S6.5 24 12 24s10-3.75 10-8.5S17.5 6.75 12 6.253z"/></svg>
                        Educational Attainment (Top 8)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <canvas id="educationChart" height="110"></canvas>
                    </div>
                    @if(isset($descriptions['education']))
                        <div class="bg-purple-50 border-l-4 border-purple-500 p-3 rounded-r mb-4">
                            <p class="text-xs text-gray-700">{{ $descriptions['education'] }}</p>
                        </div>
                    @endif
                    @if(isset($education) && $education->count())
                        <div class="space-y-2">
                            @foreach($education->take(8) as $ed)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm font-medium text-gray-700">{{ $ed->educational_attainment ?: 'Out of School' }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">{{ number_format($ed->total) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 font-medium">No education data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Youth positions summary -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Youth Positions in SK Councils
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if(isset($descriptions['council_positions']))
                            <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded-r mb-4">
                                <p class="text-xs text-gray-700">{{ $descriptions['council_positions'] }}</p>
                            </div>
                        @endif
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <span class="text-sm font-medium text-gray-700">Distinct positions held</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">{{ number_format($distinctPositionsCount) }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <p class="text-xs text-gray-600 mb-1">Chairpersons</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $chairCount }}</p>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-lg border border-purple-100">
                                <p class="text-xs text-gray-600 mb-1">Secretaries</p>
                                <p class="text-2xl font-bold text-purple-600">{{ $secretaryCount }}</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-lg border border-orange-100">
                                <p class="text-xs text-gray-600 mb-1">Treasurers</p>
                                <p class="text-2xl font-bold text-orange-600">{{ $treasurerCount }}</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                                <p class="text-xs text-gray-600 mb-1">Total Members</p>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($kagawadTotal) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Youth by Barangay -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zM9 7h.01M9 11h.01M9 15h.01M12 7h.01M12 11h.01M12 15h.01M15 7h.01M15 11h.01M15 15h.01"/></svg>
                        Youth by Barangay (Top 10)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <canvas id="barangayChart" height="110"></canvas>
                    </div>
                    @if(isset($descriptions['youth_by_barangay']))
                        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-3 rounded-r mb-4">
                            <p class="text-xs text-gray-700">{{ $descriptions['youth_by_barangay'] }}</p>
                        </div>
                    @endif
                    @if($youthByBarangay->count())
                        <div class="space-y-2">
                            @foreach($youthByBarangay as $item)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm font-medium text-gray-700">{{ $item['barangay'] }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">{{ number_format($item['total']) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent youths -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Recent Youth Registrations
                    </h3>
                </div>
                <div class="p-6">
                @if($recentYouth->count())
                    <ul class="space-y-3">
                        @foreach($recentYouth as $y)
                            <li class="p-3 bg-gradient-to-r from-blue-50 to-transparent border-l-4 border-blue-500 rounded-r hover:shadow-md transition-shadow">
                                <p class="font-semibold text-gray-900">{{ $y->full_name }}</p>
                                <div class="text-sm text-gray-600 mt-1 space-y-1">
                                    <p><span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zM9 7h.01M9 11h.01M9 15h.01M12 7h.01M12 11h.01M12 15h.01M15 7h.01M15 11h.01M15 15h.01"/></svg>{{ $y->barangay?->name ?? 'Unknown' }}</span></p>
                                    @if($y->purok)
                                        <p><span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4l4 4m0 0l4-4m-4 4V8"/></svg>{{ $y->purok }}</span></p>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-2">{{ $y->created_at->diffForHumans() }}</p>
                            </li>
                                <p class="text-xs text-gray-500 mt-2">{{ $y->created_at->diffForHumans() }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <p class="text-gray-500 font-medium">No recent registrations</p>
                    </div>
                @endif
                </div>
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
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r mb-4">
                            <p class="text-xs text-gray-700">{{ $descriptions['youth_by_sex'] }}</p>
                        </div>
                    @endif
                    @if(count($youthBySex))
                        <div class="space-y-2">
                        @foreach($youthBySex as $sex => $count)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $sex ?: 'Unknown' }}</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">{{ number_format($count) }}</span>
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
                        <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded-r mb-4">
                            <p class="text-xs text-gray-700">{{ $descriptions['youth_by_status'] }}</p>
                        </div>
                    @endif
                    @if(count($youthByStatus))
                        <div class="space-y-2">
                        @foreach($youthByStatus as $status => $count)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $status ?: 'Unknown' }}</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">{{ number_format($count) }}</span>
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
                        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-3 rounded-r mb-4">
                            <p class="text-xs text-gray-700">{{ $descriptions['age_distribution'] }}</p>
                        </div>
                    @endif
                    @if(isset($ageBuckets) && count($ageBuckets))
                        @foreach($ageBuckets as $bucket => $val)
                            <div class="flex justify-between py-1"><span>{{ $bucket }}</span><span class="font-semibold">{{ $val }}</span></div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No age data.</p>
                    @endif
                </div>
            </div>

            <!-- Household Income Ranges -->
            <div class="bg-white p-4 rounded-lg shadow group hover:shadow-lg transition-shadow cursor-help">
                <h3 class="text-lg font-semibold text-gray-800">Household Income</h3>
                <div class="mt-3 text-sm">
                    <div class="mb-3">
                        <canvas id="incomeChart" height="140"></canvas>
                    </div>
                    @if(isset($descriptions['household_income']))
                        <p class="text-xs text-gray-600 mb-3 italic border-b pb-2">{{ $descriptions['household_income'] }}</p>
                    @endif
                    @if(isset($incomeRanges) && count($incomeRanges))
                        @foreach($incomeRanges as $range => $val)
                            <div class="flex justify-between py-1"><span>{{ $range }}</span><span class="font-semibold">{{ number_format($val) }}</span></div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No income data.</p>
                    @endif
                </div>
            </div>

            <!-- Event summary -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Event Summary</h3>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between"><span>Upcoming (30d)</span><span class="font-semibold">{{ $upcomingEvents }}</span></div>
                    <div class="flex justify-between"><span>This Year</span><span class="font-semibold">{{ $eventsThisYear }}</span></div>
                </div>
            </div>

        </div>
    </div>

    <!-- Councils table -->
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">SK Councils Overview</h3>
        @if($councils->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-600 border-b">
                        <tr>
                            <th class="p-2">Barangay</th>
                            <th class="p-2">Term</th>
                            <th class="p-2">Active</th>
                            <th class="p-2">Members (distinct)</th>
                            <th class="p-2">Events</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($councils as $c)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2 font-medium">{{ $c->barangay?->name ?? 'Unknown' }}</td>
                                <td class="p-2">{{ $c->term }}</td>
                                <td class="p-2">@if($c->is_active) <span class="text-green-600 font-semibold">Yes</span> @else <span class="text-gray-500">No</span> @endif</td>
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
            foreach($education->take(8) as $e){
                $eduLabels[] = $e->educational_attainment ?: 'Unknown/Out of School';
                $eduData[] = $e->total;
            }
        }

        $barangayLabels = array_map(function($item) { return $item['barangay']; }, $youthByBarangay->toArray());
        $barangayData = array_map(function($item) { return $item['total']; }, $youthByBarangay->toArray());

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

    const barangayLabels = {!! json_encode($barangayLabels) !!};
    const barangayData = {!! json_encode($barangayData) !!};

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

    // Education bar (top 8)
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

    // Barangay bar (top 10)
    if(barangayLabels.length){
        const bCtx = document.getElementById('barangayChart').getContext('2d');
        new Chart(bCtx, {
            type: 'bar',
            data: { labels: barangayLabels, datasets: [{ label: 'Count', data: barangayData, backgroundColor: getColors(barangayLabels.length) }] },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
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
</script>
@endsection
