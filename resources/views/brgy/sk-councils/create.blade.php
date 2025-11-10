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

        @if(!$hasSkMembers)
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
                                <input type="hidden" name="chairperson_id" id="chairperson_id" value="{{ old('chairperson_id') }}">
                                <div class="flex gap-2">
                                    <div class="flex-1 px-4 py-2 border @error('chairperson_id') border-red-500 @else border-gray-300 @enderror rounded-lg bg-gray-50" id="chairperson_display">
                                        <span class="text-gray-400" id="chairperson_text">No chairperson assigned</span>
                                    </div>
                                    <button type="button" onclick="openSearchModal('chairperson')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-search"></i> Assign
                                    </button>
                                    <button type="button" onclick="clearSelection('chairperson')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @error('chairperson_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Secretary -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-pen text-blue-600 mr-1"></i>Secretary
                                </label>
                                <input type="hidden" name="secretary_id" id="secretary_id" value="{{ old('secretary_id') }}">
                                <div class="flex gap-2">
                                    <div class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" id="secretary_display">
                                        <span class="text-gray-400" id="secretary_text">No secretary assigned</span>
                                    </div>
                                    <button type="button" onclick="openSearchModal('secretary')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-search"></i> Assign
                                    </button>
                                    <button type="button" onclick="clearSelection('secretary')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Treasurer -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-coins text-green-600 mr-1"></i>Treasurer
                                </label>
                                <input type="hidden" name="treasurer_id" id="treasurer_id" value="{{ old('treasurer_id') }}">
                                <div class="flex gap-2">
                                    <div class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" id="treasurer_display">
                                        <span class="text-gray-400" id="treasurer_text">No treasurer assigned</span>
                                    </div>
                                    <button type="button" onclick="openSearchModal('treasurer')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-search"></i> Assign
                                    </button>
                                    <button type="button" onclick="clearSelection('treasurer')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad Members -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users text-indigo-600 mr-1"></i>Kagawad (Council Members)
                        </label>
                        <p class="text-sm text-gray-600 mb-3">Add council members one by one</p>

                        <div id="kagawad_list" class="space-y-2 mb-3"></div>

                        <button type="button" onclick="openSearchModal('kagawad')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i>Add Kagawad
                        </button>
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

            <!-- Search Modal -->
            <div id="searchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
                    <!-- Modal Header -->
                    <div class="p-4 border-b flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-search mr-2"></i>Search Youth Member
                        </h3>
                        <button type="button" onclick="closeSearchModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="p-4 border-b">
                        <div class="flex gap-2">
                            <input
                                type="text"
                                id="youthSearchInput"
                                placeholder="Search by name..."
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                autocomplete="off"
                            >
                            <button
                                type="button"
                                onclick="searchYouth()"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition"
                            >
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="flex-1 overflow-y-auto p-4">
                        <div id="searchResults" class="space-y-2">
                            <div class="text-center text-gray-400 py-8">
                                <i class="fas fa-search text-4xl mb-2"></i>
                                <p>Enter a name and click Search to find youth members</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        let currentPosition = null;
        let selectedKagawad = [];

        // Open search modal
        function openSearchModal(position) {
            currentPosition = position;
            document.getElementById('searchModal').classList.remove('hidden');
            document.getElementById('youthSearchInput').value = '';
            document.getElementById('youthSearchInput').focus();
            // Reset to initial state
            const resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = `
                <div class="text-center text-gray-400 py-8">
                    <i class="fas fa-search text-4xl mb-2"></i>
                    <p>Enter a name and click Search to find youth members</p>
                </div>
            `;
        }

        // Close search modal
        function closeSearchModal() {
            document.getElementById('searchModal').classList.add('hidden');
            currentPosition = null;
        }

        // Get already selected IDs
        function getExcludedIds() {
            const excludedIds = [];
            const chairpersonId = document.getElementById('chairperson_id').value;
            const secretaryId = document.getElementById('secretary_id').value;
            const treasurerId = document.getElementById('treasurer_id').value;

            if (chairpersonId) excludedIds.push(chairpersonId);
            if (secretaryId) excludedIds.push(secretaryId);
            if (treasurerId) excludedIds.push(treasurerId);

            selectedKagawad.forEach(k => excludedIds.push(k.id.toString()));

            return excludedIds;
        }

        // Search youth members
        async function searchYouth() {
            const query = document.getElementById('youthSearchInput').value.trim();
            const resultsDiv = document.getElementById('searchResults');

            // Show loading state
            resultsDiv.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';

            try {
                const excludedIds = getExcludedIds();
                const response = await fetch(`{{ route('brgy.sk-councils.search-youth') }}?search=${encodeURIComponent(query)}&exclude=${excludedIds.join(',')}`);
                const youths = await response.json();

                if (youths.length === 0) {
                    resultsDiv.innerHTML = `
                        <div class="text-center text-gray-400 py-8">
                            <i class="fas fa-user-slash text-4xl mb-2"></i>
                            <p>No youth members found${query ? ' matching "' + query + '"' : ''}</p>
                        </div>
                    `;
                    return;
                }

                resultsDiv.innerHTML = youths.map(youth => `
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="selectYouth(${youth.id}, '${youth.name.replace(/'/g, "\\'")}')">
                        <div>
                            <p class="font-medium text-gray-800">${youth.name}</p>
                            ${youth.purok ? `<p class="text-sm text-gray-600">Purok ${youth.purok}</p>` : ''}
                        </div>
                        <button type="button" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Select
                        </button>
                    </div>
                `).join('');
            } catch (error) {
                resultsDiv.innerHTML = `
                    <div class="text-center text-red-500 py-8">
                        <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                        <p>Error loading results</p>
                    </div>
                `;
            }
        }

        // Select youth member
        function selectYouth(id, name) {
            if (currentPosition === 'kagawad') {
                addKagawad(id, name);
            } else {
                document.getElementById(`${currentPosition}_id`).value = id;
                document.getElementById(`${currentPosition}_text`).textContent = name;
                document.getElementById(`${currentPosition}_text`).classList.remove('text-gray-400');
                document.getElementById(`${currentPosition}_text`).classList.add('text-gray-800');
            }
            closeSearchModal();
        }

        // Clear selection
        function clearSelection(position) {
            document.getElementById(`${position}_id`).value = '';
            document.getElementById(`${position}_text`).textContent = `No ${position} assigned`;
            document.getElementById(`${position}_text`).classList.add('text-gray-400');
            document.getElementById(`${position}_text`).classList.remove('text-gray-800');
        }

        // Add kagawad
        function addKagawad(id, name) {
            selectedKagawad.push({ id, name });
            renderKagawadList();
        }

        // Remove kagawad
        function removeKagawad(id) {
            selectedKagawad = selectedKagawad.filter(k => k.id !== id);
            renderKagawadList();
        }

        // Render kagawad list
        function renderKagawadList() {
            const listDiv = document.getElementById('kagawad_list');

            if (selectedKagawad.length === 0) {
                listDiv.innerHTML = '<p class="text-gray-400 text-sm">No kagawad members assigned yet</p>';
                return;
            }

            listDiv.innerHTML = selectedKagawad.map(k => `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <div>
                        <input type="hidden" name="kagawad_ids[]" value="${k.id}">
                        <p class="font-medium text-gray-800">${k.name}</p>
                    </div>
                    <button type="button" onclick="removeKagawad(${k.id})" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }

        // Setup event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('youthSearchInput');

            // Allow Enter key to trigger search
            if (searchInput) {
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchYouth();
                    }
                });
            }

            // Close modal on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeSearchModal();
            });

            // Close modal on backdrop click
            document.getElementById('searchModal').addEventListener('click', (e) => {
                if (e.target.id === 'searchModal') closeSearchModal();
            });

            // Initialize kagawad list
            renderKagawadList();
        });
    </script>
@endsection
