@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('brgy.events.index') }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">{{ $event->title }}</h1>
            </div>
            <p class="text-gray-600 ml-10">Event details for {{ $userBarangay->name }}</p>
        </div>

        <!-- Event Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header with Date/Time -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-blue-100 text-sm font-semibold mb-1">DATE</p>
                        <p class="text-2xl font-bold">{{ $event->date->format('M d') }}</p>
                        <p class="text-blue-100">{{ $event->date->format('Y') }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm font-semibold mb-1">TIME</p>
                        <p class="text-2xl font-bold">{{ $event->time }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm font-semibold mb-1">ORGANIZER</p>
                        <p class="text-lg font-semibold">{{ $event->organizer }}</p>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-6">
                <!-- Venue -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-600"></i>
                        Venue
                    </h3>
                    <p class="text-gray-700 text-lg">{{ $event->venue }}</p>
                </div>

                <!-- Description -->
                @if($event->description)
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-align-left text-blue-600"></i>
                            Description
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                    </div>
                @endif

                <!-- Event Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-green-600"></i>
                        Event Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Barangay</p>
                            <p class="text-gray-800">{{ $userBarangay->name }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-semibold text-gray-600 mb-1">SK Council</p>
                            @if($event->skCouncil)
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
                                        {{ $event->skCouncil->term }}
                                    </span>
                                    @if($event->skCouncil->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs font-semibold">
                                            <i class="fas fa-minus-circle"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-600 italic">Not assigned to any council</p>
                            @endif
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Created</p>
                            <p class="text-gray-800">{{ $event->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Last Updated</p>
                            <p class="text-gray-800">{{ $event->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Status</p>
                            @if($event->date->isToday())
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-calendar-check"></i>Today
                                </span>
                            @elseif($event->date->isFuture())
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-clock"></i>Upcoming
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-history"></i>Past
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50 border-t flex gap-3">
                <a href="{{ route('brgy.events.edit', $event) }}" class="flex-1 px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition text-center flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i>
                    <span>Edit Event</span>
                </a>
                <form action="{{ route('brgy.events.destroy', $event) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this event?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>Delete Event</span>
                    </button>
                </form>
                <a href="{{ route('brgy.events.index') }}" class="flex-1 px-6 py-2 bg-gray-400 text-gray-700 font-medium rounded-lg hover:bg-gray-500 transition text-center flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Events</span>
                </a>
            </div>
        </div>
    </div>
@endsection
