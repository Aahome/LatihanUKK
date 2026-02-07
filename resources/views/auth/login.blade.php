@extends('layouts.app')

@section('title', 'Login')

@section('auth-content')

<!-- Main container -->
<main class="min-h-screen flex items-center justify-center bg-slate-100 text-slate-700 px-4">

    <!-- Card login -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">

        <!-- App title -->
        <h1 class="text-2xl font-bold text-slate-800 text-center">
            UKK<span class="text-blue-600">Peminjaman</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-sm text-slate-500 text-center mt-2">
            Login to continue
        </p>


        <!-- Login form -->
        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf <!-- CSRF protection -->

            <!-- Email input -->
            <div>
                <label class="text-sm text-slate-600">Email</label>
                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none"
                    required>
            </div>

            <!-- Password input -->
            <div>
                <label class="text-sm text-slate-600">Password</label>
                <input type="password"
                    name="password"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-500 outline-none"
                    required>
            </div>

            <!-- Error message (login failed) -->
            @error('email')
            <div id="login-error" class="rounded-lg bg-red-500 mt-2 p-2 items-center text-center justify-center">
                <p class="text-sm text-white text-center ">
                    {{ $message }}
                </p>
            </div>


            <script>
                setTimeout(() => {
                    const error = document.getElementById('login-error');
                    if (error) {
                        error.style.display = 'none';
                    }
                }, 3000); // 3000 ms = 3 detik
            </script>
            @enderror


            <!-- Submit button -->
            <button type="submit"
                class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Login
            </button>
        </form>

    </div>
    <!-- End card -->

</main>
<!-- End main -->

@endsection