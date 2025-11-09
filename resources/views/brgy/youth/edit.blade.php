@extends('brgy.shell')

@section('brgy-content')
    <div class="max-w-2xl mx-auto">
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

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Youth Profile</h1>
            <p class="text-gray-600 mt-2">Update youth information</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('brgy.youth.update', $youth->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input
                                type="text"
                                name="first_name"
                                value="{{ old('first_name', $youth->first_name) }}"
                                class="w-full px-4 py-2 border @error('first_name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                            <input
                                type="text"
                                name="middle_name"
                                value="{{ old('middle_name', $youth->middle_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input
                                type="text"
                                name="last_name"
                                value="{{ old('last_name', $youth->last_name) }}"
                                class="w-full px-4 py-2 border @error('last_name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Suffix -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Suffix</label>
                            <input
                                type="text"
                                name="suffix"
                                value="{{ old('suffix', $youth->suffix) }}"
                                placeholder="Jr., Sr., etc."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth', $youth->date_of_birth?->format('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Sex -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                            <select name="sex" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select...</option>
                                <option value="Male" {{ old('sex', $youth->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $youth->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('sex', $youth->sex) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Location Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Barangay -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Barangay *</label>
                            <select
                                name="barangay"
                                id="barangay"
                                class="w-full px-4 py-2 border @error('barangay') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                                <option value="">Select Barangay...</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay }}" {{ old('barangay', $youth->barangay) === $barangay ? 'selected' : '' }}>
                                        {{ $barangay }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barangay')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Purok -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purok *</label>
                            <select
                                name="purok"
                                id="purok"
                                class="w-full px-4 py-2 border @error('purok') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                                <option value="">Select Purok...</option>
                            </select>
                            @error('purok')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Municipality</label>
                            <input
                                type="text"
                                name="municipality"
                                value="{{ old('municipality', $youth->municipality ?? 'Camalaniugan') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                readonly
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                            <input
                                type="text"
                                name="province"
                                value="{{ old('province', $youth->province ?? 'Cagayan') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                readonly
                            >
                        </div>
                    </div>
                </div>

                <!-- Location Map Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Geographic Location</h3>

                    <p class="text-sm text-gray-600 mb-4">Click on the map to set the youth's location coordinates</p>

                    <!-- Map Container -->
                    <div id="locationMap" class="w-full h-64 rounded-lg border border-gray-300 mb-4"></div>

                    <!-- Coordinate Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                            <input
                                type="number"
                                name="latitude"
                                id="latitude"
                                step="0.00000001"
                                value="{{ old('latitude', $youth->latitude) }}"
                                placeholder="Click map or enter manually"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                            <input
                                type="number"
                                name="longitude"
                                id="longitude"
                                step="0.00000001"
                                value="{{ old('longitude', $youth->longitude) }}"
                                placeholder="Click map or enter manually"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <input
                                type="tel"
                                name="contact_number"
                                value="{{ old('contact_number', $youth->contact_number) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $youth->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>

                <!-- Education & Skills Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Education & Skills</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Educational Attainment</label>
                            <select name="educational_attainment" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select...</option>
                                <option value="Elementary" {{ old('educational_attainment', $youth->educational_attainment) === 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                <option value="High School" {{ old('educational_attainment', $youth->educational_attainment) === 'High School' ? 'selected' : '' }}>High School</option>
                                <option value="College" {{ old('educational_attainment', $youth->educational_attainment) === 'College' ? 'selected' : '' }}>College</option>
                                <option value="Vocational" {{ old('educational_attainment', $youth->educational_attainment) === 'Vocational' ? 'selected' : '' }}>Vocational</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active" {{ old('status', $youth->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ old('status', $youth->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                    <textarea
                        name="remarks"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('remarks', $youth->remarks) }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <button
                        type="submit"
                        class="flex-1 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition"
                    >
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <a
                        href="{{ route('brgy.youth.show', $youth->id) }}"
                        class="flex-1 px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition text-center"
                    >
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button
                        type="button"
                        onclick="openDeleteModal({{ $youth->id }}, '{{ $youth->first_name }} {{ $youth->last_name }}')"
                        class="flex-1 px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition"
                    >
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        #locationMap {
            z-index: 1;
        }
    </style>

    <!-- Location Map Script -->
    <script>
        // Initialize map centered on Camalaniugan
        const map = L.map('locationMap').setView([17.5, 121.5], 13);

        // Add base layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        // Handle map clicks
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Update input fields
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            // Remove previous marker
            if (marker) {
                map.removeLayer(marker);
            }

            // Add new marker
            marker = L.marker([lat, lng])
                .bindPopup(`
                    <div class="p-2 text-sm">
                        <strong>Selected Location</strong><br>
                        Lat: ${lat.toFixed(6)}<br>
                        Lng: ${lng.toFixed(6)}
                    </div>
                `)
                .addTo(map)
                .openPopup();
        });

        // If coordinates are already set, show marker
        const savedLat = document.getElementById('latitude').value;
        const savedLng = document.getElementById('longitude').value;

        if (savedLat && savedLng) {
            map.setView([parseFloat(savedLat), parseFloat(savedLng)], 15);
            marker = L.marker([parseFloat(savedLat), parseFloat(savedLng)])
                .bindPopup(`
                    <div class="p-2 text-sm">
                        <strong>Saved Location</strong><br>
                        Lat: ${savedLat}<br>
                        Lng: ${savedLng}
                    </div>
                `)
                .addTo(map)
                .openPopup();
        }

        // Update marker when coordinates are manually changed
        document.getElementById('latitude').addEventListener('change', updateMarker);
        document.getElementById('longitude').addEventListener('change', updateMarker);

        function updateMarker() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;

            if (lat && lng) {
                const latNum = parseFloat(lat);
                const lngNum = parseFloat(lng);

                if (!isNaN(latNum) && !isNaN(lngNum)) {
                    map.setView([latNum, lngNum], 15);

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([latNum, lngNum])
                        .bindPopup(`
                            <div class="p-2 text-sm">
                                <strong>Updated Location</strong><br>
                                Lat: ${latNum.toFixed(6)}<br>
                                Lng: ${lngNum.toFixed(6)}
                            </div>
                        `)
                        .addTo(map)
                        .openPopup();
                }
            }
        }
    </script>

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

        // Purok mapping data
        const puroksByBarangay = @json($puroksByBarangay);

        // Handle barangay change to populate puroks
        document.getElementById('barangay').addEventListener('change', function() {
            const selectedBarangay = this.value;
            const purokSelect = document.getElementById('purok');

            // Clear existing options
            purokSelect.innerHTML = '<option value="">Select Purok...</option>';

            // Populate puroks for selected barangay
            if (selectedBarangay && puroksByBarangay[selectedBarangay]) {
                puroksByBarangay[selectedBarangay].forEach(function(purok) {
                    const option = document.createElement('option');
                    option.value = purok;
                    option.textContent = purok;
                    if (purok === "{{ old('purok', $youth->purok) }}") {
                        option.selected = true;
                    }
                    purokSelect.appendChild(option);
                });
            }
        });

        // Trigger change event on page load if barangay is already selected
        const initialBarangay = document.getElementById('barangay').value;
        if (initialBarangay) {
            document.getElementById('barangay').dispatchEvent(new Event('change'));
        }
    </script>
@endsection
