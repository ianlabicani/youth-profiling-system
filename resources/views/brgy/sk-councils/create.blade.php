@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('brgy.sk-councils.index') }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Create SK Council</h1>
            </div>
            <p class="text-gray-600 ml-10">Create a new Sangguniang Kabataan council for {{ $userBarangay->name }}</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if($skMembers->isEmpty())
            <!-- No SK Members Warning -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-yellow-800 mb-2">No SK Members Available</h3>
                        <p class="text-yellow-700 mb-3">You need to register SK members first before creating an SK Council.</p>
                        <a href="{{ route('brgy.youth.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            <i class="fas fa-user-plus"></i>
                            <span>Register Youth</span>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('brgy.sk-councils.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Barangay (Read-only) -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Council Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                                <input
                                    type="text"
                                    value="{{ $userBarangay->name }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                    readonly
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Term *</label>
                                <input
                                    type="text"
                                    name="term"
                                    value="{{ old('term') }}"
                                    placeholder="e.g., 2023-2026"
                                    class="w-full px-4 py-2 border @error('term') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                >
                                @error('term')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Officers -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">SK Officers</h3>

                        <div class="space-y-4">
                            <!-- Chairperson -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-crown text-purple-600 mr-1"></i>Chairperson *
                                </label>
                                <select
                                    name="chairperson_id"
                                    class="w-full px-4 py-2 border @error('chairperson_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                >
                                    <option value="">Select Chairperson...</option>
                                    @foreach($skMembers as $member)
                                        <option value="{{ $member->id }}" {{ old('chairperson_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->first_name }}
                                            @if($member->middle_name)
                                                {{ substr($member->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $member->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chairperson_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Secretary -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-pen text-blue-600 mr-1"></i>Secretary
                                </label>
                                <select
                                    name="secretary_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="">Select Secretary...</option>
                                    @foreach($skMembers as $member)
                                        <option value="{{ $member->id }}" {{ old('secretary_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->first_name }}
                                            @if($member->middle_name)
                                                {{ substr($member->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $member->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Treasurer -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-coins text-green-600 mr-1"></i>Treasurer
                                </label>
                                <select
                                    name="treasurer_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="">Select Treasurer...</option>
                                    @foreach($skMembers as $member)
                                        <option value="{{ $member->id }}" {{ old('treasurer_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->first_name }}
                                            @if($member->middle_name)
                                                {{ substr($member->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $member->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad Members -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users text-indigo-600 mr-1"></i>Kagawad (Council Members)
                        </label>
                        <p class="text-sm text-gray-600 mb-3">Select multiple members by holding Ctrl (Windows) or Cmd (Mac)</p>

                        <select
                            name="kagawad_ids[]"
                            multiple
                            size="8"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            @foreach($skMembers as $member)
                                <option value="{{ $member->id }}" {{ in_array($member->id, old('kagawad_ids', [])) ? 'selected' : '' }}>
                                    {{ $member->first_name }}
                                    @if($member->middle_name)
                                        {{ substr($member->middle_name, 0, 1) }}.
                                    @endif
                                    {{ $member->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Optional: You can assign kagawad members now or later</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6">
                        <button
                            type="submit"
                            class="flex-1 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition"
                        >
                            <i class="fas fa-save mr-2"></i>Create SK Council
                        </button>
                        <a
                            href="{{ route('brgy.sk-councils.index') }}"
                            class="flex-1 px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition text-center"
                        >
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Tips:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Only active SK members from {{ $userBarangay->name }} are shown in the lists</li>
                            <li>The same person cannot hold multiple positions</li>
                            <li>You can edit the council composition later if needed</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
