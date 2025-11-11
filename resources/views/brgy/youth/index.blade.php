@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Youth Management</h1>
            <p class="text-gray-600 mt-2">Register and manage youth profiles in your barangay</p>
        </div>

        <!-- Action Buttons -->
        <div class="mb-6 flex justify-between items-center gap-4">
            <a href="{{ route('brgy.youth.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
                <i class="fas fa-user-plus"></i>
                <span>Register Youth</span>
            </a>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Filters</h2>

            <form method="GET" action="{{ route('brgy.youth.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input
                            type="text"
                            name="search"
                            placeholder="Name, email, phone..."
                            value="{{ request('search') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <!-- Sex Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                        <select name="sex" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All</option>
                            <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ request('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Educational Attainment Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Education</label>
                        <select name="educational_attainment" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Levels</option>
                            <option value="Elementary" {{ request('educational_attainment') === 'Elementary' ? 'selected' : '' }}>Elementary</option>
                            <option value="High School" {{ request('educational_attainment') === 'High School' ? 'selected' : '' }}>High School</option>
                            <option value="College" {{ request('educational_attainment') === 'College' ? 'selected' : '' }}>College</option>
                            <option value="Vocational" {{ request('educational_attainment') === 'Vocational' ? 'selected' : '' }}>Vocational</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('brgy.youth.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Youth Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($youths->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Photo</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Contact</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Sex</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($youths as $youth)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3">
                                        @if($youth->photo)
                                            <img src="{{ asset('storage/'.$youth->photo) }}" alt="Photo" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="font-mono text-sm text-blue-600">#{{ $youth->id }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-800">
                                            {{ $youth->first_name }} {{ $youth->middle_name ? substr($youth->middle_name, 0, 1) . '.' : '' }} {{ $youth->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $youth->educational_attainment ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $youth->email ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $youth->contact_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($youth->status === 'active') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ ucfirst($youth->status) ?? 'Active' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $youth->sex ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('brgy.youth.show', $youth->id) }}" class="inline-flex items-center gap-1 px-3 py-1 text-sm rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                            <a href="{{ route('brgy.youth.edit', $youth->id) }}" class="inline-flex items-center gap-1 px-3 py-1 text-sm rounded bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            <button type="button" onclick="openDeleteModal({{ $youth->id }}, '{{ $youth->first_name }} {{ $youth->last_name }}')" class="inline-flex items-center gap-1 px-3 py-1 text-sm rounded bg-red-50 text-red-600 hover:bg-red-100 transition">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t">
                    {{ $youths->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg mb-4">No youth records found</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="mx-4 max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mx-auto">
                <i class="fas fa-exclamation-triangle text-lg text-red-600"></i>
            </div>

            <h3 class="mb-2 text-center text-lg font-bold text-gray-900">Delete Youth Record</h3>
            <p class="mb-2 text-center text-gray-600">Are you sure you want to delete</p>
            <p class="mb-6 text-center font-semibold text-gray-800" id="deleteYouthName"></p>

            <p class="mb-6 text-center text-sm text-gray-500">This action cannot be undone.</p>

            <form id="deleteForm" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 rounded-lg bg-gray-300 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-400">
                    Cancel
                </button>
                <button onclick="confirmDelete()" class="flex-1 rounded-lg bg-red-600 px-4 py-2 font-medium text-white transition hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteYouthId = null;

        function openDeleteModal(youthId, youthName) {
            deleteYouthId = youthId;
            document.getElementById('deleteYouthName').textContent = youthName;
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'none';
            deleteYouthId = null;
        }

        function confirmDelete() {
            if (deleteYouthId) {
                const form = document.getElementById('deleteForm');
                form.action = '/brgy/youth/' + deleteYouthId;
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection
