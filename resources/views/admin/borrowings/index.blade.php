@extends('layouts.app')

@section('title', 'Tools')

@section('dashboard-content')
    <div class="flex-1 p-8">

        <!-- Top bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-slate-800">
                    Tool Management
                </h2>
                <p class="text-sm text-slate-500">
                    Lorem Ipsum
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


        <!-- Tools And Categories Table -->

        <!-- Search & Filter -->
        <form method="GET" id="borrowSearch" action="{{ route('admin.borrowings.index') }}"
            class="py-4 flex flex-wrap gap-3 items-center">
            <input type="hidden" name="view" value="borrow">
            <!-- Search Input -->
            <input type="text" name="borrowSearch" value="{{ request('borrowSearch') }}" placeholder="Search user name..."
                class="w-full sm:w-64 px-4 py-2 border rounded-lg text-sm
                      focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

            <!-- Tool Dropdown -->
            <select name="borrowTool"
                class="w-full sm:w-48 px-4 py-2 border rounded-lg text-sm
                       focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

                @if ($tools->isEmpty())
                    <option disabled selected>
                        No Tools Yet
                    </option>
                @else
                    <option value="">All Tool</option>

                    @foreach ($tools as $tool)
                        <option value="{{ $tool->id }}" {{ request('borrowTool') == $tool->id ? 'selected' : '' }}>
                            {{ ucfirst($tool->tool_name) }}
                        </option>
                    @endforeach
                @endif

            </select>

            <!-- Search Button -->
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Search
            </button>
        </form>

        <!-- Search & Filter -->
        <form method="GET" id="returnSearch" action="{{ route('admin.borrowings.index') }}"
            class="py-4 flex flex-wrap gap-3 items-center" hidden>
            <input type="hidden" name="view" value="return">
            <!-- Search Input -->
            <input type="text" name="returnSearch" value="{{ request('returnSearch') }}" placeholder="Search user name..."
                class="w-full sm:w-64 px-4 py-2 border rounded-lg text-sm
                      focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

            <!-- Tool Dropdown -->
            <select name="returnTool"
                class="w-full sm:w-48 px-4 py-2 border rounded-lg text-sm
                       focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none">

                @if ($tools->isEmpty())
                    <option disabled selected>
                        No Tools Yet
                    </option>
                @else
                    <option value="">All Tool</option>

                    @foreach ($tools as $tool)
                        <option value="{{ $tool->id }}" {{ request('returnTool') == $tool->id ? 'selected' : '' }}>
                            {{ ucfirst($tool->tool_name) }}
                        </option>
                    @endforeach
                @endif

            </select>

            <!-- Search Button -->
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Search
            </button>
        </form>

        <div class="mb-5">
            <!-- Search Button -->
            <button type="button" id="bButton" onclick="closeReturnTable(); openBorrowTable();"
                class="px-5 py-2 transition ease-out duration-100 bg-blue-600 border-1 border-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Borrow
            </button>
            <!-- Search Button -->
            <button type="button" id="rButton" onclick="closeBorrowTable(); openReturnTable();"
                class="px-5 py-2 transition ease-out duration-100 bg-white border-1 border-blue-600 text-blue-600 rounded-lg text-sm hover:bg-blue-700 hover:text-white">
                Return
            </button>
        </div>
        @include('admin.borrowings.borrow.index')
        @include('admin.borrowings.return.index')
    </div>
    @include('admin.borrowings.borrow.create')
    @include('admin.borrowings.borrow.edit')
    {{-- @include('admin.borrowings.return.create')
@include('admin.borrowings.return.edit') --}}

    <script>
        function openBorrowTable() {
            document.getElementById('borrowTable').hidden = false;
            button = document.getElementById("bButton");
            button.classList.add('bg-blue-600', 'text-white');
            button.classList.remove('bg-white', 'hover:text-white');
            document.getElementById('borrowSearch').hidden = false;
        }

        function closeBorrowTable() {
            document.getElementById('borrowTable').hidden = true;
            button = document.getElementById("bButton");
            button.classList.add('bg-white', 'hover:text-white');
            button.classList.remove('bg-blue-600', 'text-white');
            document.getElementById('borrowSearch').hidden = true;
        }

        function openReturnTable() {
            document.getElementById('returnTable').hidden = false;
            button = document.getElementById("rButton");
            button.classList.add('bg-blue-600', 'text-white');
            button.classList.remove('bg-white', 'hover:text-white');
            document.getElementById('returnSearch').hidden = false;
        }

        function closeReturnTable() {
            document.getElementById('returnTable').hidden = true;
            button = document.getElementById("rButton");
            button.classList.add('bg-white', 'hover:text-white');
            button.classList.remove('bg-blue-600', 'text-white');
            document.getElementById('returnSearch').hidden = true;
        }
    </script>

    <script>
        function openBorrowCreateCard() {
            const form = document.getElementById('createForm');
            form.action = "{{ route('admin.borrow.store') }}";
            document.getElementById('createBorrowCard').hidden = false;
        }

        function closeBorrowCreateCard() {
            document.getElementById('createBorrowCard').hidden = true;
        }

        function openBorrowEditCard(button) {
            const id = button.dataset.id;

            const form = document.getElementById('editForm');
            form.action = `/admin/borrowings/borrow/${id}`;
            document.getElementById('editBorrowId').value = button.dataset.id;

            document.getElementById('editUserId').value = button.dataset.user_id;
            document.getElementById('editToolId').value = button.dataset.tool_id;
            document.getElementById('editQuantity').value = button.dataset.quantity;
            document.getElementById('editBorrowDate').value = button.dataset.borrow_date;
            document.getElementById('editDueDate').value = button.dataset.due_date;
            document.getElementById('editFine').value = button.dataset.fine;
            document.getElementById('editStatus').value = button.dataset.status;
            document.getElementById('editRejectionReason').value = button.dataset.rejection ?? '';

            document.getElementById('editBorrowCard').hidden = false;
        }


        function closeBorrowEditCard() {
            document.getElementById('editBorrowCard').hidden = true;
        }
    </script>

    <script>
        function openReturnCreateCard() {
            const form = document.getElementById('createForm');
            form.action = "{{ route('admin.borrow.store') }}";
            document.getElementById('createBorrowCard').hidden = false;

        }

        function closeReturnCreateCard() {
            document.getElementById('createBorrowCard').hidden = true;
        }

        function openReturnEditCard(button) {
            const id = button.dataset.id;

            document.getElementById('editForm').action = `/admin/borrow/${id}`;
            // document.getElementById('editToolId').value = id;

            // document.getElementById('editToolName').value = button.dataset.name;
            // document.getElementById('editToolCategory').value = button.dataset.category;
            // document.getElementById('editToolCondition').value = button.dataset.condition;
            // document.getElementById('editToolStock').value = button.dataset.stock;

            document.getElementById('editBorrowCard').hidden = false;
        }

        function closeReturnEditCard() {
            document.getElementById('editBorrowCard').hidden = true;
        }
    </script>

    @if (session('open_create'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('createForm');
                form.action = "{{ route('admin.borrow.store') }}";
                document.getElementById('createBorrowCard').hidden = false;
            });
        </script>
    @endif

    @if (session('open_edit') && old('borrow_id'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const id = "{{ old('borrow_id') }}";
                const form = document.getElementById('editForm');

                form.action = `/admin/borrow/${id}`;
                document.getElementById('editBorrowCard').hidden = false;
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const view = "{{ request('view', 'borrow') }}";

            if (view === 'return') {
                closeBorrowTable();
                openReturnTable();
                document.getElementById('borrowSearch').hidden = true;
                document.getElementById('returnSearch').hidden = false;
            } else {
                closeReturnTable();
                openBorrowTable();
                document.getElementById('returnSearch').hidden = true;
                document.getElementById('borrowSearch').hidden = false;
            }
        });
    </script>


@endsection
