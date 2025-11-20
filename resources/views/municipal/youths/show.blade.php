@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Photo and Basic Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Photo Card -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-center mb-4">
                        @if($youth->photo)
                            <img src="{{ asset('storage/' . $youth->photo) }}"
                                 alt="{{ $youth->first_name }} {{ $youth->last_name }}"
                                 class="w-48 h-48 rounded-full object-cover border-4 border-blue-100">
                        @else
                            <div class="w-48 h-48 rounded-full bg-gray-200 flex items-center justify-center border-4 border-blue-100">
                                <i class="fas fa-user text-6xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $youth->first_name }}
                            @if($youth->middle_name)
                                {{ substr($youth->middle_name, 0, 1) }}.
                            @endif
                            {{ $youth->last_name }}
                            @if($youth->suffix)
                                {{ $youth->suffix }}
                            @endif
                        </h2>
                        <p class="text-gray-500 mt-1">Youth ID: {{ $youth->id }}</p>
                        <div class="mt-4 flex justify-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($youth->status === 'active') bg-green-100 text-green-800
                                @elseif($youth->status === 'archived') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($youth->status ?? 'unknown') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SK Position Card (if applicable) -->
            @php
                $isChairperson = $youth->chairmanOf()->exists();
                $isSecretary = $youth->secretaryOf()->exists();
                $isTreasurer = $youth->treasurerOf()->exists();
                $hasPosition = $isChairperson || $isSecretary || $isTreasurer;
            @endphp

            @if($hasPosition)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-purple-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-medal text-purple-600 mr-2"></i>SK Council Position
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($isChairperson)
                            @foreach($youth->chairmanOf as $council)
                                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-purple-800">Chairperson</p>
                                        <p class="text-sm text-gray-600">{{ $council->barangay->name }}</p>
                                        @if($council->term)
                                            <p class="text-xs text-gray-500">Term: {{ $council->term }}</p>
                                        @endif
                                    </div>
                                    <i class="fas fa-crown text-2xl text-purple-600"></i>
                                </div>
                            @endforeach
                        @endif

                        @if($isSecretary)
                            @foreach($youth->secretaryOf as $council)
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-blue-800">Secretary</p>
                                        <p class="text-sm text-gray-600">{{ $council->barangay->name }}</p>
                                        @if($council->term)
                                            <p class="text-xs text-gray-500">Term: {{ $council->term }}</p>
                                        @endif
                                    </div>
                                    <i class="fas fa-pen text-2xl text-blue-600"></i>
                                </div>
                            @endforeach
                        @endif

                        @if($isTreasurer)
                            @foreach($youth->treasurerOf as $council)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-green-800">Treasurer</p>
                                        <p class="text-sm text-gray-600">{{ $council->barangay->name }}</p>
                                        @if($council->term)
                                            <p class="text-xs text-gray-500">Term: {{ $council->term }}</p>
                                        @endif
                                    </div>
                                    <i class="fas fa-coins text-2xl text-green-600"></i>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Detailed Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>Personal Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">First Name</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->first_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Middle Name</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->middle_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Name</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->last_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Suffix</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->suffix ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                            <p class="mt-1 text-gray-900 font-medium">
                                @if($youth->date_of_birth)
                                    {{ $youth->date_of_birth->format('F d, Y') }}
                                    <span class="text-sm text-gray-500">({{ $youth->date_of_birth->age }} years old)</span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Sex</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->sex ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-green-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-phone text-green-600 mr-2"></i>Contact Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Contact Number</label>
                            <p class="mt-1 text-gray-900 font-medium">
                                @if($youth->contact_number)
                                    <a href="tel:{{ $youth->contact_number }}" class="text-blue-600 hover:underline">
                                        <i class="fas fa-phone-alt mr-1"></i>{{ $youth->contact_number }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email Address</label>
                            <p class="mt-1 text-gray-900 font-medium">
                                @if($youth->email)
                                    <a href="mailto:{{ $youth->email }}" class="text-blue-600 hover:underline">
                                        <i class="fas fa-envelope mr-1"></i>{{ $youth->email }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-purple-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-shield text-purple-600 mr-2"></i>Guardian Information
                    </h3>
                </div>
                <div class="p-6">
                    @if($youth->guardians && is_array($youth->guardians) && count($youth->guardians) > 0)
                        <div class="space-y-4">
                            @foreach($youth->guardians as $index => $guardian)
                                @if(!empty($guardian['first_name']) || !empty($guardian['last_name']))
                                    <div class="p-4 bg-purple-50 rounded-lg">
                                        <label class="text-sm font-medium text-gray-600">Guardian {{ $index + 1 }}</label>
                                        <p class="mt-1 text-gray-900 font-medium">
                                            {{ $guardian['first_name'] ?? '' }}
                                            {{ !empty($guardian['middle_name']) ? substr($guardian['middle_name'], 0, 1) . '.' : '' }}
                                            {{ $guardian['last_name'] ?? '' }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No guardian information provided</p>
                    @endif
                </div>
            </div>

            <!-- Siblings Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-teal-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-users text-teal-600 mr-2"></i>Siblings
                    </h3>
                </div>
                <div class="p-6">
                    @if($youth->siblings && is_array($youth->siblings) && count($youth->siblings) > 0)
                        <div class="space-y-2">
                            @foreach($youth->siblings as $index => $sibling)
                                @if(!empty($sibling['first_name']) || !empty($sibling['last_name']))
                                    <div class="p-3 bg-teal-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">
                                            {{ $index + 1 }}.
                                            {{ $sibling['first_name'] ?? '' }}
                                            {{ !empty($sibling['middle_name']) ? substr($sibling['middle_name'], 0, 1) . '.' : '' }}
                                            {{ $sibling['last_name'] ?? '' }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No sibling information provided</p>
                    @endif
                </div>
            </div>

            <!-- Household Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-emerald-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-home text-emerald-600 mr-2"></i>Household Information
                    </h3>
                </div>
                <div class="p-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Monthly Household Income Range</label>
                        <p class="mt-1 text-gray-900 font-medium text-lg">
                            @if($youth->household_income)
                                <i class="fas fa-peso-sign text-emerald-600 mr-1"></i>{{ $youth->household_income }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-orange-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>Address Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Purok</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->purok ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Barangay</label>
                            <p class="mt-1 text-gray-900 font-medium">
                                @if($youth->barangay_id && $youth->barangay)
                                    <a href="{{ route('municipal.barangays.show', $youth->barangay_id) }}" class="text-blue-600 hover:underline">
                                        {{ $youth->barangay->name }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Municipality</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->municipality ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Province</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->province ?? '-' }}</p>
                        </div>
                        @if($youth->latitude && $youth->longitude)
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-600">GPS Coordinates</label>
                                <p class="mt-1 text-gray-900 font-medium">
                                    <a href="https://www.google.com/maps?q={{ $youth->latitude }},{{ $youth->longitude }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline">
                                        <i class="fas fa-map-pin mr-1"></i>{{ $youth->latitude }}, {{ $youth->longitude }}
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Educational & Skills Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-indigo-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-graduation-cap text-indigo-600 mr-2"></i>Educational Background & Skills
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Educational Attainment</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $youth->educational_attainment ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Skills</label>
                            @if($youth->skills && is_array($youth->skills) && count($youth->skills) > 0)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($youth->skills as $skill)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-check-circle mr-1"></i>{{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="mt-1 text-gray-900 font-medium">-</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            @if($youth->remarks)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-yellow-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-comment text-yellow-600 mr-2"></i>Remarks
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $youth->remarks }}</p>
                    </div>
                </div>
            @endif

            <!-- Timestamps -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-clock text-gray-600 mr-2"></i>Record Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Created At</label>
                            <p class="mt-1 text-gray-900 font-medium">
                                {{ $youth->created_at->format('F d, Y h:i A') }}
                                <span class="text-sm text-gray-500">({{ $youth->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Updated</label>
                            <p class="mt-1 text-gray-900 font-medium">
                                {{ $youth->updated_at->format('F d, Y h:i A') }}
                                <span class="text-sm text-gray-500">({{ $youth->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="flex flex-wrap justify-between gap-4">
        @if($youth->barangay_id)
            <a href="{{ route('municipal.barangays.youths', $youth->barangay_id) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Youth List
            </a>
        @else
            <a href="{{ route('municipal.youths.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Youth List
            </a>
        @endif

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success messages after 5 seconds
        const successAlert = document.querySelector('[role="alert"]');
        if (successAlert && successAlert.classList.contains('bg-green-100')) {
            setTimeout(function() {
                successAlert.style.opacity = '0';
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 300);
            }, 5000);
        }
    });
</script>
@endsection
