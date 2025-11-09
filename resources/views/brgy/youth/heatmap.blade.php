@extends('brgy.shell')

@section('brgy-content')
    <div class="h-screen flex flex-col">
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Youth Distribution Heatmap</h1>
                <p class="text-gray-600 mt-2">Geographic visualization of registered youth in your barangay</p>
            </div>
            <a href="{{ route('brgy.youth.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>

        <!-- Map Container -->
        <div id="heatmap" class="flex-1 rounded-lg shadow-md border border-gray-200"></div>

        <!-- Legend -->
        <div class="mt-4 bg-white rounded-lg shadow-md p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded" style="background: linear-gradient(to right, blue, cyan, lime, yellow, red);"></div>
                    <span class="text-sm text-gray-700">Heatmap Intensity: Blue (low) → Red (high)</span>
                </div>
                <div class="text-sm text-gray-600">
                    <strong>Total Youth:</strong> {{ $youths->count() }}
                </div>
                <div class="text-sm text-gray-600">
                    <strong>Active Records:</strong> {{ $youths->where('status', 'active')->count() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        #heatmap {
            z-index: 1;
        }
    </style>

    <script>
        // Initialize map
        const map = L.map('heatmap').setView([18.2675306, 121.6844299], 12);

        // Add base layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Prepare heatmap data
        const heatmapData = [
            @foreach($youths as $youth)
                @if($youth->latitude && $youth->longitude)
                    [{{ $youth->latitude }}, {{ $youth->longitude }}, 1],
                @endif
            @endforeach
        ];

        // Add heatmap layer if we have data
        if (heatmapData.length > 0) {
            L.heatLayer(heatmapData, {
                radius: 25,
                blur: 15,
                maxZoom: 17,
                gradient: {
                    0.2: 'blue',
                    0.4: 'cyan',
                    0.6: 'lime',
                    0.8: 'yellow',
                    1.0: 'red'
                }
            }).addTo(map);

            // Fit map to bounds of data
            if (heatmapData.length > 0) {
                const bounds = L.latLngBounds(heatmapData.map(d => [d[0], d[1]]));
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        } else {
            // Show message if no location data
            const noDataDiv = document.createElement('div');
            noDataDiv.style.position = 'absolute';
            noDataDiv.style.top = '50%';
            noDataDiv.style.left = '50%';
            noDataDiv.style.transform = 'translate(-50%, -50%)';
            noDataDiv.style.zIndex = '1000';
            noDataDiv.innerHTML = '<div class="bg-white p-6 rounded-lg shadow-lg text-center"><p class="text-gray-600 font-semibold">No youth records with location data found.</p></div>';
            document.getElementById('heatmap').appendChild(noDataDiv);
        }
    </script>
@endsection
