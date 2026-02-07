@extends('layouts.app')

@section('title', 'Categories')

@section('dashboard-content')
<div class="flex-1 p-8">

    <!-- Top bar -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">
                Category Management
            </h2>
            <p class="text-sm text-slate-500">
                Manage tool categories
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

    <!-- Search -->
    <form method="GET"
        action="{{ route('admin.categories.index') }}"
        class="py-4 flex gap-3 items-center">

        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search category..."
            class="w-full sm:w-64 px-4 py-2 border rounded-lg text-sm
                   focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

        <button type="submit"
            class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
            Search
        </button>
    </form>

    <!-- Category Table -->
    <section class="bg-white rounded-xl shadow-sm w-3xl">

        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">
                Category List
            </h3>

            <button type="button"
                onclick="openCreateCard()"
                class="text-sm text-blue-600 hover:underline">
                Add Category
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left w-12">No</th>
                        <th class="px-6 py-3 text-left">Category Name</th>
                        <th class="px-6 py-3 text-center w-40">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $category->category_name }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button
                                    type="button"
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->category_name }}"
                                    data-description="{{ $category->description }}"
                                    onclick="openEditCard(this)"
                                    class="px-3 py-1 text-xs rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200">
                                    Edit
                                </button>


                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this category?')">
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
                        <td colspan="3"
                            class="px-6 py-6 text-center text-slate-500">
                            No categories found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Add and Edit Modal -->
@include('admin.categories.create')
@include('admin.categories.edit')


<script>
    function openCreateCard() {
        const form = document.getElementById('createForm');
        form.action = "{{ route('admin.categories.store') }}";
        document.getElementById('createCategoryCard').hidden = false;

    }

    function closeCreateCard() {
        document.getElementById('createCategoryCard').hidden = true;
    }

    function openEditCard(button) {
        const id = button.dataset.id;
        const name = button.dataset.name;
        const description = button.dataset.description;

        document.getElementById('editForm').action = `/admin/categories/${id}`;
        document.getElementById('editCategoryId').value = id;

        document.getElementById('editCategoryName').value = name;
        document.getElementById('editCategoryDescription').value = description;

        document.getElementById('editCategoryCard').hidden = false;
    }

    function closeEditCard() {
        document.getElementById('editCategoryCard').hidden = true;
    }
</script>

@if (session('open_create'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('createCategoryCard').hidden = false;
    });
</script>
@endif

@if (session('open_edit') && old('user_id'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const id = "{{ old('category_id') }}";
        const form = document.getElementById('editForm');

        form.action = `/admin/categories/${id}`;
        document.getElementById('editCategoryCard').hidden = false;
    });
</script>
@endif

@endsection