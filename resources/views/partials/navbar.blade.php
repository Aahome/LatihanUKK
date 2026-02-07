<!-- Navbar -->
<header class="bg-white/80 backdrop-blur border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-5 py-4 flex items-center justify-between">

        <!-- Logo -->
        <h1 class="text-lg font-bold text-slate-800">
            UKK<span class="text-blue-600">Peminjaman</span>
        </h1>

        <!-- Right group -->
        <div class="flex items-center gap-6">

            <!-- Nav -->
            <nav class="flex items-center gap-5 text-sm text-nowrap">
                <a href="/" class="hover:text-blue-600">Home</a>
                <a href="/dashboard" class="hover:text-blue-600">Dashboard</a>
                <a href="#" class="hover:text-blue-600">Tentang</a>
            </nav>

            {{-- ================= AUTH USER ================= --}}
            @auth
                <div class="relative">
                    <!-- Profile Button -->
                    <button onclick="toggleProfileMenu()"
                        class="flex items-center gap-3 focus:outline-none">
                        <span class="text-sm text-slate-600">
                            {{ ucfirst(auth()->user()->name) }}
                        </span>
                        <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <!-- Dropdown -->
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
            @endauth

            {{-- ================= GUEST ================= --}}
            @guest
                <div class="flex items-center">
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Login
                    </a>
                </div>
            @endguest

        </div>
    </div>
</header>
