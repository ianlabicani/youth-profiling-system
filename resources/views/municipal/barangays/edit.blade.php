@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('municipal.barangays.index') }}" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Barangay</h1>
            <p class="text-gray-600 mt-1">Update barangay information</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6 md:p-8 max-w-2xl">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <p class="font-medium">Please fix the following errors:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('municipal.barangays.update', $barangay) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Barangay Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $barangay->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="e.g., Bulala"
                    required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a
                    href="{{ route('municipal.barangays.index') }}"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold text-red-600 mb-3">Danger Zone</h3>
            <p class="text-gray-600 text-sm mb-3">Delete this barangay permanently</p>
            <form action="{{ route('municipal.barangays.destroy', $barangay) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
                    onclick="return confirm('Are you sure you want to delete this barangay? This action cannot be undone.')">
                    <i class="fas fa-trash mr-2"></i>Delete Barangay
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
