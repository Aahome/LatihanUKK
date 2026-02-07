@extends('layouts.app')

@section('title', 'My Borrowings')

@section('dashboard-content')
    <div class="flex-1 p-8">

        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-slate-800">
                    My Borrowings
                </h2>
                <p class="text-sm text-slate-500">
                    Track your borrowed tools and their status
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

        <!-- Borrowing Table -->
        <section class="bg-white rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="font-semibold text-slate-800">
                    Borrowing History
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left w-12">No</th>
                            <th class="px-6 py-3 text-left">Tool</th>
                            <th class="px-6 py-3 text-left">Borrow Date</th>
                            <th class="px-6 py-3 text-left">Due Date</th>
                            <th class="px-6 py-3 text-left">Fine</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-center w-48">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse ($borrowings as $borrowing)
                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $borrowing->tool->tool_name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ $borrowing->returnData?->fine ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match ($borrowing->status) {
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'returned' => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <span class="px-3 py-1 text-xs rounded-full {{ $statusColor }}">
                                        {{ ucfirst($borrowing->status) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">

                                        @if ($borrowing->status === 'pending')
                                            <a href="{{ route('borrower.borrowings.edit', $borrowing->id) }}"
                                                class="px-3 py-1 text-xs rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200">
                                                Edit
                                            </a>
                                        @endif

                                        @if ($borrowing->status === 'approved')
                                            <form action="{{ route('borrower.borrowings.return', $borrowing->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 text-xs rounded-md
                                                    bg-emerald-100 text-emerald-700 hover:bg-emerald-200">
                                                    Return
                                                </button>
                                            </form>
                                        @elseif ($borrowing->status === 'returned' && !$borrowing->returnData)
                                            <span class="text-xs text-amber-600 font-medium">
                                                Awaiting Return Confirmation
                                            </span>
                                        @elseif ($borrowing->returnData)
                                            <span class="text-xs text-slate-400">
                                                Completed
                                            </span>
                                        @endif


                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-slate-500">
                                    You have no borrowing records.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>
    </div>
@endsection
