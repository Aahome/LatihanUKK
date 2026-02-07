<div id="editUserCard" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm w-3xl">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Add New User
            </h3>
        </div>

        <form id="editForm"
            method="POST"
            class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Name
                </label>
                <input type="text"
                    name="name"
                    id="editUserName"
                    value="{{ old('name') }}"
                    placeholder="Full name"
                    class="w-full px-4 py-2 border rounded-lg text-sm
                              focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none
                              @error('name') border-red-400 @enderror">

                @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
                <input type="hidden" name="user_id" id="editUserId" value="{{ old('user_id') }}">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Email
                </label>
                <input type="email"
                    name="email"
                    id="editUserEmail"
                    value="{{ old('email') }}"
                    placeholder="user@email.com"
                    class="w-full px-4 py-2 border rounded-lg text-sm
                              focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none
                              @error('email') border-red-400 @enderror">

                @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Role
                </label>
                <select id="editUserRole" name="role_id"
                    class="w-full px-4 py-2 border rounded-lg text-sm
                               focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none
                               @error('role_id') border-red-400 @enderror"
                    required>

                    @if ($roles->isEmpty())
                    <option disabled selected>
                        No Roles Yet.
                    </option>
                    @else
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst($role->role_name) }}
                    </option>
                    @endforeach
                    @endif
                </select>

                @error('role_id')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Password
                </label>
                <input type="password"
                    name="password"
                    placeholder="Minimum 6 characters"
                    class="w-full px-4 py-2 border rounded-lg text-sm
                              focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none
                              @error('password') border-red-400 @enderror">

                @error('password')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Confirm Password
                </label>
                <input type="password"
                    name="password_confirmation"
                    placeholder="Repeat password"
                    class="w-full px-4 py-2 border rounded-lg text-sm
                              focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button"
                    onclick="closeEditCard()"
                    class="px-5 py-2 rounded-lg text-sm border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                    class="px-6 py-2 text-sm rounded-lg
                               bg-blue-600 text-white hover:bg-blue-700">
                    Save User
                </button>
            </div>

        </form>
    </div>
</div>