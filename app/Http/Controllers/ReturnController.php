<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\ReturnModel;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ReturnController extends Controller
{
    const DAILY_FINE = 5000;

    public function index()
    {
        $borrowings = Borrowing::with(['user', 'tool'])
            ->whereIn('status', ['approved','returned'])
            ->latest()
            ->get();

        return view('staff.returns.index', compact('borrowings'));
    }

    public function store(Borrowing $borrowing)
    {
        $today = Carbon::today();
        $dueDate = Carbon::parse($borrowing->due_date);

        $lateDays = $today->greaterThan($dueDate)
            ? $today->diffInDays($dueDate)
            : 0;

        $fine = $lateDays * self::DAILY_FINE;

        // Save return data
        ReturnModel::create([
            'borrowing_id' => $borrowing->id,
            'return_date'  => $today,
            'fine'         => $fine,
        ]);

        // Restore stock
        $borrowing->tool->increment('stock');

        return back()->with('success', 'Tool returned successfully');
    }

}
