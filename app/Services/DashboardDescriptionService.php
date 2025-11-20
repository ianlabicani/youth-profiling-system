<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DashboardDescriptionService
{
    /**
     * Get AI-generated description for a dashboard metric using Gemini API REST endpoint
     */
    public function getDescription(string $metric, int $value, string $context = ''): string
    {
        $cacheKey = "dashboard_desc:{$metric}:{$value}:{$context}";

        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($metric, $value, $context) {
            try {
                $apiKey = env('GEMINI_API_KEY');

                if (! $apiKey) {
                    \Log::warning('Gemini API key is not configured');

                    return $this->getDefaultDescription($metric);
                }

                // Build the prompt
                $contextStr = $context ? " Context: {$context}" : '';
                $prompt = "Generate a brief, insightful description for a youth profiling dashboard metric:\n";
                $prompt .= "Metric: {$metric}\n";
                $prompt .= "Value: {$value}{$contextStr}\n\n";
                $prompt .= 'Provide a 1-2 sentence description that explains what this metric means in the context of a youth profiling system. Be specific and actionable. Keep it under 100 characters.';

                // Call Gemini API REST endpoint
                $response = Http::withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt,
                                    ],
                                ],
                            ],
                        ],
                    ]
                );

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        $description = trim($data['candidates'][0]['content']['parts'][0]['text']);

                        return $description ?: $this->getDefaultDescription($metric);
                    }
                }

                \Log::warning('Gemini API response error: '.$response->body());

                return $this->getDefaultDescription($metric);
            } catch (\Exception $e) {
                \Log::warning('Dashboard description generation failed: '.$e->getMessage());

                return $this->getDefaultDescription($metric);
            }
        });
    }

    /**
     * Get default description fallback
     */
    private function getDefaultDescription(string $metric): string
    {
        $defaults = [
            'total_youth' => 'Total number of registered youth in the system.',
            'total_barangays' => 'Number of barangays with registered youth.',
            'active_councils' => 'SK Councils currently active and operational.',
            'total_organizations' => 'Youth organizations registered in the system.',
            'upcoming_events' => 'Events scheduled for the next 30 days.',
            'events_this_year' => 'Total barangay events organized this year.',
            'household_income' => 'Distribution of household income ranges among youth families.',
            'education' => 'Educational attainment levels among registered youth.',
            'age_distribution' => 'Age breakdown of youth participants.',
            'youth_by_sex' => 'Gender distribution among registered youth.',
            'youth_by_status' => 'Current status categorization of youth.',
            'youth_by_barangay' => 'Distribution of youth across barangays.',
            'council_positions' => 'Youth holding official council positions.',
            'recent_registrations' => 'Recently registered youth profiles.',
            'out_of_school' => 'Youth with no formal educational attainment.',
        ];

        return $defaults[$metric] ?? 'Dashboard metric tracked.';
    }

    /**
     * Get multiple descriptions efficiently
     */
    public function getMultipleDescriptions(array $metrics): array
    {
        $descriptions = [];
        foreach ($metrics as $key => $data) {
            $descriptions[$key] = $this->getDescription($data['metric'], $data['value'], $data['context'] ?? '');
        }

        return $descriptions;
    }
}
