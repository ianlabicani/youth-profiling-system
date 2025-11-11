@extends('public.shell')

@section('title', $organization->name . ' - Youth Digital Profiling System')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('public.organizations.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Organizations</span>
                </a>
            </div>

            <!-- Organization Info Card -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8 border-l-4 border-blue-600">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">
                            {{ $organization->name ?? "Organization #" . $organization->id }}
                        </h1>
                    </div>
                    <div>
                        @if($organization->description)
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-2">Description</p>
                                <p class="text-gray-700 leading-relaxed">{{ $organization->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Officers Section -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-500"></i>
                    Organization Officers
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- President -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-yellow-500">
                        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 px-6 py-4 text-center">
                            <p class="text-white font-semibold text-lg">President</p>
                        </div>
                        <div class="p-6 text-center">
                            @if($organization->president)
                                <div class="mb-4">
                                    @if($organization->president->photo)
                                        <img src="{{ asset('storage/' . $organization->president->photo) }}"
                                            alt="{{ $organization->president->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-yellow-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-yellow-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $organization->president->full_name ?? ($organization->president->first_name . ' ' . $organization->president->last_name) }}</h3>
                                @if($organization->president->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-phone"></i>
                                        {{ $organization->president->contact_number }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Vice President -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-blue-500">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-4 text-center">
                            <p class="text-white font-semibold text-lg">Vice President</p>
                        </div>
                        <div class="p-6 text-center">
                            @if($organization->vicePresident)
                                <div class="mb-4">
                                    @if($organization->vicePresident->photo)
                                        <img src="{{ asset('storage/' . $organization->vicePresident->photo) }}"
                                            alt="{{ $organization->vicePresident->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-blue-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-blue-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $organization->vicePresident->full_name ?? ($organization->vicePresident->first_name . ' ' . $organization->vicePresident->last_name) }}</h3>
                                @if($organization->vicePresident->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-phone"></i>
                                        {{ $organization->vicePresident->contact_number }}
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
                            @if($organization->secretary)
                                <div class="mb-4">
                                    @if($organization->secretary->photo)
                                        <img src="{{ asset('storage/' . $organization->secretary->photo) }}"
                                            alt="{{ $organization->secretary->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-green-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-green-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $organization->secretary->full_name ?? ($organization->secretary->first_name . ' ' . $organization->secretary->last_name) }}</h3>
                                @if($organization->secretary->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-phone"></i>
                                        {{ $organization->secretary->contact_number }}
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
                            @if($organization->treasurer)
                                <div class="mb-4">
                                    @if($organization->treasurer->photo)
                                        <img src="{{ asset('storage/' . $organization->treasurer->photo) }}"
                                            alt="{{ $organization->treasurer->full_name }}"
                                            class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-red-500">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-red-500">
                                            <i class="fas fa-user text-2xl text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $organization->treasurer->full_name }}</h3>
                                @if($organization->treasurer->contact_number)
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-phone"></i>
                                        {{ $organization->treasurer->contact_number }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 italic">Not assigned</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Committee Heads Section -->
            @if(!empty($committeeHeads) && count($committeeHeads) > 0)
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                        <i class="fas fa-users-cog text-purple-600"></i>
                        Committee Heads
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($committeeHeads as $committee)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-purple-600">
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        @if($committee['head'] && $committee['head']->photo)
                                            <img src="{{ asset('storage/' . $committee['head']->photo) }}"
                                                alt="{{ $committee['head']->full_name }}"
                                                class="w-16 h-16 rounded-full object-cover border-2 border-purple-600 flex-shrink-0">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center border-2 border-purple-600 flex-shrink-0">
                                                <i class="fas fa-user text-xl text-gray-600"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                {{ $committee['head']->full_name ?? 'N/A' }}
                                            </h3>
                                            <p class="text-sm text-gray-600">{{ $committee['name'] ?? 'Committee Head' }}</p>
                                            @if($committee['head'] && $committee['head']->contact_number)
                                                <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                                                    <i class="fas fa-phone"></i>
                                                    {{ $committee['head']->contact_number }}
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
        </div>
    </div>
@endsection
