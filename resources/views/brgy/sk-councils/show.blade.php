@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('brgy.sk-councils.index') }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">SK Council Details</h1>
            </div>
            <p class="text-gray-600 ml-10">View Sangguniang Kabataan council information</p>
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
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-6">
                <div class="flex justify-between items-start">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold mb-1">{{ $userBarangay->name }}</h2>
                        <p class="text-purple-100">Sangguniang Kabataan Council</p>
                    </div>
                    <a href="{{ route('brgy.sk-councils.edit', $skCouncil) }}"
                       class="px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-50 transition font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit Council
                    </a>
                </div>
            </div>

            <!-- Council Info -->
            <div class="p-6 border-b">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Term</label>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ $skCouncil->term }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Created</label>
                        <p class="text-gray-900 mt-1">{{ $skCouncil->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Officers Section -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-tie text-purple-600"></i>
                    SK Officers
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Chairperson Card -->
                    <div class="bg-purple-50 rounded-lg p-5 border-2 border-purple-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-crown text-purple-600 text-xl"></i>
                            <label class="text-sm font-bold text-purple-800 uppercase">Chairperson</label>
                        </div>
                        @if($skCouncil->chairperson)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-purple-200 flex items-center justify-center flex-shrink-0">
                                    @if($skCouncil->chairperson->photo)
                                        <img src="{{ asset('storage/' . $skCouncil->chairperson->photo) }}"
                                             alt="{{ $skCouncil->chairperson->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-purple-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('brgy.youth.show', $skCouncil->chairperson) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-600 transition block">
                                        {{ $skCouncil->chairperson->first_name }}
                                        @if($skCouncil->chairperson->middle_name)
                                            {{ substr($skCouncil->chairperson->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $skCouncil->chairperson->last_name }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        @if($skCouncil->chairperson->contact_number)
                                            <i class="fas fa-phone text-xs mr-1"></i>{{ $skCouncil->chairperson->contact_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Not assigned</p>
                        @endif
                    </div>

                    <!-- Secretary Card -->
                    <div class="bg-blue-50 rounded-lg p-5 border-2 border-blue-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-pen text-blue-600 text-xl"></i>
                            <label class="text-sm font-bold text-blue-800 uppercase">Secretary</label>
                        </div>
                        @if($skCouncil->secretary)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center flex-shrink-0">
                                    @if($skCouncil->secretary->photo)
                                        <img src="{{ asset('storage/' . $skCouncil->secretary->photo) }}"
                                             alt="{{ $skCouncil->secretary->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-blue-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('brgy.youth.show', $skCouncil->secretary) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-600 transition block">
                                        {{ $skCouncil->secretary->first_name }}
                                        @if($skCouncil->secretary->middle_name)
                                            {{ substr($skCouncil->secretary->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $skCouncil->secretary->last_name }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        @if($skCouncil->secretary->contact_number)
                                            <i class="fas fa-phone text-xs mr-1"></i>{{ $skCouncil->secretary->contact_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Not assigned</p>
                        @endif
                    </div>

                    <!-- Treasurer Card -->
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-coins text-green-600 text-xl"></i>
                            <label class="text-sm font-bold text-green-800 uppercase">Treasurer</label>
                        </div>
                        @if($skCouncil->treasurer)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                                    @if($skCouncil->treasurer->photo)
                                        <img src="{{ asset('storage/' . $skCouncil->treasurer->photo) }}"
                                             alt="{{ $skCouncil->treasurer->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-green-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('brgy.youth.show', $skCouncil->treasurer) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-600 transition block">
                                        {{ $skCouncil->treasurer->first_name }}
                                        @if($skCouncil->treasurer->middle_name)
                                            {{ substr($skCouncil->treasurer->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $skCouncil->treasurer->last_name }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        @if($skCouncil->treasurer->contact_number)
                                            <i class="fas fa-phone text-xs mr-1"></i>{{ $skCouncil->treasurer->contact_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Not assigned</p>
                        @endif
                    </div>
                </div>

                <!-- Kagawads Section -->
                <div class="border-t pt-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-users text-indigo-600"></i>
                        Kagawad (Council Members)
                    </h4>
                    @if($skCouncil->kagawad_ids && count($skCouncil->kagawad_ids) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($skCouncil->kagawads() as $kagawad)
                                <a href="{{ route('brgy.youth.show', $kagawad) }}"
                                   class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition border border-gray-200">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        @if($kagawad->photo)
                                            <img src="{{ asset('storage/' . $kagawad->photo) }}"
                                                 alt="{{ $kagawad->first_name }}"
                                                 class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <i class="fas fa-user text-indigo-600"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">
                                            {{ $kagawad->first_name }} {{ $kagawad->last_name }}
                                        </p>
                                        @if($kagawad->contact_number)
                                            <p class="text-xs text-gray-500">{{ $kagawad->contact_number }}</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <i class="fas fa-users text-3xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">No kagawad members assigned yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Record Info -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Created: {{ $skCouncil->created_at->format('F d, Y h:i A') }}</span>
                    <span>Last Updated: {{ $skCouncil->updated_at->format('F d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('brgy.sk-councils.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
            <div class="flex gap-3">
                <a href="{{ route('brgy.sk-councils.edit', $skCouncil) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-edit mr-2"></i>Edit Council
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
