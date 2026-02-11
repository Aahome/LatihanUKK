<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Menampilkan judul halaman yang dikirim dari masing-masing view -->
    <title>@yield('title')</title>

    <!-- Memuat file CSS dan JavaScript menggunakan Vite (Laravel asset bundler) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    @auth class="bg-slate-100 min-h-screen text-slate-700 flex {{ request()->routeIs('home') ? 'flex-col' : '' }}">
    {{-- ================= USER SUDAH LOGIN ================= --}}
    @php
        // Mengambil nama role dari user yang sedang login
        $role = auth()->user()->role->role_name;
    @endphp
    @if (request()->routeIs('admin.*', 'staff.*', 'borrower.*'))
        <!-- Jika route mengarah ke dashboard berdasarkan role,
             maka tampilkan sidebar dan konten dashboard -->
        @include('partials.sidebar')
        @yield('dashboard-content')
    @elseif (request()->routeIs('home'))
        <!-- Jika route adalah halaman utama (home),
             tampilkan navbar, konten utama, dan footer -->
        @include('partials.navbar')
        @yield('main-content')
        @include('partials.footer')
    @endif @endauth
    {{-- ================= USER BELUM LOGIN (GUEST) ================= --}}
    @guest
@if (request()->routeIs('login'))
            <!-- Jika berada di halaman login, tampilkan konten autentikasi -->
            @yield('auth-content')
        @elseif (request()->routeIs('home'))
            <!-- Jika guest membuka halaman home,
                 tampilkan navbar, konten utama, dan footer -->
            @include('partials.navbar')
            @yield('main-content')
            @include('partials.footer')
        @endif @endguest
    </body>

</html>

<script>
    // Fungsi untuk menampilkan atau menyembunyikan dropdown menu profile
    function toggleProfileMenu() {
        document.getElementById('profileMenu').classList.toggle('hidden');
    }

    // Menutup dropdown profile jika user klik di luar area menu
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('profileMenu');
        if (!e.target.closest('.relative')) {
            menu.classList.add('hidden');
        }
    });
</script>
