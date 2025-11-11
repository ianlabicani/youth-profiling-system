@extends('public.shell')

@section('title', $event->title . ' - Event Details')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('public.events.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Events</span>
                </a>
            </div>

            <!-- Event Header Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8 border-l-4 border-blue-600">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-12">
                    <h1 class="text-4xl font-bold text-white mb-4">{{ $event->title }}</h1>
                    <div class="flex flex-wrap gap-4">
                        @if($event->date->isFuture())
                            <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium text-sm">
                                <i class="fas fa-arrow-right mr-1"></i>
                                Upcoming Event
                            </span>
                        @elseif($event->date->isToday())
                            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium text-sm">
                                <i class="fas fa-calendar mr-1"></i>
                                Today
                            </span>
                        @else
                            <span class="inline-block px-4 py-2 bg-gray-100 text-gray-800 rounded-full font-medium text-sm">
                                <i class="fas fa-history mr-1"></i>
                                Past Event
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Date & Time -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-calendar text-blue-600 text-2xl mt-1 flex-shrink-0"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Date</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $event->date->format('F d, Y') }}</p>
                            @if($event->time)
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 italic mt-2">Time TBA</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Venue -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-600">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-map-marker-alt text-red-600 text-2xl mt-1 flex-shrink-0"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Venue</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $event->venue ?? 'Location TBA' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Barangay -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-map-pin text-purple-600 text-2xl mt-1 flex-shrink-0"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Barangay</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $event->barangay?->name ?? 'Barangay TBA' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($event->description)
                <div class="bg-white rounded-lg shadow-md p-8 mb-8 border-l-4 border-green-600">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-green-600"></i>
                        Description
                    </h2>
                    <p class="text-lg text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
                </div>
            @endif

            <!-- Organizer Information -->
            <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-indigo-600">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-building text-indigo-600"></i>
                    Organizer Information
                </h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Organized By</p>
                        <p class="text-lg text-gray-900 font-semibold">
                            @if($event->organizer)
                                {{ $event->organizer }}
                            @elseif($event->skCouncil?->barangay)
                                SK Council - {{ $event->skCouncil->barangay->name }}
                            @else
                                Organization Not Specified
                            @endif
                        </p>
                    </div>

                    @if($event->skCouncil?->barangay)
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Barangay Representative</p>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-location-dot text-indigo-600"></i>
                                <p class="text-gray-900">{{ $event->skCouncil->barangay->name }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t">
                        <p class="text-sm text-gray-500 italic">
                            <i class="fas fa-info-circle mr-1"></i>
                            For more information about this event, please contact your local barangay office.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Back to Events Button -->
            <div class="text-center mt-12">
                <a href="{{ route('public.events.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-arrow-left"></i>
                    Back to All Events
                </a>
            </div>
        </div>
    </div>
@endsection
