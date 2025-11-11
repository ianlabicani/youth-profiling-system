@extends('municipal.shell')

@section('title', 'Municipal Dashboard')
@section('municipal-content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Municipal Dashboard</h1>
        <p class="text-gray-600">Overview and analytics across all barangays</p>
    </div>

    <!-- KPI cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Total Youth</p>
            <p class="text-2xl font-bold">{{ number_format($totalYouth) }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Barangays</p>
            <p class="text-2xl font-bold">{{ $totalBarangays }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Active SK Councils</p>
            <p class="text-2xl font-bold">{{ $activeCouncils }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Organizations</p>
            <p class="text-2xl font-bold">{{ $totalOrganizations }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Out of School</p>
            <p class="text-2xl font-bold text-orange-600">{{ number_format($outOfSchoolCount) }}</p>
        </div>
    </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Registrations chart (server-side data) -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">New Registrations (last 12 months)</h3>
                <div class="mt-3">
                    <canvas id="registrationsChart" height="120"></canvas>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Events (Next 30 Days)</h3>
                @if($upcomingList->count())
                    <ul class="mt-3 space-y-2">
                        @foreach($upcomingList as $ev)
                            <li class="p-3 border rounded-md flex items-center justify-between hover:bg-gray-50">
                                <div>
                                    <p class="font-medium">{{ $ev->title }}</p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i>{{ $ev->date->format('M d, Y') }}
                                        <i class="fas fa-map-marker-alt mr-1 ml-2"></i>{{ \Illuminate\Support\Str::limit($ev->venue, 40) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($ev->barangay)
                                        <p class="text-sm text-blue-700 font-semibold">{{ $ev->barangay->name }}</p>
                                    @endif
                                    @if($ev->skCouncil)
                                        <p class="text-xs text-indigo-600">{{ $ev->skCouncil->term }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 mt-3">No upcoming events in the next 30 days.</p>
                @endif
            </div>

            <!-- Education distribution -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Educational Attainment (Top 8)</h3>
                <div class="mt-3">
                    <div class="mb-3">
                        <canvas id="educationChart" height="140"></canvas>
                    </div>
                    @if(isset($education) && $education->count())
                        <ul class="space-y-1 text-sm">
                            @foreach($education->take(8) as $ed)
                                <li class="flex justify-between">
                                    <span>{{ $ed->educational_attainment ?: 'Unknown/Out of School' }}</span>
                                    <span class="font-semibold">{{ number_format($ed->total) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No education data.</p>
                    @endif
                </div>
            </div>

            <!-- Youth positions summary -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Youth Positions in SK Councils</h3>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between"><span>Distinct youths holding any council position</span><span class="font-semibold">{{ number_format($distinctPositionsCount) }}</span></div>
                    <div class="flex justify-between"><span>Chairpersons</span><span class="font-semibold">{{ $chairCount }}</span></div>
                    <div class="flex justify-between"><span>Secretaries</span><span class="font-semibold">{{ $secretaryCount }}</span></div>
                    <div class="flex justify-between"><span>Treasurers</span><span class="font-semibold">{{ $treasurerCount }}</span></div>
                    <div class="flex justify-between"><span>Total kagawad (members) entries</span><span class="font-semibold">{{ number_format($kagawadTotal) }}</span></div>
                </div>
            </div>

            <!-- Youth by Barangay -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Youth by Barangay (Top 10)</h3>
                <div class="mt-3">
                    <div class="mb-3">
                        <canvas id="barangayChart" height="140"></canvas>
                    </div>
                    @if($youthByBarangay->count())
                        <ul class="space-y-1 text-sm">
                            @foreach($youthByBarangay as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item['barangay'] }}</span>
                                    <span class="font-semibold">{{ number_format($item['total']) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Recent youths -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Recent Youth Registrations</h3>
                @if($recentYouth->count())
                    <ul class="mt-3 space-y-2 text-sm">
                        @foreach($recentYouth as $y)
                            <li class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                <div>
                                    <div class="font-medium">{{ $y->full_name }}</div>
                                    <div class="text-gray-500">
                                        <i class="fas fa-map-pin mr-1"></i>{{ $y->barangay?->name ?? 'Unknown' }}
                                        @if($y->purok)
                                            <i class="fas fa-home mr-1 ml-1"></i>{{ $y->purok }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-gray-500 text-xs">{{ $y->created_at->diffForHumans() }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 mt-3">No recent registrations.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <!-- Youth by sex -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Youth by Sex</h3>
                <div class="mt-3">
                    <div class="mb-3">
                        <canvas id="sexChart" height="120"></canvas>
                    </div>
                    @if(count($youthBySex))
                        @foreach($youthBySex as $sex => $count)
                            <div class="flex items-center justify-between py-1">
                                <div class="capitalize">{{ $sex ?: 'Unknown' }}</div>
                                <div class="font-semibold">{{ number_format($count) }}</div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No data</p>
                    @endif
                </div>
            </div>

            <!-- Youth by status -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Youth by Status</h3>
                <div class="mt-3">
                    <div class="mb-3">
                        <canvas id="statusChart" height="120"></canvas>
                    </div>
                    @if(count($youthByStatus))
                        @foreach($youthByStatus as $status => $count)
                            <div class="flex items-center justify-between py-1">
                                <div class="capitalize">{{ $status ?: 'Unknown' }}</div>
                                <div class="font-semibold">{{ number_format($count) }}</div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No data</p>
                    @endif
                </div>
            </div>

            <!-- Age buckets -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Age Distribution</h3>
                <div class="mt-3 text-sm">
                    <div class="mb-3">
                        <canvas id="ageBucketsChart" height="120"></canvas>
                    </div>
                    @if(isset($ageBuckets) && count($ageBuckets))
                        @foreach($ageBuckets as $bucket => $val)
                            <div class="flex justify-between py-1"><span>{{ $bucket }} years</span><span class="font-semibold">{{ number_format($val) }}</span></div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No age data.</p>
                    @endif
                </div>
            </div>

            <!-- Event summary -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">Event Summary</h3>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between"><span>Upcoming (30d)</span><span class="font-semibold">{{ $upcomingEvents }}</span></div>
                    <div class="flex justify-between"><span>This Year</span><span class="font-semibold">{{ $eventsThisYear }}</span></div>
                </div>
            </div>

        </div>
    </div>

    <!-- Councils table -->
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">SK Councils Overview</h3>
        @if($councils->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-600 border-b">
                        <tr>
                            <th class="p-2">Barangay</th>
                            <th class="p-2">Term</th>
                            <th class="p-2">Active</th>
                            <th class="p-2">Members (distinct)</th>
                            <th class="p-2">Events</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($councils as $c)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2 font-medium">{{ $c->barangay?->name ?? 'Unknown' }}</td>
                                <td class="p-2">{{ $c->term }}</td>
                                <td class="p-2">@if($c->is_active) <span class="text-green-600 font-semibold">Yes</span> @else <span class="text-gray-500">No</span> @endif</td>
                                <td class="p-2">{{ count(array_filter(array_merge([$c->chairperson_id, $c->secretary_id, $c->treasurer_id], is_array($c->kagawad_ids) ? $c->kagawad_ids : []))) }}</td>
                                <td class="p-2">{{ $c->events_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No councils found.</p>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = {!! json_encode($months) !!};
    const regs = {!! json_encode($dataRegs) !!};

    const ctx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Registrations',
                data: regs,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.08)',
                fill: true,
                tension: 0.2,
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Prepare data for additional charts
    @php
        $sexLabels = array_values(array_map(function($k){ return $k === null ? 'Unknown' : $k; }, array_keys($youthBySex ?? [])));
        $sexData = array_values($youthBySex ?? []);

        $statusLabels = array_values(array_map(function($k){ return $k === null ? 'Unknown' : $k; }, array_keys($youthByStatus ?? [])));
        $statusData = array_values($youthByStatus ?? []);

        $ageLabels = array_keys($ageBuckets ?? []);
        $ageData = array_values($ageBuckets ?? []);

        $eduLabels = [];
        $eduData = [];
        if(isset($education)){
            foreach($education->take(8) as $e){
                $eduLabels[] = $e->educational_attainment ?: 'Unknown/Out of School';
                $eduData[] = $e->total;
            }
        }

        $barangayLabels = array_map(function($item) { return $item['barangay']; }, $youthByBarangay->toArray());
        $barangayData = array_map(function($item) { return $item['total']; }, $youthByBarangay->toArray());
    @endphp

    const sexLabels = {!! json_encode($sexLabels) !!};
    const sexData = {!! json_encode($sexData) !!};

    const statusLabels = {!! json_encode($statusLabels) !!};
    const statusData = {!! json_encode($statusData) !!};

    const ageLabels = {!! json_encode($ageLabels) !!};
    const ageData = {!! json_encode($ageData) !!};

    const eduLabels = {!! json_encode($eduLabels) !!};
    const eduData = {!! json_encode($eduData) !!};

    const barangayLabels = {!! json_encode($barangayLabels) !!};
    const barangayData = {!! json_encode($barangayData) !!};

    const colorPalette = ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316','#84cc16'];

    function getColors(n){
        const out = [];
        for(let i=0;i<n;i++) out.push(colorPalette[i % colorPalette.length]);
        return out;
    }

    // Sex doughnut
    if(sexLabels.length){
        const sCtx = document.getElementById('sexChart').getContext('2d');
        new Chart(sCtx, {
            type: 'doughnut',
            data: { labels: sexLabels, datasets: [{ data: sexData, backgroundColor: getColors(sexLabels.length) }] },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Status doughnut
    if(statusLabels.length){
        const stCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(stCtx, {
            type: 'doughnut',
            data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: getColors(statusLabels.length) }] },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Education bar (top 8)
    if(eduLabels.length){
        const edCtx = document.getElementById('educationChart').getContext('2d');
        new Chart(edCtx, {
            type: 'bar',
            data: { labels: eduLabels, datasets: [{ label: 'Count', data: eduData, backgroundColor: getColors(eduLabels.length) }] },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }

    // Age buckets bar
    if(ageLabels.length){
        const ageCtx = document.getElementById('ageBucketsChart').getContext('2d');
        new Chart(ageCtx, {
            type: 'bar',
            data: { labels: ageLabels, datasets: [{ label: 'Count', data: ageData, backgroundColor: getColors(ageLabels.length) }] },
            options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }

    // Barangay bar (top 10)
    if(barangayLabels.length){
        const bCtx = document.getElementById('barangayChart').getContext('2d');
        new Chart(bCtx, {
            type: 'bar',
            data: { labels: barangayLabels, datasets: [{ label: 'Count', data: barangayData, backgroundColor: getColors(barangayLabels.length) }] },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }
</script>
@endsection
