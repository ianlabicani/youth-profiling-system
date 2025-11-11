
@extends('public.shell')

@section('title', 'Upcoming Events - Youth Digital Profiling System')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12">
                <a href="/" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Home</span>
                </a>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Upcoming Events</h1>
                <p class="text-lg text-gray-600">Discover and explore all youth events across our community</p>
            </div>

            <!-- Filter Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="GET" action="{{ route('public.events.index') }}" class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-filter text-blue-600"></i>
                            Filter Events
                        </h3>
                        @if(request()->hasAny(['search', 'date_from', 'date_to', 'status', 'organizer_id']))
                            <a href="{{ route('public.events.index') }}" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                <i class="fas fa-times"></i>
                                Clear Filters
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i>Search
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by title, description..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i>From Date
                            </label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i>To Date
                            </label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hourglass mr-1"></i>Status
                            </label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="" {{ !request('status') ? 'selected' : '' }}>Upcoming & Today</option>
                                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="today" {{ request('status') === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="past" {{ request('status') === 'past' ? 'selected' : '' }}>Past Events</option>
                            </select>
                        </div>

                        <!-- Barangay Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-pin mr-1"></i>Barangay
                            </label>
                            <select name="organizer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Barangays</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ request('organizer_id') == $barangay->id ? 'selected' : '' }}>
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

            @if($events->isEmpty())
                <!-- No Events Message -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No Events Scheduled</h3>
                    <p class="text-gray-600">Check back soon for upcoming youth events and programs!</p>
                </div>
            @else
                <!-- Events Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($events as $event)
                        <a href="{{ route('public.events.show', $event) }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 border-blue-600 group">
                            <!-- Event Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                                <h3 class="text-xl font-bold text-white line-clamp-2 group-hover:line-clamp-none">{{ $event->title }}</h3>
                            </div>

                            <!-- Event Content -->
                            <div class="p-6 space-y-4">
                                <!-- Date & Time -->
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-calendar text-blue-600 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Date</p>
                                        <p class="text-gray-900 font-semibold">
                                            {{ $event->date->format('F d, Y') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Time -->
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-clock text-green-600 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Time</p>
                                        <p class="text-gray-900 font-semibold">
                                            @if($event->time)
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}
                                            @else
                                                Time TBA
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-map-marker-alt text-red-600 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Venue</p>
                                        <p class="text-gray-900 font-semibold">
                                            {{ $event->venue ?? 'Location TBA' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Organizer -->
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-map-pin text-purple-600 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Barangay</p>
                                        <p class="text-gray-900 font-semibold">
                                            {{ $event->barangay?->name ?? 'Barangay TBA' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($event->description)
                                    <div class="pt-2 border-t">
                                        <p class="text-sm text-gray-700 line-clamp-3">
                                            {{ $event->description }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-3 bg-gray-50 border-t flex justify-between items-center">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    @if($event->date->isFuture())
                                        <span class="text-green-600 font-medium">Upcoming</span>
                                    @elseif($event->date->isToday())
                                        <span class="text-blue-600 font-medium">Today</span>
                                    @else
                                        <span class="text-gray-500">{{ $event->date->diffForHumans() }}</span>
                                    @endif
                                </span>
                                <i class="fas fa-arrow-right text-blue-600 group-hover:translate-x-1 transition"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                    <div class="flex justify-center">
                        {{ $events->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
