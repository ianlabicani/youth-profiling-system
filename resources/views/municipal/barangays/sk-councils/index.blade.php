@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('municipal.barangays.show', $barangay) }}" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">SK Councils - {{ $barangay->name }}</h1>
                <p class="text-gray-600 mt-1">Sangguniang Kabataan Council Records</p>
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

    @if($skCouncils->count() > 0)
        <!-- SK Councils Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($skCouncils as $council)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-bold text-gray-800">SK Council #{{ $council->id }}</h3>
                                @if(!empty($council->is_active) && $council->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        <i class="fas fa-check mr-1 text-xs"></i>Active
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $council->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-purple-600">
                            <i class="fas fa-crown text-2xl opacity-50"></i>
                        </div>
                    </div>

                    <!-- Council Positions -->
                    <div class="space-y-3 mb-4 border-t pt-4">
                        <!-- Chairperson -->
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Chairperson</p>
                            @if($council->chairperson)
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $council->chairperson->first_name }}
                                    @if($council->chairperson->middle_name)
                                        {{ substr($council->chairperson->middle_name, 0, 1) }}.
                                    @endif
                                    {{ $council->chairperson->last_name }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>

                        <!-- Secretary -->
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Secretary</p>
                            @if($council->secretary)
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $council->secretary->first_name }}
                                    @if($council->secretary->middle_name)
                                        {{ substr($council->secretary->middle_name, 0, 1) }}.
                                    @endif
                                    {{ $council->secretary->last_name }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>

                        <!-- Treasurer -->
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Treasurer</p>
                            @if($council->treasurer)
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $council->treasurer->first_name }}
                                    @if($council->treasurer->middle_name)
                                        {{ substr($council->treasurer->middle_name, 0, 1) }}.
                                    @endif
                                    {{ $council->treasurer->last_name }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>

                        <!-- Kagawads Count -->
                        @if($council->kagawad_ids && count($council->kagawad_ids) > 0)
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase">Kagawads</p>
                                <p class="text-sm font-medium text-gray-900">{{ count($council->kagawad_ids) }} members</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 pt-4 border-t">
                        <a href="{{ route('municipal.barangays.sk-councils.show', [$barangay, $council]) }}"
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($skCouncils->hasPages())
            <div class="flex justify-center mt-8">
                {{ $skCouncils->links() }}
            </div>
        @endif
    @else
        <!-- No SK Councils Message -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <i class="fas fa-info-circle text-blue-600 text-4xl mb-4"></i>
            <h3 class="text-xl font-bold text-blue-900 mt-2">No SK Councils</h3>
            <p class="text-sm text-blue-700 mt-2">There are no SK Council records for this barangay yet.</p>
        </div>
    @endif

    <!-- Back Button -->
    <div class="flex justify-center">
        <a href="{{ route('municipal.barangays.show', $barangay) }}"
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Barangay
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
