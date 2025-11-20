<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard Report - {{ $barangay->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f3f4f6;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            border-radius: 3px;
        }
        .info-box-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .info-box-value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }
        .section-title {
            background: #e5e7eb;
            padding: 10px 12px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            color: #374151;
            border-left: 4px solid #3b82f6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background: #f3f4f6;
            padding: 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            page-break-inside: avoid;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard Report</h1>
        <p>{{ $barangay->name }} - Generated {{ $date }}</p>
    </div>

    <!-- KPI Cards -->
    <div class="info-grid">
        <div class="info-box">
            <div class="info-box-label">Total Youth</div>
            <div class="info-box-value">{{ number_format($totalYouth) }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">Active SK Councils</div>
            <div class="info-box-value">{{ $activeCouncils }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">Upcoming Events (30d)</div>
            <div class="info-box-value">{{ $upcomingEvents }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">Events This Year</div>
            <div class="info-box-value">{{ $eventsThisYear }}</div>
        </div>
    </div>

    <!-- Youth by Sex -->
    <div class="section-title">Youth by Sex</div>
    <table>
        <thead>
            <tr>
                <th>Sex</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($youthBySex as $sex => $count)
                <tr>
                    <td>{{ ucfirst($sex ?? 'Unknown') }}</td>
                    <td>{{ number_format($count) }}</td>
                    <td>{{ $totalYouth > 0 ? round(($count / $totalYouth) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Youth by Status -->
    <div class="section-title">Youth by Status</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($youthByStatus as $status => $count)
                <tr>
                    <td>{{ ucfirst($status ?? 'Unknown') }}</td>
                    <td>{{ number_format($count) }}</td>
                    <td>{{ $totalYouth > 0 ? round(($count / $totalYouth) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Age Distribution -->
    <div class="section-title">Age Distribution</div>
    <table>
        <thead>
            <tr>
                <th>Age Range</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ageBuckets as $range => $count)
                <tr>
                    <td>{{ $range }} years</td>
                    <td>{{ number_format($count) }}</td>
                    <td>{{ $totalYouth > 0 ? round(($count / $totalYouth) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Educational Attainment -->
    <div class="section-title">Educational Attainment</div>
    <table>
        <thead>
            <tr>
                <th>Education Level</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($education as $edu)
                <tr>
                    <td>{{ $edu->educational_attainment ?? 'Unknown' }}</td>
                    <td>{{ number_format($edu->total) }}</td>
                    <td>{{ $totalYouth > 0 ? round(($edu->total / $totalYouth) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Household Income -->
    <div class="section-title">Household Income</div>
    <table>
        <thead>
            <tr>
                <th>Income Range</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incomeRanges as $income => $count)
                <tr>
                    <td>{{ $income ?? 'Unknown' }}</td>
                    <td>{{ number_format($count) }}</td>
                    <td>{{ array_sum($incomeRanges) > 0 ? round(($count / array_sum($incomeRanges)) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Youth Profiling System - Confidential</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i A') }}</p>
    </div>
</body>
</html>
