@extends('public.shell')

@section('title', 'Contact Us - Youth Digital Profiling System')

@section('public-content')

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-16 text-center">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Contact Us</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Have questions? We'd love to hear from you. Get in touch with us today.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                <!-- Contact Information -->
                <div class="lg:col-span-1">
                    <!-- Phone -->
                    <div class="bg-white rounded-lg shadow-md p-8 mb-6 border-l-4 border-blue-600">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <i class="fas fa-phone text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Phone</h3>
                        </div>
                        <p class="text-gray-600">
                            <a href="tel:+639123456789" class="hover:text-blue-600 transition">
                                +63 912 345 6789
                            </a>
                        </p>
                        <p class="text-sm text-gray-500 mt-2">Available Monday - Friday, 9:00 AM - 5:00 PM</p>
                    </div>

                    <!-- Email -->
                    <div class="bg-white rounded-lg shadow-md p-8 mb-6 border-l-4 border-purple-600">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                                <i class="fas fa-envelope text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                        </div>
                        <p class="text-gray-600">
                            <a href="mailto:info@youthprofiling.local" class="hover:text-blue-600 transition break-all">
                                info@youthprofiling.local
                            </a>
                        </p>
                        <p class="text-sm text-gray-500 mt-2">We'll respond within 24 hours</p>
                    </div>

                    <!-- Location -->
                    <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-green-600">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                                <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Office Location</h3>
                        </div>
                        <p class="text-gray-600">
                            Municipal Government Center<br>
                            Camalaniugan, Cagayan<br>
                            Philippines
                        </p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-blue-600">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>

                        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Your full name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    required
                                >
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="your.email@example.com"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                    required
                                >
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    placeholder="What is this about?"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                                    required
                                >
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Message <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="6"
                                    placeholder="Your message here..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror"
                                    required
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-4">
                                <button
                                    type="submit"
                                    class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2"
                                >
                                    <i class="fas fa-paper-plane"></i>
                                    Send Message
                                </button>
                                <button
                                    type="reset"
                                    class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition"
                                >
                                    Clear
                                </button>
                            </div>

                            <!-- Success Message -->
                            @if (session('success'))
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-check-circle mt-0.5"></i>
                                        <div>
                                            <p class="font-semibold">Message Sent!</p>
                                            <p class="text-sm">Thank you for contacting us. We'll get back to you soon.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Frequently Asked Questions</h2>

                <div class="space-y-6">
                    <!-- FAQ 1 -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <details class="cursor-pointer">
                            <summary class="flex items-center justify-between font-semibold text-gray-900">
                                <span>How can I find youth organizations in my barangay?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </summary>
                            <p class="mt-4 text-gray-600 leading-relaxed">
                                Visit our Organizations page and use the search feature to find organizations by name or description.
                                You can also browse all organizations by barangay to see the complete youth structure in your area.
                            </p>
                        </details>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <details class="cursor-pointer">
                            <summary class="flex items-center justify-between font-semibold text-gray-900">
                                <span>Can I find information about SK Councils?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </summary>
                            <p class="mt-4 text-gray-600 leading-relaxed">
                                Yes! Visit the Councils page to view all active SK Councils. You can see the council officers, members,
                                and other important information. Filter by barangay to find your local council.
                            </p>
                        </details>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <details class="cursor-pointer">
                            <summary class="flex items-center justify-between font-semibold text-gray-900">
                                <span>How can I stay updated on youth events?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </summary>
                            <p class="mt-4 text-gray-600 leading-relaxed">
                                Check our Events page regularly to see upcoming events organized by youth organizations and SK Councils.
                                You can filter by date range, location, and organizer to find events relevant to you.
                            </p>
                        </details>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <details class="cursor-pointer">
                            <summary class="flex items-center justify-between font-semibold text-gray-900">
                                <span>Is my personal information secure?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </summary>
                            <p class="mt-4 text-gray-600 leading-relaxed">
                                We take data security seriously. Personal information displayed is publicly available information that has been
                                approved for display. For more details, please refer to our Privacy Policy.
                            </p>
                        </details>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <details class="cursor-pointer">
                            <summary class="flex items-center justify-between font-semibold text-gray-900">
                                <span>How do I report incorrect information?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </summary>
                            <p class="mt-4 text-gray-600 leading-relaxed">
                                If you find any incorrect information, please contact us using the form above or call our office directly.
                                We'll investigate and update the information promptly.
                            </p>
                        </details>
                    </div>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 px-6 py-3 text-gray-700 hover:text-blue-600 transition font-medium">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

@endsection
