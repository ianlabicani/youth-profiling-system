@extends('municipal.shell')

@section('title', 'Municipal Reports')
@section('municipal-content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <!-- Header -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Reports & Analytics</h1>
            <p class="text-slate-600 text-lg">Generate comprehensive youth profiling reports with AI-powered insights</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-lg border border-slate-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-2">Total Youth</p>
                        <p class="text-4xl font-bold text-blue-600">{{ $stats['demographics']['total_youth'] }}</p>
                    </div>
                    <span class="text-3xl">👥</span>
                </div>
                <p class="text-xs text-slate-500">{{ $stats['demographics']['active'] }} active</p>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-2">SK Councils</p>
                        <p class="text-4xl font-bold text-purple-600">{{ $stats['leadership']['councils'] }}</p>
                    </div>
                    <span class="text-3xl">🏛️</span>
                </div>
                <p class="text-xs text-slate-500">{{ $stats['leadership']['active_councils'] }} active</p>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-2">Events</p>
                        <p class="text-4xl font-bold text-orange-600">{{ $stats['engagement']['total_events'] }}</p>
                    </div>
                    <span class="text-3xl">🎯</span>
                </div>
                <p class="text-xs text-slate-500">{{ $stats['engagement']['upcoming'] }} upcoming</p>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-2">Data Completeness</p>
                        <p class="text-4xl font-bold text-green-600">{{ round(($stats['profiles']['complete'] / max(1, $stats['profiles']['total'])) * 100, 1) }}%</p>
                    </div>
                    <span class="text-3xl">✓</span>
                </div>
                <p class="text-xs text-slate-500">{{ $stats['profiles']['total'] }} records</p>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Demographics Report -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden hover:shadow-xl transition group">
                <div class="h-2 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">📊</span>
                        <h2 class="text-2xl font-bold text-slate-900">Demographics</h2>
                    </div>
                    <p class="text-slate-600 mb-6">Analyze youth population by age, education, income, and status. Filter by barangay and date range to focus on specific demographics.</p>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Age group distribution
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Educational status breakdown
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Income distribution analysis
                        </div>
                    </div>

                    <a href="{{ route('municipal.reports.demographics') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition group-hover:translate-x-1">
                        View Report →
                    </a>
                </div>
            </div>

            <!-- Leadership Report -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden hover:shadow-xl transition group">
                <div class="h-2 bg-gradient-to-r from-purple-400 to-purple-600"></div>
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">👥</span>
                        <h2 class="text-2xl font-bold text-slate-900">Leadership</h2>
                    </div>
                    <p class="text-slate-600 mb-6">Review SK Councils, youth leaders, and organizations. Track leadership representation and governance structure.</p>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            SK Council overview
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Leadership positions analysis
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Organization tracking
                        </div>
                    </div>

                    <a href="{{ route('municipal.reports.leadership') }}" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium transition group-hover:translate-x-1">
                        View Report →
                    </a>
                </div>
            </div>



            <!-- Youth Profiles Report -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden hover:shadow-xl transition group">
                <div class="h-2 bg-gradient-to-r from-green-400 to-green-600"></div>
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">👤</span>
                        <h2 class="text-2xl font-bold text-slate-900">Profiles</h2>
                    </div>
                    <p class="text-slate-600 mb-6">Access individual youth profiles and aggregated data. Search and filter by barangay, status, or demographics.</p>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Individual profile access
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Advanced search and filtering
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"/></path></svg>
                            Batch export options
                        </div>
                    </div>

                    <a href="{{ route('municipal.reports.profiles') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition group-hover:translate-x-1">
                        View Report →
                    </a>
                </div>
            </div>

        </div>

        <!-- Report Features Section -->
        <div class="mt-12 bg-white rounded-lg border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-8">Report Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Advanced Analytics</h3>
                    <p class="text-sm text-slate-600">Deep dive into trends, patterns, and key metrics</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 text-purple-600 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">AI Insights</h3>
                    <p class="text-sm text-slate-600">Automatic AI-powered analysis and recommendations</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Export Options</h3>
                    <p class="text-sm text-slate-600">Download as PDF, Excel, CSV, or print directly</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 text-orange-600 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Customizable</h3>
                    <p class="text-sm text-slate-600">Filter, sort, and customize reports to your needs</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
