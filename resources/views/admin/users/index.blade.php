@extends('layouts.app')

@section('title', 'Users')

@section('dashboard-content')
    <div class="flex-1 p-8">

        <!-- Top bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-slate-800">
                    User Management
                </h2>
                <p class="text-sm text-slate-500">
                    Lorem Ipsum
                </p>
            </div>

            <div class="relative">
                <button onclick="toggleProfileMenu()" class="flex items-center gap-3 focus:outline-none">
                    <span class="text-sm text-slate-600">
                        {{ auth()->user()->name }}
                    </span>
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </button>

                <div id="profileMenu"
                    class="hidden absolute right-0 mt-2 w-40 bg-white border border-slate-200 rounded-lg shadow-md">
                    <form method="POST" action="{{ route('logout') }}" class="p-1">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="py-4 flex flex-wrap gap-3 items-center">

            <!-- Search Input -->
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user name..."
                class="w-full sm:w-64 px-4 py-2 border rounded-lg text-sm
                      focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

            <!-- Role Dropdown -->
            <select name="role"
                class="w-full sm:w-48 px-4 py-2 border rounded-lg text-sm
                       focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

                @if ($roles->isEmpty())
                    <option disabled selected>
                        Belum Ada Role
                    </option>
                @else
                    <option value="">All Roles</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->role_name) }}
                        </option>
                    @endforeach
                @endif

            </select>

            <!-- Search Button -->
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Search
            </button>
        </form>

        <!-- Users Table -->
        <section class="bg-white rounded-xl shadow-sm w-5xl">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-semibold text-slate-800">
                    User List
                </h3>

                <button type="button" onclick="openCreateCard()" class="text-sm text-blue-600 hover:underline">
                    Add User
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left w-12">No</th>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-center w-40">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $user->name }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $roleName = optional($user->role)->role_name;

                                        $roleColor = match ($roleName) {
                                            'admin' => 'bg-blue-100 text-blue-700',
                                            'staff' => 'bg-amber-100 text-amber-700',
                                            'borrower' => 'bg-emerald-100 text-emerald-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <span class="px-3 py-1 text-xs rounded-full {{ $roleColor }}">
                                        {{ $roleName ? ucfirst($roleName) : 'No Role' }}
                                    </span>
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                            data-role="{{ $user->role_id }}" onclick="openEditCard(this)"
                                            class="px-3 py-1 text-xs rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 text-xs rounded-md bg-red-100 text-red-700 hover:bg-red-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-slate-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>
    </div>

    <!-- Add and Edit Modal -->
    @include('admin.users.create')
    @include('admin.users.edit')

    <script>
        function openCreateCard() {
            const form = document.getElementById('createForm');
            form.reset();
            form.action = "{{ route('admin.users.store') }}";
            document.getElementById('createUserCard').hidden = false;

        }

        function closeCreateCard() {
            document.getElementById('createUserCard').hidden = true;
        }

        function openEditCard(button) {
            const id = button.dataset.id;

            document.getElementById('editForm').action = `/admin/users/${id}`;
            document.getElementById('editUserId').value = id;

            document.getElementById('editUserName').value = button.dataset.name;
            document.getElementById('editUserEmail').value = button.dataset.email;
            document.getElementById('editUserRole').value = button.dataset.role;

            document.getElementById('editUserCard').hidden = false;
        }

        function closeEditCard() {
            document.getElementById('editUserCard').hidden = true;
        }
    </script>

    @if (session('open_create'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('createUserCard').hidden = false;
            });
        </script>
    @endif

    @if (session('open_edit') && old('user_id'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const id = "{{ old('user_id') }}";
                const form = document.getElementById('editForm');

                form.action = `/admin/users/${id}`;
                document.getElementById('editUserCard').hidden = false;
            });
        </script>
    @endif
    
@endsection
