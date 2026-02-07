<div id="editToolCard"
    class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
    hidden>
    <section class="bg-white rounded-xl shadow-sm w-3xl">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Edit Tool
            </h3>
        </div>

        <!-- Form -->
        <form id="editForm"
            method="POST"
            class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Tool Name -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tool Name
                </label>
                <input type="text"
                    id="editToolName"
                    name="tool_name"
                    value="{{ old('tool_name') }}"
                    class="w-full px-4 py-2 border rounded-lg text-sm">
                @error('tool_name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
                <input type="hidden" name="tool_id" id="editToolId" value="{{ old('tool_id') }}">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Category
                </label>
                <select id="editToolCategory"
                    name="category_id"
                    class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (session('form_context') === 'edit' ? old('category_id') : '') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Condition -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Condition
                </label>
                <select id="editToolCondition"
                    name="condition"
                    class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="good">Good</option>
                    <option value="damaged">Damaged</option>
                </select>
            </div>

            <!-- Stock -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Stock
                </label>
                <input type="number"
                    id="editToolStock"
                    name="stock"
                    value="{{ old('stock') }}"
                    min="0"
                    class="w-full px-4 py-2 border rounded-lg text-sm">
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="button"
                    onclick="closeEditCard()"
                    class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Update Tool
                </button>
            </div>
        </form>

    </section>
</div>