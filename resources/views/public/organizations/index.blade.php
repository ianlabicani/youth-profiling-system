@extends('public.shell')

@section('title', 'Youth Organizations - Youth Digital Profiling System')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12">
                <a href="/" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Home</span>
                </a>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Youth Organizations</h1>
                <p class="text-lg text-gray-600">Explore all registered youth organizations and their leadership structures</p>
            </div>

            <!-- Filter Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="GET" action="{{ route('public.organizations.index') }}" class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-filter text-blue-600"></i>
                            Filter Organizations
                        </h3>
                        @if(request()->hasAny(['search', 'barangay_id']))
                            <a href="{{ route('public.organizations.index') }}" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
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
                                placeholder="Search by president name or description..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Barangay Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>Info
                            </label>
                            <input type="text" disabled value="Organizations in this system"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
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

            @if($organizations->isEmpty())
                <!-- No Organizations Message -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="mb-4">
                        <i class="fas fa-building text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No Organizations Found</h3>
                    <p class="text-gray-600">Check back soon for registered youth organizations!</p>
                </div>
            @else
                <!-- Organizations Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($organizations as $organization)
                        <a href="{{ route('public.organizations.show', $organization) }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 border-blue-600 group">
                            <!-- Organization Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                                <h3 class="text-xl font-bold text-white line-clamp-2 group-hover:line-clamp-none">
                                    {{ $organization->name ?? "Organization #" . $organization->id }}
                                </h3>
                            </div>

                            <!-- Organization Content -->
                            <div class="p-6 space-y-4">
                                <!-- President -->
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-crown text-yellow-600 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">President</p>
                                        <p class="text-gray-900 font-semibold">{{ $organization->president?->full_name ?? 'Not assigned' }}</p>
                                    </div>
                                </div>

                                <!-- Officers Count -->
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-users text-green-600 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Officers</p>
                                        <p class="text-gray-900 font-semibold">
                                            @php
                                                $officerCount = collect([$organization->president, $organization->vicePresident, $organization->secretary, $organization->treasurer])
                                                    ->filter(fn($officer) => $officer !== null)
                                                    ->count();
                                            @endphp
                                            {{ $officerCount }} / 4
                                        </p>
                                    </div>
                                </div>

                                <!-- Committee Members Count -->
                                @if(!empty($organization->committee_heads))
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-users-cog text-purple-600 flex-shrink-0"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Committee Heads</p>
                                            <p class="text-gray-900 font-semibold">{{ count($organization->committee_heads) }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Description -->
                                @if($organization->description)
                                    <div class="pt-2 border-t">
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ $organization->description }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-3 bg-gray-50 border-t flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Click to view details
                                </span>
                                <i class="fas fa-arrow-right text-blue-600 group-hover:translate-x-1 transition"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($organizations->hasPages())
                    <div class="flex justify-center">
                        {{ $organizations->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
