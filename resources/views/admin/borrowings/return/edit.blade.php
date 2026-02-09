<div id="editReturnCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>
    <div class="bg-white rounded-xl shadow-sm w-1xl">
        
        <form id="editReturnForm" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Return Date -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Return Date</label>
                <input type="date" name="return_date" id="editReturnDate"
                    value="{{ session('form_context') === 'edit' ? old('return_date') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg bg-slate-100 text-sm">
                @error('return_date')
                    <p class="text-sm text-red-500 mt-0.5">{{ $message }}</p>
                @enderror
            </div>
            <input type="hidden" name="return_id" id="editReturnId" value="{{ old('return_id') }}">

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeReturnEditCard()" class="px-5 py-2 rounded-lg text-sm border">
                    Cancel
                </button>

                <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white">
                    Update Borrow
                </button>
            </div>

        </form>
    </div>
</div>
