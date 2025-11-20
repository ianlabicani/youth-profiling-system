<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ReportGeneratorService
{
    private const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    /**
     * Generate AI insights for report data
     */
    public function generateInsights(string $reportType, array $data, string $context = ''): string
    {
        try {
            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                \Log::warning('Gemini API key is not configured');
                return $this->getDefaultInsight($reportType);
            }

            // Build the prompt based on report type
            $prompt = $this->buildInsightPrompt($reportType, $data, $context);

            // Call Gemini API REST endpoint
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post(
                self::GEMINI_API_URL,
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ]
                ]
            );

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    return trim($responseData['candidates'][0]['content']['parts'][0]['text']);
                }
            }

            \Log::warning('Gemini API response error for report insights: ' . $response->body());
            return $this->getDefaultInsight($reportType);
        } catch (\Exception $e) {
            \Log::warning('Report insight generation failed: ' . $e->getMessage());
            return $this->getDefaultInsight($reportType);
        }
    }

    /**
     * Build prompt for specific report type
     */
    private function buildInsightPrompt(string $reportType, array $data, string $context = ''): string
    {
        $dataJson = json_encode($data, JSON_PRETTY_PRINT);
        $contextStr = $context ? "\nContext: $context" : '';

        $prompts = [
            'youth_demographics' => "Analyze this youth demographics report data and provide:\n1. Key findings (2-3 bullet points)\n2. One trend or pattern identified\n3. One actionable recommendation\n\nData:\n$dataJson$contextStr\n\nKeep response concise and focused on insights relevant to youth profiling and resource allocation.",

            'youth_leadership' => "Analyze this youth leadership report data and provide:\n1. Leadership coverage analysis (2-3 bullet points)\n2. Gaps or opportunities in youth representation\n3. One recommendation for improving youth engagement\n\nData:\n$dataJson$contextStr\n\nKeep response focused on governance and leadership development.",

            'youth_engagement' => "Analyze this youth engagement and events report data and provide:\n1. Engagement effectiveness assessment (2-3 bullet points)\n2. Participation trends or patterns\n3. One recommendation for improving youth engagement\n\nData:\n$dataJson$contextStr\n\nKeep response focused on activity effectiveness and program optimization.",

            'youth_profiles' => "Analyze this youth profile data and provide:\n1. Population characteristics (2-3 key points)\n2. Support needs assessment\n3. One recommendation for targeted programs\n\nData:\n$dataJson$contextStr\n\nKeep response focused on individual and community needs.",

            'data_quality' => "Analyze this data quality report and provide:\n1. Data completeness assessment (2-3 bullet points)\n2. Critical gaps to address\n3. One recommendation for data improvement\n\nData:\n$dataJson$contextStr\n\nKeep response focused on data accuracy and completeness."
        ];

        return $prompts[$reportType] ?? "Provide insights on this report data:\n$dataJson$contextStr";
    }

    /**
     * Get default insight when API fails
     */
    private function getDefaultInsight(string $reportType): string
    {
        $defaults = [
            'youth_demographics' => '📊 Demographics Report\n• Detailed breakdown of youth population by key metrics\n• Data organized by barangay, age, education, and income\n• Enable filters to focus on specific demographics',

            'youth_leadership' => '👥 Leadership Report\n• Overview of youth in leadership positions\n• SK Council and organization structure analysis\n• Identifies youth engagement in governance',

            'youth_engagement' => '🎯 Engagement Report\n• Summary of youth participation in events and programs\n• Tracks activity levels and program effectiveness\n• Identifies engagement trends and opportunities',

            'youth_profiles' => '👤 Youth Profiles Report\n• Individual and aggregated youth profile data\n• Contact information and personal details\n• Support needs and recommendations',

            'data_quality' => '✓ Data Quality Report\n• System data completeness and accuracy metrics\n• Identifies records needing updates\n• Recommendations for data improvement'
        ];

        return $defaults[$reportType] ?? 'Report generated successfully. Use filters to customize the data view.';
    }

    /**
     * Prepare data for insight generation
     */
    public function prepareReportData(string $reportType, array $rawData): array
    {
        return match($reportType) {
            'youth_demographics' => $this->prepareDemographicsData($rawData),
            'youth_leadership' => $this->prepareLeadershipData($rawData),
            'youth_engagement' => $this->prepareEngagementData($rawData),
            'youth_profiles' => $this->prepareProfilesData($rawData),
            'data_quality' => $this->prepareQualityData($rawData),
            default => $rawData
        };
    }

    private function prepareDemographicsData(array $data): array
    {
        return [
            'total_youth' => $data['total_youth'] ?? 0,
            'by_age_group' => $data['by_age_group'] ?? [],
            'by_sex' => $data['by_sex'] ?? [],
            'by_status' => $data['by_status'] ?? [],
            'by_education' => $data['by_education'] ?? [],
            'by_income' => $data['by_income'] ?? [],
            'out_of_school' => $data['out_of_school'] ?? 0,
        ];
    }

    private function prepareLeadershipData(array $data): array
    {
        return [
            'total_councils' => $data['total_councils'] ?? 0,
            'active_councils' => $data['active_councils'] ?? 0,
            'total_leaders' => $data['total_leaders'] ?? 0,
            'positions_held' => $data['positions_held'] ?? [],
            'organizations' => $data['organizations'] ?? 0,
        ];
    }

    private function prepareEngagementData(array $data): array
    {
        return [
            'total_events' => $data['total_events'] ?? 0,
            'events_by_barangay' => $data['events_by_barangay'] ?? [],
            'participation_rate' => $data['participation_rate'] ?? 0,
            'active_participants' => $data['active_participants'] ?? 0,
        ];
    }

    private function prepareProfilesData(array $data): array
    {
        return [
            'total_records' => $data['total_records'] ?? 0,
            'active_youth' => $data['active_youth'] ?? 0,
            'archived_youth' => $data['archived_youth'] ?? 0,
            'average_age' => $data['average_age'] ?? 0,
            'contact_available' => $data['contact_available'] ?? 0,
        ];
    }

    private function prepareQualityData(array $data): array
    {
        return [
            'total_records' => $data['total_records'] ?? 0,
            'complete_records' => $data['complete_records'] ?? 0,
            'missing_contacts' => $data['missing_contacts'] ?? 0,
            'incomplete_profiles' => $data['incomplete_profiles'] ?? 0,
            'accuracy_score' => $data['accuracy_score'] ?? 0,
        ];
    }
}
