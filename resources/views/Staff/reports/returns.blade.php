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
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $b)
                @php
                    $borrowing = $return->borrowing;
                    $due = \Carbon\Carbon::parse($borrowing->due_date);
                    $returnDate = \Carbon\Carbon::parse($return->return_date);

                    $lateDays = $returnDate->greaterThan($due) ? $returnDate->diffInDays($due) : 0;
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->user->name }}</td>
                    <td>{{ $b->tool->tool_name }}</td>
                    <td>{{ $b->quantity }}</td>
                    <td>{{ $lateDays }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->borrow_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->due_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->return_date)->format('d M Y') }}</td>
                    <td>Rp {{ number_format($b->returnData->fine ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

@php
    $borrowing = $return->borrowing;
    $due = \Carbon\Carbon::parse($borrowing->due_date);
    $returnDate = \Carbon\Carbon::parse($return->return_date);

    $lateDays = $returnDate->greaterThan($due) ? $returnDate->diffInDays($due) : 0;
@endphp

<tr class="hover:bg-slate-50">
    <td class="px-6 py-4">{{ $loop->iteration }}</td>

    <td class="px-6 py-4 font-medium">
        {{ $borrowing->user->name }}
    </td>

    <td class="px-6 py-4">
        {{ $borrowing->tool->tool_name }}
    </td>

    <td class="px-6 py-4">
        {{ $return->return_date }}
    </td>
