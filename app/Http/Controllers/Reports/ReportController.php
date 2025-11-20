<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Youth;
use App\Models\SKCouncil;
use App\Models\BarangayEvent;
use App\Models\Organization;
use App\Services\ReportGeneratorService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportGeneratorService $reportGenerator;

    public function __construct(ReportGeneratorService $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
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
            ]
        ];

        return view('reports.index', compact('stats'));
    }

    /**
     * Youth Demographics Report
     */
    public function demographics(Request $request)
    {
        $barangay = $request->get('barangay');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Base query
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

        // Prepare data for insights
        $reportData = [
            'total_youth' => $youth->count(),
            'by_age_group' => $this->groupByAge($youth),
            'by_sex' => $youth->groupBy('sex')->map(fn($g) => $g->count())->toArray(),
            'by_status' => $youth->groupBy('status')->map(fn($g) => $g->count())->toArray(),
            'by_education' => $youth->groupBy('educational_attainment')->map(fn($g) => $g->count())->toArray(),
            'by_income' => $youth->groupBy('household_income')->map(fn($g) => $g->count())->toArray(),
            'out_of_school' => $youth->where('educational_attainment', 'out of school')->count(),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_demographics',
            $reportData,
            $barangay ? "Barangay: {$barangay}" : "Municipality-wide"
        );

        $barangays = Barangay::all();

        return view('reports.demographics', compact('youth', 'reportData', 'insights', 'barangays', 'barangay', 'startDate', 'endDate'));
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

        // Count leaders by position
        $leadersByPosition = Youth::whereNotNull('leadership_position')
            ->groupBy('leadership_position')
            ->selectRaw('leadership_position, count(*) as count')
            ->get()
            ->pluck('count', 'leadership_position')
            ->toArray();

        $reportData = [
            'total_councils' => $councils->count(),
            'active_councils' => $councils->where('is_active', true)->count(),
            'total_leaders' => Youth::whereNotNull('leadership_position')->count(),
            'positions_held' => $leadersByPosition,
            'organizations' => $organizations->count(),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_leadership',
            $reportData,
            $barangay ? "Barangay: {$barangay}" : "Municipality-wide"
        );

        $barangays = Barangay::all();

        return view('reports.leadership', compact('councils', 'organizations', 'reportData', 'insights', 'barangays', 'barangay'));
    }

    /**
     * Youth Engagement & Events Report
     */
    public function engagement(Request $request)
    {
        $barangay = $request->get('barangay');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = BarangayEvent::with('barangay', 'skCouncil');

        if ($barangay) {
            $query->where('barangay_id', $barangay);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $events = $query->get();

        $eventsByBarangay = BarangayEvent::groupBy('barangay_id')
            ->selectRaw('barangay_id, count(*) as count')
            ->get()
            ->pluck('count', 'barangay_id')
            ->toArray();

        $reportData = [
            'total_events' => $events->count(),
            'events_by_barangay' => $eventsByBarangay,
            'participation_rate' => round(($events->sum('participants_count') / max(1, Youth::count())) * 100, 2),
            'active_participants' => $events->sum('participants_count'),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_engagement',
            $reportData,
            $barangay ? "Barangay: {$barangay}" : "Municipality-wide"
        );

        $barangays = Barangay::all();

        return view('reports.engagement', compact('events', 'reportData', 'insights', 'barangays', 'barangay', 'startDate', 'endDate'));
    }

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
            'average_age' => round(Youth::selectRaw('YEAR(CURDATE()) - YEAR(date_of_birth) as age')
                ->avg('age'), 1),
            'contact_available' => Youth::whereNotNull('contact_number')->count(),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_profiles',
            $reportData,
            $barangay ? "Barangay: {$barangay}" : "Municipality-wide"
        );

        $barangays = Barangay::all();

        return view('reports.profiles', compact('youth', 'reportData', 'insights', 'barangays', 'barangay', 'status', 'searchTerm'));
    }

    /**
     * Data Quality & Completeness Report
     */
    public function dataQuality(Request $request)
    {
        $totalRecords = Youth::count();

        // Check completeness
        $completeRecords = Youth::whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->whereNotNull('date_of_birth')
            ->whereNotNull('barangay_id')
            ->count();

        $missingContacts = Youth::whereNull('contact_number')->count();

        $incompleteRecords = $totalRecords - $completeRecords;

        // Calculate accuracy score (higher is better)
        $accuracyScore = $totalRecords > 0
            ? round(($completeRecords / $totalRecords) * 100, 2)
            : 0;

        $reportData = [
            'total_records' => $totalRecords,
            'complete_records' => $completeRecords,
            'missing_contacts' => $missingContacts,
            'incomplete_profiles' => $incompleteRecords,
            'accuracy_score' => $accuracyScore,
        ];

        $insights = $this->reportGenerator->generateInsights('data_quality', $reportData);

        return view('reports.data-quality', compact('reportData', 'insights'));
    }

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
            if (!$y->date_of_birth) continue;

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
