<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Organizations Grouped Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 15px;
        }
        h1 {
            color: #2563eb;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            text-align: center;
        }
        .header-info {
            font-size: 9px;
            color: #666;
            margin-bottom: 15px;
            background: #f3f4f6;
            padding: 8px;
            border-radius: 3px;
            text-align: center;
        }
        .org-container {
            page-break-inside: avoid;
            background: #fffbeb;
            border-left: 5px solid #2563eb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .org-header {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
        }
        .org-info-row {
            font-size: 9px;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #1e40af;
            display: inline-block;
            width: 100px;
        }
        .officers {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
        }
        .officer-item {
            font-size: 8px;
            margin-bottom: 3px;
        }
        .officer-position {
            font-weight: bold;
            color: #1e40af;
        }
        .members-section {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
        }
        .members-title {
            font-size: 9px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .members-table th {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 3px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #bfdbfe;
        }
        .members-table td {
            padding: 3px;
            font-size: 8px;
            border: 1px solid #e0e7ff;
        }
        .members-table tr:nth-child(even) {
            background-color: #f0f9ff;
        }
        .empty-members {
            font-size: 8px;
            color: #999;
            font-style: italic;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Organizations - Detailed Report with Members</h1>

    <div class="header-info">
        <strong>Generated:</strong> {{ $date }}<br>
        <strong>Total Organizations:</strong> {{ count($organizations) }}
    </div>

    @forelse($organizations as $organization)
        <div class="org-container">
            <div class="org-header">
                {{ $organization->name ?? 'Organization #' . $organization->id }}
            </div>

            <div class="org-info-row">
                <span class="info-label">Barangay:</span>
                {{ $organization->barangay?->name ?? 'N/A' }}
            </div>

            @if($organization->description)
                <div class="org-info-row">
                    <span class="info-label">Description:</span>
                    {{ substr($organization->description, 0, 150) }}{{ strlen($organization->description) > 150 ? '...' : '' }}
                </div>
            @endif

            <div class="org-info-row">
                <span class="info-label">Total Members:</span>
                {{ count($organization->members ?? []) }}
            </div>

            <!-- Officers -->
            <div class="officers">
                <div class="officer-item">
                    <span class="officer-position">President:</span>
                    @if($organization->president)
                        {{ $organization->president->first_name }} {{ substr($organization->president->middle_name ?? '', 0, 1) }}{{ $organization->president->middle_name ? '.' : '' }} {{ $organization->president->last_name }}
                        @if($organization->president->contact_number)
                            ({{ $organization->president->contact_number }})
                        @endif
                    @else
                        Not assigned
                    @endif
                </div>
                <div class="officer-item">
                    <span class="officer-position">Vice President:</span>
                    @if($organization->vicePresident)
                        {{ $organization->vicePresident->first_name }} {{ substr($organization->vicePresident->middle_name ?? '', 0, 1) }}{{ $organization->vicePresident->middle_name ? '.' : '' }} {{ $organization->vicePresident->last_name }}
                        @if($organization->vicePresident->contact_number)
                            ({{ $organization->vicePresident->contact_number }})
                        @endif
                    @else
                        Not assigned
                    @endif
                </div>
                <div class="officer-item">
                    <span class="officer-position">Secretary:</span>
                    @if($organization->secretary)
                        {{ $organization->secretary->first_name }} {{ substr($organization->secretary->middle_name ?? '', 0, 1) }}{{ $organization->secretary->middle_name ? '.' : '' }} {{ $organization->secretary->last_name }}
                        @if($organization->secretary->contact_number)
                            ({{ $organization->secretary->contact_number }})
                        @endif
                    @else
                        Not assigned
                    @endif
                </div>
                <div class="officer-item">
                    <span class="officer-position">Treasurer:</span>
                    @if($organization->treasurer)
                        {{ $organization->treasurer->first_name }} {{ substr($organization->treasurer->middle_name ?? '', 0, 1) }}{{ $organization->treasurer->middle_name ? '.' : '' }} {{ $organization->treasurer->last_name }}
                        @if($organization->treasurer->contact_number)
                            ({{ $organization->treasurer->contact_number }})
                        @endif
                    @else
                        Not assigned
                    @endif
                </div>
            </div>

            <!-- Members -->
            @if($organization->members && count($organization->members) > 0)
                <div class="members-section">
                    <div class="members-title">Members ({{ count($organization->members) }})</div>
                    <table class="members-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Sex</th>
                                <th>Contact</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organization->members as $member)
                                <tr>
                                    <td>
                                        {{ $member['first_name'] ?? '' }}
                                        @if($member['middle_name'] ?? null)
                                            {{ substr($member['middle_name'], 0, 1) }}.
                                        @endif
                                        {{ $member['last_name'] ?? '' }}
                                    </td>
                                    <td>{{ $member['sex'] ?? '' }}</td>
                                    <td>{{ $member['contact_number'] ?? '' }}</td>
                                    <td>{{ $member['status'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="members-section">
                    <div class="empty-members">No members registered</div>
                </div>
            @endif
        </div>
    @empty
        <div style="text-align: center; color: #999; padding: 20px;">
            No organizations found.
        </div>
    @endforelse

</body>
</html>
