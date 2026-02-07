@extends('layouts.app')

@section('title', 'UKKPemnijaman')

@section('main-content')

<body class="bg-slate-100 text-slate-700 min-h-screen flex flex-col">
    <main class="flex-1">
        <!-- Hero -->
        <section class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-slate-800 leading-tight">
                    Sistem Peminjaman Alat
                    <span class="text-blue-600">Mudah & Terorganisir</span>
                </h2>
                <p class="mt-4 text-slate-500">
                    Aplikasi peminjaman alat berbasis web untuk mempermudah
                    pencatatan, monitoring, dan laporan peminjaman.
                </p>
                <div class="mt-6 flex gap-4">
                    <a href="/dashboard" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                        Mulai Sekarang
                    </a>
                    <a href="#" class="px-6 py-3 border border-slate-300 rounded-lg hover:bg-slate-50">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-8">
                <h3 class="font-semibold text-slate-800 mb-4">Ringkasan Sistem</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex gap-2">
                        <span class="text-blue-600">✔</span> Manajemen data barang
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-600">✔</span> Peminjaman & pengembalian
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-600">✔</span> Status real-time
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-600">✔</span> Laporan rapi & jelas
                    </li>
                </ul>
            </div>
        </section>

        <!-- Features -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-6">
                <h3 class="text-2xl font-semibold text-center text-slate-800">
                    Fitur Unggulan
                </h3>

                <div class="grid md:grid-cols-3 gap-8 mt-12">
                    <div class="p-6 rounded-xl shadow-sm border hover:shadow-md transition">
                        <h4 class="font-semibold text-slate-800 mb-2">Data Barang</h4>
                        <p class="text-sm text-slate-500">
                            Kelola seluruh data barang dengan rapi dan terstruktur.
                        </p>
                    </div>

                    <div class="p-6 rounded-xl shadow-sm border hover:shadow-md transition">
                        <h4 class="font-semibold text-slate-800 mb-2">Peminjaman</h4>
                        <p class="text-sm text-slate-500">
                            Proses peminjaman cepat dengan status otomatis.
                        </p>
                    </div>

                    <div class="p-6 rounded-xl shadow-sm border hover:shadow-md transition">
                        <h4 class="font-semibold text-slate-800 mb-2">Laporan</h4>
                        <p class="text-sm text-slate-500">
                            Laporan peminjaman siap cetak kapan saja.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>
    @endsection