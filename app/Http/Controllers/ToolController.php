<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToolController extends Controller
{
    /**
     * Admin tool list + search
     */
    public function index(Request $request, Tool $tool)
    {
        $tools = Tool::with('category')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->get();

        $categories = Category::all();

        return view('admin.tools.index', compact('tools', 'categories'));
    }

    /**
     * Show create tool form
     */
    public function create()
    {
        return view('admin.tools.create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store tool
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'tool_name'        => 'required|string|max:100',
        //     'category_id' => 'required|exists:categories,id',
        //     'condition'   => 'required|in:good,damaged',
        //     'stock'       => 'required|integer|min:0',
        // ]);
        $validator = Validator::make($request->all(), [
            'tool_name'   => 'required|string|max:100|unique:tools,tool_name',
            'category_id' => 'required|exists:categories,id',
            'condition'   => 'required|in:good,damaged',
            'stock'       => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_create', true)
                ->with('form_context', 'create');
        }

        Tool::create($request->only([
            'tool_name',
            'category_id',
            'condition',
            'stock'
        ]));

        return redirect()
            ->route('admin.tools.index')
            ->with('success', 'Tool added successfully');
    }

    /**
     * Show edit tool form
     */
    public function edit(Tool $tool)
    {
        return view('admin.tools.edit', [
            'tool' => $tool,
            'categories' => Category::all()
        ]);
    }

    /**
     * Update tool
     */
    public function update(Request $request, Tool $tool)
    {
        $validator = Validator::make($request->all(), [
            'tool_name'   => 'required|string|max:100|unique:tools,tool_name,' . $tool->id,
            'category_id' => 'required|exists:categories,id',
            'condition'   => 'required|in:good,damaged',
            'stock'       => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit', true)
                ->with('form_context', 'edit');
        }

        $tool->update($request->only([
            'name',
            'category_id',
            'condition',
            'stock'
        ]));

        return redirect()
            ->route('admin.tools.index')
            ->with('success', 'Tool updated successfully');
    }

    /**
     * Delete tool
     */
    public function destroy(Tool $tool)
    {
        $tool->delete();

        return back()->with('success', 'Tool deleted');
    }

    /**
     * Borrower tool list
     */
}
