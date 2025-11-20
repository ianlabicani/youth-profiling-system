<?php

namespace App\Http\Controllers\BRGY;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Youth;
use App\Models\SKCouncil;
use App\Models\BarangayEvent;
use App\Models\Organization;
use App\Services\ReportGeneratorService;
use App\Services\DemographicsInsightService;
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
        $barangay = auth()->user()->barangay;

        // Get basic stats for each report category
        $stats = [
            'demographics' => [
                'total_youth' => Youth::where('barangay_id', $barangay->id)->count(),
                'active' => Youth::where('barangay_id', $barangay->id)->where('status', 'active')->count(),
                'out_of_school' => Youth::where('barangay_id', $barangay->id)->where('educational_attainment', 'out of school')->count(),
            ],
            'leadership' => [
                'councils' => SKCouncil::where('barangay_id', $barangay->id)->count(),
                'active_councils' => SKCouncil::where('barangay_id', $barangay->id)->where('is_active', true)->count(),
                'organizations' => Organization::where('barangay_id', $barangay->id)->count(),
            ],
            'engagement' => [
                'total_events' => BarangayEvent::where('barangay_id', $barangay->id)->count(),
                'upcoming' => BarangayEvent::where('barangay_id', $barangay->id)->where('date', '>', now())->count(),
                'past_month' => BarangayEvent::where('barangay_id', $barangay->id)->where('date', '>=', now()->subMonth())->count(),
            ],
            'profiles' => [
                'total' => Youth::where('barangay_id', $barangay->id)->count(),
                'complete' => Youth::where('barangay_id', $barangay->id)
                    ->whereNotNull('first_name')
                    ->whereNotNull('last_name')
                    ->whereNotNull('date_of_birth')
                    ->count(),
                'with_contact' => Youth::where('barangay_id', $barangay->id)->whereNotNull('contact_number')->count(),
            ]
        ];

        return view('brgy.reports.index', compact('stats'));
    }

    /**
     * Youth Demographics Report
     */
    public function demographics(Request $request)
    {
        $barangay = auth()->user()->barangay;
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Youth::where('barangay_id', $barangay->id);

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

        $insights = $this->reportGenerator->generateInsights(
            'youth_demographics',
            $reportData,
            "Barangay: {$barangay->name}"
        );

        // Generate AI insights for each demographic section
        $aiInsights = $this->demographicsInsight->generateInsights($reportData);

        // Generate overall summary
        $aiSummary = $this->demographicsInsight->generateSummary($reportData);

        $barangays = Barangay::all();

        return view('brgy.reports.demographics', compact('youth', 'reportData', 'insights', 'aiInsights', 'aiSummary', 'startDate', 'endDate', 'barangay', 'barangays'));
    }

    /**
     * Youth Leadership & Governance Report
     */
    public function leadership()
    {
        $barangay = auth()->user()->barangay;

        $councils = SKCouncil::where('barangay_id', $barangay->id)->with('barangay')->get();
        $organizations = Organization::where('barangay_id', $barangay->id)->get();

        // Count leaders by position from sk_councils
        $chairpersons = $councils->filter(fn($c) => $c->chairperson_id)->count();
        $secretaries = $councils->filter(fn($c) => $c->secretary_id)->count();
        $treasurers = $councils->filter(fn($c) => $c->treasurer_id)->count();
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
            "Barangay: {$barangay->name}"
        );

        return view('brgy.reports.leadership', compact('councils', 'organizations', 'reportData', 'insights', 'barangay'));
    }

    /**
     * Youth Engagement & Events Report
     */
    public function engagement(Request $request)
    {
        $barangay = auth()->user()->barangay;
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = BarangayEvent::where('barangay_id', $barangay->id)->with('barangay', 'skCouncil');

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $events = $query->get();

        $reportData = [
            'total_events' => $events->count(),
            'events_by_barangay' => [$barangay->name => $events->count()],
            'participation_rate' => round(($events->sum('participants_count') / max(1, Youth::where('barangay_id', $barangay->id)->count())) * 100, 2),
            'active_participants' => $events->sum('participants_count'),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_engagement',
            $reportData,
            "Barangay: {$barangay->name}"
        );

        return view('brgy.reports.engagement', compact('events', 'reportData', 'insights', 'startDate', 'endDate', 'barangay'));
    }

    /**
     * Youth Profiles Report (Individual Records)
     */
    public function profiles(Request $request)
    {
        $barangay = auth()->user()->barangay;
        $status = $request->get('status');
        $searchTerm = $request->get('search');

        $query = Youth::where('barangay_id', $barangay->id);

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
            'total_records' => Youth::where('barangay_id', $barangay->id)->count(),
            'active_youth' => Youth::where('barangay_id', $barangay->id)->where('status', 'active')->count(),
            'archived_youth' => Youth::where('barangay_id', $barangay->id)->where('status', 'archived')->count(),
            'average_age' => round(Youth::where('barangay_id', $barangay->id)
                ->selectRaw('YEAR(CURDATE()) - YEAR(date_of_birth) as age')
                ->avg('age'), 1),
            'contact_available' => Youth::where('barangay_id', $barangay->id)->whereNotNull('contact_number')->count(),
        ];

        $insights = $this->reportGenerator->generateInsights(
            'youth_profiles',
            $reportData,
            "Barangay: {$barangay->name}"
        );

        return view('brgy.reports.profiles', compact('youth', 'reportData', 'insights', 'status', 'searchTerm', 'barangay'));
    }

    /**
     * Data Quality & Completeness Report
     */
    public function dataQuality()
    {
        $barangay = auth()->user()->barangay;

        $totalRecords = Youth::where('barangay_id', $barangay->id)->count();

        $completeRecords = Youth::where('barangay_id', $barangay->id)
            ->whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->whereNotNull('date_of_birth')
            ->count();

        $missingContacts = Youth::where('barangay_id', $barangay->id)->whereNull('contact_number')->count();

        $incompleteRecords = $totalRecords - $completeRecords;

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

        $insights = $this->reportGenerator->generateInsights('data_quality', $reportData, "Barangay: {$barangay->name}");

        return view('brgy.reports.data-quality', compact('reportData', 'insights', 'barangay'));
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
