<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin-bottom: 5px;
        }

        p {
            margin-top: 0;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
            text-align: center;
        }

        td.center {
            text-align: center;
        }

        td.right {
            text-align: right;
        }
    </style>
</head>
<body>

<h2>Full Borrowing & Return Report</h2>
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
            <th>Return Date</th>
            <th>Fine</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($borrowings as $b)
        <tr>
            <td class="center">{{ $loop->iteration }}</td>
            <td>{{ $b->user->name }}</td>
            <td>{{ $b->tool->tool_name }}</td>
            <td class="center">
                {{ \Carbon\Carbon::parse($b->borrow_date)->format('d M Y') }}
            </td>
            <td class="center">
                {{ \Carbon\Carbon::parse($b->due_date)->format('d M Y') }}
            </td>
            <td class="center">
                {{ ucfirst($b->status) }}
            </td>
            <td class="center">
                {{ optional($b->return)->return_date
                    ? \Carbon\Carbon::parse($b->return->return_date)->format('d M Y')
                    : '-' }}
            </td>
            <td class="right">
                Rp {{ number_format(optional($b->return)->fine ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="center">
                No data available
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
