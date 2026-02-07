<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowerController extends Controller
{
    /* =========================
     | AVAILABLE TOOLS
     ========================= */
    public function AVIndex(Request $request)
    {
        $tools = Tool::with('category')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('tool_name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->get();

        $categories = Category::all();

        return view('borrower.tools.index', compact('tools', 'categories'));
    }

    /* =========================
     | BORROWING LIST
     ========================= */
    public function index()
    {
        $borrowings = Borrowing::with(['tool', 'returnData'])
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->get();

        return view('borrower.borrowings.index', compact('borrowings'));
    }

    /* =========================
     | CREATE FORM
     ========================= */
    public function create(Tool $tool)
    {
        return view('borrower.borrowings.create', [
            'tool' => $tool,
            'user' => Auth::user(),
            'today' => Carbon::today()->toDateString()
        ]);
    }

    /* =========================
     | STORE BORROWING
     ========================= */
    public function store(Request $request, Tool $tool)
    {
        $request->validate([
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        if ($tool->stock < 1) {
            return back()->with('error', 'Tool is out of stock.');
        }

        Borrowing::create([
            'user_id'     => Auth::user()->id,
            'tool_id'     => $tool->id,
            'borrow_date' => Carbon::today(),
            'due_date'    => $request->due_date,
            'status'      => 'pending',
        ]);

        return redirect()
            ->route('borrower.borrowings.index')
            ->with('success', 'Borrowing request submitted.');
    }

    /* =========================
     | SHOW DETAILS (OPTIONAL)
     ========================= */
    public function show(Borrowing $borrowing)
    {
        abort_if($borrowing->user_id !== Auth::user()->id, 403);

        return view('borrower.borrowings.show', compact('borrowing'));
    }
    /* =========================
    | EDIT BORROWING (ONLY PENDING)
    ========================= */
    public function edit(Borrowing $borrowing)
    {
        // Ownership check
        abort_if($borrowing->user_id !== Auth::user()->id, 403);

        // Only pending borrowings can be edited
        if ($borrowing->status !== 'pending') {
            return redirect()
                ->route('borrower.borrowings.index')
                ->withErrors(['error' => 'Only pending borrowings can be edited.']);
        }

        return view('borrower.borrowings.edit', [
            'borrowing' => $borrowing,
            'tool'      => $borrowing->tool,
            'user'      => Auth::user(),
        ]);
    }

    /* =========================
    | UPDATE BORROWING (ONLY PENDING)
    ========================= */
    public function update(Request $request, Borrowing $borrowing)
    {
        // Ownership check
        abort_if($borrowing->user_id !== Auth::user()->id, 403);

        // Only pending borrowings can be updated
        if ($borrowing->status !== 'pending') {
            return back()->withErrors([
                'error' => 'Only pending borrowings can be updated.'
            ]);
        }

        $request->validate([
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $borrowing->update([
            'due_date' => $request->due_date,
        ]);

        return redirect()
            ->route('borrower.borrowings.index')
            ->with('success', 'Borrowing updated successfully.');
    }

    public function return(Borrowing $borrowing)
    {
        // Make sure borrower owns this borrowing
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent double return
        if ($borrowing->status === 'returned') {
            return back()->with('error', 'This borrowing has already been returned.');
        }

        // Update status
        $borrowing->update([
            'status' => 'returned',
        ]);

        return back()->with('success', 'Tool returned successfully. Waiting for staff confirmation.');
    }


    /* =========================
     | CANCEL (ONLY IF PENDING)
     ========================= */
    public function destroy(Borrowing $borrowing)
    {
        // Make sure borrower owns the data
        if ($borrowing->user_id !== Auth::user()->id) {
            abort(403);
        }

        // Must be returned first
        if (!in_array($borrowing->status, ['pending', 'approved'])) {
            return back()->withErrors([
                'error' => 'You can only delete borrowing after the tool is returned or pending.'
            ]);
        }

        // If return record exists and fine not paid
        if ($borrowing->returnData && $borrowing->returnData->fine > 0) {
            return back()->withErrors([
                'error' => 'Borrowing cannot be deleted until the fine is paid.'
            ]);
        }

        $borrowing->delete();

        return back()->with('success', 'Borrowing deleted successfully.');
    }
}
