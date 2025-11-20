<?php

namespace App\Http\Controllers\BRGY;

use App\Http\Controllers\Controller;
use App\Models\BarangayEvent;
use App\Models\SKCouncil;
use App\Models\Youth;
use App\Services\DashboardDescriptionService;
use Barryvdh\DomPDF\Facade\Pdf;
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

            // Income ranges
            $incomeRanges = Youth::where('barangay_id', $barangayId)
                ->whereNotNull('household_income')
                ->selectRaw('household_income, count(*) as total')
                ->groupBy('household_income')
                ->orderByRaw("FIELD(household_income, 'No Income', 'Below 10,000', '10,000 - 20,000', '20,000 - 30,000', '30,000 - 40,000', '40,000 - 50,000', 'Above 50,000')")
                ->pluck('total', 'household_income')
                ->toArray();

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
                'youthBySex', 'youthByStatus', 'education', 'incomeRanges', 'ageBuckets', 'months', 'dataRegs',
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
        $incomeRanges = $data['incomeRanges'];
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

        // Get AI descriptions for dashboard stats
        $descService = new DashboardDescriptionService();
        $descriptions = [
            'total_youth' => $descService->getDescription('total_youth', $totalYouth, "Barangay: {$userBarangay->name}"),
            'active_councils' => $descService->getDescription('active_councils', $activeCouncils, "Barangay: {$userBarangay->name}"),
            'upcoming_events' => $descService->getDescription('upcoming_events', $upcomingEvents),
            'events_this_year' => $descService->getDescription('events_this_year', $eventsThisYear),
            'youth_by_sex' => $descService->getDescription('youth_by_sex', array_sum($youthBySex), "Barangay: {$userBarangay->name}"),
            'youth_by_status' => $descService->getDescription('youth_by_status', array_sum($youthByStatus), "Barangay: {$userBarangay->name}"),
            'education' => $descService->getDescription('education', $education->count(), "Barangay: {$userBarangay->name}"),
            'age_distribution' => $descService->getDescription('age_distribution', array_sum($ageBuckets)),
            'household_income' => $descService->getDescription('household_income', array_sum($incomeRanges), "Barangay: {$userBarangay->name}"),
            'council_positions' => $descService->getDescription('council_positions', $distinctPositionsCount),
        ];

        return view('brgy.dashboard', compact(
            'userBarangay', 'totalYouth', 'activeCouncils', 'upcomingEvents', 'eventsThisYear',
            'youthBySex', 'youthByStatus', 'education', 'incomeRanges', 'ageBuckets', 'months', 'dataRegs',
            'councils', 'distinctPositionsCount', 'chairCount', 'secretaryCount', 'treasurerCount', 'kagawadTotal',
            'upcomingList', 'recentYouth', 'descriptions'
        ));
    }

    /**
     * Export dashboard to PDF or Excel
     */
    public function export(string $format = 'pdf')
    {
        $userBarangay = auth()->user()->barangays()->first();
        if (! $userBarangay) {
            abort(403, 'No barangay assigned');
        }

        $barangayId = $userBarangay->id;

        // Get dashboard data without caching to ensure fresh export
        $totalYouth = Youth::where('barangay_id', $barangayId)->count();
        $activeCouncils = SKCouncil::where('barangay_id', $barangayId)->where('is_active', true)->count();
        $upcomingEvents = BarangayEvent::where('barangay_id', $barangayId)
            ->whereBetween('date', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->count();
        $eventsThisYear = BarangayEvent::where('barangay_id', $barangayId)->whereYear('date', now()->year)->count();

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

        $incomeRanges = Youth::where('barangay_id', $barangayId)
            ->whereNotNull('household_income')
            ->selectRaw('household_income, count(*) as total')
            ->groupBy('household_income')
            ->orderByRaw("FIELD(household_income, 'No Income', 'Below 10,000', '10,000 - 20,000', '20,000 - 30,000', '30,000 - 40,000', '40,000 - 50,000', 'Above 50,000')")
            ->pluck('total', 'household_income')
            ->toArray();

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

        if ($format === 'excel') {
            return $this->exportToExcel($userBarangay, $totalYouth, $activeCouncils, $upcomingEvents, $eventsThisYear, $youthBySex, $youthByStatus, $education, $incomeRanges, $ageBuckets);
        } else {
            return $this->exportToPdf($userBarangay, $totalYouth, $activeCouncils, $upcomingEvents, $eventsThisYear, $youthBySex, $youthByStatus, $education, $incomeRanges, $ageBuckets);
        }
    }

    /**
     * Export dashboard to Excel
     */
    private function exportToExcel($userBarangay, $totalYouth, $activeCouncils, $upcomingEvents, $eventsThisYear, $youthBySex, $youthByStatus, $education, $incomeRanges, $ageBuckets)
    {
        $filename = 'dashboard_' . str_slug($userBarangay->name) . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($userBarangay, $totalYouth, $activeCouncils, $upcomingEvents, $eventsThisYear, $youthBySex, $youthByStatus, $education, $incomeRanges, $ageBuckets) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['DASHBOARD REPORT']);
            fputcsv($file, ['Barangay', $userBarangay->name ?? '']);
            fputcsv($file, ['Generated', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            // KPIs
            fputcsv($file, ['KEY PERFORMANCE INDICATORS']);
            fputcsv($file, ['Total Youth', $totalYouth]);
            fputcsv($file, ['Active SK Councils', $activeCouncils]);
            fputcsv($file, ['Upcoming Events (30 days)', $upcomingEvents]);
            fputcsv($file, ['Events This Year', $eventsThisYear]);
            fputcsv($file, []);

            // Youth by Sex
            fputcsv($file, ['YOUTH BY SEX']);
            fputcsv($file, ['Sex', 'Count']);
            foreach ($youthBySex as $sex => $count) {
                fputcsv($file, [$sex ?? 'Unknown', $count]);
            }
            fputcsv($file, []);

            // Youth by Status
            fputcsv($file, ['YOUTH BY STATUS']);
            fputcsv($file, ['Status', 'Count']);
            foreach ($youthByStatus as $status => $count) {
                fputcsv($file, [$status ?? 'Unknown', $count]);
            }
            fputcsv($file, []);

            // Education
            fputcsv($file, ['EDUCATIONAL ATTAINMENT']);
            fputcsv($file, ['Education Level', 'Count']);
            foreach ($education as $edu) {
                fputcsv($file, [$edu->educational_attainment ?? 'Unknown', $edu->total]);
            }
            fputcsv($file, []);

            // Age Buckets
            fputcsv($file, ['AGE DISTRIBUTION']);
            fputcsv($file, ['Age Range', 'Count']);
            foreach ($ageBuckets as $range => $count) {
                fputcsv($file, [$range, $count]);
            }
            fputcsv($file, []);

            // Income Ranges
            fputcsv($file, ['HOUSEHOLD INCOME']);
            fputcsv($file, ['Income Range', 'Count']);
            foreach ($incomeRanges as $income => $count) {
                fputcsv($file, [$income ?? 'Unknown', $count]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export dashboard to PDF
     */
    private function exportToPdf($userBarangay, $totalYouth, $activeCouncils, $upcomingEvents, $eventsThisYear, $youthBySex, $youthByStatus, $education, $incomeRanges, $ageBuckets)
    {
        $data = [
            'barangay' => $userBarangay,
            'totalYouth' => $totalYouth,
            'activeCouncils' => $activeCouncils,
            'upcomingEvents' => $upcomingEvents,
            'eventsThisYear' => $eventsThisYear,
            'youthBySex' => $youthBySex,
            'youthByStatus' => $youthByStatus,
            'education' => $education,
            'incomeRanges' => $incomeRanges,
            'ageBuckets' => $ageBuckets,
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.brgy-dashboard-pdf', $data);
        return $pdf->download('dashboard_' . str_slug($userBarangay->name) . '_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
