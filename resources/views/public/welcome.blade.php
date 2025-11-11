@extends('public.shell')

@section('title', 'Welcome to the Youth Profiling System')


@section('public-content')


    <section id="hero" class="relative overflow-hidden pt-10 pb-20">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-blue-50 via-white to-blue-50"></div>
        <div class="pointer-events-none select-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-blue-200 blur-3xl opacity-40"></div>
        <div class="pointer-events-none select-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-cyan-200 blur-3xl opacity-40"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-blue-800 leading-tight mb-4 max-w-5xl mx-auto">
                Data-Driven Governance. Empowering SK Officials.
            </h1>
            <p class="text-base md:text-lg text-gray-700 mb-6 max-w-3xl mx-auto">
                Centralize youth data (15-30 years old), generate real-time reports, and boost program effectiveness—securely and efficiently.
            </p>

        </div>
    </section>

    <section id="events" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800">Upcoming Events</h2>
                <p class="mt-4 text-lg text-gray-600">Stay updated with our latest youth programs and activities.</p>
            </div>

            <!-- Upcoming Events -->
            @if($upcomingEvents->isNotEmpty())
                <div class="mb-16">
                    <h3 class="text-2xl font-semibold text-blue-700 mb-8 flex items-center gap-2">
                        <i class="fas fa-arrow-right text-blue-600"></i>
                        Upcoming Events
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($upcomingEvents as $event)
                            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-green-600">
                                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                                    <h4 class="text-lg font-bold text-white line-clamp-2">{{ $event->title }}</h4>
                                </div>
                                <div class="p-6 space-y-3">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-calendar text-green-600"></i>
                                        <span>{{ $event->date->format('M d, Y') }}</span>
                                    </div>
                                    @if($event->time)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fas fa-clock text-green-600"></i>
                                            <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}</span>
                                        </div>
                                    @endif
                                    @if($event->venue)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fas fa-map-marker-alt text-green-600"></i>
                                            <span>{{ $event->venue }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-map-pin text-green-600"></i>
                                        <span>{{ $event->skCouncil?->barangay?->name ?? 'Barangay TBA' }}</span>
                                    </div>
                                    @if($event->description)
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $event->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Today's Events -->
            @if($todayEvents->isNotEmpty())
                <div class="mb-16">
                    <h3 class="text-2xl font-semibold text-blue-700 mb-8 flex items-center gap-2">
                        <i class="fas fa-clock text-blue-600"></i>
                        Today's Events
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($todayEvents as $event)
                            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-blue-600">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                                    <h4 class="text-lg font-bold text-white line-clamp-2">{{ $event->title }}</h4>
                                </div>
                                <div class="p-6 space-y-3">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-calendar text-blue-600"></i>
                                        <span>{{ $event->date->format('M d, Y') }}</span>
                                    </div>
                                    @if($event->time)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fas fa-clock text-blue-600"></i>
                                            <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}</span>
                                        </div>
                                    @endif
                                    @if($event->venue)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fas fa-map-marker-alt text-blue-600"></i>
                                            <span>{{ $event->venue }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-map-pin text-blue-600"></i>
                                        <span>{{ $event->skCouncil?->barangay?->name ?? 'Barangay TBA' }}</span>
                                    </div>
                                    @if($event->description)
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $event->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Past Events -->
            @if($pastEvents->isNotEmpty())
                <div class="mb-16">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-8 flex items-center gap-2">
                        <i class="fas fa-history text-gray-600"></i>
                        Past Events
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pastEvents as $event)
                            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-gray-400">
                                <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                                    <h4 class="text-lg font-bold text-white line-clamp-2">{{ $event->title }}</h4>
                                </div>
                                <div class="p-6 space-y-3">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-calendar text-gray-600"></i>
                                        <span>{{ $event->date->format('M d, Y') }}</span>
                                    </div>
                                    @if($event->time)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fas fa-clock text-gray-600"></i>
                                            <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}</span>
                                        </div>
                                    @endif
                                    @if($event->venue)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fas fa-map-marker-alt text-gray-600"></i>
                                            <span>{{ $event->venue }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-map-pin text-gray-600"></i>
                                        <span>{{ $event->skCouncil?->barangay?->name ?? 'Barangay TBA' }}</span>
                                    </div>
                                    @if($event->description)
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $event->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- View All Events Button -->
            <div class="text-center mt-12">
                <a href="{{ route('public.events.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-calendar-alt"></i>
                    View All Events
                </a>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-16">Features & Benefits</h2>
            <div class="grid md:grid-cols-4 gap-8">
                <div class="p-8 bg-white shadow-lg rounded-xl border border-gray-100">
                    <i class="fas fa-lock text-4xl text-blue-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-3">Data Privacy & Security</h3>
                    <p class="text-gray-600 text-sm">Ensures Data Privacy Act (RA 10173) compliance with multi-level access controls to protect sensitive youth information.</p>
                </div>
                <div class="p-8 bg-white shadow-lg rounded-xl border border-gray-100">
                    <i class="fas fa-chart-line text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-3">Automated Report</h3>
                    <p class="text-gray-600 text-sm">Instantly generate reports based on demographics, skills, and participation, enabling data-driven governance easily.</p>
                </div>
                <div class="p-8 bg-white shadow-lg rounded-xl border border-gray-100">
                    <i class="fas fa-user-check text-4xl text-red-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-3">Streamlined Registration</h3>
                    <p class="text-gray-600 text-sm">Easy youth self-registration allows for quick data input and updates, reducing administrative workload for SK.</p>
                </div>
                <div class="p-8 bg-white shadow-lg rounded-xl border border-gray-100">
                    <i class="fas fa-mobile-alt text-4xl text-purple-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-3">Web-Based & Accessible</h3>
                    <p class="text-gray-600 text-sm">Responsive design ensures the system works perfectly on mobile, tablet, or desktop across all barangays.</p>
                </div>
            </div>
        </div>
    </section>


@endsection
