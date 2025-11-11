<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\BarangayEvent;
use App\Models\SKCouncil;
use App\Models\Youth;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache key and TTL (seconds)
        $cacheKey = "municipal_dashboard";
        $ttl = 300; // 5 minutes

        $data = Cache::remember($cacheKey, $ttl, function () {
            // KPIs - Municipality-wide
            $totalYouth = Youth::count();
            $totalBarangays = Youth::distinct('barangay_id')->count('barangay_id');
            $activeCouncils = SKCouncil::where('is_active', true)->count();
            $totalOrganizations = Organization::count();

            $upcomingEvents = BarangayEvent::where('date', '>=', now()->toDateString())
                ->whereBetween('date', [now()->toDateString(), now()->addDays(30)->toDateString()])
                ->count();

            $eventsThisYear = BarangayEvent::whereYear('date', now()->year)->count();

            // Breakdowns - Municipality-wide
            $youthBySex = Youth::selectRaw('sex, count(*) as total')
                ->groupBy('sex')
                ->pluck('total', 'sex')
                ->toArray();

            $youthByStatus = Youth::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $education = Youth::selectRaw('educational_attainment, count(*) as total')
                ->groupBy('educational_attainment')
                ->orderByDesc('total')
                ->get();

            // Age buckets - Municipality-wide
            $ageBuckets = [
                '15-17' => 0,
                '18-20' => 0,
                '21-24' => 0,
                '25-30' => 0,
            ];

            $youths = Youth::select('date_of_birth')->get();
            foreach ($youths as $y) {
                if (! $y->date_of_birth) {
                    continue;
                }
                $age = Carbon::parse($y->date_of_birth)->age;
                if ($age >= 15 && $age <= 17) {
                    $ageBuckets['15-17']++;
                } elseif ($age >= 18 && $age <= 20) {
                    $ageBuckets['18-20']++;
                } elseif ($age >= 21 && $age <= 24) {
                    $ageBuckets['21-24']++;
                } elseif ($age >= 25 && $age <= 30) {
                    $ageBuckets['25-30']++;
                }
            }

            // Registrations per month (last 12 months)
            $months = [];
            $dataRegs = [];
            for ($i = 11; $i >= 0; $i--) {
                $m = now()->subMonths($i)->format('Y-m');
                $months[] = $m;
                $start = Carbon::parse($m.'-01')->startOfMonth();
                $end = (clone $start)->endOfMonth();
                $dataRegs[] = Youth::whereBetween('created_at', [$start, $end])->count();
            }

            // Council stats and youth positions - Municipality-wide
            $councils = SKCouncil::withCount('events')->get();

            $positionIds = collect();
            foreach ($councils as $c) {
                if ($c->chairperson_id) {
                    $positionIds->push($c->chairperson_id);
                }
                if ($c->secretary_id) {
                    $positionIds->push($c->secretary_id);
                }
                if ($c->treasurer_id) {
                    $positionIds->push($c->treasurer_id);
                }
                if (is_array($c->kagawad_ids)) {
                    foreach ($c->kagawad_ids as $kid) {
                        $positionIds->push($kid);
                    }
                }
            }

            $distinctPositionsCount = $positionIds->filter()->unique()->count();

            $chairCount = SKCouncil::whereNotNull('chairperson_id')->count();
            $secretaryCount = SKCouncil::whereNotNull('secretary_id')->count();
            $treasurerCount = SKCouncil::whereNotNull('treasurer_id')->count();
            $kagawadTotal = $positionIds->filter()->count() - ($chairCount + $secretaryCount + $treasurerCount);

            // Organization stats
            $totalOfficers = Organization::query()
                ->whereNotNull('president_id')
                ->orWhereNotNull('vice_president_id')
                ->orWhereNotNull('secretary_id')
                ->orWhereNotNull('treasurer_id')
                ->count();

            // Quick lists
            $upcomingList = BarangayEvent::where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->limit(5)
                ->get();

            $recentYouth = Youth::orderByDesc('created_at')->limit(5)->get();

            // Out of school count
            $outOfSchoolCount = Youth::whereNull('educational_attainment')
                ->orWhere('educational_attainment', '')
                ->count();

            // Youth by barangay
            $youthByBarangay = Youth::selectRaw('barangay_id, count(*) as total')
                ->groupBy('barangay_id')
                ->with('barangay')
                ->orderByDesc('total')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'barangay' => $item->barangay?->name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                });

            return compact(
                'totalYouth', 'totalBarangays', 'activeCouncils', 'totalOrganizations', 'upcomingEvents', 'eventsThisYear',
                'youthBySex', 'youthByStatus', 'education', 'ageBuckets', 'months', 'dataRegs',
                'councils', 'distinctPositionsCount', 'chairCount', 'secretaryCount', 'treasurerCount', 'kagawadTotal',
                'upcomingList', 'recentYouth', 'outOfSchoolCount', 'youthByBarangay', 'totalOfficers'
            );
        });

        return view('municipal.dashboard', $data);
    }

    public function heatmap()
    {
        $query = Youth::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0);

        // Filter by barangay if specified
        if ($barangayId = request('barangay_id')) {
            $query->where('barangay_id', $barangayId);
        }

        $youths = $query->get();

        // Get all barangays for filter dropdown
        $barangays = \App\Models\Barangay::orderBy('name')->get();

        return view('municipal.heatmap', compact('youths', 'barangays'));
    }
}
