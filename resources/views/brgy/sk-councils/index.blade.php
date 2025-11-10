@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">SK Council Management</h1>
            <p class="text-gray-600 mt-2">Manage the Sangguniang Kabataan council for {{ $userBarangay->name }}</p>
        </div>

        <!-- Alert Messages -->
        @if ($message = session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-check-circle mr-3 mt-0.5"></i>
                    <div>
                        <p class="font-medium">Success</p>
                        <p class="text-sm">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-red-800 mb-2">Error</h3>
                        <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if($skCouncils->isEmpty())
            <!-- No SK Council Yet -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="mb-6">
                    <i class="fas fa-users-cog text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No SK Council Yet</h3>
                <p class="text-gray-600 mb-6">You haven't created an SK Council for your barangay yet.</p>
                <a href="{{ route('brgy.sk-councils.create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create SK Council</span>
                </a>
            </div>
        @else
            <!-- SK Council Card -->
            @foreach($skCouncils as $council)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold text-white">SK Council</h2>
                            <p class="text-purple-100 text-sm">{{ $userBarangay->name }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('brgy.sk-councils.show', $council) }}"
                               class="px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-50 transition font-medium text-sm">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </a>
                            <a href="{{ route('brgy.sk-councils.edit', $council) }}"
                               class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-medium text-sm">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Term -->
                        <div class="mb-6">
                            <label class="text-sm font-medium text-gray-600">Term</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $council->term }}</p>
                        </div>

                        <!-- Officers -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Chairperson -->
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-crown text-purple-600"></i>
                                    <label class="text-sm font-semibold text-purple-800 uppercase">Chairperson</label>
                                </div>
                                @if($council->chairperson)
                                    <a href="{{ route('brgy.youth.show', $council->chairperson) }}"
                                       class="text-gray-900 font-medium hover:text-blue-600 transition">
                                        {{ $council->chairperson->first_name }}
                                        @if($council->chairperson->middle_name)
                                            {{ substr($council->chairperson->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $council->chairperson->last_name }}
                                    </a>
                                @else
                                    <p class="text-gray-500 italic">Not assigned</p>
                                @endif
                            </div>

                            <!-- Secretary -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-pen text-blue-600"></i>
                                    <label class="text-sm font-semibold text-blue-800 uppercase">Secretary</label>
                                </div>
                                @if($council->secretary)
                                    <a href="{{ route('brgy.youth.show', $council->secretary) }}"
                                       class="text-gray-900 font-medium hover:text-blue-600 transition">
                                        {{ $council->secretary->first_name }}
                                        @if($council->secretary->middle_name)
                                            {{ substr($council->secretary->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $council->secretary->last_name }}
                                    </a>
                                @else
                                    <p class="text-gray-500 italic">Not assigned</p>
                                @endif
                            </div>

                            <!-- Treasurer -->
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-coins text-green-600"></i>
                                    <label class="text-sm font-semibold text-green-800 uppercase">Treasurer</label>
                                </div>
                                @if($council->treasurer)
                                    <a href="{{ route('brgy.youth.show', $council->treasurer) }}"
                                       class="text-gray-900 font-medium hover:text-blue-600 transition">
                                        {{ $council->treasurer->first_name }}
                                        @if($council->treasurer->middle_name)
                                            {{ substr($council->treasurer->middle_name, 0, 1) }}.
                                        @endif
                                        {{ $council->treasurer->last_name }}
                                    </a>
                                @else
                                    <p class="text-gray-500 italic">Not assigned</p>
                                @endif
                            </div>
                        </div>

                        <!-- Kagawads -->
                        <div class="border-t pt-4">
                            <label class="text-sm font-semibold text-gray-700 uppercase flex items-center gap-2 mb-3">
                                <i class="fas fa-users text-indigo-600"></i>
                                Kagawad (Council Members)
                            </label>
                            @if($council->kagawad_ids && count($council->kagawad_ids) > 0)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($council->kagawads() as $kagawad)
                                        <a href="{{ route('brgy.youth.show', $kagawad) }}"
                                           class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <i class="fas fa-user-circle text-gray-400"></i>
                                            <span class="text-sm text-gray-900">
                                                {{ $kagawad->first_name }} {{ $kagawad->last_name }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">No kagawad members assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button"
                                onclick="openDeleteModal({{ $council->id }})"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                            <i class="fas fa-trash mr-1"></i>Delete Council
                        </button>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Note:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Each barangay can only have one active SK Council</li>
                        <li>Only SK members from your barangay can be assigned to positions</li>
                        <li>Members must have "SK Member" status to be eligible</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="mx-4 max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mx-auto">
                <i class="fas fa-exclamation-triangle text-lg text-red-600"></i>
            </div>

            <h3 class="mb-2 text-center text-lg font-bold text-gray-900">Delete SK Council</h3>
            <p class="mb-6 text-center text-gray-600">Are you sure you want to delete this SK Council? This action cannot be undone.</p>

            <form id="deleteForm" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                        class="flex-1 rounded-lg bg-gray-300 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-400">
                    Cancel
                </button>
                <button onclick="confirmDelete()"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 font-medium text-white transition hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteCouncilId = null;

        function openDeleteModal(councilId) {
            deleteCouncilId = councilId;
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'none';
            deleteCouncilId = null;
        }

        function confirmDelete() {
            if (deleteCouncilId) {
                const form = document.getElementById('deleteForm');
                form.action = '/brgy/sk-councils/' + deleteCouncilId;
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

        // Auto-hide success messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.querySelector('[role="alert"]');
            if (successAlert && successAlert.classList.contains('bg-green-100')) {
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 5000);
            }
        });
    </script>
@endsection
