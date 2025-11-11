@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('brgy.events.show', $event) }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit Event</h1>
            </div>
            <p class="text-gray-600 ml-10">Edit "{{ $event->title }}" for {{ $userBarangay->name }}</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('brgy.events.update', $event) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Basic Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                            <input
                                type="text"
                                name="title"
                                placeholder="e.g., Clean-Up Drive"
                                class="w-full px-4 py-2 border @error('title') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                value="{{ old('title', $event->title) }}"
                                required
                            >
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Organizer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organizer *</label>
                            <input
                                type="text"
                                name="organizer"
                                placeholder="e.g., Sangguniang Kabataan"
                                class="w-full px-4 py-2 border @error('organizer') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                value="{{ old('organizer', $event->organizer) }}"
                                required
                            >
                            @error('organizer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                            <input
                                type="date"
                                name="date"
                                class="w-full px-4 py-2 border @error('date') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                value="{{ old('date', $event->date->format('Y-m-d')) }}"
                                required
                            >
                            @error('date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Time *</label>
                            <input
                                type="time"
                                name="time"
                                class="w-full px-4 py-2 border @error('time') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                value="{{ old('time', $event->time) }}"
                                required
                            >
                            @error('time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Venue & Description -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Details</h3>

                    <div class="space-y-4">
                        <!-- Venue -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Venue *</label>
                            <input
                                type="text"
                                name="venue"
                                placeholder="e.g., Barangay Hall and surrounding areas"
                                class="w-full px-4 py-2 border @error('venue') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                value="{{ old('venue', $event->venue) }}"
                                required
                            >
                            @error('venue')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea
                                name="description"
                                placeholder="Provide event details..."
                                rows="5"
                                class="w-full px-4 py-2 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <button
                        type="submit"
                        class="flex-1 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition"
                    >
                        <i class="fas fa-save mr-2"></i>Update Event
                    </button>
                    <a
                        href="{{ route('brgy.events.show', $event) }}"
                        class="flex-1 px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition text-center"
                    >
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Editing Tips:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Event date must be today or in the future</li>
                        <li>Changes will be saved immediately after clicking Update</li>
                        <li>You can delete this event if it's no longer needed</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
