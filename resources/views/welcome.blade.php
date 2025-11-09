@extends('shell')

@section('title', 'Welcome to the Youth Profiling System')


@section('content')

    <header class="sticky top-0 z-50 backdrop-blur bg-white/70 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <div class="flex items-center gap-3">
                <x-application-logo class="h-9 w-9" />
                <span class="text-xl font-extrabold tracking-tight text-gray-900">Youth Digital Profiling</span>
            </div>
            <nav class="hidden md:flex items-center gap-8 text-gray-600 font-medium">
                <a href="#" class="hover:text-blue-600 transition">Home</a>
                <a href="#events" class="hover:text-blue-600 transition">Events</a>
                <a href="#features" class="hover:text-blue-600 transition">Features</a>
                <a href="#contact" class="hover:text-blue-600 transition">Contact</a>
            </nav>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>SK Login</span>
                </a>
            </div>
        </div>
    </header>

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

            <!-- Events Slider -->
            <div class="relative">
                <div class="overflow-hidden">
                    <div id="events-slider" class="flex transition-transform duration-500 ease-out will-change-transform">
                        <div class="w-full md:w-1/2 lg:w-1/3 shrink-0 px-4">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <img src="https://placehold.co/600x400" alt="Youth Leadership Workshop" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Youth Leadership Workshop</h3>
                                    <p class="text-gray-600 mb-4">Learn essential leadership skills and team building in this interactive workshop.</p>
                                    <p class="text-blue-600 font-medium">Dec 15, 2025</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 lg:w-1/3 shrink-0 px-4">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <img src="https://placehold.co/600x400" alt="Community Service Day" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Community Service Day</h3>
                                    <p class="text-gray-600 mb-4">Join us for a day of community service and making a positive impact in our barangay.</p>
                                    <p class="text-blue-600 font-medium">Jan 20, 2026</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 lg:w-1/3 shrink-0 px-4">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <img src="https://placehold.co/600x400" alt="Sports Tournament" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Sports Tournament</h3>
                                    <p class="text-gray-600 mb-4">Annual youth sports tournament featuring various games and competitions.</p>
                                    <p class="text-blue-600 font-medium">Feb 10, 2026</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


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

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm space-y-4 md:space-y-0">
                <div class="flex space-x-6">
                    <span class="font-semibold">Quick links</span>
                    <a href="#" class="hover:text-blue-400">Home</a>
                    <a href="#features" class="hover:text-blue-400">Features</a>
                    <a href="#" class="hover:text-blue-400">Contact</a>
                </div>
                <div class="flex space-x-6">
                    <i class="fas fa-file-contract text-blue-400"></i>
                    <a href="#" class="hover:text-blue-400">Privacy Policy</a>
                    <i class="fas fa-handshake text-blue-400"></i>
                    <a href="#" class="hover:text-blue-400">Terms of Service</a>
                </div>
            </div>
            <div class="mt-6 border-t border-gray-700 pt-4 text-center">
                <p class="text-xs text-gray-400">
                    © 2025 Youth Digital Profiling System. Developed by Cagayan State University.
                </p>
            </div>
        </div>
    </footer>
@endsection
