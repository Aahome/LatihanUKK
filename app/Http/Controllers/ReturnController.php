<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\ReturnModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReturnController extends Controller
{

    public function index()
    {
        $borrowings = Borrowing::with(['user', 'tool'])
            ->whereIn('status', ['approved', 'returned'])
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

        $fine = $lateDays * 5000 * $borrowing->quantity;

        // Save return data
        ReturnModel::create([
            'borrowing_id' => $borrowing->id,
            'return_date'  => $today,
            'fine'         => $fine,
        ]);

        // Restore stock
        $borrowing->tool->increment('stock', $borrowing->quantity);

        return back()->with('success', 'Tool returned successfully');
    }

    public function update(Request $request, ReturnModel $return)
    {
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'return_date' => 'required|date|after_or_equal:' . $return->borrowing->due_date,
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('view', 'return')
                ->with('open_edit', true)
                ->withInput(['return_id' => $return->id]);
        }

        // 2. Parse dates
        $returnDate = Carbon::parse($request->return_date);
        $dueDate    = Carbon::parse($return->borrowing->due_date);

        // 3. Calculate fine
        if ($returnDate->greaterThan($dueDate)) {
            $lateDays = $dueDate->diffInDays($returnDate);
            $fine = $lateDays * 5000 * $return->borrowing->quantity;
        } else {
            $fine = 0;
        }

        // 4. Update return data
        $return->update([
            'return_date' => $returnDate,
            'fine'        => $fine,
        ]);

        return redirect()
            ->route('admin.returns.index')
            ->with('success', 'Return data updated successfully');
    }

    public function destroy(ReturnModel $return)
    {
        $borrowing = $return->borrowing;

        $borrowing->update([
            'status' => 'pending',
        ]);

        $return->delete();

        return back()->with('success', 'Return data deleted and borrowing reverted.');
    }
}
