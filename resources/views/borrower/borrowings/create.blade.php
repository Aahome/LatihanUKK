@extends('layouts.app')

@section('title', 'Borrow Tool')

@section('dashboard-content')
<div class="flex-1 p-8 max-w-3xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-slate-800">
            Borrow Tool
        </h2>
        <p class="text-sm text-slate-500">
            Fill in the borrowing details below
        </p>
    </div>

    <!-- Form -->
    <section class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('borrower.borrowings.store', $tool->id) }}"
            method="POST">

            @csrf

            <!-- Borrower (Auto-filled) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrower
                </label>
                <input type="text"
                    value="{{ auth()->user()->name }}"
                    disabled
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Tool Name (Auto-filled) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tool
                </label>
                <input type="text"
                    value="{{ $tool->tool_name }}"
                    disabled
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                <input type="hidden" name="tool_id" value="{{ $tool->id }}">
            </div>

            <!-- Borrow Date (Today) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrow Date
                </label>
                <input type="date"
                    name="borrow_date"
                    value="{{ now()->toDateString() }}"
                    readonly
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Due Date -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Due Date
                </label>
                <input type="date"
                    name="due_date"
                    min="{{ now()->toDateString() }}"
                    required
                    class="w-full px-4 py-2 border rounded-lg text-sm
                           focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('borrower.tools.index') }}"
                    class="px-5 py-2 text-sm rounded-lg border hover:bg-slate-50">
                    Cancel
                </a>

                <button type="submit"
                    class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Submit Borrow Request
                </button>
            </div>
        </form>
    </section>
</div>
@endsection