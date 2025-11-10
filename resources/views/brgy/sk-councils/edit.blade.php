@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('brgy.sk-councils.show', $skCouncil) }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit SK Council</h1>
            </div>
            <p class="text-gray-600 ml-10">Update Sangguniang Kabataan council information</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-3 mt-0.5"></i>
                    <div>
                        <p class="font-medium">Please correct the following errors:</p>
                        <ul class="mt-2 text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Council Information</h2>
            </div>

            <form action="{{ route('brgy.sk-councils.update', $skCouncil) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Barangay (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>Barangay
                    </label>
                    <input type="text" value="{{ $userBarangay->name }}" readonly
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 font-medium cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Council is automatically associated with your barangay</p>
                </div>

                <!-- Term -->
                <div>
                    <label for="term" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>Term <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="term" id="term"
                           value="{{ old('term', $skCouncil->term) }}"
                           class="w-full px-4 py-3 border @error('term') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="e.g., 2023-2026"
                           required>
                    @error('term')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">Format: YYYY-YYYY (e.g., 2023-2026)</p>
                </div>

                <!-- SK Officers Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-tie text-purple-600"></i>
                        SK Officers
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Chairperson -->
                        <div>
                            <label for="chairperson_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-crown mr-1 text-purple-600"></i>Chairperson <span class="text-red-500">*</span>
                            </label>
                            <select name="chairperson_id" id="chairperson_id"
                                    class="w-full px-4 py-3 border @error('chairperson_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                    required>
                                <option value="">Select Chairperson</option>
                                @foreach($skMembers as $member)
                                    <option value="{{ $member->id }}"
                                            {{ old('chairperson_id', $skCouncil->chairperson_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('chairperson_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Secretary -->
                        <div>
                            <label for="secretary_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-pen mr-1 text-blue-600"></i>Secretary
                            </label>
                            <select name="secretary_id" id="secretary_id"
                                    class="w-full px-4 py-3 border @error('secretary_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select Secretary (Optional)</option>
                                @foreach($skMembers as $member)
                                    <option value="{{ $member->id }}"
                                            {{ old('secretary_id', $skCouncil->secretary_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('secretary_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Treasurer -->
                        <div>
                            <label for="treasurer_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-coins mr-1 text-green-600"></i>Treasurer
                            </label>
                            <select name="treasurer_id" id="treasurer_id"
                                    class="w-full px-4 py-3 border @error('treasurer_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select Treasurer (Optional)</option>
                                @foreach($skMembers as $member)
                                    <option value="{{ $member->id }}"
                                            {{ old('treasurer_id', $skCouncil->treasurer_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('treasurer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kagawads Section -->
                <div class="border-t pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-1 text-indigo-600"></i>Kagawad (Council Members)
                    </label>
                    <p class="text-xs text-gray-600 mb-3">Select multiple members by holding Ctrl (Windows) or Cmd (Mac) while clicking</p>

                    <select name="kagawad_ids[]" id="kagawad_ids" multiple
                            class="w-full px-4 py-3 border @error('kagawad_ids') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            size="8">
                        @foreach($skMembers as $member)
                            <option value="{{ $member->id }}"
                                    {{ (old('kagawad_ids') ? in_array($member->id, old('kagawad_ids')) : in_array($member->id, $skCouncil->kagawad_ids ?? [])) ? 'selected' : '' }}>
                                {{ $member->first_name }} {{ $member->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('kagawad_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Important Notes:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Chairperson is required</li>
                                <li>Secretary and Treasurer are optional</li>
                                <li>Kagawad members are optional</li>
                                <li>All members must be from your barangay and be active SK members</li>
                                <li>A youth can hold multiple positions (e.g., Chairperson and Kagawad)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-4 border-t">
                    <a href="{{ route('brgy.sk-councils.show', $skCouncil) }}"
                       class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i>Update Council
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden border-2 border-red-200">
            <div class="bg-red-50 px-6 py-4 border-b border-red-200">
                <h3 class="text-lg font-bold text-red-800 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    Danger Zone
                </h3>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">Delete this SK Council</p>
                        <p class="text-sm text-gray-600 mt-1">Once deleted, this council record cannot be recovered.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <i class="fas fa-trash mr-2"></i>Delete Council
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="bg-red-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Deletion
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-2">Are you sure you want to delete this SK Council?</p>
                <p class="text-sm text-gray-600 mb-4">
                    <strong>Term:</strong> {{ $skCouncil->term }}<br>
                    <strong>Barangay:</strong> {{ $userBarangay->name }}
                </p>
                <p class="text-red-600 font-medium text-sm">This action cannot be undone!</p>
            </div>
            <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </button>
                <form action="{{ route('brgy.sk-councils.destroy', $skCouncil) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('deleteModal').classList.add('hidden');
            }
        });
    </script>
@endsection
