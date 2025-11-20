<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
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
            font-size: 9px;
            color: #666;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #ea580c;
            color: white;
            padding: 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
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
                <th>Email</th>
                <th>Contact</th>
                <th>Barangay</th>
                <th>Sex</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($youths as $youth)
                <tr>
                    <td>{{ $youth->id }}</td>
                    <td>
                        {{ $youth->first_name }}
                        @if($youth->middle_name)
                            {{ substr($youth->middle_name, 0, 1) }}.
                        @endif
                        {{ $youth->last_name }}
                    </td>
                    <td>{{ $youth->email ?? '' }}</td>
                    <td>{{ $youth->contact_number ?? '' }}</td>
                    <td>{{ $youth->barangay?->name ?? 'N/A' }}</td>
                    <td>{{ $youth->sex ?? '' }}</td>
                    <td>{{ $youth->status ?? '' }}</td>
                    <td>{{ $youth->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
