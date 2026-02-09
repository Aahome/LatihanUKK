<div id="createBorrowCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm w-3xl">

        <!-- Form -->
        <form id="createForm" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Borrower -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrower
                </label>
                <select name="user_id" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="">Select Borrower</option>
                    @foreach ($borrowers as $borrower)
                        <option value="{{ $borrower->id }}"
                            {{ (session('form_context') === 'create' ? old('user_id') : '') == $borrower->id ? 'selected' : '' }}>
                            {{ $borrower->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tool Name -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tool
                </label>
                <select name="tool_id" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="">Select Tool</option>
                    @foreach ($tools as $tool)
                        <option value="{{ $tool->id }}"
                            {{ (session('form_context') === 'create' ? old('tool_id') : '') == $tool->id ? 'selected' : '' }}>
                            {{ $tool->tool_name }}
                        </option>
                    @endforeach
                </select>
                @error('tool_id')
                    <p class="text-sm text-red-500 ">{{ $message }}</p>
                @enderror
            </div>

            <!-- Borrow Quantity -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Quantity
                </label>
                <input type="number" name="quantity"
                    value="{{ session('form_context') === 'create' ? old('quantity') : '' }}" required
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                @error('quantity')
                    <p class="text-sm text-red-500 ">{{ $message }}</p>
                @enderror
            </div>

            <!-- Borrow Date -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Borrow Date
                </label>
                <input type="date" name="borrow_date" required
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                @error('borrow_date')
                    <p class="text-sm text-red-500 ">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Due Date
                </label>
                <input type="date" name="due_date" required
                    class="w-full px-4 py-2 border rounded-lg text-sm
                           focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">
                @error('due_date')
                    <p class="text-sm text-red-500 mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Status
                </label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>pending</option>
                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>rejected</option>
                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>approved</option>
                    <option value="returned" {{ old('status') == 'returned' ? 'selected' : '' }}>returned</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Borrower -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Rejection Reason
                </label>
                <input type="text" name="rejection_reason"
                    value="{{ session('form_context') === 'create' ? old('rejection_reason') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                @error('rejection_reason')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeBorrowCreateCard()"
                    class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Submit Borrow Request
                </button>
            </div>
        </form>
    </div>
</div>
