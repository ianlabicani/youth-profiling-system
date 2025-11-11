@extends('municipal.shell')

@section('municipal-content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('municipal.organizations.index') }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Create Organization</h1>
            </div>
            <p class="text-gray-600 ml-10">Register a new youth organization</p>
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

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('municipal.organizations.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Organization Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag text-gray-600 mr-1"></i>Organization Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter organization name..."
                        class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Officers Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Organization Officers</h3>

                    <div class="space-y-4">
                        <!-- President -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-crown text-blue-600 mr-1"></i>President
                            </label>
                            <input type="hidden" name="president_id" id="president_id" value="{{ old('president_id') }}">
                            <div class="flex gap-2">
                                <div class="flex-1 px-4 py-2 border @error('president_id') border-red-500 @else border-gray-300 @enderror rounded-lg bg-gray-50" id="president_display">
                                    <span class="text-gray-400" id="president_text">No president assigned</span>
                                </div>
                                <button type="button" onclick="openSearchModal('president')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-search"></i> Assign
                                </button>
                                <button type="button" onclick="clearSelection('president')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @error('president_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vice President -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tie text-purple-600 mr-1"></i>Vice President
                            </label>
                            <input type="hidden" name="vice_president_id" id="vice_president_id" value="{{ old('vice_president_id') }}">
                            <div class="flex gap-2">
                                <div class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" id="vice_president_display">
                                    <span class="text-gray-400" id="vice_president_text">No vice president assigned</span>
                                </div>
                                <button type="button" onclick="openSearchModal('vice_president')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-search"></i> Assign
                                </button>
                                <button type="button" onclick="clearSelection('vice_president')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Secretary -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-pen text-green-600 mr-1"></i>Secretary
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
                                <i class="fas fa-coins text-orange-600 mr-1"></i>Treasurer
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

                <!-- Committee Heads -->
                <div class="border-b pb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-briefcase text-indigo-600 mr-1"></i>Committee Heads
                    </label>
                    <p class="text-sm text-gray-600 mb-3">Add committee heads with their positions</p>

                    <div id="committee_heads_list" class="space-y-2 mb-3"></div>

                    <button type="button" id="addCommitteeBtn" onclick="openSearchModalForCommittee()" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i>Add Committee Head
                    </button>
                    <p class="text-xs text-gray-500 mt-1">Optional: Add committee members with their positions</p>
                </div>

                <!-- Members -->
                <div class="border-b pb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users text-indigo-600 mr-1"></i>Members
                    </label>
                    <p class="text-sm text-gray-600 mb-3">Add organization members one by one</p>

                    <div id="members_list" class="space-y-2 mb-3"></div>

                    <button type="button" id="addMemberBtn" onclick="openSearchModalForMember()" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i>Add Member
                    </button>
                    <p class="text-xs text-gray-500 mt-1">Optional: You can add members now or later</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter organization description..."
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <button
                        type="submit"
                        class="flex-1 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition"
                    >
                        <i class="fas fa-save mr-2"></i>Create Organization
                    </button>
                    <a
                        href="{{ route('municipal.organizations.index') }}"
                        class="flex-1 px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition text-center"
                    >
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
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

    <!-- Committee Head Modal -->
    <div id="committeeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Committee Head</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Committee Position *</label>
                    <input
                        type="text"
                        id="committeePosition"
                        placeholder="e.g., Finance Committee, Events Committee"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Head Name *</label>
                    <div class="flex gap-2 mb-2">
                        <input
                            type="text"
                            id="committeeHeadSearch"
                            placeholder="Search by name..."
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            autocomplete="off"
                        >
                        <button
                            type="button"
                            onclick="searchCommitteeHeads()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div id="committeeHeadResults" class="max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-2 bg-gray-50">
                        <p class="text-center text-gray-400 text-sm py-4">Search for a youth member</p>
                    </div>
                    <input type="hidden" id="committeeHeadId">
                    <div class="mt-2 px-3 py-2 border border-gray-300 rounded-lg bg-blue-50 min-h-10">
                        <p class="text-sm text-gray-600">Selected: <span id="committeeHeadName" class="font-semibold text-gray-800">None</span></p>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeCommitteeModal()"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        onclick="addCommitteeHead()"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                    >
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPosition = null;
        let currentSearchType = null;
        let selectedMembers = [];
        let selectedCommitteeHeads = [];
        let selectedYouthName = '';

        // Open search modal for officer selection
        function openSearchModal(position) {
            currentPosition = position;
            currentSearchType = 'officer';
            document.getElementById('searchModal').classList.remove('hidden');
            document.getElementById('youthSearchInput').value = '';
            document.getElementById('youthSearchInput').focus();
            resetSearchResults();
        }

        // Open search modal for member selection
        function openSearchModalForMember() {
            currentSearchType = 'member';
            document.getElementById('searchModal').classList.remove('hidden');
            document.getElementById('youthSearchInput').value = '';
            document.getElementById('youthSearchInput').focus();
            resetSearchResults();
        }

        // Open committee modal
        function openSearchModalForCommittee() {
            document.getElementById('committeeModal').classList.remove('hidden');
            document.getElementById('committeePosition').value = '';
            document.getElementById('committeeHeadId').value = '';
            document.getElementById('committeeHeadSearch').value = '';
            document.getElementById('committeeHeadName').textContent = 'None';
            document.getElementById('committeeHeadResults').innerHTML = '<p class="text-center text-gray-400 text-sm py-4">Search for a youth member</p>';
        }

        // Close search modal
        function closeSearchModal() {
            document.getElementById('searchModal').classList.add('hidden');
            currentPosition = null;
        }

        // Close committee modal
        function closeCommitteeModal() {
            document.getElementById('committeeModal').classList.add('hidden');
        }

        // Reset search results
        function resetSearchResults() {
            const resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = `
                <div class="text-center text-gray-400 py-8">
                    <i class="fas fa-search text-4xl mb-2"></i>
                    <p>Enter a name and click Search to find youth members</p>
                </div>
            `;
        }

        // Get already selected IDs
        function getExcludedIds() {
            const excludedIds = [];
            const presidentId = document.getElementById('president_id').value;
            const vicePresidentId = document.getElementById('vice_president_id').value;
            const secretaryId = document.getElementById('secretary_id').value;
            const treasurerId = document.getElementById('treasurer_id').value;

            if (presidentId) excludedIds.push(presidentId);
            if (vicePresidentId) excludedIds.push(vicePresidentId);
            if (secretaryId) excludedIds.push(secretaryId);
            if (treasurerId) excludedIds.push(treasurerId);

            selectedMembers.forEach(m => excludedIds.push(m.id.toString()));
            selectedCommitteeHeads.forEach(c => excludedIds.push(c.head_id.toString()));

            return excludedIds;
        }

        // Search youth members (main search modal)
        async function searchYouth() {
            const query = document.getElementById('youthSearchInput').value.trim();
            const resultsDiv = document.getElementById('searchResults');

            resultsDiv.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';

            try {
                const excludedIds = getExcludedIds();
                const response = await fetch(`{{ route('municipal.organizations.search-youth') }}?search=${encodeURIComponent(query)}&exclude=${excludedIds.join(',')}`);
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
                            ${youth.barangay ? `<p class="text-sm text-gray-600">${youth.barangay}</p>` : ''}
                        </div>
                        <button type="button" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Select
                        </button>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error:', error);
                resultsDiv.innerHTML = `
                    <div class="text-center text-red-500 py-8">
                        <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                        <p>Error loading results</p>
                    </div>
                `;
            }
        }

        // Search committee heads (in committee modal)
        async function searchCommitteeHeads() {
            const query = document.getElementById('committeeHeadSearch').value.trim();
            const resultsDiv = document.getElementById('committeeHeadResults');

            if (!query) {
                resultsDiv.innerHTML = '<p class="text-center text-gray-400 text-sm py-4">Search for a youth member</p>';
                return;
            }

            resultsDiv.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin text-xl text-gray-400"></i></div>';

            try {
                const excludedIds = getExcludedIds();
                const response = await fetch(`{{ route('municipal.organizations.search-youth') }}?search=${encodeURIComponent(query)}&exclude=${excludedIds.join(',')}`);
                const youths = await response.json();

                if (youths.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-center text-gray-400 text-sm py-4">No youth members found</p>';
                    return;
                }

                resultsDiv.innerHTML = youths.map(youth => `
                    <button
                        type="button"
                        onclick="selectCommitteeHead(${youth.id}, '${youth.name.replace(/'/g, "\\'")}'); event.preventDefault();"
                        class="w-full text-left px-3 py-2 hover:bg-blue-100 rounded border-b border-gray-200 last:border-b-0"
                    >
                        <p class="font-medium text-gray-800">${youth.name}</p>
                        <p class="text-xs text-gray-600">${youth.barangay ? youth.barangay : ''}</p>
                    </button>
                `).join('');
            } catch (error) {
                console.error('Error:', error);
                resultsDiv.innerHTML = '<p class="text-center text-red-500 text-sm py-4">Error loading results</p>';
            }
        }

        // Select committee head from the committee modal search
        function selectCommitteeHead(id, name) {
            document.getElementById('committeeHeadId').value = id;
            document.getElementById('committeeHeadName').textContent = name;
            document.getElementById('committeeHeadSearch').value = '';
            document.getElementById('committeeHeadResults').innerHTML = '<p class="text-center text-gray-400 text-sm py-4">Search for a youth member</p>';
        }

        // Select youth member (from main search modal)
        function selectYouth(id, name) {
            selectedYouthName = name;

            if (currentSearchType === 'officer') {
                selectOfficer(id, name);
            } else if (currentSearchType === 'member') {
                addMember(id, name);
                closeSearchModal();
            }
        }

        // Select officer
        function selectOfficer(id, name) {
            document.getElementById(`${currentPosition}_id`).value = id;
            document.getElementById(`${currentPosition}_text`).textContent = name;
            document.getElementById(`${currentPosition}_text`).classList.remove('text-gray-400');
            document.getElementById(`${currentPosition}_text`).classList.add('text-gray-800');
            closeSearchModal();
        }

        // Clear officer selection
        function clearSelection(position) {
            document.getElementById(`${position}_id`).value = '';
            document.getElementById(`${position}_text`).textContent = `No ${position.replace(/_/g, ' ')} assigned`;
            document.getElementById(`${position}_text`).classList.add('text-gray-400');
            document.getElementById(`${position}_text`).classList.remove('text-gray-800');
        }

        // Add member
        function addMember(id, name) {
            selectedMembers.push({ id, name });
            renderMembersList();
        }

        // Remove member
        function removeMember(id) {
            selectedMembers = selectedMembers.filter(m => m.id !== id);
            renderMembersList();
        }

        // Render members list
        function renderMembersList() {
            const listDiv = document.getElementById('members_list');

            if (selectedMembers.length === 0) {
                listDiv.innerHTML = '<p class="text-gray-400 text-sm">No members assigned yet</p>';
                return;
            }

            listDiv.innerHTML = selectedMembers.map(m => `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <div>
                        <input type="hidden" name="members[]" value="${m.id}">
                        <p class="font-medium text-gray-800">${m.name}</p>
                    </div>
                    <button type="button" onclick="removeMember(${m.id})" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }

        // Add committee head
        function addCommitteeHead() {
            const position = document.getElementById('committeePosition').value.trim();
            const headId = document.getElementById('committeeHeadId').value;
            const headName = document.getElementById('committeeHeadName').textContent;

            if (!position) {
                alert('Please enter a committee position');
                return;
            }

            if (!headId || headName === 'None') {
                alert('Please select a committee head');
                return;
            }

            selectedCommitteeHeads.push({
                name: position,
                head_id: parseInt(headId),
                head_name: headName
            });

            renderCommitteeHeadsList();
            closeCommitteeModal();
        }

        // Remove committee head
        function removeCommitteeHead(headId) {
            selectedCommitteeHeads = selectedCommitteeHeads.filter(c => c.head_id !== headId);
            renderCommitteeHeadsList();
        }

        // Render committee heads list
        function renderCommitteeHeadsList() {
            const listDiv = document.getElementById('committee_heads_list');

            if (selectedCommitteeHeads.length === 0) {
                listDiv.innerHTML = '<p class="text-gray-400 text-sm">No committee heads assigned yet</p>';
                return;
            }

            listDiv.innerHTML = selectedCommitteeHeads.map(c => `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <div>
                        <input type="hidden" name="committee_heads[]" value='${JSON.stringify({name: c.name, head_id: c.head_id})}'>
                        <p class="font-medium text-gray-800">${c.name}</p>
                        <p class="text-sm text-gray-600">${c.head_name}</p>
                    </div>
                    <button type="button" onclick="removeCommitteeHead(${c.head_id})" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }

        // Setup event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('youthSearchInput');
            const committeeSearchInput = document.getElementById('committeeHeadSearch');

            if (searchInput) {
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchYouth();
                    }
                });
            }

            if (committeeSearchInput) {
                committeeSearchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchCommitteeHeads();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeSearchModal();
                    closeCommitteeModal();
                }
            });

            document.getElementById('searchModal')?.addEventListener('click', (e) => {
                if (e.target.id === 'searchModal') closeSearchModal();
            });

            document.getElementById('committeeModal')?.addEventListener('click', (e) => {
                if (e.target.id === 'committeeModal') closeCommitteeModal();
            });

            renderMembersList();
            renderCommitteeHeadsList();
        });
    </script>
@endsection
