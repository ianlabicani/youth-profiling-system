@extends('shell')

@section('title', 'Youth Digital Profiling System')
@section('content')

    <header class="sticky top-0 z-50 backdrop-blur bg-white/70 border-b">
        <div class="max-w-7xl mx-auto flex justify-between items-center h-20 px-4 sm:px-6 lg:px-8 ">
            <div class="flex items-center gap-3">
                <x-application-logo class="h-9 w-9" />
                <span class="text-xl font-extrabold tracking-tight text-gray-900">Youth Digital Profiling</span>
            </div>
            <nav class="hidden md:flex items-center gap-8 text-gray-600 font-medium">
                <a href="{{ route('welcome') }}" class="hover:text-blue-600 transition">Home</a>
                <a href="{{ route('public.events.index') }}" class="hover:text-blue-600 transition">Events</a>
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

    <div class="px-4 sm:px-6 lg:px-8 ">
        @yield('public-content')
    </div>

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
