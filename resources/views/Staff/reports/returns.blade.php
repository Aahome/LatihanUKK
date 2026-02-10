<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <h2>Return Report</h2>
    <p>Date: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Borrower</th>
                <th>Tool</th>
                <th>Quantity</th>
                <th>Return Date</th>
                <th>Late Day(s)</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $b)
                @php
                    // $due = \Carbon\Carbon::parse($b->due_date);
                    // $returnDate = \Carbon\Carbon::parse($b->return_date);

                    // $lateDays = $returnDate->greaterThan($due) ? $returnDate->diffInDays($due) : 0;
                    $due = \Carbon\Carbon::parse($b->due_date);
                    $returnDate = \Carbon\Carbon::parse($b->returnData->return_date);
                    $lateDays = $returnDate->greaterThan($due) ? $returnDate->diffInDays($due) : 0;

                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->user->name }}</td>
                    <td>{{ $b->tool->tool_name }}</td>
                    <td>{{ $b->quantity }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->return_date)->format('d M Y') }}</td>
                    <td>{{ $lateDays }}</td>
                    <td>Rp {{ number_format($b->returnData->fine ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
