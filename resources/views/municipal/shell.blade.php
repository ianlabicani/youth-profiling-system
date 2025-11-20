@extends('shell')

@section('title', 'Municipal Dashboard')
@section('content')
    <!-- Sidebar -->
    <div id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="flex flex-col h-full">
            <!-- Logo -->
           <a href="{{ route('welcome') }}">
             <div class="flex items-center space-x-2 p-4 border-b">
                <img src="{{ asset('images/youth-logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full object-cover">
                <div>
                    <span class="text-lg font-bold text-gray-800">Municipal Youth Profiling</span>
                    <p class="text-xs text-gray-600">Municipal Dashboard</p>
                </div>
            </div>
           </a>

            <!-- Nav Items -->
            <nav class="flex-1 px-4 py-4 space-y-2">
                <a href="{{ route('municipal.dashboard') }}" class="flex items-center px-4 py-2 {{ request()->is('municipal/dashboard') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>

                <a href="{{ route('municipal.accounts.index') }}" class="flex items-center px-4 py-2 {{ request()->is('municipal/accounts*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>Accounts
                </a>

                <a href="{{ route('municipal.barangays.index') }}" class="flex items-center px-4 py-2 {{ request()->is('municipal/barangays*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-map-pin mr-3"></i>Barangays
                </a>
                <a href="{{ route('municipal.youths.index') }}" class="flex items-center px-4 py-2 {{ request()->is('municipal/youths*') && !request()->is('municipal/youths/out-of-school*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>Youth
                </a>

                <a href="{{ route('municipal.youths.out-of-school') }}" class="flex items-center px-4 py-2 {{ request()->is('municipal/youths/out-of-school*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-graduation-cap mr-3"></i>Out of School
                </a>

                <a href="{{ route('municipal.organizations.index') }}" class="flex items-center px-4 py-2 {{ request()->is('municipal/organizations*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-building mr-3"></i>Organizations
                </a>

                <a href="{{ route('municipal.heatmap') }}" class="flex items-center px-4 py-2 {{ request()->is('municipal/heatmap') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-map mr-3"></i>Heatmap
                </a>

                <a href="{{ route('municipal.reports.index') }}" class="flex items-center px-4 py-2 {{ request()->is('reports*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} rounded-lg transition">
                    <i class="fas fa-chart-bar mr-3"></i>Reports
                </a>

            </nav>

            <!-- Profile Dropdown at bottom -->
            <div class="border-t p-4">
                <div class="relative">
                    <button id="profile-btn" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                        <i class="fas fa-user-circle mr-3"></i>{{ auth()->user()->name }}
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </button>

                    <div id="profile-menu" class="hidden absolute bottom-full left-0 w-full bg-white rounded-lg shadow-xl border border-gray-200 mb-2">
                        <div class="py-2">
                            <a href="/municipal/profile/edit" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-user-cog w-5 mr-3 text-blue-600"></i>
                                <span class="font-medium">Profile Settings</span>
                            </a>
                            <div class="border-t border-gray-200 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                    <i class="fas fa-sign-out-alt w-5 mr-3 text-red-600"></i>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg text-gray-700 hover:bg-gray-100 transition">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const profileBtn = document.getElementById('profile-btn');
            const profileMenu = document.getElementById('profile-menu');

            menuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });

            profileBtn.addEventListener('click', function() {
                profileMenu.classList.toggle('hidden');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target) && window.innerWidth < 768) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>

    <div class="md:ml-64 p-4 mt-16 md:mt-0 mb-10">
        @yield('municipal-content')
    </div>
@endsection
