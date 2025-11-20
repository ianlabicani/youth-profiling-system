<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('OPENAI_API_KEY');

if (!$apiKey) {
    echo "❌ API Key not found. Check OPENAI_API_KEY in .env\n";
    exit(1);
}

echo "✓ API Key loaded: " . substr($apiKey, 0, 20) . "...\n\n";

// Test the API call
echo "Testing OpenAI API...\n";
echo "-------------------\n";

try {
    $response = Http::withHeaders([
        'Authorization' => "Bearer {$apiKey}",
        'Content-Type' => 'application/json',
    ])->timeout(15)->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a concise dashboard analyst.',
            ],
            [
                'role' => 'user',
                'content' => 'Generate a brief 1-2 sentence description for a youth profiling dashboard metric: Total Youth with value 150 in a specific barangay.',
            ],
        ],
        'max_tokens' => 100,
        'temperature' => 0.7,
    ]);

    echo "Response Status: " . $response->status() . "\n\n";

    if ($response->successful()) {
        $data = $response->json();
        echo "✓ API Call Successful!\n\n";
        echo "Generated Description:\n";
        echo $data['choices'][0]['message']['content'] ?? 'No content' . "\n";
        exit(0);
    } else {
        echo "❌ API Call Failed\n";
        echo "Status: " . $response->status() . "\n";
        echo "Response:\n";
        echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    exit(1);
}
