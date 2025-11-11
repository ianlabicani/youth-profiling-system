@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Barangay Events</h1>
                <p class="text-gray-600 mt-1">Manage events for {{ $userBarangay->name }}</p>
            </div>
            <a href="{{ route('brgy.events.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Create Event</span>
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                <div>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Events Grid -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden flex flex-col">
                        <!-- Event Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 text-white">
                            <h3 class="text-lg font-bold mb-2">{{ $event->title }}</h3>
                            <div class="flex items-center gap-2 text-sm opacity-90">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $event->date->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="p-4 flex-1">
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Time</p>
                                    <p class="text-gray-800">{{ $event->time }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Venue</p>
                                    <p class="text-gray-800 line-clamp-2">{{ $event->venue }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Organizer</p>
                                    <p class="text-gray-800">{{ $event->organizer }}</p>
                                </div>
                                @if($event->skCouncil)
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase">SK Council</p>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-semibold">
                                                {{ $event->skCouncil->term }}
                                            </span>
                                            @if($event->skCouncil->is_active)
                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-green-100 text-green-800 rounded text-xs">
                                                    <i class="fas fa-check-circle"></i>Active
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if($event->description)
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Description</p>
                                        <p class="text-gray-700 text-sm line-clamp-3">{{ $event->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="p-4 bg-gray-50 flex gap-2 border-t">
                            <a href="{{ route('brgy.events.show', $event) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-center text-sm">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <a href="{{ route('brgy.events.edit', $event) }}" class="flex-1 px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-center text-sm">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('brgy.events.destroy', $event) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No Events Yet</h3>
                <p class="text-gray-600 mb-6">You haven't created any events for {{ $userBarangay->name }} yet.</p>
                <a href="{{ route('brgy.events.create') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus"></i>
                    <span>Create Your First Event</span>
                </a>
            </div>
        @endif
    </div>
@endsection
