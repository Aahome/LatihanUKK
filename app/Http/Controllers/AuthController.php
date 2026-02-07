<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to intended page (ex: /dashboard),
            // fallback to "/" if none
            return redirect()->intended('/');
        }

        // Login failed
        return back()
            ->withErrors(['email' => 'Incorrect email or password'])
            ->onlyInput('email');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
