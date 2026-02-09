<!-- Return Table -->
<div id="returnTable">

    <section class="bg-white rounded-xl shadow-sm">

        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">Return List</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Borrower</th>
                        <th class="px-6 py-3 text-left">Tool</th>
                        <th class="px-6 py-3 text-left">Return Date</th>
                        <th class="px-6 py-3 text-left">Late (Days)</th>
                        <th class="px-6 py-3 text-left">Fine</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($returns as $return)
                        @php
                            $borrowing = $return->borrowing;
                            $due = \Carbon\Carbon::parse($borrowing->due_date);
                            $returnDate = \Carbon\Carbon::parse($return->return_date);

                            $lateDays = $returnDate->greaterThan($due) ? $returnDate->diffInDays($due) : 0;
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>

                            <td class="px-6 py-4 font-medium">
                                {{ $borrowing->user->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $borrowing->tool->tool_name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $return->return_date }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $lateDays }}
                            </td>

                            <td class="px-6 py-4 text-red-600 font-semibold">
                                Rp {{ number_format($return->fine, 0, ',', '.') }}
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button type="button" 
                                        data-id="{{ $return->id }}"
                                        data-borrow_date="{{ $return->return_date }}"
                                        onclick="openReturnEditCard(this)"
                                        class="px-3 py-1 text-xs rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.return.destroy', $borrowing->id) }}" method="POST"
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
                                No return data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
