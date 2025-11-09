@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Barangays</h1>
            <p class="text-gray-600 mt-1">Manage barangays in Camalaniugan</p>
        </div>
        <a href="{{ route('municipal.barangays.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Add Barangay
        </a>
    </div>

    <!-- Alert Messages -->
    @if ($message = session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-start">
                <i class="fas fa-check-circle mr-3 mt-0.5"></i>
                <div>
                    <p class="font-medium">Success</p>
                    <p class="text-sm">{{ $message }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Barangays Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($barangays as $barangay)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $barangay->name }}</h3>
                        <p class="text-gray-500 text-sm">ID: {{ $barangay->id }}</p>
                    </div>
                    <div class="text-blue-600">
                        <i class="fas fa-map-pin text-2xl opacity-50"></i>
                    </div>
                </div>

                <div class="pt-4 border-t flex gap-2">
                    <a href="{{ route('municipal.barangays.show', $barangay) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <a href="{{ route('municipal.barangays.edit', $barangay) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('municipal.barangays.destroy', $barangay) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" onclick="return confirm('Are you sure you want to delete this barangay?')">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 text-lg font-medium">No barangays yet</p>
                    <p class="text-gray-400 mt-2">Add your first barangay to get started</p>
                    <a href="{{ route('municipal.barangays.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Add First Barangay
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($barangays->hasPages())
        <div class="flex justify-center mt-8">
            {{ $barangays->links() }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success messages after 5 seconds
        const successAlert = document.querySelector('[role="alert"]');
        if (successAlert && successAlert.classList.contains('bg-green-100')) {
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 5000);
        }
    });
</script>
@endsection
