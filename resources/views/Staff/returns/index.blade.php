@extends('layouts.app')

@section('title', 'Return Tools')

@section('dashboard-content')
    <div class="flex-1 p-8">

        <div class="flex justify-between items-center mb-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-slate-800">
                    Return Tools
                </h2>
                <p class="text-sm text-slate-500">
                    Process tool returns and calculate fines
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
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Borrower</th>
                            <th class="px-6 py-3 text-left">Tool</th>
                            <th class="px-6 py-3 text-left">Quantity</th>
                            <th class="px-6 py-3 text-left">Due Date</th>
                            <th class="px-6 py-3 text-left">Late (Days)</th>
                            <th class="px-6 py-3 text-left">Returned</th>
                            <th class="px-6 py-3 text-left">Fine</th>
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
                                    $fine = $lateDays * 5000 * $borrowing->quantity;
                                }
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
                                    {{ $borrowing->quantity }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $due->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $lateDays }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($borrowing->status === 'returned')
                                        <span class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                                            Yes
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600">
                                            No
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-red-600 font-semibold">
                                    Rp {{ number_format($fine, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($borrowing->status === 'returned' && !$borrowing->returnData)
                                        <form action="{{ route('staff.returns.store', $borrowing->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 text-xs rounded-md
                                                bg-emerald-100 text-emerald-700
                                                hover:bg-emerald-200">
                                                Confirm Return
                                            </button>
                                        </form>
                                    @elseif ($borrowing->returnData)
                                        <span class="text-xs text-slate-400">
                                            Confirmed
                                        </span>
                                    @else
                                        <button disabled
                                            class="px-3 py-1 text-xs rounded-md
                                          bg-slate-100 text-slate-400 cursor-not-allowed">
                                            Waiting
                                        </button>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-slate-500">
                                    No borrowed tools to return.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
