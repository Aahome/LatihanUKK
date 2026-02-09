<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\ReturnModel;
use App\Models\User;
use App\Models\Role;
use App\Models\Tool;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        /* ===============================
         * Resolve active table (TAB LOGIC)
         * =============================== */
        if (session()->has('view')) {
            // Validation / edit redirect wins
            $view = session('view');
        } elseif ($request->filled('_tab')) {
            // Search form decides
            $view = $request->_tab;
        } else {
            // Default
            $view = 'borrow';
        }

        /* ===============================
         * Borrowings (SEARCH)
         * =============================== */
        $borrowings = Borrowing::with(['user', 'tool'])
            ->when($request->filled('borrowSearch'), function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->borrowSearch . '%');
                });
            })
            ->when($request->filled('borrowTool'), function ($q) use ($request) {
                $q->where('tool_id', $request->borrowTool);
            })
            ->latest()
            ->get();

        /* ===============================
         * Returns (SEARCH)
         * =============================== */
        $returns = ReturnModel::with([
            'borrowing.user',
            'borrowing.tool'
        ])
            ->when($request->filled('returnSearch'), function ($q) use ($request) {
                $q->whereHas('borrowing.user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->returnSearch . '%');
                });
            })
            ->when($request->filled('returnTool'), function ($q) use ($request) {
                $q->whereHas('borrowing.tool', function ($t) use ($request) {
                    $t->where('id', $request->returnTool);
                });
            })
            ->latest()
            ->get();

        /* ===============================
         * Supporting data
         * =============================== */
        $borrowers = User::where('role_id', 3)->get();
        $tools     = Tool::all();
        $roles     = Role::all();

        return view('admin.borrowings.index', compact(
            'borrowings',
            'returns',
            'borrowers',
            'tools',
            'roles',
            'view'
        ));
    }
}
