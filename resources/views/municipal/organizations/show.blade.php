@extends('municipal.shell')

@section('municipal-content')
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('municipal.organizations.index') }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Organization Details</h1>
            </div>
            <p class="text-gray-600 ml-10">View organization information</p>
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

        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-6">
                <div class="flex justify-between items-start">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold mb-1">Organization</h2>
                        @if($organization->barangay)
                            <p class="text-blue-100">{{ $organization->barangay->name }}</p>
                        @endif
                    </div>
                    <a href="{{ route('municipal.organizations.edit', $organization) }}"
                       class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>

            <!-- Description Section -->
            @if($organization->description)
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">About</h3>
                    <p class="text-gray-700">{{ $organization->description }}</p>
                </div>
            @endif

            <!-- Officers Section -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-tie text-blue-600"></i>
                    Organization Officers
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- President Card -->
                    <div class="bg-blue-50 rounded-lg p-5 border-2 border-blue-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-crown text-blue-600 text-xl"></i>
                            <label class="text-sm font-bold text-blue-800 uppercase">President</label>
                        </div>
                        @if($organization->president)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center flex-shrink-0">
                                    @if($organization->president->photo)
                                        <img src="{{ asset('storage/' . $organization->president->photo) }}"
                                             alt="{{ $organization->president->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-blue-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('municipal.youths.show', $organization->president) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-600 transition block">
                                        {{ $organization->president->first_name }}
                                        @if($organization->president->middle_name)
                                            {{ substr($organization->president->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $organization->president->last_name }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        @if($organization->president->contact_number)
                                            <i class="fas fa-phone text-xs mr-1"></i>{{ $organization->president->contact_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Not assigned</p>
                        @endif
                    </div>

                    <!-- Vice President Card -->
                    <div class="bg-purple-50 rounded-lg p-5 border-2 border-purple-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                            <label class="text-sm font-bold text-purple-800 uppercase">V. President</label>
                        </div>
                        @if($organization->vicePresident)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-purple-200 flex items-center justify-center flex-shrink-0">
                                    @if($organization->vicePresident->photo)
                                        <img src="{{ asset('storage/' . $organization->vicePresident->photo) }}"
                                             alt="{{ $organization->vicePresident->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-purple-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('municipal.youths.show', $organization->vicePresident) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-600 transition block">
                                        {{ $organization->vicePresident->first_name }}
                                        @if($organization->vicePresident->middle_name)
                                            {{ substr($organization->vicePresident->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $organization->vicePresident->last_name }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        @if($organization->vicePresident->contact_number)
                                            <i class="fas fa-phone text-xs mr-1"></i>{{ $organization->vicePresident->contact_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Not assigned</p>
                        @endif
                    </div>

                    <!-- Secretary Card -->
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-pen text-green-600 text-xl"></i>
                            <label class="text-sm font-bold text-green-800 uppercase">Secretary</label>
                        </div>
                        @if($organization->secretary)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                                    @if($organization->secretary->photo)
                                        <img src="{{ asset('storage/' . $organization->secretary->photo) }}"
                                             alt="{{ $organization->secretary->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-green-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('municipal.youths.show', $organization->secretary) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-600 transition block">
                                        {{ $organization->secretary->first_name }}
                                        @if($organization->secretary->middle_name)
                                            {{ substr($organization->secretary->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $organization->secretary->last_name }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        @if($organization->secretary->contact_number)
                                            <i class="fas fa-phone text-xs mr-1"></i>{{ $organization->secretary->contact_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Not assigned</p>
                        @endif
                    </div>

                    <!-- Treasurer Card -->
                    <div class="bg-orange-50 rounded-lg p-5 border-2 border-orange-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-coins text-orange-600 text-xl"></i>
                            <label class="text-sm font-bold text-orange-800 uppercase">Treasurer</label>
                        </div>
                        @if($organization->treasurer)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-orange-200 flex items-center justify-center flex-shrink-0">
                                    @if($organization->treasurer->photo)
                                        <img src="{{ asset('storage/' . $organization->treasurer->photo) }}"
                                             alt="{{ $organization->treasurer->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-orange-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('municipal.youths.show', $organization->treasurer) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-600 transition block">
                                        {{ $organization->treasurer->first_name }}
                                        @if($organization->treasurer->middle_name)
                                            {{ substr($organization->treasurer->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $organization->treasurer->last_name }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        @if($organization->treasurer->contact_number)
                                            <i class="fas fa-phone text-xs mr-1"></i>{{ $organization->treasurer->contact_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Not assigned</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Committee Heads Section -->
            @if($committeeHeads && count($committeeHeads) > 0)
                <div class="p-6 border-t">
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-briefcase text-indigo-600"></i>
                        Committee Heads ({{ count($committeeHeads) }})
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($committeeHeads as $committee)
                            <div class="flex items-center gap-4 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                                <div class="w-12 h-12 rounded-full bg-indigo-200 flex items-center justify-center flex-shrink-0">
                                    @if($committee['head'] && $committee['head']->photo)
                                        <img src="{{ asset('storage/' . $committee['head']->photo) }}"
                                             alt="{{ $committee['head']->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-indigo-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $committee['name'] }}</p>
                                    @if($committee['head'])
                                        @php $head = $committee['head']; @endphp
                                        <a href="{{ route('municipal.youths.show', $head->id) }}"
                                           class="text-sm text-indigo-600 hover:text-indigo-700 transition block font-medium">
                                            {{ $head->first_name }}
                                            @if($head->middle_name)
                                                {{ substr($head->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $head->last_name }}
                                        </a>
                                        @if($head->contact_number)
                                            <p class="text-xs text-gray-600">
                                                <i class="fas fa-phone text-xs mr-1"></i>{{ $head->contact_number }}
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-500 italic">No head assigned</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Members Section -->
            @if($members && count($members) > 0)
                <div class="p-6 border-t">
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-users text-indigo-600"></i>
                        Members ({{ count($members) }})
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($members as $member)
                            <a href="{{ route('municipal.youths.show', $member) }}"
                               class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition border border-gray-200">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    @if($member->photo)
                                        <img src="{{ asset('storage/' . $member->photo) }}"
                                             alt="{{ $member->first_name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-indigo-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </p>
                                    @if($member->contact_number)
                                        <p class="text-xs text-gray-500">{{ $member->contact_number }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Record Info -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Created: {{ $organization->created_at->format('F d, Y h:i A') }}</span>
                    <span>Last Updated: {{ $organization->updated_at->format('F d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('municipal.organizations.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
            <div class="flex gap-3">
                <a href="{{ route('municipal.organizations.edit', $organization) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
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
