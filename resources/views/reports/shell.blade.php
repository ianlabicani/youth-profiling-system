@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <!-- Header -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $title ?? 'Reports' }}</h1>
                    <p class="text-slate-600 mt-2">{{ $description ?? 'Generate and analyze youth profiling data' }}</p>
                </div>
                <div class="flex gap-3">
                    @if(isset($exportButton) && $exportButton)
                        <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition">
                            📥 Export
                        </button>
                    @endif
                    <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-medium transition">
                        ← Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Filters (if applicable) -->
        @if(isset($showFilters) && $showFilters)
            <div class="bg-white rounded-lg border border-slate-200 p-6 mb-8">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Filters</h2>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($filters ?? [] as $filter)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ $filter['label'] }}</label>
                            @if($filter['type'] === 'select')
                                <select name="{{ $filter['name'] }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">All</option>
                                    @foreach($filter['options'] ?? [] as $value => $label)
                                        <option value="{{ $value }}" {{ request()->get($filter['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @elseif($filter['type'] === 'date')
                                <input type="date" name="{{ $filter['name'] }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ request()->get($filter['name']) }}">
                            @endif
                        </div>
                    @endforeach
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- AI Insights Card -->
        @if(isset($insights))
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-600 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">AI Insights</h3>
                        <div class="text-slate-700 leading-relaxed whitespace-pre-wrap">
                            {{ $insights }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Statistics -->
        @if(isset($stats))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                @foreach($stats as $stat)
                    <div class="bg-white rounded-lg border border-slate-200 p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-slate-600">{{ $stat['label'] }}</h3>
                            <span class="text-2xl">{{ $stat['icon'] ?? '📊' }}</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                        @if(isset($stat['subtitle']))
                            <p class="text-xs text-slate-500 mt-2">{{ $stat['subtitle'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Main Report Content -->
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            @yield('report-content')
        </div>
    </div>
</div>

@push('scripts')
    @if(isset($scripts))
        @foreach($scripts as $script)
            <script>
                {{ $script }}
            </script>
        @endforeach
    @endif
@endpush
@endsection
