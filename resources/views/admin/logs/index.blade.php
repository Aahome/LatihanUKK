@extends('layouts.app')

@section('title', 'Activity Logs')

@section('dashboard-content')
<div class="flex-1 p-8">

    <!-- Top bar -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">
                Activity Logs
            </h2>
            <p class="text-sm text-slate-500">
                System and user activities
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

    <!-- Logs Table -->
    <section class="bg-white rounded-xl shadow-sm">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">
                Activity Log List
            </h3>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left w-12">No</th>
                        <th class="px-6 py-3 text-left">User</th>
                        <th class="px-6 py-3 text-left">Activity</th>
                        <th class="px-6 py-3 text-left w-40">Date</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            {{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ optional($log->user)->name ?? 'System' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                {{ ucfirst($log->activity) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-slate-500">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5"
                            class="px-6 py-6 text-center text-slate-500">
                            No activity logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>

    </section>
</div>
@endsection
