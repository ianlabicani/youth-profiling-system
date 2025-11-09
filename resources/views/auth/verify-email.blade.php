@extends('shell')

@section('title', 'Verify Email')

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Church Logo" class="h-20 w-20 mx-auto rounded-full object-cover shadow-lg">
                </a>
                <h2 class="mt-6 text-4xl font-bold text-gray-800">Verify Email</h2>
                <p class="mt-2 text-gray-600">Check your inbox for verification</p>
            </div>

            <!-- Verify Email Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="mb-6 text-sm text-gray-600">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <div class="flex flex-col gap-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition transform hover:scale-105">
                            Resend Verification Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-200 transition">
                            Log Out
                        </button>
                    </form>
                </div>

                <!-- Divider -->
                <div class="mt-6 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">or</span>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="mt-6">
                    <a href="{{ url('/') }}"
                       class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left"></i>
                        Back to Home
                    </a>
                </div>
            </div>

            <!-- Footer Note -->
            <p class="mt-8 text-center text-sm text-gray-600">
                Protected by SJDPP Church Security
            </p>
        </div>
    </div>

@endsection
