@extends('municipal.shell')

@section('title', 'Youth Profiles Report')
@section('municipal-content')
@php
    $title = 'Youth Profiles Report';
    $description = 'Youth profile analysis and insights';

    $stats = [
        [
            'label' => 'Total Youth',
            'value' => $reportData['total_records'],
            'icon' => '👥',
        ],
        [
            'label' => 'Active Youth',
            'value' => $reportData['active_youth'],
            'icon' => '✓',
        ],
        [
            'label' => 'Average Age',
            'value' => $reportData['average_age'],
            'icon' => '📊',
        ],
        [
            'label' => 'With Contact',
            'value' => $reportData['contact_available'],
            'icon' => '📞',
            'subtitle' => round(($reportData['contact_available'] / max(1, $reportData['total_records'])) * 100, 1) . '%'
        ],
    ];
@endphp

<div class="p-8">
    <a href="{{ route('municipal.reports.index') }}"
       class="inline-block mb-6 px-4 py-2 bg-slate-200 text-slate-800 rounded hover:bg-slate-300 transition">
        ← Back
    </a>
    <!-- Report Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $title }}</h1>
        <p class="text-gray-600">{{ $description }}</p>
    </div>

    <!-- Report Stats Section -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach($stats as $stat)
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-lg p-4 border border-slate-200">
                <p class="text-xs font-medium text-slate-600 uppercase mb-2">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                @if(isset($stat['subtitle']))
                    <p class="text-xs text-slate-500 mt-1">{{ $stat['subtitle'] }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <!-- AI Insights Section -->
    @if($insights)
        <div class="bg-white rounded-lg border border-slate-200 p-8 shadow-sm">
            <div class="flex items-start gap-3 mb-6">
                <div class="text-3xl">🤖</div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">AI Analysis & Recommendations</h3>
                    <p class="text-sm text-slate-600">Automated insights based on current youth profile data</p>
                </div>
            </div>
            <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed">
                <div class="space-y-4">
                    @php
                        // Parse the insights text to format it properly
                        $paragraphs = explode("\n\n", $insights);
                    @endphp
                    @foreach($paragraphs as $paragraph)
                        @if(trim($paragraph))
                            @if(str_starts_with(trim($paragraph), '**'))
                                <!-- Bold heading -->
                                <div class="text-base font-semibold text-slate-900 mt-6 mb-3">
                                    {!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e(trim($paragraph))) !!}
                                </div>
                            @elseif(str_starts_with(trim($paragraph), '*'))
                                <!-- Bullet list -->
                                <ul class="list-disc list-inside space-y-2 ml-2">
                                    @php
                                        $lines = explode("\n", trim($paragraph));
                                    @endphp
                                    @foreach($lines as $line)
                                        @if(trim($line))
                                            <li class="text-slate-700">
                                                {!! preg_replace_callback(
                                                    ['/\*\*(.*?)\*\*/', '/\*(.*?)\*/'],
                                                    function($m) {
                                                        if (str_starts_with($m[0], '**')) {
                                                            return '<strong>' . htmlspecialchars($m[1]) . '</strong>';
                                                        } else {
                                                            return '<em>' . htmlspecialchars($m[1]) . '</em>';
                                                        }
                                                    },
                                                    e(ltrim(trim($line), '* '))
                                                ) !!}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <!-- Regular paragraph -->
                                <p class="text-slate-700 leading-relaxed">
                                    {!! preg_replace_callback(
                                        ['/\*\*(.*?)\*\*/', '/\*(.*?)\*/'],
                                        function($m) {
                                            if (str_starts_with($m[0], '**')) {
                                                return '<strong>' . htmlspecialchars($m[1]) . '</strong>';
                                            } else {
                                                return '<em>' . htmlspecialchars($m[1]) . '</em>';
                                            }
                                        },
                                        e(trim($paragraph))
                                    ) !!}
                                </p>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
