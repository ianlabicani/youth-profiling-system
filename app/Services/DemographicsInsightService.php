<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DemographicsInsightService
{
    protected DashboardDescriptionService $descriptionService;

    public function __construct(DashboardDescriptionService $descriptionService)
    {
        $this->descriptionService = $descriptionService;
    }

    /**
     * Generate AI insights for demographics report data
     */
    public function generateInsights(array $reportData): array
    {
        $cacheKey = $this->generateCacheKey($reportData);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($reportData) {
            return $this->callGeminiAPI($reportData);
        });
    }

    /**
     * Generate overall summary for demographics
     */
    public function generateSummary(array $reportData): string
    {
        $cacheKey = 'demographics_summary_' . md5(json_encode($reportData));

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($reportData) {
            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                \Log::warning('Gemini API key is not configured');
                return $this->getDefaultSummary($reportData);
            }

            $prompt = $this->buildSummaryPrompt($reportData);
            return $this->callGemini($apiKey, 'gemini-2.0-flash', $prompt);
        });
    }

    /**
     * Call Gemini API to generate insights
     */
    private function callGeminiAPI(array $reportData): array
    {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            \Log::warning('Gemini API key is not configured');
            return $this->getDefaultInsights($reportData);
        }

        $model = 'gemini-2.0-flash';

        $insights = [];

        // Generate Age Group Insight
        $agePrompt = $this->buildAgeGroupPrompt($reportData);
        $insights['age_group'] = [
            'title' => '📊 Age Group Distribution',
            'description' => $this->callGemini($apiKey, $model, $agePrompt),
            'icon' => '📊'
        ];

        // Generate Sex Distribution Insight
        $sexPrompt = $this->buildSexPrompt($reportData);
        $insights['sex'] = [
            'title' => '👥 Sex Distribution',
            'description' => $this->callGemini($apiKey, $model, $sexPrompt),
            'icon' => '👥'
        ];

        // Generate Education Insight
        $educationPrompt = $this->buildEducationPrompt($reportData);
        $insights['education'] = [
            'title' => '🎓 Educational Attainment',
            'description' => $this->callGemini($apiKey, $model, $educationPrompt),
            'icon' => '🎓'
        ];

        // Generate Income Insight
        $incomePrompt = $this->buildIncomePrompt($reportData);
        $insights['income'] = [
            'title' => '💰 Household Income',
            'description' => $this->callGemini($apiKey, $model, $incomePrompt),
            'icon' => '💰'
        ];

        return $insights;
    }

    /**
     * Call Gemini API and return text response
     */
    private function callGemini(string $apiKey, string $model, string $prompt): string
    {
        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]
            );

            if ($response->successful() && isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                return $response['candidates'][0]['content']['parts'][0]['text'];
            }

            return 'Unable to generate insight at this time.';
        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
            return 'Unable to generate insight at this time.';
        }
    }

    /**
     * Build prompt for age group analysis
     */
    private function buildAgeGroupPrompt(array $reportData): string
    {
        $ageData = $reportData['by_age_group'];
        $total = $reportData['total_youth'];

        $ageBreakdown = implode(', ', array_map(
            fn($group, $count) => "{$group}: {$count} youth (" . round(($count / max(1, $total)) * 100, 1) . "%)",
            array_keys($ageData),
            $ageData
        ));

        return <<<PROMPT
Analyze this youth age group distribution data for a municipality:

Total Youth: {$total}
Age Breakdown: {$ageBreakdown}

Provide a brief, professional insight (2-3 sentences) that:
1. Identifies the dominant age group and its implications
2. Notes any significant gaps or imbalances
3. Suggests one actionable recommendation

Format your response as:
Finding: [observation]
Key Insight: [main takeaway]
Recommendation: [actionable suggestion]
PROMPT;
    }

    /**
     * Build prompt for sex distribution analysis
     */
    private function buildSexPrompt(array $reportData): string
    {
        $sexData = $reportData['by_sex'];
        $total = $reportData['total_youth'];

        $sexBreakdown = implode(', ', array_map(
            fn($sex, $count) => "{$sex}: {$count} youth (" . round(($count / max(1, $total)) * 100, 1) . "%)",
            array_keys($sexData),
            $sexData
        ));

        return <<<PROMPT
Analyze this youth sex/gender distribution data for a municipality:

Total Youth: {$total}
Sex Distribution: {$sexBreakdown}

Provide a brief, professional insight (2-3 sentences) that:
1. Describes the gender balance or diversity
2. Highlights representation of different gender categories
3. Suggests one actionable recommendation for inclusive programs

Format your response as:
Finding: [observation]
Key Insight: [main takeaway]
Recommendation: [actionable suggestion]
PROMPT;
    }

    /**
     * Build prompt for education analysis
     */
    private function buildEducationPrompt(array $reportData): string
    {
        $educationData = $reportData['by_education'];
        $total = $reportData['total_youth'];
        $outOfSchool = $reportData['out_of_school'];

        $educationBreakdown = implode(', ', array_map(
            fn($level, $count) => "{$level}: {$count} youth (" . round(($count / max(1, $total)) * 100, 1) . "%)",
            array_keys($educationData),
            $educationData
        ));

        return <<<PROMPT
Analyze this youth educational attainment data for a municipality:

Total Youth: {$total}
Out of School: {$outOfSchool} youth
Education Levels: {$educationBreakdown}

Provide a brief, professional insight (2-3 sentences) that:
1. Assesses overall educational attainment levels
2. Identifies any critical gaps (e.g., out-of-school youth)
3. Suggests one actionable recommendation for education programs

Format your response as:
Finding: [observation]
Key Insight: [main takeaway]
Recommendation: [actionable suggestion]
PROMPT;
    }

    /**
     * Build prompt for income analysis
     */
    private function buildIncomePrompt(array $reportData): string
    {
        $incomeData = $reportData['by_income'];
        $total = $reportData['total_youth'];

        // Filter out empty key
        $incomeData = array_filter($incomeData, fn($k) => $k !== '', ARRAY_FILTER_USE_KEY);

        $incomeBreakdown = implode(', ', array_map(
            fn($bracket, $count) => "{$bracket}: {$count} youth (" . round(($count / max(1, $total)) * 100, 1) . "%)",
            array_keys($incomeData),
            $incomeData
        ));

        $unspecified = $reportData['by_income'][''] ?? 0;

        return <<<PROMPT
Analyze this youth household income distribution data for a municipality:

Total Youth: {$total}
Income Distribution: {$incomeBreakdown}
Unspecified/No Data: {$unspecified} youth

Provide a brief, professional insight (2-3 sentences) that:
1. Describes the economic diversity of the youth population
2. Notes data quality issues if there are many unspecified records
3. Suggests one actionable recommendation for economic programs

Format your response as:
Finding: [observation]
Key Insight: [main takeaway]
Recommendation: [actionable suggestion]
PROMPT;
    }

    /**
     * Generate cache key based on report data
     */
    private function generateCacheKey(array $reportData): string
    {
        $hash = md5(json_encode($reportData));
        return "demographics_insights_{$hash}";
    }

    /**
     * Get default insights when API is unavailable
     */
    private function getDefaultInsights(array $reportData): array
    {
        return [
            'age_group' => [
                'title' => '📊 Age Group Distribution',
                'description' => 'Age group distribution data is available. Review the chart above for detailed breakdown by age ranges.',
                'icon' => '📊'
            ],
            'sex' => [
                'title' => '👥 Sex Distribution',
                'description' => 'Gender distribution shows diverse representation across your youth population.',
                'icon' => '👥'
            ],
            'education' => [
                'title' => '🎓 Educational Attainment',
                'description' => 'Educational attainment levels are displayed in the chart. Consider developing targeted programs based on education levels.',
                'icon' => '🎓'
            ],
            'income' => [
                'title' => '💰 Household Income',
                'description' => 'Household income distribution is shown above. This data can inform economic support and opportunity programs.',
                'icon' => '💰'
            ]
        ];
    }

    /**
     * Build summary prompt for overall demographics analysis
     */
    private function buildSummaryPrompt(array $reportData): string
    {
        $total = $reportData['total_youth'];
        $ageData = $reportData['by_age_group'];
        $dominantAgeGroup = array_search(max($ageData), $ageData);
        $dominantAgeCount = $ageData[$dominantAgeGroup] ?? 0;

        $sexDistribution = implode(', ', array_map(
            fn($sex, $count) => "{$sex}: {$count} (" . round(($count / max(1, $total)) * 100, 1) . "%)",
            array_keys($reportData['by_sex']),
            $reportData['by_sex']
        ));

        $educationData = $reportData['by_education'];
        $topEducation = array_search(max($educationData), $educationData);

        return <<<PROMPT
Analyze this youth demographic data and provide a structured executive summary:

Total Youth: {$total}
Dominant Age Group: {$dominantAgeGroup} years ({$dominantAgeCount} youth, representing {$dominantAgeCount}/{$total} or ~" . round(($dominantAgeCount/$total)*100, 1) . "%}
Sex Distribution: {$sexDistribution}
Top Education Level: {$topEducation}

Format your response exactly like this (use these emojis and structure):

📊 Youth Demographics Overview

Finding: [A data-driven observation about the demographic composition with specific numbers/percentages]

🔍 Key Insight: [The most significant insight about this youth population in 2-3 sentences, explaining what this demographic profile means]

💡 Recommendation: [A strategic suggestion for youth programs or initiatives based on these demographics in 2-3 sentences]

Be concise, specific with numbers, and actionable.
PROMPT;
    }

    /**
     * Get default summary when API is unavailable
     */
    private function getDefaultSummary(array $reportData): string
    {
        $total = $reportData['total_youth'];
        $ageData = $reportData['by_age_group'];
        $dominantAge = array_search(max($ageData), $ageData);
        $dominantCount = $ageData[$dominantAge] ?? 0;
        $dominantPercent = round(($dominantCount / max(1, $total)) * 100, 1);

        return <<<SUMMARY
📊 Youth Demographics Overview

Finding: Your municipality has {$total} registered youth, with {$dominantPercent}% concentrated in the {$dominantAge} age group ({$dominantCount} youth). The population demonstrates diverse distribution across education, income, and employment status categories.

🔍 Key Insight: The concentration in the {$dominantAge} age bracket indicates a young, dynamic population requiring tailored interventions. Understanding this demographic composition is essential for designing effective youth development programs and policies.

💡 Recommendation: Prioritize youth programs that address the specific needs of the {$dominantAge} age group while building inclusive initiatives that reach across all demographic segments. Focus on education, employment readiness, and skills development as primary strategic areas.
SUMMARY;
    }
}
