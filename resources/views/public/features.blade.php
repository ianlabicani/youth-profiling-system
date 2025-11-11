@extends('public.shell')

@section('title', 'Features - Youth Digital Profiling System')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-16 text-center">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Our Features</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Comprehensive tools designed to support youth development and community engagement
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Feature 1: Youth Profiling -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-8 border-t-4 border-blue-600">
                    <div class="flex items-center justify-center w-14 h-14 bg-blue-100 rounded-lg mb-6">
                        <i class="fas fa-user-circle text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Youth Profiling</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Comprehensive digital profiles of youth members including contact information, skills,
                        educational attainment, and location data for better community engagement.
                    </p>
                </div>

                <!-- Feature 2: Organization Management -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-8 border-t-4 border-purple-600">
                    <div class="flex items-center justify-center w-14 h-14 bg-purple-100 rounded-lg mb-6">
                        <i class="fas fa-sitemap text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Organization Structure</h3>
                    <p class="text-gray-600 leading-relaxed">
                        View and manage youth organizations with their complete structure including officers,
                        committee heads, and members for better organizational coordination.
                    </p>
                </div>

                <!-- Feature 3: SK Council Information -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-8 border-t-4 border-green-600">
                    <div class="flex items-center justify-center w-14 h-14 bg-green-100 rounded-lg mb-6">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">SK Councils</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Access Sangguniang Kabataan council information including officers, members, and
                        contact details for direct community engagement and support.
                    </p>
                </div>

                <!-- Feature 4: Event Management -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-8 border-t-4 border-orange-600">
                    <div class="flex items-center justify-center w-14 h-14 bg-orange-100 rounded-lg mb-6">
                        <i class="fas fa-calendar-alt text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Event Tracking</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Discover and track youth events organized by barangay and SK councils. Filter by date,
                        location, and organizer to stay informed about community activities.
                    </p>
                </div>

                <!-- Feature 5: Advanced Search -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-8 border-t-4 border-red-600">
                    <div class="flex items-center justify-center w-14 h-14 bg-red-100 rounded-lg mb-6">
                        <i class="fas fa-search text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Advanced Search</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Powerful search and filtering capabilities to quickly find organizations, councils,
                        events, and youth members across the municipality.
                    </p>
                </div>

                <!-- Feature 6: Accessible Information -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-8 border-t-4 border-teal-600">
                    <div class="flex items-center justify-center w-14 h-14 bg-teal-100 rounded-lg mb-6">
                        <i class="fas fa-globe text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Accessible Information</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Publicly available information to help youth, parents, and community members stay
                        connected with local youth organizations and initiatives.
                    </p>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-12 mb-16 text-white">
                <h2 class="text-3xl font-bold mb-8 text-center">Benefits</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 bg-opacity-50">
                                <i class="fas fa-check text-2xl"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Enhanced Community Engagement</h3>
                            <p class="text-blue-100">
                                Connect youth with local organizations and opportunities in real-time
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 bg-opacity-50">
                                <i class="fas fa-check text-2xl"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Data-Driven Insights</h3>
                            <p class="text-blue-100">
                                Better understanding of youth demographics and organizational structures
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 bg-opacity-50">
                                <i class="fas fa-check text-2xl"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Easy Information Access</h3>
                            <p class="text-blue-100">
                                Centralized platform for finding youth services and contact information
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 bg-opacity-50">
                                <i class="fas fa-check text-2xl"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Support Youth Development</h3>
                            <p class="text-blue-100">
                                Foster growth and empowerment of youth in the community
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Ready to Get Started?</h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.organizations.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-sitemap"></i>
                        Explore Organizations
                    </a>
                    <a href="{{ route('public.events.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition font-medium">
                        <i class="fas fa-calendar-alt"></i>
                        View Events
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
