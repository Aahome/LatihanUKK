@extends('layouts.app')

@section('title', 'Borrowing Requests')

@section('dashboard-content')
    <div class="flex-1 p-8">

        <div class="flex justify-between items-center mb-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-slate-800">
                    Borrowing Requests
                </h2>
                <p class="text-sm text-slate-500">
                    Review and approve tool borrowings
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

        <section class="bg-white rounded-xl shadow-sm">

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Borrower</th>
                            <th class="px-6 py-3 text-left">Tool</th>
                            <th class="px-6 py-3 text-left">Borrow Date</th>
                            <th class="px-6 py-3 text-left">Due Date</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse ($borrowings as $borrowing)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $borrowing->user->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $borrowing->tool->tool_name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColor = match ($borrowing->status) {
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'returned' => 'bg-emerald-100 text-emerald-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <span class="px-3 py-1 text-xs rounded-full {{ $statusColor }}">
                                        {{ ucfirst($borrowing->status) }}
                                    </span>

                                    {{-- Rejection reason --}}
                                    @if ($borrowing->status === 'rejected')
                                        <div class="mt-1 text-xs text-red-600">
                                            {{ $borrowing->rejection_reason }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4 text-center">
                                    @if ($borrowing->status === 'pending')
                                        <div class="flex justify-center gap-2">

                                            {{-- Approve --}}
                                            <form action="{{ route('staff.borrowings.approve', $borrowing->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="px-3 py-1 text-xs rounded-md
                                                   bg-emerald-100 text-emerald-700
                                                   hover:bg-emerald-200">
                                                    Approve
                                                </button>
                                            </form>

                                            {{-- Reject --}}
                                            <button data-id="{{ $borrowing->id }}" onclick="openRejectModal(this)"
                                                class="px-3 py-1 text-xs rounded-md
                                               bg-red-100 text-red-700
                                               hover:bg-red-200">
                                                Reject
                                            </button>

                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-slate-500">
                                    No borrowing requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class=" fixed inset-0 bg-black/40 flex items-center justify-center z-50" hidden>

        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">
                Reject Borrowing
            </h3>

            <form method="POST" id="rejectForm">
                @csrf
                @method('PUT')

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Reason for rejection
                </label>

                <textarea name="rejection_reason" rows="3"
                    class="w-full px-4 py-2 border rounded-lg text-sm
                       focus:ring focus:ring-red-200 focus:border-red-500"></textarea>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm border rounded-lg">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(button) {
            const id = button.dataset.id;

            const form = document.getElementById('rejectForm');
            form.action = `/staff/borrowings/${id}/reject`;

            document.getElementById('rejectModal').hidden = false;
        }


        function closeRejectModal() {
            document.getElementById('rejectModal').hidden = true;
        }
    </script>
@endsection
