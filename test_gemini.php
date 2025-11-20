<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');

if (!$apiKey) {
    echo "❌ API Key not found. Check GEMINI_API_KEY in .env\n";
    exit(1);
}

echo "✓ Gemini API Key loaded: " . substr($apiKey, 0, 20) . "...\n\n";

// Test the API call
echo "Testing Gemini API...\n";
echo "-------------------\n";

try {
    $url = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro-latest:generateContent?key=' . $apiKey;

    $response = Http::timeout(15)->post($url, [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => 'Generate a brief 1-2 sentence description for a youth profiling dashboard metric: Total Youth with value 150 in a specific barangay.',
                    ],
                ],
            ],
        ],
        'generationConfig' => [
            'maxOutputTokens' => 100,
            'temperature' => 0.7,
        ],
    ]);

    echo "Response Status: " . $response->status() . "\n\n";

    if ($response->successful()) {
        $data = $response->json();
        echo "✓ Gemini API Call Successful!\n\n";
        echo "Generated Description:\n";
        $description = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        echo $description ?? 'No content' . "\n";
        exit(0);
    } else {
        echo "❌ Gemini API Call Failed\n";
        echo "Status: " . $response->status() . "\n";
        echo "Response:\n";
        echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    exit(1);
}
