@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('municipal.barangays.index') }}" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $barangay->name }}</h1>
            <p class="text-gray-600 mt-1">Barangay Details</p>
        </div>
    </div>

    <!-- Barangay Card -->
    <div class="bg-white rounded-lg shadow p-6 md:p-8 max-w-2xl">
        <div class="space-y-4">
            <!-- ID -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">ID</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->id }}</p>
            </div>

            <!-- Name -->
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Barangay Name</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->name }}</p>
            </div>

            <!-- Created At -->
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Created</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->created_at->format('M d, Y H:i A') }}</p>
            </div>

            <!-- Updated At -->
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Last Updated</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->updated_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-8 border-t mt-8">
            <a
                href="{{ route('municipal.barangays.edit', $barangay) }}"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a
                href="{{ route('municipal.barangays.index') }}"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>
</div>
@endsection
