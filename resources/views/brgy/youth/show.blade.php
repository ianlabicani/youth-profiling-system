@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Youth Profile</h1>
                <p class="text-gray-600 mt-2">View youth information and details</p>
            </div>
            <a href="{{ route('brgy.youth.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header Section with Name -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-3xl font-bold">{{ $youth->first_name }} {{ $youth->middle_name ? substr($youth->middle_name, 0, 1) . '.' : '' }} {{ $youth->last_name }}{{ $youth->suffix ? ' ' . $youth->suffix : '' }}</h2>
                        <p class="text-blue-100 mt-2">Record ID: <span class="font-mono font-semibold">#{{ $youth->id }}</span></p>
                    </div>
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                        @if($youth->status === 'active') bg-green-500
                        @else bg-gray-500
                        @endif
                    ">
                        {{ ucfirst($youth->status) ?? 'Active' }}
                    </span>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6 space-y-6">
                <!-- Photo Display -->
                @if($youth->photo)
                    <div class="flex justify-center mb-6">
                        <div class="w-48 h-64 rounded-lg overflow-hidden shadow-lg border-4 border-gray-200">
                            <img src="{{ asset('storage/'.$youth->photo) }}" alt="Youth Photo" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endif

                <!-- Personal Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">First Name</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->first_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Middle Name</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->middle_name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Last Name</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->last_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Suffix</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->suffix ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Date of Birth</p>
                            <p class="text-lg font-medium text-gray-800">
                                {{ $youth->date_of_birth ? $youth->date_of_birth->format('M d, Y') : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Sex</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->sex ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Location Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Purok</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->purok ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Barangay</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->barangay->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Municipality</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->municipality ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Province</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->province ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Guardian Information</h3>

                    @if($youth->guardians && is_array($youth->guardians) && count($youth->guardians) > 0)
                        <div class="space-y-4">
                            @foreach($youth->guardians as $index => $guardian)
                                @if(!empty($guardian['first_name']) || !empty($guardian['last_name']))
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-600 mb-1">Guardian {{ $index + 1 }}</p>
                                        <p class="text-lg font-medium text-gray-800">
                                            {{ $guardian['first_name'] ?? '' }}
                                            {{ !empty($guardian['middle_name']) ? substr($guardian['middle_name'], 0, 1) . '.' : '' }}
                                            {{ $guardian['last_name'] ?? '' }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No guardian information provided</p>
                    @endif
                </div>

                <!-- Siblings Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Siblings</h3>

                    @if($youth->siblings && is_array($youth->siblings) && count($youth->siblings) > 0)
                        <div class="space-y-2">
                            @foreach($youth->siblings as $index => $sibling)
                                @if(!empty($sibling['first_name']) || !empty($sibling['last_name']))
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-base font-medium text-gray-800">
                                            {{ $index + 1 }}.
                                            {{ $sibling['first_name'] ?? '' }}
                                            {{ !empty($sibling['middle_name']) ? substr($sibling['middle_name'], 0, 1) . '.' : '' }}
                                            {{ $sibling['last_name'] ?? '' }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No sibling information provided</p>
                    @endif
                </div>

                <!-- Household Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Household Information</h3>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Monthly Household Income</p>
                        <p class="text-lg font-medium text-gray-800">
                            @if($youth->household_income)
                                ₱{{ number_format($youth->household_income, 2) }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Contact Number</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->contact_number ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education & Skills -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Education & Skills</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Educational Attainment</p>
                            <p class="text-lg font-medium text-gray-800">{{ $youth->educational_attainment ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($youth->skills)
                        <div class="mt-6">
                            <p class="text-sm text-gray-600 mb-2">Skills</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($youth->skills as $skill)
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Remarks -->
                @if($youth->remarks)
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Remarks</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $youth->remarks }}</p>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="pt-4">
                    <p class="text-sm text-gray-500">Created: {{ $youth->created_at->format('M d, Y \a\t h:i A') }}</p>
                    <p class="text-sm text-gray-500">Last Updated: {{ $youth->updated_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-6 py-4 flex gap-3">
                <a
                    href="{{ route('brgy.youth.edit', $youth->id) }}"
                    class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-center font-medium"
                >
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
                <button
                    type="button"
                    onclick="openDeleteModal({{ $youth->id }}, '{{ $youth->first_name }} {{ $youth->last_name }}')"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
                >
                    <i class="fas fa-trash mr-2"></i>Delete Profile
                </button>
            </div>
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
