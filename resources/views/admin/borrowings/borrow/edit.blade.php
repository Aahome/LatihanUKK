<div id="editBorrowCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>
    <div class="bg-white rounded-xl shadow-sm w-3xl">

        <form id="editForm" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Borrower -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Borrower</label>
                <select name="user_id" id="editUserId" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="">Select Borrower</option>
                    @foreach ($borrowers as $borrower)
                        <option value="{{ $borrower->id }}"
                            {{ (session('form_context') === 'edit' ? old('user_id') : '') == $borrower->id ? 'selected' : '' }}>
                            {{ $borrower->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <input type="hidden" name="borrow_id" id="editBorrowId" value="{{ old('borrow_id') }}">
                <input type="hidden" name="fine" id="editFine" value="{{ old('fine') }}">
            </div>

            <!-- Tool -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tool</label>
                <select name="tool_id" id="editToolId" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="">Select Tool</option>
                    @foreach ($tools as $tool)
                        <option value="{{ $tool->id }}"
                            {{ (session('form_context') === 'edit' ? old('tool_id') : '') == $borrower->id ? 'selected' : '' }}>
                            {{ $tool->tool_name }}
                        </option>
                    @endforeach
                </select>
                @error('tool_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Borrow Quantity -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Quantity
                </label>
                <input type="number" min="1" name="quantity" id="editQuantity" required
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                @error('quantity')
                    <p class="text-sm text-red-500 ">{{ $message }}</p>
                @enderror
            </div>

            <!-- Borrow Date -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Borrow Date</label>
                <input type="date" name="borrow_date" id="editBorrowDate"
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                @error('borrow_date')
                    <p class="text-sm text-red-500 mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Due Date</label>
                <input type="date" name="due_date" id="editDueDate"
                    class="w-full px-4 py-2 border rounded-lg text-sm">
                @error('due_date')
                    <p class="text-sm text-red-500 mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" id="editStatus" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>pending</option>
                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>rejected</option>
                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>approved</option>
                    <option value="returned1" {{ old('status') == 'returned1' ? 'selected' : '' }}>returned -
                        unconfirmed</option>
                    <option value="returned2" {{ old('status') == 'returned2' ? 'selected' : '' }}>returned- confirmed
                    </option>
                </select>
            </div>

            <!-- Rejection Reason -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Rejection Reason</label>
                <input type="text" name="rejection_reason" id="editRejectionReason"
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                @error('rejection_reason')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeBorrowEditCard()" class="px-5 py-2 rounded-lg text-sm border">
                    Cancel
                </button>

                <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white">
                    Update Borrow
                </button>
            </div>

        </form>
    </div>
</div>
