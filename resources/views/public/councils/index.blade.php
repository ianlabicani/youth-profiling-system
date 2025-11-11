@extends('public.shell')

@section('title', 'SK Councils - Youth Digital Profiling System')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12">
                <a href="/" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Home</span>
                </a>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">SK Councils</h1>
                <p class="text-lg text-gray-600">Discover active Sangguniang Kabataan councils across our municipality</p>
            </div>

            <!-- Filter Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="GET" action="{{ route('public.councils.index') }}" class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-filter text-blue-600"></i>
                            Filter Councils
                        </h3>
                        @if(request()->hasAny(['search', 'barangay_id']))
                            <a href="{{ route('public.councils.index') }}" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                <i class="fas fa-times"></i>
                                Clear Filters
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i>Search
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by barangay name..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Barangay Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-pin mr-1"></i>Barangay
                            </label>
                            <select name="barangay_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Barangays</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ request('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-search"></i>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            @if($skCouncils->isEmpty())
                <!-- No Councils Message -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="mb-4">
                        <i class="fas fa-landmark text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No SK Councils Found</h3>
                    <p class="text-gray-600">Check back soon for active SK councils!</p>
                </div>
            @else
                <!-- Councils Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($skCouncils as $skc)
                        <a href="{{ route('public.councils.show', $skc) }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 border-green-600 group">
                            <!-- Council Header -->
                            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                                <h3 class="text-xl font-bold text-white line-clamp-2 group-hover:line-clamp-none">
                                    {{ $skc->barangay?->name ?? "SK Council #{$skc->id}" }}
                                </h3>
                            </div>

                            <!-- Council Content -->
                            <div class="p-6 space-y-4">
                                <!-- Status -->
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-green-600 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Status</p>
                                        <p class="text-gray-900">
                                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                Active
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Barangay -->
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-map-pin text-green-600 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Barangay</p>
                                        <p class="text-gray-900 font-semibold">{{ $skc->barangay?->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-3 bg-gray-50 border-t flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Click to view details
                                </span>
                                <i class="fas fa-arrow-right text-green-600 group-hover:translate-x-1 transition"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($skCouncils->hasPages())
                    <div class="flex justify-center">
                        {{ $skCouncils->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
