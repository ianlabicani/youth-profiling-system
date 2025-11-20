<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Organization Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        h1 {
            color: #2563eb;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 10px;
            font-size: 18px;
        }
        h2 {
            color: #1e40af;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        h3 {
            color: #1e40af;
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .header-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 15px;
            background: #f3f4f6;
            padding: 10px;
            border-radius: 5px;
        }
        .org-info {
            background: #eff6ff;
            padding: 12px;
            border-left: 4px solid #2563eb;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .org-info-item {
            margin-bottom: 8px;
        }
        .org-info-label {
            font-weight: bold;
            color: #1e40af;
            display: inline-block;
            width: 120px;
        }
        .officers-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        .officer-card {
            background: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 8px;
        }
        .officer-title {
            font-weight: bold;
            color: #1e40af;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .officer-name {
            font-size: 11px;
            margin-bottom: 2px;
        }
        .officer-info {
            font-size: 9px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #2563eb;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .section-title {
            background: #dbeafe;
            padding: 6px 8px;
            margin-top: 10px;
            margin-bottom: 8px;
            font-weight: bold;
            border-radius: 3px;
            font-size: 11px;
            color: #1e40af;
        }
        .empty-state {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Organization Details Report</h1>

    <div class="header-info">
        <strong>Generated:</strong> {{ $date }}<br>
        <strong>Report Type:</strong> Single Organization with Full Membership
    </div>

    <!-- Organization Information -->
    <div class="org-info">
        <h3>{{ $organization->name ?? 'Organization #' . $organization->id }}</h3>

        <div class="org-info-item">
            <span class="org-info-label">Barangay:</span>
            {{ $organization->barangay?->name ?? 'N/A' }}
        </div>

        @if($organization->description)
            <div class="org-info-item">
                <span class="org-info-label">Description:</span>
                {{ $organization->description }}
            </div>
        @endif

        <div class="org-info-item">
            <span class="org-info-label">Created:</span>
            {{ $organization->created_at->format('F d, Y') }}
        </div>

        <div class="org-info-item">
            <span class="org-info-label">Total Members:</span>
            {{ count($organization->members ?? []) }}
        </div>
    </div>

    <!-- Officers Section -->
    <h2>Organization Officers</h2>

    <div class="officers-grid">
        <!-- President -->
        <div class="officer-card">
            <div class="officer-title">President</div>
            @if($organization->president)
                <div class="officer-name">
                    <strong>
                        {{ $organization->president->first_name }}
                        @if($organization->president->middle_name)
                            {{ substr($organization->president->middle_name, 0, 1) }}.
                        @endif
                        {{ $organization->president->last_name }}
                    </strong>
                </div>
                <div class="officer-info">
                    @if($organization->president->contact_number)
                        📞 {{ $organization->president->contact_number }}
                    @endif
                </div>
            @else
                <div class="empty-state">Not assigned</div>
            @endif
        </div>

        <!-- Vice President -->
        <div class="officer-card">
            <div class="officer-title">Vice President</div>
            @if($organization->vicePresident)
                <div class="officer-name">
                    <strong>
                        {{ $organization->vicePresident->first_name }}
                        @if($organization->vicePresident->middle_name)
                            {{ substr($organization->vicePresident->middle_name, 0, 1) }}.
                        @endif
                        {{ $organization->vicePresident->last_name }}
                    </strong>
                </div>
                <div class="officer-info">
                    @if($organization->vicePresident->contact_number)
                        📞 {{ $organization->vicePresident->contact_number }}
                    @endif
                </div>
            @else
                <div class="empty-state">Not assigned</div>
            @endif
        </div>

        <!-- Secretary -->
        <div class="officer-card">
            <div class="officer-title">Secretary</div>
            @if($organization->secretary)
                <div class="officer-name">
                    <strong>
                        {{ $organization->secretary->first_name }}
                        @if($organization->secretary->middle_name)
                            {{ substr($organization->secretary->middle_name, 0, 1) }}.
                        @endif
                        {{ $organization->secretary->last_name }}
                    </strong>
                </div>
                <div class="officer-info">
                    @if($organization->secretary->contact_number)
                        📞 {{ $organization->secretary->contact_number }}
                    @endif
                </div>
            @else
                <div class="empty-state">Not assigned</div>
            @endif
        </div>

        <!-- Treasurer -->
        <div class="officer-card">
            <div class="officer-title">Treasurer</div>
            @if($organization->treasurer)
                <div class="officer-name">
                    <strong>
                        {{ $organization->treasurer->first_name }}
                        @if($organization->treasurer->middle_name)
                            {{ substr($organization->treasurer->middle_name, 0, 1) }}.
                        @endif
                        {{ $organization->treasurer->last_name }}
                    </strong>
                </div>
                <div class="officer-info">
                    @if($organization->treasurer->contact_number)
                        📞 {{ $organization->treasurer->contact_number }}
                    @endif
                </div>
            @else
                <div class="empty-state">Not assigned</div>
            @endif
        </div>
    </div>

    <!-- Members Section -->
    <h2>Organization Members</h2>

    @if(isset($members) && count($members) > 0)
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Barangay</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>
                            {{ ($member['first_name'] ?? '') }}
                            @if(isset($member['middle_name']) && $member['middle_name'])
                                {{ substr($member['middle_name'], 0, 1) }}.
                            @endif
                            {{ ($member['last_name'] ?? '') }}
                        </td>
                        <td>{{ isset($member['age']) && $member['age'] ? $member['age'] : 'N/A' }}</td>
                        <td>{{ $member['sex'] ?? 'N/A' }}</td>
                        <td>{{ $member['contact_number'] ?? 'N/A' }}</td>
                        <td>{{ $member['email'] ?? 'N/A' }}</td>
                        <td>{{ $member['status'] ?? 'N/A' }}</td>
                        <td>{{ $member['barangay_name'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No members registered for this organization</div>
    @endif

    <!-- Committee Heads Section -->
    @if(isset($committeeHeads) && count($committeeHeads) > 0)
        <h2>Committee Heads</h2>
        <table>
            <thead>
                <tr>
                    <th>Committee</th>
                    <th>Name</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                @foreach($committeeHeads as $committee)
                    <tr>
                        <td>{{ $committee['name'] ?? '' }}</td>
                        <td>
                            @if(isset($committee['head']) && $committee['head'])
                                {{ $committee['head']->first_name }}
                                @if($committee['head']->middle_name)
                                    {{ substr($committee['head']->middle_name, 0, 1) }}.
                                @endif
                                {{ $committee['head']->last_name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if(isset($committee['head']) && $committee['head'] && $committee['head']->contact_number)
                                {{ $committee['head']->contact_number }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
