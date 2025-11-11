<?php

namespace App\Http\Controllers\BRGY;

use App\Http\Controllers\Controller;
use App\Models\BarangayEvent;
use App\Models\SKCouncil;
use App\Models\Youth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $userBarangay = auth()->user()->barangays()->first();
        if (! $userBarangay) {
            abort(403, 'No barangay assigned');
        }

        $barangayId = $userBarangay->id;

        // Cache key and TTL (seconds)
        $cacheKey = "brgy_dashboard:{$barangayId}";
        $ttl = 300; // 5 minutes

        $data = Cache::remember($cacheKey, $ttl, function () use ($barangayId) {
            // KPIs
            $totalYouth = Youth::where('barangay_id', $barangayId)->count();
            $activeCouncils = SKCouncil::where('barangay_id', $barangayId)->where('is_active', true)->count();
            $upcomingEvents = BarangayEvent::where('barangay_id', $barangayId)
                ->whereBetween('date', [now()->toDateString(), now()->addDays(30)->toDateString()])
                ->count();
            $eventsThisYear = BarangayEvent::where('barangay_id', $barangayId)->whereYear('date', now()->year)->count();

            // Breakdowns
            $youthBySex = Youth::where('barangay_id', $barangayId)
                ->selectRaw('sex, count(*) as total')
                ->groupBy('sex')
                ->pluck('total', 'sex')
                ->toArray();

            $youthByStatus = Youth::where('barangay_id', $barangayId)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $education = Youth::where('barangay_id', $barangayId)
                ->selectRaw('educational_attainment, count(*) as total')
                ->groupBy('educational_attainment')
                ->orderByDesc('total')
                ->get();

            // Age buckets
            $ageBuckets = [
                '15-17' => 0,
                '18-20' => 0,
                '21-24' => 0,
                '25-30' => 0,
            ];

            $youths = Youth::where('barangay_id', $barangayId)->select('date_of_birth')->get();
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
                $dataRegs[] = Youth::where('barangay_id', $barangayId)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }

            // Council stats and youth positions
            $councils = SKCouncil::where('barangay_id', $barangayId)->withCount('events')->get();

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

            $chairCount = SKCouncil::where('barangay_id', $barangayId)->whereNotNull('chairperson_id')->count();
            $secretaryCount = SKCouncil::where('barangay_id', $barangayId)->whereNotNull('secretary_id')->count();
            $treasurerCount = SKCouncil::where('barangay_id', $barangayId)->whereNotNull('treasurer_id')->count();
            $kagawadTotal = $positionIds->filter()->count() - ($chairCount + $secretaryCount + $treasurerCount);

            // Quick lists
            $upcomingList = BarangayEvent::where('barangay_id', $barangayId)
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->limit(5)
                ->get();

            $recentYouth = Youth::where('barangay_id', $barangayId)->orderByDesc('created_at')->limit(5)->get();

            return compact(
                'totalYouth', 'activeCouncils', 'upcomingEvents', 'eventsThisYear',
                'youthBySex', 'youthByStatus', 'education', 'ageBuckets', 'months', 'dataRegs',
                'councils', 'distinctPositionsCount', 'chairCount', 'secretaryCount', 'treasurerCount', 'kagawadTotal',
                'upcomingList', 'recentYouth'
            );
        });

        // Extract cached values
        $totalYouth = $data['totalYouth'];
        $activeCouncils = $data['activeCouncils'];
        $upcomingEvents = $data['upcomingEvents'];
        $eventsThisYear = $data['eventsThisYear'];
        $youthBySex = $data['youthBySex'];
        $youthByStatus = $data['youthByStatus'];
        $education = $data['education'];
        $ageBuckets = $data['ageBuckets'];
        $months = $data['months'];
        $dataRegs = $data['dataRegs'];
        $councils = $data['councils'];
        $distinctPositionsCount = $data['distinctPositionsCount'];
        $chairCount = $data['chairCount'];
        $secretaryCount = $data['secretaryCount'];
        $treasurerCount = $data['treasurerCount'];
        $kagawadTotal = $data['kagawadTotal'];
        $upcomingList = $data['upcomingList'];
        $recentYouth = $data['recentYouth'];

        return view('brgy.dashboard', compact(
            'userBarangay', 'totalYouth', 'activeCouncils', 'upcomingEvents', 'eventsThisYear',
            'youthBySex', 'youthByStatus', 'education', 'ageBuckets', 'months', 'dataRegs',
            'councils', 'distinctPositionsCount', 'chairCount', 'secretaryCount', 'treasurerCount', 'kagawadTotal',
            'upcomingList', 'recentYouth'
        ));
    }
}
