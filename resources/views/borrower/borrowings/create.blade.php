<div id="createBorrowCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
     {{ $errors->any() && session('form_context') === 'create' ? '' : 'hidden' }}>

    <!-- Form Card -->
    <section class="bg-white rounded-xl shadow-sm w-3xl">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Add New Borrow
            </h3>
        </div>

        <!-- Form -->
        <form id="createForm" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Borrower -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrower
                </label>
                <input type="text" value="{{ auth()->user()->name }}" disabled
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Tool -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tool
                </label>
                <input type="text" id="createToolName" disabled
                    value="{{ session('form_context') === 'create' ? old('tool_name') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">

                <input type="hidden" name="tool_id" id="createToolId"
                       value="{{ old('tool_id') }}">

                @error('tool_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Quantity
                </label>
                <input type="number" name="quantity" min="1" required
                    value="{{ session('form_context') === 'create' ? old('quantity') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg text-sm">

                @error('quantity')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Borrow Date -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrow Date
                </label>
                <input type="date" name="borrow_date"
                    value="{{ now()->toDateString() }}" readonly
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Due Date -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Due Date
                </label>
                <input type="date" name="due_date" required
                    min="{{ now()->toDateString() }}"
                    value="{{ session('form_context') === 'create' ? old('due_date') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg text-sm">

                @error('due_date')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeCreateCard()"
                        class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                        class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Submit Borrow Request
                </button>
            </div>
        </form>
    </section>
</div>
