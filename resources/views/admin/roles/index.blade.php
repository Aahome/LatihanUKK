@extends('layouts.app')

@section('title', 'Roles')

@section('dashboard-content')
<div class="flex-1 p-8">

    <!-- Top bar -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">
                Role Management
            </h2>
            <p class="text-sm text-slate-500">
                Manage system roles
            </p>
        </div>

        <div class="relative">
            <button onclick="toggleProfileMenu()"
                class="flex items-center gap-3 focus:outline-none">
                <span class="text-sm text-slate-600">
                    {{ auth()->user()->name }}
                </span>
                <div
                    class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
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

    <!-- Roles Table -->
    <section class="bg-white rounded-xl shadow-sm w-2xl">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">
                Role List
            </h3>

            <button type="button"
                onclick="openCreateCard()"
                class="text-sm text-blue-600 hover:underline">
                Add Role
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left w-12">No</th>
                        <th class="px-6 py-3 text-left">Role Name</th>
                        <th class="px-6 py-3 text-center w-40">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($roles as $role)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ ucfirst($role->role_name) }}
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button
                                    type="button"
                                    data-id="{{ $role->id }}"
                                    data-name="{{ $role->role_name }}"
                                    onclick="openEditCard(this)"
                                    class="px-3 py-1 text-xs rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200">
                                    Edit
                                </button>

                                <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this role?')">
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
                        <td colspan="3" class="px-6 py-6 text-center text-slate-500">
                            No roles found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </section>
</div>

<!-- Add and Edit Modal -->
@include('admin.roles.create')
@include('admin.roles.edit')


<script>
    function openCreateCard() {
        const form = document.getElementById('createForm');
        form.reset();
        form.action = "{{ route('admin.roles.store') }}";
        document.getElementById('createRoleCard').hidden = false;

    }

    function closeCreateCard() {
        document.getElementById('createRoleCard').hidden = true;
    }

    function openEditCard(button) {
        const id = button.dataset.id;

        document.getElementById('editForm').action = `/admin/roles/${id}`;
        document.getElementById('editRoleId').value = id;

        document.getElementById('editRoleName').value = button.dataset.name;

        document.getElementById('editRoleCard').hidden = false;
    }

    function closeEditCard() {
        document.getElementById('editRoleCard').hidden = true;
    }
</script>

@if (session('open_create'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('createRoleCard').hidden = false;
    });
</script>
@endif

@if (session('open_edit') && old('role_id'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const id = "{{ old('role_id') }}";
        const form = document.getElementById('editForm');

        form.action = `/admin/roles/${id}`;
        document.getElementById('editRoleCard').hidden = false;
    });
</script>
@endif


@endsection