<div id="editBorrowCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>

    <section class="bg-white rounded-xl shadow-sm w-3xl">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">
                Edit Borrow
            </h3>
            <button onclick="closeEditCard()" class="text-slate-400 hover:text-slate-600">
                ✕
            </button>
        </div>

        <!-- Form -->
        <form id="editForm" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Borrower -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrower
                </label>
                <input type="text" value="{{ auth()->user()->name }}" disabled
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                <input type="hidden" name="borrow_id" id="editBorrowId" value="{{ old('borrow_id') }}">
            </div>

            <!-- Tool -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tool
                </label>
                <input type="text" id="editToolName" disabled
                    value="{{ session('form_context') === 'edit' ? old('tool_name') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">


                @error('tool_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Quantity
                </label>
                <input type="number" name="quantity" id="editQuantity" min="1" required
                    value="{{ session('form_context') === 'edit' ? old('quantity') : '' }}"
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
                <input type="date" name="borrow_date" readonly
                    value="{{ session('form_context') === 'edit' ? old('borrow_date') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
            </div>

            <!-- Due Date -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Due Date
                </label>
                <input type="date" name="due_date" required
                    value="{{ session('form_context') === 'edit' ? old('due_date') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg text-sm">

                @error('due_date')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditCard()"
                    class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                    Update Borrow
                </button>
            </div>
        </form>
    </section>
</div>
