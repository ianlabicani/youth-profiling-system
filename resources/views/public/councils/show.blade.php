@extends('public.shell')

@section('title', $skCouncil->barangay?->name . ' - SK Council')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('public.councils.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Councils</span>
                </a>
            </div>

            <!-- Council Info Card -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8 border-l-4 border-green-600">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $skCouncil->barangay?->name ?? "SK Council #{$skCouncil->id}" }}</h1>
                        <p class="text-lg text-gray-600">Sangguniang Kabataan</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">
                            Active Council
                        </span>
                    </div>
                </div>
            </div>

            <!-- Council Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <!-- Information -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        Information
                    </h2>

                    <div class="space-y-4">
                        <!-- Barangay -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Barangay</p>
                            <p class="text-lg text-gray-900 font-semibold">{{ $skCouncil->barangay?->name ?? 'N/A' }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Status</p>
                            <p class="text-lg">
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    {{ $skCouncil->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>

                        <!-- Council ID -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Council ID</p>
                            <p class="text-lg text-gray-900">SK Council #{{ $skCouncil->id }}</p>
                        </div>

                        <!-- Term -->
                        @if($skCouncil->term)
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Term</p>
                                <p class="text-lg text-gray-900">{{ $skCouncil->term }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-phone text-purple-600"></i>
                        Contact Information
                    </h2>

                    <div class="space-y-4 text-gray-700">
                        <p class="text-center text-gray-500 italic">
                            <i class="fas fa-info-circle mr-2"></i>
                            Contact your local barangay office for SK Council information
                        </p>
                    </div>
                </div>
            </div>

            <!-- Officers Section -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-500"></i>
                    Council Officers
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Chairperson -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-yellow-500">
                        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 px-6 py-4 text-center">
                            <p class="text-white font-semibold text-lg">Chairperson</p>
                        </div>
                        <div class="p-6 text-center">
                            @if($skCouncil->chairperson)
                                <div class="mb-4">
                                    @if($skCouncil->chairperson->photo)
                                        <img src="{{ asset('storage/' . $skCouncil->chairperson->photo) }}"
                                            alt="{{ $skCouncil->chairperson->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-yellow-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-yellow-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $skCouncil->chairperson->full_name ?? ($skCouncil->chairperson->first_name . ' ' . $skCouncil->chairperson->last_name) }}</h3>
                                @if($skCouncil->chairperson->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-phone"></i>
                                        {{ $skCouncil->chairperson->contact_number }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Secretary -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-green-500">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 text-center">
                            <p class="text-white font-semibold text-lg">Secretary</p>
                        </div>
                        <div class="p-6 text-center">
                            @if($skCouncil->secretary)
                                <div class="mb-4">
                                    @if($skCouncil->secretary->photo)
                                        <img src="{{ asset('storage/' . $skCouncil->secretary->photo) }}"
                                            alt="{{ $skCouncil->secretary->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-green-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-green-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $skCouncil->secretary->full_name ?? ($skCouncil->secretary->first_name . ' ' . $skCouncil->secretary->last_name) }}</h3>
                                @if($skCouncil->secretary->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-phone"></i>
                                        {{ $skCouncil->secretary->contact_number }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Treasurer -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-red-500">
                        <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4 text-center">
                            <p class="text-white font-semibold text-lg">Treasurer</p>
                        </div>
                        <div class="p-6 text-center">
                            @if($skCouncil->treasurer)
                                <div class="mb-4">
                                    @if($skCouncil->treasurer->photo)
                                        <img src="{{ asset('storage/' . $skCouncil->treasurer->photo) }}"
                                            alt="{{ $skCouncil->treasurer->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-red-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-red-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $skCouncil->treasurer->full_name ?? ($skCouncil->treasurer->first_name . ' ' . $skCouncil->treasurer->last_name) }}</h3>
                                @if($skCouncil->treasurer->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-phone"></i>
                                        {{ $skCouncil->treasurer->contact_number }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Kagawad (Council Member) - Show first kagawad or placeholder -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-blue-500">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-4 text-center">
                            <p class="text-white font-semibold text-lg">Kagawad</p>
                        </div>
                        <div class="p-6 text-center">
                            @if($kagawads && count($kagawads) > 0)
                                @php $firstKagawad = $kagawads->first(); @endphp
                                <div class="mb-4">
                                    @if($firstKagawad->photo)
                                        <img src="{{ asset('storage/' . $firstKagawad->photo) }}"
                                            alt="{{ $firstKagawad->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-blue-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-blue-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $firstKagawad->full_name ?? ($firstKagawad->first_name . ' ' . $firstKagawad->last_name) }}</h3>
                                @if(count($kagawads) > 1)
                                    <p class="text-sm text-gray-600">+{{ count($kagawads) - 1 }} more member{{ count($kagawads) > 2 ? 's' : '' }}</p>
                                @endif
                                @if($firstKagawad->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1 mt-2">
                                        <i class="fas fa-phone"></i>
                                        {{ $firstKagawad->contact_number }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 italic">No kagawads assigned</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kagawads (Council Members) Section -->
            @if($kagawads && count($kagawads) > 0)
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                        <i class="fas fa-users text-blue-600"></i>
                        Council Members ({{ count($kagawads) }})
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($kagawads as $kagawad)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-600">
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        @if($kagawad->photo)
                                            <img src="{{ asset('storage/' . $kagawad->photo) }}"
                                                alt="{{ $kagawad->full_name }}"
                                                class="w-16 h-16 rounded-full object-cover border-2 border-blue-600 flex-shrink-0">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center border-2 border-blue-600 flex-shrink-0">
                                                <i class="fas fa-user text-xl text-gray-600"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                {{ $kagawad->full_name ?? ($kagawad->first_name . ' ' . $kagawad->last_name) }}
                                            </h3>
                                            <p class="text-sm text-gray-600">Council Member</p>
                                            @if($kagawad->contact_number)
                                                <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                                                    <i class="fas fa-phone"></i>
                                                    {{ $kagawad->contact_number }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Back to Events Button -->
            <div class="text-center">
                <a href="{{ route('public.events.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-calendar-alt"></i>
                    View Events from this Council
                </a>
            </div>
        </div>
    </div>
@endsection
