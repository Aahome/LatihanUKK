<div id="editRoleCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>

    <section class="bg-white rounded-xl shadow-sm w-2xs">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Edit Role
            </h3>
        </div>

        <form id="editForm" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')


            <div>
                <label class="block text-sm mb-1">Role Name</label>
                <input type="text"
                    name="role_name"
                    id="editRoleName"
                    value="{{ old('role_name') }}"
                    class="w-full border rounded-lg px-4 py-2">
                @error('role_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <input type="hidden" name="role_id" id="editRoleId" value="{{ old('role_id') }}">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                    onclick="closeEditCard()"
                    class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Save
                </button>
            </div>

        </form>
        <section>
</div>