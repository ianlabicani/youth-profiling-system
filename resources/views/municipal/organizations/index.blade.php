@extends('municipal.shell')

@section('municipal-content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Organizations Management</h1>
            <p class="text-gray-600 mt-2">Manage youth organizations across all barangays</p>
        </div>

        <!-- Alert Messages -->
        @if ($message = session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-check-circle mr-3 mt-0.5"></i>
                    <div>
                        <p class="font-medium">Success</p>
                        <p class="text-sm">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-red-800 mb-2">Error</h3>
                        <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Create Button -->
        <div class="mb-6">
            <a href="{{ route('municipal.organizations.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-plus-circle"></i>
                <span>Create Organization</span>
            </a>
        </div>

        @if($organizations->isEmpty())
            <!-- No Organizations Yet -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="mb-6">
                    <i class="fas fa-users text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Organizations Yet</h3>
                <p class="text-gray-600 mb-6">No youth organizations have been registered yet.</p>
            </div>
        @else
            <!-- Organizations Grid -->
            <div class="grid grid-cols-1 gap-6">
                @foreach($organizations as $org)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-white">{{ $org->name ?? "Organization #" . $org->id }}</h2>
                                @if($org->barangay)
                                    <p class="text-blue-100 text-sm">{{ $org->barangay->name }}</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('municipal.organizations.show', $org) }}"
                                   class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium text-sm">
                                    <i class="fas fa-eye mr-1"></i>View Details
                                </a>
                                <a href="{{ route('municipal.organizations.edit', $org) }}"
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-medium text-sm">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Officers -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <!-- President -->
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-crown text-blue-600"></i>
                                        <label class="text-sm font-semibold text-blue-800 uppercase">President</label>
                                    </div>
                                    @if($org->president)
                                        <a href="{{ route('municipal.youths.show', $org->president) }}"
                                           class="text-gray-900 font-medium hover:text-blue-600 transition">
                                            {{ $org->president->first_name }}
                                            @if($org->president->middle_name)
                                                {{ substr($org->president->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $org->president->last_name }}
                                        </a>
                                    @else
                                        <p class="text-gray-500 italic">Not assigned</p>
                                    @endif
                                </div>

                                <!-- Vice President -->
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-user-tie text-purple-600"></i>
                                        <label class="text-sm font-semibold text-purple-800 uppercase">Vice Pres.</label>
                                    </div>
                                    @if($org->vicePresident)
                                        <a href="{{ route('municipal.youths.show', $org->vicePresident) }}"
                                           class="text-gray-900 font-medium hover:text-blue-600 transition">
                                            {{ $org->vicePresident->first_name }}
                                            @if($org->vicePresident->middle_name)
                                                {{ substr($org->vicePresident->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $org->vicePresident->last_name }}
                                        </a>
                                    @else
                                        <p class="text-gray-500 italic">Not assigned</p>
                                    @endif
                                </div>

                                <!-- Secretary -->
                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-pen text-green-600"></i>
                                        <label class="text-sm font-semibold text-green-800 uppercase">Secretary</label>
                                    </div>
                                    @if($org->secretary)
                                        <a href="{{ route('municipal.youths.show', $org->secretary) }}"
                                           class="text-gray-900 font-medium hover:text-blue-600 transition">
                                            {{ $org->secretary->first_name }}
                                            @if($org->secretary->middle_name)
                                                {{ substr($org->secretary->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $org->secretary->last_name }}
                                        </a>
                                    @else
                                        <p class="text-gray-500 italic">Not assigned</p>
                                    @endif
                                </div>

                                <!-- Treasurer -->
                                <div class="bg-orange-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-coins text-orange-600"></i>
                                        <label class="text-sm font-semibold text-orange-800 uppercase">Treasurer</label>
                                    </div>
                                    @if($org->treasurer)
                                        <a href="{{ route('municipal.youths.show', $org->treasurer) }}"
                                           class="text-gray-900 font-medium hover:text-blue-600 transition">
                                            {{ $org->treasurer->first_name }}
                                            @if($org->treasurer->middle_name)
                                                {{ substr($org->treasurer->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $org->treasurer->last_name }}
                                        </a>
                                    @else
                                        <p class="text-gray-500 italic">Not assigned</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            @if($org->description)
                                <div class="mb-4 pb-4 border-b">
                                    <p class="text-gray-700">{{ $org->description }}</p>
                                </div>
                            @endif

                            <!-- Members Count -->
                            <div class="flex gap-6 text-sm text-gray-600">
                                @if($org->members)
                                    <span>
                                        <i class="fas fa-users mr-1"></i>
                                        {{ count($org->members) }} member{{ count($org->members) !== 1 ? 's' : '' }}
                                    </span>
                                @endif
                                @if($org->committee_heads)
                                    <span>
                                        <i class="fas fa-briefcase mr-1"></i>
                                        {{ count($org->committee_heads) }} committee head{{ count($org->committee_heads) !== 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <form action="{{ route('municipal.organizations.destroy', $org) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this organization?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($organizations->hasPages())
                <div class="mt-6">
                    {{ $organizations->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        // Auto-hide success messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.querySelector('[role="alert"]');
            if (successAlert && successAlert.classList.contains('bg-green-100')) {
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 5000);
            }
        });
    </script>
@endsection
