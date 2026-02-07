@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('dashboard-content')
<main class="flex-1 p-8">

    <div class="flex justify-between items-center mb-8">
        <!-- Page Header -->
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">
                Dashboard
            </h2>
            <p class="text-sm text-slate-500">
                Borrower overview
            </p>
        </div>

        <div class="relative">
            <button onclick="toggleProfileMenu()"
                class="flex items-center gap-3 focus:outline-none">
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


    <!-- Cards -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Welcome Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">
                Welcome 👋
            </h3>

            <p class="text-slate-600 text-sm">
                Hello,
                <span class="font-semibold text-slate-800">
                    {{ auth()->user()->name }}
                </span>
            </p>

            <p class="text-sm text-slate-500 mt-2">
                You are logged in as an Borrower.
                Use the sidebar to manage available tools, and borrowings.
            </p>
        </div>

        <!-- Author Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">
                Project Author
            </h3>

            <p class="text-sm text-slate-600">
                This project was developed by:
            </p>

            <p class="mt-2 text-slate-800 font-semibold">
                Aziz
            </p>

            <p class="text-sm text-slate-500">
                Inventory & Borrowing Management System
            </p>

            <p class="text-xs text-slate-400 mt-4">
                © {{ date('Y') }} — All rights reserved
            </p>
        </div>

    </section>
</main>
@endsection