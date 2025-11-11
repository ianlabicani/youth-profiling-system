@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
            <a href="{{ route('municipal.barangays.sk-councils.index', $barangay) }}" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-bold text-gray-800">SK Council #{{ $skCouncil->id }}</h1>
                    @if(!empty($skCouncil->is_active) && $skCouncil->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 text-sm font-semibold bg-green-100 text-green-800 rounded-full">
                            <i class="fas fa-check mr-1 text-xs"></i>Active
                        </span>
                    @endif
                </div>
                <p class="text-gray-600 mt-1">Sangguniang Kabataan Council Details</p>
            </div>
        </div>
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

    <!-- SK Council Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Chairperson Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-crown text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Chairperson</h3>
                    <p class="text-xs text-gray-500">Council Head</p>
                </div>
            </div>
            <div class="border-t pt-4">
                @if($skCouncil->chairperson)
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">
                                {{ $skCouncil->chairperson->first_name }}
                                @if($skCouncil->chairperson->middle_name)
                                    {{ substr($skCouncil->chairperson->middle_name, 0, 1) }}.
                                @endif
                                {{ $skCouncil->chairperson->last_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Contact</p>
                            <p class="text-sm text-gray-800">{{ $skCouncil->chairperson->contact_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Purok</p>
                            <p class="text-sm text-gray-800">{{ $skCouncil->chairperson->purok ?? 'N/A' }}</p>
                        </div>
                        <a href="{{ route('municipal.dashboard', $skCouncil->chairperson) }}"
                           class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700 mt-2">
                            <i class="fas fa-external-link-alt mr-1"></i>View Profile
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Not assigned</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Secretary Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-pen-nib text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Secretary</h3>
                    <p class="text-xs text-gray-500">Records & Correspondence</p>
                </div>
            </div>
            <div class="border-t pt-4">
                @if($skCouncil->secretary)
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">
                                {{ $skCouncil->secretary->first_name }}
                                @if($skCouncil->secretary->middle_name)
                                    {{ substr($skCouncil->secretary->middle_name, 0, 1) }}.
                                @endif
                                {{ $skCouncil->secretary->last_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Contact</p>
                            <p class="text-sm text-gray-800">{{ $skCouncil->secretary->contact_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Purok</p>
                            <p class="text-sm text-gray-800">{{ $skCouncil->secretary->purok ?? 'N/A' }}</p>
                        </div>
                        <a href="{{ route('municipal.youths.show', $skCouncil->secretary) }}"
                           class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700 mt-2">
                            <i class="fas fa-external-link-alt mr-1"></i>View Profile
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Not assigned</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Treasurer Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-wallet text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Treasurer</h3>
                    <p class="text-xs text-gray-500">Finance & Budget</p>
                </div>
            </div>
            <div class="border-t pt-4">
                @if($skCouncil->treasurer)
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">
                                {{ $skCouncil->treasurer->first_name }}
                                @if($skCouncil->treasurer->middle_name)
                                    {{ substr($skCouncil->treasurer->middle_name, 0, 1) }}.
                                @endif
                                {{ $skCouncil->treasurer->last_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Contact</p>
                            <p class="text-sm text-gray-800">{{ $skCouncil->treasurer->contact_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Purok</p>
                            <p class="text-sm text-gray-800">{{ $skCouncil->treasurer->purok ?? 'N/A' }}</p>
                        </div>
                        <a href="{{ route('municipal.youths.show', $skCouncil->treasurer) }}"
                           class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700 mt-2">
                            <i class="fas fa-external-link-alt mr-1"></i>View Profile
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Not assigned</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kagawad (Council Members) Section -->
    @if($skCouncil->kagawad_ids && count($skCouncil->kagawad_ids) > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Kagawads (Council Members)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($skCouncil->kagawads() as $kagawad)
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $kagawad->first_name }}
                                    @if($kagawad->middle_name)
                                        {{ substr($kagawad->middle_name, 0, 1) }}.
                                    @endif
                                    {{ $kagawad->last_name }}
                                </p>
                                <p class="text-xs text-gray-500">Kagawad</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div>
                                <p class="text-xs text-gray-600">Contact</p>
                                <p class="text-gray-800">{{ $kagawad->contact_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Purok</p>
                                <p class="text-gray-800">{{ $kagawad->purok ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('municipal.youths.show', $kagawad) }}"
                           class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700 mt-3">
                            <i class="fas fa-external-link-alt mr-1"></i>View Profile
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-yellow-600 text-lg mr-3 mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-yellow-800">No Kagawads Assigned</h3>
                    <p class="text-sm text-yellow-700 mt-1">There are no council members assigned yet.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Council Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Council Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase">Council ID</p>
                <p class="text-lg text-gray-900 mt-1">{{ $skCouncil->id }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase">Barangay</p>
                <p class="text-lg text-gray-900 mt-1">{{ $barangay->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase">Created</p>
                <p class="text-lg text-gray-900 mt-1">{{ $skCouncil->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase">Last Updated</p>
                <p class="text-lg text-gray-900 mt-1">{{ $skCouncil->updated_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-center">
        <a href="{{ route('municipal.barangays.sk-councils.index', $barangay) }}"
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to SK Councils
        </a>
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
