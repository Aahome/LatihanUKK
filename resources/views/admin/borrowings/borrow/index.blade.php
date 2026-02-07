<!-- Borrowing Table -->
<div id="borrowTable">

    <section class="bg-white rounded-xl shadow-sm">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">
                Borrow List
            </h3>

            <button type="button" onclick="openBorrowCreateCard()" class="text-sm text-blue-600 hover:underline">
                Add Borrow
            </button>
        </div>

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
                        <th class="px-6 py-3 text-left">Fine</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($borrowings as $borrowing)
                        @php
                            $today = \Carbon\Carbon::today();
                            $due = \Carbon\Carbon::parse($borrowing->due_date);

                            if ($borrowing->returnData) {
                                // Return already confirmed → use stored fine
                                $returnOn = \Carbon\Carbon::parse($borrowing->returnData->return_date); // <-- convert to Carbon
                                $lateDays = $returnOn->greaterThan($due) ? $returnOn->diffInDays($due) : 0;
                                $fine = $borrowing->returnData->fine;
                            } else {
                                // Not returned yet → calculate live
                                $lateDays = $today->greaterThan($due) ? $today->diffInDays($due) : 0;
                                $fine = $lateDays * 5000;
                            }
                        @endphp

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

                            <td class="px-6 py-4 text-red-600 font-semibold">
                                Rp {{ number_format($fine, 0, ',', '.') }}
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
                                <div class="flex justify-center gap-2">
                                    <button type="button" data-id="{{ $borrowing->id }}"
                                        data-user_id="{{ $borrowing->user_id }}"
                                        data-tool_id="{{ $borrowing->tool_id }}"
                                        data-borrow_date="{{ $borrowing->borrow_date }}"
                                        data-due_date="{{ $borrowing->due_date }}"
                                        data-fine="{{ $fine }}"
                                        data-status="{{ $borrowing->status }}"
                                        data-rejection="{{ $borrowing->rejection_reason }}"
                                        onclick="openBorrowEditCard(this)"
                                        class="px-3 py-1 text-xs rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.borrow.destroy', $borrowing->id) }}" method="POST"
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
