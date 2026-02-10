<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function borrowings()
    {
        $borrowings = Borrowing::with(['user', 'tool'])
            ->whereIn('status', ['pending', 'approved', 'rejected','returned'])
            ->orderBy('borrow_date')
            ->get();

        $pdf = Pdf::loadView('staff.reports.borrowings', [
            'borrowings' => $borrowings,
            'date' => Carbon::now()->format('d M Y'),
        ]);

        return $pdf->stream('borrowing-report.pdf');
    }

    public function returns()
    {
        $borrowings = Borrowing::with(['user', 'tool', 'returnData'])
            ->where('status', 'returned')
            ->orderBy('due_date')
            ->get();

        $pdf = Pdf::loadView('staff.reports.returns', [
            'borrowings' => $borrowings,
            'date' => Carbon::now()->format('d M Y'),
        ]);

        return $pdf->stream('return-report.pdf'); // preview
    }

    public function all()
    {
        $borrowings = Borrowing::with(['user', 'tool', 'returnData'])
            ->orderBy('borrow_date')
            ->get();

        $pdf = Pdf::loadView('staff.reports.all', [
            'borrowings' => $borrowings,
            'date' => Carbon::now()->format('d M Y'),
        ]);

        return $pdf->stream('full-report.pdf'); // preview
    }
}
