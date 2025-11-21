<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\BarangayEvent;
use App\Models\Organization;
use App\Models\SKCouncil;
use App\Models\Youth;
use App\Services\DemographicsInsightService;
use App\Services\ReportGeneratorService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportGeneratorService $reportGenerator;

    protected DemographicsInsightService $demographicsInsight;

    public function __construct(ReportGeneratorService $reportGenerator, DemographicsInsightService $demographicsInsight)
    {
        $this->reportGenerator = $reportGenerator;
        $this->demographicsInsight = $demographicsInsight;
    }

    /**
     * Show reports main page with category overview
     */
    public function index()
    {
        // Get basic stats for each report category
        $stats = [
            'demographics' => [
                'total_youth' => Youth::count(),
                'active' => Youth::where('status', 'active')->count(),
                'out_of_school' => Youth::where('educational_attainment', 'out of school')->count(),
            ],
            'leadership' => [
                'councils' => SKCouncil::count(),
                'active_councils' => SKCouncil::where('is_active', true)->count(),
                'organizations' => Organization::count(),
            ],
            'engagement' => [
                'total_events' => BarangayEvent::count(),
                'upcoming' => BarangayEvent::where('date', '>', now())->count(),
                'past_month' => BarangayEvent::where('date', '>=', now()->subMonth())->count(),
            ],
            'profiles' => [
                'total' => Youth::count(),
                'complete' => Youth::whereNotNull('first_name')
                    ->whereNotNull('last_name')
                    ->whereNotNull('date_of_birth')
                    ->count(),
                'with_contact' => Youth::whereNotNull('contact_number')->count(),
            ],
        ];

        return view('municipal.reports.index', compact('stats'));
    }

    /**
     * Youth Demographics Report
     */
    public function demographics(Request $request)
    {
        $barangay = $request->get('barangay');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Youth::query();

        if ($barangay) {
            $query->where('barangay_id', $barangay);
        }

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $youth = $query->get();

        // Safely group data with fallback values
        $bySex = $youth->groupBy('sex')->map(fn ($g) => $g->count())->toArray();
        $byStatus = $youth->groupBy('status')->map(fn ($g) => $g->count())->toArray();
        $byEducation = $youth->groupBy('educational_attainment')->map(fn ($g) => $g->count())->toArray();
        $byIncome = $youth->groupBy('household_income')->map(fn ($g) => $g->count())->toArray();

        $reportData = [
            'total_youth' => $youth->count(),
            'by_age_group' => $this->groupByAge($youth),
            'by_sex' => $bySex ?: ['Male' => 0, 'Female' => 0],
            'by_status' => $byStatus ?: ['active' => 0, 'archived' => 0],
            'by_education' => $byEducation ?: ['Not Specified' => 0],
            'by_income' => $byIncome ?: ['Not Specified' => 0],
            'out_of_school' => $youth->where('educational_attainment', 'out of school')->count(),
        ];

        $barangays = Barangay::all();

        $insights = $this->reportGenerator->generateInsights(
            'youth_demographics',
            $reportData,
            $barangay ? "Barangay: {$barangay}" : 'Municipality-wide'
        );

        // Generate AI insights for each demographic section
        $aiInsights = $this->demographicsInsight->generateInsights($reportData);

        // Generate overall summary
        $aiSummary = $this->demographicsInsight->generateSummary($reportData);

        return view('municipal.reports.demographics', compact('youth', 'reportData', 'insights', 'aiInsights', 'aiSummary', 'barangays', 'barangay', 'startDate', 'endDate'));
    }

    /**
     * Youth Leadership & Governance Report
     */
    public function leadership(Request $request)
    {
        $barangay = $request->get('barangay');

        $query = SKCouncil::with('barangay');

        if ($barangay) {
            $query->where('barangay_id', $barangay);
        }

        $councils = $query->get();
        $organizations = Organization::get();

        // Count leaders by position from sk_councils
        $chairpersons = $councils->filter(fn ($c) => $c->chairperson_id)->count();
        $secretaries = $councils->filter(fn ($c) => $c->secretary_id)->count();
        $treasurers = $councils->filter(fn ($c) => $c->treasurer_id)->count();
        $kagawads = 0;
        foreach ($councils as $council) {
            $kagawadIds = is_array($council->kagawad_ids) ? $council->kagawad_ids : json_decode($council->kagawad_ids, true) ?? [];
            $kagawads += count($kagawadIds);
        }

        $leadersByPosition = [
            'Chairperson' => $chairpersons,
            'Secretary' => $secretaries,
            'Treasurer' => $treasurers,
            'Kagawad' => $kagawads,
        ];

        $reportData = [
            'total_councils' => $councils->count(),
            'active_councils' => $councils->where('is_active', true)->count(),
            'total_leaders' => $chairpersons + $secretaries + $treasurers + $kagawads,
            'positions_held' => $leadersByPosition,
            'organizations' => $organizations->count(),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_leadership',
            $reportData,
            $barangay ? "Barangay: {$barangay}" : 'Municipality-wide'
        );

        $barangays = Barangay::all();

        return view('municipal.reports.leadership', compact('councils', 'organizations', 'reportData', 'insights', 'barangays', 'barangay'));
    }

    /**
     * Youth Engagement & Events Report
     */
    /**
     * Youth Profiles Report (Individual Records)
     */
    public function profiles(Request $request)
    {
        $barangay = $request->get('barangay');
        $status = $request->get('status');
        $searchTerm = $request->get('search');

        $query = Youth::query();

        if ($barangay) {
            $query->where('barangay_id', $barangay);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%$searchTerm%")
                    ->orWhere('last_name', 'like', "%$searchTerm%")
                    ->orWhere('contact_number', 'like', "%$searchTerm%");
            });
        }

        $youth = $query->paginate(50);

        $reportData = [
            'total_records' => Youth::count(),
            'active_youth' => Youth::where('status', 'active')->count(),
            'archived_youth' => Youth::where('status', 'archived')->count(),
            'average_age' => round(Youth::whereNotNull('date_of_birth')
                ->selectRaw('AVG(YEAR(CURDATE()) - YEAR(date_of_birth)) as avg_age')
                ->pluck('avg_age')
                ->first() ?? 0, 1),
            'contact_available' => Youth::whereNotNull('contact_number')->count(),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_profiles',
            $reportData,
            $barangay ? "Barangay: {$barangay}" : 'Municipality-wide'
        );

        $barangays = Barangay::all();

        return view('municipal.reports.profiles', compact('youth', 'reportData', 'insights', 'barangays', 'barangay', 'status', 'searchTerm'));
    }

    /**
     * Data Quality & Completeness Report
     */
    /**
     * Group youth by age groups
     */
    private function groupByAge($youth): array
    {
        $groups = [
            '13-15' => 0,
            '16-18' => 0,
            '19-21' => 0,
            '22-25' => 0,
            '26-30' => 0,
        ];

        foreach ($youth as $y) {
            if (! $y->date_of_birth) {
                continue;
            }

            $age = $y->date_of_birth->diffInYears(now());

            if ($age >= 13 && $age <= 15) {
                $groups['13-15']++;
            } elseif ($age >= 16 && $age <= 18) {
                $groups['16-18']++;
            } elseif ($age >= 19 && $age <= 21) {
                $groups['19-21']++;
            } elseif ($age >= 22 && $age <= 25) {
                $groups['22-25']++;
            } elseif ($age >= 26 && $age <= 30) {
                $groups['26-30']++;
            }
        }

        return $groups;
    }
}
