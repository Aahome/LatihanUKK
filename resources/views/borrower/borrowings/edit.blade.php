@extends('layouts.app')

@section('title', 'Edit Borrowing')

@section('dashboard-content')
<div class="flex-1 p-8 max-w-3xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-slate-800">
            Edit Borrowing
        </h2>
        <p class="text-sm text-slate-500">
            Update borrowing details
        </p>
    </div>

    <!-- Form -->
    <section class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST"
            action="{{ route('borrower.borrowings.update', $borrowing->id) }}">
            @csrf
            @method('PUT')

            <!-- Borrower -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrower
                </label>
                <input type="text"
                    value="{{ $borrowing->user->name }}"
                    disabled
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Tool -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tool
                </label>
                <input type="text"
                    value="{{ $borrowing->tool->tool_name }}"
                    disabled
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Borrow Date -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrow Date
                </label>
                <input type="date"
                    value="{{ $borrowing->borrow_date }}"
                    disabled
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Due Date -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Due Date
                </label>
                <input type="date"
                    name="due_date"
                    value="{{ $borrowing->due_date }}"
                    required
                    class="w-full px-4 py-2 border rounded-lg text-sm
                           focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('borrower.borrowings.index') }}"
                    class="px-5 py-2 text-sm rounded-lg border hover:bg-slate-50">
                    Cancel
                </a>

                <button type="submit"
                    class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
