<div id="createToolCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>
    <section class="bg-white rounded-xl shadow-sm w-3xl">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Add New Tool
            </h3>
        </div>

        <!-- Form -->
        <form id="createForm"
            method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Tool Name -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tool Name
                </label>
                <input type="text"
                    name="tool_name"
                    value="{{ session('form_context') === 'create' ? old('tool_name') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg text-sm">
                @error('tool_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Category
                </label>
                <select name="category_id" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (session('form_context') === 'create' ? old('category_id') : '') == $category->id ? 'selected' : '' }}>
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
                <select name="condition" class="w-full px-4 py-2 border rounded-lg text-sm">
                    <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                    <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                </select>
                @error('condition')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Stock
                </label>
                <input type="number"
                    name="stock"
                    min="0"
                    value="{{ session('form_context') === 'create' ? old('stock') : '' }}"
                    class="w-full px-4 py-2 border rounded-lg text-sm">
                @error('stock')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="button"
                    onclick="closeCreateCard()"
                    class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Save Tool
                </button>
            </div>
        </form>

    </section>
</div>