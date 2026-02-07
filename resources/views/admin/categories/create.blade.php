<div id="createCategoryCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>
    <!-- <div class="flex-1 p-8 max-w-3xl"> -->
    <section class="bg-white rounded-xl shadow-sm w-1/3">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Add New Category
            </h3>
        </div>

        <!-- Form -->
        <form id="createForm"
            method="POST"
            class="p-6 space-y-5">

            @csrf

            <!-- Category Name -->
            <div class="flex flex-col gap-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Category Name
                    </label>
                    <input type="text"
                        name="category_name"
                        value="{{ session('form_context') === 'create' ? old('category_name') : '' }}"
                        placeholder="Enter category name"
                        class="w-full px-4 py-2 border rounded-lg text-sm">
                    @error('category_name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Description
                    </label>
                    <input type="text"
                        name="description"
                        value="{{ session('form_context') === 'create' ? old('description') : '' }}"
                        placeholder="Enter description"
                        class="w-full px-4 py-2 border rounded-lg text-sm">
                    @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="button"
                    onclick="closeCreateCard()"
                    class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                    Save Category
                </button>
            </div>

        </form>
    </section>
</div>