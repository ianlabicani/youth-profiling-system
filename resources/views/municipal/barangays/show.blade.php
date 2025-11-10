@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('municipal.barangays.index') }}" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $barangay->name }}</h1>
            <p class="text-gray-600 mt-1">Barangay Details</p>
        </div>
    </div>

    <!-- Barangay Card -->
    <div class="bg-white rounded-lg shadow p-6 md:p-8 max-w-2xl">
        <div class="space-y-4">
            <!-- ID -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">ID</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->id }}</p>
            </div>

            <!-- Name -->
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Barangay Name</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->name }}</p>
            </div>

            <!-- Administrator Account -->
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Administrator Account</h3>
                @php
                    $admin = $barangay->users()->first();
                @endphp
                @if($admin)
                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-600">Account Name</p>
                                <p class="text-base font-medium text-gray-800">{{ $admin->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Email</p>
                                <p class="text-base font-medium text-gray-800">{{ $admin->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Status</p>
                                <p class="text-base font-medium">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-exclamation-circle mr-2"></i>No administrator account assigned
                        </p>
                    </div>
                @endif
            </div>

            <!-- Created At -->
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Created</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->created_at->format('M d, Y H:i A') }}</p>
            </div>

            <!-- Updated At -->
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Last Updated</h3>
                <p class="text-lg text-gray-800 mt-1">{{ $barangay->updated_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-8 border-t mt-8">
            <a
                href="{{ route('municipal.barangays.edit', $barangay) }}"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a
                href="{{ route('municipal.barangays.index') }}"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Youths and SK Council Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Youths Card -->
        <a href="{{ route('municipal.barangays.youths', $barangay) }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Youths</h3>
                    <p class="text-sm text-gray-600">Manage youth records</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
            </div>
        </a>

        <!-- SK Council Card -->
        <a href="{{ route('municipal.barangays.sk-councils.index', $barangay) }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-crown text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">SK Council</h3>
                    <p class="text-sm text-gray-600">Manage SK council</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
            </div>
        </a>
    </div>
</div>

@endsection
