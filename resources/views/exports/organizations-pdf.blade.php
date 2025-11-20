<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #333;
            margin: 20px;
        }
        h2 {
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
            margin-bottom: 5px;
        }
        .header-info {
            font-size: 8px;
            color: #666;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #2563eb;
            color: white;
            padding: 5px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
        }
        td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
            font-size: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <div class="header-info">
        <strong>Generated:</strong> {{ $date }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Barangay</th>
                <th>President</th>
                <th>Vice President</th>
                <th>Secretary</th>
                <th>Treasurer</th>
                <th>Members</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($organizations as $org)
                <tr>
                    <td>{{ $org->id }}</td>
                    <td>{{ $org->name ?? '' }}</td>
                    <td>{{ $org->barangay?->name ?? 'N/A' }}</td>
                    <td>{{ $org->president ? $org->president->first_name . ' ' . $org->president->last_name : 'N/A' }}</td>
                    <td>{{ $org->vicePresident ? $org->vicePresident->first_name . ' ' . $org->vicePresident->last_name : 'N/A' }}</td>
                    <td>{{ $org->secretary ? $org->secretary->first_name . ' ' . $org->secretary->last_name : 'N/A' }}</td>
                    <td>{{ $org->treasurer ? $org->treasurer->first_name . ' ' . $org->treasurer->last_name : 'N/A' }}</td>
                    <td>{{ count($org->members ?? []) }}</td>
                    <td>{{ $org->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #999;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
