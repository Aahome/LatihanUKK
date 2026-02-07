<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | UKK Peminjaman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen flex text-slate-700">

    <!-- Sidebar -->
    <aside class="w-64 bg-white/80 backdrop-blur border-r border-slate-200">
        <div class="p-6">
            <h1 class="text-lg font-bold text-slate-800 tracking-wide">
                UKK<span class="text-blue-600">Peminjaman</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Dashboard Admin</p>
        </div>

        <nav class="px-4 space-y-1">
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-blue-600 text-white">
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-100">
                Data Barang
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-100">
                Peminjaman
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-100">
                Pengembalian
            </a>
        </nav>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">

        <!-- Top bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-slate-800">
                    Dashboard
                </h2>
                <p class="text-sm text-slate-500">
                    Ringkasan aktivitas peminjaman
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600">Admin</span>
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                    A
                </div>
            </div>
        </div>

        <!-- Stat cards -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm text-slate-500">Total Barang</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">120</h3>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm text-slate-500">Sedang Dipinjam</p>
                <h3 class="text-3xl font-bold text-amber-600 mt-2">24</h3>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm text-slate-500">Sudah Kembali</p>
                <h3 class="text-3xl font-bold text-emerald-600 mt-2">96</h3>
            </div>

        </section>

        <!-- Table card -->
        <section class="bg-white rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-semibold text-slate-800">
                    Peminjaman Terbaru
                </h3>
                <a href="#" class="text-sm text-blue-600 hover:underline">
                    Lihat semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Peminjam</th>
                            <th class="px-6 py-3 text-left">Barang</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr>
                            <td class="px-6 py-4">Aziz</td>
                            <td class="px-6 py-4">Laptop</td>
                            <td class="px-6 py-4">12 Jan 2026</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-700">
                                    Dipinjam
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Budi</td>
                            <td class="px-6 py-4">Proyektor</td>
                            <td class="px-6 py-4">10 Jan 2026</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                                    Kembali
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

    </main>

</body>
</html>
