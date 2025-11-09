@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('municipal.accounts.index') }}" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Barangay Account</h1>
            <p class="text-gray-600 mt-1">Update barangay admin account details</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6 md:p-8 max-w-2xl">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <p class="font-medium">Please fix the following errors:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('municipal.accounts.update', $account) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $account->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="e.g., Juan Dela Cruz"
                    required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $account->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="e.g., admin@barangay.com"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field (Optional for updates) -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    New Password <span class="text-gray-500 text-sm">(Leave blank to keep current password)</span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Minimum 8 characters">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Must be at least 8 characters long if you want to change it</p>
            </div>

            <!-- Password Confirmation Field -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm New Password
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Re-enter your new password">
            </div>

            <!-- Barangay Assignment Field -->
            <div class="border-t pt-6">
                <label for="barangay_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Assigned Barangay
                </label>
                <select
                    id="barangay_id"
                    name="barangay_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- No Assignment --</option>
                    @foreach ($barangays as $barangay)
                        <option value="{{ $barangay->id }}" {{ $assignedBarangay?->id === $barangay->id ? 'selected' : '' }}>
                            {{ $barangay->name }}
                        </option>
                    @endforeach
                </select>
                @error('barangay_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">
                    @if ($assignedBarangay)
                        Currently assigned to: <strong>{{ $assignedBarangay->name }}</strong>
                    @else
                        This account is not assigned to any barangay
                    @endif
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a
                    href="{{ route('municipal.accounts.index') }}"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold text-red-600 mb-3">Danger Zone</h3>
            <p class="text-gray-600 text-sm mb-3">Delete this account permanently</p>
            <form action="{{ route('municipal.accounts.destroy', $account) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
                    onclick="return confirm('Are you sure you want to delete this account? This action cannot be undone.')">
                    <i class="fas fa-trash mr-2"></i>Delete Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
