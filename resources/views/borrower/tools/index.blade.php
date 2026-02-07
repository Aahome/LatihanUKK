@extends('layouts.app')

@section('title', 'Available Tools')

@section('dashboard-content')
<div class="flex-1 p-8">

    <!-- Top bar -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">
                Available Tools
            </h2>
            <p class="text-sm text-slate-500">
                View and request tools for borrowing
            </p>
        </div>

        <!-- Profile -->
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

    <!-- Search & Filter -->
    <form method="GET"
        action="{{ route('borrower.tools.index') }}"
        class="py-4 flex flex-wrap gap-3 items-center">

        <!-- Search Input -->
        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search tool name..."
            class="w-full sm:w-64 px-4 py-2 border rounded-lg text-sm
                          focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

        <!-- Category Dropdown -->
        <select name="category"
            class="w-full sm:w-48 px-4 py-2 border rounded-lg text-sm
               focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

            @if ($categories->isEmpty())
            <option disabled selected>
                Belum Ada Category
            </option>
            @else
            <option value="">All Categories</option>

            @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->category_name }}
            </option>
            @endforeach
            @endif

        </select>


        <!-- Search Button -->
        <button type="submit"
            class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
            Search
        </button>
    </form>

    <!-- Tools Table -->
    <section class="bg-white rounded-xl shadow-sm">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Tool List
            </h3>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left w-12">No</th>
                        <th class="px-6 py-3 text-left">Tool Name</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Condition</th>
                        <th class="px-6 py-3 text-left">Stock</th>
                        <th class="px-6 py-3 text-center w-40">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($tools as $tool)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $tool->tool_name }}
                        </td>
                        
                        <td class="px-6 py-4">
                            {{ $tool->category->category_name }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $conditionColor = match($tool->condition) {
                                    'good' => 'bg-emerald-100 text-emerald-700',
                                    'damaged' => 'bg-red-100 text-red-700',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp

                            <span class="px-3 py-1 text-xs rounded-full {{ $conditionColor }}">
                                {{ ucfirst($tool->condition) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            {{ $tool->stock }}
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 text-center">
                            @if ($tool->stock > 0)
                                <a href="{{ route('borrower.borrowings.create', $tool->id) }}"
                                    class="px-3 py-1 text-xs rounded-md bg-blue-100 text-blue-700 hover:bg-blue-200">
                                    Borrow
                                </a>
                            @else
                                <span class="text-xs text-slate-400">
                                    Out of Stock
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6"
                            class="px-6 py-6 text-center text-slate-500">
                            No tools available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
