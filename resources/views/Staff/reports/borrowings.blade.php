<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h2>Borrowing Report</h2>
<p>Date: {{ $date }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Borrower</th>
            <th>Tool</th>
            <th>Borrow Date</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($borrowings as $b)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $b->user->name }}</td>
            <td>{{ $b->tool->tool_name }}</td>
            <td>{{ \Carbon\Carbon::parse($b->borrow_date)->format('d M Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($b->due_date)->format('d M Y') }}</td>
            <td>{{ ucfirst($b->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
