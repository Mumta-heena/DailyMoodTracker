<!DOCTYPE html>
<html>
<head>
    <title>Mood Log Export</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Mood Log from {{ \Carbon\Carbon::parse(request('from_date'))->format('M d, Y') }} to {{ \Carbon\Carbon::parse(request('to_date'))->format('M d, Y') }}</h1>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Mood</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($moods as $mood)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mood->entry_date)->format('M d, Y') }}</td>
                    <td>{{ $mood->mood_type }}</td>
                    <td>{{ $mood->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>