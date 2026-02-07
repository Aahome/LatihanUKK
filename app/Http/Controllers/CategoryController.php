<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index(Request $request)
    {

        $categories = Category::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('category_name', 'like', '%' . $request->search . '%');
            })
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:100|unique:categories,category_name',
            'description'   => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_create', true)
                ->with('form_context', 'create');
        }

        Category::create([
            'category_name' => $request->category_name,
            'description'   => $request->description,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category added successfully');
    }

    /**
     * Show edit category form
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:100|unique:categories,category_name,' . $category->id,
            'description'   => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit', true)
                ->with('form_context', 'edit');
        }

        $category->update([
            'category_name' => $request->category_name,
            'description'   => $request->description,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        if ($category->tools()->exists()) {
            return back()->withErrors([
                'error' => 'Category is used by tools and cannot be deleted'
            ]);
        }

        $category->delete();

        return back()->with('success', 'Category deleted');
    }
}
