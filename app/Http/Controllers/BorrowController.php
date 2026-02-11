<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\ReturnModel;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'tool'])
            ->latest()
            ->get();

        return view('staff.borrowings.index', compact('borrowings'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required|exists:users,id',
            'tool_id'           => 'required|exists:tools,id',
            'quantity'          => 'required|integer',
            'borrow_date'       => 'nullable|date',
            'due_date'          => 'required|date|after_or_equal:borrow_date',
            'status'            => 'required|in:pending,approved,rejected,returned',
            'rejection_reason'  => 'nullable|string|max:255',
        ]);

        // Custom rule: rejection_reason only if rejected
        $validator->after(function ($validator) use ($request) {
            if ($request->status === 'rejected' && empty($request->rejection_reason)) {
                $validator->errors()->add(
                    'rejection_reason',
                    'Rejection reason is required when status is rejected.'
                );
            }

            if ($request->status !== 'rejected' && $request->rejection_reason) {
                $validator->errors()->add(
                    'rejection_reason',
                    'Rejection reason is only allowed when status is rejected.'
                );
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_create', true)
                ->with('form_context', 'create');
        }

        $tool = Tool::findOrFail($request->tool_id);

        // If approved → ensure stock available
        if ($request->status === 'approved' && $tool->stock < 1 || $request->quantity > $tool->stock) {
            return back()
                ->withErrors(['tool_id' => 'Tool stock is not available'])
                ->withInput()
                ->with('open_create', true)
                ->with('form_context', 'create');
        }

        $borrowing = Borrowing::create([
            'user_id'          => $request->user_id,
            'tool_id'          => $request->tool_id,
            'quantity'         => $request->quantity,
            'borrow_date'      => $request->borrow_date ?? now(),
            'due_date'         => $request->due_date,
            'status'           => $request->status,
            // 'status'           => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        if ($request->status === 'returned') {
            $today    = Carbon::today();
            $dueDate  = Carbon::parse($borrowing->due_date);

            $lateDays = $today->gt($dueDate)
                ? $dueDate->diffInDays($today)
                : 0;

            $fine = $lateDays * 5000 * $request->quantity;

            ReturnModel::create([
                'borrowing_id' => $borrowing->id,
                'return_date'  => $today,
                'fine'         => $fine,
            ]);
        }

        // Decrement stock ONLY if approved
        if ($request->status === 'approved') {
            $tool->decrement('stock', $request->quantity);
        }

        activity_log('added new borrow data, Id:' . $borrowing->id);

        return redirect()
            ->route('admin.borrowings.index')
            ->with('success', 'Borrow request created successfully');
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Borrowing $borrow)
    {
        // dd($borrow->returnData()->exists());
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required|exists:users,id',
            'tool_id'           => 'required|exists:tools,id',
            'quantity'          => 'required|integer|min:1',
            'borrow_date'       => 'required|date',
            'due_date'          => 'required|date|after_or_equal:borrow_date',
            'status'            => 'required|in:pending,approved,rejected,returned',
            'rejection_reason'  => 'nullable|string|max:255',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->status === 'rejected' && empty($request->rejection_reason)) {
                $validator->errors()->add(
                    'rejection_reason',
                    'Rejection reason is required when status is rejected.'
                );
            }

            if ($request->status !== 'rejected' && $request->rejection_reason) {
                $validator->errors()->add(
                    'rejection_reason',
                    'Rejection reason is only allowed when status is rejected.'
                );
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit', true)
                ->with('form_context', 'edit');
        }

        $oldStatus = $borrow->status;
        $newStatus = $request->status;
        $qty       = $request->quantity;

        $oldTool = $borrow->tool;
        $newTool = Tool::findOrFail($request->tool_id);

        /*
    |--------------------------------------------------------------------------
    | STOCK LOGIC
    |--------------------------------------------------------------------------
    */

        // approved → returned
        if ($oldStatus === 'approved' && $newStatus === 'returned') {
            $oldTool->increment('stock', $qty);
        }

        // approved → other than approved / returned
        if ($oldStatus === 'approved' && !in_array($newStatus, ['approved', 'returned'])) {
            $oldTool->increment('stock', $qty);
        }

        $shouldDecrement = (!in_array($oldStatus, ['approved', 'returned']) && $newStatus === 'approved') ||
        ($oldStatus === 'returned' && $newStatus === 'approved' && $borrow->returnData);


        // anything → approved
        if ($shouldDecrement) {
            if ($newTool->stock < $qty) {
                return back()
                    ->withErrors(['tool_id' => 'Tool stock is not available'])
                    ->withInput()
                    ->with('open_edit', true)
                    ->with('form_context', 'edit');
            }

            $newTool->decrement('stock', $qty);
        }


        /*
    |--------------------------------------------------------------------------
    | UPDATE BORROWING
    |--------------------------------------------------------------------------
    */

        // dd($request->all(),$oldStatus !== 'returned' && $newStatus === 'returned');
        $borrow->update([
            'user_id'          => $request->user_id,
            'tool_id'          => $request->tool_id,
            'quantity'         => $qty,
            'borrow_date'      => $request->borrow_date,
            'due_date'         => $request->due_date,
            'status'           => $newStatus,
            'rejection_reason' => $newStatus === 'rejected'
                ? $request->rejection_reason
                : null,
        ]);

        /*
    |--------------------------------------------------------------------------
    | RETURN DATA LOGIC
    |--------------------------------------------------------------------------
    */

        // entering returned → create return
        if (($oldStatus !== 'returned' && $newStatus === 'returned') || ($oldStatus == 'returned' && $newStatus === 'returned')) {
            if (!$borrow->returnData) {
                ReturnModel::create([
                    'borrowing_id' => $borrow->id,
                    'return_date'  => now(),
                    'fine'         => $request->fine ?? 0,
                ]);
            }
        }

        // leaving returned → ALWAYS delete return
        if ($oldStatus === 'returned' && $newStatus !== 'returned') {
            if ($borrow->returnData) {
                $borrow->returnData->delete();
            }
        }

        return redirect()
            ->route('admin.borrowings.index')
            ->with('success', 'Borrowing updated successfully');
    }


    /**
     * DESTROY
     */
    public function destroy(Borrowing $borrow)
    {
        // Restore stock if approved borrowing is deleted
        if ($borrow->status === 'approved') {
            $borrow->tool->increment('stock', $borrow->quantity);
        }

        $borrow->delete();

        return back()->with('success', 'Borrowing deleted');
    }

    /**
     * APPROVE (your existing method, safe)
     */
    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back();
        }

        if ($borrowing->tool->stock < 1) {
            return back()->withErrors(['stock' => 'Tool stock not available']);
        }

        $borrowing->update([
            'status' => 'approved',
        ]);

        $borrowing->tool->decrement('stock', $borrowing->quantity);

        return back()->with('success', 'Borrowing approved');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        // Only pending borrowings can be rejected
        if ($borrowing->status !== 'pending') {
            return back()->withErrors('Only pending borrowings can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $borrowing->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Borrowing rejected successfully.');
    }
}
