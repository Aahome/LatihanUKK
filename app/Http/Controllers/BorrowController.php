<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\ReturnModel;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
        if ($request->status === 'approved' && $tool->stock < 1) {
            return back()
                ->withErrors(['tool_id' => 'Tool stock is not available'])
                ->withInput()
                ->with('open_create', true)
                ->with('form_context', 'create');
        }

        $borrowing = Borrowing::create([
            'user_id'          => $request->user_id,
            'tool_id'          => $request->tool_id,
            'borrow_date'      => $request->borrow_date ?? now(),
            'due_date'         => $request->due_date,
            'status'           => $request->status,
            'rejection_reason' => $request->status === 'rejected'
                ? $request->rejection_reason
                : null,
        ]);

        if ($request->status === 'returned') {
            $today    = Carbon::today();
            $dueDate  = Carbon::parse($borrowing->due_date);

            $lateDays = $today->gt($dueDate)
                ? $dueDate->diffInDays($today)
                : 0;

            $fine = $lateDays * 5000;

            ReturnModel::create([
                'borrowing_id' => $borrowing->id,
                'return_date'  => $today,
                'fine'         => $fine,
            ]);
        }

        // Decrement stock ONLY if approved
        if ($request->status === 'approved') {
            $tool->decrement('stock');
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required|exists:users,id',
            'tool_id'           => 'required|exists:tools,id',
            'borrow_date'       => 'required|date',
            'due_date'          => 'required|date|after_or_equal:borrow_date',
            'status'            => 'required|in:pending,approved,rejected,returned',
            'rejection_reason'  => 'nullable|string|max:255',
        ]);

        // Custom validation
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
        $oldTool   = $borrow->tool;
        $newTool   = Tool::findOrFail($request->tool_id);

        /*
        |--------------------------------------------------------------------------
        | STOCK HANDLING
        |--------------------------------------------------------------------------
        */

        // If switching tool while approved → restore old stock first
        if ($oldStatus === 'approved' && $oldTool->id !== $newTool->id) {
            $oldTool->increment('stock');
        }

        // If becoming approved → check & decrement
        if ($oldStatus !== 'approved' && $request->status === 'approved') {
            if ($newTool->stock < 1) {
                return back()
                    ->withErrors(['tool_id' => 'Tool stock is not available'])
                    ->withInput()
                    ->with('open_edit', true)
                    ->with('form_context', 'edit');
            }

            $newTool->decrement('stock');
        }

        // If leaving approved → restore stock
        if ($oldStatus === 'approved' && $request->status !== 'approved') {
            $oldTool->increment('stock');
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE BORROWING
        |--------------------------------------------------------------------------
        */
        $isBecomingReturned =
            $oldStatus !== 'returned' &&
            $request->status === 'returned';

        $borrow->update([
            'user_id'          => $request->user_id,
            'tool_id'          => $request->tool_id,
            'borrow_date'      => $request->borrow_date,
            'due_date'         => $request->due_date,
            'status'           => $request->status,
            'rejection_reason' => $request->status === 'rejected'
                ? $request->rejection_reason
                : null,
        ]);

        if ($isBecomingReturned) {
            // restore stock (only once)
            $newTool->increment('stock');

            // create return record (only if not exists)
            if (!$borrow->return) {
                ReturnModel::create([
                    'borrowing_id' => $borrow->id,
                    'return_date'  => Carbon::now(),
                    'fine'  => $request->fine,
                ]);
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
            $borrow->tool->increment('stock');
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

        $borrowing->tool->decrement('stock');

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
