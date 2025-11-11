@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Out of School Youths</h1>
        <p class="text-gray-600 mt-2">View and manage youths who are not currently enrolled in any educational institution</p>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Out of School</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $youths->total() }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-graduation-cap text-xl text-orange-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $youths->getCollection()->where('status', 'active')->count() }}
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Archived</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ $youths->getCollection()->where('status', 'archived')->count() }}
                    </p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-archive text-xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter mr-2 text-blue-600"></i>Filters
        </h2>

        <form method="GET" action="{{ route('municipal.youths.out-of-school') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Name, email, phone..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Barangay Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                    <select name="barangay_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Barangays</option>
                        @foreach(\App\Models\Barangay::orderBy('name')->get() as $barangay)
                            <option value="{{ $barangay->id }}" {{ request('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                {{ $barangay->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <!-- Sex Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                    <select name="sex" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All</option>
                        <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ request('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('municipal.youths.out-of-school') }}" class="inline-flex items-center px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
                <div class="ml-auto flex gap-2">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        <i class="fas fa-file-excel mr-2"></i>Export
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Out of School Youths Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($youths->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Barangay</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Age</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Sex</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($youths as $youth)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-blue-600 font-semibold">#{{ $youth->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if($youth->photo)
                                            <img src="{{ asset('storage/' . $youth->photo) }}" alt="{{ $youth->full_name }}" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($youth->first_name, 0, 1) }}{{ substr($youth->last_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $youth->first_name }}
                                                @if($youth->middle_name)
                                                    {{ substr($youth->middle_name, 0, 1) }}.
                                                @endif
                                                {{ $youth->last_name }}
                                                @if($youth->suffix)
                                                    {{ $youth->suffix }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-graduation-cap mr-1 text-orange-600"></i>Out of School
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($youth->barangay_id && $youth->barangay)
                                        <a href="{{ route('municipal.barangays.show', $youth->barangay_id) }}"
                                           class="text-blue-600 hover:underline font-medium">
                                            {{ $youth->barangay->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($youth->date_of_birth)
                                        {{ now()->diff($youth->date_of_birth)->y }} years old
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        @if($youth->contact_number)
                                            <p><i class="fas fa-phone text-xs mr-1"></i>{{ $youth->contact_number }}</p>
                                        @endif
                                        @if($youth->email)
                                            <p class="text-xs text-gray-500"><i class="fas fa-envelope text-xs mr-1"></i>{{ $youth->email }}</p>
                                        @endif
                                        @if(!$youth->contact_number && !$youth->email)
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $youth->sex ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($youth->status === 'active') bg-green-100 text-green-800
                                        @elseif($youth->status === 'archived') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($youth->status ?? 'unknown') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('municipal.youths.show', $youth) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1 text-sm rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($youths->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $youths->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-graduation-cap text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg font-medium mb-2">No out of school youths found</p>
                <p class="text-gray-400">
                    @if(request()->hasAny(['search', 'barangay_id', 'status', 'sex']))
                        Try adjusting your filters or
                        <a href="{{ route('municipal.youths.out-of-school') }}" class="text-blue-600 hover:underline font-medium">reset all filters</a>
                    @else
                        All youths in the system have educational attainment records
                    @endif
                </p>
            </div>
        @endif
    </div>

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
